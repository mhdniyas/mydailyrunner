-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: localhost    Database: equipnow
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `booking_combos`
--

DROP TABLE IF EXISTS `booking_combos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_combos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `combo_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `daily_rate` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_combos_booking_id_foreign` (`booking_id`),
  KEY `booking_combos_combo_id_foreign` (`combo_id`),
  CONSTRAINT `booking_combos_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_combos_combo_id_foreign` FOREIGN KEY (`combo_id`) REFERENCES `combos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_combos`
--

LOCK TABLES `booking_combos` WRITE;
/*!40000 ALTER TABLE `booking_combos` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_combos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_equipment`
--

DROP TABLE IF EXISTS `booking_equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_equipment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `equipment_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `daily_rate` decimal(10,2) NOT NULL,
  `is_free` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_equipment_booking_id_foreign` (`booking_id`),
  KEY `booking_equipment_equipment_id_foreign` (`equipment_id`),
  CONSTRAINT `booking_equipment_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_equipment_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_equipment`
--

LOCK TABLES `booking_equipment` WRITE;
/*!40000 ALTER TABLE `booking_equipment` DISABLE KEYS */;
INSERT INTO `booking_equipment` VALUES (1,1,10,1,200.00,0,'2025-06-04 15:03:53','2025-06-04 15:03:53');
/*!40000 ALTER TABLE `booking_equipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `booking_date` datetime NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `total_rent` decimal(10,2) NOT NULL,
  `deposit_amount` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `setup_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `insurance_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('pending','partial','paid','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','confirmed','active','returned','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `delivery_required` tinyint(1) NOT NULL DEFAULT '0',
  `setup_required` tinyint(1) NOT NULL DEFAULT '0',
  `insurance_required` tinyint(1) NOT NULL DEFAULT '0',
  `delivery_address` text COLLATE utf8mb4_unicode_ci,
  `delivery_date` date DEFAULT NULL,
  `delivery_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_customer_id_foreign` (`customer_id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,3,1,'2025-06-04 15:03:53','2025-06-04 00:00:00','2025-06-05 00:00:00',400.00,0.00,0.00,0.00,0.00,'pending',NULL,'pending',NULL,0,0,0,NULL,NULL,NULL,'2025-06-04 15:03:53','2025-06-04 15:03:53');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('equipnow_cache_admin@example.com|157.51.206.142','i:1;',1749461961),('equipnow_cache_admin@example.com|157.51.206.142:timer','i:1749461961;',1749461961),('equipnow_cache_info@fenlainteriors.com|103.153.104.77','i:1;',1749104562),('equipnow_cache_info@fenlainteriors.com|103.153.104.77:timer','i:1749104562;',1749104562),('equipnow_cache_spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:37:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"equipment.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:16:\"equipment.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:14:\"equipment.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:16:\"equipment.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:21:\"equipment.maintenance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:13:\"category.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:15:\"category.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:13:\"category.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:15:\"category.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"subcategory.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:18:\"subcategory.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"subcategory.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:18:\"subcategory.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:3:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"booking.view\";s:1:\"c\";s:3:\"web\";}i:14;a:3:{s:1:\"a\";i:15;s:1:\"b\";s:14:\"booking.create\";s:1:\"c\";s:3:\"web\";}i:15;a:3:{s:1:\"a\";i:16;s:1:\"b\";s:12:\"booking.edit\";s:1:\"c\";s:3:\"web\";}i:16;a:3:{s:1:\"a\";i:17;s:1:\"b\";s:14:\"booking.cancel\";s:1:\"c\";s:3:\"web\";}i:17;a:3:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"booking.return\";s:1:\"c\";s:3:\"web\";}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:13:\"customer.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:15:\"customer.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:13:\"customer.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:10:\"combo.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:12:\"combo.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:10:\"combo.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:12:\"combo.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:11:\"report.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:13:\"report.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:12:\"report.daily\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:9:\"user.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:11:\"user.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:9:\"user.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:11:\"user.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:13:\"bookings.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:15:\"bookings.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:13:\"bookings.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:15:\"bookings.cancel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:15:\"bookings.return\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:3:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:8:\"Salesman\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:5:\"Staff\";s:1:\"c\";s:3:\"web\";}}}',1749459314);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Audio Equipment','Professional audio equipment for events and productions','fa-solid fa-microphone','2025-06-04 14:43:01','2025-06-04 14:43:01'),(2,'Lighting','Professional lighting equipment for events and productions','fa-solid fa-lightbulb','2025-06-04 14:43:01','2025-06-04 14:43:01'),(3,'Video Equipment','Professional video equipment for recording and streaming','fa-solid fa-video','2025-06-04 14:43:01','2025-06-04 14:43:01'),(4,'Power & Electrical','Power generators and electrical equipment','fa-solid fa-plug','2025-06-04 14:43:01','2025-06-04 14:43:01'),(5,'Stage Equipment','Stages, platforms, and structural equipment','fa-solid fa-theater-masks','2025-06-04 14:43:01','2025-06-04 14:43:01'),(6,'DJ Equipment','Complete DJ setups and accessories','fa-solid fa-record-vinyl','2025-06-04 14:43:01','2025-06-04 14:43:01');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `combo_items`
--

DROP TABLE IF EXISTS `combo_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `combo_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `combo_id` bigint unsigned NOT NULL,
  `equipment_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `is_free` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `combo_items_combo_id_foreign` (`combo_id`),
  KEY `combo_items_equipment_id_foreign` (`equipment_id`),
  CONSTRAINT `combo_items_combo_id_foreign` FOREIGN KEY (`combo_id`) REFERENCES `combos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `combo_items_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `combo_items`
--

LOCK TABLES `combo_items` WRITE;
/*!40000 ALTER TABLE `combo_items` DISABLE KEYS */;
INSERT INTO `combo_items` VALUES (1,1,3,2,0,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(2,1,4,1,0,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(3,1,6,1,0,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(4,1,1,2,1,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(5,2,7,8,0,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(6,2,8,2,0,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(7,3,11,1,0,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(8,3,3,2,0,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(9,3,4,2,0,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(10,3,7,4,1,'2025-06-04 14:43:01','2025-06-04 14:43:01');
/*!40000 ALTER TABLE `combo_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `combos`
--

DROP TABLE IF EXISTS `combos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `combos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint unsigned NOT NULL,
  `combo_price` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `combos_category_id_foreign` (`category_id`),
  CONSTRAINT `combos_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `combos`
--

LOCK TABLES `combos` WRITE;
/*!40000 ALTER TABLE `combos` DISABLE KEYS */;
INSERT INTO `combos` VALUES (1,'Basic PA System','Complete PA system for small events (up to 100 people)',1,225.00,'active','2025-06-04 14:43:01','2025-06-04 14:43:01'),(2,'Small Event Lighting Package','Basic lighting setup for small venues',2,150.00,'active','2025-06-04 14:43:01','2025-06-04 14:43:01'),(3,'DJ Performance Package','Complete DJ setup with sound and lighting',6,350.00,'active','2025-06-04 14:43:01','2025-06-04 14:43:01');
/*!40000 ALTER TABLE `combos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_document_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'John Smith','john.smith@example.com','555-123-4567','123 Main Street','New York','NY','10001','Driver\'s License','DL98765432',NULL,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(2,'Sarah Johnson','sarah.johnson@example.com','555-234-5678','456 Park Avenue','Los Angeles','CA','90001','Passport','P123456789',NULL,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(3,'Michael Williams','michael.williams@example.com','555-345-6789','789 Broadway','Chicago','IL','60601','Driver\'s License','DL12345678',NULL,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(4,'Emily Davis','emily.davis@example.com','555-456-7890','321 Maple Road','Miami','FL','33101','State ID','ID87654321',NULL,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(5,'Sound & Light Productions','bookings@soundlight.com','555-789-0123','987 Industry Way','Nashville','TN','37201','Business License','BL2023-45678',NULL,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(6,'Event Masters Inc.','info@eventmasters.com','555-890-1234','654 Conference Blvd','Las Vegas','NV','89101','Business License','BL2023-98765',NULL,'2025-06-04 14:43:01','2025-06-04 14:43:01');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint unsigned DEFAULT NULL,
  `subcategory_id` bigint unsigned DEFAULT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `deposit_amount` decimal(10,2) NOT NULL,
  `status` enum('available','in_use','maintenance','damaged') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `quantity` int NOT NULL DEFAULT '1',
  `quantity_available` int NOT NULL DEFAULT '1',
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `equipment_category_id_foreign` (`category_id`),
  KEY `equipment_subcategory_id_foreign` (`subcategory_id`),
  CONSTRAINT `equipment_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `equipment_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment`
--

LOCK TABLES `equipment` WRITE;
/*!40000 ALTER TABLE `equipment` DISABLE KEYS */;
INSERT INTO `equipment` VALUES (1,'Shure SM58','Industry standard dynamic vocal microphone',1,1,15.00,100.00,'available',10,10,NULL,'Excellent condition, regularly maintained','2025-06-04 14:43:01','2025-06-04 14:43:01'),(2,'Sennheiser EW 100 G4','Wireless handheld microphone system',1,1,35.00,250.00,'available',5,5,NULL,'New units, all accessories included','2025-06-04 14:43:01','2025-06-04 14:43:01'),(3,'JBL PRX815','15\" two-way powered loudspeaker',1,2,75.00,500.00,'available',8,8,NULL,'Good condition, minor cosmetic wear','2025-06-04 14:43:01','2025-06-04 14:43:01'),(4,'QSC KW181','18\" powered subwoofer',1,2,85.00,600.00,'available',4,4,NULL,'Excellent condition, regularly tested','2025-06-04 14:43:01','2025-06-04 14:43:01'),(5,'Allen & Heath SQ-6','Digital mixer with 24 faders and 48 inputs',1,3,120.00,1000.00,'available',2,2,NULL,'Excellent condition, latest firmware','2025-06-04 14:43:01','2025-06-04 14:43:01'),(6,'Yamaha MG12XU','12-channel analog mixer with effects',1,3,40.00,300.00,'available',3,3,NULL,'Good condition, all channels working properly','2025-06-04 14:43:01','2025-06-04 14:43:01'),(7,'ADJ Mega Par Profile','RGB+UV LED par can',2,5,20.00,150.00,'maintenance',16,14,NULL,'Good condition, regularly maintained','2025-06-04 14:43:01','2025-06-05 06:03:56'),(8,'Chauvet Intimidator Spot 375Z','150W LED moving head light',2,6,65.00,450.00,'available',6,6,NULL,'Excellent condition, all features working','2025-06-04 14:43:01','2025-06-04 14:43:01'),(9,'Sony PXW-FS5','Professional 4K camcorder',3,9,150.00,2000.00,'available',2,2,NULL,'Excellent condition, includes basic accessories','2025-06-04 14:43:01','2025-06-04 14:43:01'),(10,'Epson Pro G7500U','6500-lumen WUXGA projector',3,10,200.00,1500.00,'available',3,3,NULL,'Good condition, lamps have >80% life remaining','2025-06-04 14:43:01','2025-06-04 14:43:01'),(11,'Pioneer DDJ-1000','4-channel DJ controller for Rekordbox',6,19,100.00,800.00,'maintenance',3,1,NULL,'Excellent condition, includes software license','2025-06-04 14:43:01','2025-06-05 06:03:56'),(12,'Honda EU7000is','7000W super quiet inverter generator',4,13,175.00,1000.00,'available',2,2,NULL,'Excellent condition, regularly serviced','2025-06-04 14:43:01','2025-06-04 14:43:01'),(13,'Staging Deck 4\'x8\'','Professional staging deck with adjustable legs',5,16,50.00,300.00,'available',12,12,NULL,'Good condition, some minor scratches','2025-06-04 14:43:01','2025-06-04 14:43:01');
/*!40000 ALTER TABLE `equipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_records`
--

DROP TABLE IF EXISTS `maintenance_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `equipment_id` bigint unsigned NOT NULL,
  `reported_by` bigint unsigned NOT NULL,
  `assigned_to` bigint unsigned DEFAULT NULL,
  `issue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `resolution_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_records_equipment_id_foreign` (`equipment_id`),
  KEY `maintenance_records_reported_by_foreign` (`reported_by`),
  KEY `maintenance_records_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `maintenance_records_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `maintenance_records_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE,
  CONSTRAINT `maintenance_records_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_records`
--

LOCK TABLES `maintenance_records` WRITE;
/*!40000 ALTER TABLE `maintenance_records` DISABLE KEYS */;
INSERT INTO `maintenance_records` VALUES (1,3,1,3,'Speaker emitting buzzing noise when powered on','completed','2025-05-05 14:43:01','2025-05-07 14:43:01',75.50,'Replaced faulty power supply unit and tested for 48 hours. Issue resolved.','2025-06-04 14:43:01','2025-06-04 14:43:01'),(2,1,1,3,'Microphone not picking up sound properly','completed','2025-05-20 14:43:01','2025-05-21 14:43:01',25.00,'Cleaned internal components and replaced damaged capsule screen. Working normally now.','2025-06-04 14:43:01','2025-06-04 14:43:01'),(3,11,1,3,'Right channel output not working','in_progress','2025-06-02 14:43:01',NULL,0.00,NULL,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(4,7,1,3,'One LED not lighting up properly','pending','2025-06-03 14:43:01',NULL,0.00,NULL,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(5,3,1,3,'Speaker emitting buzzing noise when powered on','completed','2025-05-06 06:03:56','2025-05-08 06:03:56',75.50,'Replaced faulty power supply unit and tested for 48 hours. Issue resolved.','2025-06-05 06:03:56','2025-06-05 06:03:56'),(6,1,1,3,'Microphone not picking up sound properly','completed','2025-05-21 06:03:56','2025-05-22 06:03:56',25.00,'Cleaned internal components and replaced damaged capsule screen. Working normally now.','2025-06-05 06:03:56','2025-06-05 06:03:56'),(7,11,1,3,'Right channel output not working','in_progress','2025-06-03 06:03:56',NULL,0.00,NULL,'2025-06-05 06:03:56','2025-06-05 06:03:56'),(8,7,1,3,'One LED not lighting up properly','pending','2025-06-04 06:03:56',NULL,0.00,NULL,'2025-06-05 06:03:56','2025-06-05 06:03:56');
/*!40000 ALTER TABLE `maintenance_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_06_04_044211_create_permission_tables',1),(5,'2025_06_04_045315_create_equipment_table',1),(6,'2025_06_04_045448_create_categories_table',1),(7,'2025_06_04_045506_create_subcategories_table',1),(8,'2025_06_04_045637_create_combos_table',1),(9,'2025_06_04_045646_create_combo_items_table',1),(10,'2025_06_04_045756_create_customers_table',1),(11,'2025_06_04_045757_create_bookings_table',1),(12,'2025_06_04_050141_create_booking_equipment_table',1),(13,'2025_06_04_050218_create_booking_combos_table',1),(14,'2025_06_04_050301_create_payments_table',1),(15,'2025_06_04_050313_create_return_records_table',1),(16,'2025_06_04_050322_create_maintenance_records_table',1),(17,'2025_06_04_050646_create_returned_items_table',1),(18,'2025_06_04_050821_add_foreign_keys_to_equipment_table',1),(19,'2025_06_04_063043_update_combos_table_add_missing_columns',1),(20,'2025_06_05_065257_add_additional_fields_to_bookings_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(3,'App\\Models\\User',3);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` datetime NOT NULL,
  `type` enum('rent','deposit','refund') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'rent',
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_booking_id_foreign` (`booking_id`),
  KEY `payments_user_id_foreign` (`user_id`),
  CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'equipment.view','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(2,'equipment.create','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(3,'equipment.edit','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(4,'equipment.delete','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(5,'equipment.maintenance','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(6,'category.view','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(7,'category.create','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(8,'category.edit','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(9,'category.delete','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(10,'subcategory.view','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(11,'subcategory.create','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(12,'subcategory.edit','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(13,'subcategory.delete','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(14,'booking.view','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(15,'booking.create','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(16,'booking.edit','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(17,'booking.cancel','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(18,'booking.return','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(19,'customer.view','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(20,'customer.create','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(21,'customer.edit','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(22,'combo.view','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(23,'combo.create','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(24,'combo.edit','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(25,'combo.delete','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(26,'report.view','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(27,'report.export','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(28,'report.daily','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(29,'user.view','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(30,'user.create','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(31,'user.edit','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(32,'user.delete','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(33,'bookings.view','web','2025-06-05 07:04:57','2025-06-05 07:04:57'),(34,'bookings.create','web','2025-06-05 07:04:57','2025-06-05 07:04:57'),(35,'bookings.edit','web','2025-06-05 07:04:57','2025-06-05 07:04:57'),(36,'bookings.cancel','web','2025-06-05 07:04:57','2025-06-05 07:04:57'),(37,'bookings.return','web','2025-06-05 07:04:57','2025-06-05 07:04:57');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_records`
--

DROP TABLE IF EXISTS `return_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `return_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `return_date` datetime NOT NULL,
  `condition_notes` text COLLATE utf8mb4_unicode_ci,
  `refund_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `refund_status` enum('none','partial','full') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `damages_description` text COLLATE utf8mb4_unicode_ci,
  `damage_charges` decimal(10,2) NOT NULL DEFAULT '0.00',
  `late_return_charges` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `return_records_booking_id_foreign` (`booking_id`),
  KEY `return_records_user_id_foreign` (`user_id`),
  CONSTRAINT `return_records_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `return_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_records`
--

LOCK TABLES `return_records` WRITE;
/*!40000 ALTER TABLE `return_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `return_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `returned_items`
--

DROP TABLE IF EXISTS `returned_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `returned_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `return_record_id` bigint unsigned NOT NULL,
  `equipment_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `condition` enum('good','damaged','needs_repair') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'good',
  `damage_description` text COLLATE utf8mb4_unicode_ci,
  `damage_charges` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `returned_items_return_record_id_foreign` (`return_record_id`),
  KEY `returned_items_equipment_id_foreign` (`equipment_id`),
  CONSTRAINT `returned_items_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE,
  CONSTRAINT `returned_items_return_record_id_foreign` FOREIGN KEY (`return_record_id`) REFERENCES `return_records` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `returned_items`
--

LOCK TABLES `returned_items` WRITE;
/*!40000 ALTER TABLE `returned_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `returned_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(1,2),(6,2),(10,2),(19,2),(20,2),(21,2),(22,2),(26,2),(28,2),(33,2),(34,2),(35,2),(37,2),(1,3),(5,3),(33,3);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(2,'Salesman','web','2025-06-04 14:43:00','2025-06-04 14:43:00'),(3,'Staff','web','2025-06-04 14:43:00','2025-06-04 14:43:00');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
INSERT INTO `sessions` VALUES ('19A5kvFlM5b5A9oTXmMroboJANwjQGKBooUnSuaY',NULL,'184.154.76.22','Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/6.0)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUGZnSTdnRVM5YUhqWTdkbnpYSlM1cmxmeDB6TUdkbG85d2N5TkFHSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749529368),('1et1p6wrL91nNWQPgWX7FJwnlxhrjdfV7dgEm6ck',NULL,'49.51.233.95','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieUthamppY3dsQTRXUzZBNHdQMFdXVDZvWVpGeEtSSmQwbGpDbzIzTCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cHM6Ly93d3cuemVyb2hpdmUuc3RvcmUvY2F0ZWdvcmllcy8xIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL2NhdGVnb3JpZXMvMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749526627),('3dGqlX7dxKQ2vnioG6CEma3HmUGGILcc36v1ODbp',NULL,'66.249.69.70','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTozOntzOjY6Il90b2tlbiI7czo0MDoieTJpY2tCZ3c0c3VMN0N3ajdIMmxsVUx5VnJZb1RtOFk2ZnE3OEJKbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749532545),('3qxHtzMcEYvk8hIevat3FBN6WaXYinW3BAZEO3wL',NULL,'43.153.67.21','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMDNYS2tseU5nYmZuNk03T3M4eXJMZHdrWGlqS2tvcVk5cjE5SlNGSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL3Nob3AvMiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749526067),('4CFkVzTsfFFDSr7hFCP04VC4ABq0QA6rmcjY9epj',NULL,'170.106.161.78','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzBoTWpGT0tvR2RYc3g3ZGU4Smd6ckVQUTU2bHA5MHBhek54RHFERSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL2NvbnRhY3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749523067),('8f2qvFty42dHyWLvDBAVnkyrUh2qWTtzPhUuY382',NULL,'43.157.156.190','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMkUwWmdDSmdGbE1EVGc4ZDhGclh2MjBrY2xhOExLeHFWV0Znb1VuNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL3Nob3AvMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749527150),('8JjkJ7763VI929ohiyIuSRWi3qrMx74ZaF0vcUn9',NULL,'20.171.207.137','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.2; +https://openai.com/gptbot)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1hjeDU4TFNUWEVEbFhOZ0JGNmIzUkxERmdzYzY3OFV2dXVIVXpUZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749500849),('a7j2S9mT4U9r5sUKKJPwWVX2Ro6pPcvdFRDKAvHq',NULL,'43.157.179.227','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWGdMZU9hUEZ2eGhMVnk4R3ZCMG52SGoyZHVXU1J1QnlNZEVjQ1JaZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749519949),('cSiN6Ol9r74L5vfNzlgu3Idc1AAScsIFvpmAMYDD',NULL,'43.153.135.208','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTXR0OTdQcUR6ZlBoQkk5T1lRZFBTb1hCelY4bzlUN1U2VGsxMEpGbCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749520532),('DHUMUWiXvFiP3s0c1mQdQ5MafdGksC0xzhicm2CT',NULL,'43.128.156.124','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNjRkYkNEN0RVSjZmb1dtU2xZbWM1bzI3eVVEcXMzV3FPYkFuSjNsTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749521787),('ehbdOzDPJVoPFXmUkap0i5C4p3KOjHYsVMht9FkQ',NULL,'132.232.203.74','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNEVPOTdhMWlqclpXU0xsZlFVekFOYm1XNFB2c0taV2tTMW1MV05YSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749522543),('FbxuN9Dc3IymklocxsoJtArpEQMl13dGtSX0GQL6',NULL,'66.249.69.71','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU1hyMXh0dFpxZkFFQ3p5aHBoRUZnUUdWZ0JsSnJCNmMzMFZvb3ZBUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL3Nob3AvNSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749521742),('Fnz9onpKxSgkavKXOHFeoLPTiF37hGn3PouzZjGY',NULL,'43.157.180.116','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRFhJZjNxVFVqR2Rsb2taWXZycG5GcjNucGZzckdaejFJaEhsMHNucSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL2ZvcmdvdC1wYXNzd29yZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749523524),('kBBawX3wIi0PbmY8B0KZsTT63ekWs4pjjBm5fILB',NULL,'1.192.192.8','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS1Nwc2hYTHZEM25pankwSkZjM2VWaTJlVWpjWUdndVVjRmxnOWZHSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749509661),('knMxsQMizeVqudi1RotTMEo2rMVv5M6BSIwEohNV',NULL,'185.247.137.207','Mozilla/5.0 (compatible; InternetMeasurement/1.0; +https://internet-measurement.com/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU1A1MDQ4MzdoVDB5MlJxcm1iMFQ3bERaMGdqUXVLa2xzbzN6V21jQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749496482),('llJHa6Ozdi8n7BclEsREJKvyRTpoeBidILYFceEx',NULL,'185.39.19.43','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 CCleaner/130.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM1FDb0NMdk5yVHQ0OFJBOTNscnc1aUxOUEtXcHpTbEhxYzFIYzZyeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUvY29udGFjdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749503584),('nEfzolHvAmzPhqViETKnDcUWRUa5McOwHvxAxxP3',NULL,'182.44.12.37','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWXNTakt4YmNjeFRTWFR4Mlp2TW81ZlBycUJYc3d2QWdMRzc1RnNzMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749500981),('nMF8Pd8VeNNAEGCMjwWo2FGrP8ca45FlI3dWd0rb',NULL,'43.157.170.13','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRENFWlE1VDVFVXhBRGdMc1lITUxXMmkyR3RZNm1lVlo3T2dzRHZnSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL3Nob3AvMyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749524135),('nptn5QNwBnbUPzeg5lrHpdhTiXdq8hGzPg7aRIGK',NULL,'43.157.179.227','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWkt2eUVWRENQb0VHdkJZcU5JdjNCYXpPOXJiRmEyOFNXME9IWjAzUiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cHM6Ly93d3cuemVyb2hpdmUuc3RvcmUvY2F0ZWdvcmllcyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwczovL3d3dy56ZXJvaGl2ZS5zdG9yZS9jYXRlZ29yaWVzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749519947),('OgDiGJnwbeDwMzgO0qqSKZXTZHJF8ugmCbaXXwCQ',NULL,'87.236.176.146','Mozilla/5.0 (compatible; InternetMeasurement/1.0; +https://internet-measurement.com/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlRyQ2tSbVlKS1Z5N0JsdWFXblNQOXpzT2hsS2RScWQ5YXRrZHd1eSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749504922),('oqcYd47vKXVuVvXhhYmrC4g4NweleKYUohGYTh0z',NULL,'185.177.72.236','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV0hrTURReTZzTXFWWmZqOE5Ta3ZLTDJjYmxnRjhIbnVGQTVNekwzQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUvP3BocGluZm89MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749497893),('PcdMb4o3RZEr46YZD69daGkgk7ZIMfC3ZmFzcBFf',NULL,'20.171.207.147','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.2; +https://openai.com/gptbot)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRTYyYUUzdXpDQ1RyUEFPYXUxVldTRGEyNmViNERWU3lHOWF3ZzhUeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749500844),('pCikT66TwfnLVwMM4zBQ2aLQbgOIaB7nFBsO3Gwj',NULL,'185.177.72.236','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUjNrMGtEVlk2OHFtWGxDOUhOVXZKVnNIbkhBYlZ1RjR6aW1FSzdNNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749497865),('pJMuMKIHQOXrVszVe3QeSPE9WjxPp1Pvy6cpmZCr',NULL,'43.143.248.236','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjZDa05yc0I4R0I0N0ZRZU1RTnUwZ0xGdGxLQWRrRWNUV2hUdm05YSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749533465),('pNd0lXqRQKkBNEWZWPbVf9A8tev0Q20szBJIUZYF',NULL,'184.154.76.22','Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/6.0)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOWxsV1VsTk5qSjZUM085MTVDTjJJdHlyMDBBUXpjNFJOWks2OGx3biI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUvc2hvcC85Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749529386),('PXDV1o5VONfySPUBQTB4Dru7W9gNYABySV1qiWt1',NULL,'43.130.67.6','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNEh0T21uc29iTG9ZRnE3VGJ5dGpJb2hsYlVYdEV2cHpmWEtrbHpleiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749519194),('RCo5H2bYIhYZWh3aspqVXTeWbG42tAqO8SDlneVi',NULL,'43.173.1.69','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU1ZmcllXeW9ScGthRllzazVlWng3OVJKNnA0TklPRmo0WnBmcFI0UyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL2Fib3V0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749521207),('rEw3G3lZSgvCAPGTI1Pv4pBbYflMOqJl1xRBqnat',NULL,'43.157.147.3','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWhBMWxlRkV2QTNNMzJJSWs3eDdtNHUwaXZGTXdzRnhDRzF1bFJFeiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL3Nob3AvMTAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749525351),('SnpAcPgbNWcwpZjpkfS41XJF2ju23eP4yRww9vRg',NULL,'43.157.168.43','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoicVRZdmw4MjlCWjdSdDNJOTZIR2ZMRk5Kd2VBSDBIQWRyQTlHMFg2ayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL3Nob3AvNCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749527780),('SqPme8FsWQrF1o9tBnXXqYsMVU9MRhcyL0ZBHrOI',NULL,'49.51.233.95','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN0lYckNPZnBtbmI0OTBxcmcwQWplMjA1WDdFUTk4NmxTVFoxZDBsYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749526632),('UIx1vGPVmFoGXOaDXNp8FVzF9yEoHMSZ6LszaMcI',NULL,'124.156.226.179','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWFlmWHpBaUlQbm5xSEtIUzNVS0NSNjVSNFV0S29KNmxCd0RSUm5hWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL3Nob3AiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749522432),('uuvKSVxaiMv6Zp7ti9z12ZLPNSnRefyWJbiqA2qE',NULL,'205.210.31.48','Expanse, a Palo Alto Networks company, searches across the global IPv4 space multiple times per day to identify customers&#39; presences on the Internet. If you would like to be excluded from our scans, please send IP addresses/domains to: scaninfo@paloaltonetworks.com','YTozOntzOjY6Il90b2tlbiI7czo0MDoia3Y4WThwT2U4R0RtMjNoRHJhSlRncTJiZktLMnE2d2Q4cVJRVDhwQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749529578),('UValXzTBKwbH2fmhQCKSpPYHYXp0yRMopYzkTIbc',NULL,'43.166.255.122','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRENvaVJpRmdGeGFVdUV6Yzhqa0swM2YxWDNqejJWcmdIU1JJZkE2VyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749522036),('X8G9OTuYrNJEcE4pk1TED5O4fiR4Tr7t8zGDgqcj',NULL,'43.157.168.43','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoibDdwYnhYRUw0VGVIQzNkNjR4OUlSUFltSElZRUdtRmlMbkk1MVl0aCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vd3d3Lnplcm9oaXZlLnN0b3JlL3Nob3AvNSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749524745),('Y0Famt5KrEhC65g6EmThZbWx17Dv1hvgMM3OWfKa',NULL,'184.154.76.22','SiteLockSpider [en] (WinNT; I ;Nav)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieFZ1eUhHSzVXWG01dmFQWGM2c2t1MjdxZ3lVSHFDVWw3akY3RWFPUSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMzoiaHR0cHM6Ly96ZXJvaGl2ZS5zdG9yZS9jYXRlZ29yaWVzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUvcmVnaXN0ZXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749529382),('Ynp0oHDns8kL0UxmKifpfgG1IUZNv5ULACFyMdqP',NULL,'157.51.213.236','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYjJwRjE5dTlLUzRTaGF6OVhYdklpeFppTTNmSEJCMENhemRjdG1PWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vemVyb2hpdmUuc3RvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749534888);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subcategories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subcategories_category_id_foreign` (`category_id`),
  CONSTRAINT `subcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategories`
--

LOCK TABLES `subcategories` WRITE;
/*!40000 ALTER TABLE `subcategories` DISABLE KEYS */;
INSERT INTO `subcategories` VALUES (1,'Microphones','Wired and wireless microphones for various applications',1,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(2,'Speakers','PA speakers, monitors, and subwoofers',1,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(3,'Mixers','Analog and digital audio mixers',1,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(4,'Audio Interfaces','Digital audio interfaces and converters',1,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(5,'LED Lights','Energy-efficient LED lighting solutions',2,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(6,'Moving Heads','Dynamic moving head lighting fixtures',2,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(7,'Par Cans','Traditional and LED par can lights',2,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(8,'Light Controllers','DMX controllers and lighting consoles',2,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(9,'Cameras','Professional video and DSLR cameras',3,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(10,'Projectors','Video projectors of various brightness levels',3,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(11,'Screens','Projection screens and displays',3,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(12,'Video Switchers','Video mixers and switching equipment',3,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(13,'Generators','Portable power generators',4,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(14,'Distribution Boxes','Power distribution systems',4,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(15,'Cables & Adapters','Power cables and electrical adapters',4,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(16,'Stage Platforms','Modular stage systems and risers',5,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(17,'Truss Systems','Aluminum truss for lighting and equipment mounting',5,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(18,'Backdrop & Drapes','Stage backdrops and curtain systems',5,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(19,'DJ Controllers','Digital DJ controllers and interfaces',6,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(20,'Turntables','Vinyl turntables and direct-drive systems',6,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(21,'CDJs','Professional CD/media players for DJs',6,'2025-06-04 14:43:01','2025-06-04 14:43:01'),(22,'DJ Accessories','Headphones, cables, and other DJ accessories',6,'2025-06-04 14:43:01','2025-06-04 14:43:01');
/*!40000 ALTER TABLE `subcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin User','admin@equipnow.com','2025-06-04 14:43:00','$2y$12$FmVfwEmo7q11yU5YSfW4zOz1rrD0UMyO0P.yFqSkN00RFf5msDYsi','ddw5MtAQtv','2025-06-04 14:43:01','2025-06-04 14:43:01'),(2,'Salesman User','salesman@equipnow.com','2025-06-04 14:43:01','$2y$12$jN41Ey0Nd6A1lLLCKwVjZeuDZvRwb7RKENq5U9xltpqwYBAA5v71C','qnbJZvaqOF','2025-06-04 14:43:01','2025-06-04 14:43:01'),(3,'Staff User','staff@equipnow.com','2025-06-04 14:43:01','$2y$12$d7AHiY/eDdxkbkvtEnMRq.4ORajSOpG9iPLcx8xrNoyTlznO9mBQ2','m01BJzhEGK','2025-06-04 14:43:01','2025-06-04 14:43:01'),(4,'TfWRCtJeC','mieshaanniston48974@yahoo.com',NULL,'$2y$12$Ls9bU7ksZxRiblUb7p7wH.TtSjN9DNdiv9Tst63t8ci1aNna3RjF2',NULL,'2025-06-05 20:43:43','2025-06-05 20:43:43'),(5,'mIcJInTYXj','erikacole641775@yahoo.com',NULL,'$2y$12$Ad8jYAGMu47trIqp2elTZ.y0wp0KVyCNi2/eg/u3nt6h47SlPKl96',NULL,'2025-06-06 09:45:01','2025-06-06 09:45:01'),(6,'TtUcEMzvqtZSA','vinnicantuh@gmail.com',NULL,'$2y$12$KBWDMisjck9lmTKoNNDORuCBe7FvxhiBj2lxtgy5Isd7MaBRygYAy',NULL,'2025-06-07 01:30:52','2025-06-07 01:30:52'),(7,'HYShUTCrjIuAn','doritawillisiy@gmail.com',NULL,'$2y$12$yUPSyZrZPFN7M13AYRAV7OykAIJexPiHf0ImXl76eUVOXTesgG7OG',NULL,'2025-06-07 02:53:32','2025-06-07 02:53:32'),(8,'MlfzawEzu','girardmichael406945@yahoo.com',NULL,'$2y$12$etguRqaDFK168aMutcY6Mu3qTBPgfPFIvZsRCqXCpXmmocNZTKjpC',NULL,'2025-06-07 05:44:48','2025-06-07 05:44:48'),(9,'isWDSHpqci','lukaguilar2@gmail.com',NULL,'$2y$12$OZ3jiUFLXyzmi2jRYfQW8uvi3.F19vreYQP0oQq03OGH03rsN6h2C',NULL,'2025-06-07 22:27:40','2025-06-07 22:27:40'),(10,'yARZwNty','viktoriyath1990@gmail.com',NULL,'$2y$12$gmSmHKZwakFqW7Ix/hk66.WNVAFxwBXAWxkRY39uIaiDoJF4ZsK4C',NULL,'2025-06-07 23:11:02','2025-06-07 23:11:02'),(11,'zKvltqcouZGrSY','sheridanarmstrongh@gmail.com',NULL,'$2y$12$EkAgozbEPOJQRaKh/B5fn.EDkD6FgLwz.T418A7ZOGspPsg.QdVuu',NULL,'2025-06-09 01:57:30','2025-06-09 01:57:30');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'equipnow'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-10  7:10:20
