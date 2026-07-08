<?php
include('../base_de_datos/conexion.php');

if ($_POST) {
    $token = mysqli_real_escape_string($conexionDB, $_POST["token"]);
    $p1 = intval($_POST["respuesta_p1"]);
    $p2 = intval($_POST["respuesta_p2"]);
    $p3 = intval($_POST["respuesta_p3"]);

    $consultaSolicitud = "
        SELECT solicitud_ID, RUT_ciudadano
        FROM solicitud
        WHERE token_encuesta = '$token'
        LIMIT 1
    ";

    $resultadoSolicitud = mysqli_query($conexionDB, $consultaSolicitud);
    $solicitud = mysqli_fetch_assoc($resultadoSolicitud);

    if (!$solicitud) {
        die("Error: encuesta no válida.");
    }

    $idSolicitud = $solicitud["solicitud_ID"];
    $rutCiudadano = $solicitud["RUT_ciudadano"];

    $consultaExiste = "SELECT ID_encuesta FROM encuesta WHERE solicitud_ID = $idSolicitud LIMIT 1";
    $resultadoExiste = mysqli_query($conexionDB, $consultaExiste);

    if (mysqli_num_rows($resultadoExiste) > 0) {
        header("Location: ../ventanas/Encuesta.php?token=$token");
        exit;
    }

    $insertar = "
        INSERT INTO encuesta (
            RUT_ciudadano,
            solicitud_ID,
            respuesta_p1,
            respuesta_p2,
            respuesta_p3
        ) VALUES (
            $rutCiudadano,
            $idSolicitud,
            $p1,
            $p2,
            $p3
        )
    ";

    $resultadoInsertar = mysqli_query($conexionDB, $insertar);

    if ($resultadoInsertar) {
       $actualizarSolicitud = "
       UPDATE solicitud
       SET estado_solicitud = 'Cerrada',
          token_encuesta = NULL
       WHERE solicitud_ID = $idSolicitud
       ";

        mysqli_query($conexionDB, $actualizarSolicitud);

        header("Location: ../ventanas/Encuesta.php?token=$token&gracias=1");
        exit;
    } else {
        echo "Error al guardar la encuesta: " . mysqli_error($conexionDB);
    }
}
?>