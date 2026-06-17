<?php
    include('conexion.php');

    $correo_electronico=$_POST["correo_electronico"];
    $RUT_ciudadano=$_POST["RUT_ciudadano"];

    $consulta = "INSERT INTO ciudadano (RUT_ciudadano, correo_electronico)  VALUES ('$RUT_ciudadano','$correo_electronico')";
    $resultado = mysqli_query($conexionDB,$consulta);

    header('Location: ciudadano.php');
?>