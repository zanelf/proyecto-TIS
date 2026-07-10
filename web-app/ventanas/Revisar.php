<?php
    session_start();
    include('../base_de_datos/conexion.php');

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../index.php");
        exit;
    }

    if (!isset($_GET['id_enviado'])) {
        echo "Faltan parámetros.";
        exit;
    }

    $id_solicitud = mysqli_real_escape_string($conexionDB, $_GET['id_enviado']);

    $consulta = "SELECT solicitud.*, categoria.nombre AS nombre_categoria
                 FROM solicitud
                 LEFT JOIN categoria ON solicitud.ID_categoria = categoria.ID_categoria
                 WHERE solicitud.solicitud_ID='$id_solicitud'";
    $resultado = mysqli_query($conexionDB, $consulta);
    $solicutdRevisar = mysqli_fetch_assoc($resultado);

    if (!$solicutdRevisar) {
        echo "Solicitud no encontrada.";
        exit;
    }

    $prioridadesQuery = "SELECT * FROM prioridad";
    $prioridades = mysqli_query($conexionDB, $prioridadesQuery);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisar Solicitud - SGISC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../recursos/css/style_index.css">
</head>
<body class="bg-light">
    <?php include('../recursos/componentes/navbar1.php'); ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-4">Revisar solicitud #<?php echo htmlspecialchars($solicutdRevisar['solicitud_ID']); ?></h4>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small">Estado</p>
                                <p><?php echo htmlspecialchars($solicutdRevisar['Tipo_estado']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small">Categoría</p>
                                <p><?php echo htmlspecialchars($solicutdRevisar['nombre_categoria'] ?? '—'); ?></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small">Correo del ciudadano</p>
                                <p><?php echo htmlspecialchars($solicutdRevisar['correo_electronico'] ?? '—'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small">Tipo de solicitud</p>
                                <p><?php echo htmlspecialchars($solicutdRevisar['ID_tipo_solicitud'] ?? '—'); ?></p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 text-muted small">Asunto</p>
                            <p><?php echo htmlspecialchars($solicutdRevisar['Asunto']); ?></p>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 text-muted small">Descripción</p>
                            <p><?php echo nl2br(htmlspecialchars($solicutdRevisar['Descripcion'])); ?></p>
                        </div>

                        <hr>

                        <form action="../consultas/CambiarEstadoSol.php" method="POST">
                            <input type="hidden" name="ID_cambio" value="<?php echo $id_solicitud; ?>">
                            <input type="hidden" name="estadoSiguiente" value="Derivada">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Prioridad</label>
                                <select name="ID_prioridad" class="form-select">
                                    <?php while($prioridad = mysqli_fetch_assoc($prioridades)) { ?>
                                        <option value="<?php echo $prioridad['ID_prioridad']; ?>">
                                            <?php echo htmlspecialchars($prioridad['Nombre']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="solicitud.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-success">Derivar solicitud</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../recursos/componentes/footer.php'); ?>
</body>
</html>