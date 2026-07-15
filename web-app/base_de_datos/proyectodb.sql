-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-07-2026 a las 17:33:15
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
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `ID_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ID_departamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`ID_categoria`, `nombre`, `ID_departamento`) VALUES
(1, 'Agua', 2),
(2, 'Residuos', 2),
(3, 'Áreas verdes', 2),
(4, 'Electrico', 3),
(5, 'Transito', 3),
(6, 'Salud', 4),
(7, 'Educación', 4);

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
(987654327, 'elziberiano@gmail.com'),
(987654325, 'franco.videlat@gmail.com'),
(987654326, 'lacuna@ing.ucsc.cl'),
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

--
-- Volcado de datos para la tabla `comprobante`
--

INSERT INTO `comprobante` (`ID_comprobante`, `Fecha`, `Hora`, `solicitud_ID`) VALUES
(1, '2026-07-09', '15:04:42', 1),
(2, '2026-07-09', '23:06:17', 2),
(3, '2026-07-09', '23:26:53', 3),
(4, '2026-07-10', '14:30:57', 4),
(5, '2026-07-13', '17:23:23', 5),
(6, '2026-07-13', '17:58:08', 6),
(7, '2026-07-13', '18:42:46', 7),
(8, '2026-07-14', '21:39:12', 8),
(9, '2026-07-14', '22:11:36', 9);

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
(4, 'Salud y Educación');

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_estado`
--

