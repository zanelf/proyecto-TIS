<?php
    session_start();
    include("../base_de_datos/conexion.php");

    if (!isset($_SESSION["usuario"])) {
        header("Location: Login.php");
        exit;
    }

    $Tipo_modelo = "prioridad";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prioridades - SGISC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/style_index.css">
    <link rel="stylesheet" href="../recursos/css/style_mantenedores.css">
</head>
<body class="bg-light">
    <?php include('../recursos/componentes/navbar1.php'); ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h1 class="fw-bold text-dark m-0">Prioridades</h1>
            <button type="button" class="btn btn-success fw-medium shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalNuevaPrioridad">
                + Agregar Prioridad
            </button>
        </div>

        <div class="card shadow-sm border mb-3" style="border-radius: 8px;">
            <div class="card-body p-3">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-8">
                        <form method="GET" class="d-flex gap-2">
                            <input
                                type="text"
                                class="form-control"
                                name="buscar"
                                placeholder="Buscar por nombre..."
                                value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>"
                            >
                            <button type="submit" class="btn btn-primary text-white px-4">Buscar</button>
                        </form>
                    </div>
                    <div class="col-12 col-md-4 text-md-end">
                        <span class="badge bg-secondary-subtle text-secondary fw-semibold px-3 py-2">
                            Catálogo global
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border" style="border-radius: 8px; overflow: hidden;">
            <div class="card-header custom-card-header-table py-3">
                <h6 class="mb-0 fw-bold text-secondary text-uppercase small tracking-wider">Registros Actuales</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <?php include("../recursos/componentes/Tabla.php"); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNuevaPrioridad" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold text-dark m-0">Nueva Prioridad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <?php include("../recursos/componentes/Formulario2.php"); ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('../recursos/componentes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>