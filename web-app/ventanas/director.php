<?php
    session_start();
    require(__DIR__ . '/../base_de_datos/conexion.php');
    require(__DIR__ . '/../consultas/CalcularMetricas.php');

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../index.php");
        exit;
    }

    if ($_SESSION["tipo"] !== "Director") {
        header("Location: Inicio.php?error=NoAutorizado");
        exit;
    }

    $dpto = $_SESSION["ID_departamento"];

    // Nombre del dep
    $depFila = mysqli_fetch_assoc(mysqli_query($conexionDB, "SELECT nombre FROM departamento WHERE ID_departamento = '$dpto' LIMIT 1"));
    $nombreDep = $depFila ? $depFila["nombre"] : "Mi Departamento";

    // Kpis del dep
    $kpis = obtenerKpis($conexionDB, '', $dpto);
    $datosTipos = obtenerDistribucionTipos($conexionDB, '', $dpto);

    // Solicitudes recibidas y que estén pendientes 
    $sqlPendientes = "SELECT s.solicitud_ID, s.Asunto, s.Tipo_estado, s.fecha_creacion,
                             c.nombre AS categoria
                      FROM solicitud s
                      LEFT JOIN categoria c ON s.ID_categoria = c.ID_categoria
                      WHERE s.ID_departamento = '$dpto' AND s.Tipo_estado = 'Recibida'
                      ORDER BY s.fecha_creacion ASC";
    $pendientes = mysqli_query($conexionDB, $sqlPendientes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Director - SGISC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/style_index.css?v=<?php echo time(); ?>">
</head>
<body class="bg-light">

    <?php include('../recursos/componentes/navbar1.php'); ?>

    <div class="container mt-5 mb-5">

        <div class="d-flex justify-content-between align-items-start flex-wrap mb-4">
            <div>
                <h1 class="fw-bold text-dark mb-1">Panel del Director</h1>
                <p class="text-muted mb-0">Departamento de <?php echo htmlspecialchars($nombreDep); ?></p>
            </div>
            <a href="metricas.php" class="btn btn-primary text-white">
                <i class="bi bi-graph-up me-2"></i>Ver panel analítico completo
            </a>
        </div>

        <!-- KPIs del departamento -->
        <div class="row g-3 mb-5">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <span class="text-muted small fw-bold">Total Solicitudes</span>
                    <h3 class="fw-bold text-dark m-0 mt-1"><?php echo $kpis['total'] ?? 0; ?></h3>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <span class="text-muted small fw-bold text-warning">Por Revisar</span>
                    <h3 class="fw-bold text-warning m-0 mt-1"><?php echo $kpis['pendientes'] ?? 0; ?></h3>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <span class="text-muted small fw-bold text-primary">En Proceso</span>
                    <h3 class="fw-bold text-primary m-0 mt-1"><?php echo $kpis['en_proceso'] ?? 0; ?></h3>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <span class="text-muted small fw-bold text-success">Resueltas</span>
                    <h3 class="fw-bold text-success m-0 mt-1"><?php echo $kpis['resueltos'] ?? 0; ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Distribución por tipo -->
            <div class="col-12 col-md-5">
                <div class="card shadow-sm border-0 p-4 bg-white h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Distribución por Tipo</h5>
                    <div class="d-flex flex-column gap-3">
                        <?php if (count($datosTipos) === 0): ?>
                            <p class="text-muted small text-center">No hay datos.</p>
                        <?php else:
                            $totalTipos = $kpis['total'] > 0 ? $kpis['total'] : 1;
                            foreach ($datosTipos as $item):
                                $porcentaje = round(($item['cantidad'] / $totalTipos) * 100);
                        ?>
                            <div>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-semibold text-secondary"><?php echo htmlspecialchars($item['nombre']); ?></span>
                                    <span class="text-muted fw-bold"><?php echo $item['cantidad']; ?> (<?php echo $porcentaje; ?>%)</span>
                                </div>
                                <div class="progress" style="height: 10px; background-color: #e2e8f0;">
                                    <div class="progress-bar bg-primary" style="width: <?php echo $porcentaje; ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-md-7">
                <div class="card shadow-sm border-0 p-4 bg-white h-100" style="border-radius: 12px;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-inbox-fill text-warning me-2"></i>Solicitudes por Revisar</h5>
                        <a href="solicitud.php" class="btn btn-sm btn-outline-primary">Ver todas</a>
                    </div>

                    <?php if (mysqli_num_rows($pendientes) === 0): ?>
                        <p class="text-muted small text-center py-3">No hay solicitudes pendientes de revisión.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:10%;">ID</th>
                                        <th style="width:45%;">Asunto</th>
                                        <th style="width:25%;">Categoría</th>
                                        <th style="width:20%;" class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($sol = mysqli_fetch_assoc($pendientes)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($sol['solicitud_ID']); ?></td>
                                            <td><?php echo htmlspecialchars($sol['Asunto']); ?></td>
                                            <td><?php echo htmlspecialchars($sol['categoria'] ?? '—'); ?></td>
                                            <td class="text-center">
                                                <a href="Revisar.php?id_enviado=<?php echo $sol['solicitud_ID']; ?>&tipomod=solicitud"
                                                   class="btn btn-sm btn-success">Revisar</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <?php include('../recursos/componentes/footer.php'); ?>
</body>
</html>