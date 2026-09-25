-- InventarioApp - carga inicial (copia limpia / datos demo)
-- Generado para publicacion: esquema completo + catalogos + datos de demostracion
-- en todos los apartados (tiendas, trabajadores, stock, packs, recepciones,
-- devoluciones y envios) para poder enseñar la aplicacion con contenido real.
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
-- ================= TIENDAS =================
INSERT INTO tienda (id, numero, nombre, id_sociedad, movil, fijo, email, observaciones, anydesk_equipo1, pos_equipo1, caja_equipo1, anydesk_equipo2, pos_equipo2, caja_equipo2, operadora, ip_fija, vpn, id_cluster_manager, estado) VALUES (1, 101, 'Sevilla Nervion', 1, '695822412', '924942603', 'tienda101@ejemplo.com', 'Tienda de demostracion.', 'AD-101-1', 'POS10101', 'CAJA10101', 'AD-101-2', NULL, NULL, 'Movistar', '10.1.0.10', 'vpn-tienda101', NULL, 'activa');
INSERT INTO tienda (id, numero, nombre, id_sociedad, movil, fijo, email, observaciones, anydesk_equipo1, pos_equipo1, caja_equipo1, anydesk_equipo2, pos_equipo2, caja_equipo2, operadora, ip_fija, vpn, id_cluster_manager, estado) VALUES (2, 102, 'Madrid Gran Via', 1, '646913810', '942868828', 'tienda102@ejemplo.com', 'Tienda de demostracion.', 'AD-102-1', 'POS10201', 'CAJA10201', 'AD-102-2', NULL, NULL, 'Movistar', '10.2.0.10', 'vpn-tienda102', NULL, 'activa');
INSERT INTO tienda (id, numero, nombre, id_sociedad, movil, fijo, email, observaciones, anydesk_equipo1, pos_equipo1, caja_equipo1, anydesk_equipo2, pos_equipo2, caja_equipo2, operadora, ip_fija, vpn, id_cluster_manager, estado) VALUES (3, 103, 'Barcelona Diagonal', 1, '628728463', '923756669', 'tienda103@ejemplo.com', 'Tienda de demostracion.', 'AD-103-1', 'POS10301', 'CAJA10301', 'AD-103-2', NULL, NULL, 'Orange', '10.3.0.10', 'vpn-tienda103', NULL, 'activa');
INSERT INTO tienda (id, numero, nombre, id_sociedad, movil, fijo, email, observaciones, anydesk_equipo1, pos_equipo1, caja_equipo1, anydesk_equipo2, pos_equipo2, caja_equipo2, operadora, ip_fija, vpn, id_cluster_manager, estado) VALUES (4, 104, 'Valencia Colon', 1, '683197857', '921668732', 'tienda104@ejemplo.com', 'Tienda de demostracion.', 'AD-104-1', 'POS10401', 'CAJA10401', 'AD-104-2', NULL, NULL, 'Orange', '10.4.0.10', 'vpn-tienda104', NULL, 'activa');
INSERT INTO tienda (id, numero, nombre, id_sociedad, movil, fijo, email, observaciones, anydesk_equipo1, pos_equipo1, caja_equipo1, anydesk_equipo2, pos_equipo2, caja_equipo2, operadora, ip_fija, vpn, id_cluster_manager, estado) VALUES (5, 105, 'Malaga Larios', 1, '666629388', '914265799', 'tienda105@ejemplo.com', 'Tienda de demostracion.', 'AD-105-1', 'POS10501', 'CAJA10501', 'AD-105-2', NULL, NULL, 'Movistar', '10.5.0.10', 'vpn-tienda105', NULL, 'activa');
INSERT INTO tienda (id, numero, nombre, id_sociedad, movil, fijo, email, observaciones, anydesk_equipo1, pos_equipo1, caja_equipo1, anydesk_equipo2, pos_equipo2, caja_equipo2, operadora, ip_fija, vpn, id_cluster_manager, estado) VALUES (6, 106, 'Zaragoza Independencia', 1, '622575562', '939345092', 'tienda106@ejemplo.com', 'Tienda de demostracion.', 'AD-106-1', 'POS10601', 'CAJA10601', 'AD-106-2', NULL, NULL, 'Movistar', '10.6.0.10', 'vpn-tienda106', NULL, 'activa');
INSERT INTO tienda (id, numero, nombre, id_sociedad, movil, fijo, email, observaciones, anydesk_equipo1, pos_equipo1, caja_equipo1, anydesk_equipo2, pos_equipo2, caja_equipo2, operadora, ip_fija, vpn, id_cluster_manager, estado) VALUES (7, 107, 'Bilbao Gran Via', 1, '677827638', '990801586', 'tienda107@ejemplo.com', 'Tienda de demostracion.', 'AD-107-1', 'POS10701', 'CAJA10701', 'AD-107-2', NULL, NULL, 'Movistar', '10.7.0.10', 'vpn-tienda107', NULL, 'activa');
INSERT INTO tienda (id, numero, nombre, id_sociedad, movil, fijo, email, observaciones, anydesk_equipo1, pos_equipo1, caja_equipo1, anydesk_equipo2, pos_equipo2, caja_equipo2, operadora, ip_fija, vpn, id_cluster_manager, estado) VALUES (8, 108, 'Murcia Centro', 1, '685329037', '936687537', 'tienda108@ejemplo.com', 'Cerrada temporalmente, pendiente de recepcion de material.', 'AD-108-1', 'POS10801', 'CAJA10801', 'AD-108-2', NULL, NULL, 'Orange', '10.8.0.10', 'vpn-tienda108', NULL, 'cerrada');
INSERT INTO tienda (id, numero, nombre, id_sociedad, movil, fijo, email, observaciones, anydesk_equipo1, pos_equipo1, caja_equipo1, anydesk_equipo2, pos_equipo2, caja_equipo2, operadora, ip_fija, vpn, id_cluster_manager, estado) VALUES (9, 109, 'Alicante Rambla', 1, '697226012', '983140807', 'tienda109@ejemplo.com', 'Tienda de demostracion.', 'AD-109-1', 'POS10901', 'CAJA10901', 'AD-109-2', NULL, NULL, 'Vodafone', '10.9.0.10', 'vpn-tienda109', NULL, 'activa');
INSERT INTO tienda (id, numero, nombre, id_sociedad, movil, fijo, email, observaciones, anydesk_equipo1, pos_equipo1, caja_equipo1, anydesk_equipo2, pos_equipo2, caja_equipo2, operadora, ip_fija, vpn, id_cluster_manager, estado) VALUES (10, 110, 'Cordoba Centro', 1, '639587039', '970291817', 'tienda110@ejemplo.com', 'Tienda de demostracion.', 'AD-110-1', 'POS11001', 'CAJA11001', 'AD-110-2', NULL, NULL, 'Orange', '10.10.0.10', 'vpn-tienda110', NULL, 'en_apertura');

