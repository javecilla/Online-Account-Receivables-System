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
-- Table structure for table `amortization_types`
--
DROP TABLE IF EXISTS `amortization_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!50503 SET character_set_client = utf8mb4 */
;
CREATE TABLE `amortization_types` (
  `type_id` int NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `interest_rate` decimal(5, 2) DEFAULT '0.00',
  `term_months` int NOT NULL,
  `minimum_amount` decimal(10, 2) DEFAULT '1000.00',
  `maximum_amount` decimal(10, 2) DEFAULT '50000.00',
  `status` enum('active', 'inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;
--
-- Dumping data for table `amortization_types`
--
LOCK TABLES `amortization_types` WRITE;
/*!40000 ALTER TABLE `amortization_types` DISABLE KEYS */
;
INSERT INTO `amortization_types`
VALUES (
    1,
    'Educational Loan',
    'Financial assistance for educational expenses including tuition fees, books, and other school-related costs',
    6.00,
    12,
    5000.00,
    50000.00,
    'active',
    '2025-04-05 05:46:46',
    '2025-04-05 05:46:46'
  ),
  (
    2,
    'Calamity Loan',
    'Emergency financial aid for members affected by natural disasters or calamities',
    4.00,
    6,
    3000.00,
    30000.00,
    'active',
    '2025-04-05 05:46:46',
    '2025-04-05 05:46:46'
  ),
  (
    3,
    'Business Loan',
    'Capital financing for small business ventures and entrepreneurial activities',
    8.00,
    24,
    10000.00,
    100000.00,
    'active',
    '2025-04-05 05:46:46',
    '2025-04-05 05:46:46'
  ),
  (
    4,
    'Personal Loan',
    'Multi-purpose loan for personal expenses and needs',
    7.50,
    12,
    5000.00,
    40000.00,
    'active',
    '2025-04-05 05:46:46',
    '2025-04-05 05:46:46'
  ),
  (
    5,
    'Agricultural Loan',
    'Financing for farming activities, equipment, and agricultural supplies',
    5.50,
    18,
    8000.00,
    75000.00,
    'active',
    '2025-04-05 05:46:46',
    '2025-04-05 05:46:46'
  );
/*!40000 ALTER TABLE `amortization_types` ENABLE KEYS */
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