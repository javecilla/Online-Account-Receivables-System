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
-- Table structure for table `members`
--
DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!50503 SET character_set_client = utf8mb4 */
;
CREATE TABLE `members` (
  `member_id` int NOT NULL AUTO_INCREMENT,
  `account_id` int DEFAULT NULL,
  `type_id` int NOT NULL,
  `member_uid` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `membership_status` enum('active', 'inactive', 'suspended', 'closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'active',
  `current_balance` decimal(10, 2) DEFAULT '0.00',
  `opened_date` date NOT NULL,
  `closed_date` date DEFAULT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `middle_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contact_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `house_address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `barangay` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `municipality` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `province` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `region` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `member_uid` (`member_uid`),
  KEY `idx_member_uid` (`member_uid`),
  KEY `fk_members_type_id` (`type_id`),
  KEY `fk_members_account_id` (`account_id`),
  CONSTRAINT `fk_members_account_id` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE
  SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_members_type_id` FOREIGN KEY (`type_id`) REFERENCES `member_types` (`type_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 27 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;
--
-- Dumping data for table `members`
--
LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */
;
INSERT INTO `members`
VALUES (
    1,
    4,
    1,
    'M65123',
    'active',
    5000.00,
    '2025-04-04',
    NULL,
    'Maria',
    'Santos',
    'Cruz',
    '+639201234567',
    'Block 5 Lot 12',
    'San Jose',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-04 12:36:48',
    '2025-04-04 12:36:48'
  ),
  (
    2,
    5,
    2,
    'M65124',
    'active',
    15000.00,
    '2025-04-04',
    NULL,
    'Pedro',
    'Garcia',
    'Luna',
    '+639211234567',
    '123 Main Street',
    'Santa Maria',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-04 12:36:48',
    '2025-04-04 12:36:48'
  ),
  (
    3,
    6,
    1,
    'M65125',
    'active',
    2500.00,
    '2025-04-04',
    NULL,
    'Carlos',
    'Mendez',
    'Santos',
    '+639221234567',
    'Block 1 Lot 5',
    'San Vicente',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-04 12:36:48',
    '2025-04-04 12:36:48'
  ),
  (
    4,
    7,
    1,
    'M65126',
    'active',
    3500.00,
    '2025-04-04',
    NULL,
    'Diana',
    'Rivera',
    'Cruz',
    '+639231234567',
    'Block 2 Lot 8',
    'San Vicente',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-04 12:36:48',
    '2025-04-04 12:36:48'
  ),
  (
    5,
    8,
    1,
    'M65127',
    'active',
    1800.00,
    '2025-04-04',
    NULL,
    'Eduardo',
    'Luna',
    'Reyes',
    '+639241234567',
    'Block 3 Lot 12',
    'Santa Cruz',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-04 12:36:48',
    '2025-04-04 12:36:48'
  ),
  (
    6,
    9,
    2,
    'M65128',
    'active',
    15000.00,
    '2025-04-04',
    NULL,
    'Fatima',
    'Santos',
    'Garcia',
    '+639251234567',
    'Block 4 Lot 15',
    'Santa Cruz',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-04 12:36:48',
    '2025-04-04 12:36:48'
  ),
  (
    7,
    10,
    2,
    'M65129',
    'active',
    25000.00,
    '2025-04-04',
    NULL,
    'Gabriel',
    'Cruz',
    'Torres',
    '+639261234567',
    'Block 5 Lot 18',
    'Poblacion',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-04 12:36:48',
    '2025-04-04 12:36:48'
  ),
  (
    8,
    11,
    2,
    'M65130',
    'active',
    20000.00,
    '2025-04-04',
    NULL,
    'Helena',
    'Reyes',
    'Lopez',
    '+639271234567',
    'Block 6 Lot 21',
    'Poblacion',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-04 12:36:48',
    '2025-04-04 12:36:48'
  ),
  (
    9,
    12,
    3,
    'M65131',
    'active',
    8608.68,
    '2025-04-04',
    NULL,
    'Ivan',
    'Garcia',
    'Mendoza',
    '+639281234567',
    'Block 7 Lot 24',
    'Masagana',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-04 12:36:48',
    '2025-04-06 11:23:19'
  ),
  (
    10,
    13,
    3,
    'M65132',
    'active',
    75000.00,
    '2025-04-04',
    NULL,
    'Julia',
    'Torres',
    'Ramos',
    '+639291234567',
    'Block 8 Lot 27',
    'Masagana',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-04 12:36:48',
    '2025-04-04 12:36:48'
  ),
  (
    11,
    14,
    4,
    'M65133',
    'active',
    100000.00,
    '2025-04-04',
    NULL,
    'Kevin',
    'Lopez',
    'Santos',
    '+639301234567',
    'Block 9 Lot 30',
    'Cacarong',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-04 12:36:48',
    '2025-04-04 12:36:48'
  ),
  (
    12,
    15,
    4,
    'M65134',
    'active',
    84075.05,
    '2025-04-04',
    NULL,
    'Luna',
    'Mendoza',
    'Ferrer',
    '+639311234567',
    'Block 10 Lot 33',
    'Cacarong',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-04 12:36:48',
    '2025-04-05 11:12:41'
  ),
  (
    16,
    18,
    6,
    'M267066',
    'active',
    15900.00,
    '2025-04-05',
    NULL,
    'John',
    'Smith',
    'Doe',
    '+639772465533',
    'Ph7, Blk3, Lot24, Residence III',
    'Brgy. Mapulang Lupa',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-05 05:24:17',
    '2025-04-05 09:27:33'
  ),
  (
    22,
    19,
    6,
    'M65135',
    'active',
    5000.00,
    '2025-04-06',
    NULL,
    'Test',
    'Overdue',
    'One',
    '+639321234567',
    'Block 11 Lot 36',
    'San Jose',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-06 11:06:14',
    '2025-04-06 11:06:14'
  ),
  (
    23,
    20,
    6,
    'M65136',
    'active',
    7500.00,
    '2025-04-06',
    NULL,
    'Test',
    'Overdue',
    'Two',
    '+639331234567',
    'Block 12 Lot 39',
    'San Jose',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-06 11:06:14',
    '2025-04-06 11:06:14'
  ),
  (
    24,
    21,
    6,
    'M65137',
    'active',
    10000.00,
    '2025-04-06',
    NULL,
    'Test',
    'Overdue',
    'Three',
    '+639341234567',
    'Block 13 Lot 42',
    'Santa Maria',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-06 11:06:14',
    '2025-04-06 11:06:14'
  ),
  (
    25,
    22,
    6,
    'M65138',
    'active',
    15000.00,
    '2025-04-06',
    NULL,
    'Test',
    'Overdue',
    'Four',
    '+639351234567',
    'Block 14 Lot 45',
    'Santa Maria',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-06 11:06:14',
    '2025-04-06 11:06:14'
  ),
  (
    26,
    23,
    6,
    'M65139',
    'active',
    20000.00,
    '2025-04-06',
    NULL,
    'Test',
    'Overdue',
    'Five',
    '+639361234567',
    'Block 15 Lot 48',
    'Santa Maria',
    'Pandi',
    'Bulacan',
    'Region 3',
    '2025-04-06 11:06:14',
    '2025-04-06 11:06:14'
  );
/*!40000 ALTER TABLE `members` ENABLE KEYS */
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