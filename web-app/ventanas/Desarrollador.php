<?php

    include('../base_de_datos/conexion.php');
    $env = parse_ini_file(__DIR__ . '/../.env');

    $consulta = "SELECT * FROM departamento";

    try {
        mysqli_select_db($conexionDB, $env['DB_NAME']);
        $resultado = mysqli_query($conexionDB,$consulta);
        $atributos = mysqli_query($conexionDB,"DESCRIBE departamento");
        //echo "La base de datos existe, conectando ";
        $conexionDB = mysqli_connect($env['DB_HOST'],$env['DB_USER'],"",$env['DB_NAME']);
    } catch (mysqli_sql_exception $e) {
        echo "ERROR EN DESARROLLADOR.PHP";
    }
    session_start();

    if(!isset($_SESSION["usuario"])){
        header("Location: Login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        include('../recursos/componentes/navbar1.php');
    ?>
    <form action="../base_de_datos/borrarDB.php" method="POST">
        <button type="submit">BORRAR BASE DATOS</button>
    </form>
    <form action="../base_de_datos/crearDB.php" method="POST">
        <button type="submit">CREAR BASE DATOS</button>
    </form>
    <form action="../base_de_datos/semilla1.php" method="POST">
        <button type="submit">Semilla1</button>
    </form>
</body>
</html>