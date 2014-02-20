CREATE DATABASE  IF NOT EXISTS `fire` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `fire`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: fire
-- ------------------------------------------------------
-- Server version	5.5.24-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `informes`
--

DROP TABLE IF EXISTS `informes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `informes` (
  `idInformes` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuarios` int(11) NOT NULL,
  `idUsos` int(11) NOT NULL DEFAULT '1',
  `fecha` datetime NOT NULL,
  `direccion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cpostal` int(5) unsigned zerofill NOT NULL,
  `localidad` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `provincia` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `superficie` int(11) NOT NULL COMMENT 'Superficie construida',
  `altura_d` int(11) NOT NULL COMMENT 'Altura de evacuación descendente',
  `altura_a` int(11) NOT NULL COMMENT 'Altura de evacuación ascendente',
  `dens_1per` tinyint(1) DEFAULT NULL COMMENT '¿La densidad de ocupación es mayor que 1 persona cada 5 m cuadrados?',
  `cocina_50kW` tinyint(1) DEFAULT NULL,
  `centro_transf` tinyint(1) DEFAULT NULL,
  `trasteros` tinyint(1) DEFAULT NULL,
  `superficie_trasteros` int(11) DEFAULT NULL,
  `reprografia` tinyint(1) DEFAULT NULL,
  `volumen_construido` int(11) DEFAULT NULL,
  `aloj_50pers` tinyint(1) DEFAULT NULL,
  `cocina_20kW` tinyint(1) DEFAULT NULL,
  `roperos` tinyint(1) DEFAULT NULL,
  `superficie_roperos` int(11) DEFAULT NULL,
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
  `centro_transf_interior` tinyint(1) DEFAULT NULL,
  `talleres_dec` tinyint(1) DEFAULT NULL COMMENT '¿Existen talleres o almacenes de decorados, de vestuario, etc., con un volumen construido superior a 200m³?',
  `robotizado` tinyint(1) DEFAULT NULL,
  `plantas_rasante` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idInformes`),
  KEY `Usuarios_idx` (`idUsuarios`),
  KEY `Usos_idx` (`idUsos`),
  CONSTRAINT `Usos` FOREIGN KEY (`idUsos`) REFERENCES `usos` (`idUsos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Usuarios` FOREIGN KEY (`idUsuarios`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Datos generales de un informe. ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `informes`
--

LOCK TABLES `informes` WRITE;
/*!40000 ALTER TABLE `informes` DISABLE KEYS */;
INSERT INTO `informes` VALUES (1,6,5,'2013-03-25 08:46:25','Cuenca, 9',02002,'Albacete','Albacete',6500,16,16,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',NULL,0,0,0),(2,6,5,'2013-03-25 08:47:01','Mayor, 2',02001,'Albacete','Albacete',9000,25,25,0,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',NULL,0,0,0),(3,6,2,'2013-03-25 11:54:41','Paseo de la Cuba, 2',02006,'Albacete','Albacete',9000,25,25,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',NULL,0,0,0),(4,6,1,'2013-03-26 12:10:20','Hellín, 10',02002,'Albacete','Albacete',4000,40,40,1,0,1,1,3000,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',NULL,0,0,0);
/*!40000 ALTER TABLE `informes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usos`
--

DROP TABLE IF EXISTS `usos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usos` (
  `idUsos` int(11) NOT NULL AUTO_INCREMENT,
  `Tipo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idUsos`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Diferentes usos previstos de las instalaciones';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usos`
--

LOCK TABLES `usos` WRITE;
/*!40000 ALTER TABLE `usos` DISABLE KEYS */;
INSERT INTO `usos` VALUES (1,'Residencial Vivienda'),(2,'Administrativo'),(3,'Residencial Público'),(4,'Hospitalario'),(5,'Docente'),(6,'Comercial'),(7,'Pública Concurrencia'),(8,'Aparcamiento');
/*!40000 ALTER TABLE `usos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `idUsuarios` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `roles` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idUsuarios`),
  UNIQUE KEY `usuario_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Usuarios técnicos con capacidad para almacenar informes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (6,'Santos','Jiménez','sjimenez77@gmail.com','xWUkGYZSfAGNiurr1KK3nfnrkt9Hs3LJH0pBTPYCV4eRqvF9hxjALhR6LMr+f4FsR2obFIGpMV4R//pmVz5gdQ==','','ROLE_USER');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'fire'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-02-20 12:47:17
