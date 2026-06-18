<?php
    include('conexion.php');

    $nombre=$_POST["nombre"];
    $descripcion=$_POST["descripcion"];

    $consulta = "INSERT INTO tipo_solicitud (nombre, descripcion)  VALUES ('$nombre','$descripcion')";
    $resultado = mysqli_query($conexionDB,$consulta);

    header('Location: tipo_solicitud.php');
?>