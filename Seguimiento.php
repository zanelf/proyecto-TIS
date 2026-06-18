<?php
    include('conexion.php');
    $consulta = "SELECT * FROM solicitud";
    $resultado = mysqli_query($conexionDB,$consulta);
    $atributos = mysqli_query($conexionDB,"DESCRIBE solicitud");
    $tipos_solicitudes=mysqli_query($conexionDB,"SELECT * FROM tipo_solicitud");
    $tipo_sol=mysqli_fetch_assoc($tipos_solicitudes);
    $departamentos=mysqli_query($conexionDB,"SELECT * FROM departamento");
    $Comp = null;

    if (isset($_POST["ID_comprobante"]) && $_POST["ID_comprobante"] != "") {
        $ID_comprobante = $_POST["ID_comprobante"];

        $consultaCompr = mysqli_query(
            $conexionDB,
            "SELECT ID_comprobante
            FROM comprobante
            WHERE ID_comprobante = $ID_comprobante"
        );

        $Comp = mysqli_fetch_assoc($consultaCompr);
    }
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
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-4 shadow">
        <a href="index.php">
            <button type="button">INDEX</button>
        </a>
        <a href="Login.php">
            <button type="button">Acceso</button>
        </a>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <form action="Seguimiento.php" method="POST">
                    <label class="form-label">ID_comprobante</label>
                    <input type="number" name="ID_comprobante" class="form-control">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-6">

            </div>
        </div>
    </div>
</body>
</html>