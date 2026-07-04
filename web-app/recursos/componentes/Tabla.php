<?php
    include('../base_de_datos/conexion.php');

    $modelo = $Tipo_modelo;
    $atributos = mysqli_query($conexionDB,"DESCRIBE ".$modelo);

    $campos=[];
    $PKmodelo = "";
    $PKValue="";

    $tipoUsuario=$_SESSION["tipo"];

    while ($fila = mysqli_fetch_assoc($atributos)) {
        $campos[] = $fila;
        if($fila['Key']=="PRI"){
            //echo "PRIMARIA".$fila['Field'];
            $PKmodelo=$fila['Field'];
        }
    }

    $resultado = mysqli_query($conexionDB,"SELECT * FROM ".$modelo);
    //echo __FILE__;
    //echo __DIR__;
    //echo $_SERVER['SCRIPT_FILENAME'];
    $archivos = scandir(__DIR__ . '/../../');

   // echo '<pre>';
    //print_r($archivos);
    //echo '</pre>';

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
<<<<<<< HEAD
                    echo '<td>
                            <a class="mx-2" href="Eliminar.php?id_enviado='.$PKValue.'&tipomod='.$modelo.'">Eliminar</a>
                            
                            <a href="editar.php?id_enviado='.$PKmodelo.'">Editar</a>
                            </td>';
=======
                    echo '<td>';
                    if(($tipoUsuario == "Administrador" and $modelo=="solicitud") or ($tipoUsuario == "Desarrollador" and $modelo=="solicitud")){
                        echo '<a class="mx-2" href="eliminar.php?id_enviado='.$PKValue.'&tipomod='.$modelo.'">Eliminar</a>';
                        echo '<a href="editar.php?id_enviado='.$PKmodelo.'">Editar(NI)</a>';
                        echo '<a class="mx-2" href="../consultas/ResponderSolicitud.php?id_enviado='.$PKValue.'&tipomod='.$modelo.'">RESPONDER</a>';
                        echo '<a class="mx-2" href="../consultas/ResponderSolicitud.php?id_enviado='.$PKValue.'&tipomod='.$modelo.'">Derivar</a>'; 
                    }
                    if($tipoUsuario == "Desarrollador"){
                        echo '<a class="mx-2" href="eliminar.php?id_enviado='.$PKValue.'&tipomod='.$modelo.'">Eliminar</a>';
                        echo '<a href="editar.php?id_enviado='.$PKmodelo.'">Editar(NI)</a>';
                    }
                    if($tipoUsuario == "Funcionario" and $modelo=="solicitud"){
                        echo '<a class="mx-2" href="../consultas/ResponderSolicitud.php?id_enviado='.$PKValue.'&tipomod='.$modelo.'">RESPONDER</a>'; 
                    }

                    echo '</td>';
                    
>>>>>>> leonardo
                echo "</tr>";
            }
        echo '</tbody>';
    echo '</table>';

?>