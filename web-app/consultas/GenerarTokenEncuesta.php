<?php

require_once __DIR__ . '/EnviarCorreoEncuesta.php';

function generarTokenEncuesta($conexionDB, $idSolicitud) {
    $idSolicitud = intval($idSolicitud);

    $consulta = "
        SELECT solicitud.solicitud_ID, solicitud.Asunto, solicitud.token_encuesta,
               ciudadano.correo_electronico
        FROM solicitud
        INNER JOIN ciudadano ON solicitud.correo_electronico = ciudadano.correo_electronico
        WHERE solicitud.solicitud_ID = $idSolicitud
        LIMIT 1
    ";

    $resultado = mysqli_query($conexionDB, $consulta);
    $solicitud = mysqli_fetch_assoc($resultado);

    if (!$resultado) {
    echo "Error SQL: " . mysqli_error($conexionDB);
    return false;
}

if (!$solicitud) {
    echo "No se encontró la solicitud o no tiene ciudadano asociado.";
    return false;
}

    if ($solicitud["token_encuesta"] != null && $solicitud["token_encuesta"] != "") {
        $token = $solicitud["token_encuesta"];
    } else {
        $token = bin2hex(random_bytes(32));

        $actualizar = "
            UPDATE solicitud
            SET token_encuesta = '$token',
                Tipo_estado = 'Respondida',
                fecha_respondida = NOW()
            WHERE solicitud_ID = $idSolicitud
        ";

       if (!mysqli_query($conexionDB, $actualizar)) {
    echo "Error al actualizar solicitud: " . mysqli_error($conexionDB);
    return false;
};
    }

    return enviarCorreoEncuesta(
        $solicitud["correo_electronico"],
        $solicitud["Asunto"],
        $token
    );
}
?>