<?php
    include('conexion.php');

    $usuario=$_POST["usuario"];
    $contraseña=$_POST["contraseña"];

    $consulta = "SELECT * FROM usuario WHERE nombre_usuario='$usuario' and contraseña='$contraseña'";
    $resultado = mysqli_query($conexionDB,$consulta);

    if(mysqli_num_rows($resultado) > 0){
        session_start();

        $datosUsuario = mysqli_fetch_assoc($resultado);

        $_SESSION["usuario"] = $datosUsuario["nombre_usuario"];
        $_SESSION["id_usuario"] = $datosUsuario["id_usuario"];

        header('Location: usuario.php');
        exit;
    }else{
        header('Location: Login.php');
    }
?>