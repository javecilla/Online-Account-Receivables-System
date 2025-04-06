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
-- Table structure for table `accounts`
--
DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!50503 SET character_set_client = utf8mb4 */
;
CREATE TABLE `accounts` (
  `account_id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `account_uid` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_status` enum('active', 'inactive') DEFAULT 'inactive',
  `first_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `account_uid` (`account_uid`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_accounts_role_id` (`role_id`),
  KEY `idx_account_uid` (`account_uid`),
  KEY `idx_email` (`email`),
  KEY `idx_username` (`username`),
  CONSTRAINT `fk_accounts_role_id` FOREIGN KEY (`role_id`) REFERENCES `account_roles` (`role_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 24 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;
--
-- Dumping data for table `accounts`
--
LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */
;
INSERT INTO `accounts`
VALUES (
    1,
    1,
    'A581822',
    'admin@example.com',
    NULL,
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    2,
    2,
    'A581823',
    'accountant1@example.com',
    NULL,
    'accountant1',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    3,
    2,
    'A581824',
    'accountant2@example.com',
    NULL,
    'accountant2',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    4,
    3,
    'A581825',
    'member1@example.com',
    NULL,
    'member1',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    5,
    3,
    'A581826',
    'member2@example.com',
    NULL,
    'member2',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    6,
    3,
    'A581827',
    'carlos.santos@example.com',
    NULL,
    'carlos.santos',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    7,
    3,
    'A581828',
    'diana.cruz@example.com',
    NULL,
    'diana.cruz',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    8,
    3,
    'A581829',
    'eduardo.reyes@example.com',
    NULL,
    'eduardo.reyes',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    9,
    3,
    'A581830',
    'fatima.garcia@example.com',
    NULL,
    'fatima.garcia',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    10,
    3,
    'A581831',
    'gabriel.torres@example.com',
    NULL,
    'gabriel.torres',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    11,
    3,
    'A581832',
    'helena.lopez@example.com',
    NULL,
    'helena.lopez',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    12,
    3,
    'A581833',
    'ivan.mendoza@example.com',
    NULL,
    'ivan.mendoza',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    13,
    3,
    'A581834',
    'julia.ramos@example.com',
    NULL,
    'julia.ramos',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    14,
    3,
    'A581835',
    'kevin.santos@example.com',
    NULL,
    'kevin.santos',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    15,
    3,
    'A581836',
    'luna.ferrer@example.com',
    NULL,
    'luna.ferrer',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-04 12:34:09',
    '2025-04-04 12:34:09'
  ),
  (
    18,
    3,
    'A839109',
    'jeromesavc@gmail.com',
    '2025-04-05 05:26:06',
    'jerome123',
    '$2y$10$m0.WFrp5tTwJsEraQK9j6eem75E72Famfz0EnX2tyD5cLKee17oIO',
    'active',
    '2025-04-05 05:28:09',
    '2025-04-05 05:24:17',
    '2025-04-05 05:28:09'
  ),
  (
    19,
    3,
    'A581837',
    'test.overdue1@example.com',
    NULL,
    'overdue.test1',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-06 11:04:44',
    '2025-04-06 11:04:44'
  ),
  (
    20,
    3,
    'A581838',
    'test.overdue2@example.com',
    NULL,
    'overdue.test2',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-06 11:04:44',
    '2025-04-06 11:04:44'
  ),
  (
    21,
    3,
    'A581839',
    'test.overdue3@example.com',
    NULL,
    'overdue.test3',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-06 11:04:44',
    '2025-04-06 11:04:44'
  ),
  (
    22,
    3,
    'A581840',
    'test.overdue4@example.com',
    NULL,
    'overdue.test4',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-06 11:04:44',
    '2025-04-06 11:04:44'
  ),
  (
    23,
    3,
    'A581841',
    'test.overdue5@example.com',
    NULL,
    'overdue.test5',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'active',
    NULL,
    '2025-04-06 11:04:44',
    '2025-04-06 11:04:44'
  );
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */
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