<?php
    session_start();
    include("../base_de_datos/conexion.php");

    if (!isset($_SESSION["usuario"])) {
        header("Location: Login.php");
        exit;
    }

    // solo admin y director pueden gestionar tiempos
    if ($_SESSION["tipo"] != "Administrador" && $_SESSION["tipo"] != "Director") {
        header("Location: ../index.php");
        exit;
    }

    $esDirector = ($_SESSION["tipo"] == "Director");
    $dptoDirector = $esDirector ? $_SESSION["ID_departamento"] : null;

    $prioridades = mysqli_query($conexionDB, "SELECT * FROM prioridad");
    $tipos = mysqli_query($conexionDB, "SELECT * FROM tipo_solicitud");
    $departamentos = mysqli_query($conexionDB, "SELECT * FROM departamento");

    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";
    $buscarEsc = mysqli_real_escape_string($conexionDB, $buscar);

    $where = [];
    if ($esDirector) {
        $where[] = "t.ID_departamento = '" . mysqli_real_escape_string($conexionDB, $dptoDirector) . "'";
    }
    if ($buscar !== "") {
        $where[] = "(p.Nombre LIKE '%$buscarEsc%' OR ts.nombre LIKE '%$buscarEsc%' OR d.nombre LIKE '%$buscarEsc%')";
    }
    $whereSql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

    $consulta = "SELECT t.ID_prioridad, t.ID_tipo_solicitud, t.ID_departamento, t.tiempo,
                        p.Nombre AS prioridad, ts.nombre AS tipo, d.nombre AS departamento
                 FROM tiempo t
                 JOIN prioridad p ON t.ID_prioridad = p.ID_prioridad
                 JOIN tipo_solicitud ts ON t.ID_tipo_solicitud = ts.ID_tipo_solicitud
                 JOIN departamento d ON t.ID_departamento = d.ID_departamento
                 $whereSql
                 ORDER BY d.nombre, ts.nombre, p.Nombre";
    $tiempos = mysqli_query($conexionDB, $consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiempos SLA - SGISC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/style_index.css">
    <link rel="stylesheet" href="../recursos/css/style_mantenedores.css">
</head>
<body class="bg-light">
    <?php include('../recursos/componentes/navbar1.php'); ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h1 class="fw-bold text-dark m-0">Tiempos de Respuesta (SLA)</h1>
                <?php if ($esDirector): ?>
                    <p class="text-muted mb-0 small">Configuración de tu departamento</p>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-success fw-medium shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalNuevoTiempo">
                + Agregar Tiempo
            </button>
        </div>

        <div class="card shadow-sm border mb-3" style="border-radius: 8px;">
            <div class="card-body p-3">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-8">
                        <form method="GET" class="d-flex gap-2">
                            <input type="text" class="form-control" name="buscar"
                                placeholder="Buscar por prioridad, tipo o departamento..."
                                value="<?php echo htmlspecialchars($buscar); ?>">
                            <button type="submit" class="btn btn-primary text-white px-4">Buscar</button>
                        </form>
                    </div>
                    <div class="col-12 col-md-4 text-md-end">
                        <span class="badge bg-secondary-subtle text-secondary fw-semibold px-3 py-2">
                            <?php echo $esDirector ? 'Tu departamento' : 'Todos los departamentos'; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border" style="border-radius: 8px; overflow: hidden;">
            <div class="card-header custom-card-header-table py-3">
                <h6 class="mb-0 fw-bold text-secondary text-uppercase small tracking-wider">Configuraciones Actuales</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Prioridad</th>
                                <th>Tipo de solicitud</th>
                                <th>Departamento</th>
                                <th>Días hábiles</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($tiempos) === 0): ?>
                                <tr><td colspan="5" class="text-center text-muted py-3">No hay tiempos configurados.</td></tr>
                            <?php else: ?>
                                <?php while ($t = mysqli_fetch_assoc($tiempos)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($t['prioridad']); ?></td>
                                        <td><?php echo htmlspecialchars($t['tipo']); ?></td>
                                        <td><?php echo htmlspecialchars($t['departamento']); ?></td>
                                        <td><?php echo htmlspecialchars($t['tiempo']); ?></td>
                                        <td class="text-center">
                                            <?php $params = "ID_prioridad=" . $t['ID_prioridad'] . "&ID_tipo_solicitud=" . $t['ID_tipo_solicitud'] . "&ID_departamento=" . $t['ID_departamento']; ?>
                                            <a href="../recursos/componentes/Editartiempo.php?<?php echo $params; ?>" class="btn btn-sm btn-warning text-dark fw-medium shadow-sm px-2">Editar</a>
                                            <a href="../consultas/Eliminartiempo.php?<?php echo $params; ?>" class="btn btn-sm btn-danger fw-medium shadow-sm px-2"
                                               onclick="return confirm('¿Confirma eliminar esta configuración de tiempo?');">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="modalNuevoTiempo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold text-dark m-0">Nuevo Tiempo SLA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form action="../consultas/Insertartiempo.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Prioridad</label>
                            <select name="ID_prioridad" class="form-select" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <?php while ($p = mysqli_fetch_assoc($prioridades)): ?>
                                    <option value="<?php echo $p['ID_prioridad']; ?>"><?php echo htmlspecialchars($p['Nombre']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo de solicitud</label>
                            <select name="ID_tipo_solicitud" class="form-select" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <?php while ($ts = mysqli_fetch_assoc($tipos)): ?>
                                    <option value="<?php echo $ts['ID_tipo_solicitud']; ?>"><?php echo htmlspecialchars($ts['nombre']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <?php if ($esDirector): ?>
                            <input type="hidden" name="ID_departamento" value="<?php echo htmlspecialchars($dptoDirector); ?>">
                        <?php else: ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Departamento</label>
                                <select name="ID_departamento" class="form-select" required>
                                    <option value="" disabled selected>Seleccione...</option>
                                    <option value="TODOS">Todos los departamentos</option>
                                    <?php while ($d = mysqli_fetch_assoc($departamentos)): ?>
                                        <option value="<?php echo $d['ID_departamento']; ?>"><?php echo htmlspecialchars($d['nombre']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Días hábiles</label>
                            <input type="number" name="tiempo" class="form-control" min="1" max="30" required>
                        </div>

                        <button type="submit" class="btn btn-success fw-medium px-3 shadow-sm w-100 py-2">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include('../recursos/componentes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>