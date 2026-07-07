<?php
    include("../base_de_datos/conexion.php");
    $consulta = "SELECT * FROM tipo_solicitud";
    $resultado = mysqli_query($conexionDB,$consulta);
    $atributos = mysqli_query($conexionDB,"DESCRIBE tipo_solicitud");

    session_start();

    if(!isset($_SESSION["usuario"])){
        header("Location: Login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de Solicitudes - SGISC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
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
        }
        unset($campo);
    ?>
    
    <div class="container mt-5">
        <div class="row">
            
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Nuevo Tipo de Solicitud</h5>
                    </div>
                    <div class="card-body">
                        <?php
                            $Tipo_modelo = "tipo_solicitud";
                            include("../recursos/componentes/Formulario2.php");
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Registros Actuales</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php
                                include("../recursos/componentes/Tabla.php");
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>