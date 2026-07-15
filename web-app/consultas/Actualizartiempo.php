<?php
    session_start();
    include('../base_de_datos/conexion.php');

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../index.php");
        exit;
    }

    $ID_prioridad = mysqli_real_escape_string($conexionDB, $_POST["ID_prioridad"]);
    $ID_tipo_solicitud = mysqli_real_escape_string($conexionDB, $_POST["ID_tipo_solicitud"]);
    $ID_departamento = mysqli_real_escape_string($conexionDB, $_POST["ID_departamento"]);
    $tiempo = mysqli_real_escape_string($conexionDB, $_POST["tiempo"]);

    // Condición para el director
    if ($_SESSION["tipo"] == "Director" && $_SESSION["ID_departamento"] != $ID_departamento) {
        header("Location: ../ventanas/tiempo.php?error=noAutorizado");
        exit;
    }

    $consulta = "UPDATE tiempo SET tiempo = '$tiempo'
                 WHERE ID_prioridad = '$ID_prioridad'
                   AND ID_tipo_solicitud = '$ID_tipo_solicitud'
                   AND ID_departamento = '$ID_departamento'";
    mysqli_query($conexionDB, $consulta);

    header("Location: ../ventanas/tiempo.php");
    exit;
?>