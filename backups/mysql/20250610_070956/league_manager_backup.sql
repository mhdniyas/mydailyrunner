-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: localhost    Database: league_manager
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
-- Table structure for table `auction_bids`
--

DROP TABLE IF EXISTS `auction_bids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auction_bids` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `auction_id` bigint unsigned NOT NULL,
  `auction_player_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `is_winning` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_bids_auction_id_foreign` (`auction_id`),
  KEY `auction_bids_auction_player_id_foreign` (`auction_player_id`),
  KEY `auction_bids_team_id_foreign` (`team_id`),
  CONSTRAINT `auction_bids_auction_id_foreign` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auction_bids_auction_player_id_foreign` FOREIGN KEY (`auction_player_id`) REFERENCES `auction_players` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auction_bids_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auction_bids`
--

LOCK TABLES `auction_bids` WRITE;
/*!40000 ALTER TABLE `auction_bids` DISABLE KEYS */;
/*!40000 ALTER TABLE `auction_bids` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auction_players`
--

DROP TABLE IF EXISTS `auction_players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auction_players` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `auction_id` bigint unsigned NOT NULL,
  `player_id` bigint unsigned NOT NULL,
  `status` enum('pending','in_progress','sold','unsold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `base_price` decimal(10,2) NOT NULL,
  `sold_price` decimal(10,2) DEFAULT NULL,
  `sold_to_team_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auction_players_auction_id_player_id_unique` (`auction_id`,`player_id`),
  KEY `auction_players_player_id_foreign` (`player_id`),
  KEY `auction_players_sold_to_team_id_foreign` (`sold_to_team_id`),
  CONSTRAINT `auction_players_auction_id_foreign` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auction_players_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auction_players_sold_to_team_id_foreign` FOREIGN KEY (`sold_to_team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auction_players`
--

LOCK TABLES `auction_players` WRITE;
/*!40000 ALTER TABLE `auction_players` DISABLE KEYS */;
/*!40000 ALTER TABLE `auction_players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auctions`
--

DROP TABLE IF EXISTS `auctions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auctions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `league_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `minimum_bid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `increment_amount` decimal(10,2) NOT NULL DEFAULT '100.00',
  `time_per_bid` int NOT NULL DEFAULT '30',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auctions_league_id_foreign` (`league_id`),
  CONSTRAINT `auctions_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auctions`
--

LOCK TABLES `auctions` WRITE;
/*!40000 ALTER TABLE `auctions` DISABLE KEYS */;
/*!40000 ALTER TABLE `auctions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `award_winners`
--

DROP TABLE IF EXISTS `award_winners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `award_winners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `award_id` bigint unsigned NOT NULL,
  `league_id` bigint unsigned NOT NULL,
  `fixture_id` bigint unsigned DEFAULT NULL,
  `player_id` bigint unsigned DEFAULT NULL,
  `team_id` bigint unsigned DEFAULT NULL,
  `season` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stats` json DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `award_winners_unique` (`award_id`,`league_id`,`fixture_id`,`player_id`,`team_id`,`season`),
  KEY `award_winners_league_id_foreign` (`league_id`),
  KEY `award_winners_fixture_id_foreign` (`fixture_id`),
  KEY `award_winners_player_id_foreign` (`player_id`),
  KEY `award_winners_team_id_foreign` (`team_id`),
  CONSTRAINT `award_winners_award_id_foreign` FOREIGN KEY (`award_id`) REFERENCES `awards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `award_winners_fixture_id_foreign` FOREIGN KEY (`fixture_id`) REFERENCES `fixtures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `award_winners_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE,
  CONSTRAINT `award_winners_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE,
  CONSTRAINT `award_winners_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `award_winners`
--

LOCK TABLES `award_winners` WRITE;
/*!40000 ALTER TABLE `award_winners` DISABLE KEYS */;
/*!40000 ALTER TABLE `award_winners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `awards`
--

DROP TABLE IF EXISTS `awards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `awards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `league_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('batting','bowling','fielding','all_rounder','team','special') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'batting',
  `scope` enum('match','tournament','season') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'match',
  `criteria` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `awards_slug_unique` (`slug`),
  KEY `awards_league_id_foreign` (`league_id`),
  CONSTRAINT `awards_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `awards`
--

LOCK TABLES `awards` WRITE;
/*!40000 ALTER TABLE `awards` DISABLE KEYS */;
/*!40000 ALTER TABLE `awards` ENABLE KEYS */;
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
-- Table structure for table `cricket_matches`
--

