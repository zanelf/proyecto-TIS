

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../recursos/css/style_navbar1.css">
</head>
<body>
    
<?php


$opciones = ["index" => "index",'ciudadano' => "Ciudadanos",'solicitud' => "Solicitudes", "tipo_solicitud" => "Tipos de solicitud","usuario" => "Usuarios", "departamento" => "Departamentos","../../consultas/logout" => "Cerrar sesion"];
$opcionesAdm = ["../index" => "index",'ciudadano' => "Ciudadanos",'solicitud' => "Solicitudes", "../ventanas/departamento" => "Departamentos", "usuario" => "Usuarios","../consultas/logout" => "Cerrar sesion"];
$opcionesFun = ["index" => "index",'ciudadano' => "Ciudadanos",'solicitud' => "Solicitudes"];
$opcionesDir = ["index" => "index",'ciudadano' => "Ciudadanos",'solicitud' => "Solicitudes"];
$opcionesDev = ["index" => "index",'ciudadano' => "Ciudadanos",'solicitud' => "Solicitudes"];
$tipoUsuario = "";

if(isset($_SESSION["tipo"])){
    $tipoUsuario = $_SESSION["tipo"];
}

echo '<nav class="navbar navbar-expand-lg bg-body-tertiary mb-4 shadow">';
    echo '<div class="container-fluid">';
        
        if($tipoUsuario=="Administrador"){
            foreach($opcionesAdm as $opcion => $label){
                echo '<a href="'.$opcion.'.php">';
                    echo '<button type="button">'.$label.'</button>';
                echo '</a>';
            }
        }
        if($tipoUsuario=="Funcionario"){
            foreach($opcionesAdm as $opcion => $label){
                echo '<a href="'.$opcion.'.php">';
                    echo '<button type="button">'.$label.'</button>';
                echo '</a>';
            }
        }
        if($tipoUsuario=="Director"){
            foreach($opcionesAdm as $opcion => $label){
                echo '<a href="'.$opcion.'.php">';
                    echo '<button type="button">'.$label.'</button>';
                echo '</a>';
            }
        }
    echo '</div>';

        echo '<a href="Encuesta.php">';
        echo '<button type="encuesta">encuesta</button>';
        echo '</a>';
        echo '<a href="Administrador.php">';
        echo '<button type="Administrador">Administrador</button>';
        echo '</a>';
    
echo '</nav>';
?>
</body>
</html>
