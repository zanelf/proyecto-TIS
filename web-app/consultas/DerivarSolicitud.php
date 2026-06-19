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
                    $Tipo_modelo = "departamento";
                    include('../recursos/componentes/Tabla.php');
                ?>
            </div>
        </div>
    </div>
</body>
</html>