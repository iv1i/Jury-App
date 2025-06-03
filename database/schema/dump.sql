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
(1,'admin','$2y$12$wNjxpKTBy79T8KpW4NZo1uckoqlO/i.wRXYZtXR6ZGAPOMIGB.n5.','NjT1P3kYXxIIB6SMUbzVQ7MmUZkGM3wsBpCuEg100GWosjNjvKrpspP0tm7F','2024-08-20 05:03:32','2025-06-01 08:43:38');
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
(1,1,0,0,0,0,'2025-02-06 17:28:48','2025-06-01 05:27:00'),
(2,2,0,0,0,0,'2024-08-25 15:31:53','2025-06-01 05:27:00'),
(3,3,0,0,0,0,'2024-08-23 06:23:43','2025-06-01 05:27:00'),
(4,4,0,0,0,0,'2024-08-23 06:23:45','2025-06-01 05:27:00'),
(5,5,0,0,0,0,'2024-08-23 06:23:48','2025-06-01 05:27:00'),
(6,6,0,0,0,0,'2024-10-31 16:49:23','2025-06-01 05:27:00'),
(7,7,1,0,1,0,'2024-08-26 08:04:47','2025-06-01 08:43:03');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `desided_tasks_teams`
--

LOCK TABLES `desided_tasks_teams` WRITE;
/*!40000 ALTER TABLE `desided_tasks_teams` DISABLE KEYS */;
INSERT INTO `desided_tasks_teams` VALUES
(1,7,1,'<div id=\"medium\" style=\"background-color: #0086d3; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px\"> <b></b> </div>','2025-06-01 08:43:03','2025-06-01 08:43:03');
/*!40000 ALTER TABLE `desided_tasks_teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `info_tasks`
--

DROP TABLE IF EXISTS `info_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `info_tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sumary` bigint NOT NULL,
  `easy` int NOT NULL,
  `medium` int NOT NULL,
  `hard` int NOT NULL,
  `admin` int NOT NULL,
  `recon` int NOT NULL,
  `crypto` int NOT NULL,
  `stegano` int NOT NULL,
  `ppc` int NOT NULL,
  `pwn` int NOT NULL,
  `web` int NOT NULL,
  `forensic` int NOT NULL,
  `joy` int NOT NULL,
  `misc` int NOT NULL,
  `osint` int NOT NULL,
  `reverse` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `info_tasks`
--

LOCK TABLES `info_tasks` WRITE;
/*!40000 ALTER TABLE `info_tasks` DISABLE KEYS */;
INSERT INTO `info_tasks` VALUES
(1,31,10,12,9,0,0,6,6,0,0,6,2,2,1,5,3,NULL,'2025-02-06 11:21:14');
/*!40000 ALTER TABLE `info_tasks` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES
('F8e41V8QFp6WrLpXztabbWNRGOgjTXslAJZeE8pp',7,'192.168.1.17','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQXphTUZUZHlHVFNOZnhjRm1qQ3N3dnFOVGNodnBaVGpjeWZGRUVuWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xOTIuMTY4LjEuMTcvQWRtaW4vU2V0dGluZ3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Nzt9',1748767396);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Rules` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Projector` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Home` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Scoreboard` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Statistics` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Logout` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Rule` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES
(1,'yes','yes','yes','yes','yes','yes','<div class=\"dec\">\r\n    <p style=\"text-indent: 1vw; padding-left: 20px; padding-right: 20px\">\r\n        1. Длительность соревнований - 6 часов + час перерыва на обед.\r\n        Соревнования могут быть продлены по решению жюри.\r\n    </p>\r\n    <p style=\"text-indent: 1vw; padding-left: 20px; padding-right: 20px\">\r\n        2. Участникам предоставляется набор заданий, к которым требуется найти ответ «флаг» и отправить его\r\n        в проверяющую систему.\r\n        Каждое такое задание оценивается различным количеством очков, в зависимости от количества\r\n        решивших это задание.\r\n        В случае проблем с доступностью заданий или проверяющей системы можно написать боту в\r\n        https://t.me/alt_school_ctfbot\r\n    </p>\r\n    <p style=\"text-indent: 1vw; padding-left: 20px; padding-right: 20px\">\r\n        3. В данных Соревнованиях присутствуют задания следующих категорий:\r\n    <ul style=\"text-indent: 1vw;\">\r\n        <p>\r\n            > admin — задачи на администрирование;\r\n        </p>\r\n        <p>\r\n            > recon — поиск информации в Интернете;\r\n        </p>\r\n        <p>\r\n            > crypto — криптография;\r\n        </p>\r\n        <p>\r\n            > stegano — стеганография;\r\n        </p>\r\n        <p>\r\n            > ppc — задачи на программирование;\r\n        </p>\r\n        <p>\r\n            > pwn — задачи на эксплуатацию бинарных уязвимостей.\r\n        </p>\r\n        <p>\r\n            > web — задачи на веб-уязвимости, такие как SQL injection, XSS и другие;\r\n        </p>\r\n        <p>\r\n            > forensic — задания на исследование цифровых доказательств, их поиск и получение;\r\n        </p>\r\n        <p>\r\n            > joy — различные развлекательные задачи;\r\n        </p>\r\n        <p>\r\n            > misc — задачи, сочетающие в себе несколько вышеописанных категорий.\r\n        </p>\r\n    </ul>\r\n    </p>\r\n    <p style=\"text-indent: 1vw; padding-left: 20px; padding-right: 20px\">\r\n        4. Результатом правильного решения задания является «флаг» - правильный ответ в формате:\r\n        school{[A-Za-z0-9_]+} или flag{[A-Za-z0-9_]+}\r\n        например school{g00D_j0b_mY_fr13nd}. За отправку корректного «флага» команда получает баллы от\r\n        этого задания.\r\n    </p>\r\n    <p style=\"text-indent: 1vw; padding-left: 20px; padding-right: 20px\">\r\n        5. Во время проведения Соревнования участники могут общаться только с членами своей команды и с\r\n        представителями жюри.\r\n    </p>\r\n    <p style=\"text-indent: 1vw; padding-left: 20px; padding-right: 20px\">\r\n        6. При выполнении заданий участникам Соревнования запрещается выполнять следующие\r\n        действия:\r\n    <ul style=\"text-indent: 2vw;\">\r\n        <p>\r\n            - проводить атаки на серверы жюри и автоматизированную систему поддержки сервисов;\r\n        </p>\r\n        <p>\r\n            - генерировать неоправданно большой объем трафика;\r\n        </p>\r\n        <p>\r\n            - мешать всевозможными способами получению флага другим командам;\r\n        </p>\r\n        <p>\r\n            - сообщать условия задач, а также значения флагов, кому-либо, за исключением членов своей\r\n            команды.\r\n        </p>\r\n    </ul>\r\n    </p>\r\n    <p style=\"text-indent: 1vw; padding-left: 20px; padding-right: 20px\">\r\n        7. За нарушение требований Правил или нарушение хода Соревнований (например, неподобающее\r\n        поведение)\r\n        команда может быть дисквалифицирована по решению жюри.\r\n    </p>\r\n    <p style=\"text-indent: 1vw; padding-left: 20px; padding-right: 20px\">\r\n        8. Если по ходу проведения Соревнования были отмечены нарушения требований Правил,\r\n        участники должны обратить на них внимание представителей жюри с целью устранения причин\r\n        нарушения.\r\n    </p>\r\n    <p style=\"text-indent: 1vw; padding-left: 20px; padding-right: 20px\">\r\n        9. В случае несогласия с предварительными результатами Соревнования участники могут\r\n        подать апелляцию в жюри до оглашения ими результатов Соревнования.\r\n    </p>\r\n    <p style=\"text-indent: 1vw; padding-left: 20px; padding-right: 20px\">\r\n        10. Если по результатам апелляции изменятся результаты Соревнования, список победителей и\r\n        призеров также может измениться.\r\n    </p>\r\n    <p style=\"text-indent: 1vw; padding-left: 20px; padding-right: 20px\">\r\n        11. Жюри в обязательном порядке оглашает результаты рассмотрения поступивших апелляций\r\n        до оглашения итоговых результатов Соревнования.\r\n    </p>\r\n    <p style=\"text-indent: 1vw; padding-left: 20px; padding-right: 20px\">\r\n        12. Удачи в решении заданий ;)\r\n    </p>\r\n</div>\r\n<ol class=\"dec\" style=\"display: none\">\r\n    <li>\r\n        Длительность соревнований - 6 часов + час перерыва на обед.\r\n        Соревнования могут быть продлены по решению жюри.\r\n    </li>\r\n    <li>\r\n        Участникам предоставляется набор заданий, к которым требуется найти ответ «флаг» и отправить его\r\n        в проверяющую систему.\r\n        Каждое такое задание оценивается различным количеством очков, в зависимости от количества\r\n        решивших это задание.\r\n        В случае проблем с доступностью заданий или проверяющей системы можно написать боту в\r\n        https://t.me/alt_school_ctfbot\r\n    </li>\r\n    <li>\r\n        В данных Соревнованиях присутствуют задания следующих категорий:\r\n        <ul>\r\n            <li>\r\n                admin — задачи на администрирование;\r\n            </li>\r\n            <li>\r\n                recon — поиск информации в Интернете;\r\n            </li>\r\n            <li>\r\n                crypto — криптография;\r\n            </li>\r\n            <li>\r\n                stegano — стеганография;\r\n            </li>\r\n            <li>\r\n                ppc — задачи на программирование (professional programming and coding);\r\n            </li>\r\n            <li>\r\n                pwn — задачи на эксплуатацию бинарных уязвимостей.\r\n            </li>\r\n            <li>\r\n                web — задачи на веб-уязвимости, такие как SQL injection, XSS и другие;\r\n            </li>\r\n            <li>\r\n                forensic — задания на исследование цифровых доказательств, их поиск и получение,\r\n                например, анализ дампов оперативной памяти компьютера;\r\n            </li>\r\n            <li>\r\n                joy — различные развлекательные задачи;\r\n            </li>\r\n            <li>\r\n                misc — задачи, сочетающие в себе несколько вышеописанных категорий.\r\n            </li>\r\n        </ul>\r\n    </li>\r\n    <li>\r\n        Результатом правильного решения задания является «флаг» - правильный ответ в формате:\r\n        school{[A-Za-z0-9_]+} или flag{[A-Za-z0-9_]+}\r\n        например school{g00D_j0b_mY_fr13nd}. За отправку корректного «флага» команда получает баллы от\r\n        этого задания.\r\n    </li>\r\n    <li>\r\n        Во время проведения Соревнования участники могут общаться только с членами своей команды и с\r\n        представителями жюри.\r\n    </li>\r\n    <li>\r\n        При выполнении заданий участникам Соревнования запрещается выполнять следующие\r\n        действия:\r\n        <ul>\r\n            <li>\r\n                проводить атаки на серверы жюри и автоматизированную систему поддержки сервисов и\r\n                проверки решений;\r\n            </li>\r\n            <li>\r\n                генерировать неоправданно большой объем трафика;\r\n            </li>\r\n            <li>\r\n                мешать всевозможными способами получению флага другим командам;\r\n            </li>\r\n            <li>\r\n                сообщать условия задач, а также значения флагов, кому-либо, за исключением членов своей\r\n                команды.\r\n            </li>\r\n        </ul>\r\n    </li>\r\n    <li>\r\n        За нарушение требований Правил или нарушение хода Соревнований (например, неподобающее\r\n        поведение)\r\n        команда может быть дисквалифицирована по решению жюри.\r\n    </li>\r\n    <li>\r\n        Если по ходу проведения Соревнования были отмечены нарушения требований Правил,\r\n        участники должны обратить на них внимание представителей жюри с целью устранения причин\r\n        нарушения.\r\n    </li>\r\n    <li>\r\n        В случае несогласия с предварительными результатами Соревнования участники могут\r\n        подать апелляцию в жюри до оглашения ими результатов Соревнования.\r\n    </li>\r\n    <li>\r\n        Если по результатам апелляции изменятся результаты Соревнования, список победителей и\r\n        призеров также может измениться.\r\n    </li>\r\n    <li>\r\n        Жюри в обязательном порядке оглашает результаты рассмотрения поступивших апелляций\r\n        до оглашения итоговых результатов Соревнования.\r\n    </li>\r\n    <li>\r\n        Удачи в решении заданий ;)\r\n    </li>\r\n\r\n</ol >',NULL,'2025-06-01 08:39:41');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solved_tasks`
--

LOCK TABLES `solved_tasks` WRITE;
/*!40000 ALTER TABLE `solved_tasks` DISABLE KEYS */;
INSERT INTO `solved_tasks` VALUES
(1,7,1,1.00,'2025-06-01 08:43:03','2025-06-01 08:43:03');
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
(1,'SeQueL','web','medium','В далекой деревне жил один умелый программист. Он был известен своими навыками в работе с базами данных, разработкой сайтов и часто помогал местным жителям с их проблемами. Однажды, к нему обратился торговец, утверждая, что его сайт был взломан.Он решил помочь и начал исследовать...<br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4001\" target=\"_blank\">SeQueL | WEB</a>',NULL,1.00,1000.00,1000.00,'school{SGFja2luZyB5b3VyIHdheSB0byBncmVhdG5lc3M=}',NULL,'2024-08-20 05:41:45','2025-06-01 08:43:03'),
(2,'ReCcE','web','hard','В древние времена, когда магия и технологии переплетались в удивительном союзе, жил волшебник по имени Эдмунд. Он обладал уникальным даром - способностью управлять магическими силами с помощью команд.<br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4002\" target=\"_blank\">ReCcE | WEB</a>',NULL,0.00,1000.00,1000.00,'school{SGFjayB5b3VyIHdheSB0byBzdWNjZXNz}',NULL,'2024-08-20 05:42:18','2025-02-07 15:07:53'),
(3,'Auth','web','easy','В далекой деревне жила добрая ведьма по имени Элиза. Она хранила секреты магии и помогала жителям своей деревни в трудные времена. Однажды, когда в деревне начались странные происшествия, все обратились к Элизе за помощью.Ведьма поняла, что причиной всех бед стал злой тролль, который заблокировал доступ к сокровищам, спрятанным в горах. Элиза решила отправиться на поиски тролля и разгадать его загадки.С помощью своих знаний магии и дружелюбного общения с природой, Элиза смогла найти тролля и победить его с помощью заклинания: 6W2I9K9I...<br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4003\" target=\"_blank\">Login Form | WEB</a>',NULL,0.00,1000.00,1000.00,'school{schoolGigglesAndGiggles}',NULL,'2024-08-20 05:42:34','2025-06-01 05:27:00'),
(4,'TheCube','joy','medium','В далеком королевстве жил мудрый старец по имени Артур. Он был известен своими удивительными способностями собирать сложные головоломки. Однажды, в королевстве появился загадочный кубик размером 5х5х5, который никто не мог собрать.(В настройках выбрать 5х5х5) <br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4004\" target=\"_blank\">The Cube | WEB</a>',NULL,0.00,1000.00,1000.00,'school{Hacking_is_life}',NULL,'2024-08-20 05:43:29','2025-02-07 15:07:53'),
(5,'FeedBack','web','hard','В древние времена в лесу стоял древний храм, в котором хранился магический артефакт – кристалл бесконечной мудрости. Легенда гласила, что тот, кто сможет проникнуть внутрь храма и дотронуться до кристалла, получит неограниченные знания и силу. А имя этому артефакту flag.php...<br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4005\" target=\"_blank\">FeedBack | WEB</a>',NULL,0.00,1000.00,1000.00,'school{HackerIsVirtuosoOfDigitalMysticism}',NULL,'2024-08-20 05:47:38','2025-02-07 15:07:53'),
(6,'DirTraver','web','hard','В древние времена в маленькой деревне жила девушка по имени Лилия. Она была известна своими невероятными способностями к путешествиям в параллельные миры и возможностью видеть то, что другие не могли.Однажды Лилия услышала о загадочном месте под названием \"DirTraver\"... <br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4006\" target=\"_blank\">DirTraver | WEB</a>',NULL,0.00,1000.00,1000.00,'school{AltayCTFisFun2024AltSTU}',NULL,'2024-08-20 05:47:59','2025-02-07 15:07:53'),
(7,'Stolen-Session','web','hard','Злоумышленники взламывая сайт похитили токен одного из пользователей, благодаря отслеживанию пакетов мы смогли предотвратить их атаку, но потеряли токен в трафике...<br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4007\" target=\"_blank\">Stolen-Session | WEB</a>','dd6919412fbbeba59f95d4cea66922dc.pcapng;',0.00,1000.00,1000.00,'school{JohnDraperistheFirstHacker}',NULL,'2024-10-13 16:57:21','2025-02-07 15:07:53'),
(8,'Battleship','crypto','hard','Вы работаете на Военно-морской базе страны N, вам необходимо потопить товарные корабли. Ваша команда перехватила сигналы перемещения кораблей, однако никто не может их расшифровать, может у вас получится.<br>\r\n<a href=\"/\" onclick=\"javascript:event.target.port=4008\" target=\"_blank\">Battleship</a>\r\n<br>Формат флага school{A-Z0-9}',NULL,0.00,1000.00,1000.00,'SCHOOL{SIPHERSFORSHIPS}',NULL,'2024-10-13 17:01:58','2025-02-07 15:07:53'),
(9,'AircraftGame','joy','medium','Окунитесь в атмосферу космической битвы и встретьтесь лицом к лицу с космическими захватчиками. Став легендой космоса вы можете быть вознаграждены.','a3323703dcd24bf43fc6359f8bb8ebba.zip;',0.00,1000.00,1000.00,'school{gamer_is_hacker}',NULL,'2024-10-31 16:30:05','2025-02-07 15:07:53'),
(10,'LecLab','stegano','easy','Я выполнил лабораторную работу, но не знаю всё ли с ней в порядке, посмотрите пожалуйста.','3f810dd20eeee2111660e23660b4e53c.pptx;',0.00,1000.00,1000.00,'school{Metadata_The_Easy_Base64_Flag_In_mp3.file}',NULL,'2024-10-31 16:31:12','2025-02-07 15:07:53'),
(11,'The Mystery of the Lost Pizza','stegano','easy','<p align=\"justify\"> &emsp;&emsp; Много лет назад, в Нью-Йорке, процветала легенда о потерянной пицце. Группа легендарных черепашек-ниндзя обладала картой, указывающей на местоположение этой великой пиццы, которая, как говорили, имела волшебную силу, способную придать невероятную силу и мудрость тому, кто съест ее.</p>\r\n<p align=\"justify\"> &emsp;&emsp; Однако, когда черепашек-ниндзя отправились на поиски этой потерянной пиццы, им пришлось столкнуться с темными силами, желающими завладеть ее магией. В поединке с зловещими хакерами, картография, указывающая на местонахождение пиццы, была повреждена и закодирована в специальном файле. Теперь Черепашкам Ниндзя предстоит восстановить карту, расшифровать ее содержимое и снова найти потерянную пиццу, прежде чем темные силы успеют сделать это.</p>','a508a60912cfbd08e2c7661c07e3f503.;',0.00,1000.00,1000.00,'school{m1chelan9el0}',NULL,'2024-10-31 16:33:26','2025-02-07 15:07:53'),
(12,'tydysh','misc','medium','<p align=\"justify\"> &emsp;&emsp; В \"Фиксополисе\" существовал проект, созданный великим Профессором Чудаковым. Однако он внезапно исчез без следа. Легенда гласит, что Профессор спрятал его, зашифровав. Только герои, расшифровав тайны Фиксиков, смогут найти и восстановить проект, прежде чем он окажется в руках зла.</p>\r\n<p>Формат флага: school{A-Z, a-z, 0-1}</p>','5cfdf3fc714b893603534c1cb875495b.jpg;',0.00,1000.00,1000.00,'school{d1m-d1mych}',NULL,'2024-10-31 16:35:33','2025-02-07 15:07:53'),
(13,'the song of flag','stegano','medium','Существует древняя песня, исполненная мудрым бардом, в которой спрятано тайное послание. Это послание содержит ключ к скрытому сокровищу, которое ждет того, кто сможет его разгадать. Только тот, кто улавливает каждое слово и звук, сможет расшифровать его и найти путь к сокровищу.<br><br>\\r\\nФормат флага: school{flag}','796b6f67aed45ac9ef14e030f4c1102f.mp3;',0.00,1000.00,1000.00,'school{Y_a_quelquun?}',NULL,'2024-10-31 16:36:47','2025-02-07 15:07:53'),
(14,'Sons lang','crypto','medium','Мой сын придумал свой язык, когда я спросил как общаться на этом языке, он сказал: «Назови мне пароль, который я написал тут, и я тебе расскажу как на нем общаться»,- и протянул этот лист мне. Помогите мне решить этот шифр.<br>\r\nФормат флага school{a-z0-9}','9eab788e3dfe18c77a9efc68d8a03fe4.pdf;',0.00,1000.00,1000.00,'school{skynet}',NULL,'2024-10-31 16:38:55','2025-02-07 15:07:53'),
(15,'Word spell','crypto','hard','В древние времена жил очень мудрый и одинокий король, он был самым богатым человеком в мире, а все его богатство лежало в волшебном сундуке, чтобы его открыть, надо произнести слово-заклинание. Когда король стал очень старым, встал вопрос о наследнике престола, на что король ответил: «Наследником станет тот, кто откроет мой сундук». Король умер, а сундук так и не был открыт. Много лет спустя люди поняли, что пароль хранится в тексте его последней книги, но он написал ее не обычным способом.<br>\r\n\r\nФормат флага school{a-z0-9}','1e6b9a59e566079303816970dfea0451.txt;',0.00,1000.00,1000.00,'school{analysis}',NULL,'2024-10-31 16:40:21','2025-02-07 15:07:53'),
(16,'Questroom','crypto','easy','Вопрошатель – всемирно известный мастер головоломок позвал сто человек для решения его очередной загадки. Вы собрались в большой комнате с сотней ящиков, где Вопрошатель каждому дал порядковый номер и сказал: «Первый номер откроет все шкафчики, второй номер закроет каждый второй шкафчик, третий номер откроет или закроет каждый третий шкафчик. Кто первый назовет мне номера оставшихся открытых шкафчиков – победит». После этих слов вы подошли к Вопрошателю и сказали, что знаете номера открытых ящиков.<br>\r\n\r\nФормат флага school{0-9}',NULL,0.00,1000.00,1000.00,'school{149162536496481100}',NULL,'2024-10-31 16:41:20','2025-02-07 15:07:53'),
(17,'Puzzlature','osint','medium','Я сказал другу почистить жесткий диск и он постирал его в стиральной машинке. Теперь он жалуется, что у него ничего не запускается, говорит: «Всё поперемешалось, а собрать я это не могу»','b71fc9f4e100b0456273419aae971d3d.zip;',0.00,1000.00,1000.00,'school{signacoola}',NULL,'2024-10-31 16:58:06','2025-02-07 15:07:53'),
(18,'Square№9¾','crypto','easy','Вы видели как мальчик со шрамом забежал в стену, подойдя к ней вы поняли,\r\nчто он попал в нее, сказав волшебое слово, так же вы увидели надпись: bwkls->beach,\r\nрисунок и это странное слово: uylwjqrkdw<br>\r\n\r\nФормат флага school{a-z}','d4b0c20a15f53e60aa954c944700eb77.png;',0.00,1000.00,1000.00,'school{nicesquare}',NULL,'2024-10-31 17:02:58','2025-02-07 15:07:53'),
(19,'Salad','crypto','medium','Столовая saladsCTF - ваш конкурент - стала продавать очень вкусный салат,что забирает у вас клиентов. Вы захотели узнать рецепт этого салата, но поняли, что от обычного его отличает наличие \"секретного ингредеента\". Вы купили этот салат и решили разгадать его \"секрет\".<br>\r\nФормат флага school{A-Z0-9}','629d2fa19e761020ccffdbd38e394623.zip;',0.00,1000.00,1000.00,'school{AB0BAA155A51}',NULL,'2024-10-31 17:08:21','2025-02-07 15:07:53'),
(20,'kaChow','stegano','hard','В мире Радиатора существует древний обычай гадать на кофейной гуще для предсказания будущего. Однако однажды возникла новая идея: гадание на бензине. Верят, что расплывчатые разводы бензина могут тоже скрывать тайны и предсказывать судьбу.\r\nТеперь мастерство гадания на бензине стало популярным искусством среди жителей Радиатора, и каждый, кто освоит его технику, может узнать удивительные предсказания и открыть новые горизонты будущего.<br>\r\n\r\nФормат флага: school{A-Z, a-z, 0-1}','320eb54bed20c9883782c8bb2233ec27.rar;',0.00,1000.00,1000.00,'school{flag_is_going_to_do_ka_chow}',NULL,'2024-10-31 17:09:54','2025-02-07 15:07:53'),
(21,'oslik {I, A}','stegano','easy','Во время шпионской операции, агенты использовали басню для сокрытия секретной информации. Ваша миссия - расколоть шифр и раскрыть тайну.<br>\r\nФормат флага: school{A-Z, a-z, 0-1,and \"?\"}','0e4bd0f0c1f1c23cccd37a8b3a6cd367.docx;',0.00,1000.00,1000.00,'school{KRYLOV?}',NULL,'2024-10-31 17:11:14','2025-02-07 15:07:53'),
(22,'staff','stegano','medium','Легендарный композитор закодировал свою последнюю мелодию в нотах, чтобы защитить ее от непрошеных слушателей. Чтобы разгадать секрет, нужно проявить музыкальность и логику.<br>\r\nФормат флага: school{A-Z, a-z, 0-1}','118cb7da02c0859281fa28d7f9a60f34.zip;',0.00,1000.00,1000.00,'school{9Rh0ven}',NULL,'2024-10-31 17:12:15','2025-02-07 15:07:53'),
(23,'BossFighting','forensic','medium','Мой босс очень жестокий человек. Какое-то время назад я скачал с сетевого диска компании 2 файла, в одном находился пароль, а в другом секретная информация компании. Файлы я успешно утерял... Если я их не восстановлю, мой босс меня убъёт... К счастью у меня остался сетевой трафик, помоги мне, пожалуйста...','cc13ba8afd3534c3f806521dd68ba607.pcapng;',0.00,1000.00,1000.00,'shool{My_boss_Is_Bad}',NULL,'2024-10-31 17:13:09','2025-02-07 15:07:53'),
(24,'Not a pirate, but a hunter','forensic','easy','Не так давно я занимался \"добычей\" игр на всем известном ресурсе, но почему-то моё устройство перестало воспринимать доменные адреса. Друг поделился трафиком и сказал, что там можно найти ip адресс, не поможешь?<br>\r\nФормат флага : shool{ip}','c9477176202692ddc1ab8353417303e7.pcapng;',0.00,1000.00,1000.00,'shool{81.91.178.146}',NULL,'2024-10-31 17:16:28','2025-02-07 15:07:53'),
(25,'Petremyach','osint','easy','Поп Петр любитель поесть. В начале воскресной службы он куда-то пропал, охранник на входе говорит, что Отец бормотал про какой-то перемяч. Найдите где он может быть.<br>\r\nФормат флага: school{A-Z, a-z, 0-1}','546d33f0d8ce808616116149a614dfc4.jpg;',0.00,1000.00,1000.00,'school{PETRFOOD}',NULL,'2024-10-31 17:19:51','2025-02-07 15:07:53'),
(26,'Shnuk','osint','easy','Паучок не очень хотел встречаться с людьми, но он очень хотел бы узнать кто его озвучивает.<br>\r\nФормат флага: school{NameSurname}','cdfae0e560b9a90be4d2059372c48953.mp4;',0.00,1000.00,1000.00,'school{DincerCekmez}',NULL,'2024-10-31 17:32:38','2025-02-07 15:07:53'),
(27,'Old','osint','medium','<a href=\"https://www.liverpoolecho.co.uk/news/liverpool-news/landmark-building-bought-multi-million-22196475\" target=\"_blank\\\">Landmark building bought by multi million pound company - Liverpool Echo</a> Около данного здания был замечен с деловым вопросом очень известный агент по недвижимости. Вот только ни фамилия, ни дата рождения его неизвестна. Известно лишь, что его зовут Тревор и он работает в одной компании со своим братом. Помогите найти полную дату рождения Тревора<br>\r\nФормат флага: school{day.month.year}',NULL,0.00,1000.00,1000.00,'school{12.01.1970}',NULL,'2024-10-31 17:34:26','2025-02-07 15:07:53'),
(28,'import substitution','osint','medium','Некий с ником taramtaramych представляется сотрудником очень известной компании «Пив Пав». Выясните информацию, которая поможет внедриться в данную компанию.<br>\r\nФормат флага: school{ID No}',NULL,0.00,1000.00,1000.00,'school{Taras-Macha-8chDY-5l7}',NULL,'2024-10-31 17:35:25','2025-02-07 15:07:53'),
(29,'Adrastei&#039;s…','reverse','hard','Вас пригласили на VIP вечеринку любителей обратной перемотки. Кодовое слово, что у вас спрашивают и будет флагом  <br>\r\nФормат флага: school{A-Z, a-z, 0-1}','336b7abf20c29950c9a3d513b92d0ebe.exe;',0.00,1000.00,1000.00,'school{YlAFRTLM44GC34oPDix7}',NULL,'2024-10-31 17:48:20','2025-02-07 15:07:53'),
(30,'…sister','reverse','easy','ООО «Гексагон» приглашает вас к себе на работу, но охранник без кодового слова не пускает<br>\r\nФормат флага:school{codeword}','5e635409c4f88692bc544d297b7ee6aa.zip;',0.00,1000.00,1000.00,'school{UPUPDDLRLRBA}',NULL,'2024-10-31 17:51:38','2025-02-07 15:07:53'),
(31,'&','reverse','hard','Мы отдали разработку кода на аутсорс программистам из индии. В результате буквы разбрелись по консоли. Поставьте их снова в красивый рядочек, что будет флажком<br>\r\nФормат флага: school{A-Z}','75bdd7638cf3a2ced902b6c9dd27dee2.zip;',0.00,1000.00,1000.00,'school{ACDJKBEILSFHMRTGNQUXOPVWY}',NULL,'2024-10-31 17:52:53','2025-02-07 15:07:53');
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
  `GuestLogo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
(1,'Team A','$2y$10$f4mKEMdjppNZ/A05Ed0JdOlcHDGinf1On.s/xrUrgvTQZY7OCfxxq','StandartLogo.png','',8,'МАОУ &quot;СОШ №132&quot;  им. Н.М. Малахова г. Барнаул','Yes',0.00,NULL,'2025-02-06 17:28:48','2025-06-01 05:27:00'),
(2,'Team B','$2y$10$Oi6BvZ41h879Z8TXG4oj5.xtFMvNs.9Qc/qpSXcYSftwMB.xx1ISC','StandartLogo.png','',5,'МАОУ &quot;СОШ №136&quot;','Yes',0.00,NULL,'2024-08-25 15:31:53','2025-06-01 05:27:00'),
(3,'Team C','$2y$10$Rb47S0EEH4cbGTU1anTt4.s6VlzEN89ONhdekLmRwb9U8xbXxCLXa','StandartLogo.png','',5,'БОУ г. Омска &quot;Лицей № 149&quot;','Yes',0.00,NULL,'2024-08-23 06:23:43','2025-06-01 05:27:00'),
(4,'Team D','$2y$10$kvQGNE/lOftqi1UU3EnIHOgRcDd/tL/W6PNbdJ..lkHY1uoZFM2KW','StandartLogo.png','',5,'МБОУ &quot;СОШ №49&quot; г.Барнаул','Yes',0.00,NULL,'2024-08-23 06:23:45','2025-06-01 05:27:00'),
(5,'Team E','$2y$10$zPPOI2mCf8.PW4nuwudRoevgp2PGd/n1xLy8OogC.q0p.PiGDxNCu','StandartLogo.png','',5,'МАОУ «СОШ №134» г. Барнаул','Yes',0.00,NULL,'2024-08-23 06:23:48','2025-06-01 05:27:00'),
(6,'SharLike','$2y$10$5nqcESyPJcvYjjWW96IuouHGws7Qj2/V/Y6Ox.4ASc7D17pq5JmVW','1730425369_like-button.png','',7,'Алтайский государственный технический университет им. И. И. Ползунова','No',0.00,NULL,'2024-10-31 16:49:23','2025-06-01 05:27:00'),
(7,'Жуколовы','$2y$12$yA3TgrKZ.BfgBXg40UavbOfCPId9sONJfTdxsbCaA2dAQS5lBoLcm','1727521723_New_Logo_Жуколовы-5-full.png','',6,'Алтайский государственный технический университет им. И. И. Ползунова','No',1000.00,NULL,'2024-08-26 08:04:47','2025-06-01 08:43:03');
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

-- Dump completed on 2025-06-01  8:43:38
