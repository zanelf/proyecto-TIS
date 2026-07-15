<?php

// Filtros: tipo, departamento y rango de fechas (por fecha_creacion)
function construirWhereMetricas($filtroTipo, $filtroDep, $fechaInicio = '', $fechaFin = '') {
    $condiciones = ["1=1"];
    if (!empty($filtroTipo)) $condiciones[] = "s.ID_tipo_solicitud = '$filtroTipo'";
    if (!empty($filtroDep))  $condiciones[] = "s.ID_departamento = '$filtroDep'";
    if (!empty($fechaInicio)) $condiciones[] = "s.fecha_creacion >= '$fechaInicio'";
    if (!empty($fechaFin))    $condiciones[] = "s.fecha_creacion <= '$fechaFin'";
    return implode(" AND ", $condiciones);
}

// KPI generales
function obtenerKpis($conexionDB, $filtroTipo = '', $filtroDep = '', $fechaInicio = '', $fechaFin = '') {
    $where = construirWhereMetricas($filtroTipo, $filtroDep, $fechaInicio, $fechaFin);
    $sql = "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN Tipo_estado = 'Recibida' THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN Tipo_estado = 'En revision' THEN 1 ELSE 0 END) as en_revision,
                SUM(CASE WHEN Tipo_estado = 'Derivada' THEN 1 ELSE 0 END) as derivadas,
                SUM(CASE WHEN Tipo_estado = 'En proceso' THEN 1 ELSE 0 END) as en_proceso,
                SUM(CASE WHEN Tipo_estado = 'Respondida' THEN 1 ELSE 0 END) as respondidas,
                SUM(CASE WHEN Tipo_estado = 'Cerrada' THEN 1 ELSE 0 END) as cerradas,
                SUM(CASE WHEN Tipo_estado IN ('Respondida', 'Cerrada') THEN 1 ELSE 0 END) as resueltos
            FROM solicitud s WHERE $where";
    $res = mysqli_query($conexionDB, $sql);
    return $res ? mysqli_fetch_assoc($res) : ['total'=>0, 'pendientes'=>0, 'en_revision'=>0, 'derivadas'=>0, 'en_proceso'=>0, 'respondidas'=>0, 'cerradas'=>0, 'resueltos'=>0];
}

// Por tipo
function obtenerDistribucionTipos($conexionDB, $filtroTipo = '', $filtroDep = '', $fechaInicio = '', $fechaFin = '') {
    $where = construirWhereMetricas($filtroTipo, $filtroDep, $fechaInicio, $fechaFin);
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
function obtenerResolucionDeptos($conexionDB, $filtroTipo = '', $filtroDep = '', $fechaInicio = '', $fechaFin = '') {
    $where = construirWhereMetricas($filtroTipo, $filtroDep, $fechaInicio, $fechaFin);
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

// Tiempo promedio de resolucion en dias (desde creacion hasta respondida/cerrada)
function obtenerTiempoPromedioResolucion($conexionDB, $filtroTipo = '', $filtroDep = '', $fechaInicio = '', $fechaFin = '') {
    $where = construirWhereMetricas($filtroTipo, $filtroDep, $fechaInicio, $fechaFin);
    $sql = "SELECT AVG(DATEDIFF(s.fecha_respondida, s.fecha_creacion)) as promedio
            FROM solicitud s
            WHERE $where AND s.fecha_respondida IS NOT NULL";
    $res = mysqli_query($conexionDB, $sql);
    if ($res && $fila = mysqli_fetch_assoc($res)) {
        return $fila['promedio'] !== null ? round((float)$fila['promedio'], 1) : 0;
    }
    return 0;
}

// Tasa de cumplimiento de SLA: % de solicitudes respondidas dentro del plazo
function obtenerTasaCumplimientoSLA($conexionDB, $filtroTipo = '', $filtroDep = '', $fechaInicio = '', $fechaFin = '') {
    $where = construirWhereMetricas($filtroTipo, $filtroDep, $fechaInicio, $fechaFin);
    $sql = "SELECT
                COUNT(*) as total_con_sla,
                SUM(CASE WHEN s.fecha_respondida <= s.fecha_vencimiento THEN 1 ELSE 0 END) as cumplidas
            FROM solicitud s
            WHERE $where
              AND s.fecha_vencimiento IS NOT NULL
              AND s.fecha_respondida IS NOT NULL";
    $res = mysqli_query($conexionDB, $sql);
    if ($res && $fila = mysqli_fetch_assoc($res)) {
        $total = (int)$fila['total_con_sla'];
        $cumplidas = (int)$fila['cumplidas'];
        return $total > 0 ? round(($cumplidas / $total) * 100) : 0;
    }
    return 0;
}

// Solicitudes vencidas: activas cuyo plazo ya paso
function obtenerSolicitudesVencidas($conexionDB, $filtroTipo = '', $filtroDep = '', $fechaInicio = '', $fechaFin = '') {
    $where = construirWhereMetricas($filtroTipo, $filtroDep, $fechaInicio, $fechaFin);
    $sql = "SELECT COUNT(*) as vencidas
            FROM solicitud s
            WHERE $where
              AND s.fecha_vencimiento IS NOT NULL
              AND s.fecha_vencimiento < NOW()
              AND s.Tipo_estado IN ('Derivada', 'En proceso')";
    $res = mysqli_query($conexionDB, $sql);
    if ($res && $fila = mysqli_fetch_assoc($res)) {
        return (int)$fila['vencidas'];
    }
    return 0;
}

// Tendencia historica: cantidad de solicitudes creadas por mes
function obtenerTendenciaHistorica($conexionDB, $filtroTipo = '', $filtroDep = '', $fechaInicio = '', $fechaFin = '') {
    $where = construirWhereMetricas($filtroTipo, $filtroDep, $fechaInicio, $fechaFin);
    $sql = "SELECT DATE_FORMAT(s.fecha_creacion, '%Y-%m') as mes, COUNT(*) as cantidad
            FROM solicitud s
            WHERE $where
            GROUP BY DATE_FORMAT(s.fecha_creacion, '%Y-%m')
            ORDER BY mes ASC";
    $res = mysqli_query($conexionDB, $sql);
    $datos = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $datos[] = ['mes' => $row['mes'], 'cantidad' => (int)$row['cantidad']];
        }
    }
    return $datos;
}
?>