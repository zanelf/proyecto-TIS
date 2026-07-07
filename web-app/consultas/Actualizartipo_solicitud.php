<?php
// 1. Incluir la conexión a la base de datos (subiendo un nivel desde la carpeta consultas)
include_once('../base_de_datos/conexion.php');

// 2. Validar que todos los datos obligatorios hayan llegado de forma correcta mediante el método POST
if (isset($_POST['Id_tipo_solicitud']) && isset($_POST['Nombre']) && isset($_POST['Descripcion'])) {
    
    // Almacenar las variables y limpiarlas con mysqli_real_escape_string para evitar inyecciones SQL
    $id = mysqli_real_escape_string($conexionDB, $_POST['Id_tipo_solicitud']);
    $nombre = mysqli_real_escape_string($conexionDB, $_POST['Nombre']);
    $descripcion = mysqli_real_escape_string($conexionDB, $_POST['Descripcion']);

    // 3. Definir y ejecutar la sentencia SQL UPDATE
    $consulta = "UPDATE tipo_solicitud SET Nombre = '$nombre', Descripcion = '$descripcion' WHERE Id_tipo_solicitud = '$id'";
    $resultado = mysqli_query($conexionDB, $consulta);

    if ($resultado) {
        // Si la actualización es exitosa, redirige de vuelta a la vista de la tabla
        header("Location: ../ventanas/tipo_solicitud.php");
        exit;
    } else {
        // En caso de un fallo en la consulta, se muestra el error de MySQL
        echo "Error crítico al intentar actualizar el registro: " . mysqli_error($conexionDB);
    }
} else {
    // Mensaje de control por si se intenta acceder al archivo de forma directa o vacía
    echo "Error: No se recibieron los parámetros necesarios para procesar la actualización.";
}
?>