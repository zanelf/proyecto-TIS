<?php
$tipoUsuario = "";
$usuario = "";

if(isset($_SESSION["tipo"])){
    $tipoUsuario = $_SESSION["tipo"];
}

if(isset($_SESSION["usuario"])){
    $usuario = $_SESSION["usuario"];
}

$opcionesAdm = [
    "../index" => "Inicio",
    "solicitud" => "Solicitudes",
    "ciudadano" => "Ciudadanos",
    "departamento" => "Departamentos",
    "tipo_solicitud" => "Tipos de solicitud",
    "Usuario" => "Usuarios",
    "Encuesta" => "Encuesta",
    "Administrador" => "Administrador"
];

$opcionesFun = [
    "../index" => "Inicio",
    "../ventanas/solicitud" => "Solicitudes",
    "../ventanas/ciudadano" => "Ciudadanos"
];

$opcionesDir = [
    "../index" => "Inicio",
    "solicitud" => "Solicitudes",
    "departamento" => "Departamentos"
];

$opcionesDev = [
    "../index" => "Inicio",
    "solicitud" => "Solicitudes",
    "ciudadano" => "Ciudadanos",
    "departamento" => "Departamentos",
    "tipo_solicitud" => "Tipos de solicitud",
    "Usuario" => "Usuarios"
];

$opcionesMenu = [];

if($tipoUsuario=="Administrador"){
    $opcionesMenu = $opcionesAdm;
}
if($tipoUsuario=="Funcionario"){
    $opcionesMenu = $opcionesFun;
}
if($tipoUsuario=="Director"){
    $opcionesMenu = $opcionesDir;
}
if($tipoUsuario=="Desarrollador"){
    $opcionesMenu = $opcionesDev;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <nav class="navbar-interna shadow-sm">
    <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center gap-3">
        <a href="../index.php" class="marca-sgisc">SGISC</a>

        <div class="d-flex flex-wrap align-items-center justify-content-end gap-2">
            <?php if($usuario!=""): ?>
                <span class="texto-usuario">
                    <?php echo $usuario; ?>
                    <?php if($tipoUsuario!=""): ?>
                        (<?php echo $tipoUsuario; ?>)
                    <?php endif; ?>
                </span>
            <?php endif; ?>

            <?php foreach($opcionesMenu as $opcion => $label): ?>
                <a href="<?php echo $opcion; ?>.php" class="btn-nav"><?php echo $label; ?></a>
            <?php endforeach; ?>

            <a href="../consultas/logout.php" class="btn-nav btn-salir">Cerrar sesion</a>
        </div>
    </div>
</nav>    
</body>
</html>
