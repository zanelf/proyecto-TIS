<?php
    include('../base_de_datos/conexion.php');



    $modelo = $Tipo_modelo;
    echo $modelo;
    $atributos = mysqli_query($conexionDB,"DESCRIBE ".$modelo);

    $campos=[];

    while ($fila = mysqli_fetch_assoc($atributos)) {
        $campos[] = $fila;
    }
    foreach($campos as &$campo){
        // Verificamos que 'Type' exista y no sea nulo antes de evaluar
        if(isset($campo['Type']) && $campo['Type'] !== null) {
            if(str_contains((string)$campo['Type'], "int")){
                $campo['Type'] = "number";
            }
            if(str_contains((string)$campo['Type'], "varchar")){
                $campo['Type'] = "text";
            }
        }
    }
    unset($campo);
    foreach($campos as $campo){
        //echo $campo['Type']."<br>";
    }
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