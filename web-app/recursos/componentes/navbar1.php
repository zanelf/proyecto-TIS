<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tipoUsuario = isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "";

// Opciones de menú
$opcionesAdm = [
    "../index" => "Inicio",
    "ciudadano" => "Ciudadanos",
    "solicitud" => "Solicitudes",
    "departamento" => "Departamentos",
    "usuario" => "Usuarios",
    "tipo_solicitud" => "Tipos de solicitudes",
    "metricas" => "Métricas",
    "../consultas/logout" => "Cerrar Sesión"
];

$opcionesFun = [
    "../index" => "Inicio",
    "ciudadano" => "Ciudadanos",
    "solicitud" => "Solicitudes",
    "../consultas/logout" => "Cerrar Sesión"
];

$opcionesDir = [
    "../index" => "Inicio",
    "ciudadano" => "Ciudadanos",
    "solicitud" => "Solicitudes",
    "../consultas/logout" => "Cerrar Sesión"
];

// Seleccionar el arreglo de opciones correcto
$opcionesActuales = [];
if($tipoUsuario == "Administrador") {
    $opcionesActuales = $opcionesAdm;
} elseif ($tipoUsuario == "Funcionario") {
    $opcionesActuales = $opcionesFun;
} elseif ($tipoUsuario == "Director") {
    $opcionesActuales = $opcionesDir;
}
?>

<link rel="stylesheet" href="../recursos/css/style_index.css?v=<?php echo time(); ?>">

<nav class="navbar navbar-expand-lg navbar-municipalidad py-3 shadow-sm mb-4">
    <div class="container">
        
        <a href="../index.php" class="navbar-brand-text text-uppercase text-decoration-none">SGISC</a>

        <div class="d-flex flex-wrap align-items-center gap-2">
            
            <?php if (isset($_SESSION["usuario"])): ?>
                <span class="texto-bienvenida">
                    Bienvenido, <?php echo htmlspecialchars($_SESSION["usuario"]); ?>
                    <?php if ($tipoUsuario): ?>
                        (<?php echo htmlspecialchars($tipoUsuario); ?>)
                    <?php endif; ?>
                </span>
            <?php endif; ?>

            <?php foreach ($opcionesActuales as $opcion => $label): ?>
                <?php 
                    $nombreOpcion = basename($opcion); 
                    $nombrePaginaActual = basename($_SERVER['PHP_SELF'], ".php"); 
                    
                    $claseExtra = (str_contains($opcion, 'logout')) ? 'btn-cerrar' : '';
                    
                    if ($nombreOpcion === $nombrePaginaActual && !str_contains($opcion, 'logout')) {
                        $claseExtra .= ' btn-activo';
                    }
                ?>
                <a href="<?php echo $opcion; ?>.php" class="btn-acceso <?php echo trim($claseExtra); ?>">
                    <?php echo $label; ?>
                </a>
            <?php endforeach; ?>

        </div>
    </div>
</nav>