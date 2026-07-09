-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2026 at 06:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proyectodb`
--

-- --------------------------------------------------------

--
-- Table structure for table `ciudadano`
--

CREATE TABLE IF NOT EXISTS `ciudadano` (
  `RUT_ciudadano` int(11) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  PRIMARY KEY (`RUT_ciudadano`) USING BTREE,
  KEY `correo_electronico` (`correo_electronico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ciudadano`
--

REPLACE INTO `ciudadano` (`RUT_ciudadano`, `correo_electronico`) VALUES
(987654321, 'prueba2@gmail.com'),
(987654322, 'prueba2@gmail.com'),
(112233445, 'prueba3@gmail.com'),
(987654323, 'prueba3@gmail.com'),
(123456789, 'prueba@gmail.com'),
(987654324, 'prueba@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `comprobante`
--

CREATE TABLE IF NOT EXISTS `comprobante` (
  `ID_comprobante` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `Hora` time DEFAULT NULL,
  `solicitud_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_comprobante`),
  KEY `solicitud_ID` (`solicitud_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departamento`
--

CREATE TABLE IF NOT EXISTS `departamento` (
  `ID_departamento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`ID_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departamento`
--

REPLACE INTO `departamento` (`ID_departamento`, `nombre`) VALUES
(2, 'Medio Ambiente'),
(3, 'Tránsito'),
(4, 'Salud y Educación'),
(5, 'Medio Ambiente'),
(6, 'Tránsito'),
(7, 'Salud y Educación'),
(8, 'Medio Ambiente'),
(9, 'Tránsito'),
(10, 'Salud y Educación'),
(11, 'Medio Ambiente'),
(12, 'Tránsito'),
(13, 'Salud y Educación'),
(14, 'Medio Ambiente'),
(15, 'Tránsito'),
(16, 'Salud y Educación'),
(17, 'Medio Ambiente'),
(18, 'Tránsito'),
(19, 'Salud y Educación');

-- --------------------------------------------------------

--
-- Table structure for table `desarrollador`
--

