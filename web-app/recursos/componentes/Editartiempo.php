<?php
    session_start();
    include_once('../../base_de_datos/conexion.php');

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../../index.php");
        exit;
    }

    if (!isset($_GET['ID_prioridad']) || !isset($_GET['ID_tipo_solicitud']) || !isset($_GET['ID_departamento'])) {
        echo "Faltan parámetros para editar.";
        exit;
    }

    $ID_prioridad = mysqli_real_escape_string($conexionDB, $_GET["ID_prioridad"]);
    $ID_tipo_solicitud = mysqli_real_escape_string($conexionDB, $_GET["ID_tipo_solicitud"]);
    $ID_departamento = mysqli_real_escape_string($conexionDB, $_GET["ID_departamento"]);

    // Condición para el director
    if ($_SESSION["tipo"] == "Director" && $_SESSION["ID_departamento"] != $ID_departamento) {
        header("Location: ../../ventanas/tiempo.php?error=noAutorizado");
        exit;
    }

    $consulta = "SELECT t.*, p.Nombre AS prioridad, ts.nombre AS tipo, d.nombre AS departamento
                 FROM tiempo t
                 JOIN prioridad p ON t.ID_prioridad = p.ID_prioridad
                 JOIN tipo_solicitud ts ON t.ID_tipo_solicitud = ts.ID_tipo_solicitud
                 JOIN departamento d ON t.ID_departamento = d.ID_departamento
                 WHERE t.ID_prioridad = '$ID_prioridad'
                   AND t.ID_tipo_solicitud = '$ID_tipo_solicitud'
                   AND t.ID_departamento = '$ID_departamento'
                 LIMIT 1";
    $registro = mysqli_fetch_assoc(mysqli_query($conexionDB, $consulta));

    if (!$registro) {
        echo "Configuración no encontrada.";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tiempo SLA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom border-primary py-3 text-center">
                        <h6 class="mb-0 fw-bold text-primary text-uppercase small">Editar Tiempo de Respuesta</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="text-muted small">Prioridad:</span> <strong><?php echo htmlspecialchars($registro['prioridad']); ?></strong><br>
                            <span class="text-muted small">Tipo:</span> <strong><?php echo htmlspecialchars($registro['tipo']); ?></strong><br>
                            <span class="text-muted small">Departamento:</span> <strong><?php echo htmlspecialchars($registro['departamento']); ?></strong>
                        </div>
                        <hr>
                        <form action="../../consultas/Actualizartiempo.php" method="POST">
                            <input type="hidden" name="ID_prioridad" value="<?php echo htmlspecialchars($ID_prioridad); ?>">
                            <input type="hidden" name="ID_tipo_solicitud" value="<?php echo htmlspecialchars($ID_tipo_solicitud); ?>">
                            <input type="hidden" name="ID_departamento" value="<?php echo htmlspecialchars($ID_departamento); ?>">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Días hábiles</label>
                                <input type="number" class="form-control" name="tiempo" min="1" max="30"
                                       value="<?php echo htmlspecialchars($registro['tiempo']); ?>" required>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="../../ventanas/tiempo.php" class="btn btn-sm btn-secondary fw-medium px-4 shadow-sm py-2">Cancelar</a>
                                <button type="submit" class="btn btn-sm btn-success fw-medium px-4 shadow-sm py-2">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>