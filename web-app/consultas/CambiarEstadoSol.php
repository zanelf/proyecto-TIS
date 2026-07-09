<?php
    session_start();
    include('../base_de_datos/conexion.php');

    $IDsol=$_POST['ID_cambio'];
    $estadoSiguiente=$_POST['estadoSiguiente'];

    echo $IDsol;
    echo $estadoSiguiente;

    $consulta="UPDATE solicitud SET Tipo_estado = '$estadoSiguiente' WHERE solicitud_ID='$IDsol'";
    $resultado=mysqli_query($conexionDB,$consulta);
    $consulta="SELECT tiempo FROM tiempo WHERE "

    if($estadoSiguiente=="Derivada"){
        $consulta="SELECT tiempo FROM tiempo WHERE "
    }
    
    if(!isset($_SESSION["usuario"])){
        header("Location: ../index.php");
        exit;
    }
    echo "CAMBIADO DE REVISADO A DERIVADO"
?>