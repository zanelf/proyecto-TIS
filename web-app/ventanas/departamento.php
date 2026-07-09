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
<body style="background-color: #f8fafc;">
    <?php include('../recursos/componentes/navbar1.php'); ?>

    <div class="container py-4">
        <h1 class="fw-bold text-dark mb-4">Departamentos</h1>

        <div class="row">
            <div class="col-12 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 12px;">
                    <h5 class="fw-bold text-dark mb-3">Nuevo departamento</h5>
                    <?php include('../recursos/componentes/Formulario2.php'); ?>
                </div>
            </div>

            <div class="col-12 col-lg-8">
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
        </div>
    </div>

    <?php include('../recursos/componentes/footer.php'); ?>
</body>
</html>