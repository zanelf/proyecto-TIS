<?php
    include('conexion.php');

    $nombre_usuario=$_POST["nombre_usuario"];
    $contraseña=$_POST["contraseña"];

    $consulta = "INSERT INTO usuario (nombre_usuario, contraseña)  VALUES ('$nombre_usuario','$contraseña')";
    $resultado = mysqli_query($conexionDB,$consulta);

    header('Location: usuario.php');
?>