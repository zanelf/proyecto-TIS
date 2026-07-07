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
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/style_mantenedores.css">
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
                <div class="card shadow-sm border" style="border-radius: 8px; overflow: hidden;">
                    <div class="card-header custom-card-header py-3">
                        <h6 class="mb-0 fw-bold text-primary text-uppercase small tracking-wider">Nuevo Tipo de Solicitud</h6>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <?php
                            $Tipo_modelo = "tipo_solicitud";
                            include("../recursos/componentes/Formulario2.php");
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border" style="border-radius: 8px; overflow: hidden;">
                    <div class="card-header custom-card-header-table py-3">
                        <h6 class="mb-0 fw-bold text-secondary text-uppercase small tracking-wider">Registros Actuales</h6>
                    </div>
                    <div class="card-body p-0"> <div class="table-responsive">
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