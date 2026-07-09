<?php
    include('../base_de_datos/conexion.php');

    $correo = mysqli_real_escape_string($conexionDB, $_POST["correo"]);
    $Asunto = mysqli_real_escape_string($conexionDB, $_POST["asunto"]);
    $Descripcion = mysqli_real_escape_string($conexionDB, $_POST["descripcion"]);
    $ID_categoria = mysqli_real_escape_string($conexionDB, $_POST["categoria"]);
    $ID_tipo_solicitud = mysqli_real_escape_string($conexionDB, $_POST["tipo"]);

    $existe = mysqli_query($conexionDB, "SELECT correo_electronico FROM ciudadano WHERE correo_electronico = '$correo' LIMIT 1");
    if (mysqli_num_rows($existe) == 0) {
        $fila = mysqli_fetch_assoc(mysqli_query($conexionDB, "SELECT IFNULL(MAX(RUT_ciudadano),0)+1 AS nuevo FROM ciudadano"));
        $nuevoRut = $fila["nuevo"];
        if (!mysqli_query($conexionDB, "INSERT INTO ciudadano (RUT_ciudadano, correo_electronico) VALUES ($nuevoRut, '$correo')")) {
            echo "Error al registrar el correo del ciudadano: " . mysqli_error($conexionDB);
            exit;
        }
    }

    // El departamento se deriva de la categoría elegida, quitamos la selección del ciudadano
    $categoriaFila = mysqli_fetch_assoc(mysqli_query($conexionDB, "SELECT ID_departamento FROM categoria WHERE ID_categoria = '$ID_categoria'"));
    if (!$categoriaFila) {
        echo "Categoría no válida.";
        exit;
    }
    $ID_departamento = $categoriaFila["ID_departamento"];

    $consulta = "INSERT INTO solicitud (Asunto, Descripcion, ID_categoria, correo_electronico, ID_departamento, ID_tipo_solicitud, fecha_creacion)
                 VALUES ('$Asunto', '$Descripcion', '$ID_categoria', '$correo', '$ID_departamento', '$ID_tipo_solicitud', CURDATE())";

    if (!mysqli_query($conexionDB, $consulta)) {
        echo "Error al registrar la solicitud: " . mysqli_error($conexionDB);
        exit;
    }

    $ID_solicitud = mysqli_insert_id($conexionDB);

    mysqli_query($conexionDB, "INSERT INTO comprobante (Fecha, Hora, solicitud_ID) VALUES (CURDATE(), CURTIME(), $ID_solicitud)");

    header('Location: ../index.php');
    exit;
?>