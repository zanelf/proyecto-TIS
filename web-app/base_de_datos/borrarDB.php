<?php

    include('conexion.php');

    $env = parse_ini_file(__DIR__ . '/../.env');

    try {
        mysqli_query($conexionDB, "DROP DATABASE ".$env['DB_NAME']);
        echo "La base de datos BORRADA";
    } catch (mysqli_sql_exception $e) {
        echo "No se pudo borrar la base de datos";
    }
?>