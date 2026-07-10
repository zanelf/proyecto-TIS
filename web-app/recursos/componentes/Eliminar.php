<?php
    include_once('../../base_de_datos/conexion.php');

    $id_recibido = $_GET["id_enviado"];
    $sd = $_GET["tipomod"]; 

    $atributos = mysqli_query($conexionDB, "DESCRIBE ".$sd);

    $campos = [];
    $PKmodelo = "";

    while ($fila = mysqli_fetch_assoc($atributos)) {
        $campos[] = $fila;
        if($fila['Key'] == "PRI"){
            $PKmodelo = $fila['Field'];
        }
    }
    
    $consulta = "DELETE FROM $sd WHERE $PKmodelo = $id_recibido";
    $resultado = mysqli_query($conexionDB, $consulta);

   
    if (!$resultado) {
        $codigo_error = mysqli_errno($conexionDB);
        
       
        if ($codigo_error == 1451) {
            echo "<script>
                    alert('No se puede eliminar este registro porque está siendo utilizado en otra sección del sistema (Clave Foránea).');
                    window.location.href = '../../ventanas/" . $sd . ".php';
                  </script>";
            exit;
        } else {
      
            echo "Error al eliminar: " . mysqli_error($conexionDB);
            exit;
        }
    }

    header("Location: ../../ventanas/" . $sd . ".php");
    exit;
?>