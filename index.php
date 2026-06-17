<?php
    // Iniciamos sesión por si el navbar1.php o el sistema valida el tipo de usuario
    session_start();
    include('conexion.php');

    // Consultas opcionales para que las estadísticas muestren datos reales de tu base de datos
    // Si aún no tienes registros, mostrará 0 automáticamente sin romperse.
    $cantSolicitudes = 0;
    $cantCiudadanos = 0;
    $cantDepartamentos = 0;

    $resSol = mysqli_query($conexionDB, "SELECT COUNT(*) as total FROM solicitud");
    if($resSol) { $data = mysqli_fetch_assoc($resSol); $cantSolicitudes = $data['total']; }

    $resCiu = mysqli_query($conexionDB, "SELECT COUNT(*) as total FROM ciudadano");
    if($resCiu) { $data = mysqli_fetch_assoc($resCiu); $cantCiudadanos = $data['total']; }

    $resDep = mysqli_query($conexionDB, "SELECT COUNT(*) as total FROM departamento");
    if($resDep) { $data = mysqli_fetch_assoc($resDep); $cantDepartamentos = $data['total']; }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGISC - Portal de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* Estilos personalizados para lograr el acabado fidedigno de Lovable */
        .navbar-brand-custom {
            font-weight: 700;
            color: #0d6efd;
            letter-spacing: 0.5px;
        }
        .hero-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: none;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        }
        .icon-box {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
    </style>
</head>
<body class="bg-light text-dark">

    <nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom sticky-top py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand navbar-brand-custom d-flex align-items-center gap-2" href="index.php">
                <i class="bi bi-shield-check fs-4 text-primary"></i>
                <span>SGISC</span>
            </a>
            
            <button class="navbar-expand-lg border-0 bg-transparent text-secondary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-2"></i>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    <a href="index.php" class="btn btn-sm btn-primary px-3 rounded-pill">Inicio</a>
                    <a href="Enviarsolicitud.php" class="btn btn-sm btn-light px-3 rounded-pill text-secondary">Solicitudes</a>
                    <a href="Login.php" class="btn btn-sm btn-light px-3 rounded-pill text-secondary">Acceso Interno</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        
        <div class="card hero-card rounded-4 shadow-sm p-4 p-md-5 mb-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-primary-subtle text-primary mb-3 px-3 py-2 rounded-pill fw-semibold">Incremento 2</span>
                    <h1 class="display-5 fw-bold text-dark mb-3">Portal de Solicitudes Ciudadanas</h1>
                    <p class="lead text-secondary mb-4">
                        Bienvenido al Sistema de Gestión de Solicitudes Ciudadanas (SGISC). 
                        Utiliza este espacio para reportar incidencias, ingresar solicitudes de servicio 
                        o realizar el seguimiento a trámites comunales de manera rápida y transparente.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="solicitud.php" class="btn btn-primary btn-lg px-4 rounded-3 d-flex align-items-center gap-2 shadow">
                            <i class="bi bi-plus-circle"></i> Nueva Solicitud
                        </a>
                        <a href="Enviarsolicitud.php" class="btn btn-outline-secondary btn-lg px-4 rounded-3">
                            <i class="bi bi-search"></i> Consultar Estado
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-center">
                    <i class="bi bi-file-earmark-text text-primary-subtle" style="font-size: 10rem;"></i>
                </div>
            </div>
        </div>

        <h3 class="fw-bold mb-4 text-dark d-flex align-items-center gap-2">
            <i class="bi bi-graph-up text-primary"></i> Estado del Sistema
        </h3>
        
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="card stat-card bg-white rounded-4 shadow-sm h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box bg-primary-subtle text-primary">
                            <i class="bi bi-clipboard-data fs-4"></i>
                        </div>
                        <span class="text-success small fw-medium d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-up-short"></i> Activo
                        </span>
                    </div>
                    <h6 class="text-secondary fw-normal text-uppercase small mb-1">Total Solicitudes</h6>
                    <h2 class="fw-bold m-0 text-dark"><?php echo $cantSolicitudes; ?></h2>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card stat-card bg-white rounded-4 shadow-sm h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box bg-success-subtle text-success">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                        <span class="text-success small fw-medium d-flex align-items-center gap-1">
                            <i class="bi bi-check-circle"></i> En línea
                        </span>
                    </div>
                    <h6 class="text-secondary fw-normal text-uppercase small mb-1">Ciudadanos</h6>
                    <h2 class="fw-bold m-0 text-dark"><?php echo $cantCiudadanos; ?></h2>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card stat-card bg-white rounded-4 shadow-sm h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box bg-warning-subtle text-warning">
                            <i class="bi bi-building fs-4"></i>
                        </div>
                        <span class="text-muted small fw-medium">Unidades</span>
                    </div>
                    <h6 class="text-secondary fw-normal text-uppercase small mb-1">Departamentos</h6>
                    <h2 class="fw-bold m-0 text-dark"><?php echo $cantDepartamentos; ?></h2>
                </div>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>