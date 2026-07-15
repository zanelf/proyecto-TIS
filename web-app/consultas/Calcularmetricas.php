<?php

// Filtros
function construirWhereMetricas($filtroTipo, $filtroDep) {
    $condiciones = ["1=1"];
    if (!empty($filtroTipo)) $condiciones[] = "s.ID_tipo_solicitud = '$filtroTipo'";
    if (!empty($filtroDep))  $condiciones[] = "s.ID_departamento = '$filtroDep'";
    return implode(" AND ", $condiciones);
}

// KPI generales
function obtenerKpis($conexionDB, $filtroTipo = '', $filtroDep = '') {
    $where = construirWhereMetricas($filtroTipo, $filtroDep);
    $sql = "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN Tipo_estado = 'Recibida' THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN Tipo_estado = 'En proceso' THEN 1 ELSE 0 END) as en_proceso,
                SUM(CASE WHEN Tipo_estado IN ('Respondida', 'Cerrada') THEN 1 ELSE 0 END) as resueltos
            FROM solicitud s WHERE $where";
    $res = mysqli_query($conexionDB, $sql);
    return $res ? mysqli_fetch_assoc($res) : ['total'=>0, 'pendientes'=>0, 'en_proceso'=>0, 'resueltos'=>0];
}

// Por tipo
function obtenerDistribucionTipos($conexionDB, $filtroTipo = '', $filtroDep = '') {
    $where = construirWhereMetricas($filtroTipo, $filtroDep);
    $sql = "SELECT ts.*, COUNT(s.solicitud_ID) as cantidad
            FROM solicitud s
            JOIN tipo_solicitud ts ON s.ID_tipo_solicitud = ts.ID_tipo_solicitud
            WHERE $where GROUP BY ts.ID_tipo_solicitud";
    $res = mysqli_query($conexionDB, $sql);
    $datos = [];
    if ($res) {
        while ($row = mysqli_fetch_array($res)) {
            $datos[] = ['nombre' => $row[1], 'cantidad' => (int)$row['cantidad']];
        }
    }
    return $datos;
}

// Por dep
function obtenerResolucionDeptos($conexionDB, $filtroTipo = '', $filtroDep = '') {
    $where = construirWhereMetricas($filtroTipo, $filtroDep);
    $sql = "SELECT d.*,
                COUNT(s.solicitud_ID) as total_dep,
                SUM(CASE WHEN s.Tipo_estado IN ('Respondida', 'Cerrada') THEN 1 ELSE 0 END) as resueltas_dep
             FROM solicitud s
             JOIN departamento d ON s.ID_departamento = d.ID_departamento
             WHERE $where GROUP BY d.ID_departamento";
    $res = mysqli_query($conexionDB, $sql);
    $datos = [];
    if ($res) {
        while ($row = mysqli_fetch_array($res)) {
            $datos[] = ['nombre' => $row[1], 'totales' => (int)$row['total_dep'], 'resueltas' => (int)$row['resueltas_dep']];
        }
    }
    return $datos;
}
?>