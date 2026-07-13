<?php
function registrarLogEstado($conexionDB, $idSolicitud, $estadoAnterior, $estadoNuevo, $idUsuario = null){
    $idSolicitud = mysqli_real_escape_string($conexionDB, $idSolicitud);
    $estadoAnterior = mysqli_real_escape_string($conexionDB, $estadoAnterior);
    $estadoNuevo = mysqli_real_escape_string($conexionDB, $estadoNuevo);

    $usuarioSql = ($idUsuario !== null && $idUsuario !== '') ? "'" . mysqli_real_escape_string($conexionDB, $idUsuario) . "'" : "NULL";

    $sql = "INSERT INTO log_estado (solicitud_ID, estado_anterior, estado_nuevo, ID_usuario, fecha_hora_cambio)
            VALUES ('$idSolicitud', '$estadoAnterior', '$estadoNuevo', $usuarioSql, NOW())";

    return mysqli_query($conexionDB, $sql);
}

function obtenerLogEstado($conexionDB, $idSolicitud){
    $sql = "SELECT estado_anterior, estado_nuevo, fecha_hora_cambio
            FROM log_estado
            WHERE solicitud_ID = '$idSolicitud'
            ORDER BY fecha_hora_cambio ASC";

    $resultado = mysqli_query($conexionDB, $sql);
    $logEstados = [];
    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $logEstados[] = $fila;
        }
    }
    return $logEstados;
}
?>