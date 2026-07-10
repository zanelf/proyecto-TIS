<?php
    session_start();
    include('../base_de_datos/conexion.php');
    $id_solicitud = $_GET['id_enviado'];

    $consulta = "SELECT * FROM solicitud WHERE solicitud_ID='$id_solicitud'";
    $resultado = mysqli_query($conexionDB,$consulta);
    $solicutdRevisar =  mysqli_fetch_assoc($resultado);

    if(!isset($_SESSION["usuario"])){
        header("Location: ../index.php");
        exit;
    }



    $prioridadesQuery = "SELECT * FROM prioridad";
    $prioridades = mysqli_query($conexionDB, $prioridadesQuery);
    $dpto=$_SESSION['ID_departamento'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../recursos/css/style_index.css">
</head>
<body>
    <?php
        include('../recursos/componentes/navbar1.php');
    ?>
    <div class="container">
        <div class="row">
            <h2>Solicitud a revisar</h2>
            <div class="col-6">
                <table>
                    <thead>
                        <th>ID</th>
                        <th>Estado</th>
                        <th>Asunto</th>
                        <th>Descripcion</th>
                        <th>Categoria</th>
                        <th>Rut del ciudadano</th>
                        <th>Departamento</th>
                        <th>Tipo de solicitud</th>
                    </thead>
                    <tbody>
                        <td><?php echo $solicutdRevisar['solicitud_ID']; ?></td>
                        <td><?php echo $solicutdRevisar['Tipo_estado']; ?></td>
                        <td><?php echo $solicutdRevisar['Asunto']; ?></td>
                        <td><?php echo $solicutdRevisar['Descripcion']; ?></td>
                        <td><?php echo $solicutdRevisar['Categoria']; ?></td>
                        <td><?php echo $solicutdRevisar['correo_electronico']; ?></td>
                        <td><?php echo $solicutdRevisar['ID_tipo_solicitud']; ?></td>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <h2>Revision</h2>
            <div class="col-6">
                <form action="../consultas/CambiarEstadoSol.php" method="POST">
                    <label class="form-check-label" for="Aprobacion"> Aprobar</label>
                    <input type="checkbox" name="Aprobracion" class="form-check-input" checked /><br>
                    <label class="form-label">Acotacion</label>
                    <input type="text" name="Acotacion" class="form-control">
                    <label class="form-label">Seleccionar prioridad</label>
                    <select name="ID_prioridad" class="form-select">
                        <?php while($prioridad = mysqli_fetch_assoc($prioridades)) { ?>
                            <option value="<?php echo $prioridad['ID_prioridad']; ?>">
                                <?php echo $prioridad['Nombre']; ?>
                            </option>
                        <?php } ?>
                    </select>                             
                    <input type="hidden" value="<?php echo $solicutdRevisar['ID_departamento'] ?>" name="ID_departamento">
                    <input type="hidden" value="<?php echo $solicutdRevisar['ID_tipo_solicitud'] ?>" name="ID_tipo_solicitud">
                    <input type="hidden" value="<?php echo $id_solicitud ?>" name="ID_cambio">
                    <input type="hidden" value="Derivada" name="estadoSiguiente">
                    <input type="submit" class="btn bg-success">
                </form>
            </div>
        </div>
    </div>

    <?php include('../recursos/componentes/footer.php'); ?>
</body>
</html>