<?php
    $env = parse_ini_file(__DIR__ . '/../.env');

    $conexionMySql = mysqli_connect($env['DB_HOST'],$env['DB_USER'],"");
    $aux=0;
    try {
        mysqli_select_db($conexionMySql, $env['DB_NAME']);
        //echo "La base de datos existe, conectando ";
        $conexionDB = mysqli_connect($env['DB_HOST'],$env['DB_USER'],"",$env['DB_NAME']);
    } catch (mysqli_sql_exception $e) {
        echo "No se puede conectar a la base de datos";
        $aux=1;
    }
?>