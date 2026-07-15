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

    // si es director, forzar su departamento (no puede configurar otros)
    if ($_SESSION["tipo"] == "Director") {
        $ID_departamento = mysqli_real_escape_string($conexionDB, $_SESSION["ID_departamento"]);
    }

    // validar que la combinacion no exista ya (RF-05 D3.1)
    $existe = mysqli_query($conexionDB,
        "SELECT * FROM tiempo
         WHERE ID_prioridad = '$ID_prioridad'
           AND ID_tipo_solicitud = '$ID_tipo_solicitud'
           AND ID_departamento = '$ID_departamento'
         LIMIT 1");

    if (mysqli_num_rows($existe) > 0) {
        echo "<script>
                alert('Ya existe esa regla de prioridad para el tipo de solicitud en el departamento.');
                window.location.href = '../ventanas/tiempo.php';
              </script>";
        exit;
    }

    $consulta = "INSERT INTO tiempo (ID_prioridad, ID_tipo_solicitud, ID_departamento, tiempo)
                 VALUES ('$ID_prioridad', '$ID_tipo_solicitud', '$ID_departamento', '$tiempo')";
    mysqli_query($conexionDB, $consulta);

    header("Location: ../ventanas/tiempo.php");
    exit;
?>