<?php
    include('../base_de_datos/conexion.php');

    $ID_solicitud=$_GET["id_enviado"];

    $consulta = "SELECT * FROM departamento";

    $resultado = mysqli_query($conexionDB,$consulta);
    $atributos = mysqli_query($conexionDB,"DESCRIBE ciudadano");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <a href="../consultas/Enviarsolicitud.php">
                    <button type="button">Crear solicitud</button>
                </a>
        <div class="row">
            <div class="col-6">
                <?php
                    include('../recursos/componentes/navbar1.php'); // barra superiror autenticada
                    echo "Bienvenido------ ".$_SESSION["usuario"]."  ERES UN MALDITO".$_SESSION["tipo"];
                    $campos=[];
                    while ($fila = mysqli_fetch_assoc($atributos)) {
                        $campos[] = $fila; // No estoy tan seguro de esto o hacerlo directo, pero funca
                    }
                    foreach($campos as &$campo){ // Para pasar el tipo de dato al formulario. EJ: int(32)=number, el form de html pide number en Type=""
                        if(str_contains($campo['Type'],"int")){
                            $campo['Type'] = "number";
                        }
                        if(str_contains($campo['Type'],"varchar")){
                            $campo['Type'] = "text";
                        }
                    }
                    unset($campo);
                    foreach($campos as $campo){  // ESTO ERA PARA DEBUGEAR ALGO, PUEDE QUE SE USE PARA DEBUGEAR
                        //echo $campo['Type']."<br>";
                    }
                ?>
            </div>
            
            <div class="col-6">
                
                <?php
                    $Tipo_modelo = "departamento";
                    include('../recursos/componentes/Tabla.php');
                ?>
            </div>
        </div>
    </div>
</body>
</html>