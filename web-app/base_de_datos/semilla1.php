<?php

    include('conexion.php');

    $env = parse_ini_file(__DIR__ . '/../.env');

    try {
        mysqli_query($conexionDB,"INSERT INTO usuario (nombre_usuario, contraseña)  VALUES ('admin','admin')");
        $ultimoID = mysqli_insert_id($conexionDB);
        mysqli_query($conexionDB,"INSERT INTO administrador (ID_usuario)  VALUES ('$ultimoID')");
        mysqli_query($conexionDB,"INSERT INTO usuario (nombre_usuario, contraseña)  VALUES ('desarrollador','desarrollador')");
        $ultimoID = mysqli_insert_id($conexionDB);
        mysqli_query($conexionDB,"INSERT INTO desarrollador (ID_usuario)  VALUES ('$ultimoID')");
        echo "Se creo admin";
    } catch (mysqli_sql_exception $e) {
        echo "NO ADMIN";
    }
?>