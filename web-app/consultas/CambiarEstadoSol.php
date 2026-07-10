<?php
    session_start();
    include('../base_de_datos/conexion.php');
    require_once('GenerarTokenEncuesta.php');

    $IDsol=$_POST['ID_cambio'];
    $estadoSiguiente=$_POST['estadoSiguiente'];

    $prioridad = $_GET['ID_prioridad'] ?? '';
$dpto = $_GET['ID_departamento'] ?? '';
$IDtipoSol = $_GET['ID_tipo_solicitud'] ?? '';

    $consulta="UPDATE solicitud SET Tipo_estado = '$estadoSiguiente' WHERE solicitud_ID='$IDsol'";
    $resultado=mysqli_query($conexionDB,$consulta);

    $consulta="SELECT tiempo FROM tiempo WHERE $IDtipoSol";

    if($estadoSiguiente=="Derivada"){
        $consulta="SELECT tiempo FROM tiempo WHERE ID_prioridad='$prioridad' and ID_departamento = '$dpto' and ID_tipo_solicitud='$IDtipoSol'";
        $resultado=mysqli_query($conexionDB,$consulta);
        $tiempoAsignado=mysqli_fetch_assoc($resultado)['tiempo'];
        $consulta2="UPDATE solicitud SET tiempo_asignado = '$tiempoAsignado' WHERE solicitud_id = '$IDsol'";
    }
    if($estadoSiguiente=="Respondida"){
          generarTokenEncuesta($conexionDB,$IDsol);
    }
    
    if(!isset($_SESSION["usuario"])){
        header("Location: ../index.php");
        exit;
    }

    if (!isset($_POST['ID_cambio']) || !isset($_POST['estadoSiguiente'])) {
        echo "Faltan parámetros para cambiar el estado.";
        exit;
    }

    $idSolicitud = mysqli_real_escape_string($conexionDB, $_POST['ID_cambio']);
    $estadoSiguiente = mysqli_real_escape_string($conexionDB, $_POST['estadoSiguiente']);
    $idUsuario = $_SESSION['ID_usuario'];

    $estadosValidos = ['En revision', 'Derivada', 'En proceso', 'Respondida', 'Anulada'];
    if (!in_array($estadoSiguiente, $estadosValidos)) {
        echo "Estado no válido.";
        exit;
    }

    if ($estadoSiguiente == 'Respondida') {
        $respuesta = isset($_POST['respuesta']) ? mysqli_real_escape_string($conexionDB, trim($_POST['respuesta'])) : '';
        if ($respuesta === '') {
            echo "Debe ingresar una respuesta antes de marcar la solicitud como respondida.";
            exit;
        }

        $consulta = "UPDATE solicitud
                     SET Tipo_estado = 'Respondida', respuesta = '$respuesta', fecha_respondida = NOW()
                     WHERE solicitud_ID = '$idSolicitud'";
        mysqli_query($conexionDB, $consulta);

        generarTokenEncuesta($conexionDB, $idSolicitud);

    } else {
        $consulta = "UPDATE solicitud SET Tipo_estado = '$estadoSiguiente' WHERE solicitud_ID = '$idSolicitud'";
        mysqli_query($conexionDB, $consulta);
    }

    // volver a la pantalla que corresponde al rol
    if ($_SESSION["tipo"] == "Funcionario") {
        header("Location: ../ventanas/solicitud.php?actualizado=ok");
    } else {
        header("Location: ../ventanas/director.php?actualizado=ok");
    }
    exit;
?>