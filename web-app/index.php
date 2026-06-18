<?php
    include('base_de_datos/conexion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGISC - Municipalidad</title>
    <!-- Bootstrap 5 CDN para maquetación limpia y las tarjetas con bordes redondeados -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap para el documento y la lupa de las tarjetas -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="recursos/css/style_index.css" rel="stylesheet">
    
    <style>
        /* Fondo gris claro muy sutil idéntico a la imagen */

    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- NAVBAR SUPERIOR -->
    <nav class="navbar navbar-municipalidad py-3 shadow-sm">
        <div class="container">
            <?php if (isset($_SESSION["usuario"])): ?>

    <span>
        Bienvenido,
        <?php echo $_SESSION["usuario"]; ?>
        (<?php echo $_SESSION["tipo"]; ?>)
    </span>

    <?php if ($_SESSION["tipo"] == "Administrador"): ?>
            <a href="ventanas/Inicio.php" class="btn-acceso">Solicitudes</a>
            <a href="ventanas/Inicio.php" class="btn-acceso">Ciudadanos</a>
            <a href="ventanas/Inicio.php" class="btn-acceso">Departamentos</a>
            <a href="ventanas/Inicio.php" class="btn-acceso">Tipos de solicitudes</a>
    <?php endif; ?>

            <a href="./consultas/logout.php" class="btn-acceso">Cerrar sesion</a>

    <?php else: ?>
        <span class="navbar-brand-text text-uppercase">Municipalidad</span>
            <a href="ventanas/Inicio.php" class="btn-acceso">Acceso</a>
    <?php endif; ?>
        </div>
    </nav>

    <!-- CUERPO PRINCIPAL -->
    <main class="container flex-grow-1 my-5 py-4">
        <div class="row mb-5">
            <div class="col-12">
                <p class="text-muted small mb-1 text-uppercase fw-semibold tracking-wider">SGISC</p>
                <h1 class="fw-bold text-dark mb-3" style="font-size: 2.25rem;">Sistema de Gestión Inteligente de Solicitudes Ciudadanas</h1>
                <p class="text-secondary fs-5">Plataforma para registrar, gestionar y realizar seguimiento de solicitudes ciudadanas.</p>
            </div>
        </div>

        <!-- FILA DE TARJETAS -->
        <div class="row g-4">
            <!-- Tarjeta 1: Nueva Solicitud (Clickeable completa hacia Enviarsolicitud.php) -->
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

            <!-- Tarjeta 2: Seguimiento de Solicitud (Clickeable pero sin acción) -->
            <div class="col-12 col-md-6">
                <div class="option-card card-dummy shadow-sm" onclick="event.preventDefault();">
                    <div class="icon-container mb-4">
                        <i class="bi bi-search fs-5"></i>
                    </div>
                    <h2 class="h5 fw-bold text-dark mb-2">Seguimiento de Solicitud</h2>
                    <p class="text-secondary small mb-4">Consulte el estado de su solicitud con el código de seguimiento.</p>
                    <span class="action-link" style="cursor: pointer;">Consultar &rarr;</span>
                </div>
            </div>
        </div>
    </main>

    <!-- PIE DE PÁGINA -->
    <footer class="py-4 mt-auto">
        <div class="container">
            <p class="m-0">&copy; 2026 Municipalidad. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>