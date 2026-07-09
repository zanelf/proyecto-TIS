<?php
require(__DIR__ . '/../base_de_datos/conexion.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$filter_tipo = isset($_GET['id_tipo_solicitud']) ? $_GET['id_tipo_solicitud'] : '';
$filter_dep = isset($_GET['id_departamento']) ? $_GET['id_departamento'] : '';


$where_clauses = ["1=1"];
if (!empty($filter_tipo)) $where_clauses[] = "s.ID_tipo_solicitud = '$filter_tipo'";
if (!empty($filter_dep)) $where_clauses[] = "s.ID_departamento = '$filter_dep'";
$where_str = implode(" AND ", $where_clauses);

$sql_kpi = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN Tipo_estado = 'Pendiente' THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN Tipo_estado = 'En Proceso' THEN 1 ELSE 0 END) as en_proceso,
                SUM(CASE WHEN Tipo_estado = 'Resuelto' THEN 1 ELSE 0 END) as resueltos
            FROM solicitud s WHERE $where_str";
$res_kpi = mysqli_query($conexionDB, $sql_kpi);
$kpis = $res_kpi ? mysqli_fetch_assoc($res_kpi) : ['total'=>0, 'pendientes'=>0, 'en_proceso'=>0, 'resueltos'=>0];


$sql_tipos = "SELECT ts.*, COUNT(s.solicitud_ID) as cantidad 
              FROM solicitud s 
              JOIN tipo_solicitud ts ON s.ID_tipo_solicitud = ts.ID_tipo_solicitud 
              WHERE $where_str GROUP BY ts.ID_tipo_solicitud";
$res_tipos = mysqli_query($conexionDB, $sql_tipos);
$datosTipos = [];
if ($res_tipos) {
    while ($row = mysqli_fetch_array($res_tipos)) {
        $datosTipos[] = ['nombre' => $row[1], 'cantidad' => (int)$row['cantidad']];
    }
}


$sql_deps = "SELECT d.*, 
                COUNT(s.solicitud_ID) as total_dep,
                SUM(CASE WHEN s.Tipo_estado = 'Resuelto' THEN 1 ELSE 0 END) as resueltas_dep
             FROM solicitud s
             JOIN departamento d ON s.ID_departamento = d.ID_departamento
             WHERE $where_str GROUP BY d.ID_departamento";
$res_deps = mysqli_query($conexionDB, $sql_deps);
$datosDeps = [];
if ($res_deps) {
    while ($row = mysqli_fetch_array($res_deps)) {
        $datosDeps[] = ['nombre' => $row[1], 'totales' => (int)$row['total_dep'], 'resueltas' => (int)$row['resueltas_dep']];
    }
}


if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    ob_clean(); 
    header('Content-Type: application/json');
    echo json_encode([
        'kpis' => $kpis,
        'tipos' => $datosTipos,
        'deps' => $datosDeps
    ]);
    exit; 
}


$list_tipos = mysqli_query($conexionDB, "SELECT * FROM tipo_solicitud");
$list_deps = mysqli_query($conexionDB, "SELECT * FROM departamento");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SGISC - Panel Analítico y SLA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/style_index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../recursos/css/style_metricas.css?v=<?php echo time(); ?>">
</head>

