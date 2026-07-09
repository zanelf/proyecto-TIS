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

    $consulta = "SELECT * FROM solicitud WHERE solicitud_ID = '$id_solicitud'";
    $resultado = mysqli_query($conexionDB, $consulta);
    $solicitud = mysqli_fetch_assoc($resultado);

    if (!$solicitud) {
        echo "Solicitud no encontrada.";
        exit;
    }

    if ($solicitud['Tipo_estado'] != 'Derivada') {
        header("Location: solicitud.php?error=estadoInvalido");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomar Solicitud - SGISC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../recursos/css/style_index.css">
</head>
<body class="bg-light">
    <?php include('../recursos/componentes/navbar1.php'); ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">Tomar solicitud</h4>

                        <p><strong>Asunto:</strong> <?php echo htmlspecialchars($solicitud['Asunto']); ?></p>
                        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($solicitud['Descripcion']); ?></p>

                        <p class="text-muted">
                            Al tomar esta solicitud, pasará al estado "En proceso" y quedará asignada a usted
                            para su gestión.
                        </p>

                        <form action="../consultas/CambiarEstadoSol.php" method="POST">
                            <input type="hidden" name="ID_cambio" value="<?php echo $id_solicitud; ?>">
                            <input type="hidden" name="estadoSiguiente" value="En proceso">
                            <div class="d-flex justify-content-between mt-4">
                                <a href="solicitud.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-success">Tomar solicitud</button>
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