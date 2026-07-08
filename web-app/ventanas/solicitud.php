<?php
    session_start();
    include('../base_de_datos/conexion.php');
    $consulta = "SELECT * FROM solicitud";
    
    $resultado = mysqli_query($conexionDB,$consulta);
    $atributos = mysqli_query($conexionDB,"DESCRIBE solicitud");

    $tipos_solicitudes=mysqli_query($conexionDB,"SELECT * FROM tipo_solicitud");
    $tipo_sol=mysqli_fetch_assoc($tipos_solicitudes);
    $departamentos=mysqli_query($conexionDB,"SELECT * FROM departamento");



    if(!isset($_SESSION["usuario"])){
        header("Location: ../index.php");
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
        include('../recursos/componentes/navbar1.php');
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
            if(str_contains($campo['Type'],"varchar")){
                $campo['Type'] = "date";
            }
        }
        unset($campo);
        foreach($campos as $campo){
            //echo $campo['Type']."<br>";
        }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <form method="GET">
                <input
                    type="text"
                    name="buscar"
                    placeholder="Buscar trabajador..."
                    value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>"
                >
                <button type="submit">Buscar</button>
            </form>
            <button>MI DEPARTAMENTO</button>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?php
                    $Tipo_modelo = "solicitud";
                    include('../recursos/componentes/Tabla.php');
                ?>
            </div>
        </div>
    </div>
</body>
</html>