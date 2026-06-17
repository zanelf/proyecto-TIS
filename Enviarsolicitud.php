<?php
    include('conexion.php');
    $consulta = "SELECT * FROM solicitud";
    $resultado = mysqli_query($conexionDB,$consulta);
    $atributos = mysqli_query($conexionDB,"DESCRIBE solicitud");
    $tipos_solicitudes=mysqli_query($conexionDB,"SELECT * FROM tipo_solicitud");
    $tipo_sol=mysqli_fetch_assoc($tipos_solicitudes);
    $departamentos=mysqli_query($conexionDB,"SELECT * FROM departamento");
    session_start();
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
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-4 shadow">

    <a href="index.php">
        <button type="button">INDEX</button>
    </a>

    <?php if (isset($_SESSION["usuario"])): ?>

        <span class="mx-3">
            Bienvenido, <?php echo $_SESSION["usuario"]; ?>
        </span>

        <a href="departamento.php">
            <button type="button">departamento</button>
        </a>
        <a href="ciudadano.php">
            <button type="button">ciudadano</button>
        </a>
        <a href="tipo_soliciutd.php">
            <button type="button">tipo soliciutd</button>
        </a>
        <a href="logout.php">
            <button type="button">Cerrar Sesión</button>
        </a>

    <?php else: ?>

        <a href="Login.php">
            <button type="button">LOGIN</button>
        </a>
    <?php endif; ?>

</nav>
    <?php
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

        <div class="row">
            <div class="col-8">
                <h3>Formulario</h3>
                <form action="">
                    <label class="form-label">correo electronico</label>
                    <input type="text" name="" class="form-control">
                    
                    <label class="form-label">Tipo solicitud</label>
                    <?php
                        echo '<select id="tipo" name="tipo">';
                        while($tipo_sol = mysqli_fetch_assoc($tipos_solicitudes)){
                            echo '<option value="'.$tipo_sol["ID_tipo_solicitud"].'">'.$tipo_sol["nombre"].'</option>';
                        }
                        echo '</select>';
                    ?>
                    <label class="form-label">Departamento destino</label>
                    <?php
                        echo '<select id="tipo" name="tipo">';
                        while($dpto = mysqli_fetch_assoc($departamentos)){
                            echo '<option value="'.$dpto["ID_departamento"].'">'.$dpto["nombre"].'</option>';
                        }
                        echo '</select>';
                    ?>
                    <label class="form-label">Categoria</label>
                    <select id="tipo" name="tipo">
                        <option value="Categoria1">Categoria1</option>
                        <option value="Categoria2">Categoria2</option>
                        <option value="Categoria3">Categoria3</option>
                    </select>

                    <label class="form-label">Asunto</label>
                    <input type="text" name="" class="form-control">
                    <label class="form-label">Descripcion</label>
                    <input type="text" name="" class="form-control">
                    <label class="form-label">Archivo</label>
                    <input type="file" id="archivo" name="archivo" class="form-control">

                    <input type="submit" class="btn btn-success mt-4 w-100">
                    <a href="solicitud.php">;
                        <button type="button">Volver</button>;
                    </a>;
                </form>
            </div>
        </div>
    </div>
</body>
</html>