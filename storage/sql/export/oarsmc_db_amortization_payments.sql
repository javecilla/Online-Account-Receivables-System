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
-- Table structure for table `amortization_payments`
--
DROP TABLE IF EXISTS `amortization_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!50503 SET character_set_client = utf8mb4 */
;
CREATE TABLE `amortization_payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `amortization_id` int NOT NULL,
  `amount` decimal(10, 2) NOT NULL,
  `payment_date` date NOT NULL,
  `reference_number` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `reference_number` (`reference_number`),
  KEY `amortization_id` (`amortization_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_payment_date` (`payment_date`),
  KEY `idx_reference_number` (`reference_number`),
  CONSTRAINT `amortization_payments_ibfk_1` FOREIGN KEY (`amortization_id`) REFERENCES `member_amortizations` (`amortization_id`),
  CONSTRAINT `amortization_payments_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `accounts` (`account_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;
--
-- Dumping data for table `amortization_payments`
--
LOCK TABLES `amortization_payments` WRITE;
/*!40000 ALTER TABLE `amortization_payments` DISABLE KEYS */
;
INSERT INTO `amortization_payments`
VALUES (
    1,
    1,
    2650.00,
    '2025-04-06',
    'AMT202504052195',
    'First monthly payment',
    1,
    '2025-04-05 08:31:55'
  ),
(
    2,
    1,
    5300.00,
    '2025-05-20',
    'AMT202504052695',
    'Second monthly payment',
    1,
    '2025-04-05 08:45:47'
  ),
(
    3,
    1,
    5300.00,
    '2025-06-11',
    'AMT202504057481',
    'Third monthly payment',
    1,
    '2025-04-05 09:18:26'
  ),
(
    4,
    1,
    2650.00,
    '2025-07-25',
    'AMT202504053139',
    'Forth monthly payment',
    1,
    '2025-04-05 09:27:33'
  );
/*!40000 ALTER TABLE `amortization_payments` ENABLE KEYS */
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