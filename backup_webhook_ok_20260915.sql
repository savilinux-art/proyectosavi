/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: proyectosavi
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `agent_conversation_messages`
--

DROP TABLE IF EXISTS `agent_conversation_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `agent_conversation_messages` (
  `id` varchar(36) NOT NULL,
  `conversation_id` varchar(36) NOT NULL,
  `participant_type` varchar(255) DEFAULT NULL,
  `participant_id` bigint(20) unsigned DEFAULT NULL,
  `agent` varchar(255) NOT NULL,
  `role` varchar(25) NOT NULL,
  `content` text NOT NULL,
  `attachments` text NOT NULL,
  `tool_calls` text NOT NULL,
  `tool_results` text NOT NULL,
  `usage` text NOT NULL,
  `meta` text NOT NULL,
  `approval_state` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `conversation_index` (`conversation_id`,`participant_type`,`participant_id`,`updated_at`),
  KEY `participant_index` (`participant_type`,`participant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_conversation_messages`
--

LOCK TABLES `agent_conversation_messages` WRITE;
/*!40000 ALTER TABLE `agent_conversation_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `agent_conversation_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agent_conversations`
--

DROP TABLE IF EXISTS `agent_conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `agent_conversations` (
  `id` varchar(36) NOT NULL,
  `participant_type` varchar(255) DEFAULT NULL,
  `participant_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `participant_updated_at_index` (`participant_type`,`participant_id`,`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_conversations`
--

LOCK TABLES `agent_conversations` WRITE;
/*!40000 ALTER TABLE `agent_conversations` DISABLE KEYS */;
/*!40000 ALTER TABLE `agent_conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('laravel-cache-a75f3f172bfb296f2e10cbfc6dfc1883','i:1;',1789518779),
('laravel-cache-a75f3f172bfb296f2e10cbfc6dfc1883:timer','i:1789518779;',1789518779);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_categoria` (`nombre_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES
(13,'AUDIO','2020-11-26 01:04:58','2020-11-26 01:04:58'),
(14,'ILUMINACION','2020-11-26 01:12:20','2020-11-26 01:12:20'),
(15,'VIDEO','2020-11-26 01:12:51','2020-11-26 01:12:51'),
(16,'ALARMA','2020-11-26 01:13:03','2020-11-26 01:13:03'),
(17,'CABLEADO','2020-11-26 01:13:11','2020-11-26 01:13:11'),
(18,'DOMOTICA','2020-11-26 01:13:30','2020-11-26 01:13:30'),
(19,'ACCESORIO','2020-11-26 01:16:59','2020-11-26 01:16:59'),
(20,'HARDWARE','2020-11-26 01:24:45','2020-11-26 01:24:45'),
(21,'TELEFONIA','2020-11-26 01:24:59','2020-11-26 01:24:59'),
(22,'CORTINA','2020-11-26 01:40:17','2020-11-26 01:40:17'),
(23,'TV','2020-11-26 01:42:37','2020-11-26 01:42:37'),
(24,'RACK O GABINETE','2020-11-26 01:43:00','2020-11-26 01:43:00'),
(25,'RED E INTERNET','2020-11-27 22:31:01','2020-11-27 22:31:01'),
(26,'VENTILACION','2020-11-28 01:33:34','2020-11-28 01:33:34'),
(27,'CONECTOR O CLAVIJA','2020-12-01 03:26:28','2020-12-01 03:26:28'),
(28,'RESISTENCIA O CAPACITOR','2020-12-01 03:39:43','2020-12-01 03:39:43'),
(29,'SOPORTE O BASE','2020-12-04 04:28:12','2020-12-04 04:28:12'),
(30,'FIBRA OPTICA','2020-12-04 04:47:50','2020-12-04 04:47:50'),
(31,'AIRE ACONDICIONADO','2020-12-04 05:03:42','2020-12-04 05:03:42'),
(32,'CONTROL REMOTO','2020-12-05 03:01:01','2020-12-05 03:01:01'),
(33,'INTERFON O VIDEOPORTERO','2020-12-10 02:23:52','2020-12-10 02:23:52'),
(34,'CERRADURA','2020-12-10 02:34:03','2020-12-10 02:34:03'),
(35,'CARGADOR','2020-12-15 05:49:20','2020-12-15 05:49:20'),
(36,'MICROFONO','2020-12-22 04:14:10','2020-12-22 04:14:10'),
(37,'NO BREAK ','2020-12-29 03:32:45','2020-12-29 03:32:45'),
(38,'FUENTE DE PODER','2021-01-22 04:38:08','2021-01-22 04:38:08'),
(39,'PEDIDO','2021-12-17 00:04:25','2021-12-17 00:04:25'),
(40,'INSUMOS Y HERRAMIENTAS','2026-04-30 16:57:32','2026-04-30 16:57:32'),
(41,'PUNTO DE ACCESO','2026-07-31 18:57:52','2026-07-31 18:57:52'),
(42,'SWITCH','2026-08-05 16:45:52','2026-08-05 16:45:52'),
(43,' PATCHCORD','2026-08-05 17:12:18','2026-08-05 17:12:18'),
(44,'PLACAS DECORATIVAS','2026-08-21 17:27:16','2026-08-21 17:27:16'),
(45,'CABLES (Accesorios)','2026-08-24 15:57:24','2026-08-24 15:57:24');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rfc` varchar(255) NOT NULL,
  `razon_social` varchar(255) NOT NULL,
  `nombre_proyecto` varchar(255) NOT NULL,
  `regimen_fiscal` varchar(255) NOT NULL,
  `constancia_situacion_fiscal` mediumblob DEFAULT NULL,
  `codigo_postal` int(11) NOT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rfc` (`rfc`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES
(1,'DMA1904303W3','DESARROLLO MIS AMORES SA DE CV','PUNTO NOVO','Persona Moral',NULL,63735,'jsanchez@dcmarq.com','2026-09-08 12:59:05','2026-09-08 12:59:05');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cotizacion_detalles`
--

DROP TABLE IF EXISTS `cotizacion_detalles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cotizacion_detalles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cotizacion_id` bigint(20) unsigned NOT NULL,
  `inventario_id` bigint(20) unsigned DEFAULT NULL,
  `descripcion` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(12,2) NOT NULL,
  `importe` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cotizacion_detalles_cotizacion_id_foreign` (`cotizacion_id`),
  KEY `cotizacion_detalles_inventario_id_foreign` (`inventario_id`),
  CONSTRAINT `cotizacion_detalles_cotizacion_id_foreign` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cotizacion_detalles_inventario_id_foreign` FOREIGN KEY (`inventario_id`) REFERENCES `inventario` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cotizacion_detalles`
--

LOCK TABLES `cotizacion_detalles` WRITE;
/*!40000 ALTER TABLE `cotizacion_detalles` DISABLE KEYS */;
INSERT INTO `cotizacion_detalles` VALUES
(1,1,829,'Nvr de 8 canalesHikvision 1080p cuadrada blanca (DS-7108NI-Q1/8P)',1,1344.00,1344.00,'2026-09-08 13:33:18','2026-09-08 13:33:18'),
(2,2,829,'Nvr de 8 canalesHikvision 1080p cuadrada blanca (DS-7108NI-Q1/8P)',1,1344.00,1344.00,'2026-09-08 13:42:28','2026-09-08 13:42:28'),
(3,3,1495,'NVR 4 Megapixel (Compatible con Cámaras AcuSense), 8 Canales IP, 8 Puertos PoE+, 1 Bahía de Disco Duro, Salida en Full HD',1,1344.00,1344.00,'2026-09-09 14:39:09','2026-09-09 14:39:09'),
(4,3,NULL,'mano de obra',1,2500.00,2500.00,'2026-09-09 14:39:09','2026-09-09 14:39:09'),
(5,4,829,'Nvr de 8 canalesHikvision 1080p cuadrada blanca (DS-7108NI-Q1/8P)',1,1344.00,1344.00,'2026-09-09 16:08:40','2026-09-09 16:08:40'),
(6,4,NULL,'mano de obra',1,2500.00,2500.00,'2026-09-09 16:08:40','2026-09-09 16:08:40'),
(7,5,829,'Nvr de 8 canalesHikvision 1080p cuadrada blanca (DS-7108NI-Q1/8P)',1,1344.00,1344.00,'2026-09-09 16:29:02','2026-09-09 16:29:02'),
(8,5,NULL,'mano de obra',1,2500.00,2500.00,'2026-09-09 16:29:02','2026-09-09 16:29:02'),
(9,6,829,'Nvr de 8 canalesHikvision 1080p cuadrada blanca (DS-7108NI-Q1/8P)',1,1344.00,1344.00,'2026-09-09 16:47:35','2026-09-09 16:47:35'),
(10,7,829,'Nvr de 8 canalesHikvision 1080p cuadrada blanca (DS-7108NI-Q1/8P)',1,1344.00,1344.00,'2026-09-09 17:25:28','2026-09-09 17:25:28'),
(11,8,317,'Bocina para exterior Bose 151SE color negro (151SE-BLK)',1,2500.00,2500.00,'2026-09-09 17:28:45','2026-09-09 17:28:45'),
(12,8,NULL,'mano de obra',1,2500.00,2500.00,'2026-09-09 17:28:45','2026-09-09 17:28:45');
/*!40000 ALTER TABLE `cotizacion_detalles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cotizaciones`
--

DROP TABLE IF EXISTS `cotizaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cotizaciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `folio` varchar(20) NOT NULL,
  `cliente_id` bigint(20) unsigned NOT NULL,
  `proyecto_id` bigint(20) unsigned NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_validez` date DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `iva` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `moneda` varchar(10) NOT NULL DEFAULT 'USD',
  `condiciones` text DEFAULT NULL,
  `estatus` enum('borrador','enviada','aprobada','rechazada','facturada') NOT NULL DEFAULT 'borrador',
  `creado_por` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cotizaciones_folio_unique` (`folio`),
  KEY `cotizaciones_cliente_id_foreign` (`cliente_id`),
  KEY `cotizaciones_creado_por_foreign` (`creado_por`),
  KEY `cotizaciones_proyecto_id_foreign` (`proyecto_id`),
  CONSTRAINT `cotizaciones_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cotizaciones_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE,
  CONSTRAINT `cotizaciones_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cotizaciones`
--

LOCK TABLES `cotizaciones` WRITE;
/*!40000 ALTER TABLE `cotizaciones` DISABLE KEYS */;
INSERT INTO `cotizaciones` VALUES
(1,'COT-000001',1,7,'2026-09-08','2026-10-08',1344.00,215.04,1559.04,'USD','** SE REQUIERE EL 80% DE ANTICIPO Y EL RESTO A CONTRA ENTREGA.\r\n** ESTA COTIZACION NO INCLUYE CABLEDOS O DUCTERIAS.\r\n** ESTOS PRECIOS PUEDEN VARIAR SIN PREVIO AVISO.\r\n** TIEMPO DE ENTREGA ES DE 30 DIAS HABILES.\r\n** MERCANCIA F.B.O. PUERTO VALLARTA, JALISCO.','borrador','admin','2026-09-08 13:33:18','2026-09-08 13:33:18'),
(2,'COT-000002',1,7,'2026-09-08','2026-10-08',1344.00,215.04,1559.04,'USD','** SE REQUIERE EL 80% DE ANTICIPO Y EL RESTO A CONTRA ENTREGA.\r\n** ESTA COTIZACION NO INCLUYE CABLEDOS O DUCTERIAS.\r\n** ESTOS PRECIOS PUEDEN VARIAR SIN PREVIO AVISO.\r\n** TIEMPO DE ENTREGA ES DE 30 DIAS HABILES.\r\n** MERCANCIA F.B.O. PUERTO VALLARTA, JALISCO.','enviada','admin','2026-09-08 13:42:28','2026-09-08 20:10:34'),
(3,'COT-000003',1,7,'2026-09-09','2026-10-09',3844.00,615.04,4459.04,'USD','** SE REQUIERE EL 80% DE ANTICIPO Y EL RESTO A CONTRA ENTREGA.\r\n** ESTA COTIZACION NO INCLUYE CABLEDOS O DUCTERIAS.\r\n** ESTOS PRECIOS PUEDEN VARIAR SIN PREVIO AVISO.\r\n** TIEMPO DE ENTREGA ES DE 30 DIAS HABILES.\r\n** MERCANCIA F.B.O. PUERTO VALLARTA, JALISCO.','borrador','admin','2026-09-09 14:39:09','2026-09-09 14:39:09'),
(4,'COT-000004',1,7,'2026-09-09','2026-10-09',3844.00,615.04,4459.04,'USD','** SE REQUIERE EL 80% DE ANTICIPO Y EL RESTO A CONTRA ENTREGA.\r\n** ESTA COTIZACION NO INCLUYE CABLEDOS O DUCTERIAS.\r\n** ESTOS PRECIOS PUEDEN VARIAR SIN PREVIO AVISO.\r\n** TIEMPO DE ENTREGA ES DE 30 DIAS HABILES.\r\n\r\n** MERCANCIA F.B.O. PUERTO VALLARTA, JALISCO.','borrador','admin','2026-09-09 16:08:40','2026-09-09 16:08:40'),
(5,'COT-000005',1,7,'2026-09-09','2026-10-09',3844.00,615.04,4459.04,'USD','** SE REQUIERE EL 80% DE ANTICIPO Y EL RESTO A CONTRA ENTREGA.\r\n** ESTA COTIZACION NO INCLUYE CABLEDOS O DUCTERIAS.\r\n** ESTOS PRECIOS PUEDEN VARIAR SIN PREVIO AVISO.\r\n** TIEMPO DE ENTREGA ES DE 30 DIAS HABILES.\r\n** MERCANCIA F.B.O. PUERTO VALLARTA, JALISCO.','borrador','admin','2026-09-09 16:29:02','2026-09-09 16:29:02'),
(6,'COT-000006',1,7,'2026-09-09','2026-10-09',1344.00,215.04,1559.04,'USD','** SE REQUIERE EL 80% DE ANTICIPO Y EL RESTO A CONTRA ENTREGA.\r\n** ESTA COTIZACION NO INCLUYE CABLEDOS O DUCTERIAS.\r\n** ESTOS PRECIOS PUEDEN VARIAR SIN PREVIO AVISO.\r\n** TIEMPO DE ENTREGA ES DE 30 DIAS HABILES.\r\n** MERCANCIA F.B.O. PUERTO VALLARTA, JALISCO.','borrador','admin','2026-09-09 16:47:35','2026-09-09 16:47:35'),
(7,'COT-000007',1,7,'2026-09-09','2026-10-09',1344.00,215.04,1559.04,'USD','** SE REQUIERE EL 80% DE ANTICIPO Y EL RESTO A CONTRA ENTREGA.\r\n** ESTA COTIZACION NO INCLUYE CABLEDOS O DUCTERIAS.\r\n** ESTOS PRECIOS PUEDEN VARIAR SIN PREVIO AVISO.\r\n** TIEMPO DE ENTREGA ES DE 30 DIAS HABILES.\r\n** MERCANCIA F.B.O. PUERTO VALLARTA, JALISCO.','borrador','admin','2026-09-09 17:25:28','2026-09-09 17:25:28'),
(8,'COT-000008',1,7,'2026-09-09','2026-10-09',5000.00,800.00,5800.00,'USD','** SE REQUIERE EL 80% DE ANTICIPO Y EL RESTO A CONTRA ENTREGA.\r\n** ESTA COTIZACION NO INCLUYE CABLEDOS O DUCTERIAS.\r\n** ESTOS PRECIOS PUEDEN VARIAR SIN PREVIO AVISO.\r\n** TIEMPO DE ENTREGA ES DE 30 DIAS HABILES.\r\n** MERCANCIA F.B.O. PUERTO VALLARTA, JALISCO.','borrador','admin','2026-09-09 17:28:45','2026-09-09 17:28:45');
/*!40000 ALTER TABLE `cotizaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devolucion_detalle`
--

DROP TABLE IF EXISTS `devolucion_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `devolucion_detalle` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `devolucion_id` bigint(20) unsigned NOT NULL,
  `inventario_id` bigint(20) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(12,2) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `devolucion_id` (`devolucion_id`),
  KEY `inventario_id` (`inventario_id`),
  CONSTRAINT `devolucion_detalle_ibfk_1` FOREIGN KEY (`devolucion_id`) REFERENCES `devoluciones_inventario` (`id`) ON DELETE CASCADE,
  CONSTRAINT `devolucion_detalle_ibfk_2` FOREIGN KEY (`inventario_id`) REFERENCES `inventario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devolucion_detalle`
--

LOCK TABLES `devolucion_detalle` WRITE;
/*!40000 ALTER TABLE `devolucion_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `devolucion_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devoluciones_inventario`
--

DROP TABLE IF EXISTS `devoluciones_inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `devoluciones_inventario` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_proyecto` varchar(255) DEFAULT NULL,
  `devuelto_por` varchar(255) NOT NULL,
  `recibido_por` varchar(255) NOT NULL,
  `fecha_hora_devolucion` datetime NOT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `devoluciones_inventario_nombre_proyecto_foreign` (`nombre_proyecto`),
  KEY `devoluciones_inventario_devuelto_por_foreign` (`devuelto_por`),
  KEY `devoluciones_inventario_recibido_por_foreign` (`recibido_por`),
  CONSTRAINT `devoluciones_inventario_devuelto_por_foreign` FOREIGN KEY (`devuelto_por`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `devoluciones_inventario_nombre_proyecto_foreign` FOREIGN KEY (`nombre_proyecto`) REFERENCES `ventas` (`nombre_proyecto`) ON DELETE SET NULL,
  CONSTRAINT `devoluciones_inventario_recibido_por_foreign` FOREIGN KEY (`recibido_por`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devoluciones_inventario`
--

LOCK TABLES `devoluciones_inventario` WRITE;
/*!40000 ALTER TABLE `devoluciones_inventario` DISABLE KEYS */;
/*!40000 ALTER TABLE `devoluciones_inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estatus`
--

DROP TABLE IF EXISTS `estatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estatus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estatus` varchar(255) NOT NULL,
  `tipo` enum('venta','instalacion','proyecto') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `estatus` (`estatus`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estatus`
--

LOCK TABLES `estatus` WRITE;
/*!40000 ALTER TABLE `estatus` DISABLE KEYS */;
INSERT INTO `estatus` VALUES
(1,'prospeccion','venta','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(2,'levantamiento','venta','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(3,'cotizacion','venta','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(4,'cierre_venta','venta','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(5,'preparacion','instalacion','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(6,'en_proceso','instalacion','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(7,'programacion','instalacion','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(8,'pruebas','instalacion','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(9,'entrega','instalacion','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(10,'en_desarrollo','proyecto','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(11,'detenido','proyecto','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(12,'pruebas_finales','proyecto','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(13,'terminado','proyecto','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(14,'pendiente','instalacion','2026-09-15 15:43:06','2026-09-15 15:43:06'),
(15,'asignada','instalacion','2026-09-15 15:43:06','2026-09-15 15:43:06'),
(16,'completada','instalacion','2026-09-15 15:43:06','2026-09-15 15:43:06'),
(17,'cancelada','instalacion','2026-09-15 15:43:06','2026-09-15 15:43:06');
/*!40000 ALTER TABLE `estatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `geocerca_alertas`
--

DROP TABLE IF EXISTS `geocerca_alertas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `geocerca_alertas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `geocerca_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `latitud` decimal(10,7) NOT NULL,
  `longitud` decimal(10,7) NOT NULL,
  `fecha_hora` timestamp NULL DEFAULT current_timestamp(),
  `notificado` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `geocerca_id` (`geocerca_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `geocerca_alertas_ibfk_1` FOREIGN KEY (`geocerca_id`) REFERENCES `geocercas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `geocerca_alertas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `geocerca_alertas`
--

LOCK TABLES `geocerca_alertas` WRITE;
/*!40000 ALTER TABLE `geocerca_alertas` DISABLE KEYS */;
/*!40000 ALTER TABLE `geocerca_alertas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `geocerca_estados`
--

DROP TABLE IF EXISTS `geocerca_estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `geocerca_estados` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `geocerca_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `estado` enum('dentro','fuera') NOT NULL DEFAULT 'fuera',
  `ultima_latitud` decimal(10,7) DEFAULT NULL,
  `ultima_longitud` decimal(10,7) DEFAULT NULL,
  `ultima_actualizacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `geocerca_estados_geocerca_id_usuario_id_unique` (`geocerca_id`,`usuario_id`),
  KEY `geocerca_estados_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `geocerca_estados_geocerca_id_foreign` FOREIGN KEY (`geocerca_id`) REFERENCES `geocercas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `geocerca_estados_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `geocerca_estados`
--

LOCK TABLES `geocerca_estados` WRITE;
/*!40000 ALTER TABLE `geocerca_estados` DISABLE KEYS */;
INSERT INTO `geocerca_estados` VALUES
(1,1,2,'fuera',20.7727750,-105.2201430,'2026-09-15 16:21:17','2026-09-10 17:43:48','2026-09-15 16:21:17'),
(2,2,2,'fuera',20.7727750,-105.2201430,'2026-09-15 16:21:17','2026-09-10 17:43:48','2026-09-15 16:21:17'),
(3,4,2,'fuera',20.7727750,-105.2201430,'2026-09-15 16:21:17','2026-09-10 17:43:48','2026-09-15 16:21:17');
/*!40000 ALTER TABLE `geocerca_estados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `geocercas`
--

DROP TABLE IF EXISTS `geocercas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `geocercas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `latitud` decimal(10,7) NOT NULL,
  `longitud` decimal(10,7) NOT NULL,
  `radio` int(11) NOT NULL COMMENT 'Radio en metros',
  `proyecto_id` bigint(20) unsigned DEFAULT NULL,
  `instalacion_id` bigint(20) unsigned DEFAULT NULL,
  `color` varchar(7) DEFAULT '#FF0000' COMMENT 'Color del círculo en el mapa',
  `activa` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proyecto_id` (`proyecto_id`),
  KEY `instalacion_id` (`instalacion_id`),
  CONSTRAINT `geocercas_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `geocercas_ibfk_2` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `geocercas`
--

LOCK TABLES `geocercas` WRITE;
/*!40000 ALTER TABLE `geocercas` DISABLE KEYS */;
INSERT INTO `geocercas` VALUES
(1,'Oficina Central',20.6706000,-105.2126200,40,NULL,NULL,'#ff0000',1,'2026-09-07 19:11:33','2026-09-07 13:36:35'),
(2,'Proyecto Isla Capitan',20.6969246,-105.2914292,30,NULL,NULL,'#00aaff',1,'2026-09-07 19:11:33','2026-09-07 13:49:53'),
(4,'punta de mita',20.7786412,-105.5103993,300,NULL,NULL,'#e5a50a',1,'2026-09-07 14:10:29','2026-09-07 14:11:31');
/*!40000 ALTER TABLE `geocercas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instalacion_fotos`
--

DROP TABLE IF EXISTS `instalacion_fotos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `instalacion_fotos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `instalacion_id` bigint(20) unsigned NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `nombre_original` varchar(255) DEFAULT NULL,
  `mime` varchar(100) DEFAULT NULL,
  `tamano_kb` int(10) unsigned DEFAULT NULL,
  `tipo` enum('inicio','proceso','fin','incidencia') NOT NULL DEFAULT 'proceso',
  `descripcion` text DEFAULT NULL,
  `subida_por_usuario` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `instalacion_fotos_instalacion_id_tipo_index` (`instalacion_id`,`tipo`),
  CONSTRAINT `instalacion_fotos_instalacion_id_foreign` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instalacion_fotos`
--

LOCK TABLES `instalacion_fotos` WRITE;
/*!40000 ALTER TABLE `instalacion_fotos` DISABLE KEYS */;
INSERT INTO `instalacion_fotos` VALUES
(1,34,'instalaciones/34/proceso/btXrZWjTGlq3fFGVmCGQ5grSo8J00xHLuJmmE0KH.png','qr kupuri 24.png','image/png',1,'proceso',NULL,'admin','2026-09-15 16:36:06','2026-09-15 16:36:06'),
(2,33,'instalaciones/33/proceso/mSWQErCr8M7Le6gXDGfDdMII9UEl4I6dFSy09hXD.png','logosavi.png','image/png',34,'proceso',NULL,'admin','2026-09-15 16:37:57','2026-09-15 16:37:57');
/*!40000 ALTER TABLE `instalacion_fotos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instalacion_instalador`
--

DROP TABLE IF EXISTS `instalacion_instalador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `instalacion_instalador` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `instalacion_id` bigint(20) unsigned NOT NULL,
  `instalador_usuario` varchar(255) NOT NULL,
  `es_principal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_instalacion_instalador` (`instalacion_id`,`instalador_usuario`),
  KEY `fk_instalacion_instalador_usuario` (`instalador_usuario`),
  CONSTRAINT `fk_instalacion_instalador_instalacion` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_instalacion_instalador_usuario` FOREIGN KEY (`instalador_usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instalacion_instalador`
--

LOCK TABLES `instalacion_instalador` WRITE;
/*!40000 ALTER TABLE `instalacion_instalador` DISABLE KEYS */;
INSERT INTO `instalacion_instalador` VALUES
(19,15,'lino',0,NULL,NULL),
(29,25,'lino',0,NULL,NULL),
(30,26,'lino',0,NULL,NULL),
(33,29,'lino',0,NULL,NULL),
(37,34,'lino',0,NULL,NULL),
(38,33,'lino',0,NULL,NULL);
/*!40000 ALTER TABLE `instalacion_instalador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instalaciones`
--

DROP TABLE IF EXISTS `instalaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `instalaciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_proyecto` varchar(255) NOT NULL,
  `ubicacion_actual` point DEFAULT NULL,
  `latitud` decimal(10,7) DEFAULT NULL,
  `longitud` decimal(10,7) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `ubicacion_actualizada_en` timestamp NULL DEFAULT NULL,
  `evidencia_inicio` mediumblob DEFAULT NULL,
  `incidencias` mediumblob DEFAULT NULL,
  `evidencia_fin` mediumblob DEFAULT NULL,
  `check_list` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`check_list`)),
  `fecha_hora_inicio` datetime NOT NULL,
  `fecha_hora_fin` datetime DEFAULT NULL,
  `estatus_instalacion` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nombre_instalacion` varchar(255) NOT NULL DEFAULT 'Instalación',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `instalaciones_nombre_proyecto_foreign` (`nombre_proyecto`),
  KEY `instalaciones_estatus_instalacion_foreign` (`estatus_instalacion`),
  KEY `instalaciones_estatus_instalacion_index` (`estatus_instalacion`),
  KEY `instalaciones_fecha_hora_inicio_index` (`fecha_hora_inicio`),
  CONSTRAINT `instalaciones_estatus_instalacion_foreign` FOREIGN KEY (`estatus_instalacion`) REFERENCES `estatus` (`estatus`),
  CONSTRAINT `instalaciones_nombre_proyecto_foreign` FOREIGN KEY (`nombre_proyecto`) REFERENCES `ventas` (`nombre_proyecto`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instalaciones`
--

LOCK TABLES `instalaciones` WRITE;
/*!40000 ALTER TABLE `instalaciones` DISABLE KEYS */;
INSERT INTO `instalaciones` VALUES
(15,'Isla capitan',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 05:27:00',NULL,'en_proceso','2026-08-30 11:27:38','2026-08-30 11:27:50','Redes',NULL),
(25,'kupuri 24',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 17:03:00',NULL,'en_proceso','2026-08-30 23:03:56','2026-08-30 23:04:12','Instalación',NULL),
(26,'lote9',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 17:21:00',NULL,'en_proceso','2026-08-30 23:22:15','2026-08-30 23:22:28','Instalación',NULL),
(29,'kupuri 24',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 20:47:00',NULL,'en_proceso','2026-08-31 02:48:06','2026-08-31 02:48:16','Instalación',NULL),
(33,'Isla capitan',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-09-04 14:05:00',NULL,'en_proceso','2026-09-04 14:06:11','2026-09-05 16:03:44','Instalación',NULL),
(34,'Isla capitan',NULL,20.7727750,-105.2201430,NULL,'2026-09-15 16:19:02',NULL,NULL,NULL,NULL,'2026-09-04 16:01:00',NULL,'completada','2026-09-04 16:01:48','2026-09-15 16:37:39','Instalación','2026-09-15 16:37:39');
/*!40000 ALTER TABLE `instalaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario`
--

DROP TABLE IF EXISTS `inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `existencia` int(11) NOT NULL DEFAULT 0,
  `modelo` varchar(255) DEFAULT NULL,
  `descripcion` varchar(255) NOT NULL,
  `marca` varchar(255) DEFAULT NULL,
  `categoria` varchar(255) NOT NULL,
  `almacen` varchar(255) NOT NULL,
  `apea` varchar(255) DEFAULT NULL,
  `imagen` mediumblob DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `comentarios` varchar(255) DEFAULT NULL,
  `apartados` varchar(255) DEFAULT NULL,
  `cantidad_apartados` varchar(255) DEFAULT NULL,
  `modificado_por` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `codigo_origen` varchar(255) DEFAULT NULL COMMENT 'ID original desde sistema B',
  `fecha_alta` datetime DEFAULT NULL COMMENT 'Fecha de alta original desde sistema B',
  `precio` decimal(12,2) DEFAULT NULL COMMENT 'Precio original (no migrado)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_codigo_origen` (`codigo_origen`),
  KEY `inventario_categoria_foreign` (`categoria`),
  KEY `inventario_modificado_por_foreign` (`modificado_por`),
  KEY `inventario_almacen_index` (`almacen`),
  KEY `inventario_existencia_index` (`existencia`),
  KEY `inventario_marca_index` (`marca`),
  KEY `inventario_fecha_modificacion_index` (`fecha_modificacion`),
  CONSTRAINT `inventario_categoria_foreign` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`nombre_categoria`),
  CONSTRAINT `inventario_modificado_por_foreign` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2059 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario`
--

LOCK TABLES `inventario` WRITE;
/*!40000 ALTER TABLE `inventario` DISABLE KEYS */;
INSERT INTO `inventario` VALUES
(11,0,'NT-R3-NFB-QB','Tapa de 1 ventana, nova-t Laton antiguo','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557827904_1.png','2020-11-26 20:41:00',NULL,NULL,'0','admin','2020-11-27 02:41:00','2020-11-27 02:41:00','027557827904','2020-11-26 20:41:00',1212.00),
(12,13,NULL,'Modulo de interfaz del sistema de control de iluminacion, Grafik eye, lutron (QSE-IO)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/027557613231.png','2020-11-26 22:23:10',NULL,NULL,'0','admin','2020-11-27 04:23:10','2020-11-27 04:23:10','027557613231','2020-11-26 22:23:10',1252.00),
(13,32,NULL,'Modulo de energia/atenuador de luz lutron (LQR-WPM-6P)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/784276070164.png','2020-11-26 22:31:14',NULL,NULL,'0','admin','2020-11-27 04:31:14','2020-11-27 04:31:14','784276070164','2020-11-26 22:31:14',1254.00),
(14,40,NULL,'Frente de switch lutron color nieve/snow (RK-S-SW)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557227704.jpg','2020-11-26 22:49:53',NULL,NULL,'0','admin','2020-11-27 04:49:53','2020-11-27 04:49:53','027557227704','2020-11-26 22:49:53',1219.00),
(15,20,NULL,'Frente de switch lutron color taupe (RK-S-TP)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557227698.jpg','2020-11-26 22:55:39',NULL,NULL,'0','admin','2020-11-27 04:55:39','2020-11-27 04:55:39','027557227698','2020-11-26 22:55:39',NULL),
(16,6,NULL,'Frente de dimmer lutron color siena (RK-D-SI)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557227575.jpg','2020-11-26 23:00:25',NULL,NULL,'0','admin','2020-11-27 05:00:25','2020-11-27 05:00:25','027557227575','2020-11-26 23:00:25',1214.00),
(17,28,NULL,'Frente de dimmer lutron color blanco (RK-D-WH)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557227155.jpg','2020-11-26 23:05:47',NULL,NULL,'0','admin','2020-11-27 05:05:47','2020-11-27 05:05:47','027557227155','2020-11-26 23:05:47',1214.00),
(18,46,NULL,'Frente de control de ventilador lutron color media noche (RK-F-MN)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557583060_1.jpg','2020-11-26 23:11:13',NULL,NULL,'0','admin','2020-11-27 05:11:13','2020-11-27 05:11:13','027557583060','2020-11-26 23:11:13',1224.00),
(19,4,NULL,'Frente de control de ventilador lutron color blanco (RK-F-WH)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/RK-F-WH.jpg','2020-11-26 23:12:50',NULL,NULL,'0','admin','2020-11-27 05:12:50','2020-11-27 05:12:50','RK-F-WH','2020-11-26 23:12:50',1224.00),
(20,39,NULL,'Frente de switch lutron color media noche (RK-S-MN)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557227681.png','2020-11-26 23:29:57',NULL,NULL,'0','admin','2020-11-27 05:29:57','2020-11-27 05:29:57','027557227681','2020-11-26 23:29:57',1219.00),
(21,4,NULL,'Switch lutron homeworks QS color media noche (HQRD-8ANS-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557005197.png','2020-11-26 23:53:36',NULL,NULL,'0','admin','2020-11-27 05:53:36','2020-11-27 05:53:36','027557005197','2020-11-26 23:53:36',1271.00),
(22,14,NULL,'Dimmer lutron homeworks color media noche (HWD-5NE-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557103794_1.png','2020-11-27 19:36:02',NULL,NULL,'0','admin','2020-11-28 01:36:02','2020-11-28 01:36:02','027557103794','2020-11-27 19:36:02',1244.00),
(23,32,NULL,'Dimmer lutron homeworks color blanco (HWD-6ND-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557050890.jpg','2020-11-27 19:49:24',NULL,NULL,'0','admin','2020-11-28 01:49:24','2020-11-28 01:49:24','027557050890','2020-11-27 19:49:24',NULL),
(24,35,NULL,'Switch lutron homeworks color blanco (HWD-8ANS-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557050326.jpg','2020-11-27 19:56:58',NULL,NULL,'0','admin','2020-11-28 01:56:58','2020-11-28 01:56:58','027557050326','2020-11-27 19:56:58',1244.00),
(25,6,NULL,'Control de velocidad de ventilador lutron diva, silencioso de 3 velocidades con interruptor de luz unipolar color blanco (DVFSQ-F-HO-WH)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/027557119184.jpg','2020-11-27 20:06:31',NULL,NULL,'0','admin','2020-11-28 02:06:31','2020-11-28 02:06:31','027557119184','2020-11-27 20:06:31',1264.00),
(26,8,NULL,'Control de ventilador de techo lutron homeworks color blanco (HWD-2ANF-WH)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/027557592758.jpg','2020-11-27 20:18:49',NULL,NULL,'0','admin','2020-11-28 02:18:49','2020-11-28 02:18:49','027557592758','2020-11-27 20:18:49',1244.00),
(27,2,NULL,'Dimmer Lutron Homeworks color biscuit (HWD-6ND-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557050036.jpg','2020-11-27 20:26:37',NULL,NULL,'0','admin','2020-11-28 02:26:37','2020-11-28 02:26:37','027557050036','2020-11-27 20:26:37',1244.00),
(28,16,NULL,'Pedestal para botonera pico lutron color blanco (L-PED1-WH)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557765916.jpg','2020-11-27 21:19:50',NULL,NULL,'0','admin','2020-11-28 03:19:50','2020-11-28 03:19:50','027557765916','2020-11-27 21:19:50',1225.00),
(29,0,'HQWD-W1B-MN','Botonera de 1 boton homeworksQS seeTouch color media noche','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557011235.png','2020-11-28 17:01:41',NULL,NULL,'0','admin','2020-11-28 23:01:41','2020-11-28 23:01:41','027557011235','2020-11-28 17:01:41',1266.00),
(30,2,NULL,'Botonera lutron de 3 botones homeworks QS seeTouch color media noche (HQWD-W3BD-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557036399.png','2020-11-28 17:07:22',NULL,NULL,'0','admin','2020-11-28 23:07:22','2020-11-28 23:07:22','027557036399','2020-11-28 17:07:22',1266.00),
(31,19,NULL,'Botonera lutron de 5 botones homeworks QS seeTouch color media noche (HQWD-W5BRL-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2020-11-28 17:15:54',NULL,NULL,'0','admin','2020-11-28 23:15:54','2020-11-28 23:15:54','027557026901','2020-11-28 17:15:54',1266.00),
(32,1,NULL,'Botonera lutron de 5 botones homeworks QS seeTouch color blanco (HQWD-W5BRL-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/087557022525.jpg','2020-11-28 17:30:24',NULL,NULL,'0','admin','2020-11-28 23:30:24','2020-11-28 23:30:24','087557022525','2020-11-28 17:30:24',1262.00),
(33,18,NULL,'Botonera lutron de 6 botones homeworks QS color biscuit (HQRD-W6BRL-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557007603.jpg','2020-11-28 19:46:23',NULL,NULL,'0','admin','2020-11-29 01:46:23','2020-11-29 01:46:23','027557007603','2020-11-28 19:46:23',1266.00),
(34,2,NULL,'Botonera lutron de 5 botones radioRa2 color media noche (RRD-W5BRL-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557989138.png','2020-11-30 16:14:50',NULL,NULL,'0','admin','2020-11-30 22:14:50','2020-11-30 22:14:50','027557989138','2020-11-30 16:14:50',1271.00),
(35,3,NULL,'Procesador lutron homeworks QS (HQP6-2)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/784276008679.jpg','2020-11-30 16:39:01',NULL,NULL,'0','admin','2020-11-30 22:39:01','2020-11-30 22:39:01','784276008679','2020-11-30 16:39:01',1252.00),
(36,4,NULL,'Caseta wireless atenuador lutron inteligente inalambrico color blanco (PD-6WCL-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276071987.jpg','2020-11-30 17:14:22',NULL,NULL,'0','admin','2020-11-30 23:14:22','2020-11-30 23:14:22','784276071987','2020-11-30 17:14:22',1234.00),
(37,4,NULL,'Caseta wireless pro atenuador 1000W lutron color blanco (PD-10NXD-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276146128.jpg','2020-11-30 17:20:55',NULL,NULL,'0','admin','2020-11-30 23:20:55','2020-11-30 23:20:55','784276146128','2020-11-30 17:20:55',1237.00),
(38,5,NULL,'Puente/bridge inteligente inalambrico lutron caseta pro (L-BDGPRO2-WH)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/784276099349.jpg','2020-11-30 17:43:12',NULL,NULL,'0','admin','2020-11-30 23:43:12','2020-11-30 23:43:12','784276099349','2020-11-30 17:43:12',1233.00),
(39,10,NULL,'Botonera lutron pico de 3 botones y subir/bajar color blanco (PJ2-3BRL-GWH-L01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276067782.jpg','2020-11-30 17:49:39',NULL,NULL,'0','admin','2020-11-30 23:49:39','2020-11-30 23:49:39','784276067782','2020-11-30 17:49:39',1229.00),
(40,20,NULL,'Botonera lutron pico de 2 botones color blanco (PJ2-2B-GWH-L01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276067287.jpg','2020-11-30 18:14:39',NULL,NULL,'0','admin','2020-12-01 00:14:39','2020-12-01 00:14:39','784276067287','2020-11-30 18:14:39',NULL),
(41,31,'SC-BI-TP','Tapa ciega, Color Taupe','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557362016.png','2020-11-30 20:01:31',NULL,NULL,'0','admin','2020-12-01 02:01:31','2020-12-01 02:01:31','027557362016','2020-11-30 20:01:31',1225.00),
(42,32,'SC-BI-MN','Tapa ciega, color Media Noche','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557462242_1.png','2020-11-30 20:06:16',NULL,NULL,'0','admin','2020-12-01 02:06:16','2020-12-01 02:06:16','027557462242','2020-11-30 20:06:16',1225.00),
(43,34,'2160W-BOX','Tapa ciega, color blanco','Eaton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/032664751653.jpg','2020-11-30 20:27:26',NULL,NULL,'0','admin','2020-12-01 02:27:26','2020-12-01 02:27:26','032664751653','2020-11-30 20:27:26',NULL),
(44,6,'DV-BI-LA','Tapa ciega, color Almendra Claro','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557510615.jpg','2020-11-30 20:34:22',NULL,NULL,'0','admin','2020-12-01 02:34:22','2020-12-01 02:34:22','027557510615','2020-11-30 20:34:22',1225.00),
(45,2,NULL,'Panel/switch táctil Aeon Labs de un botón para micro interruptor color blanco (AL-001W)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/810667022631.png','2020-11-30 20:46:54',NULL,NULL,'0','admin','2020-12-01 02:46:54','2020-12-01 02:46:54','810667022631','2020-11-30 20:46:54',1354.00),
(46,16,NULL,'Botonera cristalizada lutron de 3 botones homeworks QS color blanco (HQWT-U-P3W-CWH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276113083.png','2020-11-30 22:29:51',NULL,NULL,'0','admin','2020-12-01 04:29:51','2020-12-01 04:29:51','784276113083','2020-11-30 22:29:51',1270.00),
(47,13,NULL,'Base para pico lutron color blanco (PICO-WBX-ADAPT)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/784276072137.jpg','2020-11-30 22:35:23',NULL,NULL,'0','admin','2020-12-01 04:35:23','2020-12-01 04:35:23','784276072137','2020-11-30 22:35:23',1225.00),
(48,5,NULL,'Botonera pico lutron de 2 botones color media noche (PJ2-2B-TMN-L01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276241229.jpg','2020-11-30 22:44:32',NULL,NULL,'0','admin','2020-12-01 04:44:32','2020-12-01 04:44:32','784276241229','2020-11-30 22:44:32',1229.00),
(49,3,NULL,'Botonera pico lutron de 4 botones color almendra claro (PJ2-4B-GLA-L01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276091411.jpg','2020-11-30 22:47:08',NULL,NULL,'0','admin','2020-12-01 04:47:08','2020-12-01 04:47:08','784276091411','2020-11-30 22:47:08',1229.00),
(50,5,NULL,'Botonera pico lutron de 3 botones y subir/bajar color media noche (PJ2-3BRL-TMN-L01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276241557.jpg','2020-11-30 22:48:50',NULL,NULL,'0','admin','2020-12-01 04:48:50','2020-12-01 04:48:50','784276241557','2020-11-30 22:48:50',1229.00),
(51,9,NULL,'Interruptor electronico lutron RF de multiples sitios caseta wireless color blanco (PD-6ANS-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276152808.png','2020-11-30 22:53:31',NULL,NULL,'0','admin','2020-12-01 04:53:31','2020-12-01 04:53:31','784276152808','2020-11-30 22:53:31',NULL),
(52,6,'04998-00W','Tapa para intemperie de 1 ventana, color blanco','Leviton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/078477880227.jpg','2020-12-01 16:48:42',NULL,NULL,'0','admin','2020-12-01 22:48:42','2020-12-01 22:48:42','078477880227','2020-12-01 16:48:42',1225.00),
(53,2,NULL,'Transformador lutron de 24VCA clase 2, funciona con sivoia o para cortina motorizada sivoia QED (SV-50SF-PI)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/027557113687.png','2020-12-01 21:02:11',NULL,NULL,'0','admin','2020-12-02 03:02:11','2020-12-02 03:02:11','027557113687','2020-12-01 21:02:11',1269.00),
(54,1,NULL,'Puente/bridge de conexion lutron connect color bk (CONNECT-BDG2-1)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/784276177641.png','2020-12-01 21:12:03',NULL,NULL,'0','admin','2020-12-02 03:12:03','2020-12-02 03:12:03','784276177641','2020-12-01 21:12:03',1257.00),
(55,1,NULL,'Sensor de particion de infrarojo lutron grafik eye color blanco (GRX-IRPS-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557319447.png','2020-12-01 22:14:00',NULL,NULL,'0','admin','2020-12-02 04:14:00','2020-12-02 04:14:00','027557319447','2020-12-01 22:14:00',1269.00),
(56,10,NULL,'Atenuador de luz digital lutron maestro de 600 vatios, multiposicion y un solo polo color blanco (MA-600-WH-S)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557517478.png','2020-12-01 22:36:48',NULL,NULL,'0','admin','2020-12-02 04:36:48','2020-12-02 04:36:48','027557517478','2020-12-01 22:36:48',1272.00),
(57,62,NULL,'Switch lutron maestro on/off de 1 polo color blanco (MA-AS-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557082686.png','2020-12-01 22:41:41',NULL,NULL,'0','admin','2020-12-02 04:41:41','2020-12-02 04:41:41','027557082686','2020-12-01 22:41:41',1272.00),
(58,0,NULL,'Atenuador digital lutron maestro IR de 5 amperios 600W color taupe (MIR-600-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2020-12-01 22:48:28',NULL,NULL,'0','admin','2020-12-02 04:48:28','2020-12-02 04:48:28','027557209946','2020-12-01 22:48:28',1271.00),
(59,1,NULL,'Dimmer incandescente lutron maestro wireless multi-ubicacion inalambrico de 600W color biscuit (MRF2-600M-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557667111.jpg','2020-12-01 23:23:35',NULL,NULL,'0','admin','2020-12-02 05:23:35','2020-12-02 05:23:35','027557667111','2020-12-01 23:23:35',1272.00),
(60,5,NULL,'Dimmer lutron maestro wireless para bombillas RF ubicacion multiple de 150W color siena (MRF2-6CL-SI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276066105.jpg','2020-12-01 23:26:22',NULL,NULL,'0','admin','2020-12-02 05:26:22','2020-12-02 05:26:22','784276066105','2020-12-01 23:26:22',1272.00),
(61,5,NULL,'Atenuador de lampara enchufable inalambrico lutron maestro wireless de 120V, montaje en caja de pared acabado brillante color blanco (MRF2-3LD-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557665087.jpg','2020-12-01 23:32:50',NULL,NULL,'0','admin','2020-12-02 05:32:50','2020-12-02 05:32:50','027557665087','2020-12-01 23:32:50',1272.00),
(62,8,NULL,'Dimmer Lutron Maestro IR para bombillas incandescentes y halagenas, unipolar, con control remoto IR color blanco (MIR-600T-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557260978.jpg','2020-12-01 23:39:02',NULL,NULL,'0','admin','2020-12-02 05:39:02','2020-12-02 05:39:02','027557260978','2020-12-01 23:39:02',1272.00),
(63,57,NULL,'Frente de dimmer lutron color taupe (RK-D-TP)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557227421.jpg','2020-12-02 18:00:39',NULL,NULL,'0','admin','2020-12-03 00:00:39','2020-12-03 00:00:39','027557227421','2020-12-02 18:00:39',NULL),
(64,33,NULL,'Frente de dimmer lutron color biscuit (RK-D-BI)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557227254.jpg','2020-12-02 18:10:31',NULL,NULL,'0','admin','2020-12-03 00:10:31','2020-12-03 00:10:31','027557227254','2020-12-02 18:10:31',1214.00),
(65,30,NULL,'Frente de dimmer lutron color media noche (RK-D-MN)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557227414.jpg','2020-12-02 18:15:55',NULL,NULL,'0','admin','2020-12-03 00:15:55','2020-12-03 00:15:55','027557227414','2020-12-02 18:15:55',1214.00),
(66,5,NULL,'Interruptor dimmer lutron homeworks color blanco (HWV-600D-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557691819.png','2020-12-02 20:23:24',NULL,NULL,'0','admin','2020-12-03 02:23:24','2020-12-03 02:23:24','027557691819','2020-12-02 20:23:24',1273.00),
(67,2,NULL,'Interruptor de boton de 4 escenas lutron seetouch clase 2 de 24V color blanco (SO-4SN-WH-EGN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557382205.png','2020-12-02 21:17:26',NULL,NULL,'0','admin','2020-12-03 03:17:26','2020-12-03 03:17:26','027557382205','2020-12-02 21:17:26',1269.00),
(68,4,NULL,'Atenuador/dimmer lutron vierti con LED azul color blanco (VT-600-B-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557545747.png','2020-12-02 21:39:32',NULL,NULL,'0','admin','2020-12-03 03:39:32','2020-12-03 03:39:32','027557545747','2020-12-02 21:39:32',1273.00),
(69,1,NULL,'Modulo de poder lutron con ecosystem (LQSE-2ECO-D)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276008723.png','2020-12-02 22:15:56',NULL,NULL,'0','admin','2020-12-03 04:15:56','2020-12-03 04:15:56','784276008723','2020-12-02 22:15:56',1243.00),
(70,4,NULL,'Modulo de potenciaLutron Grafik Eye (PHPM-PA-120-WH)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/027557533881.png','2020-12-02 22:27:22',NULL,NULL,'0','admin','2020-12-03 04:27:22','2020-12-03 04:27:22','027557533881','2020-12-02 22:27:22',1269.00),
(71,3,NULL,'Interruptor de uso general lutron satin colors color media noche (SC-1PS-MN-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485456.jpg','2020-12-02 22:32:21',NULL,NULL,'0','admin','2020-12-03 04:32:21','2020-12-03 04:32:21','027557485456','2020-12-02 22:32:21',1242.00),
(72,26,NULL,'Sensor con interruptor de tecnologia doble con ubicacion multiple lutron maestro color taupe (MS-B102-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276079464.jpg','2020-12-02 22:46:22',NULL,NULL,'0','admin','2020-12-03 04:46:22','2020-12-03 04:46:22','784276079464','2020-12-02 22:46:22',1235.00),
(73,2,NULL,'Sensor de ocupacion con interruptor lutron maestro color blanco (MS-OPS2-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557982825.png','2020-12-02 22:52:41',NULL,NULL,'0','admin','2020-12-03 04:52:41','2020-12-03 04:52:41','027557982825','2020-12-02 22:52:41',1235.00),
(74,6,NULL,'Sensor de ocupacion/desocupacion con interruptor de multiples sitios lutron maestro color blanco (MS-OPS6M2N-DV-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276012713.jpg','2020-12-02 23:24:06',NULL,NULL,'0','admin','2020-12-03 05:24:06','2020-12-03 05:24:06','784276012713','2020-12-02 23:24:06',1235.00),
(75,4,NULL,'Interruptor electronico de 8A lutron satin colors color nieve/snow (MSC-S8AM-SW)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557232739.png','2020-12-02 23:57:30',NULL,NULL,'0','admin','2020-12-03 05:57:30','2020-12-03 05:57:30','027557232739','2020-12-02 23:57:30',1271.00),
(76,1,NULL,'Dimmer lutron satin colors 600W de multiples sitios color siena (MSC-600M-SI-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557615280.jpg','2020-12-03 16:24:12',NULL,NULL,'0','admin','2020-12-03 22:24:12','2020-12-03 22:24:12','027557615280','2020-12-03 16:24:12',1271.00),
(77,4,NULL,'Dimmer compañero lutron satin colors color piedra del desierto (MSC-AD-DS)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557802796.jpg','2020-12-08 23:31:01',NULL,NULL,'0','admin','2020-12-09 05:31:01','2020-12-09 05:31:01','027557802796','2020-12-08 23:31:01',1269.00),
(78,56,NULL,'Dimmer compañero lutron maestro color blanco (MA-R-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557687539.jpg','2020-12-08 23:34:21',NULL,NULL,'0','admin','2020-12-09 05:34:21','2020-12-09 05:34:21','027557687539','2020-12-08 23:34:21',NULL),
(79,1,'GWN7600LR','Punto de acceso de largo alcance para exteriores soporta hasta 450 usuarios color blanco','Grandstream','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/6947273702368.jpg','2020-12-08 23:47:44',NULL,NULL,'0','admin','2020-12-09 05:47:44','2020-12-09 05:47:44','6947273702368','2020-12-08 23:47:44',1330.00),
(80,2,'EWS1200D-10T','Switch Administrable Capa 2 de 8 Puertos Gigabit Serie Neutron, Negro','EnGenius','SWITCH','Bodega General',NULL,NULL,'/img/productos/655216008021.jpg','2020-12-08 23:55:21',NULL,NULL,'0','admin','2020-12-09 05:55:21','2020-12-09 05:55:21','655216008021','2020-12-08 23:55:21',1298.00),
(81,3,NULL,'Router vpn multi-wan gigabit con balanceador de carga 8 Grandstream (GWN7000)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/6947273702061.png','2020-12-09 18:00:37',NULL,NULL,'0','admin','2020-12-10 00:00:37','2020-12-10 00:00:37','6947273702061','2020-12-09 18:00:37',1324.00),
(82,2,NULL,'Extensor de rango punto de acceso insteon banda dual, banda de 915MHz (2443)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/689076406840.jpg','2020-12-09 19:25:54',NULL,NULL,'0','admin','2020-12-10 01:25:54','2020-12-10 01:25:54','689076406840','2020-12-09 19:25:54',1295.00),
(83,3,NULL,'Modem powerlinc de interfaz serial de doble banda plm insteon (2413S)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/718122390717.jpg','2020-12-09 19:31:59',NULL,NULL,'0','admin','2020-12-10 01:31:59','2020-12-10 01:31:59','718122390717','2020-12-09 19:31:59',1289.00),
(84,2,NULL,'Modem de linea electrica smartlabs Insteon (2412S)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/689076403047.jpg','2020-12-09 19:34:31',NULL,NULL,'0','admin','2020-12-10 01:34:31','2020-12-10 01:34:31','689076403047','2020-12-09 19:34:31',1289.00),
(85,2,NULL,'Teclado linc relay smarthome insteon, control de escena de 6 botones con encendido/apagado (2486SWH6)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/689076407847.jpg','2020-12-09 19:37:01',NULL,NULL,'0','admin','2020-12-10 01:37:01','2020-12-10 01:37:01','689076407847','2020-12-09 19:37:01',1295.00),
(86,3,NULL,'Divisor/splitter de video HDMI con 8 puertos, 4k x 2k 1x4, Epcom Titanuim color negro (TT314PRO)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/21116695.jpg','2020-12-09 21:01:02',NULL,NULL,'0','admin','2020-12-10 03:01:02','2020-12-10 03:01:02','21116695','2020-12-09 21:01:02',1329.00),
(87,6,'2420M','Sensor de movimiento inalambrico','Insteon','ALARMA','Bodega General',NULL,NULL,'/img/productos/718122388714.jpg','2020-12-09 21:34:20',NULL,NULL,'0','admin','2020-12-10 03:34:20','2020-12-10 03:34:20','718122388714','2020-12-09 21:34:20',1295.00),
(88,19,NULL,'Micro modulo de encendido/apagado insteon color blanco (2443-222)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922012736.jpg','2020-12-09 21:38:11',NULL,NULL,'0','admin','2020-12-10 03:38:11','2020-12-10 03:38:11','813922012736','2020-12-09 21:38:11',1294.00),
(89,11,NULL,'Atenuador/dimmer de doble banda lamplinc insteon (2457D2)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922010183.png','2020-12-09 21:40:52',NULL,NULL,'0','admin','2020-12-10 03:40:52','2020-12-10 03:40:52','813922010183','2020-12-09 21:40:52',NULL),
(90,4,NULL,'Modulo atenuador de lampara enchufable de 3 pines smarthome lamplinc insteon (2456D3)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/891114000051.png','2020-12-09 21:42:53',NULL,NULL,'0','admin','2020-12-10 03:42:53','2020-12-10 03:42:53','891114000051','2020-12-09 21:42:53',1295.00),
(91,7,NULL,'Controlador de bajo voltaje enchufable de control remoto insteon smartlabs (2450)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/689076403542.jpg','2020-12-09 21:48:43',NULL,NULL,'0','admin','2020-12-10 03:48:43','2020-12-10 03:48:43','689076403542','2020-12-09 21:48:43',1295.00),
(92,5,NULL,'Punto de acceso inalámbrico Ubiquiti air gateway installer color negro (AG-PRO-INS)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/810354022036.jpg','2020-12-09 22:55:41',NULL,NULL,'0','admin','2020-12-10 04:55:41','2020-12-10 04:55:41','810354022036','2020-12-09 22:55:41',1339.00),
(93,1,NULL,'Teclado keypadlinc insteon de 6 botones con 1800W on/off color blanco (2487S)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922011449.png','2020-12-10 20:49:08',NULL,NULL,'0','admin','2020-12-11 02:49:08','2020-12-11 02:49:08','813922011449','2020-12-10 20:49:08',1294.00),
(94,39,NULL,'Dimmer inteligente insteon color blanco (2477D)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/689076401746.png','2020-12-10 21:01:16',NULL,NULL,'0','admin','2020-12-11 03:01:16','2020-12-11 03:01:16','689076401746','2020-12-10 21:01:16',1294.00),
(95,14,NULL,'Modulo de encendido y apagado de tres clavijas con control remoto inalambrico inteligente insteon (2635-222)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922013368.png','2020-12-10 21:05:30',NULL,NULL,'0','admin','2020-12-11 03:05:30','2020-12-11 03:05:30','813922013368','2020-12-10 21:05:30',1294.00),
(96,26,NULL,'Interruptor de control remoto switchlink on/off insteon color blanco (2477S)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922012378.png','2020-12-10 21:44:44',NULL,NULL,'0','admin','2020-12-11 03:44:44','2020-12-11 03:44:44','813922012378','2020-12-10 21:44:44',1294.00),
(97,5,NULL,'Puente detector de humo caja blanca insteon (2982-222)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922013450.png','2020-12-10 21:49:48',NULL,NULL,'0','admin','2020-12-11 03:49:48','2020-12-11 03:49:48','813922013450','2020-12-10 21:49:48',1294.00),
(98,2,NULL,'Switch Epcom Titanium de 5 entradas HDMI a 1 salida HDMI 4k (TT501-V2.0)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/220400129.png','2020-12-10 22:28:40',NULL,NULL,'0','admin','2020-12-11 04:28:40','2020-12-11 04:28:40','220400129','2020-12-10 22:28:40',1329.00),
(99,1,'UAP-AC-PRO-E','Punto de acceso Unifi Ap Ac Pro sin inyector Poe','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/817882024051.jpg','2020-12-11 19:25:24',NULL,NULL,'0','admin','2020-12-12 01:25:24','2020-12-12 01:25:24','817882024051','2020-12-11 19:25:24',1334.00),
(100,2,NULL,'Receptor IR de control remoto insteon y convertidor de señal IR a insteon (2411R)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/689076402941.png','2020-12-11 21:20:57',NULL,NULL,'0','admin','2020-12-12 03:20:57','2020-12-12 03:20:57','689076402941','2020-12-11 21:20:57',1295.00),
(101,3,NULL,'Timerlinc insteon temporizador enchufable (2456S3T)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/718122389414.png','2020-12-11 21:47:31',NULL,NULL,'0','admin','2020-12-12 03:47:31','2020-12-12 03:47:31','718122389414','2020-12-11 21:47:31',1295.00),
(102,2,NULL,'Splitter HDMI 1x2 4k/60 Muxlab Prodigital (500425)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/627699004258.jpg','2020-12-11 22:11:33',NULL,NULL,'0','admin','2020-12-12 04:11:33','2020-12-12 04:11:33','627699004258','2020-12-11 22:11:33',1304.00),
(103,14,NULL,'Interfaz de cierre corcholata lutron qs wallbox (QSE-CI-WCI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276008419.png','2020-12-11 22:20:23',NULL,NULL,'0','admin','2020-12-12 04:20:23','2020-12-12 04:20:23','784276008419','2020-12-11 22:20:23',1237.00),
(104,4,NULL,'Enrutador empresarial de 3 puertos gigabit servidor VPN Ubquiti Unifi (USG)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'https://inventario.savicontrolhome.com/img/productos/810354020803.png','2020-12-11 22:24:58',NULL,'','0','admin','2020-12-12 04:24:58','2020-12-12 04:24:58','810354020803','2020-12-11 22:24:58',1334.00),
(105,3,NULL,'Mini teclado de control remoto de 4 escenas insteon color blanco (2444A2WH4)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922010701.png','2020-12-11 22:55:14',NULL,NULL,'0','admin','2020-12-12 04:55:14','2020-12-12 04:55:14','813922010701','2020-12-11 22:55:14',1295.00),
(106,6,NULL,'Controlador de ventilador de techo de doble banda insteon (2575F)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/813922011548.jpg','2020-12-11 23:11:52',NULL,NULL,'0','admin','2020-12-12 05:11:52','2020-12-12 05:11:52','813922011548','2020-12-11 23:11:52',1289.00),
(107,5,NULL,'Sensor inalambrico de puerta y ventana insteon abrir/cerrar (2421)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/718122388912.png','2020-12-11 23:13:56',NULL,NULL,'0','admin','2020-12-12 05:13:56','2020-12-12 05:13:56','718122388912','2020-12-11 23:13:56',1295.00),
(108,3,NULL,'Adaptador de termostato RF insteon color blanco (2441V)',NULL,'AIRE ACONDICIONADO','Bodega General',NULL,NULL,'/img/productos/2441V.png','2020-12-12 16:25:50',NULL,NULL,'0','admin','2020-12-12 22:25:50','2020-12-12 22:25:50','2441V','2020-12-12 16:25:50',1295.00),
(109,12,NULL,'Caja de tablero de teclado insteon color blanco (2402WH)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/2402WH.png','2020-12-12 16:29:25',NULL,NULL,'0','admin','2020-12-12 22:29:25','2020-12-12 22:29:25','2402WH','2020-12-12 16:29:25',1295.00),
(110,4,NULL,'Atenuador dimmer en linea insteon smarthome (2475D)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/689076402743.png','2020-12-12 17:36:33',NULL,NULL,'0','admin','2020-12-12 23:36:33','2020-12-12 23:36:33','689076402743','2020-12-12 17:36:33',1294.00),
(111,28,'CAB150-WH','Cable de uso rudo usb tipo c color blanco','1Hora','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/7503027214403.jpg','2020-12-14 19:43:51',NULL,NULL,'0','admin','2020-12-15 01:43:51','2020-12-15 01:43:51','7503027214403','2020-12-14 19:43:51',1354.00),
(112,2,NULL,'Kit extensor HDMI powerline  para hasta 300mts Epcom Titanium Pro (TT380PRO)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6955361233809.jpg','2020-12-14 21:50:10',NULL,NULL,'0','admin','2020-12-15 03:50:10','2020-12-15 03:50:10','6955361233809','2020-12-14 21:50:10',1304.00),
(113,1,NULL,'Transmisor/receptor HDMI extensor Binary (B-320-1CAT-HDIR)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/842822023221.jpg','2020-12-14 22:00:46',NULL,NULL,'0','admin','2020-12-15 04:00:46','2020-12-15 04:00:46','842822023221','2020-12-14 22:00:46',1329.00),
(114,3,NULL,'Dimmer compañero lutron satin colors color paladium (MSC-AD-PD)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557265850.png','2020-12-15 17:26:12',NULL,NULL,'0','admin','2020-12-15 23:26:12','2020-12-15 23:26:12','027557265850','2020-12-15 17:26:12',1269.00),
(115,6,NULL,'Amplificador de audio de estereo digital microfidelity, modelo 200 negro (MODEL200)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/804258557220.png','2020-12-15 17:42:44',NULL,NULL,'0','admin','2020-12-15 23:42:44','2020-12-15 23:42:44','804258557220','2020-12-15 17:42:44',1359.00),
(116,1,NULL,'Sistema wifi de malla Tenda Nova para todo el hogar, paquete de 3 color blanco (MW6-3PACK)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/885397270898.jpg','2020-12-15 22:20:44',NULL,NULL,'0','admin','2020-12-16 04:20:44','2020-12-16 04:20:44','885397270898','2020-12-15 22:20:44',1299.00),
(117,39,NULL,'Dimmer compañero Lutron Satin Colors, color Taupe (MSC-AD-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557803038.jpg','2020-12-17 22:19:30',NULL,NULL,'0','admin','2020-12-18 04:19:30','2020-12-18 04:19:30','027557803038','2020-12-17 22:19:30',1268.00),
(118,131,'SC-1-TP','Tapa de 1 ventana, color Taupe ()','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557508261.jpg','2020-12-17 22:39:57',NULL,NULL,'0','admin','2020-12-18 04:39:57','2020-12-18 04:39:57','027557508261','2020-12-17 22:39:57',1213.00),
(119,4,NULL,'Contacto duplex sencillo de 15A lutron satin colors color taupe (SCR-15-TP-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485845.png','2020-12-17 23:01:01',NULL,NULL,'0','admin','2020-12-18 05:01:01','2020-12-18 05:01:01','027557485845','2020-12-17 23:01:01',NULL),
(120,32,'SC-6PF-TP','Marco de 6 puertos, Satin Colors, color Taupe','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557061940.jpg','2020-12-17 23:09:25',NULL,NULL,'0','admin','2020-12-18 05:09:25','2020-12-18 05:09:25','027557061940','2020-12-17 23:09:25',1230.00),
(121,8,'SC-6-TP','Tapa de 6 ventanas, color Taupe','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557494830.jpg','2020-12-17 23:15:39',NULL,NULL,'0','admin','2020-12-18 05:15:39','2020-12-18 05:15:39','027557494830','2020-12-17 23:15:39',1228.00),
(122,4,NULL,'Contacto duplex falla tierra de 15A GFCI con proteccion para niños lutron satin colors color biscuit (SCR-15-GFST-BI-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276159319.png','2020-12-17 23:49:25',NULL,NULL,'0','admin','2020-12-18 05:49:25','2020-12-18 05:49:25','784276159319','2020-12-17 23:49:25',1263.00),
(123,30,'SCR-15-BI-L','Contacto duplex sencillo de 15A, Satin Colors, color biscuit','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485739.png','2020-12-18 16:47:34',NULL,NULL,'0','admin','2020-12-18 22:47:34','2020-12-18 22:47:34','027557485739','2020-12-18 16:47:34',1267.00),
(124,19,'SC-6PF-BI','Marco de 6 puertos, Satin Colors, color Biscuit','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557061971.png','2020-12-18 17:17:52',NULL,NULL,'0','admin','2020-12-18 23:17:52','2020-12-18 23:17:52','027557061971','2020-12-18 17:17:52',1230.00),
(125,10,'SC-6-BI','Tapa de 6 ventanas, color Biscuit','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557494694.png','2020-12-18 20:37:00',NULL,NULL,'0','admin','2020-12-19 02:37:00','2020-12-19 02:37:00','027557494694','2020-12-18 20:37:00',1228.00),
(126,7,'SC-4-TP','Tapa de 4 ventanas, color Taupe','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475648.png','2020-12-18 20:37:29',NULL,NULL,'0','admin','2020-12-19 02:37:29','2020-12-19 02:37:29','027557475648','2020-12-18 20:37:29',1228.00),
(127,2,NULL,'Micrófono de calibración audyssey para denon/marantz receptor onkyo (ACM1HB)',NULL,'MICROFONO','Bodega General',NULL,NULL,'/img/productos/ACM1HB.jpg','2020-12-21 22:12:26',NULL,NULL,'0','admin','2020-12-22 04:12:26','2020-12-22 04:12:26','ACM1HB','2020-12-21 22:12:26',1354.00),
(128,2,NULL,'Convertidor/conversor de audio de fibra óptica a rca para audio marca Binary digital a analógico (B-220-DAC)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/842822020664.jpg','2020-12-21 22:23:07',NULL,NULL,'0','admin','2020-12-22 04:23:07','2020-12-22 04:23:07','842822020664','2020-12-21 22:23:07',1329.00),
(129,1,NULL,'Control de volumen de altavoz Steren color blanco (AT-50)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/7501483186234.png','2020-12-21 22:49:32',NULL,NULL,'0','admin','2020-12-22 04:49:32','2020-12-22 04:49:32','7501483186234','2020-12-21 22:49:32',1354.00),
(130,1,NULL,'Control de volumen giratorio comercial de 70V Episode (EA-MR-COMM-RVC-50)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/842822023702.png','2020-12-21 22:50:59',NULL,NULL,'0','admin','2020-12-22 04:50:59','2020-12-22 04:50:59','842822023702','2020-12-21 22:50:59',1354.00),
(131,23,NULL,'Interruptor de uso general, 3 vias 15A, lutron satin colors color biscuit (SC-3PS-BI-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485470.png','2020-12-23 21:20:11',NULL,NULL,'0','admin','2020-12-24 03:20:11','2020-12-24 03:20:11','027557485470','2020-12-23 21:20:11',1242.00),
(132,16,NULL,'Botonera lutron de 4 botones homeworksQS color blanco (HQWT-U-P4W-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276113090.png','2020-12-24 16:37:12',NULL,NULL,'0','admin','2020-12-24 22:37:12','2020-12-24 22:37:12','784276113090','2020-12-24 16:37:12',1270.00),
(133,1,NULL,'Splitter HDMI 1x4 de 4 puertos para compatibilidad con full HD 1080p y 3D Rei (HD-104)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/5011402183159.jpg','2020-12-28 21:05:25',NULL,NULL,'0','admin','2020-12-29 03:05:25','2020-12-29 03:05:25','5011402183159','2020-12-28 21:05:25',1304.00),
(134,1,NULL,'Adaptador voip de teléfono analógico 1-FXS/1-FXO-Port con router ingrado, Granstream (HT503)',NULL,'TELEFONIA','Bodega General',NULL,NULL,'/img/productos/6947273700074.png','2020-12-28 21:07:26',NULL,NULL,'0','admin','2020-12-29 03:07:26','2020-12-29 03:07:26','6947273700074','2020-12-28 21:07:26',1330.00),
(135,16,'UAP-AC-M','Punto de acceso para exterior, antena de conejo Unifi mesh, doble blanda 2.4 o 5 GHZ','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/817882020565.jpg','2020-12-28 21:20:31',NULL,NULL,'0','admin','2020-12-29 03:20:31','2020-12-29 03:20:31','817882020565','2020-12-28 21:20:31',1338.00),
(136,1,NULL,'Citófono digital, auricular central de portería, 2voice Urmet, conexión 2 hilos no polarizados, botón apertura de puerta y botón de llamado (1183/5)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/8021156053704.jpg','2020-12-29 22:35:21',NULL,NULL,'0','admin','2020-12-30 04:35:21','2020-12-30 04:35:21','8021156053704','2020-12-29 22:35:21',1324.00),
(137,7,NULL,'Distribuidor Urmet domus spa 4 usuarios 2voice (1083/55)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/8021156044269.jpg','2020-12-29 22:40:28',NULL,NULL,'0','admin','2020-12-30 04:40:28','2020-12-30 04:40:28','8021156044269','2020-12-29 22:40:28',1324.00),
(138,3,NULL,'Frente Urmet 1 boton solo tapa para 2voice (1083/107)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/8021156057085.jpg','2020-12-29 22:42:21',NULL,NULL,'0','admin','2020-12-30 04:42:21','2020-12-30 04:42:21','8021156057085','2020-12-29 22:42:21',1324.00),
(139,2,NULL,'Monitor de color manos libres 2voice color blanco Urmet (1750/6)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/1750-6.jpg','2020-12-29 22:49:40',NULL,NULL,'0','admin','2020-12-30 04:49:40','2020-12-30 04:49:40','1750/6','2020-12-29 22:49:40',1324.00),
(140,2,NULL,'Kit extensor HDMI matricial 4K x 2K, 30Hz sobre cat5/6 a 120/150 metros Epcom Titanium (TT683MATRIX-RX)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/20052364.png','2020-12-30 19:22:34',NULL,NULL,'0','admin','2020-12-31 01:22:34','2020-12-31 01:22:34','20052364','2020-12-30 19:22:34',1329.00),
(141,4,NULL,'Adaptador ethernet de alta velocidad Tp-Link usb 3.0 a gigabit  (UE300)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/845973091743.jpg','2021-01-04 20:43:42',NULL,NULL,'0','admin','2021-01-05 02:43:42','2021-01-05 02:43:42','845973091743','2021-01-04 20:43:42',1339.00),
(142,1,NULL,'Terminal GPON de 1 puerto gigabit Tp-Link (TX-6610)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/845973060978.jpg','2021-01-04 20:45:31',NULL,NULL,'0','admin','2021-01-05 02:45:31','2021-01-05 02:45:31','845973060978','2021-01-04 20:45:31',1315.00),
(143,0,NULL,'Router inalámbrico, 1 puerto GPON SC/APC, 2 puertos LAN (1GE/1FE) y 1 puerto POST (FXS) Tp-Link (XN020-G3V)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/845973086282.jpg','2021-01-04 20:52:50',NULL,NULL,'0','admin','2021-01-05 02:52:50','2021-01-05 02:52:50','845973086282','2021-01-04 20:52:50',1315.00),
(144,6,'UAP-AC-LR-5','Paquete de 5 puntos de acceso UniFi de largo alcance, Doble banda 802.11ac MIMO2X2 para interior, PoE 802.3af, soporta 250 clientes, hasta 867 Mbps, Los equipos no incluyen poe se sugiere comprar aparte el modelo POE-24-12W-G \\r\\n','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/810354025457.jpg','2021-01-04 21:40:04',NULL,NULL,'0','admin','2021-01-05 03:40:04','2021-01-05 03:40:04','810354025457','2021-01-04 21:40:04',1343.00),
(145,5,NULL,'Sistema wi-fi en malla para todo el hogar, Deco E4 Tp Llink, paquete de 3 (DECOE4)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/840030700170.jpg','2021-01-04 22:28:35',NULL,NULL,'0','admin','2021-01-05 04:28:35','2021-01-05 04:28:35','840030700170','2021-01-04 22:28:35',1298.00),
(146,21,NULL,'Montaje para camara Dahua color blanca (DH-PFA134)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/6939554903502.jpg','2021-01-05 17:22:23',NULL,NULL,'0','admin','2021-01-05 23:22:23','2021-01-05 23:22:23','6939554903502','2021-01-05 17:22:23',1335.00),
(147,2,NULL,'Cámara bala IR a color exir turbo hd gris Epcom (LB7TURBOEXIR2)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/153207349.jpg','2021-01-05 17:50:34',NULL,NULL,'0','admin','2021-01-05 23:50:34','2021-01-05 23:50:34','153207349','2021-01-05 17:50:34',1341.00),
(148,1,NULL,'Detector de rayo fotoeléctrico de 3 rayos/4 frecuencias/100mts exterior/ alimentación 12-24 vcd (SFE100)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/SFE100.png','2021-01-05 23:18:20',NULL,NULL,'0','admin','2021-01-06 05:18:20','2021-01-06 05:18:20','SFE100','2021-01-05 23:18:20',1364.00),
(149,3,NULL,'Modulo de relevadores inalámbrico con 2 salidas de alarma para panel de alarma Hikvision color blanco (DS-PM-WO2)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6941264014188.jpg','2021-01-06 16:20:23',NULL,NULL,'0','admin','2021-01-06 22:20:23','2021-01-06 22:20:23','6941264014188','2021-01-06 16:20:23',1364.00),
(150,1,NULL,'Cámara inalámbrica IP en la nube para día y noche Tenda (C50+)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6932849434095.jpg','2021-01-06 16:28:08',NULL,NULL,'0','admin','2021-01-06 22:28:08','2021-01-06 22:28:08','6932849434095','2021-01-06 16:28:08',1341.00),
(151,1,NULL,'Cámara domo 720p con micrófono incluido Unifi Ubiquiti (UVC-DOME)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/810354020483.jpg','2021-01-06 17:04:49',NULL,NULL,'0','admin','2021-01-06 23:04:49','2021-01-06 23:04:49','810354020483','2021-01-06 17:04:49',1346.00),
(152,1,NULL,'Cámara domo eyeball Epcom para interior/exterior antivandálico varifocal, IR inteligente de 40M, color gris oscuro (HRE800V)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/514818321.jpg','2021-01-06 20:50:10',NULL,NULL,'0','admin','2021-01-07 02:50:10','2021-01-07 02:50:10','514818321','2021-01-06 20:50:10',1346.00),
(153,1,NULL,'Mini cámara turret TURBO Hikvision 1080p/ lente 2.8mm/ 20M IR EXIR (DS-2CE70D0T-ITMF)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6941264009795.jpg','2021-01-06 21:26:13',NULL,NULL,'0','admin','2021-01-07 03:26:13','2021-01-07 03:26:13','6941264009795','2021-01-06 21:26:13',1346.00),
(154,1,NULL,'Detector de humo inalámbrico para panel Hikvision (DS-PD1-SMK-W)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6954273685867.png','2021-01-07 19:00:27',NULL,NULL,'0','admin','2021-01-08 01:00:27','2021-01-08 01:00:27','6954273685867','2021-01-07 19:00:27',1367.00),
(155,1,NULL,'Panel de alarma Honeywell inalámbrico autocontenido con pantalla touch (L5210PK)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/886618221781.png','2021-01-07 22:18:39',NULL,NULL,'0','admin','2021-01-08 04:18:39','2021-01-08 04:18:39','886618221781','2021-01-07 22:18:39',1364.00),
(156,2,NULL,'Control remoto para alarma tipo llavero marca Honeywell color negro (5834-4)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/781410995369.jpg','2021-01-08 19:24:00',NULL,NULL,'0','admin','2021-01-09 01:24:00','2021-01-09 01:24:00','781410995369','2021-01-08 19:24:00',1363.00),
(157,1,NULL,'Multisensor dual Zipato detector de temperatura y movimiento (VS-ZP3102+.US)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/3858890730289.png','2021-01-08 19:57:23',NULL,NULL,'0','admin','2021-01-09 01:57:23','2021-01-09 01:57:23','3858890730289','2021-01-08 19:57:23',1362.00),
(158,1,NULL,'Kit de localizador de marcador de emergencia, alerta de marcado completo SkyLlink (ED-100W)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/623459401251.png','2021-01-08 21:03:17',NULL,NULL,'0','admin','2021-01-09 03:03:17','2021-01-09 03:03:17','623459401251','2021-01-08 21:03:17',1368.00),
(159,2,NULL,'Receptor de alarma inalámbrico de zona ilimitada Honeywell (5881EN)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/781410318588.png','2021-01-09 16:31:27',NULL,NULL,'0','admin','2021-01-09 22:31:27','2021-01-09 22:31:27','781410318588','2021-01-09 16:31:27',NULL),
(160,10,NULL,'Sensor de temperatura y humedad orvibo (ST21)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6928986702975.png','2021-01-11 19:51:48',NULL,NULL,'0','admin','2021-01-12 01:51:48','2021-01-12 01:51:48','6928986702975','2021-01-11 19:51:48',1293.00),
(161,6,'SC-5-BI','Tapa de 5 ventanas, color Biscuit','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475839.png','2021-01-11 20:57:18',NULL,NULL,'0','admin','2021-01-12 02:57:18','2021-01-12 02:57:18','027557475839','2021-01-11 20:57:18',1228.00),
(162,4,'SC-5-SW','Tapa de 5 ventanas, color Nieve/Snow','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475990.png','2021-01-11 20:59:05',NULL,NULL,'0','admin','2021-01-12 02:59:05','2021-01-12 02:59:05','027557475990','2021-01-11 20:59:05',1228.00),
(163,17,'SC-5-TP','Tapa de 5 ventanas, color Taupe','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475976.png','2021-01-11 21:00:32',NULL,NULL,'0','admin','2021-01-12 03:00:32','2021-01-12 03:00:32','027557475976','2021-01-11 21:00:32',1228.00),
(164,6,'80312-SE','Tapa  de 4 ventanas, color Negro','Leviton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/078477204610.png','2021-01-11 21:28:41',NULL,NULL,'0','admin','2021-01-12 03:28:41','2021-01-12 03:28:41','078477204610','2021-01-11 21:28:41',1220.00),
(165,19,'PJS266W','Tapa de 6 ventanas, color blanco','Cooper','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/032664617225.png','2021-01-11 21:37:41',NULL,NULL,'0','admin','2021-01-12 03:37:41','2021-01-12 03:37:41','032664617225','2021-01-11 21:37:41',1215.00),
(166,14,'80326-SW','Tapa de 6 ventanas, color blanco','Leviton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/078477160336.png','2021-01-11 21:40:08',NULL,NULL,'0','admin','2021-01-12 03:40:08','2021-01-12 03:40:08','078477160336','2021-01-11 21:40:08',NULL),
(167,32,'PJS265W','Tapa de 5 ventanas, color Blanco','Cooper','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/032664617171.png','2021-01-11 21:42:34',NULL,NULL,'0','admin','2021-01-12 03:42:34','2021-01-12 03:42:34','032664617171','2021-01-11 21:42:34',1215.00),
(168,24,'80321-SW','Tapa de 5 ventanas, color Blanco','Leviton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/078477160329.png','2021-01-11 21:54:07',NULL,NULL,'0','admin','2021-01-12 03:54:07','2021-01-12 03:54:07','078477160329','2021-01-11 21:54:07',1215.00),
(169,6,'PJS262W','Tapa de 2 ventanas, color Blanco','Eaton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/032664751615.png','2021-01-11 22:32:11',NULL,NULL,'0','admin','2021-01-12 04:32:11','2021-01-12 04:32:11','032664751615','2021-01-11 22:32:11',1215.00),
(170,77,'PJS263W','Tapa de 3 ventanas, color Blanco','Cooper','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/032664617072.png','2021-01-11 22:35:32',NULL,NULL,'0','admin','2021-01-12 04:35:32','2021-01-12 04:35:32','032664617072','2021-01-11 22:35:32',1215.00),
(171,11,'80311-SE','Tapa de 3 ventanas, color Negro','Leviton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/078477204580.png','2021-01-11 22:41:38',NULL,NULL,'0','admin','2021-01-12 04:41:38','2021-01-12 04:41:38','078477204580','2021-01-11 22:41:38',1220.00),
(172,30,'80311-SW','Tapa de 3 ventanas, color Blanco','Leviton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/078477160312.png','2021-01-11 22:46:04',NULL,NULL,'0','admin','2021-01-12 04:46:04','2021-01-12 04:46:04','078477160312','2021-01-11 22:46:04',1215.00),
(173,3,NULL,'Dimmer lutron radiora2 pro color taupe (RRD-PRO-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276270458.png','2021-01-11 23:05:15',NULL,NULL,'0','admin','2021-01-12 05:05:15','2021-01-12 05:05:15','784276270458','2021-01-11 23:05:15',1267.00),
(174,5,NULL,'Switch lutron radiora2 color biscuit (RRD-8ANS-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557771580.png','2021-01-11 23:11:44',NULL,NULL,'0','admin','2021-01-12 05:11:44','2021-01-12 05:11:44','027557771580','2021-01-11 23:11:44',1267.00),
(175,0,NULL,'Switch lutron radiora2 color media noche (RRD-8ANS-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-01-11 23:13:59',NULL,NULL,'0','admin','2021-01-12 05:13:59','2021-01-12 05:13:59','027557771719','2021-01-11 23:13:59',1267.00),
(176,2,NULL,'Control de ventilador de techo lutron radiora2 color biscuit (RRD-2ANF-BI)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/784276040242.jpg','2021-01-12 20:58:07',NULL,NULL,'0','admin','2021-01-13 02:58:07','2021-01-13 02:58:07','784276040242','2021-01-12 20:58:07',1271.00),
(177,33,NULL,'Contacto duplex falla tierra de 15A GFCI con proteccion para niños lutron satin colors color taupe (SCR-15-GFST-TP-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276159487.png','2021-01-12 21:00:54',NULL,NULL,'0','admin','2021-01-13 03:00:54','2021-01-13 03:00:54','784276159487','2021-01-12 21:00:54',1263.00),
(178,1,NULL,'Contacto duplex USB de 15A con proteccion para niños lutron satin colors color siena (SCR-15-UBTR-SI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276166348.png','2021-01-12 21:31:44',NULL,NULL,'0','admin','2021-01-13 03:31:44','2021-01-13 03:31:44','784276166348','2021-01-12 21:31:44',1263.00),
(179,8,NULL,'Contacto duplex falla tierra de 15A GFCI con proteccion para niños lutron satin colors color media noche (SCR-15-GFST-MN-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276159401.png','2021-01-12 21:52:07',NULL,NULL,'0','admin','2021-01-13 03:52:07','2021-01-13 03:52:07','784276159401','2021-01-12 21:52:07',1263.00),
(180,5,NULL,'Atenuador de lampara RF lutron radioRa2 color nieve/snow (RRD-3LD-SW)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557774697.png','2021-01-12 21:56:26',NULL,NULL,'0','admin','2021-01-13 03:56:26','2021-01-13 03:56:26','027557774697','2021-01-12 21:56:26',1265.00),
(181,1,NULL,'Botonera lutron de 5 botones radioRa2 color biscuit (RRD-W5BRL-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557989015.png','2021-01-13 16:35:43',NULL,NULL,'0','admin','2021-01-13 22:35:43','2021-01-13 22:35:43','027557989015','2021-01-13 16:35:43',1271.00),
(182,2,NULL,'Botonera hibrida lutron de 5 botones radioRa2 color blanco (RRD-H5BRL-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557986649.png','2021-01-13 16:46:58',NULL,NULL,'0','admin','2021-01-13 22:46:58','2021-01-13 22:46:58','027557986649','2021-01-13 16:46:58',1271.00),
(183,2,NULL,'Interruptor de uso general, 4 vias 15A, lutron satin colors color taupe (SC-4PS-TP-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485722.png','2021-01-13 17:53:41',NULL,NULL,'0','admin','2021-01-13 23:53:41','2021-01-13 23:53:41','027557485722','2021-01-13 17:53:41',1238.00),
(184,42,NULL,'Interruptor de uso general, 4 vias 15A, lutron satin colors color media noche (SC-4PS-MN-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485715.png','2021-01-13 17:55:33',NULL,NULL,'0','admin','2021-01-13 23:55:33','2021-01-13 23:55:33','027557485715','2021-01-13 17:55:33',1238.00),
(185,20,'SCR-15-MN-L','Contacto duplex sencillo de 15A, Satin Colors, color media noche','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485838.png','2021-01-13 18:00:25',NULL,NULL,'0','admin','2021-01-14 00:00:25','2021-01-14 00:00:25','027557485838','2021-01-13 18:00:25',1267.00),
(186,3,NULL,'Contacto duplex sencillo de 15A lutron satin colors color piedra/stone (SCR-15-ST-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485753.png','2021-01-13 18:04:05',NULL,NULL,'0','admin','2021-01-14 00:04:05','2021-01-14 00:04:05','027557485753','2021-01-13 18:04:05',1267.00),
(187,1,NULL,'Contacto duplex sencillo de 15A lutron satin colors color siena (SCR-15-SI-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557222976.png','2021-01-13 18:05:13',NULL,NULL,'0','admin','2021-01-14 00:05:13','2021-01-14 00:05:13','027557222976','2021-01-13 18:05:13',1264.00),
(188,1,'SC-6PF-SI','Marco de 6 puertos, Satin Colors, color Siena','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557175173.png','2021-01-13 19:39:27',NULL,NULL,'0','admin','2021-01-14 01:39:27','2021-01-14 01:39:27','027557175173','2021-01-13 19:39:27',1230.00),
(189,11,'SC-6PF-MN','Marco de 6 puertos, Satin Colors, color Media Noche','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557061933.png','2021-01-13 20:16:23',NULL,NULL,'0','admin','2021-01-14 02:16:23','2021-01-14 02:16:23','027557061933','2021-01-13 20:16:23',1230.00),
(190,21,NULL,'Contacto dúplex USB de 15A con protección para niños lutron claro color almendra claro (CAR-15-UBTR-LA)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276166089.png','2021-01-13 20:49:44',NULL,NULL,'0','admin','2021-01-14 02:49:44','2021-01-14 02:49:44','784276166089','2021-01-13 20:49:44',1263.00),
(191,26,NULL,'Control de velocidad de ventilador lutron, silencioso de 3 velocidades con interruptor de luz unipolar color taupe (DVSCFSQ-F-TP-L)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/027557484169.jpg','2021-01-13 20:59:35',NULL,NULL,'0','admin','2021-01-14 02:59:35','2021-01-14 02:59:35','027557484169','2021-01-13 20:59:35',1264.00),
(192,6,NULL,'Interruptor de uso general, 3 vias 15A, lutron satin colors color piedra/stone (SC-3PS-ST-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485494.png','2021-01-13 21:02:31',NULL,NULL,'0','admin','2021-01-14 03:02:31','2021-01-14 03:02:31','027557485494','2021-01-13 21:02:31',1238.00),
(193,3,'CA-6PF-GR','Marco de 6 puertos, linea Claro, color Gris','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557061919.png','2021-01-13 21:13:41',NULL,NULL,'0','admin','2021-01-14 03:13:41','2021-01-14 03:13:41','027557061919','2021-01-13 21:13:41',1230.00),
(194,32,NULL,'Interruptor de uso general, 3 vias 15A, lutron claro color blanco (CA-3PS-WH-S)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276016285.png','2021-01-13 23:44:25',NULL,NULL,'0','admin','2021-01-14 05:44:25','2021-01-14 05:44:25','784276016285','2021-01-13 23:44:25',1242.00),
(195,9,NULL,'Dimmer lutron diva CL 150W color taupe (DVSCCL-153P-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557003247.png','2021-01-14 19:14:22',NULL,NULL,'0','admin','2021-01-15 01:14:22','2021-01-15 01:14:22','027557003247','2021-01-14 19:14:22',1268.00),
(196,9,NULL,'Dimmer lutron diva CL 150W color nieve/snow (DVSCCL-153P-SW)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557003223.png','2021-01-14 19:17:02',NULL,NULL,'0','admin','2021-01-15 01:17:02','2021-01-15 01:17:02','027557003223','2021-01-14 19:17:02',1264.00),
(197,49,NULL,'Dimmer lutron vive maestro wireless RF para bombillas LFCA/LED atenuables multiples sitios color blanco (MRF2S-6CL-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276147873.png','2021-01-14 19:54:14',NULL,NULL,'0','admin','2021-01-15 01:54:14','2021-01-15 01:54:14','784276147873','2021-01-14 19:54:14',1238.00),
(198,14,NULL,'Dimmer lutron vive maestro wireless RF con interruptor electronico color blanco (MRF2S-6ANS-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276151030.png','2021-01-14 20:04:35',NULL,NULL,'0','admin','2021-01-15 02:04:35','2021-01-15 02:04:35','784276151030','2021-01-14 20:04:35',1238.00),
(199,30,NULL,'Dimmer lutron vive maestro wireless RF con interruptor electronico color media noche (MRF2S-6ANS-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276164078.png','2021-01-14 20:07:09',NULL,NULL,'0','admin','2021-01-15 02:07:09','2021-01-15 02:07:09','784276164078','2021-01-14 20:07:09',1238.00),
(200,9,NULL,'Interruptor/switch lutron vive maestro wireless RF multiples sitios color blanco (MRF2S-8ANS120-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276148399.png','2021-01-14 20:23:07',NULL,NULL,'0','admin','2021-01-15 02:23:07','2021-01-15 02:23:07','784276148399','2021-01-14 20:23:07',1238.00),
(201,23,NULL,'Interruptor de uso general, 4 vias 15A, lutron claro color blanco (CA-4PS-WH-S)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276016421.png','2021-01-18 16:44:55',NULL,NULL,'0','admin','2021-01-18 22:44:55','2021-01-18 22:44:55','784276016421','2021-01-18 16:44:55',1242.00),
(202,30,NULL,'Contacto duplex sencillo de 15A lutron claro gloss finish color blanco (CAR-15H-WH-S)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557368285.png','2021-01-18 16:55:08',NULL,NULL,'0','admin','2021-01-18 22:55:08','2021-01-18 22:55:08','027557368285','2021-01-18 16:55:08',1265.00),
(203,2,NULL,'Control de velocidad de ventilador lutron, silencioso de 3 velocidades con interruptor de luz unipolar color piedra caliza/limestone (DVSCFSQ-LF-ST)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/784276229883.jpg','2021-01-18 21:02:17',NULL,NULL,'0','admin','2021-01-19 03:02:17','2021-01-19 03:02:17','784276229883','2021-01-18 21:02:17',1263.00),
(204,15,'US-8-150W','Switch Unifi de 8 puertos PoE gigabit administrado con SFP 150W ()','Ubiquiti','SWITCH','Bodega General',NULL,NULL,'/img/productos/810354024450.jpg','2021-01-18 21:43:04',NULL,NULL,'0','admin','2021-01-19 03:43:04','2021-01-19 03:43:04','810354024450','2021-01-18 21:43:04',1313.00),
(205,0,'US-8-60W','Switch de 8 puertos 4 puertos PoE manejable par trenzado 60W Unifi','Ubiquiti','SWITCH','Bodega General',NULL,NULL,'/img/productos/810354026164.jpg','2021-01-18 21:48:35',NULL,NULL,'0','admin','2021-01-19 03:48:35','2021-01-19 03:48:35','810354026164','2021-01-18 21:48:35',1303.00),
(206,1,NULL,'Modulo de relay RF con softswitch lutron color blanco (LMJ-16R-DV-B)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276070010.png','2021-01-19 19:35:47',NULL,NULL,'0','admin','2021-01-20 01:35:47','2021-01-20 01:35:47','784276070010','2021-01-19 19:35:47',1237.00),
(207,2,NULL,'Atenuador/table top enchufable de RF lutron homeworks qs color nieve (HQR-3PD-1-SW)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557449663.png','2021-01-19 19:56:44',NULL,NULL,'0','admin','2021-01-20 01:56:44','2021-01-20 01:56:44','027557449663','2021-01-19 19:56:44',1237.00),
(208,27,NULL,'Transporte óptico de datos para dispositivos PoE de exteriores Ubiquiti (F-POE-G2)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/817882024686.jpg','2021-01-19 22:28:51',NULL,NULL,'0','admin','2021-01-20 04:28:51','2021-01-20 04:28:51','817882024686','2021-01-19 22:28:51',1314.00),
(209,1,'ET1008EPE','Switch con PoE 100M, 8 puertos 10/100Mbps + 1 puerto uplink, Epcom Titanium','Epcom','SWITCH','Bodega General',NULL,NULL,'/img/productos/ET1008EPE.png','2021-01-19 22:31:38',NULL,NULL,'0','admin','2021-01-20 04:31:38','2021-01-20 04:31:38','ET1008EPE','2021-01-19 22:31:38',1298.00),
(210,2,NULL,'Cliente VPN para comunicar la red del hogar implementada con routers amplifi amplificador teleport Ubiquiti (AFI-T)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/817882024266.jpg','2021-01-19 22:40:15',NULL,NULL,'0','admin','2021-01-20 04:40:15','2021-01-20 04:40:15','817882024266','2021-01-19 22:40:15',1358.00),
(211,1,NULL,'Mini enchufe interior inteligente wifi tp-link color blanco (HS105)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/840030700255.png','2021-01-19 23:04:28',NULL,NULL,'0','admin','2021-01-20 05:04:28','2021-01-20 05:04:28','840030700255','2021-01-19 23:04:28',1288.00),
(212,1,NULL,'Switch inteligente mixpad S orvibo (V20X)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/6928986703354.png','2021-01-19 23:07:15',NULL,NULL,'0','admin','2021-01-20 05:07:15','2021-01-20 05:07:15','6928986703354','2021-01-19 23:07:15',1292.00),
(213,0,NULL,'Transceptor óptico instantáneo ufiber Ubiquiti (UF-INSTANT)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/817882029612.jpg','2021-01-19 23:29:07',NULL,NULL,'0','admin','2021-01-20 05:29:07','2021-01-20 05:29:07','817882029612','2021-01-19 23:29:07',1339.00),
(214,5,NULL,'Receptaculo doble orvibo color blanco (ZB15R)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/ZB15R.png','2021-01-19 23:42:12',NULL,NULL,'0','admin','2021-01-20 05:42:12','2021-01-20 05:42:12','ZB15R','2021-01-19 23:42:12',1293.00),
(215,4,NULL,'Control remoto wifi IR cubo magico orvibo (CT10W-B1VO)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/6928986701497.png','2021-01-20 21:53:52',NULL,NULL,'0','admin','2021-01-21 03:53:52','2021-01-21 03:53:52','6928986701497','2021-01-20 21:53:52',1293.00),
(216,1,NULL,'Control remoto wifi magic dot orvibo (CT30W)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/6928986703286.png','2021-01-20 21:55:29',NULL,NULL,'0','admin','2021-01-21 03:55:29','2021-01-21 03:55:29','6928986703286','2021-01-20 21:55:29',1293.00),
(217,4,NULL,'Controlador inteligente de aire acondicionado airmaster pro orvibo (CV40ZW)',NULL,'AIRE ACONDICIONADO','Bodega General',NULL,NULL,'/img/productos/6928986703019.png','2021-01-20 22:00:22',NULL,NULL,'0','admin','2021-01-21 04:00:22','2021-01-21 04:00:22','6928986703019','2021-01-20 22:00:22',1293.00),
(218,1,NULL,'Mixswitch zigbee interruptor 1 gang orvibo (T40W1ZW)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/6928986703910_1.png','2021-01-20 23:26:38',NULL,NULL,'0','admin','2021-01-21 05:26:38','2021-01-21 05:26:38','6928986703910','2021-01-20 23:26:38',1293.00),
(219,1,NULL,'Boton de emergencia inalambrico zigbee orvibo (SE21)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/6928986702937.png','2021-01-20 23:30:06',NULL,NULL,'0','admin','2021-01-21 05:30:06','2021-01-21 05:30:06','6928986702937','2021-01-20 23:30:06',1293.00),
(220,3,NULL,'Switch inteligente mixpad mini orvibo (V30X)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/6928986703637.png','2021-01-20 23:37:31',NULL,NULL,'0','admin','2021-01-21 05:37:31','2021-01-21 05:37:31','6928986703637','2021-01-20 23:37:31',1293.00),
(221,16,NULL,'Sensor de fuga de agua inteligente orvibo (SW30)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/6928986703460.png','2021-01-20 23:57:35',NULL,NULL,'0','admin','2021-01-21 05:57:35','2021-01-21 05:57:35','6928986703460','2021-01-20 23:57:35',1293.00),
(222,4,NULL,'Interruptor de escena de pared orvibo color blanco (ZBSC7)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/ZBSC7.png','2021-01-21 16:35:59',NULL,NULL,'0','admin','2021-01-21 22:35:59','2021-01-21 22:35:59','ZBSC7','2021-01-21 16:35:59',1293.00),
(223,1,NULL,'Comando de luces inalambrico SMART con baterias 2 mod, incluye chasis bticino (K4003CW)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/8005543612491.png','2021-01-21 19:32:01',NULL,NULL,'0','admin','2021-01-22 01:32:01','2021-01-22 01:32:01','8005543612491','2021-01-21 19:32:01',1290.00),
(224,1,NULL,'Comando de persianas inalambrico SMART con baterias 2 mod, incluye chasis bticino (K4027CW)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/8005543612958.png','2021-01-21 19:33:30',NULL,NULL,'0','admin','2021-01-22 01:33:30','2021-01-22 01:33:30','8005543612958','2021-01-21 19:33:30',1290.00),
(225,16,NULL,'Placa living now 3 modulos color blanco material: tecnopolimero bticino (KA4803KW)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543613788.png','2021-01-21 20:19:04',NULL,NULL,'0','admin','2021-01-22 02:19:04','2021-01-22 02:19:04','8005543613788','2021-01-21 20:19:04',NULL),
(226,29,NULL,'Placa living now 2 modulos color blanco material: tecnopolimero bticino (KA4802KW)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543612712.png','2021-01-21 20:59:50',NULL,NULL,'0','admin','2021-01-22 02:59:50','2021-01-22 02:59:50','8005543612712','2021-01-21 20:59:50',1295.00),
(227,0,'UAP-AC-LITE','Punto de acceso de disco wlan de doble banda inyector poe','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/810354025327.png','2021-01-21 22:08:44',NULL,NULL,'0','admin','2021-01-22 04:08:44','2021-01-22 04:08:44','810354025327','2021-01-21 22:08:44',1338.00),
(228,6,NULL,'Carcasa protectora para videoporteros IP Hikvision (DS-KABV8113-RS)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/6941264032700.png','2021-01-21 22:11:40',NULL,NULL,'0','admin','2021-01-22 04:11:40','2021-01-22 04:11:40','6941264032700','2021-01-21 22:11:40',1367.00),
(229,3,NULL,'Contacto duplex USB de 15A con proteccion para niños lutron satin colors color biscuit (SCR-15-UBTR-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276166218.png','2021-01-22 20:58:19',NULL,NULL,'0','admin','2021-01-23 02:58:19','2021-01-23 02:58:19','784276166218','2021-01-22 20:58:19',1263.00),
(230,9,'SC-1-MN','Tapa de 1 ventana color media noche','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557508148.png','2021-01-22 21:56:57',NULL,NULL,'0','admin','2021-01-23 03:56:57','2021-01-23 03:56:57','027557508148','2021-01-22 21:56:57',1213.00),
(231,77,'CW-1-SS','Tapa de 1 ventana, de acero inoxidable','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557223249.png','2021-01-22 22:06:13',NULL,NULL,'0','admin','2021-01-23 04:06:13','2021-01-23 04:06:13','027557223249','2021-01-22 22:06:13',NULL),
(232,7,'SC-1-MR','Tapa de 1 ventana, color Merlot','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557172776.png','2021-01-22 22:10:31',NULL,NULL,'0','admin','2021-01-23 04:10:31','2021-01-23 04:10:31','027557172776','2021-01-22 22:10:31',1213.00),
(233,8,'SC-1-PL','Tapa de 1 ventana, color Ciruela','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557266840.png','2021-01-22 22:12:06',NULL,NULL,'0','admin','2021-01-23 04:12:06','2021-01-23 04:12:06','027557266840','2021-01-22 22:12:06',1213.00),
(234,8,'SC-1-GB','Tapa de 1 ventana, color Greenbriar/Piedra Verde','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557172752.png','2021-01-22 22:13:01',NULL,NULL,'0','admin','2021-01-23 04:13:01','2021-01-23 04:13:01','027557172752','2021-01-22 22:13:01',1213.00),
(235,8,'SC-1-TC','Tapa de 1 ventana, color Terracota','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557495202.png','2021-01-22 22:16:26',NULL,NULL,'0','admin','2021-01-23 04:16:26','2021-01-23 04:16:26','027557495202','2021-01-22 22:16:26',1213.00),
(236,8,'SC-1-MS','Tapa de 1 ventana, color piedra moka','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557264648.png','2021-01-22 22:19:56',NULL,NULL,'0','admin','2021-01-23 04:19:56','2021-01-23 04:19:56','027557264648','2021-01-22 22:19:56',1213.00),
(237,12,'SC-1-ST','Tapa de 1 ventana, color Piedra','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557508247.png','2021-01-22 22:22:09',NULL,NULL,'0','admin','2021-01-23 04:22:09','2021-01-23 04:22:09','027557508247','2021-01-22 22:22:09',1213.00),
(238,9,'SC-1-ES','Tapa de 1 ventana, color Egshell/Blanco Ostion','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557508162.png','2021-01-22 22:26:13',NULL,NULL,'0','admin','2021-01-23 04:26:13','2021-01-23 04:26:13','027557508162','2021-01-22 22:26:13',1213.00),
(239,21,'SC-1-PD','Tapa de 1 ventana, color Paladio','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557266291.png','2021-01-22 22:28:07',NULL,NULL,'0','admin','2021-01-23 04:28:07','2021-01-23 04:28:07','027557266291','2021-01-22 22:28:07',1213.00),
(240,8,'SC-1-GS','Tapa de 1 ventana, color Piedra Dorada','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557265744.png','2021-01-22 22:31:00',NULL,NULL,'0','admin','2021-01-23 04:31:00','2021-01-23 04:31:00','027557265744','2021-01-22 22:31:00',1213.00),
(241,9,'CW-1-LA','Tapa de 1 ventana, color Almedra Claro','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557511735.png','2021-01-22 22:32:04',NULL,NULL,'0','admin','2021-01-23 04:32:04','2021-01-23 04:32:04','027557511735','2021-01-22 22:32:04',1213.00),
(242,7,'SC-1-BG','Tapa de 1 ventan, color Piedra Azul','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557172769.png','2021-01-22 22:34:41',NULL,NULL,'0','admin','2021-01-23 04:34:41','2021-01-23 04:34:41','027557172769','2021-01-22 22:34:41',1213.00),
(243,4,'SC-1-LS','Tapa de 1 ventana, color piedra caliza','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557508223.png','2021-01-22 22:36:12',NULL,NULL,'0','admin','2021-01-23 04:36:12','2021-01-23 04:36:12','027557508223','2021-01-22 22:36:12',1213.00),
(244,104,'CW-2-SS','Tapa de 2 ventanas, de Acero Inoxidable','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557223256.png','2021-01-25 19:01:14',NULL,NULL,'0','admin','2021-01-26 01:01:14','2021-01-26 01:01:14','027557223256','2021-01-25 19:01:14',1218.00),
(245,23,'SC-2-MN','Tapa de 2 ventanas, color Media Noche','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557508476.png','2021-01-25 19:06:51',NULL,NULL,'0','admin','2021-01-26 01:06:51','2021-01-26 01:06:51','027557508476','2021-01-25 19:06:51',1218.00),
(246,7,'SC-2-ST','Tapa de 2 ventanas, color Piedra','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475129.png','2021-01-25 19:15:38',NULL,NULL,'0','admin','2021-01-26 01:15:38','2021-01-26 01:15:38','027557475129','2021-01-25 19:15:38',1218.00),
(247,20,'SC-2-TP','Tapa de 2 ventanas,color Taupe','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475150.png','2021-01-25 19:18:52',NULL,NULL,'0','admin','2021-01-26 01:18:52','2021-01-26 01:18:52','027557475150','2021-01-25 19:18:52',1218.00),
(248,7,'CW-2-LA','Tapa de 2 ventanas, linea Claro, color Almendra Claro','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557513050.png','2021-01-25 19:26:47',NULL,NULL,'0','admin','2021-01-26 01:26:47','2021-01-26 01:26:47','027557513050','2021-01-25 19:26:47',1218.00),
(249,1,'SC-2-SI','Tapa de 2 ventanas, color siena','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557175630.png','2021-01-25 19:37:18',NULL,NULL,'0','admin','2021-01-26 01:37:18','2021-01-26 01:37:18','027557175630','2021-01-25 19:37:18',1218.00),
(250,0,NULL,'Home center lite fibaro (FGHCL-001)\r\nCAJA FIBARO',NULL,'DOMOTICA','Bodega General',NULL,NULL,NULL,'2021-01-25 21:23:58',NULL,NULL,'0','admin','2021-01-26 03:23:58','2021-01-26 03:23:58','hcl\'049572','2021-01-25 21:23:58',0.00),
(251,0,NULL,'Memoria RAM hyperx fury 16GB color negro \r\nESTANTE: B-8',NULL,'HARDWARE','Bodega General',NULL,NULL,NULL,'2021-01-25 22:54:49',NULL,NULL,'0','admin','2021-01-26 04:54:49','2021-01-26 04:54:49','HX426C16FB4/16','2021-01-25 22:54:49',0.00),
(252,0,NULL,'Procesador con solucion termica intel (I5-10400)\r\nESTANTE: B-8',NULL,'HARDWARE','Bodega General',NULL,NULL,NULL,'2021-01-25 22:57:56',NULL,NULL,'0','admin','2021-01-26 04:57:56','2021-01-26 04:57:56','735858446006','2021-01-25 22:57:56',0.00),
(253,16,'CW-3-WH','Tapa de 3 ventanas, linea Claro, color blanco','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557692175.png','2021-01-26 19:22:34',NULL,NULL,'0','admin','2021-01-27 01:22:34','2021-01-27 01:22:34','027557692175','2021-01-26 19:22:34',NULL),
(254,33,'SC-3-TP','Tapa de 3 ventanas, color Taupe','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475327.png','2021-01-26 19:28:13',NULL,NULL,'0','admin','2021-01-27 01:28:13','2021-01-27 01:28:13','027557475327','2021-01-26 19:28:13',1223.00),
(255,4,'CW-3-LA','Tapa de 3 ventanas, color Almendra Claro','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557513180.png','2021-01-26 19:30:03',NULL,NULL,'0','admin','2021-01-27 01:30:03','2021-01-27 01:30:03','027557513180','2021-01-26 19:30:03',1223.00),
(256,6,'SC-3-MN','Tapa de 3 ventanas, color Media Noche','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475198.png','2021-01-26 19:32:16',NULL,NULL,'0','admin','2021-01-27 01:32:16','2021-01-27 01:32:16','027557475198','2021-01-26 19:32:16',1223.00),
(257,8,'SC-3-ST','Tapa de 3 ventanas, color Piedra','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475297.png','2021-01-26 19:43:34',NULL,NULL,'0','admin','2021-01-27 01:43:34','2021-01-27 01:43:34','027557475297','2021-01-26 19:43:34',1223.00),
(258,2,'SC-3-SI','Tapa de 3 ventanas, color Siena','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557175685.png','2021-01-26 19:46:02',NULL,NULL,'0','admin','2021-01-27 01:46:02','2021-01-27 01:46:02','027557175685','2021-01-26 19:46:02',1223.00),
(259,8,'SC-4-MN','Tapa de 4 ventanas, color Media Noche','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475525.png','2021-01-26 21:14:42',NULL,NULL,'0','admin','2021-01-27 03:14:42','2021-01-27 03:14:42','027557475525','2021-01-26 21:14:42',1228.00),
(260,19,'SC-4-BI','Tapa de 4 ventanas, color Biscuit','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475518.png','2021-01-26 21:16:54',NULL,NULL,'0','admin','2021-01-27 03:16:54','2021-01-27 03:16:54','027557475518','2021-01-26 21:16:54',1228.00),
(261,5,'SC-4-ST','Tapa de 4 ventanas, color piedra','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475624.png','2021-01-26 21:20:37',NULL,NULL,'0','admin','2021-01-27 03:20:37','2021-01-27 03:20:37','027557475624','2021-01-26 21:20:37',1228.00),
(262,2,'SC-4-SI','Tapa de 4 ventanas, color siena','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557175739.png','2021-01-26 21:25:55',NULL,NULL,'0','admin','2021-01-27 03:25:55','2021-01-27 03:25:55','027557175739','2021-01-26 21:25:55',1228.00),
(263,0,NULL,'Tapa lutron de 4 ventanas color nieve/snow (SC-4-SW)\r\nESTANTE: TAPAS',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-01-26 21:27:06',NULL,NULL,'0','admin','2021-01-27 03:27:06','2021-01-27 03:27:06','027557475662','2021-01-26 21:27:06',0.00),
(264,0,NULL,'Tapa amerelle de 4 ventanas de bronce envejecido ESTANTE: TAPAS',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-01-26 21:38:44',NULL,NULL,'0','admin','2021-01-27 03:38:44','2021-01-27 03:38:44','LPNN632098616','2021-01-26 21:38:44',0.00),
(265,0,NULL,'Tapa amerelle de 4 ventanas de bronce envejecido pulido\r\nESTANTE: TAPAS',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-01-26 21:39:31',NULL,NULL,'0','admin','2021-01-27 03:39:31','2021-01-27 03:39:31','070686569559','2021-01-26 21:39:31',0.00),
(266,4,'CW-4-LA','Tapa de 4 ventanas, color Almendra Claro','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557513920.png','2021-01-26 21:40:58',NULL,NULL,'0','admin','2021-01-27 03:40:58','2021-01-27 03:40:58','027557513920','2021-01-26 21:40:58',1223.00),
(267,5,NULL,'Repetidor inalambrico caseta wireless lutron (PD-REP-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276274043.png','2021-01-27 23:14:54',NULL,NULL,'0','admin','2021-01-28 05:14:54','2021-01-28 05:14:54','784276274043','2021-01-27 23:14:54',1257.00),
(268,0,NULL,'Cerradura electrica de sobreponer derecha phillips (321-DCD-ABG)\r\nESTANTE: I-4',NULL,'CERRADURA','Bodega General',NULL,NULL,NULL,'2021-01-29 20:31:16',NULL,NULL,'0','admin','2021-01-30 02:31:16','2021-01-30 02:31:16','751299034114','2021-01-29 20:31:16',0.00),
(269,9,'EF50M','Carrete de Fibra Óptica Monomodo con conectores SC-SC Duplex, Reforzada con Kevlar, de 50 metros','Linkedpro','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/EF50M.png','2021-02-02 23:21:17',NULL,NULL,'0','admin','2021-02-03 05:21:17','2021-02-03 05:21:17','EF50M','2021-02-02 23:21:17',1036.00),
(270,28,NULL,'Cable Genesis 16x2 de 305m Honeywell Genesis color blanco (16/2 Stranded Audacios)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/781410995550.jpg','2021-02-03 20:56:54',NULL,NULL,'0','admin','2021-02-04 02:56:54','2021-02-04 02:56:54','781410995550','2021-02-03 20:56:54',NULL),
(271,5,NULL,'Cable de control de audio blindado de 7 hilos, 2 conductores, calibre 22 de 1000ft wirepath color morado (NST-222-SH-E-1K-PUR)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/842822035286.jpg','2021-02-03 21:21:04',NULL,NULL,'0','admin','2021-02-04 03:21:04','2021-02-04 03:21:04','842822035286','2021-02-03 21:21:04',NULL),
(272,0,NULL,'Cable coaxial wirepath RG6, CCS, de 500ft color negro (NST-RG6-500-BLK)\r\nBODEGA',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2021-02-03 21:47:16',NULL,NULL,'0','admin','2021-02-04 03:47:16','2021-02-04 03:47:16','842822013918','2021-02-03 21:47:16',0.00),
(273,27,NULL,'Contacto duplex USB de 15A con proteccion para niños lutron satin colors color media noche (SCR-15-UBTR-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276166287.png','2021-02-04 19:51:35',NULL,NULL,'0','admin','2021-02-05 01:51:35','2021-02-05 01:51:35','784276166287','2021-02-04 19:51:35',1263.00),
(274,0,NULL,'Botonera lutron de 4 botones homeworks QS color negro (HQWT-U-P4W-BL)\r\nBAÑO',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-02-04 20:24:26',NULL,NULL,'0','admin','2021-02-05 02:24:26','2021-02-05 02:24:26','784276113182','2021-02-04 20:24:26',0.00),
(275,7,NULL,'Botonera lutron pico de 2 botones con luz de noche color negro (PJN-2B-GBL-L01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276068062.png','2021-02-04 20:29:31',NULL,NULL,'0','admin','2021-02-05 02:29:31','2021-02-05 02:29:31','784276068062','2021-02-04 20:29:31',1224.00),
(276,12,NULL,'Botonera lutron pico de 4 botones con luna color negro (PJN-3BRL-GBL-L01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276068116.png','2021-02-04 20:32:24',NULL,NULL,'0','admin','2021-02-05 02:32:24','2021-02-05 02:32:24','784276068116','2021-02-04 20:32:24',1229.00),
(277,0,NULL,'Switch lutron homeworks QS color biscuit (HQRD-8ANS-BI)\r\nESTANTE: A-8',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-02-05 17:31:44',NULL,NULL,'0','admin','2021-02-05 23:31:44','2021-02-05 23:31:44','027557005067','2021-02-05 17:31:44',0.00),
(278,7,NULL,'Botonera lutron de 5 botones homeworks QS color biscuit (HQRD-H5BRL-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557997805.png','2021-02-05 19:50:02',NULL,NULL,'0','admin','2021-02-06 01:50:02','2021-02-06 01:50:02','027557997805','2021-02-05 19:50:02',1270.00),
(279,0,NULL,'Botonera lutron de 3 botones homeworks QS color biscuit (HQRD-H3BSRL-BI)\r\nESTANTE: G-2',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-02-05 19:55:06',NULL,NULL,'0','admin','2021-02-06 01:55:06','2021-02-06 01:55:06','027557997546','2021-02-05 19:55:06',0.00),
(280,1,NULL,'Camara inteligente wifi IP orvibo (SC32PT)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/190279000648.jpg','2021-02-06 17:55:44',NULL,NULL,'0','admin','2021-02-06 23:55:44','2021-02-06 23:55:44','190279000648','2021-02-06 17:55:44',1293.00),
(281,0,NULL,'Control remoto para cortinas tecno RF multicanal 15 bmighty con bateria ultralast 2430\r\nESTANTE: CORTINAS',NULL,'CONTROL REMOTO','Bodega General',NULL,NULL,NULL,'2021-02-06 18:11:20',NULL,NULL,'0','admin','2021-02-07 00:11:20','2021-02-07 00:11:20','DC313','2021-02-06 18:11:20',0.00),
(282,0,NULL,'Sistema de enfriamiento liquido yeyian (WC1200)\r\nESTANTE: I-1',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-02-08 21:16:32',NULL,NULL,'0','admin','2021-02-09 03:16:32','2021-02-09 03:16:32','7503026359655','2021-02-08 21:16:32',0.00),
(283,0,NULL,'Gabinete para pc getttech (GG1801)\r\nESTANTE: I-2',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-02-08 21:19:08',NULL,NULL,'0','admin','2021-02-09 03:19:08','2021-02-09 03:19:08','7503026359037','2021-02-08 21:19:08',0.00),
(284,0,NULL,'Proyector epson con router (H846A)\r\nBAÑO',NULL,'VIDEO','Bodega General',NULL,NULL,NULL,'2021-02-09 19:50:04',NULL,NULL,'0','admin','2021-02-10 01:50:04','2021-02-10 01:50:04','010343935778','2021-02-09 19:50:04',0.00),
(285,0,NULL,'Pantalla de proyeccion retractil de 72 pulgadas con tripie 183cm (SCR-72)\r\nESTANTE: I-3',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,NULL,'2021-02-09 22:22:16',NULL,NULL,'0','admin','2021-02-10 04:22:16','2021-02-10 04:22:16','793611878419','2021-02-09 22:22:16',0.00),
(286,4,NULL,'Cable Genesis 22x2 de 1000ft, blindado de bajo voltaje, 2 conductores de cobre color gris (22021109)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/619172020923.png','2021-02-10 17:31:53',NULL,NULL,'0','admin','2021-02-10 23:31:53','2021-02-10 23:31:53','619172020923','2021-02-10 17:31:53',NULL),
(287,2,NULL,'Cable Panduit netkey UTP de 305m, de cobre, cat5e color azul (4PR/24AWG)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/074983194490.jpg','2021-02-10 17:45:34',NULL,NULL,'0','admin','2021-02-10 23:45:34','2021-02-10 23:45:34','074983194490','2021-02-10 17:45:34',1114.00),
(288,2,NULL,'Cable siemon azul UTP reelex de 305m, 4 pares, categoria 6, 24 AWG (9C6M4-E2-06-RXA)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/700416063513.png','2021-02-10 17:49:33',NULL,NULL,'0','admin','2021-02-10 23:49:33','2021-02-10 23:49:33','700416063513','2021-02-10 17:49:33',NULL),
(289,0,NULL,'Cable condumex de 305m, categoria 6 con gel, para intemperie, para aplicaciones en CCTV, y redes de alta velocidad color negro (66766645)\r\nBODEGA',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2021-02-10 18:01:19',NULL,NULL,'0','admin','2021-02-11 00:01:19','2021-02-11 00:01:19','SSEP180213','2021-02-10 18:01:19',0.00),
(290,4,NULL,'Bobina de Cable Condumex , categoria 6 con gel, para intemperie, para aplicaciones en CCTV, y redes de alta velocidad color negro (66766645-U)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/66766645-U.jpg','2021-02-10 18:02:05',NULL,NULL,'0','admin','2021-02-11 00:02:05','2021-02-11 00:02:05','66766645-U','2021-02-10 18:02:05',NULL),
(291,9,NULL,'Cable Condumex de 305m, categoria 5e con gel, para intemperie, aplicaciones en sistemas de redes de datos y cableado estructurado color negro (664464)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/P664464.png','2021-02-10 18:06:15',NULL,NULL,'0','admin','2021-02-11 00:06:15','2021-02-11 00:06:15','P664464','2021-02-10 18:06:15',NULL),
(292,0,NULL,'Cable condumex de 305m, blindado para interior F/UTP categoria 5e, calibre 24 AWG color azul (664445-15)\r\nBODEGA',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2021-02-10 18:10:51',NULL,NULL,'0','admin','2021-02-11 00:10:51','2021-02-11 00:10:51','SENE090042','2021-02-10 18:10:51',0.00),
(293,0,NULL,'Cable linkedpro de 305m, categoria 6, calibre 23, para climas extremos, sin blindar, para intemperie color negro\r\nBODEGA',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2021-02-10 18:23:33',NULL,NULL,'0','admin','2021-02-11 00:23:33','2021-02-11 00:23:33','PROCAT6EXLITE','2021-02-10 18:23:33',0.00),
(294,0,NULL,'Cable panduit netkey de 305m reelex, de cobre, categoria 6 (24AWG) de 4 pares color gris (NUC6C04IG-FE)\r\nBODEGA',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2021-02-10 18:29:05',NULL,NULL,'0','admin','2021-02-11 00:29:05','2021-02-11 00:29:05','613056570986','2021-02-10 18:29:05',0.00),
(295,0,NULL,'Reproductor de CD mp3 Onkyo 6 discos color negro (DX-C390)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/751398005435.png','2021-02-10 20:20:10',NULL,NULL,'0','admin','2021-02-11 02:20:10','2021-02-11 02:20:10','751398005435','2021-02-10 20:20:10',1127.00),
(296,0,NULL,'Gabinete de acero precision uso en intemperie (300 x 400 x 200 mm) con placa trasera interior y compuerta inferior atornillable\r\nBODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2021-02-10 20:22:50',NULL,NULL,'0','admin','2021-02-11 02:22:50','2021-02-11 02:22:50','PST-3040-20A','2021-02-10 20:22:50',0.00),
(297,0,NULL,'Gabinete de acero precision uso en intemperie (400 x 650 x 200 mm) con placa trasera interior y compuerta inferior atornillable\r\nBODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2021-02-10 20:24:28',NULL,NULL,'0','admin','2021-02-11 02:24:28','2021-02-11 02:24:28','PST-4060-20A','2021-02-10 20:24:28',0.00),
(298,0,NULL,'Gabinete de acero precision uso en intemperie (600 x 600 x 300 mm) con placa trasera interior y compuerta inferior atornillable\r\nBODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2021-02-10 20:25:37',NULL,NULL,'0','admin','2021-02-11 02:25:37','2021-02-11 02:25:37','PST-6060-30A','2021-02-10 20:25:37',0.00),
(299,0,NULL,'Gabinete linkedpro para montaje en pared, puerta de cristal templado, cuerpo fijo con rack 19\" de 16 unidades\r\nBODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2021-02-10 20:27:20',NULL,NULL,'0','admin','2021-02-11 02:27:20','2021-02-11 02:27:20','SR1916GFP','2021-02-10 20:27:20',0.00),
(300,1,NULL,'Gabinete Linkedpro para montaje en pared, puerta de cristal templado, cuerpo fijo con rack 19\\\\\\\" de 9 unidades',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/SR1909GFP.png','2021-02-10 20:30:17',NULL,NULL,'0','admin','2021-02-11 02:30:17','2021-02-11 02:30:17','SR1909GFP','2021-02-10 20:30:17',1105.00),
(301,1,NULL,'Rack de acero Strong Profundidad 18, Altura 16 negro (SR-CS-RACK-16U)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/842822019316.jpg','2021-02-10 20:33:49',NULL,NULL,'0','admin','2021-02-11 02:33:49','2021-02-11 02:33:49','842822019316','2021-02-10 20:33:49',NULL),
(302,3,'SR-CUSTOM-BASE-24','Estante de suelo, altura 42, profundidad 24 Unidades, color negro','Strong','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/842822033206.png','2021-02-10 20:35:49',NULL,NULL,'0','admin','2021-02-11 02:35:49','2021-02-11 02:35:49','842822033206','2021-02-10 20:35:49',1100.00),
(303,3,NULL,'Rack de acero Strong de 21 unidades negro (SR-CS-RACK-21U)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/842822019323.png','2021-02-10 20:39:55',NULL,NULL,'0','admin','2021-02-11 02:39:55','2021-02-11 02:39:55','842822019323','2021-02-10 20:39:55',NULL),
(304,0,NULL,'Base de tv sanus morada multiposicion de 22 a 55 pulgadas (SMF218-B8)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/793795533005.jpg','2021-02-10 20:42:19',NULL,NULL,'0','admin','2021-02-11 02:42:19','2021-02-11 02:42:19','793795533005','2021-02-10 20:42:19',1066.00),
(305,0,NULL,'Base de tv sanus verde multiposicion de 37 a 90 pulgadas (SLF226-B8)\r\nBODEGA',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,NULL,'2021-02-10 20:44:01',NULL,NULL,'0','admin','2021-02-11 02:44:01','2021-02-11 02:44:01','793795531012','2021-02-10 20:44:01',0.00),
(306,1,NULL,'Pilar de rack strong personalizado de 42u negro (SR-CUSTOMPILLAR-42U)\r\nBODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2021-02-10 20:46:16',NULL,NULL,'0','admin','2021-02-11 02:46:16','2021-02-11 02:46:16','842822033183','2021-02-10 20:46:16',0.00),
(307,1,'R-115SW','Subwoofer cuadrado de 15 pulgadas, color negro','Klipsch','AUDIO','Bodega General',NULL,NULL,'/img/productos/743878027389.jpg','2021-02-10 21:16:47',NULL,NULL,'0','admin','2021-02-11 03:16:47','2021-02-11 03:16:47','743878027389','2021-02-10 21:16:47',1124.00),
(308,0,NULL,'Subwoofer jamo 250W negro\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-10 21:27:24',NULL,NULL,'0','admin','2021-02-11 03:27:24','2021-02-11 03:27:24','SUB-250','2021-02-10 21:27:24',0.00),
(309,2,NULL,'Bocina sonance mariner 86 color negro (MARINER86BLK) (83157)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/041093831577.png','2021-02-10 21:29:32',NULL,NULL,'0','admin','2021-02-11 03:29:32','2021-02-11 03:29:32','041093831577','2021-02-10 21:29:32',1038.00),
(310,0,NULL,'Bocina sonance mariner 66 color negro (83155) BODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-10 21:30:14',NULL,NULL,'0','admin','2021-02-11 03:30:14','2021-02-11 03:30:14','041093831553','2021-02-10 21:30:14',0.00),
(311,4,NULL,'Regulador de voltaje y protector de sobrecarga panamax (M5400-PM)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/050616008150.jpg','2021-02-10 21:34:03',NULL,NULL,'0','admin','2021-02-11 03:34:03','2021-02-11 03:34:03','050616008150','2021-02-10 21:34:03',NULL),
(312,17,NULL,'Protector de sobretension panamax blanco (MD2)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/050616008754.jpg','2021-02-10 21:35:32',NULL,NULL,'0','admin','2021-02-11 03:35:32','2021-02-11 03:35:32','050616008754','2021-02-10 21:35:32',1016.00),
(313,0,NULL,'Amplificador bose soundtouch negro (SA-5)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-10 22:06:34',NULL,NULL,'0','admin','2021-02-11 04:06:34','2021-02-11 04:06:34','017817699198','2021-02-10 22:06:34',0.00),
(314,0,NULL,'Paneles laterales para rack reistentes de la serie custom, profundidad 24, altura 42u color negro (SR-CUST-SP-42U-24IN)\r\nBODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2021-02-10 22:46:06',NULL,NULL,'0','admin','2021-02-11 04:46:06','2021-02-11 04:46:06','842822033404','2021-02-10 22:46:06',0.00),
(315,0,NULL,'Subwoofer sonance sonarray \r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-11 19:39:29',NULL,NULL,'0','admin','2021-02-12 01:39:29','2021-02-12 01:39:29','93139','2021-02-11 19:39:29',0.00),
(316,0,NULL,'Bocina jamo de piedra\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-11 19:47:41',NULL,NULL,'0','admin','2021-02-12 01:47:41','2021-02-12 01:47:41','JA-STO-GRY','2021-02-11 19:47:41',0.00),
(317,1,NULL,'Bocina para exterior Bose 151SE color negro (151SE-BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/17817343152.png','2021-02-11 19:51:50',NULL,NULL,'0','admin','2021-02-12 01:51:50','2021-02-12 01:51:50','17817343152','2021-02-11 19:51:50',NULL),
(318,0,NULL,'Bocina para exterior bose 251 color blanco (251-WH)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-11 19:55:10',NULL,NULL,'0','admin','2021-02-12 01:55:10','2021-02-12 01:55:10','017817263566','2021-02-11 19:55:10',0.00),
(319,1,NULL,'Amplificador Bose Soundtouch SA-4 color negro (SOUNDTOUCHSA-4)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/17817598323.png','2021-02-11 19:59:39',NULL,NULL,'0','admin','2021-02-12 01:59:39','2021-02-12 01:59:39','17817598323','2021-02-11 19:59:39',1130.00),
(320,1,NULL,'Barra de sonido bose Soundtouch 300 color negro (300SOUNDBAR)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/017817739924.png','2021-02-11 20:04:30',NULL,NULL,'0','admin','2021-02-12 02:04:30','2021-02-12 02:04:30','017817739924','2021-02-11 20:04:30',NULL),
(321,0,NULL,'Bocina para exterior bose 151 color negro (151-BLK)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-11 20:06:20',NULL,NULL,'0','admin','2021-02-12 02:06:20','2021-02-12 02:06:20','017817263573','2021-02-11 20:06:20',0.00),
(322,1,NULL,'Bocina tipo bafle bose 301 color negro (301-BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/017817305518.png','2021-02-11 20:07:27',NULL,NULL,'0','admin','2021-02-12 02:07:27','2021-02-12 02:07:27','017817305518','2021-02-11 20:07:27',1065.00),
(323,0,NULL,'Sistema de entretenimiento bose AV48 color gris\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-11 20:09:06',NULL,NULL,'0','admin','2021-02-12 02:09:06','2021-02-12 02:09:06','AV48MEDIACENTER','2021-02-11 20:09:06',0.00),
(324,0,NULL,'Altavoz de linea JBL color blanco\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-11 20:10:32',NULL,NULL,'0','admin','2021-02-12 02:10:32','2021-02-12 02:10:32','CBT70JE-1-WH','2021-02-11 20:10:32',0.00),
(325,4,NULL,'Bocina para exterior/interior JBL bidireccional color negro (CONTROL25-1-BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991002021.png','2021-02-11 20:11:58',NULL,NULL,'0','admin','2021-02-12 02:11:58','2021-02-12 02:11:58','691991002021','2021-02-11 20:11:58',1126.00),
(326,0,NULL,'Bocina para interior/exterior JBL color negro (CONTROL28-1-BLK)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-11 20:21:25',NULL,NULL,'0','admin','2021-02-12 02:21:25','2021-02-12 02:21:25','691991002045','2021-02-11 20:21:25',0.00),
(327,6,NULL,'Amplificador Crown 1502 de dos canales (XLS1502)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991001000.png','2021-02-11 20:23:18',NULL,NULL,'0','admin','2021-02-12 02:23:18','2021-02-12 02:23:18','691991001000','2021-02-11 20:23:18',NULL),
(328,0,NULL,'Corrector de voltaje ISB color blanco\r\nBODEGA',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-02-11 20:26:06',NULL,NULL,'0','admin','2021-02-12 02:26:06','2021-02-12 02:26:06','CO-V-ISB','2021-02-11 20:26:06',0.00),
(329,0,NULL,'Bocina jamo para exterior color negro (I/O3S-BLK)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-12 17:20:48',NULL,NULL,'0','admin','2021-02-12 23:20:48','2021-02-12 23:20:48','008634937181','2021-02-12 17:20:48',0.00),
(330,1,NULL,'Sistema de sonido envolvente cine en casa Jamo color negro (A102HCS6)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/008634895092.png','2021-02-12 17:33:08',NULL,NULL,'0','admin','2021-02-12 23:33:08','2021-02-12 23:33:08','008634895092','2021-02-12 17:33:08',NULL),
(331,0,NULL,'Gabinete metalico para usos multiples, instalacion en muro (461 x 405 x 273 mm) epcom color blanco\r\nBODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2021-02-12 17:43:14',NULL,NULL,'0','admin','2021-02-12 23:43:14','2021-02-12 23:43:14','SYG-075-EXT','2021-02-12 17:43:14',0.00),
(332,2,NULL,'Sistema de sonido envolvente m-cube color negro (M-CUBESYSTEM)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/703339736243.png','2021-02-12 17:56:43',NULL,NULL,'0','admin','2021-02-12 23:56:43','2021-02-12 23:56:43','703339736243','2021-02-12 17:56:43',1015.00),
(333,0,NULL,'Bocina de pared proficient color blanco (W665)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-12 18:08:08',NULL,NULL,'0','admin','2021-02-13 00:08:08','2021-02-13 00:08:08','674254005626','2021-02-12 18:08:08',0.00),
(334,0,NULL,'Barra de sonido y subwoofer para cine en casa heos (HEOSCINEMABKE3)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-12 18:11:53',NULL,NULL,'0','admin','2021-02-13 00:11:53','2021-02-13 00:11:53','883795003704','2021-02-12 18:11:53',0.00),
(335,0,NULL,'Bocina de hongo tic color verde USADA\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-12 18:13:01',NULL,NULL,'0','admin','2021-02-13 00:13:01','2021-02-13 00:13:01','BO-HO-V-TIC','2021-02-12 18:13:01',0.00),
(336,2,NULL,'Bocina de pared sonance de 6 pulgadas color blanco (C6R)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/041093931246.png','2021-02-12 18:15:31',NULL,NULL,'0','admin','2021-02-13 00:15:31','2021-02-13 00:15:31','041093931246','2021-02-12 18:15:31',1034.00),
(337,0,NULL,'Bocina para exterior/interior proficient color biscuit (AW400WHT)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-12 19:28:21',NULL,NULL,'0','admin','2021-02-13 01:28:21','2021-02-13 01:28:21','814138894406','2021-02-12 19:28:21',0.00),
(338,1,NULL,'Bocina compacta interior/exterior klipsch color negra (CP-4T)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/743878025972.png','2021-02-12 19:50:37',NULL,NULL,'0','admin','2021-02-13 01:50:37','2021-02-13 01:50:37','743878025972','2021-02-12 19:50:37',1134.00),
(339,3,NULL,'Bocina de techo KEF color blanca 8 pulgadas (CI200.2CR)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/637203215469.png','2021-02-12 21:03:12',NULL,NULL,'0','admin','2021-02-13 03:03:12','2021-02-13 03:03:12','637203215469','2021-02-12 21:03:12',1169.00),
(340,0,NULL,'Bocina para exterior KEF color negro (VENTURA6)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-12 21:05:24',NULL,NULL,'0','admin','2021-02-13 03:05:24','2021-02-13 03:05:24','637203043109','2021-02-12 21:05:24',0.00),
(341,0,NULL,'Bocina para intemperie KEF color blanco (VENTURA4)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-12 21:07:06',NULL,NULL,'0','admin','2021-02-13 03:07:06','2021-02-13 03:07:06','637203028816','2021-02-12 21:07:06',0.00),
(342,0,NULL,'Pantalla samsung de 55 pulgadas 4K UHD color negro (UN55RU7100F)\r\nBODEGA',NULL,'TV','Bodega General',NULL,NULL,NULL,'2021-02-12 21:08:42',NULL,NULL,'0','admin','2021-02-13 03:08:42','2021-02-13 03:08:42','8801643681456','2021-02-12 21:08:42',0.00),
(343,15,NULL,'Bocina de pared Sonos in-wall color blanco (INWLLWW1)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/878269007654.png','2021-02-12 21:19:33',NULL,NULL,'0','admin','2021-02-13 03:19:33','2021-02-13 03:19:33','878269007654','2021-02-12 21:19:33',NULL),
(344,1,NULL,'Bocina tipo hongo JBL color verde (CONTROL88M)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991003084.png','2021-02-12 21:33:03',NULL,NULL,'0','admin','2021-02-13 03:33:03','2021-02-13 03:33:03','691991003084','2021-02-12 21:33:03',1065.00),
(345,3,NULL,'Bocina de pared klipsch color blanca (R-5650-WII)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/743878023992.png','2021-02-12 21:35:06',NULL,NULL,'0','admin','2021-02-13 03:35:06','2021-02-13 03:35:06','743878023992','2021-02-12 21:35:06',1127.00),
(346,11,NULL,'Gabinete plastico para exterior de 300 x 300 x 150 mm cierre por tornillos txpro color blanco (TXG-01-571)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/TXG-01-571.png','2021-02-12 22:26:40',NULL,NULL,'0','admin','2021-02-13 04:26:40','2021-02-13 04:26:40','TXG-01-571','2021-02-12 22:26:40',NULL),
(347,1,NULL,'Bocina para exterior bose tipo hongo color verde freespace 360p (FREESPACE360P) (040151)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/017017391917.png','2021-02-12 22:28:36',NULL,NULL,'0','admin','2021-02-13 04:28:36','2021-02-13 04:28:36','017017391917','2021-02-12 22:28:36',1052.00),
(348,1,NULL,'Amplificador de audio Crown color negro (CT-4150)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/871015004679.png','2021-02-12 22:30:10',NULL,NULL,'0','admin','2021-02-13 04:30:10','2021-02-13 04:30:10','871015004679','2021-02-12 22:30:10',1118.00),
(349,0,NULL,'Bocina para exterior klipsch color negro (AW-525)\r\nBODEGA A-3',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-12 22:41:37',NULL,NULL,'0','admin','2021-02-13 04:41:37','2021-02-13 04:41:37','743878020366','2021-02-12 22:41:37',0.00),
(350,2,NULL,'Bocina de pared klipsch color negro (R-5650-SII)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/743878024005.png','2021-02-12 23:41:50',NULL,NULL,'0','admin','2021-02-13 05:41:50','2021-02-13 05:41:50','743878024005','2021-02-12 23:41:50',1127.00),
(351,0,NULL,'Router engenius, 2.4 GHZ, 4 puertos LAN, 1 WAN, seguridad WPA/WPA2 en vitafil color negro (ENGENIUSGOLD)\r\nESTANTE: B-8',NULL,'RED E INTERNET','Bodega General',NULL,NULL,NULL,'2021-02-13 16:48:14',NULL,NULL,'0','admin','2021-02-13 22:48:14','2021-02-13 22:48:14','07898923458052','2021-02-13 16:48:14',0.00),
(352,0,NULL,'Pantalla de proyeccion para arq. Lulu\r\nBODEGA',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,NULL,'2021-02-13 17:04:59',NULL,NULL,'0','admin','2021-02-13 23:04:59','2021-02-13 23:04:59','PAN-PRO-LU','2021-02-13 17:04:59',0.00),
(353,4,NULL,'Bocina de rango completo de pared Bose color blanco (FREESPACE-DS16SE)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/017817397384.png','2021-02-13 17:17:45',NULL,NULL,'0','admin','2021-02-13 23:17:45','2021-02-13 23:17:45','017817397384','2021-02-13 17:17:45',1103.00),
(354,0,NULL,'Bocina de pared para exteriores bose color blanca (FREESPACEDS40SE)\r\nBODEGA A-8 A-15',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-15 22:05:51',NULL,NULL,'0','admin','2021-02-16 04:05:51','2021-02-16 04:05:51','017817535427','2021-02-15 22:05:51',0.00),
(355,0,NULL,'Bocina tipo libreria bose color blanco (161TM)\r\nBODEGA A-9',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-15 22:12:46',NULL,NULL,'0','admin','2021-02-16 04:12:46','2021-02-16 04:12:46','078172293007','2021-02-15 22:12:46',0.00),
(356,0,NULL,'Amplificador backstage 800W color negro\r\nBODEGA A-11',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-16 19:30:11',NULL,NULL,'0','admin','2021-02-17 01:30:11','2021-02-17 01:30:11','MX4.4','2021-02-16 19:30:11',0.00),
(357,1,NULL,'Bocina Sonos Five color blanca (FIVE1US1)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/878269009887.png','2021-02-16 19:31:09',NULL,NULL,'0','admin','2021-02-17 01:31:09','2021-02-17 01:31:09','878269009887','2021-02-16 19:31:09',1187.00),
(358,1,NULL,'Bocina de pared Bose color negro (FREESPACE DS100SE)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/017817397575.png','2021-02-16 19:33:18',NULL,NULL,'0','admin','2021-02-17 01:33:18','2021-02-17 01:33:18','017817397575','2021-02-16 19:33:18',1101.00),
(359,1,NULL,'Bocina de pared para exteriores Bose color negro (FREESPACEDS40SE)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/17817535410.png','2021-02-16 19:48:02',NULL,NULL,'0','admin','2021-02-17 01:48:02','2021-02-17 01:48:02','17817535410','2021-02-16 19:48:02',1115.00),
(360,2,NULL,'Bocina para exterior jamo color negro (I/O8A2-WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/08634937136.png','2021-02-16 19:56:37',NULL,NULL,'0','admin','2021-02-17 01:56:37','2021-02-17 01:56:37','08634937136','2021-02-16 19:56:37',1152.00),
(361,0,NULL,'No break epcom 1500VA/900W\r\nBODEGA A-14',NULL,'NO BREAK ','Bodega General',NULL,NULL,NULL,'2021-02-16 20:02:44',NULL,NULL,'0','admin','2021-02-17 02:02:44','2021-02-17 02:02:44','EPU1500LCD1500VA','2021-02-16 20:02:44',0.00),
(362,0,NULL,'No break epcom 1200VA/720W\r\nBODEGA A-14',NULL,'NO BREAK ','Bodega General',NULL,NULL,NULL,'2021-02-16 20:08:08',NULL,NULL,'0','admin','2021-02-17 02:08:08','2021-02-17 02:08:08','EPU1200LCD1200VA','2021-02-16 20:08:08',0.00),
(363,0,NULL,'Panel lutron (HWI-PNL-8)\r\nBODEGA B-2',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-02-16 23:05:23',NULL,NULL,'0','admin','2021-02-17 05:05:23','2021-02-17 05:05:23','30027557366268','2021-02-16 23:05:23',0.00),
(364,0,NULL,'Panel lutron\r\nBODEGA B-2\r\nSALA JUNTAS',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-02-16 23:06:22',NULL,NULL,'0','admin','2021-02-17 05:06:22','2021-02-17 05:06:22','HWI-PNL-8','2021-02-16 23:06:22',0.00),
(365,0,NULL,'Muelle inalambrico sonos wireless dock color blanco (WD100US1)\r\nBODEGA B-3',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-16 23:11:15',NULL,NULL,'0','admin','2021-02-17 05:11:15','2021-02-17 05:11:15','180501001659','2021-02-16 23:11:15',0.00),
(366,20,'EPB64EW','Soporte de pared universal articulado para tv de 32 a 64 pulgadas Epcom color negro','Epcom','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/EPB64EW.png','2021-02-16 23:22:18',NULL,NULL,'0','admin','2021-02-17 05:22:18','2021-02-17 05:22:18','EPB64EW','2021-02-16 23:22:18',NULL),
(367,3,NULL,'Bocina compacta Sonos Play:1 color negro (PLAY1US1BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/878269000327.png','2021-02-17 19:57:04',NULL,NULL,'0','admin','2021-02-18 01:57:04','2021-02-18 01:57:04','878269000327','2021-02-17 19:57:04',1164.00),
(368,7,NULL,'Placa de montaje de 10 a 12\\\" pizza Strong color negro (SM-VP-10X12-BLK)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/842822031455.jpg','2021-02-17 21:17:15',NULL,NULL,'0','admin','2021-02-18 03:17:15','2021-02-18 03:17:15','842822031455','2021-02-17 21:17:15',1078.00),
(369,4,NULL,'Soporte de piso para Sonos play:1 flexon color negro (P1-FS)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/702534974801.jpg','2021-02-17 21:20:46',NULL,NULL,'0','admin','2021-02-18 03:20:46','2021-02-18 03:20:46','702534974801','2021-02-17 21:20:46',1114.00),
(370,1,NULL,'Extensor de IR balun binary (01-B-400-1COAX-HDIR)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/842822017909.png','2021-02-17 21:30:19',NULL,NULL,'0','admin','2021-02-18 03:30:19','2021-02-18 03:30:19','842822017909','2021-02-17 21:30:19',1329.00),
(371,1,NULL,'Amplificador de transmisión Sonos connect:amp color blanco (CTAZPUS1)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/180501001017.png','2021-02-17 21:48:50',NULL,NULL,'0','admin','2021-02-18 03:48:50','2021-02-18 03:48:50','180501001017','2021-02-17 21:48:50',1184.00),
(372,0,NULL,'Soporte de montaje de instalacion rapida 32 bose (010148)\r\nBODEGA B-17',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,NULL,'2021-02-17 22:15:44',NULL,NULL,'0','admin','2021-02-18 04:15:44','2021-02-18 04:15:44','017617101486','2021-02-17 22:15:44',0.00),
(373,0,NULL,'Bocina inalambrica heos color negro (HEOS1BK3)\r\nBODEGA B-15',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-18 21:00:17',NULL,NULL,'0','admin','2021-02-19 03:00:17','2021-02-19 03:00:17','883795003612','2021-02-18 21:00:17',0.00),
(374,0,NULL,'Bocina de pared para cine en casa mach speaker (MIW62I)\r\nBODEGA B-18',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-18 21:17:30',NULL,NULL,'0','admin','2021-02-19 03:17:30','2021-02-19 03:17:30','5706681104833','2021-02-18 21:17:30',0.00),
(375,0,NULL,'Bocina jamo USADA color blanco \r\nBODEGA C-1',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-18 21:30:36',NULL,NULL,'0','admin','2021-02-19 03:30:36','2021-02-19 03:30:36','BO-JA-WH','2021-02-18 21:30:36',0.00),
(376,0,NULL,'Bocina jamo USADA color negro \r\nBODEGA C-1',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-18 21:31:08',NULL,NULL,'0','admin','2021-02-19 03:31:08','2021-02-19 03:31:08','BO-JA-BLK','2021-02-18 21:31:08',0.00),
(377,5,NULL,'Base de pared para Sonos Playbar color negro (PBRWMWW1)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/180501002892.jpg','2021-02-18 22:13:52',NULL,NULL,'0','admin','2021-02-19 04:13:52','2021-02-19 04:13:52','180501002892','2021-02-18 22:13:52',NULL),
(378,0,'B3-AUD-4','Cable de audio binary color gris','Binary','CABLES (Accesorios)','Bodega General',NULL,NULL,NULL,'2021-02-19 20:36:50',NULL,NULL,'0','admin','2021-02-20 02:36:50','2021-02-20 02:36:50','842822005166','2021-02-19 20:36:50',NULL),
(379,1,NULL,'Pulsera colgante con botón de ayuda personal, resistente al agua, para activar alarma de emergencia numera color gris (2GIG-PHB1-345)',NULL,'CONTROL REMOTO','Bodega General',NULL,NULL,'/img/productos/093863135514.png','2021-02-19 21:48:39',NULL,NULL,'0','admin','2021-02-20 03:48:39','2021-02-20 03:48:39','093863135514','2021-02-19 21:48:39',1362.00),
(380,0,NULL,'Lampara atenuable ketra\r\nESTANTE: A-21',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-02-23 17:41:35',NULL,NULL,'0','admin','2021-02-23 23:41:35','2021-02-23 23:41:35','HW-A20-LAMP-X','2021-02-23 17:41:35',0.00),
(381,3,NULL,'Puerta de enlace de conexion clara lutron homeworks qs 48V, 4W (HQP7-RF)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276270885.jpg','2021-02-23 17:46:18',NULL,NULL,'0','admin','2021-02-23 23:46:18','2021-02-23 23:46:18','784276270885','2021-02-23 17:46:18',1257.00),
(382,0,NULL,'Procesador lutron homeworks QSX \r\nESTANTE: G-9',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-02-23 17:51:22',NULL,NULL,'0','admin','2021-02-23 23:51:22','2021-02-23 23:51:22','HQP7-1','2021-02-23 17:51:22',0.00),
(383,0,NULL,'Botonera lutron de 5 botones homeworks QS color blanco controla 4 escenas\r\nBAÑO',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-02-23 17:53:58',NULL,NULL,'0','admin','2021-02-23 23:53:58','2021-02-23 23:53:58','HQWD-W4S-WH','2021-02-23 17:53:58',0.00),
(384,1,NULL,'Bocina Revel de techo para clima extremo color blanco (C383XC)',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-23 22:40:44',NULL,NULL,'0','admin','2021-02-24 04:40:44','2021-02-24 04:40:44','6925281908538','2021-02-23 22:40:44',1368.00),
(385,0,NULL,'Receptor denon av de 7.2 canales 4K ultra hd con audio 3D y HEOS integrado (AVR-X1600H)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-23 22:45:08',NULL,NULL,'0','admin','2021-02-24 04:45:08','2021-02-24 04:45:08','883795004848','2021-02-23 22:45:08',0.00),
(386,0,NULL,'Subwoofer klipsch color negro (R-100SW)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-02-23 22:48:20',NULL,NULL,'0','admin','2021-02-24 04:48:20','2021-02-24 04:48:20','743878036190','2021-02-23 22:48:20',0.00),
(387,7,NULL,'Soporte de montaje de chapa plana con alas para fijacion, Harman (MTC-24NC)\\r\\n',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/MTC-24NC.jpg','2021-02-23 22:50:49',NULL,NULL,'0','admin','2021-02-24 04:50:49','2021-02-24 04:50:49','MTC-24NC','2021-02-23 22:50:49',NULL),
(388,0,NULL,'DVR epcom de 16 canales, 8 MP, 4K turbohd + 16 canales IP, 4 canales de audio, 16 entradas de alarma con disco duro\r\nBAÑO',NULL,'VIDEO','Bodega General',NULL,NULL,NULL,'2021-02-26 16:35:03',NULL,NULL,'0','admin','2021-02-26 22:35:03','2021-02-26 22:35:03','EV8016TURBO','2021-02-26 16:35:03',0.00),
(389,13,'LWT-U-PP-QZ','Tapa de 2 ventanas, Palladiom, bronce antiguo','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/784276110402.png','2021-02-26 19:19:45',NULL,NULL,'0','admin','2021-02-27 01:19:45','2021-02-27 01:19:45','784276110402','2021-02-26 19:19:45',1222.00),
(390,0,NULL,'Resistencia amarilla plana\r\nCLOSET',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-02-26 22:19:54',NULL,NULL,'0','admin','2021-02-27 04:19:54','2021-02-27 04:19:54','RES-AM-PLA','2021-02-26 22:19:54',0.00),
(391,19,NULL,'Pre-amplificador Sonos Port color negro (PORT1US1BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/878269008828.png','2021-03-01 17:38:16',NULL,NULL,'0','admin','2021-03-01 23:38:16','2021-03-01 23:38:16','878269008828','2021-03-01 17:38:16',NULL),
(392,6,NULL,'Barra de sonido inteligente Sonos Beam Gen2 color negro (BEAM1US1BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136802327.png','2021-03-01 17:39:43',NULL,NULL,'0','admin','2021-03-01 23:39:43','2021-03-01 23:39:43','840136802327','2021-03-01 17:39:43',NULL),
(393,0,NULL,'Bocina blanca speakercraft (CR26)\r\nBODEGA C-7',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-03-01 17:41:30',NULL,NULL,'0','admin','2021-03-01 23:41:30','2021-03-01 23:41:30','664254913396','2021-03-01 17:41:30',0.00),
(394,0,NULL,'Bocina USADA de pared sonance color negro\r\nBODEGA C-7',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-03-01 17:44:15',NULL,NULL,'0','admin','2021-03-01 23:44:15','2021-03-01 23:44:15','VPXT6-U','2021-03-01 17:44:15',0.00),
(395,0,NULL,'Bocina USADA bose color negro\r\nBODEGA C-7',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-03-01 17:45:29',NULL,NULL,'0','admin','2021-03-01 23:45:29','2021-03-01 23:45:29','151-U','2021-03-01 17:45:29',0.00),
(396,1,NULL,'Amplificador de señal Sonos Boost color blanco (BOOSTUS1)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/878269000853.png','2021-03-01 17:53:02',NULL,NULL,'0','admin','2021-03-01 23:53:02','2021-03-01 23:53:02','878269000853','2021-03-01 17:53:02',1184.00),
(397,2,NULL,'Barra de sonido Sonos Playbar color negro (PBAR1US1BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/180501002847.png','2021-03-01 17:55:18',NULL,NULL,'0','admin','2021-03-01 23:55:18','2021-03-01 23:55:18','180501002847','2021-03-01 17:55:18',1159.00),
(398,0,NULL,'Bocina de pared jamo color blanca\r\nBODEGA C-10',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-03-01 17:56:19',NULL,NULL,'0','admin','2021-03-01 23:56:19','2021-03-01 23:56:19','A500','2021-03-01 17:56:19',0.00),
(399,0,NULL,'Bocina de pared jamo USADA color blanca\r\nBODEGA C-10',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-03-01 17:56:32',NULL,NULL,'0','admin','2021-03-01 23:56:32','2021-03-01 23:56:32','A500-U','2021-03-01 17:56:32',0.00),
(400,2,NULL,'Base para Sonos Beam color negro (BM1WMWW1BLK)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/878269004479.jpg','2021-03-01 17:58:22',NULL,NULL,'0','admin','2021-03-01 23:58:22','2021-03-01 23:58:22','878269004479','2021-03-01 17:58:22',1188.00),
(401,10,'ARCG1US1BLK','ARC, Barra de Sonido inalambrica e inteligente, con Dolby Atmos, color negro','Sonos','AUDIO','Bodega General',NULL,NULL,'/img/productos/840136800019.png','2021-03-01 18:00:09',NULL,NULL,'0','admin','2021-03-02 00:00:09','2021-03-02 00:00:09','840136800019','2021-03-01 18:00:09',NULL),
(402,0,NULL,'Bocina jamo para teatro en casa color negro (A320)\r\nBODEGA C-12',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-03-01 18:02:36',NULL,NULL,'0','admin','2021-03-02 00:02:36','2021-03-02 00:02:36','008634843796','2021-03-01 18:02:36',0.00),
(403,0,NULL,'Bocina para interior/exterior jamo color blanco (A32)\r\nBODEGA C-12',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-03-01 18:04:16',NULL,NULL,'0','admin','2021-03-02 00:04:16','2021-03-02 00:04:16','008634843772','2021-03-01 18:04:16',0.00),
(404,3,NULL,'Crown Amplificador de potencia CDi 2000 de dos canales, 800 vatios a 4 Ω, 70 V/140 V (CDI2000)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991013737.png','2021-03-03 16:17:50',NULL,NULL,'0','admin','2021-03-03 22:17:50','2021-03-03 22:17:50','691991013737','2021-03-03 16:17:50',NULL),
(405,1,NULL,'Cerradura Digital Llave Y Manija Real Living, Yale (YRL221-NR-619)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/7704359090325.png','2021-03-03 17:23:12',NULL,NULL,'0','admin','2021-03-03 23:23:12','2021-03-03 23:23:12','7704359090325','2021-03-03 17:23:12',1325.00),
(406,3,NULL,'Contactor de 2 polos hartland controls (HCB-302)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/HCB-302.png','2021-03-04 18:07:23',NULL,NULL,'0','admin','2021-03-05 00:07:23','2021-03-05 00:07:23','HCB-302','2021-03-04 18:07:23',1280.00),
(407,0,NULL,'Bocina para exterior sonance rectangular (VPXT6)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-03-10 21:36:09',NULL,NULL,'0','admin','2021-03-11 03:36:09','2021-03-11 03:36:09','041093933387','2021-03-10 21:36:09',0.00),
(408,22,'USW-FLEX-MINI','Switch Unifi administrable compacto de 5 puertos 10/100/1000 mbps, soporta poe,','Ubiquiti','SWITCH','Bodega General',NULL,NULL,'/img/productos/817882029605.jpg','2021-03-17 21:13:31',NULL,NULL,'0','admin','2021-03-18 03:13:31','2021-03-18 03:13:31','817882029605','2021-03-17 21:13:31',1338.00),
(409,0,NULL,'Bafle activo plastificado con microfono QMC color negro\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-03-17 22:07:37',NULL,NULL,'0','admin','2021-03-18 04:07:37','2021-03-18 04:07:37','QMC10A','2021-03-17 22:07:37',0.00),
(410,1,NULL,'Tira LED inteligente orvibo de 2m (LS20W)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6928986702685.png','2021-03-19 16:33:32',NULL,NULL,'0','admin','2021-03-19 22:33:32','2021-03-19 22:33:32','6928986702685','2021-03-19 16:33:32',1293.00),
(411,2,NULL,'Control de ventilador de techo lutron homeworks color biscuit (HQRD-2ANF-BI)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/784276040518.jpg','2021-03-19 17:29:32',NULL,NULL,'0','admin','2021-03-19 23:29:32','2021-03-19 23:29:32','784276040518','2021-03-19 17:29:32',1244.00),
(412,9,NULL,'Antena repetidora hibrida cableada/wireless lutron color blanco (HQR-REP-120)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/027557006736.png','2021-03-22 18:36:55',NULL,NULL,'0','admin','2021-03-23 00:36:55','2021-03-23 00:36:55','027557006736','2021-03-22 18:36:55',1257.00),
(413,0,NULL,'Router inalambrico cisco linksys color negro usado en vitafil\r\nESTANTE: B-3',NULL,'RED E INTERNET','Bodega General',NULL,NULL,NULL,'2021-03-23 18:21:11',NULL,NULL,'0','admin','2021-03-24 00:21:11','2021-03-24 00:21:11','E1000-U','2021-03-23 18:21:11',0.00),
(414,0,NULL,'2 Modulos RF con salida de contactos lutron color blanco con cable y clavija en vitafil\r\nESTANTE: B-8',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-03-23 19:05:10',NULL,NULL,'0','admin','2021-03-24 01:05:10','2021-03-24 01:05:10','LMJ-CCO1-24-B-U','2021-03-23 19:05:10',0.00),
(415,0,NULL,'Proyector 4K para entretenimiento y cine en casa benq (TK800M)\r\nBAÑO',NULL,'VIDEO','Bodega General',NULL,NULL,NULL,'2021-03-24 21:38:37',NULL,NULL,'0','admin','2021-03-25 03:38:37','2021-03-25 03:38:37','840046041106','2021-03-24 21:38:37',0.00),
(416,1,NULL,'Interruptor de uso general, 3 vias 15A, lutron claro color gris (CA-3PS-GR-S)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276016223.png','2021-03-27 18:55:43',NULL,NULL,'0','admin','2021-03-28 00:55:43','2021-03-28 00:55:43','784276016223','2021-03-27 18:55:43',1242.00),
(417,0,NULL,'Base de acrilico para antenas DECO\r\nARRIBA CLOSET\r\nBAÑO',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-04-05 18:40:10',NULL,NULL,'0','admin','2021-04-05 23:40:10','2021-04-05 23:40:10','BA-AC-DECO','2021-04-05 18:40:10',0.00),
(418,0,NULL,'Base de acrilico para hub y apple tv\r\nARRIBA DE CLOSET\r\nBODEGA',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-04-05 18:55:24',NULL,NULL,'0','admin','2021-04-05 23:55:24','2021-04-05 23:55:24','BA-AC-HUB','2021-04-05 18:55:24',0.00),
(419,6,'MX2050S','Tapa de 2 ventanas para exterior/intemperie, color gris','Mulberry Metal Products','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/092326110716.png','2021-04-07 22:58:12',NULL,NULL,'0','admin','2021-04-08 03:58:12','2021-04-08 03:58:12','092326110716','2021-04-07 22:58:12',1225.00),
(420,3,'MX3050S','Tapa de 3 ventanas para exterior/intemperie, color gris','Mulberry Metal Products','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/092326112611.png','2021-04-07 22:59:58',NULL,NULL,'0','admin','2021-04-08 03:59:58','2021-04-08 03:59:58','092326112611','2021-04-07 22:59:58',1225.00),
(421,0,NULL,'Fuente industrial epcom power line tipo DNI de 12V (PLI12DC5A)\r\nESTANTE: I-4',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,NULL,'2021-04-09 19:50:28',NULL,NULL,'0','admin','2021-04-10 00:50:28','2021-04-10 00:50:28','2005sty020019','2021-04-09 19:50:28',0.00),
(422,45,NULL,'Atenuador de lampara RF lutron table top homeworksQS color media noche (HQR-3LD-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557011051.png','2021-04-12 21:32:11',NULL,NULL,'0','admin','2021-04-13 02:32:11','2021-04-13 02:32:11','027557011051','2021-04-12 21:32:11',1265.00),
(423,0,NULL,'Control remoto para cerradura\r\nESTANTE: AB-1',NULL,'CERRADURA','Bodega General',NULL,NULL,NULL,'2021-04-14 21:12:36',NULL,NULL,'0','admin','2021-04-15 02:12:36','2021-04-15 02:12:36','RTU5053','2021-04-14 21:12:36',0.00),
(424,95,NULL,'Barra en L horizontal strong rack con desplazamiento lacebar (SR-LACEBAR-H)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/842822024013.jpg','2021-04-15 16:46:25',NULL,NULL,'0','admin','2021-04-15 21:46:25','2021-04-15 21:46:25','842822024013','2021-04-15 16:46:25',1319.00),
(425,0,NULL,'Dispositivo de desvio de llamadas urmet (1083/58)\r\nESTANTE: C-12',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,NULL,'2021-04-17 16:19:48',NULL,NULL,'0','admin','2021-04-17 21:19:48','2021-04-17 21:19:48','8021156057641','2021-04-17 16:19:48',0.00),
(426,0,NULL,'Kit de montaje en pared para desvio de llamadas urmet (1083/88)\r\nESTANTE: C-12',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,NULL,'2021-04-17 16:23:29',NULL,NULL,'0','admin','2021-04-17 21:23:29','2021-04-17 21:23:29','8021156060191','2021-04-17 16:23:29',0.00),
(427,0,NULL,'Monitor manos libres 2voice urmet con pantalla de 4,3\" color negro (1750/5)\r\nESTANTE: C-12',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,NULL,'2021-04-17 16:25:45',NULL,NULL,'0','admin','2021-04-17 21:25:45','2021-04-17 21:25:45','8021156054671','2021-04-17 16:25:45',0.00),
(428,0,NULL,'Splitter HDMI de 4 puertos para video wall (LWCY48152)\r\nBODEGA',NULL,'VIDEO','Bodega General',NULL,NULL,NULL,'2021-04-21 16:22:45',NULL,NULL,'0','admin','2021-04-21 21:22:45','2021-04-21 21:22:45','lw[y481/)','2021-04-21 16:22:45',0.00),
(429,0,NULL,'Frente de calle Urmet Alfa para el audio/video del modulo con 2 botones color negro (1083/48)\r\nESTANTE: C-6',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,NULL,'2021-04-24 20:01:30',NULL,NULL,'0','admin','2021-04-25 01:01:30','2021-04-25 01:01:30','1418200013345','2021-04-24 20:01:30',0.00),
(430,0,NULL,'Frente de calle para exterior 2Voice Mikra Exigo de\r\nlaton satinado color oro urmet (1783/1L)\r\nESTANTE: C-6',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,NULL,'2021-04-24 20:07:35',NULL,NULL,'0','admin','2021-04-25 01:07:35','2021-04-25 01:07:35','8021156049813','2021-04-24 20:07:35',0.00),
(431,0,NULL,'Router empresarial 4 puertos + 2 SFP soporta balanceo de carga y fail over entre 2 WAN Unifi Ubiquiti (USG-PRO-4)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'https://inventario.savicontrolhome.com/img/productos/810354021572.png','2021-04-26 19:16:26',NULL,'','0','admin','2021-04-27 00:16:26','2021-04-27 00:16:26','810354021572','2021-04-26 19:16:26',1302.00),
(432,0,NULL,'Control remoto savant para iluminacion, tonos y clima\r\n(SAVANTPROREMOTEX2)\r\nBAÑO',NULL,'CONTROL REMOTO','Bodega General',NULL,NULL,NULL,'2021-04-26 22:36:16',NULL,NULL,'0','admin','2021-04-27 03:36:16','2021-04-27 03:36:16','863184002853','2021-04-26 22:36:16',0.00),
(433,0,NULL,'Teclado de marcacion inalambrico echo: atenuador adaptable savant color blanco nieve (WPK-SWA105-00)\r\nBAÑO',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-04-26 22:39:29',NULL,NULL,'0','admin','2021-04-27 03:39:29','2021-04-27 03:39:29','728028292206','2021-04-26 22:39:29',0.00),
(434,2,NULL,'Atenuador adaptativo inalambrico savant echo color blanco nieve (WPD-SWA102-00)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/728028290905.png','2021-04-26 22:42:14',NULL,NULL,'0','admin','2021-04-27 03:42:14','2021-04-27 03:42:14','728028290905','2021-04-26 22:42:14',1275.00),
(435,1,NULL,'Qo relay doble 20 amp savant (RPM-Q2R20120-01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/728028448023.png','2021-04-26 22:45:53',NULL,NULL,'0','admin','2021-04-27 03:45:53','2021-04-27 03:45:53','728028448023','2021-04-26 22:45:53',1274.00),
(436,1,NULL,'Atenuador de fase adaptativa dual Qo savant (SPM-Q2APD10-01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/863184002785.png','2021-04-26 22:47:18',NULL,NULL,'0','admin','2021-04-27 03:47:18','2021-04-27 03:47:18','863184002785','2021-04-26 22:47:18',1274.00),
(437,0,NULL,'Teclado configurable inalambrico echo savant color blanco nieve (WPB-SWA106-00)\r\nBAÑO',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-04-26 22:54:53',NULL,NULL,'0','admin','2021-04-27 03:54:53','2021-04-27 03:54:53','8112812292213','2021-04-26 22:54:53',0.00),
(438,1,NULL,'Control de ventilador inalambrico metropolitano Savant color blanco nieve (WIF-SWS104-00)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/728028294767.jpg','2021-04-26 22:56:27',NULL,NULL,'0','admin','2021-04-27 03:56:27','2021-04-27 03:56:27','728028294767','2021-04-26 22:56:27',1275.00),
(439,0,NULL,'Emisor de IR savant (IRB-1006-00)\r\nBAÑO',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-04-26 22:57:53',NULL,NULL,'0','admin','2021-04-27 03:57:53','2021-04-27 03:57:53','728028294262','2021-04-26 22:57:53',0.00),
(440,1,'CLI-W210W-00','Termostato inteligente, color blanco','Savant','AIRE ACONDICIONADO','Bodega General',NULL,NULL,'/img/productos/863184002921.png','2021-04-26 22:59:56',NULL,NULL,'0','admin','2021-04-27 03:59:56','2021-04-27 03:59:56','863184002921','2021-04-26 22:59:56',1274.00),
(441,1,NULL,'Controlador de iluminacion de 8 canales savant (PBC-1000-10)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/863184000651.png','2021-04-26 23:01:38',NULL,NULL,'0','admin','2021-04-27 04:01:38','2021-04-27 04:01:38','863184000651','2021-04-26 23:01:38',1274.00),
(442,6,NULL,'Controlador inalambrico savant smartcontrol 2, wifi, puerto GPIO, relevador savant (SSC-W003I-01)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/728028295290.png','2021-04-26 23:03:31',NULL,NULL,'0','admin','2021-04-27 04:03:31','2021-04-27 04:03:31','728028295290','2021-04-26 23:03:31',1275.00),
(443,1,NULL,'Pantalla tactil de control 5,5 pulgadas savant color blanco (ITP-E5500W-20)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/863184000743.jpg','2021-04-26 23:05:22',NULL,NULL,'0','admin','2021-04-27 04:05:22','2021-04-27 04:05:22','863184000743','2021-04-26 23:05:22',1275.00),
(444,0,NULL,'Modulo de control con IP para sistemas de audio con amplificador savant (HST-SIPA1SM-00)\r\nBAÑO',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-04-26 23:14:56',NULL,NULL,'0','admin','2021-04-27 04:14:56','2021-04-27 04:14:56','863184001597','2021-04-26 23:14:56',0.00),
(445,1,NULL,'Servidor de audio IP savant (PAV-SMS2001-10)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/863184001542.png','2021-04-26 23:17:21',NULL,NULL,'0','admin','2021-04-27 04:17:21','2021-04-27 04:17:21','863184001542','2021-04-26 23:17:21',1275.00),
(446,0,NULL,'Portalampara bticino color blanco (P21BN/SP21BN)\r\nESTANTE: A-21',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-04-26 23:25:53',NULL,NULL,'0','admin','2021-04-27 04:25:53','2021-04-27 04:25:53','7501400636729','2021-04-26 23:25:53',0.00),
(447,0,NULL,'Pastilla electromagnetica square D color negro\r\nESTANTE: A-21',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-04-26 23:29:23',NULL,NULL,'0','admin','2021-04-27 04:29:23','2021-04-27 04:29:23','PA-SQD-BLK','2021-04-26 23:29:23',0.00),
(448,0,NULL,'Centro de carga QOD metalico para 4 pastillas electromagneticas square D\r\nBAÑO',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2021-04-26 23:31:51',NULL,NULL,'0','admin','2021-04-27 04:31:51','2021-04-27 04:31:51','CE-CA-SQD','2021-04-26 23:31:51',0.00),
(449,0,NULL,'No break epcom con proteccion de picos 1500VA (OM1500ATLCD)\r\nBODEGA A-14',NULL,'NO BREAK ','Bodega General',NULL,NULL,NULL,'2021-04-29 19:34:54',NULL,NULL,'0','admin','2021-04-30 00:34:54','2021-04-30 00:34:54','649532612246','2021-04-29 19:34:54',0.00),
(450,1,NULL,'Panel de alarma hibrido IP/WiFi Hikvision (DS-PHA64-W4P)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/302401632.png','2021-04-30 19:19:15',NULL,NULL,'0','admin','2021-05-01 00:19:15','2021-05-01 00:19:15','302401632','2021-04-30 19:19:15',1364.00),
(451,0,NULL,'Detector PIR inalambrico para panel de alarma hikvision/inmunidad a mascotas (DS-PD2-P10P-W)\r\nESTANTE: D-8',NULL,'ALARMA','Bodega General',NULL,NULL,NULL,'2021-04-30 19:32:54',NULL,NULL,'0','admin','2021-05-01 00:32:54','2021-05-01 00:32:54','6941264023203','2021-04-30 19:32:54',0.00),
(452,1,NULL,'Detector PIR interior con cámara inalámbrica/inmunidad a mascotas/rango de detección de 12mts (DS-PDPC12P-EG2-WB)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6941264065593.png','2021-04-30 19:35:13',NULL,NULL,'0','admin','2021-05-01 00:35:13','2021-05-01 00:35:13','6941264065593','2021-04-30 19:35:13',1363.00),
(453,0,NULL,'Gabinete de pared saxxon,6UR, capacidad de carga de hasta 60kg (SE540601)\r\nBODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2021-05-03 22:43:21',NULL,NULL,'0','admin','2021-05-04 03:43:21','2021-05-04 03:43:21','TCE439047','2021-05-03 22:43:21',0.00),
(454,6,NULL,'Insteon plm usb modem interface smarthome (2413U)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/891114000136.jpg','2021-05-05 17:15:19',NULL,NULL,'0','admin','2021-05-05 22:15:19','2021-05-05 22:15:19','891114000136','2021-05-05 17:15:19',1289.00),
(455,1,NULL,'Boton inteligente wifi shelly (SHELLYBUTTON1)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/3809511202623.png','2021-05-05 17:17:48',NULL,NULL,'0','admin','2021-05-05 22:17:48','2021-05-05 22:17:48','3809511202623','2021-05-05 17:17:48',1278.00),
(456,2,'SC-5-MN','Tapa de 5 ventanas, Color Negro','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475846.png','2021-05-08 20:30:48',NULL,NULL,'0','admin','2021-05-09 01:30:48','2021-05-09 01:30:48','027557475846','2021-05-08 20:30:48',1228.00),
(457,3,'SC-6-MN','Tapa de 6 ventanas, Color Negro','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557494700.png','2021-05-08 20:31:54',NULL,NULL,'0','admin','2021-05-09 01:31:54','2021-05-09 01:31:54','027557494700','2021-05-08 20:31:54',1228.00),
(458,0,NULL,'Botonera de mesa lutron homeworksQS de 15 botones color nieve (HQR-T15RL-SW)\r\nESTANTE: A-9',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-05-08 20:33:35',NULL,NULL,'0','admin','2021-05-09 01:33:35','2021-05-09 01:33:35','027557006798','2021-05-08 20:33:35',0.00),
(459,18,NULL,'Contacto duplex falla tierra de 15A con proteccion para niños Lutron Claro, color blanco (CAR-15-GFST-WH-S)\\\\r\\\\nBAÑO',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276159647.png','2021-05-08 20:37:30',NULL,NULL,'0','admin','2021-05-09 01:37:30','2021-05-09 01:37:30','784276159647','2021-05-08 20:37:30',1263.00),
(460,13,NULL,'Interruptor de uso general lutron claro color blanco (CA-1PS-WH-S)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276016148.png','2021-05-08 20:43:57',NULL,NULL,'0','admin','2021-05-09 01:43:57','2021-05-09 01:43:57','784276016148','2021-05-08 20:43:57',1242.00),
(461,24,NULL,'Contacto duplex sencillo de 15A lutron claro color blanco (CAR-15-WH-S)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276013420.png','2021-05-10 19:21:23',NULL,NULL,'0','admin','2021-05-11 00:21:23','2021-05-11 00:21:23','784276013420','2021-05-10 19:21:23',1242.00),
(462,4,NULL,'Sirena inalámbrica con estrobo azul para exterior/110dB Hikvision (DS-PS1-E-WB)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6941264065197.png','2021-05-11 20:39:59',NULL,NULL,'0','admin','2021-05-12 01:39:59','2021-05-12 01:39:59','6941264065197','2021-05-11 20:39:59',NULL),
(463,1,NULL,'Comunicador 3G/4G / Compatible con el Panel de Alarma Hibrido Hikvision (DS-PMA-S2)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/6941264024668.jpg','2021-05-11 20:50:47',NULL,NULL,'0','admin','2021-05-12 01:50:47','2021-05-12 01:50:47','6941264024668','2021-05-11 20:50:47',1368.00),
(464,0,NULL,'Patchpanel panduit de 24 puertos de plastico color negro (NKPPN24P)\r\nESTANTE: ID-3',NULL,'RED E INTERNET','Bodega General',NULL,NULL,NULL,'2021-05-11 21:21:36',NULL,NULL,'0','admin','2021-05-12 02:21:36','2021-05-12 02:21:36','074983357048','2021-05-11 21:21:36',0.00),
(465,0,NULL,'Contacto duplex USB eaton color blanco (TR7755W)',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-05-12 18:16:56',NULL,NULL,'0','admin','2021-05-12 23:16:56','2021-05-12 23:16:56','032664741531','2021-05-12 18:16:56',1277.00),
(466,1,NULL,'Switch ubiquiti de 48 puertos poe+ administrable, 2 puertos SFP gigabit, 500W (US-48-500W)\r\nESTANTE: B-28',NULL,'RED E INTERNET','Bodega General',NULL,NULL,NULL,'2021-05-13 21:20:16',NULL,NULL,'0','admin','2021-05-14 02:20:16','2021-05-14 02:20:16','810354020780','2021-05-13 21:20:16',0.00),
(467,0,'DS-3E0109P-E/M (B)','Switch de 8 puertos con poe + 250M poe larga distancia 30W','Hikvision','SWITCH','Bodega General',NULL,NULL,'/img/productos/6941264013945.jpg','2021-05-13 21:28:54',NULL,NULL,'0','admin','2021-05-14 02:28:54','2021-05-14 02:28:54','6941264013945','2021-05-13 21:28:54',1303.00),
(468,0,NULL,'Controlador ubiquiti cloud key gen2 para gestionar hasta 100 dispositivos, portal cautivo, alertas, configura via remota (UCK-G2)\r\nESTANTE: I-3',NULL,'RED E INTERNET','Bodega General',NULL,NULL,NULL,'2021-05-13 21:34:47',NULL,NULL,'0','admin','2021-05-14 02:34:47','2021-05-14 02:34:47','817882024525','2021-05-13 21:34:47',0.00),
(469,0,NULL,'Jumper de 1 metro para conexion directa SFP+ ufiber (UDC-1)\r\nESTANTE: F-2',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2021-05-13 21:52:09',NULL,NULL,'0','admin','2021-05-14 02:52:09','2021-05-14 02:52:09','817882020527','2021-05-13 21:52:09',0.00),
(470,0,NULL,'Cable de parcheo patchcord UTP categoria 6, con plug modular en cada extremo de 1M panduit color azul (NK6PZ3BUY)\r\nESTANTE: H-1',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2021-05-13 21:56:38',NULL,NULL,'0','admin','2021-05-14 02:56:38','2021-05-14 02:56:38','074983055609','2021-05-13 21:56:38',0.00),
(471,0,'NK6PC3Y','Cable de parcheo patchcord UTP Categoría 6, con plug modular en cada extremo de 1M, color blanco mate\\r\\n','Panduid',' PATCHCORD','Bodega General',NULL,NULL,NULL,'2021-05-13 22:00:36',NULL,NULL,'0','admin','2021-05-14 03:00:36','2021-05-14 03:00:36','074983055593','2021-05-13 22:00:36',NULL),
(472,0,'NK6PC7BUY','Cable de parcheo patchcord UTP categoria 6, con plug modular en cada extremo de 2M panduit color azul','Panduid',' PATCHCORD','Bodega General',NULL,NULL,NULL,'2021-05-13 22:10:46',NULL,NULL,'0','admin','2021-05-14 03:10:46','2021-05-14 03:10:46','074983055708','2021-05-13 22:10:46',NULL),
(473,0,NULL,'Acondicionador de voltaje wattbox montaje en rack de 5 toma corrientes (WB-300VB-IP-5)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/842822034203.jpg','2021-05-13 22:15:57',NULL,NULL,'0','admin','2021-05-14 03:15:57','2021-05-14 03:15:57','842822034203','2021-05-13 22:15:57',1023.00),
(474,0,NULL,'No break de 2000 VA/1320W, entrada 120Vca, con 8 tomas cyberpower (OR2200LCDRT2U)\r\nBODEGA',NULL,'NO BREAK ','Bodega General',NULL,NULL,NULL,'2021-05-14 17:58:57',NULL,NULL,'0','admin','2021-05-14 22:58:57','2021-05-14 22:58:57','649532610037','2021-05-14 17:58:57',0.00),
(475,0,NULL,'Switch tp-link de 24 puertos gigabit y 4 puertos 10gbps SFP+ (TL-SG3428X)\r\nESTANTE: B-26',NULL,'RED E INTERNET','Bodega General',NULL,NULL,NULL,'2021-05-21 17:59:03',NULL,NULL,'0','admin','2021-05-21 22:59:03','2021-05-21 22:59:03','840030702082','2021-05-21 17:59:03',0.00),
(476,18,NULL,'Sensor de movimiento y ruptura de cristal inalámbrico Hikvision AXPRO (DS-PDPG12P-EG2-WB)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6941264073567.png','2021-05-21 18:12:24',NULL,NULL,'0','admin','2021-05-21 23:12:24','2021-05-21 23:12:24','6941264073567','2021-05-21 18:12:24',1363.00),
(477,3,NULL,'Relevador inalámbrico 1 entrada de alarma 27/7  1 salida de relevador 0 a 36 VCD (Max. 5 A) Hikvision AXPRO (DS-PM1-O1L-WB)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6941264076971.png','2021-05-21 18:15:48',NULL,NULL,'0','admin','2021-05-21 23:15:48','2021-05-21 23:15:48','6941264076971','2021-05-21 18:15:48',1363.00),
(478,0,NULL,'Gabinete para montaje en pared, puerta de cristal templado, cuerpo fijo con rack 19\" de 9 unidades linkedpro \r\nBODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2021-05-21 18:38:34',NULL,NULL,'0','admin','2021-05-21 23:38:34','2021-05-21 23:38:34','SR-1909-GFP','2021-05-21 18:38:34',0.00),
(479,0,NULL,'Disco duro purple de 2TB, 3 años de garantia, para videovigilancia western digital (WD20PURZ)\r\nESTANTE: I-2',NULL,'HARDWARE','Bodega General',NULL,NULL,NULL,'2021-05-26 19:27:37',NULL,NULL,'0','admin','2021-05-27 00:27:37','2021-05-27 00:27:37','wcc4m7spz209','2021-05-26 19:27:37',0.00),
(480,3,NULL,'Contacto duplex usb leviton color blanco 15A, 125V (T5632-W)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/078477683866.png','2021-05-26 20:47:17',NULL,NULL,'0','admin','2021-05-27 01:47:17','2021-05-27 01:47:17','078477683866','2021-05-26 20:47:17',1276.00),
(481,0,NULL,'Kit de 4 bocinas de estaca, 1 subwoofer para exterior sonance (PATIO4.1)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-06-01 19:39:15',NULL,NULL,'0','admin','2021-06-02 00:39:15','2021-06-02 00:39:15','041093934292','2021-06-01 19:39:15',0.00),
(482,6,NULL,'Antena repetidora lutron radiora2 blanca (RR-AUX-REP-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557774666.png','2021-06-01 21:16:11',NULL,NULL,'0','admin','2021-06-02 02:16:11','2021-06-02 02:16:11','027557774666','2021-06-01 21:16:11',1252.00),
(483,0,NULL,'Controlador de entretenimiento y automatizacion control4 (C4-EA5-V2)\r\nBAÑO',NULL,'DOMOTICA','Bodega General',NULL,NULL,NULL,'2021-06-03 16:54:49',NULL,NULL,'0','admin','2021-06-03 21:54:49','2021-06-03 21:54:49','876389032099','2021-06-03 16:54:49',0.00),
(484,2,NULL,'Videoportero IP (SIP) FISHEYE, apertura por codigo, antivandalico, llamada y/o tarjeta, teclado retro-iluminacion grandstream (GDS3710)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6947273702177.png','2021-06-03 20:16:32',NULL,NULL,'0','admin','2021-06-04 01:16:32','2021-06-04 01:16:32','6947273702177','2021-06-03 20:16:32',1330.00),
(485,1,NULL,'Altavoz de techo 6.5 pulgadas LitheAudio color negro (LWFI)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/5060544551043.png','2021-06-03 23:36:38',NULL,NULL,'0','admin','2021-06-04 04:36:38','2021-06-04 04:36:38','5060544551043','2021-06-03 23:36:38',NULL),
(486,0,NULL,'Subwoofer enterrado de 10\" speakercraft boomtomb color verde (ASM6710BT)\r\nESTANTE: I-4',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-06-04 21:01:48',NULL,NULL,'0','admin','2021-06-05 02:01:48','2021-06-05 02:01:48','664254011795','2021-06-04 21:01:48',0.00),
(487,2,NULL,'Cubierta industrial para montaje de porteros IP fanvil y grandstream color gris (BASEINT)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/BASEINT.png','2021-06-04 21:30:31',NULL,NULL,'0','admin','2021-06-05 02:30:31','2021-06-05 02:30:31','BASEINT','2021-06-04 21:30:31',1026.00),
(488,1,NULL,'Satelite para montaje en superficie bose freespace3 color blanco (040143)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/017817391535.png','2021-06-08 22:47:17',NULL,NULL,'0','admin','2021-06-09 03:47:17','2021-06-09 03:47:17','017817391535','2021-06-08 22:47:17',1020.00),
(489,1,NULL,'Bocina para exterior tipo hongo bose color verde freespace51 (031763)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/017817325455.png','2021-06-08 22:53:31',NULL,NULL,'0','admin','2021-06-09 03:53:31','2021-06-09 03:53:31','017817325455','2021-06-08 22:53:31',1053.00),
(490,0,NULL,'Pantalla samsung UHD de 55 pulgadas (55\") (UN55AU7000F)\r\nTALLER',NULL,'TV','Bodega General',NULL,NULL,NULL,'2021-06-10 19:26:59',NULL,NULL,'0','admin','2021-06-11 00:26:59','2021-06-11 00:26:59','8806092057425','2021-06-10 19:26:59',0.00),
(491,0,NULL,'Pantalla samsung UHD de 85\" (UN85AU7000F)\r\nTALLER',NULL,'TV','Bodega General',NULL,NULL,NULL,'2021-06-10 19:28:10',NULL,NULL,'0','admin','2021-06-11 00:28:10','2021-06-11 00:28:10','8806092057470','2021-06-10 19:28:10',0.00),
(492,0,NULL,'Pantalla samsung UHD de 75\" (UN75AU7000F)\r\nTALLER',NULL,'TV','Bodega General',NULL,NULL,NULL,'2021-06-10 19:29:10',NULL,NULL,'0','admin','2021-06-11 00:29:10','2021-06-11 00:29:10','8806092057463','2021-06-10 19:29:10',0.00),
(493,0,NULL,'Pantalla samsung QLED de 65\" (QN65Q60AAF)\r\nTALLER',NULL,'TV','Bodega General',NULL,NULL,NULL,'2021-06-10 19:30:29',NULL,NULL,'0','admin','2021-06-11 00:30:29','2021-06-11 00:30:29','8806090962073','2021-06-10 19:30:29',0.00),
(494,4,NULL,'Regulador de iluminacion dimmer inteligente brillant color blanco (BHS120US-WH1)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/855591007507.png','2021-06-14 16:56:20',NULL,NULL,'0','admin','2021-06-14 21:56:20','2021-06-14 21:56:20','855591007507','2021-06-14 16:56:20',1288.00),
(495,3,NULL,'Contacto inteligente brillant color blanco (BHP120US-WH1)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/855591007491.png','2021-06-14 17:00:46',NULL,NULL,'0','admin','2021-06-14 22:00:46','2021-06-14 22:00:46','855591007491','2021-06-14 17:00:46',1288.00),
(496,1,NULL,'Control de casa triple switch panel todo en uno brillant color blanco (BHA120US-WH3)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/855591007033.png','2021-06-14 17:03:33',NULL,NULL,'0','admin','2021-06-14 22:03:33','2021-06-14 22:03:33','855591007033','2021-06-14 17:03:33',1282.00),
(497,116,'CW-3-SS','Tapa de 3 ventanas de acero inoxidable','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557223263.png','2021-06-17 18:38:09',NULL,NULL,'0','admin','2021-06-17 23:38:09','2021-06-17 23:38:09','027557223263','2021-06-17 18:38:09',1223.00),
(498,0,NULL,'Kit de seguridad dahua con dvr de 4 canales y 4 camaras de vigilancia de 2MP (DH-KIT/XVR1B04/4-B1A21N)\r\nBODEGA\r\nBAÑO',NULL,'VIDEO','Bodega General',NULL,NULL,NULL,'2021-06-17 19:59:20',NULL,NULL,'0','admin','2021-06-18 00:59:20','2021-06-18 00:59:20','6L00EDEPA10E581','2021-06-17 19:59:20',0.00),
(499,0,NULL,'Kit de seguridad dahua con dvr de 8 canales y 4 camaras de vigilancia de 1080P (DH-KIT/XVR1B08/8-B2A21N)\r\nBODEGA',NULL,'VIDEO','Bodega General',NULL,NULL,NULL,'2021-06-17 20:02:27',NULL,NULL,'0','admin','2021-06-18 01:02:27','2021-06-18 01:02:27','7A0A3D8PA1A0A30','2021-06-17 20:02:27',0.00),
(500,0,NULL,'Alimentador de sistema SCS: entrada 127VAC, salida 27VDC bticino\r\nCLOSET',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,NULL,'2021-06-22 18:17:09',NULL,NULL,'0','admin','2021-06-22 23:17:09','2021-06-22 23:17:09','E46ADCN/127','2021-06-22 18:17:09',0.00),
(501,0,NULL,'Interfaz registrador de la ultima condicion del sistema bticino \r\nCLOSET',NULL,'DOMOTICA','Bodega General',NULL,NULL,NULL,'2021-06-22 18:24:57',NULL,NULL,'0','admin','2021-06-22 23:24:57','2021-06-22 23:24:57','F425','2021-06-22 18:24:57',0.00),
(502,0,NULL,'Comando de 2 funciones my home up bticino \r\nCLOSET',NULL,'DOMOTICA','Bodega General',NULL,NULL,NULL,'2021-06-22 18:28:51',NULL,NULL,'0','admin','2021-06-22 23:28:51','2021-06-22 23:28:51','K4652M2','2021-06-22 18:28:51',0.00),
(503,1,NULL,'Botonera de 3 funciones my home up bticino (K4652M3)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/8005543615164.png','2021-06-22 18:30:08',NULL,NULL,'0','admin','2021-06-22 23:30:08','2021-06-22 23:30:08','8005543615164','2021-06-22 18:30:08',1290.00),
(504,0,NULL,'Tapa de 1 modulo con luna color blanco bticino CLOSET',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-06-22 18:34:26',NULL,NULL,'0','admin','2021-06-22 23:34:26','2021-06-22 23:34:26','KW01MHBED','2021-06-22 18:34:26',0.00),
(505,0,NULL,'Tapa de 1 modulo con salida color blanco bticino CLOSET',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-06-22 18:36:24',NULL,NULL,'0','admin','2021-06-22 23:36:24','2021-06-22 23:36:24','KW01MHGEN','2021-06-22 18:36:24',0.00),
(506,13,NULL,'Multicontacto largo de 6 salidas 2.5mts steren (905-215)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/7506250912696.jpg','2021-06-22 20:05:59',NULL,NULL,'0','admin','2021-06-23 01:05:59','2021-06-23 01:05:59','7506250912696','2021-06-22 20:05:59',NULL),
(507,1,NULL,'Procesador de zona digital DBX 16x6 zonepro (DBX-1261MVVM)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991401190.png','2021-06-23 22:37:36',NULL,NULL,'0','admin','2021-06-24 03:37:36','2021-06-24 03:37:36','691991401190','2021-06-23 22:37:36',1120.00),
(508,0,NULL,'Altavoz de frecuencias graves subwoofer klipsch (SPL-120)\r\nTALLER',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-06-23 22:49:19',NULL,NULL,'0','admin','2021-06-24 03:49:19','2021-06-24 03:49:19','743878036084','2021-06-23 22:49:19',0.00),
(509,0,NULL,'Cable coaxial wirepath RG6 USADO, CCS, de 500ft color negro\r\nBODEGA',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2021-06-24 18:52:54',NULL,NULL,'0','admin','2021-06-24 23:52:54','2021-06-24 23:52:54','NST-RG6-500-BLK-U','2021-06-24 18:52:54',0.00),
(510,0,'LPUT6100BU','Patchcord  de 1M cat6 UTP color azul','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,NULL,'2021-06-25 22:41:42',NULL,NULL,'0','admin','2021-06-26 03:41:42','2021-06-26 03:41:42','LPUT6100BU','2021-06-25 22:41:42',NULL),
(511,71,'LPUT6050BU','Patchcord linkedpro de 0.5M cat6 UTP color azul','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPUT6050BU.jpg','2021-06-25 23:14:13',NULL,NULL,'0','admin','2021-06-26 04:14:13','2021-06-26 04:14:13','LPUT6050BU','2021-06-25 23:14:13',1351.00),
(512,1,NULL,'Boton de panico inalambrico para panel de alarma Hikvision AXPRO (DS-PDEB1-EG2-WB)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6941264065654.png','2021-06-25 23:20:31',NULL,NULL,'0','admin','2021-06-26 04:20:31','2021-06-26 04:20:31','6941264065654','2021-06-25 23:20:31',1363.00),
(513,3,NULL,'Disco duro Purple de 3TB, 3 años de garantia, para videovigilancia Western digital (WD30PURZ)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/WD30PURZ.png','2021-06-25 23:25:49',NULL,NULL,'0','admin','2021-06-26 04:25:49','2021-06-26 04:25:49','WD30PURZ','2021-06-25 23:25:49',1354.00),
(514,0,NULL,'Switch ubiquiti de 12 puertos SFP, 10Gb,4 puertos 10G (US-16-XG)\r\nBAÑO',NULL,'RED E INTERNET','Bodega General',NULL,NULL,NULL,'2021-06-26 17:56:51',NULL,NULL,'0','admin','2021-06-26 22:56:51','2021-06-26 22:56:51','810354026072','2021-06-26 17:56:51',0.00),
(515,0,NULL,'Multicontacto horizontal panduit de 12 contactos color negro\r\nTALLER',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2021-06-26 18:00:19',NULL,NULL,'0','admin','2021-06-26 23:00:19','2021-06-26 23:00:19','P12B01M','2021-06-26 18:00:19',0.00),
(516,0,NULL,'No break cyberpower de 1500VA con 8 tomas\r\nTALLER',NULL,'NO BREAK ','Bodega General',NULL,NULL,NULL,'2021-06-26 18:07:16',NULL,NULL,'0','admin','2021-06-26 23:07:16','2021-06-26 23:07:16','PR1500RTXL2U','2021-06-26 18:07:16',0.00),
(517,0,NULL,'No break cyberpower de 1000VA con 9 tomas\r\nTALLER',NULL,'NO BREAK ','Bodega General',NULL,NULL,NULL,'2021-06-26 18:12:47',NULL,NULL,'0','admin','2021-06-26 23:12:47','2021-06-26 23:12:47','CP1000AVRLCD','2021-06-26 18:12:47',0.00),
(518,0,NULL,'No break cyberpower de 1000VA online doble conversion\r\nTALLER',NULL,'NO BREAK ','Bodega General',NULL,NULL,NULL,'2021-06-26 18:13:31',NULL,NULL,'0','admin','2021-06-26 23:13:31','2021-06-26 23:13:31','OL1000RTXL2U','2021-06-26 18:13:31',0.00),
(519,0,NULL,'No break cyberpower 500VA con 6 tomas\r\nTALLER',NULL,'NO BREAK ','Bodega General',NULL,NULL,NULL,'2021-06-26 18:15:21',NULL,NULL,'0','admin','2021-06-26 23:15:21','2021-06-26 23:15:21','OR500LCDRM1U','2021-06-26 18:15:21',0.00),
(520,0,NULL,'Organizador de cables horizontal panduit doble, frontal y posterior, tapa extendida \r\nTALLER',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-06-26 18:19:02',NULL,NULL,'0','admin','2021-06-26 23:19:02','2021-06-26 23:19:02','WMPH2E','2021-06-26 18:19:02',0.00),
(521,0,NULL,'Boton timbre switch compañero lutron satin colors color taupe (MSC-AS-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-06-29 21:07:37',NULL,NULL,'0','admin','2021-06-30 02:07:37','2021-06-30 02:07:37','027557066167','2021-06-29 21:07:37',1268.00),
(522,9,NULL,'Procesador MDU lutron del 2do enlace homeworks QSX 1.2 (HQP7-2)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276270984.png','2021-06-29 21:14:48',NULL,'PACIFICA-1 | APARTADO:PROYECTO | FOLIO:PACIFICA-1 | VENCE:2026-07-02\r\nSE APARTA 1pza. PARA PROYECTO PACIFICA-1','1','admin','2021-06-30 02:14:48','2021-06-30 02:14:48','784276270984','2021-06-29 21:14:48',1257.00),
(523,6,NULL,'Modulo de cargador USB bticino 1 toma color negro (K4285C1)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/8005543620991.jpg','2021-07-01 23:15:11',NULL,NULL,'0','admin','2021-07-02 04:15:11','2021-07-02 04:15:11','8005543620991','2021-07-01 23:15:11',1290.00),
(524,0,NULL,'Frente de switch lutron color piedra caliza\r\nESTANTE: A-7',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-07-02 17:30:33',NULL,NULL,'0','admin','2021-07-02 22:30:33','2021-07-02 22:30:33','RK-S-LS','2021-07-02 17:30:33',0.00),
(525,4,NULL,'Contacto duplex leviton USB color negro (R05-T5632-BE)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/078477683842.png','2021-07-02 18:18:47',NULL,NULL,'0','admin','2021-07-02 23:18:47','2021-07-02 23:18:47','078477683842','2021-07-02 18:18:47',1276.00),
(526,14,NULL,'Panel de metal de 1U accesorio Strong color negro (SR-BLNK-1U)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/842822018401.jpg','2021-07-02 18:27:24',NULL,NULL,'0','admin','2021-07-02 23:27:24','2021-07-02 23:27:24','842822018401','2021-07-02 18:27:24',NULL),
(527,10,NULL,'Actuador de 4 relays bticino (F411/4)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/8012199425061.png','2021-07-05 16:31:29',NULL,NULL,'0','admin','2021-07-05 21:31:29','2021-07-05 21:31:29','8012199425061','2021-07-05 16:31:29',1290.00),
(528,0,NULL,'Placa living now 6 modulos - blanco - material: tecnopolimero, bticino (KA4806KW)\r\nCLOSET',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-07-05 16:38:33',NULL,NULL,'0','admin','2021-07-05 21:38:33','2021-07-05 21:38:33','8005543614686','2021-07-05 16:38:33',0.00),
(529,30,'F418U2','Actuador dimmer universal myhome','Bticino','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/8005543534991.png','2021-07-05 16:42:03',NULL,NULL,'0','admin','2021-07-05 21:42:03','2021-07-05 21:42:03','8005543534991','2021-07-05 16:42:03',1290.00),
(530,16,NULL,'Interfase de comando con dos contactos independientes bticino (3477)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/8012199653136.png','2021-07-13 23:26:42',NULL,NULL,'0','admin','2021-07-14 04:26:42','2021-07-14 04:26:42','8012199653136','2021-07-13 23:26:42',1295.00),
(531,9,NULL,'Placa living now 2 modulos color blanco con simbolo de sol bticino (KW01MH2A)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543619780.png','2021-07-13 23:30:39',NULL,NULL,'0','admin','2021-07-14 04:30:39','2026-09-08 10:36:59','8005543619780','2021-07-13 23:30:39',1290.00),
(532,10,NULL,'Placa living now 2 modulos color blanco ON/OFF bticino (KW01MH2AG)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543619803.png','2021-07-13 23:32:31',NULL,NULL,'0','admin','2021-07-14 04:32:31','2021-07-14 04:32:31','8005543619803','2021-07-13 23:32:31',1290.00),
(533,10,NULL,'Placa living now 1 modulo color blanco entrada USB bticino (KW10C)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543614013.png','2021-07-13 23:36:29',NULL,NULL,'0','admin','2021-07-14 04:36:29','2021-07-14 04:36:29','8005543614013','2021-07-13 23:36:29',1290.00),
(534,100,NULL,'Chasis soporte de 2 modulos bticino color negro (K4702)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543615195.png','2021-07-14 16:47:17',NULL,NULL,'0','admin','2021-07-14 21:47:17','2021-07-14 21:47:17','8005543615195','2021-07-14 16:47:17',1291.00),
(535,2,NULL,'Emisor IR con disco bticino (3456)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/8005543400944.png','2021-07-14 19:34:19',NULL,NULL,'0','admin','2021-07-15 00:34:19','2021-07-15 00:34:19','8005543400944','2021-07-14 19:34:19',1290.00),
(536,0,NULL,'Sistema de bocinas y subwoofer sonance garden series \r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-07-15 17:54:59',NULL,NULL,'0','admin','2021-07-15 22:54:59','2021-07-15 22:54:59','SGS-8.1','2021-07-15 17:54:59',0.00),
(537,0,NULL,'Base de pared para bocinas sonos one o sonos play:1 marca flexson color blanca (S1-WM) ESTANTE: AB-1',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,NULL,'2021-07-15 23:44:42',NULL,NULL,'0','admin','2021-07-16 04:44:42','2021-07-16 04:44:42','652508362328','2021-07-15 23:44:42',0.00),
(538,1,NULL,'Bocina wifi Sonos One sl color blanco (ONESLUS1)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/878269008477.png','2021-07-15 23:46:43',NULL,NULL,'0','admin','2021-07-16 04:46:43','2021-07-16 04:46:43','878269008477','2021-07-15 23:46:43',1189.00),
(539,7,NULL,'Placa living now 1 modulo color blanco con simbolo de sol bticino (KW01A)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543613313.png','2021-07-21 23:00:45',NULL,NULL,'0','admin','2021-07-22 04:00:45','2021-07-22 04:00:45','8005543613313','2021-07-21 23:00:45',1290.00),
(540,0,NULL,'Carrete de fibra optica monomodo con conectores SC-SC duplex, 300m linkedpro\r\nBODEGA',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2021-08-04 16:35:03',NULL,NULL,'0','admin','2021-08-04 21:35:03','2021-08-04 21:35:03','EF-300-SC','2021-08-04 16:35:03',0.00),
(541,0,NULL,'Videograbadora digital DVR hikvision de 8 canales (DS-7316HGHI-SH)\r\nBAÑO',NULL,'VIDEO','Bodega General',NULL,NULL,NULL,'2021-08-04 18:16:33',NULL,NULL,'0','admin','2021-08-04 23:16:33','2021-08-04 23:16:33','6954273635237','2021-08-04 18:16:33',0.00),
(542,17,NULL,'Actuador de 2 canales de gestion de lamparas LED bticino (F411U2)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/8005543533871.png','2021-08-04 20:47:04',NULL,NULL,'0','admin','2021-08-05 01:47:04','2021-08-05 01:47:04','8005543533871','2021-08-04 20:47:04',1290.00),
(543,0,'576-125-005','Patchcord de 0.5m CAT6 color blanco','Legrand',' PATCHCORD','Bodega General',NULL,NULL,NULL,'2021-08-04 21:00:09',NULL,NULL,'0','admin','2021-08-05 02:00:09','2021-08-05 02:00:09','884815779999','2021-08-04 21:00:09',NULL),
(544,0,NULL,'DVR epcom de 8 canales negra (EV4008TURBOD)\r\nBAÑO',NULL,'VIDEO','Bodega General',NULL,NULL,NULL,'2021-08-04 21:52:24',NULL,NULL,'0','admin','2021-08-05 02:52:24','2021-08-05 02:52:24','300226498','2021-08-04 21:52:24',0.00),
(545,0,NULL,'Gabinete de pared con rack horizontal de 6 unidades linkedpro color negro\r\nSALA JUNTAS',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2021-08-04 23:12:26',NULL,NULL,'0','admin','2021-08-05 04:12:26','2021-08-05 04:12:26','LPGPH06','2021-08-04 23:12:26',0.00),
(546,14,NULL,'Switch lutron radiora2 color taupe (RRD-8ANS-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557771801.png','2021-08-06 17:01:00',NULL,NULL,'0','admin','2021-08-06 22:01:00','2021-08-06 22:01:00','027557771801','2021-08-06 17:01:00',1267.00),
(547,2,'USW-PRO-24-POE','UniFi Switch USW-Pro-24-POE Gen2, con funciones capa 3, de 24 puertos PoE 802.3at/bt + 2 puertos 1/10G SFP+, 400W, pantalla informativa','Ubiquiti','SWITCH','Bodega General',NULL,NULL,'/img/productos/817882028349.jpg','2021-08-07 17:59:01',NULL,NULL,'0','admin','2021-08-07 22:59:01','2021-08-07 22:59:01','817882028349','2021-08-07 17:59:01',1302.00),
(548,1,NULL,'Regulador de voltaje solabasic isb de 500VA, 500W (PC-500)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/660077600031.png','2021-08-11 17:04:07',NULL,NULL,'0','admin','2021-08-11 22:04:07','2021-08-11 22:04:07','660077600031','2021-08-11 17:04:07',1014.00),
(549,5,NULL,'Controlador Ubiquiti cloud key gen2 PLUS para gestionar hasta 100 dispositivos, portal cautivo, alertas, configura via remota (UCK-G2-PLUS)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/817882024532.jpg','2021-08-13 20:33:56',NULL,NULL,'0','admin','2021-08-14 01:33:56','2021-08-14 01:33:56','817882024532','2021-08-13 20:33:56',1358.00),
(550,17,NULL,'Regulador de voltaje y protector de sobrecarga Panamax M5300 (M5300-PM)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/M5300-PM.jpg','2021-08-16 17:05:34',NULL,NULL,'0','admin','2021-08-16 22:05:34','2021-08-16 22:05:34','M5300-PM','2021-08-16 17:05:34',NULL),
(551,2,'DS-KB2421-IM','Frente de calle o videoportero Hikvision color gris','Hikvision','INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6954273654009.jpg','2021-08-16 18:52:42',NULL,NULL,'0','admin','2021-08-16 23:52:42','2021-08-16 23:52:42','6954273654009','2021-08-16 18:52:42',1367.00),
(552,1,NULL,'Botonera hibrida lutron de 5 botones radioRa2 color taupe (RRD-H5BRL-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557986625.png','2021-08-17 20:59:47',NULL,NULL,'0','admin','2021-08-18 01:59:47','2021-08-18 01:59:47','027557986625','2021-08-17 20:59:47',1271.00),
(553,0,NULL,'Termostato Google Nest color blanco (G4CVZ)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/193575007649.png','2021-08-25 21:22:47',NULL,NULL,'0','admin','2021-08-26 02:22:47','2021-08-26 02:22:47','193575007649','2021-08-25 21:22:47',1358.00),
(554,1,'RCHT8612WF','Termostato digital honeywell home color negro','Honeywell','AIRE ACONDICIONADO','Bodega General',NULL,NULL,'/img/productos/085267865647.png','2021-08-25 21:24:30',NULL,NULL,'0','admin','2021-08-26 02:24:30','2021-08-26 02:24:30','085267865647','2021-08-25 21:24:30',1284.00),
(555,1,NULL,'Cerradura bidireccional digital tecnolite connect negra (CIE202VCDTCW-BD)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/7506134800996.jpg','2021-08-26 17:51:39',NULL,NULL,'0','admin','2021-08-26 22:51:39','2021-08-26 22:51:39','7506134800996','2021-08-26 17:51:39',1325.00),
(556,0,NULL,'Interruptor dimmer inteligente de 3 botones tecnolite connect color blanco (TDSWI1MVBTCW)\r\nESTANTE: BB-1',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2021-08-26 18:00:27',NULL,NULL,'0','admin','2021-08-26 23:00:27','2021-08-26 23:00:27','7501668564444','2021-08-26 18:00:27',0.00),
(557,15,NULL,'Chasis soporte de 6 modulos bticino color negro (K4706)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543611128.png','2021-08-26 19:09:29',NULL,NULL,'0','admin','2021-08-27 00:09:29','2021-08-27 00:09:29','8005543611128','2021-08-26 19:09:29',1291.00),
(558,15,NULL,'Chasis soporte de 4 modulos bticino color negro (K4704L)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543649763.png','2021-08-26 19:22:16',NULL,NULL,'0','admin','2021-08-27 00:22:16','2021-08-27 00:22:16','8005543649763','2021-08-26 19:22:16',1291.00),
(559,6,NULL,'Bocina de techo redonda Revel color blanca 8 pulgadas (C583)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/848592000226.png','2021-09-01 21:04:23',NULL,NULL,'0','admin','2021-09-02 02:04:23','2021-09-02 02:04:23','848592000226','2021-09-01 21:04:23',1174.00),
(560,1,NULL,'Botón de salida AcessPro con símbolo de mano y temporizador regulable (PRO841D)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/20100075446.png','2021-09-08 21:18:22',NULL,NULL,'0','admin','2021-09-09 02:18:22','2021-09-09 02:18:22','20100075446','2021-09-08 21:18:22',1354.00),
(561,0,NULL,'Elevador de pantalla electric lift con capacidad de 100kg (TS1000A)\r\nTALLER',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,NULL,'2021-09-08 23:22:20',NULL,NULL,'0','admin','2021-09-09 04:22:20','2021-09-09 04:22:20','5708499116367','2021-09-08 23:22:20',0.00),
(562,0,NULL,'Sensor de temperatura para liquidos con arduino (DS18B20)\r\nCLOSET',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-09-11 18:27:53',NULL,NULL,'0','admin','2021-09-11 23:27:53','2021-09-11 23:27:53','DUPO90705','2021-09-11 18:27:53',0.00),
(563,5,'USW-LITE-16-POE','Switch UniFi Lite Administrable PoE de 16 Puertos 10/100/1000 Mbps (8 puertos 802.3af/at), 45 W','Ubiquiti','SWITCH','Bodega General',NULL,NULL,'/img/productos/8100100071118.png','2021-09-21 20:33:41',NULL,NULL,'0','admin','2021-09-22 01:33:41','2021-09-22 01:33:41','8100100071118','2021-09-21 20:33:41',1312.00),
(564,24,'K4672M2L','Comando actuador de luces MH','Bticino','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/8005543615119.png','2021-09-29 21:11:41',NULL,NULL,'0','admin','2021-09-30 02:11:41','2021-09-30 02:11:41','8005543615119','2021-09-29 21:11:41',1290.00),
(565,0,NULL,'Distribuidor de fibra optica para montaje en rack, 12 acopladores\r\nBODEGA',NULL,'FIBRA OPTICA','Bodega General',NULL,NULL,NULL,'2021-09-29 23:27:51',NULL,NULL,'0','admin','2021-09-30 04:27:51','2021-09-30 04:27:51','SBE-DFO12','2021-09-29 23:27:51',0.00),
(566,2,NULL,'Convertidor de medios Planet 1000 Mbps UTP/fibra óptica Mono-Modo hasta 10 Km, conector SC (GT-802S)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/847690006017.jpg','2021-10-01 22:41:10',NULL,NULL,'0','admin','2021-10-02 03:41:10','2021-10-02 03:41:10','847690006017','2021-10-01 22:41:10',1359.00),
(567,40,'5523-5EW','Chasis de 3 ventanas para jack de red, Color Blanco','Eaton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/032664629358.png','2021-10-05 16:36:32',NULL,NULL,'0','admin','2021-10-05 21:36:32','2021-10-05 21:36:32','032664629358','2021-10-05 16:36:32',1221.00),
(568,0,NULL,'Bocina larga de pared B&W color negro con reja blanca (CWM7.4S2)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-10-14 21:32:19',NULL,NULL,'0','admin','2021-10-15 02:32:19','2021-10-15 02:32:19','714346332007','2021-10-14 21:32:19',0.00),
(569,1,NULL,'Amplificador crown 1000 de dos canales (NCDI1000) BODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991013720.png','2021-10-14 21:43:32',NULL,NULL,'0','admin','2021-10-15 02:43:32','2021-10-15 02:43:32','691991013720','2021-10-14 21:43:32',1116.00),
(570,0,NULL,'Conector de banana individual metalico (14BSPKP50)\r\nCLOSET',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2021-10-14 21:45:03',NULL,NULL,'0','admin','2021-10-15 02:45:03','2021-10-15 02:45:03','019954968618','2021-10-14 21:45:03',0.00),
(571,3,NULL,'Switch de 6 puertos con 2 PoE, 1 puerto sfp, 90w color negro  HikVision  (DS-3E0510HP-E)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/6941264031611.png','2021-10-21 19:22:48',NULL,NULL,'0','admin','2021-10-22 00:22:48','2021-10-22 00:22:48','6941264031611','2021-10-21 19:22:48',1303.00),
(572,0,NULL,'Router Tp-Link archer C6 color negro (ARCHER-C6)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,NULL,'2021-10-21 19:32:24',NULL,NULL,'0','admin','2021-10-22 00:32:24','2021-10-22 00:32:24','845973084110','2021-10-21 19:32:24',1311.00),
(573,1,NULL,'Control remoto tactil neeo control4 color plata (C4-NEEO-SILVER)',NULL,'CONTROL REMOTO','Bodega General',NULL,NULL,'/img/productos/876389035960.png','2021-10-21 20:53:25',NULL,NULL,'0','admin','2021-10-22 01:53:25','2021-10-22 01:53:25','876389035960','2021-10-21 20:53:25',1279.00),
(574,0,NULL,'Cable panduit cat5e, 305m color azul (PUR550BUY)\r\nBODEGA',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2021-10-22 16:30:13',NULL,NULL,'0','admin','2021-10-22 21:30:13','2021-10-22 21:30:13','074983058327','2021-10-22 16:30:13',0.00),
(575,0,NULL,'Receptor AV Denon de 7.2 canales 8K ultra HD, 145W por canal, compatible con audio 3D a través de Dolby Atmos\\r\\n',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-10-28 17:24:07',NULL,NULL,'0','admin','2021-10-28 22:24:07','2021-10-28 22:24:07','AVR-S960H','2021-10-28 17:24:07',1105.00),
(576,2,NULL,'Procesador de señal de audio Daytonaudio (DSP-408)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/848791004957.png','2021-10-28 18:46:38',NULL,NULL,'0','admin','2021-10-28 23:46:38','2021-10-28 23:46:38','848791004957','2021-10-28 18:46:38',1358.00),
(577,3,NULL,'Lectora de proximidad para interior y exterior rosslare security products (AY-K12C)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/4897027888027.png','2021-10-28 19:17:27',NULL,NULL,'0','admin','2021-10-29 00:17:27','2021-10-29 00:17:27','4897027888027','2021-10-28 19:17:27',1284.00),
(578,1,NULL,'Cubierta de tablero de conexiones negro Panduit (FCE1U)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/74983008735.jpg','2021-10-30 18:30:37',NULL,NULL,'0','admin','2021-10-30 23:30:37','2021-10-30 23:30:37','74983008735','2021-10-30 18:30:37',1110.00),
(579,0,NULL,'Gabinete profesional para telecomunicaciones de 37UR, 600 mm de ancho x 600 mm profundidad linkedpro\r\nSALA JUNTAS',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2021-10-30 18:32:38',NULL,NULL,'0','admin','2021-10-30 23:32:38','2021-10-30 23:32:38','LP606037UR2','2021-10-30 18:32:38',0.00),
(580,1,NULL,'Bocina para plafon Bowers & Wilkins color negro 8 pulgadas (CCM684)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/714346310401.png','2021-11-10 18:42:06',NULL,NULL,'0','admin','2021-11-11 00:42:06','2021-11-11 00:42:06','714346310401','2021-11-10 18:42:06',1147.00),
(581,0,NULL,'Bocina de muro rectangular Bowers & Wilkins empotrable (CWM7.5S2)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-11-10 18:47:26',NULL,NULL,'0','admin','2021-11-11 00:47:26','2021-11-11 00:47:26','2123-0006460','2021-11-10 18:47:26',0.00),
(582,16,'NT-R3R3-NFB-QZ','Tapa de 2 ventanas, nova-t bronce antiguo','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557827935.png','2021-11-12 18:24:31',NULL,NULL,'0','admin','2021-11-13 00:24:31','2021-11-13 00:24:31','027557827935','2021-11-12 18:24:31',1212.00),
(583,2,NULL,'Pedestal triple para botonera pico lutron color blanco (L-PED3-WH)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557765923.png','2021-11-12 18:57:19',NULL,NULL,'0','admin','2021-11-13 00:57:19','2021-11-13 00:57:19','027557765923','2021-11-12 18:57:19',1225.00),
(584,0,'HQWT-T-HW-CWH-A','Termostato con 5 botones, Palladiom cristalizado, color blanco','Lutron','AIRE ACONDICIONADO','Bodega General',NULL,NULL,'/img/productos/0784276227094.png','2021-11-12 18:58:47',NULL,NULL,'0','admin','2021-11-13 00:58:47','2021-11-13 00:58:47','0784276227094','2021-11-12 18:58:47',1271.00),
(585,0,NULL,'Receptor denon av 8k de 7.2 canales, audio 3D denon (para IYARI 10)\r\nTALLER',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-11-16 17:44:14',NULL,NULL,'0','admin','2021-11-16 23:44:14','2021-11-16 23:44:14','AVR-X2700H','2021-11-16 17:44:14',0.00),
(586,12,NULL,'Interruptor de relay cuadrupe riel DIN shelly pro 4pm azul (SHELLYPRO4PM)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235268049.png','2021-11-22 16:55:47',NULL,NULL,'0','admin','2021-11-22 22:55:47','2021-11-22 22:55:47','3800235268049','2021-11-22 16:55:47',1278.00),
(587,1,NULL,'Soportes tipo Z y L para puerta de vidrio metálico, Access Pro (BZL600N)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/20100046953.png','2021-11-26 19:02:35',NULL,NULL,'0','admin','2021-11-27 01:02:35','2021-11-27 01:02:35','20100046953','2021-11-26 19:02:35',1325.00),
(588,1,NULL,'Teclado de 7 botones Elan color blanco (GKP7)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/844039012821.png','2021-12-01 21:25:01',NULL,NULL,'0','admin','2021-12-02 03:25:01','2021-12-02 03:25:01','844039012821','2021-12-01 21:25:01',1358.00),
(589,10,NULL,'Switch electronico lutron RadioRa3 color blanco (RRST-8ANS-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276808613.png','2021-12-08 19:48:40',NULL,NULL,'0','admin','2021-12-09 01:48:40','2021-12-09 01:48:40','784276808613','2021-12-08 19:48:40',1243.00),
(590,1,NULL,'Gabinete de acero precision uso en intemperie (400x400x200mm) con placa trasera interior y compuerta inferior atornillable (PST-4040-20A)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/PST-4040-20A.png','2021-12-08 21:18:53',NULL,NULL,'0','admin','2021-12-09 03:18:53','2021-12-09 03:18:53','PST-4040-20A','2021-12-08 21:18:53',1000.00),
(591,0,NULL,'Subwoofer Bowers & Wilkins negro (CT-SW15)\r\nTALLER',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2021-12-14 18:12:01',NULL,NULL,'0','admin','2021-12-15 00:12:01','2021-12-15 00:12:01','CT-SW15','2021-12-14 18:12:01',0.00),
(592,1,NULL,'Termostato circular blanco chino (BHT-6000)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/H22287W.png','2021-12-21 17:51:24',NULL,NULL,'0','admin','2021-12-21 23:51:24','2021-12-21 23:51:24','H22287W','2021-12-21 17:51:24',1285.00),
(593,1,NULL,'Base de pared para bocinas Sonos Five color negra (S5-WM)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/713541217492.jpg','2021-12-21 17:57:28',NULL,NULL,'0','admin','2021-12-21 23:57:28','2021-12-21 23:57:28','713541217492','2021-12-21 17:57:28',1170.00),
(594,0,NULL,'Extensor de señal control4 v2 negro (C4-IOXV2)\r\nBAÑO',NULL,'DOMOTICA','Bodega General',NULL,NULL,NULL,'2022-01-03 15:56:56',NULL,NULL,'0','admin','2022-01-03 21:56:56','2022-01-03 21:56:56','876389020775','2022-01-03 15:56:56',0.00),
(595,0,NULL,'Receptor denon av 8k de 9.2 canales, audio 3D denon\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-01-05 22:33:10',NULL,NULL,'0','admin','2022-01-06 04:33:10','2022-01-06 04:33:10','AVR-X3700H','2022-01-05 22:33:10',0.00),
(596,0,NULL,'Altoparlante JBL profesional subwoofer negro \r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-01-05 22:36:00',NULL,NULL,'0','admin','2022-01-06 04:36:00','2022-01-06 04:36:00','EON618S','2022-01-05 22:36:00',0.00),
(597,0,NULL,'Pre-amplificador de audio AV marantz\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-01-05 22:43:32',NULL,NULL,'0','admin','2022-01-06 04:43:32','2022-01-06 04:43:32','AV7706','2022-01-05 22:43:32',0.00),
(598,46,NULL,'Panel tipo reja para rack negro metálico 19 pulgadas strong (SR-VENT-2U)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/SR-VENT-2U.png','2022-01-06 21:29:51',NULL,NULL,'0','admin','2022-01-07 03:29:51','2022-01-07 03:29:51','SR-VENT-2U','2022-01-06 21:29:51',NULL),
(599,0,NULL,'Pilar de rack strong personalizado de 32u negro (SR-CUSTOMPILLAR-32U) BODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2022-01-06 21:36:03',NULL,NULL,'0','admin','2022-01-07 03:36:03','2022-01-07 03:36:03','842822033169','2022-01-06 21:36:03',0.00),
(600,10,NULL,'Convertidor de medios Tp-Link SFP gigabit, 1 puerto RJ45 1000MB, 1 puerto SFP, hasta 550M en fibra multimodo y 10km en fibra monomodo negro (MC220L)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/845973030476.png','2022-01-17 20:14:36',NULL,NULL,'0','admin','2022-01-18 02:14:36','2022-01-18 02:14:36','845973030476','2022-01-17 20:14:36',1359.00),
(601,4,NULL,'Dimmer atenuador de multiples ubicaciones lutron maestro LED+ color negro (MACL-153M-BL)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276010696.png','2022-01-25 16:16:40',NULL,NULL,'0','admin','2022-01-25 22:16:40','2022-01-25 22:16:40','784276010696','2022-01-25 16:16:40',1268.00),
(602,24,NULL,'Dimmer compañero lutron satin colors color media noche (MSC-AD-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557802918.png','2022-01-25 17:45:29',NULL,NULL,'0','admin','2022-01-25 23:45:29','2022-01-25 23:45:29','027557802918','2022-01-25 17:45:29',1273.00),
(603,5,NULL,'Control remoto con muelle de carga control4 color negro (C4-SR260RSK)',NULL,'CONTROL REMOTO','Bodega General',NULL,NULL,'/img/productos/876389016266.png','2022-01-28 22:06:19',NULL,NULL,'0','admin','2022-01-29 04:06:19','2022-01-29 04:06:19','876389016266','2022-01-28 22:06:19',1279.00),
(604,17,'UAP-AC-M-PRO','Punto de acceso mesh rectangular, wlan de largo alcanze','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'https://inventario.savicontrolhome.com/img/productos/810354024726.png','2022-02-11 20:01:16',NULL,'','0','admin','2022-02-12 02:01:16','2022-02-12 02:01:16','810354024726','2022-02-11 20:01:16',NULL),
(605,0,NULL,'Bocina para intemperie KEF color blanco (VENTURA5) BODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-02-11 22:29:41',NULL,NULL,'0','admin','2022-02-12 04:29:41','2022-02-12 04:29:41','637203028823','2022-02-11 22:29:41',0.00),
(606,0,NULL,'Contacto duplex USB de 15A con proteccion para niños lutron satin colors color paladio (SCR-15-UBTR-PD)',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2022-02-16 17:44:11',NULL,NULL,'0','admin','2022-02-16 23:44:11','2022-02-16 23:44:11','784276166317','2022-02-16 17:44:11',1263.00),
(607,5,NULL,'Contacto duplex USB de 15A con proteccion para niños lutron satin colors color piedra moka (SCR-15-UBTR-MS)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276166300.png','2022-02-16 17:45:47',NULL,NULL,'0','admin','2022-02-16 23:45:47','2022-02-16 23:45:47','784276166300','2022-02-16 17:45:47',1263.00),
(608,3,'SC-2-PD','Tapa de 2 ventanas, color paladio','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557266307.png','2022-02-16 17:47:32',NULL,NULL,'0','admin','2022-02-16 23:47:32','2022-02-16 23:47:32','027557266307','2022-02-16 17:47:32',1218.00),
(609,15,NULL,'Marco de 6 puertos lutron satin colors color paladio (SC-6PF-PD)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557266284.png','2022-02-16 17:49:13',NULL,NULL,'0','admin','2022-02-16 23:49:13','2022-02-16 23:49:13','027557266284','2022-02-16 17:49:13',1230.00),
(610,11,'SC-6PF-MS','Marco de 6 puertos, satin colors, color piedra moka','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557264631.png','2022-02-16 17:52:55',NULL,NULL,'0','admin','2022-02-16 23:52:55','2022-02-16 23:52:55','027557264631','2022-02-16 17:52:55',1230.00),
(611,14,NULL,'Switch hdmi de 3 puertos liberty av solutions negro (DL-A31-H2)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/601446000946.jpg','2022-02-22 22:43:37',NULL,NULL,'0','admin','2022-02-23 04:43:37','2022-02-23 04:43:37','601446000946','2022-02-22 22:43:37',1299.00),
(612,0,NULL,'Perillas terminales de conexion sonos amp (refaccion)\r\nCLOSET',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2022-02-25 19:24:21',NULL,NULL,'0','admin','2022-02-26 01:24:21','2022-02-26 01:24:21','PE-SO-AMP','2022-02-25 19:24:21',0.00),
(613,0,NULL,'Bocina para pared sonance circular (C363DT) BODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-02-25 22:22:17',NULL,NULL,'0','admin','2022-02-26 04:22:17','2022-02-26 04:22:17','848592000059','2022-02-25 22:22:17',0.00),
(614,3,NULL,'Bocina de exterior en forma de piedra color arena Klipsch (AWR-650-SM)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/743878019575.png','2022-02-25 22:24:21',NULL,NULL,'0','admin','2022-02-26 04:24:21','2022-02-26 04:24:21','743878019575','2022-02-25 22:24:21',NULL),
(615,44,NULL,'Transmisor de contacto magnetico de puerta/ventana inalámbrico Ademco (5816)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/781410001497.png','2022-03-01 23:47:17',NULL,NULL,'0','admin','2022-03-02 05:47:17','2022-03-02 05:47:17','781410001497','2022-03-01 23:47:17',1340.00),
(616,2,NULL,'Modulo de 4 relevadores de 30VCD para funciones de automatización Hikvision (DS-PM-RSO4)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/6954273636302.png','2022-03-04 20:23:51',NULL,NULL,'0','admin','2022-03-05 02:23:51','2022-03-05 02:23:51','6954273636302','2022-03-04 20:23:51',1368.00),
(617,1,NULL,'Soporte bracket tipo U hikvision metálico para cerradura (DS-K4H258-LZ)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/6954273636036.png','2022-03-04 20:28:31',NULL,NULL,'0','admin','2022-03-05 02:28:31','2022-03-05 02:28:31','6954273636036','2022-03-04 20:28:31',1325.00),
(618,9,NULL,'Contacto duplex sencillo de 15A lutron satin colors color paladio (SCR-15-PD-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557222952.png','2022-03-07 21:24:22',NULL,NULL,'0','admin','2022-03-08 03:24:22','2022-03-08 03:24:22','027557222952','2022-03-07 21:24:22',1267.00),
(619,5,NULL,'Contacto duplex falla tierra de 15A GFCI con proteccion para niños lutron satin colors color paladio (SCR-15-GFST-PD-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276159425.png','2022-03-07 21:29:32',NULL,NULL,'0','admin','2022-03-08 03:29:32','2022-03-08 03:29:32','784276159425','2022-03-07 21:29:32',1263.00),
(620,1,NULL,'Contacto duplex sencillo de 15A lutron satin colors color piedra moka (SCR-15-MS-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557222945.png','2022-03-07 21:35:43',NULL,NULL,'0','admin','2022-03-08 03:35:43','2022-03-08 03:35:43','027557222945','2022-03-07 21:35:43',1264.00),
(621,19,NULL,'Frente de dimmer lutron color paladio (RK-D-PD)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557295680.png','2022-03-07 21:38:12',NULL,NULL,'0','admin','2022-03-08 03:38:12','2022-03-08 03:38:12','027557295680','2022-03-07 21:38:12',1214.00),
(622,1,NULL,'Bocina wifi Sonos One SL color negro (ONESLUS1BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/878269008484.png','2022-03-11 20:24:27',NULL,NULL,'0','admin','2022-03-12 02:24:27','2022-03-12 02:24:27','878269008484','2022-03-11 20:24:27',1189.00),
(623,0,NULL,'Base flexson de piso para bocinas sonos color blanco (S1-SF-WH)\r\nBODEGA',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,NULL,'2022-03-11 20:25:28',NULL,NULL,'0','admin','2022-03-12 02:25:28','2022-03-12 02:25:28','S1-SF-WH','2022-03-11 20:25:28',0.00),
(624,0,NULL,'Bocina sonos five color negra (FIVE1US1BLK) AREA SISTEMAS',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-03-11 20:26:25',NULL,NULL,'0','admin','2022-03-12 02:26:25','2022-03-12 02:26:25','FIVE1US1BLK','2022-03-11 20:26:25',0.00),
(625,1,NULL,'Base para Sonos Beam color blanco (BM1WMWW1BWH)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/878269004462.jpg','2022-03-11 20:28:12',NULL,NULL,'0','admin','2022-03-12 02:28:12','2022-03-12 02:28:12','878269004462','2022-03-11 20:28:12',1188.00),
(626,3,NULL,'Base de carga para Sonos Move color blanca Genérica (MVCHBUS1WH)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/MVCHBUS1WH.jpg','2022-03-11 20:29:48',NULL,NULL,'0','admin','2022-03-12 02:29:48','2022-03-12 02:29:48','MVCHBUS1WH','2022-03-11 20:29:48',1190.00),
(627,3,NULL,'Router Omada de 4 puertos ethernet alambrico Tp-Link (ER605)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/845973089597.jpg','2022-03-12 16:02:19',NULL,NULL,'0','admin','2022-03-12 22:02:19','2022-03-12 22:02:19','845973089597','2022-03-12 16:02:19',1304.00),
(628,0,NULL,'Bocina de riel inalambrica ecler tube tipo bala blanca (RAILM3)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-03-12 16:14:25',NULL,NULL,'0','admin','2022-03-12 22:14:25','2022-03-12 22:14:25','8435071312387','2022-03-12 16:14:25',0.00),
(629,0,NULL,'Transmisor y unidad master ecler core (WISPEAK)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-03-12 16:15:49',NULL,NULL,'0','admin','2022-03-12 22:15:49','2022-03-12 22:15:49','8435071312370','2022-03-12 16:15:49',0.00),
(630,0,NULL,'Adaptador/base de montaje en superficie para riel ecler tube sma (TUBESMA)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-03-12 16:19:09',NULL,NULL,'0','admin','2022-03-12 22:19:09','2022-03-12 22:19:09','8435071312455','2022-03-12 16:19:09',0.00),
(631,1,NULL,'Subwoofer Revel para climas extremos grande (L12XC)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/L12XC.png','2022-03-12 17:53:12',NULL,NULL,'0','admin','2022-03-12 23:53:12','2022-03-12 23:53:12','L12XC','2022-03-12 17:53:12',1150.00),
(632,3,NULL,'Controlador de Hardware de 2 puertos Omada Tp-Link negro (OC300)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/845973089863.png','2022-03-12 17:56:56',NULL,NULL,'0','admin','2022-03-12 23:56:56','2022-03-12 23:56:56','845973089863','2022-03-12 17:56:56',1303.00),
(633,3,NULL,'Divisor/splitter de video HDMI con 4 puertos, Epcom Titanuim color negro (TT314HDRV2.0)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6955361271894.png','2022-03-14 22:19:45',NULL,NULL,'0','admin','2022-03-15 04:19:45','2022-03-15 04:19:45','6955361271894','2022-03-14 22:19:45',1304.00),
(634,4,'DS-KV8113-WME1(B)','Videoportero IP, wifi, PoE con llamada y apertura desde app hik-connect metalico','Hikvision','INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6941264069225.jpg','2022-03-15 17:49:29',NULL,NULL,'0','admin','2022-03-15 23:49:29','2022-03-15 23:49:29','6941264069225','2022-03-15 17:49:29',1367.00),
(635,2,'EAP660HD','Punto de Acceso de Disco Wifi 6,','Tp-Link Omada','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/845973089719.jpg','2022-03-18 22:23:54',NULL,NULL,'0','admin','2022-03-19 04:23:54','2022-03-19 04:23:54','845973089719','2022-03-18 22:23:54',1309.00),
(636,10,NULL,'Dimmer lutron homeworks qs pro color biscuit (HQRD-PRO-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276270502.png','2022-03-26 16:01:00',NULL,NULL,'0','admin','2022-03-26 22:01:00','2022-03-26 22:01:00','784276270502','2022-03-26 16:01:00',1271.00),
(637,1,NULL,'Subwoofer Yamaha 3,5 pulgadas color blanco (VXS3SW)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/889025111874.png','2022-03-28 17:14:13',NULL,NULL,'0','admin','2022-03-28 23:14:13','2022-03-28 23:14:13','889025111874','2022-03-28 17:14:13',1116.00),
(638,4,NULL,'Altavoz de montaje en superficie Yamaha 1.5 pulgadas cuadrada negro (VXS1MLW)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/889025111867.png','2022-03-28 17:17:19',NULL,NULL,'0','admin','2022-03-28 23:17:19','2022-03-28 23:17:19','889025111867','2022-03-28 17:17:19',1126.00),
(639,2,NULL,'Sistema en casa opolk negro completo (AM1655) (TL1600)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/AM1655.png','2022-03-28 17:23:47',NULL,NULL,'0','admin','2022-03-28 23:23:47','2022-03-28 23:23:47','AM1655','2022-03-28 17:23:47',1020.00),
(640,0,NULL,'Subwoofer alta frecuencia JBL negro 15\" (AC115S)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-03-28 17:27:31',NULL,NULL,'0','admin','2022-03-28 23:27:31','2022-03-28 23:27:31','AC115S','2022-03-28 17:27:31',0.00),
(641,0,NULL,'Placa living now 1 modulo - blanco - con 1 linea bticino (KW01) \r\nCLOSET',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2022-03-28 17:46:37',NULL,NULL,'0','admin','2022-03-28 23:46:37','2022-03-28 23:46:37','8005543613283','2022-03-28 17:46:37',0.00),
(642,0,NULL,'Tapon ciego bticino negro (K4950)\r\nCLOSET',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2022-03-28 17:47:43',NULL,NULL,'0','admin','2022-03-28 23:47:43','2022-03-28 23:47:43','K4950','2022-03-28 17:47:43',0.00),
(643,1,NULL,'Adaptador de teléfono analógico Grandstream (ATA) (HT802)',NULL,'TELEFONIA','Bodega General',NULL,NULL,'/img/productos/6947273702047.png','2022-03-30 17:59:54',NULL,NULL,'0','admin','2022-03-30 23:59:54','2022-03-30 23:59:54','6947273702047','2022-03-30 17:59:54',1330.00),
(644,4,NULL,'Adaptador de teléfono analógico Grandstream (ATA) (HT813)',NULL,'TELEFONIA','Bodega General',NULL,NULL,'/img/productos/6947273702634.png','2022-03-30 18:00:52',NULL,NULL,'0','admin','2022-03-31 00:00:52','2022-03-31 00:00:52','6947273702634','2022-03-30 18:00:52',NULL),
(645,19,NULL,'Repetidor inalambrico lutron radiora negro (L-REPPRO-BL)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/784276240239.png','2022-03-30 18:22:22',NULL,NULL,'0','admin','2022-03-31 00:22:22','2022-03-31 00:22:22','784276240239','2022-03-30 18:22:22',1257.00),
(646,3,NULL,'Bridge repetidor lutron radiora2 negro (RR-SEL-REP2-BL)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/784276240222.png','2022-03-30 18:23:46',NULL,NULL,'0','admin','2022-03-31 00:23:46','2022-03-31 00:23:46','784276240222','2022-03-30 18:23:46',1257.00),
(647,0,NULL,'Amplificador crown de 4 canales negro CDI (CRNCDI4X3BLVMUS)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-03-30 22:32:44',NULL,NULL,'0','admin','2022-03-31 04:32:44','2022-03-31 04:32:44','CRNCDI4X3BLVMUS','2022-03-30 22:32:44',0.00),
(648,1,NULL,'Procesador de zona digital DBX zonepro 641 (DBX-641VVM)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991400735.png','2022-03-30 22:36:15',NULL,NULL,'0','admin','2022-03-31 04:36:15','2022-03-31 04:36:15','691991400735','2022-03-30 22:36:15',1120.00),
(649,0,NULL,'Bocina para exterior JBL color blanco (CONTROL25-1-WH) BODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-03-30 22:38:00',NULL,NULL,'0','admin','2022-03-31 04:38:00','2022-03-31 04:38:00','CONTROL25-1-WH','2022-03-30 22:38:00',0.00),
(650,0,NULL,'Sistema de audio para hogar harman 300V (A100P)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-03-30 22:40:27',NULL,NULL,'0','admin','2022-03-31 04:40:27','2022-03-31 04:40:27','A100P','2022-03-30 22:40:27',0.00),
(651,0,NULL,'Subwoofer JBL 35Hz 70V/100V color blanco (CONTROL50S/T-WH)\r\nBODEGA ENTRADA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-03-31 21:39:09',NULL,NULL,'0','admin','2022-04-01 03:39:09','2022-04-01 03:39:09','CONTROL50S/T-WH','2022-03-31 21:39:09',0.00),
(652,4,NULL,'Bocina tipo satélite para teatro en casa Bower & Wilkins color negro mate (M-1-BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/714346314409.png','2022-03-31 21:42:39',NULL,NULL,'0','admin','2022-04-01 03:42:39','2022-04-01 03:42:39','714346314409','2022-03-31 21:42:39',1146.00),
(653,2,NULL,'Pareja de soportes de suelo para bocinas Bower & Wilkins color negro (FS-700S2)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/714346329304.jpg','2022-03-31 21:44:36',NULL,NULL,'0','admin','2022-04-01 03:44:36','2022-04-01 03:44:36','714346329304','2022-03-31 21:44:36',1142.00),
(654,2,NULL,'Paquete bocina para exterior Bowers & Wilkins marine 6 color negro (MARINE6)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/714346324828.png','2022-03-31 21:46:37',NULL,NULL,'0','admin','2022-04-01 03:46:37','2022-04-01 03:46:37','714346324828','2022-03-31 21:46:37',1161.00),
(655,0,NULL,'Paquete de 4 bocinas de estaca sonance para sistema garden (93436)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-04-02 18:05:47',NULL,NULL,'0','admin','2022-04-03 00:05:47','2022-04-03 00:05:47','SGS-SAT-93436','2022-04-02 18:05:47',0.00),
(656,2,NULL,'Cámara domo Hilook de 4MP color blanco lente 2.8 mm (IPC-T240H)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6941264092483.jpg','2022-04-02 18:27:25',NULL,NULL,'0','admin','2022-04-03 00:27:25','2022-04-03 00:27:25','6941264092483','2022-04-02 18:27:25',1346.00),
(657,2,NULL,'Gabinete mediano Epcom para sirenas color gris (IMP30V3)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/IMP30V3.png','2022-04-06 21:45:00',NULL,NULL,'0','admin','2022-04-07 02:45:00','2022-04-07 02:45:00','IMP30V3','2022-04-06 21:45:00',1323.00),
(658,4,'TL-SG3428','Switch de red de 24 puertos con 4 SFP',' Tp-Link Omada ','SWITCH','Bodega General',NULL,NULL,'/img/productos/845973010140.jpg','2022-04-07 21:20:14',NULL,NULL,'0','admin','2022-04-08 02:20:14','2022-04-08 02:20:14','845973010140','2022-04-07 21:20:14',1303.00),
(659,0,NULL,'Altavoz de piso tipo torre Bowers & Wilkins B&W (702S2)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-04-07 21:32:00',NULL,NULL,'0','admin','2022-04-08 02:32:00','2022-04-08 02:32:00','702S2','2022-04-07 21:32:00',0.00),
(660,0,NULL,'Teclado de alarma Honeywell blanco (6152RF)',NULL,'ALARMA','Bodega General',NULL,NULL,NULL,'2022-04-19 21:46:49',NULL,NULL,'0','admin','2022-04-20 02:46:49','2022-04-20 02:46:49','886618166808','2022-04-19 21:46:49',1366.00),
(661,11,NULL,'Actuador bticino con 1 relevador (3476)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3476.png','2022-04-19 21:54:27',NULL,NULL,'0','admin','2022-04-20 02:54:27','2022-04-20 02:54:27','3476','2022-04-19 21:54:27',1295.00),
(662,4,NULL,'Interruptor unipolar de uso general lutron satin colors color paladio (SC-1PS-PD-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557584609.png','2022-04-19 21:58:52',NULL,NULL,'0','admin','2022-04-20 02:58:52','2022-04-20 02:58:52','027557584609','2022-04-19 21:58:52',1242.00),
(663,0,NULL,'Soporte universal para barras de sonido Sanus color naranja (SSASB2-B8)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/SSASB2-B8.jpg','2022-04-22 19:48:45',NULL,NULL,'0','admin','2022-04-23 00:48:45','2022-04-23 00:48:45','SSASB2-B8','2022-04-22 19:48:45',1181.00),
(664,0,NULL,'Bocina wifi sonos move color negro (MOVE1US1BLK) BODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-04-23 17:09:09',NULL,NULL,'0','admin','2022-04-23 22:09:09','2022-04-23 22:09:09','MOVE1US1BLK','2022-04-23 17:09:09',0.00),
(665,0,'NT-NFB-R3-SB','Tapa de 1 ventana, nova-t laton satinado','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,NULL,'2022-04-28 20:34:58',NULL,NULL,'0','admin','2022-04-29 01:34:58','2022-04-29 01:34:58','NT-R3-NFB-SB','2022-04-28 20:34:58',1212.00),
(666,0,NULL,'Dimmer lutron radiora2 color almendra claro (RRD-6CL-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276055437.png','2022-05-03 22:07:51',NULL,NULL,'0','admin','2022-05-04 03:07:51','2022-05-04 03:07:51','784276055437','2022-05-03 22:07:51',1267.00),
(667,8,NULL,'Soporte de pared para bocinas Bose modelo MA12/MA12EX color negro (WMB2-BLK)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/017817505291.jpg','2022-05-03 22:34:12',NULL,NULL,'0','admin','2022-05-04 03:34:12','2022-05-04 03:34:12','017817505291','2022-05-03 22:34:12',1051.00),
(668,0,NULL,'Pilar de rack strong personalizado de 37u negro (SR-CUSTOMPILLAR-37U) BODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2022-05-11 20:21:01',NULL,NULL,'0','admin','2022-05-12 01:21:01','2022-05-12 01:21:01','SR-CUSTOMPILLAR-37U','2022-05-11 20:21:01',0.00),
(669,0,NULL,'Gabinete de plastico txpro mediano (TXG0146)\r\nBODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2022-05-24 20:22:46',NULL,NULL,'0','admin','2022-05-25 01:22:46','2022-05-25 01:22:46','TXG0146','2022-05-24 20:22:46',0.00),
(670,0,NULL,'Gabinete de plastico txpro chico (TXG0130)\r\nBODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2022-05-24 20:23:04',NULL,NULL,'0','admin','2022-05-25 01:23:04','2022-05-25 01:23:04','TXG0130','2022-05-24 20:23:04',0.00),
(671,1,NULL,'Transformador de pared Epcom Powerline negro 16VCA (RT1640LS)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/697477291023.png','2022-05-24 20:25:02',NULL,NULL,'0','admin','2022-05-25 01:25:02','2022-05-25 01:25:02','697477291023','2022-05-24 20:25:02',1354.00),
(672,6,NULL,'Interruptor de uso general, 3 vias 15A, lutron satin colors color media noche (SC-3PS-MN-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485586.png','2022-05-24 20:45:01',NULL,NULL,'0','admin','2022-05-25 01:45:01','2022-05-25 01:45:01','027557485586','2022-05-24 20:45:01',1242.00),
(673,0,NULL,'Altavoz de canal central tweeter Bower & Wilkins B&W negro (HTM71-S2)\\r\\nBODEGA ENTRADA',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/HTM71-S2.png','2022-06-15 20:05:03',NULL,NULL,'0','admin','2022-06-16 01:05:03','2022-06-16 01:05:03','HTM71-S2','2022-06-15 20:05:03',NULL),
(674,0,NULL,'Subwoofer negro Bower & Wilkins 1000W (DB2D)\r\nBODEGA ENTRADA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-06-15 20:10:51',NULL,NULL,'0','admin','2022-06-16 01:10:51','2022-06-16 01:10:51','DB2D','2022-06-15 20:10:51',0.00),
(675,10,'RG-RAP6260','Punto de acceso Wi-Fi 6 para exterior 360°  IP68 hasta 1775Mbps doble banda',' Ruijie','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/6971693272187.png','2022-06-29 21:02:11',NULL,NULL,'0','admin','2022-06-30 02:02:11','2022-06-30 02:02:11','6971693272187','2022-06-29 21:02:11',NULL),
(676,8,'RG-RAP2260-G','Punto de acceso Wi-Fi 6 para interior en techo hasta 3.2 Gbps doble banda','Ruijie','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/6971693271838.png','2022-06-29 21:09:46',NULL,NULL,'0','admin','2022-06-30 02:09:46','2022-06-30 02:09:46','6971693271838','2022-06-29 21:09:46',1299.00),
(677,0,'RG-AP180','Punto de Acceso de muro,   Wi-Fi 6','Ruijie','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/6971693273863.png','2022-06-29 21:13:11',NULL,NULL,'0','admin','2022-06-30 02:13:11','2022-06-30 02:13:11','6971693273863','2022-06-29 21:13:11',1299.00),
(678,1,NULL,'Cuadro de luz led myth realm (LPT00105D1520D)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/X002K6L1SD.png','2022-06-29 21:29:10',NULL,NULL,'0','admin','2022-06-30 02:29:10','2022-06-30 02:29:10','X002K6L1SD','2022-06-29 21:29:10',1276.00),
(679,0,NULL,'Procesador de zona digital dbx driverackPA2\r\nARRIBA CLOSET',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-07-01 16:16:36',NULL,NULL,'0','admin','2022-07-01 21:16:36','2022-07-01 21:16:36','DRIVERACKPA2','2022-07-01 16:16:36',0.00),
(680,33,'SC-3-BI','Tapa de 3 ventanas, Color Biscuit','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475181.png','2022-07-08 16:46:39',NULL,NULL,'0','admin','2022-07-08 21:46:39','2022-07-08 21:46:39','027557475181','2022-07-08 16:46:39',1223.00),
(681,1,NULL,'Control de casa 4 switch panel todo en uno brillant color blanco (BHA120US-WH4)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/855591007040.jpg','2022-07-13 18:27:29',NULL,NULL,'0','admin','2022-07-13 23:27:29','2022-07-13 23:27:29','855591007040','2022-07-13 18:27:29',1274.00),
(682,6,NULL,'Controlador de 4 salidas shelly plus naranja shellyi4 (SHELLYPLUSI4)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235265079.jpg','2022-07-13 18:33:04',NULL,NULL,'0','admin','2022-07-13 23:33:04','2022-07-13 23:33:04','3800235265079','2022-07-13 18:33:04',1278.00),
(683,4,NULL,'Blaster controlador IR marca rusa look.in (MAPYCR)',NULL,'CONTROL REMOTO','Bodega General',NULL,NULL,'/img/productos/LOOK-IN-BLASTER.png','2022-07-13 18:35:43',NULL,NULL,'0','admin','2022-07-13 23:35:43','2022-07-13 23:35:43','LOOK.IN-BLASTER','2022-07-13 18:35:43',1284.00),
(684,4,NULL,'Barra de Sonido Sonos Ray Negro (RAYG1US1BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136802839.png','2022-07-19 21:32:07',NULL,NULL,'0','admin','2022-07-20 02:32:07','2022-07-20 02:32:07','840136802839','2022-07-19 21:32:07',1180.00),
(685,0,NULL,'Barra de sonido esencial sonos ray color negra (RAY1US1BLK) \r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-07-19 21:32:19',NULL,NULL,'0','admin','2022-07-20 02:32:19','2022-07-20 02:32:19','RAY1US1BLK','2022-07-19 21:32:19',0.00),
(686,2,'COOLMASTER','Sistema de plug & play universal para climas VRF','CoolAutomation','AIRE ACONDICIONADO','Bodega General',NULL,NULL,'/img/productos/795044493627.png','2022-07-21 23:13:29',NULL,NULL,'0','admin','2022-07-22 04:13:29','2022-07-22 04:13:29','795044493627','2022-07-21 23:13:29',1354.00),
(687,28,NULL,'Caja de conexiones metalica 1 modulo lutron (EBB-1-SQ)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/EBB-1-SQ.png','2022-07-21 23:15:44',NULL,NULL,'0','admin','2022-07-22 04:15:44','2022-07-22 04:15:44','EBB-1-SQ','2022-07-21 23:15:44',NULL),
(688,3,'DS-KV6113-WPE1 (C)','Frente de calle o videoportero Hikvision con cámara para exteriores','Hikvision','INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6931847170455.jpg','2022-07-21 23:19:10',NULL,NULL,'0','admin','2022-07-22 04:19:10','2022-07-22 04:19:10','6931847170455','2022-07-21 23:19:10',1367.00),
(689,0,NULL,'NVR grabadora de video digital Hilook de 8 canales  (NVR-108H-D/8P)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6941264092292.png','2022-07-21 23:21:27',NULL,NULL,'0','admin','2022-07-22 04:21:27','2022-07-22 04:21:27','6941264092292','2022-07-21 23:21:27',1339.00),
(690,2,NULL,'Bocina tipo satélite de 2 vías para teatro en casa Bower & Wilkins color blanco mate (AM1-WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/714346314416.png','2022-07-26 20:33:39',NULL,NULL,'0','admin','2022-07-27 01:33:39','2022-07-27 01:33:39','714346314416','2022-07-26 20:33:39',1145.00),
(691,4,NULL,'Dimmer atenuador lutron maestro LED+ color blanco (STCL-153M-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2022-08-04 22:59:33',NULL,NULL,'0','admin','2022-08-05 03:59:33','2022-08-05 03:59:33','784276810920','2022-08-04 22:59:33',1268.00),
(692,27,'MSC-AS-BI','Boton timbre switch compañero, Satin Colors, color biscuit','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557062466.jpg','2022-08-16 23:24:35',NULL,NULL,'0','admin','2022-08-17 04:24:35','2022-08-17 04:24:35','27557062466','2022-08-16 23:24:35',1268.00),
(693,0,NULL,'Columna de matriz JBL bocina color blanco (CBT70J-1-WH)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-08-29 16:51:24',NULL,NULL,'0','admin','2022-08-29 21:51:24','2022-08-29 21:51:24','CBT70J-1-WH','2022-08-29 16:51:24',0.00),
(694,0,NULL,'Amplificador multizona russound (MCA-88)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-08-31 21:56:44',NULL,NULL,'0','admin','2022-09-01 02:56:44','2022-09-01 02:56:44','MCA-88','2022-08-31 21:56:44',0.00),
(695,1,NULL,'Bocina tipo hongo para exterior sonance color cafe (OMNI-6T)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/OMNI-6T.png','2022-08-31 21:57:39',NULL,NULL,'0','admin','2022-09-01 02:57:39','2022-09-01 02:57:39','OMNI-6T','2022-08-31 21:57:39',1058.00),
(696,1,'6D9125SMOS2P','Rollo de fibra optica Monomodo de 6 hilos 304M','Cleerline SSF','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/602573831359.jpg','2022-08-31 21:58:47',NULL,NULL,'0','admin','2022-09-01 02:58:47','2022-09-01 02:58:47','602573831359','2022-08-31 21:58:47',1036.00),
(697,0,NULL,'Botonera splitter russound (SA-ZX3)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-08-31 22:00:21',NULL,NULL,'0','admin','2022-09-01 03:00:21','2022-09-01 03:00:21','SA-ZX3','2022-08-31 22:00:21',0.00),
(698,0,NULL,'Botonera russound para audio (SLK)\r\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2022-08-31 22:01:04',NULL,NULL,'0','admin','2022-09-01 03:01:04','2022-09-01 03:01:04','SLK','2022-08-31 22:01:04',0.00),
(699,1,NULL,'Cámara tipo domo Hikvision blanca 2.88mm acusense (DS-2CD3356G2-ISU/SL)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6941264089834.jpg','2022-09-01 22:58:40',NULL,NULL,'0','admin','2022-09-02 03:58:40','2022-09-02 03:58:40','6941264089834','2022-09-01 22:58:40',1346.00),
(700,2,NULL,'Telefono IP empresarial para 2 lineas SIP con pantalla LCD, conferencia de 3 vías PoE Fanvil (X3SP)',NULL,'TELEFONIA','Bodega General',NULL,NULL,'/img/productos/X3SP.png','2022-09-07 18:06:28',NULL,NULL,'0','admin','2022-09-07 23:06:28','2022-09-07 23:06:28','X3SP','2022-09-07 18:06:28',1328.00),
(701,0,NULL,'Organizador de cable vertical linkedpro 24U (LPCV24URL)\r\nBODEGA ABAJO',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2022-09-07 18:10:06',NULL,NULL,'0','admin','2022-09-07 23:10:06','2022-09-07 23:10:06','LPCV24URL','2022-09-07 18:10:06',0.00),
(702,1,NULL,'Organizador de cable vertical linkedpro 21U (LPCV21URM)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/LPCV21URM.png','2022-09-07 18:10:52',NULL,NULL,'0','admin','2022-09-07 23:10:52','2022-09-07 23:10:52','LPCV21URM','2022-09-07 18:10:52',1057.00),
(703,0,NULL,'Gabinete metalico para montaje en pared con puerta de cristal templado 6U linkedpro epcom (SR1906GFP)\r\nBODEGA ABAJO',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2022-09-07 18:15:49',NULL,NULL,'0','admin','2022-09-07 23:15:49','2022-09-07 23:15:49','SR1906GFP','2022-09-07 18:15:49',0.00),
(704,0,NULL,'Organizador de cable horizontal 19\" 2U linkedpro epcom (LPCM042U)\r\nBODEGA ABAJO',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2022-09-07 18:24:24',NULL,NULL,'0','admin','2022-09-07 23:24:24','2022-09-07 23:24:24','LPCM042U','2022-09-07 18:24:24',0.00),
(705,0,'SYSB11C','Boton de salida sin contacto e ilustracion de mano','Accesspro','ALARMA','Bodega General',NULL,NULL,'/img/productos/SYSB11C.png','2022-09-07 20:49:03',NULL,NULL,'0','admin','2022-09-08 01:49:03','2022-09-08 01:49:03','SYSB11C','2022-09-07 20:49:03',1285.00),
(706,2,NULL,'Receptor de bluetooth ugreen negro (30445)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/6957303834457.png','2022-09-07 20:50:31',NULL,NULL,'0','admin','2022-09-08 01:50:31','2022-09-08 01:50:31','6957303834457','2022-09-07 20:50:31',1358.00),
(707,1,NULL,'Cámara bala Hikvision blanca acusense 2MP para exterior con micrófono integrado (DS-2CD2023G2-IU)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6941264070795.jpg','2022-09-07 20:53:25',NULL,NULL,'0','admin','2022-09-08 01:53:25','2022-09-08 01:53:25','6941264070795','2022-09-07 20:53:25',1341.00),
(708,0,'10512','Cable de audio de 3.5mm macho a 2 RCA Macho, 3m color gris\\r\\nCLOSET TRASERO','Ugreen','CABLES (Accesorios)','Bodega General',NULL,NULL,NULL,'2022-09-15 17:07:12',NULL,NULL,'0','admin','2022-09-15 22:07:12','2022-09-15 22:07:12','10512','2022-09-15 17:07:12',NULL),
(709,22,'20712','Cable para microfono XLR tipo canon macho a hembra 5m CLOSET TRASERO','Ugreen','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/6957303827121.png','2022-09-15 17:09:43',NULL,NULL,'0','admin','2022-09-15 22:09:43','2022-09-15 22:09:43','6957303827121','2022-09-15 17:09:43',1331.00),
(710,9,NULL,'Repetidor de señal Hikvision, LED Indicador, batería de respaldo blanco (DS-PR1-WB)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6941264077992.png','2022-09-15 17:15:39',NULL,NULL,'0','admin','2022-09-15 22:15:39','2022-09-15 22:15:39','6941264077992','2022-09-15 17:15:39',1330.00),
(711,7,NULL,'Patch Panel de impacto 110, Blindado STP, 12 puertos, Cat6, LinkedPro  (LPPP629)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/LPPP629.png','2022-09-15 17:28:19',NULL,NULL,'0','admin','2022-09-15 22:28:19','2022-09-15 22:28:19','LPPP629','2022-09-15 17:28:19',1330.00),
(712,1,NULL,'Cerradura inteligente con bluetooth y lector de huella Zkteco (TL400B)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/TL400BL.png','2022-09-23 17:34:54',NULL,NULL,'0','admin','2022-09-23 22:34:54','2022-09-23 22:34:54','TL400BL','2022-09-23 17:34:54',1325.00),
(713,0,NULL,'Gabinete de acero precision uso en intemperie (500 x 700 x 250 mm) con placa trasera interior y compuerta inferior atornillable BODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,NULL,'2022-10-01 16:31:42',NULL,NULL,'0','admin','2022-10-01 21:31:42','2022-10-01 21:31:42','PST-5070-25A','2022-10-01 16:31:42',0.00),
(714,0,NULL,'Extensor IP por cable coaxial epcom titanium negro (TTIPCX)\\r\\nESANTE: H-1',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/TTIPCX.png','2022-10-01 16:35:00',NULL,NULL,'0','admin','2022-10-01 21:35:00','2022-10-01 21:35:00','TTIPCX','2022-10-01 16:35:00',NULL),
(715,3,NULL,'Subwoofer 15 pulgadas JBL negro de piso (ASB6115)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/ASB6115.png','2022-10-07 18:13:04',NULL,NULL,'0','admin','2022-10-07 23:13:04','2022-10-07 23:13:04','ASB6115','2022-10-07 18:13:04',1110.00),
(716,1,NULL,'Frente de calle IP 2MP videportero modular angulo 180Â° (DS-KD8003-IME1)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6954273689681.jpg','2022-10-13 22:12:18',NULL,NULL,'0','admin','2022-10-14 03:12:18','2022-10-14 03:12:18','6954273689681','2022-10-13 22:12:18',1367.00),
(717,1,NULL,'Modulo con lector de tarjetas para frente de calle Hikvision (DS-KD-E)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6954273693565.jpg','2022-10-13 22:14:06',NULL,NULL,'0','admin','2022-10-14 03:14:06','2022-10-14 03:14:06','6954273693565','2022-10-13 22:14:06',1367.00),
(718,1,NULL,'Modulo con teclado para videoportero Hikvision (DS-KD-KP)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6954273691509.jpg','2022-10-13 22:17:16',NULL,NULL,'0','admin','2022-10-14 03:17:16','2022-10-14 03:17:16','6954273691509','2022-10-13 22:17:16',1367.00),
(719,4,'DS-KH6320-WTE1','Monitor IP wifi Hikvision a color para frente de calle','Hikvision','INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6941264014126.jpg','2022-10-13 22:25:45',NULL,NULL,'0','admin','2022-10-14 03:25:45','2022-10-14 03:25:45','6941264014126','2022-10-13 22:25:45',1367.00),
(720,1,NULL,'Base de 2 módulos para frente de calle Hikvision de plástico (DS-KD-ACF2/PLASTIC)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6954273691561.jpg','2022-10-13 22:26:46',NULL,NULL,'0','admin','2022-10-14 03:26:46','2022-10-14 03:26:46','6954273691561','2022-10-13 22:26:46',1367.00),
(721,1,NULL,'Actuador dimmer bticino (F417U2)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/8012199968216.jpg','2022-10-25 20:45:43',NULL,NULL,'0','admin','2022-10-26 01:45:43','2022-10-26 01:45:43','8012199968216','2022-10-25 20:45:43',1290.00),
(722,0,NULL,'Registro de plastico naranja bticino (500)\\r\\nCLOSET',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/500-NA.png','2022-10-25 20:51:54',NULL,NULL,'0','admin','2022-10-26 01:51:54','2022-10-26 01:51:54','500-NA','2022-10-25 20:51:54',NULL),
(723,0,NULL,'Kit de seguridad hilook 4 canales, 4 camaras (HL24LQKITS-M)\\r\\nBAÑO',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/HL24LQKITS-M.png','2022-10-25 20:58:13',NULL,NULL,'0','admin','2022-10-26 01:58:13','2022-10-26 01:58:13','HL24LQKITS-M','2022-10-25 20:58:13',NULL),
(724,0,NULL,'No break sola basic monofasico 5000VA (XL-13-250)\\r\\nBODEGA',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/XL-13-250.png','2022-11-01 20:29:04',NULL,NULL,'0','admin','2022-11-02 02:29:04','2022-11-02 02:29:04','XL-13-250','2022-11-01 20:29:04',NULL),
(725,1,NULL,'Subwoofer ultradelgado de 10 pulgadas James Loudspeaker (DF-10)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/DF-10.png','2022-11-03 16:28:10',NULL,NULL,'0','admin','2022-11-03 22:28:10','2022-11-03 22:28:10','DF-10','2022-11-03 16:28:10',1161.00),
(726,1,NULL,'Subwoofer de 6.5 pulgadas 2 vias, profundidad de 4 pulgadas James Loudspeaker (OW63Q-M)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/OW63Q-M.png','2022-11-03 16:29:26',NULL,NULL,'0','admin','2022-11-03 22:29:26','2022-11-03 22:29:26','OW63Q-M','2022-11-03 16:29:26',1160.00),
(727,1,NULL,'Altavoces Horizontales para exterior 6,5 pulgadas James Loudspeaker (AT62-4)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/AT62-4.png','2022-11-03 16:31:16',NULL,NULL,'0','admin','2022-11-03 22:31:16','2022-11-03 22:31:16','AT62-4','2022-11-03 16:31:16',1163.00),
(728,1,NULL,'Altavoz todo terreno de 4 pulgadas y 2 Vias james Loudspeaker (AT42-4)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/AT42-4.png','2022-11-03 16:35:18',NULL,NULL,'0','admin','2022-11-03 22:35:18','2022-11-03 22:35:18','AT42-4','2022-11-03 16:35:18',1162.00),
(729,1,NULL,'James Loudspeaker Wedge Series W53Q Dual 5.25 Inch Woofer 2-Way Wedge Speaker (W53Q)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/W53Q.png','2022-11-03 16:37:48',NULL,NULL,'0','admin','2022-11-03 22:37:48','2022-11-03 22:37:48','W53Q','2022-11-03 16:37:48',1148.00),
(730,5,'DS-3E1510P-EI','Switch de administrable de 8 puertos 10/100/1000 Mbps PoE+ 2 puertos SFP 110w','Hikvision','SWITCH','Bodega General',NULL,NULL,'/img/productos/DS-3E1510P-EI.png','2022-11-15 16:27:55',NULL,NULL,'0','admin','2022-11-15 22:27:55','2022-11-15 22:27:55','DS-3E1510P-EI','2022-11-15 16:27:55',NULL),
(731,3,NULL,'Extensor de HDMI sobre fibra optica IP, control IR, Epcom Titanium (TT378A4K)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6955361269587.jpg','2022-11-15 16:30:51',NULL,NULL,'0','admin','2022-11-15 22:30:51','2022-11-15 22:30:51','6955361269587','2022-11-15 16:30:51',1304.00),
(732,8,'LPUT6100WH28','Patchcord delgado linkedpro de 1M cat6 UTP color Blanco','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPUT6100WH28.jpg','2022-11-15 16:37:00',NULL,NULL,'0','admin','2022-11-15 22:37:00','2022-11-15 22:37:00','LPUT6100WH28','2022-11-15 16:37:00',1351.00),
(733,0,'LPUT6200WH','Patchcord delgado linkedpro de 2M cat6 UTP color Blanco','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPUT6200WH.jpg','2022-11-15 16:37:35',NULL,NULL,'0','admin','2022-11-15 22:37:35','2022-11-15 22:37:35','LPUT6200WH','2022-11-15 16:37:35',1351.00),
(734,0,NULL,'Cubierta de taladro para polvo de plastico color blanco (X0031T0235)\\r\\nBAÑO',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/X0031T0235.png','2022-11-22 16:40:39',NULL,NULL,'0','admin','2022-11-22 22:40:39','2022-11-22 22:40:39','X0031T0235','2022-11-22 16:40:39',NULL),
(735,1,NULL,'Frente de botonera lutron 5 botones hibrida media noche (RKD-H5BRL-MN)',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2022-11-22 16:41:56',NULL,NULL,'0','admin','2022-11-22 22:41:56','2022-11-22 22:41:56','27557987837','2022-11-22 16:41:56',1247.00),
(736,2,NULL,'Frente de botonera lutron 6 botones hibrida media noche (RKD-H6BRL-MN)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/RKD-H6BRL-MN-E.png','2022-11-22 16:42:24',NULL,NULL,'0','admin','2022-11-22 22:42:24','2022-11-22 22:42:24','RKD-H6BRL-MN-E','2022-11-22 16:42:24',1247.00),
(737,3,'DS-KAB21-H','Montaje de Escritorio para Monitor Analógico DS-KH2220 y DSKIS203','Hikvision','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/246263883.png','2022-11-22 16:45:26',NULL,NULL,'0','admin','2022-11-22 22:45:26','2022-11-22 22:45:26','246263883','2022-11-22 16:45:26',1368.00),
(738,1,NULL,'Kit de videoportero IP, 1.3 megapixel, monitor touch, lector de tarjetas Hikvision (DS-KIS601)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/305301780.jpg','2022-11-22 16:59:35',NULL,NULL,'0','admin','2022-11-22 22:59:35','2022-11-22 22:59:35','305301780','2022-11-22 16:59:35',NULL),
(739,4,NULL,'EdgeRouter X de 5 puertos Gigabit con funciones avanzadas de ruteo Ubiquiti (ER-X)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/810354021640.png','2022-11-22 17:04:25',NULL,NULL,'0','admin','2022-11-22 23:04:25','2022-11-22 23:04:25','810354021640','2022-11-22 17:04:25',1339.00),
(740,2,NULL,'Bocina de exterior klipsch color blanco KIO (KIO-650)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/KIO-650.png','2022-12-03 17:23:56',NULL,NULL,'0','admin','2022-12-03 23:23:56','2022-12-03 23:23:56','KIO-650','2022-12-03 17:23:56',1034.00),
(741,2,NULL,'Receptor de 4 canales Seco Alarm independientes inalámbricos (SK-910R4Q)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/676544011330.png','2022-12-14 22:06:22',NULL,NULL,'0','admin','2022-12-15 04:06:22','2022-12-15 04:06:22','676544011330','2022-12-14 22:06:22',1368.00),
(742,0,NULL,'Relevador con temporizador elk products (ELK960)\\r\\nCLOSET',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/ELK960.png','2022-12-14 22:09:15',NULL,NULL,'0','admin','2022-12-15 04:09:15','2022-12-15 04:09:15','ELK960','2022-12-14 22:09:15',NULL),
(743,3,NULL,'Router administrable cloud 8 puertos gigabit, 1 puerto SFP + 1 puerto SFP, 6WAN, hasta 1000 usuarios, Ruijie (RG-EG3230)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/6971693275041.jpg','2022-12-21 21:48:48',NULL,NULL,'0','admin','2022-12-22 03:48:48','2022-12-22 03:48:48','6971693275041','2022-12-21 21:48:48',1298.00),
(744,0,NULL,'Gabinete precision (300 x 300 x 180 mm) cierre por broche inoxidable (PST303018E) BODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/PST303018E.png','2022-12-21 21:55:38',NULL,NULL,'0','admin','2022-12-22 03:55:38','2022-12-22 03:55:38','PST303018E','2022-12-21 21:55:38',NULL),
(745,1,NULL,'Divisor con amplificador hdmi steren UHD 4k 4 salidas (BOS-404)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/7506250917363.png','2023-01-23 22:01:12',NULL,NULL,'0','admin','2023-01-24 04:01:12','2023-01-24 04:01:12','7506250917363','2023-01-23 22:01:12',1354.00),
(746,3,NULL,'Switch HDMI 4x1 4K/18G con L/R y salida de audio óptico desincrustado Key Digital (KD-S4X1X)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/815996021034.jpg','2023-01-23 22:05:19',NULL,NULL,'0','admin','2023-01-24 04:05:19','2023-01-24 04:05:19','815996021034','2023-01-23 22:05:19',1304.00),
(747,38,NULL,'Kit lutron de pico switch y casetta dimmer blanco (DVRF-PKG1D-WH-R)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276816984.jpg','2023-01-23 22:06:34',NULL,NULL,'0','admin','2023-01-24 04:06:34','2023-01-24 04:06:34','784276816984','2023-01-23 22:06:34',1234.00),
(748,0,NULL,'Kit extensor balun HDMI por fibra optica Epcom Titanium (TT993)',NULL,'VIDEO','Bodega General',NULL,NULL,NULL,'2023-01-23 22:23:22',NULL,NULL,'0','admin','2023-01-24 04:23:22','2023-01-24 04:23:22','6955361273126','2023-01-23 22:23:22',1304.00),
(749,9,NULL,'Cámara tipo domo Hikvision 4MP color blanco colorvu (DS-2CD1347G2-LUF)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6931847178628.png','2023-01-23 22:29:11',NULL,NULL,'0','admin','2023-01-24 04:29:11','2023-01-24 04:29:11','6931847178628','2023-01-23 22:29:11',NULL),
(750,0,NULL,'Amplificador Crown de 2 canales 650W negro (NXL2002)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991001086.png','2023-01-23 22:31:19',NULL,NULL,'0','admin','2023-01-24 04:31:19','2023-01-24 04:31:19','691991001086','2023-01-23 22:31:19',1117.00),
(751,1,NULL,'Bobina de Cable para Aplicaciones en alarmas de Intrusión, Control de Acceso, Tv Porteros, Automatización, Audio y Voceo, 22x6 AWG Genesis Gris (22061109)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/619172021081.png','2023-01-23 22:36:59',NULL,NULL,'0','admin','2023-01-24 04:36:59','2023-01-24 04:36:59','619172021081','2023-01-23 22:36:59',NULL),
(752,0,'TL-SL2428P','Switch de red ethernet 24 puertos 10/100mps + 2 puertos SFP gestionado','Tp-Link','SWITCH','Bodega General',NULL,NULL,NULL,'2023-01-27 18:31:53',NULL,NULL,'0','admin','2023-01-28 00:31:53','2023-01-28 00:31:53','845973088699','2023-01-27 18:31:53',1303.00),
(753,14,NULL,'Bocina de muro Sonos/Sonance in-ceilling color blanco (INCLGWW1)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/878269007647.png','2023-01-30 20:09:05',NULL,NULL,'0','admin','2023-01-31 02:09:05','2023-01-31 02:09:05','878269007647','2023-01-30 20:09:05',1183.00),
(754,0,NULL,'Bocina Revel de plafón circular exterior color blanco  6 pulgadas (C363XC)',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2023-01-30 20:15:33',NULL,NULL,'0','admin','2023-01-31 02:15:33','2023-01-31 02:15:33','050667370619','2023-01-30 20:15:33',1168.00),
(755,0,NULL,'Soporte de television doble brazo hasta 45-70\" negro (MODEL:P-6)\r\nTALLER',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,NULL,'2023-01-30 20:26:27',NULL,NULL,'0','admin','2023-01-31 02:26:27','2023-01-31 02:26:27','MODELP-6','2023-01-30 20:26:27',0.00),
(756,3,NULL,'Inyector PoE planet negro 2 puertos (POE-173)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/847690002880.png','2023-01-30 20:30:23',NULL,NULL,'0','admin','2023-01-31 02:30:23','2023-01-31 02:30:23','847690002880','2023-01-30 20:30:23',1339.00),
(757,50,'ACCESSPROXCARD','Tarjeta de proximidad, color blanca','AccessPro','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/ACCESSPROXCARD.png','2023-02-08 21:33:13',NULL,NULL,'0','admin','2023-02-09 03:33:13','2023-02-09 03:33:13','ACCESSPROXCARD','2023-02-08 21:33:13',1354.00),
(758,0,NULL,'Rollo de cable primal 2x16 de 152,2m color blanco (811885022700)\r\nBODEGA',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2023-02-08 21:40:55',NULL,NULL,'0','admin','2023-02-09 03:40:55','2023-02-09 03:40:55','811885022700','2023-02-08 21:40:55',0.00),
(759,1,NULL,'Cerradura magnetica de 600LB Assa Abloy (600LB)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/604840236379.png','2023-02-08 21:43:39',NULL,NULL,'0','admin','2023-02-09 03:43:39','2023-02-09 03:43:39','604840236379','2023-02-08 21:43:39',NULL),
(760,359,'LPUT6030WH28','Patchcord delgado linkedpro de 30CM cat6 UTP color Blanco','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPUT6030WH28.jpg','2023-02-08 21:49:36',NULL,NULL,'0','admin','2023-02-09 03:49:36','2023-02-09 03:49:36','LPUT6030WH28','2023-02-08 21:49:36',1351.00),
(761,0,'LPUT6100GY28','Patchcord delgado de 1M cat6 UTP color gris','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,NULL,'2023-02-08 21:50:50',NULL,NULL,'0','admin','2023-02-09 03:50:50','2023-02-09 03:50:50','LPUT6100GY28','2023-02-08 21:50:50',NULL),
(762,0,NULL,'Rollo de cable tierra calibre 0 rockseries color negro (PC015BK)\r\nBODEGA',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2023-02-08 21:53:02',NULL,NULL,'0','admin','2023-02-09 03:53:02','2023-02-09 03:53:02','PC015BK','2023-02-08 21:53:02',0.00),
(763,0,NULL,'Rollo de cable tierra calibre 0 rockseries color rojo (PC015RO)\r\nBODEGA',NULL,'CABLEADO','Bodega General',NULL,NULL,NULL,'2023-02-08 21:53:14',NULL,NULL,'0','admin','2023-02-09 03:53:14','2023-02-09 03:53:14','PC015RO','2023-02-08 21:53:14',0.00),
(764,0,NULL,'Bocina marina de 7\\\" JL color blanca (770ETXV3)\\r\\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/770ETXV3.png','2023-02-08 21:54:01',NULL,NULL,'0','admin','2023-02-09 03:54:01','2023-02-09 03:54:01','770ETXV3','2023-02-08 21:54:01',NULL),
(765,0,NULL,'Subwoofer marino de 10\\\" JL color blanco (M6-10)\\r\\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/M6-10.png','2023-02-08 21:54:57',NULL,NULL,'0','admin','2023-02-09 03:54:57','2023-02-09 03:54:57','M6-10','2023-02-08 21:54:57',NULL),
(766,0,NULL,'Amplifiacdor de rango completo JL (KDM400/4)\\r\\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/KDM400-4.png','2023-02-08 21:55:42',NULL,NULL,'0','admin','2023-02-09 03:55:42','2023-02-09 03:55:42','KDM400/4','2023-02-08 21:55:42',NULL),
(767,0,NULL,'Amplificador marino clase \\\"D\\\" JL color blanco (M600/1)\\r\\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/M600-1.png','2023-02-08 21:56:48',NULL,NULL,'0','admin','2023-02-09 03:56:48','2023-02-09 03:56:48','M600/1','2023-02-08 21:56:48',NULL),
(768,0,NULL,'Control de audio digital JL mediamaster color negro (MEDIAMASTER105)\\r\\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/MEDIAMASTER105.png','2023-02-08 21:57:24',NULL,NULL,'0','admin','2023-02-09 03:57:24','2023-02-09 03:57:24','MEDIAMASTER105','2023-02-08 21:57:24',NULL),
(769,1,'SR1912GFPVR2','Gabinete de pared fijo de puerta perforada de 12U puerta de cristal templado, fabricado en acero','Linkedpro','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/SR1912GFPVR2.png','2023-02-08 21:59:38',NULL,NULL,'0','admin','2023-02-09 03:59:38','2023-02-09 03:59:38','SR1912GFPVR2','2023-02-08 21:59:38',1111.00),
(770,0,NULL,'Sujetador de montaje en tubo bracket JL de metal (91280)\\r\\nBODEGA',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/91280.png','2023-02-08 22:00:33',NULL,NULL,'0','admin','2023-02-09 04:00:33','2023-02-09 04:00:33','91280','2023-02-08 22:00:33',NULL),
(771,0,NULL,'Lector de RFID de largo alcance para control de acceso vehicular (PRO12RF)\\r\\nBODEGA',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/PRO12RF.png','2023-02-08 22:02:18',NULL,NULL,'0','admin','2023-02-09 04:02:18','2023-02-09 04:02:18','PRO12RF','2023-02-08 22:02:18',NULL),
(772,2,NULL,'Fusible JL maxi audio 60A color azul (XD-MAXI-60)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/XD-MAXI-60.png','2023-02-08 22:03:10',NULL,NULL,'0','admin','2023-02-09 04:03:10','2023-02-09 04:03:10','XD-MAXI-60','2023-02-08 22:03:10',1359.00),
(773,1,NULL,'Conector de batería ultra compacto JL audio (XD-BTS)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/699440904582.png','2023-02-08 22:04:21',NULL,NULL,'0','admin','2023-02-09 04:04:21','2023-02-09 04:04:21','699440904582','2023-02-08 22:04:21',1359.00),
(774,4,NULL,'Bloque de distribucion JL de 2 vias portafusible (XD-FDBU-2)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/699440904612.png','2023-02-08 22:05:27',NULL,NULL,'0','admin','2023-02-09 04:05:27','2023-02-09 04:05:27','699440904612','2023-02-08 22:05:27',1359.00),
(775,0,NULL,'Gabinete de pared linkedpro 6U negro metalico (LPGPH06R2)\\r\\nTALLER',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/LPGPH06R2.png','2023-02-28 17:02:52',NULL,NULL,'0','admin','2023-02-28 23:02:52','2023-02-28 23:02:52','LPGPH06R2','2023-02-28 17:02:52',NULL),
(776,0,NULL,'Altavoz de piedra para exteriores klipsch (PRO-500T-RK)\\r\\nTALLER',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/PRO-500T-RK.png','2023-02-28 17:26:32',NULL,NULL,'0','admin','2023-02-28 23:26:32','2023-02-28 23:26:32','PRO-500T-RK','2023-02-28 17:26:32',NULL),
(777,0,NULL,'Receptor Denon av 8k teatro en casa 7.2ch 150W (AVR-X2800HBKE)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/747192138462.png','2023-02-28 17:29:20',NULL,NULL,'0','admin','2023-02-28 23:29:20','2023-02-28 23:29:20','747192138462','2023-02-28 17:29:20',1126.00),
(778,2,NULL,'Dimmer inteligente martin jerry (SD-TC01-1P)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/SD-TC01-1P.jpg','2023-03-01 16:05:53',NULL,NULL,'0','admin','2023-03-01 22:05:53','2023-03-01 22:05:53','SD-TC01-1P','2023-03-01 16:05:53',1285.00),
(779,12,NULL,'Botonera lutron de 2 botones homeworksQS color blanca (HQWT-U-P2W-WH-E)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276112659.png','2023-03-06 16:31:04',NULL,'JEFF YORKE | APARTADO:TICKET | FOLIO:JEFF YORKE | VENCE:2026-06-30\r\n10 pz PARA CLIENTE JEFF YORK','0','admin','2023-03-06 22:31:04','2023-03-06 22:31:04','784276112659','2023-03-06 16:31:04',1271.00),
(780,5,NULL,'Botonera lutron de 3 botones homeworks QS color blanco (HQWT-U-P3W-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276112871.png','2023-03-06 16:32:32',NULL,NULL,'0','admin','2023-03-06 22:32:32','2023-03-06 22:32:32','784276112871','2023-03-06 16:32:32',1270.00),
(781,13,NULL,'Dimmer lutron homeworks qs pro color biscuit (HQRD-PRO-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276270724.jpg','2023-03-06 16:34:46',NULL,NULL,'0','admin','2023-03-06 22:34:46','2023-03-06 22:34:46','784276270724','2023-03-06 16:34:46',1271.00),
(782,2,NULL,'Switch lutron homeworks QS color blanco (HQRD-8ANS-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557005302.png','2023-03-06 16:36:08',NULL,NULL,'0','admin','2023-03-06 22:36:08','2023-03-06 22:36:08','027557005302','2023-03-06 16:36:08',1271.00),
(783,1,NULL,'Modulo de alimentacion electrica DIN de salida para cuatro zonas con Subir/Bajar para controlar cargas de motor de CA de tres cables lutron (LQSE-4M-120-D)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276258128.png','2023-03-06 16:37:30',NULL,NULL,'0','admin','2023-03-06 22:37:30','2023-03-06 22:37:30','784276258128','2023-03-06 16:37:30',1243.00),
(784,1,NULL,'Cubierta para panel grafik lutron color negro (LWT-G-BL)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/LWT-G-BL.png','2023-03-06 16:39:16',NULL,NULL,'0','admin','2023-03-06 22:39:16','2023-03-06 22:39:16','LWT-G-BL','2023-03-06 16:39:16',1227.00),
(785,78,NULL,'Modulo de alimentacion electrica adaptable lutron (LQSE-4A1-D)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276109055.jpg','2023-03-06 16:40:47',NULL,'PACIFICA-1 | APARTADO:PROYECTO | FOLIO:PACIFICA-1 | VENCE:2026-07-02\nSE APARTAN 35pz. PARA PROYECTO PACIFICA-1','35','admin','2023-03-06 22:40:47','2023-03-06 22:40:47','784276109055','2023-03-06 16:40:47',1258.00),
(786,0,NULL,'Kit amplificador de señal telefonica epcom (EPDB3G)\\r\\n',NULL,'TELEFONIA','Bodega General',NULL,NULL,'/img/productos/EPDB3G.png','2023-03-06 16:56:35',NULL,NULL,'0','admin','2023-03-06 22:56:35','2023-03-06 22:56:35','EPDB3G','2023-03-06 16:56:35',NULL),
(787,2,NULL,'No break epcom de 850VA negro (EPU850L)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/EPU850L.png','2023-03-24 16:49:49',NULL,NULL,'0','admin','2023-03-24 22:49:49','2023-03-24 22:49:49','EPU850L','2023-03-24 16:49:49',1074.00),
(788,0,NULL,'No break epcom horizontal de 1000VA modelo estandard (STANDARD1000VA)\\r\\n\\r\\nTALLER',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/STANDARD1000VA.png','2023-03-24 16:50:54',NULL,NULL,'0','admin','2023-03-24 22:50:54','2023-03-24 22:50:54','STANDARD1000VA','2023-03-24 16:50:54',NULL),
(789,60,NULL,'Módulo de conmutación de alimentación eléctrica 4 Zonas, Lutron (LQSE-4S8-120-D)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276240048.jpg','2023-04-11 22:46:47',NULL,NULL,'0','admin','2023-04-12 04:46:47','2023-04-12 04:46:47','784276240048','2023-04-11 22:46:47',1259.00),
(790,4,NULL,'Bocina estereo Sonos Era 100 color negro (ERA100-BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136805830.png','2023-04-11 22:52:11',NULL,NULL,'0','admin','2023-04-12 04:52:11','2023-04-12 04:52:11','840136805830','2023-04-11 22:52:11',1165.00),
(791,4,NULL,'Bocina estereo Sonos era 300 color blanco (ERA300-WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136805588.png','2023-04-11 22:53:05',NULL,NULL,'0','admin','2023-04-12 04:53:05','2026-09-08 11:34:49','840136805588','2023-04-11 22:53:05',1184.00),
(792,0,NULL,'Base para proyector strong metalico (SM-PROJ-XL)\\r\\nTALLER',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/SM-PROJ-XL.png','2023-04-11 22:53:55',NULL,NULL,'0','admin','2023-04-12 04:53:55','2023-04-12 04:53:55','SM-PROJ-XL','2023-04-11 22:53:55',NULL),
(793,2,NULL,'Controlador de entretenimiento control core 3 (C4-CORE3)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/C4-CORE3.png','2023-04-11 22:58:13',NULL,NULL,'0','admin','2023-04-12 04:58:13','2023-04-12 04:58:13','C4-CORE3','2023-04-11 22:58:13',1279.00),
(794,0,NULL,'Estacion de acoplamiento de disco duro externo orico (6629US3-C)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/6629US3-C.png','2023-04-11 23:06:49',NULL,NULL,'0','admin','2023-04-12 05:06:49','2023-04-12 05:06:49','6629US3-C','2023-04-11 23:06:49',NULL),
(795,1,NULL,'Botonera de 6 botones tis (VEN-6S-BUS)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/658921796252.png','2023-04-18 22:43:55',NULL,NULL,'0','admin','2023-04-19 04:43:55','2023-04-19 04:43:55','658921796252','2023-04-18 22:43:55',1277.00),
(796,0,NULL,'Amplificador de audio delgado crown cometch (87101500475)\\r\\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/87101500475.png','2023-04-19 17:41:52',NULL,NULL,'0','admin','2023-04-19 23:41:52','2023-04-19 23:41:52','87101500475','2023-04-19 17:41:52',NULL),
(797,0,NULL,'Rollo de cable UtTP cat6 panduit azul (PUC6004BU-FE)\\r\\nBODEGA',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/PUC6004BU-FE.png','2023-04-29 17:07:18',NULL,NULL,'0','admin','2023-04-29 23:07:18','2023-04-29 23:07:18','PUC6004BU-FE','2023-04-29 17:07:18',NULL),
(798,0,NULL,'Receptor de 9 canales teatro en casa integra (DRX-5.4M2)\\r\\nBODEGA',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/DRX-5-4M2.png','2023-04-29 17:09:32',NULL,NULL,'0','admin','2023-04-29 23:09:32','2023-04-29 23:09:32','DRX-5.4M2','2023-04-29 17:09:32',NULL),
(799,12,NULL,'Transeptor pasivo balun Epcom Titanium (TT101FTURBO)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/TT101FTURBO.png','2023-04-29 17:13:48',NULL,NULL,'0','admin','2023-04-29 23:13:48','2023-04-29 23:13:48','TT101FTURBO','2023-04-29 17:13:48',1331.00),
(800,11,NULL,'Transceptor activo balun Epcom Titanium (TT101PVTURBO)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/TT101PVTURBO.png','2023-04-29 17:14:48',NULL,NULL,'0','admin','2023-04-29 23:14:48','2023-04-29 23:14:48','TT101PVTURBO','2023-04-29 17:14:48',1331.00),
(801,3,NULL,'Dimmer lutron radiora2 color blanco (RRD-6ND-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276243797.jpg','2023-05-04 23:42:13',NULL,NULL,'0','admin','2023-05-05 05:42:13','2023-05-05 05:42:13','784276243797','2023-05-04 23:42:13',NULL),
(802,10,NULL,'Switch casseta lutron RF color blanco (DVRF-AS-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276302753.jpg','2023-05-04 23:44:32',NULL,NULL,'0','admin','2023-05-05 05:44:32','2023-05-05 05:44:32','784276302753','2023-05-04 23:44:32',1237.00),
(803,7,NULL,'Botonera lutron sunnata 4 botones color blanco (RRST-W4B-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/RRST-W4B-WH.jpg','2023-05-04 23:45:16',NULL,NULL,'0','admin','2023-05-05 05:45:16','2023-05-05 05:45:16','RRST-W4B-WH','2023-05-04 23:45:16',1243.00),
(804,8,NULL,'Switch inteligente lutron sunnata radiora3 color blanco (RRST-RS-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276808750.jpg','2023-05-04 23:46:15',NULL,NULL,'0','admin','2023-05-05 05:46:15','2023-05-05 05:46:15','784276808750','2023-05-04 23:46:15',1243.00),
(805,4,NULL,'Dimmer inteligente lutron sunnata radiora3 color blanco (RRST-RD-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276808682.png','2023-05-04 23:48:03',NULL,NULL,'0','admin','2023-05-05 05:48:03','2023-05-05 05:48:03','784276808682','2023-05-04 23:48:03',1243.00),
(806,9,NULL,'Casseta lutron diva dimmer inteligente color almendra claro (DVRF-6L-LA)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276302685.png','2023-05-04 23:49:55',NULL,NULL,'0','admin','2023-05-05 05:49:55','2023-05-05 05:49:55','784276302685','2023-05-04 23:49:55',1237.00),
(807,36,NULL,'Casseta lutron diva switch inteligente color blanco (DVRF-5NS-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276302630.jpg','2023-05-04 23:50:56',NULL,NULL,'0','admin','2023-05-05 05:50:56','2023-05-05 05:50:56','784276302630','2023-05-04 23:50:56',1238.00),
(808,2,NULL,'Bocina marina sonance mariner64 color negro (MARINER64-BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/MARINER64-BLK.png','2023-05-04 23:53:53',NULL,NULL,'0','admin','2023-05-05 05:53:53','2023-05-05 05:53:53','MARINER64-BLK','2023-05-04 23:53:53',1028.00),
(809,1,NULL,'Camara domo Hikvision turbo HD colorvu (DS-2CE72UF3T-E)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6941264071839.png','2023-05-11 00:38:47',NULL,NULL,'0','admin','2023-05-11 06:38:47','2023-05-11 06:38:47','6941264071839','2023-05-11 00:38:47',1346.00),
(810,2,'RG-RAP6262(G)','Punto de acceso para exterior esférico blanco','Ruijie','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/6971693272422.png','2023-05-22 22:34:42',NULL,NULL,'0','admin','2023-05-23 04:34:42','2023-05-23 04:34:42','6971693272422','2023-05-22 22:34:42',1311.00),
(811,30,NULL,'Interruptor de uso general, 4 vias 15A, lutron satin colors color biscuit (SC-4PS-BI-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485609.jpg','2023-05-22 22:40:07',NULL,NULL,'0','admin','2023-05-23 04:40:07','2023-05-23 04:40:07','027557485609','2023-05-22 22:40:07',1238.00),
(812,1,NULL,'Teclado autónomo con lector de proximidad Access Pro (PROKEYPADSV2)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/PROKEYPADSV2.png','2023-05-26 17:51:04',NULL,NULL,'0','admin','2023-05-26 23:51:04','2023-05-26 23:51:04','PROKEYPADSV2','2023-05-26 17:51:04',1368.00),
(813,1,NULL,'Cerrador de puerta automatico metalico Tesa  Asa abloy (1604)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/601154023107.png','2023-05-26 17:53:05',NULL,NULL,'0','admin','2023-05-26 23:53:05','2023-05-26 23:53:05','601154023107','2023-05-26 17:53:05',1325.00),
(814,2,'NKFP24Y','Patchpanel de parcheo negro de plástico 24 puertos RJ45 sin conectores','Panduit','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/74983055937.png','2023-05-26 17:56:20',NULL,NULL,'0','admin','2023-05-26 23:56:20','2023-05-26 23:56:20','74983055937','2023-05-26 17:56:20',1365.00),
(815,0,NULL,'Convertidor de medios Planet 1000 base-T a dual 1000 base-x sfp (GT-1205A)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/847690000459.png','2023-06-07 18:10:08',NULL,NULL,'0','admin','2023-06-08 00:10:08','2023-06-08 00:10:08','847690000459','2023-06-07 18:10:08',1359.00),
(816,2,NULL,'Puerto Gigabit, plug & play, ethernet sobre alimentación red domestica Tp-Link (TL-PA7017-KIT)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/845973089054.jpg','2023-06-07 18:12:55',NULL,NULL,'0','admin','2023-06-08 00:12:55','2023-06-08 00:12:55','845973089054','2023-06-07 18:12:55',1310.00),
(817,1,NULL,'Boton de salida sin contacto hikvision (DS-K7P03)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/DS-K7P03.png','2023-06-07 18:13:44',NULL,NULL,'0','admin','2023-06-08 00:13:44','2023-06-08 00:13:44','DS-K7P03','2023-06-07 18:13:44',1285.00),
(818,0,NULL,'Pilar de rack strong personalizado de 32u negro (SR-CUSTOMPILLAR-32U) BODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/SR-CUSTOMPILLAR-32U.png','2023-06-14 23:30:28',NULL,NULL,'0','admin','2023-06-15 05:30:28','2023-06-15 05:30:28','SR-CUSTOMPILLAR-32U','2023-06-14 23:30:28',NULL),
(819,1,'SC-3-SW','Tapa de 3 ventanas, Color Nieve','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557475341.png','2023-06-22 20:57:35',NULL,NULL,'0','admin','2023-06-23 02:57:35','2023-06-23 02:57:35','027557475341','2023-06-22 20:57:35',1223.00),
(820,7,'SC-2-SW','Tapa de 2 ventanas, color nieve','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/SC-2-SW.png','2023-06-22 20:58:36',NULL,NULL,'0','admin','2023-06-23 02:58:36','2023-06-23 02:58:36','SC-2-SW','2023-06-22 20:58:36',1218.00),
(821,8,NULL,'Contacto duplex USB de 15A con proteccion para niños lutron satin colors color nieve (SCR-15-UBTR-SW)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/784276166362.png','2023-06-22 20:59:35',NULL,NULL,'0','admin','2023-06-23 02:59:35','2023-06-23 02:59:35','784276166362','2023-06-22 20:59:35',1263.00),
(822,1,NULL,'Interruptor unipolar de uso general lutron satin colors color nieve (SC-1PS-SW-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485432.png','2023-06-22 21:01:50',NULL,NULL,'0','admin','2023-06-23 03:01:50','2023-06-23 03:01:50','027557485432','2023-06-22 21:01:50',1242.00),
(823,1,NULL,'Interruptor de uso general, 4 vias 15A, lutron satin colors color nieve (SC-4PS-SW-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/SC-4PS-SW-L.jpg','2023-06-22 21:03:04',NULL,NULL,'0','admin','2023-06-23 03:03:04','2023-06-23 03:03:04','SC-4PS-SW-L','2023-06-22 21:03:04',1242.00),
(824,22,NULL,'Contacto duplex sencillo de 15A lutron satin colors color nieve (SCR-15-SW-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557485982.png','2023-06-22 21:05:54',NULL,NULL,'0','admin','2023-06-23 03:05:54','2023-06-23 03:05:54','027557485982','2023-06-22 21:05:54',1267.00),
(825,7,NULL,'Contacto duplex falla tierra de 15A GFCI lutron satin colors color nieve (SCR-15-GFST-SW-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276159463.jpg','2023-06-22 21:06:43',NULL,NULL,'0','admin','2023-06-23 03:06:43','2023-06-23 03:06:43','784276159463','2023-06-22 21:06:43',1263.00),
(826,3,'SC-6PF-SW','Marco de 6 puertos, Satin Colors, color nieve','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/027557061957.png','2023-06-22 21:08:05',NULL,NULL,'0','admin','2023-06-23 03:08:05','2023-06-23 03:08:05','027557061957','2023-06-22 21:08:05',1230.00),
(827,0,NULL,'Lampara inteligente lutron ketra grande negra (HW-S38-LAMP-X)\\r\\nBODEGA',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/HW-S38-LAMP-X.png','2023-06-29 17:40:26',NULL,NULL,'0','admin','2023-06-29 23:40:26','2023-06-29 23:40:26','HW-S38-LAMP-X','2023-06-29 17:40:26',NULL),
(828,0,NULL,'Lampara ajustable con base lutron ketra (HW-D4R-DWNLGT-X)\\r\\nBODEGA',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/HW-D4R-DWNLGT-X.png','2023-06-29 17:52:44',NULL,NULL,'0','admin','2023-06-29 23:52:44','2023-06-29 23:52:44','HW-D4R-DWNLGT-X','2023-06-29 17:52:44',NULL),
(829,1,NULL,'Nvr de 8 canalesHikvision 1080p cuadrada blanca (DS-7108NI-Q1/8P)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6941264092155.png','2023-06-29 17:55:02',NULL,NULL,'0','admin','2023-06-29 23:55:02','2023-06-29 23:55:02','6941264092155','2023-06-29 17:55:02',1344.00),
(830,6,NULL,'Traductor de enlaces lutron (HQ-HWI-LX)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/784276107464.png','2023-07-20 17:22:57',NULL,NULL,'0','admin','2023-07-20 23:22:57','2023-07-20 23:22:57','784276107464','2023-07-20 17:22:57',1233.00),
(831,1,NULL,'Procesador de iluminacion serie 8 lutron (H8P5-MI-H48-120)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/H8P5-MI-H48-120.png','2023-07-20 17:26:49',NULL,NULL,'0','admin','2023-07-20 23:26:49','2023-07-20 23:26:49','H8P5-MI-H48-120','2023-07-20 17:26:49',1255.00),
(832,6,NULL,'Camara tipo domo Hikvision color blanco colorvu (DS-2CD1347G0-L)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6931847127619.png','2023-08-08 18:50:09',NULL,NULL,'0','admin','2023-08-09 00:50:09','2023-08-09 00:50:09','6931847127619','2023-08-08 18:50:09',1346.00),
(833,0,NULL,'Camara IP tipo domo Hikvision acusense 6MP doble lente (DS-2CD2363G2-IU)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6941264098492.png','2023-08-08 18:59:39',NULL,NULL,'0','admin','2023-08-09 00:59:39','2023-08-09 00:59:39','6941264098492','2023-08-08 18:59:39',1346.00),
(834,1,NULL,'Casseta lutron diva dimmer inteligente color blanco (DVRF-6L-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/DVRF-6L-WH.jpg','2023-08-18 20:40:13',NULL,NULL,'0','admin','2023-08-19 02:40:13','2023-08-19 02:40:13','DVRF-6L-WH','2023-08-18 20:40:13',1238.00),
(835,1,NULL,'Hub zigbee aqara inteligente M1S (HM1S-G01)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/HM1S-G01.png','2023-08-18 20:42:28',NULL,NULL,'0','admin','2023-08-19 02:42:28','2023-08-19 02:42:28','HM1S-G01','2023-08-18 20:42:28',1288.00),
(836,12,NULL,'Botonera insteon de 4 botones color blanco (KP014)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/813922018363.png','2023-08-18 20:46:47',NULL,NULL,'0','admin','2023-08-19 02:46:47','2023-08-19 02:46:47','813922018363','2023-08-18 20:46:47',1294.00),
(837,3,'HTCM1U10C','Multicontacto horizontal para rack  de 10 tomas','Linkedpro','CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/HTCM1U10C.jpg','2023-08-18 20:55:43',NULL,NULL,'0','admin','2023-08-19 02:55:43','2023-08-19 02:55:43','HTCM1U10C','2023-08-18 20:55:43',1022.00),
(838,1,NULL,'Base de muro para bocina sonos era 300 color negro (E30MPWW1-BLK)\\r\\nTALLER',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/E30MPWW1-BLK.png','2023-09-30 18:29:39',NULL,NULL,'0','admin','2023-10-01 00:29:39','2023-10-01 00:29:39','E30MPWW1-BLK','2023-09-30 18:29:39',NULL),
(839,0,NULL,'Estaca para exterior de 19\\\" sonance color cafe (19\\\"STAKE)\\r\\nTALLER',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/19-STAKE.png','2023-09-30 18:32:30',NULL,NULL,'0','admin','2023-10-01 00:32:30','2023-10-01 00:32:30','19\\\"STAKE','2023-09-30 18:32:30',NULL),
(840,0,NULL,'Bocina para exterior tipo estaca sonance color cafe (LST6T-SAT)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/LST6T-SAT.png','2023-09-30 18:38:30',NULL,NULL,'0','admin','2023-10-01 00:38:30','2023-10-01 00:38:30','LST6T-SAT','2023-09-30 18:38:30',NULL),
(841,0,NULL,'Subwoofer para exterior tipo hongo color cafe sonance (LS12T-SUB)\\r\\nTALLER',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/LS12T-SUB.png','2023-09-30 18:40:59',NULL,NULL,'0','admin','2023-10-01 00:40:59','2023-10-01 00:40:59','LS12T-SUB','2023-09-30 18:40:59',NULL),
(842,4,NULL,'Botonera lutron homeworksQS 5 botones color negro (HQWD-W5B-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2023-10-04 18:32:09',NULL,NULL,'0','admin','2023-10-05 00:32:09','2023-10-05 00:32:09','2027557012270','2023-10-04 18:32:09',1266.00),
(843,1,NULL,'Bocina estéreo Sonos Era 300 color negro (E30G1US1BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136805595.png','2023-10-04 18:33:08',NULL,NULL,'0','admin','2023-10-05 00:33:08','2023-10-05 00:33:08','840136805595','2023-10-04 18:33:08',1164.00),
(844,2,NULL,'Bocina para exterior bose 251 color negra (251-BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/17817263559.png','2023-11-10 16:34:32',NULL,NULL,'0','admin','2023-11-10 22:34:32','2023-11-10 22:34:32','17817263559','2023-11-10 16:34:32',1131.00),
(845,11,'U6-LITE','Punto de Acceso UniFi doble banda 802.11ax WiFi 6, 5 GHz (MU-MIMO 2x2 y OFDMA) y 2.4 GHz (MIMO 2x2)','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/810010073341.png','2023-11-30 17:37:26',NULL,NULL,'0','admin','2023-11-30 23:37:26','2023-11-30 23:37:26','810010073341','2023-11-30 17:37:26',1343.00),
(846,6,'UDM-PRO','Consola ubiquiti UniFi OS Console: Dream Machine Pro, con 1 puerto WAN Gigabit RJ45, 1 puerto WAN 10G SFP+ / 8 puertos LAN Gigabit RJ-45, y una banda de HDD 3.5 pulgadas (No incluye HDD), Integra todas las aplicaciones UniFi','Ubiquiti','RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/810010071033.png','2023-11-30 17:40:57',NULL,NULL,'0','admin','2023-11-30 23:40:57','2023-11-30 23:40:57','810010071033','2023-11-30 17:40:57',1308.00),
(847,28,NULL,'Boton timbre switch compañero lutron satin colors color snow (MSC-AS-SW)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/027557066143.png','2023-12-06 17:36:05',NULL,'PACIFICA-1 | APARTADO:PROYECTO | FOLIO:PACIFICA-1 | VENCE:2026-07-02\nSE APARTAN 20pz. PARA PROYECTO PACIFICA-1','20','admin','2023-12-06 23:36:05','2023-12-06 23:36:05','027557066143','2023-12-06 17:36:05',1268.00),
(848,1,NULL,'Modulo de poder lutron dalun (LQSE-2DALUNV-D)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276284783.jpg','2023-12-06 17:37:10',NULL,NULL,'0','admin','2023-12-06 23:37:10','2023-12-06 23:37:10','784276284783','2023-12-06 17:37:10',1243.00),
(849,1,NULL,'Cable de 305m para aplicaciones en alarmas de intrusión, control de acceso, automatización, interfonos y tv porteros, S-Fire color blanco (SF-2204)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/SF-2204.jpg','2023-12-06 17:40:48',NULL,NULL,'0','admin','2023-12-06 23:40:48','2023-12-06 23:40:48','SF-2204','2023-12-06 17:40:48',NULL),
(850,5,NULL,'Regulador de voltaje y protector de sobrecarga, 11 tomas, Panamax (MR5100)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/050616008945.png','2023-12-20 21:13:11',NULL,NULL,'0','admin','2023-12-21 03:13:11','2023-12-21 03:13:11','050616008945','2023-12-20 21:13:11',NULL),
(851,1,NULL,'Bocina de muro Revel para interior 8 pulgadas (W383)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/W383.png','2023-12-20 21:15:31',NULL,NULL,'0','admin','2023-12-21 03:15:31','2023-12-21 03:15:31','W383','2023-12-20 21:15:31',1173.00),
(852,8,NULL,'Base de muro para bocina Sonos Era 300 color blanco (E30MPWW1-WH)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/8717755779380.png','2023-12-20 21:17:24',NULL,NULL,'0','admin','2023-12-21 03:17:24','2023-12-21 03:17:24','8717755779380','2023-12-20 21:17:24',1185.00),
(853,1,NULL,'Kit adaptador para switch exterior Ubiquiti blanco (USW-FLEX-UTILITY)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/810010070531.png','2023-12-20 21:20:30',NULL,NULL,'0','admin','2023-12-21 03:20:30','2023-12-21 03:20:30','810010070531','2023-12-20 21:20:30',1331.00),
(854,1,'USW-FLEX','Switch de red Unifi Flex de 5 puertos semi-exterior','Ubiquiti','SWITCH','Bodega General',NULL,NULL,'/img/productos/817882027595.png','2023-12-20 21:22:26',NULL,NULL,'0','admin','2023-12-21 03:22:26','2023-12-21 03:22:26','817882027595','2023-12-20 21:22:26',1339.00),
(855,3,NULL,'Switch de pared sunnata lutron color blanco (STCL-153MW-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276307802.jpg','2023-12-20 21:25:43',NULL,NULL,'0','admin','2023-12-21 03:25:43','2023-12-21 03:25:43','784276307802','2023-12-20 21:25:43',1237.00),
(856,1,NULL,'Bocina para exterior de 6 pulgadas color blanco Focal (100-OD6-WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/100-OD6-WH.png','2023-12-20 21:29:06',NULL,NULL,'0','admin','2023-12-21 03:29:06','2023-12-21 03:29:06','100-OD6-WH','2023-12-20 21:29:06',1129.00),
(857,5,NULL,'Bocina para exterior de 8 pulgadas color blanco focal (100-OD8-WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/3544051691413.png','2023-12-20 21:29:36',NULL,NULL,'0','admin','2023-12-21 03:29:36','2023-12-21 03:29:36','3544051691413','2023-12-20 21:29:36',NULL),
(858,1,NULL,'Bocina para exterior de 8 pulgadas color negro focal (100-OD8-BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/3544054691410.png','2023-12-20 21:36:45',NULL,NULL,'0','admin','2023-12-21 03:36:45','2023-12-21 03:36:45','3544054691410','2023-12-20 21:36:45',1139.00),
(859,0,NULL,'Bocina tipo piedra para exterior focal de 8\\\" (OD-STONE-8)\\r\\nSALA JUNTAS',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/OD-STONE-8.png','2023-12-20 21:38:44',NULL,NULL,'0','admin','2023-12-21 03:38:44','2023-12-21 03:38:44','OD-STONE-8','2023-12-20 21:38:44',NULL),
(860,2,NULL,'Bocina tipo repisa focal dark (VESTIA-N1-DARK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/VESTIA-N1-DARK.png','2023-12-20 21:40:21',NULL,NULL,'0','admin','2023-12-21 03:40:21','2023-12-21 03:40:21','VESTIA-N1-DARK','2023-12-20 21:40:21',1070.00),
(861,0,NULL,'Gabinete de acero anclo 40x30x20 (G4A43020-UL)\\r\\nBODEGA',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/G4A43020-UL.png','2024-01-03 17:29:01',NULL,NULL,'0','admin','2024-01-03 23:29:01','2024-01-03 23:29:01','G4A43020-UL','2024-01-03 17:29:01',NULL),
(862,0,NULL,'Amplificador crown de 8 canales 75W (CT875)\\r\\nTALLER',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/CT875.png','2024-01-03 17:33:26',NULL,NULL,'0','admin','2024-01-03 23:33:26','2024-01-03 23:33:26','CT875','2024-01-03 17:33:26',NULL),
(863,0,NULL,'Botonera lutron de 3 botones hibrida homeworksQS biscuit (HQRD-HN3BSRL-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/HQRD-HN3BSRL-BI.png','2024-01-03 18:21:09',NULL,NULL,'0','admin','2024-01-04 00:21:09','2024-01-04 00:21:09','HQRD-HN3BSRL-BI','2024-01-03 18:21:09',NULL),
(864,12,NULL,'Switch casseta lutron claro color blanco (DVRF-PKG1S-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276816960.png','2024-01-03 18:31:22',NULL,NULL,'0','admin','2024-01-04 00:31:22','2024-01-04 00:31:22','784276816960','2024-01-03 18:31:22',1234.00),
(865,0,NULL,'Botonera hibrida lutron homeworksQS de 6 botones color biscuit (HQRD-HN6BRL-BI) TALLER',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/HQRD-HN6BRL-BI.png','2024-01-03 18:35:37',NULL,NULL,'0','admin','2024-01-04 00:35:37','2024-01-04 00:35:37','HQRD-HN6BRL-BI','2024-01-03 18:35:37',NULL),
(866,0,NULL,'Botonera hibrida lutron homeworksQS de 3 botones color biscuit (HQRD-HN3S-BI) TALLER',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/HQRD-HN3S-BI.png','2024-01-03 18:36:44',NULL,NULL,'0','admin','2024-01-04 00:36:44','2024-01-04 00:36:44','HQRD-HN3S-BI','2024-01-03 18:36:44',NULL),
(867,0,NULL,'Botonera lutron de 5 botones esparcidos 3&2 botones color biscuit (HQRD-HN1RLD-BI)\\r\\nTALLER',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/HQRD-HN1RLD-BI.png','2024-01-03 18:38:13',NULL,NULL,'0','admin','2024-01-04 00:38:13','2024-01-04 00:38:13','HQRD-HN1RLD-BI','2024-01-03 18:38:13',NULL),
(868,8,NULL,'Subwoofer de montaje en superficie yamaha 10 pulgadas color blanco (VXS10SW)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/VXS10SW.png','2024-01-17 22:35:38',NULL,NULL,'0','admin','2024-01-18 04:35:38','2024-01-18 04:35:38','VXS10SW','2024-01-17 22:35:38',NULL),
(869,11,'PST-BC-V60','Tapa de ventilacion plastica pequeña para gabinete','Precision','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/PST-BC-V60.png','2024-01-17 22:43:40',NULL,NULL,'0','admin','2024-01-18 04:43:40','2024-01-18 04:43:40','PST-BC-V60','2024-01-17 22:43:40',1032.00),
(870,11,NULL,'Sensor de ocupacion/desocupacion con interruptor de multiples sitios lutron maestro color snow (MS-OPS6M2-DV-SW)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/027557984287.jpg','2024-01-17 23:00:42',NULL,NULL,'0','admin','2024-01-18 05:00:42','2024-01-18 05:00:42','027557984287','2024-01-17 23:00:42',1235.00),
(871,0,NULL,'Base metalica de rack strong serie custom 24U (SR-CUSTOMBASE-24)\\r\\nTALLER',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/SR-CUSTOMBASE-24.png','2024-01-17 23:10:04',NULL,NULL,'0','admin','2024-01-18 05:10:04','2024-01-18 05:10:04','SR-CUSTOMBASE-24','2024-01-17 23:10:04',NULL),
(872,3,NULL,'Inyector PoE estandar 802.3at Gigabit, 53 V-0.6 A-30W Ruijie (RGE130(GE))',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/RGE130-GE.png','2024-04-08 17:14:17',NULL,NULL,'0','admin','2024-04-08 23:14:17','2024-04-08 23:14:17','RGE130(GE)','2024-04-08 17:14:17',1304.00),
(873,4,'RE200','Repetidor,  Extensor de Cobertura W-iFi AC, 750 Mbps, doble banda 2.4 GHz y 5 GHz, con 1 puerto 10/100 Mbps','Tp-Link','RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/845973071875.jpg','2024-04-08 17:35:26',NULL,NULL,'0','admin','2024-04-08 23:35:26','2024-04-08 23:35:26','845973071875','2024-04-08 17:35:26',1310.00),
(874,12,'DECO-M5','Sistema Wi-Fi Mesh para todo el hogar decom 5 paquete de 3 discos','Tp-Link','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/845973080839.jpg','2024-04-08 18:34:00',NULL,NULL,'0','admin','2024-04-09 00:34:00','2024-04-09 00:34:00','845973080839','2024-04-08 18:34:00',1312.00),
(875,1,'TL-SG3210','Switch JetStream SDN Administrable 8 puertos 10/100/1000 Mbps + 2 puertos SFP, administracion centralizada  SDN  Tp-link  Omada','Tp-Link','SWITCH','Bodega General',NULL,NULL,'/img/productos/840030702464.jpg','2024-04-08 19:01:12',NULL,NULL,'0','admin','2024-04-09 01:01:12','2024-04-09 01:01:12','840030702464','2024-04-08 19:01:12',1303.00),
(876,15,'EAP245','Punto de acceso inalambrico de montaje en techo Gigabit de doble banda','Tp-Link','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/845973092962.png','2024-04-08 19:19:06',NULL,NULL,'0','admin','2024-04-09 01:19:06','2024-04-09 01:19:06','845973092962','2024-04-08 19:19:06',1309.00),
(877,0,'TL-SG2210','Switch PoE JetStream SDN Administrable 8 puertos 10/100/1000 Mbps + 2 puertos SFP, 8 puertos PoE, 61W, administración centralizada OMADA SDN','Tp-Link','SWITCH','Bodega General',NULL,NULL,'/img/productos/845973088668.png','2024-04-08 19:25:04',NULL,NULL,'0','admin','2024-04-09 01:25:04','2024-04-09 01:25:04','845973088668','2024-04-08 19:25:04',1309.00),
(878,7,'RG-NBS3200-24GT4XS-P','Switch Administrable PoE Capa 2+ Plus, con 24 puertos Gigabit PoE 802.3af/at + 4 SFP+ para fibra 10Gb 370W','Ruijie','SWITCH','Bodega General',NULL,NULL,'/img/productos/6971693270862.jpg','2024-04-08 20:35:17',NULL,NULL,'0','admin','2024-04-09 02:35:17','2024-04-09 02:35:17','6971693270862','2024-04-08 20:35:17',NULL),
(879,1,NULL,'Router balanceador de carga de banda ancha Tp-Link (TL-R480T+)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/845973040123.jpg','2024-04-08 20:42:12',NULL,NULL,'0','admin','2024-04-09 02:42:12','2024-04-09 02:42:12','845973040123','2024-04-08 20:42:12',1303.00),
(880,1,NULL,'Cerradura digital inteligente, zigbee, Ezviz con huella (CS-L2S)\\\\r\\\\n',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/6941545614700.jpg','2024-04-09 16:51:49',NULL,NULL,'0','admin','2024-04-09 22:51:49','2024-04-09 22:51:49','6941545614700','2024-04-09 16:51:49',1325.00),
(881,1,NULL,'Cerradura inteligente Yale con teclado numérico touch (YRD256)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/751299893858.png','2024-04-09 17:22:56',NULL,NULL,'0','admin','2024-04-09 23:22:56','2024-04-09 23:22:56','751299893858','2024-04-09 17:22:56',1325.00),
(882,1,NULL,'Monitor MODO manos libres soft touch de 7\\\'\\\' Urmet (1750/6)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/8021156064120.jpg','2024-04-09 17:35:24',NULL,NULL,'0','admin','2024-04-09 23:35:24','2024-04-09 23:35:24','8021156064120','2024-04-09 17:35:24',1324.00),
(883,3,NULL,'Camara bala redondeada Hikvision blanca 2MP colorvu de metal (DS-2CD1027G0-L)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6931847127749.png','2024-04-09 19:16:49',NULL,NULL,'0','admin','2024-04-10 01:16:49','2024-04-10 01:16:49','6931847127749','2024-04-09 19:16:49',1336.00),
(884,5,NULL,'Reproductor multimedia Roku Express 4k con control de voz color morado (3941RW)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/829610004747.png','2024-04-11 20:45:59',NULL,NULL,'0','admin','2024-04-12 02:45:59','2024-04-12 02:45:59','829610004747','2024-04-11 20:45:59',1358.00),
(885,1,NULL,'Puente bidireccional, 110V, Nevo Connect (NC-50 1037)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/1037.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','1037','2026-02-12 15:52:37',1288.00),
(886,2,NULL,'Control de ventilador para techo, 5 velocidades, Veker (7692)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/10633349031779.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','10633349031779','2026-02-12 15:52:37',1354.00),
(887,1,NULL,'Ecualizador bose (901 VI)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/17817506878.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','17817506878','2026-02-12 15:52:37',1130.00),
(888,1,NULL,'Soporte de Piso Universal Bose Negro Serie II (UFS-20)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/17817662581.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','17817662581','2026-02-12 15:52:37',NULL),
(889,1,NULL,'Soporte universal Bose Blanco (OMNIJEWEL)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/17817745772.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','17817745772','2026-02-12 15:52:37',NULL),
(890,1,NULL,'Barra de Sonido Bose Surround 700 Blanco 30W (700WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/17817806992.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','17817806992','2026-02-12 15:52:37',NULL),
(891,1,NULL,'Barra de Sonido Bose Smart soundbar 900 Blanco (900WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/17817829151.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','17817829151','2026-02-12 15:52:37',NULL),
(892,1,NULL,'Barra de Sonido Bose Surround 600 Negro (SB600BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/17817841467.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','17817841467','2026-02-12 15:52:37',NULL),
(893,1,NULL,'Sonos Bridge Blanco (BRIDGUS1)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/180501001147.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','180501001147','2026-02-12 15:52:37',1184.00),
(894,2,NULL,'Control de entretenimiento Apple TV 4K, 32GB (MGY2CL/A)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/190199532625.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','190199532625','2026-02-12 15:52:37',1334.00),
(895,1,NULL,'Amplificador de Audio Clase D 5 Canales 1600 W Kenwood (XM802-5)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/19048233851.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','19048233851','2026-02-12 15:52:37',1185.00),
(896,2,NULL,'Bocina inalambrica Apple HomePod  Mini, Wifi, Naranja (MJ2D3CL/A)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/194252271858.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','194252271858','2026-02-12 15:52:37',1339.00),
(897,4,NULL,'Control de entretenimiento Apple TV 4K, (64GBMN873E/A)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/194253468134.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','194253468134','2026-02-12 15:52:37',1334.00),
(898,5,NULL,'Control de entretenimiento Apple TV 4K, 128Gb (MN893E/A)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/194253468158.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','194253468158','2026-02-12 15:52:37',1334.00),
(899,3,NULL,'Fuente de poder para exterior, 12 Vcc, 2A, 1 salida de voltaje, Epcom Powerline (PL12DC2AW)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/2108STY036001.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','2108STY036001','2026-02-12 15:52:37',1343.00),
(900,4,NULL,'Montaje tipo U para puerta de vidrio, Accesspro (BU600NLED)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/22020012823.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','22020012823','2026-02-12 15:52:37',1325.00),
(901,1,NULL,'Protector de Cable en Puerta, Accesspro (ACCESSLOOP)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/240906006385.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','240906006385','2026-02-12 15:52:37',1329.00),
(902,2,NULL,'Mando a Distancia Universal con visualización a Color Negro, Nevo (NEVO-C2)',NULL,'CONTROL REMOTO','Bodega General',NULL,NULL,'/img/productos/25383020209.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','25383020209','2026-02-12 15:52:37',1288.00),
(903,22,NULL,'Dimmer lutron diva CL 150W color biscuit (DVSCCL-153P-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557003070.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557003070','2026-02-12 15:52:37',NULL),
(904,53,NULL,'Dimmer lutron diva CL 150W color media noche (DVSCCL-153P-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557003148.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557003148','2026-02-12 15:52:37',NULL),
(905,1,NULL,'Dimmer para bombilla LED 3 vias lutron color almendra claro (DVCL-153P-LA)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557003216.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557003216','2026-02-12 15:52:37',1264.00),
(906,4,NULL,'Teclado de sobremesa 5 botones lutron color snow (HQR-T5RL-SW)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557006750.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557006750','2026-02-12 15:52:37',1252.00),
(907,5,NULL,'Botonera lutron de 3 botones homeworks QS seeTouch color biscuit (HQWD-W3BSLR-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557012935','2026-02-12 15:52:37',1270.00),
(908,15,NULL,'Botonera lutron de 3 botones homeworksQS seeTouch color media noche (HQWD-W3BSRL-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557016490','2026-02-12 15:52:37',NULL),
(909,0,NULL,'Botonera lutron de 5 botones homeworks QS seeTouch color biscuit (HQWD-W5BRL-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557023238.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557023238','2026-02-12 15:52:37',1266.00),
(910,80,NULL,'Contacto duplex entrada tipo T lutron satin colors color taupe (SCR-20-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557029872.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557029872','2026-02-12 15:52:37',1262.00),
(911,3,NULL,'Dimmer lutron Homeworks color blanco (HRD-6D-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557050708.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557050708','2026-02-12 15:52:37',1244.00),
(912,2,NULL,'Transformador de pared 15V 1.5A lutron (T120-15DC-9-BL)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/27557055765.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557055765','2026-02-12 15:52:37',1239.00),
(913,2,NULL,'Modulo de interfaz 12V lutron (HWI-H48)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557061629.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557061629','2026-02-12 15:52:37',1255.00),
(914,5,NULL,'Switch compañero lutron maestro color negro (MA-AS-BL)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557082877.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557082877','2026-02-12 15:52:37',1272.00),
(915,1,NULL,'Control de ventilador 3 velocidades lutron diva color almendra clara (DVFSQ-F-HO-LA)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/27557119214.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557119214','2026-02-12 15:52:37',1264.00),
(916,30,NULL,'Clavija de reemplazo lutron color blanco (RP-FDU-10-WH-S)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/27557181068.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557181068','2026-02-12 15:52:37',1233.00),
(917,9,'CW-4-SS','Tapa de 4 ventanas, acero inoxidbale','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557223270.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557223270','2026-02-12 15:52:37',1223.00),
(918,7,'CW-5-SS','Tapa de 5 ventanas, linea Claro, acero inoxidable','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557223287.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557223287','2026-02-12 15:52:37',1222.00),
(919,20,NULL,'Frente de dimmer lutron color snow (RK-D-SW)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/27557227438.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557227438','2026-02-12 15:52:37',1214.00),
(920,3,NULL,'Frente de dimmer lutron color piedra (RK-D-ST)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/27557227483.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557227483','2026-02-12 15:52:37',1214.00),
(921,2,NULL,'Frente de switch lutron color almendra (RK-S-AL)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/27557227629.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557227629','2026-02-12 15:52:37',1219.00),
(922,0,NULL,'Switch Maestro unipolar, 8A, taupe, Lutron, Maestro (MSC-S8AM-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557256353.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557256353','2026-02-12 15:52:37',1271.00),
(923,8,NULL,'Dimmer compañero lutron satin colors color piedra dorada (MSC-AD-GS)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557265300.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557265300','2026-02-12 15:52:37',1269.00),
(924,1,NULL,'Placa de aterrizaje de cables para gabinete (HWI-WLB)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/27557363891.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557363891','2026-02-12 15:52:37',1255.00),
(925,3,NULL,'Placa de entrada de cierre de contacto interactivo (HWI-CCI-8)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/27557367004.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557367004','2026-02-12 15:52:37',1254.00),
(926,1,NULL,'Modulo de interfaz lutron 120V homeworks (HWI-MI-120)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/27557367028.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557367028','2026-02-12 15:52:37',1255.00),
(927,87,NULL,'Tablero de conexiones clemero lutron homeworksQS (QS-WLB)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/27557449762.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557449762','2026-02-12 15:52:37',1233.00),
(928,12,NULL,'Control de velocidad de ventilador 3 velocidades lutron color snow (DVSCFSQ-F-SW-L)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/27557484138.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557484138','2026-02-12 15:52:37',1264.00),
(929,3,NULL,'Contacto lutron doble nova-t color beige (MTR-20-GFCI-BE)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557503365.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557503365','2026-02-12 15:52:37',1273.00),
(930,2,NULL,'Modulo de poder relay 277V homeworks (HW-RPM-4R)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557507677.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557507677','2026-02-12 15:52:37',1273.00),
(931,5,'SC-1-BI','Tapa de 1 ventana,  satin niquel color biscuit','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557508131.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557508131','2026-02-12 15:52:37',1213.00),
(932,20,'SC-1-SW','Tapa de 1 ventana, color nieve snow','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557508285.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557508285','2026-02-12 15:52:37',1213.00),
(933,84,'SC-2-BI','Tapa de 2 ventanas, color biscuit','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557508469.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557508469','2026-02-12 15:52:37',1218.00),
(934,2,'CW-5-LA','Tapa de 5 ventanas, linea Claro, color almendra claro','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557514095.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557514095','2026-02-12 15:52:37',1227.00),
(935,4,'CW-6-LA','Tapa de 6 ventanas, linea Claro, color almendra claro','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557514491.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557514491','2026-02-12 15:52:37',1227.00),
(936,11,'CW-5-WH','Tapa de 5 ventanas ,Linea claro, Color blanco','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557516273.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557516273','2026-02-12 15:52:37',1222.00),
(937,1,NULL,'Dimmer doble Lutron maestro color blanco (MA-L3L3-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557554411.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557554411','2026-02-12 15:52:37',1272.00),
(938,4,NULL,'Kit de botones para HQR-T5RL-XX lutron (SIB-5BRL-AW-E)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/27557596817.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557596817','2026-02-12 15:52:37',1226.00),
(939,7,NULL,'Control de ventilador lutron skylark color blanco (SFSQ-LF-WH)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/27557670357.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557670357','2026-02-12 15:52:37',1269.00),
(940,1,NULL,'Control de velocidad de 3 vias lutron diva color almendra (DVFSQ-F-AL)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/27557691789.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557691789','2026-02-12 15:52:37',1264.00),
(941,1,'CW-1-BR','Tapa de 1 ventana sin tornillos visibles, Linea Claro, color Marron','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557692090.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557692090','2026-02-12 15:52:37',1213.00),
(942,5,'CW-4-WH','Tapa de 4 ventanas, color blanco','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557692236.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557692236','2026-02-12 15:52:37',1223.00),
(943,2,NULL,'Modulo de control lutron 120V homeworks (HW-RPM-4U-120)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/27557755153.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557755153','2026-02-12 15:52:37',1236.00),
(944,20,NULL,'Switch compañero lutron RadioRa2 color taupe (RD-RS-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557771269.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557771269','2026-02-12 15:52:37',1271.00),
(945,9,NULL,'Switch lutron radiora2 color blanco (RRD-8ANS-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557771825.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557771825','2026-02-12 15:52:37',1267.00),
(946,10,NULL,'Contacto duplex tamper resist lutron color snow (SCR-15-HDTR-SW)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557782050.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557782050','2026-02-12 15:52:37',1267.00),
(947,50,'NT-R3-NFB-WH','Tapa de 1 ventana, nova-t blanca','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557801089.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557801089','2026-02-12 15:52:37',1245.00),
(948,9,NULL,'Dimmer compañero lutron satin colors color eggshell (MCS-AD-ES)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557802819.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557802819','2026-02-12 15:52:37',1269.00),
(949,8,NULL,'Dimmer compañero lutron satin colors color stone/piedra (MSC-AD-ST)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557802970.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557802970','2026-02-12 15:52:37',1269.00),
(950,2,NULL,'Sensor de movimiento de muro blanco lutron (LRF2-OWLB-P-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557823562.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557823562','2026-02-12 15:52:37',1235.00),
(951,2,NULL,'Sensor de movimiento lutron de muro 90 grados color blanco (LRF2-OKLB-P-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557823586.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557823586','2026-02-12 15:52:37',1235.00),
(952,46,NULL,'Sensor de movimiento de techo lutron color blanco (LRF2-OCR2B-P-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557831970.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557831970','2026-02-12 15:52:37',1235.00),
(953,2,NULL,'Sensor de movimiento con switch unipolar lutron color negro (MS-OPS2-BL)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557982597.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557982597','2026-02-12 15:52:37',1235.00),
(954,8,NULL,'Frente de botonera  lutron 5 botones color biscuit (RKD-H5BRL-BI)',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','27557987714','2026-02-12 15:52:37',1247.00),
(955,1,'THC-B120-EVC','Camara tipo bala turboHD, 2MB, gran angular, exterior','Hikvision','VIDEO','Bodega General',NULL,NULL,'/img/productos/300512958.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','300512958','2026-02-12 15:52:37',1346.00),
(956,1,NULL,'Fuente de Poder Regulada 12V para 4 camaras hikvision (DS-2FA1225-C4)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/30083778069.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','30083778069','2026-02-12 15:52:37',1367.00),
(957,7,NULL,'Carcasa protectora de plastico para videoportero IP hikvision (DS-KABV6113-RS)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/305700600.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','305700600','2026-02-12 15:52:37',1368.00),
(958,4,'DS-KAB502-S1','Carcasa Protectora para Biometrico para DS-K1T502DBFWX-C / Protege de la Lluvia / Material SECC','Hikvision','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/305700902_1.png','2026-02-12 15:52:37',NULL,'','0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','305700902','2026-02-12 15:52:37',1368.00),
(959,2,NULL,'Pastilla electrica, 15A, Squared (QO215)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/30785901400302.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','30785901400302','2026-02-12 15:52:37',1280.00),
(960,0,NULL,'Bobina Legrand de UTP Cat 6 4 Pares cubierta azul 305 mt (BP-30076)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/3245060327552.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3245060327552','2026-02-12 15:52:37',NULL),
(961,68,'2867-BOX','Clavija de uso rudo eaton color amarillo','Eaton','CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/32664325809.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664325809','2026-02-12 15:52:37',1277.00),
(962,18,'2887-BOX','Contacto de uso rudo eaton color amarillo','Eaton','CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/32664326103.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664326103','2026-02-12 15:52:37',1277.00),
(963,36,'S2966-SP','Tapa de 1 ventana, para exterior color gris','Eaton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/32664449406.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664449406','2026-02-12 15:52:37',1225.00),
(964,112,'PJS26W','Tapa de 1 ventana, color blanco','Cooper','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/32664617270.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664617270','2026-02-12 15:52:37',1215.00),
(965,10,'5522-5EW','Chasis de 2 ventanas para RJ45, color blanco','Eaton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/32664629327.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664629327','2026-02-12 15:52:37',1221.00),
(966,74,'5521-5EW','Chasis 1 ventana para RJ45 jack, color blanco','Eaton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/32664654916.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664654916','2026-02-12 15:52:37',1220.00),
(967,6,NULL,'Switch triple eaton color blanco (7729W-SP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/32664664830.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664664830','2026-02-12 15:52:37',1276.00),
(968,1,NULL,'Luz LED con sensor de luz Cooper color blanco (TR7735W-BOX)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/32664727689.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664727689','2026-02-12 15:52:37',1276.00),
(969,1,NULL,'Luz LED con sensor de luz cooper color blanca (7737W-BOX)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/32664727764.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664727764','2026-02-12 15:52:37',1276.00),
(970,1,NULL,'Conector industrial de ultra-apreston, bipolar, trifilar, conexión a tierra, Blanco, Eaton (AHL520C)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/32664738401.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664738401','2026-02-12 15:52:37',1277.00),
(971,1,NULL,'Conector industrial de ultra-apreton, bipolar, trifilar, conexión a tierra, Blanco, Eaton (AHL530C)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/32664738425.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664738425','2026-02-12 15:52:37',1277.00),
(972,1,NULL,'Conector industrial de ultra-apreton, bipolar, trifilar, conexión a tierra, Blanco, Eaton (AHL630C)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/32664738463.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664738463','2026-02-12 15:52:37',1277.00),
(973,9,NULL,'Contacto duplex USB eaton color negro (TR7755BK-BOX)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/32664741494.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664741494','2026-02-12 15:52:37',1277.00),
(974,2,NULL,'Contacto de piso eaton bronce  (TR5249B)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/32664748691.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','32664748691','2026-02-12 15:52:37',1277.00),
(975,10,NULL,'Contactor para riel DIN, 480W, Schneider (LC1D12F7)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/3389110349221.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3389110349221','2026-02-12 15:52:37',1280.00),
(976,281,NULL,'Clema legrand con portafusible 6.3A 12AWG (37181)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/3414971445376.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3414971445376','2026-02-12 15:52:37',1240.00),
(977,3,NULL,'Tapa de 2 ventanas para intemperie color gris carlon (E98MG)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/34481190905.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','34481190905','2026-02-12 15:52:37',1220.00),
(978,8,NULL,'Camara Oculta en sensor de movimiento turbo HD, epcom (H7TURBO-720P)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/34596023723.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','34596023723','2026-02-12 15:52:37',1288.00),
(979,2,NULL,'Bocina tipo Columna Focal Kanta gris Obscuro (Kanta-N2)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/3544053695198.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3544053695198','2026-02-12 15:52:37',NULL),
(980,2,NULL,'Bocina para Exterior 2 vias Od Stone 8 Calcarie (OD-STONE8)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/3544054680001.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3544054680001','2026-02-12 15:52:37',NULL),
(981,2,NULL,'Soporte para Bocina Focal Theva Vestia (THEVA-VESTIA)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/3544059691484.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3544059691484','2026-02-12 15:52:37',1143.00),
(982,24,NULL,'Mini Modulo de Switch Azul, Shelly GEN3 (SHELLY-1-MINI-GEN3)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235261576.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235261576','2026-02-12 15:52:37',1278.00),
(983,3,NULL,'Micro modulo de switch inteligente 8A, Shelly Rojo (SHELLY-1PM-MINI-GEN3)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235261590.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235261590','2026-02-12 15:52:37',1278.00),
(984,6,NULL,'Micro modulo shelly reelevador, wiffi, bluetoo, Rojo (SHELLY-1PM)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235262016.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235262016','2026-02-12 15:52:37',1278.00),
(985,3,NULL,'Micro Moludo Dimmer 2, Verde, Shelly (SHELLY-DIMMER2)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235262184.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235262184','2026-02-12 15:52:37',1278.00),
(986,9,NULL,'Micro Modulo Wifi, luces led, Amarillo, Shelly (SHELLY-RGBW2)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235262191.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235262191','2026-02-12 15:52:37',1278.00),
(987,6,NULL,'Sensor de puerta y ventana shelly (SHELLY-DOOR/WINDOW-2-SENSOR)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235262221.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235262221','2026-02-12 15:52:37',1278.00),
(988,2,NULL,'Interruptor Atenuable de Pared con Conexión WIFI, Soporta Nest, Alexa, Nube P2P y control local, Shelly Plus (SHELLY-PLUS-WALL-DIMMER)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235262504.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235262504','2026-02-12 15:52:37',1278.00),
(989,3,NULL,'Sensor de Moviento de pared, Blanco, Shelly Plus (SHELLY-MOTION)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235262511.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235262511','2026-02-12 15:52:37',1278.00),
(990,0,NULL,'Micro Modulo Inteligente switch, 16A, Wifi, Bluetoo, Azul, Shelly (SHELLY-PLUS-1)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235265000.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235265000','2026-02-12 15:52:37',1278.00),
(991,8,NULL,'Micro modulo shelly reelevador, wifi, Bluetooth, Rojo (SHELLY-PLUS-1PM)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235265536.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235265536','2026-02-12 15:52:37',1278.00),
(992,5,NULL,'Micro modulo Wifi, luces Led, Amarillo, Shelly (SHELLY-PLUS-RGBW-PM)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235265635.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235265635','2026-02-12 15:52:37',1278.00),
(993,4,NULL,'Dimmer - Micro Modulo dimmer, wifi, Azul claro, Shelly Plus (SHELLY-PLUS-0-10V)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235265703.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235265703','2026-02-12 15:52:37',1278.00),
(994,7,NULL,'Shelly By Pass (SHELY-BYPASS)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/3800235266120.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235266120','2026-02-12 15:52:37',1278.00),
(995,1,NULL,'Sensor de puerta y venta, Blanco, Shelly Blu (SHELLY-BLU-DOOR-WINDOW-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235266601.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235266601','2026-02-12 15:52:37',1278.00),
(996,1,NULL,'Conector USB, wiffi, bluetooth, Shelly (GWF-KZ01)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/3800235266656.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235266656','2026-02-12 15:52:37',1278.00),
(997,6,NULL,'Sensor de movimiento, blanco, Inteligente, Shelly Blu (Shelly-BLU-MOTION)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235266700.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235266700','2026-02-12 15:52:37',1278.00),
(998,1,NULL,'Boton inteligente, wifi, bluetooth, Shelly (SHELLY-BL1-BUTTON-TOUGH-1)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/3800235266861.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235266861','2026-02-12 15:52:37',1278.00),
(999,5,NULL,'Modulo de dimmer para riel DIN Verde, Shelly PRO (SHELLY-PRO-DIMMER-2PM)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235268179.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235268179','2026-02-12 15:52:37',1278.00),
(1000,8,NULL,'Botonera de 4 botones, Blanco, Shellu Blu (SHELLY-BLU-RC-BUTTON-4-US-B)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235268315.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3800235268315','2026-02-12 15:52:37',1278.00),
(1001,4,NULL,'Micro modulo shelly doble switch color negro (SHELLY2.5)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3809511201855.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3809511201855','2026-02-12 15:52:37',1278.00),
(1002,2,NULL,'Sensor de temperatura y Humedad inalambrico, Azul, Shelly (SHELLY-H&T)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3809511201930.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3809511201930','2026-02-12 15:52:37',1278.00),
(1003,1,NULL,'Sensor de Temperetura y humedad, Blanco, Shelly (SHELLY-FLOOD)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3809511202005.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3809511202005','2026-02-12 15:52:37',1278.00),
(1004,1,NULL,'Medidor de Energia con contactor, Blanco, Shelly (SHELLY-EM)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3809511202104.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3809511202104','2026-02-12 15:52:37',1278.00),
(1005,6,NULL,'Transformado de corriente, 120A, Blanco, Shelly (CT313)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3809511202135.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3809511202135','2026-02-12 15:52:37',1278.00),
(1006,14,NULL,'Micro Modulo, wifi, multi switch, Naranja, Shelly (SHELLY-I3)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3809511202593.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3809511202593','2026-02-12 15:52:37',1278.00),
(1007,1,NULL,'Sensor de Moviento, Zipato (PH-PSM02.US)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3858890730432.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3858890730432','2026-02-12 15:52:37',1284.00),
(1008,1,NULL,'Hub, Zipatox, masrt home, zipato (ZIPABOX-SHC-V1S)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/3859892652128.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','3859892652128','2026-02-12 15:52:37',1284.00),
(1009,14,NULL,'Contactor auxiliar Sirius, 3 polos, 12A siemens (3RT2017-1AK61)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/4011209828216.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','4011209828216','2026-02-12 15:52:37',1280.00),
(1010,1,'SPLWP-W','Tapa de 3 ventanas impermeable','Mulberry Metal Products','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/4097643979.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','4097643979','2026-02-12 15:52:37',1220.00),
(1011,2,'SPMWP-W','Tapa de 4 ventanas impermeable color blanco','Mulberry Metal Products','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/4097669557.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','4097669557','2026-02-12 15:52:37',1220.00),
(1012,4,NULL,'Base de carga para escritorio para Ipad ,Gris, Iport (Basestation)',NULL,'CARGADOR','Bodega General',NULL,NULL,'/img/productos/41093701412.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','41093701412','2026-02-12 15:52:37',1334.00),
(1013,1,NULL,'Funda Protectora para Ipad 6 y 5 generacion, negro, Iport (AP.5SLEEVE)',NULL,'CARGADOR','Bodega General',NULL,NULL,'/img/productos/41093703003.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','41093703003','2026-02-12 15:52:37',1334.00),
(1014,0,NULL,'2M.2SLEEVE - Funda Protectora para Ipad Mini 1 y 2, Blanca, Iport',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/41093703058.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','41093703058','2026-02-12 15:52:37',NULL),
(1015,0,NULL,'Funda Protectora para Ipad 3 y 7 generacion, negro, Iport (70390)',NULL,'CARGADOR','Bodega General',NULL,NULL,'/img/productos/41093703904.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','41093703904','2026-02-12 15:52:37',NULL),
(1016,0,NULL,'Funda protectora para Ipad 7 y 8 generacion, Iport (72300)',NULL,'CARGADOR','Bodega General',NULL,NULL,'/img/productos/41093723001.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','41093723001','2026-02-12 15:52:37',NULL),
(1017,2,NULL,' Base de carga de pared para Ipad, blanca, Iport (7235)',NULL,'CARGADOR','Bodega General',NULL,NULL,'/img/productos/41093723513.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','41093723513','2026-02-12 15:52:37',1334.00),
(1018,3,NULL,'Base de carga para escritorio pro, para Ipad, negro, Iport (72352)',NULL,'CARGADOR','Bodega General',NULL,NULL,'/img/productos/41093723520.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','41093723520','2026-02-12 15:52:37',1334.00),
(1019,1,NULL,'Bocina Marina Sonance 86 Color Blanco (MARINER86WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/41093831560.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','41093831560','2026-02-12 15:52:37',NULL),
(1020,0,NULL,'Bocinas Marina Sonance 64 Color Negro (MARINER 64BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/41093931536.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','41093931536','2026-02-12 15:52:37',NULL),
(1021,0,NULL,'Bocina sonance Mariner Mx66 Blanca (MX66WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/41093936111.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','41093936111','2026-02-12 15:52:37',1039.00),
(1022,1,NULL,'Microfono Shure Dual (MX396/C)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/42406141413.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','42406141413','2026-02-12 15:52:37',1155.00),
(1023,1,NULL,'Modulo y controlador de sistema, Xantech Dragon Drop IR (DD4)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/42777311231.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','42777311231','2026-02-12 15:52:37',1303.00),
(1024,1,NULL,'Foco LED inteligente bluetooth y wifi color blanco cync (CLEDA199SD1/BSS2P)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/43168539555.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','43168539555','2026-02-12 15:52:37',1239.00),
(1025,1,NULL,'Par de bombillas inteligentes led cync (CLEDP3815CD1-BSS2P)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/43168539630.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','43168539630','2026-02-12 15:52:37',1239.00),
(1026,1,NULL,'Tira Led 32ft para exterior cync (SLDESTR36LCDODP/ST-1P)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/43168548458.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','43168548458','2026-02-12 15:52:37',1239.00),
(1027,1,NULL,' Foco LED inteligente a todo color bluetooth y wifi cync (paquete de 4) (CLEDA199C1/BS-4SIOC -)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/43168550505.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','43168550505','2026-02-12 15:52:37',1239.00),
(1028,5,NULL,'Gabinete Anclo de Acero IP66 Uso en Intemperie (400x400x 200mm) (PST-4040-20A)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/43223306.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','43223306','2026-02-12 15:52:37',NULL),
(1029,1,NULL,'Gabinete Prescision de Acero IP66 Uso en Intemperie (400x600x250mm) (PST-406025A)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/43223307.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','43223307','2026-02-12 15:52:37',1005.00),
(1030,1,NULL,'Kit de 4 bombillas 800 lumens, 10W, Blanca, Philips Hue (2AGBW3241312018AX)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/46677471989.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','46677471989','2026-02-12 15:52:37',1288.00),
(1031,1,NULL,'Router Balanceador de carga 5 puertos, Planet (MH-2300)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/4711605280846.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','4711605280846','2026-02-12 15:52:37',1303.00),
(1032,1,'FGSD-1022VHP','Switch PoE+  Distancia 250 Metros 8 Puertos + 2 Combo TP/SFP Gigabit y Pantalla LCD Para Monitoreo','Planet','SWITCH','Bodega General',NULL,NULL,'/img/productos/4711605281614.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','4711605281614','2026-02-12 15:52:37',1303.00),
(1033,3,NULL,'Panel de Iluminacion, Lite Puter (ECP-101)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/4716977280302.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','4716977280302','2026-02-12 15:52:37',1283.00),
(1034,3,NULL,'Panel de Iluminacion 6 Botones, Lite Puter (ECP-102)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/4716977280418.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','4716977280418','2026-02-12 15:52:37',1283.00),
(1035,4,NULL,'Panel de Iluminacion (ECP-202)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/4716977287394.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','4716977287394','2026-02-12 15:52:37',1283.00),
(1036,4,NULL,'Panel de Iluminacion 6 Botones, Lite Puter (ECP-110T)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/4716977288599.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','4716977288599','2026-02-12 15:52:37',1283.00),
(1037,20,NULL,'Modulo Antiparpadeo,  Lite Puter (Fliker-II)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/4716977301199.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','4716977301199','2026-02-12 15:52:37',1283.00),
(1038,2,NULL,'Estante Decorativo 60,32 cm Blanco Home Decorators (120866)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/49332106103.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','49332106103','2026-02-12 15:52:37',1139.00),
(1039,1,NULL,'Control de interface lutron grafik eye 100-277VAC-16A (GRX-TVI)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/50027557376155.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','50027557376155','2026-02-12 15:52:37',1236.00),
(1040,1,NULL,'Naim Uniti Atom HDMI 115V (40241)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/5060332107278.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','5060332107278','2026-02-12 15:52:37',1150.00),
(1041,49,NULL,'Multicontacto Panamax 8 Tomas Supresor de picos (M8-EX)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/50616007498.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','50616007498','2026-02-12 15:52:37',NULL),
(1042,7,NULL,'Receptor bluetooth para pared, Eissound (52808)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/52808010002.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','52808010002','2026-02-12 15:52:37',1359.00),
(1043,2,NULL,'Altavoces para Exterior Jamo Negro (I/O3A2)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/5709009937120.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','5709009937120','2026-02-12 15:52:37',1154.00),
(1044,2,NULL,'Timbre de puerta con cámara y detector de movimiento, Tuya (Bell 7S)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/58626628.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','58626628','2026-02-12 15:52:37',1328.00),
(1045,0,NULL,'YH-001 - Hub, Fibaro',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/5902701703103.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','5902701703103','2026-02-12 15:52:37',NULL),
(1046,870,NULL,'Fusible europeo steren 3A (45251)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/59651000100.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','59651000100','2026-02-12 15:52:37',1240.00),
(1047,1,NULL,'Caja de Cable control de iluminación blue OEM universal Lutron amarillo 1000 ft (GRX-CBL-346S)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/601446106174.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','601446106174','2026-02-12 15:52:37',1113.00),
(1048,1,NULL,'Kit de amplificador, Russound (A-D440)',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','612934534881','2026-02-12 15:52:37',1365.00),
(1049,5,NULL,'Multicontacto de 12 entradas Negro Panduit (P12B01M)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/613056263307.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','613056263307','2026-02-12 15:52:37',NULL),
(1050,2,NULL,'Distribuidor de energia 16 contactos instalacion vertical (P16B07M)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/613056263505.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','613056263505','2026-02-12 15:52:37',1060.00),
(1051,4,NULL,'Bobina de Cable para Aplicaciones en alarmas de Intrusión, Control de Acceso, Tv Porteros, Automatización, Audio y Voceo, 2x18 AWG Genesis  Blanco (21141101)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/619172070812.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','619172070812','2026-02-12 15:52:37',NULL),
(1052,1,NULL,'Control con Pantalla tactil Universal, Nevo (NEVO Q50)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/624000090802851.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','624000090802851','2026-02-12 15:52:37',1288.00),
(1053,1,NULL,'Camara de vigilancia, Wifi 2.4 / 5 G, 4MB tuya Bullet (Bullet 4S-Tuya-A2)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6299700.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6299700','2026-02-12 15:52:37',1328.00),
(1054,4,NULL,'Bocina de muro blanca para exterior ventura 4 KEF (VENTURA4WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/637203028786.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','637203028786','2026-02-12 15:52:37',1149.00),
(1055,4,NULL,'Altavoz para exteriores, color Blanco KEF (Ventura5WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/637203028793.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','637203028793','2026-02-12 15:52:37',NULL),
(1056,2,NULL,'Repisa de vidrio para Pared Vivo (Mount-SF011)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/641020646672.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','641020646672','2026-02-12 15:52:37',1077.00),
(1057,1,NULL,'No Break cyberpoower 500VA (OR500KCDRM1Ua)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/649532715015.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','649532715015','2026-02-12 15:52:37',1074.00),
(1058,13,'Thinos10','Cable de fibra óptica, audio digital 3m,','StarTech','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/65030802642.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','65030802642','2026-02-12 15:52:37',1330.00),
(1059,1,NULL,'Base de piso para Bocina Sonos One Blanco Flexxon (SF-FSWHT)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/652508362557.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','652508362557','2026-02-12 15:52:37',1114.00),
(1060,1,NULL,'Mini relevador TIS color blanco (ADS-3R-BUS)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/658921796238.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','658921796238','2026-02-12 15:52:37',1277.00),
(1061,1,NULL,'Botonera de 9 botones luna TIS color negro (LUNA-9GANGS)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/658921797204_1.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','658921797204','2026-02-12 15:52:37',1277.00),
(1062,1,NULL,'Dimmer de fase final 220V (DIM-TE-2CH-3A)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/658921798331.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','658921798331','2026-02-12 15:52:37',1277.00),
(1063,1,NULL,'Contacto Magnetico de puerta/ventana, blanco, TisBee (TIS-BEE-DW-SENS-1)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/658921800577.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','658921800577','2026-02-12 15:52:37',1277.00),
(1064,1,NULL,'Sensor de Moviento, Zigbee, Tisbee (TIS-BEE-PIR-1)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/658921800645.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','658921800645','2026-02-12 15:52:37',1277.00),
(1065,1,NULL,'Botonera de 2 ventanas, Blanca, Tis Bee (DIO-2G-SW-BEE)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/658921800713.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','658921800713','2026-02-12 15:52:37',1289.00),
(1066,1,NULL,'Botonera de 4 botones, Blanco, TisBee (DIO-4G-SC-BEE)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/658921800720.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','658921800720','2026-02-12 15:52:37',1277.00),
(1067,1,NULL,'Botón para salida,  Secco Alarm Enforcer (SD-7201GCPE1Q)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/676544011941.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','676544011941','2026-02-12 15:52:37',1363.00),
(1068,1,NULL,'Amplificador de Potencia 2 Canales 775W Negro Crown (XLS2502)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991001161.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','691991001161','2026-02-12 15:52:37',1121.00),
(1069,5,NULL,'Bocina Tipo Hongo Exterior JBL Verde (CONTROL85M)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991005077.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','691991005077','2026-02-12 15:52:37',NULL),
(1070,6,NULL,'Amplificador de Potencia Cdi 400 de 2 canales Crown 1200 watts (CDi 4000)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991013744.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','691991013744','2026-02-12 15:52:37',NULL),
(1071,2,NULL,'Procesador de Control de  Audio con pantalla, DBX DriveRack (DVXPA2-V-TW)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991033773.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','691991033773','2026-02-12 15:52:37',1316.00),
(1072,1,NULL,'Dispositivo de Control de Audio Zc-4V (Zc-4)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991400698.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','691991400698','2026-02-12 15:52:37',1116.00),
(1073,1,NULL,'Dispositivo de Control de Audio Zc-6V (Zc-6)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991400759.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','691991400759','2026-02-12 15:52:37',1116.00),
(1074,1,NULL,'Dispositivo de Control de Audio Zc-8 dbx (Zc-8)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991400797.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','691991400797','2026-02-12 15:52:37',1116.00),
(1075,1,NULL,'Sensor, blanco Sonoff (SNZB-06P)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6920075741858.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6920075741858','2026-02-12 15:52:37',1288.00),
(1076,1,NULL,'Sensor de Puerta y ventana, wifi, Sonoff (DW2-WIFI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6920075775891.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6920075775891','2026-02-12 15:52:37',1284.00),
(1077,1,NULL,'Reelevador Multifuncional, Orvibo (CM10ZW)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6928986700667.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6928986700667','2026-02-12 15:52:37',1292.00),
(1078,16,NULL,'Sensor de movimiento orvibo (SN11)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6928986701565.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6928986701565','2026-02-12 15:52:37',1292.00),
(1079,1,NULL,'Sensor de Puerta/Ventana Orvibo (SM11)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6928986701572.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6928986701572','2026-02-12 15:52:37',1292.00),
(1080,5,NULL,'Mini HUB Orvibo (UVS20ZW)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/6928986701978.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6928986701978','2026-02-12 15:52:37',1292.00),
(1081,5,NULL,'Hub Inteligente con control remoto universal, Orvibo (ALLONEPRO)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/6928986702364.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6928986702364','2026-02-12 15:52:37',1292.00),
(1082,1,NULL,'Alarma Wifi , Orvibo (VS30ZW)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6928986702555.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6928986702555','2026-02-12 15:52:37',1293.00),
(1083,3,NULL,'Cerradura T1 inteligente biometrica Wifi Zigbee para sistemas domoticos Orvibo (DOM-T1)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/6928986702814.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6928986702814','2026-02-12 15:52:37',1073.00),
(1084,1,NULL,'Termostato, Orvibo (TS31W5LZ)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/6928986702838.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6928986702838','2026-02-12 15:52:37',1292.00),
(1085,5,NULL,'Switch de Pared, gris, Orvibo (R20-W2Z)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6928986703446.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6928986703446','2026-02-12 15:52:37',1288.00),
(1086,2,NULL,'Mix switch 4 botones, Negro, Orvibo (T40S6Z)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6928986703699.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6928986703699','2026-02-12 15:52:37',1293.00),
(1087,1,NULL,'Foco Led Orvibo (DS20ZO7A)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6928986704339.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6928986704339','2026-02-12 15:52:37',1292.00),
(1088,1,'DS-K1T502DBFWX-C','Lector Biometrico con Teclado para Exterior Antivandálico IP65 & IK09 con función de Videoportero Multiapartamento, Huella, Tarjeta, Código QR, PIN o App HikConnect, Cámara 2 MP compatible con NVRs, Soporta biom','Hikvision','INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6931847103910.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6931847103910','2026-02-12 15:52:37',1367.00),
(1089,1,NULL,'Detector PIR Dual-Tech Exterior IP65 / COMPATIBLE CON CÁMARA DS-PDCM15PF-IR / Rango de Detección 15 mts / Ángulo de Cobertura de 90° / Movilidad de Lente en Eje de 180° / Inmunidad a Mascotas 40, Hikvision Ax Pro (DS-PDTT15AM-LM-WB)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6931847146764.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6931847146764','2026-02-12 15:52:37',1363.00),
(1090,1,NULL,'Modulo de cámara, Hikvision AX Pro (DS-PDCM15PF-IR)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6931847146788.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6931847146788','2026-02-12 15:52:37',1363.00),
(1091,6,'DS-KH6320-LE1(B)','Monitor IP Lite TOUCH 7 pulgadas para Videoportero IP, Vídeo en Vivo, PoE Estándar, Apertura Remota , Llamada Entre Monitores, Audio de dos vías, Hikvision','Hikvision','INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6931847163662.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6931847163662','2026-02-12 15:52:37',NULL),
(1092,0,NULL,'DS-2CV2041G2-IDW - Camara tipo bala, 4 MB,IP66,  Microfono y Bocina, HikVision',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/6931847168346.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6931847168346','2026-02-12 15:52:37',NULL),
(1093,1,NULL,'Detector de Humo Fotoeléctrico Inalámbrico para Panel de Alarma HIKVISION / Interior / Soporta Funcionalidad Autónoma / No compatible con el panel DS-PHA64-LP, Hikvision, AX Pro (DS-PDSMK-E-WD)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6931847172060.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6931847172060','2026-02-12 15:52:37',1367.00),
(1094,1,NULL,'NVR, 4K, 16 canales, 16 puertos POE, 2 bahías de disco duro, Hikvision (DS-7616NXI-K2/16P)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6931847175177.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-09-05 20:54:04','6931847175177','2026-02-12 15:52:37',1346.00),
(1095,3,NULL,'Camara de bala fija, 4 MB, ColorVu, Blanca, HikVision (DS-2CD1047G2-L)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6931847178529.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6931847178529','2026-02-12 15:52:37',1336.00),
(1096,1,NULL,'Cámara tipo tourret 4MB, AcuSense Lite, POE, (DS-2CD1347G2-L(UF))',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6931847178604.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6931847178604','2026-02-12 15:52:37',1346.00),
(1097,1,NULL,'DS-KIS604-P - Kit de Videoportero IP PoE Estandar con llamada a App de Smartphone (Hik-Connect) / Apertura con Tarjeta MIFARE / Frente de calle IK08 & IP65 / Soporta 2 puertas',NULL,'HARDWARE','Bodega General',NULL,NULL,NULL,'2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6931847179472','2026-02-12 15:52:37',NULL),
(1098,2,NULL,'Videporteo, altavoz, inalámbrico, plata negro, Akuvox (E12W)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6933964802370.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6933964802370','2026-02-12 15:52:37',1325.00),
(1099,3,'CPE2010','Punto de Acceso para Exterior, 2.4 GHZ 300MBPS,','Tp-Link','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/6935364071677.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6935364071677','2026-02-12 15:52:37',1309.00),
(1100,2,NULL,'Cámara tipo bala, 2 MB, IR, HikVision (DH-HAC-B1A21N)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6939554982361.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6939554982361','2026-02-12 15:52:37',1346.00),
(1101,30,NULL,'Montaje para Sensores PIR HIKVISION / Montaje en Pared o Techo / Ajuste Rotativo / Compatible con el PIR Cableado o Inalámbrico, HikVision (DS-PDB-IN)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/6941264033400.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6941264033400','2026-02-12 15:52:37',1340.00),
(1102,4,NULL,'Mini Camara de bala fija, 2 MB,ColorVu, Blanca,HikVision (DS-2CE10DF0T-F)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6941264044703.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6941264044703','2026-02-12 15:52:37',1336.00),
(1103,1,NULL,'Cámara tipo bala, 4 MB, Acusense, POE, micrófono integrado,Hikvision (DS-2CD2043G2-IU)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6941264063711.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6941264063711','2026-02-12 15:52:37',1346.00),
(1104,24,NULL,'Control remoto tipo llavero para alarma, Hikvision AX Pro (DS-PKF1-WB)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/6941264065074.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6941264065074','2026-02-12 15:52:37',1363.00),
(1105,2,NULL,'Sirena Inalámbrica Interior / 110 dB / Estrobo Color Azul, Hikvision Ax Pro (DS-PS1-I-WB)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6941264065173.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6941264065173','2026-02-12 15:52:37',1363.00),
(1106,20,'DS-PDMCS-EG2-WB','Contacto Magnético Inalámbrico, AX Pro, Slim/ Uso en Interior/ Led Indicador/ Tamper, Ax Pro','Hikvision','ALARMA','Bodega General',NULL,NULL,'/img/productos/6941264065791.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6941264065791','2026-02-12 15:52:37',1363.00),
(1107,2,NULL,'Memoria Ram Notebook Sodimm DDR4 8GB 2666Mhz Hikvision (HKED4082CDA1D0ZA1 8G)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/6941264070016.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6941264070016','2026-02-12 15:52:37',1334.00),
(1108,6,NULL,'Contacto Magnético Inalámbrico para Exterior Protección IP66 / No compatible con el panel DS-PHA64-LP, Hikvision Ax Pro (DS-PDMCX-WB)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6941264079965.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6941264079965','2026-02-12 15:52:37',1363.00),
(1109,1,NULL,'Panel de control para sistema de alarma, 96 zonas, Hikvision Ax Pro (DS-PWA96-M-WB)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6941264080169.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6941264080169','2026-02-12 15:52:37',1363.00),
(1110,5,NULL,' Sensor PIR de Movimiento con Cobertura de 360° Inalámbrico, Uso en Interior, Hikvision AX Pro DS-(PDCL12-EG2-WB)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/6941264082392.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6941264082392','2026-02-12 15:52:37',1362.00),
(1111,1,'IDS-7204HQHI-M1/S','DVR,Evita Falsas Alarmas DVR 4 Megapixel, 4 Canales TURBOHD + 2 Canales IP, 1 Bahía de Disco Duro, 1 Canal de Audio','Hikvison','VIDEO','Bodega General',NULL,NULL,'/img/productos/6941264090786.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6941264090786','2026-02-12 15:52:37',1339.00),
(1112,1,'DS-TDSB00-EKH','Radar Auxiliar de Detección de Caídas, Monitoreo de Frecuencia Cardiaca, Monitoreo de Respiración, Detección de Cuerpo Humano,  WiFi,  Compatible con HikCentral Professional, Rango de Detección hasta 2.7 Metros','Hikvision','ALARMA','Bodega General',NULL,NULL,'/img/productos/6942160412474.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6942160412474','2026-02-12 15:52:37',1368.00),
(1113,8,'IPC-T221H-C','Cámara tipo turret, 2MB, POE,','HiLook HikVision','VIDEO','Bodega General',NULL,NULL,'/img/productos/6942160415413.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6942160415413','2026-02-12 15:52:37',1341.00),
(1114,30,'DS-PDMC-EG2-WB','Contacto Magnético Inalámbrico + 2 ZONAS PARA AGREGAR SENSORES CABLEADOS, 3 en 1, Soporta 2 Zonas Cableadas, AX Pro','Hikvision','ALARMA','Bodega General',NULL,NULL,'/img/productos/6942160434551.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6942160434551','2026-02-12 15:52:37',1362.00),
(1115,0,'DS-3E1505P-EI/M','Switch Gigabit PoE+  Monitoreable, 4 Puertos 1000 Mbps PoE+ 1 Puerto 1000 Mbps Uplink, Configuración Nube Hik-PartnerPro, Modo Extendido hasta 300 Metros, 45 Watts \\r\\n','Hikvision','SWITCH','Bodega General',NULL,NULL,'/img/productos/6942160450094.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6942160450094','2026-02-12 15:52:37',1303.00),
(1116,11,NULL,'Camara de Bala fija, 6 MB, ColorVu, blanca, HikVision (DS-2CD2063G2-LI2U)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6942160462455.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6942160462455','2026-02-12 15:52:37',1336.00),
(1117,1,NULL,'Montaje de Pared con Caja de Conexión para Cámaras Domo IP (DS-1272ZJ-120B)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/6954273608309.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6954273608309','2026-02-12 15:52:37',1340.00),
(1118,2,NULL,'Montaje de pared para exterior compatible con camara tipo torre DS-2CD23XX, HikVision (DS-1273ZJ-130-TRL)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/6954273611453.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6954273611453','2026-02-12 15:52:37',1340.00),
(1119,0,NULL,'NVR, 16 canales IP, 4K, soporta camaras con AcuSense, 2 bahias de disco duro (DS-7616NI-K2)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6954273635220.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6954273635220','2026-02-12 15:52:37',1344.00),
(1120,1,NULL,'Montaje Tipo U para cerradura, HIkVision (DS-K4H258-U)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/6954273636029.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6954273636029','2026-02-12 15:52:37',NULL),
(1121,1,NULL,'Hub con contacto inteligente, Ezlo (2AMY9PLUGHB)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/6954273636067.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6954273636067','2026-02-12 15:52:37',1285.00),
(1122,2,NULL,'Cámara tipo bala, 6 MB, HikVision (DS-2CD2063G0-I)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6954273656683.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6954273656683','2026-02-12 15:52:37',3141.00),
(1123,1,'NVR-108MH-C/8P','NVR 8 megapixel (4K)  8 canales IP, 8 puertos POE +1 bahia de disco duro HDMI en 4K videoanaliticos','HiLook by Hikvision','VIDEO','Bodega General',NULL,NULL,'/img/productos/6954273658526.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6954273658526','2026-02-12 15:52:37',1339.00),
(1124,18,'DS-1280ZJ-S','Base metalica para exterior para tipo Domo, Turret y Bala IP66','Hikvision','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/6954273658670.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6954273658670','2026-02-12 15:52:37',1340.00),
(1125,9,NULL,'Base metalica para camara tipo domo o eyeball, uso exterior, sin tapa, HikVision (DZ-1280ZJ-DM8)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/6954273672676.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6954273672676','2026-02-12 15:52:37',1335.00),
(1126,5,NULL,'Base metalica para camara tipo domo, sin tapa, HikVision (DS-1280ZJ-DM55)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/6954273678708.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6954273678708','2026-02-12 15:52:37',1335.00),
(1127,0,NULL,'DS-2CD2024G1-IDW1 - Camara Mini bala, 4 MB, microfono integrado, Hikvision',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/6954273678951.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6954273678951','2026-02-12 15:52:37',NULL),
(1128,2,'HD132','Cable HDIMO de fibra óptica, 30 Mt,','Ugreen','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/6957303852178.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6957303852178','2026-02-12 15:52:37',1354.00),
(1129,2,'HD132-50219','Cable HDMI de 50 Metros por Fibra Óptica 4K@60Hz Fibra de 4 núcleos + Cobre estañado de 7 núcleos Compatible con HDMI 2.0 Alta velocidad 18 Gbps 3D HDR Carcasa de Zinc Premium','Ugreen','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/6957303852192.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6957303852192','2026-02-12 15:52:37',1357.00),
(1130,0,'70893','Cable de audio por fibra optica 3 mt,','Ugreen','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/6957303878932.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6957303878932','2026-02-12 15:52:37',1331.00),
(1131,4,NULL,'Interruptor inalambrico inteligente, Aqara (WXKG12LM)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6970504210301.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6970504210301','2026-02-12 15:52:37',1284.00),
(1132,1,NULL,'Sensor de puerta y ventanas, Aqara (MCCGQ11LM)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6970504210561.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6970504210561','2026-02-12 15:52:37',1284.00),
(1133,3,NULL,'Sensor de Temperatura y Humedad Zigbee, Aqara (WSDCGQ11LM)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6970504210585.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6970504210585','2026-02-12 15:52:37',1284.00),
(1134,1,NULL,'Modulo de control bidireccional On/Off, Aqara (LLKZMK11LM)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6970504210714.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6970504210714','2026-02-12 15:52:37',1284.00),
(1135,1,NULL,'Sensor de moviento, Aqara (FP2)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6970504219663.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6970504219663','2026-02-12 15:52:37',1284.00),
(1136,3,'RG-ES226GC-P','Switch Smart PoE con 24 puertos Gigabit PoE 802.3af/at + 2 SFP para fibra 1Gb, 370W, Negro,','Ruijie','SWITCH','Bodega General',NULL,NULL,'/img/productos/6971693270428.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6971693270428','2026-02-12 15:52:37',1316.00),
(1137,3,'RG-RAP6020(H)','Punto de Acceso para Exerior Wi-Fi 6, cuadrado Grande,','Ruijie','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/6971693273672.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6971693273672','2026-02-12 15:52:37',1306.00),
(1138,1,'RG-RAP1260','Punto de Acceso Mesh Wi-Fi 6 para interior en pared, hasta 512 usuarios ideal para Hotelería u Oficina.','Ruijie','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/6971693274860.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6971693274860','2026-02-12 15:52:37',1309.00),
(1139,1,'RG-AP820-L(V3)','Punto de acceso Enterprise Wi-Fi6, para interior, hasta 2.97 Gbps MU-MIMO 2x2 incluye puerto SFP,','Ruijie','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/6971693277847.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6971693277847','2026-02-12 15:52:37',1309.00),
(1140,1,'RG-NBS3100-24GT4SFP-P-V2','Switch Administrable PoE Capa 2+ Plus, con 24 puertos Gigabit PoE 802.3af/at + 4 SFP+ para fibra 10Gb,370W','Ruijie','SWITCH','Bodega General',NULL,NULL,'/img/productos/6971693278172.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6971693278172','2026-02-12 15:52:37',1301.00),
(1141,1,'RG-RAP1261','Punto de Acceso de pared WI-FI 6,','Ruijie','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/6971693279186.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6971693279186','2026-02-12 15:52:37',1309.00),
(1142,1,NULL,'Cámara dual, domo, ip, Tiandy (TC-C32RN)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6971849729794.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6971849729794','2026-02-12 15:52:37',1341.00),
(1143,4,NULL,'Lampara Inteligente para plafon, blanca, TopBand (DD20Z-L)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6972894790036.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6972894790036','2026-02-12 15:52:37',1282.00),
(1144,1,NULL,'Controlador IoT multifuncion milesight (UC300915M)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/6974225034475.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6974225034475','2026-02-12 15:52:37',1274.00),
(1145,1,NULL,'Controlador inteligente para iluminacion milesight (WS558915MPNLN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6974225037582.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6974225037582','2026-02-12 15:52:37',1274.00),
(1146,1,NULL,'Marco de Pared de Moes para 2 interruptures, Blanco, Serie Star Ring (MOZRSFRAME2)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/6974246475615.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6974246475615','2026-02-12 15:52:37',1288.00),
(1147,1,NULL,'Marco de pared de Moes para 3 interruptores, Blanco, Serie Star Ring (MOZRSFRAME3)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/6974246475622.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6974246475622','2026-02-12 15:52:37',1288.00),
(1148,1,NULL,'Cámara de Montaje en Parabrisas / 720p (1 Megapixel ) / Lente 2.1 mm / Conexión Tipo Aviación / Diseño Antivibración / Micrófono Integrado / 5 mts IR / Compatible con AE-DI5042-G4 / AE-DI2032-G40(B) / 30 cms de L, Hikvision (AE-VC143T-ITS)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6975248490255.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6975248490255','2026-02-12 15:52:37',1367.00),
(1149,1,NULL,'Detector de movimiento de pared color blanco LinknLink (EMOTION)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6975318230088.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6975318230088','2026-02-12 15:52:37',1289.00),
(1150,0,NULL,'Cargador tipo c, 20A, 65W (BT-DC-65W)',NULL,'CARGADOR','Bodega General',NULL,NULL,NULL,'2026-02-12 15:52:37',NULL,'','0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6976398200008','2026-02-12 15:52:37',1354.00),
(1151,1,NULL,'Camara tipo bala, Ip, POE, 2MB,Tiandy (TC-C321N)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6976642042293.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6976642042293','2026-02-12 15:52:37',1341.00),
(1152,1,NULL,'Cámara tipo domo IP 2MB, Tiandy (TC-C320N)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6976642042316.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','6976642042316','2026-02-12 15:52:37',1341.00),
(1153,3,'XD-CLRAIC2-12','Cable Rca Xd-clraic2-12 Para 2 Canales 12ft 3.7 M','JL Audio','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/699440904230.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','699440904230','2026-02-12 15:52:37',1359.00),
(1154,5,'XD-CLRAICY-1F2M','Cable Rca Y Xd-clraicy-1f2m  (1 Hembra A 2 Machos)','JL Audio','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/699440904339.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','699440904339','2026-02-12 15:52:37',1359.00),
(1155,1,NULL,'Bloque De Distribución 4 salidas Jl Audio (XD-FDBU-4)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/699440904629.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','699440904629','2026-02-12 15:52:37',1359.00),
(1156,1,NULL,'Receptor Bluetooth Marino Jl Audio Mbt-crx V2 Racers Lanchas (Mbt-crx V2)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/699440913690.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','699440913690','2026-02-12 15:52:37',1359.00),
(1157,1,NULL,'Bobina de Cable para audio Marino 16AWG JL Blanco 150 mts (XM-WHTSC16-500)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/699440916929.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','699440916929','2026-02-12 15:52:37',NULL),
(1158,1,'VISTA250FBP','Panel Hibrido de Incendio e Intrusión, 8 Particiones, Hasta 250 Zonas, Compatible con AlarmNet y Total Connect ()','AlarmNet','ALARMA','Bodega General',NULL,NULL,'/img/productos/701410948426.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','701410948426','2026-02-12 15:52:37',1030.00),
(1159,2,NULL,'Filtro de ruido electrico, Insteon (1626-10)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/718122395118.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','718122395118','2026-02-12 15:52:37',1289.00),
(1160,1,NULL,'Control con Pantalla Tactil Universal, Nevo (NEVO S70)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/724000300800818.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','724000300800818','2026-02-12 15:52:37',1288.00),
(1161,1,NULL,'Estacion de montaje para puerta, Savant (DOR-SM0B-00)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/728028292442.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','728028292442','2026-02-12 15:52:37',1275.00),
(1162,1,NULL,'Kit de montaje empotrado para estacion de puerta pesada, Savant (DOR-HDFLBRIC)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/728028292503.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','728028292503','2026-02-12 15:52:37',1275.00),
(1163,1,NULL,'Bombilla Inteligente Aplicación Controlada Color Temperatura, Savant (A19COLORLEDSMARTBULB)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/728028292657.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','728028292657','2026-02-12 15:52:37',1275.00),
(1164,2,NULL,'Detector PIR QUAD Digital, Crow (SWANQUAD)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/7290015112222.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7290015112222','2026-02-12 15:52:37',1367.00),
(1165,3,'1AHCD-SKY-V2','Control para Aire Aconcionado, wifi, Sky','Sensivo Air','AIRE ACONDICIONADO','Bodega General',NULL,NULL,'/img/productos/7290016037098.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7290016037098','2026-02-12 15:52:37',1284.00),
(1166,1,'SEN-AIR-CRL-01','Sensor de aire acondicionado','Sensibo Air','AIRE ACONDICIONADO','Bodega General',NULL,NULL,'/img/productos/7290016037173.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7290016037173','2026-02-12 15:52:37',1284.00),
(1167,1,NULL,'Sensor de Habitacion, Sensibo (RoomSensor)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/7290016037180.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7290016037180','2026-02-12 15:52:37',1284.00),
(1168,0,NULL,'K9-85MCW - Sensor de moviento, IPR, Sensonic',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/7290103301903.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7290103301903','2026-02-12 15:52:37',NULL),
(1169,1,NULL,'Mezcladora Profesional 24 Canales DMX (LC2412)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/736211579140.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','736211579140','2026-02-12 15:52:37',1311.00),
(1170,2,NULL,'Kit Subwoofer Inalámbrico Para Graves Potentes Negro, Klipsch (WA-2)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/743878023077.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','743878023077','2026-02-12 15:52:37',1361.00),
(1171,1,NULL,'SISTEMA DE ALTAVOCES DE CINE EN CASA KLIPSCH QUINTET 5.0 - NEGRO (QUINTET 5.0)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/743878024579.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','743878024579','2026-02-12 15:52:37',1181.00),
(1172,7,NULL,'Panel de Parcheo  patchpanel modular de 24 puertos 1UR panduit (CPP24WBLY)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/74983036554.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','74983036554','2026-02-12 15:52:37',1365.00),
(1173,1,NULL,'Patchpanel para rack panduit 48 puerto s (NK5EPPG48Y)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/74983055395.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','74983055395','2026-02-12 15:52:37',1051.00),
(1174,0,NULL,'Caja de montaje en superficie para 6 módulos, blanco, Panduit (NK6BXWH-AY)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/74983176250.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','74983176250','2026-02-12 15:52:37',1340.00),
(1175,2,'WMPHF2E','Organizador de Cables Horizontal PatchLink, Sencillo (Solo Frontal), Con Tapa Extendida, Para Rack de 19 pulgadas, 2 unidades','Panduit','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/74983376476.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','74983376476','2026-02-12 15:52:37',1051.00),
(1176,1,NULL,'Organizador de Cables Horizontal Panduit (WMP1E)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/74983376490.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','74983376490','2026-02-12 15:52:37',1051.00),
(1177,7,NULL,'Montaje de Pared para Panel de Parcheo de 12 puertos, Color Blanco, Panduit (WB89D)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/74983620531.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','74983620531','2026-02-12 15:52:37',1365.00),
(1178,10,NULL,'Tapa de 4 modulos panduit para exterior color gris (CFPWR4CIG)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/74983854455.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','74983854455','2026-02-12 15:52:37',1220.00),
(1179,5,NULL,'Panel de Parcheo Modular Keystone (Sin Conectores), de Montaje en Pared usando Accesorio WB89D, 12 Puertos, Panduit (NKFP12W)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/74983861149.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','74983861149','2026-02-12 15:52:37',1365.00),
(1180,20,'4PR/23AWG','Bobina de Cable Utp Ca6 mejorado Azul de Cobre TX6000 1000ft(305mt)','Panduit','CABLEADO','Bodega General',NULL,NULL,'/img/productos/74983878413.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','74983878413','2026-02-12 15:52:37',1114.00),
(1181,1,NULL,'Control remoto inteligente de Wifi + IR Cubo, Negro Gynoid orvibo (GY-309)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/7500326729102.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7500326729102','2026-02-12 15:52:37',1288.00),
(1182,13,'N1370-NS','Tapa ciega de 1 ventana, color negro','Estevez','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/7500449031359.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7500449031359','2026-02-12 15:52:37',1225.00),
(1183,1,'PPCI-5','Tapa ciega para pared beige','Volteck','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/7501206657751.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7501206657751','2026-02-12 15:52:37',1220.00),
(1184,1,NULL,'Central telefonica, 2 lineas, 8 Extenciones (305841)',NULL,'TELEFONIA','Bodega General',NULL,NULL,'/img/productos/7501400621954.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7501400621954','2026-02-12 15:52:37',1290.00),
(1185,1,NULL,'Pabx Bticino (335910)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/7501400622197.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7501400622197','2026-02-12 15:52:37',1295.00),
(1186,1,NULL,'Frente de calle 2 hilos, Bticino (307201)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/7501400652453.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7501400652453','2026-02-12 15:52:37',1290.00),
(1187,1,NULL,'Organizador de Cables Horizontal  thorsman de 19 pulgadas metalico (THORG2UR)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/7501509908062.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7501509908062','2026-02-12 15:52:37',1056.00),
(1188,1,NULL,'Gabinete Anclo Nema 4 -IP 66 de 40X30X20CM con Platina y Certificado (G4A0403020-UL)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/7501575313470.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7501575313470','2026-02-12 15:52:37',1000.00),
(1189,2,NULL,'ANCLO GABINETE METALICO CON PLATINA 40X40X20cm (G4A404020-UL)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/7501575313487.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7501575313487','2026-02-12 15:52:37',NULL),
(1190,5,NULL,'Lampara de uso Interior, Blanca, Construlita (RE1036BBCD)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/7501668581700.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7501668581700','2026-02-12 15:52:37',1282.00),
(1191,1,NULL,'Lampara para Plafon, 9W, blanca, Magg (LUNA9)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/7501671722008.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7501671722008','2026-02-12 15:52:37',1285.00),
(1192,1,NULL,'Charola para Rack NCS Jaguar (NCS-CMS-U2)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/750202223950467.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','750202223950467','2026-02-12 15:52:37',1050.00),
(1193,1,NULL,'Fuente de Poder PS500W (FG500WBX)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/7503026359242.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7503026359242','2026-02-12 15:52:37',1126.00),
(1194,0,'','Cargador cubo y cable tipo C, turbo 3A, 1Hora (GAR092)','','CARGADOR','Bodega General',NULL,NULL,'https://inventario.savicontrolhome.com/img/productos/7503027214571_4.png','2026-02-12 15:52:37',NULL,'','0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7503027214571','2026-02-12 15:52:37',1354.00),
(1195,2,NULL,'Gabinete Bticino 24 Modulos (F107N25D2K)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/7506181339951.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7506181339951','2026-02-12 15:52:37',NULL),
(1196,11,NULL,'Gabinete Bticino 36 Modulos (F107N36D2K)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/7506181347284.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7506181347284','2026-02-12 15:52:37',NULL),
(1197,7,NULL,'Bobina de Cable UTP CAT 6A, 1000 ft, Bticino Azul (100UC6AR-06)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/7506181349219.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7506181349219','2026-02-12 15:52:37',NULL),
(1198,2,NULL,'Contacto doble falla tierra eaton color blanco (MGF15W)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/7506229023996.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7506229023996','2026-02-12 15:52:37',1277.00),
(1199,3,NULL,'Luminario para Exterior, 30W, JWJ (JLRE-T30)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/7506235707309.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7506235707309','2026-02-12 15:52:37',1282.00),
(1200,4,NULL,'Caja directa para audio negro steren (BAF-006)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/7506250912870.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7506250912870','2026-02-12 15:52:37',1235.00),
(1201,0,NULL,'252-921 - Blaster IR, Blastbot negro',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/7506250935176.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7506250935176','2026-02-12 15:52:37',NULL),
(1202,10,'950-3004','Tira de Multicontacto corto de 9 Salidas 1.1mts blanco','Steren','CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/7506250972584.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','7506250972584','2026-02-12 15:52:37',1144.00),
(1203,1,NULL,'Cerradura Eléctrica de sobreponer, Philips (321 DC/ 321 DCB)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/751299034190.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','751299034190','2026-02-12 15:52:37',1320.00),
(1204,1,NULL,'Cerradura biométrica, + Hub, Yale  (YMF40)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/751299893674.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','751299893674','2026-02-12 15:52:37',1325.00),
(1205,1,NULL,'Bocina JobSite Gris (LSR-6)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/760514010918.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','760514010918','2026-02-12 15:52:37',1135.00),
(1206,26,NULL,'Kit de 4 magnetos para contactos 5816 de Honeywell (5899)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/781410001664.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','781410001664','2026-02-12 15:52:37',1340.00),
(1207,39,NULL,'Contacto magnético direccionable compatible con paneles vista con V-Plex color blanco, Honeywell (4939SN-WH)',NULL,'ALARMA','Bodega General',NULL,NULL,'/img/productos/781410007086.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','781410007086','2026-02-12 15:52:37',1361.00),
(1208,6,NULL,'Teclado LCD de palabras fijas compatible con paneles de la serie VISTA, Honeywell (6150)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/781410351943.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','781410351943','2026-02-12 15:52:37',1366.00),
(1209,2,NULL,'Detector de movimiento y microondas, alarma, intrusion, Honeywell (DT-7450)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/781410366657.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','781410366657','2026-02-12 15:52:37',1362.00),
(1210,1,NULL,'Kit de Panel de Alarma VISTA48LA con Gabinete, Batería y Transformador (VISTA48LA)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/781410804791.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','781410804791','2026-02-12 15:52:37',1364.00),
(1211,3,NULL,'Detector Direccionable de Humo Fotoeléctrico con Detector Térmico de Temperatura Fija, Honeywell (5193SDT)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/781410900387.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','781410900387','2026-02-12 15:52:37',1362.00),
(1212,28,NULL,'Sensor de Movimiento PIR, Honeywell (5800PIR)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/781410936799.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','781410936799','2026-02-12 15:52:37',1326.00),
(1213,1,NULL,'Protector de Descargas Atmosféricas, Diseñado Para Sistemas Fotovoltaicos, Uso en Corriente Directa, 0-500 Vcc (LA-302-DC)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/782381302057.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','782381302057','2026-02-12 15:52:37',1280.00),
(1214,3,NULL,'Servidor VPN, para mineria de criptomonedas (Deeper Network)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/782947376492.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','782947376492','2026-02-12 15:52:37',1358.00),
(1215,2,NULL,'Contacto duplex unipolar lutron claro color cafe (CAR-15-BR-S)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/784276013345.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276013345','2026-02-12 15:52:37',1242.00),
(1216,1,NULL,'Interruptor de 4 vias lutron claro color almendra claro (CA-4PS-LA-S)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/784276016407.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276016407','2026-02-12 15:52:37',1242.00),
(1217,4,NULL,'Frente de control de ventilador lutron color media noche (RKD-F-MN)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/784276024785.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276024785','2026-02-12 15:52:37',1246.00),
(1218,3,NULL,'Control de ventilador de techo lutron RadioRa2 color taupe (RRD-2ANF-TP)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/784276040457.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276040457','2026-02-12 15:52:37',1267.00),
(1219,5,NULL,'Control de ventilador de techo lutron RadioRa2 color blanco (RRD-2ANF-WH)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/784276040471.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276040471','2026-02-12 15:52:37',1264.00),
(1220,2,NULL,'Dimmer lutron maestro wireless color blanco (MRF2-6CL-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276055376.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276055376','2026-02-12 15:52:37',1272.00),
(1221,5,NULL,'Dimmer lutron radiora2 color media noche (RRD-6CL-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276066549.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276066549','2026-02-12 15:52:37',1267.00),
(1222,0,NULL,'Botonera pico de 4 botones lutron color blanco (PJ2-2BRL-GWH-L01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276067393.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276067393','2026-02-12 15:52:37',1229.00),
(1223,2,NULL,'Botonera lutron de 4 botones palladiom blanca cristalizada demo (RPU-4CWH-DEMO)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/784276068406.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276068406','2026-02-12 15:52:37',1224.00),
(1224,10,NULL,'Sensor de movimiento de tecnologia doble lutron maestro color blanco (MS-B102-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276086011.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276086011','2026-02-12 15:52:37',1235.00),
(1225,58,NULL,'Botonera pico lutron de 4 botones color blanco (PJ2-4B-GWH-L41)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276101622.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276101622','2026-02-12 15:52:37',NULL),
(1226,4,NULL,'Botonera pico pro de 4 botones lutron color blanco (PJ2-4B-WH-M21P)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276102384.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276102384','2026-02-12 15:52:37',1229.00),
(1227,1,'LWT-U-P-SN','Tapa de 1 ventana,Linea Palladiom, Satin Niquel','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,NULL,'2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276110167','2026-02-12 15:52:37',1227.00),
(1228,14,'LWT-U-P-QB','Tapa de 1 ventana palladiom laton antiguo','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/784276110174.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276110174','2026-02-12 15:52:37',1222.00),
(1229,8,'LWT-U-P-QZ','Tapa de 1 ventana, bronce antiguo','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/784276110181_1.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276110181','2026-02-12 15:52:37',1222.00),
(1230,19,'LWT-U-PP-LA','Tapa palladiom de 2 ventanas color almendra claro','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/784276110242.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276110242','2026-02-12 15:52:37',1227.00),
(1231,18,NULL,'Frente para botonera lutron 4 botones palladiom color negra (PBT-4W-BL-E)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/784276112307.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276112307','2026-02-12 15:52:37',1225.00),
(1232,20,NULL,'Fuente de poder de 24V lutron (QSPS-DH-1-75-H)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/784276151382.png','2026-02-12 15:52:37',NULL,'PACIFICA-1 | APARTADO:PROYECTO | FOLIO:PACIFICA-1 | VENCE:2026-07-02\r\nSE APARTAN 2pz. PARA PROYECTO PACIFICA-1','2','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276151382','2026-02-12 15:52:37',1239.00),
(1233,0,NULL,'LTR-15-TR-LA - Tomacorriente Doble,15A, Tamper Reist, Almendra Claro, Lutron,',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/784276154185.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276154185','2026-02-12 15:52:37',NULL),
(1234,18,NULL,'Contacto doble 15A lutron color negro (LTR-15-TR-BL)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276154222.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276154222','2026-02-12 15:52:37',1263.00),
(1235,4,NULL,'Contacto duplex USB 15A lutron color almendra claro (LTR-15-UBTR-LA)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276154406_1.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276154406','2026-02-12 15:52:37',1263.00),
(1236,1,NULL,'Botonera lutron de 4 botones palladiom satin niquel demo (RPU-4SN-DEMO)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276162722.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276162722','2026-02-12 15:52:37',1224.00),
(1237,1,NULL,'Botonera hibrida de 5 botones lutron RadioRa2 color taupe (RRD-HN5BRL-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276165402.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276165402','2026-02-12 15:52:37',1271.00),
(1238,3,NULL,'Botonera hibrida de 5 botones lutron homeworksQS color blanca (HQRD-HN1RLD-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276168267.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276168267','2026-02-12 15:52:37',1271.00),
(1239,6,NULL,'Botonera hibrida de 5 botones lutron RadioRa2 color blanca (RRD-HN4S-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276169813.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276169813','2026-02-12 15:52:37',1271.00),
(1240,1,NULL,'Control de ventilador 3 velocidades lutron diva color blanco (DVFSQ-LF-WH)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/784276226677.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276226677','2026-02-12 15:52:37',1264.00),
(1241,14,NULL,'Control de velocidad de ventilador lutron, silencioso de 3 velocidades con interruptor de luz unipolar color snow (DVSCFSQ-LF-SW)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/784276229890.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276229890','2026-02-12 15:52:37',1264.00),
(1242,7,NULL,'Control de velocidad de ventilador 3 velocidades lutron color taupe (DVSCFSQ-LF-TP)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/784276229913.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276229913','2026-02-12 15:52:37',1264.00),
(1243,6,NULL,'Interfaz de potencia lutron para riel din (LQSE-4T5-120-D)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276240031.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276240031','2026-02-12 15:52:37',1253.00),
(1244,16,NULL,'Botonera pico de 4 botones lutron color media noche (PJ2-4B-TMN-L31)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276240857.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276240857','2026-02-12 15:52:37',1229.00),
(1245,4,NULL,'Botonera pico lutron de 4 botones color biscuit (PJ2-4B-TBI-L31)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276241045.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276241045','2026-02-12 15:52:37',1224.00),
(1246,11,NULL,'Botonera pico de 4 botones lutron color biscuit (PJ2-4B-TBI-L41)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276241076.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276241076','2026-02-12 15:52:37',NULL),
(1247,38,NULL,'Botonera pico de 2 botones lutron color biscuit (PJ2-2B-TBI-S08)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276241359.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276241359','2026-02-12 15:52:37',1229.00),
(1248,2,NULL,'Dimmer lutron radiora2 color taupe (RRD-6ND-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276243902.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276243902','2026-02-12 15:52:37',1267.00),
(1249,36,NULL,'Dimmer lutron radiora2 pro color blanco (RRD-PRO-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276270465.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276270465','2026-02-12 15:52:37',1267.00),
(1250,2,NULL,'Sensor de movimiento lutron casseta wireless para muro 180 grados color blanco (PD-OSENS-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276276849.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276276849','2026-02-12 15:52:37',1235.00),
(1251,2,NULL,'Antena principal RadioRa3 lutron (RR-PROC3-KIT)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/784276285728.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276285728','2026-02-12 15:52:37',1243.00),
(1252,3,NULL,'Botonera hibrida lutron RadioRa3 de 4 botones sunnata color blanco (RRST-HN4B-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276304610.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276304610','2026-02-12 15:52:37',1243.00),
(1253,5,NULL,'Modulo dimmer lutron para riel din (LQSE-4T20-120-D)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276311106.jpg','2026-02-12 15:52:37',NULL,'PACIFICA-1 | APARTADO:PROYECTO | FOLIO:PACIFICA-1 | VENCE:2026-07-02\nSE APARTAN 4pz. PARA PROYECTO PACIFICA-1','4','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276311106','2026-02-12 15:52:37',1253.00),
(1254,29,NULL,'Contacto duplex lutron USB-C con entrada tipo C y A color taupe (SCR-15-ACTR-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276323710.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276323710','2026-02-12 15:52:37',1262.00),
(1255,28,NULL,'Botonera pico para ventilador lutron de 5 botones color biscuit (PJ2-3BRL-GWH-F01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276803175.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276803175','2026-02-12 15:52:37',NULL),
(1256,36,NULL,'Dimmer pro lutron sunnata color blanco Radiora3 (RRST-PRO-N-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276808545.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','784276808545','2026-02-12 15:52:37',1243.00),
(1257,40,'80301-SW','Tapa de 1 ventanas color blanco','Leviton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/78477159699.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','78477159699','2026-02-12 15:52:37',1215.00),
(1258,30,'803012-SW','Tapa de 4 ventanas color blanco','Leviton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/78477159880.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','78477159880','2026-02-12 15:52:37',1215.00),
(1259,8,'80309-SE','Tapa de 2 ventanas, color negro','Leviton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/78477159903.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','78477159903','2026-02-12 15:52:37',1220.00),
(1260,4,'80309-ST','Tapa de 2 ventanas color almendra claro','Leviton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/78477276808.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','78477276808','2026-02-12 15:52:37',1220.00),
(1261,55,'30301-ST','Tapa de 1 ventanas color almendra claro','Leviton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/78477277126.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','78477277126','2026-02-12 15:52:37',1220.00),
(1262,1,NULL,'Switch de 4 botones, blanco, Leviton (R02-D2SCS-1RW)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/78477472668.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','78477472668','2026-02-12 15:52:37',1284.00),
(1263,1,NULL,'Cubierta de caja electrica para exterior leviton color gris (05980-UCL)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/78477673928.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','78477673928','2026-02-12 15:52:37',1225.00),
(1264,3,NULL,'Tomacorriente doble, negro, Leviton (GFNT1-E)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/78477712573.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','78477712573','2026-02-12 15:52:37',1278.00),
(1265,1,NULL,'Alimentador 2 Hilos, Bticino (346050)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/8005543545324.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8005543545324','2026-02-12 15:52:37',1295.00),
(1266,40,'KW00','Tapon ciego, 1 modulo, blanco,','Bticino','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/8005543613245.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8005543613245','2026-02-12 15:52:37',1290.00),
(1267,1,NULL,'Placa de 3 Ventanas, Pixel, Bticino (KA480MW)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543613863.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8005543613863','2026-02-12 15:52:37',1290.00),
(1268,1,NULL,'Placa de 3 Ventanas, Acero, Bticino (KA4803ZG)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543613948.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8005543613948','2026-02-12 15:52:37',1290.00),
(1269,8,NULL,'Botonera de 2 botones, Bticino (K4652M2)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/8005543615157.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8005543615157','2026-02-12 15:52:37',1295.00),
(1270,3,NULL,'Reelevador cableado bticino (3584C)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/8005543616420.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8005543616420','2026-02-12 15:52:37',1289.00),
(1271,10,NULL,'Frente ON/Off blanco, Bticino (KW01MHAG)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/8005543619933.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8005543619933','2026-02-12 15:52:37',1290.00),
(1272,1,NULL,'Placa de 3 Ventanas, Optic, Bticino (KA480MM)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543620496.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8005543620496','2026-02-12 15:52:37',1290.00),
(1273,70,NULL,'Soporte 3 Modulos, negro, bticino (K4703L)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8005543649459.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8005543649459','2026-02-12 15:52:37',1291.00),
(1274,9,'KW33','Frente de tapa, color Blanco','Bticino','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/8005543669181.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8005543669181','2026-02-12 15:52:37',1290.00),
(1275,1,NULL,'Fuente de poder, Bticino (E56)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/8005543693629.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8005543693629','2026-02-12 15:52:37',1290.00),
(1276,35,NULL,'Caja Plastica para exterior, naranja, Bticino (22W35)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8012199108445.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8012199108445','2026-02-12 15:52:37',1291.00),
(1277,8,NULL,'Modulo de memoria para riel dim, Bticino (F425)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/8012199529646.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8012199529646','2026-02-12 15:52:37',1290.00),
(1278,1,NULL,'Controlador central  Hub, Legrand (LC7001)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/804428122180.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','804428122180','2026-02-12 15:52:37',1364.00),
(1279,6,NULL,'UFiber Módulo SFP, transceptor MiniGibic MonoModo 1.25 Gbps, distancia 3km, Simplex, un conector LC, incluye 2 tranceptores, Ubiquiti (UACC-OM-SM-1G-S-2)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/810010076984.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','810010076984','2026-02-12 15:52:37',1339.00),
(1280,4,NULL,'Cable de conexión directa DAC de 1 metro SFP+ 1/10Gbps, ideal para switches UniFi, Ubiquiti (UACC-DAC-SFP10-1M)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/810010077059.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','810010077059','2026-02-12 15:52:37',1330.00),
(1281,7,'USW-PRO-8-POE','Switch UniFi Pro 8 PoE Administrable Capa 3, 8 puertos GbE / (6) puertos POE+ af/at y (2) puertos POE++ 802.3bt (2) puertos SFP+ 10G, 120W POE','Ubiquit','SWITCH','Bodega General',NULL,NULL,'/img/productos/810084692585.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','810084692585','2026-02-12 15:52:37',1312.00),
(1282,10,'UK-ULTRA','Punto de Acceso UniFi UK Ultra Navaja Suiza WiFi 5, MU-MIMO 2x2, instalación flexible, antena sectorial integrada, (2) conectores RP-SMA para antenas omnidireccionales externas, para interior o exterior (IPX6)','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/810084693636.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','810084693636','2026-02-12 15:52:37',1314.00),
(1283,8,'USW-ULTRA','USW Ultra Switch de 8 Puertos GbE (7 Puertos PoE+ y 1 Puerto Entrada PoE++) con Opciones de Montaje Versátiles','Ubiquiti','SWITCH','Bodega General',NULL,NULL,'/img/productos/810084693698.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','810084693698','2026-02-12 15:52:37',1314.00),
(1284,12,'UACC-UK-ULTRA-OMNI-ANNTENA','Antena Omnidireccionales y stand para AP UK-Ultra (no incluido)','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/810084694091.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','810084694091','2026-02-12 15:52:37',1314.00),
(1285,9,NULL,'Router Unifi cloud, multiwan para balanceo, 1 puerto wan + 4 land, Ubiquiti (UCG-ULTRA)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'https://inventario.savicontrolhome.com/img/productos/810084695135.png','2026-02-12 15:52:37',NULL,'','0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','810084695135','2026-02-12 15:52:37',1344.00),
(1286,2,NULL,'Tira Led, 16ft neon LG (LIG-RO16INWS1-00)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/810136780246.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','810136780246','2026-02-12 15:52:37',1289.00),
(1287,3,NULL,'Adaptador PoE Ubiquiti de 24 VDC, 1.0 A con puerto Gigabit (POE2424W)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/810354021206.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','810354021206','2026-02-12 15:52:37',1339.00),
(1288,41,'UAP-AC-IW','Punto de Acceso de doble banda, Unifi, MI-MO 2x2 diseño placa de pared con dos puertos adicionales, hasta 100 usuarios Wi-Fi','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/810354025549.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','810354025549','2026-02-12 15:52:37',NULL),
(1289,2,NULL,'Mini Modulo Switch, blanco, AeoTec (ZW111-A)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/810667023881.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','810667023881','2026-02-12 15:52:37',1282.00),
(1290,4,'14-2FX','Bobina de Cable de Audio CAT6 550 MHZ 1000ft color blanco ()','Ice Cable','CABLEADO','Bodega General',NULL,NULL,'/img/productos/811885020164.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','811885020164','2026-02-12 15:52:37',NULL),
(1291,1,NULL,'Kit de Tranceptores (Baluns) RJ45, HD View (LTA1010)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/812008010160.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','812008010160','2026-02-12 15:52:37',1331.00),
(1292,1,NULL,'Modulador digital de video coxial, Zeevee (ZVPRO820-NA)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/812254010403.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','812254010403','2026-02-12 15:52:37',1302.00),
(1293,1,'NC2100MX','Nest Cam Cámara Ip Para Exterior 1080p','','VIDEO','Bodega General',NULL,NULL,'/img/productos/813917020692.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','813917020692','2026-02-12 15:52:37',1341.00),
(1294,2,NULL,'Botonera tipo Pico On/Off, Blanca, Insteon (2342-242)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922011616.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','813922011616','2026-02-12 15:52:37',1289.00),
(1295,5,NULL,'Micromodulo Dimmer ON/Off (2442-222)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922012705.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','813922012705','2026-02-12 15:52:37',1294.00),
(1296,17,NULL,'Botonera de 8 botones, blanca, Insteon (2334-222)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922013108.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','813922013108','2026-02-12 15:52:37',NULL),
(1297,3,NULL,'Botonera de 6 Botones, blanco insteon (2334-232)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922013177.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','813922013177','2026-02-12 15:52:37',1294.00),
(1298,1,NULL,'Hub, blanco, Insteon (2245-222)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/813922013498.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','813922013498','2026-02-12 15:52:37',1289.00),
(1299,2,NULL,'Switch on/off y Dimmer, Blanco, Insteon (PS01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922018325.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','813922018325','2026-02-12 15:52:37',1294.00),
(1300,4,NULL,'Dimmer de Perilla, blanco, Insteon (DS01)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/813922018332.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','813922018332','2026-02-12 15:52:37',1294.00),
(1301,1,NULL,'Caja fuerte electronica, 20x30x13, Honeywell (5101)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/814113015017.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','814113015017','2026-02-12 15:52:37',1301.00),
(1302,12,'N-SW','Switch POE 4 puertos NanoSwitch,','Ubiquiti','SWITCH','Bodega General',NULL,NULL,'/img/productos/817882021708.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','817882021708','2026-02-12 15:52:37',1314.00),
(1303,32,NULL,'Protector contra descargas electrostáticas Gen2 para equipos Ubiquiti para exterior (ETH-ST-G2)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/817882023900.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','817882023900','2026-02-12 15:52:37',1339.00),
(1304,2,'ES-10XP','EdgeSwitch de 8 puertos + 2 SFP','Ubiquiti','SWITCH','Bodega General',NULL,NULL,'/img/productos/817882025195.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','817882025195','2026-02-12 15:52:37',1303.00),
(1305,1,NULL,'Kit de Red con router, Ubiquiti (AFI-INS)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/817882025331.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','817882025331','2026-02-12 15:52:37',1310.00),
(1306,1,'USW-24-POE','Switch UniFi Capa 2, 24 Puertos Gigabit, 16 Puertos PoE+ 802.3af/at, 95W Total, 2 Puertos SFP 1G, Pantalla LCM, Sin Ventilador','Ubiquiti','SWITCH','Bodega General',NULL,NULL,'/img/productos/817882028523.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','817882028523','2026-02-12 15:52:37',1302.00),
(1307,3,NULL,'Soporte para altavoces vivo para sonos one sl y play1 color blanco (MOUNT-PLAY1W)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/818538021721.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','818538021721','2026-02-12 15:52:37',1185.00),
(1308,7,NULL,'Balun de audio digital, conector CAT5, Key Digital (KD-CAT5XST)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/819505002163.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','819505002163','2026-02-12 15:52:37',1353.00),
(1309,5,NULL,'Adptador de ethernet para Firestick Amazon (PS92LQ)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/840080511733.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','840080511733','2026-02-12 15:52:37',1358.00),
(1310,1,NULL,'Barra de Sonido Sonos ARC Blanco (ARCG1US1)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136800002.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','840136800002','2026-02-12 15:52:37',NULL),
(1311,5,NULL,'Bocina Portátil Sonos Move Blanca (MOVE1US1-WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136800620.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','840136800620','2026-02-12 15:52:37',1369.00),
(1312,1,NULL,'Sonos Roam Portátil Negro (ROAM1US1BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136801467.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','840136801467','2026-02-12 15:52:37',1185.00),
(1313,1,NULL,'Sonos Barra de Sonido Beam Gen 2 Blanco (BEAM2US1WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136802310.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','840136802310','2026-02-12 15:52:37',1191.00),
(1314,1,NULL,'Barra de Sonido Sonos Ray Blanco (RAYG1US1WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136802846.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','840136802846','2026-02-12 15:52:37',1180.00),
(1315,2,NULL,'Sonos Sub Mini Blanco Graves Profundos (SUBM1US1)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136804024.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','840136804024','2026-02-12 15:52:37',1167.00),
(1316,3,NULL,'Sonos Sub Mini, Subwoofer Inalámbrico Negro (SUBM1US1BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136804031.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','840136804031','2026-02-12 15:52:37',NULL),
(1317,3,NULL,'Sonos Era 100 Blanco (E10G1US1)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136805823.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','840136805823','2026-02-12 15:52:37',1184.00),
(1318,5,NULL,'Subwoofer Gen 4, Sonos, Color Negro (SUB4US1BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136812807.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','840136812807','2026-02-12 15:52:37',NULL),
(1319,0,NULL,'Dispositivo de streaming en 4K, Wi-Fi 6, control remoto por voz Alexa, Fire Stick (FIRETVMAX4K)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/840268907969.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','840268907969','2026-02-12 15:52:37',1358.00),
(1320,6,NULL,'Tomacorrientes Wattbox 10 Tomas Supresor de Picos (WB-400-CE-10)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/842822019828.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','842822019828','2026-02-12 15:52:37',1022.00),
(1321,10,NULL,'Multicontacto Vertical de 20 tomas WattBox Vertical Aluminio (WB-100-VPS-20)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/842822020565.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','842822020565','2026-02-12 15:52:37',1071.00),
(1322,4,NULL,'Repetidor IR con fuente de poder, Epison (Kit-IR-RPTR-1X4)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/842822021104.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','842822021104','2026-02-12 15:52:37',1328.00),
(1323,0,NULL,'Acondicionador de voltaje Wattbox 3 tomas (WB-200-IPCE-3)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/842822021531.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','842822021531','2026-02-12 15:52:37',1023.00),
(1324,1,NULL,'Switch de 8 Puertos, Araknis (AN-100-SW-R-8)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/842822025560.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','842822025560','2026-02-12 15:52:37',1303.00),
(1325,1,NULL,'Placa de pared metalica 1 ventana 2 puertos canon hembra wirepath (WP-XLR-102F-ALU)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/842822028936.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','842822028936','2026-02-12 15:52:37',1276.00),
(1326,6,NULL,'Placa de pared metalica 2 ventanas 4 puertos canon hembra wirepath (WP-XLR-204F-ALU)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/842822028943.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','842822028943','2026-02-12 15:52:37',1276.00),
(1327,2,NULL,' Kit de montaje en superficie, IR prueba de plasma/Led (EE-IR-RCVR-SM-KIT)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/842822034708.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','842822034708','2026-02-12 15:52:37',1323.00),
(1328,3,NULL,'Controlador y hub core5 control4 (C4-CORE5)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/842822053624.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','842822053624','2026-02-12 15:52:37',1279.00),
(1329,9,NULL,'Control Remoto inteligente con pantalla, Control4 Touch (C4-HALO-TS-BL)',NULL,'CONTROL REMOTO','Bodega General',NULL,NULL,'/img/productos/842822056687.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','842822056687','2026-02-12 15:52:37',1279.00),
(1330,9,NULL,'Kit control4, + Hub lite + control remoto Halo (C4-CORE-LITE-BL)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/842822063838.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','842822063838','2026-02-12 15:52:37',1279.00),
(1331,3,NULL,'Preamplificador para tocadiscos Mini Phono, preamplificador estéreo con entrada, salida RCA, bajo ruido, alimentado por adaptador de 12 V CC, ideal para vinilo y fonógrafos (PP777)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/842893145051.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','842893145051','2026-02-12 15:52:37',1354.00),
(1332,15,'AP6-PRO','Punto de Acceso Wi-Fi 6 de 6.3 Gbps para 350 Usuarios MU-MIMO 4X4, Adopción Fácil por Bluetooth, Para Interior, Montaje Rápido QuickMount™ para Techo o Pared, DPI para Bloqueo de Aplicaciones, Equipo de USA \\r\\n','Alta Labs','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/845882005497.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','845882005497','2026-02-12 15:52:37',1314.00),
(1333,4,'AP6-PRO-OUTDOOR','Punto de Acceso para exterior,','Alta Labs','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/845882005657.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','845882005657','2026-02-12 15:52:37',1314.00),
(1334,0,NULL,'Router 4puertos + 2 SFP, 10 Gbps, Alta Labs (Route-10)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/845882005701.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','845882005701','2026-02-12 15:52:37',1314.00),
(1335,2,NULL,'Convertidor Fibra Óptica WDM Monomodo 1 puerto RJ45 10/100 Mbps, 1 puerto SC/UPC 100 Mbps, Hasta 20 Km, Para su Funcionamiento Requiere el Modelo MC111CS, Plug and Play (MC112CS)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/845973030421.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','845973030421','2026-02-12 15:52:37',1359.00),
(1336,3,'EAP225 -Outdoor','Punto de Acceso tipo conejo para Exterior,','Tp-Link Omada','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/845973083571.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','845973083571','2026-02-12 15:52:37',1309.00),
(1337,20,'MGBLX','Transceptor Mini-GBIC SFP 1G LC Duplex para Fibra Monomodo 20Km','Planet','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/847690000732.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','847690000732','2026-02-12 15:52:37',1329.00),
(1338,6,NULL,'Modulo Convertidor de SFP a Ethernet 10/100/1000Mbit/s (MGB-GT)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/847690000947.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','847690000947','2026-02-12 15:52:37',1330.00),
(1339,0,NULL,'Transceptor mini-Gbic SFP 100Mbps LC 1310nm para fibra Mono Modo 20 Km, Planet (MFBM20)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/847690002163.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','847690002163','2026-02-12 15:52:37',1329.00),
(1340,6,NULL,'Inyector PoE IEEE 802.3at, Puertos Gigabit, Hasta 30 W de Salida PoE (Mid-span), Planet (POE-163)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/847690002712.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','847690002712','2026-02-12 15:52:37',1339.00),
(1341,3,NULL,'Bocina para Plafon 2 vias Revel (C763L)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/8485920003188.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8485920003188','2026-02-12 15:52:37',1155.00),
(1342,1,NULL,'Accesorio para rack strong 3U (SR-SHELF-3U)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/848882018511.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','848882018511','2026-02-12 15:52:37',1050.00),
(1343,1,NULL,'Control recoto pro con pantalla rti color negro (PRO24.Z)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/850180005070.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','850180005070','2026-02-12 15:52:37',1274.00),
(1344,1,NULL,'Kit de Control de Iluminacion  inteligente,  Medioon (MA1FB742071TF)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/853383007025.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','853383007025','2026-02-12 15:52:37',1285.00),
(1345,1,NULL,'Dimmer de 5 Botones, Zigbee, Zooz (ZN32)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/853478006322.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','853478006322','2026-02-12 15:52:37',1284.00),
(1346,2,NULL,'Timbre de Casa con Camara, Gris oscuro Auguts (AUG-AB01-M01-G01)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/853984006007.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','853984006007','2026-02-12 15:52:37',1284.00),
(1347,1,NULL,'Timbre de puerta inteligente con cámara, August (AUG-AB01-M01-S01)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/853984006014.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','853984006014','2026-02-12 15:52:37',1354.00),
(1348,1,NULL,'Cerradura inteligente, Gris Obscuro Augoust (AUG-SL02-M02-G02)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/853984006038.jpg','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','853984006038','2026-02-12 15:52:37',1284.00),
(1349,5,NULL,'Transformador de pared 24 VAC a 20VA, Revere (RT-2420-SL/M)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/85903036011.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','85903036011','2026-02-12 15:52:37',1280.00),
(1350,1,NULL,'Hub, controlador inteligente para dispositivos Zwave, zigbee, lutron, Hubitat (HC5-HABITAT)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/860453001807.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','860453001807','2026-02-12 15:52:37',1358.00),
(1351,4,NULL,'Control remoto con pantalla savant (PRO-REMOTE-X2)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/86318400253.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','86318400253','2026-02-12 15:52:37',1275.00),
(1352,9,NULL,'Host montable en rack savant (SHRS201)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/863184005892.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','863184005892','2026-02-12 15:52:37',1275.00),
(1353,1,NULL,'Switch V2 de 4 botones, negro, Savant (WIB-BKS106V2-00)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/863184007186.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','863184007186','2026-02-12 15:52:37',1275.00),
(1354,3,'AUG-AK01-M01-G01','Teclado Inteligente para Puerta, Gris Obscuro','August ','ALARMA','Bodega General',NULL,NULL,'/img/productos/863246000056.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','863246000056','2026-02-12 15:52:37',1284.00),
(1355,4,NULL,'Bocina para Exterior Sonos  Sonance Blanco (4S-OUTDRWW1)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/8717755776013.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8717755776013','2026-02-12 15:52:37',1175.00),
(1356,6,NULL,'Soporte para Pared Sonos para ARC Negro (ARCWWW1BLK)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/8717755776853.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8717755776853','2026-02-12 15:52:37',1188.00),
(1357,4,'E30MPWW1BLK','Soporte para Altavoces Negro Sonos Era 300','Sonos','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/8717755779397.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8717755779397','2026-02-12 15:52:37',1185.00),
(1358,2,'AC-WP2-W','Tapa de 2 ventanas control4 blanco','Control4','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/876389000562.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','876389000562','2026-02-12 15:52:37',1220.00),
(1359,1,NULL,'Botonera configurable, blanco, Control4 (C4-KC120277-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/876389010059.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','876389010059','2026-02-12 15:52:37',1279.00),
(1360,1,NULL,'Controlador automatico control4 (C4-CA1)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/876389022175.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','876389022175','2026-02-12 15:52:37',1279.00),
(1361,0,NULL,'Control 4 zigbee (C4-Z2IO)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/876389025022.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','876389025022','2026-02-12 15:52:37',1279.00),
(1362,3,NULL,'Control de entretenimiento y automatizacion, Control4 (C4-EA3-V2)',NULL,'CONTROL REMOTO','Bodega General',NULL,NULL,'/img/productos/876389032068.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','876389032068','2026-02-12 15:52:37',1279.00),
(1363,13,NULL,'Amplificador Sonos AMP Negro (AMPG1US1BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/878269007104.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','878269007104','2026-02-12 15:52:37',NULL),
(1364,1,NULL,'Bocina Sonos One Gen2 Blanco (ONEG2US1WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/878269007265.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','878269007265','2026-02-12 15:52:37',1164.00),
(1365,2,NULL,'Bocina Sonos One Gen2 Negro (ONEG2US1BLK)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/878269007272.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','878269007272','2026-02-12 15:52:37',1164.00),
(1366,1,NULL,'Sistema de Sonido CD/FM Micro Denon (D-M40S)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/883795003575.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','883795003575','2026-02-12 15:52:37',1142.00),
(1367,1,NULL,'Base dock universal Apple, incluye control remoto (MC746E-A)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/885909445431.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','885909445431','2026-02-12 15:52:37',1334.00),
(1368,3,NULL,'Router Wi-Fi Apple, Airport express 2da generacion, doble banda (A1392)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/885909462605.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','885909462605','2026-02-12 15:52:37',1334.00),
(1369,13,NULL,'Detector infrarrojo y microondas sin inmunidad a mascotas, Honeywell (DT8050)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/886618176647.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','886618176647','2026-02-12 15:52:37',NULL),
(1370,11,NULL,'Transformador de pared 9VAC a 25V honeywell (K10145WH-1)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/886618176821.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','886618176821','2026-02-12 15:52:37',1280.00),
(1371,1,NULL,'Tarjeta de ampliación de mensajes, Panasonic (KX-TE82491X)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/8887549187193.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8887549187193','2026-02-12 15:52:37',1323.00),
(1372,1,NULL,'Tarjeta de Mensaje de voz integrado de 2 canales (KX-TE82492X)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/8887549187209.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8887549187209','2026-02-12 15:52:37',1323.00),
(1373,2,NULL,'Tarjeta de identificación de llamante 3 puertos, Panasonic (XK-TE82494X)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/8887549291067.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','8887549291067','2026-02-12 15:52:37',1323.00),
(1374,2,NULL,'Placa de pared de 2 ventanas color blanco hellermantyton (FPIDUAL-W)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/89306205708.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','89306205708','2026-02-12 15:52:37',1215.00),
(1375,23,'HDM18BLUE150','Cable HDMI Blueberry 18, 1.5m','AudioQuets','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/92592015371.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','92592015371','2026-02-12 15:52:37',1353.00),
(1376,2,NULL,'Teclado para acceso de puerta, Rosslare (AC-Q44)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/949924030077.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','949924030077','2026-02-12 15:52:37',1363.00),
(1377,5,'30452W','Tapa de 2 ventanas, con cubierta impermeable','Mulberry Metal Products','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/95327311366.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','95327311366','2026-02-12 15:52:37',1220.00),
(1378,1,'30405W','Tapa de 5 ventanas impermeable color blanco','Mulberry Metal Products','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/95327311960.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','95327311960','2026-02-12 15:52:37',1220.00),
(1379,4,NULL,'Switch maestro unipolar 8A lutron maestro color blanco (MSC-S8AM-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/MSC-S8AM-WH.png','2026-02-12 15:52:37',NULL,NULL,'0','admin','2026-02-12 21:52:37','2026-02-12 21:52:37','MSC-S8AM-WH','2026-02-12 15:52:37',1272.00),
(1380,67,NULL,'Base para punto de acceso ruijie cuadrado chico (RG-AP180-MNT)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/RG-AP180-MNT.png','2026-02-13 20:39:48',NULL,NULL,'0','admin','2026-02-14 02:39:48','2026-02-14 02:39:48','RG-AP180-MNT','2026-02-13 20:39:48',1297.00),
(1381,1,NULL,'Cerradura derecha access pro metalica color beige (ACCESSRIMB)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/ACCESSRIMB.png','2026-02-13 20:54:00',NULL,NULL,'0','admin','2026-02-14 02:54:00','2026-02-14 02:54:00','ACCESSRIMB','2026-02-13 20:54:00',1230.00),
(1382,3,'RG-NBS3200-24GT4XS','Switch Administrable Capa 2+ Plus, con 24 puertos Gigabit, 4 puertos SFP+ para fibra 10Gb','Ruijie','SWITCH','Bodega General',NULL,NULL,'/img/productos/6971693270855.png','2026-02-13 21:15:02',NULL,NULL,'0','admin','2026-02-14 03:15:02','2026-02-14 03:15:02','6971693270855','2026-02-13 21:15:02',NULL),
(1383,2,NULL,'Soporte para antena starlink con brazo (OJXE32478)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/OJXE32478.png','2026-02-13 21:40:25',NULL,NULL,'0','admin','2026-02-14 03:40:25','2026-02-14 03:40:25','OJXE32478','2026-02-13 21:40:25',1066.00),
(1384,1,NULL,'Shelly dimmer para riel din verde 1pm (SHELLYPRODIMMER1PM)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/SHELLYPRODIMMER1PM.png','2026-02-13 21:55:04',NULL,NULL,'0','admin','2026-02-14 03:55:04','2026-02-14 03:55:04','SHELLYPRODIMMER1PM','2026-02-13 21:55:04',1278.00),
(1385,2,'SMOKEPLUSSMOKEALARM','Detector de humo shelly pro','Shelly','ALARMA','Bodega General',NULL,NULL,'/img/productos/SMOKEPLUSSMOKEALARM.png','2026-02-13 21:58:28',NULL,NULL,'0','admin','2026-02-14 03:58:28','2026-02-14 03:58:28','SMOKEPLUSSMOKEALARM','2026-02-13 21:58:28',1278.00),
(1386,1,NULL,'Modulo de dimmer shelly verde gen4 (SHELLYDIMMERGEN4US)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/SHELLYDIMMERGEN4US.png','2026-02-13 22:01:25',NULL,NULL,'0','admin','2026-02-14 04:01:25','2026-02-14 04:01:25','SHELLYDIMMERGEN4US','2026-02-13 22:01:25',1278.00),
(1387,2,NULL,'Mini dimmer shelly dali verde (SHELLYDALIDIMMERGEN3)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3800235261866.png','2026-02-13 22:03:35',NULL,NULL,'0','admin','2026-02-14 04:03:35','2026-02-14 04:03:35','3800235261866','2026-02-13 22:03:35',1278.00),
(1388,14,NULL,'Interruptor selector HDMI 5 puertos HDR IR remoto 4Kx2K HDMI Selector Box 5 en 1 Salida UHD 4K HDMI Conmutador compatible HDR 18Gps HDCP 2.2 Dolby Vision Ultra HD 3D 2160P 1080P Nowbotuch (T-305E)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/T-305E.png','2026-02-13 22:21:53',NULL,NULL,'0','admin','2026-02-14 04:21:53','2026-02-14 04:21:53','‎T-305E','2026-02-13 22:21:53',1304.00),
(1389,5,'NT-R3R3-NFB-QB','Tapa de 2 ventanas nova-t laton antiguo','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/NT-R3R3-NFB-QB.png','2026-02-13 22:23:16',NULL,NULL,'0','admin','2026-02-14 04:23:16','2026-02-14 04:23:16','NT-R3R3-NFB-QB','2026-02-13 22:23:16',1212.00),
(1390,4,'NT-R3R3R3-NFB-QB','Tapa de 3 ventanas nova-t laton antiguo','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/NT-R3R3R3-NFB-QB.png','2026-02-13 22:24:36',NULL,NULL,'0','admin','2026-02-14 04:24:36','2026-02-14 04:24:36','NT-R3R3R3-NFB-QB','2026-02-13 22:24:36',1212.00),
(1391,1,NULL,'Mini extensor HDMI, sobre cable de fibra óptica, monomodo steloproat (LMK-HF100DA-20-T)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/LMK-HF100DA-20-T.png','2026-02-13 22:26:58',NULL,NULL,'0','admin','2026-02-14 04:26:58','2026-02-14 04:26:58','LMK-HF100DA-20-T','2026-02-13 22:26:58',1304.00),
(1392,6,'NT-R3R3R3-FB-SB','Tapa de 3 ventanas nova-t laton satinado','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/NT-R3R3R3-FB-SB.png','2026-02-13 22:27:02',NULL,NULL,'0','admin','2026-02-14 04:27:02','2026-02-14 04:27:02','NT-R3R3R3-FB-SB','2026-02-13 22:27:02',1212.00),
(1393,4,'NT-R3R3-FB-SB','Tapa de 2 ventanas nova-t laton satinado','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/NT-R3R3-FB-SB.png','2026-02-13 22:28:36',NULL,NULL,'0','admin','2026-02-14 04:28:36','2026-02-14 04:28:36','NT-R3R3-FB-SB','2026-02-13 22:28:36',1212.00),
(1394,44,'CW-1-WH','Tapa de 1 ventana, claro blanco','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557692052.png','2026-02-13 22:36:27',NULL,NULL,'0','admin','2026-02-14 04:36:27','2026-02-14 04:36:27','27557692052','2026-02-13 22:36:27',NULL),
(1395,0,NULL,'Kit Extensor HDMI, 4K2K@60HZ, HDR 4:4:4, HDBaseT  120/150m , Salida de Audio, HDCP 2.2, IR Bidireccional (EPC-EHB150)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/EPC-EHB150.png','2026-02-13 22:50:04',NULL,NULL,'0','admin','2026-02-14 04:50:04','2026-02-14 04:50:04','EPC-EHB150','2026-02-13 22:50:04',1304.00),
(1396,3,NULL,'Splitter HDMI 1X4, Velocidad de trasmisión 48 Gbps, Resolución 8K, Salida de audio, HDCP 2.3, Epcom Titanium (TT314-8K)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/TT314-8K.png','2026-02-13 22:56:29',NULL,NULL,'0','admin','2026-02-14 04:56:29','2026-02-14 04:56:29','TT314-8K','2026-02-13 22:56:29',1304.00),
(1397,3,'RG-ES206GS-P','Switch Administrable con 4 puertos Gigabit PoE, 2 Uplink Gigabit y 1 Uplink Gigabit para Fibra SFP en combo, gestión gratuita desde la nube','Ruijie','SWITCH','Bodega General',NULL,NULL,'/img/productos/RG-ES206GS-P.png','2026-02-13 22:58:27',NULL,NULL,'0','admin','2026-02-14 04:58:27','2026-02-14 04:58:27','RG-ES206GS-P','2026-02-13 22:58:27',1304.00),
(1398,1,'RG-EG105G-V3','Router Balanceador con Función SD-WAN, Hasta 2 Servicios de Internet y hasta 100 clientes con desempeño de 600 Mbps,','Ruijie','RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/RG-EG105G-V3.png','2026-02-13 23:00:17',NULL,NULL,'0','admin','2026-02-14 05:00:17','2026-02-14 05:00:17','RG-EG105G-V3','2026-02-13 23:00:17',1304.00),
(1399,1,'SC-1-SR','Tapa de 1 ventana color sirena roja','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/SC-1-SR.png','2026-02-14 15:54:31',NULL,NULL,'0','admin','2026-02-14 21:54:31','2026-02-14 21:54:31','SC-1-SR','2026-02-14 15:54:31',1213.00),
(1400,9,NULL,'Frente de dimmer lutron color lima stone (RK-D-LS)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/RK-D-LS.png','2026-02-14 16:00:29',NULL,NULL,'0','admin','2026-02-14 22:00:29','2026-02-14 22:00:29','RK-D-LS','2026-02-14 16:00:29',1214.00),
(1401,6,NULL,'Frente de dimmer lutron color piedra moka (RK-D-MS)',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2026-02-14 16:01:21',NULL,NULL,'0','admin','2026-02-14 22:01:21','2026-02-14 22:01:21','RK-D-MS','2026-02-14 16:01:21',1214.00),
(1402,21,'PJS264W','Tapa de 4 ventanas color blanco','Cooper','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/PJS264W.png','2026-02-14 16:12:42',NULL,NULL,'0','admin','2026-02-14 22:12:42','2026-02-14 22:12:42','PJS264W','2026-02-14 16:12:42',1215.00),
(1403,17,'SI8831N','Tapa de 1 ventana color blanco','Enterlites','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/SI8831N.png','2026-02-14 16:14:26',NULL,NULL,'0','admin','2026-02-14 22:14:26','2026-02-14 22:14:26','SI8831N','2026-02-14 16:14:26',1215.00),
(1404,3,'NT-R3R3R3-NFB-QZ','Tapa de 3 ventanas nova-t bronce antiguo','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/NT-R3R3R3-NFB-QZ.png','2026-02-14 16:30:06',NULL,NULL,'0','admin','2026-02-14 22:30:06','2026-02-14 22:30:06','NT-R3R3R3-NFB-QZ','2026-02-14 16:30:06',1217.00),
(1405,3,'NT-R3-NFB-SN','Tapa de 1 ventana nova-t niquel satinado','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/NT-R3-NFB-SN.png','2026-02-14 16:31:18',NULL,NULL,'0','admin','2026-02-14 22:31:18','2026-02-14 22:31:18','NT-R3-NFB-SN','2026-02-14 16:31:18',1217.00),
(1406,12,'NT-C71-NFB-SW','Tapa nova-t para sensores color blanca','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,NULL,'2026-02-14 16:36:05',NULL,NULL,'0','admin','2026-02-14 22:36:05','2026-02-14 22:36:05','NT-C71-NFB-SW','2026-02-14 16:36:05',1217.00),
(1407,3,'USW-PRO-24','UniFi Switch USW-Pro-24, Capa 3 de 24 puertos Gigabit RJ-45 + 2 puertos 1/10G SFP+, pantalla informativa','Ubiquiti','SWITCH','Bodega General',NULL,NULL,'/img/productos/810010070562.png','2026-02-14 16:37:24',NULL,NULL,'0','admin','2026-02-14 22:37:24','2026-02-14 22:37:24','810010070562','2026-02-14 16:37:24',1307.00),
(1408,3,NULL,'Tapa para graficador lutron blanco nieve (QSGFP-SW)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/QSGFP-SW.png','2026-02-14 16:38:49',NULL,NULL,'0','admin','2026-02-14 22:38:49','2026-02-14 22:38:49','QSGFP-SW','2026-02-14 16:38:49',1217.00),
(1409,4,'NT-R3R3R3R3-NFB-QZ','Tapa de 4 ventanas nova-t bronce antiguo','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/NT-R3R3R3R3-NFB-QZ_1.png','2026-02-14 16:40:45',NULL,NULL,'0','admin','2026-02-14 22:40:45','2026-02-14 22:40:45','NT-R3R3R3R3-NFB-QZ','2026-02-14 16:40:45',1217.00),
(1410,63,'CW-2-WH','Tapa lutron claro de 2 ventanas color blanco','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/CW-2-WH.png','2026-02-14 17:17:29',NULL,NULL,'0','admin','2026-02-14 23:17:29','2026-02-14 23:17:29','CW-2-WH','2026-02-14 17:17:29',1218.00),
(1411,30,NULL,'Frente de switch lutron color piedra (RK-S-ST)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/RK-S-ST.png','2026-02-16 16:09:44',NULL,NULL,'0','admin','2026-02-16 22:09:44','2026-02-16 22:09:44','RK-S-ST','2026-02-16 16:09:44',1219.00),
(1412,30,NULL,'Frente de switch lutron color blanca (RK-S-WH)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/RK-S-WH.png','2026-02-16 16:22:20',NULL,NULL,'0','admin','2026-02-16 22:22:20','2026-02-16 22:22:20','RK-S-WH','2026-02-16 16:22:20',1219.00),
(1413,20,NULL,'Frente de control de ventilador lutron color taupe (RK-F-TP)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/RK-F-TP.png','2026-02-16 16:27:38',NULL,NULL,'0','admin','2026-02-16 22:27:38','2026-02-16 22:27:38','RK-F-TP','2026-02-16 16:27:38',1219.00),
(1414,3,NULL,'Router 5 puertos + 1 SFP Router Board Mikrotik (RB260GS)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/RB260GS.png','2026-02-16 16:59:10',NULL,NULL,'0','admin','2026-02-16 22:59:10','2026-02-16 22:59:10','RB260GS','2026-02-16 16:59:10',1310.00),
(1415,1,'RG-RAP6020G','Punto de Acceso Mesh Wi-Fi 6 Industrial para Exterior, 360°  Filtros Anti Interferencia y Auto Optimización con IA','Ruijie','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/RG-RAP6020G.png','2026-02-16 17:06:01',NULL,NULL,'0','admin','2026-02-16 23:06:01','2026-02-16 23:06:01','RG-RAP6020G','2026-02-16 17:06:01',1311.00),
(1416,1,NULL,'Chasis con lampara ketra para plafond (D3)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/D3.png','2026-02-16 17:28:08',NULL,NULL,'0','admin','2026-02-16 23:28:08','2026-02-16 23:28:08','D3','2026-02-16 17:28:08',1221.00),
(1417,1,'FPQUAD-FW','Chasis de 4 ventanas para RJ45, color blanco','HellermanTyton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/FPQUAD-FW.png','2026-02-16 17:30:46',NULL,NULL,'0','admin','2026-02-16 23:30:46','2026-02-16 23:30:46','FPQUAD-FW','2026-02-16 17:30:46',1221.00),
(1418,1,'FPITRIPLE-FW1','Chasis de 3 ventanas para RJ45, color blanco','HellermanTyton','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/FPITRIPLE-FW1.png','2026-02-16 17:32:45',NULL,NULL,'0','admin','2026-02-16 23:32:45','2026-02-16 23:32:45','FPITRIPLE-FW1','2026-02-16 17:32:45',1221.00),
(1419,2,'U7-OUTDOOR','Punto de Acceso para exterior, Ubiquiti, U7','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/U7-OUTDOOR.png','2026-02-16 17:53:55',NULL,NULL,'0','admin','2026-02-16 23:53:55','2026-02-16 23:53:55','U7-OUTDOOR','2026-02-16 17:53:55',1312.00),
(1420,2,NULL,'Caja para conexion para sobreponer blanca de plastico thorsman (TMK-51)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/TMK-51.png','2026-02-16 18:35:54',NULL,NULL,'0','admin','2026-02-17 00:35:54','2026-02-17 00:35:54','TMK-51','2026-02-16 18:35:54',1225.00),
(1421,3,'S16-POE','Switch Gigabit PoE+ Administrable, 16 puertos 10/100/1000 Mbps + 2 Puertos SFP Uplink, Presupuesto PoE hasta 120W,  Administración Gratuita en la Nube','Alta Labs','SWITCH','Bodega General',NULL,NULL,'/img/productos/S16-POE.png','2026-02-16 18:36:25',NULL,NULL,'0','admin','2026-02-17 00:36:25','2026-02-17 00:36:25','S16-POE','2026-02-16 18:36:25',1313.00),
(1422,5,'SC-BI-BI','Tapa ciega, color biscuit','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/SC-BI-BI.png','2026-02-16 18:46:55',NULL,NULL,'0','admin','2026-02-17 00:46:55','2026-02-17 00:46:55','SC-BI-BI','2026-02-16 18:46:55',1225.00),
(1423,22,'LWT-U-P-WH','Tapa de 1 ventana palladiom color blanco','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/LWT-U-P-WH.png','2026-02-16 18:58:14',NULL,NULL,'0','admin','2026-02-17 00:58:14','2026-02-17 00:58:14','LWT-U-P-WH','2026-02-16 18:58:14',1227.00),
(1424,8,'LWT-U-PP-WH','Tapa lutron de 2 ventanas palladiom color blanco','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/LWT-U-PP-WH.png','2026-02-16 18:58:38',NULL,NULL,'0','admin','2026-02-17 00:58:38','2026-02-17 00:58:38','LWT-U-PP-WH','2026-02-16 18:58:38',1227.00),
(1425,4,NULL,'Controlador para Dispositivos Alta Labs, Hasta 1000 dispositivos, Actualizaciones Automáticas, Configuración Aplicación Móvil (CONTROL)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/CONTROL.png','2026-02-16 19:09:05',NULL,NULL,'0','admin','2026-02-17 01:09:05','2026-02-17 01:09:05','CONTROL','2026-02-16 19:09:05',1315.00),
(1426,2,NULL,'Botonera lutron pico de 4 botones y subir/bajar color blanco (PJ2-3BRL-GWH-S03)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/PJ2-3BRL-GWH-S03.png','2026-02-16 19:34:18',NULL,NULL,'0','admin','2026-02-17 01:34:18','2026-02-17 01:34:18','PJ2-3BRL-GWH-S03','2026-02-16 19:34:18',1229.00),
(1427,3,'SC-6PF-ST','Marco de 6 puertos, satin colors color piedra','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/SC-6PF-ST.png','2026-02-16 19:38:47',NULL,NULL,'0','admin','2026-02-17 01:38:47','2026-02-17 01:38:47','SC-6PF-ST','2026-02-16 19:38:47',1230.00),
(1428,4,NULL,'Mini protector de voltaje con gabinete para equipos lutron (AL-D15P24DW)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/AL-D15P24DW.png','2026-02-16 19:48:23',NULL,NULL,'0','admin','2026-02-17 01:48:23','2026-02-17 01:48:23','AL-D15P24DW','2026-02-16 19:48:23',1234.00),
(1429,1,NULL,'Sensor de movimiento de techo lutron color almendra claro (LRF2-OCRB-P-LA)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/LRF2-OCRB-P-LA.png','2026-02-16 20:34:23',NULL,NULL,'0','admin','2026-02-17 02:34:23','2026-02-17 02:34:23','LRF2-OCRB-P-LA','2026-02-16 20:34:23',1235.00),
(1430,2,'AP6','Punto de Acceso para interior Wi-Fi 6','Alta Labs','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/AP6.png','2026-02-16 20:40:14',NULL,NULL,'0','admin','2026-02-17 02:40:14','2026-02-17 02:40:14','AP6','2026-02-16 20:40:14',1315.00),
(1431,3,'S8-POE','Switch Gigabit PoE+ Administrable 8 puertos 10/100/1000 Mbps (4 de ellos PoE+ 802.3af/at) Hasta 60W Administración nube gratuita,','Alta Labs ','SWITCH','Bodega General',NULL,NULL,'/img/productos/S8-Poe.png','2026-02-16 20:49:39',NULL,NULL,'0','admin','2026-02-17 02:49:39','2026-02-17 02:49:39','845882005541','2026-02-16 20:49:39',1315.00),
(1432,2,NULL,'Motor de cortina lutron roller 64 (QSSC-EDU-10-RPL)',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/QSSC-EDU-10-RPL.png','2026-02-16 20:54:42',NULL,NULL,'0','admin','2026-02-17 02:54:42','2026-02-17 02:54:42','QSSC-EDU-10-RPL','2026-02-16 20:54:42',1236.00),
(1433,1,NULL,'Motor de cortina lutron roller 100 (QSSC-EDU-100-RPL)',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/QSSC-EDU-100-RPL.png','2026-02-16 20:55:02',NULL,NULL,'0','admin','2026-02-17 02:55:02','2026-02-17 02:55:02','QSSC-EDU-100-RPL','2026-02-16 20:55:02',1236.00),
(1434,3,NULL,'Lampara led para plafond 4 pulgadas cync (CFIXCNRL4CRVD-OT)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/CFIXCNRL4CRVD-OT.png','2026-02-16 21:14:52',NULL,NULL,'0','admin','2026-02-17 03:14:52','2026-02-17 03:14:52','CFIXCNRL4CRVD-OT','2026-02-16 21:14:52',1239.00),
(1435,1,NULL,'Lampara LED tipo oblea 6 pulgadas bluetooth y wifi cync (B0C4X96KC3)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/B0C4X96KC3.png','2026-02-16 21:18:26',NULL,NULL,'0','admin','2026-02-17 03:18:26','2026-02-17 03:18:26','B0C4X96KC3','2026-02-16 21:18:26',1239.00),
(1436,7,NULL,'Fuente de poder de 35V lutron (QSPS-P1-1-35-V)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/QSPS-P1-1-35-V.png','2026-02-16 21:22:17',NULL,NULL,'0','admin','2026-02-17 03:22:17','2026-02-17 03:22:17','QSPS-P1-1-35-V','2026-02-16 21:22:17',1239.00),
(1437,25,'U6-Mesh-Pro','Punto de Acceso para interior Mesh','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'https://inventario.savicontrolhome.com/img/productos/U6-MESH-PRO.png','2026-02-16 21:22:30',NULL,'','0','admin','2026-02-17 03:22:30','2026-02-17 03:22:30','U6-MESH-PRO','2026-02-16 21:22:30',1317.00),
(1438,955,NULL,'Fusible europeo steren 5A (32325)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/32325.png','2026-02-16 21:26:08',NULL,NULL,'0','admin','2026-02-17 03:26:08','2026-02-17 03:26:08','32325','2026-02-16 21:26:08',1240.00),
(1439,44,NULL,'Conector mecánico SC-SC Simplex, para carretes de fibra óptica EZ Fiber (EF-UNION-SC)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/EF-UNION-SC.png','2026-02-16 21:37:49',NULL,NULL,'0','admin','2026-02-17 03:37:49','2026-02-17 03:37:49','EF-UNION-SC','2026-02-16 21:37:49',NULL),
(1440,1,NULL,'Chapa magnética 600Lb con Buzzer de alarma de puerta mantenida abierta / LED indicador de estado accesspro (MAG600BZ)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/MAG600BZ.png','2026-02-16 21:43:41',NULL,NULL,'0','admin','2026-02-17 03:43:41','2026-02-17 03:43:41','MAG600BZ','2026-02-16 21:43:41',1320.00),
(1441,8,NULL,'Caja Directa Doble con acoplador de señal no balanceada a balanceada, American (DB-42)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/DB-42.png','2026-02-16 21:46:15',NULL,NULL,'0','admin','2026-02-17 03:46:15','2026-02-17 03:46:15','DB-42','2026-02-16 21:46:15',1321.00),
(1442,14,'OD6025-24HSS','Ventilador para gabinetes 5CM, Orion, Knigth','Orion','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/OD6025-24HSS.png','2026-02-16 21:51:58',NULL,NULL,'0','admin','2026-02-17 03:51:58','2026-02-17 03:51:58','OD6025-24HSS','2026-02-16 21:51:58',1321.00),
(1443,2,NULL,'Portero frente de calle Panasonic (KX-T30865)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/KX-T30865.jpg','2026-02-16 22:05:25',NULL,NULL,'0','admin','2026-02-17 04:05:25','2026-02-17 04:05:25','KX-T30865','2026-02-16 22:05:25',1323.00),
(1444,98,NULL,'Tapon para clema legrand gris oscuro (37510)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/37510.png','2026-02-16 22:12:14',NULL,NULL,'0','admin','2026-02-17 04:12:14','2026-02-17 04:12:14','37510','2026-02-16 22:12:14',1240.00),
(1445,7,NULL,'Clema legrand sin portafusible blanca (37162)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/37162.png','2026-02-16 22:12:56',NULL,NULL,'0','admin','2026-02-17 04:12:56','2026-02-17 04:12:56','37162','2026-02-16 22:12:56',1240.00),
(1446,11,'NT-R3-NFB-BRA','Tapa lutron de 1 ventana nova-t laton dorado','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/NT-R3-NFB-BRA.png','2026-02-16 22:14:42',NULL,NULL,'0','admin','2026-02-17 04:14:42','2026-02-17 04:14:42','NT-R3-NFB-BRA','2026-02-16 22:14:42',1241.00),
(1447,27,'NT-R3R3-FB-QB','Tapa lutron de 2 ventanas nova-t laton dorado','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/NT-R3R3-NFB-BRA.png','2026-02-16 22:15:36',NULL,NULL,'0','admin','2026-02-17 04:15:36','2026-02-17 04:15:36','NT-R3R3-NFB-BRA','2026-02-16 22:15:36',1241.00),
(1448,2,NULL,'Botonera lutron de 6 botones homeworksQS (STWD-6BRL)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/STWD-6BRL.png','2026-02-16 22:38:08',NULL,NULL,'0','admin','2026-02-17 04:38:08','2026-02-17 04:38:08','STWD-6BRL','2026-02-16 22:38:08',1243.00),
(1449,1,NULL,'Mini Hub Yale (YALE-4940)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/YALE-4940.png','2026-02-17 15:39:06',NULL,NULL,'0','admin','2026-02-17 21:39:06','2026-02-17 21:39:06','YALE-4940','2026-02-17 15:39:06',1325.00),
(1450,72,NULL,'Clema legrand sin portafusible azul (037102)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/3414971444423.png','2026-02-17 15:45:00',NULL,NULL,'0','admin','2026-02-17 21:45:00','2026-02-17 21:45:00','3414971444423','2026-02-17 15:45:00',1240.00),
(1451,47,'LP-SM-LCUPC','Conector de Fibra Óptica Pre-pulido Re-terminable Monomodo LC/UPC 900um, 2.0 mm, 3.0 mm','Linkedpro','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/LP-SM-LCUPC_1.png','2026-02-17 15:58:31',NULL,NULL,'0','admin','2026-02-17 21:58:31','2026-02-17 21:58:31','LP-SM-LCUPC','2026-02-17 15:58:31',1325.00),
(1452,2,NULL,'Cerradura derecha accesspro metalica color negra (BL-ACCESSRIMB)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/BL-ACCESSRIMB.png','2026-02-17 16:01:19',NULL,NULL,'0','admin','2026-02-17 22:01:19','2026-02-17 22:01:19','BL-ACCESSRIMB','2026-02-17 16:01:19',1230.00),
(1453,43,'FASC-UPC','Conector Rápido de Instalación en Campo, Monomodo, SC/UPC, pre-pulido, re-terminable, ideal para Aplicaciones FTTx','FiberHome','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/FASC-UPC.png','2026-02-17 16:02:54',NULL,NULL,'0','admin','2026-02-17 22:02:54','2026-02-17 22:02:54','FASC-UPC','2026-02-17 16:02:54',NULL),
(1454,1,NULL,'Contrachapa Universal ideal para cerraduras Estándar/ Sensor/ UL/ Domarkava (6514-LMKN-32D)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/6514-LMKN-32D.png','2026-02-17 16:06:35',NULL,NULL,'0','admin','2026-02-17 22:06:35','2026-02-17 22:06:35','6514-LMKN-32D','2026-02-17 16:06:35',1325.00),
(1455,50,NULL,'Contacto magnético para puertas y ventanas color blanco, GAP: 33 mm, S-fire (SF-2031)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/SF-2031.png','2026-02-17 16:26:39',NULL,NULL,'0','admin','2026-02-17 22:26:39','2026-02-17 22:26:39','SF-2031','2026-02-17 16:26:39',1328.00),
(1456,30,NULL,'Contacto magnético delgado/ Uso en puertas y ventanas, Color blanco, GAP: 70 mm, Sfire (SF-2041)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/SF-2041.png','2026-02-17 16:28:04',NULL,NULL,'0','admin','2026-02-17 22:28:04','2026-02-17 22:28:04','SF-2041','2026-02-17 16:28:04',1328.00),
(1457,2,NULL,'Teléfono IP empresarial para 4 Líneas SIP con 2 pantallas LCD, 6 teclas BLF/DSS, puertos Gigabit y conferencia de 3 vías, PoE Fanvil (X4G)',NULL,'TELEFONIA','Bodega General',NULL,NULL,'/img/productos/X4G.png','2026-02-17 16:34:03',NULL,NULL,'0','admin','2026-02-17 22:34:03','2026-02-17 22:34:03','X4G','2026-02-17 16:34:03',1328.00),
(1458,38,NULL,'Contacto magnético para puertas y ventanas color café, GAP: 33mm, S-fire (SF-2031BR)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/SF-2031BR.png','2026-02-17 16:45:00',NULL,NULL,'0','admin','2026-02-17 22:45:00','2026-02-17 22:45:00','SF-2031BR','2026-02-17 16:45:00',1328.00),
(1459,1,'SNAP 11S-Tuya/ WT/H1','Cámara de vigilancia','Tuya','VIDEO','Bodega General',NULL,NULL,'/img/productos/SNAP-11S-Tuya.png','2026-02-17 16:54:03',NULL,NULL,'0','admin','2026-02-17 22:54:03','2026-02-17 22:54:03','SNAP 11S-Tuya','2026-02-17 16:54:03',1328.00),
(1460,1,'HESC15M','Cable HDMI Fino 15 mt V1.4 ()','','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/HESC15M.jpg','2026-02-17 17:07:52',NULL,NULL,'0','admin','2026-02-17 23:07:52','2026-02-17 23:07:52','HESC15M','2026-02-17 17:07:52',1328.00),
(1461,3,NULL,'Contacto Metálico para cortina, compatible con paneles DSC/Risco/Bosch, Seco-Alarm  (SM-226LQ)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/SM-226LQ.png','2026-02-17 17:11:03',NULL,NULL,'0','admin','2026-02-17 23:11:03','2026-02-17 23:11:03','SM-226LQ','2026-02-17 17:11:03',1329.00),
(1462,1,NULL,'Splitter HDMI de 2 Puerto, Epcom Titanium\\\\r\\\\n(ET312-UV2.0)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/ET312-UV2-0.png','2026-02-17 17:15:38',NULL,NULL,'0','admin','2026-02-17 23:15:38','2026-02-17 23:15:38','ET312-UV2.0','2026-02-17 17:15:38',1329.00),
(1463,25,'SCAPCMCS10','Conector Mecánico Monomodo SC/APC Pre-pulido 3mm,','Tempo','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/SCAPCMCS10.png','2026-02-17 17:32:28',NULL,NULL,'0','admin','2026-02-17 23:32:28','2026-02-17 23:32:28','SCAPCMCS10','2026-02-17 17:32:28',1329.00),
(1464,40,'FTTH','Conectores SC/UPC Incrustados de Fibra Óptica para Lechtas de Fibra Óptica','Tp-Link','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/FTTH.png','2026-02-17 17:38:16',NULL,NULL,'0','admin','2026-02-17 23:38:16','2026-02-17 23:38:16','FTTH','2026-02-17 17:38:16',1329.00),
(1465,8,'FTTH 10','Conector rápido de fibra óptica SC APC monomodo pulido UPC rápido ','Linkedpro','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/FTTH-10.png','2026-02-17 17:41:33',NULL,NULL,'0','admin','2026-02-17 23:41:33','2026-02-17 23:41:33','FTTH 10','2026-02-17 17:41:33',1329.00),
(1466,2,NULL,'GPON/GEPON Splitter UPC (1 x 8 PLC Splitter, Wavelength 1230 ~ 1650 nm), Planet (EPL-SPT-8)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/EPL-SPT-8.png','2026-02-17 17:48:05',NULL,NULL,'0','admin','2026-02-17 23:48:05','2026-02-17 23:48:05','EPL-SPT-8','2026-02-17 17:48:05',1329.00),
(1467,0,'8LC-Duplex','Panel de conexión de fibra, 8 LC Duplex SFM,UPC, 10Gtek','H!Fiber.com','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/8LC-Duplex.png','2026-02-17 17:51:23',NULL,NULL,'0','admin','2026-02-17 23:51:23','2026-02-17 23:51:23','8LC-Duplex','2026-02-17 17:51:23',1329.00),
(1468,2,NULL,'Dimmer lutron homeworks color blanco (HRD-6ND-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557050890.png','2026-02-17 18:15:19',NULL,NULL,'0','admin','2026-02-18 00:15:19','2026-02-18 00:15:19','27557050890','2026-02-17 18:15:19',1244.00),
(1469,2,'HQWT-T-HW-BL-A','Termostato con 5 botones palladiom, color negro','Lutron','AIRE ACONDICIONADO','Bodega General',NULL,NULL,'/img/productos/784276227094.png','2026-02-17 18:43:19',NULL,NULL,'0','admin','2026-02-18 00:43:19','2026-02-18 00:43:19','784276227094','2026-02-17 18:43:19',1257.00),
(1470,4,NULL,'Panel de parcheo 110, Blindado STP, 8 puertos, Cat6, LinkedPro (LPPP628)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/LPPP628.png','2026-02-17 18:56:38',NULL,NULL,'0','admin','2026-02-18 00:56:38','2026-02-18 00:56:38','LPPP628','2026-02-17 18:56:38',1330.00),
(1471,4,'LPPP605A','Patch Panel de impacto, 110, Blindado, STP, 24 puertos, Cat6','Linkedpro','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/LPPP605A.png','2026-02-17 18:59:32',NULL,NULL,'0','admin','2026-02-18 00:59:32','2026-02-18 00:59:32','LPPP605A','2026-02-17 18:59:32',1330.00),
(1472,10,NULL,'Contacto duplex falla tierra lutron 15A color blanco (LTR-15-TR-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/LTR-15-TR-WH.png','2026-02-17 19:13:02',NULL,NULL,'0','admin','2026-02-18 01:13:02','2026-02-18 01:13:02','LTR-15-TR-WH','2026-02-17 19:13:02',1263.00),
(1473,0,'FTB506','Caja Terminal de Fibra Óptica (Roseta) con 2 acopladores SC/APC, blanca','FiberHome','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/FTB506.png','2026-02-17 19:14:05',NULL,NULL,'0','admin','2026-02-18 01:14:05','2026-02-18 01:14:05','FTB506','2026-02-17 19:14:05',1330.00),
(1474,20,NULL,'Contacto duplex falla tierra lutron 15A color almendra claro (LTR-15-TR-LA)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/LTR-15-TR-LA.png','2026-02-17 19:14:06',NULL,NULL,'0','admin','2026-02-18 01:14:06','2026-02-18 01:14:06','LTR-15-TR-LA','2026-02-17 19:14:06',1263.00),
(1475,12,NULL,'Dimmer lutron 0 - 10V diva color taupe (DVSCSTV-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/DVSCSTV-TP.png','2026-02-17 19:15:14',NULL,NULL,'0','admin','2026-02-18 01:15:14','2026-02-18 01:15:14','DVSCSTV-TP','2026-02-17 19:15:14',1263.00),
(1476,61,NULL,'Control de ventilador de 3 velocidades lutron color media noche (DVSCFQ-F-MN-L)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/DVSCFQ-F-MN.png','2026-02-17 19:18:23',NULL,NULL,'0','admin','2026-02-18 01:18:23','2026-02-18 01:18:23','DVSCFQ-F-MN','2026-02-17 19:18:23',1264.00),
(1477,17,'LP-SFP-BD-10G-5','Transceptores Ópticos Industriales Bidireccionales SFP+ (Mini-Gbic) / Monomodo 1270 & 1330 nm / 10 Gbps','Linkedpro','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/LP-SFP-BD-10G-5.png','2026-02-17 19:20:40',NULL,NULL,'0','admin','2026-02-18 01:20:40','2026-02-18 01:20:40','LP-SFP-BD-10G-5','2026-02-17 19:20:40',1330.00),
(1478,11,NULL,'Control de velocidad de ventilador lutron, silencioso de 3 velocidades con interruptor de luz unipolar color biscuit (DVSCFSQ-F-BI-L)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/27557484046.png','2026-02-17 19:21:28',NULL,NULL,'0','admin','2026-02-18 01:21:28','2026-02-18 01:21:28','27557484046','2026-02-17 19:21:28',1264.00),
(1479,2,'R5AC-PT-MT','Punto de Acceso punto a multipunto, Ubiquiti Networks rocket','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/R5AC-PT-MT.png','2026-02-17 20:13:14',NULL,NULL,'0','admin','2026-02-18 02:13:14','2026-02-18 02:13:14','R5AC-PT-MT','2026-02-17 20:13:14',1333.00),
(1480,1,NULL,'Contacto duplex tamper resist lutron color media noche (SCR-15-HDTR-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/SCR-15-HDTR-MN.png','2026-02-17 20:13:56',NULL,NULL,'0','admin','2026-02-18 02:13:56','2026-02-18 02:13:56','SCR-15-HDTR-MN','2026-02-17 20:13:56',1267.00),
(1481,3,'SCR-15-HDTR-BI-L','Contacto duplex con nariz tamper resist, color biscuit','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/SCR-15-HDTR-BI.png','2026-02-17 20:14:52',NULL,NULL,'0','admin','2026-02-18 02:14:52','2026-02-18 02:14:52','SCR-15-HDTR-BI','2026-02-17 20:14:52',1267.00),
(1482,4,NULL,'Control de ventilador de techo lutron homeworks color blanco (HQRD-2ANF-WH)',NULL,'VENTILACION','Bodega General',NULL,NULL,'/img/productos/HQRD-2ANF-WH.png','2026-02-17 20:15:40',NULL,NULL,'0','admin','2026-02-18 02:15:40','2026-02-18 02:15:40','HQRD-2ANF-WH','2026-02-17 20:15:40',1267.00),
(1483,16,'DS-1280ZJ-S','Base metalica para camara tipo domo, turret y bala, color blanca','Hikvision','HARDWARE','Bodega General',NULL,NULL,'/img/productos/DS-1280ZJ-XS.png','2026-02-17 20:46:56',NULL,NULL,'0','admin','2026-02-18 02:46:56','2026-02-18 02:46:56','DS-1280ZJ-S','2026-02-17 20:46:56',1335.00),
(1484,17,NULL,'Base metalica para camara tipo domo, ip66, sin tapa, HikVision (DS-1280ZJ-DM21)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/DS-1280ZJ-DM21.png','2026-02-17 20:52:43',NULL,NULL,'0','admin','2026-02-18 02:52:43','2026-02-18 02:52:43','DS-1280ZJ-DM21','2026-02-17 20:52:43',1335.00),
(1485,33,NULL,'Dimmer compañero lutron satin colors color biscuit (MSC-AD-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/MSC-AD-BI.png','2026-02-17 21:27:13',NULL,NULL,'0','admin','2026-02-18 03:27:13','2026-02-18 03:27:13','MSC-AD-BI','2026-02-17 21:27:13',1268.00),
(1486,1,NULL,'Interfaces para lamparas fluoresentes 16A lutron grafik eye (GRX-FDBI16A120-S)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/GRX-FDBI16A120-S.png','2026-02-17 21:37:10',NULL,NULL,'0','admin','2026-02-18 03:37:10','2026-02-18 03:37:10','GRX-FDBI16A120-S','2026-02-17 21:37:10',1269.00),
(1487,6,NULL,'Base metálica para techo inclinado para modelos DS-63X2FXX, HikVision (DS-1281ZJ-N)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/DS-1281ZJ-N.png','2026-02-17 21:47:47',NULL,NULL,'0','admin','2026-02-18 03:47:47','2026-02-18 03:47:47','DS-1281ZJ-N','2026-02-17 21:47:47',1340.00),
(1488,1,NULL,'Botonera lutron de 5 botones radioRa2 color blanco (RRD-W5BRL-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/RRD-W5BRL-WH.png','2026-02-17 22:19:06',NULL,NULL,'0','admin','2026-02-18 04:19:06','2026-02-18 04:19:06','RRD-W5BRL-WH','2026-02-17 22:19:06',1271.00),
(1489,1,NULL,'Dimmer lutron homeworksQS color taupe (HQRD-6ND-TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/HQRD-6ND-TP.png','2026-02-17 22:23:58',NULL,NULL,'0','admin','2026-02-18 04:23:58','2026-02-18 04:23:58','HQRD-6ND-TP','2026-02-17 22:23:58',1271.00),
(1490,0,NULL,'Cámara Mini con sensor de movimiento, 1080 p, Wifi, Alexa, Ezviz (MINIO)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/MINIO.png','2026-02-17 22:42:09',NULL,NULL,'0','admin','2026-02-18 04:42:09','2026-02-18 04:42:09','MINIO','2026-02-17 22:42:09',1341.00),
(1491,1,NULL,'Switch lutron maestro color blanco (MRF2-8ANS-120-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/MRF2-8ANS-120-WH.png','2026-02-17 22:42:47',NULL,NULL,'0','admin','2026-02-18 04:42:47','2026-02-18 04:42:47','MRF2-8ANS-120-WH','2026-02-17 22:42:47',1272.00),
(1492,2,NULL,'Cámara Tipo domo, 6MB doble lente, HikVision (DS-2CD2363G2-UI)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/DS-2CD2363G2-UI.png','2026-02-17 22:50:39',NULL,NULL,'0','admin','2026-02-18 04:50:39','2026-02-18 04:50:39','DS-2CD2363G2-UI','2026-02-17 22:50:39',1341.00),
(1493,1,NULL,'Fuente de Poder Profesional HEAVY DUTY @ 16 Amperes, 8 Canales,Hasta 2A por Salida, Ajuste independiente de 11 a 15 Vcc por Salida, Protección Contra Sobrecargas ,Filtro de Ruido Especial para Cámaras 4K, Epcom Powerline (XP8DC164KV)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/XP8DC164KV.png','2026-02-17 22:54:38',NULL,NULL,'0','admin','2026-02-18 04:54:38','2026-02-18 04:54:38','XP8DC164KV','2026-02-17 22:54:38',1343.00),
(1494,3,NULL,'NVR, 12 MB, 4K, 16 canales, acuSensen, 1 Bahia de disco duro, HikVision (DS-7616NXIK1)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6942160424606.png','2026-02-18 15:46:17',NULL,NULL,'0','admin','2026-02-18 21:46:17','2026-02-18 21:46:17','6942160424606','2026-02-18 15:46:17',1343.00),
(1495,1,'NVR-108H-D/8P','NVR 4 Megapixel (Compatible con Cámaras AcuSense), 8 Canales IP, 8 Puertos PoE+, 1 Bahía de Disco Duro, Salida en Full HD','HiLook by HikVision','VIDEO','Bodega General',NULL,NULL,'/img/productos/6942160416991.png','2026-02-18 15:49:01',NULL,NULL,'0','admin','2026-02-18 21:49:01','2026-02-18 21:49:01','6942160416991','2026-02-18 15:49:01',1344.00),
(1496,12,NULL,'Cono de plastico para salida de audio generico',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/CO-PLA-GE.png','2026-02-18 16:22:35',NULL,NULL,'0','admin','2026-02-18 22:22:35','2026-02-18 22:22:35','CO-PLA-GE','2026-02-18 16:22:35',1032.00),
(1497,1,NULL,'Irradiador de luz roja tipo audifonos marca generica (SPK-700)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/SPK-700.png','2026-02-18 16:25:27',NULL,NULL,'0','admin','2026-02-18 22:25:27','2026-02-18 22:25:27','SPK-700','2026-02-18 16:25:27',1358.00),
(1498,8,NULL,'Contacto inalambrico maestro wireless lutron plug-in color blanco (MRF2-15APS-1WH)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/27557869744.png','2026-02-18 16:41:21',NULL,NULL,'0','admin','2026-02-18 22:41:21','2026-02-18 22:41:21','27557869744','2026-02-18 16:41:21',1273.00),
(1499,5,NULL,'UCG Max Cloud Gateway de 2.5 GbE Compacto con Soporte para hasta 30 Dispositivos UniFi, 300+ Clientes, Enrutamiento IPS de 2.3 Gbps y Almacenamiento 512 GB de Memoria Interna (UCG-MAX)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/UCG-MAX.png','2026-02-18 16:46:08',NULL,NULL,'0','admin','2026-02-18 22:46:08','2026-02-18 22:46:08','UCG-MAX','2026-02-18 16:46:08',1344.00),
(1500,18,'PLDC1000','Fuente de poder 12 V, Epcom Rojo','Epcom','FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/PLDC1000.png','2026-02-18 16:50:35',NULL,NULL,'0','admin','2026-02-18 22:50:35','2026-02-18 22:50:35','PLDC1000','2026-02-18 16:50:35',1345.00),
(1501,8,NULL,'Fuente de poder 12 V, 2A, Epcom caja roja (PSD1202D)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/PSD1202D.png','2026-02-18 16:51:42',NULL,NULL,'0','admin','2026-02-18 22:51:42','2026-02-18 22:51:42','PSD1202D','2026-02-18 16:51:42',NULL),
(1502,19,NULL,'Modulo dimmer moes color blanco de 1 via (MO-D1-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/MO-D1-WH.png','2026-02-18 17:34:36',NULL,NULL,'0','admin','2026-02-18 23:34:36','2026-02-18 23:34:36','MO-D1-WH','2026-02-18 17:34:36',1274.00),
(1503,18,NULL,'Modulo dimmer moes color blanco de 2 vias (MO-D2-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/MO-D2-WH.png','2026-02-18 17:35:11',NULL,NULL,'0','admin','2026-02-18 23:35:11','2026-02-18 23:35:11','MO-D2-WH','2026-02-18 17:35:11',1274.00),
(1504,29,NULL,'Modulo switch moes color blanco de 1 via (MO-SW1-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/MO-SW1-WH.png','2026-02-18 17:35:52',NULL,NULL,'0','admin','2026-02-18 23:35:52','2026-02-18 23:35:52','MO-SW1-WH','2026-02-18 17:35:52',1274.00),
(1505,12,NULL,'Modulo switch moes color blanco de 2 vias (MO-SW2-WH)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/MO-SW2-WH.png','2026-02-18 17:36:54',NULL,NULL,'0','admin','2026-02-18 23:36:54','2026-02-18 23:36:54','MO-SW2-WH','2026-02-18 17:36:54',1274.00),
(1506,1,NULL,'Cámara tipo domo antivandálico, dia/noche, Epcom (HRD700V)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/HRD700V.png','2026-02-18 17:53:50',NULL,NULL,'0','admin','2026-02-18 23:53:50','2026-02-18 23:53:50','HRD700V','2026-02-18 17:53:50',1346.00),
(1507,2,NULL,'Cámara tipo Bala, 720 p, Hilook (PHC-B110-P)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/PHC-B110-P.png','2026-02-18 18:03:17',NULL,NULL,'0','admin','2026-02-19 00:03:17','2026-02-19 00:03:17','PHC-B110-P','2026-02-18 18:03:17',1346.00),
(1508,1,NULL,'Cámara de red Fixed Bullet exterior de 2 MP con micrófono incorporado y antena (DS-2CD2021G1-IDW1)\\\\r\\\\n',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/DS-2CD2021G1-IDW1.png','2026-02-18 18:10:01',NULL,NULL,'0','admin','2026-02-19 00:10:01','2026-02-19 00:10:01','DS-2CD2021G1-IDW1','2026-02-18 18:10:01',1346.00),
(1509,1,NULL,'Cámara tipo domo, 2MB, Hilook (IPC-T221-H)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/IPC-T221-H.png','2026-02-18 18:14:08',NULL,NULL,'0','admin','2026-02-19 00:14:08','2026-02-19 00:14:08','IPC-T221-H','2026-02-18 18:14:08',1346.00),
(1510,2,NULL,'Fuente de poder regulada, 24V, 1,5A, Meanwell (LRS-35-24)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/LRS-35-24.png','2026-02-18 18:18:12',NULL,NULL,'0','admin','2026-02-19 00:18:12','2026-02-19 00:18:12','LRS-35-24','2026-02-18 18:18:12',1351.00),
(1511,21,NULL,'Fuente de poder regulada, 5V, 10 A, Meanwell (LRS-50-5)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/LRS-50-5.png','2026-02-18 18:19:22',NULL,NULL,'0','admin','2026-02-19 00:19:22','2026-02-19 00:19:22','LRS-50-5','2026-02-18 18:19:22',1351.00),
(1512,2,NULL,'Fuente de poder regulada, 12V, 8,5 A, Meanwell (RS-100-12)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/RS-100-12.png','2026-02-18 18:20:25',NULL,NULL,'0','admin','2026-02-19 00:20:25','2026-02-19 00:20:25','RS-100-12','2026-02-18 18:20:25',1351.00),
(1513,1,NULL,'Controlador dmx para productos RGBW de uso interior (DMX-RGB-W)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/DMX-RGB-W.png','2026-02-18 18:22:19',NULL,NULL,'0','admin','2026-02-19 00:22:19','2026-02-19 00:22:19','DMX-RGB-W','2026-02-18 18:22:19',1277.00),
(1514,4,NULL,'Fuente de poder regulada, 15V, 2,8A, Meanwell (HTS-40F-15)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/HTS-40F-15.png','2026-02-18 18:23:29',NULL,NULL,'0','admin','2026-02-19 00:23:29','2026-02-19 00:23:29','HTS-40F-15','2026-02-18 18:23:29',1351.00),
(1515,1,NULL,'Interfaz de audio, 2 canales, Behringer (u-phoria) (UMC204HD)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/UMC204HD.png','2026-02-18 18:35:37',NULL,NULL,'0','admin','2026-02-19 00:35:37','2026-02-19 00:35:37','UMC204HD','2026-02-18 18:35:37',1353.00),
(1516,2,NULL,'Modulo de control wifi para cortinas, BMIGHTY (DD7002B)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/DD7002B.png','2026-02-18 18:37:45',NULL,NULL,'0','admin','2026-02-19 00:37:45','2026-02-19 00:37:45','DD7002B','2026-02-18 18:37:45',NULL),
(1517,0,NULL,'Disco Duro PURPLE de 4TB, Para Videovigilancia, Western Digital (WD43PURZ)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/WD43PURZ.png','2026-02-18 18:50:51',NULL,NULL,'0','admin','2026-02-19 00:50:51','2026-02-19 00:50:51','WD43PURZ','2026-02-18 18:50:51',1354.00),
(1518,3,NULL,'Disco duro WD de 8TB, 7200RPM,Optimizado para soluciones de video inteligente, Western Digital (WD8002PURP)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/WD8002PURP.png','2026-02-18 18:52:07',NULL,NULL,'0','admin','2026-02-19 00:52:07','2026-02-19 00:52:07','WD8002PURP','2026-02-18 18:52:07',1354.00),
(1519,1,NULL,'Disco Duro PURPLE de 6TB, Para Videovigilancia, Western Digital (WD64PURZ)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/WD64PURZ.png','2026-02-18 18:53:13',NULL,NULL,'0','admin','2026-02-19 00:53:13','2026-02-19 00:53:13','WD64PURZ','2026-02-18 18:53:13',1354.00),
(1520,0,NULL,'Disco Duro Purple Pro de 10TB, 7200 RPM, Optimizado para Soluciones de Videovigilancia con Analíticos (Meta Data),Uso 24-7, Western digital (WD101PURP)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/WD101PURP.png','2026-02-18 18:54:36',NULL,NULL,'0','admin','2026-02-19 00:54:36','2026-02-19 00:54:36','WD101PURP','2026-02-18 18:54:36',1354.00),
(1521,1,NULL,'Disco Duro 2TB, Seagate (ST2000VM003 )',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/ST2000VM003.png','2026-02-18 18:55:41',NULL,NULL,'0','admin','2026-02-19 00:55:41','2026-02-19 00:55:41','ST2000VM003 ','2026-02-18 18:55:41',1354.00),
(1522,1,NULL,'Comunicador LTE 5G Dual SIM, 4G/5G sin Limites, ÚNICO Programación Remota DS, HONEYWELL ,Cero Configuración,Compatible Honeywell ,DSC, CROW, PIMA, MSM Services (MN01-LTE-M)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/MN01-LTE-M.png','2026-02-18 18:58:33',NULL,NULL,'0','admin','2026-02-19 00:58:33','2026-02-19 00:58:33','MN01-LTE-M','2026-02-18 18:58:33',1354.00),
(1523,1,NULL,'Amplificador una Zona, 2 Canales, 50 W por Canal, con Chromecast, Alexa Cast, Airplay, Spotify Connect: funciona con Google Assist, VSSL (VSSL-A1)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/VSSL-A1.png','2026-02-18 19:02:47',NULL,NULL,'0','admin','2026-02-19 01:02:47','2026-02-19 01:02:47','VSSL-A1','2026-02-18 19:02:47',1354.00),
(1524,1,NULL,'Protector de picos ethernet (cat6), TXPRO (TXCAT6)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/TXCAT6.png','2026-02-18 19:03:52',NULL,NULL,'0','admin','2026-02-19 01:03:52','2026-02-19 01:03:52','TXCAT6','2026-02-18 19:03:52',1354.00),
(1525,2,NULL,'Juego de alfombrillas para aislamiento de voz Hi-Fi, amortiguador de voz, Audiocrast (ASP4020B)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/ASP4020B.png','2026-02-18 19:21:52',NULL,NULL,'0','admin','2026-02-19 01:21:52','2026-02-19 01:21:52','ASP4020B','2026-02-18 19:21:52',1354.00),
(1526,4,NULL,'Contacto doble falla tierra eaton color taupe (MGF15TP)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/MGF15TP.png','2026-02-18 19:26:09',NULL,NULL,'0','admin','2026-02-19 01:26:09','2026-02-19 01:26:09','MGF15TP','2026-02-18 19:26:09',1278.00),
(1527,3,NULL,'Convertidor de RS232 a RS485 Epcom (485D)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/485D.png','2026-02-18 19:28:06',NULL,NULL,'0','admin','2026-02-19 01:28:06','2026-02-19 01:28:06','485D','2026-02-18 19:28:06',1354.00),
(1528,2,NULL,'Hub de Centro de control universal CAAVO (CAAVO)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/CAAVO.png','2026-02-18 19:29:59',NULL,NULL,'0','admin','2026-02-19 01:29:59','2026-02-19 01:29:59','CAAVO','2026-02-18 19:29:59',1354.00),
(1529,4,NULL,'Control remoto sin base de carga control4 (C4-SR260)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/876389016013.png','2026-02-18 19:36:23',NULL,NULL,'0','admin','2026-02-19 01:36:23','2026-02-19 01:36:23','876389016013','2026-02-18 19:36:23',1279.00),
(1530,2,NULL,'Controlador automatico control4 V2 (C4-CA1-V2)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/C4-CA1-V2.png','2026-02-18 19:37:35',NULL,NULL,'0','admin','2026-02-19 01:37:35','2026-02-19 01:37:35','C4-CA1-V2','2026-02-18 19:37:35',1279.00),
(1531,3,NULL,'Distribuidor de audio RCA 1 entrada 4 salidas, Generico (AK-401R)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/AK-401R.png','2026-02-18 19:39:16',NULL,NULL,'0','admin','2026-02-19 01:39:16','2026-02-19 01:39:16','AK-401R','2026-02-18 19:39:16',1354.00),
(1532,5,NULL,'Kit control4, + Hub lite + control remoto Halo (C4-CORE-LITE)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/C4-CORE-LITE.png','2026-02-18 19:39:46',NULL,NULL,'0','admin','2026-02-19 01:39:46','2026-02-19 01:39:46','C4-CORE-LITE','2026-02-18 19:39:46',1279.00),
(1533,1,NULL,'Control Remoto inteligente con pantalla, Control4 (C4-HALO-BL)',NULL,'CONTROL REMOTO','Bodega General',NULL,NULL,'/img/productos/C4-HALO-BL.png','2026-02-18 19:41:30',NULL,NULL,'0','admin','2026-02-19 01:41:30','2026-02-19 01:41:30','C4-HALO-BL','2026-02-18 19:41:30',1279.00),
(1534,1,NULL,'Gabinete plastico para exterior para riel din epcom (56CB4N)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/56CB4N.png','2026-02-18 19:47:43',NULL,NULL,'0','admin','2026-02-19 01:47:43','2026-02-19 01:47:43','56CB4N','2026-02-18 19:47:43',1280.00),
(1535,14,NULL,'Transformador de pared Epcom powerline 12V (RT1230L)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/RT1230L.png','2026-02-18 19:52:49',NULL,NULL,'0','admin','2026-02-19 01:52:49','2026-02-19 01:52:49','RT1230L','2026-02-18 19:52:49',1280.00),
(1536,1,NULL,'Convertidor Ethernet serie industrial de alto nivel Protección de puerto serie Dispositivo de servidor serie Soporta Watchdog Modbus RTU a Modbus TCP (USR-N510)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/USR-N510.png','2026-02-18 19:53:06',NULL,NULL,'0','admin','2026-02-19 01:53:06','2026-02-19 01:53:06','USR-N510','2026-02-18 19:53:06',1358.00),
(1537,1,NULL,'Convertidor de USB a RS232/485 (USB-RS232/485)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/USB-RS232-485.png','2026-02-18 19:58:44',NULL,NULL,'0','admin','2026-02-19 01:58:44','2026-02-19 01:58:44','USB-RS232/485','2026-02-18 19:58:44',1358.00),
(1538,1,NULL,'Control remoto para cerradura (RTU5035)',NULL,'CERRADURA','Bodega General',NULL,NULL,'/img/productos/RTU5035.png','2026-02-18 20:01:24',NULL,NULL,'0','admin','2026-02-19 02:01:24','2026-02-19 02:01:24','RTU5035','2026-02-18 20:01:24',1358.00),
(1539,1,NULL,'Extensor POE, 100 mts 1 puerto a 2 salidas 30W, Epcom (TT-2003EX-POE)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/TT-2003EX-POE.png','2026-02-18 21:22:24',NULL,NULL,'0','admin','2026-02-19 03:22:24','2026-02-19 03:22:24','TT-2003EX-POE','2026-02-18 21:22:24',1359.00),
(1540,2,NULL,'Procesador de audio 2 in 4 out, MiniDSP (MINiDSP)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/MINiDSP.png','2026-02-18 21:24:03',NULL,NULL,'0','admin','2026-02-19 03:24:03','2026-02-19 03:24:03','MINiDSP','2026-02-18 21:24:03',1359.00),
(1541,1,NULL,'Crossover Pasivo 2 Vias 300w Radox 140-790 1 Bajo Y 2 Agudos (140-790)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/140-790.png','2026-02-18 21:37:10',NULL,NULL,'0','admin','2026-02-19 03:37:10','2026-02-19 03:37:10','140-790','2026-02-18 21:37:10',1359.00),
(1542,5,NULL,'Crossover Pasivo 3vias 200w Radox 140-789 1bajo 1medio 1agud (140-789)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/140-789.png','2026-02-18 21:38:02',NULL,NULL,'0','admin','2026-02-19 03:38:02','2026-02-19 03:38:02','140-789','2026-02-18 21:38:02',1359.00),
(1543,4,'TLC-360','Detector de movimiento 360 grados','TLC','ALARMA','Bodega General',NULL,NULL,'/img/productos/TLC-360.png','2026-02-18 21:42:11',NULL,NULL,'0','admin','2026-02-19 03:42:11','2026-02-19 03:42:11','TLC-360','2026-02-18 21:42:11',1361.00),
(1544,21,NULL,'Extensor de HDMI sobre fibra HDMI 4K Pro.2 foxxun (SX-EF04)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/9328202031174.png','2026-02-18 21:54:30',NULL,NULL,'0','admin','2026-02-19 03:54:30','2026-02-19 03:54:30','9328202031174','2026-02-18 21:54:30',NULL),
(1545,1,'WS4904',' Detector de Movimiento infrarrojo Inalámbrico, compatible con Power Series, Impassa y Maxsys','DSC','ALARMA','Bodega General',NULL,NULL,'/img/productos/WS4904.png','2026-02-18 22:03:07',NULL,NULL,'0','admin','2026-02-19 04:03:07','2026-02-19 04:03:07','WS4904','2026-02-18 22:03:07',1362.00),
(1546,4,'SS-078','Botón de pánico con reestablecimiento manual,  con cubierta de metal, incluye 2 llaves','Enforce Seco Alarm','ALARMA','Bodega General',NULL,NULL,'/img/productos/SS-078.png','2026-02-18 22:05:48',NULL,NULL,'0','admin','2026-02-19 04:05:48','2026-02-19 04:05:48','SS-078','2026-02-18 22:05:48',1362.00),
(1547,35,'SF-1021','Contacto magnético para puertas y ventanas Metálicas, aluminio y madera de empotrar color blanco / GAP: 19mm, con cable','S-Fire','ALARMA','Bodega General',NULL,NULL,'/img/productos/SF-1021.png','2026-02-18 22:12:00',NULL,NULL,'0','admin','2026-02-19 04:12:00','2026-02-19 04:12:00','SF-1021','2026-02-18 22:12:00',1362.00),
(1548,10,'SF-1012','Contacto magnético pequeño para puertas y ventanas de aluminio o madera, Empotrable, color blanco, GAP: 20 mm, con cable','S-Fire','ALARMA','Bodega General',NULL,NULL,'/img/productos/SF-1012.png','2026-02-18 22:12:59',NULL,NULL,'0','admin','2026-02-19 04:12:59','2026-02-19 04:12:59','SF-1012','2026-02-18 22:12:59',1362.00),
(1549,1,NULL,'Monitor IP/SIP para interior, Wi-Fi, pantalla a color de 4.3 pulgadas, audio de 2 vías, PoE, 8 interfaces de entrada de alarma, Fanvil (I51W)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/I51W.png','2026-02-18 22:38:05',NULL,NULL,'0','admin','2026-02-19 04:38:05','2026-02-19 04:38:05','I51W','2026-02-18 22:38:05',1363.00),
(1550,1,'SF501P','Generador de Humo Antirobo','S-Fire','ALARMA','Bodega General',NULL,NULL,'/img/productos/SF501P.png','2026-02-18 22:49:45',NULL,NULL,'0','admin','2026-02-19 04:49:45','2026-02-19 04:49:45','SF501P','2026-02-18 22:49:45',1364.00),
(1551,4,NULL,'PDU Básico Para Distribución de Energía, Con 8 Tomas NEMA 5-15R Traseras y 2 Tomas 5-15R Frontales, 1UR, 15 Amp, 120 Vca, Cyberpower (PDU15B2F8R-MX)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/PDU15B2F8R-MX.png','2026-02-19 15:35:22',NULL,NULL,'0','admin','2026-02-19 21:35:22','2026-02-19 21:35:22','PDU15B2F8R-MX','2026-02-19 15:35:22',1366.00),
(1552,2,NULL,'Modulo de 4 relevadores para funciones de automatización, Honeywell (4204)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/4204.png','2026-02-19 15:38:23',NULL,NULL,'0','admin','2026-02-19 21:38:23','2026-02-19 21:38:23','4204','2026-02-19 15:38:23',1366.00),
(1553,4,NULL,'Modulo de alarman 8 zonas, para le serie vista, Ademco (4208U)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/4208U.png','2026-02-19 15:46:01',NULL,NULL,'0','admin','2026-02-19 21:46:01','2026-02-19 21:46:01','4208U','2026-02-19 15:46:01',1366.00),
(1554,1,'6128RF','Panel de alarma con teclado en ingles','Ademco','ALARMA','Bodega General',NULL,NULL,'/img/productos/6128RF.png','2026-02-19 15:48:31',NULL,NULL,'0','admin','2026-02-19 21:48:31','2026-02-19 21:48:31','6128RF','2026-02-19 15:48:31',1366.00),
(1555,1,NULL,'Kit Montaje En Rack Para Firewall USG6510FD/USG6530FD y Controladora de APs AC650128/AC650256, Huawei (E5700MK00)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/E5700MK00.png','2026-02-19 16:10:32',NULL,NULL,'0','admin','2026-02-19 22:10:32','2026-02-19 22:10:32','E5700MK00','2026-02-19 16:10:32',1367.00),
(1556,1,NULL,'Vídeoportero IP con 2 líneas SIP, cámara HD, relevador integrado, teclado numérico y lectora de tarjetas RFID para control de acceso, PoE fanvil (I30)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/I30.png','2026-02-19 16:12:49',NULL,NULL,'0','admin','2026-02-19 22:12:49','2026-02-19 22:12:49','I30','2026-02-19 16:12:49',1367.00),
(1557,2,'SF-1301-SAQ','Luz Estroboscópica LED (55), Patrones de Parpadeo Ajustables, 8 Patrones, Batería de Respaldo,12-24 Vcc/Vca / Resistente al Clima IP66/IP55','S-Fire','ALARMA','Bodega General',NULL,NULL,'/img/productos/SF-1301-SAQ.png','2026-02-19 16:15:38',NULL,NULL,'0','admin','2026-02-19 22:15:38','2026-02-19 22:15:38','SF-1301-SAQ','2026-02-19 16:15:38',1367.00),
(1558,9,'DS-KABH6320-T','Montaje de Escritorio para Monitores IP Compatible con series 6320, 8520, 6220 y 9310','Hikvision','INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/DS-KABH6320-T_1.png','2026-02-19 16:20:06',NULL,NULL,'0','admin','2026-02-19 22:20:06','2026-02-19 22:20:06','6941264027270','2026-02-19 16:20:06',1367.00),
(1559,2,NULL,'Interfon de metal, con botón y cámara 2mb, Hikvision (DS-KV8113-WME1)',NULL,'INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6931847179403.png','2026-02-19 16:25:49',NULL,NULL,'0','admin','2026-02-19 22:25:49','2026-02-19 22:25:49','6931847179403','2026-02-19 16:25:49',1368.00),
(1560,1,NULL,'Bocina para Techo 8 pulgadas Color Blanco JBL (STAGE-280CDT)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/6925281930621.png','2026-02-19 18:00:40',NULL,NULL,'0','admin','2026-02-20 00:00:40','2026-02-20 00:00:40','6925281930621','2026-02-19 18:00:40',1180.00),
(1561,3,NULL,'Base de Bocina Sonos Ray Human Centric (101-2102)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/810009091783.png','2026-02-19 18:12:02',NULL,NULL,'0','admin','2026-02-20 00:12:02','2026-02-20 00:12:02','810009091783','2026-02-19 18:12:02',1180.00),
(1562,2,NULL,'Sistema de Audio 200W negro K-Array (Kit) (KAMT2L14 II)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/8055731374571.png','2026-02-19 18:15:59',NULL,NULL,'0','admin','2026-02-20 00:15:59','2026-02-20 00:15:59','8055731374571','2026-02-19 18:15:59',1179.00),
(1563,1,NULL,'Altavoz de Muro 6,5 pulgadas blanca Klipsch (DS-160W)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/DS-160W.png','2026-02-19 18:43:28',NULL,NULL,'0','admin','2026-02-20 00:43:28','2026-02-20 00:43:28','DS-160W','2026-02-19 18:43:28',1173.00),
(1564,1,NULL,'Soporte de pared para altavoz Sonos Play 5 (SWM002-2)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/SWM002-2.png','2026-02-19 19:10:18',NULL,NULL,'0','admin','2026-02-20 01:10:18','2026-02-20 01:10:18','SWM002-2','2026-02-19 19:10:18',1370.00),
(1565,1,NULL,'Gabinete Anclo Nema 4 -IP 66 de 40X30X20CM sin platina (G4A0403020-UL-SINPLATINA)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/G4A0403020-UL-SINPLA.png','2026-02-19 19:18:25',NULL,NULL,'0','admin','2026-02-20 01:18:25','2026-02-20 01:18:25','G4A0403020-UL-SINPLA','2026-02-19 19:18:25',1000.00),
(1566,2,NULL,'Bocina para Techo Revel 6 pulgadas Blanca (C363)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/C363.png','2026-02-19 19:19:34',NULL,NULL,'0','admin','2026-02-20 01:19:34','2026-02-20 01:19:34','C363','2026-02-19 19:19:34',1168.00),
(1567,2,NULL,'Base de Carga para Sonos Move Blanco (MVCHBUS1)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/840136800507.png','2026-02-19 19:37:29',NULL,NULL,'0','admin','2026-02-20 01:37:29','2026-02-20 01:37:29','840136800507','2026-02-19 19:37:29',1166.00),
(1568,1,NULL,'ANCLO GABINETE METALICO SIN PLATINA 40X40X20cm (G4A404020-UL-SINPLATINA)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/G4A404020-UL-SINPLAT.png','2026-02-19 19:38:42',NULL,NULL,'0','admin','2026-02-20 01:38:42','2026-02-20 01:38:42','G4A404020-UL-SINPLAT','2026-02-19 19:38:42',NULL),
(1569,2,NULL,'Base de Carga para Sonos Move Negro (MVCHBUS1BLK)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/878269008941.png','2026-02-19 19:40:06',NULL,NULL,'0','admin','2026-02-20 01:40:06','2026-02-20 01:40:06','878269008941','2026-02-19 19:40:06',1165.00),
(1570,1,NULL,'Subwoofer inalámbrico para graves profundos Sonos Gen 3 Blanco (SUBG3US1WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/SUBG3US1WH.png','2026-02-19 15:05:53',NULL,NULL,'0','admin','2026-02-19 21:05:53','2026-02-19 21:05:53','SUBG3US1WH','2026-02-19 15:05:53',1157.00),
(1571,1,NULL,'Altavoz Subwoofer de empotrar en techo y pared James Loudspeaker y accesorios (PP10-N)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/PP10-N.png','2026-02-19 15:07:42',NULL,NULL,'0','admin','2026-02-19 21:07:42','2026-02-19 21:07:42','PP10-N','2026-02-19 15:07:42',1156.00),
(1572,25,NULL,'Tirante para Barra de sonido Steren Negro (STV-330)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/STV-330.png','2026-02-19 15:17:57',NULL,NULL,'0','admin','2026-02-19 21:17:57','2026-02-19 21:17:57','STV-330','2026-02-19 15:17:57',1152.00),
(1573,1,NULL,'Bocina tipo Satélite estaca Revel exterior color cafe (L41XC)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/L41XC.png','2026-02-19 15:31:13',NULL,NULL,'0','admin','2026-02-19 21:31:13','2026-02-19 21:31:13','L41XC','2026-02-19 15:31:13',1150.00),
(1574,2,NULL,'Soporte de brazo doble movil para pantallas de 40-85 pulgadas, Strong (STH-43100EXB)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/STH-43100EXB.png','2026-02-19 15:40:11',NULL,NULL,'0','admin','2026-02-19 21:40:11','2026-02-19 21:40:11','STH-43100EXB','2026-02-19 15:40:11',NULL),
(1575,4,NULL,'Bocina Satelital de 2 vías Medio Blanca 100W Bowers & Wilkins (M-1WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/M-1WH.png','2026-02-19 15:46:55',NULL,NULL,'0','admin','2026-02-19 21:46:55','2026-02-19 21:46:55','M-1WH','2026-02-19 15:46:55',1146.00),
(1576,0,NULL,'Altavoz de Graves 1000 w Negra Kicker y 12 pulgadas (48CWRT122)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/48CWRT122.png','2026-02-19 15:58:04',NULL,NULL,'0','admin','2026-02-19 21:58:04','2026-02-19 21:58:04','713034019183','2026-02-19 15:58:04',1145.00),
(1577,4,'GSF3-GN','Bocina de exterior tipo estaca, color verde, vienen en par','JBL','AUDIO','Bodega General',NULL,NULL,'/img/productos/GSF3-GN.png','2026-02-19 16:04:15',NULL,NULL,'0','admin','2026-02-19 22:04:15','2026-02-19 22:04:15','691991039300','2026-02-19 16:04:15',1144.00),
(1578,2,'GSF3-TN','Par de bocina de exterior tipo estaca, color cafe','JBL','AUDIO','Bodega General',NULL,NULL,'/img/productos/GSF3-TN.png','2026-02-19 16:08:07',NULL,NULL,'0','admin','2026-02-19 22:08:07','2026-02-19 22:08:07','691991039317','2026-02-19 16:08:07',1144.00),
(1579,2,NULL,'Bocina para Pared 8 pulgadas Focal (300ICW8)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/3544050691322.png','2026-02-19 16:14:32',NULL,NULL,'0','admin','2026-02-19 22:14:32','2026-02-19 22:14:32','3544050691322','2026-02-19 16:14:32',NULL),
(1580,3,NULL,'Bocina para Exterior 5 pulgadas blanco Bowers & Wilkins (AM-1-WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/714346318704.png','2026-02-19 16:20:21',NULL,NULL,'0','admin','2026-02-19 22:20:21','2026-02-19 22:20:21','714346318704','2026-02-19 16:20:21',1140.00),
(1581,7,NULL,'Bocina para techo  2 Vias 8 pulgadas JBL (STAGE-280C)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/6925281932304.png','2026-02-19 16:22:54',NULL,NULL,'0','admin','2026-02-19 22:22:54','2026-02-19 22:22:54','6925281932304','2026-02-19 16:22:54',1176.00),
(1582,2,NULL,'Tweeters Marinos Jl Audio 1 pulgada (M6-100CT)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/699440936934.png','2026-02-19 16:24:33',NULL,NULL,'0','admin','2026-02-19 22:24:33','2026-02-19 22:24:33','699440936934','2026-02-19 16:24:33',1139.00),
(1583,1,NULL,'Bocina para Techo Marina 6.5 pulgadas TDG (NFC-62M)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/747742164934.png','2026-02-19 16:27:54',NULL,NULL,'0','admin','2026-02-19 22:27:54','2026-02-19 22:27:54','747742164934','2026-02-19 16:27:54',1139.00),
(1584,2,NULL,'Bocina para plafonde de 8 pulgadas Klipsch (DS-180-CDT)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/DS-180-CDT.png','2026-02-19 16:30:43',NULL,NULL,'0','admin','2026-02-19 22:30:43','2026-02-19 22:30:43','DS-180-CDT','2026-02-19 16:30:43',1127.00),
(1585,2,NULL,'Bocina Coaxial Marina Wet Sounds 8 pulgadas 300W Negro (ZERO 8XZ-B)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/810042172760.png','2026-02-20 09:11:08',NULL,NULL,'0','admin','2026-02-20 15:11:08','2026-02-20 15:11:08','810042172760','2026-02-20 09:11:08',1138.00),
(1586,1,NULL,'Subwoofer marino Revo 8 pulgadas 300W Blanco Wet Sounds (FA-S4-W)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/814820024807.png','2026-02-20 09:16:51',NULL,NULL,'0','admin','2026-02-20 15:16:51','2026-02-20 15:16:51','814820024807','2026-02-20 09:16:51',1139.00),
(1587,2,NULL,'Detector de Rayo Fotoelectrico tipo Cortina 2 rayos 0.48mt Alcance 100mt s-fire (SF100482I)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/SF100482I.png','2026-02-20 09:31:56',NULL,NULL,'0','admin','2026-02-20 15:31:56','2026-02-20 15:31:56','SF100482I','2026-02-20 09:31:56',NULL),
(1588,1,NULL,'Bocina para Techo  LitheAudio 6.5 pulgadas 60W (LAS65)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/5060260697384.png','2026-02-20 09:46:53',NULL,NULL,'0','admin','2026-02-20 15:46:53','2026-02-20 15:46:53','5060260697384','2026-02-20 09:46:53',NULL),
(1589,6,NULL,'Bocina Modular de Columna Bose Panarray Negro (MA12)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/17817392297.png','2026-02-20 09:50:30',NULL,NULL,'0','admin','2026-02-20 15:50:30','2026-02-20 15:50:30','17817392297','2026-02-20 09:50:30',NULL),
(1590,3,NULL,'Detector de Rayo Fotoelectrico tipo Cortina 6 rayos 1.08 mts Alcance 100mt S-fire (SF1001086I)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/SF1001086I.png','2026-02-20 10:05:59',NULL,NULL,'0','admin','2026-02-20 16:05:59','2026-02-20 16:05:59','SF1001086I','2026-02-20 10:05:59',NULL),
(1591,3,NULL,'Bocina para Exterior 120W 6.5 pulgadas Blanco Focal (100OD6WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/3544052691412.png','2026-02-20 10:13:49',NULL,NULL,'0','admin','2026-02-20 16:13:49','2026-02-20 16:13:49','3544052691412','2026-02-20 10:13:49',NULL),
(1592,1,NULL,'Bocina para Exterior 120W 8 pulgadas Blanco Focal (100 OD8WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/100OD8WH.png','2026-02-20 10:15:48',NULL,NULL,'0','admin','2026-02-20 16:15:48','2026-02-20 16:15:48','100OD8WH','2026-02-20 10:15:48',1132.00),
(1593,1,NULL,'Bocina para Techo 8 pulgadas Blanco Klipsch (CDT5850-CII)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/CDT5850-CII.png','2026-02-20 10:20:41',NULL,NULL,'0','admin','2026-02-20 16:20:41','2026-02-20 16:20:41','CDT5850-CII','2026-02-20 10:20:41',1131.00),
(1594,1,NULL,'Bocina para Techo 6 pulgadas Blanco Klipsch (CDT5650-CII)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/CDT5650-CII.png','2026-02-20 10:22:41',NULL,NULL,'0','admin','2026-02-20 16:22:41','2026-02-20 16:22:41','CDT5650-CII','2026-02-20 10:22:41',1131.00),
(1595,2,NULL,'Bocina para Interior o Exterior 5 pulgadas Blanco JBL (Control25-1WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991002038.png','2026-02-20 10:25:15',NULL,NULL,'0','admin','2026-02-20 16:25:15','2026-02-20 16:25:15','691991002038','2026-02-20 10:25:15',1130.00),
(1596,2,NULL,'Altavos Tipo Baffle Bose Negro (251)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/251.png','2026-02-20 10:43:14',NULL,NULL,'0','admin','2026-02-20 16:43:14','2026-02-20 16:43:14','251','2026-02-20 10:43:14',1129.00),
(1597,4,NULL,'Altavoz Compacto 1.5 pulgadas Alambrico Negro  Yamaha (VXS1MLB)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/889025111850.png','2026-02-20 10:56:34',NULL,NULL,'0','admin','2026-02-20 16:56:34','2026-02-20 16:56:34','889025111850','2026-02-20 10:56:34',1126.00),
(1598,2,NULL,'Bocina Coxial 120w 4 pulgadas Negro Kicker (DSC40)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/713034077640.png','2026-02-20 11:00:20',NULL,NULL,'0','admin','2026-02-20 17:00:20','2026-02-20 17:00:20','713034077640','2026-02-20 11:00:20',1126.00),
(1599,1,NULL,'Amplificador de Audio Integrado Rotel Negro (RA-6000)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/RA-6000.png','2026-02-20 11:10:00',NULL,NULL,'0','admin','2026-02-20 17:10:00','2026-02-20 17:10:00','RA-6000','2026-02-20 11:10:00',1125.00),
(1600,4,NULL,'Altavoces para Exteriror 6.5 pulgadas 400W Polk Atrium 5 Blanco (Atrium5)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/747192118822.png','2026-02-20 11:14:19',NULL,NULL,'0','admin','2026-02-20 17:14:19','2026-02-20 17:14:19','747192118822','2026-02-20 11:14:19',1123.00),
(1601,3,NULL,'Altavoces para Techo JBL 6 pulgadas  Blanco (STAGE-260CDT)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/05036389464.png','2026-02-20 11:16:13',NULL,NULL,'0','admin','2026-02-20 17:16:13','2026-02-20 17:16:13','05036389464','2026-02-20 11:16:13',1123.00),
(1602,1,NULL,'Gabinete precision de poliester IP65 uso en intemperie (400x400x200mm) (PST-4040-20P)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/PST-4040-20P.png','2026-02-20 11:22:12',NULL,NULL,'0','admin','2026-02-20 17:22:12','2026-02-20 17:22:12','PST-4040-20P','2026-02-20 11:22:12',1011.00),
(1603,1,NULL,'Gabinete Precision de acero IP65 Uso en Intemperie (300x400x200mm) (PST304020A)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/PST304020A.png','2026-02-20 11:23:38',NULL,NULL,'0','admin','2026-02-20 17:23:38','2026-02-20 17:23:38','PST304020A','2026-02-20 11:23:38',1011.00),
(1604,6,NULL,'Toma corriente con protector de voltaje wattbox de 12 tomas 600V (WB-600-SVCE-12)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/WB-600-SVCE-12.png','2026-02-20 11:27:44',NULL,NULL,'0','admin','2026-02-20 17:27:44','2026-02-20 17:27:44','WB-600-SVCE-12','2026-02-20 11:27:44',1017.00),
(1605,5,NULL,'Toma corriente con protector de voltaje wattbox de 12 tomas 820V (WB-820-IPVM-12)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/WB-820-IPVM-12.png','2026-02-20 11:28:13',NULL,NULL,'0','admin','2026-02-20 17:28:13','2026-02-20 17:28:13','WB-820-IPVM-12','2026-02-20 11:28:13',1017.00),
(1606,1,NULL,'Dispositivo de Control de Audio DBX (Zc-9)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/Zc-9.png','2026-02-20 11:47:16',NULL,NULL,'0','admin','2026-02-20 17:47:16','2026-02-20 17:47:16','Zc-9','2026-02-20 11:47:16',1116.00),
(1607,13,NULL,'Gabinete de plastico precision (140x190x76mm) (PST-1419-E)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/PST-1419-E.png','2026-02-20 12:04:54',NULL,NULL,'0','admin','2026-02-20 18:04:54','2026-02-20 18:04:54','PST-1419-E','2026-02-20 12:04:54',1023.00),
(1608,2,NULL,'Regulador de voltaje titan power 2000 (DSE-2000)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/DSE-2000.png','2026-02-20 12:06:22',NULL,NULL,'0','admin','2026-02-20 18:06:22','2026-02-20 18:06:22','DSE-2000','2026-02-20 12:06:22',1024.00),
(1609,1,NULL,'Gabinete epcom para Resguardo de Sirena de 30w (IMP-30-V3)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/IMP-30-V3.png','2026-02-20 12:08:36',NULL,NULL,'0','admin','2026-02-20 18:08:36','2026-02-20 18:08:36','IMP-30-V3','2026-02-20 12:08:36',1026.00),
(1610,5,'FCSM200','Fibercable de 60 Metros (200 Pies) Monomodo (SM) con Conectores LC, Ideal para Equipos Edgerouter, Edgeswitch y Edgepoint','Ubiquiti','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/FCSM200.png','2026-02-20 12:14:05',NULL,NULL,'0','admin','2026-02-20 18:14:05','2026-02-20 18:14:05','FCSM200','2026-02-20 12:14:05',1026.00),
(1611,5,NULL,'Gabinete de plastico para exterior txpro (100x68x50mm) (TXG-01-4)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/TXG-01-4.png','2026-02-20 12:16:25',NULL,NULL,'0','admin','2026-02-20 18:16:25','2026-02-20 18:16:25','TXG-01-4','2026-02-20 12:16:25',1027.00),
(1612,7,NULL,'Gabinete de plastico para exterior txpro (158x90x60mm) (TXG-01-2)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/TXG-01-2.png','2026-02-20 12:18:14',NULL,NULL,'0','admin','2026-02-20 18:18:14','2026-02-20 18:18:14','TXG-01-2','2026-02-20 12:18:14',1027.00),
(1613,1,NULL,'Gabinete saxxon linkedpro metalico 461x405x273mm (SYG075EXT)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/SYG075EXT.png','2026-02-20 12:20:00',NULL,NULL,'0','admin','2026-02-20 18:20:00','2026-02-20 18:20:00','SYG075EXT','2026-02-20 12:20:00',1027.00),
(1614,3,NULL,'Bocina para exterior 8 pulgadas Focal tipo piedra (ODSTONE8)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/ODSTONE8.png','2026-02-20 12:21:08',NULL,NULL,'0','admin','2026-02-20 18:21:08','2026-02-20 18:21:08','ODSTONE8','2026-02-20 12:21:08',NULL),
(1615,1,NULL,'Gabinete de medios epcom 19 pulgadas acero (EIGTR19)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/EIGTR19.png','2026-02-20 12:21:39',NULL,NULL,'0','admin','2026-02-20 18:21:39','2026-02-20 18:21:39','EIGTR19','2026-02-20 12:21:39',1027.00),
(1616,1,NULL,'Bocina sonance mariner 86 color blanco (MARINER86WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/MARINER86WH.png','2026-02-20 12:24:32',NULL,NULL,'0','admin','2026-02-20 18:24:32','2026-02-20 18:24:32','MARINER86WH','2026-02-20 12:24:32',1028.00),
(1617,1,NULL,'Caja de 15 pulgadas con tapa para voz y datos (COOPER)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/COOPER.png','2026-02-20 12:25:45',NULL,NULL,'0','admin','2026-02-20 18:25:45','2026-02-20 18:25:45','COOPER','2026-02-20 12:25:45',1028.00),
(1618,1,NULL,'UPS de rack 8 tomas episode (EP-400-UPS-8HTR-100)',NULL,'NO BREAK ','Bodega General',NULL,NULL,'/img/productos/EP-400-UPS-8HTR-100.png','2026-02-20 12:29:17',NULL,NULL,'0','admin','2026-02-20 18:29:17','2026-02-20 18:29:17','EP-400-UPS-8HTR-100','2026-02-20 12:29:17',1029.00),
(1619,8,NULL,'Caja de derivacion con 10 glandulas de goma precision (PST-1419-ER)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/PST-1419-ER.png','2026-02-20 12:43:05',NULL,NULL,'0','admin','2026-02-20 18:43:05','2026-02-20 18:43:05','PST-1419-ER','2026-02-20 12:43:05',1031.00),
(1620,0,NULL,'Modulo de subwoofer amplificado 200w dynabox (SUB200)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/SUB200.png','2026-02-20 12:50:20',NULL,NULL,'0','admin','2026-02-20 18:50:20','2026-02-20 18:50:20','SUB200','2026-02-20 12:50:20',1359.00),
(1621,3,NULL,'Bobina de Cable para Sistemas de Seguridad y Alarmas 2x18 AWG, 1000 t, Linkedpro Blanco (LP218W/1000)\\\\r\\\\n',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/LP218W-1000.png','2026-02-20 13:00:24',NULL,NULL,'0','admin','2026-02-20 19:00:24','2026-02-20 19:00:24','LP218W/1000','2026-02-20 13:00:24',NULL),
(1622,1,NULL,'Subwoofer Focal (SUB 1000F)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/SUB-1000F.png','2026-02-20 13:02:53',NULL,NULL,'0','admin','2026-02-20 19:02:53','2026-02-20 19:02:53','SUB 1000F','2026-02-20 13:02:53',NULL),
(1623,1,NULL,'Bobina de Cable eléctrico 18 AWG 1000 mts Condumex Verde (TF-LS)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/TF-LS.png','2026-02-20 13:05:19',NULL,NULL,'0','admin','2026-02-20 19:05:19','2026-02-20 19:05:19','TF-LS','2026-02-20 13:05:19',NULL),
(1624,2,NULL,'Bobina de Cable Coaxial CATV 6 1000ft, color negro (800072)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/800072.png','2026-02-20 13:07:13',NULL,NULL,'0','admin','2026-02-20 19:07:13','2026-02-20 19:07:13','800072','2026-02-20 13:07:13',NULL),
(1625,2,NULL,'Bobina de Cable Duplex 2x18 AWG 300m Condumex Transparente (720270)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/720270.png','2026-02-20 13:10:51',NULL,NULL,'0','admin','2026-02-20 19:10:51','2026-02-20 19:10:51','720270','2026-02-20 13:10:51',NULL),
(1626,15,NULL,'Bobina de cable de Audio Premium 2x16 AWG, 1000 fts, Genesis Blanco (5473-1101)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/5473-111.png','2026-02-20 13:20:43',NULL,NULL,'0','admin','2026-02-20 19:20:43','2026-02-20 19:20:43','5473-1101','2026-02-20 13:20:43',NULL),
(1627,8,NULL,'Caja de derivacion de plastico con 12 glandulas plastico (190x240x165mm) (PST-1924-1ER)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/PST-1924-1ER.png','2026-02-20 13:20:44',NULL,NULL,'0','admin','2026-02-20 19:20:44','2026-02-20 19:20:44','PST-1924-1ER','2026-02-20 13:20:44',1031.00),
(1628,10,NULL,'Gabinete de exterior txpro (180x125x57mm) (TXG-01-11)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/TXG-01-11.png','2026-02-20 13:22:53',NULL,NULL,'0','admin','2026-02-20 19:22:53','2026-02-20 19:22:53','TXG-01-11','2026-02-20 13:22:53',1032.00),
(1629,13,NULL,'Bobina de cable para aplicaciones de audio, seguridad y control  Genesis gris (21141109)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/21141109.png','2026-02-20 13:25:54',NULL,NULL,'0','admin','2026-02-20 19:25:54','2026-02-20 19:25:54','21141109','2026-02-20 13:25:54',NULL),
(1630,6,'EF100M','Carrete de 100 metros de fibra óptica monomodo pre-conectorizada (generalmente con conectores SC/SC o LC/LC Dúplex), reforzada con Kevlar, diseñada para instalaciones rápidas de redes y videovigilancia sin requerir fusión en campo.','Linkedpro','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/EF100M.png','2026-02-20 13:30:52',NULL,NULL,'0','admin','2026-02-20 19:30:52','2026-02-20 19:30:52','EF100M','2026-02-20 13:30:52',1036.00),
(1631,1,'SF-2204LE','Bobina de Cable para Aplicaciones en Alarmas de Intrusión y Automatización, Tipo Aluminio revestido de cobre,  4x22 AWG, , 1000ft, color blanco','S-Fire','CABLEADO','Bodega General',NULL,NULL,'/img/productos/SF-2204LE.png','2026-02-20 13:31:26',NULL,NULL,'0','admin','2026-02-20 19:31:26','2026-02-20 19:31:26','SF-2204LE','2026-02-20 13:31:26',NULL),
(1632,10,NULL,'Charola para Rack de 19\\\", 34 cm de Profundidad, 2UR Linkedpro (SCH19x13.5)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/SCH19x13-5.png','2026-02-20 13:31:50',NULL,NULL,'0','admin','2026-02-20 19:31:50','2026-02-20 19:31:50','SCH19x13.5','2026-02-20 13:31:50',NULL),
(1633,2,NULL,'Bocina marina Sonance mariner 66, color blanco (MARINER-66-WH)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/41093931543.png','2026-02-20 13:32:56',NULL,NULL,'0','admin','2026-02-20 19:32:56','2026-02-20 19:32:56','41093931543','2026-02-20 13:32:56',1038.00),
(1634,4,'SF-2206LE','Bobina de Cable para Aplicaciones en Alarmas de Intrusión y Automatización, Tipo Aluminio revestido de cobre, 22x4 4x22 AWG,1000ft, color blanco','S-Fire','CABLEADO','Bodega General',NULL,NULL,'/img/productos/SF-2206LE.png','2026-02-20 13:35:48',NULL,NULL,'0','admin','2026-02-20 19:35:48','2026-02-20 19:35:48','SF-2206LE','2026-02-20 13:35:48',NULL),
(1635,2,NULL,'Subwoofer sonance tipo hongo cafe sistema patio (SGS-SUB)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/41093934353.png','2026-02-20 13:36:21',NULL,NULL,'0','admin','2026-02-20 19:36:21','2026-02-20 19:36:21','41093934353','2026-02-20 13:36:21',NULL),
(1636,1,NULL,'Accesorio de rack strong 4U (SM-SHELF-4U)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/SM-SHELF-4U.png','2026-02-20 14:50:59',NULL,NULL,'0','admin','2026-02-20 20:50:59','2026-02-20 20:50:59','SM-SHELF-4U','2026-02-20 14:50:59',1050.00),
(1637,17,'54735508','Bobina de cable  altavoz Audacious sin blindaje, 16/2 (cobre libre de oxígeno de 65 hilos), riser, CMR, FT4, caja de tracción de 500\\\\\\\' (152,4 m), negro','Genesis','CABLEADO','Bodega General',NULL,NULL,'/img/productos/16-2-Stranded-Audaci.png','2026-02-20 14:54:56',NULL,NULL,'0','admin','2026-02-20 20:54:56','2026-02-20 20:54:56','886618248610','2026-02-20 14:54:56',NULL),
(1638,10,NULL,'Bobina de cable de audio 500ft Epcom negro (EP1602EXT/500)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/EP1602EXT-500.png','2026-02-20 14:58:50',NULL,NULL,'0','admin','2026-02-20 20:58:50','2026-02-20 20:58:50','EP1602EXT/500','2026-02-20 14:58:50',NULL),
(1639,1,'EP1402/500','Bobina de cable de audio 500ft, color  Blanco','Epcom','CABLEADO','Bodega General',NULL,NULL,'/img/productos/EP1402-500.png','2026-02-20 15:00:03',NULL,NULL,'0','admin','2026-02-20 21:00:03','2026-02-20 21:00:03','EP1402/500','2026-02-20 15:00:03',NULL),
(1640,1,NULL,'Organizador de cable hellermantyton (WMB2)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/89306153603.png','2026-02-20 15:00:34',NULL,NULL,'0','admin','2026-02-20 21:00:34','2026-02-20 21:00:34','89306153603','2026-02-20 15:00:34',1051.00),
(1641,1,NULL,'Organizador de cables horizontal panduit de plastico 2U (LPCM0424U)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/LPCM0424U.png','2026-02-20 15:02:47',NULL,NULL,'0','admin','2026-02-20 21:02:47','2026-02-20 21:02:47','LPCM0424U','2026-02-20 15:02:47',1052.00),
(1642,6,NULL,'Bobina de cable de audio 18x2, 500 ft,Epcom, Negro (EP-1802-EXT/500)',NULL,'CABLEADO','Bodega General',NULL,NULL,'/img/productos/EP-1802-EXT-500.jpg','2026-02-20 15:04:00',NULL,NULL,'0','admin','2026-02-20 21:04:00','2026-02-20 21:04:00','EP-1802-EXT/500','2026-02-20 15:04:00',NULL),
(1643,2,NULL,'Fuente de poder profesional 24VCA epcom (GRT2416DV)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/GRT2416DV.png','2026-02-20 15:05:38',NULL,NULL,'0','admin','2026-02-20 21:05:38','2026-02-20 21:05:38','GRT2416DV','2026-02-20 15:05:38',1054.00),
(1644,1,'EP1802/500-BLK','Bobina de cable de audio 500ft  Blanco','Epcom','CABLEADO','Bodega General',NULL,NULL,'/img/productos/EP1802-500-BLK.png','2026-02-20 15:08:15',NULL,NULL,'0','admin','2026-02-20 21:08:15','2026-02-20 21:08:15','EP1802/500-BLK','2026-02-20 15:08:15',NULL),
(1645,9,'EP-1402/500','Bobina de Cable de 152 mts para Aplicaciones de Audio, Control de Acceso y Automatización','Epcom Proaudio','CABLEADO','Bodega General',NULL,NULL,'/img/productos/EP-1402-500.png','2026-02-20 15:18:17',NULL,NULL,'0','admin','2026-02-20 21:18:17','2026-02-20 21:18:17','EP-1402/500','2026-02-20 15:18:17',NULL),
(1646,1,'Procat6Extitle','Bobina de cable, Cat 6 1000ft','Linkedpro','CABLEADO','Bodega General',NULL,NULL,'/img/productos/Procat6Extitle.png','2026-02-20 15:20:21',NULL,NULL,'0','admin','2026-02-20 21:20:21','2026-02-20 21:20:21','Procat6Extitle','2026-02-20 15:20:21',NULL),
(1647,1,'LP216W1000','Bobina de cable para alarma 1000ft','Linkedpro','CABLEADO','Bodega General',NULL,NULL,'/img/productos/LP216W1000.png','2026-02-20 15:22:38',NULL,NULL,'0','admin','2026-02-20 21:22:38','2026-02-20 21:22:38','LP216W1000','2026-02-20 15:22:38',NULL),
(1648,3,'CATV6-u-60AI','Bobina de Cable Coaxial Catv 6 1000Ft, color Negro','Condumex','CABLEADO','Bodega General',NULL,NULL,'/img/productos/CATV6-u-60AI.png','2026-02-20 15:35:10',NULL,NULL,'0','admin','2026-02-20 21:35:10','2026-02-20 21:35:10','CATV6-u-60AI','2026-02-20 15:35:10',NULL),
(1649,2,NULL,'Sistema de audio Bose (901SeriesVI)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/901SeriesVI.png','2026-02-20 15:50:57',NULL,NULL,'0','admin','2026-02-20 21:50:57','2026-02-20 21:50:57','901SeriesVI','2026-02-20 15:50:57',1114.00),
(1650,8,NULL,'Bocina Sonance Mariner Blanco (MX86WHT)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/MX86WHT.png','2026-02-20 16:08:32',NULL,NULL,'0','admin','2026-02-20 22:08:32','2026-02-20 22:08:32','MX86WHT','2026-02-20 16:08:32',NULL),
(1651,2,NULL,'Gabinete de pared linkedpro (LPGPH06R2)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/LP-GPH-06R2.png','2026-02-20 16:10:50',NULL,NULL,'0','admin','2026-02-20 22:10:50','2026-02-20 22:10:50','LP-GPH-06R2','2026-02-20 16:10:50',NULL),
(1652,4,'P1-FSBLK','Base de piso para Bocina Sonos One Negro Flexon','Sonos','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/P1-FSBLK.png','2026-02-20 16:21:32',NULL,NULL,'0','admin','2026-02-20 22:21:32','2026-02-20 22:21:32','P1-FSBLK','2026-02-20 16:21:32',1114.00),
(1653,3,'NST-LUT1-1000','Caja de cable control de iluminacion 1000ft','Lutron','CABLEADO','Bodega General',NULL,NULL,'/img/productos/NST-LUT1-1000.png','2026-02-20 16:32:12',NULL,NULL,'0','admin','2026-02-20 22:32:12','2026-02-20 22:32:12','NST-LUT1-1000','2026-02-20 16:32:12',1113.00),
(1654,14,'LUT-GREEN','Carrete de madera de cable de control de iluminacion Lutron 1000ft','Lutron','CABLEADO','Bodega General',NULL,NULL,'/img/productos/LUTGREN.png','2026-02-20 16:39:29',NULL,NULL,'0','admin','2026-02-20 22:39:29','2026-02-20 22:39:29','LUT-GREEN','2026-02-20 16:39:29',1113.00),
(1655,1,'SPL-150','Subwoofer 15 pulgadas','Klipsch ','AUDIO','Bodega General',NULL,NULL,'/img/productos/SPL-150.png','2026-02-20 16:55:29',NULL,NULL,'0','admin','2026-02-20 22:55:29','2026-02-20 22:55:29','SPL-150','2026-02-20 16:55:29',NULL),
(1656,34,'SSR-LACEBAR-OFF2','Accesorio para rack horizontal, 2 espacios','Strong','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/SR-LACEBAR-OFF2.png','2026-02-20 16:58:50',NULL,NULL,'0','admin','2026-02-20 22:58:50','2026-02-20 22:58:50','SR-LACEBAR-OFF2','2026-02-20 16:58:50',1112.00),
(1657,1,'SR1906LH3G','Gabinete de Pared de 6U con Puerta de Cristal Templado, 455 mm de Profundidad Total, Rack de 19in, Acero Reforzado Color Negro','Linkedpro','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/SR1906LH3G.png','2026-02-21 09:05:20',NULL,NULL,'0','admin','2026-02-21 15:05:20','2026-02-21 15:05:20','SR1906LH3G','2026-02-21 09:05:20',1111.00),
(1658,1,NULL,'Gabinete para Montaje en Pared, Puerta de Cristal Templado, Cuerpo Fijo con Rack 19 pulgadas de 16 Unidades linkedpro (USR1916GFP)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/USR1916GFP.png','2026-02-21 09:06:56',NULL,NULL,'0','admin','2026-02-21 15:06:56','2026-02-21 15:06:56','USR1916GFP','2026-02-21 09:06:56',NULL),
(1659,1,'GABVID4R3','Gabinete Metálico para DVR/NVR con Chapa y llave','Epcom','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/GABVID4R3.png','2026-02-21 09:07:52',NULL,NULL,'0','admin','2026-02-21 15:07:52','2026-02-21 15:07:52','GABVID4R3','2026-02-21 09:07:52',1111.00),
(1660,2,'AVR-S960H','Receptor de cine en casa de 7.2 canales Denon color negro','Denon','AUDIO','Bodega General',NULL,NULL,'/img/productos/AVR-X3400H.png','2026-02-21 09:17:08',NULL,NULL,'0','admin','2026-02-21 15:17:08','2026-02-21 15:17:08','883795005081','2026-02-21 09:17:08',1163.00),
(1661,1,'EDX-610X','Atenuador de 6 canales con borde de ataque/0-10V para controlar ecenas',' Lite -Puter','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/EDX-610X.png','2026-02-21 09:36:12',NULL,NULL,'0','admin','2026-02-21 15:36:12','2026-02-21 15:36:12','EDX-610X','2026-02-21 09:36:12',1110.00),
(1662,1,'SR-10U',' Rack de escritorio de 19 pulgadas y 10U con ángulo ajustable, SolidRack10','Mikrotik','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/SolidRack10.png','2026-02-25 09:57:22',NULL,NULL,'0','admin','2026-02-25 15:57:22','2026-02-25 15:57:22','SR-10U','2026-02-25 09:57:22',1107.00),
(1663,1,'Bose161','Sistema de altavoces para estanteria bose negro','Bose','AUDIO','Bodega General',NULL,NULL,'/img/productos/BOSE161.png','2026-02-25 10:01:44',NULL,NULL,'0','admin','2026-02-25 16:01:44','2026-02-25 16:01:44','BOSE161','2026-02-25 10:01:44',1107.00),
(1664,1,'Tria-Platinum','Repisas de cristal 3 niveles',' Omnimount','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/Tria-Platinum.png','2026-02-25 10:09:41',NULL,NULL,'0','admin','2026-02-25 16:09:41','2026-02-25 16:09:41','Tria-Platinum','2026-02-25 10:09:41',1106.00),
(1665,0,'EPU300RTOL2U','UPS de 3000VA/2700W / Topología On-Line Doble Conversión con Baterías Internas  Entrada y Salida de 120 Vca  Clavija de Entrada NEMA L5-30P  Pantalla LCD Configurable  Formato Rack/Torre','Epcom','NO BREAK ','Bodega General',NULL,NULL,'/img/productos/EPU300RTOL2U.png','2026-02-25 10:17:31',NULL,NULL,'0','admin','2026-02-25 16:17:31','2026-02-25 16:17:31','EPU300RTOL2U','2026-02-25 10:17:31',1104.00),
(1666,2,'C4-IC6.5P','Bocina 6.5 pulgadas, color blanco','Control 4','AUDIO','Bodega General',NULL,NULL,'/img/productos/C4-IC6-5P.png','2026-02-25 10:30:28',NULL,NULL,'0','admin','2026-02-25 16:30:28','2026-02-25 16:30:28','C4-IC6.5P','2026-02-25 10:30:28',1102.00),
(1667,3,NULL,'Extencion de cable starlink (X0042L46AT)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/X0042L46AT.png','2026-02-25 10:38:40',NULL,NULL,'0','admin','2026-02-25 16:38:40','2026-02-25 16:38:40','X0042L46AT','2026-02-25 10:38:40',1056.00),
(1668,1,'Promedia 2.1','Sistema de altavoces para computadora 2.1','Klipsch','AUDIO','Bodega General',NULL,NULL,'/img/productos/Promedia-2-1.png','2026-02-25 10:40:50',NULL,NULL,'0','admin','2026-02-25 16:40:50','2026-02-25 16:40:50','Promedia 2.1','2026-02-25 10:40:50',1102.00),
(1669,4,'FS-2C-WH','Bocina de techo, Freespace, Blanco','Bose','AUDIO','Bodega General',NULL,NULL,'/img/productos/FS-2C-WH.png','2026-02-25 10:44:47',NULL,NULL,'0','admin','2026-02-25 16:44:47','2026-02-25 16:44:47','FS-2C-WH','2026-02-25 10:44:47',1101.00),
(1670,2,'LPORG02','Organizador de Cables Horizontal de 2U para Rack de 19','Linkedpro','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/LPORG02.png','2026-02-25 10:44:53',NULL,NULL,'0','admin','2026-02-25 16:44:53','2026-02-25 16:44:53','LPORG02','2026-02-25 10:44:53',1062.00),
(1671,1,NULL,'Subwoofer de muro klipsch 8 pulgadas color blanco (SSW-4)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/SSW-4.png','2026-02-25 10:45:54',NULL,NULL,'0','admin','2026-02-25 16:45:54','2026-02-25 16:45:54','SSW-4','2026-02-25 10:45:54',1062.00),
(1672,1,NULL,'Subwoofer pasivo sonance sonarray (SR1)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/SR1.png','2026-02-25 10:49:09',NULL,NULL,'0','admin','2026-02-25 16:49:09','2026-02-25 16:49:09','SR1','2026-02-25 10:49:09',1063.00),
(1673,0,'EN-430459-5','Regulador Pc 1000 Ferroresonante 1000Va/1000W 4 Contactos','Sola Basic','NO BREAK ','Bodega General',NULL,NULL,'/img/productos/660077600048.png','2026-02-25 10:50:41',NULL,NULL,'0','admin','2026-02-25 16:50:41','2026-02-25 16:50:41','660077600048','2026-02-25 10:50:41',1063.00),
(1674,1,'CONTROL60PS/TWH','Subwoofer colgante con crossover color blanco )','JBL','AUDIO','Bodega General',NULL,NULL,'/img/productos/Control-60PS-T-WH.png','2026-02-25 10:53:12',NULL,NULL,'0','admin','2026-02-25 16:53:12','2026-02-25 16:53:12','Control 60PS-T-WH','2026-02-25 10:53:12',1064.00),
(1675,1,NULL,'Par de altavoces satélites para techo Bose blanco 2.5 (Freespace Flush Sat)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/17817391894.png','2026-02-25 11:04:27',NULL,NULL,'0','admin','2026-02-25 17:04:27','2026-02-25 17:04:27','17817391894','2026-02-25 11:04:27',1101.00),
(1676,2,'SR_CUSTOM_BASE_20','Estante de suelo Strong custom series, altura 40, profundidad 20 color negro','Strong','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/SR-Custom-Base-20.png','2026-02-25 11:08:27',NULL,NULL,'0','admin','2026-02-25 17:08:27','2026-02-25 17:08:27','SR-Custom-Base-20','2026-02-25 11:08:27',1100.00),
(1677,1,NULL,'Distribuidor de Voltaje Samson 7 tomas (PBPro7)',NULL,'FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/PBPro7.png','2026-02-25 11:21:47',NULL,NULL,'0','admin','2026-02-25 17:21:47','2026-02-25 17:21:47','PBPro7','2026-02-25 11:21:47',1079.00),
(1678,1,'UC-Pro210','Soporte de Televisión Techo  27 a 46','ProMount','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/UC-Pro210.png','2026-02-25 11:26:08',NULL,NULL,'0','admin','2026-02-25 17:26:08','2026-02-25 17:26:08','UC-Pro210','2026-02-25 11:26:08',1077.00),
(1679,2,NULL,'Base de television para muro de 26 a 55 pulgadas sanus morada (SMF421-B8)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/793795537461.png','2026-02-25 11:32:41',NULL,NULL,'0','admin','2026-02-25 17:32:41','2026-02-25 17:32:41','793795537461','2026-02-25 11:32:41',1066.00),
(1680,9,'MODEL:SP5','Soporte para television de hasta 110 pulgadas metalica color negro',' North Bayou','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/6943223102042.png','2026-02-25 11:34:22',NULL,NULL,'0','admin','2026-02-25 17:34:22','2026-02-25 17:34:22','6943223102042','2026-02-25 11:34:22',NULL),
(1681,1,'CL1000VR','Regulador De Voltaje de 1000VA/500W, Entrada NEMA 5-15P, Con 8 Salidas NEMA 5-15R y Supresión de Picos a 245 Joules','CyberPower','NO BREAK ','Bodega General',NULL,NULL,'/img/productos/CL1000VR.png','2026-02-25 11:40:05',NULL,NULL,'0','admin','2026-02-25 17:40:05','2026-02-25 17:40:05','CL1000VR','2026-02-25 11:40:05',1069.00),
(1682,3,'MODEL:96','Soporte de Pared doble brazo movil para monitor/pantalla 45 a 75 pulgadas','North Bayou','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/6943223105944.png','2026-02-25 11:44:37',NULL,NULL,'0','admin','2026-02-25 17:44:37','2026-02-25 17:44:37','6943223105944','2026-02-25 11:44:37',1072.00),
(1683,5,'MI-893','Repisas de Vidrio','Moun-it','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/MI-893.png','2026-02-25 11:47:41',NULL,NULL,'0','admin','2026-02-25 17:47:41','2026-02-25 17:47:41','MI-893','2026-02-25 11:47:41',1077.00),
(1684,1,'051-BKL','Charola para Monitor 19 x15  Negro Acero','North System','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/051-BKL.png','2026-02-25 11:57:19',NULL,NULL,'0','admin','2026-02-25 17:57:19','2026-02-25 17:57:19','051-BKL','2026-02-25 11:57:19',1075.00),
(1685,1,'MKP80511067','Soporte para pantallas de 40 a 75 pulgadas','Onix','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/7500464780744.png','2026-02-25 12:31:44',NULL,NULL,'0','admin','2026-02-25 18:31:44','2026-02-25 18:31:44','7500464780744','2026-02-25 12:31:44',1072.00),
(1686,0,'SLT4-B8','Base de television sanus azul de 42 a 90 pulgadas ()','Sanus','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/793795535542.png','2026-02-25 12:32:58',NULL,NULL,'0','admin','2026-02-25 18:32:58','2026-02-25 18:32:58','793795535542','2026-02-25 12:32:58',1072.00),
(1687,1,'SLT3-B8','Soporte de television con inclinacion de 37 a 90 pulgadas','Sanus','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/793795533180.png','2026-02-25 12:34:16',NULL,NULL,'0','admin','2026-02-25 18:34:16','2026-02-25 18:34:16','793795533180','2026-02-25 12:34:16',1072.00),
(1688,1,'STV-105','Soporte de television hasta 70 pulgadas con brazo articulado','Steren','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/STV-105.png','2026-02-25 12:35:28',NULL,NULL,'0','admin','2026-02-25 18:35:28','2026-02-25 18:35:28','STV-105','2026-02-25 12:35:28',1072.00),
(1689,5,'DSE-1000','Regulador de voltaje con corte y reinicio automático, Titan Power 1000','Titan Power','NO BREAK ','Bodega General',NULL,NULL,'/img/productos/DSE-1000.png','2026-02-25 12:39:50',NULL,NULL,'0','admin','2026-02-25 18:39:50','2026-02-25 18:39:50','DSE-1000','2026-02-25 12:39:50',1074.00),
(1690,1,'DIAMOND8','Bocina tipo torre wharfedale','Wharfedale','AUDIO','Bodega General',NULL,NULL,'/img/productos/DIAMOND8SERIES.png','2026-02-25 13:02:48',NULL,NULL,'0','admin','2026-02-25 19:02:48','2026-02-25 19:02:48','DIAMOND8SERIES','2026-02-25 13:02:48',1075.00),
(1691,2,'SHELLY2LGEN3','Switch shelly de 2 canales con neutro para control de luces con bypass azul celeste','Shelly','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/9800235261644.png','2026-02-25 15:14:57',NULL,NULL,'0','admin','2026-02-25 21:14:57','2026-02-25 21:14:57','9800235261644','2026-02-25 15:14:57',1278.00),
(1692,7,'USR-W610','Conversor Ethernet RS232 RS485 Serial a WiFi Soporte Cliente TCP/Servidor TCP/Servidor UDP/UDP','Psur','RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/USR-W610.png','2026-02-26 16:24:44',NULL,NULL,'0','admin','2026-02-26 22:24:44','2026-02-26 22:24:44','USR-W610','2026-02-26 16:24:44',1355.00),
(1693,6,'EVR-10-005','Regulador2.500W, 2.5kVA, Entrada 120V, Salida 120V, 2 Salidas','Complet','NO BREAK ','Bodega General',NULL,NULL,'/img/productos/ERV-10-005.png','2026-02-27 14:15:02',NULL,NULL,'0','admin','2026-02-27 20:15:02','2026-02-27 20:15:02','ERV-10-005','2026-02-27 14:15:02',NULL),
(1694,0,NULL,'Elevador para proyector con soporte universal y control remoto hangfort (LPD150)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/LPD150.png','2026-02-27 14:18:57',NULL,NULL,'0','admin','2026-02-27 20:18:57','2026-02-27 20:18:57','LPD150','2026-02-27 14:18:57',1089.00),
(1695,285,NULL,'Fusible europeo steren 1.6A (49674)',NULL,'HARDWARE','Bodega General',NULL,NULL,'/img/productos/49674.png','2026-03-02 12:36:41',NULL,NULL,'0','admin','2026-03-02 18:36:41','2026-03-02 18:36:41','49674','2026-03-02 12:36:41',1240.00),
(1696,8,'WS-B-US','Botonera inteligente moes 4 botones color blanca','Moes','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/WS-B-US.png','2026-03-04 15:18:00',NULL,NULL,'0','admin','2026-03-04 21:18:00','2026-03-04 21:18:00','WS-B-US','2026-03-04 15:18:00',1293.00),
(1697,2,'SFL01','Switch inteligente 4 escenas moes color blanco','Moes','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/SFL01.png','2026-03-04 15:22:48',NULL,NULL,'0','admin','2026-03-04 21:22:48','2026-03-04 21:22:48','SFL01','2026-03-04 15:22:48',1293.00),
(1698,3,'ERV-10-006','Regulador de 3kW, 3kVA, Entrada 120V, Salida 120V, 2 Salidas','Complet','NO BREAK ','Bodega General',NULL,NULL,'/img/productos/7501693403978.png','2026-03-06 14:17:15',NULL,NULL,'0','admin','2026-03-06 20:17:15','2026-03-06 20:17:15','7501693403978','2026-03-06 14:17:15',NULL),
(1699,6,'LWT-U-PP-CWH','Tapa palladiom de 2 ventanas color blanco cristalizado','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/LWT-U-PP-CWH.png','2026-03-10 10:11:05',NULL,NULL,'0','admin','2026-03-10 16:11:05','2026-03-10 16:11:05','LWT-U-PP-CWH','2026-03-10 10:11:05',1228.00),
(1700,2,'LTR-15-UBTR-WH','Contacto duplex USB 15A lutron color blanco','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/LTR-15-UBTR-WH.png','2026-03-10 10:15:22',NULL,NULL,'0','admin','2026-03-10 16:15:22','2026-03-10 16:15:22','LTR-15-UBTR-WH','2026-03-10 10:15:22',1263.00),
(1701,1,'RRD-6ND-BI','Dimmer lutron radiora2 color biscuit','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/RRD-6ND-BI.png','2026-03-10 10:49:36',NULL,NULL,'0','admin','2026-03-10 16:49:36','2026-03-10 16:49:36','RRD-6ND-BI','2026-03-10 10:49:36',1267.00),
(1702,1,'RRD-PRO-BI','Dimmer color biscuit RadioRa2 pro','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/RRD-PRO-BI.png','2026-03-10 10:54:57',NULL,NULL,'0','admin','2026-03-10 16:54:57','2026-03-10 16:54:57','RRD-PRO-BI','2026-03-10 10:54:57',1267.00),
(1703,35,'HQWT-U-P4W-LA-E','Botonera de 4 botones homeworksQS color almendra claro (grabada)','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/HQWT-U-P4W-LA-E.png','2026-03-10 10:59:17',NULL,NULL,'0','admin','2026-03-10 16:59:17','2026-03-10 16:59:17','HQWT-U-P4W-LA-E','2026-03-10 10:59:17',NULL),
(1704,30,NULL,'Modulo de potencia dimmer lutron de 4 zonas 120V (LQSE-4A5-120-D)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/LQSE-4A5-120-D.png','2026-03-10 11:06:03',NULL,'Apartados de Jeff | APARTADO:TICKET | FOLIO:Jeff | VENCE:2026-06-30\r\n15 pza para Jeff Yorke','0','admin','2026-03-10 17:06:03','2026-03-10 17:06:03','LQSE-4A5-120-D','2026-03-10 11:06:03',NULL),
(1705,0,NULL,'Amplificador Crown 1002 de dos canales (XLS1002)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/XLS1002.png','2026-03-10 16:04:30',NULL,NULL,'0','admin','2026-03-10 22:04:30','2026-03-10 22:04:30','XLS1002','2026-03-10 16:04:30',1128.00),
(1706,15,'HQWT-U-P4W-WH-E','Botonera lutron de 4 botones homeworksQS color blanco EXTRAS R7 (HQWT-U-P4W-WH-E)','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/HQWT-U-P4W-WH-E.png','2026-03-11 10:06:47',NULL,'JEFF YORKE | APARTADO:TICKET | FOLIO:JEFF YORKE | VENCE:2026-06-30\r\n35 pz PARA  CLIENTE JEFF YORKE','0','admin','2026-03-11 16:06:47','2026-03-11 16:06:47','HQWT-U-P4W-WH-E','2026-03-11 10:06:47',1241.00),
(1707,7,NULL,'Panel lutron 120V DIN y pasantes (PD10-65T-DV)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/PD10-65T-DV.png','2026-03-11 10:09:13',NULL,'PACIFICA-1 | APARTADO:PROYECTO | FOLIO:PACIFICA-1 | VENCE:2026-07-02\r\nSE APARTAN 3pz. PARA PROYECTO PACIFICA-1','3','admin','2026-03-11 16:09:13','2026-03-11 16:09:13','PD10-65T-DV','2026-03-11 10:09:13',NULL),
(1708,23,'FTB522SCA','Roseta para fibra optica para interior 8 Acopladores SC/APC Monomodo','FiberHome','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/FTB522SCA.png','2026-03-11 14:33:11',NULL,NULL,'0','admin','2026-03-11 20:33:11','2026-03-11 20:33:11','FTB522SCA','2026-03-11 14:33:11',1325.00),
(1709,0,NULL,'Subwoofer Cerwin Vega mobile de 12 pulgadas redondo color negro (CUP1204)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/CUP1204.png','2026-03-12 13:25:05',NULL,NULL,'0','admin','2026-03-12 19:25:05','2026-03-12 19:25:05','847169063749','2026-03-12 13:25:05',1145.00),
(1710,1,NULL,'Caja para empotrar en pared de metal control4 (C4-NWB57C-M)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/C4-NWB57C-M.png','2026-03-12 15:25:25',NULL,NULL,'0','admin','2026-03-12 21:25:25','2026-03-12 21:25:25','C4-NWB57C-M','2026-03-12 15:25:25',NULL),
(1711,0,NULL,'Pantalla tactil de muro 8 pulgadas control4 color negro (C4-T4IW8-BL)',NULL,'DOMOTICA','Bodega General',NULL,NULL,'/img/productos/C4-T4IW8-BL.png','2026-03-12 15:27:54',NULL,NULL,'0','admin','2026-03-12 21:27:54','2026-03-12 21:27:54','C4-T4IW8-BL','2026-03-12 15:27:54',NULL),
(1712,3,NULL,'Elevador de pantalla electric lift (TS1000A)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/PL1000_1.png','2026-03-12 15:35:18',NULL,NULL,'0','admin','2026-03-12 21:35:18','2026-03-12 21:35:18','TS1000A','2026-03-12 15:35:18',NULL),
(1713,2,NULL,'Subwoofer pasivo enterrable en jardin para exterior de 12 pulgadas 6 Ohms color verde 70/100V JBL (GSB12-GN)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/GSB12-GN.png','2026-03-13 09:51:06',NULL,NULL,'0','admin','2026-03-13 15:51:06','2026-03-13 15:51:06','691991037818','2026-03-13 09:51:06',NULL),
(1714,0,NULL,'Camara bala hikvision IP 2MP con microfono acusense lite para exterior IP67 PoE entrada micro SD (DS-2CD1023G2-LIU)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/DS-2CD1023G2-LIU.png','2026-03-18 10:25:35',NULL,NULL,'0','admin','2026-03-18 16:25:35','2026-03-18 16:25:35','DS-2CD1023G2-LIU','2026-03-18 10:25:35',1336.00),
(1715,1,NULL,'Botonera hibrida lutron de 5 botones RadioRa2 color biscuit (RRD-H5BRL-BI)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/RRD-H5BRL-BI.png','2026-03-18 10:29:26',NULL,NULL,'0','admin','2026-03-18 16:29:26','2026-03-18 16:29:26','RRD-H5BRL-BI','2026-03-18 10:29:26',1271.00),
(1716,1,NULL,'Kit de muestrario mini bocinas k-array distintos colores (K-LPCOUVETTE I)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/K-LPCOUVETTE-I.png','2026-03-18 10:53:06',NULL,NULL,'0','admin','2026-03-18 16:53:06','2026-03-18 16:53:06','K-LPCOUVETTE I','2026-03-18 10:53:06',1179.00),
(1717,9,'SHELLY2PM','Shelly switch inteligente 2 canales shelly plus 2pm color negro','Shelly','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/SHELLY2PM.png','2026-03-18 10:54:36',NULL,NULL,'0','admin','2026-03-18 16:54:36','2026-03-18 16:54:36','SHELLY2PM','2026-03-18 10:54:36',1278.00),
(1718,12,NULL,'Atenuador de lampara RF lutron table top RadioRa2 color media noche (RRD-3LD-MN)',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2026-03-19 09:39:46',NULL,NULL,'0','admin','2026-03-19 15:39:46','2026-03-19 15:39:46','RRD-3LD-MN','2026-03-19 09:39:46',1265.00),
(1719,1,NULL,'Pantalla manual modelo BTM 90 pulgadas para proyector BCA',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/BTM-90.png','2026-03-20 10:02:22',NULL,NULL,'0','admin','2026-03-20 16:02:22','2026-03-20 16:02:22','BTM-90','2026-03-20 10:02:22',NULL),
(1720,0,NULL,'Pantalla de proyeccion stewart formato 16:9 HD/4K 237x140, postes (marco)',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/ST-SD100HFH3WE.png','2026-03-20 10:16:18',NULL,NULL,'0','admin','2026-03-20 16:16:18','2026-03-20 16:16:18','ST-SD100HFH3WE','2026-03-20 10:16:18',NULL),
(1721,3,NULL,'Lienzo lutron roller 100 123x260 sheer',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/BA-O-R3-ICEBERG.png','2026-03-20 10:21:20',NULL,NULL,'0','admin','2026-03-20 16:21:20','2026-03-20 16:21:20','BAÑO-R3-ICEBERG','2026-03-20 10:21:20',NULL),
(1722,1,NULL,'Lienzo lutron roller 100 122x260 sheer',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/REEMPLAZO-ICEBERG.png','2026-03-20 10:22:41',NULL,NULL,'0','admin','2026-03-20 16:22:41','2026-03-20 16:22:41','REEMPLAZO-ICEBERG','2026-03-20 10:22:41',NULL),
(1723,1,NULL,'Cortina roller 100 black out 3.38x2',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/K33-HUESPEDES.png','2026-03-20 10:24:22',NULL,NULL,'0','admin','2026-03-20 16:24:22','2026-03-20 16:24:22','K33-HUESPEDES','2026-03-20 10:24:22',NULL),
(1724,1,NULL,'Cortina roller 100 sheer 3.38x2',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/K33-HUESPEDES1.png','2026-03-20 10:25:58',NULL,NULL,'0','admin','2026-03-20 16:25:58','2026-03-20 16:25:58','K33-HUESPEDES1','2026-03-20 10:25:58',NULL),
(1725,2,NULL,'Cortina con motor tecnolite sunscreen HQ 1% clark motor=0.93cm tela=90cm largo=2.90cm',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/CORT-MOT-0-93-90-2-9.png','2026-03-20 10:36:44',NULL,NULL,'0','admin','2026-03-20 16:36:44','2026-03-20 16:36:44','CORT-MOT-0.93-90-2.9','2026-03-20 10:36:44',NULL),
(1726,1,NULL,'Cortina con motor tecnolite sunscreen HQ 1% clark motor=0.95cm tela=92.5cm largo=2.90cm',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/CORT-MOT-0-95-92-5-2.png','2026-03-20 10:40:19',NULL,NULL,'0','admin','2026-03-20 16:40:19','2026-03-20 16:40:19','CORT-MOT-0.95-92.5-2','2026-03-20 10:40:19',NULL),
(1727,1,NULL,'Cortina con motor tecnolite sunscreen HQ 1% clark motor=0.94cm tela=91cm largo=2.90cm',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/CORT-MOT-0-94-91-2-9.png','2026-03-20 10:47:10',NULL,NULL,'0','admin','2026-03-20 16:47:10','2026-03-20 16:47:10','CORT-MOT-0.94-91-2.9','2026-03-20 10:47:10',NULL),
(1728,3,NULL,'Cortina esclava tecnolite sunscreen HQ 1% clark ancho=0.93 largo=2.90',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/CORT-ESCL-0-93-2-90.png','2026-03-20 10:56:03',NULL,NULL,'0','admin','2026-03-20 16:56:03','2026-03-20 16:56:03','CORT-ESCL-0.93-2.90','2026-03-20 10:56:03',NULL),
(1729,1,NULL,'Cortina esclava tecnolite sunscreen HQ 1% clark ancho=0.92.5 largo=2.90',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/CORT-ESCL-0-92-5-2-9.png','2026-03-20 10:59:59',NULL,NULL,'0','admin','2026-03-20 16:59:59','2026-03-20 16:59:59','CORT-ESCL-0.92.5-2.9','2026-03-20 10:59:59',NULL),
(1730,1,NULL,'Cortina esclava tecnolite sunscreen HQ 1% clark ancho=0.95 largo=2.90',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/CORT-ESCL-0-95-2-90.png','2026-03-20 11:02:54',NULL,NULL,'0','admin','2026-03-20 17:02:54','2026-03-20 17:02:54','CORT-ESCL-0.95-2.90','2026-03-20 11:02:54',NULL),
(1731,1,NULL,'Cortina esclava tecnolite sunscreen HQ 1% clark ancho=0.91 largo=2.90',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/CORT-ESCL-0-91-2-90.png','2026-03-20 11:09:36',NULL,NULL,'0','admin','2026-03-20 17:09:36','2026-03-20 17:09:36','CORT-ESCL-0.91-2.90','2026-03-20 11:09:36',NULL),
(1732,1,NULL,'Cortina esclava tecnolite sunscreen HQ 1% clark ancho=0.90 largo=2.90',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/CORT-ESCL-0-90-2-90.png','2026-03-20 11:10:42',NULL,NULL,'0','admin','2026-03-20 17:10:42','2026-03-20 17:10:42','CORT-ESCL-0.90-2.90','2026-03-20 11:10:42',NULL),
(1733,1,NULL,'Cortina esclava tecnolite sunscreen HQ 1% clark ancho=0.88 largo=2.90',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/CORT-ESCL-0-88-2-90.png','2026-03-20 11:11:23',NULL,NULL,'0','admin','2026-03-20 17:11:23','2026-03-20 17:11:23','CORT-ESCL-0.88-2.90','2026-03-20 11:11:23',NULL),
(1734,1,NULL,'Cortina esclava tecnolite sunscreen HQ 1% clark ancho=0.86 largo=2.90',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/CORT-ESCL-0-86-2-90.png','2026-03-20 11:11:47',NULL,NULL,'0','admin','2026-03-20 17:11:47','2026-03-20 17:11:47','CORT-ESCL-0.86-2.90','2026-03-20 11:11:47',NULL),
(1735,1,NULL,'Cortina esclava tecnolite sunscreen HQ 1% clark ancho=0.87 largo=3',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/CORT-ESCL-0-87-3.png','2026-03-20 11:12:44',NULL,NULL,'0','admin','2026-03-20 17:12:44','2026-03-20 17:12:44','CORT-ESCL-0.87-3','2026-03-20 11:12:44',NULL),
(1736,4,NULL,'Galera tecnolite sunscreen HQ 1% 275x20',NULL,'CORTINA','Bodega General',NULL,NULL,'/img/productos/GAL-SUNS-275X20.png','2026-03-20 11:14:30',NULL,NULL,'0','admin','2026-03-20 17:14:30','2026-03-20 17:14:30','GAL-SUNS-275X20','2026-03-20 11:14:30',NULL),
(1737,0,'HQWD-W6BRL-WH','Botonera de 6 botones homeworks QS color blanca','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/HQWD-W6BRL-WH.png','2026-03-24 09:22:52',NULL,NULL,'0','admin','2026-03-24 15:22:52','2026-03-24 15:22:52','HQWD-W6BRL-WH','2026-03-24 09:22:52',NULL),
(1738,0,NULL,'Router Balanceador ruijie, 200 clientes 600Mbps (RG-EG209GS)',NULL,'RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/RG-EG209GS.png','2026-03-24 09:37:25',NULL,NULL,'0','admin','2026-03-24 15:37:25','2026-03-24 15:37:25','RG-EG209GS','2026-03-24 09:37:25',NULL),
(1739,20,'CA-6PF-WH','Marco de 6 puertos personalizable para conectores de red, fibra óptica, coaxial y telefonía, línea Claro, color blanco','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/27557061889.png','2026-04-15 15:38:31',NULL,NULL,'0','admin','2026-04-15 21:38:31','2026-04-15 21:38:31','27557061889','2026-04-15 15:38:31',1273.00),
(1740,2,NULL,'Contacto dúplex USB de 15A con protección para niños Lutron Claro, color almendra claro (CAR-15-UBTR-WH)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/784276166096.png','2026-04-15 16:24:51',NULL,NULL,'0','admin','2026-04-15 22:24:51','2026-04-15 22:24:51','784276166096','2026-04-15 16:24:51',1263.00),
(1741,1,NULL,'CORTINAS SIVOIA QS ROLLER 64, QMR-R64-C',NULL,'CORTINA','Bodega General',NULL,NULL,NULL,'2026-04-16 11:37:22',NULL,NULL,'0','admin','2026-04-16 17:37:22','2026-04-16 17:37:22','32964890','2026-04-16 11:37:22',NULL),
(1742,1,NULL,'MEMORIA MICRO ESPECIALIZADO CCTV, HSTFE1/128 G',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/6974202726331.jpg','2026-04-16 15:58:32',NULL,NULL,'0','admin','2026-04-16 21:58:32','2026-04-16 21:58:32','6974202726331','2026-04-16 15:58:32',1336.00),
(1743,2,NULL,'CAMARA WIFI 3K AUD DE DOS VIAS, VISION A COLOR/ EXTERIOR, CS-H3 3K',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6941545617558.jpg','2026-04-16 16:03:40',NULL,NULL,'0','admin','2026-04-16 22:03:40','2026-04-16 22:03:40','6941545617558','2026-04-16 16:03:40',1336.00),
(1744,42,NULL,'Dimmer Inteligente de pared Zigbee, Enerware, color blanco (ZB500D)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/ZB500D.jpg','2026-04-16 16:04:01',NULL,NULL,'0','admin','2026-04-16 22:04:01','2026-04-16 22:04:01','ZB500D','2026-04-16 16:04:01',1248.00),
(1745,11,NULL,'Sensor tipo PIR para activar una carga al detectar un paso de un peatón, Sigma (Pir-8)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/Pir-8.jpg','2026-04-16 16:20:36',NULL,NULL,'0','admin','2026-04-16 22:20:36','2026-04-16 22:20:36','Pir-8','2026-04-16 16:20:36',1276.00),
(1746,9,'Shelly 1','Relé inteligente de contacto seco Gen3 de 16 A (Wi-Fi y Matter) Azul, X1','shelly','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/3800235262009.jpg','2026-04-16 16:30:36',NULL,NULL,'0','admin','2026-04-16 22:30:36','2026-04-16 22:30:36','3800235261767','2026-04-16 16:30:36',1278.00),
(1747,0,NULL,'SONOS MOVE 2 BLACK',NULL,'AUDIO','Bodega General',NULL,NULL,NULL,'2026-04-17 09:00:15',NULL,NULL,'0','admin','2026-04-17 15:00:15','2026-04-17 15:00:15','840136808794','2026-04-17 09:00:15',1191.00),
(1748,0,NULL,'WALL HOOK SONOS MOVE BASE BLACK',NULL,'ACCESORIO','Bodega General',NULL,NULL,NULL,'2026-04-17 09:03:51',NULL,NULL,'0','admin','2026-04-17 15:03:51','2026-04-17 15:03:51','840136800477','2026-04-17 09:03:51',1191.00),
(1749,20,' AP271E ','Punto de Acceso Inalámbrico eKit Wi-Fi 7 / 3.57 Gbps, Smart Antenna,  Montaje en Pared, Seguridad WPA3, Gestión Local y en la Nube Gratis, Puerto 2.5GE + 4 GE','Huawei','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/886598347778.jpg','2026-04-17 09:10:20',NULL,NULL,'0','admin','2026-04-17 15:10:20','2026-04-17 15:10:20','886598347778','2026-04-17 09:10:20',1299.00),
(1750,0,'AP772E','Punto de Acceso Wi-Fi 7 para Exterior, Sectorial 70&ordm; 1,024 Usuarios, Puerto SFP+ Hasta 6.45 Gbps, Gestión Gratuita desde la Nube \\r\\n','Huawei','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/886598348454.jpg','2026-04-17 09:12:47',NULL,NULL,'0','admin','2026-04-17 15:12:47','2026-04-17 15:12:47','886598348454','2026-04-17 09:12:47',1310.00),
(1751,0,NULL,'ROUTER EKIT VPN WI-FI 7, HASTA 2 GBPS, AR180PRO',NULL,'RED E INTERNET','Bodega General',NULL,NULL,NULL,'2026-04-17 17:01:46',NULL,NULL,'0','admin','2026-04-17 23:01:46','2026-04-17 23:01:46','886598350372','2026-04-17 17:01:46',1314.00),
(1752,0,'S220S24P4J X','Switch de Acceso Gigabit Administrable PoE Capa 2 / 24 puertos 10/100/1000 Mbps (PoE), 4 Puertos 2.5GE SFP Uplink, ERPS, IMGP Snooping, DHCP Snooping, PoE Perpetuo, 400 W, Administración Nube Gratis','Huawei','SWITCH','Bodega General',NULL,NULL,'/img/productos/886598345248.jpg','2026-04-17 17:04:09',NULL,NULL,'0','admin','2026-04-17 23:04:09','2026-04-17 23:04:09','886598345248','2026-04-17 17:04:09',1310.00),
(1753,0,NULL,'Altavoces redondos de 8\\\" para techo, con parrilla micro blanca, Sonance (VX80R)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/041093960895.jpg','2026-04-20 09:31:46',NULL,NULL,'0','admin','2026-04-20 15:31:46','2026-04-20 15:31:46','041093960895','2026-04-20 09:31:46',NULL),
(1754,4,NULL,'Altavoz pasivo de fuente puntual de 3 vías de 15 pulgadas, JBL, color negro (SRX835)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/69199100706.jpg','2026-04-20 15:25:15',NULL,NULL,'0','admin','2026-04-20 21:25:15','2026-04-20 21:25:15','69199100706','2026-04-20 15:25:15',NULL),
(1755,1,NULL,'ROAM2BLK - Sonos Roam Inteligente y recargable Negro, so-roam-2 b',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840136811107.jpg','2026-04-20 16:53:42',NULL,NULL,'0','admin','2026-04-20 22:53:42','2026-04-20 22:53:42','840136811107','2026-04-20 16:53:42',1185.00),
(1756,3,NULL,'CROWN XLS SERIES POWER AMPLIFIER, 350W, 05-XLS 1002',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/691991000935.jpg','2026-04-20 16:56:21',NULL,NULL,'0','admin','2026-04-20 22:56:21','2026-04-20 22:56:21','691991000935','2026-04-20 16:56:21',NULL),
(1757,0,NULL,'Base para proyector universal Strong (SM-PROJ-M)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/SM-PROJ-M.jpg','2026-04-21 12:07:55',NULL,NULL,'0','admin','2026-04-21 18:07:55','2026-04-21 18:07:55','SM-PROJ-M','2026-04-21 12:07:55',1191.00),
(1758,0,NULL,'Bocina Klipsch THX-502-L',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/THX-502-L.jpg','2026-04-22 09:44:47',NULL,NULL,'0','admin','2026-04-22 15:44:47','2026-04-22 15:44:47','THX-502-L','2026-04-22 09:44:47',NULL),
(1759,0,NULL,'Cámara de seguridad wifi para exterior Ezviz (CS-H3-3K)',NULL,'VIDEO','Bodega General',NULL,NULL,NULL,'2026-04-22 12:18:09',NULL,NULL,'0','admin','2026-04-22 18:18:09','2026-04-22 18:18:09','CS-H3-3K','2026-04-22 12:18:09',NULL),
(1760,0,NULL,' MOTORTUBULAR35MMWIFI6NBidireccional,  SHA35-6/33-WIFIBI',NULL,'CORTINA','Bodega General',NULL,NULL,NULL,'2026-04-22 15:53:53',NULL,NULL,'0','admin','2026-04-22 21:53:53','2026-04-22 21:53:53','26101112','2026-04-22 15:53:53',1114.00),
(1761,13,'LPPSLIM3WH','Patchcord delgado linkedpro de 3M cat6 UTP color Blanco','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPPSLIM3WH.png','2026-04-24 16:38:27',NULL,NULL,'0','admin','2026-04-24 22:38:27','2026-04-24 22:38:27','LPPSLIM3WH','2026-04-24 16:38:27',1351.00),
(1762,6,'LPPSLIM15WH','Patchcord delgado linkedpro de 1.5M cat6 UTP color Blanco','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPPSLIM15WH.jpg','2026-04-24 16:45:19',NULL,NULL,'0','admin','2026-04-24 22:45:19','2026-04-24 22:45:19','LPPSLIM15WH','2026-04-24 16:45:19',1351.00),
(1763,88,'LPUT6040CS','Patchcord linkedpro de 0.4M cat6 UTP color Gris','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPUT6040CS.jpg','2026-04-24 16:55:59',NULL,NULL,'0','admin','2026-04-24 22:55:59','2026-04-24 22:55:59','LPUT6040CS','2026-04-24 16:55:59',1351.00),
(1764,249,'LPUT6050WH','Patchcord delgado linkedpro de 0.5M cat6 UTP color Blanco','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPUT6050WH.jpg','2026-04-25 10:27:51',NULL,NULL,'0','admin','2026-04-25 16:27:51','2026-04-25 16:27:51','LPUT6050WH','2026-04-25 10:27:51',1351.00),
(1765,16,'LPUTGA500WH28','Patchcord delgado linkedpro de 5.0M cat6 UTP color Blanco','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPUTGA500WH28.jpg','2026-04-25 10:32:50',NULL,NULL,'0','admin','2026-04-25 16:32:50','2026-04-25 16:32:50','LPUTGA500WH28','2026-04-25 10:32:50',1351.00),
(1766,254,'LPUTG020GY28','Patchcord delgado linkedpro de 0.2M cat6 UTP color Gris','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPUTG020GY28.jpg','2026-04-25 10:52:50',NULL,NULL,'0','admin','2026-04-25 16:52:50','2026-04-25 16:52:50','LPUTG020GY28','2026-04-25 10:52:50',1351.00),
(1767,68,'LPUT6030BK28','Patchcord delgado linkedpro de 0.3M cat6 UTP color Negro','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPUT6030BK28.jpg','2026-04-27 13:20:59',NULL,NULL,'0','admin','2026-04-27 19:20:59','2026-04-27 19:20:59','LPUT6030BK28','2026-04-27 13:20:59',1351.00),
(1768,9,'LPSTPGA700BU','Patch Cord Cat6A 10G Blindado 7.0 Metros color Azul','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPSTPGA700BU.jpg','2026-04-27 13:26:53',NULL,NULL,'0','admin','2026-04-27 19:26:53','2026-04-27 19:26:53','LPSTPGA700BU','2026-04-27 13:26:53',1351.00),
(1769,8,'LPUTG100WH28','Patchcord delgado linkedpro de 1.0M cat6 UTP color Blanco','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPUTG100WH28.jpg','2026-04-27 14:16:05',NULL,NULL,'0','admin','2026-04-27 20:16:05','2026-04-27 20:16:05','LPUTG100WH28','2026-04-27 14:16:05',1351.00),
(1770,3,'LPUTG100BK28','Patchcord delgado linkedpro de 1.0M cat6 UTP color Negro','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPUTG100BK28.jpg','2026-04-27 14:19:32',NULL,NULL,'0','admin','2026-04-27 20:19:32','2026-04-27 20:19:32','LPUTG100BK28','2026-04-27 14:19:32',1351.00),
(1771,326,'LPUT6020WH28','Patchcord delgado linkedpro de 0.2M cat6 UTP color Blanco','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LPUT6020WH28.jpg','2026-04-27 14:25:50',NULL,NULL,'0','admin','2026-04-27 20:25:50','2026-04-27 20:25:50','LPUT6020WH28','2026-04-27 14:25:50',1351.00),
(1772,1,NULL,'Caja de empotrar doble 2N (2N9155015)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/8595159504674.png','2026-04-27 16:54:12',NULL,NULL,'0','admin','2026-04-27 22:54:12','2026-04-27 22:54:12','8595159504674','2026-04-27 16:54:12',NULL),
(1773,0,'LPORG01','Organizador de Cables Horizontal de 1U para Rack de 19 pulgadas,','Linkedpro','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/LPORG01.jpg','2026-04-28 09:44:07',NULL,NULL,'0','admin','2026-04-28 15:44:07','2026-04-28 15:44:07','LPORG01','2026-04-28 09:44:07',1062.00),
(1774,3,NULL,'Adaptador Poe Pasivo Cable Inyector Y Divisor Para Camara Ip',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/inyector-poe-pasivo.jpg','2026-04-28 13:34:18',NULL,NULL,'0','admin','2026-04-28 19:34:18','2026-04-28 19:34:18','inyector poe pasivo','2026-04-28 13:34:18',1353.00),
(1775,0,NULL,'Radio profesional 16 canales, Tx Pro, color negro (Tx-320)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/Tx-320.jpg','2026-04-30 11:10:48',NULL,NULL,'0','admin','2026-04-30 17:10:48','2026-04-30 17:10:48','Tx-320','2026-04-30 11:10:48',NULL),
(1776,2,NULL,'Caja de Cable Eléctrico THW CDC 1x14 100 mt Blanco (14BC-WH)\\\\',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/14BC-WH.jpg','2026-04-30 11:34:34',NULL,NULL,'0','admin','2026-04-30 17:34:34','2026-04-30 17:34:34','14BC-WH','2026-04-30 11:34:34',NULL),
(1777,1,NULL,'Cable Eléctrico AWG 14 100 mts, verde Condulac  (14AWG-Green)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/14AWG-Green.jpg','2026-04-30 15:44:17',NULL,NULL,'0','admin','2026-04-30 21:44:17','2026-04-30 21:44:17','14AWG-Green','2026-04-30 15:44:17',NULL),
(1778,1,NULL,'Cable Eléctrico THW CDC 1x12, 100 mts, Blanco (120BC-WH)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/120BC-WH.jpg','2026-04-30 15:49:36',NULL,NULL,'0','admin','2026-04-30 21:49:36','2026-04-30 21:49:36','120BC-WH','2026-04-30 15:49:36',NULL),
(1779,4,NULL,'Cable Electrico rojo, 16 AWG, Kobrex, 100 mts  (16AWG-Red-kobrex)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/16AWG-Red-kobrex.jpg','2026-04-30 16:16:41',NULL,NULL,'0','admin','2026-04-30 22:16:41','2026-04-30 22:16:41','16AWG-Red-kobrex','2026-04-30 16:16:41',NULL),
(1780,3,NULL,'Kit de casco amarillo con cubrenucas (Kit-hd07sb)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/7510800397482.jpg','2026-05-02 09:37:12',NULL,NULL,'0','admin','2026-05-02 15:37:12','2026-05-02 15:37:12','7510800397482','2026-05-02 09:37:12',NULL),
(1781,1,NULL,'Cable Electrico THW CDC negro 1x14, 100 mts, (14NG)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/14NG.jpg','2026-05-02 09:48:05',NULL,NULL,'0','admin','2026-05-02 15:48:05','2026-05-02 15:48:05','14NG','2026-05-02 09:48:05',NULL),
(1782,72,NULL,'Bibina de cable uso rudo, 100 mt, 3x100 16 DCD \\\\r\\\\n  (CDC Wire)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/CDC-Wire.jpg','2026-05-02 09:55:52',NULL,NULL,'0','admin','2026-05-02 15:55:52','2026-05-02 15:55:52','CDC Wire','2026-05-02 09:55:52',NULL),
(1783,1,NULL,'Cincho de nylon, 200 mm, 7\\\\\\\'\\\\\\\' 7x8, 100 pz, Panduit, Negro  (S8-18-C0)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/S8-18-C0.jpg','2026-05-02 10:32:21',NULL,NULL,'0','admin','2026-05-02 16:32:21','2026-05-02 16:32:21','S8-18-C0','2026-05-02 10:32:21',NULL),
(1784,1,NULL,'Cincho de nylon, 140 mm, 3,2  100 pz, negro, Thorsman  (TH-140-3-2-34)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/TH-140-3-2-34.jpg','2026-05-02 10:39:29',NULL,NULL,'0','admin','2026-05-02 16:39:29','2026-05-02 16:39:29','TH-140-3-2-34','2026-05-02 10:39:29',NULL),
(1785,1,NULL,'Tornillo para rack 10x30 (200) Strong\\\\r\\\\n  (SR-SCREWS-200JAR)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/SR-SCREWS-200JAR.jpg','2026-05-02 10:44:20',NULL,NULL,'0','admin','2026-05-02 16:44:20','2026-05-02 16:44:20','SR-SCREWS-200JAR','2026-05-02 10:44:20',NULL),
(1786,20,'AP371','Punto de Acceso Wi-Fi 7 / 3.57 Gbps / MU-MIMO 2x2 (2.4GH y 5GHz), Smart  Huawei color blanco','Huawei','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/AP371.jpg','2026-05-04 09:15:20',NULL,NULL,'0','admin','2026-05-04 15:15:20','2026-05-04 15:15:20','AP371','2026-05-04 09:15:20',1305.00),
(1787,1,NULL,'Juego de 48 Tornillos con 48 Arandelas y 48 Tuercas Enjauladas para Rack LINKED PRO (LPTAT48)\\\\r\\\\n',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/LPTAT48.jpg','2026-05-04 13:29:30',NULL,NULL,'0','admin','2026-05-04 19:29:30','2026-05-04 19:29:30','LPTAT48','2026-05-04 13:29:30',NULL),
(1788,1,NULL,'Probador de video, pantalla 4.3 (EPMONTVI3.0)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/EPMONTVI3-0.jpg','2026-05-04 13:33:46',NULL,NULL,'0','admin','2026-05-04 19:33:46','2026-05-04 19:33:46','EPMONTVI3.0','2026-05-04 13:33:46',NULL),
(1789,2,NULL,' Organizador de plástico con separadores de 15 \\\\\\\'\\\\\\\' Bellota (5028-10)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/5028-10.jpg','2026-05-04 13:43:49',NULL,NULL,'0','admin','2026-05-04 19:43:49','2026-05-04 19:43:49','5028-10','2026-05-04 13:43:49',NULL),
(1790,1,NULL,'Organizador de plástico con separadores reforzado de 17 \\\\\\\'\\\\\\\' Truper (ORG-17X)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/ORG-17X.jpg','2026-05-04 13:50:05',NULL,NULL,'0','admin','2026-05-04 19:50:05','2026-05-04 19:50:05','ORG-17X','2026-05-04 13:50:05',NULL),
(1791,7,NULL,'Barra de silicón multiusos grueso 11.2x 22 cm 6pz, Parisina (11.2x22)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/11-2x22.png','2026-05-04 13:56:58',NULL,NULL,'0','admin','2026-05-04 19:56:58','2026-05-04 19:56:58','11.2x22','2026-05-04 13:56:58',NULL),
(1792,1,NULL,'Pistola Fijación Hilti (Dx 450)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/DX-450.jpg','2026-05-04 14:06:49',NULL,NULL,'0','admin','2026-05-04 20:06:49','2026-05-04 20:06:49','DX 450','2026-05-04 14:06:49',NULL),
(1793,1,NULL,'Probador de polaridad y fase para audio, The american Cable Company (PHI-3)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/PHI-3.jpg','2026-05-04 16:13:36',NULL,NULL,'0','admin','2026-05-04 22:13:36','2026-05-04 22:13:36','PHI-3','2026-05-04 16:13:36',NULL),
(1794,0,NULL,'Tira de luces tipo LED, GENG XIN (2835-120P-5M)',NULL,'ILUMINACION','Bodega General',NULL,NULL,'/img/productos/2835-120-5M.jpg','2026-05-05 10:46:55',NULL,NULL,'0','admin','2026-05-05 16:46:55','2026-05-05 16:46:55','2835-120-5M','2026-05-05 10:46:55',NULL),
(1795,10,'U7-IW','Punto de Acceso, U7 In-Wall WiFi 7 Doble Banda para Interiores','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/810084698754.jpg','2026-05-06 09:38:50',NULL,NULL,'0','admin','2026-05-06 15:38:50','2026-05-06 15:38:50','810084698754','2026-05-06 09:38:50',1310.00),
(1796,1,NULL,'Llave hexagonal de extremo de bola de 7 pulgadas, extra fuerte, 6mm (S2-rainbow)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/S2-rainbow.jpg','2026-05-06 11:07:17',NULL,NULL,'0','admin','2026-05-06 17:07:17','2026-05-06 17:07:17','S2-rainbow','2026-05-06 11:07:17',NULL),
(1797,1,NULL,'Llave hexagonal de 13 pulgadas, extra fuerte, 5 mm (S4-Rainbow)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/S4-Rainbow.jpg','2026-05-06 11:19:25',NULL,NULL,'0','admin','2026-05-06 17:19:25','2026-05-06 17:19:25','S4-Rainbow','2026-05-06 11:19:25',NULL),
(1798,4,NULL,'Gabinete Metalico con platina 60x40x25, Anclo (G4A 604025-UL)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/7501575313517.jpg','2026-05-06 14:05:34',NULL,NULL,'0','admin','2026-05-06 20:05:34','2026-05-06 20:05:34','7501575313517','2026-05-06 14:05:34',1005.00),
(1799,4,NULL,'Regleta vertical Series 100 de 12 contactos para montaje en rack (pieza) Negro, Wattbox (01-WB-100-VPS-12)',NULL,'CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/01-WB-100-VPS-12.jpg','2026-05-07 14:25:36',NULL,NULL,'0','admin','2026-05-07 20:25:36','2026-05-07 20:25:36','01-WB-100-VPS-12','2026-05-07 14:25:36',1076.00),
(1800,1,NULL,'Llave hexagonal de 18 pulgadas, extra fuerte, 5 mm, color negro (S6-Rainbow)',NULL,'INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/S6-Rainbow.jpg','2026-05-07 14:31:23',NULL,NULL,'0','admin','2026-05-07 20:31:23','2026-05-07 20:31:23','S6-Rainbow','2026-05-07 14:31:23',NULL),
(1801,0,'LRS-100-249','Fuente de poder regulada  MW LRS 24V LRS-100-24 4,5A','Mean Well','FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/LRS-100-24.jpg','2026-05-08 10:20:48',NULL,NULL,'0','admin','2026-05-08 16:20:48','2026-05-08 16:20:48','LRS-100-24','2026-05-08 10:20:48',NULL),
(1802,74,'LP-FOAD-6137U','Módulo Acoplador de Fibra Óptica Duplex LC/UPC a LC/UPC Compatible con Fibra Monomodo','Linkedpro','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/LP-FOAD-6137U.jpg','2026-05-08 10:34:53',NULL,NULL,'0','admin','2026-05-08 16:34:53','2026-05-08 16:34:53','LP-FOAD-6137U','2026-05-08 10:34:53',1326.00),
(1803,2,NULL,' NVR12MP/ 8CANALESIP/H.265+ /SOP1HDD(NOINC) ACUSENSE, ACUSEARC, /RECONOCIMIENTOFACIAL, (DS-7608NI-K2/8P)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/46171621.jpg','2026-05-08 11:26:57',NULL,NULL,'0','admin','2026-05-08 17:26:57','2026-09-05 21:22:43','46171621','2026-05-08 11:26:57',NULL),
(1804,8,'WMPFSE','Organizador de Cables Horizontal, Sencillo (Solo Frontal), Para Rack de 19in, 1UR','Panduit','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/074983377992.jpg','2026-05-08 11:30:36',NULL,NULL,'0','admin','2026-05-08 17:30:36','2026-05-08 17:30:36','074983377992','2026-05-08 11:30:36',1051.00),
(1805,4,'LP-FO-6049B','Jumper de Fibra Óptica Monomodo LC/LC Duplex de 1 Metro (3.28 Pies)','Linkedpro','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/LP-FO-6049B.jpg','2026-05-08 12:01:00',NULL,NULL,'0','admin','2026-05-08 18:01:00','2026-05-08 18:01:00','LP-FO-6049B','2026-05-08 12:01:00',1326.00),
(1806,9,'LP-FO-6060B','Jumper de Fibra Óptica Monomodo LC/SC Duplex de 1 Metro (3.28 Pies)','Linkedpro','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/LP-FO-6060B.jpg','2026-05-08 12:05:41',NULL,NULL,'0','admin','2026-05-08 18:05:41','2026-05-08 18:05:41','LP-FO-6060B','2026-05-08 12:05:41',1326.00),
(1807,24,'LPSM3LCULCUS1','Jumper de Fibra Óptica Monomodo LC,UPC-LC/UPC Simplex de 1 Metro (3.28 Pies), 3mm','Linkedpro','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/LPSM3LCULCUS1.jpg','2026-05-08 12:13:08',NULL,NULL,'0','admin','2026-05-08 18:13:08','2026-05-08 18:13:08','LPSM3LCULCUS1','2026-05-08 12:13:08',1326.00),
(1808,0,NULL,'Frente de botonera Lutron de 1 boton, color negro,  (RKD-W1B-MN-E)',NULL,'ILUMINACION','Bodega General',NULL,NULL,NULL,'2026-05-08 14:25:20',NULL,NULL,'0','admin','2026-05-08 20:25:20','2026-05-08 20:25:20','RKD-W1B-MN-E','2026-05-08 14:25:20',1262.00),
(1809,0,NULL,' DOMO IP 4MP, LENTE 2.8MM,  WIFI, 30MTS IREXIR BOCINA Y MICROFONO INTEGRADO, IP66, H.265 (DS-2CV214162-IDW 4MP)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/69422160463490.jpg','2026-05-08 16:00:40',NULL,NULL,'0','admin','2026-05-08 22:00:40','2026-05-08 22:00:40','69422160463490','2026-05-08 16:00:40',NULL),
(1810,2,'USW-Pro-48-POE','UniFi Switch  Gen2, Capa 3 de 48 puertos PoE 802.3at/bt + 4 puertos 1/10G SFP+, 600W, pantalla informativa','Ubiquiti','SWITCH','Bodega General',NULL,NULL,'/img/productos/USW-Pro-48-POE.jpg','2026-05-11 13:17:20',NULL,NULL,'0','admin','2026-05-11 19:17:20','2026-05-11 19:17:20','USW-Pro-48-POE','2026-05-11 13:17:20',NULL),
(1811,1,'SM-ADJPOLE-S','Strong Universal Fit Adjustable Extension Poles Small 9-12\\\" (Tubo para base de proyector), color Blanco','Strong','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/01-SM-ADJP-OLE-S-WH.jpg','2026-05-13 16:23:34',NULL,NULL,'0','admin','2026-05-13 22:23:34','2026-05-13 22:23:34','842822025294','2026-05-13 16:23:34',1165.00),
(1812,1,NULL,'Bose-Professional FreeSpace 360P Series II outdoor speaker (02-FS-360-P)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/840409500622.png','2026-05-13 16:56:11',NULL,NULL,'0','admin','2026-05-13 22:56:11','2026-05-13 22:56:11','840409500622','2026-05-13 16:56:11',1181.00),
(1813,1,NULL,'Cámara de red bala fija de audio exterior de 4 MP (DS-2CV2041G2-IDW)',NULL,'VIDEO','Bodega General',NULL,NULL,'/img/productos/6942160444949.jpg','2026-05-16 09:09:23',NULL,NULL,'0','admin','2026-05-16 15:09:23','2026-09-05 20:54:04','6942160444949','2026-05-16 09:09:23',1336.00),
(1814,79,'NK688MBU','Conector Jack Estilo 110 (de Impacto), Tipo Keystone, Categoría 6, de 8 Posiciones y 8 Cables, Color Azul','Panduit','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/NK688MBU.jpg','2026-05-18 10:25:59',NULL,NULL,'0','admin','2026-05-18 16:25:59','2026-05-18 16:25:59','NK688MBU','2026-05-18 10:25:59',NULL),
(1815,0,'RKD-W5BRL-WH-E','FRENTE DE BOTONERA SEETOUCH LUTRON','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/02755750453.jpg','2026-05-18 13:46:29',NULL,NULL,'0','admin','2026-05-18 19:46:29','2026-05-18 19:46:29','02755750453','2026-05-18 13:46:29',NULL),
(1816,2,NULL,'Sombra de aislamiento exterior 6x4 reforzada de cuerda negra (GROU49224)',NULL,'ACCESORIO','Bodega General',NULL,NULL,'/img/productos/GROU49224.jpg','2026-05-19 14:43:58',NULL,NULL,'0','admin','2026-05-19 20:43:58','2026-05-19 20:43:58','GROU49224','2026-05-19 14:43:58',1041.00),
(1817,4,NULL,'Altavoz multipropósito bidireccional de 15 pulgadas, JBL, color negro   (SRX815P)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/SRX815P.jpg','2026-05-19 15:27:38',NULL,NULL,'0','admin','2026-05-19 21:27:38','2026-05-19 21:27:38','SRX815P','2026-05-19 15:27:38',NULL),
(1818,2,NULL,'Subwoofer Activo de Gira Profesional de 18\\\" con Amplificador Crown y Red, JBL, color negro (SRX818SP)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/SRX818SP.jpg','2026-05-19 15:41:23',NULL,NULL,'0','admin','2026-05-19 21:41:23','2026-05-19 21:41:23','SRX818SP','2026-05-19 15:41:23',NULL),
(1819,1,NULL,'Subwoofer Klipsch (R1215W)',NULL,'AUDIO','Bodega General',NULL,NULL,'/img/productos/R1215W.jpg','2026-05-19 16:05:00',NULL,NULL,'0','admin','2026-05-19 22:05:00','2026-05-19 22:05:00','R1215W','2026-05-19 16:05:00',NULL),
(1820,4,NULL,'Caja estancas IP65, lados cerrados y placa con tornillos, gris 150X105X80 Eaton (WDL1510S)',NULL,'RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/WDL1510S_1.jpg','2026-05-20 09:17:56',NULL,NULL,'0','admin','2026-05-20 15:17:56','2026-05-20 15:17:56','WDL1510S','2026-05-20 09:17:56',NULL),
(1821,11,NULL,'Soporte de pared para barra de sonido, Sonos ARC Ultra, Gen 2, color negro  (AR2WMWW1BLK)',NULL,'SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/840136812647.jpg','2026-05-20 11:14:46',NULL,NULL,'0','admin','2026-05-20 17:14:46','2026-05-20 17:14:46','840136812647','2026-05-20 11:14:46',1180.00),
(1822,0,'QN80H','Pantalla 75 pulgadas Samsung Neo QLED 4K Vision AI Smart TV','Samsung','TV','Bodega General',NULL,NULL,'/img/productos/QN80H.jpg','2026-05-20 12:44:49',NULL,NULL,'0','admin','2026-05-20 18:44:49','2026-05-20 18:44:49','QN80H','2026-05-20 12:44:49',NULL),
(1823,0,'QN85F','Pantalla 65 pulgadas Samsung, Neo QLED 4K, Vision AI, Smart TV','Samsung','TV','Bodega General',NULL,NULL,'/img/productos/QN85F.jpg','2026-05-20 12:52:54',NULL,NULL,'0','admin','2026-05-20 18:52:54','2026-05-20 18:52:54','QN85F','2026-05-20 12:52:54',NULL),
(1824,12,'PST-1010-ER','Caja Derivación-Conexión de Plástico (100 X 100 X 54 mm) con 7 Glándulas de Goma. Para Instalaciones Comerciales, Residenciales e Industriales. Incluye Tornillería para su Tapa','Precision','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/PST-1010-ER.png','2026-05-20 13:13:40',NULL,NULL,'0','admin','2026-05-20 19:13:40','2026-05-20 19:13:40','PST-1010-ER','2026-05-20 13:13:40',1027.00),
(1825,6,'TXG-01-52','Gabinete Plástico Gris para Exterior (IP65) de 120 x 120 x 60 mm Cierre por Tornillos','TXPRO','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/TXG-01-52.jpg','2026-05-20 13:18:36',NULL,NULL,'0','admin','2026-05-20 19:18:36','2026-05-20 19:18:36','TXG-01-52','2026-05-20 13:18:36',1027.00),
(1826,1,'506-034','Extensión DB9, de 1,8 m con conector macho a conector hembra','Steren','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/7501483112738.jpg','2026-05-20 15:10:59',NULL,NULL,'0','admin','2026-05-20 21:10:59','2026-05-20 21:10:59','7501483112738','2026-05-20 15:10:59',NULL),
(1827,1,'506-040','Cable Jack A Jack DB9 RS232 Null Modem, De 3 M, color negro','Steren','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/7501483182267.jpg','2026-05-20 15:16:53',NULL,NULL,'0','admin','2026-05-20 21:16:53','2026-05-20 21:16:53','7501483182267','2026-05-20 15:16:53',NULL),
(1828,2,'G4A-606025-UL','Gabinete de Acero, Nema 4 -IP 66 de 60 x 60 x 25 cm con Platina y certificado UL','Anclo','RACK O GABINETE','Bodega General',NULL,NULL,'/img/productos/G4A-606025-UL.jpg','2026-05-22 11:13:15',NULL,NULL,'0','admin','2026-05-22 17:13:15','2026-05-22 17:13:15','G4A-606025-UL','2026-05-22 11:13:15',NULL),
(1829,23,'CL-H-46122','Clavija Hule con 2 piezas, sin tierra, Color Negro','Volteck','CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/7501206659441.jpg','2026-05-22 12:00:28',NULL,NULL,'0','admin','2026-05-22 18:00:28','2026-05-22 18:00:28','7501206659441','2026-05-22 12:00:28',NULL),
(1830,20,'CO-H 46123','Contacto Hule 2 con 2 piezas, sin tierra, Color Negro','Volteck','CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/7501206659458.jpg','2026-05-22 12:05:33',NULL,NULL,'0','admin','2026-05-22 18:05:33','2026-05-22 18:05:33','7501206659458','2026-05-22 12:05:33',NULL),
(1831,55,'666623','Cable uso rudo, multiconductor flexible, 18x3 AWG 600V','Condulac','CABLEADO','Bodega General',NULL,NULL,'/img/productos/666623.jpg','2026-05-22 12:48:02',NULL,NULL,'0','admin','2026-05-22 18:48:02','2026-05-22 18:48:02','666623','2026-05-22 12:48:02',NULL),
(1832,6,'NK4BXWH-AY','Caja de Montaje en Superficie, Para 4 Módulos Keystone, Color Blanco','Panduid','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/NK4BXWH-AY.jpg','2026-05-22 13:10:10',NULL,NULL,'0','admin','2026-05-22 19:10:10','2026-05-22 19:10:10',' NK4BXWH-AY','2026-05-22 13:10:10',NULL),
(1833,5,'295-812','Cable HDMI Ultra HD macho a macho, ultra delgado, 3.6 mt','Steren','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/7506250909405.jpg','2026-05-22 14:04:50',NULL,NULL,'0','admin','2026-05-22 20:04:50','2026-05-22 20:04:50','7506250909405','2026-05-22 14:04:50',NULL),
(1834,2,'PHDMI5M','Cable HDMI versión 2.0 plano de 5m (16.4 ft) optimizado para resolución 4K ULTRA HD','Epcom','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/PHDMI5M.jpg','2026-05-22 15:05:39',NULL,NULL,'0','admin','2026-05-22 21:05:39','2026-05-22 21:05:39',' PHDMI5M','2026-05-22 15:05:39',NULL),
(1835,6,'FIRETVSTICK4K','Amazon Fire TV Stick 4K con Wi-Fi 6, Dolby Vision/Atmos, 8 GB','Amazon','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/FIRETVSTICK4K.jpg','2026-05-25 11:03:28',NULL,NULL,'0','admin','2026-05-25 17:03:28','2026-05-25 17:03:28','FIRETVSTICK4K','2026-05-25 11:03:28',1358.00),
(1836,1,'GTYP-GWD-1T','Disco Duro de estado solido 1 TB,','WD Green','HARDWARE','Bodega General',NULL,NULL,'/img/productos/718037894287.jpg','2026-05-25 11:10:36',NULL,NULL,'0','admin','2026-05-25 17:10:36','2026-05-25 17:10:36','718037894287','2026-05-25 11:10:36',NULL),
(1837,4,'KF556C40BB-8','Memoria Gamer para PC, Capacidad: 8GB, Kingston Fury Beast Black DDR5','Kingston','HARDWARE','Bodega General',NULL,NULL,'/img/productos/KF556C40BB-8.jpg','2026-05-25 11:31:00',NULL,NULL,'0','admin','2026-05-25 17:31:00','2026-05-25 17:31:00','KF556C40BB-8','2026-05-25 11:31:00',NULL),
(1838,3,'2090','Cinta masking 3m scotch blue, azul, 24 mm x 55 m','Scotch Blue','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/Scotch-Blue-2090.jpg','2026-05-25 12:24:25',NULL,NULL,'0','admin','2026-05-25 18:24:25','2026-05-25 18:24:25','Scotch Blue 2090','2026-05-25 12:24:25',NULL),
(1839,3,'PLG 2090','Cinta masking 3m Scotch Blue, azul, para pintor 2pulgadas','Scotch Blue','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/2090.jpg','2026-05-25 12:29:57',NULL,NULL,'0','admin','2026-05-25 18:29:57','2026-05-25 18:29:57','2090','2026-05-25 12:29:57',NULL),
(1840,1,'EA-300-LYNK','Interfaz de transmisión de Audio streaming','Episode','AUDIO','Bodega General',NULL,NULL,'/img/productos/EA-300-LYNK.jpg','2026-05-25 15:27:31',NULL,NULL,'0','admin','2026-05-25 21:27:31','2026-05-25 21:27:31','EA-300-LYNK','2026-05-25 15:27:31',NULL),
(1841,1,'TS-AMS16','Conmutador de matriz de audio','Triad','AUDIO','Bodega General',NULL,NULL,'/img/productos/TS-AMS16.jpg','2026-05-25 15:36:11',NULL,NULL,'0','admin','2026-05-25 21:36:11','2026-05-25 21:36:11','TS-AMS16','2026-05-25 15:36:11',NULL),
(1842,2,'DCI 8 300','Amplificador de potencia de 8 canales','Crown','AUDIO','Bodega General',NULL,NULL,'/img/productos/DCI-8-300.jpg','2026-05-25 15:59:10',NULL,NULL,'0','admin','2026-05-25 21:59:10','2026-05-25 21:59:10','DCI 8 300','2026-05-25 15:59:10',NULL),
(1843,4,'AV104','Cable De Audio M/m 2rca/2rca 3m Negro','Ugreen','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/AV104.jpg','2026-05-25 16:06:12',NULL,NULL,'0','admin','2026-05-25 22:06:12','2026-05-25 22:06:12',' AV104','2026-05-25 16:06:12',NULL),
(1844,7,'DS-3T0506HP-E/HS','Switch Industrial No Administrable Gigabit, 3 Puertos Gigabit PoE+ (30 W) + 1 Puerto Gigabit PoE++ (60 W), 2 Puertos SFP, 65 W Total, 48 a 57 VCD, Ideal para Proyectos, 300 Metros de Distancia','Hikvision','SWITCH','Bodega General',NULL,NULL,'/img/productos/6941264038351.jpg','2026-05-25 16:26:01',NULL,NULL,'0','admin','2026-05-25 22:26:01','2026-05-25 22:26:01','6941264038351','2026-05-25 16:26:01',1299.00),
(1845,10,'KPL-065S-II','Fuente de Poder Regulada 48 Vcc / 1.35 A / Conector Tipo Plug','Hikvision','FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/101700994.jpg','2026-05-25 16:31:33',NULL,NULL,'0','admin','2026-05-25 22:31:33','2026-05-25 22:31:33','101700994','2026-05-25 16:31:33',1299.00),
(1846,1,'4333021','Ventilador de torre 97 cm White 2025 Edition Alexa, Wifi and Google Home works.','OMNI BREEZE','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/819079023823.jpg','2026-05-26 13:16:36',NULL,NULL,'0','admin','2026-05-26 19:16:36','2026-05-26 19:16:36','819079023823','2026-05-26 13:16:36',NULL),
(1847,1,'Rad-010','Kit 2 Radios Walkie 16canales 30km Clip Bases Color Negro','Steren','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/7506250927799.jpg','2026-05-27 10:15:34',NULL,NULL,'0','admin','2026-05-27 16:15:34','2026-05-27 16:15:34','7506250927799','2026-05-27 10:15:34',NULL),
(1848,19,'Model 170','Pinzas de micro corte a raz, azules,','Ivitc ','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/Model-170.jpg','2026-05-28 12:01:25',NULL,NULL,'0','admin','2026-05-28 18:01:25','2026-05-28 18:01:25','Model 170','2026-05-28 12:01:25',NULL),
(1849,3,'ARCG2US1BLK','Barra de sonido Arc Ultra con Dolby Atmos y control de voz, Sonido envolvente 9.1.4 para TV y música, color Negro','Sonos','AUDIO','Bodega General',NULL,NULL,'/img/productos/840136811336.jpg','2026-05-28 14:36:30',NULL,NULL,'0','admin','2026-05-28 20:36:30','2026-05-28 20:36:30','840136811336','2026-05-28 14:36:30',NULL),
(1850,8,'LRS-200-24','Fuente de poder regulada, 24V 200W 8.8A','Mean Well','FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/LRS-200-24.jpg','2026-05-29 10:40:18',NULL,NULL,'0','admin','2026-05-29 16:40:18','2026-05-29 16:40:18','LRS-200-24','2026-05-29 10:40:18',NULL),
(1851,15,'P45 40 125 E26 CLEAR','foco incandescente 40 watts luz cálida','Osram','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/4058075527485.jpg','2026-05-29 11:14:04',NULL,NULL,'0','admin','2026-05-29 17:14:04','2026-05-29 17:14:04','4058075527485','2026-05-29 11:14:04',NULL),
(1852,2,'122391','Repuesto para luminario 8W - 900 LM dia 170 MM, con selector de color de luz. Akzi (122391)','Akzi','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/7506198164607.jpg','2026-05-29 11:21:17',NULL,NULL,'0','admin','2026-05-29 17:21:17','2026-05-29 17:21:17','7506198164607','2026-05-29 11:21:17',NULL),
(1853,1,'122392','repuesto para luminario 18 w 1350 lm diámetro 19 cm con selector de color de luz','Akzi','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/7506198164614.jpg','2026-05-29 11:28:35',NULL,NULL,'0','admin','2026-05-29 17:28:35','2026-05-29 17:28:35','7506198164614','2026-05-29 11:28:35',NULL),
(1854,1,'217311','Cable con aislamiento de PVC 75 C, 90 C, 600V para alambrado de tableros, 16AWG, IUSA, color rojo','IUSA','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/791279166121.jpg','2026-05-29 11:54:29',NULL,NULL,'0','admin','2026-05-29 17:54:29','2026-05-29 17:54:29','791279166121','2026-05-29 11:54:29',NULL),
(1855,1,'3004357','Cable Electrico 100 Metros Calibre 14  AWG, Color Blanco','Condulac','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/798190233383.jpg','2026-05-29 12:02:48',NULL,NULL,'0','admin','2026-05-29 18:02:48','2026-05-29 18:02:48','798190233383','2026-05-29 12:02:48',NULL),
(1856,1,'3004356','Cable Electrico calibre 14 AWG, 100 mts, color negro','Condulac','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/798190233352.jpg','2026-05-29 12:08:12',NULL,NULL,'0','admin','2026-05-29 18:08:12','2026-05-29 18:08:12','798190233352','2026-05-29 12:08:12',NULL),
(1857,2,'DXN11107','Portalámparas redondo en color blanco Dexson de 120W y base de bombilla E27','Dexson','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/7702496034981.jpg','2026-05-29 12:30:41',NULL,NULL,'0','admin','2026-05-29 18:30:41','2026-05-29 18:30:41','7702496034981','2026-05-29 12:30:41',NULL),
(1858,17,'A-100FAN-24','Fuente de poder regulada 24V 4.5 A, 108W, 110-200','CZCL','FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/A-100FAN-24.jpg','2026-05-29 16:47:51',NULL,NULL,'0','admin','2026-05-29 22:47:51','2026-05-29 22:47:51','A-100FAN-24','2026-05-29 16:47:51',NULL),
(1859,15,'FTB-501','Caja Terminal de Fibra Óptica (Roseta) con un Acoplador SC/APC, Color Blanco','FiberHome','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/FTB-501.jpg','2026-06-03 12:04:13',NULL,NULL,'0','admin','2026-06-03 18:04:13','2026-06-03 18:04:13','FTB-501','2026-06-03 12:04:13',1330.00),
(1860,2,'S310-48HP4X','Switch Core/Distribución eKit Administrable, Capa 3,  48 puertos Gigabit PoE+ + 4 SFP+, PoE Perpetuo, PoE Budget 849 W, Sin Licenciamiento','Huawei','SWITCH','Bodega General',NULL,NULL,'/img/productos/6901443464593.jpg','2026-06-03 14:29:06',NULL,NULL,'0','admin','2026-06-03 20:29:06','2026-06-03 20:29:06','6901443464593','2026-06-03 14:29:06',1300.00),
(1861,4,'S310-24P4X','Switch de Distribución, Core Gigabit Administrable PoE Capa 3, 24 puertos 10/100/1000 Mbps (PoE), 4 Puertos 10GE SFP+ Uplink, ERPS, Rutas Estáticas, iStack, PoE Perpetuo, 400W, Administración Nube Gratis','Huawei','SWITCH','Bodega General',NULL,NULL,'/img/productos/6901443450145.jpg','2026-06-03 14:40:27',NULL,NULL,'0','admin','2026-06-03 20:40:27','2026-06-03 20:40:27','6901443450145','2026-06-03 14:40:27',1310.00),
(1862,28,'AP361','Punto de Acceso eKit Wi-Fi 6 para techo o pared, 1.775 Gbps, MU-MIMO 2x2 (2.4GHz y 5GHz), Smart Antenna 20% más cobertura, Hasta 128 clientes, Libre de licenciamiento','Huawei','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/AP361.jpg','2026-06-03 15:14:10',NULL,NULL,'0','admin','2026-06-03 21:14:10','2026-06-03 21:14:10','AP361','2026-06-03 15:14:10',1305.00),
(1863,4,'AR280','Router eKit para 150 clientes,  Controladora para 16 APs, 5 Puertos PoE+ (1 WAN 2.5 Gbps + 1 LAN Gigabit + 3 LAN/WAN gigabit), Presupuesto PoE 41W, Gestión en la nube, Libre de licenciamiento','Huawei','RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/AR280.jpg','2026-06-03 15:19:11',NULL,NULL,'0','admin','2026-06-03 21:19:11','2026-06-03 21:19:11','6901443468997','2026-06-03 15:19:11',1300.00),
(1864,3,'S380-S8P2T','Router eKit, 2 Puertos WAN Gigabit, 1 Puerto LAN/WAN Gigabit, 7 Puertos PoE LAN Gigabit, Presupuesto PoE 124 W, Rendimiento de 2 Gbps, Controlador (64 APs), Hasta 250 clientes, Libre de licenciamiento','Huawei','RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/S380-S8P2T.jpg','2026-06-03 15:23:57',NULL,NULL,'0','admin','2026-06-03 21:23:57','2026-06-03 21:23:57','S380-S8P2T','2026-06-03 15:23:57',1310.00),
(1865,144,'LP-UT6-100-BU','Cable de Parcheo UTP Cat6 - 1 Metro (3.28 Pies) - Azul','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LP-UT6-100-BU.jpg','2026-06-03 16:24:41',NULL,NULL,'0','admin','2026-06-03 22:24:41','2026-06-03 22:24:41','LP-UT6-100-BU','2026-06-03 16:24:41',NULL),
(1866,90,'LP-UT6-200-BU','Cable de Parcheo UTP Cat6 - 2 Metros (6.56 Pies) - Azul','Linkedpro',' PATCHCORD','Bodega General',NULL,NULL,'/img/productos/LP-UT6-200-BU.jpg','2026-06-03 16:52:51',NULL,NULL,'0','admin','2026-06-03 22:52:51','2026-06-03 22:52:51',' LP-UT6-200-BU','2026-06-03 16:52:51',NULL),
(1867,3,'Modelo G-2','Cartucho de gas butano/propano 275 gr','LinMex Gas','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/7502236961320.jpg','2026-06-04 10:09:00',NULL,NULL,'0','admin','2026-06-04 16:09:00','2026-06-04 16:09:00','7502236961320','2026-06-04 10:09:00',NULL),
(1868,60,'ST14-075-60BK','Cinta Eléctrica STRONGHOLD para Aislar, de PVC, Uso General Reparación y Mantenimiento, Grosor de 0.18mm (7 mil), Ancho de 19mm, y 18m de Largo, Color Negro','StrongHold','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/30613056732651.jpg','2026-06-04 11:27:46',NULL,NULL,'0','admin','2026-06-04 17:27:46','2026-06-04 17:27:46','30613056732651','2026-06-04 11:27:46',NULL),
(1869,113,'1600','Cinta Aislante De Vinil 3m','Temflex','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/7501023105725.jpg','2026-06-04 11:36:37',NULL,NULL,'0','admin','2026-06-04 17:36:37','2026-06-04 17:36:37','7501023105725','2026-06-04 11:36:37',NULL),
(1870,1,'SLF-428-B8','Soporte de pared para TV  Preferred de movimiento completo para televisores de 37\\\" a 90\\\"','Sanus','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/SLF428-B8.jpg','2026-06-06 11:27:48',NULL,NULL,'0','admin','2026-06-06 17:27:48','2026-06-06 17:27:48','SLF428-B8','2026-06-06 11:27:48',NULL),
(1871,0,'U8200F','Pantalla 65 pulgadas Crystal UHD 4K Smart TV, Samsung','Samsung','TV','Bodega General',NULL,NULL,'/img/productos/U8200F.jpg','2026-06-06 11:33:43',NULL,NULL,'0','admin','2026-06-06 17:33:43','2026-06-06 17:33:43',' U8200F','2026-06-06 11:33:43',NULL),
(1872,0,'CL120VP','Kit de Prueba de Voltaje, Multimetro,  Detector de Voltaje, Mide Corriente, Resistencia y Continuidad, Ideal para Diagnósticos','Klein Tools','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/92644693410.jpg','2026-06-08 16:30:42',NULL,NULL,'0','admin','2026-06-08 22:30:42','2026-06-08 22:30:42','92644693410','2026-06-08 16:30:42',NULL),
(1873,2,'TTHDMI10M','Cable HDMI de 10 Metros (32.81 pies) (High Speed), Resolución 4K, Soporta Canal de Retorno de Audio (ARC), Soporta 3D, Blindado para Reducir Interferencia, Chapado en Oro, Alta Resistencia y Durabilidad','Epcom','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/TTHDMI10M.jpg','2026-06-09 14:57:45',NULL,NULL,'0','admin','2026-06-09 20:57:45','2026-06-09 20:57:45','TTHDMI10M','2026-06-09 14:57:45',1355.00),
(1874,2,'EPFOH4K15M','Cable HDMI de Fibra Óptica de 15 Metros (49.21 Pies), Alta Definición, Versión 2.0,  Alta Velocidad 18Gbps, 4K@60Hz, HDCP 2.2, Resistente a EMI y RFI','Epcom','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/EPFOH4K15M.jpg','2026-06-09 15:05:57',NULL,NULL,'0','admin','2026-06-09 21:05:57','2026-06-09 21:05:57','EPFOH4K15M','2026-06-09 15:05:57',1355.00),
(1875,3,'EVC-100R-WHLA','Control de volumen giratorio comercial de 100V','Episode','AUDIO','Bodega General',NULL,NULL,'/img/productos/842822011853.jpg','2026-06-09 15:39:56',NULL,NULL,'0','admin','2026-06-09 21:39:56','2026-06-09 21:39:56','842822011853','2026-06-09 15:39:56',1320.00),
(1876,36,'MWSB2','Soporte universal ajustable en profundidad para barra de sonido (pieza) Negro','Mounts','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/814183020614.jpg','2026-06-09 15:48:43',NULL,NULL,'0','admin','2026-06-09 21:48:43','2026-06-09 21:48:43','814183020614','2026-06-09 15:48:43',NULL),
(1877,2,'MR4300','Regulador de voltaje y protector de sobrecarga, 9 tomas','Panamax','NO BREAK ','Bodega General',NULL,NULL,'/img/productos/50616008938.jpg','2026-06-10 15:13:05',NULL,NULL,'0','admin','2026-06-10 21:13:05','2026-06-10 21:13:05','50616008938','2026-06-10 15:13:05',1014.00),
(1878,0,'MRX 1140 8K','Receptor A/V de 15.2 canales con preamplificador y 11 canales amplificadores, compatible con HDMI 2.1 8K y corrección de sala ARC Genesis','ANTHEM ','AUDIO','Bodega General',NULL,NULL,'/img/productos/MRX-1140-8K.jpg','2026-06-10 15:57:25',NULL,NULL,'0','admin','2026-06-10 21:57:25','2026-06-10 21:57:25','MRX 1140 8K','2026-06-10 15:57:25',NULL),
(1879,0,'HQWT-T-HW-SN-A','Termostato con 5 botones Palladiom, Nikel Satinado','Lutron','AIRE ACONDICIONADO','Bodega General',NULL,NULL,'/img/productos/784276227070.jpg','2026-06-10 16:13:10',NULL,NULL,'0','admin','2026-06-10 22:13:10','2026-06-10 22:13:10','784276227070','2026-06-10 16:13:10',NULL),
(1880,10,'SCR-15-DDTR-BI-L','Tomacorriente a prueba de manipulaciones, doble atenuacion, 15A/125V, Satin Color','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557781008.jpg','2026-06-10 16:23:54',NULL,NULL,'0','admin','2026-06-10 22:23:54','2026-06-10 22:23:54','27557781008','2026-06-10 16:23:54',NULL),
(1881,10,'MA-T51MN-SW-S','Interruptor con temporizador de multiples sitios, Maestro','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557568982.jpg','2026-06-10 16:33:11',NULL,NULL,'0','admin','2026-06-10 22:33:11','2026-06-10 22:33:11','27557568982','2026-06-10 16:33:11',NULL),
(1882,2,'EPFOH8K15M','Cable HDMI de Fibra Óptica de 15 Metros (49.21 Pies),  Alta Definición, Versión 2.1,  Alta Velocidad 18Gbps,  8K@60Hz,  HDCP 2.2,  Resistente a EMI y RFI,','Epcom','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/EPFOH8K15M.jpg','2026-06-11 09:17:47',NULL,NULL,'0','admin','2026-06-11 15:17:47','2026-06-11 15:17:47','EPFOH8K15M','2026-06-11 09:17:47',NULL),
(1883,3,'SMI100','Detector de humo con batería','First Alert','ALARMA','Bodega General',NULL,NULL,'/img/productos/29054022202.jpg','2026-06-11 09:58:22',NULL,NULL,'0','admin','2026-06-11 15:58:22','2026-06-11 15:58:22','29054022202','2026-06-11 09:58:22',1364.00),
(1884,4600,'PLT-2M-MO','Cincho de Nylon 6.6 de Bloqueo, 203 mm largo x 2.5mm ancho, Color Negro, Exterior Resistente a Rayos UV, Paquete de 1000pz','Panduit','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/74983540273.jpg','2026-06-11 10:06:28',NULL,NULL,'0','admin','2026-06-11 16:06:28','2026-06-11 16:06:28','74983540273','2026-06-11 10:06:28',NULL),
(1885,0,'TTHDMI1M','Cable HDMI de 1 Metro, Resolución 4K / Soporta Canal de Retorno de Audio','Epcom','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/TTHDMI1M.jpg','2026-06-11 10:58:36',NULL,NULL,'0','admin','2026-06-11 16:58:36','2026-06-11 16:58:36',' TTHDMI1M','2026-06-11 10:58:36',NULL),
(1886,1,'14 E1404GA','Laptop ASUS VivoBook Go, Core i3-N305, 8GB RAM, 512GB SSD UFS, Intel UHD Graphics, 14\\\" FHD','ASUS','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/14-E1404GA.jpg','2026-06-12 10:40:43',NULL,NULL,'0','admin','2026-06-12 16:40:43','2026-06-12 16:40:43','14 E1404GA','2026-06-12 10:40:43',NULL),
(1887,6,'370058','Taquete de mariposa 4.75x2.90','Hilman','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/370058.jpg','2026-06-12 10:59:03',NULL,NULL,'0','admin','2026-06-12 16:59:03','2026-06-12 16:59:03','370058','2026-06-12 10:59:03',NULL),
(1888,4,'13989','Probador de circuitos de corriente alterna 19 cm','Truper','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/13989.jpg','2026-06-12 11:38:03',NULL,NULL,'0','admin','2026-06-12 17:38:03','2026-06-12 17:38:03','13989','2026-06-12 11:38:03',NULL),
(1889,0,'STV-039','Soporte para pantallas de 32\\\" a 85\\\", con ajuste de inclinación','Steren','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/7506250921582.jpg','2026-06-15 09:15:02',NULL,NULL,'0','admin','2026-06-15 15:15:02','2026-06-15 15:15:02','7506250921582','2026-06-15 09:15:02',NULL),
(1890,0,'STV-049','Soporte para pantallas de 43” a 100”, con ajuste de inclinación y cordones de fácil montaje,  desmontaje','Steren','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/7506250979200.jpg','2026-06-15 09:17:46',NULL,NULL,'0','admin','2026-06-15 15:17:46','2026-06-15 15:17:46','7506250979200','2026-06-15 09:17:46',NULL),
(1891,1,'NAUQ67445','Cargador para notebook original Huawei 65W, blanco','Huawei','CARGADOR','Bodega General',NULL,NULL,'/img/productos/NAUQ67445.jpg','2026-06-15 09:26:38',NULL,NULL,'0','admin','2026-06-15 15:26:38','2026-06-15 15:26:38','NAUQ67445','2026-06-15 09:26:38',1355.00),
(1892,2,'80406','Cable de transmisión de vídeo por fibra óptica HDMI 2.1 8K 60Hz 4K 120Hz, 10 metros','Ugreen','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/80406.jpg','2026-06-15 10:13:50',NULL,NULL,'0','admin','2026-06-15 16:13:50','2026-06-15 16:13:50','80406','2026-06-15 10:13:50',1355.00),
(1893,4,'LC1D18F7','Contactor de 18A, 440V, 3 Polos, 3 Fases, bobina de 110V','TeSysD','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/3389110349450.jpg','2026-06-15 10:43:14',NULL,NULL,'0','admin','2026-06-15 16:43:14','2026-06-15 16:43:14','3389110349450','2026-06-15 10:43:14',NULL),
(1894,13,'788-507','Módulo con relé, Tensión nominal de entrada 115V AC, 1 inversor, Corriente permanente límite 16 A, Visualización de estados rojo, 15 mm ancho de montaje, 2,50 mm², gris,','Wago','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/788-507.jpg','2026-06-15 11:24:36',NULL,NULL,'0','admin','2026-06-15 17:24:36','2026-06-15 17:24:36','788-507','2026-06-15 11:24:36',1240.00),
(1895,22,'788-311','Módulo con relé, Tensión nominal de entrada 12V DC, 2 inversores, Corriente permanente límite 8 A, Visualización de estados rojo, 15 mm ancho de montaje, 2,50 mm², gris','Wago','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/788-311.jpg','2026-06-15 11:28:21',NULL,NULL,'0','admin','2026-06-15 17:28:21','2026-06-15 17:28:21','788-311','2026-06-15 11:28:21',1240.00),
(1896,2,'LR3BHSW',' Control remoto para iluminación y puertas de garage, uso en visera de automóvil, integrelo con RRDVCRXWH, RadioRa2','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/27557774680.jpg','2026-06-15 13:31:37',NULL,NULL,'0','admin','2026-06-15 19:31:37','2026-06-15 19:31:37','27557774680','2026-06-15 13:31:37',1242.00),
(1897,1,'LC2101-WH','Interruptor De Luz Atenuador, Radiant, Blanco','Legrand','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/LC2101-WH.jpg','2026-06-15 15:51:22',NULL,NULL,'0','admin','2026-06-15 21:51:22','2026-06-15 21:51:22','LC2101-WH','2026-06-15 15:51:22',1239.00),
(1898,10,'788-312','Módulo con relé, Tensión nominal de entrada: 24 VCC, 2 inversores, Corriente permanente límite 8 A; Visualización de estados rojo, 15 mm ancho de montaje, 2,50 mm², gris','Wago','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/788-312.jpg','2026-06-15 16:04:37',NULL,NULL,'0','admin','2026-06-15 22:04:37','2026-06-15 22:04:37','788-312','2026-06-15 16:04:37',1240.00),
(1899,0,'QN85F','Pantalla 85 pulgadas, Neo QLED, 4k, VisionI, Smart TV,','Samsung','TV','Bodega General',NULL,NULL,'/img/productos/TV-QN85F.jpg','2026-06-15 16:25:12',NULL,NULL,'0','admin','2026-06-15 22:25:12','2026-06-15 22:25:12','TV QN85F','2026-06-15 16:25:12',NULL),
(1900,3,'L-PED2-WH','Pedestal doble para botonera pico, color blanco','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/2755765923.jpg','2026-06-15 16:38:10',NULL,NULL,'0','admin','2026-06-15 22:38:10','2026-06-15 22:38:10','2755765923','2026-06-15 16:38:10',1235.00),
(1901,0,'RKD-W5BRL-BI-E','Frente de botonera SeeTouch, 5 botones, 2 de subir y bajar, RadioRa2, Bisquit','Lutron','ILUMINACION','Bodega General',NULL,NULL,NULL,'2026-06-16 16:10:21',NULL,NULL,'0','admin','2026-06-16 22:10:21','2026-06-16 22:10:21','RKD-W5BRL-BI-E','2026-06-16 16:10:21',NULL),
(1902,0,'RKD-W3BRL-BI-E','Frente de botonera SeeTouch, 3 botones, 2 de subir y bajar, RadioRa2, Bisquit','Lutron','ILUMINACION','Bodega General',NULL,NULL,NULL,'2026-06-16 16:14:14',NULL,NULL,'0','admin','2026-06-16 22:14:14','2026-06-16 22:14:14','2755748268','2026-06-16 16:14:14',NULL),
(1903,0,'RKD-W4BS-BI-E','Frente de botonera SeeTouch, 4 botones, RadioRa2, Bisquit','Lutron','ILUMINACION','Bodega General',NULL,NULL,NULL,'2026-06-16 16:17:13',NULL,NULL,'0','admin','2026-06-16 22:17:13','2026-06-16 22:17:13','2755748852','2026-06-16 16:17:13',NULL),
(1904,0,'RKD-W2BS-BI-E','Frente de botonera SeeTouch, 2 botones, RadioRa2, Bisquit','Lutron','ILUMINACION','Bodega General',NULL,NULL,NULL,'2026-06-16 16:19:19',NULL,NULL,'0','admin','2026-06-16 22:19:19','2026-06-16 22:19:19','2755747850','2026-06-16 16:19:19',NULL),
(1905,1,'RKD-H6BRL-WH-E','Frente de botonera de 6 botones hibrida, blanca','Lutron','ILUMINACION','Bodega General',NULL,NULL,NULL,'2026-06-17 13:26:55',NULL,NULL,'0','admin','2026-06-17 19:26:55','2026-06-17 19:26:55','RKD-H6BRL-WH-E','2026-06-17 13:26:55',1247.00),
(1906,0,'QN80H','PANTALLA 100\\\" pulgadas, NEO QLED, 252CM, 4k','Samsung','TV','Bodega General',NULL,NULL,'/img/productos/QN100QN80HF.jpg','2026-06-18 15:34:31',NULL,NULL,'0','admin','2026-06-18 21:34:31','2026-06-18 21:34:31','QN100QN80HF','2026-06-18 15:34:31',NULL),
(1907,1,'SC-1PS-BI-L','Interruptor unipolar de uso general 15 amperios, Satin Colors, color Bisquit','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/SC-1PS-BI-L.jpg','2026-06-23 12:46:19',NULL,NULL,'0','admin','2026-06-23 18:46:19','2026-06-23 18:46:19','SC-1PS-BI-L','2026-06-23 12:46:19',NULL),
(1908,1,'MSC-AD-SW','Dimmer compañero, Satin Colors, color snow','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/MSC-AD-SW.jpg','2026-06-23 12:53:12',NULL,NULL,'0','admin','2026-06-23 18:53:12','2026-06-23 18:53:12','MSC-AD-SW','2026-06-23 12:53:12',NULL),
(1909,0,NULL,'CONTROL VENTILADOR 0-10 V L-SERIE PCBA MODULE (TARJETA ORIGINAL)',NULL,'VENTILACION','Bodega General',NULL,NULL,NULL,'2026-06-24 09:47:25',NULL,NULL,'0','admin','2026-06-24 15:47:25','2026-06-24 15:47:25','0-10V (ORIGINAL)','2026-06-24 09:47:25',NULL),
(1910,0,NULL,'CONTROL VENTILADOR 0-10 V L-SERIE PCBA MODULE',NULL,'VENTILACION','Bodega General',NULL,NULL,NULL,'2026-06-24 09:48:17',NULL,NULL,'0','admin','2026-06-24 15:48:17','2026-06-24 15:48:17','0-10V (MODIFICADO)','2026-06-24 09:48:17',NULL),
(1911,1,'SM-VM-ART2-IW-XL','Soporte para TV VersaMount Montaje articulado de doble brazo en pared, Pantallas de 49-90\\\"','Strong','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/842822047517.jpg','2026-06-25 16:00:34',NULL,NULL,'0','admin','2026-06-25 22:00:34','2026-06-25 22:00:34','842822047517','2026-06-25 16:00:34',NULL),
(1912,1,'3004-BL','Extractor De Aire, Ventilador para Baño y Cocina de 4\\\\\\\", 14 Watts, 33dB, Flujo de Aire 56 CFM, Instalación en Muro','Estevez','AIRE ACONDICIONADO','Bodega General',NULL,NULL,'/img/productos/7500449026058.jpg','2026-06-29 09:15:23',NULL,NULL,'0','admin','2026-06-29 15:15:23','2026-06-29 15:15:23','7500449026058','2026-06-29 09:15:23',NULL),
(1913,1,'1104-eco','Extractor De Aire, Ventilador para Baño y Cocina con Sensor de Movimiento 4\\\", 15 Watts, 41dB, Flujo de Aire 68 m³/hr, Instalación en Muro o Ventana','Estevez','AIRE ACONDICIONADO','Bodega General',NULL,NULL,'/img/productos/7500449011986.jpg','2026-06-29 09:18:37',NULL,NULL,'0','admin','2026-06-29 15:18:37','2026-06-29 15:18:37','7500449011986','2026-06-29 09:18:37',NULL),
(1914,3,'PI 4B','Carcasa Case Disipador Aluminio Doble Ventilador para Raspberry Pi 4 B','Raspberry ','HARDWARE','Bodega General',NULL,NULL,'/img/productos/GPCE45918.jpg','2026-07-01 16:40:36',NULL,NULL,'0','admin','2026-07-01 22:40:36','2026-07-01 22:40:36','GPCE45918','2026-07-01 16:40:36',NULL),
(1915,6,'E7313006-BK','Bateria de Litio recargable 18V, 6.0 Ah. para taladro','Generica','CARGADOR','Bodega General',NULL,NULL,'/img/productos/E7313006-BK.jpg','2026-07-04 10:22:23',NULL,NULL,'0','admin','2026-07-04 16:22:23','2026-07-04 16:22:23','E7313006-BK','2026-07-04 10:22:23',NULL),
(1916,2,'Petoul50ft','Kit de varillas pasacables, de fibra de vidrio, 50 pies','Petoul','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,NULL,'2026-07-10 14:02:25',NULL,NULL,'0','admin','2026-07-10 20:02:25','2026-07-10 20:02:25','Petoul50ft','2026-07-10 14:02:25',NULL),
(1917,1,'CSP-LIR-USB','Dispositivo USB  con software Crestron Toolbox, para crear, editar, probar y gestionar archivos de control  IR','Crestron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6501813.jpg','2026-07-14 14:37:24',NULL,NULL,'0','admin','2026-07-14 20:37:24','2026-07-14 20:37:24','6501813','2026-07-14 14:37:24',NULL),
(1918,1,'CM2-FP-G1-W-S','Tapa de 1 ventana, color blanco','Crestron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/6513742.jpg','2026-07-14 15:05:35',NULL,NULL,'0','admin','2026-07-14 21:05:35','2026-07-14 21:05:35','6513742','2026-07-14 15:05:35',NULL),
(1919,1,'CM2-KPCN','Teclado Cameo 2 con comunicaciones y Crestnet , teclado completo','Crestron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6513898.jpg','2026-07-14 15:15:14',NULL,NULL,'0','admin','2026-07-14 21:15:14','2026-07-14 21:15:14','6513898','2026-07-14 15:15:14',NULL),
(1920,1,'HZ2-KPCN-B','Teclado Horizon 2 con comunicaciones Cresnet, grabado estándar, color negro','Crestron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6513108.jpg','2026-07-14 15:20:35',NULL,NULL,'0','admin','2026-07-14 21:20:35','2026-07-14 21:20:35','6513108','2026-07-14 15:20:35',NULL),
(1921,1,'HR-CV-MINI','Control remoto, mini portatil, botones configurables, opciones de programación personalizadas y control por voz','Crestron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6514422.jpg','2026-07-14 15:26:20',NULL,NULL,'0','admin','2026-07-14 21:26:20','2026-07-14 21:26:20','6514422','2026-07-14 15:26:20',NULL),
(1922,1,'DIN-8SW8-I','Modulo de control de iluminacion de 8 canales','Crestron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6503776.jpg','2026-07-14 15:31:39',NULL,NULL,'0','admin','2026-07-14 21:31:39','2026-07-14 21:31:39','6503776','2026-07-14 15:31:39',NULL),
(1923,1,'DIN-2MC2','Módulo de control de motores de 2 canales, para motores bidirecionales de cortinas, persianas','Crestron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6301745.jpg','2026-07-14 15:37:16',NULL,NULL,'0','admin','2026-07-14 21:37:16','2026-07-14 21:37:16','6301745','2026-07-14 15:37:16',NULL),
(1924,1,'DIN-AP4-R','Modulo de control con motor de la Serie 4','Crestron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6512607.jpg','2026-07-14 15:41:49',NULL,NULL,'0','admin','2026-07-14 21:41:49','2026-07-14 21:41:49','6512607','2026-07-14 15:41:49',NULL),
(1925,1,'DIN-PWS60','Modulo de alimentacion Cresnet 60V,','Crestron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6507733.jpg','2026-07-14 15:46:47',NULL,NULL,'0','admin','2026-07-14 21:46:47','2026-07-14 21:46:47','6507733','2026-07-14 15:46:47',NULL),
(1926,1,'DIN-1DIMU4','Modulo regulador universal para 4 canales de atenuacion de fase directa o inversa','Crestron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/6501748.jpg','2026-07-14 15:57:24',NULL,NULL,'0','admin','2026-07-14 21:57:24','2026-07-14 21:57:24','6501748','2026-07-14 15:57:24',NULL),
(1927,1,'DM-NAX-4ZSA-50','Amplificador de audio, sobre IP','Crestron','AUDIO','Bodega General',NULL,NULL,'/img/productos/6512320.jpg','2026-07-14 16:06:50',NULL,NULL,'0','admin','2026-07-14 22:06:50','2026-07-14 22:06:50','6512320','2026-07-14 16:06:50',NULL),
(1928,6,'RS-25-12','Fuente de Poder Industrial Conmutada 25W de Potencia, 12Vcc, 2.1A,','Mean Well','FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/39121000.jpg','2026-07-16 10:18:14',NULL,NULL,'0','admin','2026-07-16 16:18:14','2026-07-16 16:18:14','39121000','2026-07-16 10:18:14',NULL),
(1929,2,'WD33PURZ','Disco Duro PURPLE de 3TB, para Videovigilancia,','Western Digital','HARDWARE','Bodega General',NULL,NULL,'/img/productos/WD33PURZ.jpg','2026-07-16 10:29:33',NULL,NULL,'0','admin','2026-07-16 16:29:33','2026-07-16 16:29:33','WD33PURZ','2026-07-16 10:29:33',1254.00),
(1930,6,'LWT-U-PP-BL','Tapa de 2 ventanas, Palladiom, color Negra','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/LWT-U-PP-BL.jpg','2026-07-16 13:09:20',NULL,NULL,'0','admin','2026-07-16 19:09:20','2026-07-16 19:09:20','784276110327','2026-07-16 13:09:20',NULL),
(1931,4,'LWT-U-P-BL','Tapa de 1 ventana, Palladiom, color Negro','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/784276110105.jpg','2026-07-16 13:17:55',NULL,NULL,'0','admin','2026-07-16 19:17:55','2026-07-16 19:17:55','784276110105','2026-07-16 13:17:55',NULL),
(1932,10,'LTR-15-TR-AL','Contacto duplex arquitectónico 15A resistente a manipulaciones, color Almendra','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276154178.jpg','2026-07-16 16:10:22',NULL,NULL,'0','admin','2026-07-16 22:10:22','2026-07-16 22:10:22','784276154178','2026-07-16 16:10:22',NULL),
(1933,8,'LTR-15-UBTR-BL','Contacto duplex, USB, arquitectonico 15A, Lutron, negro','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276154444.jpg','2026-07-16 16:17:47',NULL,NULL,'0','admin','2026-07-16 22:17:47','2026-07-16 22:17:47','784276154444','2026-07-16 16:17:47',NULL),
(1934,0,'PBT-4W-WH-E','Frente para botonera de 4 botones, Palladiom, color Blanca','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/78427611221.jpg','2026-07-16 16:37:45',NULL,NULL,'0','admin','2026-07-16 22:37:45','2026-07-16 22:37:45','78427611221','2026-07-16 16:37:45',NULL),
(1935,6,'LWT-U-PPP-LA','Tapa de 3 ventanas, Palladiom color Almendra Claro','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/784276110464.jpg','2026-07-16 16:51:57',NULL,NULL,'0','admin','2026-07-16 22:51:57','2026-07-16 22:51:57','784276110464','2026-07-16 16:51:57',NULL),
(1936,6,'LWT-U-PPP-BL','Tapa de 3 ventanas, negra, Palladiom','Lutron','PLACAS DECORATIVAS','Bodega General',NULL,NULL,'/img/productos/784276110549.jpg','2026-07-16 16:53:46',NULL,NULL,'0','admin','2026-07-16 22:53:46','2026-07-16 22:53:46','784276110549','2026-07-16 16:53:46',NULL),
(1937,5,'190187196','Montaje de Pared o Bracket para monitor, compatible con modelos DS-KH6320-WTE1 y DS-KH8520-WTE1','Hikvision','INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/190187196.jpg','2026-07-22 09:23:57',NULL,NULL,'0','admin','2026-07-22 15:23:57','2026-07-22 15:23:57','190187196','2026-07-22 09:23:57',1367.00),
(1938,0,' DS-KV6113-PE1(C)','Frente de Calle o Videoportero IP 2 Megapixel, PoE Estandar, IP65, Apertura desde Hik-connect, Soporta 1 Departamento y Hasta 6 Monitores, Soporta Tarjetas Mifare. 1 salida Relay','Hikvison','INTERFON O VIDEOPORTERO','Bodega General',NULL,NULL,'/img/productos/6931847170448.jpg','2026-07-23 10:44:41',NULL,NULL,'0','admin','2026-07-23 16:44:41','2026-07-23 16:44:41','6931847170448','2026-07-23 10:44:41',1367.00),
(1939,8,'SD-969-S18Q','Cordón blindado para puertas con tapas de acero inoxidable, ideal para control de acceso','Enforcer Seco-Larm','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/676544018698.jpg','2026-07-23 11:39:38',NULL,NULL,'0','admin','2026-07-23 17:39:38','2026-07-23 17:39:38','676544018698','2026-07-23 11:39:38',1328.00),
(1940,5,'S-16','Supresor de ruidos con conectores RCA','Isolation','AUDIO','Bodega General',NULL,NULL,'/img/productos/656831050075.jpg','2026-07-23 13:43:09',NULL,NULL,'0','admin','2026-07-23 19:43:09','2026-07-23 19:43:09','656831050075','2026-07-23 13:43:09',NULL),
(1941,2,'Fabaterx9q3omwt42','Soporte de pared Starlink, compatible con Starlink Satellite o Starlink V2, color Gris','Fabater','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/Fabaterx9q3omwt42_1.jpg','2026-07-24 15:36:06',NULL,NULL,'0','admin','2026-07-24 21:36:06','2026-07-24 21:36:06','Fabaterx9q3omwt42','2026-07-24 15:36:06',NULL),
(1942,0,' 2T-C40GF2320U','Televisión Pantalla 40 Pulgadas Sharp Roku TV FHD','Sharp','TV','Bodega General',NULL,NULL,'/img/productos/74000703667_1.jpg','2026-07-28 09:18:36',NULL,NULL,'0','admin','2026-07-28 15:18:36','2026-07-28 15:18:36','74000703667','2026-07-28 09:18:36',NULL),
(1943,1,'APCUWCQ','Kit de montaje para pared o techo, ajustable para  internet satelital Gen3','Starlink','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/APCUWCQ_1.jpg','2026-07-28 15:56:09',NULL,NULL,'0','admin','2026-07-28 21:56:09','2026-07-28 21:56:09','APCUWCQ','2026-07-28 15:56:09',NULL),
(1944,0,'AP772','Punto de Acceso Wi-Fi 7 para Exterior, Omnidireccional 360&ordm; 1,024 Usuarios, 1 Puerto RJ45 PoE In 2.5 Gbps 1 Puerto SFP+ Hasta 6.45 Gbps, Libre de Licenciamiento','Huawei','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/6901443464449.jpg','2026-07-30 11:20:12',NULL,NULL,'0','admin','2026-07-30 17:20:12','2026-07-30 17:20:12','6901443464449','2026-07-30 11:20:12',NULL),
(1945,0,'WD11PURZ','Disco Duro Purple de 1 TB, 5400 RPM, Optimizado para Soluciones de Videovigilancia, Uso 24-7, 3 Años de Garantia','Western Digital','HARDWARE','Bodega General',NULL,NULL,'/img/productos/WD11PURZ.jpg','2026-07-30 11:28:38',NULL,NULL,'0','admin','2026-07-30 17:28:38','2026-07-30 17:28:38','WD11PURZ','2026-07-30 11:28:38',NULL),
(1946,0,'MAG600NLEDB','Chapa magnética 600 lbs con LED Ultra-brillante Color negro, Libre de Magnetismo Residual, Sensor de estado de la placa, Color Negro','AccessPro','CERRADURA','Bodega General',NULL,NULL,'/img/productos/2502250003168.jpg','2026-07-30 11:37:10',NULL,NULL,'0','admin','2026-07-30 17:37:10','2026-07-30 17:37:10','2502250003168','2026-07-30 11:37:10',NULL),
(1947,0,'BZL600NB','Montaje para MAG600NLEDB tipo Z y L color negro','AccessPro','CERRADURA','Bodega General',NULL,NULL,'/img/productos/2502250005412.jpg','2026-07-30 11:42:16',NULL,NULL,'0','admin','2026-07-30 17:42:16','2026-07-30 17:42:16','2502250005412','2026-07-30 11:42:16',NULL),
(1948,0,'NKPP24FMY','Panel de Parcheo Modular Keystone (Sin Conectores), Numerado y Espacio para Etiquetas, de 24 Puertos, 1UR','Panduit','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/74983056019_1.jpg','2026-07-30 11:50:59',NULL,NULL,'0','admin','2026-07-30 17:50:59','2026-07-30 17:50:59','74983056019','2026-07-30 11:50:59',NULL),
(1949,0,'HTCM1U6C','Multicontacto Horizontal (PDU) de 6 Contactos (NEMA 5-15R) Rack 19\\\" 1UR. Voltaje Entrada/Salida: 120Vca/15A','Linkedpro','CONECTOR O CLAVIJA','Bodega General',NULL,NULL,'/img/productos/HTCM1U6C.jpg','2026-07-30 12:06:47',NULL,NULL,'0','admin','2026-07-30 18:06:47','2026-07-30 18:06:47','HTCM1U6C','2026-07-30 12:06:47',NULL),
(1950,0,'DS-7632NXI-K2/16P','NVR 12 Megapixel (4K), 32 canales IP, 16 Puertos PoE+ Recocimiento Facial, AcuSense (Evita Falsas Alarmas) 2 Bahías de Disco Duro, HDMI en 4K, Alarmas I/O ,300 Metros PoE Modo Extendido','Hikvison','VIDEO','Bodega General',NULL,NULL,'/img/productos/6936422159436.jpg','2026-07-30 12:21:29',NULL,NULL,'0','admin','2026-07-30 18:21:29','2026-07-30 18:21:29','6936422159436','2026-07-30 12:21:29',NULL),
(1951,0,'S220-24P4X','Switch de Acceso eKit Administrable, Capa 2 / 24 Puertos PoE+ Gigabit + 4 Puertos SFP+/SFP + Puerto de Consola, Poe Perpetuo, Presupuesto PoE 400 W, Sin Licenciamiento','Huawei','SWITCH','Bodega General',NULL,NULL,'/img/productos/6901443450084_1.jpg','2026-07-30 15:08:04',NULL,NULL,'0','admin','2026-07-30 21:08:04','2026-07-30 21:08:04','6901443450084','2026-07-30 15:08:04',NULL),
(1952,0,'AR180PRO','Router eKit Wi-Fi 7 para 150 clientes, Controladora para 8 APs, 2 Puertos WAN 2.5 Gbps + 1 Puerto LAN Gigabit + 3 Puertos LAN/WAN Gigabit, Gestión en la nube, Libre de licenciamiento','Huawei','RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/6901443468973.jpg','2026-07-30 15:44:38',NULL,NULL,'0','admin','2026-07-30 21:44:38','2026-07-30 21:44:38','6901443468973','2026-07-30 15:44:38',NULL),
(1953,0,'UTP28SP3GY','Cable de Parcheo TX6, UTP Cat6, Diámetro Reducido (28AWG), Color Gris, 0.91 Metros (3 Pies)','Panduit','CABLEADO','Bodega General',NULL,NULL,NULL,'2026-07-31 09:56:23',NULL,NULL,'0','admin','2026-07-31 15:56:23','2026-07-31 15:56:23','74983959747','2026-07-31 09:56:23',NULL),
(1954,0,'LRS-350-12','Fuente de poder industrial conmutada 350W de potencia 12V, 29A','Mean Wells','FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/LRS-350-12_1.jpg','2026-07-31 11:14:34',NULL,NULL,'0','admin','2026-07-31 17:14:34','2026-07-31 17:14:34','LRS-350-12','2026-07-31 11:14:34',NULL),
(1955,50,'DS-K7M101-M1','Tarjeta MiFare 13.56 MHz, Imprimible Ambos Lados, PVC Blanco, Control de Acceso Hikvision','Hikvison','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/303703308.jpg','2026-07-31 12:43:46',NULL,NULL,'0','admin','2026-07-31 18:43:46','2026-07-31 18:43:46','303703308','2026-07-31 12:43:46',1354.00),
(1956,2,'HWL-MWCL-WH','Controlador inalámbrico multicanal, diseñado para alimentar y controlar tiras de luces LED RGB y blancas ajustables de Lumaris, Homeworks QSX','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276823715.jpg','2026-07-31 17:02:15',NULL,NULL,'0','admin','2026-07-31 23:02:15','2026-07-31 23:02:15','784276823715','2026-07-31 17:02:15',NULL),
(1957,2,'134','Soquet Porcelana Redondo, color Blanco','IUSA','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/791279001583.jpg','2026-08-04 16:13:09',NULL,NULL,'0','admin','2026-08-04 22:13:09','2026-08-04 22:13:09','791279001583','2026-08-04 16:13:09',NULL),
(1958,6,'A-100FAN-12','Fuente de poder regulada,  100W 12V 8.5A FPC','CZCL','FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/A-100FAN-12_1.jpg','2026-08-04 16:47:14',NULL,NULL,'0','admin','2026-08-04 22:47:14','2026-08-04 22:47:14','A-100FAN-12','2026-08-04 16:47:14',NULL),
(1959,2,'A-350FAK-12','Fuente de poder industrial conmutada 350W de potencia 12V, 29A UL','CZCL','FUENTE DE PODER','Bodega General',NULL,NULL,'/img/productos/A-350FAK-12.jpg','2026-08-04 16:53:24',NULL,NULL,'0','admin','2026-08-04 22:53:24','2026-08-04 22:53:24','A-350FAK-12','2026-08-04 16:53:24',NULL),
(1960,2,'UAP-AC-LR','Punto de acceso UniFi de largo alcance, Doble banda 802.11ac MIMO2X2 para interior, PoE 802.3af, soporta 250 clientes, hasta 867 Mbps','Ubiquiti','PUNTO DE ACCESO','Bodega General',NULL,NULL,'/img/productos/UAP-AC-LR.jpg','2026-08-06 09:19:14',NULL,NULL,'0','admin','2026-08-06 15:19:14','2026-08-06 15:19:14','UAP-AC-LR','2026-08-06 09:19:14',NULL),
(1961,990,' TC6-PASS','Bote con 100 Plugs Pass Through RJ45 Cat6 sin Blindaje, Chapado de Oro a 30 Micras para Durabilidad Extrema','Linkedpro','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/TC6-PASS.jpg','2026-08-11 14:46:27',NULL,NULL,'0','admin','2026-08-11 20:46:27','2026-08-11 20:46:27',' TC6-PASS','2026-08-11 14:46:27',NULL),
(1962,3,' EP670','Herramienta de Corte y Terminado de Plugs Pass Through RJ45, Acero al Carbón y Mango Ergonómico ABS + TPR. (Ponchado, impacto y Pelado)','Epcom','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/EP670.jpg','2026-08-11 14:55:22',NULL,NULL,'0','admin','2026-08-11 20:55:22','2026-08-11 20:55:22',' EP670','2026-08-11 14:55:22',NULL),
(1963,10,'EF-UNION-LC','Conector Mecánico LC-LC Duplex, para Carretes de Fibra Óptica EZ Fiber','Linkedpro','FIBRA OPTICA','Bodega General',NULL,NULL,'/img/productos/EF-UNION-LC.jpg','2026-08-11 16:24:09',NULL,NULL,'0','admin','2026-08-11 22:24:09','2026-08-11 22:24:09','EF-UNION-LC','2026-08-11 16:24:09',NULL),
(1964,0,'MG10','Mezcladora de Audio, 10 Canales, 4 Entradas de Micrófono, 3 Entradas de Línea Estéreo, Phantom Power','Yamaha','AUDIO','Bodega General',NULL,NULL,'/img/productos/86792985091.jpg','2026-08-12 15:53:02',NULL,NULL,'0','admin','2026-08-12 21:53:02','2026-08-12 21:53:02','86792985091','2026-08-12 15:53:02',1164.00),
(1965,0,'SCR-15-GFST-WH-S ','Contacto duplex falla tierra de 15A GFCI con proteccion para niños lutron satin colors color Blanco','Lutron','ILUMINACION','Bodega General',NULL,NULL,NULL,'2026-08-14 15:55:29',NULL,NULL,'0','admin','2026-08-14 21:55:29','2026-08-14 21:55:29','SCR-15-GFST-WH-S','2026-08-14 15:55:29',NULL),
(1966,0,'LRS-150-12','Fuente de poder regulada 12V 12.5A 150W','Mean Well','FUENTE DE PODER','Bodega General',NULL,NULL,NULL,'2026-08-14 20:52:54',NULL,NULL,'0','admin','2026-08-15 02:52:54','2026-08-15 02:52:54','LRS-150-12','2026-08-14 20:52:54',NULL),
(1967,1,'LU-PH3-B','Interfaz de alimentación para tiras de luces Lumaris de 96 W','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276823784_1.png','2026-08-17 15:49:20',NULL,NULL,'0','admin','2026-08-17 21:49:20','2026-08-17 21:49:20','784276823784','2026-08-17 15:49:20',NULL),
(1968,40,'LU-CK1-4W','Conectores de cuatro clavijas de cable a cinta (wire-to-tape)','Lutron','ILUMINACION','Bodega General',NULL,NULL,'/img/productos/784276338394.jpg','2026-08-17 15:59:14',NULL,NULL,'0','admin','2026-08-17 21:59:14','2026-08-17 21:59:14','784276338394','2026-08-17 15:59:14',NULL),
(1969,8,'BS-400','Soplete con cerradura de seguridad, llama ajustable, recargable con gas butano','Yostyle','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/BS-400_1.jpg','2026-08-18 10:39:26',NULL,NULL,'0','admin','2026-08-18 16:39:26','2026-08-18 16:39:26','BS-400','2026-08-18 10:39:26',NULL),
(1970,1,'RG-EG210G-E','Router Balanceador administrable, 10 puertos gibabit, soporta 4x WAN configurables','Ruijie','RED E INTERNET','Bodega General',NULL,NULL,'/img/productos/6971693271586_1.jpg','2026-08-18 11:06:37',NULL,NULL,'0','admin','2026-08-18 17:06:37','2026-08-18 17:06:37','6971693271586','2026-08-18 11:06:37',NULL),
(1971,9,'AV102','Cable Adaptador De Audio  3.5mm A 2 Rca (estéreo) 3m','Ugreen','CABLES (Accesorios)','Bodega General',NULL,NULL,'/img/productos/695730815128_1.jpg','2026-08-18 13:49:47',NULL,NULL,'0','admin','2026-08-18 19:49:47','2026-08-18 19:49:47','695730815128','2026-08-18 13:49:47',NULL),
(1972,1,'PST-C05-001-BK','Cinta de Contacto Autoadherente Color Negro / 1.6 cm x 25 metros / Espalda con Espalda','Precision','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/PST-C05-001-BK.jpg','2026-08-19 09:56:22',NULL,NULL,'0','admin','2026-08-19 15:56:22','2026-08-19 15:56:22','PST-C05-001-BK','2026-08-19 09:56:22',NULL),
(1973,0,'CH-60-300EZ','Charola tipo malla 60x300 mm ancho, tramo 3 m','charofil','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/CH-60-300EZ.jpg','2026-08-19 09:59:44',NULL,NULL,'0','admin','2026-08-19 15:59:44','2026-08-19 15:59:44','CH-60-300EZ','2026-08-19 09:59:44',NULL),
(1974,1,'STV-026','Soporte de techo o pared para proyector','Steren','SOPORTE O BASE','Bodega General',NULL,NULL,'/img/productos/STV-026_1.jpg','2026-08-19 12:04:49',NULL,NULL,'0','admin','2026-08-19 18:04:49','2026-08-19 18:04:49','STV-026','2026-08-19 12:04:49',1144.00),
(1975,17,'MP2X(01)','Conector plug mono de 1/4 macho, carcasa cromada perla','Seetronic','AUDIO','Bodega General',NULL,NULL,'/img/productos/MP2X-01.jpg','2026-08-19 12:25:04',NULL,NULL,'0','admin','2026-08-19 18:25:04','2026-08-19 18:25:04','MP2X(01)','2026-08-19 12:25:04',NULL),
(1976,2,'DLA-NZ700B','Proyector Nativo 4K D-ILA, HDMI, Color Negro','JVC','VIDEO','Bodega General',NULL,NULL,'/img/productos/46838503986_1.png','2026-08-19 16:25:23',NULL,NULL,'0','admin','2026-08-19 22:25:23','2026-08-19 22:25:23','46838503986','2026-08-19 16:25:23',1177.00),
(1977,1,'NCT875','Amplificador de potencia Ct875 de 8 canales y 75 W a 4 Ohmios','Crown ','AUDIO','Bodega General',NULL,NULL,'/img/productos/871015004914.jpg','2026-08-20 14:46:19',NULL,NULL,'0','admin','2026-08-20 20:46:19','2026-08-20 20:46:19','871015004914','2026-08-20 14:46:19',1122.00),
(1978,0,'RDIN_PD10','Riel Din estandar 35 MM de ancho y 7.5 Mm de Grosor, con kit de bloques de terminales  PDT-DS-1 o PDT-ED-1','Lutron','ACCESORIO','Bodega General',NULL,NULL,NULL,'2026-08-26 09:36:36',NULL,NULL,'0','admin','2026-08-26 15:36:36','2026-08-26 15:36:36','RDIN_PD10','2026-08-26 09:36:36',NULL),
(1979,8,'DATA TRAVEL SE9','USB 64GB','KINGSTON ','INSUMOS Y HERRAMIENTAS','Bodega General',NULL,NULL,'/img/productos/6604933C.jpg','2026-08-26 11:53:46',NULL,NULL,'0','admin','2026-08-26 17:53:46','2026-08-26 17:53:46','6604933C','2026-08-26 11:53:46',NULL),
(1980,100,'CONECTOR TELEFONICO RJ-11 DOS PARES','RJ-11 DOS PARES','STEREN','ACCESORIO','Bodega General',NULL,NULL,'/img/productos/300-062.jpg','2026-08-28 10:11:08',NULL,NULL,'0','admin','2026-08-28 16:11:08','2026-08-28 16:11:08','300-062','2026-08-28 10:11:08',NULL),
(1981,4,'CSH6C/3MP','Camara PT 3 Megapixel, Wi-Fi de Doble banda 2.4,  5 GHz, Vision Nocturna A Color, Detección de Ruidos Fuertes, Seguimiento Inteligente, Deteccion de Humanos, Audio de Dos Vías, MicroSD512GB','EZVIZ','VIDEO','Bodega General',NULL,NULL,'/img/productos/6941545622781.jpg','2026-08-29 09:28:52',NULL,NULL,'0','admin','2026-08-29 15:28:52','2026-08-29 15:28:52','6941545622781','2026-08-29 09:28:52',NULL),
(1982,4,' CSH8C/3MP','Cámara PT  Wi-Fi, Resolución 3 Megapixel (2K), Audio de Dos Vías, Visión Nocturna en Color, AI Detección de Humanos y Vehículos, Defensa Activa con Sirena y Luz Estroboscópica, IP65','EZVIZ','VIDEO','Bodega General',NULL,NULL,'/img/productos/6941545622651.jpg','2026-08-29 09:45:57',NULL,NULL,'0','admin','2026-08-29 15:45:57','2026-08-29 15:45:57','6941545622651','2026-08-29 09:45:57',NULL),
(1983,1,'CS-X5S/8W','NVR Inalámbrico (WiFi) de 8 Canales IP, Admite Cámaras hasta 5 Megapixel (3K), Salidas HDMI y VGA, H.265, Almacenamiento hasta 8 TB','EZVIZ','VIDEO','Bodega General',NULL,NULL,'/img/productos/6941545622392_1.jpg','2026-08-29 09:55:48',NULL,NULL,'0','admin','2026-08-29 15:55:48','2026-08-29 15:55:48','6941545622392','2026-08-29 09:55:48',NULL),
(1984,1,'SG2218P','Switch Omada SDN Administrable, 16 puertos Gigabit y 2 puertos SFP, Funciones sFlow, QinQ y QoS, Administración centralizada OMADA','Tp-Link','SWITCH','Bodega General',NULL,NULL,'/img/productos/840030709500.jpg','2026-08-29 10:11:59',NULL,NULL,'0','admin','2026-08-29 16:11:59','2026-08-29 16:11:59','840030709500','2026-08-29 10:11:59',NULL);
/*!40000 ALTER TABLE `inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'2024_01_01_111000_create_traccar_devices_table',1),
(2,'2026_08_18_011115_create_roles_table',1),
(3,'2026_08_18_011116_create_usuarios_table',1),
(4,'2026_08_18_011117_create_categorias_table',1),
(5,'2026_08_18_011118_create_estatus_table',1),
(6,'2026_08_18_011119_create_clientes_table',1),
(7,'2026_08_18_011120_create_ventas_table',1),
(8,'2026_08_18_011121_create_proyectos_table',1),
(9,'2026_08_18_011122_create_inventario_table',1),
(10,'2026_08_18_011123_create_movimientos_inventario_table',1),
(11,'2026_08_18_011124_create_instalaciones_table',1),
(12,'2026_08_18_024829_create_sessions_table',1),
(13,'2026_08_18_033806_create_cache_table',1),
(14,'2026_08_18_034732_add_estatus_to_ventas_table',1),
(15,'2026_08_18_203337_create_permisos_table',1),
(16,'2026_08_18_203338_create_permiso_rol_table',1),
(17,'2026_08_19_210309_create_notificaciones_table',1),
(18,'2026_08_19_232453_create_personal_access_tokens_table',1),
(19,'2026_08_20_012059_create_personal_access_tokens_table',1),
(20,'2026_08_20_012616_create_personal_access_tokens_table',1),
(21,'2026_08_20_030958_create_salidas_inventario_table',1),
(22,'2026_08_20_045807_create_devoluciones_inventario_table',1),
(23,'2026_09_05_140623_add_traccar_device_id_to_usuarios_table',1),
(24,'2026_09_12_093523_create_agent_conversations_table',1),
(25,'2026_09_15_153444_agregar_estatus_instalacion_opcion_c',2),
(26,'2026_09_15_153638_agregar_coordenadas_softdeletes_instalaciones',2),
(27,'2026_09_15_153940_create_instalacion_fotos_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos_inventario`
--

DROP TABLE IF EXISTS `movimientos_inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimientos_inventario` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `inventario_id` bigint(20) unsigned NOT NULL,
  `entrada` int(11) DEFAULT NULL,
  `salida` int(11) DEFAULT NULL,
  `ajuste` int(11) DEFAULT NULL,
  `devolucion` int(11) DEFAULT NULL,
  `apartado` int(11) DEFAULT NULL,
  `instalacion` varchar(255) DEFAULT NULL,
  `devolucion_proveedor` int(11) DEFAULT NULL,
  `modificado_por` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `movimientos_inventario_inventario_id_foreign` (`inventario_id`),
  KEY `movimientos_inventario_instalacion_foreign` (`instalacion`),
  KEY `movimientos_inventario_modificado_por_foreign` (`modificado_por`),
  CONSTRAINT `movimientos_inventario_instalacion_foreign` FOREIGN KEY (`instalacion`) REFERENCES `ventas` (`nombre_proyecto`),
  CONSTRAINT `movimientos_inventario_inventario_id_foreign` FOREIGN KEY (`inventario_id`) REFERENCES `inventario` (`id`),
  CONSTRAINT `movimientos_inventario_modificado_por_foreign` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos_inventario`
--

LOCK TABLES `movimientos_inventario` WRITE;
/*!40000 ALTER TABLE `movimientos_inventario` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimientos_inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `referencia_id` int(11) DEFAULT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notificaciones_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `notificaciones_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso_rol`
--

DROP TABLE IF EXISTS `permiso_rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permiso_rol` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) NOT NULL,
  `permiso_id` bigint(20) unsigned NOT NULL,
  `permitido` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permiso_rol_rol_permiso_id_unique` (`rol`,`permiso_id`),
  KEY `permiso_rol_permiso_id_foreign` (`permiso_id`),
  CONSTRAINT `permiso_rol_permiso_id_foreign` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permiso_rol_rol_foreign` FOREIGN KEY (`rol`) REFERENCES `roles` (`rol`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso_rol`
--

LOCK TABLES `permiso_rol` WRITE;
/*!40000 ALTER TABLE `permiso_rol` DISABLE KEYS */;
INSERT INTO `permiso_rol` VALUES
(1,'Administrador',25,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(2,'Administrador',17,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(3,'Administrador',41,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(4,'Administrador',13,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(5,'Administrador',35,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(6,'Administrador',3,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(7,'Administrador',21,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(8,'Administrador',31,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(9,'Administrador',39,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(10,'Administrador',8,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(11,'Administrador',26,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(12,'Administrador',18,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(13,'Administrador',14,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(14,'Administrador',36,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(15,'Administrador',4,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(16,'Administrador',22,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(17,'Administrador',32,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(18,'Administrador',9,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(19,'Administrador',27,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(20,'Administrador',19,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(21,'Administrador',15,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(22,'Administrador',37,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(23,'Administrador',5,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(24,'Administrador',23,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(25,'Administrador',33,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(26,'Administrador',10,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(27,'Administrador',6,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(28,'Administrador',11,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(29,'Administrador',29,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(30,'Administrador',24,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(31,'Administrador',16,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(32,'Administrador',1,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(33,'Administrador',40,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(34,'Administrador',12,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(35,'Administrador',2,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(36,'Administrador',34,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(37,'Administrador',20,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(38,'Administrador',28,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(39,'Administrador',30,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(40,'Administrador',38,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(41,'Administrador',7,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(64,'Instalador',1,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(65,'Instalador',12,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(66,'Instalador',14,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(67,'Instalador',20,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(68,'Instalador',24,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(69,'Inventarios',1,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(70,'Inventarios',2,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(71,'Inventarios',3,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(72,'Inventarios',4,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(73,'Inventarios',5,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(74,'Inventarios',6,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(75,'Inventarios',12,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(76,'Inventarios',20,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(77,'Inventarios',38,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(78,'Inventarios',39,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(79,'Inventarios',40,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(80,'Inventarios',41,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(81,'Contabilidad',1,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(82,'Contabilidad',7,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(83,'Contabilidad',12,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(84,'Contabilidad',16,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(85,'Contabilidad',17,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(86,'Contabilidad',18,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(87,'Contabilidad',19,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(88,'Contabilidad',20,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(89,'Ventas',1,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(90,'Ventas',2,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(91,'Ventas',7,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(92,'Ventas',8,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(93,'Ventas',9,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(94,'Ventas',10,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(95,'Ventas',11,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(96,'Ventas',12,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(97,'Ventas',14,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(98,'Ventas',16,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(99,'Ventas',20,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(100,'Ventas',22,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(101,'Ventas',24,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(102,'Ventas',25,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(103,'Ventas',26,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(104,'Ventas',27,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(105,'Ventas',28,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(106,'Ventas',29,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(107,'Sistemas',1,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(108,'Sistemas',20,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(109,'Sistemas',21,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(110,'Sistemas',22,1,'2026-08-20 22:35:05','2026-08-20 22:35:05'),
(111,'Sistemas',23,1,'2026-08-20 22:35:05','2026-08-20 22:35:05');
/*!40000 ALTER TABLE `permiso_rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permisos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `modulo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES
(1,'Ver Dashboard','ver-dashboard','Acceder al dashboard principal','dashboard','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(2,'Ver Inventario','ver-inventario','Ver listado de inventario','inventario','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(3,'Crear Producto','crear-producto','Agregar nuevos productos','inventario','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(4,'Editar Producto','editar-producto','Editar productos existentes','inventario','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(5,'Eliminar Producto','eliminar-producto','Eliminar productos','inventario','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(6,'Exportar Inventario','exportar-inventario','Exportar inventario a archivo','inventario','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(7,'Ver Ventas','ver-ventas','Ver listado de ventas','ventas','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(8,'Crear Venta','crear-venta','Registrar nuevas ventas','ventas','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(9,'Editar Venta','editar-venta','Editar ventas existentes','ventas','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(10,'Eliminar Venta','eliminar-venta','Eliminar ventas','ventas','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(11,'Exportar Ventas','exportar-ventas','Exportar ventas a archivo','ventas','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(12,'Ver Instalaciones','ver-instalaciones','Ver listado de instalaciones','instalaciones','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(13,'Crear Instalación','crear-instalacion','Registrar nuevas instalaciones','instalaciones','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(14,'Editar Instalación','editar-instalacion','Editar instalaciones existentes','instalaciones','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(15,'Eliminar Instalación','eliminar-instalacion','Eliminar instalaciones','instalaciones','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(16,'Ver Clientes','ver-clientes','Ver listado de clientes','clientes','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(17,'Crear Cliente','crear-cliente','Registrar nuevos clientes','clientes','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(18,'Editar Cliente','editar-cliente','Editar clientes existentes','clientes','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(19,'Eliminar Cliente','eliminar-cliente','Eliminar clientes','clientes','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(20,'Ver Proyectos','ver-proyectos','Ver listado de proyectos','proyectos','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(21,'Crear Proyecto','crear-proyecto','Registrar nuevos proyectos','proyectos','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(22,'Editar Proyecto','editar-proyecto','Editar proyectos existentes','proyectos','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(23,'Eliminar Proyecto','eliminar-proyecto','Eliminar proyectos','proyectos','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(24,'Ver Asignaciones','ver-asignaciones','Ver listado de asignaciones','asignaciones','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(25,'Crear Asignación','crear-asignacion','Crear nuevas asignaciones','asignaciones','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(26,'Editar Asignación','editar-asignacion','Editar asignaciones existentes','asignaciones','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(27,'Eliminar Asignación','eliminar-asignacion','Eliminar asignaciones','asignaciones','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(28,'Ver Reportes','ver-reportes','Ver módulo de reportes','reportes','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(29,'Generar Reportes','generar-reportes','Generar nuevos reportes','reportes','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(30,'Ver Roles','ver-roles','Ver listado de roles','roles','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(31,'Crear Rol','crear-rol','Crear nuevos roles','roles','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(32,'Editar Rol','editar-rol','Editar roles existentes','roles','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(33,'Eliminar Rol','eliminar-rol','Eliminar roles','roles','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(34,'Ver Permisos','ver-permisos','Ver listado de permisos','roles','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(35,'Crear Permiso','crear-permiso','Crear nuevos permisos','roles','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(36,'Editar Permiso','editar-permiso','Editar permisos existentes','roles','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(37,'Eliminar Permiso','eliminar-permiso','Eliminar permisos','roles','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(38,'Ver Salidas de Inventario','ver-salidas','Permite ver el listado de salidas de inventario','inventario','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(39,'Crear Salida de Inventario','crear-salida','Permite registrar nuevas salidas de inventario','inventario','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(40,'Ver Devoluciones de Inventario','ver-devoluciones','Permite ver el listado de devoluciones de inventario','inventario','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(41,'Crear Devolución de Inventario','crear-devolucion','Permite registrar nuevas devoluciones de inventario','inventario','2026-08-20 22:35:05','2026-08-20 22:35:05');
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proyectos`
--

DROP TABLE IF EXISTS `proyectos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `proyectos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_proyecto` varchar(255) NOT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `ubicacion` point DEFAULT NULL,
  `propuesta_economica` mediumblob DEFAULT NULL,
  `archivo_as_built` mediumblob DEFAULT NULL,
  `credenciales` varchar(255) DEFAULT NULL,
  `salida_inventario` mediumblob DEFAULT NULL,
  `devolucion_inventario` mediumblob DEFAULT NULL,
  `modificado_por` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proyectos_nombre_proyecto_foreign` (`nombre_proyecto`),
  KEY `proyectos_modificado_por_foreign` (`modificado_por`),
  CONSTRAINT `proyectos_modificado_por_foreign` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `proyectos_nombre_proyecto_foreign` FOREIGN KEY (`nombre_proyecto`) REFERENCES `ventas` (`nombre_proyecto`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proyectos`
--

LOCK TABLES `proyectos` WRITE;
/*!40000 ALTER TABLE `proyectos` DISABLE KEYS */;
INSERT INTO `proyectos` VALUES
(1,'Isla capitan','islacapitan@savicontrolhome.com',NULL,NULL,NULL,NULL,NULL,NULL,'admin','2026-08-27 09:34:42','2026-08-27 09:34:42'),
(2,'kupuri 24','kupury24@savicontrolhome.com',NULL,NULL,NULL,NULL,NULL,NULL,'admin','2026-08-27 09:36:15','2026-08-27 09:36:15'),
(3,'lote9','lote9@savicontrolhome.com',NULL,NULL,NULL,NULL,NULL,NULL,'admin','2026-08-27 09:50:03','2026-08-27 09:50:03'),
(4,'casa pacifico','casapacifico@hotmaol.com',NULL,NULL,NULL,NULL,NULL,NULL,'admin','2026-08-28 07:28:14','2026-08-28 07:28:14'),
(5,'nauca','nauca@savicontrolhome.com',NULL,NULL,NULL,NULL,NULL,NULL,'admin','2026-08-28 07:38:58','2026-08-28 07:38:58'),
(6,'lote 31','lote31@savicontrolhome.com',NULL,NULL,NULL,NULL,NULL,NULL,'admin','2026-08-28 07:39:37','2026-08-28 07:39:37'),
(7,'PUNTO NOVO','jsanchez@dcmarq.com',NULL,NULL,NULL,NULL,NULL,NULL,'admin','2026-09-08 13:04:21','2026-09-08 13:04:21');
/*!40000 ALTER TABLE `proyectos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rol` (`rol`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'Administrador','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(2,'Contabilidad','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(3,'Inventarios','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(4,'Instalador','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(5,'Ventas','2026-08-20 22:35:05','2026-08-20 22:35:05'),
(6,'Sistemas','2026-08-20 22:35:05','2026-08-20 22:35:05');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salida_detalle`
--

DROP TABLE IF EXISTS `salida_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `salida_detalle` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `salida_id` bigint(20) unsigned NOT NULL,
  `inventario_id` bigint(20) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(12,2) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `salida_id` (`salida_id`),
  KEY `inventario_id` (`inventario_id`),
  CONSTRAINT `salida_detalle_ibfk_1` FOREIGN KEY (`salida_id`) REFERENCES `salidas_inventario` (`id`) ON DELETE CASCADE,
  CONSTRAINT `salida_detalle_ibfk_2` FOREIGN KEY (`inventario_id`) REFERENCES `inventario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salida_detalle`
--

LOCK TABLES `salida_detalle` WRITE;
/*!40000 ALTER TABLE `salida_detalle` DISABLE KEYS */;
INSERT INTO `salida_detalle` VALUES
(1,2,531,1,NULL,NULL,'2026-09-08 10:36:59','2026-09-08 10:36:59'),
(2,3,791,1,NULL,NULL,'2026-09-08 11:34:49','2026-09-08 11:34:49');
/*!40000 ALTER TABLE `salida_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salidas_inventario`
--

DROP TABLE IF EXISTS `salidas_inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `salidas_inventario` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_proyecto` varchar(255) NOT NULL,
  `entregado_por` varchar(255) NOT NULL,
  `entregado_a` varchar(255) NOT NULL,
  `fecha_hora_salida` datetime NOT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `salidas_inventario_nombre_proyecto_foreign` (`nombre_proyecto`),
  KEY `salidas_inventario_entregado_por_foreign` (`entregado_por`),
  KEY `salidas_inventario_entregado_a_foreign` (`entregado_a`),
  CONSTRAINT `salidas_inventario_entregado_a_foreign` FOREIGN KEY (`entregado_a`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `salidas_inventario_entregado_por_foreign` FOREIGN KEY (`entregado_por`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `salidas_inventario_nombre_proyecto_foreign` FOREIGN KEY (`nombre_proyecto`) REFERENCES `ventas` (`nombre_proyecto`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salidas_inventario`
--

LOCK TABLES `salidas_inventario` WRITE;
/*!40000 ALTER TABLE `salidas_inventario` DISABLE KEYS */;
INSERT INTO `salidas_inventario` VALUES
(2,'kupuri 24','admin','lino','2026-09-08 10:36:59',NULL,'2026-09-08 10:36:59','2026-09-08 10:36:59'),
(3,'Isla capitan','admin','lino','2026-09-08 11:34:49',NULL,'2026-09-08 11:34:49','2026-09-08 11:34:49');
/*!40000 ALTER TABLE `salidas_inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('IagFMz1LI7OhWTAEtSMmQWvPdI6dUMGTmiNUU8Uv',NULL,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:154.0) Gecko/20100101 Firefox/154.0','eyJfdG9rZW4iOiJtVldKMzRrWUlENWVoWnRHQjRMSWlmdnJMNTBGYUlxQ3l3V0pRVHFvIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9pbnN0YWxhY2lvbmVzXC9tYXBhLWRhdGEiLCJyb3V0ZSI6Imluc3RhbGFjaW9uZXMubWFwYURhdGEifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1c2VyX2lkIjoxLCJ1c2VyX3VzdWFyaW8iOiJhZG1pbiIsInVzZXJfbm9tYnJlIjoiQWRtaW5pc3RyYWRvciBQcmluY2lwYWwiLCJ1c2VyX3JvbCI6IkFkbWluaXN0cmFkb3IiLCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvdHJhY2NhciJ9fQ==',1789518746);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitudes_ubicacion`
--

DROP TABLE IF EXISTS `solicitudes_ubicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_ubicacion` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `chat_id` varchar(100) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `instalacion_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `solicitudes_usuario_id_foreign` (`usuario_id`),
  KEY `fk_solicitudes_ubicacion_instalacion` (`instalacion_id`),
  CONSTRAINT `fk_solicitudes_ubicacion_instalacion` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `solicitudes_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitudes_ubicacion`
--

LOCK TABLES `solicitudes_ubicacion` WRITE;
/*!40000 ALTER TABLE `solicitudes_ubicacion` DISABLE KEYS */;
INSERT INTO `solicitudes_ubicacion` VALUES
(10,2,'8884130238','fin',26,'2026-08-30 23:23:01','2026-08-30 23:23:01');
/*!40000 ALTER TABLE `solicitudes_ubicacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `traccar_devices`
--

DROP TABLE IF EXISTS `traccar_devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `traccar_devices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `uniqueId` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT NULL,
  `lastUpdate` timestamp NULL DEFAULT NULL,
  `positionId` int(11) DEFAULT NULL,
  `groupId` int(11) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `attribs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attribs`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `traccar_devices`
--

LOCK TABLES `traccar_devices` WRITE;
/*!40000 ALTER TABLE `traccar_devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `traccar_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ubicaciones_usuarios`
--

DROP TABLE IF EXISTS `ubicaciones_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ubicaciones_usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `latitud` decimal(10,7) NOT NULL,
  `longitud` decimal(10,7) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `fuente` varchar(50) NOT NULL DEFAULT 'telegram',
  `tipo` varchar(20) DEFAULT 'inicio',
  `instalacion_id` bigint(20) unsigned DEFAULT NULL,
  `detalles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalles`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_usuario_id` (`usuario_id`),
  KEY `fk_ubicaciones_usuarios_instalacion` (`instalacion_id`),
  CONSTRAINT `fk_ubicaciones_usuarios_instalacion` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ubicaciones_usuarios_usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ubicaciones_usuarios`
--

LOCK TABLES `ubicaciones_usuarios` WRITE;
/*!40000 ALTER TABLE `ubicaciones_usuarios` DISABLE KEYS */;
INSERT INTO `ubicaciones_usuarios` VALUES
(1,2,20.7727700,-105.2201620,'2026-08-30 12:17:15','telegram','inicio',15,'{\"message_id\":26,\"from\":{\"id\":8884130238,\"is_bot\":false,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"language_code\":\"es\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788070634,\"reply_to_message\":{\"message_id\":25,\"from\":{\"id\":8707500603,\"is_bot\":true,\"first_name\":\"Proyectosavi\",\"username\":\"Proyectosavibot\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788070616,\"text\":\"\\ud83d\\udfe2 Para iniciar la jornada, comparte tu ubicaci\\u00f3n actual presionando el bot\\u00f3n de abajo.\",\"entities\":[{\"offset\":8,\"length\":7,\"type\":\"bold\"}]},\"location\":{\"latitude\":20.77277,\"longitude\":-105.220162}}','2026-08-30 12:17:15','2026-08-30 12:17:15'),
(2,2,20.7727700,-105.2201620,'2026-08-30 12:22:37','telegram','fin',15,'{\"message_id\":32,\"from\":{\"id\":8884130238,\"is_bot\":false,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"language_code\":\"es\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788070957,\"reply_to_message\":{\"message_id\":31,\"from\":{\"id\":8707500603,\"is_bot\":true,\"first_name\":\"Proyectosavi\",\"username\":\"Proyectosavibot\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788070944,\"text\":\"\\ud83d\\udd34 Para finalizar la jornada, comparte tu ubicaci\\u00f3n actual presionando el bot\\u00f3n de abajo.\",\"entities\":[{\"offset\":8,\"length\":9,\"type\":\"bold\"}]},\"location\":{\"latitude\":20.77277,\"longitude\":-105.220162}}','2026-08-30 12:22:37','2026-08-30 12:22:37'),
(7,2,20.7727700,-105.2201620,'2026-08-30 23:05:18','telegram','inicio',25,'{\"message_id\":54,\"from\":{\"id\":8884130238,\"is_bot\":false,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"language_code\":\"es\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788109518,\"reply_to_message\":{\"message_id\":53,\"from\":{\"id\":8707500603,\"is_bot\":true,\"first_name\":\"Proyectosavi\",\"username\":\"Proyectosavibot\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788109510,\"text\":\"\\ud83d\\udfe2 Para iniciar la jornada, comparte tu ubicaci\\u00f3n actual presionando el bot\\u00f3n de abajo.\",\"entities\":[{\"offset\":8,\"length\":7,\"type\":\"bold\"}]},\"location\":{\"latitude\":20.77277,\"longitude\":-105.220162}}','2026-08-30 23:05:18','2026-08-30 23:05:18'),
(8,2,20.7727700,-105.2201620,'2026-08-30 23:22:51','telegram','inicio',26,'{\"message_id\":59,\"from\":{\"id\":8884130238,\"is_bot\":false,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"language_code\":\"es\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788110571,\"reply_to_message\":{\"message_id\":58,\"from\":{\"id\":8707500603,\"is_bot\":true,\"first_name\":\"Proyectosavi\",\"username\":\"Proyectosavibot\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788110565,\"text\":\"\\ud83d\\udfe2 Para iniciar la jornada, comparte tu ubicaci\\u00f3n actual presionando el bot\\u00f3n de abajo.\",\"entities\":[{\"offset\":8,\"length\":7,\"type\":\"bold\"}]},\"location\":{\"latitude\":20.77277,\"longitude\":-105.220162}}','2026-08-30 23:22:51','2026-08-30 23:22:51'),
(10,2,20.7727700,-105.2201620,'2026-08-31 02:28:35','telegram','fin',25,'{\"message_id\":73,\"from\":{\"id\":8884130238,\"is_bot\":false,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"language_code\":\"es\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788121716,\"reply_to_message\":{\"message_id\":72,\"from\":{\"id\":8707500603,\"is_bot\":true,\"first_name\":\"Proyectosavi\",\"username\":\"Proyectosavibot\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788121697,\"text\":\"\\ud83d\\udd34 Para finalizar la jornada, comparte tu ubicaci\\u00f3n actual presionando el bot\\u00f3n de abajo.\",\"entities\":[{\"offset\":8,\"length\":9,\"type\":\"bold\"}]},\"location\":{\"latitude\":20.77277,\"longitude\":-105.220162}}','2026-08-31 02:28:35','2026-08-31 02:28:35'),
(12,2,20.7727700,-105.2201620,'2026-08-31 02:29:53','telegram','fin',26,'{\"message_id\":79,\"from\":{\"id\":8884130238,\"is_bot\":false,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"language_code\":\"es\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788121793,\"reply_to_message\":{\"message_id\":78,\"from\":{\"id\":8707500603,\"is_bot\":true,\"first_name\":\"Proyectosavi\",\"username\":\"Proyectosavibot\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788121784,\"text\":\"\\ud83d\\udd34 Para finalizar la jornada, comparte tu ubicaci\\u00f3n actual presionando el bot\\u00f3n de abajo.\",\"entities\":[{\"offset\":8,\"length\":9,\"type\":\"bold\"}]},\"location\":{\"latitude\":20.77277,\"longitude\":-105.220162}}','2026-08-31 02:29:53','2026-08-31 02:29:53'),
(14,2,20.7727700,-105.2201620,'2026-08-31 02:48:46','telegram','inicio',29,'{\"message_id\":86,\"from\":{\"id\":8884130238,\"is_bot\":false,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"language_code\":\"es\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788122926,\"reply_to_message\":{\"message_id\":85,\"from\":{\"id\":8707500603,\"is_bot\":true,\"first_name\":\"Proyectosavi\",\"username\":\"Proyectosavibot\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788122922,\"text\":\"\\ud83d\\udfe2 Para iniciar la jornada, comparte tu ubicaci\\u00f3n actual presionando el bot\\u00f3n de abajo.\",\"entities\":[{\"offset\":8,\"length\":7,\"type\":\"bold\"}]},\"location\":{\"latitude\":20.77277,\"longitude\":-105.220162}}','2026-08-31 02:48:46','2026-08-31 02:48:46'),
(15,2,20.7727700,-105.2201620,'2026-08-31 02:52:51','telegram','fin',29,'{\"message_id\":90,\"from\":{\"id\":8884130238,\"is_bot\":false,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"language_code\":\"es\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788123171,\"reply_to_message\":{\"message_id\":89,\"from\":{\"id\":8707500603,\"is_bot\":true,\"first_name\":\"Proyectosavi\",\"username\":\"Proyectosavibot\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788123163,\"text\":\"\\ud83d\\udd34 Para finalizar la jornada, comparte tu ubicaci\\u00f3n actual presionando el bot\\u00f3n de abajo.\",\"entities\":[{\"offset\":8,\"length\":9,\"type\":\"bold\"}]},\"location\":{\"latitude\":20.77277,\"longitude\":-105.220162}}','2026-08-31 02:52:51','2026-08-31 02:52:51'),
(17,2,20.7727750,-105.2201430,'2026-09-06 15:37:27','telegram','inicio',33,'{\"message_id\":122,\"from\":{\"id\":8884130238,\"is_bot\":false,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"language_code\":\"es\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788645851,\"reply_to_message\":{\"message_id\":99,\"from\":{\"id\":8707500603,\"is_bot\":true,\"first_name\":\"Proyectosavi\",\"username\":\"Proyectosavibot\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1788186621,\"text\":\"\\ud83d\\udfe2 Para iniciar la jornada, comparte tu ubicaci\\u00f3n actual presionando el bot\\u00f3n de abajo.\",\"entities\":[{\"offset\":8,\"length\":7,\"type\":\"bold\"}]},\"location\":{\"latitude\":20.772775,\"longitude\":-105.220143}}','2026-09-06 15:37:27','2026-09-06 15:37:27'),
(19,2,20.7727750,-105.2201430,'2026-09-15 16:19:02','telegram','inicio',34,'{\"message_id\":292,\"from\":{\"id\":8884130238,\"is_bot\":false,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"language_code\":\"es\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1789510741,\"reply_to_message\":{\"message_id\":291,\"from\":{\"id\":8707500603,\"is_bot\":true,\"first_name\":\"Proyectosavi\",\"username\":\"Proyectosavibot\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1789510734,\"text\":\"\\ud83d\\udfe2 Para iniciar la jornada, comparte tu ubicaci\\u00f3n actual presionando el bot\\u00f3n de abajo.\",\"entities\":[{\"offset\":8,\"length\":7,\"type\":\"bold\"}]},\"location\":{\"latitude\":20.772775,\"longitude\":-105.220143}}','2026-09-15 16:19:02','2026-09-15 16:19:02'),
(20,2,20.7727750,-105.2201430,'2026-09-15 16:21:17','telegram','fin',34,'{\"message_id\":292,\"from\":{\"id\":8884130238,\"is_bot\":false,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"language_code\":\"es\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1789510741,\"reply_to_message\":{\"message_id\":291,\"from\":{\"id\":8707500603,\"is_bot\":true,\"first_name\":\"Proyectosavi\",\"username\":\"Proyectosavibot\"},\"chat\":{\"id\":8884130238,\"first_name\":\"lino\",\"last_name\":\"Rosas\",\"type\":\"private\"},\"date\":1789510734,\"text\":\"\\ud83d\\udfe2 Para iniciar la jornada, comparte tu ubicaci\\u00f3n actual presionando el bot\\u00f3n de abajo.\",\"entities\":[{\"offset\":8,\"length\":7,\"type\":\"bold\"}]},\"location\":{\"latitude\":20.772775,\"longitude\":-105.220143}}','2026-09-15 16:21:17','2026-09-15 16:21:17');
/*!40000 ALTER TABLE `ubicaciones_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `telegram_chat_id` varchar(255) DEFAULT NULL,
  `traccar_device_id` varchar(255) DEFAULT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  UNIQUE KEY `correo` (`correo`),
  UNIQUE KEY `telegram_chat_id` (`telegram_chat_id`),
  UNIQUE KEY `traccar_device_id` (`traccar_device_id`),
  KEY `usuarios_rol_foreign` (`rol`),
  CONSTRAINT `usuarios_rol_foreign` FOREIGN KEY (`rol`) REFERENCES `roles` (`rol`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES
(1,'admin','Administrador Principal','admin@saviproyectos.com','123456789',NULL,'$2y$12$ohD1jRp8UYkYh91yGbu.juUg7HC26ShSdh0.UBuzGeWrpL5dU5DkS','Administrador','2026-08-20 22:35:05','2016-12-19 21:06:00'),
(2,'lino','Lino Antonio Rosas Jimenez','lra@savicontrolhome.com','8884130238','1','$2y$12$BA9JRNS6GcbMc2h89YidYeZZCQlblpc.pl93H8a8uS2.0KCZmcC.i','Instalador','2026-08-21 07:43:10','2026-09-05 18:31:43'),
(4,'edgar','Edgar De La O','eo@savicontrolhome.com','123456787',NULL,'$2y$12$tgywvYIGyYA7/5Oc4AXK.u.ztbhfaeVsjRMQ5TTKxIGHvXyzk1xUC','Inventarios','2026-08-21 08:02:10','2026-08-21 08:02:10'),
(5,'alonso','Ramon Alonso Ibarra','ai@savicontrolhome.com','123456786',NULL,'$2y$12$PwbqMQ0WldnA/j.k.e663OPmY1guUSWVa5CRm5AuLqywuKaMf1IF2','Instalador','2026-08-21 08:03:17','2026-08-21 08:03:17'),
(6,'toño2','Lino Antonio Rosas Martinez','lr@savicontrolhome.com','7071921967',NULL,'$2y$12$UyUnyCmb7KUi9CGJyFz4VexaRkMA5Bp9Vu23kjn5A6zBWsQyf3xMW','Instalador','2026-08-25 10:46:22','2026-08-25 10:46:22'),
(8,'cachito','Fabricio gonzalez','fo@savicontrolhome.com','1234567891',NULL,'$2y$12$Ywwgy6/wC/dymb5Mn8hF5.DKWfkySI78wP/EjePUbLUMCYxLERdBG','Instalador','2026-08-29 09:10:38','2026-08-29 09:10:38'),
(9,'morfin','Jorje Alfonso Morfin Martin','jm@savicontrolhome.com','1234567890',NULL,'$2y$12$PxrdpWp7.1zDLcSt4.AniOuym.qkAm7vXltn2fMw7qG1WKCdIcjhy','Administrador','2026-08-30 16:00:00','2026-09-09 17:31:30');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ventas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titulo_venta` varchar(255) NOT NULL,
  `nombre_proyecto` varchar(255) NOT NULL,
  `moneda` varchar(255) NOT NULL,
  `monto_venta` decimal(10,2) NOT NULL,
  `requerimiento_venta` varchar(255) NOT NULL,
  `cotizacion` mediumblob DEFAULT NULL,
  `ubicacion` point DEFAULT NULL,
  `vendedor` varchar(255) NOT NULL,
  `fecha_hora_levantamiento` datetime NOT NULL,
  `levantamiento` mediumblob DEFAULT NULL,
  `venta_ganada` tinyint(1) NOT NULL DEFAULT 0,
  `razon_perdida_venta` varchar(255) DEFAULT NULL,
  `estatus` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_proyecto` (`nombre_proyecto`),
  KEY `ventas_vendedor_foreign` (`vendedor`),
  CONSTRAINT `ventas_vendedor_foreign` FOREIGN KEY (`vendedor`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
INSERT INTO `ventas` VALUES
(1,'Isla Capitan','Isla capitan','USD',500.00,'radia ra 3, ruijie, hikvision',NULL,NULL,'admin','2026-05-01 03:27:00',NULL,1,NULL,'cierre_venta','2026-08-27 09:30:29','2026-08-27 09:30:29'),
(2,'Kupuri 24','kupuri 24','USD',500.00,'lutron , unifi, hikvision',NULL,NULL,'admin','2026-04-07 03:30:00',NULL,1,NULL,'cierre_venta','2026-08-27 09:33:30','2026-08-27 09:33:30'),
(3,'lote9','lote9','USD',500.00,'redes y lutron',NULL,NULL,'admin','2026-08-27 03:44:00',NULL,1,NULL,'cierre_venta','2026-08-27 09:45:34','2026-08-27 09:45:34'),
(4,'casa pacifico','casa pacifico','USD',1000.00,'redes, lutron y cctv',NULL,NULL,'admin','2026-05-11 03:46:00',NULL,1,NULL,'cierre_venta','2026-08-27 09:48:11','2026-08-27 09:48:11'),
(5,'nauca','nauca','USD',1000.00,'lutron, redes',NULL,NULL,'admin','2026-08-28 01:28:00',NULL,1,NULL,'cierre_venta','2026-08-28 07:29:22','2026-08-28 07:29:22'),
(6,'lote 31','lote 31','USD',1000.00,'redes, iluminacion, cctv',NULL,NULL,'admin','2026-08-28 01:37:00',NULL,1,NULL,'cierre_venta','2026-08-28 07:38:11','2026-08-28 07:38:11'),
(7,'casa de la paz','casa de la paz','USD',1000.00,'lutron, cortinas ,cctv, redes',NULL,NULL,'admin','2026-08-28 01:40:00',NULL,1,NULL,'cierre_venta','2026-08-28 07:41:07','2026-08-28 07:41:07'),
(8,'Oficina Central','Oficina Central','USD',1.00,'redes, cctv, llutron, de todo',NULL,NULL,'admin','2026-09-07 13:38:00',NULL,1,NULL,'cierre_venta','2026-09-07 13:39:34','2026-09-07 13:39:34'),
(9,'CONTROL DE ACCESO 3 PISO','PUNTO NOVO','USD',6704.63,'SISTEMA CONTROL DE ACCESO Y SISTEMA DE VIDEO VIGILANCIA',NULL,NULL,'admin','2026-09-08 12:54:00',NULL,1,NULL,'cotizacion','2026-09-08 12:55:51','2026-09-08 12:55:51');
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-15 18:32:26
