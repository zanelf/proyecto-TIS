<?php
    include('conexion.php');
    $modelo = $Tipo_modelo;
    $atributos = mysqli_query($conexionDB,"DESCRIBE ".$modelo);

    $campos=[];
    $PKmodelo = "";
    $PKValue="";
    while ($fila = mysqli_fetch_assoc($atributos)) {
        $campos[] = $fila;
        if($fila['Key']=="PRI"){
            echo "PRIMARIA".$fila['Field'];
            $PKmodelo=$fila['Field'];
        }
    }
    
    echo '<table class="table">';
        echo '<thead>';
            foreach($campos as $campo){
                echo '<th scope="col">'.strtolower(str_replace("_"," ",$campo['Field'])).'</th>';
            }            
        echo '</thead>';
        echo '<tbody>';
            while($row=mysqli_fetch_assoc($resultado)){
                echo "<tr>";
                    foreach($campos as $campo){
                        if($campo['Key']=="PRI"){
                            $PKValue=$row["".$campo['Field'].""];
                        }
                        $aux = $row["".$campo['Field'].""];
                        echo '<td>'.$aux.'</td>';
                    } 
                    echo '<td>
                            <a class="mx-2" href="eliminar.php?id_enviado='.$PKValue.'&tipomod='.$modelo.'">Eliminar</a>
                            <a href="editar.php?id_enviado='.$PKmodelo.'">Editar</a>
                            </td>';
                echo "</tr>";
            }
        echo '</tbody>';
    echo '</table>';

?>