<?php
require(__DIR__ . '/../base_de_datos/conexion.php');
require(__DIR__ . '/../consultas/CalcularMetricas.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$rolUsuario = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : '';
if ($rolUsuario != 'Administrador' && $rolUsuario != 'Director') {
    header("Location: ../index.php");
    exit;
}


$esDirector = ($rolUsuario == 'Director');
$dptoDirector = $esDirector ? $_SESSION['ID_departamento'] : '';

$filter_tipo   = isset($_GET['id_tipo_solicitud']) ? $_GET['id_tipo_solicitud'] : '';
$filter_dep    = $esDirector ? $dptoDirector : (isset($_GET['id_departamento']) ? $_GET['id_departamento'] : '');
$fecha_inicio  = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin     = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

$kpis           = obtenerKpis($conexionDB, $filter_tipo, $filter_dep, $fecha_inicio, $fecha_fin);
$datosTipos     = obtenerDistribucionTipos($conexionDB, $filter_tipo, $filter_dep, $fecha_inicio, $fecha_fin);
$datosDeps      = obtenerResolucionDeptos($conexionDB, $filter_tipo, $filter_dep, $fecha_inicio, $fecha_fin);
$tiempoProm     = obtenerTiempoPromedioResolucion($conexionDB, $filter_tipo, $filter_dep, $fecha_inicio, $fecha_fin);
$tasaSLA        = obtenerTasaCumplimientoSLA($conexionDB, $filter_tipo, $filter_dep, $fecha_inicio, $fecha_fin);
$vencidas       = obtenerSolicitudesVencidas($conexionDB, $filter_tipo, $filter_dep, $fecha_inicio, $fecha_fin);
$tendencia      = obtenerTendenciaHistorica($conexionDB, $filter_tipo, $filter_dep, $fecha_inicio, $fecha_fin);


if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'kpis'      => $kpis,
        'tipos'     => $datosTipos,
        'deps'      => $datosDeps,
        'tiempoProm'=> $tiempoProm,
        'tasaSLA'   => $tasaSLA,
        'vencidas'  => $vencidas,
        'tendencia' => $tendencia
    ]);
    exit;
}

$list_tipos = mysqli_query($conexionDB, "SELECT * FROM tipo_solicitud");
$list_deps  = mysqli_query($conexionDB, "SELECT * FROM departamento");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGISC - Panel Analítico y SLA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/style_index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../recursos/css/style_metricas.css?v=<?php echo time(); ?>">
</head>

