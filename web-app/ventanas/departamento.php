<?php
    session_start();
    include('../base_de_datos/conexion.php');

    if (!isset($_SESSION["usuario"])) {
        header("Location: Login.php");
        exit;
    }

    $Tipo_modelo = "departamento";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGISC - Departamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../recursos/css/style_index.css?v=<?php echo time(); ?>">
</head>
<body class="bg-light">
    <?php include('../recursos/componentes/navbar1.php'); ?>

    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h1 class="fw-bold text-dark m-0">Departamentos</h1>
            <button type="button" class="btn btn-success fw-medium shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalNuevoDepartamento">
                + Agregar Departamento
            </button>
        </div>

        <div class="card border-0 shadow-sm p-3 mb-3 bg-white" style="border-radius: 12px;">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-8">
                    <form method="GET" class="d-flex gap-2">
                        <input
                            type="text"
                            class="form-control"
                            name="buscar"
                            placeholder="Buscar por nombre de departamento..."
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

        <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 12px;">
            <?php include('../recursos/componentes/Tabla.php'); ?>
        </div>
    </div>

    <div class="modal fade" id="modalNuevoDepartamento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold text-dark m-0">Nuevo departamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <?php include('../recursos/componentes/Formulario2.php'); ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('../recursos/componentes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>