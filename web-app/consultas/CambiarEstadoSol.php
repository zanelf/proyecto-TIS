<?php
    session_start();
    include('../base_de_datos/conexion.php');

    $IDsol=$_POST['ID_cambio'];
    $estadoSiguiente=$_POST['estadoSiguiente'];
    $prioridad= $_POST['ID_prioridad'];
    $dpto= $_POST['ID_departamento'];
    $IDtipoSol=$solicutdRevisar['ID_tipo_solicitud'];
    $consulta="UPDATE solicitud SET Tipo_estado = '$estadoSiguiente' WHERE solicitud_ID='$IDsol'";
    $resultado=mysqli_query($conexionDB,$consulta);

    $consulta="SELECT tiempo FROM tiempo WHERE $IDtipoSol";

    if($estadoSiguiente=="Derivada"){
        $consulta="SELECT tiempo FROM tiempo WHERE ID_prioridad='$prioridad' and ID_departamento = '$dpto' and ID_tipo_solicitud='$IDtipoSol'";
        $resultado=mysqli_query($conexionDB,$consulta);
        $tiempoAsignado=mysqli_fetch_assoc($resultado)['Tiempo'];
        $consulta2="UPDATE solicitud SET Tiempo_asignado = '$tiempoAsignado'";
    }
    if($estadoSiguiente=="Respondida"){
    }
    
    if(!isset($_SESSION["usuario"])){
        header("Location: ../index.php");
        exit;
    }
    echo "CAMBIADO DE REVISADO A DERIVADO"
?>