CREATE TABLE IF NOT EXISTS `desarrollador` (
  `ID_usuario` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `director`
--

CREATE TABLE IF NOT EXISTS `director` (
  `ID_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `ID_departamento` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_usuario`),
  KEY `ID_departamento` (`ID_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `director`
--

REPLACE INTO `director` (`ID_usuario`, `ID_departamento`) VALUES
(21, 2);

-- --------------------------------------------------------

--
-- Table structure for table `encuesta`
--

CREATE TABLE IF NOT EXISTS `encuesta` (
  `ID_encuesta` int(11) NOT NULL AUTO_INCREMENT,
  `Pregunta` varchar(100) DEFAULT NULL,
  `RUT_ciudadano` int(11) DEFAULT NULL,
  `solicitud_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_encuesta`),
  KEY `RUT_ciudadano` (`RUT_ciudadano`),
  KEY `solicitud_ID` (`solicitud_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `funcionario`
--

CREATE TABLE IF NOT EXISTS `funcionario` (
  `ID_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `ID_departamento` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_usuario`),
  KEY `ID_departamento` (`ID_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `funcionario`
--

REPLACE INTO `funcionario` (`ID_usuario`, `ID_departamento`) VALUES
(20, 4);

-- --------------------------------------------------------

--
-- Table structure for table `prioridad`
--

CREATE TABLE IF NOT EXISTS `prioridad` (
  `ID_prioridad` int(11) NOT NULL,
  `Nombre` int(11) NOT NULL,
  PRIMARY KEY (`ID_prioridad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `solicitud`
--

CREATE TABLE IF NOT EXISTS `solicitud` (
  `solicitud_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Tipo_estado` enum('Recibida','En revision','Derivada','En proceso','Respondida','Cerrada') DEFAULT 'Recibida',
  `Asunto` varchar(100) DEFAULT NULL,
  `Descripcion` varchar(100) DEFAULT NULL,
  `Categoria` enum('Agua','Electrico','Transito') DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `ID_departamento` int(11) DEFAULT NULL,
  `ID_tipo_solicitud` int(11) DEFAULT NULL,
  `fecha_respondida` datetime DEFAULT NULL,
  `token_encuesta` varchar(64) DEFAULT NULL,
  `tiempo_asignado` int(10) DEFAULT NULL,
  `fecha_creacion` date NOT NULL,
  PRIMARY KEY (`solicitud_ID`),
  KEY `correo_electronico` (`correo_electronico`),
  KEY `ID_departamento` (`ID_departamento`),
  KEY `ID_tipo_solicitud` (`ID_tipo_solicitud`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tiempo`
--

CREATE TABLE IF NOT EXISTS `tiempo` (
  `ID_prioridad` int(11) NOT NULL,
  `ID_tipo_solicitud` int(11) NOT NULL,
  `ID_departamento` int(11) NOT NULL,
  `tiempo` int(11) NOT NULL,
  PRIMARY KEY (`ID_prioridad`,`ID_tipo_solicitud`,`ID_departamento`),
  KEY `ID_tipo_solicitud` (`ID_tipo_solicitud`),
  KEY `ID_departamento` (`ID_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tipo_solicitud`
--

CREATE TABLE IF NOT EXISTS `tipo_solicitud` (
  `ID_tipo_solicitud` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  PRIMARY KEY (`ID_tipo_solicitud`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tipo_solicitud`
--

REPLACE INTO `tipo_solicitud` (`ID_tipo_solicitud`, `nombre`, `descripcion`) VALUES
(5, 'Reclamo', 'Un reclamo sobre algo de la comuna'),
(6, 'Felicitacion', ''),
(7, 'Sugerencia', 'Sugiere algun cambio para hacer en la comuna');

-- --------------------------------------------------------

--
-- Table structure for table `trabajador`
--

CREATE TABLE IF NOT EXISTS `trabajador` (
  `id_trabajador` int(11) NOT NULL AUTO_INCREMENT,
  `rut_usuario` varchar(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_trabajador` enum('director','funcionario','','') NOT NULL,
  `prevision` enum('Fonasa','Isapres','') NOT NULL,
  `AFP` enum('AFP Capital','AFPUno','AFP Provida','AFP Planvital','AFP Modelo','AFP Habitat','AFP Cuprum') NOT NULL,
  `ID_usuario` int(11) DEFAULT NULL,
  `ID_departamento` int(11) NOT NULL,
  PRIMARY KEY (`id_trabajador`),
  KEY `ID_usuario` (`ID_usuario`),
  KEY `ID_departamento` (`ID_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `ID_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `rut_usuario` varchar(100) NOT NULL,
  `contraseña` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `cambio_contraseña` tinyint(1) NOT NULL DEFAULT 0,
  `Fecha_creacion` date NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

REPLACE INTO `usuario` (`ID_usuario`, `rut_usuario`, `contraseña`, `activo`, `cambio_contraseña`, `Fecha_creacion`, `is_admin`) VALUES
(19, 'admin', '$2a$12$4wY.vaDJ83/FRDWDfVN8TuaRCBV4uV6PfI4tw1iws2kn52kjejdtK', 1, 0, '2026-07-09', 1),
(20, 'funcionario', '$2a$12$3Bfun8iYq0U3FDrc62AkBO3iy70FnUB5l5Xy3U0CGp9PNiT7SBq/O', 1, 0, '2026-07-09', 1),
(21, 'director', '$2a$12$iylkTIo1ZImTbv/513JOruSDqehXVWsb6GJ5yU3sy41Bs7aZVLEfi', 1, 0, '2026-07-09', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comprobante`
--
ALTER TABLE `comprobante`
  ADD CONSTRAINT `comprobante_ibfk_1` FOREIGN KEY (`solicitud_ID`) REFERENCES `solicitud` (`solicitud_ID`);

--
-- Constraints for table `desarrollador`
--
ALTER TABLE `desarrollador`
  ADD CONSTRAINT `desarrollador_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`);

--
-- Constraints for table `director`
--
ALTER TABLE `director`
  ADD CONSTRAINT `director_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`),
  ADD CONSTRAINT `director_ibfk_2` FOREIGN KEY (`ID_departamento`) REFERENCES `departamento` (`ID_departamento`);

--
-- Constraints for table `encuesta`
--
ALTER TABLE `encuesta`
  ADD CONSTRAINT `encuesta_ibfk_1` FOREIGN KEY (`RUT_ciudadano`) REFERENCES `ciudadano` (`RUT_ciudadano`),
  ADD CONSTRAINT `encuesta_ibfk_2` FOREIGN KEY (`solicitud_ID`) REFERENCES `solicitud` (`solicitud_ID`);

--
-- Constraints for table `funcionario`
--
ALTER TABLE `funcionario`
  ADD CONSTRAINT `funcionario_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`),
  ADD CONSTRAINT `funcionario_ibfk_2` FOREIGN KEY (`ID_departamento`) REFERENCES `departamento` (`ID_departamento`);

--
-- Constraints for table `solicitud`
--
ALTER TABLE `solicitud`
  ADD CONSTRAINT `FK_ciudadano` FOREIGN KEY (`correo_electronico`) REFERENCES `ciudadano` (`correo_electronico`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_departamento` FOREIGN KEY (`ID_departamento`) REFERENCES `departamento` (`ID_departamento`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_tipo_solicitud` FOREIGN KEY (`ID_tipo_solicitud`) REFERENCES `tipo_solicitud` (`ID_tipo_solicitud`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
