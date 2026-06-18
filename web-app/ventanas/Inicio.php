<?php
include('conexion.php');
?>

<!DOCTYPE html>
<html lang="es" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema SGISC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="h-100 m-0 overflow-hidden">

    <div class="container-fluid h-100 p-0">
        <div class="row g-0 h-100">
            
            <div class="col-lg-6 bg-custom-dark text-white d-flex flex-column justify-content-between p-5">
                <div>
                    <span class="text-uppercase tracking-wider fw-bold text-white-50" style="font-size: 0.75rem;">Municipalidad</span>
                    <h1 class="fw-bold h3 m-0 mt-1">SGISC</h1>
                </div>

                <div class="text-center my-auto">
                    <i class="bi bi-building-gear central-building-icon"></i>
                </div>

                <div style="font-size: 0.9rem;">
                    <p class="mb-3 text-white-50">Portal interno para funcionarios municipales.</p>
                    <div class="d-flex flex-column gap-2 opacity-75">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check"></i> Seguro
                        </span>
                        <span class="d-flex align-items-center gap-2">
                            <i class="bi bi-lock"></i> Confiable
                        </span>
                        <span class="d-flex align-items-center gap-2">
                            <i class="bi bi-people"></i> Cercano a la comunidad
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 bg-light d-flex align-items-center justify-content-center p-5">
                <div class="w-100" style="max-width: 420px;">
                    
                    <div class="text-center mb-4">
                        <h2 class="fw-bold h4 text-dark mb-2">Acceso al Sistema SGISC</h2>
                        <p class="text-muted small">Ingrese sus credenciales para continuar.</p>
                    </div>

                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label fw-semibold text-dark mb-1" style="font-size: 0.85rem;">Usuario</label>
                            <input type="text" class="form-control py-2" id="usuario" name="usuario" placeholder="Ingrese su usuario" required>
                        </div>

                        <div class="mb-2">
                            <label for="password" class="form-label fw-semibold text-dark mb-1" style="font-size: 0.85rem;">Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control py-2" id="password" name="password" placeholder="Ingrese su contraseña" required>
                                <span class="input-group-text bg-white text-muted border-start-0" style="cursor: pointer;">
                                    <i class="bi bi-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="text-end mb-4">
                            <a href="#" class="text-custom-link">¿Olvidó su contraseña?</a>
                        </div>

                        <button type="submit" class="btn btn-custom-dark w-100 py-2 fw-medium mb-4">Ingresar</button>

                        <div class="text-center">
                            <a href="index.php" class="text-custom-link text-muted d-inline-flex align-items-center gap-1">
                                <i class="bi bi-arrow-left"></i> Volver al portal ciudadano
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>