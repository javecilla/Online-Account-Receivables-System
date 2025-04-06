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
-- Table structure for table `account_otp_codes`
--
DROP TABLE IF EXISTS `account_otp_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!50503 SET character_set_client = utf8mb4 */
;
CREATE TABLE `account_otp_codes` (
  `aoc_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `code` varchar(6) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  PRIMARY KEY (`aoc_id`),
  UNIQUE KEY `code` (`code`),
  KEY `idx_email_code` (`email`, `code`)
) ENGINE = InnoDB AUTO_INCREMENT = 9 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;
--
-- Dumping data for table `account_otp_codes`
--
LOCK TABLES `account_otp_codes` WRITE;
/*!40000 ALTER TABLE `account_otp_codes` DISABLE KEYS */
;
INSERT INTO `account_otp_codes`
VALUES (
    1,
    'jeromesavc@gmail.com',
    '342470',
    '2025-04-04 16:35:42',
    '2025-04-04 16:40:42',
    NULL
  ),
  (
    2,
    'jeromesavc@gmail.com',
    '710383',
    '2025-04-04 16:52:05',
    '2025-04-04 16:57:05',
    '2025-04-04 16:52:33'
  ),
  (
    3,
    'jeromesavc@gmail.com',
    '796559',
    '2025-04-04 18:50:33',
    '2025-04-04 18:55:32',
    '2025-04-04 18:51:37'
  ),
  (
    4,
    'jeromesavc@gmail.com',
    '526118',
    '2025-04-04 18:51:52',
    '2025-04-04 18:56:52',
    NULL
  ),
  (
    5,
    'jeromesavc@gmail.com',
    '397952',
    '2025-04-04 18:57:02',
    '2025-04-04 19:02:02',
    '2025-04-04 18:58:21'
  ),
  (
    6,
    'jeromesavc@gmail.com',
    '693711',
    '2025-04-04 22:38:46',
    '2025-04-04 22:43:46',
    NULL
  ),
  (
    7,
    'jeromesavc@gmail.com',
    '512049',
    '2025-04-04 22:40:21',
    '2025-04-04 22:45:21',
    '2025-04-04 22:41:42'
  ),
  (
    8,
    'jeromesavc@gmail.com',
    '492960',
    '2025-04-05 13:26:56',
    '2025-04-05 13:31:56',
    '2025-04-05 13:28:09'
  );
/*!40000 ALTER TABLE `account_otp_codes` ENABLE KEYS */
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