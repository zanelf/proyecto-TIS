<?php
define('BASE_URL', 'http://localhost/xampp/TIS/proyecto-TIS/web-app/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'SGISC - Municipalidad'; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>recursos/css/style.css">

    <?php
    if (isset($css_modules) && is_array($css_modules)) {
        foreach ($css_modules as $css_file) {
            echo '<link rel="stylesheet" href="' . BASE_URL . 'recursos/css/' . $css_file . '">' . "\n";
        }
    }
    ?>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">