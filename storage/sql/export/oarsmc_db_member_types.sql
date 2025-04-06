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
-- Table structure for table `member_types`
--
DROP TABLE IF EXISTS `member_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!50503 SET character_set_client = utf8mb4 */
;
CREATE TABLE `member_types` (
  `type_id` int NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL,
  `type_description` text,
  `minimum_balance` decimal(10, 2) DEFAULT '0.00',
  `interest_rate` decimal(5, 2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `penalty_rate` decimal(5, 3) DEFAULT '0.010' COMMENT 'Percentage rate for penalties (e.g., 0.010 = 1%)',
  `minimum_penalty_fee` decimal(10, 2) DEFAULT '50.00' COMMENT 'Minimum penalty amount to charge',
  `maximum_penalty_fee` decimal(10, 2) DEFAULT '500.00' COMMENT 'Maximum penalty amount to charge',
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE = InnoDB AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;
--
-- Dumping data for table `member_types`
--
LOCK TABLES `member_types` WRITE;
/*!40000 ALTER TABLE `member_types` DISABLE KEYS */
;
INSERT INTO `member_types`
VALUES (
    1,
    'Savings Account',
    'Regular savings account with easy access and basic interest rates',
    500.00,
    0.25,
    '2025-04-04 12:36:30',
    '2025-04-04 12:36:30',
    0.010,
    50.00,
    500.00
  ),
(
    2,
    'Time Deposit',
    'Fixed-term deposit with higher interest rates, minimum 30 days lock-in',
    10000.00,
    4.50,
    '2025-04-04 12:36:30',
    '2025-04-04 16:39:17',
    0.020,
    100.00,
    1000.00
  ),
(
    3,
    'Fixed Deposit',
    'Long-term savings with guaranteed returns, minimum 1 year lock-in',
    25000.00,
    6.00,
    '2025-04-04 12:36:30',
    '2025-04-04 16:39:17',
    0.030,
    200.00,
    2000.00
  ),
(
    4,
    'Special Savings',
    'High-yield savings account with maintaining balance',
    50000.00,
    3.75,
    '2025-04-04 12:36:30',
    '2025-04-04 16:39:17',
    0.015,
    150.00,
    1500.00
  ),
(
    5,
    'Youth Savings',
    'Savings account for members under 21 years old with lower maintaining balance',
    200.00,
    0.50,
    '2025-04-04 12:36:30',
    '2025-04-04 16:39:17',
    0.005,
    25.00,
    250.00
  ),
(
    6,
    'Loan',
    'Credit account for borrowing money with regular interest charges',
    0.00,
    12.00,
    '2025-04-05 05:00:21',
    '2025-04-05 05:05:51',
    0.050,
    500.00,
    5000.00
  );
/*!40000 ALTER TABLE `member_types` ENABLE KEYS */
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
-- Dump completed on 2025-04-06  9:38:57