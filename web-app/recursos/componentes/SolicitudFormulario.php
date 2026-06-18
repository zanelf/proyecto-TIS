<?php
    include('conexion.php');
    $tipo_estado=
    $asunto=
    $descripcion=
    $categoria=
    $rut_ciudadano
    $
    $

    

    $campos=['tipo_estado','asunto','descripcion','categoria','correo_electronico','ID_departamento','ID_tipo_solicitud'];

    echo ' - form sobre archivo: Insertar'.$modelo.'.ph';
    echo '<form action="Insertar'.$modelo.'.php" method="POST">';
    foreach($campos as $campo){
        if($campo['Key']!="PRI"){
            echo '<label class=form-label>'.strtolower(str_replace("_"," ",$campo['Field'])).'</label>';
        echo '<input type="'.$campo['Type'].'" name="'.$campo['Field'].'" class="form-control">';
        }
    }
    echo '<input type="submit" class="btn btn-success mt-4 w-100">';
    echo '</form>';
?>