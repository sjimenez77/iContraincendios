-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-03-2013 a las 23:08:18
-- Versión del servidor: 5.5.27
-- Versión de PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `fire`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informes`
--

CREATE TABLE IF NOT EXISTS `informes` (
  `idInformes` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuarios` int(11) NOT NULL,
  `idUsos` int(11) NOT NULL DEFAULT '1',
  `fecha` datetime NOT NULL,
  `direccion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cpostal` int(5) NOT NULL,
  `poblacion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `provincia` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `superfice` int(11) NOT NULL COMMENT 'Superficie construida',
  `altura_d` int(11) NOT NULL COMMENT 'Altura de evacuación descendente',
  `altura_e` int(11) NOT NULL COMMENT 'Altura de evacuación ascendente',
  `dens_1per` tinyint(1) DEFAULT NULL COMMENT '¿La densidad de ocupación es mayor que 1 persona cada 5 m cuadrados?',
  `cocina_50kW` tinyint(1) DEFAULT NULL,
  `centro_transf` tinyint(1) DEFAULT NULL,
  `trasteros` tinyint(1) DEFAULT NULL,
  `superficie_trasteros` int(11) DEFAULT NULL,
  `locales_riesgo` tinyint(1) DEFAULT NULL,
  `volumen_construido` int(11) DEFAULT NULL,
  `aloj_50pers` tinyint(1) DEFAULT NULL,
  `cocina_20kW` tinyint(1) DEFAULT NULL,
  `superficie_locales` int(11) DEFAULT NULL,
  `camas_100` tinyint(1) DEFAULT NULL,
  `almacenes_fc` tinyint(1) DEFAULT NULL COMMENT '¿Hay almacenes de productos farmacéuticos y clínicos?',
  `v_almacenes_fc` int(11) DEFAULT NULL,
  `lab_c` tinyint(1) DEFAULT NULL COMMENT '¿Hay laboratorios clínicos?',
  `v_lab_c` int(11) DEFAULT NULL,
  `zonas_est` tinyint(1) DEFAULT NULL COMMENT '¿Hay zonas de esterilización y almacenes anejos?',
  `area_ventas_1500` tinyint(1) DEFAULT NULL COMMENT '¿La superficie total construida del área pública de ventas excede de 1.500 m²?',
  `densidad_cf_500` tinyint(1) DEFAULT NULL COMMENT '¿La densidad de carga de fuego ponderada y corregida aportada por los productos comercializados es mayor que 500 MJ/m²?',
  `almacenes_cf_3400` tinyint(1) DEFAULT NULL COMMENT '¿Hay almacenes en los que la densidad de carga de fuego ponderada y corregida (Qs) aportada por los productos almacenados es superior a 3.400 MJ/m²?',
  `ocupacion_500` tinyint(1) DEFAULT NULL COMMENT '¿La ocupación excede de 500 personas?',
  `tipo_pub_concurrencia` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `talleres_dec` tinyint(1) DEFAULT NULL COMMENT '¿Existen talleres o almacenes de decorados, de vestuario, etc., con un volumen construido superior a 200m³?',
  `robotizado` tinyint(1) DEFAULT NULL,
  `plantas_rasante` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idInformes`),
  KEY `Usuarios_idx` (`idUsuarios`),
  KEY `Usos_idx` (`idUsos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Datos generales de un informe. ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usos`
--

CREATE TABLE IF NOT EXISTS `usos` (
  `idUsos` int(11) NOT NULL AUTO_INCREMENT,
  `Tipo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idUsos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Diferentes usos previstos de las instalaciones' AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `usos`
--

INSERT INTO `usos` (`idUsos`, `Tipo`) VALUES
(1, 'Residencial Vivienda'),
(2, 'Administrativo'),
(3, 'Residencial Público'),
(4, 'Hospitalario'),
(5, 'Docente'),
(6, 'Comercial'),
(7, 'Pública Concurrencia'),
(8, 'Aparcamiento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `idUsuarios` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `roles` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idUsuarios`),
  UNIQUE KEY `usuario_UNIQUE` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Usuarios técnicos con capacidad para almacenar informes' AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuarios`, `nombre`, `apellidos`, `username`, `password`, `salt`, `roles`) VALUES
(6, 'Santos', 'Jiménez', 'sjimenez77@gmail.com', 'xWUkGYZSfAGNiurr1KK3nfnrkt9Hs3LJH0pBTPYCV4eRqvF9hxjALhR6LMr+f4FsR2obFIGpMV4R//pmVz5gdQ==', '', 'ROLE_USER');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `informes`
--
ALTER TABLE `informes`
  ADD CONSTRAINT `Usos` FOREIGN KEY (`idUsos`) REFERENCES `usos` (`idUsos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Usuarios` FOREIGN KEY (`idUsuarios`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
