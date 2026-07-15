<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tipoUsuario = isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "";


$opcionesAdm = [
    "../index" => "Inicio",
    "solicitud" => "Solicitudes",
    "departamento" => "Departamentos",
    "usuario" => "Usuarios",
    "tipo_solicitud" => "Tipos de solicitudes",
    "prioridad" => "Prioridades",
    "tiempo" => "Tiempos SLA",
    "metricas" => "Métricas",
    "../consultas/logout" => "Cerrar Sesión"
];

$opcionesFun = [
    "../index" => "Inicio",
    "solicitud" => "Solicitudes",
    "../consultas/logout" => "Cerrar Sesión"
];

$opcionesDir = [
    "../index" => "Inicio",
    "director" => "Panel",
    "solicitud" => "Solicitudes",
    "tiempo" => "Tiempos SLA",
    "metricas" => "Métricas",
    "../consultas/logout" => "Cerrar Sesión"
];

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
    <div class="container-fluid">
        <a href="../index.php" class="navbar-brand-text text-uppercase text-decoration-none me-2">SGISC</a>

        <div class="d-flex align-items-center">
            <?php if (isset($_SESSION["usuario"]) && $tipoUsuario): ?>
                <span class="texto-bienvenida me-2">
                    Bienvenido, <?php echo htmlspecialchars($tipoUsuario); ?>
                </span>
            <?php endif; ?>

            <div class="d-flex flex-wrap align-items-center gap-2" style="flex: 1; overflow-x: auto;">
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
    </div>
</nav>