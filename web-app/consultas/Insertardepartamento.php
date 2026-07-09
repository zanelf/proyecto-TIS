<?php
    include('../base_de_datos/conexion.php');

    $nombre=$_POST["nombre"];

    $consulta = "INSERT INTO departamento (nombre)  VALUES ('$nombre')";
    $resultado = mysqli_query($conexionDB,$consulta);

    header('Location: ../ventanas/departamento.php');
?>