-- ================= TRABAJADORES =================
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (1, 1, 'Marta Sanchez Ruiz', 'marta.sanchez.ruiz@ejemplo.com', 1, 'Directora General', NULL, 'AD-EMP-001', NULL, NULL, 'ACTIVO', '2024-02-01 09:00:00', NULL);
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (2, 1, 'Javier Lopez Diaz', 'javier.lopez.diaz@ejemplo.com', 2, 'Tecnico IT', NULL, 'AD-EMP-002', NULL, NULL, 'ACTIVO', '2024-03-01 09:00:00', NULL);
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (3, 1, 'Laura Fernandez Gil', 'laura.fernandez.gil@ejemplo.com', 3, 'Tecnica de RRHH', NULL, 'AD-EMP-003', NULL, NULL, 'ACTIVO', '2024-04-01 09:00:00', NULL);
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (4, 1, 'Carlos Martin Ortega', 'carlos.martin.ortega@ejemplo.com', 4, 'Responsable de Finanzas', NULL, 'AD-EMP-004', NULL, NULL, 'ACTIVO', '2024-05-01 09:00:00', NULL);
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (5, 1, 'Sara Gomez Navarro', 'sara.gomez.navarro@ejemplo.com', 5, 'Analista de Planificacion', NULL, 'AD-EMP-005', NULL, NULL, 'ACTIVO', '2024-06-01 09:00:00', NULL);
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (6, 1, 'David Perez Molina', 'david.perez.molina@ejemplo.com', 6, 'Coordinador Retail', NULL, 'AD-EMP-006', NULL, NULL, 'ACTIVO', '2024-07-01 09:00:00', NULL);
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (7, 1, 'Elena Torres Vidal', 'elena.torres.vidal@ejemplo.com', 7, 'Disenadora Grafica', NULL, 'AD-EMP-007', NULL, NULL, 'ACTIVO', '2024-08-01 09:00:00', NULL);
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (8, 1, 'Ruben Castro Leon', 'ruben.castro.leon@ejemplo.com', 8, 'Especialista Marketing', NULL, 'AD-EMP-008', NULL, NULL, 'BAJA', '2024-09-01 09:00:00', '2026-06-15 10:00:00');
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (9, 2, 'Ana Belen Ramos', 'ana.belen.ramos@ejemplo.com', 9, 'Responsable de Almacen', NULL, 'AD-EMP-009', NULL, NULL, 'ACTIVO', '2024-01-01 09:00:00', NULL);
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (10, 2, 'Ivan Moreno Cano', 'ivan.moreno.cano@ejemplo.com', 9, 'Mozo de Almacen', NULL, 'AD-EMP-010', NULL, NULL, 'ACTIVO', '2024-02-01 09:00:00', NULL);
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (11, 2, 'Cristina Ibanez Serra', 'cristina.ibanez.serra@ejemplo.com', 9, 'Mozo de Almacen', NULL, 'AD-EMP-011', NULL, NULL, 'ACTIVO', '2024-03-01 09:00:00', NULL);
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (12, 3, 'Pablo Ortiz Reyes', 'pablo.ortiz.reyes@ejemplo.com', 10, 'Operario de Serigrafia', NULL, 'AD-EMP-012', NULL, NULL, 'ACTIVO', '2024-04-01 09:00:00', NULL);
INSERT INTO trabajador (id, id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones, estado, fecha_alta, fecha_baja) VALUES (13, 3, 'Nuria Vega Campos', 'nuria.vega.campos@ejemplo.com', 10, 'Operaria de Serigrafia', NULL, 'AD-EMP-013', NULL, NULL, 'ACTIVO', '2024-05-01 09:00:00', NULL);

