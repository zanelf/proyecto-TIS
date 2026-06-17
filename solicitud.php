<?php
    include('conexion.php');
    $consulta = "SELECT * FROM solicitud";
    $resultado = mysqli_query($conexionDB,$consulta);
    $atributos = mysqli_query($conexionDB,"DESCRIBE solicitud");
    $tipos_solicitudes=mysqli_query($conexionDB,"SELECT * FROM tipo_solicitud");
    $tipo_sol=mysqli_fetch_assoc($tipos_solicitudes);
    $departamentos=mysqli_query($conexionDB,"SELECT * FROM departamento");
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
    <title>Document</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <?php
        include('navbar1.php');
        $campos=[];
        while ($fila = mysqli_fetch_assoc($atributos)) {
            $campos[] = $fila;
        }
        foreach($campos as &$campo){
            if(str_contains($campo['Type'],"int")){
                $campo['Type'] = "number";
            }
            if(str_contains($campo['Type'],"varchar")){
                $campo['Type'] = "text";
            }
        }
        unset($campo);
        foreach($campos as $campo){
            //echo $campo['Type']."<br>";
        }
    ?>
    <div class="container">
        <a href="Enviarsolicitud.php">
                    <button type="button">Crear solicitud</button>
                </a>
        <div class="row">
            <div class="col-6">
                <?php
                    $Tipo_modelo = "solicitud";
                    include('Tabla.php');
                ?>
            </div>
        </div>
    </div>
</body>
</html>