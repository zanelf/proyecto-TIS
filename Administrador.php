<?php
    include('conexion.php');
    $consulta = "SELECT * FROM administrador";
    $resultado = mysqli_query($conexionDB,$consulta);
    $atributos = mysqli_query($conexionDB,"DESCRIBE administrador");

    session_start();

    if(!isset($_SESSION["usuario"])){
        header("Location: Login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <title>Document</title>
</head>

<body>
    <?php
        include('navbar1.php'); // barra superiror autenticada
        echo "Bienvenido------ ".$_SESSION["usuario"];
        echo "Bienvenido------ ".$_SESSION["tipo"];
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

    <div class="container">

        <div class="row">
            <div class="col-6">
                <h3>Formulario</h3>
                <?php
                    $Tipo_modelo = "administrador";
                    include('Formulario2.php');
                ?>
            </div>
            <div class="col-6">
                <?php
                    $Tipo_modelo = "usuario";
                    include('Tabla.php');
                ?>
            </div>
        </div>
    </div>
    
</body>
</html>