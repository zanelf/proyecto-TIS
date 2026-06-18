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
        header('Location: /xampp/php/funciones/AsignarDpto.php');
    }
    if($tipo=="Funcionario"){
        $consulta = "INSERT INTO Funcionario (ID_usuario)  VALUES ('$id')";
        $resultado = mysqli_query($conexionDB,$consulta);
        echo '<form action="/xampp/php/funciones/AsignarDpto.php" method="POST">';
            echo '<label class=form-label>Departamento del funcionario</label>';
            echo '<input type="number" name="departamento" class="form-control">';
            echo '<label class=form-label>USUARIO: '.$id.'</label>';
            echo '<input type="hidden" name="usuario" value="'.$id.'" class="form-control" >';
            echo '<label class=form-label>TIPO: '.$tipo.'</label>';
            echo '<input type="hidden" name="tipo" value="Funcionario" class="form-control" >';
            echo '<input type="submit" class="btn btn-success mt-4 w-100">';
        echo '</form>';
        //header('Location: /xampp/php/funciones/AsignarDpto.php');
    }
    if($tipo=="Director"){
        $consulta = "INSERT INTO director (ID_usuario)  VALUES ('$id')";
        $resultado = mysqli_query($conexionDB,$consulta);
        echo '<form action="/xampp/php/funciones/AsignarDpto.php" method="POST">';
            echo '<label class=form-label>Departamento del funcionario</label>';
            echo '<input type="number" name="departamento" class="form-control">';
            echo '<label class=form-label>USUARIO: '.$id.'</label>';
            echo '<input type="hidden" name="usuario" value="'.$id.'" class="form-control" >';
            echo '<label class=form-label>TIPO: '.$tipo.'</label>';
            echo '<input type="hidden" name="tipo" value="Funcionario" class="form-control" >';
            echo '<input type="submit" class="btn btn-success mt-4 w-100">';
        echo '</form>';
        //header('Location: /xampp/php/funciones/AsignarDpto.php');
    }

    
?>