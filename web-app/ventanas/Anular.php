<?php
    session_start();
    include('../base_de_datos/conexion.php');

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../index.php");
        exit;
    }

    // Solo director
    if ($_SESSION["tipo"] != "Director") {
        header("Location: solicitud.php?error=noAutorizado");
        exit;
    }

    if (!isset($_GET['id_enviado'])) {
        echo "Faltan parámetros.";
        exit;
    }

    $id_solicitud = mysqli_real_escape_string($conexionDB, $_GET['id_enviado']);

    $consulta = "SELECT solicitud.*, tipo_solicitud.nombre AS tipo_solicitud, categoria.nombre AS categoria
                 FROM solicitud
                 LEFT JOIN tipo_solicitud ON solicitud.ID_tipo_solicitud = tipo_solicitud.ID_tipo_solicitud
                 LEFT JOIN categoria ON solicitud.ID_categoria = categoria.ID_categoria
                 WHERE solicitud.solicitud_ID = '$id_solicitud'";
    $resultado = mysqli_query($conexionDB, $consulta);
    $solicitud = mysqli_fetch_assoc($resultado);

    if (!$solicitud) {
        echo "Solicitud no encontrada.";
        exit;
    }

    // Solo anular solicitudes recibidas
    if ($solicitud['Tipo_estado'] != 'Recibida') {
        header("Location: solicitud.php?error=estadoInvalido");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anular Solicitud - SGISC</title>
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
                        <h4 class="fw-bold mb-3 text-danger">Anular solicitud #<?php echo htmlspecialchars($solicitud['solicitud_ID']); ?></h4>

                        <div class="alert alert-warning" role="alert">
                            Esta acción anulará permanentemente la solicitud. El ciudadano será notificado por correo con el motivo indicado.
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small">Tipo de solicitud</p>
                                <p><?php echo htmlspecialchars($solicitud['tipo_solicitud'] ?? '—'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small">Categoría</p>
                                <p><?php echo htmlspecialchars($solicitud['categoria'] ?? '—'); ?></p>
                            </div>
                        </div>

                        <p class="mb-1 text-muted small">Asunto</p>
                        <p><?php echo htmlspecialchars($solicitud['Asunto']); ?></p>

                        <p class="mb-1 text-muted small">Descripción del ciudadano</p>
                        <p><?php echo nl2br(htmlspecialchars($solicitud['Descripcion'])); ?></p>

                        <hr>

                        <form action="../consultas/CambiarEstadoSol.php" method="POST">
                            <input type="hidden" name="ID_cambio" value="<?php echo $id_solicitud; ?>">
                            <input type="hidden" name="estadoSiguiente" value="Anulada">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Motivo de anulación <span class="text-danger">*</span></label>
                                <textarea name="motivo_anulacion" class="form-control" rows="4" required
                                    placeholder="Explique por qué se anula esta solicitud (ej. contenido fuera de lugar, ajena a competencia municipal, etc.)"></textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="solicitud.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-danger">Confirmar anulación</button>
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