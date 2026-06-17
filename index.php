<?php
    include('conexion.php');
    $consulta = "SELECT * FROM ciudadano";
    $resultado = mysqli_query($conexionDB,$consulta);
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
<body>
    <?php if (isset($_SESSION["usuario"])): ?>

    <span>
        Bienvenido,
        <?php echo $_SESSION["usuario"]; ?>
        (<?php echo $_SESSION["tipo"]; ?>)
    </span>

    <?php if ($_SESSION["tipo"] == "Administrador"): ?>
        <a href="Administrador.php">
            <button type="button">Administradores</button>
        </a>
        <a href="ciudadano.php">
            <button type="button">Ciudadano</button>
        </a>
        <a href="solicitud.php">
            <button type="button">Solicitud</button>
        </a>
        <a href="usuario.php">
            <button type="button">Usuarios</button>
        </a>
    <?php endif; ?>

        <a href="logout.php">
            <button type="button">Cerrar Sesión</button>
        </a>

    <?php else: ?>

        <a href="Login.php">
            <button type="button">Acceso</button>
        </a>

    <?php endif; ?>
    
    <div class="container">
        <div class="row">
            <div class="col-6">
                <a href="Enviarsolicitud.php">;
                    <button type="button">Enviar solicitud</button>;
                </a>;
            </div>
            <div class="col-6">
                <a href="Seguimiento.php">;
                    <button type="button">Seguimiento</button>;
                </a>;
            </div>
        </div>
        

    </div>
</body>
</html>