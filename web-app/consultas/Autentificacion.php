<?php
    include('../base_de_datos/conexion.php');
    
    $usuario = mysqli_real_escape_string($conexionDB, $_POST["usuario"]);
    $password = $_POST["password"];
    

    $consulta = "SELECT * FROM usuario WHERE rut_usuario='$usuario' LIMIT 1";
    $resultado = mysqli_query($conexionDB, $consulta);
    if(mysqli_num_rows($resultado) > 0){
        $datosUsuario = mysqli_fetch_assoc($resultado);

        if ((int)$datosUsuario["activo"] === 0) {
            header('Location: ../ventanas/Inicio.php?error=inactivo');
            exit;
        }

        if (password_verify($password, $datosUsuario["contraseña"])) {
            
            session_start();
            $_SESSION["usuario"] = $datosUsuario["rut_usuario"];
            $_SESSION["ID_usuario"] = $datosUsuario["ID_usuario"];
            
            $var = $_SESSION["ID_usuario"];
            
            $consulta="SELECT * FROM Trabajador WHERE ID_usuario = '$var'";
            $datosTrabajador=mysqli_fetch_assoc(mysqli_query($conexionDB,$consulta));
            
            $datoDpto= $datosTrabajador['ID_departamento'];
            $datoTipo= $datosTrabajador['tipo_trabajador'];

            if((int)$datosUsuario["is_admin"]==1){
                $_SESSION["ID_departamento"] = "";
                $_SESSION["tipo"] = "Administrador";
                $_SESSION["ID_departamento"] = $datoDpto;
            }elseif($datosTrabajador["tipo_trabajador"]==="funcionario"){ 
                $_SESSION["ID_departamento"] = mysqli_fetch_assoc(mysqli_query($conexionDB,$consulta))['ID_departamento'];
                $_SESSION["tipo"] = "funcionario";
            }
            elseif($datosTrabajador["tipo_trabajador"]==="director"){
                $_SESSION["tipo"] = "director";
                $_SESSION["ID_departamento"] = mysqli_fetch_assoc(mysqli_query($conexionDB,$consulta))['ID_departamento'];
            }

            if ($datoTipo) {
                switch ($datoTipo) {
                    case "director":
                        header('Location: ../ventanas/director.php');
                        break;
                    case "funcionario":
                        header('Location: ../ventanas/solicitud.php');
                        break;
                    default:
                        header('Location: ../ventanas/Inicio.php?error=RolInvalido');
                        break;
                }
                exit;
            }
            exit;

        } else {
            header('Location: ../ventanas/Inicio.php?error=clave');
            exit;
        }
    } else {
        header('Location: ../ventanas/Inicio.php?error=usuario');
        exit;
    }
?>