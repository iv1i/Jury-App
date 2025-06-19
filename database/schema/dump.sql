/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.11-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: mysql    Database: jury_app
-- ------------------------------------------------------
-- Server version	8.0.32

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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES
(1,'admin','$2y$12$CmUxFfmm6cxjYiXmoVl96uYOZubdnf7LQQzZA0WDvX4UkJYe0EUvq','NjT1P3kYXxIIB6SMUbzVQ7MmUZkGM3wsBpCuEg100GWosjNjvKrpspP0tm7F','2024-08-20 05:03:32','2025-06-17 12:41:40');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `check_tasks`
--

DROP TABLE IF EXISTS `check_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `check_tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `sumary` bigint NOT NULL,
  `easy` int NOT NULL,
  `medium` int NOT NULL,
  `hard` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `check_tasks`
--

LOCK TABLES `check_tasks` WRITE;
/*!40000 ALTER TABLE `check_tasks` DISABLE KEYS */;
INSERT INTO `check_tasks` VALUES
(1,1,0,0,0,0,'2025-02-06 17:28:48','2025-06-17 06:58:35'),
(2,2,0,0,0,0,'2024-08-25 15:31:53','2025-06-17 06:58:35'),
(3,3,0,0,0,0,'2024-08-23 06:23:43','2025-06-17 06:58:35'),
(4,4,0,0,0,0,'2024-08-23 06:23:45','2025-06-17 06:58:35'),
(5,5,0,0,0,0,'2024-08-23 06:23:48','2025-06-17 06:58:35'),
(6,6,0,0,0,0,'2024-10-31 16:49:23','2025-06-17 06:58:35'),
(7,7,0,0,0,0,'2024-08-26 08:04:47','2025-06-17 06:58:35');
/*!40000 ALTER TABLE `check_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `desided_tasks_teams`
--

DROP TABLE IF EXISTS `desided_tasks_teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `desided_tasks_teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `tasks_id` bigint unsigned NOT NULL,
  `StyleTask` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `desided_tasks_teams`
--

LOCK TABLES `desided_tasks_teams` WRITE;
/*!40000 ALTER TABLE `desided_tasks_teams` DISABLE KEYS */;
/*!40000 ALTER TABLE `desided_tasks_teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'2014_10_12_000000_create_users_table',1),
(2,'2019_12_14_000001_create_personal_access_tokens_table',1),
(3,'2024_08_11_040726_create_tasks_table',1),
(4,'2024_08_11_040739_create_admins_table',1),
(5,'2024_08_11_040855_create_info_tasks_table',1),
(6,'2024_08_11_040915_create_check_tasks_table',1),
(7,'2024_08_11_040926_create_solved_tasks_table',1),
(8,'2024_08_22_043055_create_desided_tasks_teams_table',2),
(9,'2024_09_08_095122_create_settings_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
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
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `sessions` VALUES
('4bzO2AarBLxgeCobr68bkGt2mKbBbLowgug9FmTK',NULL,'192.168.1.17','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSVJGWGdGeExXTkNYVDRrRGNWa2F6N3FXeEJ1YnZWWE9xVUw0Zk1GSyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNDoiaHR0cDovLzE5Mi4xNjguMS4xNy9Ib21lIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly8xOTIuMTY4LjEuMTcvSG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1750163792),
('94h2IEwN2VMJMnxWfbh8OD4bLba0VtvRogXXBEb2',NULL,'192.168.1.17','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTzMxcWNsMFNmTTZTcTRVT0ZkeHJ0eVR1OVJWcnpOS25lU0xidkc0SCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTk6Imh0dHA6Ly8xOTIuMTY4LjEuMTciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1750163793),
('Lyaeei3Uh6FhHQauQp3vqzlxVmMDpvhttaxbdLbj',NULL,'192.168.1.17','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOVRmbExNMlRCOHQ0UTVFcDZIUmxGN3AxcGx6MjRvYmY5UnkxZkg2cCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly8xOTIuMTY4LjEuMTcvQXV0aCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1750163793),
('ngSxweSbMimgpQmrhTHTJEbsl8vWbfkR26xEgrsQ',7,'192.168.1.17','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSm9pbkV1SVY3dnM1MXg3czQ3ZERSMVVWVWQxeXZqSWh1TGd3a05kOCI7czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI0OiJodHRwOi8vMTkyLjE2OC4xLjE3L0hvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo3O30=',1750163903);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solved_tasks`
--

DROP TABLE IF EXISTS `solved_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `solved_tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `tasks_id` bigint unsigned NOT NULL,
  `price` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solved_tasks`
--

LOCK TABLES `solved_tasks` WRITE;
/*!40000 ALTER TABLE `solved_tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `solved_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `complexity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `FILES` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `web_port` int DEFAULT NULL,
  `db_port` int DEFAULT NULL,
  `web_directory` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `solved` double(8,2) NOT NULL,
  `price` double(8,2) NOT NULL,
  `oldprice` double(8,2) NOT NULL,
  `flag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `decide` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES
(1,'SeQueL','web','medium','В далекой деревне жил один умелый программист. Он был известен своими навыками в работе с базами данных, разработкой сайтов и часто помогал местным жителям с их проблемами. Однажды, к нему обратился торговец, утверждая, что его сайт был взломан.Он решил помочь и начал исследовать...<br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4001\" target=\"_blank\">SeQueL | WEB</a>',NULL,NULL,NULL,NULL,0.00,1000.00,1000.00,'school{SGFja2luZyB5b3VyIHdheSB0byBncmVhdG5lc3M=}',NULL,'2024-08-20 05:41:45','2025-06-15 09:44:48'),
(2,'ReCcE','web','hard','В древние времена, когда магия и технологии переплетались в удивительном союзе, жил волшебник по имени Эдмунд. Он обладал уникальным даром - способностью управлять магическими силами с помощью команд.<br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4002\" target=\"_blank\">ReCcE | WEB</a>',NULL,NULL,NULL,NULL,0.00,1000.00,1000.00,'school{SGFjayB5b3VyIHdheSB0byBzdWNjZXNz}',NULL,'2024-08-20 05:42:18','2025-02-07 15:07:53'),
(3,'Auth','web','easy','В далекой деревне жила добрая ведьма по имени Элиза. Она хранила секреты магии и помогала жителям своей деревни в трудные времена. Однажды, когда в деревне начались странные происшествия, все обратились к Элизе за помощью.Ведьма поняла, что причиной всех бед стал злой тролль, который заблокировал доступ к сокровищам, спрятанным в горах. Элиза решила отправиться на поиски тролля и разгадать его загадки.С помощью своих знаний магии и дружелюбного общения с природой, Элиза смогла найти тролля и победить его с помощью заклинания: 6W2I9K9I...<br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4003\" target=\"_blank\">Login Form | WEB</a>',NULL,NULL,NULL,NULL,0.00,1000.00,1000.00,'school{schoolGigglesAndGiggles}',NULL,'2024-08-20 05:42:34','2025-06-01 05:27:00'),
(4,'TheCube','joy','medium','В далеком королевстве жил мудрый старец по имени Артур. Он был известен своими удивительными способностями собирать сложные головоломки. Однажды, в королевстве появился загадочный кубик размером 5х5х5, который никто не мог собрать.(В настройках выбрать 5х5х5) <br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4004\" target=\"_blank\">The Cube | WEB</a>',NULL,NULL,NULL,NULL,0.00,1000.00,1000.00,'school{Hacking_is_life}',NULL,'2024-08-20 05:43:29','2025-02-07 15:07:53'),
(5,'FeedBack','web','hard','В древние времена в лесу стоял древний храм, в котором хранился магический артефакт – кристалл бесконечной мудрости. Легенда гласила, что тот, кто сможет проникнуть внутрь храма и дотронуться до кристалла, получит неограниченные знания и силу. А имя этому артефакту flag.php...<br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4005\" target=\"_blank\">FeedBack | WEB</a>',NULL,NULL,NULL,NULL,0.00,1000.00,1000.00,'school{HackerIsVirtuosoOfDigitalMysticism}',NULL,'2024-08-20 05:47:38','2025-02-07 15:07:53'),
(6,'DirTraver','web','hard','В древние времена в маленькой деревне жила девушка по имени Лилия. Она была известна своими невероятными способностями к путешествиям в параллельные миры и возможностью видеть то, что другие не могли.Однажды Лилия услышала о загадочном месте под названием \"DirTraver\"... <br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4006\" target=\"_blank\">DirTraver | WEB</a>',NULL,NULL,NULL,NULL,0.00,1000.00,1000.00,'school{AltayCTFisFun2024AltSTU}',NULL,'2024-08-20 05:47:59','2025-02-07 15:07:53'),
(7,'Stolen-Session','web','hard','Злоумышленники взламывая сайт похитили токен одного из пользователей, благодаря отслеживанию пакетов мы смогли предотвратить их атаку, но потеряли токен в трафике...<br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4007\" target=\"_blank\">Stolen-Session | WEB</a>','dd6919412fbbeba59f95d4cea66922dc.pcapng;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{JohnDraperistheFirstHacker}',NULL,'2024-10-13 16:57:21','2025-02-07 15:07:53'),
(8,'Battleship','crypto','hard','Вы работаете на Военно-морской базе страны N, вам необходимо потопить товарные корабли. Ваша команда перехватила сигналы перемещения кораблей, однако никто не может их расшифровать, может у вас получится.<br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4008\" target=\"_blank\">Battleship</a>\r\n<br>Формат флага school{A-Z0-9}',NULL,NULL,NULL,NULL,0.00,1000.00,1000.00,'SCHOOL{SIPHERSFORSHIPS}',NULL,'2024-10-13 17:01:58','2025-02-07 15:07:53'),
(9,'AircraftGame','joy','medium','Окунитесь в атмосферу космической битвы и встретьтесь лицом к лицу с космическими захватчиками. Став легендой космоса вы можете быть вознаграждены.','a3323703dcd24bf43fc6359f8bb8ebba.zip;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{gamer_is_hacker}',NULL,'2024-10-31 16:30:05','2025-02-07 15:07:53'),
(10,'LecLab','stegano','easy','Я выполнил лабораторную работу, но не знаю всё ли с ней в порядке, посмотрите пожалуйста.','3f810dd20eeee2111660e23660b4e53c.pptx;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{Metadata_The_Easy_Base64_Flag_In_mp3.file}',NULL,'2024-10-31 16:31:12','2025-02-07 15:07:53'),
(11,'The Mystery of the Lost Pizza','stegano','easy','<p align=\"justify\"> &emsp;&emsp; Много лет назад, в Нью-Йорке, процветала легенда о потерянной пицце. Группа легендарных черепашек-ниндзя обладала картой, указывающей на местоположение этой великой пиццы, которая, как говорили, имела волшебную силу, способную придать невероятную силу и мудрость тому, кто съест ее.</p>\r\n<p align=\"justify\"> &emsp;&emsp; Однако, когда черепашек-ниндзя отправились на поиски этой потерянной пиццы, им пришлось столкнуться с темными силами, желающими завладеть ее магией. В поединке с зловещими хакерами, картография, указывающая на местонахождение пиццы, была повреждена и закодирована в специальном файле. Теперь Черепашкам Ниндзя предстоит восстановить карту, расшифровать ее содержимое и снова найти потерянную пиццу, прежде чем темные силы успеют сделать это.</p>','a508a60912cfbd08e2c7661c07e3f503.;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{m1chelan9el0}',NULL,'2024-10-31 16:33:26','2025-02-07 15:07:53'),
(12,'tydysh','misc','medium','<p align=\"justify\"> &emsp;&emsp; В \"Фиксополисе\" существовал проект, созданный великим Профессором Чудаковым. Однако он внезапно исчез без следа. Легенда гласит, что Профессор спрятал его, зашифровав. Только герои, расшифровав тайны Фиксиков, смогут найти и восстановить проект, прежде чем он окажется в руках зла.</p>\r\n<p>Формат флага: school{A-Z, a-z, 0-1}</p>','5cfdf3fc714b893603534c1cb875495b.jpg;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{d1m-d1mych}',NULL,'2024-10-31 16:35:33','2025-02-07 15:07:53'),
(13,'the song of flag','stegano','medium','Существует древняя песня, исполненная мудрым бардом, в которой спрятано тайное послание. Это послание содержит ключ к скрытому сокровищу, которое ждет того, кто сможет его разгадать. Только тот, кто улавливает каждое слово и звук, сможет расшифровать его и найти путь к сокровищу.<br><br>\\r\\nФормат флага: school{flag}','796b6f67aed45ac9ef14e030f4c1102f.mp3;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{Y_a_quelquun?}',NULL,'2024-10-31 16:36:47','2025-02-07 15:07:53'),
(14,'Sons lang','crypto','medium','Мой сын придумал свой язык, когда я спросил как общаться на этом языке, он сказал: «Назови мне пароль, который я написал тут, и я тебе расскажу как на нем общаться»,- и протянул этот лист мне. Помогите мне решить этот шифр.<br>\r\nФормат флага school{a-z0-9}','9eab788e3dfe18c77a9efc68d8a03fe4.pdf;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{skynet}',NULL,'2024-10-31 16:38:55','2025-02-07 15:07:53'),
(15,'Word spell','crypto','hard','В древние времена жил очень мудрый и одинокий король, он был самым богатым человеком в мире, а все его богатство лежало в волшебном сундуке, чтобы его открыть, надо произнести слово-заклинание. Когда король стал очень старым, встал вопрос о наследнике престола, на что король ответил: «Наследником станет тот, кто откроет мой сундук». Король умер, а сундук так и не был открыт. Много лет спустя люди поняли, что пароль хранится в тексте его последней книги, но он написал ее не обычным способом.<br>\r\n\r\nФормат флага school{a-z0-9}','1e6b9a59e566079303816970dfea0451.txt;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{analysis}',NULL,'2024-10-31 16:40:21','2025-02-07 15:07:53'),
(16,'Questroom','crypto','easy','Вопрошатель – всемирно известный мастер головоломок позвал сто человек для решения его очередной загадки. Вы собрались в большой комнате с сотней ящиков, где Вопрошатель каждому дал порядковый номер и сказал: «Первый номер откроет все шкафчики, второй номер закроет каждый второй шкафчик, третий номер откроет или закроет каждый третий шкафчик. Кто первый назовет мне номера оставшихся открытых шкафчиков – победит». После этих слов вы подошли к Вопрошателю и сказали, что знаете номера открытых ящиков.<br>\r\n\r\nФормат флага school{0-9}',NULL,NULL,NULL,NULL,0.00,1000.00,1000.00,'school{149162536496481100}',NULL,'2024-10-31 16:41:20','2025-02-07 15:07:53'),
(17,'Puzzlature','osint','medium','Я сказал другу почистить жесткий диск и он постирал его в стиральной машинке. Теперь он жалуется, что у него ничего не запускается, говорит: «Всё поперемешалось, а собрать я это не могу»','b71fc9f4e100b0456273419aae971d3d.zip;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{signacoola}',NULL,'2024-10-31 16:58:06','2025-02-07 15:07:53'),
(18,'Square№9¾','crypto','easy','Вы видели как мальчик со шрамом забежал в стену, подойдя к ней вы поняли,\r\nчто он попал в нее, сказав волшебое слово, так же вы увидели надпись: bwkls->beach,\r\nрисунок и это странное слово: uylwjqrkdw<br>\r\n\r\nФормат флага school{a-z}','d4b0c20a15f53e60aa954c944700eb77.png;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{nicesquare}',NULL,'2024-10-31 17:02:58','2025-02-07 15:07:53'),
(19,'Salad','crypto','medium','Столовая saladsCTF - ваш конкурент - стала продавать очень вкусный салат,что забирает у вас клиентов. Вы захотели узнать рецепт этого салата, но поняли, что от обычного его отличает наличие \"секретного ингредеента\". Вы купили этот салат и решили разгадать его \"секрет\".<br>\r\nФормат флага school{A-Z0-9}','629d2fa19e761020ccffdbd38e394623.zip;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{AB0BAA155A51}',NULL,'2024-10-31 17:08:21','2025-02-07 15:07:53'),
(20,'kaChow','stegano','hard','В мире Радиатора существует древний обычай гадать на кофейной гуще для предсказания будущего. Однако однажды возникла новая идея: гадание на бензине. Верят, что расплывчатые разводы бензина могут тоже скрывать тайны и предсказывать судьбу.\r\nТеперь мастерство гадания на бензине стало популярным искусством среди жителей Радиатора, и каждый, кто освоит его технику, может узнать удивительные предсказания и открыть новые горизонты будущего.<br>\r\n\r\nФормат флага: school{A-Z, a-z, 0-1}','320eb54bed20c9883782c8bb2233ec27.rar;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{flag_is_going_to_do_ka_chow}',NULL,'2024-10-31 17:09:54','2025-02-07 15:07:53'),
(21,'oslik {I, A}','stegano','easy','Во время шпионской операции, агенты использовали басню для сокрытия секретной информации. Ваша миссия - расколоть шифр и раскрыть тайну.<br>\r\nФормат флага: school{A-Z, a-z, 0-1,and \"?\"}','0e4bd0f0c1f1c23cccd37a8b3a6cd367.docx;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{KRYLOV?}',NULL,'2024-10-31 17:11:14','2025-02-07 15:07:53'),
(22,'staff','stegano','medium','Легендарный композитор закодировал свою последнюю мелодию в нотах, чтобы защитить ее от непрошеных слушателей. Чтобы разгадать секрет, нужно проявить музыкальность и логику.<br>\r\nФормат флага: school{A-Z, a-z, 0-1}','118cb7da02c0859281fa28d7f9a60f34.zip;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{9Rh0ven}',NULL,'2024-10-31 17:12:15','2025-02-07 15:07:53'),
(23,'BossFighting','forensic','medium','Мой босс очень жестокий человек. Какое-то время назад я скачал с сетевого диска компании 2 файла, в одном находился пароль, а в другом секретная информация компании. Файлы я успешно утерял... Если я их не восстановлю, мой босс меня убъёт... К счастью у меня остался сетевой трафик, помоги мне, пожалуйста...','cc13ba8afd3534c3f806521dd68ba607.pcapng;',NULL,NULL,NULL,0.00,1000.00,1000.00,'shool{My_boss_Is_Bad}',NULL,'2024-10-31 17:13:09','2025-02-07 15:07:53'),
(24,'Not a pirate, but a hunter','forensic','easy','Не так давно я занимался \"добычей\" игр на всем известном ресурсе, но почему-то моё устройство перестало воспринимать доменные адреса. Друг поделился трафиком и сказал, что там можно найти ip адресс, не поможешь?<br>\r\nФормат флага : shool{ip}','c9477176202692ddc1ab8353417303e7.pcapng;',NULL,NULL,NULL,0.00,1000.00,1000.00,'shool{81.91.178.146}',NULL,'2024-10-31 17:16:28','2025-02-07 15:07:53'),
(25,'Petremyach','osint','easy','Поп Петр любитель поесть. В начале воскресной службы он куда-то пропал, охранник на входе говорит, что Отец бормотал про какой-то перемяч. Найдите где он может быть.<br>\r\nФормат флага: school{A-Z, a-z, 0-1}','546d33f0d8ce808616116149a614dfc4.jpg;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{PETRFOOD}',NULL,'2024-10-31 17:19:51','2025-02-07 15:07:53'),
(26,'Shnuk','osint','easy','Паучок не очень хотел встречаться с людьми, но он очень хотел бы узнать кто его озвучивает.<br>\r\nФормат флага: school{NameSurname}','cdfae0e560b9a90be4d2059372c48953.mp4;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{DincerCekmez}',NULL,'2024-10-31 17:32:38','2025-02-07 15:07:53'),
(27,'Old','osint','medium','<a href=\"https://www.liverpoolecho.co.uk/news/liverpool-news/landmark-building-bought-multi-million-22196475\" target=\"_blank\\\">Landmark building bought by multi million pound company - Liverpool Echo</a> Около данного здания был замечен с деловым вопросом очень известный агент по недвижимости. Вот только ни фамилия, ни дата рождения его неизвестна. Известно лишь, что его зовут Тревор и он работает в одной компании со своим братом. Помогите найти полную дату рождения Тревора<br>\r\nФормат флага: school{day.month.year}',NULL,NULL,NULL,NULL,0.00,1000.00,1000.00,'school{12.01.1970}',NULL,'2024-10-31 17:34:26','2025-02-07 15:07:53'),
(28,'import substitution','osint','medium','Некий с ником taramtaramych представляется сотрудником очень известной компании «Пив Пав». Выясните информацию, которая поможет внедриться в данную компанию.<br>\r\nФормат флага: school{ID No}',NULL,NULL,NULL,NULL,0.00,1000.00,1000.00,'school{Taras-Macha-8chDY-5l7}',NULL,'2024-10-31 17:35:25','2025-02-07 15:07:53'),
(29,'Adrastei&#039;s…','reverse','hard','Вас пригласили на VIP вечеринку любителей обратной перемотки. Кодовое слово, что у вас спрашивают и будет флагом  <br>\r\nФормат флага: school{A-Z, a-z, 0-1}','336b7abf20c29950c9a3d513b92d0ebe.exe;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{YlAFRTLM44GC34oPDix7}',NULL,'2024-10-31 17:48:20','2025-02-07 15:07:53'),
(30,'…sister','reverse','easy','ООО «Гексагон» приглашает вас к себе на работу, но охранник без кодового слова не пускает<br>\r\nФормат флага:school{codeword}','5e635409c4f88692bc544d297b7ee6aa.zip;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{UPUPDDLRLRBA}',NULL,'2024-10-31 17:51:38','2025-02-07 15:07:53'),
(31,'&','reverse','hard','Мы отдали разработку кода на аутсорс программистам из индии. В результате буквы разбрелись по консоли. Поставьте их снова в красивый рядочек, что будет флажком<br>\r\nФормат флага: school{A-Z}','75bdd7638cf3a2ced902b6c9dd27dee2.zip;',NULL,NULL,NULL,0.00,1000.00,1000.00,'school{ACDJKBEILSFHMRTGNQUXOPVWY}',NULL,'2024-10-31 17:52:53','2025-02-07 15:07:53');
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `teamlogo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `GuestLogo` longtext COLLATE utf8mb4_unicode_ci,
  `players` int NOT NULL,
  `wherefrom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guest` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scores` double(8,2) NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Team A','$2y$10$f4mKEMdjppNZ/A05Ed0JdOlcHDGinf1On.s/xrUrgvTQZY7OCfxxq','StandartLogo.png','<svg version=\"1.0\" xmlns=\"http://www.w3.org/2000/svg\"\n     width=\"30.000000pt\" height=\"8.000000pt\" viewBox=\"0 0 1794.000000 512.000000\"\n     preserveAspectRatio=\"xMidYMid meet\">\n    <g transform=\"translate(0.000000,512.000000) scale(0.100000,-0.100000)\"\n       fill=\"#4169E1\" stroke=\"none\">\n        <path d=\"M0 2560 l0 -2560 8970 0 8970 0 0 2560 0 2560 -8970 0 -8970 0 0\n-2560z m3548 1901 c231 -35 502 -119 559 -173 87 -81 88 -142 5 -411 -51 -169\n-65 -204 -93 -234 -72 -75 -156 -83 -295 -27 -332 134 -813 137 -1118 6 -385\n-165 -593 -498 -612 -982 -17 -421 116 -771 372 -977 133 -107 289 -175 481\n-208 135 -24 488 -17 575 11 17 5 18 27 18 325 l0 319 -242 0 c-258 0 -302 6\n-356 47 -67 50 -72 75 -72 333 0 209 2 234 20 269 24 48 70 87 118 101 23 6\n250 10 620 10 642 0 623 2 690 -63 63 -61 62 -45 62 -938 0 -556 -3 -824 -11\n-860 -9 -39 -21 -62 -53 -94 -37 -37 -59 -47 -172 -83 -375 -120 -723 -170\n-1089 -159 -523 17 -908 153 -1245 442 -298 254 -503 668 -566 1140 -19 141\n-22 437 -5 570 43 332 159 645 328 885 87 122 291 327 413 412 286 202 615\n316 1045 362 104 11 494 -3 623 -23z m9032 4 c127 -19 233 -46 339 -85 212\n-78 281 -142 281 -259 0 -57 -118 -417 -155 -473 -34 -51 -95 -82 -165 -82\n-44 0 -74 9 -155 46 -163 75 -309 108 -475 108 -279 -1 -441 -123 -428 -324\n10 -166 124 -256 535 -425 293 -120 450 -208 603 -337 298 -252 427 -625 359\n-1039 -57 -343 -281 -641 -607 -804 -441 -221 -1138 -208 -1616 31 -106 53\n-142 81 -167 131 -36 70 -31 120 40 352 61 199 67 212 107 251 46 46 120 71\n174 59 17 -4 82 -33 143 -64 187 -95 357 -140 557 -148 145 -6 237 10 335 55\n151 71 214 191 193 370 -18 162 -165 280 -518 417 -192 75 -364 159 -480 236\n-106 70 -247 201 -317 294 -64 84 -128 222 -159 340 -23 86 -26 122 -27 250\n-1 227 44 394 154 571 184 297 531 496 953 544 100 12 372 4 496 -15z m-6995\n-28 c21 -12 53 -42 70 -65 l30 -44 6 -1141 c5 -1118 6 -1144 26 -1235 43 -188\n94 -297 178 -383 98 -101 189 -139 350 -146 176 -8 295 30 398 125 106 99 162\n218 209 445 7 35 13 432 17 1194 l6 1141 30 44 c17 23 49 53 70 65 39 23 45\n23 326 21 l286 -3 41 -28 c23 -15 52 -46 64 -70 l23 -42 3 -1015 c3 -1028 -2\n-1229 -34 -1415 -66 -381 -198 -653 -423 -868 -253 -242 -581 -357 -1014 -357\n-499 1 -852 165 -1109 517 -99 135 -203 382 -243 578 -49 238 -48 224 -52\n1402 -3 759 -1 1124 6 1151 16 58 51 103 100 129 43 22 50 23 320 23 267 0\n277 -1 316 -23z m4810 -19 c42 -23 81 -66 99 -110 13 -30 16 -81 16 -260 0\n-194 -3 -228 -19 -263 -26 -57 -79 -101 -141 -114 -34 -7 -247 -11 -630 -11\nl-580 0 0 -320 0 -320 558 0 c374 0 570 -4 597 -11 55 -15 119 -83 133 -142 7\n-27 12 -124 12 -232 0 -108 -5 -205 -12 -232 -14 -59 -78 -127 -133 -142 -27\n-7 -223 -11 -597 -11 l-558 0 0 -395 0 -394 648 -3 647 -3 47 -28 c29 -17 57\n-44 73 -71 25 -43 25 -44 25 -287 l0 -245 -29 -47 c-19 -28 -46 -55 -73 -69\nl-43 -23 -973 -3 c-637 -1 -986 1 -1011 8 -55 15 -103 50 -128 95 l-23 40 0\n1735 0 1735 23 39 c12 22 34 48 48 58 65 49 41 48 1049 45 859 -2 948 -4 975\n-19z m6002 -7 c24 -14 55 -42 70 -64 l28 -40 3 -236 c4 -255 -1 -289 -45 -342\n-58 -68 -59 -69 -540 -69 l-433 0 0 -1405 c0 -1540 4 -1443 -58 -1508 -57 -60\n-65 -62 -338 -65 -158 -2 -266 0 -291 7 -59 16 -105 51 -133 101 l-25 45 -3\n1413 -2 1412 -428 0 c-394 0 -430 2 -467 19 -48 22 -79 54 -100 103 -12 30\n-15 83 -15 270 0 216 1 235 21 276 23 47 77 89 131 102 18 5 607 7 1308 7\nl1275 -2 42 -24z\"/>\n    </g>\n</svg>\n',8,'МАОУ &quot;СОШ №132&quot;  им. Н.М. Малахова г. Барнаул','Yes',0.00,NULL,'2025-02-06 17:28:48','2025-06-17 06:58:35'),
(2,'Team B','$2y$10$Oi6BvZ41h879Z8TXG4oj5.xtFMvNs.9Qc/qpSXcYSftwMB.xx1ISC','StandartLogo.png','<svg version=\"1.0\" xmlns=\"http://www.w3.org/2000/svg\"\n     width=\"30.000000pt\" height=\"8.000000pt\" viewBox=\"0 0 1794.000000 512.000000\"\n     preserveAspectRatio=\"xMidYMid meet\">\n    <g transform=\"translate(0.000000,512.000000) scale(0.100000,-0.100000)\"\n       fill=\"#4169E1\" stroke=\"none\">\n        <path d=\"M0 2560 l0 -2560 8970 0 8970 0 0 2560 0 2560 -8970 0 -8970 0 0\n-2560z m3548 1901 c231 -35 502 -119 559 -173 87 -81 88 -142 5 -411 -51 -169\n-65 -204 -93 -234 -72 -75 -156 -83 -295 -27 -332 134 -813 137 -1118 6 -385\n-165 -593 -498 -612 -982 -17 -421 116 -771 372 -977 133 -107 289 -175 481\n-208 135 -24 488 -17 575 11 17 5 18 27 18 325 l0 319 -242 0 c-258 0 -302 6\n-356 47 -67 50 -72 75 -72 333 0 209 2 234 20 269 24 48 70 87 118 101 23 6\n250 10 620 10 642 0 623 2 690 -63 63 -61 62 -45 62 -938 0 -556 -3 -824 -11\n-860 -9 -39 -21 -62 -53 -94 -37 -37 -59 -47 -172 -83 -375 -120 -723 -170\n-1089 -159 -523 17 -908 153 -1245 442 -298 254 -503 668 -566 1140 -19 141\n-22 437 -5 570 43 332 159 645 328 885 87 122 291 327 413 412 286 202 615\n316 1045 362 104 11 494 -3 623 -23z m9032 4 c127 -19 233 -46 339 -85 212\n-78 281 -142 281 -259 0 -57 -118 -417 -155 -473 -34 -51 -95 -82 -165 -82\n-44 0 -74 9 -155 46 -163 75 -309 108 -475 108 -279 -1 -441 -123 -428 -324\n10 -166 124 -256 535 -425 293 -120 450 -208 603 -337 298 -252 427 -625 359\n-1039 -57 -343 -281 -641 -607 -804 -441 -221 -1138 -208 -1616 31 -106 53\n-142 81 -167 131 -36 70 -31 120 40 352 61 199 67 212 107 251 46 46 120 71\n174 59 17 -4 82 -33 143 -64 187 -95 357 -140 557 -148 145 -6 237 10 335 55\n151 71 214 191 193 370 -18 162 -165 280 -518 417 -192 75 -364 159 -480 236\n-106 70 -247 201 -317 294 -64 84 -128 222 -159 340 -23 86 -26 122 -27 250\n-1 227 44 394 154 571 184 297 531 496 953 544 100 12 372 4 496 -15z m-6995\n-28 c21 -12 53 -42 70 -65 l30 -44 6 -1141 c5 -1118 6 -1144 26 -1235 43 -188\n94 -297 178 -383 98 -101 189 -139 350 -146 176 -8 295 30 398 125 106 99 162\n218 209 445 7 35 13 432 17 1194 l6 1141 30 44 c17 23 49 53 70 65 39 23 45\n23 326 21 l286 -3 41 -28 c23 -15 52 -46 64 -70 l23 -42 3 -1015 c3 -1028 -2\n-1229 -34 -1415 -66 -381 -198 -653 -423 -868 -253 -242 -581 -357 -1014 -357\n-499 1 -852 165 -1109 517 -99 135 -203 382 -243 578 -49 238 -48 224 -52\n1402 -3 759 -1 1124 6 1151 16 58 51 103 100 129 43 22 50 23 320 23 267 0\n277 -1 316 -23z m4810 -19 c42 -23 81 -66 99 -110 13 -30 16 -81 16 -260 0\n-194 -3 -228 -19 -263 -26 -57 -79 -101 -141 -114 -34 -7 -247 -11 -630 -11\nl-580 0 0 -320 0 -320 558 0 c374 0 570 -4 597 -11 55 -15 119 -83 133 -142 7\n-27 12 -124 12 -232 0 -108 -5 -205 -12 -232 -14 -59 -78 -127 -133 -142 -27\n-7 -223 -11 -597 -11 l-558 0 0 -395 0 -394 648 -3 647 -3 47 -28 c29 -17 57\n-44 73 -71 25 -43 25 -44 25 -287 l0 -245 -29 -47 c-19 -28 -46 -55 -73 -69\nl-43 -23 -973 -3 c-637 -1 -986 1 -1011 8 -55 15 -103 50 -128 95 l-23 40 0\n1735 0 1735 23 39 c12 22 34 48 48 58 65 49 41 48 1049 45 859 -2 948 -4 975\n-19z m6002 -7 c24 -14 55 -42 70 -64 l28 -40 3 -236 c4 -255 -1 -289 -45 -342\n-58 -68 -59 -69 -540 -69 l-433 0 0 -1405 c0 -1540 4 -1443 -58 -1508 -57 -60\n-65 -62 -338 -65 -158 -2 -266 0 -291 7 -59 16 -105 51 -133 101 l-25 45 -3\n1413 -2 1412 -428 0 c-394 0 -430 2 -467 19 -48 22 -79 54 -100 103 -12 30\n-15 83 -15 270 0 216 1 235 21 276 23 47 77 89 131 102 18 5 607 7 1308 7\nl1275 -2 42 -24z\"/>\n    </g>\n</svg>\n',5,'МАОУ &quot;СОШ №136&quot;;','Yes',0.00,NULL,'2024-08-25 15:31:53','2025-06-17 06:58:35'),
(3,'Team C','$2y$10$Rb47S0EEH4cbGTU1anTt4.s6VlzEN89ONhdekLmRwb9U8xbXxCLXa','StandartLogo.png','<svg version=\"1.0\" xmlns=\"http://www.w3.org/2000/svg\"\n     width=\"30.000000pt\" height=\"8.000000pt\" viewBox=\"0 0 1794.000000 512.000000\"\n     preserveAspectRatio=\"xMidYMid meet\">\n    <g transform=\"translate(0.000000,512.000000) scale(0.100000,-0.100000)\"\n       fill=\"#4169E1\" stroke=\"none\">\n        <path d=\"M0 2560 l0 -2560 8970 0 8970 0 0 2560 0 2560 -8970 0 -8970 0 0\n-2560z m3548 1901 c231 -35 502 -119 559 -173 87 -81 88 -142 5 -411 -51 -169\n-65 -204 -93 -234 -72 -75 -156 -83 -295 -27 -332 134 -813 137 -1118 6 -385\n-165 -593 -498 -612 -982 -17 -421 116 -771 372 -977 133 -107 289 -175 481\n-208 135 -24 488 -17 575 11 17 5 18 27 18 325 l0 319 -242 0 c-258 0 -302 6\n-356 47 -67 50 -72 75 -72 333 0 209 2 234 20 269 24 48 70 87 118 101 23 6\n250 10 620 10 642 0 623 2 690 -63 63 -61 62 -45 62 -938 0 -556 -3 -824 -11\n-860 -9 -39 -21 -62 -53 -94 -37 -37 -59 -47 -172 -83 -375 -120 -723 -170\n-1089 -159 -523 17 -908 153 -1245 442 -298 254 -503 668 -566 1140 -19 141\n-22 437 -5 570 43 332 159 645 328 885 87 122 291 327 413 412 286 202 615\n316 1045 362 104 11 494 -3 623 -23z m9032 4 c127 -19 233 -46 339 -85 212\n-78 281 -142 281 -259 0 -57 -118 -417 -155 -473 -34 -51 -95 -82 -165 -82\n-44 0 -74 9 -155 46 -163 75 -309 108 -475 108 -279 -1 -441 -123 -428 -324\n10 -166 124 -256 535 -425 293 -120 450 -208 603 -337 298 -252 427 -625 359\n-1039 -57 -343 -281 -641 -607 -804 -441 -221 -1138 -208 -1616 31 -106 53\n-142 81 -167 131 -36 70 -31 120 40 352 61 199 67 212 107 251 46 46 120 71\n174 59 17 -4 82 -33 143 -64 187 -95 357 -140 557 -148 145 -6 237 10 335 55\n151 71 214 191 193 370 -18 162 -165 280 -518 417 -192 75 -364 159 -480 236\n-106 70 -247 201 -317 294 -64 84 -128 222 -159 340 -23 86 -26 122 -27 250\n-1 227 44 394 154 571 184 297 531 496 953 544 100 12 372 4 496 -15z m-6995\n-28 c21 -12 53 -42 70 -65 l30 -44 6 -1141 c5 -1118 6 -1144 26 -1235 43 -188\n94 -297 178 -383 98 -101 189 -139 350 -146 176 -8 295 30 398 125 106 99 162\n218 209 445 7 35 13 432 17 1194 l6 1141 30 44 c17 23 49 53 70 65 39 23 45\n23 326 21 l286 -3 41 -28 c23 -15 52 -46 64 -70 l23 -42 3 -1015 c3 -1028 -2\n-1229 -34 -1415 -66 -381 -198 -653 -423 -868 -253 -242 -581 -357 -1014 -357\n-499 1 -852 165 -1109 517 -99 135 -203 382 -243 578 -49 238 -48 224 -52\n1402 -3 759 -1 1124 6 1151 16 58 51 103 100 129 43 22 50 23 320 23 267 0\n277 -1 316 -23z m4810 -19 c42 -23 81 -66 99 -110 13 -30 16 -81 16 -260 0\n-194 -3 -228 -19 -263 -26 -57 -79 -101 -141 -114 -34 -7 -247 -11 -630 -11\nl-580 0 0 -320 0 -320 558 0 c374 0 570 -4 597 -11 55 -15 119 -83 133 -142 7\n-27 12 -124 12 -232 0 -108 -5 -205 -12 -232 -14 -59 -78 -127 -133 -142 -27\n-7 -223 -11 -597 -11 l-558 0 0 -395 0 -394 648 -3 647 -3 47 -28 c29 -17 57\n-44 73 -71 25 -43 25 -44 25 -287 l0 -245 -29 -47 c-19 -28 -46 -55 -73 -69\nl-43 -23 -973 -3 c-637 -1 -986 1 -1011 8 -55 15 -103 50 -128 95 l-23 40 0\n1735 0 1735 23 39 c12 22 34 48 48 58 65 49 41 48 1049 45 859 -2 948 -4 975\n-19z m6002 -7 c24 -14 55 -42 70 -64 l28 -40 3 -236 c4 -255 -1 -289 -45 -342\n-58 -68 -59 -69 -540 -69 l-433 0 0 -1405 c0 -1540 4 -1443 -58 -1508 -57 -60\n-65 -62 -338 -65 -158 -2 -266 0 -291 7 -59 16 -105 51 -133 101 l-25 45 -3\n1413 -2 1412 -428 0 c-394 0 -430 2 -467 19 -48 22 -79 54 -100 103 -12 30\n-15 83 -15 270 0 216 1 235 21 276 23 47 77 89 131 102 18 5 607 7 1308 7\nl1275 -2 42 -24z\"/>\n    </g>\n</svg>\n',5,'БОУ г. Омска &quot;Лицей № 149&quot;','Yes',0.00,NULL,'2024-08-23 06:23:43','2025-06-17 06:58:35'),
(4,'Team D','$2y$10$kvQGNE/lOftqi1UU3EnIHOgRcDd/tL/W6PNbdJ..lkHY1uoZFM2KW','StandartLogo.png','<svg version=\"1.0\" xmlns=\"http://www.w3.org/2000/svg\"\n     width=\"30.000000pt\" height=\"8.000000pt\" viewBox=\"0 0 1794.000000 512.000000\"\n     preserveAspectRatio=\"xMidYMid meet\">\n    <g transform=\"translate(0.000000,512.000000) scale(0.100000,-0.100000)\"\n       fill=\"#4169E1\" stroke=\"none\">\n        <path d=\"M0 2560 l0 -2560 8970 0 8970 0 0 2560 0 2560 -8970 0 -8970 0 0\n-2560z m3548 1901 c231 -35 502 -119 559 -173 87 -81 88 -142 5 -411 -51 -169\n-65 -204 -93 -234 -72 -75 -156 -83 -295 -27 -332 134 -813 137 -1118 6 -385\n-165 -593 -498 -612 -982 -17 -421 116 -771 372 -977 133 -107 289 -175 481\n-208 135 -24 488 -17 575 11 17 5 18 27 18 325 l0 319 -242 0 c-258 0 -302 6\n-356 47 -67 50 -72 75 -72 333 0 209 2 234 20 269 24 48 70 87 118 101 23 6\n250 10 620 10 642 0 623 2 690 -63 63 -61 62 -45 62 -938 0 -556 -3 -824 -11\n-860 -9 -39 -21 -62 -53 -94 -37 -37 -59 -47 -172 -83 -375 -120 -723 -170\n-1089 -159 -523 17 -908 153 -1245 442 -298 254 -503 668 -566 1140 -19 141\n-22 437 -5 570 43 332 159 645 328 885 87 122 291 327 413 412 286 202 615\n316 1045 362 104 11 494 -3 623 -23z m9032 4 c127 -19 233 -46 339 -85 212\n-78 281 -142 281 -259 0 -57 -118 -417 -155 -473 -34 -51 -95 -82 -165 -82\n-44 0 -74 9 -155 46 -163 75 -309 108 -475 108 -279 -1 -441 -123 -428 -324\n10 -166 124 -256 535 -425 293 -120 450 -208 603 -337 298 -252 427 -625 359\n-1039 -57 -343 -281 -641 -607 -804 -441 -221 -1138 -208 -1616 31 -106 53\n-142 81 -167 131 -36 70 -31 120 40 352 61 199 67 212 107 251 46 46 120 71\n174 59 17 -4 82 -33 143 -64 187 -95 357 -140 557 -148 145 -6 237 10 335 55\n151 71 214 191 193 370 -18 162 -165 280 -518 417 -192 75 -364 159 -480 236\n-106 70 -247 201 -317 294 -64 84 -128 222 -159 340 -23 86 -26 122 -27 250\n-1 227 44 394 154 571 184 297 531 496 953 544 100 12 372 4 496 -15z m-6995\n-28 c21 -12 53 -42 70 -65 l30 -44 6 -1141 c5 -1118 6 -1144 26 -1235 43 -188\n94 -297 178 -383 98 -101 189 -139 350 -146 176 -8 295 30 398 125 106 99 162\n218 209 445 7 35 13 432 17 1194 l6 1141 30 44 c17 23 49 53 70 65 39 23 45\n23 326 21 l286 -3 41 -28 c23 -15 52 -46 64 -70 l23 -42 3 -1015 c3 -1028 -2\n-1229 -34 -1415 -66 -381 -198 -653 -423 -868 -253 -242 -581 -357 -1014 -357\n-499 1 -852 165 -1109 517 -99 135 -203 382 -243 578 -49 238 -48 224 -52\n1402 -3 759 -1 1124 6 1151 16 58 51 103 100 129 43 22 50 23 320 23 267 0\n277 -1 316 -23z m4810 -19 c42 -23 81 -66 99 -110 13 -30 16 -81 16 -260 0\n-194 -3 -228 -19 -263 -26 -57 -79 -101 -141 -114 -34 -7 -247 -11 -630 -11\nl-580 0 0 -320 0 -320 558 0 c374 0 570 -4 597 -11 55 -15 119 -83 133 -142 7\n-27 12 -124 12 -232 0 -108 -5 -205 -12 -232 -14 -59 -78 -127 -133 -142 -27\n-7 -223 -11 -597 -11 l-558 0 0 -395 0 -394 648 -3 647 -3 47 -28 c29 -17 57\n-44 73 -71 25 -43 25 -44 25 -287 l0 -245 -29 -47 c-19 -28 -46 -55 -73 -69\nl-43 -23 -973 -3 c-637 -1 -986 1 -1011 8 -55 15 -103 50 -128 95 l-23 40 0\n1735 0 1735 23 39 c12 22 34 48 48 58 65 49 41 48 1049 45 859 -2 948 -4 975\n-19z m6002 -7 c24 -14 55 -42 70 -64 l28 -40 3 -236 c4 -255 -1 -289 -45 -342\n-58 -68 -59 -69 -540 -69 l-433 0 0 -1405 c0 -1540 4 -1443 -58 -1508 -57 -60\n-65 -62 -338 -65 -158 -2 -266 0 -291 7 -59 16 -105 51 -133 101 l-25 45 -3\n1413 -2 1412 -428 0 c-394 0 -430 2 -467 19 -48 22 -79 54 -100 103 -12 30\n-15 83 -15 270 0 216 1 235 21 276 23 47 77 89 131 102 18 5 607 7 1308 7\nl1275 -2 42 -24z\"/>\n    </g>\n</svg>\n',5,'МБОУ &quot;СОШ №49&quot;; г.Барнаул','Yes',0.00,NULL,'2024-08-23 06:23:45','2025-06-17 06:58:35'),
(5,'Team E','$2y$10$zPPOI2mCf8.PW4nuwudRoevgp2PGd/n1xLy8OogC.q0p.PiGDxNCu','StandartLogo.png','<svg version=\"1.0\" xmlns=\"http://www.w3.org/2000/svg\"\n     width=\"30.000000pt\" height=\"8.000000pt\" viewBox=\"0 0 1794.000000 512.000000\"\n     preserveAspectRatio=\"xMidYMid meet\">\n    <g transform=\"translate(0.000000,512.000000) scale(0.100000,-0.100000)\"\n       fill=\"#4169E1\" stroke=\"none\">\n        <path d=\"M0 2560 l0 -2560 8970 0 8970 0 0 2560 0 2560 -8970 0 -8970 0 0\n-2560z m3548 1901 c231 -35 502 -119 559 -173 87 -81 88 -142 5 -411 -51 -169\n-65 -204 -93 -234 -72 -75 -156 -83 -295 -27 -332 134 -813 137 -1118 6 -385\n-165 -593 -498 -612 -982 -17 -421 116 -771 372 -977 133 -107 289 -175 481\n-208 135 -24 488 -17 575 11 17 5 18 27 18 325 l0 319 -242 0 c-258 0 -302 6\n-356 47 -67 50 -72 75 -72 333 0 209 2 234 20 269 24 48 70 87 118 101 23 6\n250 10 620 10 642 0 623 2 690 -63 63 -61 62 -45 62 -938 0 -556 -3 -824 -11\n-860 -9 -39 -21 -62 -53 -94 -37 -37 -59 -47 -172 -83 -375 -120 -723 -170\n-1089 -159 -523 17 -908 153 -1245 442 -298 254 -503 668 -566 1140 -19 141\n-22 437 -5 570 43 332 159 645 328 885 87 122 291 327 413 412 286 202 615\n316 1045 362 104 11 494 -3 623 -23z m9032 4 c127 -19 233 -46 339 -85 212\n-78 281 -142 281 -259 0 -57 -118 -417 -155 -473 -34 -51 -95 -82 -165 -82\n-44 0 -74 9 -155 46 -163 75 -309 108 -475 108 -279 -1 -441 -123 -428 -324\n10 -166 124 -256 535 -425 293 -120 450 -208 603 -337 298 -252 427 -625 359\n-1039 -57 -343 -281 -641 -607 -804 -441 -221 -1138 -208 -1616 31 -106 53\n-142 81 -167 131 -36 70 -31 120 40 352 61 199 67 212 107 251 46 46 120 71\n174 59 17 -4 82 -33 143 -64 187 -95 357 -140 557 -148 145 -6 237 10 335 55\n151 71 214 191 193 370 -18 162 -165 280 -518 417 -192 75 -364 159 -480 236\n-106 70 -247 201 -317 294 -64 84 -128 222 -159 340 -23 86 -26 122 -27 250\n-1 227 44 394 154 571 184 297 531 496 953 544 100 12 372 4 496 -15z m-6995\n-28 c21 -12 53 -42 70 -65 l30 -44 6 -1141 c5 -1118 6 -1144 26 -1235 43 -188\n94 -297 178 -383 98 -101 189 -139 350 -146 176 -8 295 30 398 125 106 99 162\n218 209 445 7 35 13 432 17 1194 l6 1141 30 44 c17 23 49 53 70 65 39 23 45\n23 326 21 l286 -3 41 -28 c23 -15 52 -46 64 -70 l23 -42 3 -1015 c3 -1028 -2\n-1229 -34 -1415 -66 -381 -198 -653 -423 -868 -253 -242 -581 -357 -1014 -357\n-499 1 -852 165 -1109 517 -99 135 -203 382 -243 578 -49 238 -48 224 -52\n1402 -3 759 -1 1124 6 1151 16 58 51 103 100 129 43 22 50 23 320 23 267 0\n277 -1 316 -23z m4810 -19 c42 -23 81 -66 99 -110 13 -30 16 -81 16 -260 0\n-194 -3 -228 -19 -263 -26 -57 -79 -101 -141 -114 -34 -7 -247 -11 -630 -11\nl-580 0 0 -320 0 -320 558 0 c374 0 570 -4 597 -11 55 -15 119 -83 133 -142 7\n-27 12 -124 12 -232 0 -108 -5 -205 -12 -232 -14 -59 -78 -127 -133 -142 -27\n-7 -223 -11 -597 -11 l-558 0 0 -395 0 -394 648 -3 647 -3 47 -28 c29 -17 57\n-44 73 -71 25 -43 25 -44 25 -287 l0 -245 -29 -47 c-19 -28 -46 -55 -73 -69\nl-43 -23 -973 -3 c-637 -1 -986 1 -1011 8 -55 15 -103 50 -128 95 l-23 40 0\n1735 0 1735 23 39 c12 22 34 48 48 58 65 49 41 48 1049 45 859 -2 948 -4 975\n-19z m6002 -7 c24 -14 55 -42 70 -64 l28 -40 3 -236 c4 -255 -1 -289 -45 -342\n-58 -68 -59 -69 -540 -69 l-433 0 0 -1405 c0 -1540 4 -1443 -58 -1508 -57 -60\n-65 -62 -338 -65 -158 -2 -266 0 -291 7 -59 16 -105 51 -133 101 l-25 45 -3\n1413 -2 1412 -428 0 c-394 0 -430 2 -467 19 -48 22 -79 54 -100 103 -12 30\n-15 83 -15 270 0 216 1 235 21 276 23 47 77 89 131 102 18 5 607 7 1308 7\nl1275 -2 42 -24z\"/>\n    </g>\n</svg>\n',5,'МАОУ «СОШ №134» г. Барнаул','Yes',0.00,NULL,'2024-08-23 06:23:48','2025-06-17 06:58:35'),
(6,'SharLike','$2y$10$5nqcESyPJcvYjjWW96IuouHGws7Qj2/V/Y6Ox.4ASc7D17pq5JmVW','1730425369_like-button.png','',7,'Алтайский государственный технический университет им. И. И. Ползунова','No',0.00,NULL,'2024-10-31 16:49:23','2025-06-17 06:58:35'),
(7,'Жуколовы','$2y$12$yA3TgrKZ.BfgBXg40UavbOfCPId9sONJfTdxsbCaA2dAQS5lBoLcm','1727521723_New_Logo_Жуколовы-5-full.png','',6,'Алтайский государственный технический университет им. И. И. Ползунова','No',0.00,NULL,'2024-08-26 08:04:47','2025-06-17 06:58:35');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-17 12:41:40
