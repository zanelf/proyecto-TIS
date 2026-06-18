<?php
    include('conexion.php');


    $id_recibido=$_GET["id_enviado"];
    $sd=$_GET["tipomod"];

    $atributos = mysqli_query($conexionDB,"DESCRIBE ".$sd);

    $campos=[];
    $PKmodelo = "";

    while ($fila = mysqli_fetch_assoc($atributos)) {
        $campos[] = $fila;
        if($fila['Key']=="PRI"){
            $PKmodelo=$fila['Field'];
        }
    }
    echo "DELETE FROM $sd WHERE $PKmodelo=$id_recibido;";
    $consulta = "DELETE FROM $sd WHERE $PKmodelo=$id_recibido";
    
    $resultado = mysqli_query($conexionDB,$consulta);

    header('Location: '.$sd.'.php');
?>