<?php
session_start();

include('../base_de_datos/conexion.php');

if (!isset($_SESSION["usuario"])) {
    header("Location: Login.php");
    exit;
}

// Administrador ve a todo el personal; Director ve solo el de su departamento (filtrado en Tabla.php)
if ($_SESSION["tipo"] !== "Administrador" && $_SESSION["tipo"] !== "Director") {
    header("Location: Inicio.php?error=NoAutorizado");
    exit;
}

$tipoUsuario = $_SESSION["tipo"];
$Tipo_modelo = "usuario";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - SGISC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/style_mantenedores.css">
</head>
<body class="bg-light">
    <?php include('../recursos/componentes/navbar1.php'); ?>

    <div class="container mt-5">
        <div class="row">

            <?php if ($tipoUsuario == "Administrador"): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border" style="border-radius: 8px; overflow: hidden;">
                    <div class="card-header custom-card-header py-3">
                        <h6 class="mb-0 fw-bold text-primary text-uppercase small tracking-wider">Nuevo Usuario</h6>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <?php include('../recursos/componentes/UsuarioFormulario.php'); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="col-md-<?php echo $tipoUsuario == "Administrador" ? "8" : "12"; ?>">
                <div class="card shadow-sm border mb-3" style="border-radius: 8px;">
                    <div class="card-body p-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-md-8">
                                <form method="GET" class="d-flex gap-2">
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="buscar"
                                        placeholder="Buscar por RUT..."
                                        value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>"
                                    >
                                    <button type="submit" class="btn btn-primary text-white px-4">Buscar</button>
                                </form>
                            </div>
                            <div class="col-12 col-md-4 text-md-end">
                                <?php if ($tipoUsuario == "Director"): ?>
                                    <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2">
                                        Mostrando solo tu departamento
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary fw-semibold px-3 py-2">
                                        Mostrando todos los usuarios
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border" style="border-radius: 8px; overflow: hidden;">
                    <div class="card-header custom-card-header-table py-3">
                        <h6 class="mb-0 fw-bold text-secondary text-uppercase small tracking-wider">Registros Actuales</h6>
                    </div>
                    <div class="card-body p-0"> <div class="table-responsive">
                            <?php include('../recursos/componentes/Tabla.php'); ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include('../recursos/componentes/footer.php'); ?>
</body>
</html>