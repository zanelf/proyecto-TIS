<?php
    // 1. SIEMPRE iniciar la sesión primero
    session_start();
    
    // 2. Incluir la conexión a la base de datos
    include('../base_de_datos/conexion.php');

    // 3. Validar si el usuario está logueado
    if(!isset($_SESSION["usuario"])){
        header("Location: Login.php");
        exit;
    }

    // 4. Validar que el rol sea estrictamente "Director"
    if($_SESSION["tipo"] !== "Director"){ 
        // Si no es director, lo expulsamos por seguridad al login
        header("Location: Inicio.php?error=NoAutorizado"); 
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Director Municipal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <?php
        include('../recursos/componentes/navbar1.php'); 
    ?>

    <div class="container mt-5">

        <div class="row mt-4">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>