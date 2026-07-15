<?php
include('./base_de_datos/conexion.php');
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGISC</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="recursos/css/style_index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="recursos/css/style_metricas.css?v=<?php echo time(); ?>">
</head>

<body class="d-flex flex-column min-vh-100">


    <nav class="navbar navbar-expand-lg navbar-municipalidad py-3 shadow-sm">
        <div class="container">
            <a href="index.php" class="navbar-brand-text text-uppercase text-decoration-none">SGISC</a>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <?php if (isset($_SESSION["usuario"])): ?>
                    <span class="texto-bienvenida">
                        Bienvenido, <?php echo $_SESSION["usuario"]; ?>
                        <?php if (isset($_SESSION["tipo"])): ?>
                            (<?php echo $_SESSION["tipo"]; ?>)
                        <?php endif; ?>
                    </span>

                    <?php if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] == "Administrador"): ?>
                        <a href="ventanas/solicitud.php" class="btn-acceso">Solicitudes</a>
                        <a href="ventanas/departamento.php" class="btn-acceso">Departamentos</a>
                        <a href="ventanas/tipo_solicitud.php" class="btn-acceso">Tipos de solicitudes</a>
                        <a href="ventanas/prioridad.php" class="btn-acceso">Prioridades</a>
                        <a href="ventanas/tiempo.php" class="btn-acceso">Tiempos SLA</a>
                        <a href="ventanas/Usuario.php" class="btn-acceso">Usuarios</a>
                        <a href="ventanas/metricas.php" class="btn-acceso">Métricas</a>
                    <?php endif; ?>

                    <?php if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] == "Funcionario"): ?>
                        <a href="ventanas/solicitud.php" class="btn-acceso">Solicitudes</a>
                    <?php endif; ?>

                    <?php if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] == "Director"): ?>
                        <a href="ventanas/director.php" class="btn-acceso">Panel</a>
                        <a href="ventanas/solicitud.php" class="btn-acceso">Solicitudes</a>
                        <a href="ventanas/tiempo.php" class="btn-acceso">Tiempos SLA</a>
                        <a href="ventanas/metricas.php" class="btn-acceso">Métricas</a>
                    <?php endif; ?>

                    <a href="./consultas/logout.php" class="btn-acceso btn-cerrar">Cerrar sesion</a>
                <?php else: ?>
                    <a href="ventanas/Inicio.php" class="btn-acceso">Acceso</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>


    <main class="container flex-grow-1 my-5 py-4">
        <div class="row mb-5">
            <div class="col-12">
                <p class="text-muted small mb-1 text-uppercase fw-semibold tracking-wider">SGISC</p>
                <h1 class="fw-bold text-dark mb-3" style="font-size: 2.25rem;">Sistema de Gestión Inteligente de Solicitudes Ciudadanas</h1>
                <p class="text-secondary fs-5">Plataforma para registrar, gestionar y realizar seguimiento de solicitudes ciudadanas.</p>
            </div>
        </div>


        <div class="row g-4">

            <div class="col-12 col-md-6">
                <a href="ventanas/Enviarsolicitud.php" class="option-card card-clickable shadow-sm">
                    <div class="icon-container mb-4">
                        <i class="bi bi-file-earmark-text fs-5"></i>
                    </div>
                    <h2 class="h5 fw-bold text-dark mb-2">Nueva Solicitud</h2>
                    <p class="text-secondary small mb-4">Registre una solicitud, reclamo, sugerencia o felicitación.</p>
                    <span class="action-link">Ingresar &rarr;</span>
                </a>
            </div>


            <div class="col-12 col-md-6">
                <a href="ventanas/Seguimiento.php" class="option-card card-clickable shadow-sm">
                    <div class="icon-container mb-4">
                        <i class="bi bi-search fs-5"></i>
                    </div>
                    <h2 class="h5 fw-bold text-dark mb-2">Seguimiento de Solicitud</h2>
                    <p class="text-secondary small mb-4">Consulte el estado de su solicitud con el código de seguimiento.</p>
                    <span class="action-link">Consultar &rarr;</span>
                </a>
            </div>
        </div>
    </main>


    <footer class="py-4 mt-auto">
        <div class="container">
            <p class="m-0">&copy; 2026 SGISC. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>

</html>