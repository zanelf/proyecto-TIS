<?php
    include('conexion.php');


    $id_recibido=$_GET["id_enviado"];
    $sd=$_GET["tipomod"];

    $atributos = mysqli_query($conexionDB,"DESCRIBE ".$sd);
    
    $consulta = "DELETE FROM ciudadano WHERE RUT_ciudadano='$id_recibido'";
    $resultado = mysqli_query($conexionDB,$consulta);

   // header('Location: ciudadanos.php');
?>