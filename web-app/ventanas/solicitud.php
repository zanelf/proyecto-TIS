<?php
    session_start();
    include('../base_de_datos/conexion.php');

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../index.php");
        exit;
    }

    $tipoUsuario = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : "";
    $tieneDepartamento = isset($_SESSION['ID_departamento']) && $_SESSION['ID_departamento'] !== "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGISC - Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../recursos/css/style_index.css?v=<?php echo time(); ?>">
</head>
<body class="bg-light">
    <?php include('../recursos/componentes/navbar1.php'); ?>

    <div class="container mt-5 mb-5">
        <h1 class="fw-bold text-dark mb-4">Solicitudes</h1>

        <div class="card border-0 shadow-sm p-3 mb-4 bg-white" style="border-radius: 12px;">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-8">
                    <form method="GET" class="d-flex gap-2">
                        <input
                            type="text"
                            class="form-control"
                            name="buscar"
                            placeholder="Buscar por asunto, descripción o ID..."
                            value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>"
                        >
                        <button type="submit" class="btn btn-primary text-white px-4">Buscar</button>
                    </form>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <?php if ($tieneDepartamento && ($tipoUsuario == "Funcionario" || $tipoUsuario == "Director")): ?>
                        <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2">
                            Mostrando solo tu departamento
                        </span>
                    <?php elseif ($tipoUsuario == "Administrador"): ?>
                        <span class="badge bg-secondary-subtle text-secondary fw-semibold px-3 py-2">
                            Mostrando todas las solicitudes
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 12px;">
            <?php
                $Tipo_modelo = "solicitud";
                include('../recursos/componentes/Tabla.php');
            ?>
        </div>
    </div>

    <?php include('../recursos/componentes/footer.php'); ?>
</body>
</html>