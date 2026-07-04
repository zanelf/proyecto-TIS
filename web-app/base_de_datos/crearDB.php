<?php
    include('conexion.php');
    $env = parse_ini_file(__DIR__ . '/../.env');
    echo $aux;
    if ($aux==1) {
        $consulta = "CREATE DATABASE ".$env['DB_NAME'];
        try{
            mysqli_query($conexionMySql,$consulta);
            $conexionDB = mysqli_connect($env['DB_HOST'],$env['DB_USER'],"",$env['DB_NAME']);
            echo "(SE PUDO CREAR LA BASE DE DATOS)";
        }catch (mysqli_sql_exception $e) {
            echo "(No se pudo crear la base de datos)";
        }
        try{
            $conexionDB = mysqli_connect($env['DB_HOST'],$env['DB_USER'],"",$env['DB_NAME']);
            echo "(Se conecto a la db post creada)";
        }catch (mysqli_sql_exception $e) {
            echo "(No se pudo conectar aaa la base de datos)";
        }
        $consulta = "
            CREATE TABLE Ciudadano (
                RUT_ciudadano INT PRIMARY KEY AUTO_INCREMENT,
                correo_electronico VARCHAR(100) NOT NULL
            );
            CREATE TABLE Tipo_solicitud (
                ID_tipo_solicitud INT PRIMARY KEY AUTO_INCREMENT,
                nombre VARCHAR(100) NOT NULL,
                descripcion VARCHAR(255) NOT NULL
            );
            CREATE TABLE Departamento (
                ID_departamento INT PRIMARY KEY AUTO_INCREMENT,
                nombre VARCHAR(100) NOT NULL
            );
            CREATE TABLE Solicitud (
                solicitud_ID INT PRIMARY KEY AUTO_INCREMENT,
                Tipo_estado ENUM('Recibida', 'En revision', 'Derivada', 'En proceso', 'Respondida', 'Cerrada'),
                Asunto VARCHAR(100),
                Descripcion VARCHAR(100),
                Categoria ENUM('Agua', 'Electrico', 'Transito'),
                RUT_ciudadano INT,
                FOREIGN KEY (RUT_ciudadano) REFERENCES ciudadano(RUT_ciudadano),
                ID_departamento INT,
                FOREIGN KEY (ID_departamento) REFERENCES departamento(ID_departamento),
                ID_tipo_solicitud INT,
                FOREIGN KEY (ID_tipo_solicitud) REFERENCES tipo_solicitud(ID_tipo_solicitud)
            ) ENGINE=InnoDB;

            CREATE TABLE Encuesta (
                ID_encuesta INT PRIMARY KEY AUTO_INCREMENT,
                Pregunta VARCHAR(100),
                RUT_ciudadano INT,
                FOREIGN KEY (RUT_ciudadano) REFERENCES ciudadano(RUT_ciudadano),
                solicitud_ID INT,
                FOREIGN KEY (solicitud_ID) REFERENCES Solicitud(solicitud_ID)
            ) ENGINE=InnoDB;

            CREATE TABLE Comprobante (
                ID_comprobante INT PRIMARY KEY AUTO_INCREMENT,
                Fecha date,
                Hora Time,
                solicitud_ID INT,
                FOREIGN KEY (solicitud_ID) REFERENCES Solicitud(solicitud_ID)
            ) ENGINE=InnoDB;

            CREATE TABLE Archivo (
                ID_archvio INT PRIMARY KEY AUTO_INCREMENT,
                descripcion varchar(255),
                solicitud_ID INT,
                FOREIGN KEY (solicitud_ID) REFERENCES Solicitud(solicitud_ID)
            ) ENGINE=InnoDB;

            CREATE TABLE Usuario (
                ID_usuario INT PRIMARY KEY AUTO_INCREMENT,
                nombre_usuario VARCHAR(100) NOT NULL,
                contraseña VARCHAR(100) NOT NULL
            );
            CREATE TABLE Funcionario (
                ID_usuario INT PRIMARY KEY AUTO_INCREMENT,
                FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario),
                ID_departamento INT,
                FOREIGN KEY (ID_departamento) REFERENCES departamento(ID_departamento)
            );
            CREATE TABLE Administrador (
                ID_usuario INT PRIMARY KEY AUTO_INCREMENT,
                FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
            );
            CREATE TABLE Director (
                ID_usuario INT PRIMARY KEY AUTO_INCREMENT,
                FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario),
                ID_departamento INT,
                FOREIGN KEY (ID_departamento) REFERENCES departamento(ID_departamento)
            );
            CREATE TABLE Desarrollador (
                ID_usuario INT PRIMARY KEY AUTO_INCREMENT,
                FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
            );
        ";
        try{
            mysqli_multi_query($conexionDB,$consulta);
        }catch (mysqli_sql_exception $e) {
            echo "(No se puedo crear las tablas en la base de datos)";
            echo $e;
        }
        echo "(Se creo la Base de datos)";
    } else {
        
    }
?>