DROP TABLE IF EXISTS `cricket_matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cricket_matches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team1_id` bigint unsigned NOT NULL,
  `team2_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `venue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `team1_score` int DEFAULT NULL,
  `team2_score` int DEFAULT NULL,
  `team1_wickets` int DEFAULT NULL,
  `team2_wickets` int DEFAULT NULL,
  `team1_overs` int DEFAULT NULL,
  `team2_overs` int DEFAULT NULL,
  `result_summary` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cricket_matches_team1_id_foreign` (`team1_id`),
  KEY `cricket_matches_team2_id_foreign` (`team2_id`),
  CONSTRAINT `cricket_matches_team1_id_foreign` FOREIGN KEY (`team1_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cricket_matches_team2_id_foreign` FOREIGN KEY (`team2_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cricket_matches`
--

LOCK TABLES `cricket_matches` WRITE;
/*!40000 ALTER TABLE `cricket_matches` DISABLE KEYS */;
/*!40000 ALTER TABLE `cricket_matches` ENABLE KEYS */;
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
-- Table structure for table `fixture_events`
--

DROP TABLE IF EXISTS `fixture_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fixture_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fixture_id` bigint unsigned NOT NULL,
  `fixture_innings_id` bigint unsigned NOT NULL,
  `player_id` bigint unsigned NOT NULL,
  `bowler_id` bigint unsigned DEFAULT NULL,
  `fielder_id` bigint unsigned DEFAULT NULL,
  `type` enum('run','boundary','six','wicket','wide','no_ball','bye','leg_bye','bowled','caught','lbw','run_out','stumped','hit_wicket') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `runs` int NOT NULL DEFAULT '0',
  `ball_number` int NOT NULL,
  `over_number` decimal(4,1) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fixture_events_fixture_id_foreign` (`fixture_id`),
  KEY `fixture_events_fixture_innings_id_foreign` (`fixture_innings_id`),
  KEY `fixture_events_player_id_foreign` (`player_id`),
  KEY `fixture_events_bowler_id_foreign` (`bowler_id`),
  KEY `fixture_events_fielder_id_foreign` (`fielder_id`),
  CONSTRAINT `fixture_events_bowler_id_foreign` FOREIGN KEY (`bowler_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fixture_events_fielder_id_foreign` FOREIGN KEY (`fielder_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fixture_events_fixture_id_foreign` FOREIGN KEY (`fixture_id`) REFERENCES `fixtures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fixture_events_fixture_innings_id_foreign` FOREIGN KEY (`fixture_innings_id`) REFERENCES `fixture_innings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fixture_events_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fixture_events`
--

LOCK TABLES `fixture_events` WRITE;
/*!40000 ALTER TABLE `fixture_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `fixture_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fixture_innings`
--

DROP TABLE IF EXISTS `fixture_innings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fixture_innings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fixture_id` bigint unsigned NOT NULL,
  `batting_team_id` bigint unsigned NOT NULL,
  `bowling_team_id` bigint unsigned NOT NULL,
  `innings_number` int NOT NULL,
  `total_runs` int NOT NULL,
  `wickets` int NOT NULL,
  `overs` decimal(4,1) NOT NULL,
  `extras` int NOT NULL DEFAULT '0',
  `wides` int NOT NULL DEFAULT '0',
  `no_balls` int NOT NULL DEFAULT '0',
  `byes` int NOT NULL DEFAULT '0',
  `leg_byes` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fixture_innings_fixture_id_foreign` (`fixture_id`),
  KEY `fixture_innings_batting_team_id_foreign` (`batting_team_id`),
  KEY `fixture_innings_bowling_team_id_foreign` (`bowling_team_id`),
  CONSTRAINT `fixture_innings_batting_team_id_foreign` FOREIGN KEY (`batting_team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fixture_innings_bowling_team_id_foreign` FOREIGN KEY (`bowling_team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fixture_innings_fixture_id_foreign` FOREIGN KEY (`fixture_id`) REFERENCES `fixtures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fixture_innings`
--

LOCK TABLES `fixture_innings` WRITE;
/*!40000 ALTER TABLE `fixture_innings` DISABLE KEYS */;
/*!40000 ALTER TABLE `fixture_innings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fixture_players`
--

DROP TABLE IF EXISTS `fixture_players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fixture_players` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fixture_id` bigint unsigned NOT NULL,
  `player_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  `is_playing` tinyint(1) NOT NULL DEFAULT '0',
  `batting_order` int DEFAULT NULL,
  `role` enum('batsman','bowler','all_rounder','wicket_keeper') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `runs_scored` int NOT NULL DEFAULT '0',
  `balls_faced` int NOT NULL DEFAULT '0',
  `wickets_taken` int NOT NULL DEFAULT '0',
  `overs_bowled` decimal(4,1) NOT NULL DEFAULT '0.0',
  `runs_conceded` int NOT NULL DEFAULT '0',
  `catches` int NOT NULL DEFAULT '0',
  `stumpings` int NOT NULL DEFAULT '0',
  `run_outs` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fixture_players_fixture_id_player_id_unique` (`fixture_id`,`player_id`),
  KEY `fixture_players_player_id_foreign` (`player_id`),
  KEY `fixture_players_team_id_foreign` (`team_id`),
  CONSTRAINT `fixture_players_fixture_id_foreign` FOREIGN KEY (`fixture_id`) REFERENCES `fixtures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fixture_players_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fixture_players_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fixture_players`
--

LOCK TABLES `fixture_players` WRITE;
/*!40000 ALTER TABLE `fixture_players` DISABLE KEYS */;
/*!40000 ALTER TABLE `fixture_players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fixtures`
--

DROP TABLE IF EXISTS `fixtures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fixtures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `league_id` bigint unsigned NOT NULL,
  `home_team_id` bigint unsigned NOT NULL,
  `away_team_id` bigint unsigned NOT NULL,
  `venue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `match_date` datetime NOT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled','postponed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `match_type` enum('T20','ODI','Test','Local') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Local',
  `overs_per_innings` int NOT NULL DEFAULT '10',
  `players_per_team` int NOT NULL DEFAULT '11',
  `home_runs` int DEFAULT NULL,
  `home_wickets` int DEFAULT NULL,
  `home_overs` decimal(4,1) DEFAULT NULL,
  `away_runs` int DEFAULT NULL,
  `away_wickets` int DEFAULT NULL,
  `away_overs` decimal(4,1) DEFAULT NULL,
  `match_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `round` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fixtures_league_id_foreign` (`league_id`),
  KEY `fixtures_home_team_id_foreign` (`home_team_id`),
  KEY `fixtures_away_team_id_foreign` (`away_team_id`),
  CONSTRAINT `fixtures_away_team_id_foreign` FOREIGN KEY (`away_team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fixtures_home_team_id_foreign` FOREIGN KEY (`home_team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fixtures_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fixtures`
--

LOCK TABLES `fixtures` WRITE;
/*!40000 ALTER TABLE `fixtures` DISABLE KEYS */;
/*!40000 ALTER TABLE `fixtures` ENABLE KEYS */;
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
-- Table structure for table `leaderboards`
--

DROP TABLE IF EXISTS `leaderboards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leaderboards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `league_id` bigint unsigned DEFAULT NULL,
  `player_id` bigint unsigned DEFAULT NULL,
  `team_id` bigint unsigned DEFAULT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int NOT NULL DEFAULT '0',
  `matches_played` int NOT NULL DEFAULT '0',
  `detailed_stats` json DEFAULT NULL,
  `season` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leaderboard_player_unique` (`league_id`,`player_id`,`type`,`category`,`season`),
  UNIQUE KEY `leaderboard_team_unique` (`league_id`,`team_id`,`type`,`category`,`season`),
  KEY `leaderboards_player_id_foreign` (`player_id`),
  KEY `leaderboards_team_id_foreign` (`team_id`),
  CONSTRAINT `leaderboards_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leaderboards_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leaderboards_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leaderboards`
--

LOCK TABLES `leaderboards` WRITE;
/*!40000 ALTER TABLE `leaderboards` DISABLE KEYS */;
/*!40000 ALTER TABLE `leaderboards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `league_icon_players`
--

DROP TABLE IF EXISTS `league_icon_players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `league_icon_players` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `league_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  `player_id` bigint unsigned NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `league_icon_players_league_id_team_id_player_id_unique` (`league_id`,`team_id`,`player_id`),
  KEY `league_icon_players_team_id_foreign` (`team_id`),
  KEY `league_icon_players_player_id_foreign` (`player_id`),
  CONSTRAINT `league_icon_players_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE,
  CONSTRAINT `league_icon_players_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE,
  CONSTRAINT `league_icon_players_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `league_icon_players`
--

LOCK TABLES `league_icon_players` WRITE;
/*!40000 ALTER TABLE `league_icon_players` DISABLE KEYS */;
/*!40000 ALTER TABLE `league_icon_players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `league_player`
--

DROP TABLE IF EXISTS `league_player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `league_player` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `league_id` bigint unsigned NOT NULL,
  `player_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned DEFAULT NULL,
  `status` enum('available','drafted','sold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `player_role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_type` enum('captain','vice-captain','wicket-keeper','bowler','all-rounder','batsman') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sold_price` decimal(10,2) DEFAULT NULL,
  `joined_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `has_iconplayer` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `league_player_league_id_player_id_unique` (`league_id`,`player_id`),
  KEY `league_player_player_id_foreign` (`player_id`),
  KEY `league_player_team_id_foreign` (`team_id`),
  CONSTRAINT `league_player_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE,
  CONSTRAINT `league_player_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE,
  CONSTRAINT `league_player_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `league_player`
--

LOCK TABLES `league_player` WRITE;
/*!40000 ALTER TABLE `league_player` DISABLE KEYS */;
/*!40000 ALTER TABLE `league_player` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `league_team`
--

DROP TABLE IF EXISTS `league_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `league_team` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `league_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `joined_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `league_team_league_id_team_id_unique` (`league_id`,`team_id`),
  KEY `league_team_team_id_foreign` (`team_id`),
  CONSTRAINT `league_team_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE,
  CONSTRAINT `league_team_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `league_team`
--

LOCK TABLES `league_team` WRITE;
/*!40000 ALTER TABLE `league_team` DISABLE KEYS */;
/*!40000 ALTER TABLE `league_team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leagues`
--

DROP TABLE IF EXISTS `leagues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leagues` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `rules` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','pending','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `format` enum('Mini','T10','T20','ODI','Test') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'T20',
  `has_auction` tinyint(1) NOT NULL DEFAULT '1',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `max_teams` int NOT NULL DEFAULT '8',
  `max_players_per_team` int NOT NULL DEFAULT '11',
  `min_teams` int NOT NULL DEFAULT '4',
  `registration_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `budget` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leagues_slug_unique` (`slug`),
  KEY `leagues_created_by_foreign` (`created_by`),
  CONSTRAINT `leagues_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leagues`
--

LOCK TABLES `leagues` WRITE;
/*!40000 ALTER TABLE `leagues` DISABLE KEYS */;
INSERT INTO `leagues` VALUES (1,'Indian Premier League 2023','indian-premier-league-2023','The 16th season of the Indian Premier League, a professional Twenty20 cricket league.','1. Each team can have a maximum of 25 players\n2. Maximum 8 overseas players per team\n3. Playing XI can have maximum 4 overseas players\n4. Strategic timeout of 2.5 minutes after 10 overs in each innings\n5. Decision Review System (DRS) available for each team',NULL,NULL,'draft','T20',1,'2023-03-31','2023-05-28',10,25,8,2500000.00,90000000.00,1,'2025-06-08 15:49:03','2025-06-08 15:55:54',NULL),(2,'Cricket Premier League 2023','cpl-2023','A professional Twenty20 cricket league.','1. Each team can have a maximum of 20 players\n2. Maximum 6 overseas players per team\n3. Playing XI can have maximum 4 overseas players',NULL,NULL,'draft','T20',1,'2023-09-01','2023-10-15',8,20,6,1500000.00,70000000.00,1,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL);
/*!40000 ALTER TABLE `leagues` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_03_19_000001_create_roles_table',1),(5,'2024_03_19_000002_create_permissions_table',1),(6,'2024_03_19_000003_create_role_user_table',1),(7,'2024_03_19_000004_create_permission_role_table',1),(8,'2024_03_21_000005_create_leagues_table',1),(9,'2024_03_21_000006_create_teams_table',1),(10,'2024_03_21_000007_create_players_table',1),(11,'2024_03_21_000008_create_auctions_table',1),(12,'2024_03_21_000009_create_fixtures_table',1),(13,'2024_03_21_000010_create_awards_table',1),(14,'2024_03_21_000011_create_leaderboards_table',1),(15,'2024_06_08_add_auction_flag_to_leagues',1),(16,'2024_06_08_add_phone_numbers',1),(17,'2024_06_08_add_player_role_columns',1),(18,'2025_06_08_033216_create_cricket_matches_table',1),(19,'2025_06_08_133632_add_has_iconplayer_to_league_player_table',1),(20,'2025_06_08_135225_create_league_iconplayers_table',1),(21,'2025_06_10_000001_update_leagues_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
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
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permission_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_role_permission_id_role_id_unique` (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_role`
--

LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
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
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`),
  UNIQUE KEY `permissions_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `players` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jersey_number` int DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `players_slug_unique` (`slug`),
  KEY `players_user_id_foreign` (`user_id`),
  CONSTRAINT `players_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
INSERT INTO `players` VALUES (1,'Virat Kohli','virat-kohli',NULL,NULL,'1986-07-17','India','+42 65912647','Batsman',18,1150000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(2,'Rohit Sharma','rohit-sharma',NULL,NULL,'1998-10-07','India','+26 29562682','Batsman',96,1280000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(3,'MS Dhoni','ms-dhoni',NULL,NULL,'2001-02-12','India','+75 43545403','Wicket-Keeper',3,1320000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(4,'Jasprit Bumrah','jasprit-bumrah',NULL,NULL,'1990-12-16','India','+67 50720379','Bowler',38,590000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(5,'Ravindra Jadeja','ravindra-jadeja',NULL,NULL,'2001-05-05','India','+57 99621180','All-Rounder',99,1610000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(6,'KL Rahul','kl-rahul',NULL,NULL,'2000-03-25','India','+81 35662016','Batsman',49,810000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(7,'Hardik Pandya','hardik-pandya',NULL,NULL,'1997-11-15','India','+73 78374601','All-Rounder',84,1370000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(8,'Rishabh Pant','rishabh-pant',NULL,NULL,'1997-05-09','India','+5 14858870','Wicket-Keeper',13,600000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(9,'Shikhar Dhawan','shikhar-dhawan',NULL,NULL,'1999-02-04','India','+95 76217794','Batsman',66,1700000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(10,'Ravichandran Ashwin','ravichandran-ashwin',NULL,NULL,'1994-11-16','India','+1 35252683','Bowler',41,1020000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(11,'Yuzvendra Chahal','yuzvendra-chahal',NULL,NULL,'1997-03-19','India','+38 22215309','Bowler',92,1210000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(12,'Suryakumar Yadav','suryakumar-yadav',NULL,NULL,'1999-08-02','India','+54 72656160','Batsman',39,1390000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(13,'Shreyas Iyer','shreyas-iyer',NULL,NULL,'1989-08-16','India','+77 52449874','Batsman',8,670000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(14,'Mohammed Shami','mohammed-shami',NULL,NULL,'1995-11-10','India','+71 85583523','Bowler',66,620000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(15,'Bhuvneshwar Kumar','bhuvneshwar-kumar',NULL,NULL,'1990-04-08','India','+5 57545556','Bowler',12,1930000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(16,'Ishan Kishan','ishan-kishan',NULL,NULL,'1993-09-13','India','+10 73545023','Wicket-Keeper',69,1300000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(17,'Shubman Gill','shubman-gill',NULL,NULL,'1989-12-13','India','+14 71933053','Batsman',63,1980000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(18,'Axar Patel','axar-patel',NULL,NULL,'1999-04-05','India','+63 22389355','All-Rounder',96,1290000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(19,'Deepak Chahar','deepak-chahar',NULL,NULL,'1987-12-22','India','+93 23335558','Bowler',83,680000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(20,'Shardul Thakur','shardul-thakur',NULL,NULL,'1990-10-12','India','+67 71175379','All-Rounder',67,1860000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(21,'Jos Buttler','jos-buttler',NULL,NULL,'1987-07-14','England','+66 59113329','Wicket-Keeper',69,1170000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(22,'Kane Williamson','kane-williamson',NULL,NULL,'1997-01-15','New Zealand','+38 80984318','Batsman',85,1400000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(23,'David Warner','david-warner',NULL,NULL,'1999-12-23','Australia','+35 94692851','Batsman',33,1540000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(24,'AB de Villiers','ab-de-villiers',NULL,NULL,'1991-04-07','South Africa','+83 78904671','Batsman',87,1740000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(25,'Rashid Khan','rashid-khan',NULL,NULL,'1994-02-04','Afghanistan','+40 71067823','Bowler',7,730000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(26,'Trent Boult','trent-boult',NULL,NULL,'1999-03-11','New Zealand','+13 87777838','Bowler',92,560000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(27,'Faf du Plessis','faf-du-plessis',NULL,NULL,'1993-02-26','South Africa','+13 72276277','Batsman',4,950000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(28,'Quinton de Kock','quinton-de-kock',NULL,NULL,'1992-07-11','South Africa','+11 67290865','Wicket-Keeper',26,1660000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(29,'Kagiso Rabada','kagiso-rabada',NULL,NULL,'1990-08-27','South Africa','+42 96205950','Bowler',7,1220000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(30,'Glenn Maxwell','glenn-maxwell',NULL,NULL,'2002-07-01','Australia','+52 97797695','All-Rounder',59,1460000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(31,'Kieron Pollard','kieron-pollard',NULL,NULL,'1994-06-23','West Indies','+80 79705014','All-Rounder',95,1380000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(32,'Andre Russell','andre-russell',NULL,NULL,'1992-05-17','West Indies','+30 65100529','All-Rounder',1,1770000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(33,'Chris Gayle','chris-gayle',NULL,NULL,'1989-02-08','West Indies','+33 98631510','Batsman',52,1910000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(34,'Steve Smith','steve-smith',NULL,NULL,'1986-12-25','Australia','+11 48224833','Batsman',20,1700000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(35,'Pat Cummins','pat-cummins',NULL,NULL,'2003-04-04','Australia','+93 12930430','Bowler',84,1620000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(36,'Mitchell Starc','mitchell-starc',NULL,NULL,'1987-02-08','Australia','+61 22776786','Bowler',54,1750000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(37,'Jonny Bairstow','jonny-bairstow',NULL,NULL,'1994-01-03','England','+27 49028632','Wicket-Keeper',13,1540000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(38,'Eoin Morgan','eoin-morgan',NULL,NULL,'1996-01-19','England','+66 75731177','Batsman',90,1870000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(39,'Jason Roy','jason-roy',NULL,NULL,'1990-01-13','England','+25 14347518','Batsman',61,1430000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(40,'Shakib Al Hasan','shakib-al-hasan',NULL,NULL,'1997-03-03','Bangladesh','+28 44762778','All-Rounder',47,1730000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(41,'Prithvi Shaw','prithvi-shaw',NULL,NULL,'1987-10-15','India','+25 88604136','Batsman',33,1510000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(42,'Sanju Samson','sanju-samson',NULL,NULL,'1990-04-16','India','+55 41924932','Wicket-Keeper',75,1160000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(43,'Mayank Agarwal','mayank-agarwal',NULL,NULL,'1999-10-16','India','+34 51471525','Batsman',16,1620000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(44,'Washington Sundar','washington-sundar',NULL,NULL,'1998-02-24','India','+11 19534287','All-Rounder',70,1360000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(45,'T Natarajan','t-natarajan',NULL,NULL,'1998-11-10','India','+93 76632562','Bowler',88,900000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(46,'Kuldeep Yadav','kuldeep-yadav',NULL,NULL,'1996-11-13','India','+37 24987178','Bowler',24,610000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(47,'Krunal Pandya','krunal-pandya',NULL,NULL,'1994-03-21','India','+8 99461652','All-Rounder',34,530000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(48,'Umesh Yadav','umesh-yadav',NULL,NULL,'1996-09-25','India','+39 44392303','Bowler',12,1470000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(49,'Dinesh Karthik','dinesh-karthik',NULL,NULL,'1999-08-20','India','+73 46436262','Wicket-Keeper',94,640000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(50,'Ambati Rayudu','ambati-rayudu',NULL,NULL,'1999-12-09','India','+8 86528008','Batsman',5,860000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(51,'Nicholas Pooran','nicholas-pooran',NULL,NULL,'1988-10-27','West Indies','+43 66557629','Wicket-Keeper',34,790000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(52,'Shimron Hetmyer','shimron-hetmyer',NULL,NULL,'1997-09-02','West Indies','+4 60631018','Batsman',62,1670000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(53,'Dwayne Bravo','dwayne-bravo',NULL,NULL,'1994-09-21','West Indies','+5 12862744','All-Rounder',89,880000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(54,'Sunil Narine','sunil-narine',NULL,NULL,'1992-04-21','West Indies','+9 63482752','All-Rounder',54,950000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(55,'Lockie Ferguson','lockie-ferguson',NULL,NULL,'2000-11-22','New Zealand','+6 48708521','Bowler',58,960000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(56,'Adam Zampa','adam-zampa',NULL,NULL,'1995-12-01','Australia','+21 30471508','Bowler',10,1910000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(57,'Marcus Stoinis','marcus-stoinis',NULL,NULL,'2003-06-03','Australia','+11 43553583','All-Rounder',92,1860000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(58,'Sam Curran','sam-curran',NULL,NULL,'2001-12-06','England','+42 37347548','All-Rounder',75,1680000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(59,'Moeen Ali','moeen-ali',NULL,NULL,'1992-06-20','England','+87 51019773','All-Rounder',86,1790000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(60,'Liam Livingstone','liam-livingstone',NULL,NULL,'1999-05-05','England','+18 65912376','All-Rounder',49,1760000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(61,'Yashasvi Jaiswal','yashasvi-jaiswal',NULL,NULL,'1998-10-16','India','+74 40017750','Batsman',57,1110000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(62,'Ruturaj Gaikwad','ruturaj-gaikwad',NULL,NULL,'2002-02-12','India','+7 65562704','Batsman',21,1850000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(63,'Devdutt Padikkal','devdutt-padikkal',NULL,NULL,'2001-10-05','India','+5 20068041','Batsman',77,820000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(64,'Venkatesh Iyer','venkatesh-iyer',NULL,NULL,'2000-12-13','India','+74 47167594','All-Rounder',7,1690000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(65,'Rahul Chahar','rahul-chahar',NULL,NULL,'1988-05-10','India','+64 28108799','Bowler',32,1820000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(66,'Ravi Bishnoi','ravi-bishnoi',NULL,NULL,'1991-10-04','India','+36 45309467','Bowler',55,960000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(67,'Arshdeep Singh','arshdeep-singh',NULL,NULL,'2000-02-27','India','+64 36262509','Bowler',95,1640000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(68,'Avesh Khan','avesh-khan',NULL,NULL,'1987-08-31','India','+79 55558055','Bowler',82,510000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(69,'Prasidh Krishna','prasidh-krishna',NULL,NULL,'1997-06-25','India','+61 57769875','Bowler',31,580000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(70,'Rahul Tripathi','rahul-tripathi',NULL,NULL,'1997-12-26','India','+62 21520511','Batsman',36,1330000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(71,'Nitish Rana','nitish-rana',NULL,NULL,'1989-05-29','India','+52 81419695','Batsman',94,640000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(72,'Deepak Hooda','deepak-hooda',NULL,NULL,'1986-12-15','India','+87 12812619','All-Rounder',89,1850000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(73,'Khaleel Ahmed','khaleel-ahmed',NULL,NULL,'1987-04-14','India','+56 88176029','Bowler',83,1670000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(74,'Mohammed Siraj','mohammed-siraj',NULL,NULL,'2001-11-04','India','+55 22933967','Bowler',80,1100000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(75,'Navdeep Saini','navdeep-saini',NULL,NULL,'1992-01-15','India','+67 45671227','Bowler',50,910000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(76,'Chetan Sakariya','chetan-sakariya',NULL,NULL,'2002-07-08','India','+26 75337005','Bowler',8,800000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(77,'Kartik Tyagi','kartik-tyagi',NULL,NULL,'1995-08-28','India','+1 45840294','Bowler',82,880000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(78,'Anrich Nortje','anrich-nortje',NULL,NULL,'1996-04-23','South Africa','+55 64030693','Bowler',54,1270000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(79,'Marco Jansen','marco-jansen',NULL,NULL,'1991-04-13','South Africa','+62 30290973','Bowler',67,1250000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(80,'Aiden Markram','aiden-markram',NULL,NULL,'1999-12-11','South Africa','+26 11483786','Batsman',33,630000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(81,'Rassie van der Dussen','rassie-van-der-dussen',NULL,NULL,'2000-04-09','South Africa','+93 13444892','Batsman',55,730000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(82,'Devon Conway','devon-conway',NULL,NULL,'2001-12-07','New Zealand','+91 95280350','Batsman',66,510000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(83,'Tim Southee','tim-southee',NULL,NULL,'2000-09-25','New Zealand','+49 86175920','Bowler',32,1940000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(84,'Mitchell Santner','mitchell-santner',NULL,NULL,'1999-11-18','New Zealand','+31 59854440','All-Rounder',60,1960000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(85,'Josh Hazlewood','josh-hazlewood',NULL,NULL,'1993-08-02','Australia','+45 63333694','Bowler',90,1840000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(86,'Mitchell Marsh','mitchell-marsh',NULL,NULL,'1997-08-02','Australia','+61 57224587','All-Rounder',16,1690000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(87,'Aaron Finch','aaron-finch',NULL,NULL,'1987-12-29','Australia','+75 29925049','Batsman',29,910000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(88,'Alex Carey','alex-carey',NULL,NULL,'1999-08-17','Australia','+44 28770032','Wicket-Keeper',60,1950000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(89,'Marnus Labuschagne','marnus-labuschagne',NULL,NULL,'1995-09-19','Australia','+48 83967717','Batsman',9,1630000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(90,'Chris Woakes','chris-woakes',NULL,NULL,'1992-12-25','England','+33 61525075','All-Rounder',13,880000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(91,'Adil Rashid','adil-rashid',NULL,NULL,'2002-08-20','England','+34 21895135','Bowler',41,1140000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(92,'Mark Wood','mark-wood',NULL,NULL,'1990-04-20','England','+87 76928409','Bowler',32,1580000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(93,'Dawid Malan','dawid-malan',NULL,NULL,'1991-09-23','England','+44 95733635','Batsman',30,1170000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(94,'Tom Curran','tom-curran',NULL,NULL,'1993-03-01','England','+6 54702765','All-Rounder',7,1150000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(95,'Rovman Powell','rovman-powell',NULL,NULL,'1996-09-13','West Indies','+20 55523976','Batsman',8,1080000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(96,'Fabian Allen','fabian-allen',NULL,NULL,'1991-03-22','West Indies','+14 43029893','All-Rounder',96,980000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(97,'Obed McCoy','obed-mccoy',NULL,NULL,'1993-10-22','West Indies','+17 55250532','Bowler',83,1930000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(98,'Akeal Hosein','akeal-hosein',NULL,NULL,'1987-02-20','West Indies','+91 12691841','Bowler',93,1500000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(99,'Romario Shepherd','romario-shepherd',NULL,NULL,'2000-01-29','West Indies','+33 77152389','All-Rounder',80,980000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(100,'Mujeeb Ur Rahman','mujeeb-ur-rahman',NULL,NULL,'1997-01-21','Afghanistan','+64 89968620','Bowler',43,1800000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(101,'Mohammad Nabi','mohammad-nabi',NULL,NULL,'1988-12-07','Afghanistan','+44 92047547','All-Rounder',2,1690000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(102,'Najibullah Zadran','najibullah-zadran',NULL,NULL,'1987-08-08','Afghanistan','+66 93108519','Batsman',14,1160000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(103,'Hazratullah Zazai','hazratullah-zazai',NULL,NULL,'1994-12-24','Afghanistan','+8 22984072','Batsman',39,1910000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(104,'Naveen-ul-Haq','naveen-ul-haq',NULL,NULL,'1995-06-02','Afghanistan','+83 73077941','Bowler',24,1830000.00,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL);
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_user_role_id_user_id_unique` (`role_id`,`user_id`),
  KEY `role_user_user_id_foreign` (`user_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
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
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('3QwgDiCprWiYKUtPKYtSTU1sKMPzeKpAWE76eaMg',NULL,'91.84.87.137','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic2pzS2J3WXA1dDJnMW9GcjM4RmdnMDRZeTBGZlk0a0toVjZ0WHVYOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHBzOi8vd3d3LmNyaWNrZXRsZWFndWVtYW5hZ2VyLmluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749412043),('3S9E6OL7TBzKqQxcss91nmWoakybtcPi5ITI9aO3',NULL,'66.249.74.70','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibGJyMVY1Y2NRTGttNFVHZGhDWmFyeXp3N3J5MDl4VThUeUZGQTRpeSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cHM6Ly9jcmlja2V0bGVhZ3VlbWFuYWdlci5pbi90ZWFtcy8yIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749466247),('4RAlXIaLiqKxA1Bfpl7u60x91SACS8PyXQ0nhU5W',NULL,'69.55.54.123','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic0laUWZ6dVBvUEt1ODUwbHBVQ0F3Z25RRHgzOTRIOTFVc3puZFhoOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vNjQuMjI3LjE3My4xNDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749404094),('7WMbN5uLr5SvGI19BY1BjEwa4O6SW3lrwYVuw8sI',NULL,'66.102.6.69','Mozilla/5.0 (Linux; Android 7.0; SM-G930V Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.125 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUXFxR0NqcDhzVnVJVVBRWkRYYTlvWXJlb3JjQWY5ZXpoNk5xUndXcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8vNmFtbW9ybmluZ2NyaWNrZXQuc2hvcCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749408409),('85EoLkvt8GdIftAbWomslKD4uGeXR8dskEbejIDJ',NULL,'45.82.78.254','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:102.0) Gecko/20100101 Firefox/102.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYms0aVlsTHljQWtQMXZrZDJFUG5IanUwZzdlMDU5MG56anpSYVIwUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vNjQuMjI3LjE3My4xNDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749399619),('8b2AQuLrNfeBrsP3tMkp3Z4YSzLbr6ZrPOt18xSB',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiODJocXZ5RGxnUVJ2TXRkSjdOTTBKT0plek5WZFU3MFpvbWpKMXZmcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749446331),('92l5wTRZrS5XqYziRdMB3Py9F9RynhAKNOPjTKlz',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0sxTmNBbWFtbk5oMXhWZ0hUUzhYYm92Qm9sTHdXMHBRZllrd21sMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749461742),('9IV2aSBCe8VOeYTqaxxyR8FtLQSp53S43NOQO0jw',NULL,'94.247.172.129','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWFdweDhoVXdjU0JkZWtnNzA5ZXVmeXpmajhwUjZWVkl5Tm45TmJ4WSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHBzOi8vd3d3LmNyaWNrZXRsZWFndWVtYW5hZ2VyLmluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749450365),('9RsM8dvg5M3uv7nkdnNMlBSHeWrhkjhJ3xq4DgfH',NULL,'137.184.191.101','python-requests/2.31.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicDBYMWZDNEhKTEFtZEZubjk4R05Kd0xKam95SGJXZm9JRE1jN3RGRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHBzOi8vd3d3LmNyaWNrZXRsZWFndWVtYW5hZ2VyLmluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749490477),('Cxlu8nkZAtI37c1K8eEXFWOGJ0UkGfzOfZrhw711',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOThvbnhqTmNiSWlhQm9QanJ4Nlg4WkZUSk9CRDhielBONlNIRVhnWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749529892),('DnVenCk1t3b9SmOOdRy6rvCvg8mm5w9h0LzERulW',NULL,'66.249.73.225','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTWtmc2liU1ZTQnEwSG9mZ0NaN3dwdDVkQjl5T25hUUh0VUJIUndTbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHBzOi8vd3d3LmNyaWNrZXRsZWFndWVtYW5hZ2VyLmluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749504173),('dtsG5kIqONWocQ1CbUoMK7VPTR7wf1NJTXrKoQbo',1,'157.51.204.51','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTUJqaTg5V0tReUVYUmlFY3hhRmx1Q3Q5c0FydWx3Q0NEenF1YXNpciI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQxOiJodHRwczovL2NyaWNrZXRsZWFndWVtYW5hZ2VyLmluL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1749445507),('fbiqBOWE1pzgyMrDi4An05ZkjyVgyQu3JOPFqSV6',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTzBvY05xS3ZDMTZuellYUFQ3akZxTHpzaGFFZXVTeU02MnZpQVNtUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749461748),('G1Q5uc8EFKhPphkgVFl5xUEqkAHlSkn6kSIekv80',NULL,'162.245.236.130','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:77.0; Mandajanganpergi) Gecko/20190101 Firefox/77.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVklqZHdRSGFOU3RUZWZ3REx1YzQ4enRCbnJwdFVncG05eE1WUENsRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749494783),('hBqroTRf20QsJgU4UhyGD1ofbzt6TcHxQZZhNgjQ',NULL,'66.249.74.70','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRHlyQVNFMTF4bThETnFtbGg1Q3VpRmlEcGJidHVEZFIya2JQMWZoYSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cHM6Ly9jcmlja2V0bGVhZ3VlbWFuYWdlci5pbi90ZWFtcy8zIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749460848),('hCOjDtO25Hhf0MGeey2pfwzmOJvjHeIyZbHmbceL',NULL,'124.222.8.47','Mozilla/5.0 (Linux; Android 10; LIO-AN00 Build/HUAWEILIO-AN00; wv) MicroMessenger Weixin QQ AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/78.0.3904.62 XWEB/2692 MMWEBSDK/200901 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSjgwTUpkYTJEck9WTXJuNWxQQTdLWVZDeTdZaVdMbElEbFdUb1J0YSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749527705),('hIlRBAK76bTLRYV8IqJczLfOPA5s6VuRB14QwnLZ',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicEZQMWpLa0tndFdJVm1oT3YyTlZ5bVVLUEtCT3c1eWJKd2hIejJ0OSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749446322),('i7ztN4A1FvE2eo82iMR3vU6u3H9X8p76iR10ezTi',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVENXaTZBODA1MGtlakdSWk5XajcyZWpHb1BpSGViSWNOQ0xZZzFpTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749529761),('IG5SBq8yYDQ70seFVMfxDv6hHADPjJwiPdos2rwP',NULL,'43.246.208.9','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.12 Safari/537.36 OPR/14.0.1116.4','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV3RRM2xQUmFnMlgxeVZmZnVGTGdJbko5U01BczhDY3VEalA4SVg1MiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vNjQuMjI3LjE3My4xNDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749399360),('IPr79dtoucUKdu79JO2cMyDixrovQSxx3kt2MQOR',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoickt1dnB3VGhLaU9iWmNZd2pxejZkQW9HQ0lnaXoxckU1VFA4V0s5aSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749471994),('iuvRLUk44DJVFDUG6uyKmyhjyvt1OLDg1pHralEw',1,'103.151.189.222','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUFBqNlB0V2M5TG9WWjl5RWZZbm42TUNzdlN4VEdHeGtOc3JFVTkzRCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM5OiJodHRwczovL2NyaWNrZXRsZWFndWVtYW5hZ2VyLmluL3BsYXllcnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1749408913),('jE7CfTzgQKmbdZNIP0C5YjKqpFDcEh3nSnMPax2V',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWGxSdnR2TnMxZnVnMmxJMzg3a3VsTVdrWWJrOTVxa0g5T3lMUEVyQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749446327),('KuNMjrgD7QPfsZkLpcYmTUuVvwRg8QW18M70tALh',NULL,'150.158.45.24','Mozilla/5.0 (Linux; Android 10; LIO-AN00 Build/HUAWEILIO-AN00; wv) MicroMessenger Weixin QQ AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/78.0.3904.62 XWEB/2692 MMWEBSDK/200901 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNGFmTFZvdEJqV2Q2YWhqelF3TW9LWHBhb0pHd05kVHI1UDBxMDByVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHBzOi8vd3d3LmNyaWNrZXRsZWFndWVtYW5hZ2VyLmluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749450382),('ldgaY8XPmvjNcJrWaq2Noai6DZBIW8b3XaN9XKwx',1,'157.51.223.40','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoidDJWZmRHYU5uQjFUNEZWb1JxTmV1bjI2SFBkOGRCTVUwSHhvMWZwayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vYXBpL2xlYWd1ZXMvMS90ZWFtcyI7fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1749408397),('lGCzCbv3lQTiOVXTAscSh3z33VMaebfNyUqLoBD2',NULL,'66.249.74.71','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOUdqSldVMzZncGFSQTA4S2xWUWxVZUx0NzFHWTN4V0JOWGNhTVM0MyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cHM6Ly9jcmlja2V0bGVhZ3VlbWFuYWdlci5pbi90ZWFtcy8xIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749479252),('mGxCtG3K9CAHO1p58METzD7SJrGpvvERzRXjpBli',NULL,'74.125.215.230','Google-Display-Ads-Bot','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQkJrYUlndGhBMFpVUjBGWG1aZHlFU2VGMUl4N0lueW9tdExvVzZVcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749452553),('Mn6Sem3xtwqiJnAx3BQbwzcNguQ06mMcW88QmKyf',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaVNzOXphZ1JzVkJqYkpoT2hqSFBNQ29USURiRG9SNzBOZUxqT2RUNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749529732),('nbi5xUwsWbWJ2XK8KVSpWKgrR3LU2OhxsS4HBsOj',NULL,'66.249.74.69','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicjg3QU5xbWZVUjFaRkpDa2t1MVE4dDB5bWxwVVBmTUJpRTJQUzgzNCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cHM6Ly9jcmlja2V0bGVhZ3VlbWFuYWdlci5pbi90ZWFtcy82Ijt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749487558),('nqFX9utEx9gbNFuKeNGoXzW7tqgzTY2P3iAZ2s4Y',NULL,'66.102.6.69','Mozilla/5.0 (Linux; Android 7.0; SM-G930V Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.125 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN0lCVVg2SWpqQTM5RURYSEJ4YVl4cmt0NXBxaERqejloaTlXNW0zayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8vNmFtbW9ybmluZ2NyaWNrZXQuc2hvcCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749408409),('p5p9hL6yBcTkC0eDeeeNEjhz7cThxOoandS37yHV',NULL,'46.17.174.172','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:98.0) Gecko/20100101 Firefox/98.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVjA2SHRqZzJ5WDRuTDNhWEt1OEdkQ1dBQnFkazhOS0RQQmJmUzJYeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749498256),('pakjOHpt4oUXyre9fu5WdpCrD3YzjxFew6L2Q0bI',NULL,'66.249.74.69','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM0ZBcXNMTXVQc25xYnRCM3JYdkd4MGhRYTBxektGQm9VOGNvYlo5VyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MToiaHR0cHM6Ly9jcmlja2V0bGVhZ3VlbWFuYWdlci5pbi9sZWFndWVzLzMiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNzoiaHR0cHM6Ly9jcmlja2V0bGVhZ3VlbWFuYWdlci5pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749525091),('PHLTyQlpdcBGPjtl9fJhkwpsZ9nHqjPlVDPHwGc0',NULL,'66.249.74.69','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidHd4UGxUblJTOElDTzVBY1ZtNGJrYkJvNU5xTU0xS2lkTjNTbGpJeSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cHM6Ly9jcmlja2V0bGVhZ3VlbWFuYWdlci5pbi90ZWFtcy80Ijt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749470942),('PqKW44nA6bwTnYxbGYs0jvfsBAM4WROL0ukACRb0',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUkhkcE5nZUYydVZjajhCY2FXRHRmNlRsbjFwZ2NGZ1VpNTdUV05FSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749529879),('QMoEDXS40WyTMqCJYsefQZe9HhCxNELvGGlWWCj2',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibE41RHgzd05nSjFMcERDcTMxUU9TVkF6Y1RrUzF1SnZEM3RhRUprRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749472003),('RvUKEKiB0TqWMf4bhu2bTMu2ViCH2YDBpsUbLqw3',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTGYzdUJWRVQ1QUU1dWVXSmdQdVBUajk0V1c4ODVXNGdsbEZsU0hJbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749471998),('sCYmwRltCokMxkHzhXjYc8QCVgLIrWKa3BieVfDF',NULL,'91.84.87.137','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSkl3QUNTaUY1SENab2hNTUVXMWk0elNLTDhOZzV3eFlrZTlqdzNrYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749408325),('SqpCpfd5briK3cC7ehp31tthgIKX79WIiSjH6yW2',1,'157.51.212.3','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUmlZOU9nblMwdklCTXJMeUFVRnVhWkw0R1Z5RFhDSmNUdlRZV2laUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbWF0Y2hlcyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1749477179),('sqR3mjkZlq4wuP9ZlAxlDLEey5p3MNATeG713tbV',NULL,'66.249.73.224','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiemc2UkxiUXJYU0FoOTlLTnRYSkhMZXNURkcyclNBRXZ0WVpNUFJaTCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo2MzoiaHR0cHM6Ly93d3cuY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vcGxheWVycz9zcG9ydF90eXBlPWZvb3RiYWxsIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmNyaWNrZXRsZWFndWVtYW5hZ2VyLmluL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749455736),('SyGGV0rzM9oDPWfj3HkKV9sqjCd4QZiD6APgdOM9',NULL,'157.51.223.40','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWEJBbjY1WXNwWGdNUFA4MXM3VXA4OUNuVkVXRjZITEk5MmRiNjI0SiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8vNmFtbW9ybmluZ2NyaWNrZXQuc2hvcCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1749408403),('tC8osDtx0JXwok56OZiHcZzGWlndNYet6Y5Z9SwL',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMEtXUzJyU0N0RnBNcUw5VEZtcE9NVDM5TEtzcXN0eHhjQlBqMVROMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749529773),('u9oz9drVQZEkNl3zNKebCfjUFjVnWJ9AcFSEr3Gp',NULL,'9.234.10.182','Mozilla/5.0 zgrab/0.x','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV3lUMTFVSWVQRmhiVkg5MFoxWFJQdXFlclhpVGN6UjJGeWcwdGhGeiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vNjQuMjI3LjE3My4xNDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749403391),('ungNwnKWKupVhYS6Mbre41YYMrzjyZsNnXQK7frc',NULL,'66.249.74.69','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZmJYR1NjWWhlT1RqcjNzcnF6a2prUllQYXNvZ01uekhBZkxiYk1HNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vcmVnaXN0ZXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749516783),('uS4tKOUQVr5v1sRWMyHVEqwbKtdw3NBZvn7jOL4C',NULL,'162.120.184.9','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiOVdhaGwwUUlsaEFweG1hdGRiRlcwdG0xSXMzMVVEc0lNNzVkRkVJbSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749477106),('VQ8xOExWtDndz3eOMHOf1uTDcL2E1f0OKDwLPaBs',NULL,'137.184.191.101','python-requests/2.31.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSkVXS1Vjdm50a2JROW1oN0taZHZPSVh2RTJnMUM1TmNQUUxPekw4TCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749490477),('VQWDAxZaEh1yAnRO38q3N98FgNpY7fQH71UNulp8',NULL,'152.57.129.145','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWlVRenRlbER6YWFWRUdlNEsyVGdzVHBES2IzWFk5eDBmZU9RVm1CRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749525992),('VRDJqrKOpyAKIunUJI1Gx1f6HdbbPmxwC722RTrV',NULL,'162.245.236.130','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:77.0; Mandajanganpergi) Gecko/20190101 Firefox/77.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS0NBYVBtNkJIeWo5amFNaG9jVTNWZjRWYWpXQ2JtbXZvbFRaUDVOMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHBzOi8vd3d3LmNyaWNrZXRsZWFndWVtYW5hZ2VyLmluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749513499),('X8IERh9o5GvZ90aqGJGxmEurnU5mQI2RQUSsPpzg',NULL,'54.90.135.15','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoia2UzRVRmY0JEZUU3Z1Z6bzcyRHZlalU2WThxQXlvUjdxckNMRVZ4VyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vNjQuMjI3LjE3My4xNDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749406096),('xcSYKCWaQ7wkvnaCkB3XMpqRP0YVS78tlH5jI7a5',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWNZc1d1Rk1WbWVtQzhKZ0dFNlZiMUc2U1ZxdkdqUHZ0Vld2anVaMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749461752),('xOJTcjnxkcMMiL3t38dRzEsPXjcjG8ZTE3ZID2Sb',NULL,'198.235.24.197','Expanse, a Palo Alto Networks company, searches across the global IPv4 space multiple times per day to identify customers&#39; presences on the Internet. If you would like to be excluded from our scans, please send IP addresses/domains to: scaninfo@paloaltonetworks.com','YTozOntzOjY6Il90b2tlbiI7czo0MDoibm14QUxjclFTN3pkVU1Ia0JScFVUcFlyaWxTOHVMZzI0SzlQMkZtayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749504322),('y5lTAdGd541x3hpX79V4z3CSgiEYtQA5VnhJrsyR',NULL,'198.235.24.153','','YTozOntzOjY6Il90b2tlbiI7czo0MDoibndsTW44dXNYclBmNVVoZXVSa2k5UndYZjY3Yks0ZlRQY291a1I4NiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749445862),('Yr18EjJIFMAxbJT0xWFGwRxuPnMzfGuMSYag3bcW',NULL,'157.51.213.248','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSXJtdXlmczJac3B0RWNYZVNBT2JkRlgzU2ZaQno4bzFEYVY1eFdtZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749487181),('yTgda248cERSjPLdLE3XFfVGtJbinq5JJ9Ey0BlG',NULL,'66.249.74.70','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOXRocmdkMUJZRHVyb0txTFQzdVNaQTdMNmx2OG5GeFlvbmNVR2xpbiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cHM6Ly9jcmlja2V0bGVhZ3VlbWFuYWdlci5pbi9wbGF5ZXJzLzE2Ijt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749533399),('YvmaKbQzkxMXYSk3Zp2cOWK3qCRzugrQ4Yc6t2NI',NULL,'66.249.74.69','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.7103.113 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTozOntzOjY6Il90b2tlbiI7czo0MDoielBUTVhTUWtDdkl1TVJpRlU1emluRnhPWTRFNTJiWjFOT1JJMHdrSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vZm9yZ290LXBhc3N3b3JkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1749495865),('Zy2pG5nTmDTBzrRo52De2c6P6LyAR58wXH8uabwv',NULL,'156.146.48.214','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOEFrdGVHYzFSN3BRTDVRbXFlNHFTNTY1ZUh5MGZoWFhkeTJ5TEhYTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY3JpY2tldGxlYWd1ZW1hbmFnZXIuaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1749529884);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_id` bigint unsigned NOT NULL,
  `owner_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teams_slug_unique` (`slug`),
  KEY `teams_manager_id_foreign` (`manager_id`),
  CONSTRAINT `teams_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (1,'Chennai Super Kings','chennai-super-kings','Official IPL team',NULL,NULL,'#FFFF00','#0080C8',1,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(2,'Mumbai Indians','mumbai-indians','Official IPL team',NULL,NULL,'#004BA0','#D1AB3E',1,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(3,'Royal Challengers Bangalore','royal-challengers-bangalore','Official IPL team',NULL,NULL,'#EC1C24','#000000',1,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(4,'Kolkata Knight Riders','kolkata-knight-riders','Official IPL team',NULL,NULL,'#3A225D','#D4AF37',1,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(5,'Delhi Capitals','delhi-capitals','Official IPL team',NULL,NULL,'#0078BC','#EF1B23',1,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(6,'Punjab Kings','punjab-kings','Official IPL team',NULL,NULL,'#ED1B24','#A7A9AC',1,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(7,'Rajasthan Royals','rajasthan-royals','Official IPL team',NULL,NULL,'#2D3E8B','#FF69B4',1,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(8,'Sunrisers Hyderabad','sunrisers-hyderabad','Official IPL team',NULL,NULL,'#FF822A','#000000',1,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(9,'Gujarat Titans','gujarat-titans','Official IPL team',NULL,NULL,'#1B2133','#0B4973',1,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL),(10,'Lucknow Super Giants','lucknow-super-giants','Official IPL team',NULL,NULL,'#A72056','#FFCC00',1,NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03',NULL);
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin User','admin@example.com','2025-06-08 15:49:02','$2y$12$eAs7Je3vNVqZXbekuac13usdyh.1TUoBb2KC9nI5lb7MeGPck/RS.',NULL,'2025-06-08 15:49:02','2025-06-08 15:49:02'),(2,'John Doe','john@example.com','2025-06-08 15:49:02','$2y$12$0H700lCODjUsSp5tCReJ3ebxzQw96KdNrrnNXXRSMDy2u8LyY8nKq',NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03'),(3,'Jane Smith','jane@example.com','2025-06-08 15:49:03','$2y$12$gJuV1V73/6N3qvUVhjIC/e/f5AHHkknY33gyaDhp52hqMH2KM.KfG',NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03'),(4,'Bob Wilson','bob@example.com','2025-06-08 15:49:03','$2y$12$6EVcSHVvZO1cDPh.kYCyPePBe94mQAQIF74af7U5dplJyh8CLYcui',NULL,'2025-06-08 15:49:03','2025-06-08 15:49:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'league_manager'
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
