<?php 
function sumarDiasHabiles($diasHabiles, $fechaInicio = null) {
    $fecha = $fechaInicio ? new DateTime($fechaInicio) : new DateTime();
    $diasSumados = 0;

    while ($diasSumados < $diasHabiles) {
        $fecha->modify('+1 day');
        $diaSemana = (int)$fecha->format('N'); 
        if ($diaSemana < 6) { // para que solo sea de lunes a viernesss
            $diasSumados++;
        }
    }

    return $fecha->format('Y-m-d H:i:s');
}


function obtenerTiempoSLA($conexionDB, $idPrioridad, $idTipoSolicitud, $idDepartamento) {
    $idPrioridad = mysqli_real_escape_string($conexionDB, $idPrioridad);
    $idTipoSolicitud = mysqli_real_escape_string($conexionDB, $idTipoSolicitud);
    $idDepartamento = mysqli_real_escape_string($conexionDB, $idDepartamento);

    $res = mysqli_query($conexionDB,
        "SELECT tiempo FROM tiempo
         WHERE ID_prioridad = '$idPrioridad'
           AND ID_tipo_solicitud = '$idTipoSolicitud'
           AND ID_departamento = '$idDepartamento'
         LIMIT 1");

    if ($res && mysqli_num_rows($res) > 0) {
        $fila = mysqli_fetch_assoc($res);
        return (int)$fila['tiempo'];
    }
    return null;
}
?>