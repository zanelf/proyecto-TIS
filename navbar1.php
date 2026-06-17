<?php
$opciones = ["index" => "index",'ciudadano' => "Ciudadanos",'solicitud' => "Solicitudes", "tipo_solicitud" => "Tipos de solicitud","usuario" => "Usuarios", "departamento" => "Departamentos","logout" => "Cerrar sesion"];
$opcionesAdm = ["index" => "index",'ciudadano' => "Ciudadanos",'solicitud' => "Solicitudes", "departamento" => "Departamentos", "usuario" => "Usuarios","logout" => "Cerrar sesion"];
$opcionesFun = ["index" => "index",'ciudadano' => "Ciudadanos",'solicitud' => "Solicitudes"];
$opcionesDir = ["index" => "index",'ciudadano' => "Ciudadanos",'solicitud' => "Solicitudes"];
$opcionesDev = ["index" => "index",'ciudadano' => "Ciudadanos",'solicitud' => "Solicitudes"];

echo '<nav class="navbar navbar-expand-lg bg-body-tertiary mb-4 shadow">';
    echo '<div class="container-fluid">';
        
        if($_SESSION["tipo"]=="Administrador"){
            foreach($opcionesAdm as $opcion => $label){
                echo '<a href="'.$opcion.'.php">';
                    echo '<button type="button">'.$label.'</button>';
                echo '</a>';
            }
        }
        if($_SESSION["tipo"]=="Funcionario"){
            foreach($opcionesAdm as $opcion => $label){
                echo '<a href="'.$opcion.'.php">';
                    echo '<button type="button">'.$label.'</button>';
                echo '</a>';
            }
        }
        if($_SESSION["tipo"]=="Director"){
            foreach($opcionesAdm as $opcion => $label){
                echo '<a href="'.$opcion.'.php">';
                    echo '<button type="button">'.$label.'</button>';
                echo '</a>';
            }
        }
    echo '</div>';

        echo '<a href="encuesta.php">';
        echo '<button type="encuesta">encuesta</button>';
        echo '</a>';
        echo '<a href="administrador.php">';
        echo '<button type="administrador">administrador</button>';
        echo '</a>';
    
echo '</nav>';
?>