<body class="bg-light">

    <?php include("../recursos/componentes/navbar1.php"); ?>

    <div class="contenedor-metricas container">
        <h1 class="titulo-seccion mb-4 fw-bold text-dark">Panel Analítico y Desempeño (SLA)</h1>

        <form id="form-filtros" class="card p-4 border-0 shadow-sm mb-5 bg-white" style="border-radius: 12px;">
            <div class="row g-3">
                <div class="col-12 col-md-3 d-flex flex-column">
                    <label class="form-label fw-semibold text-secondary small">Fecha Inicio</label>
                    <input type="date" class="form-control" name="fecha_inicio">
                </div>
                <div class="col-12 col-md-3 d-flex flex-column">
                    <label class="form-label fw-semibold text-secondary small">Fecha Fin</label>
                    <input type="date" class="form-control" name="fecha_fin">
                </div>
                <div class="col-12 col-md-3 d-flex flex-column">
                    <label class="form-label fw-semibold text-secondary small">Departamento</label>
                    <select class="form-select" name="id_departamento">
                        <option value="">Todos</option>
                        <?php while($d = mysqli_fetch_array($list_deps)): ?>
                            <option value="<?php echo $d[0]; ?>"><?php echo htmlspecialchars($d[1]); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex flex-column">
                    <label class="form-label fw-semibold text-secondary small">Tipo Solicitud</label>
                    <select class="form-select" name="id_tipo_solicitud">
                        <option value="">Todas</option>
                        <?php while($t = mysqli_fetch_array($list_tipos)): ?>
                            <option value="<?php echo $t[0]; ?>"><?php echo htmlspecialchars($t[1]); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary px-4 fw-bold text-white">Aplicar Filtros</button>
                </div>
            </div>
        </form>

        <div class="row g-3 mb-5">
            <div class="col-6 col-lg-3"><div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;"><span class="text-muted small fw-bold">Total Solicitudes</span><h3 class="fw-bold text-dark m-0 mt-1" id="kpi-total">0</h3></div></div>
            <div class="col-6 col-lg-3"><div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;"><span class="text-muted small fw-bold text-warning">Pendientes</span><h3 class="fw-bold text-warning m-0 mt-1" id="kpi-pendientes">0</h3></div></div>
            <div class="col-6 col-lg-3"><div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;"><span class="text-muted small fw-bold text-primary">En Proceso</span><h3 class="fw-bold text-primary m-0 mt-1" id="kpi-proceso">0</h3></div></div>
            <div class="col-6 col-lg-3"><div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;"><span class="text-muted small fw-bold text-success">Resueltas (SLA)</span><h3 class="fw-bold text-success m-0 mt-1" id="kpi-resueltos">0</h3></div></div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 p-4 bg-white" style="border-radius: 12px;">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Distribución por Tipo de Solicitud</h5>
                    <div id="contenedor-tipos" class="d-flex flex-column gap-3"></div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 p-4 bg-white" style="border-radius: 12px;">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-bar-chart-line-fill text-success me-2"></i>Resolución por Departamento</h5>
                    <div id="contenedor-deps" class="d-flex flex-column gap-3"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('form-filtros');
           
            function renderizarDashboard(datos) {
                document.getElementById('kpi-total').textContent = datos.kpis.total || 0;
                document.getElementById('kpi-pendientes').textContent = datos.kpis.pendientes || 0;
                document.getElementById('kpi-proceso').textContent = datos.kpis.en_proceso || 0;
                document.getElementById('kpi-resueltos').textContent = datos.kpis.resueltos || 0;

                const divisorGeneral = parseInt(datos.kpis.total) || 1;

                
                let htmlTipos = "";
                if(datos.tipos.length === 0) {
                    htmlTipos = '<p class="text-muted small text-center">No hay datos.</p>';
                } else {
                    datos.tipos.forEach(item => {
                        const porcentaje = Math.round((item.cantidad / divisorGeneral) * 100);
                        htmlTipos += `
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-semibold text-secondary">${item.nombre}</span>
                                <span class="text-muted fw-bold">${item.cantidad} (${porcentaje}%)</span>
                            </div>
                            <div class="progress" style="height: 10px; background-color: #e2e8f0;">
                                <div class="progress-bar bg-primary" style="width: ${porcentaje}%"></div>
                            </div>
                        </div>`;
                    });
                }
                document.getElementById('contenedor-tipos').innerHTML = htmlTipos;

                
                let htmlDeps = "";
                if(datos.deps.length === 0) {
                    htmlDeps = '<p class="text-muted small text-center">No hay datos.</p>';
                } else {
                    datos.deps.forEach(item => {
                        const divDep = item.totales > 0 ? item.totales : 1;
                        const porcentaje = Math.round((item.resueltas / divDep) * 100);
                        htmlDeps += `
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-semibold text-secondary">${item.nombre}</span>
                                <span class="text-muted small">${item.resueltas} de ${item.totales} resueltas (${porcentaje}%)</span>
                            </div>
                            <div class="progress" style="height: 10px; background-color: #e2e8f0;">
                                <div class="progress-bar bg-success" style="width: ${porcentaje}%"></div>
                            </div>
                        </div>`;
                    });
                }
                document.getElementById('contenedor-deps').innerHTML = htmlDeps;
            }

            
            const datosIniciales = {
                kpis: <?php echo json_encode($kpis); ?>,
                tipos: <?php echo json_encode($datosTipos); ?>,
                deps: <?php echo json_encode($datosDeps); ?>
            };
            renderizarDashboard(datosIniciales);

            
            form.addEventListener('submit', function(e) {
                e.preventDefault(); 

               
                const formData = new FormData(form);
               
                const url = new URL(window.location.origin + window.location.pathname);
                url.searchParams.set('ajax', '1'); 
                
                for (let [key, value] of formData.entries()) {
                    url.searchParams.set(key, value);
                }

               
                fetch(url)
                    .then(response => response.text()) 
                    .then(text => {
                        try {
                            const data = JSON.parse(text); 
                            renderizarDashboard(data); 
                        } catch (error) {
                            console.error("El servidor no devolvió JSON válido. Esto respondió PHP:", text);
                            alert("Hay un problema en PHP. Revisa la consola (F12) para ver el error exacto.");
                        }
                    })
                    .catch(error => console.error("Error en AJAX:", error));
            });
        });
    </script>

    <?php include('../recursos/componentes/footer.php'); ?>
</body>
</html>