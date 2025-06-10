-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: localhost    Database: kapcwayanad_test
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
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `patient_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `bookings_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_type_unique` (`name`,`type`),
  KEY `categories_created_by_foreign` (`created_by`),
  CONSTRAINT `categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Membership Fee','income','Monthly membership fees collected from organization members',1,NULL,'2025-06-05 18:44:17','2025-06-05 18:44:17');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clinics`
--

DROP TABLE IF EXISTS `clinics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clinics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_id` bigint unsigned NOT NULL,
  `taluk` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_numbers` json DEFAULT NULL,
  `whatsapp_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clinics_member_id_foreign` (`member_id`),
  KEY `clinics_location_id_foreign` (`location_id`),
  CONSTRAINT `clinics_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `clinics_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinics`
--

LOCK TABLES `clinics` WRITE;
/*!40000 ALTER TABLE `clinics` DISABLE KEYS */;
INSERT INTO `clinics` VALUES (1,'H Clini',NULL,1,'Mananthavady','Anjukunnu','[\"08301867613\"]',NULL,NULL,'Kavuginthottathil House,Kavumannam P O\r\nKalpetta Via',NULL,'active','2025-06-06 07:19:54','2025-06-06 07:20:11',7);
/*!40000 ALTER TABLE `clinics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `read_at` timestamp NULL DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_created_by_foreign` (`created_by`),
  CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_category_id_foreign` (`category_id`),
  KEY `expenses_created_by_foreign` (`created_by`),
  CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `financial_reports`
--

DROP TABLE IF EXISTS `financial_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financial_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `period_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_income` decimal(12,2) NOT NULL,
  `total_expenses` decimal(12,2) NOT NULL,
  `balance` decimal(12,2) NOT NULL,
  `income_by_category` json DEFAULT NULL,
  `expenses_by_category` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `financial_reports_start_date_end_date_period_type_unique` (`start_date`,`end_date`,`period_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financial_reports`
--

LOCK TABLES `financial_reports` WRITE;
/*!40000 ALTER TABLE `financial_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `financial_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incomes`
--

DROP TABLE IF EXISTS `incomes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `incomes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `member_id` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `collected_by` bigint unsigned DEFAULT NULL,
  `is_membership_fee` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `incomes_category_id_foreign` (`category_id`),
  KEY `incomes_member_id_foreign` (`member_id`),
  KEY `incomes_collected_by_foreign` (`collected_by`),
  KEY `incomes_created_by_foreign` (`created_by`),
  CONSTRAINT `incomes_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `incomes_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`),
  CONSTRAINT `incomes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `incomes_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incomes`
--

LOCK TABLES `incomes` WRITE;
/*!40000 ALTER TABLE `incomes` DISABLE KEYS */;
/*!40000 ALTER TABLE `incomes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'India',
  `state` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Kerala',
  `district` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Wayanad',
  `taluk` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_location_combination` (`country`,`state`,`district`,`taluk`,`location`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'India','Kerala','Wayanad','Mananthavady',NULL,1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(2,'India','Kerala','Wayanad','Mananthavady','Mananthavady',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(3,'India','Kerala','Wayanad','Mananthavady','Thirunelli',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(4,'India','Kerala','Wayanad','Mananthavady','Thavinjal',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(5,'India','Kerala','Wayanad','Mananthavady','Thondernadu',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(6,'India','Kerala','Wayanad','Mananthavady','Panamaram',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(7,'India','Kerala','Wayanad','Mananthavady','Anjukunnu',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(8,'India','Kerala','Wayanad','Mananthavady','Porunnannur',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(9,'India','Kerala','Wayanad','Mananthavady','Nallurnadu',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(10,'India','Kerala','Wayanad','Mananthavady','Thrissilery',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(11,'India','Kerala','Wayanad','Mananthavady','Payyambally',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(12,'India','Kerala','Wayanad','Mananthavady','Cherukattoor',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(13,'India','Kerala','Wayanad','Mananthavady','Periya',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(14,'India','Kerala','Wayanad','Mananthavady','Vellamunda',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(15,'India','Kerala','Wayanad','Mananthavady','Edavaka',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(16,'India','Kerala','Wayanad','Mananthavady','Valad',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(17,'India','Kerala','Wayanad','Mananthavady','Kanhirangad',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(18,'India','Kerala','Wayanad','Sulthan Bathery',NULL,1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(19,'India','Kerala','Wayanad','Sulthan Bathery','Sulthan Bathery',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(20,'India','Kerala','Wayanad','Sulthan Bathery','Ambalavayal',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(21,'India','Kerala','Wayanad','Sulthan Bathery','Meenangadi',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(22,'India','Kerala','Wayanad','Sulthan Bathery','Pulpally',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(23,'India','Kerala','Wayanad','Sulthan Bathery','Muthanga',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(24,'India','Kerala','Wayanad','Sulthan Bathery','Nenmeni',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(25,'India','Kerala','Wayanad','Sulthan Bathery','Kidanganad',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(26,'India','Kerala','Wayanad','Sulthan Bathery','Noolpuzha',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(27,'India','Kerala','Wayanad','Sulthan Bathery','Thomattuchal',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(28,'India','Kerala','Wayanad','Sulthan Bathery','Kuppadi',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(29,'India','Kerala','Wayanad','Sulthan Bathery','Cheeral',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(30,'India','Kerala','Wayanad','Sulthan Bathery','Purakkadi',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(31,'India','Kerala','Wayanad','Sulthan Bathery','Poothadi',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(32,'India','Kerala','Wayanad','Sulthan Bathery','Padichira',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(33,'India','Kerala','Wayanad','Sulthan Bathery','Irulam',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(34,'India','Kerala','Wayanad','Sulthan Bathery','Krishnagiri',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(35,'India','Kerala','Wayanad','Sulthan Bathery','Nadavayal',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(36,'India','Kerala','Wayanad','Vythiri',NULL,1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(37,'India','Kerala','Wayanad','Vythiri','Kalpetta',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(38,'India','Kerala','Wayanad','Vythiri','Vythiri',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(39,'India','Kerala','Wayanad','Vythiri','Meppadi',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(40,'India','Kerala','Wayanad','Vythiri','Padinjarathara',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(41,'India','Kerala','Wayanad','Vythiri','Pozhuthana',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(42,'India','Kerala','Wayanad','Vythiri','Muttil',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(43,'India','Kerala','Wayanad','Vythiri','Muppanad',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(44,'India','Kerala','Wayanad','Vythiri','Kunnathidavaka',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(45,'India','Kerala','Wayanad','Vythiri','Achooranam',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(46,'India','Kerala','Wayanad','Vythiri','Thariode',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(47,'India','Kerala','Wayanad','Vythiri','Kottathara',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(48,'India','Kerala','Wayanad','Vythiri','Kuppadithara',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(49,'India','Kerala','Wayanad','Vythiri','Chundel',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(50,'India','Kerala','Wayanad','Vythiri','Vengappally',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(51,'India','Kerala','Wayanad','Vythiri','Kavummannam',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(52,'India','Kerala','Wayanad','Vythiri','Kaniyambetta',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(53,'India','Kerala','Wayanad','Vythiri','Kottappadi',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(54,'India','Kerala','Wayanad','Vythiri','Vellarmala',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(55,'India','Kerala','Wayanad','Vythiri','Thrikkaippetta',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(56,'India','Kerala','Wayanad',NULL,NULL,1,'2025-06-05 18:50:50','2025-06-05 18:50:50');
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_phone` tinyint(1) NOT NULL DEFAULT '0',
  `address` text COLLATE utf8mb4_unicode_ci,
  `working_place` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_institution` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interested_in_private_case` tinyint(1) NOT NULL DEFAULT '0',
  `taluk` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `membership_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `position` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `kapc_registration_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interested_in_kapc` tinyint(1) NOT NULL DEFAULT '0',
  `is_leadership` tinyint(1) NOT NULL DEFAULT '0',
  `leadership_order` int DEFAULT NULL,
  `joined_at` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_phone_unique` (`phone`),
  UNIQUE KEY `members_email_unique` (`email`),
  KEY `members_location_id_foreign` (`location_id`),
  CONSTRAINT `members_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES (1,'Muhammed Niyas K M',NULL,'niyasnavas737@gmail.com','08301867613',1,'Kavuginthottathil House,Kavumannam P O\r\nKalpetta Via',NULL,NULL,'India',0,'Mananthavady','Edavaka','active','president',NULL,0,0,NULL,'2025-06-05',NULL,'2025-06-05 18:59:06','2025-06-05 19:00:42',15),(3,'Muhammed Niyas',NULL,'mhdniyas37@gmail.com','8301867613',0,'Kavuginthottathil House,Kavumannam P O\r\nKalpetta Via',NULL,NULL,'India',0,'Sulthan Bathery','Kidanganad','active','joint_secretary',NULL,0,0,NULL,'2025-06-05',NULL,'2025-06-05 18:59:36','2025-06-06 07:14:09',25),(4,'Test User',NULL,'test@example.com','1234567890',1,'Test Address',NULL,NULL,NULL,0,NULL,NULL,'active','member',NULL,1,0,NULL,'2025-06-05',NULL,'2025-06-05 19:11:25','2025-06-05 19:11:25',NULL);
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membership_fees`
--

DROP TABLE IF EXISTS `membership_fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membership_fees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint unsigned NOT NULL,
  `year` year NOT NULL,
  `month` tinyint NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `actual_paid_amount` decimal(12,2) DEFAULT NULL,
  `is_annual_payment` tinyint(1) NOT NULL DEFAULT '0',
  `payment_date` date NOT NULL,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `reference_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `collected_by` bigint unsigned NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `membership_fees_member_id_year_month_unique` (`member_id`,`year`,`month`),
  KEY `membership_fees_collected_by_foreign` (`collected_by`),
  KEY `membership_fees_created_by_foreign` (`created_by`),
  CONSTRAINT `membership_fees_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `membership_fees_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `membership_fees_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membership_fees`
--

LOCK TABLES `membership_fees` WRITE;
/*!40000 ALTER TABLE `membership_fees` DISABLE KEYS */;
/*!40000 ALTER TABLE `membership_fees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2023_05_26_000001_create_categories_table',1),(5,'2023_05_26_000002_create_members_table',1),(6,'2023_05_26_000003_create_incomes_table',1),(7,'2023_05_26_000004_create_expenses_table',1),(8,'2023_05_26_000005_create_settings_table',1),(9,'2023_05_26_000006_create_events_table',1),(10,'2023_05_26_000007_create_membership_fees_table',1),(11,'2023_05_26_000008_create_clinics_table',1),(12,'2023_07_10_000001_create_financial_reports_table',1),(13,'2023_07_25_000000_create_bookings_table',1),(14,'2024_01_01_000000_add_fields_to_bookings_table',1),(15,'2025_05_27_000001_add_actual_paid_amount_to_membership_fees_table',1),(16,'2025_05_28_050038_create_visitors_table',1),(17,'2025_05_28_091911_create_contacts_table',1),(18,'2025_05_28_160108_add_status_to_clinics_table',1),(19,'2025_06_01_025313_add_work_and_private_case_to_members_table',1),(20,'2025_06_01_025344_create_private_cases_table',1),(21,'2025_06_01_121600_create_locations_table',1),(22,'2025_06_01_121912_add_location_id_to_members_table',1),(23,'2025_06_01_121930_add_location_id_to_clinics_table',1),(24,'2025_06_01_122542_migrate_existing_locations_to_foreign_keys',1),(25,'2025_06_01_200511_create_work_reports_table',1),(26,'2025_06_02_092249_add_kapc_registration_fields_to_members_table',1),(27,'2025_06_02_100154_create_notifications_table',1),(28,'2025_06_05_154035_add_show_phone_to_members_table',1),(29,'2025_06_06_033258_create_sms_logs_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `private_cases`
--

DROP TABLE IF EXISTS `private_cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `private_cases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `taluks` json DEFAULT NULL,
  `locations` json DEFAULT NULL,
  `all_taluks` tinyint(1) NOT NULL DEFAULT '0',
  `all_locations` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `private_cases_member_id_foreign` (`member_id`),
  CONSTRAINT `private_cases_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `private_cases`
--

LOCK TABLES `private_cases` WRITE;
/*!40000 ALTER TABLE `private_cases` DISABLE KEYS */;
/*!40000 ALTER TABLE `private_cases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `sessions` VALUES ('1o4IqOHZDOamIA6Yuwj4Hg5dO3VXvnycScdGkPCq',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZHRjM2VDM1dvMHJKWG53NTh0dTN1TlJWSjhEc2xsam9aUGFISDBUdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hcGkvbG9jYXRpb25zL3RhbHVrcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749194193),('7zXMOELcPAhvFylhcz5ismqlojTbrzlPNrBaVWaY',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaW1zQlUyWXhZYVpOSW1oakVEa3FEcTRVZ0JlZTlaaXBpUTVMbTdWOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749150750),('cUk75uqRDetXyxml6TqfmzLuj07MlIY0UaDGHkh0',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOEh2eGcxc3BKYk9IV08xRGxlVm9lS1pZWVFxZHphb3RIUWhZZUJFUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTEwOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvY2xpbmljL3JlZ2lzdGVyP2lkPWJlOGIxYjZhLTNmMmItNDFiYy04NzIxLWQyNTIwNjE4MTk1YiZ2c2NvZGVCcm93c2VyUmVxSWQ9MTc0OTE5NDE5MTkyOSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749194192),('eaK1OUyLrtrtpq7OH4sHC5pY4cEhP6zbjoh2F726',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidzlBYlJYWnVVR002NTJiZ2lGN1VGQ3ZiaFZHc2NKanlON3dsd0V0WiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hcGkvbG9jYXRpb25zL3RhbHVrcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749194193),('h8iu3DbmTMElhlNVhwjIANJxIEr2oqxDGce1QRDd',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiYVVhTTJBdXpkWFlnWHdtOHZVenY5M0JwSEhSRXdxc0pGQ0phUXp3aiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vbWFpbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRkQjdzZy92elB4RFI5TDVWUGdFbzlPNFhYbGRwRk52N2VoZWZjc2xlT3RnWTlETzZQNktDNiI7czo4OiJmaWxhbWVudCI7YTowOnt9fQ==',1749150861),('vvolEfzhaJg2mUHnYLTlWBvqWT03C1EE7f6ewafA',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoic2Q2czRqbWk4M1RrVW44SnJaSE13S3lQalMyMGZ1TlRUYjA4TnV6aiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hcGkvbWVtYmVycy8zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRkQjdzZy92elB4RFI5TDVWUGdFbzlPNFhYbGRwRk52N2VoZWZjc2xlT3RnWTlETzZQNktDNiI7fQ==',1749181154),('wqVqGW12KAzQ0TcMqn9VgH0i4j6HBAMli5oqSkpb',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoidmFLSVFSeEdBRDZ1RGRxSDBKTEhXMHhsNTVjc1JsRVREYVZxa0tjYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZW1iZXJzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRkQjdzZy92elB4RFI5TDVWUGdFbzlPNFhYbGRwRk52N2VoZWZjc2xlT3RnWTlETzZQNktDNiI7fQ==',1749194750),('Yxn6Zb42uJdLH1vviU73jWVm9NtcembPoTPGAK7m',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ1pUVzhRaWU2aEh2UUJmMWh4VFNMbTBRSkRCOFR5T3I4c1ZhYXMyTiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9tYWlsP2lkPWM4MDQ0YjAyLWVhOWYtNDJjNC1hODQ3LTZjZDM4ZDdlMGQwNSZ2c2NvZGVCcm93c2VyUmVxSWQ9MTc0OTE1MDc0OTkyMiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjEwNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL21haWw/aWQ9YzgwNDRiMDItZWE5Zi00MmM0LWE4NDctNmNkMzhkN2UwZDA1JnZzY29kZUJyb3dzZXJSZXFJZD0xNzQ5MTUwNzQ5OTIyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749150750);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `group` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'organization_name','Kerala Association of Professional Physiotherapists - Wayanad Chapter','Organization Name','text','general','Full name of the organization',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(2,'organization_short_name','KAPC Wayanad','Short Name','text','general','Short name or acronym of the organization',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(3,'organization_email','info@kapcwayanad.org','Email Address','text','contact','Primary email address for the organization',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(4,'organization_phone','9876543210','Phone Number','text','contact','Primary phone number for the organization',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(5,'organization_address','Wayanad, Kerala, India','Address','text','contact','Physical address of the organization',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(6,'organization_website','https://kapcwayanad.org','Website','text','contact','Official website URL',1,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(7,'membership_fee','100','Membership Fee','text','financial','Regular membership fee amount',0,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(8,'membership_fee_frequency','monthly','Fee Frequency','text','financial','How often membership fees are collected',0,'2025-06-05 18:50:50','2025-06-05 18:50:50'),(9,'membership_fee_due_day','5','Due Day','text','financial','Day of the month when membership fees are due',0,'2025-06-05 18:50:50','2025-06-05 18:50:50');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_logs`
--

DROP TABLE IF EXISTS `sms_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `request_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `booking_id` bigint unsigned DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `error_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_time` timestamp NULL DEFAULT NULL,
  `response_data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sms_logs_booking_id_foreign` (`booking_id`),
  KEY `sms_logs_request_id_index` (`request_id`),
  CONSTRAINT `sms_logs_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_logs`
--

LOCK TABLES `sms_logs` WRITE;
/*!40000 ALTER TABLE `sms_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin User','88sanalraj@gmail.com','2025-06-05 18:50:49','$2y$12$ofKUHO0YcVOBltLhKK8guuo.AxAePMrRiJTXp64aOs2xmwcfAcd1y',NULL,'2025-06-05 18:50:49','2025-06-05 18:50:49'),(2,'Muhammed Niyas K M','mhdniyas37@gmail.com','2025-06-05 18:50:50','$2y$12$dB7sg/vzPxDR9L5VPgEo9O4XXldpFNv7ehefcsleOtgY9DO6P6KC6',NULL,'2025-06-05 18:50:50','2025-06-05 18:50:50');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visitors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_visited` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visit_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitors`
--

LOCK TABLES `visitors` WRITE;
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;
INSERT INTO `visitors` VALUES (1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-05','2025-06-05 18:44:29','2025-06-05 18:44:29'),(2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home','http://127.0.0.1:8000/admin/mail','2025-06-05','2025-06-05 18:58:32','2025-06-05 18:58:32'),(3,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','contact','http://127.0.0.1:8000/membership/success','2025-06-05','2025-06-05 18:59:40','2025-06-05 18:59:40'),(4,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home','http://127.0.0.1:8000/contact','2025-06-05','2025-06-05 18:59:43','2025-06-05 18:59:43'),(5,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/','2025-06-05','2025-06-05 19:00:07','2025-06-05 19:00:07'),(6,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home','http://127.0.0.1:8000/admin','2025-06-05','2025-06-05 19:00:23','2025-06-05 19:00:23'),(7,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/','2025-06-05','2025-06-05 19:01:33','2025-06-05 19:01:33'),(8,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home','http://127.0.0.1:8000/members','2025-06-05','2025-06-05 19:01:38','2025-06-05 19:01:38'),(9,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 03:23:41','2025-06-06 03:23:41'),(10,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home','http://localhost:8000/admin/clinics/create','2025-06-06','2025-06-06 03:38:47','2025-06-06 03:38:47'),(11,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','clinics','http://localhost:8000/','2025-06-06','2025-06-06 03:38:48','2025-06-06 03:38:48'),(12,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:13:51','2025-06-06 06:13:51'),(13,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:14:49','2025-06-06 06:14:49'),(14,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:15:06','2025-06-06 06:15:06'),(15,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:15:07','2025-06-06 06:15:07'),(16,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:36:51','2025-06-06 06:36:51'),(17,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:37:24','2025-06-06 06:37:24'),(18,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:37:53','2025-06-06 06:37:53'),(19,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:38:05','2025-06-06 06:38:05'),(20,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:38:13','2025-06-06 06:38:13'),(21,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:38:21','2025-06-06 06:38:21'),(22,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:38:46','2025-06-06 06:38:46'),(23,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:39:25','2025-06-06 06:39:25'),(24,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:39:28','2025-06-06 06:39:28'),(25,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 06:39:48','2025-06-06 06:39:48'),(26,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/','2025-06-06','2025-06-06 06:39:53','2025-06-06 06:39:53'),(27,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 06:40:00','2025-06-06 06:40:00'),(28,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home',NULL,'2025-06-06','2025-06-06 07:08:25','2025-06-06 07:08:25'),(29,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','clinics','http://127.0.0.1:8000/','2025-06-06','2025-06-06 07:08:34','2025-06-06 07:08:34'),(30,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/clinic/register','2025-06-06','2025-06-06 07:12:37','2025-06-06 07:12:37'),(31,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:12:48','2025-06-06 07:12:48'),(32,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home','http://127.0.0.1:8000/admin/members','2025-06-06','2025-06-06 07:14:13','2025-06-06 07:14:13'),(33,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/','2025-06-06','2025-06-06 07:14:16','2025-06-06 07:14:16'),(34,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:14:21','2025-06-06 07:14:21'),(35,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:14:29','2025-06-06 07:14:29'),(36,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:14:51','2025-06-06 07:14:51'),(37,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:15:02','2025-06-06 07:15:02'),(38,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/members/muhammed-niyas-k-m','2025-06-06','2025-06-06 07:15:08','2025-06-06 07:15:08'),(39,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:15:11','2025-06-06 07:15:11'),(40,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/members/muhammed-niyas','2025-06-06','2025-06-06 07:15:24','2025-06-06 07:15:24'),(41,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:15:28','2025-06-06 07:15:28'),(42,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:15:36','2025-06-06 07:15:36'),(43,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/members/muhammed-niyas','2025-06-06','2025-06-06 07:15:49','2025-06-06 07:15:49'),(44,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:15:53','2025-06-06 07:15:53'),(45,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/members/muhammed-niyas','2025-06-06','2025-06-06 07:16:02','2025-06-06 07:16:02'),(46,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:16:05','2025-06-06 07:16:05'),(47,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home','http://127.0.0.1:8000/members/muhammed-niyas','2025-06-06','2025-06-06 07:16:19','2025-06-06 07:16:19'),(48,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','clinics','http://127.0.0.1:8000/','2025-06-06','2025-06-06 07:17:15','2025-06-06 07:17:15'),(49,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/clinic/register','2025-06-06','2025-06-06 07:18:06','2025-06-06 07:18:06'),(50,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:18:08','2025-06-06 07:18:08'),(51,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','clinics','http://127.0.0.1:8000/members/muhammed-niyas','2025-06-06','2025-06-06 07:19:10','2025-06-06 07:19:10'),(52,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','home','http://127.0.0.1:8000/clinic/success','2025-06-06','2025-06-06 07:19:58','2025-06-06 07:19:58'),(53,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','clinics','http://127.0.0.1:8000/','2025-06-06','2025-06-06 07:20:01','2025-06-06 07:20:01'),(54,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','clinics-by-taluk','http://127.0.0.1:8000/clinics','2025-06-06','2025-06-06 07:20:13','2025-06-06 07:20:13'),(55,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/bookings/create/1','2025-06-06','2025-06-06 07:21:10','2025-06-06 07:21:10'),(56,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:21:14','2025-06-06 07:21:14'),(57,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/members/muhammed-niyas','2025-06-06','2025-06-06 07:21:18','2025-06-06 07:21:18'),(58,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:21:21','2025-06-06 07:21:21'),(59,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/members/muhammed-niyas-k-m','2025-06-06','2025-06-06 07:21:26','2025-06-06 07:21:26'),(60,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:21:30','2025-06-06 07:21:30'),(61,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:24:28','2025-06-06 07:24:28'),(62,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','member-view','http://127.0.0.1:8000/members','2025-06-06','2025-06-06 07:24:45','2025-06-06 07:24:45'),(63,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','members','http://127.0.0.1:8000/members/muhammed-niyas','2025-06-06','2025-06-06 07:25:50','2025-06-06 07:25:50');
/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_reports`
--

DROP TABLE IF EXISTS `work_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `work_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_reports`
--

LOCK TABLES `work_reports` WRITE;
/*!40000 ALTER TABLE `work_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `work_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'kapcwayanad_test'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-10  7:10:21
