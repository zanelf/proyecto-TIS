<?php
    include('conexion.php');
    $consulta = "SELECT * FROM ciudadano";
    $resultado = mysqli_query($conexionDB,$consulta);
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
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-4 shadow">
        <a href="index.php">
            <button type="button">INDEX</button>
        </a>
        <a href="Enviarsolicitud.php">
            <button type="button">SOLICITUDES</button>
        </a>
        <a href="Login.php">
            <button type="button">LOGIN</button>
        </a>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h5>NO SE QUE PONER AQUI</h5>
            </div>
        </div>

    </div>
    zdads
</body>
</html>