<?php
//misma lógica que la de la tabla.php
include_once('../base_de_datos/conexion.php');

$tipoUsuario = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : "";
$dpto = isset($_SESSION['ID_departamento']) && $_SESSION['ID_departamento'] !== "" ? $_SESSION['ID_departamento'] : null;
$gestionaSolicitudes = ($tipoUsuario == "Funcionario" || $tipoUsuario == "Director");

$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";
$buscarEsc = mysqli_real_escape_string($conexionDB, $buscar);


$columnas = [
    'Recibida'   => ['titulo' => 'Recibida',   'estados' => ['Recibida']],
    'En revision'=> ['titulo' => 'En revisión','estados' => ['En revision']],
    'Derivada'   => ['titulo' => 'Derivada',    'estados' => ['Derivada']],
    'En proceso' => ['titulo' => 'En proceso',  'estados' => ['En proceso']],
    'Respondida' => ['titulo' => 'Respondida',  'estados' => ['Respondida']],
    'Cerrada'    => ['titulo' => 'Cerrada',     'estados' => ['Cerrada', 'Anulada']],
];

$where = [];
if (($tipoUsuario == "Funcionario" || $tipoUsuario == "Director") && $dpto !== null) {
    $where[] = "s.ID_departamento = '" . mysqli_real_escape_string($conexionDB, $dpto) . "'";
}
if ($buscar !== "") {
    $where[] = "(s.Asunto LIKE '%$buscarEsc%' OR s.Descripcion LIKE '%$buscarEsc%' OR s.solicitud_ID LIKE '%$buscarEsc%')";
}
$whereSql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

$consulta = "SELECT s.solicitud_ID, s.Asunto, s.Tipo_estado, s.fecha_creacion,
                    s.fecha_vencimiento, s.tiempo_asignado,
                    c.nombre AS categoria, p.Nombre AS prioridad
             FROM solicitud s
             LEFT JOIN categoria c ON s.ID_categoria = c.ID_categoria
             LEFT JOIN prioridad p ON s.ID_prioridad = p.ID_prioridad
             $whereSql
             ORDER BY s.fecha_creacion DESC";
$resultado = mysqli_query($conexionDB, $consulta);


$solicitudesPorColumna = [];
foreach ($columnas as $clave => $col) {
    $solicitudesPorColumna[$clave] = [];
}
while ($sol = mysqli_fetch_assoc($resultado)) {
    foreach ($columnas as $clave => $col) {
        if (in_array($sol['Tipo_estado'], $col['estados'])) {
            $solicitudesPorColumna[$clave][] = $sol;
            break;
        }
    }
}


function claseBadgePrioridad($prioridad) {
    $p = strtolower(trim($prioridad));
    if ($p == 'alta' || $p == 'urgente') return 'bg-danger';
    if ($p == 'media') return 'bg-warning text-dark';
    if ($p == 'baja') return 'bg-success';
    return 'bg-secondary';
}


function estadoSLA($sol) {
    $estadosActivos = ['Derivada', 'En proceso'];
    if (empty($sol['fecha_vencimiento']) || !in_array($sol['Tipo_estado'], $estadosActivos)) {
        return '';
    }

    $ahora = new DateTime();
    $vencimiento = new DateTime($sol['fecha_vencimiento']);

    if ($ahora > $vencimiento) {
        return 'vencido';
    }

    // por si está por vencer (20% era??)
    $dias = (int)($sol['tiempo_asignado'] ?? 0);
    if ($dias > 0) {
        $totalSegundos = $dias * 24 * 3600;
        $restanteSegundos = $vencimiento->getTimestamp() - $ahora->getTimestamp();
        if ($restanteSegundos <= ($totalSegundos * 0.20)) {
            return 'por_vencer';
        }
    }
    return '';
}