CREATE TABLE `log_estado` (
  `ID_log` int(11) NOT NULL,
  `solicitud_ID` int(11) NOT NULL,
  `estado_anterior` varchar(50) DEFAULT NULL,
  `estado_nuevo` varchar(50) NOT NULL,
  `ID_usuario` int(11) DEFAULT NULL,
  `fecha_hora_cambio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `log_estado`
--

INSERT INTO `log_estado` (`ID_log`, `solicitud_ID`, `estado_anterior`, `estado_nuevo`, `ID_usuario`, `fecha_hora_cambio`) VALUES
(1, 5, '', 'Recibida', NULL, '2026-07-13 17:23:23'),
(2, 5, 'Recibida', 'Derivada', 28, '2026-07-13 17:26:03'),
(3, 5, 'Derivada', 'En proceso', 28, '2026-07-13 17:26:28'),
(4, 5, 'En proceso', 'Respondida', 28, '2026-07-13 17:27:14'),
(5, 6, '', 'Recibida', NULL, '2026-07-13 17:58:08'),
(6, 6, 'Recibida', 'Derivada', 28, '2026-07-13 18:41:00'),
(7, 7, '', 'Recibida', NULL, '2026-07-13 18:42:46'),
(8, 7, 'Recibida', 'En revision', 28, '2026-07-13 18:50:22'),
(9, 7, 'En revision', 'Derivada', 28, '2026-07-14 19:40:02'),
(10, 7, 'Derivada', 'En proceso', 28, '2026-07-14 19:43:32'),
(11, 8, '', 'Recibida', NULL, '2026-07-14 21:39:12'),
(12, 8, 'Recibida', 'En revision', 28, '2026-07-14 21:39:20'),
(13, 8, 'En revision', 'Derivada', 28, '2026-07-14 21:39:25'),
(14, 9, '', 'Recibida', NULL, '2026-07-14 22:11:36'),
(15, 9, 'Recibida', 'En revision', 28, '2026-07-14 22:11:47'),
(16, 9, 'En revision', 'Derivada', 28, '2026-07-14 22:11:51'),
(17, 9, 'Derivada', 'En proceso', 28, '2026-07-14 22:12:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prioridad`
--

CREATE TABLE `prioridad` (
  `ID_prioridad` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prioridad`
--

INSERT INTO `prioridad` (`ID_prioridad`, `Nombre`) VALUES
(1, 'Urgente'),
(2, 'Alta'),
(3, 'Media'),
(4, 'Baja');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud`
--

CREATE TABLE `solicitud` (
  `solicitud_ID` int(11) NOT NULL,
  `Tipo_estado` enum('Recibida','En revision','Derivada','En proceso','Respondida','Cerrada') DEFAULT 'Recibida',
  `Asunto` varchar(100) DEFAULT NULL,
  `Descripcion` varchar(100) DEFAULT NULL,
  `respuesta` text DEFAULT NULL,
  `motivo_anulacion` text DEFAULT NULL,
  `ID_categoria` int(11) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `ID_departamento` int(11) DEFAULT NULL,
  `ID_tipo_solicitud` int(11) DEFAULT NULL,
  `fecha_respondida` datetime DEFAULT NULL,
  `token_encuesta` varchar(64) DEFAULT NULL,
  `tiempo_asignado` int(10) DEFAULT NULL,
  `fecha_vencimiento` datetime DEFAULT NULL,
  `fecha_creacion` date NOT NULL,
  `ID_prioridad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud`
--

INSERT INTO `solicitud` (`solicitud_ID`, `Tipo_estado`, `Asunto`, `Descripcion`, `respuesta`, `motivo_anulacion`, `ID_categoria`, `correo_electronico`, `ID_departamento`, `ID_tipo_solicitud`, `fecha_respondida`, `token_encuesta`, `tiempo_asignado`, `fecha_vencimiento`, `fecha_creacion`, `ID_prioridad`) VALUES
(1, 'Respondida', 'Corte de agua', 'Me cortaron el agua', 'dadasdasd', NULL, 1, 'franco.videlat@gmail.com', 2, 5, '2026-07-09 14:02:59', 'a876c140fd2082b74b5c3944f2b9e5eca9de048631e503663a8ca6296b3f8e97', NULL, NULL, '2026-07-09', NULL),
(2, 'Respondida', 'Pasto feo', 'El pasto está muy feo, hay un indigente que orina ahí siempre', 'Listo, matamos al indigente', NULL, 3, 'franco.videlat@gmail.com', 2, 5, '2026-07-09 23:19:11', '24b8820f6e6c75028e121d8f7be07524491f5cb7ea500a8123dffc2e97873500', NULL, NULL, '2026-07-09', NULL),
(3, 'Respondida', 'Gracias', 'Ahora ya no está el indigente', 'Gracias por la espera', NULL, 3, 'franco.videlat@gmail.com', 2, 6, '2026-07-09 23:32:24', 'f155baad1601bbf1de21f6ffb1e13ebb8a11849763d658dfc1d9b61468ca1c0c', NULL, NULL, '2026-07-09', NULL),
(4, 'Respondida', 'PRUEBA', 'PRUEBA', 'No', NULL, 3, 'lacuna@ing.ucsc.cl', 2, 5, '2026-07-12 19:27:50', 'b87309d45dbe32a538597dc06112accd479fa38d968a330dc46f917d533046fd', NULL, NULL, '2026-07-10', NULL),
(5, 'Respondida', 'Pasto en horrible estado', 'El pasto está muy mal cuidado y está seco y feo', 'Unidades de la municipalidad se dirigirán al lugar para observar el estado del pasto', NULL, 3, 'elziberiano@gmail.com', 2, 5, '2026-07-13 17:27:14', '27fde7342d650d3c2dbfcc014622a2f72ce954e443934353b10805cb91b9df44', NULL, NULL, '2026-07-13', NULL),
(6, 'Derivada', 'El pasto podría cuidarse de mejor forma', 'Podrían darle más mantenimiento al pasto de la plaza', NULL, NULL, 3, 'elziberiano@gmail.com', 2, 7, NULL, NULL, NULL, NULL, '2026-07-13', NULL),
(7, 'En proceso', 'Prueba 2', 'Prueba2', NULL, NULL, 3, 'elziberiano@gmail.com', 2, 5, NULL, NULL, NULL, NULL, '2026-07-13', NULL),
(8, 'Derivada', 'PRUEBA FECHA VENCIMEINTO', 'PRUEBAAAA', NULL, NULL, 3, 'franco.videlat@gmail.com', 2, 5, NULL, NULL, 1, '2026-07-16 03:39:25', '2026-07-14', 1),
(9, 'En proceso', 'PRUEBA 5', 'PRUEBA 5', NULL, NULL, 3, 'franco.videlat@gmail.com', 2, 6, NULL, NULL, NULL, NULL, '2026-07-14', 1);

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

--
-- Volcado de datos para la tabla `tiempo`
--

INSERT INTO `tiempo` (`ID_prioridad`, `ID_tipo_solicitud`, `ID_departamento`, `tiempo`) VALUES
(1, 5, 2, 1),
(1, 5, 3, 1),
(1, 5, 4, 1),
(1, 7, 2, 3),
(1, 7, 3, 3),
(1, 7, 4, 3),
(2, 5, 2, 2),
(2, 5, 3, 2),
(2, 5, 4, 2),
(2, 7, 2, 4),
(2, 7, 3, 4),
(2, 7, 4, 4),
(3, 5, 2, 3),
(3, 5, 3, 3),
(3, 5, 4, 3),
(3, 7, 2, 5),
(3, 7, 3, 5),
(3, 7, 4, 5),
(4, 5, 2, 5),
(4, 5, 3, 5),
(4, 5, 4, 5),
(4, 6, 2, 7),
(4, 6, 3, 7),
(4, 6, 4, 7),
(4, 7, 2, 6),
(4, 7, 3, 6),
(4, 7, 4, 6);

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
(5, 'Reclamo', 'Informe una situación en la que considere que existió una deficiencia, incumplimiento, demora o inconveniente relacionado con un servicio, procedimiento o atención brindada por la municipalidad. Su reclamo será registrado para su revisión y gestión.'),
(6, 'Felicitacion', 'Reconoce y destaca la buena atención, gestión o desempeño de un funcionario, departamento o servicio municipal. Tu opinión ayuda a valorar las buenas prácticas y fomentar un mejor servicio a la comunidad.'),
(7, 'Sugerencia', 'Proponga ideas o recomendaciones que contribuyan a mejorar los servicios, procesos, infraestructura o atención entregada por la municipalidad. Su sugerencia será evaluada por el departamento correspondiente.');

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

--
-- Volcado de datos para la tabla `trabajador`
--

INSERT INTO `trabajador` (`id_trabajador`, `rut_usuario`, `nombre`, `apellido`, `tipo_trabajador`, `prevision`, `AFP`, `ID_usuario`, `ID_departamento`) VALUES
(3, '20342935-5', 'Waldo', 'Ponce', 'funcionario', 'Fonasa', 'AFP Planvital', 25, 2),
(4, '10966216-4', 'Dani', 'Olmo', 'director', 'Fonasa', 'AFP Modelo', 27, 3),
(5, '96610223-5', 'Arturo', 'Vidal', 'director', 'Fonasa', 'AFP Habitat', 28, 2);

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
(25, '20342935-5', 'franco.videlat@gmail.com', '$2y$10$zcqOtm4eIc/2PdKU74KAjOhwkeXkQdXAGblbrAMBqgvd3tuWWjDHC', 1, 1, '2026-07-09', 0),
(26, '2034237-9', 'franco.videlat@gmail.com', '$2y$10$cIGz24wIxGM3a5W6KB1lk.i8TQTUBr2hEgl.IlmJLQuM/24p6JHcK', 1, 1, '2026-07-09', 1),
(27, '10966216-4', 'franco.videlat@gmail.com', '$2y$10$iwM9TYpbqqUWqvkkcrmV9OWRSkCTk3tWcpKICh4qS40h.GbV4dRvm', 1, 1, '2026-07-10', 0),
(28, '96610223-5', 'franco.videlat@gmail.com', '$2y$10$BP7QFY43YxA7SlpR2sdxruN8ZEti8T6Dqs0PY4rKTUoZRgUA.juwS', 1, 1, '2026-07-12', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`ID_categoria`),
  ADD KEY `ID_departamento` (`ID_departamento`);

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
-- Indices de la tabla `log_estado`
--
ALTER TABLE `log_estado`
  ADD PRIMARY KEY (`ID_log`),
  ADD KEY `solicitud_ID` (`solicitud_ID`),
  ADD KEY `ID_usuario` (`ID_usuario`);

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
  ADD KEY `ID_tipo_solicitud` (`ID_tipo_solicitud`),
  ADD KEY `FK_categoria` (`ID_categoria`),
  ADD KEY `FK_ID_prioridad` (`ID_prioridad`);

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
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `ID_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `comprobante`
--
ALTER TABLE `comprobante`
  MODIFY `ID_comprobante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- AUTO_INCREMENT de la tabla `log_estado`
--
ALTER TABLE `log_estado`
  MODIFY `ID_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `prioridad`
--
ALTER TABLE `prioridad`
  MODIFY `ID_prioridad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `solicitud_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tipo_solicitud`
--
ALTER TABLE `tipo_solicitud`
  MODIFY `ID_tipo_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  MODIFY `id_trabajador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `categoria_ibfk_departamento` FOREIGN KEY (`ID_departamento`) REFERENCES `departamento` (`ID_departamento`);

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
-- Filtros para la tabla `log_estado`
--
ALTER TABLE `log_estado`
  ADD CONSTRAINT `log_estado_ibfk_1` FOREIGN KEY (`solicitud_ID`) REFERENCES `solicitud` (`solicitud_ID`),
  ADD CONSTRAINT `log_estado_ibfk_2` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`);

--
-- Filtros para la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD CONSTRAINT `FK_categoria` FOREIGN KEY (`ID_categoria`) REFERENCES `categoria` (`ID_categoria`),
  ADD CONSTRAINT `FK_ciudadano` FOREIGN KEY (`correo_electronico`) REFERENCES `ciudadano` (`correo_electronico`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_departamento` FOREIGN KEY (`ID_departamento`) REFERENCES `departamento` (`ID_departamento`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_tipo_solicitud` FOREIGN KEY (`ID_tipo_solicitud`) REFERENCES `tipo_solicitud` (`ID_tipo_solicitud`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
