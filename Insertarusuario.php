<?php
    include('conexion.php');

    $nombre_usuario=$_POST["nombre_usuario"];
    $contraseña=$_POST["contraseña"];
    $tipo=$_POST["tipo"];

    $consulta = "INSERT INTO usuario (nombre_usuario, contraseña)  VALUES ('$nombre_usuario','$contraseña')";
    $resultado = mysqli_query($conexionDB,$consulta);
    $consulta = "SELECT * FROM usuario WHERE nombre_usuario='$nombre_usuario' and contraseña='$contraseña'";
    $resultado = mysqli_query($conexionDB,$consulta);

    $usuario= mysqli_fetch_assoc($resultado);
    $id=$usuario["ID_usuario"];

    if($tipo=="Administrador"){
        $consulta = "INSERT INTO administrador (ID_usuario)  VALUES ('$id')";
        $resultado = mysqli_query($conexionDB,$consulta);
    }
    if($tipo=="Funcionario"){
        $consulta = "INSERT INTO Funcionario (ID_usuario)  VALUES ('$id')";
        $resultado = mysqli_query($conexionDB,$consulta);
    }
    if($tipo=="Director"){
        $consulta = "INSERT INTO director (ID_usuario)  VALUES ('$id')";
        $resultado = mysqli_query($conexionDB,$consulta);
    }

    

    header('Location: usuario.php');
?>