-- ================= PRODUCTOS =================
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (1, 'OR0000000001', '', 1, 'nuevo', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (2, 'OR0000000002', '', 1, 'en_reparacion', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (3, 'OR0000000003', '', 1, 'averiado', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (4, 'OR0000000004', '', 1, 'usado', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (5, 'OR0000000005', '', 1, 'nuevo', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (6, 'OR0000000006', '', 1, 'en_reparacion', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (7, 'OR0000000007', '', 1, 'nuevo', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (8, 'OR0000000008', '', 1, 'usado', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (9, 'OR0000000009', '', 1, 'usado', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (10, 'OR0000000010', '', 1, 'reacondicionado', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (11, 'OR0000000011', '', 1, 'en_reparacion', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (12, 'OR0000000012', '', 1, 'averiado', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (13, 'OR0000000013', '', 1, 'usado', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (14, 'OR0000000014', '', 1, 'desechado', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (15, 'OR0000000015', '', 1, 'nuevo', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (16, 'OR0000000016', '', 1, 'para_piezas', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (17, 'OR0000000017', '', 1, 'reacondicionado', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (18, 'OR0000000018', '', 1, 'averiado', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (19, 'OR0000000019', '', 1, 'nuevo', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (20, 'OR0000000020', '', 1, 'en_reparacion', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (21, 'OR0000000021', '', 1, 'nuevo', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (22, 'OR0000000022', '', 1, 'para_piezas', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (23, 'OR0000000023', '', 1, 'usado', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (24, 'OR0000000024', '', 1, 'usado', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (25, 'OR0000000025', '', 1, 'nuevo', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (26, 'OR0000000026', '', 1, 'usado', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (27, 'OR0000000027', '', 1, 'reacondicionado', 'Sobremesa');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (28, 'OR0000000028', '', 1, 'averiado', 'Portatil');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (29, 'MONI000001', '', 11, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (30, 'MONI000002', '', 11, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (31, 'MONI000003', '', 11, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (32, 'MONI000004', '', 11, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (33, 'MONI000005', '', 11, 'averiado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (34, 'MONI000006', '', 11, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (35, 'MONI000007', '', 11, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (36, 'MONI000008', '', 11, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (37, 'MONI000009', '', 11, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (38, 'MONI000010', '', 11, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (39, 'MONI000011', '', 11, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (40, 'MONI000012', '', 11, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (41, 'TECL000001', '', 16, 'averiado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (42, 'TECL000002', '', 16, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (43, 'TECL000003', '', 16, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (44, 'TECL000004', '', 16, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (45, 'TECL000005', '', 16, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (46, 'TECL000006', '', 16, 'para_piezas', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (47, 'TECL000007', '', 16, 'en_reparacion', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (48, 'TECL000008', '', 16, 'en_reparacion', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (49, 'TECL000009', '', 16, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (50, 'TECL000010', '', 16, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (51, 'RATO000001', '', 17, 'para_piezas', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (52, 'RATO000002', '', 17, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (53, 'RATO000003', '', 17, 'en_reparacion', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (54, 'RATO000004', '', 17, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (55, 'RATO000005', '', 17, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (56, 'RATO000006', '', 17, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (57, 'RATO000007', '', 17, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (58, 'RATO000008', '', 17, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (59, 'RATO000009', '', 17, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (60, 'RATO000010', '', 17, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (61, 'RATO000011', '', 17, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (62, 'RATO000012', '', 17, 'averiado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (63, 'RATO000013', '', 17, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (64, 'RATO000014', '', 17, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (65, 'HUBU000001', '', 18, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (66, 'HUBU000002', '', 18, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (67, 'HUBU000003', '', 18, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (68, 'HUBU000004', '', 18, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (69, 'HUBU000005', '', 18, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (70, 'HUBU000006', '', 18, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (71, 'HUBU000007', '', 18, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (72, 'HUBU000008', '', 18, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (73, 'HUBU000009', '', 18, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (74, 'HUBU000010', '', 18, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (75, 'IMPT000001', '', 2, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (76, 'IMPT000002', '', 2, 'averiado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (77, 'IMPT000003', '', 2, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (78, 'IMPT000004', '', 2, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (79, 'IMPT000005', '', 2, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (80, 'IMPT000006', '', 2, 'averiado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (81, 'IMPT000007', '', 2, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (82, 'IMPT000008', '', 2, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (83, 'IMPT000009', '', 2, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (84, 'IMPT000010', '', 2, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (85, 'IMPT000011', '', 2, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (86, 'IMPT000012', '', 2, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (87, 'IMPT000013', '', 2, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (88, 'IMPM000001', '', 3, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (89, 'IMPM000002', '', 3, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (90, 'IMPM000003', '', 3, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (91, 'IMPM000004', '', 3, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (92, 'IMPM000005', '', 3, 'en_reparacion', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (93, 'IMPM000006', '', 3, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (94, 'IMPM000007', '', 3, 'para_piezas', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (95, 'LECC000001', '', 4, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (96, 'LECC000002', '', 4, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (97, 'LECC000003', '', 4, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (98, 'LECC000004', '', 4, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (99, 'LECC000005', '', 4, 'en_reparacion', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (100, 'LECC000006', '', 4, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (101, 'LECC000007', '', 4, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (102, 'LECC000008', '', 4, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (103, 'LECC000009', '', 4, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (104, 'LECC000010', '', 4, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (105, 'LECC000011', '', 4, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (106, 'LECC000012', '', 4, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (107, 'LECC000013', '', 4, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (108, 'LECB000001', '', 5, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (109, 'LECB000002', '', 5, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (110, 'LECB000003', '', 5, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (111, 'LECB000004', '', 5, 'para_piezas', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (112, 'ROUT000001', '', 6, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (113, 'ROUT000002', '', 6, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (114, 'ROUT000003', '', 6, 'averiado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (115, 'ROUT000004', '', 6, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (116, 'ROUT000005', '', 6, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (117, 'ROUT000006', '', 6, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (118, 'DATA000001', '', 9, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (119, 'DATA000002', '', 9, 'en_reparacion', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (120, 'DATA000003', '', 9, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (121, 'DATA000004', '', 9, 'en_reparacion', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (122, 'DATA000005', '', 9, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (123, 'DATA000006', '', 9, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (124, 'PINP000001', '', 10, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (125, 'PINP000002', '', 10, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (126, 'PINP000003', '', 10, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (127, 'TELF000001', '', 13, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (128, 'TELF000002', '', 13, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (129, 'TELF000003', '', 13, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (130, 'TELF000004', '', 13, 'averiado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (131, 'TELF000005', '', 13, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (132, 'TELF000006', '', 13, 'averiado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (133, 'NUMM000001', '', 14, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (134, 'NUMM000002', '', 14, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (135, 'NUMM000003', '', 14, 'en_reparacion', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (136, 'NUMM000004', '', 14, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (137, 'NUMM000005', '', 14, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (138, 'NUMM000006', '', 14, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (139, 'NUMM000007', '', 14, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (140, 'NUMM000008', '', 14, 'para_piezas', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (141, 'LICE000001', '', 15, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (142, 'LICE000002', '', 15, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (143, 'LICE000003', '', 15, 'para_piezas', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (144, 'LICE000004', '', 15, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (145, 'LICE000005', '', 15, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (146, 'LICE000006', '', 15, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (147, 'PERI000001', '', 12, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (148, 'PERI000002', '', 12, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (149, 'PERI000003', '', 12, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (150, 'PERI000004', '', 12, 'en_reparacion', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (151, 'CAM3000001', '', 7, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (152, 'CAM3000002', '', 7, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (153, 'CAMF000001', '', 8, 'en_reparacion', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (154, 'CAMF000002', '', 8, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (155, 'CAMF000003', '', 8, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (156, 'CAJO000001', '', 19, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (157, 'CAJO000002', '', 19, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (158, 'CAJO000003', '', 19, 'reacondicionado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (159, 'TELM000001', '', 20, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (160, 'TELM000002', '', 20, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (161, 'TELM000003', '', 20, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (162, 'TELM000004', '', 20, 'nuevo', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (163, 'TELM000005', '', 20, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (164, 'TELM000006', '', 20, 'usado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (165, 'TELM000007', '', 20, 'desechado', '');
INSERT INTO producto (id, codigo, modelo, id_categoria, estado, subtipo) VALUES (166, 'TELM000008', '', 20, 'para_piezas', '');

-- ================= ATRIBUTOS DE PRODUCTO =================
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (1, 1, 'Lenovo');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (1, 2, 'ThinkPad E14');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (1, 3, 'SN000001');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (1, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (1, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (1, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (1, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (2, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (2, 2, 'Latitude 5420');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (2, 3, 'SN000002');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (2, 4, 'AMD Ryzen 5 5500U');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (2, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (2, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (2, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (3, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (3, 2, 'ProBook 450');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (3, 3, 'SN000003');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (3, 4, 'AMD Ryzen 5 5500U');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (3, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (3, 6, '1TB HDD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (3, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (4, 1, 'Lenovo');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (4, 2, 'ThinkPad E14');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (4, 3, 'SN000004');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (4, 4, 'AMD Ryzen 5 5500U');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (4, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (4, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (4, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (5, 1, 'Lenovo');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (5, 2, 'ThinkPad E14');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (5, 3, 'SN000005');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (5, 4, 'Intel Core i7-1165G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (5, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (5, 6, '512GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (5, 7, 'Windows 10 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (6, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (6, 2, 'Latitude 5420');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (6, 3, 'SN000006');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (6, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (6, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (6, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (6, 7, 'Windows 10 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (7, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (7, 2, 'Latitude 5420');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (7, 3, 'SN000007');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (7, 4, 'AMD Ryzen 5 5500U');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (7, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (7, 6, '1TB HDD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (7, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (8, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (8, 2, 'Latitude 5420');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (8, 3, 'SN000008');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (8, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (8, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (8, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (8, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (9, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (9, 2, 'Latitude 5420');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (9, 3, 'SN000009');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (9, 4, 'Intel Core i7-1165G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (9, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (9, 6, '1TB HDD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (9, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (10, 1, 'Lenovo');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (10, 2, 'ThinkPad E14');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (10, 3, 'SN000010');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (10, 4, 'AMD Ryzen 5 5500U');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (10, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (10, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (10, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (11, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (11, 2, 'OptiPlex 3080');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (11, 3, 'SN000011');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (11, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (11, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (11, 6, '512GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (11, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (12, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (12, 2, 'Latitude 5420');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (12, 3, 'SN000012');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (12, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (12, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (12, 6, '512GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (12, 7, 'Windows 10 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (13, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (13, 2, 'OptiPlex 3080');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (13, 3, 'SN000013');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (13, 4, 'Intel Core i7-1165G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (13, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (13, 6, '1TB HDD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (13, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (14, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (14, 2, 'Latitude 5420');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (14, 3, 'SN000014');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (14, 4, 'Intel Core i7-1165G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (14, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (14, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (14, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (15, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (15, 2, 'ProBook 450');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (15, 3, 'SN000015');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (15, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (15, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (15, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (15, 7, 'Windows 10 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (16, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (16, 2, 'ProBook 450');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (16, 3, 'SN000016');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (16, 4, 'Intel Core i7-1165G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (16, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (16, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (16, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (17, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (17, 2, 'OptiPlex 3080');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (17, 3, 'SN000017');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (17, 4, 'AMD Ryzen 5 5500U');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (17, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (17, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (17, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (18, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (18, 2, 'Latitude 5420');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (18, 3, 'SN000018');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (18, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (18, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (18, 6, '512GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (18, 7, 'Windows 10 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (19, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (19, 2, 'OptiPlex 3080');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (19, 3, 'SN000019');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (19, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (19, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (19, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (19, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (20, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (20, 2, 'OptiPlex 3080');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (20, 3, 'SN000020');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (20, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (20, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (20, 6, '512GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (20, 7, 'Windows 10 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (21, 1, 'Lenovo');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (21, 2, 'ThinkPad E14');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (21, 3, 'SN000021');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (21, 4, 'Intel Core i7-1165G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (21, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (21, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (21, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (22, 1, 'Lenovo');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (22, 2, 'ThinkPad E14');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (22, 3, 'SN000022');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (22, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (22, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (22, 6, '1TB HDD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (22, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (23, 1, 'Lenovo');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (23, 2, 'ThinkPad E14');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (23, 3, 'SN000023');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (23, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (23, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (23, 6, '1TB HDD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (23, 7, 'Windows 10 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (24, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (24, 2, 'ProBook 450');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (24, 3, 'SN000024');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (24, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (24, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (24, 6, '256GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (24, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (25, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (25, 2, 'Latitude 5420');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (25, 3, 'SN000025');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (25, 4, 'AMD Ryzen 5 5500U');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (25, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (25, 6, '512GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (25, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (26, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (26, 2, 'ProBook 450');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (26, 3, 'SN000026');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (26, 4, 'AMD Ryzen 5 5500U');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (26, 5, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (26, 6, '1TB HDD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (26, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (27, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (27, 2, 'OptiPlex 3080');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (27, 3, 'SN000027');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (27, 4, 'AMD Ryzen 5 5500U');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (27, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (27, 6, '512GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (27, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (28, 1, 'Lenovo');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (28, 2, 'ThinkPad E14');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (28, 3, 'SN000028');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (28, 4, 'Intel Core i5-1135G7');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (28, 5, '16');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (28, 6, '512GB SSD');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (28, 7, 'Windows 11 Pro');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (29, 1, 'LG');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (29, 2, '24MK430H');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (29, 3, 'SN000029');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (30, 1, 'Samsung');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (30, 2, 'S24R350');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (30, 3, 'SN000030');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (31, 1, 'LG');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (31, 2, '24MK430H');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (31, 3, 'SN000031');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (32, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (32, 2, 'P2422H');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (32, 3, 'SN000032');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (33, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (33, 2, 'P2422H');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (33, 3, 'SN000033');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (34, 1, 'Samsung');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (34, 2, 'S24R350');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (34, 3, 'SN000034');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (35, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (35, 2, 'P2422H');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (35, 3, 'SN000035');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (36, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (36, 2, 'P2422H');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (36, 3, 'SN000036');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (37, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (37, 2, 'V24i');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (37, 3, 'SN000037');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (38, 1, 'LG');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (38, 2, '24MK430H');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (38, 3, 'SN000038');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (39, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (39, 2, 'V24i');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (39, 3, 'SN000039');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (40, 1, 'LG');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (40, 2, '24MK430H');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (40, 3, 'SN000040');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (41, 1, 'Logitech');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (41, 2, 'K120');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (41, 3, 'SN000041');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (42, 1, 'Logitech');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (42, 2, 'K120');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (42, 3, 'SN000042');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (43, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (43, 2, '125');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (43, 3, 'SN000043');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (44, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (44, 2, '125');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (44, 3, 'SN000044');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (45, 1, 'Logitech');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (45, 2, 'K120');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (45, 3, 'SN000045');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (46, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (46, 2, '125');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (46, 3, 'SN000046');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (47, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (47, 2, 'KB216');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (47, 3, 'SN000047');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (48, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (48, 2, 'KB216');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (48, 3, 'SN000048');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (49, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (49, 2, '125');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (49, 3, 'SN000049');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (50, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (50, 2, 'KB216');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (50, 3, 'SN000050');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (51, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (51, 2, 'MS116');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (51, 3, 'SN000051');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (52, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (52, 2, 'MS116');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (52, 3, 'SN000052');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (53, 1, 'Logitech');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (53, 2, 'M90');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (53, 3, 'SN000053');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (54, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (54, 2, 'MS116');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (54, 3, 'SN000054');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (55, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (55, 2, 'MS116');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (55, 3, 'SN000055');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (56, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (56, 2, '125');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (56, 3, 'SN000056');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (57, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (57, 2, 'MS116');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (57, 3, 'SN000057');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (58, 1, 'Logitech');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (58, 2, 'M90');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (58, 3, 'SN000058');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (59, 1, 'Logitech');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (59, 2, 'M90');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (59, 3, 'SN000059');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (60, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (60, 2, '125');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (60, 3, 'SN000060');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (61, 1, 'Logitech');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (61, 2, 'M90');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (61, 3, 'SN000061');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (62, 1, 'Logitech');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (62, 2, 'M90');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (62, 3, 'SN000062');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (63, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (63, 2, 'MS116');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (63, 3, 'SN000063');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (64, 1, 'Dell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (64, 2, 'MS116');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (64, 3, 'SN000064');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (65, 1, 'Anker');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (65, 2, '4-Port USB3');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (65, 3, 'SN000065');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (66, 1, 'Ugreen');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (66, 2, 'USB HUB 4');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (66, 3, 'SN000066');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (67, 1, 'Ugreen');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (67, 2, 'USB HUB 4');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (67, 3, 'SN000067');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (68, 1, 'Genérico');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (68, 2, 'HUB-4P');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (68, 3, 'SN000068');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (69, 1, 'Anker');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (69, 2, '4-Port USB3');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (69, 3, 'SN000069');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (70, 1, 'Genérico');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (70, 2, 'HUB-4P');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (70, 3, 'SN000070');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (71, 1, 'Ugreen');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (71, 2, 'USB HUB 4');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (71, 3, 'SN000071');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (72, 1, 'Anker');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (72, 2, '4-Port USB3');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (72, 3, 'SN000072');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (73, 1, 'Genérico');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (73, 2, 'HUB-4P');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (73, 3, 'SN000073');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (74, 1, 'Genérico');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (74, 2, 'HUB-4P');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (74, 3, 'SN000074');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (75, 1, 'Star');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (75, 2, 'TSP143III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (75, 3, 'SN000075');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (76, 1, 'Star');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (76, 2, 'TSP143III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (76, 3, 'SN000076');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (77, 1, 'Star');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (77, 2, 'TSP143III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (77, 3, 'SN000077');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (78, 1, 'Epson');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (78, 2, 'TM-T20III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (78, 3, 'SN000078');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (79, 1, 'Epson');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (79, 2, 'TM-T20III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (79, 3, 'SN000079');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (80, 1, 'Star');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (80, 2, 'TSP143III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (80, 3, 'SN000080');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (81, 1, 'Star');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (81, 2, 'TSP143III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (81, 3, 'SN000081');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (82, 1, 'Epson');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (82, 2, 'TM-T20III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (82, 3, 'SN000082');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (83, 1, 'Epson');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (83, 2, 'TM-T20III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (83, 3, 'SN000083');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (84, 1, 'Star');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (84, 2, 'TSP143III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (84, 3, 'SN000084');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (85, 1, 'Epson');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (85, 2, 'TM-T20III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (85, 3, 'SN000085');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (86, 1, 'Star');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (86, 2, 'TSP143III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (86, 3, 'SN000086');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (87, 1, 'Epson');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (87, 2, 'TM-T20III');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (87, 3, 'SN000087');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (88, 1, 'Brother');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (88, 2, 'MFC-L2710DW');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (88, 3, 'SN000088');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (89, 1, 'Brother');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (89, 2, 'MFC-L2710DW');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (89, 3, 'SN000089');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (90, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (90, 2, 'LaserJet M28w');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (90, 3, 'SN000090');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (91, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (91, 2, 'LaserJet M28w');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (91, 3, 'SN000091');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (92, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (92, 2, 'LaserJet M28w');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (92, 3, 'SN000092');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (93, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (93, 2, 'LaserJet M28w');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (93, 3, 'SN000093');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (94, 1, 'HP');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (94, 2, 'LaserJet M28w');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (94, 3, 'SN000094');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (95, 1, 'Zebra');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (95, 2, 'DS2208');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (95, 3, 'SN000095');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (96, 1, 'Honeywell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (96, 2, 'Voyager 1200g');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (96, 3, 'SN000096');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (97, 1, 'Zebra');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (97, 2, 'DS2208');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (97, 3, 'SN000097');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (98, 1, 'Honeywell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (98, 2, 'Voyager 1200g');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (98, 3, 'SN000098');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (99, 1, 'Honeywell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (99, 2, 'Voyager 1200g');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (99, 3, 'SN000099');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (100, 1, 'Zebra');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (100, 2, 'DS2208');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (100, 3, 'SN000100');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (101, 1, 'Zebra');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (101, 2, 'DS2208');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (101, 3, 'SN000101');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (102, 1, 'Honeywell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (102, 2, 'Voyager 1200g');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (102, 3, 'SN000102');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (103, 1, 'Zebra');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (103, 2, 'DS2208');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (103, 3, 'SN000103');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (104, 1, 'Honeywell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (104, 2, 'Voyager 1200g');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (104, 3, 'SN000104');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (105, 1, 'Honeywell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (105, 2, 'Voyager 1200g');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (105, 3, 'SN000105');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (106, 1, 'Honeywell');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (106, 2, 'Voyager 1200g');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (106, 3, 'SN000106');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (107, 1, 'Zebra');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (107, 2, 'DS2208');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (107, 3, 'SN000107');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (108, 1, 'JCM');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (108, 2, 'iVizion');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (108, 3, 'SN000108');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (109, 1, 'Cashcode');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (109, 2, 'SM Series');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (109, 3, 'SN000109');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (110, 1, 'Cashcode');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (110, 2, 'SM Series');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (110, 3, 'SN000110');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (111, 1, 'Cashcode');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (111, 2, 'SM Series');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (111, 3, 'SN000111');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (112, 1, 'TP-Link');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (112, 2, 'Archer C6');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (112, 3, 'SN000112');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (112, 15, 'TIENDA-WIFI-112');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (112, 16, 'Wifi112Segura!');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (113, 1, 'Asus');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (113, 2, 'RT-AC58U');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (113, 3, 'SN000113');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (113, 15, 'TIENDA-WIFI-113');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (113, 16, 'Wifi113Segura!');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (114, 1, 'TP-Link');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (114, 2, 'Archer C6');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (114, 3, 'SN000114');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (114, 15, 'TIENDA-WIFI-114');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (114, 16, 'Wifi114Segura!');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (115, 1, 'TP-Link');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (115, 2, 'Archer C6');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (115, 3, 'SN000115');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (115, 15, 'TIENDA-WIFI-115');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (115, 16, 'Wifi115Segura!');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (116, 1, 'Asus');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (116, 2, 'RT-AC58U');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (116, 3, 'SN000116');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (116, 15, 'TIENDA-WIFI-116');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (116, 16, 'Wifi116Segura!');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (117, 1, 'Asus');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (117, 2, 'RT-AC58U');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (117, 3, 'SN000117');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (117, 15, 'TIENDA-WIFI-117');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (117, 16, 'Wifi117Segura!');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (124, 1, 'Ingenico');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (124, 18, '97806');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (124, 2, 'iPP320');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (124, 19, '870763');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (124, 3, 'SN000124');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (124, 20, 'Si');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (124, 21, '4');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (125, 1, 'Ingenico');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (125, 18, '44970');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (125, 2, 'iPP320');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (125, 19, '266931');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (125, 3, 'SN000125');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (125, 20, 'Si');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (125, 21, '2');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (126, 1, 'Ingenico');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (126, 18, '60140');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (126, 2, 'iPP320');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (126, 19, '140605');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (126, 3, 'SN000126');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (126, 20, 'Si');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (126, 21, '8');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (127, 1, 'Gigaset');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (127, 2, 'A170');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (127, 3, 'SN000127');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (127, 8, '');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (128, 1, 'Gigaset');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (128, 2, 'A170');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (128, 3, 'SN000128');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (128, 8, '');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (129, 1, 'Panasonic');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (129, 2, 'KX-TS500');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (129, 3, 'SN000129');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (129, 8, '');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (130, 1, 'Panasonic');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (130, 2, 'KX-TS500');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (130, 3, 'SN000130');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (130, 8, '');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (131, 1, 'Panasonic');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (131, 2, 'KX-TS500');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (131, 3, 'SN000131');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (131, 8, '');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (132, 1, 'Gigaset');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (132, 2, 'A170');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (132, 3, 'SN000132');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (132, 8, '');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (133, 14, '639920292');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (133, 12, 'Movistar');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (133, 9, '8934317444144115546');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (133, 10, '7528');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (133, 11, '54058573');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (133, 13, 'Ilimitados 5G');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (134, 14, '619317495');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (134, 12, 'Vodafone');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (134, 9, '8934822243208656497');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (134, 10, '9346');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (134, 11, '63643924');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (134, 13, '10GB 4G');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (135, 14, '681969657');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (135, 12, 'Vodafone');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (135, 9, '8934131078122379385');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (135, 10, '2889');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (135, 11, '45059710');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (135, 13, '20GB 5G');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (136, 14, '687925434');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (136, 12, 'Vodafone');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (136, 9, '8934222050249963842');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (136, 10, '8119');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (136, 11, '56397338');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (136, 13, '10GB 4G');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (137, 14, '652101056');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (137, 12, 'Vodafone');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (137, 9, '8934230204130123565');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (137, 10, '7311');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (137, 11, '87388337');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (137, 13, '20GB 5G');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (138, 14, '644188276');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (138, 12, 'Movistar');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (138, 9, '8934590956460848080');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (138, 10, '1027');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (138, 11, '79782527');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (138, 13, '10GB 4G');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (139, 14, '636445607');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (139, 12, 'Vodafone');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (139, 9, '8934178784437518755');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (139, 10, '6409');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (139, 11, '93638503');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (139, 13, 'Ilimitados 5G');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (140, 14, '699038359');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (140, 12, 'Movistar');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (140, 9, '8934670969947237264');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (140, 10, '6067');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (140, 11, '99508850');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (140, 13, 'Ilimitados 5G');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (147, 1, 'Genérico');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (147, 2, 'Alfombrilla');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (147, 3, 'SN000147');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (148, 1, 'Genérico');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (148, 2, 'Alfombrilla');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (148, 3, 'SN000148');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (149, 1, 'Genérico');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (149, 2, 'Alfombrilla');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (149, 3, 'SN000149');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (150, 1, 'Genérico');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (150, 2, 'Adaptador USB-C');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (150, 3, 'SN000150');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (151, 1, 'Ricoh');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (151, 2, 'Theta V');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (151, 3, 'SN000151');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (151, 17, '32GB');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (152, 1, 'Ricoh');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (152, 2, 'Theta V');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (152, 3, 'SN000152');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (152, 17, '32GB');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (153, 1, 'Hikvision');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (153, 2, 'DS-2CD1023G0');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (153, 3, 'SN000153');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (153, 17, '32GB');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (154, 1, 'Hikvision');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (154, 2, 'DS-2CD1023G0');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (154, 3, 'SN000154');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (154, 17, '32GB');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (155, 1, 'Hikvision');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (155, 2, 'DS-2CD1023G0');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (155, 3, 'SN000155');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (155, 17, '32GB');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (159, 8, '356342941059723');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (159, 1, 'Xiaomi');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (159, 2, 'Redmi 12');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (159, 3, 'SN000159');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (160, 8, '358560045168705');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (160, 1, 'Xiaomi');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (160, 2, 'Redmi 12');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (160, 3, 'SN000160');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (161, 8, '358771592947529');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (161, 1, 'Xiaomi');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (161, 2, 'Redmi 12');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (161, 3, 'SN000161');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (162, 8, '354760998180894');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (162, 1, 'Xiaomi');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (162, 2, 'Redmi 12');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (162, 3, 'SN000162');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (163, 8, '353988163201073');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (163, 1, 'Xiaomi');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (163, 2, 'Redmi 12');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (163, 3, 'SN000163');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (164, 8, '356895354835020');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (164, 1, 'Samsung');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (164, 2, 'Galaxy A14');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (164, 3, 'SN000164');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (165, 8, '355134984652484');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (165, 1, 'Samsung');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (165, 2, 'Galaxy A14');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (165, 3, 'SN000165');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (166, 8, '353590720536040');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (166, 1, 'Xiaomi');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (166, 2, 'Redmi 12');
INSERT INTO producto_atributo (id_producto, id_atributo, valor) VALUES (166, 3, 'SN000166');-- ================= PACK DE APERTURA =================
INSERT INTO pack (id, codigo, id_tienda, estado, fecha_creacion, usuario_creacion, fecha_envio, fecha_entrega, observaciones) VALUES (1, 'PACK00001', 10, 'preparacion', '2026-09-20 09:00:00', 'Administrador Demo', NULL, NULL, 'Apertura de tienda nueva.');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (1, 19, 'pack', 1, 'pack');
INSERT INTO pack_linea (id, id_pack, id_producto, fecha) VALUES (1, 1, 19, '2026-09-20 09:30:00');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (2, 8, 'pack', 1, 'pack');
INSERT INTO pack_linea (id, id_pack, id_producto, fecha) VALUES (2, 1, 8, '2026-09-20 09:30:00');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (3, 52, 'pack', 1, 'pack');
INSERT INTO pack_linea (id, id_pack, id_producto, fecha) VALUES (3, 1, 52, '2026-09-20 09:30:00');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (4, 67, 'pack', 1, 'pack');
INSERT INTO pack_linea (id, id_pack, id_producto, fecha) VALUES (4, 1, 67, '2026-09-20 09:30:00');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (5, 93, 'pack', 1, 'pack');
INSERT INTO pack_linea (id, id_pack, id_producto, fecha) VALUES (5, 1, 93, '2026-09-20 09:30:00');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (6, 86, 'pack', 1, 'pack');
INSERT INTO pack_linea (id, id_pack, id_producto, fecha) VALUES (6, 1, 86, '2026-09-20 09:30:00');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (7, 98, 'pack', 1, 'pack');
INSERT INTO pack_linea (id, id_pack, id_producto, fecha) VALUES (7, 1, 98, '2026-09-20 09:30:00');

-- ================= DEVOLUCIONES =================
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (8, 30, 'devolucion', 1, 'monitor');
INSERT INTO devolucion (id, id_producto, id_tienda_origen, slot_origen, motivo, estado, fecha_solicitud, usuario_solicitud, fecha_recepcion, usuario_recepcion, condicion_final, observaciones, id_envio) VALUES (1, 30, 1, 'monitor', 'Pantalla con lineas verticales', 'pendiente', '2026-09-19 10:00:00', 'Administrador Demo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (9, 49, 'devolucion', 1, 'teclado');
INSERT INTO devolucion (id, id_producto, id_tienda_origen, slot_origen, motivo, estado, fecha_solicitud, usuario_solicitud, fecha_recepcion, usuario_recepcion, condicion_final, observaciones, id_envio) VALUES (2, 49, 1, 'teclado', 'Teclado con teclas que no responden', 'recibido', '2026-09-19 10:00:00', 'Administrador Demo', '2026-09-22 09:00:00', 'Administrador Demo', 'para_piezas', NULL, NULL);
DELETE FROM asignacion WHERE id_producto = 49 AND destino_tipo = 'devolucion';
UPDATE producto SET estado = 'para_piezas' WHERE id = 49;

-- ================= ENVIOS =================
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (10, 32, 'envio', 2, '');
INSERT INTO envio (id, id_producto, id_tienda_destino, slot_destino, motivo, estado, fecha_creacion, usuario_creacion, fecha_enviado, fecha_llegado, usuario_llegado, id_devolucion) VALUES (1, 32, 2, '', 'Sustitucion de monitor averiado', 'preparando', '2026-09-21 09:00:00', 'Administrador Demo', NULL, NULL, NULL, NULL);
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (11, 50, 'envio', 2, '');
INSERT INTO envio (id, id_producto, id_tienda_destino, slot_destino, motivo, estado, fecha_creacion, usuario_creacion, fecha_enviado, fecha_llegado, usuario_llegado, id_devolucion) VALUES (2, 50, 2, '', 'Reposicion de teclado', 'enviado', '2026-09-21 09:00:00', 'Administrador Demo', '2026-09-23 08:00:00', NULL, NULL, NULL);

-- ================= ASIGNACIONES: TIENDAS =================
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (12, 10, 'tienda', 1, 'equipo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (13, 27, 'tienda', 1, 'equipo2');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (14, 77, 'tienda', 1, 'tickets1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (15, 97, 'tienda', 1, 'lector_codigo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (16, 123, 'tienda', 1, 'datafono');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (17, 113, 'tienda', 1, 'router_sos');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (18, 40, 'tienda', 1, 'monitor');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (19, 45, 'tienda', 1, 'teclado');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (20, 55, 'tienda', 1, 'raton');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (21, 129, 'tienda', 1, 'telefono_fijo');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (22, 1, 'tienda', 2, 'equipo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (23, 75, 'tienda', 2, 'tickets1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (24, 107, 'tienda', 2, 'lector_codigo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (25, 120, 'tienda', 2, 'datafono');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (26, 116, 'tienda', 2, 'router_sos');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (27, 34, 'tienda', 2, 'monitor');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (28, 42, 'tienda', 2, 'teclado');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (29, 63, 'tienda', 2, 'raton');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (30, 128, 'tienda', 2, 'telefono_fijo');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (31, 4, 'tienda', 3, 'equipo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (32, 15, 'tienda', 3, 'equipo2');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (33, 81, 'tienda', 3, 'tickets1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (34, 96, 'tienda', 3, 'lector_codigo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (35, 118, 'tienda', 3, 'datafono');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (36, 112, 'tienda', 3, 'router_sos');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (37, 38, 'tienda', 3, 'monitor');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (38, 44, 'tienda', 3, 'teclado');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (39, 56, 'tienda', 3, 'raton');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (40, 127, 'tienda', 3, 'telefono_fijo');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (41, 21, 'tienda', 4, 'equipo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (42, 87, 'tienda', 4, 'tickets1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (43, 100, 'tienda', 4, 'lector_codigo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (44, 122, 'tienda', 4, 'datafono');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (45, 115, 'tienda', 4, 'router_sos');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (46, 29, 'tienda', 4, 'monitor');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (47, 43, 'tienda', 4, 'teclado');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (48, 54, 'tienda', 4, 'raton');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (49, 9, 'tienda', 5, 'equipo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (50, 23, 'tienda', 5, 'equipo2');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (51, 79, 'tienda', 5, 'tickets1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (52, 103, 'tienda', 5, 'lector_codigo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (53, 36, 'tienda', 5, 'monitor');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (54, 64, 'tienda', 5, 'raton');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (55, 26, 'tienda', 6, 'equipo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (56, 17, 'tienda', 6, 'equipo2');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (57, 84, 'tienda', 6, 'tickets1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (58, 105, 'tienda', 6, 'lector_codigo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (59, 35, 'tienda', 6, 'monitor');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (60, 60, 'tienda', 6, 'raton');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (61, 13, 'tienda', 7, 'equipo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (62, 82, 'tienda', 7, 'tickets1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (63, 106, 'tienda', 7, 'lector_codigo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (64, 31, 'tienda', 7, 'monitor');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (65, 57, 'tienda', 7, 'raton');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (66, 25, 'tienda', 8, 'equipo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (67, 78, 'tienda', 8, 'tickets1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (68, 101, 'tienda', 8, 'lector_codigo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (69, 37, 'tienda', 8, 'monitor');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (70, 58, 'tienda', 8, 'raton');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (71, 24, 'tienda', 9, 'equipo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (72, 5, 'tienda', 9, 'equipo2');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (73, 85, 'tienda', 9, 'tickets1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (74, 104, 'tienda', 9, 'lector_codigo1');

-- ================= ASIGNACIONES: TRABAJADORES =================
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (75, 7, 'trabajador', 1, 'equipo1');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (76, 161, 'trabajador', 1, 'telefono_movil');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (77, 162, 'trabajador', 2, 'telefono_movil');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (78, 136, 'trabajador', 2, 'movil');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (79, 160, 'trabajador', 3, 'telefono_movil');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (80, 139, 'trabajador', 3, 'movil');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (81, 164, 'trabajador', 4, 'telefono_movil');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (82, 137, 'trabajador', 4, 'movil');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (83, 159, 'trabajador', 5, 'telefono_movil');
INSERT INTO asignacion (id, id_producto, destino_tipo, destino_id, slot) VALUES (84, 134, 'trabajador', 7, 'movil');

-- ================= RECEPCIONES =================
UPDATE asignacion SET destino_tipo = 'recepcion' WHERE id_producto = 25 AND destino_tipo = 'tienda' AND destino_id = 8 AND slot = 'equipo1';
INSERT INTO recepcion (id, lote, id_producto, id_tienda_origen, slot_origen, fecha_cierre, usuario_cierre, estado, fecha_recepcion, usuario_recepcion, observaciones) VALUES (1, 'LOTE-CIERRE-8', 25, 8, 'equipo1', '2026-09-18 18:30:00', 'Administrador Demo', 'pendiente', NULL, NULL, NULL);
UPDATE asignacion SET destino_tipo = 'recepcion' WHERE id_producto = 78 AND destino_tipo = 'tienda' AND destino_id = 8 AND slot = 'tickets1';
INSERT INTO recepcion (id, lote, id_producto, id_tienda_origen, slot_origen, fecha_cierre, usuario_cierre, estado, fecha_recepcion, usuario_recepcion, observaciones) VALUES (2, 'LOTE-CIERRE-8', 78, 8, 'tickets1', '2026-09-18 18:30:00', 'Administrador Demo', 'pendiente', NULL, NULL, NULL);
UPDATE asignacion SET destino_tipo = 'recepcion' WHERE id_producto = 101 AND destino_tipo = 'tienda' AND destino_id = 8 AND slot = 'lector_codigo1';
INSERT INTO recepcion (id, lote, id_producto, id_tienda_origen, slot_origen, fecha_cierre, usuario_cierre, estado, fecha_recepcion, usuario_recepcion, observaciones) VALUES (3, 'LOTE-CIERRE-8', 101, 8, 'lector_codigo1', '2026-09-18 18:30:00', 'Administrador Demo', 'recibido', '2026-09-20 11:00:00', 'Administrador Demo', NULL);
DELETE FROM asignacion WHERE id_producto = 101 AND destino_tipo = 'recepcion';