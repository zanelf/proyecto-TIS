<?php
    include('../base_de_datos/conexion.php');
    
    $usuario = mysqli_real_escape_string($conexionDB, $_POST["usuario"]);
    $password = $_POST["password"];
    

    $consulta = "SELECT * FROM usuario WHERE rut_usuario='$usuario' LIMIT 1";
    $resultado = mysqli_query($conexionDB, $consulta);

    if(mysqli_num_rows($resultado) > 0){
        $datosUsuario = mysqli_fetch_assoc($resultado);

        if ((int)$datosUsuario["Activo"] === 0) {
            header('Location: ../ventanas/Inicio.php?error=inactivo');
            exit;
        }

        if (password_verify($password, $datosUsuario["contraseña"])) {
            
            session_start();
            $_SESSION["usuario"] = $datosUsuario["rut_usuario"];
            $_SESSION["ID_usuario"] = $datosUsuario["ID_usuario"];
            
            $var = $_SESSION["ID_usuario"];


            $adm = "SELECT * FROM administrador WHERE ID_usuario='$var';";
            $esAdm = mysqli_query($conexionDB, $adm);

            $func = "SELECT * FROM funcionario WHERE ID_usuario='$var';";
            $esFun = mysqli_query($conexionDB, $func);

            $dir = "SELECT * FROM director WHERE ID_usuario='$var';";
            $esDir = mysqli_query($conexionDB, $dir);

            $dev = "SELECT * FROM desarrollador WHERE ID_usuario='$var';";
            $esDev = mysqli_query($conexionDB, $dev);
            

            if(mysqli_num_rows($esAdm) > 0){
                $_SESSION["ID_departamento"] = "";
                $_SESSION["tipo"] = "Administrador";
            }
            elseif(mysqli_num_rows($esFun) > 0){ 
                $consulta="SELECT ID_departamento FROM Funcionario WHERE ID_usuario = '$var'; ";
                $_SESSION["ID_departamento"] = mysqli_fetch_assoc(mysqli_query($conexionDB,$consulta))['ID_departamento'];
                $_SESSION["tipo"] = "Funcionario";
            }
            elseif(mysqli_num_rows($esDir) > 0){
                $_SESSION["tipo"] = "Director";
            }
            elseif(mysqli_num_rows($esDev) > 0){
                
                $_SESSION["tipo"] = "Desarrollador";
            }

            if (isset($_SESSION["tipo"])) {
                switch ($_SESSION["tipo"]) {
                    case "Administrador":
                        header('Location: ../ventanas/Usuario.php');
                        break;
                    case "Director":
                        header('Location: ../ventanas/director.php');
                        break;
                    case "Funcionario":
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