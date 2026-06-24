<?php
    include('../base_de_datos/conexion.php');
    session_start();
    if(!isset($_SESSION["usuario"])){
        header("Location: Login.php");
        exit;
    }

    $consulta = "SHOW TABLES FROM proyectodb;";
    $consulta2 = '';
    $tablasDB = mysqli_query($conexionDB,$consulta);

    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        include('../recursos/componentes/navbar1.php');
    ?>
    <h1>Tablas de la DB</h1>
    <?php
        while($tablas = mysqli_fetch_assoc($tablasDB)){ 
            $consulta2 = "SELECT * FROM ".$tablas["Tables_in_proyectodb"];
            $resultado = mysqli_query($conexionDB,$consulta2);
            $Tipo_modelo = "".$tablas["Tables_in_proyectodb"];
            echo "<h4>------------------------------------------</h4>";
            echo "<h4>".$tablas["Tables_in_proyectodb"]."</h4>";
            
            include('../recursos/componentes/Tabla.php');
        }
    ?>
    
    <div class="container">
        <div class="row">
            <div class="col-6">
            </div>
        </div>
    </div>
</body>
</html>