function botonAccion($sol, $gestionaSolicitudes, $tipoUsuario) {
    $id = $sol['solicitud_ID'];
    $estado = $sol['Tipo_estado'];
    $html = '';

    if ($estado == "Recibida" && $gestionaSolicitudes) {
        $html .= '<a href="Revisar.php?id_enviado=' . $id . '&tipomod=solicitud" class="btn btn-sm btn-success">Revisar</a>';
    }
    if ($estado == "Recibida" && $tipoUsuario == "Director") {
        $html .= ' <a href="Anular.php?id_enviado=' . $id . '" class="btn btn-sm btn-outline-danger">Anular</a>';
    }
    if ($estado == "En revision" && $gestionaSolicitudes) {
        $html .= '<a href="Revisar.php?id_enviado=' . $id . '&tipomod=solicitud" class="btn btn-sm btn-success">Continuar revisión</a>';
    }
    if ($estado == "Derivada" && $gestionaSolicitudes) {
        $html .= '<a href="Tomarsolicitud.php?id_enviado=' . $id . '" class="btn btn-sm btn-success">Tomar</a>';
    }
    if ($estado == "En proceso" && $gestionaSolicitudes) {
        $html .= '<a href="Responder.php?id_enviado=' . $id . '" class="btn btn-sm btn-success">Responder</a>';
    }
    return $html;
}
?>

<div class="kanban-board">
    <?php foreach ($columnas as $clave => $col): ?>
        <div class="kanban-columna">
            <div class="kanban-columna-titulo">
                <span><?php echo htmlspecialchars($col['titulo']); ?></span>
                <span class="kanban-contador"><?php echo count($solicitudesPorColumna[$clave]); ?></span>
            </div>

            <?php if (count($solicitudesPorColumna[$clave]) === 0): ?>
                <p class="kanban-vacio">Sin solicitudes</p>
            <?php else: ?>
                <?php foreach ($solicitudesPorColumna[$clave] as $sol):
                    $sla = estadoSLA($sol);
                    $claseSLA = $sla === 'vencido' ? 'tarjeta-vencida' : ($sla === 'por_vencer' ? 'tarjeta-por-vencer' : '');
                ?>
                    <div class="kanban-tarjeta <?php echo $claseSLA; ?>">
                        <div class="kanban-tarjeta-meta">
                            #<?php echo htmlspecialchars($sol['solicitud_ID']); ?>
                            <?php if ($sol['categoria']): ?>
                                · <?php echo htmlspecialchars($sol['categoria']); ?>
                            <?php endif; ?>
                        </div>
                        <div class="kanban-tarjeta-asunto"><?php echo htmlspecialchars($sol['Asunto']); ?></div>

                        <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                            <?php if (!empty($sol['prioridad'])): ?>
                                <span class="badge <?php echo claseBadgePrioridad($sol['prioridad']); ?>" style="font-size:10px;">
                                    <?php echo htmlspecialchars($sol['prioridad']); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($sla === 'vencido'): ?>
                                <span class="badge bg-danger" style="font-size:10px;">Vencido</span>
                            <?php elseif ($sla === 'por_vencer'): ?>
                                <span class="badge bg-warning text-dark" style="font-size:10px;">Por vencer</span>
                            <?php endif; ?>
                        </div>

                        <div class="kanban-tarjeta-fecha">
                            <?php echo htmlspecialchars(date('d-m-Y', strtotime($sol['fecha_creacion']))); ?>
                            <?php if (!empty($sol['fecha_vencimiento']) && in_array($sol['Tipo_estado'], ['Derivada','En proceso'])): ?>
                                <br><span style="font-size:10px;">Vence: <?php echo htmlspecialchars(date('d-m-Y', strtotime($sol['fecha_vencimiento']))); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php
                            $boton = botonAccion($sol, $gestionaSolicitudes, $tipoUsuario);
                            if ($boton !== '') {
                                echo '<div class="d-flex gap-1 flex-wrap">' . $boton . '</div>';
                            }
                        ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>