<?php
    include('conexion.php');
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
    
    <style>
        /* Fondo gris claro muy sutil idéntico a la imagen */
        body {
            background-color: #f8fafc;
            font-family: system-ui, -apple-system, sans-serif;
        }
        /* Barra superior azul oscuro institucional */
        .navbar-municipalidad {
            background-color: #112d57;
        }
        .navbar-brand-text {
            color: #ffffff;
            font-weight: 600;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }
        /* Botón Acceso transparente con borde blanco */
        .btn-acceso {
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.6);
            background: transparent;
            font-size: 0.9rem;
            padding: 0.375rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-acceso:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        /* Cajas de las tarjetas contenedoras de íconos */
        .icon-container {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background-color: #f1f5f9;
            color: #334155;
        }
        /* Tarjetas de opciones principales */
        .option-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 2rem;
            height: 100%;
            transition: border-color 0.15s ease-in-out;
        }
        /* Estilo interactivo para la tarjeta que sí funciona */
        .card-clickable {
            cursor: pointer;
            text-decoration: none;
            display: block;
        }
        .card-clickable:hover {
            border-color: #cbd5e1;
        }
        /* Estilo interactivo para la tarjeta que es clickeable pero no hace nada */
        .card-dummy {
            cursor: pointer;
        }
        /* Enlaces internos de acción */
        .action-link {
            color: #1d4ed8;
            font-weight: 500;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .card-clickable:hover .action-link {
            text-decoration: underline;
        }
        /* Pie de página */
        footer {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 0.85rem;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- NAVBAR SUPERIOR -->
    <nav class="navbar navbar-municipalidad py-3 shadow-sm">
        <div class="container">
            <span class="navbar-brand-text text-uppercase">Municipalidad</span>
            <a href="Login.php" class="btn-acceso">Acceso</a>
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
                <a href="Enviarsolicitud.php" class="option-card card-clickable shadow-sm">
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