<body class="bg-light">

    <?php include("../recursos/componentes/navbar1.php"); ?>

    <div class="container-fluid px-4 mt-5 mb-5">

        <h1 class="titulo-seccion mb-1 fw-bold text-dark">Panel Analítico y Desempeño (SLA)</h1>
        <?php if ($esDirector):
            $nombreDepDir = mysqli_fetch_assoc(mysqli_query($conexionDB, "SELECT nombre FROM departamento WHERE ID_departamento = '$dptoDirector' LIMIT 1"));
        ?>
            <p class="text-muted mb-4">Departamento de <?php echo htmlspecialchars($nombreDepDir['nombre'] ?? ''); ?></p>
        <?php else: ?>
            <div class="mb-4"></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 p-3 mb-4 bg-white" style="border-radius: 12px;">
            <form id="filtrosForm" class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary small">Fecha Inicio</label>
                    <input type="date" class="form-control" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary small">Fecha Fin</label>
                    <input type="date" class="form-control" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">
                </div>
                <?php if (!$esDirector): ?>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary small">Departamento</label>
                    <select class="form-select" name="id_departamento">
                        <option value="">Todos</option>
                        <?php while($d = mysqli_fetch_array($list_deps)): ?>
                            <option value="<?php echo $d[0]; ?>" <?php echo ($filter_dep == $d[0]) ? 'selected' : ''; ?>><?php echo htmlspecialchars($d[1]); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary small">Tipo Solicitud</label>
                    <select class="form-select" name="id_tipo_solicitud">
                        <option value="">Todas</option>
                        <?php while($t = mysqli_fetch_array($list_tipos)): ?>
                            <option value="<?php echo $t[0]; ?>" <?php echo ($filter_tipo == $t[0]) ? 'selected' : ''; ?>><?php echo htmlspecialchars($t[1]); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary text-white fw-medium px-4">Aplicar Filtros</button>
                </div>
            </form>
        </div>

        
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3 bg-white h-100" style="border-radius: 10px;">
                    <span class="text-muted small fw-bold">Total</span>
                    <h3 class="fw-bold text-dark m-0 mt-1" id="kpi-total"><?php echo $kpis['total'] ?? 0; ?></h3>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3 bg-white h-100" style="border-radius: 10px;">
                    <span class="text-muted small fw-bold text-warning">Recibidas</span>
                    <h3 class="fw-bold text-warning m-0 mt-1" id="kpi-recibidas"><?php echo $kpis['pendientes'] ?? 0; ?></h3>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3 bg-white h-100" style="border-radius: 10px;">
                    <span class="text-muted small fw-bold text-secondary">En Revisión</span>
                    <h3 class="fw-bold text-secondary m-0 mt-1" id="kpi-revision"><?php echo $kpis['en_revision'] ?? 0; ?></h3>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3 bg-white h-100" style="border-radius: 10px;">
                    <span class="text-muted small fw-bold text-primary">En Proceso</span>
                    <h3 class="fw-bold text-primary m-0 mt-1" id="kpi-proceso"><?php echo $kpis['en_proceso'] ?? 0; ?></h3>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3 bg-white h-100" style="border-radius: 10px;">
                    <span class="text-muted small fw-bold text-success">Resueltas</span>
                    <h3 class="fw-bold text-success m-0 mt-1" id="kpi-resueltas"><?php echo $kpis['resueltos'] ?? 0; ?></h3>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3 bg-white h-100" style="border-radius: 10px;">
                    <span class="text-muted small fw-bold">Tiempo Prom.</span>
                    <h3 class="fw-bold text-dark m-0 mt-1"><span id="kpi-tiempo"><?php echo $tiempoProm; ?></span> <small class="text-muted" style="font-size:0.9rem;">días</small></h3>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3 bg-white h-100" style="border-radius: 10px;">
                    <span class="text-muted small fw-bold text-info">Cumpl. SLA</span>
                    <h3 class="fw-bold text-info m-0 mt-1"><span id="kpi-sla"><?php echo $tasaSLA; ?></span>%</h3>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3 bg-white h-100" style="border-radius: 10px;">
                    <span class="text-muted small fw-bold text-danger">Vencidas</span>
                    <h3 class="fw-bold text-danger m-0 mt-1" id="kpi-vencidas"><?php echo $vencidas; ?></h3>
                </div>
            </div>
        </div>

     
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-5">
                <div class="card shadow-sm border-0 p-4 bg-white h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Distribución por Tipo</h5>
                    <div style="position:relative; height:280px;"><canvas id="chartTipos"></canvas></div>
                </div>
            </div>
            <div class="col-12 col-lg-7">
                <div class="card shadow-sm border-0 p-4 bg-white h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-bar-chart-line-fill text-success me-2"></i>Solicitudes por Departamento</h5>
                    <div style="position:relative; height:280px;"><canvas id="chartDeptos"></canvas></div>
                </div>
            </div>
        </div>

    
        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <div class="card shadow-sm border-0 p-4 bg-white h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-clipboard-check text-info me-2"></i>Solicitudes por Estado</h5>
                    <div style="position:relative; height:280px;"><canvas id="chartEstados"></canvas></div>
                </div>
            </div>
            <div class="col-12 col-lg-7">
                <div class="card shadow-sm border-0 p-4 bg-white h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-graph-up text-warning me-2"></i>Tendencia Histórica</h5>
                    <div style="position:relative; height:280px;"><canvas id="chartTendencia"></canvas></div>
                </div>
            </div>
        </div>

    </div>

    <?php include('../recursos/componentes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        
        let datosTipos = <?php echo json_encode($datosTipos); ?>;
        let datosDeps  = <?php echo json_encode($datosDeps); ?>;
        let kpis       = <?php echo json_encode($kpis); ?>;
        let tendencia  = <?php echo json_encode($tendencia); ?>;

        const paleta = ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997'];

        // Distribución por tipo en torta
        const chartTipos = new Chart(document.getElementById('chartTipos'), {
            type: 'doughnut',
            data: {
                labels: datosTipos.map(t => t.nombre),
                datasets: [{ data: datosTipos.map(t => t.cantidad), backgroundColor: paleta }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        // Solicitudes por departamento 
        const chartDeptos = new Chart(document.getElementById('chartDeptos'), {
            type: 'bar',
            data: {
                labels: datosDeps.map(d => d.nombre),
                datasets: [
                    { label: 'Total', data: datosDeps.map(d => d.totales), backgroundColor: '#0d6efd' },
                    { label: 'Resueltas', data: datosDeps.map(d => d.resueltas), backgroundColor: '#198754' }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        // En proceso vs cerradas 
        const chartEstados = new Chart(document.getElementById('chartEstados'), {
            type: 'bar',
            data: {
                labels: ['Recibida', 'En revisión', 'Derivada', 'En proceso', 'Respondida', 'Cerrada'],
                datasets: [{
                    data: [kpis.pendientes, kpis.en_revision, kpis.derivadas, kpis.en_proceso, kpis.respondidas, kpis.cerradas],
                    backgroundColor: ['#ffc107', '#6c757d', '#0dcaf0', '#0d6efd', '#198754', '#343a40']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        // Tendencia 
        const chartTendencia = new Chart(document.getElementById('chartTendencia'), {
            type: 'line',
            data: {
                labels: tendencia.map(t => t.mes),
                datasets: [{
                    label: 'Solicitudes',
                    data: tendencia.map(t => t.cantidad),
                    borderColor: '#fd7e14',
                    backgroundColor: 'rgba(253,126,20,0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        // Ajax para no tener que recargar pag
        document.getElementById('filtrosForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const params = new URLSearchParams(new FormData(this));
            params.append('ajax', '1');

            fetch('metricas.php?' + params.toString())
                .then(r => r.json())
                .then(data => {
                    // KPIs
                    document.getElementById('kpi-total').textContent = data.kpis.total;
                    document.getElementById('kpi-recibidas').textContent = data.kpis.pendientes;
                    document.getElementById('kpi-revision').textContent = data.kpis.en_revision;
                    document.getElementById('kpi-proceso').textContent = data.kpis.en_proceso;
                    document.getElementById('kpi-resueltas').textContent = data.kpis.resueltos;
                    document.getElementById('kpi-tiempo').textContent = data.tiempoProm;
                    document.getElementById('kpi-sla').textContent = data.tasaSLA;
                    document.getElementById('kpi-vencidas').textContent = data.vencidas;

                    // Gráficos
                    chartTipos.data.labels = data.tipos.map(t => t.nombre);
                    chartTipos.data.datasets[0].data = data.tipos.map(t => t.cantidad);
                    chartTipos.update();

                    chartDeptos.data.labels = data.deps.map(d => d.nombre);
                    chartDeptos.data.datasets[0].data = data.deps.map(d => d.totales);
                    chartDeptos.data.datasets[1].data = data.deps.map(d => d.resueltas);
                    chartDeptos.update();

                    chartEstados.data.datasets[0].data = [data.kpis.pendientes, data.kpis.en_revision, data.kpis.derivadas, data.kpis.en_proceso, data.kpis.respondidas, data.kpis.cerradas];
                    chartEstados.update();

                    chartTendencia.data.labels = data.tendencia.map(t => t.mes);
                    chartTendencia.data.datasets[0].data = data.tendencia.map(t => t.cantidad);
                    chartTendencia.update();
                })
                .catch(err => console.error('Error al filtrar:', err));
        });
    </script>
</body>
</html>