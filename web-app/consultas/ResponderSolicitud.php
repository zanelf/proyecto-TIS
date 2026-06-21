<?php
    include('../base_de_datos/conexion.php');

    $atributos = mysqli_query($conexionDB,"DESCRIBE ciudadano");
    session_start();

$ID_solicitud = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $ID_solicitud = $_GET['id_enviado'];

    $consulta = "SELECT * FROM solicitud
                 WHERE solicitud_ID='$ID_solicitud'";
    $resultado = mysqli_query($conexionDB, $consulta);
}
else {
    $ID_solicitud = $_POST['solicitud_ID'];
    $Respuesta = $_POST['Respuesta'];

    $consulta = "UPDATE solicitud
                 SET descripcion='$Respuesta'
                 WHERE solicitud_ID='$ID_solicitud'";
    $Actualizacion="UPDATE solicitud
                 SET Tipo_estado='Respondida'
                 WHERE solicitud_ID='$ID_solicitud'";

    mysqli_query($conexionDB, $consulta);
    mysqli_query($conexionDB, $Actualizacion);
    header('Location: ../ventanas/solicitud.php');
}



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
        <?php
                include('../recursos/componentes/navbar1.php'); // barra superiror autenticada
            ?>
        <div class="row">
            
            <div class="col-6">
                <?php
                    $solicitud=mysqli_fetch_assoc($resultado);
                    echo '<h3> Descripcion de la solicitud </h3>';
                    echo $solicitud["ID_tipo_solicitud"]."-\n-";
                    echo "Descripcion: ".$solicitud["Descripcion"];
                ?>
            </div>
            <div class="col-6">
                <h3> Respuesta </h3>
                <form action="ResponderSolicitud.php" method="POST">
                    <label class="form-label">Ingrese respuesta a la solicitud</label>
                    <input type="text" name="Respuesta" class="form-control">
                    <input type="hidden" name="solicitud_ID" class="form-control" value="<?php echo $ID_solicitud; ?>">
                    <input type="submit">
                </form>
            </div>
        </div>
    </div>
</body>
</html>