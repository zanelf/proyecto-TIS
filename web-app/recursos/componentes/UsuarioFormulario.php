<?php
    include('conexion.php');

    $modelo = $Tipo_modelo;
    echo $modelo;

    echo '<form action="Insertar'.$modelo.'.php" method="POST">';
        echo '<label class="form-label">USUARIO</label>';
        echo '<input type="text" name="nombre_usuario" class="form-control">';
        echo '<label class="form-label">CONTRASEÑA</label>';
        echo '<input type="text" name="contraseña" class="form-control">';
        echo '<select id="tipo" name="tipo">';
            echo '<option value="Director">Director</option>';
            echo '<option value="Administrador">Administrador</option>';
            echo '<option value="Funcionario">Funcionario</option>';
        echo '</select>';
        echo '<input type="submit" class="btn btn-success mt-4 w-100">';
    echo '</form>';
?>