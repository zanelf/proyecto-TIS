<?php
    include('conexion.php');
    
    $usuario=$_POST["usuario"];
    $contraseña=$_POST["contraseña"];
    
    $adm="SELECT * FROM administrador WHERE nombre_usuario='$usuario' and contraseña='$contraseña'";
    $func="SELECT * FROM funcionario WHERE nombre_usuario='$usuario' and contraseña='$contraseña'";
    $dir="SELECT * FROM director WHERE nombre_usuario='$usuario' and contraseña='$contraseña'";
    $dev="SELECT * FROM desarrollador WHERE nombre_usuario='$usuario' and contraseña='$contraseña'";


    $consulta = "SELECT * FROM usuario WHERE nombre_usuario='$usuario' and contraseña='$contraseña'";
    $resultado = mysqli_query($conexionDB,$consulta);

    if(mysqli_num_rows($resultado) > 0){
        session_start();

        $datosUsuario = mysqli_fetch_assoc($resultado);

        $_SESSION["usuario"] = $datosUsuario["nombre_usuario"];
        $_SESSION["ID_usuario"] = $datosUsuario["ID_usuario"];

        $var = $_SESSION["ID_usuario"];

        $adm="SELECT * FROM administrador WHERE ID_usuario='$var';";
        $esAdm=mysqli_query($conexionDB,$adm);

        $func=mysqli_query($conexionDB,"SELECT * FROM funcionario WHERE ID_usuario='$var';");
        $esFun=mysqli_fetch_assoc($func);

        $dir="SELECT * FROM director WHERE ID_usuario='$var';";
        $esDir=mysqli_query($conexionDB,$dir);

        $dev="SELECT * FROM desarrollador WHERE ID_usuario='$var';";
        $esDev=mysqli_query($conexionDB,$dev);
        

        if(mysqli_num_rows($esAdm) > 0){
            $_SESSION["tipo"] = "Administrador";
        }
        if(mysqli_num_rows($func) > 0){
            $_SESSION["tipo"] = "Funcionario";
        }
        if(mysqli_num_rows($esDir) > 0){
            $_SESSION["tipo"] = "Director";
        }
        if(mysqli_num_rows($esDev) > 0){
            $_SESSION["tipo"] = "Desarrollador";
        }
        header('Location: usuario.php');
        exit;
    }else{
        header('Location: Login.php');
    }
?>