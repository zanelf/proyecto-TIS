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
                    c.nombre AS categoria
             FROM solicitud s
             LEFT JOIN categoria c ON s.ID_categoria = c.ID_categoria
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
                <?php foreach ($solicitudesPorColumna[$clave] as $sol): ?>
                    <div class="kanban-tarjeta">
                        <div class="kanban-tarjeta-meta">
                            #<?php echo htmlspecialchars($sol['solicitud_ID']); ?>
                            <?php if ($sol['categoria']): ?>
                                · <?php echo htmlspecialchars($sol['categoria']); ?>
                            <?php endif; ?>
                        </div>
                        <div class="kanban-tarjeta-asunto"><?php echo htmlspecialchars($sol['Asunto']); ?></div>
                        <div class="kanban-tarjeta-fecha">
                            <?php echo htmlspecialchars(date('d-m-Y', strtotime($sol['fecha_creacion']))); ?>
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