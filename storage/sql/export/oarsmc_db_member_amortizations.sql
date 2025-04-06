-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: oarsmc_db
-- ------------------------------------------------------
-- Server version	8.0.40
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!50503 SET NAMES utf8 */
;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */
;
/*!40103 SET TIME_ZONE='+00:00' */
;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */
;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */
;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */
;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */
;
--
-- Table structure for table `member_amortizations`
--
DROP TABLE IF EXISTS `member_amortizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!50503 SET character_set_client = utf8mb4 */
;
CREATE TABLE `member_amortizations` (
  `amortization_id` int NOT NULL AUTO_INCREMENT,
  `member_id` int NOT NULL,
  `type_id` int NOT NULL,
  `principal_amount` decimal(10, 2) NOT NULL,
  `monthly_amount` decimal(10, 2) NOT NULL,
  `remaining_balance` decimal(10, 2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active', 'completed', 'defaulted') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`amortization_id`),
  KEY `member_id` (`member_id`),
  KEY `type_id` (`type_id`),
  KEY `idx_status` (`status`),
  KEY `idx_dates` (`start_date`, `end_date`),
  CONSTRAINT `member_amortizations_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`),
  CONSTRAINT `member_amortizations_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `amortization_types` (`type_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 8 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;
--
-- Dumping data for table `member_amortizations`
--
LOCK TABLES `member_amortizations` WRITE;
/*!40000 ALTER TABLE `member_amortizations` DISABLE KEYS */
;
INSERT INTO `member_amortizations`
VALUES (
    1,
    16,
    1,
    30000.00,
    2650.00,
    15900.00,
    '2025-04-05',
    '2026-04-05',
    'active',
    '2025-04-05 07:02:08',
    '2025-04-05 09:27:33'
  ),
(
    2,
    12,
    2,
    30000.00,
    2600.00,
    31200.00,
    '2025-04-05',
    '2026-04-05',
    'active',
    '2025-04-05 11:14:04',
    '2025-04-05 11:14:04'
  ),
(
    3,
    22,
    1,
    25000.00,
    2291.67,
    20000.00,
    '2025-02-06',
    '2025-03-06',
    'active',
    '2025-04-06 11:07:26',
    '2025-04-06 11:07:26'
  ),
(
    4,
    23,
    4,
    30000.00,
    2750.00,
    25000.00,
    '2025-01-06',
    '2025-02-20',
    'active',
    '2025-04-06 11:07:26',
    '2025-04-06 11:07:26'
  ),
(
    5,
    24,
    3,
    50000.00,
    4583.33,
    45000.00,
    '2024-12-06',
    '2025-02-06',
    'active',
    '2025-04-06 11:07:26',
    '2025-04-06 11:07:26'
  ),
(
    6,
    25,
    5,
    40000.00,
    3666.67,
    38000.00,
    '2024-10-06',
    '2025-01-06',
    'active',
    '2025-04-06 11:07:26',
    '2025-04-06 11:07:26'
  ),
(
    7,
    26,
    2,
    20000.00,
    1833.33,
    19000.00,
    '2024-08-06',
    '2024-12-06',
    'active',
    '2025-04-06 11:07:26',
    '2025-04-06 11:07:26'
  );
/*!40000 ALTER TABLE `member_amortizations` ENABLE KEYS */
;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */
;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */
;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */
;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */
;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */
;
-- Dump completed on 2025-04-06  9:38:56