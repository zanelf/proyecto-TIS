-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-07-2026 a las 20:03:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyectodb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudadano`
--

CREATE TABLE `ciudadano` (
  `RUT_ciudadano` int(11) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ciudadano`
--

INSERT INTO `ciudadano` (`RUT_ciudadano`, `correo_electronico`) VALUES
(987654321, 'prueba2@gmail.com'),
(987654322, 'prueba2@gmail.com'),
(112233445, 'prueba3@gmail.com'),
(987654323, 'prueba3@gmail.com'),
(123456789, 'prueba@gmail.com'),
(987654324, 'prueba@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante`
--

CREATE TABLE `comprobante` (
  `ID_comprobante` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora` time DEFAULT NULL,
  `solicitud_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `ID_departamento` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`ID_departamento`, `nombre`) VALUES
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
-- Estructura de tabla para la tabla `desarrollador`
--

CREATE TABLE `desarrollador` (
  `ID_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `director`
--

CREATE TABLE `director` (
  `ID_usuario` int(11) NOT NULL,
  `ID_departamento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `director`
--

INSERT INTO `director` (`ID_usuario`, `ID_departamento`) VALUES
(21, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `ID_encuesta` int(11) NOT NULL,
  `Pregunta` varchar(100) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `solicitud_ID` int(11) DEFAULT NULL,
  `respuesta_p1` int(11) DEFAULT NULL,
  `respuesta_p2` int(11) DEFAULT NULL,
  `respuesta_p3` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcionario`
--

CREATE TABLE `funcionario` (
  `ID_usuario` int(11) NOT NULL,
  `ID_departamento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `funcionario`
--

INSERT INTO `funcionario` (`ID_usuario`, `ID_departamento`) VALUES
(20, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prioridad`
--

CREATE TABLE `prioridad` (
  `ID_prioridad` int(11) NOT NULL,
  `Nombre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud`
--

CREATE TABLE `solicitud` (
  `solicitud_ID` int(11) NOT NULL,
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
  `fecha_creacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiempo`
--

CREATE TABLE `tiempo` (
  `ID_prioridad` int(11) NOT NULL,
  `ID_tipo_solicitud` int(11) NOT NULL,
  `ID_departamento` int(11) NOT NULL,
  `tiempo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_solicitud`
--

CREATE TABLE `tipo_solicitud` (
  `ID_tipo_solicitud` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_solicitud`
--

INSERT INTO `tipo_solicitud` (`ID_tipo_solicitud`, `nombre`, `descripcion`) VALUES
(5, 'Reclamo', 'Un reclamo sobre algo de la comuna'),
(6, 'Felicitacion', ''),
(7, 'Sugerencia', 'Sugiere algun cambio para hacer en la comuna');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador`
--

CREATE TABLE `trabajador` (
  `id_trabajador` int(11) NOT NULL,
  `rut_usuario` varchar(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_trabajador` enum('director','funcionario','','') NOT NULL,
  `prevision` enum('Fonasa','Isapres','') NOT NULL,
  `AFP` enum('AFP Capital','AFPUno','AFP Provida','AFP Planvital','AFP Modelo','AFP Habitat','AFP Cuprum') NOT NULL,
  `ID_usuario` int(11) DEFAULT NULL,
  `ID_departamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID_usuario` int(11) NOT NULL,
  `rut_usuario` varchar(100) NOT NULL,
  `correo_usuario` varchar(150) NOT NULL DEFAULT '',
  `contraseña` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `cambio_contraseña` tinyint(1) NOT NULL DEFAULT 0,
  `Fecha_creacion` date NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID_usuario`, `rut_usuario`, `correo_usuario`, `contraseña`, `activo`, `cambio_contraseña`, `Fecha_creacion`, `is_admin`) VALUES
(19, 'admin', '', '$2a$12$4wY.vaDJ83/FRDWDfVN8TuaRCBV4uV6PfI4tw1iws2kn52kjejdtK', 1, 0, '2026-07-09', 1),
(20, 'funcionario', '', '$2a$12$3Bfun8iYq0U3FDrc62AkBO3iy70FnUB5l5Xy3U0CGp9PNiT7SBq/O', 1, 0, '2026-07-09', 1),
(21, 'director', '', '$2a$12$iylkTIo1ZImTbv/513JOruSDqehXVWsb6GJ5yU3sy41Bs7aZVLEfi', 1, 0, '2026-07-09', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ciudadano`
--
ALTER TABLE `ciudadano`
  ADD PRIMARY KEY (`RUT_ciudadano`) USING BTREE,
  ADD KEY `correo_electronico` (`correo_electronico`);

--
-- Indices de la tabla `comprobante`
--
ALTER TABLE `comprobante`
  ADD PRIMARY KEY (`ID_comprobante`),
  ADD KEY `solicitud_ID` (`solicitud_ID`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`ID_departamento`);

--
-- Indices de la tabla `desarrollador`
--
ALTER TABLE `desarrollador`
  ADD PRIMARY KEY (`ID_usuario`);

--
-- Indices de la tabla `director`
--
ALTER TABLE `director`
  ADD PRIMARY KEY (`ID_usuario`),
  ADD KEY `ID_departamento` (`ID_departamento`);

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`ID_encuesta`),
  ADD KEY `RUT_ciudadano` (`correo_electronico`),
  ADD KEY `solicitud_ID` (`solicitud_ID`);

--
-- Indices de la tabla `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`ID_usuario`),
  ADD KEY `ID_departamento` (`ID_departamento`);

--
-- Indices de la tabla `prioridad`
--
ALTER TABLE `prioridad`
  ADD PRIMARY KEY (`ID_prioridad`);

--
-- Indices de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD PRIMARY KEY (`solicitud_ID`),
  ADD KEY `correo_electronico` (`correo_electronico`),
  ADD KEY `ID_departamento` (`ID_departamento`),
  ADD KEY `ID_tipo_solicitud` (`ID_tipo_solicitud`);

--
-- Indices de la tabla `tiempo`
--
ALTER TABLE `tiempo`
  ADD PRIMARY KEY (`ID_prioridad`,`ID_tipo_solicitud`,`ID_departamento`),
  ADD KEY `ID_tipo_solicitud` (`ID_tipo_solicitud`),
  ADD KEY `ID_departamento` (`ID_departamento`);

--
-- Indices de la tabla `tipo_solicitud`
--
ALTER TABLE `tipo_solicitud`
  ADD PRIMARY KEY (`ID_tipo_solicitud`);

--
-- Indices de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  ADD PRIMARY KEY (`id_trabajador`),
  ADD KEY `ID_usuario` (`ID_usuario`),
  ADD KEY `ID_departamento` (`ID_departamento`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comprobante`
--
ALTER TABLE `comprobante`
  MODIFY `ID_comprobante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `ID_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `desarrollador`
--
ALTER TABLE `desarrollador`
  MODIFY `ID_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `director`
--
ALTER TABLE `director`
  MODIFY `ID_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `ID_encuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `ID_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `tipo_solicitud`
--
ALTER TABLE `tipo_solicitud`
  MODIFY `ID_tipo_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  MODIFY `id_trabajador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comprobante`
--
ALTER TABLE `comprobante`
  ADD CONSTRAINT `comprobante_ibfk_1` FOREIGN KEY (`solicitud_ID`) REFERENCES `solicitud` (`solicitud_ID`);

--
-- Filtros para la tabla `desarrollador`
--
ALTER TABLE `desarrollador`
  ADD CONSTRAINT `desarrollador_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`);

--
-- Filtros para la tabla `director`
--
ALTER TABLE `director`
  ADD CONSTRAINT `director_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`),
  ADD CONSTRAINT `director_ibfk_2` FOREIGN KEY (`ID_departamento`) REFERENCES `departamento` (`ID_departamento`);

--
-- Filtros para la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD CONSTRAINT `encuesta_ibfk_2` FOREIGN KEY (`solicitud_ID`) REFERENCES `solicitud` (`solicitud_ID`);

--
-- Filtros para la tabla `funcionario`
--
ALTER TABLE `funcionario`
  ADD CONSTRAINT `funcionario_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`),
  ADD CONSTRAINT `funcionario_ibfk_2` FOREIGN KEY (`ID_departamento`) REFERENCES `departamento` (`ID_departamento`);

--
-- Filtros para la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD CONSTRAINT `FK_ciudadano` FOREIGN KEY (`correo_electronico`) REFERENCES `ciudadano` (`correo_electronico`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_departamento` FOREIGN KEY (`ID_departamento`) REFERENCES `departamento` (`ID_departamento`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_tipo_solicitud` FOREIGN KEY (`ID_tipo_solicitud`) REFERENCES `tipo_solicitud` (`ID_tipo_solicitud`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
