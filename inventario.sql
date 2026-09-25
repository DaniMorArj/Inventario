-- InventarioApp - carga inicial (copia limpia / datos demo)
-- Generado para publicacion: esquema completo + catalogos + admin demo.
-- NO contiene datos reales. Login demo: admin@demo.local / Admin1234

/*M!999999\- enable the sandbox mode */ 

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;
DROP TABLE IF EXISTS `asignacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `asignacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `destino_tipo` varchar(20) NOT NULL,
  `destino_id` int(11) NOT NULL,
  `slot` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_producto` (`id_producto`),
  CONSTRAINT `asignacion_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `asignacion_historial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `asignacion_historial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_trabajador` int(11) NOT NULL,
  `slot` varchar(50) NOT NULL,
  `accion` enum('ASIGNAR','LIBERAR','CAMBIAR') NOT NULL,
  `id_producto_anterior` int(11) DEFAULT NULL,
  `id_producto_nuevo` int(11) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_trabajador` (`id_trabajador`),
  CONSTRAINT `asignacion_historial_ibfk_1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajador` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `atributo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `atributo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(60) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('text','number','date','bool','select') NOT NULL DEFAULT 'text',
  `opciones` text DEFAULT NULL,
  `unidad` varchar(20) DEFAULT NULL,
  `requerido` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categoria_atributo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria_atributo` (
  `id_categoria` int(11) NOT NULL,
  `id_atributo` int(11) NOT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `requerido` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_categoria`,`id_atributo`),
  KEY `id_atributo` (`id_atributo`),
  CONSTRAINT `categoria_atributo_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`),
  CONSTRAINT `categoria_atributo_ibfk_2` FOREIGN KEY (`id_atributo`) REFERENCES `atributo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `centro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `centro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` enum('oficina','almacen','serigrafia') NOT NULL,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `departamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `departamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `devolucion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `devolucion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `id_tienda_origen` int(11) NOT NULL,
  `slot_origen` varchar(30) NOT NULL DEFAULT '',
  `motivo` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','recibido','incidencia') NOT NULL DEFAULT 'pendiente',
  `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_solicitud` varchar(100) DEFAULT NULL,
  `fecha_recepcion` datetime DEFAULT NULL,
  `usuario_recepcion` varchar(100) DEFAULT NULL,
  `condicion_final` varchar(20) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `id_envio` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_estado` (`estado`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `envio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `envio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `id_tienda_destino` int(11) NOT NULL,
  `slot_destino` varchar(30) NOT NULL DEFAULT '',
  `motivo` varchar(255) DEFAULT NULL,
  `estado` enum('preparando','enviado','llegado','cancelado') NOT NULL DEFAULT 'preparando',
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_creacion` varchar(100) DEFAULT NULL,
  `fecha_enviado` datetime DEFAULT NULL,
  `fecha_llegado` datetime DEFAULT NULL,
  `usuario_llegado` varchar(100) DEFAULT NULL,
  `id_devolucion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_estado` (`estado`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pack` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) NOT NULL,
  `id_tienda` int(11) NOT NULL,
  `estado` enum('preparacion','completo','enviado','entregado','cancelado') NOT NULL DEFAULT 'preparacion',
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_creacion` varchar(100) DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_codigo` (`codigo`),
  KEY `idx_tienda` (`id_tienda`),
  KEY `idx_estado` (`estado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pack_linea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pack_linea` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pack` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pack_producto` (`id_pack`,`id_producto`),
  KEY `idx_pack` (`id_pack`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pack_plantilla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pack_plantilla` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_categoria` int(11) NOT NULL,
  `subtipo` varchar(50) DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `orden` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expira` datetime NOT NULL,
  `usado` tinyint(1) DEFAULT 0,
  `creado_en` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `modelo` varchar(80) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `subtipo` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=676 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `producto_atributo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto_atributo` (
  `id_producto` int(11) NOT NULL,
  `id_atributo` int(11) NOT NULL,
  `valor` text DEFAULT NULL,
  PRIMARY KEY (`id_producto`,`id_atributo`),
  KEY `id_atributo` (`id_atributo`),
  CONSTRAINT `producto_atributo_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`) ON DELETE CASCADE,
  CONSTRAINT `producto_atributo_ibfk_2` FOREIGN KEY (`id_atributo`) REFERENCES `atributo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `recepcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `recepcion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lote` varchar(40) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_tienda_origen` int(11) NOT NULL,
  `slot_origen` varchar(30) NOT NULL DEFAULT '',
  `fecha_cierre` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_cierre` varchar(100) DEFAULT NULL,
  `estado` enum('pendiente','recibido','incidencia') NOT NULL DEFAULT 'pendiente',
  `fecha_recepcion` datetime DEFAULT NULL,
  `usuario_recepcion` varchar(100) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_estado` (`estado`),
  KEY `idx_lote` (`lote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sociedad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sociedad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `cif` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cif` (`cif`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tienda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tienda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_sociedad` int(11) NOT NULL DEFAULT 1,
  `movil` varchar(20) NOT NULL,
  `fijo` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `observaciones` varchar(255) NOT NULL,
  `anydesk_equipo1` varchar(50) NOT NULL DEFAULT '',
  `pos_equipo1` varchar(30) DEFAULT NULL,
  `caja_equipo1` varchar(30) DEFAULT NULL,
  `anydesk_equipo2` varchar(50) NOT NULL DEFAULT '',
  `pos_equipo2` varchar(30) DEFAULT NULL,
  `caja_equipo2` varchar(30) DEFAULT NULL,
  `operadora` varchar(30) NOT NULL DEFAULT '',
  `ip_fija` varchar(50) NOT NULL DEFAULT '',
  `vpn` varchar(30) NOT NULL DEFAULT '',
  `id_cluster_manager` int(11) DEFAULT NULL,
  `estado` enum('activa','cerrada','en_apertura') NOT NULL DEFAULT 'activa',
  PRIMARY KEY (`id`),
  KEY `fk_tienda_sociedad` (`id_sociedad`),
  CONSTRAINT `fk_tienda_sociedad` FOREIGN KEY (`id_sociedad`) REFERENCES `sociedad` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `trabajador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `trabajador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_centro` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `id_departamento` int(11) DEFAULT NULL,
  `cargo` varchar(120) DEFAULT NULL,
  `id_producto_numero_movil` int(11) DEFAULT NULL,
  `anydesk_equipo1` varchar(50) DEFAULT NULL,
  `anydesk_equipo2` varchar(50) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('ACTIVO','BAJA') NOT NULL DEFAULT 'ACTIVO',
  `fecha_alta` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_baja` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_trabajador_centro` (`id_centro`),
  KEY `fk_trabajador_depto` (`id_departamento`),
  KEY `fk_trabajador_numero` (`id_producto_numero_movil`),
  CONSTRAINT `fk_trabajador_centro` FOREIGN KEY (`id_centro`) REFERENCES `centro` (`id`),
  CONSTRAINT `fk_trabajador_depto` FOREIGN KEY (`id_departamento`) REFERENCES `departamento` (`id`),
  CONSTRAINT `fk_trabajador_numero` FOREIGN KEY (`id_producto_numero_movil`) REFERENCES `producto` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `trabajador_historial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `trabajador_historial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_trabajador` int(11) NOT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `accion` varchar(50) NOT NULL,
  `campo` varchar(100) DEFAULT NULL,
  `valor_anterior` text DEFAULT NULL,
  `valor_nuevo` text DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_trabajador` (`id_trabajador`),
  CONSTRAINT `trabajador_historial_ibfk_1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajador` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `rol` enum('admin','user') NOT NULL DEFAULT 'user',
  `departamento` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;


-- ===== Datos de catalogos =====
/*M!999999\- enable the sandbox mode */ 

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES
(1,'Ordenador'),
(2,'Impresora Tickets'),
(3,'Impresoras Multifuncion'),
(4,'Lector Codigo'),
(5,'Lector Billete'),
(6,'Router'),
(7,'Camaras 360'),
(8,'Camaras Fija'),
(9,'Datafono'),
(10,'Pinpad'),
(11,'Monitor'),
(12,'Periferico'),
(13,'Telefono Fijo'),
(14,'Numero Movil'),
(15,'Licencia'),
(16,'Teclado'),
(17,'Raton'),
(18,'HUB USB'),
(19,'Cajon Portamonedas'),
(20,'Telefono Movil');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `atributo` WRITE;
/*!40000 ALTER TABLE `atributo` DISABLE KEYS */;
INSERT INTO `atributo` VALUES
(1,'marca','Marca','text',NULL,NULL,0),
(2,'modelo_fabricante','Modelo fabricante','text',NULL,NULL,0),
(3,'numero_serie','Número de serie','text',NULL,NULL,0),
(4,'cpu','CPU','text',NULL,NULL,0),
(5,'ram_gb','RAM (GB)','number',NULL,NULL,0),
(6,'disco','Disco','text',NULL,NULL,0),
(7,'sistema_operativo','Sistema operativo','text',NULL,NULL,0),
(8,'imei','IMEI','text',NULL,NULL,0),
(9,'icc','ICC','text',NULL,NULL,0),
(10,'pin','PIN','text',NULL,NULL,0),
(11,'puk','PUK','text',NULL,NULL,0),
(12,'compania','Compañía','text',NULL,NULL,0),
(13,'tarifa','Tarifa','text',NULL,NULL,0),
(14,'numero','Número','text',NULL,NULL,0),
(15,'ssid','SSID','text',NULL,NULL,0),
(16,'clave_wifi','Clave WiFi','text',NULL,NULL,0),
(17,'sd','Tarjeta SD','text',NULL,NULL,0),
(18,'terminal','Terminal','number',NULL,NULL,0),
(19,'numero_comercio','Número Comercio','number',NULL,NULL,0),
(20,'firma','Firma','text',NULL,NULL,0),
(21,'puerto_com','Puerto COM','number',NULL,NULL,0);
/*!40000 ALTER TABLE `atributo` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `categoria_atributo` WRITE;
/*!40000 ALTER TABLE `categoria_atributo` DISABLE KEYS */;
INSERT INTO `categoria_atributo` VALUES
(1,1,1,0),
(1,2,2,0),
(1,3,3,0),
(1,4,4,0),
(1,5,5,0),
(1,6,6,0),
(1,7,7,0),
(2,1,1,0),
(2,2,2,0),
(2,3,3,0),
(3,1,1,0),
(3,2,2,0),
(3,3,3,0),
(4,1,1,0),
(4,2,2,0),
(4,3,3,0),
(5,1,1,0),
(5,2,2,0),
(5,3,3,0),
(6,1,1,0),
(6,2,2,0),
(6,3,3,0),
(6,15,4,0),
(6,16,5,0),
(7,1,1,0),
(7,2,2,0),
(7,3,3,0),
(7,17,4,0),
(8,1,1,0),
(8,2,2,0),
(8,3,3,0),
(8,17,4,0),
(10,1,1,0),
(10,2,2,0),
(10,3,3,0),
(10,18,1,0),
(10,19,2,0),
(10,20,3,0),
(10,21,4,0),
(11,1,1,0),
(11,2,2,0),
(11,3,3,0),
(12,1,1,0),
(12,2,2,0),
(12,3,3,0),
(13,1,1,0),
(13,2,2,0),
(13,3,3,0),
(13,8,4,0),
(14,9,3,0),
(14,10,4,0),
(14,11,5,0),
(14,12,2,0),
(14,13,6,0),
(14,14,1,0),
(16,1,1,0),
(16,2,2,0),
(16,3,3,0),
(18,1,1,0),
(18,2,2,0),
(18,3,3,0),
(20,1,2,0),
(20,2,3,0),
(20,3,4,0),
(20,8,1,0);
/*!40000 ALTER TABLE `categoria_atributo` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pack_plantilla` WRITE;
/*!40000 ALTER TABLE `pack_plantilla` DISABLE KEYS */;
INSERT INTO `pack_plantilla` VALUES
(1,1,'Portatil',2,1),
(2,17,NULL,1,2),
(3,18,NULL,1,3),
(4,3,NULL,1,4),
(5,2,NULL,1,5),
(6,4,NULL,2,6),
(7,5,NULL,1,7),
(8,19,NULL,1,8),
(9,20,NULL,1,9),
(10,13,NULL,1,10),
(11,6,NULL,1,11);
/*!40000 ALTER TABLE `pack_plantilla` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `departamento` WRITE;
/*!40000 ALTER TABLE `departamento` DISABLE KEYS */;
INSERT INTO `departamento` VALUES
(1,'Direccion'),
(2,'IT'),
(3,'RRHH'),
(4,'Finanzas'),
(5,'Planificación'),
(6,'Retail'),
(7,'Diseño'),
(8,'Marketing'),
(9,'Logística'),
(10,'Serigrafía');
/*!40000 ALTER TABLE `departamento` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `centro` WRITE;
/*!40000 ALTER TABLE `centro` DISABLE KEYS */;
INSERT INTO `centro` VALUES
(1,'oficina','Oficina Central'),
(2,'almacen','Almacén Principal'),
(3,'serigrafia','Serigrafía');
/*!40000 ALTER TABLE `centro` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;


--
-- DATOS DEMO (copia limpia para publicar) - sin datos reales
-- Usuario admin de demostracion: admin@demo.local / Admin1234
--
INSERT INTO `sociedad` (`id`, `nombre`, `cif`) VALUES
(1, 'Sociedad Demo S.L.', 'B00000000');

INSERT INTO `usuario` (`id`, `nombre`, `email`, `pass`, `rol`, `departamento`) VALUES
(1, 'Administrador Demo', 'admin@demo.local', '$2y$10$IqUD/HAcvWw0eykZgWaQ0ODDDR2RJw.kmaoMaEWycBCsKp2D8YlD2', 'admin', 'IT');
