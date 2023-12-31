-- MySQL dump 10.13  Distrib 8.0.32, for Win64 (x86_64)
--
-- Host: localhost    Database: project_db
-- ------------------------------------------------------
-- Server version	8.0.32

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `closure_dates`
--

DROP TABLE IF EXISTS `closure_dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `closure_dates` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `ACADEMIC_YEAR` date NOT NULL,
  `CLOSURE_DATE` date NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `closure_dates`
--

LOCK TABLES `closure_dates` WRITE;
/*!40000 ALTER TABLE `closure_dates` DISABLE KEYS */;
INSERT INTO `closure_dates` VALUES (5,'2023-08-31','2023-09-30');
/*!40000 ALTER TABLE `closure_dates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `COMMENT_ID` int NOT NULL AUTO_INCREMENT,
  `IDEA_ID` int NOT NULL,
  `USER_ID` int NOT NULL,
  `COMMENT` varchar(1000) NOT NULL,
  `IS_ANONYMOUS` varchar(10) NOT NULL DEFAULT 'No',
  `DATE_POSTED` timestamp NOT NULL,
  PRIMARY KEY (`COMMENT_ID`),
  KEY `idea_id_fk_idx` (`IDEA_ID`),
  KEY `user_id_fk_idx` (`USER_ID`),
  CONSTRAINT `idea_id_fk` FOREIGN KEY (`IDEA_ID`) REFERENCES `idea` (`IDEA_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_id_fk` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (33,6,2748726,'Something','No','2023-05-15 09:36:00'),(35,8,2748726,'A comment','No','2023-05-15 10:18:04'),(40,6,2748726,'A comment from viewed','No','2023-05-15 04:55:50'),(41,8,2748726,'A comment from latest','No','2023-05-15 05:16:36'),(44,14,629932730,'A new comment','No','2023-05-22 04:17:48'),(45,14,629932730,'A another new comment','Yes','2023-05-22 04:18:26');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `department` (
  `DEPT_ID` int NOT NULL AUTO_INCREMENT,
  `DEPARTMENT_NAME` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`DEPT_ID`),
  UNIQUE KEY `ID` (`DEPT_ID`),
  UNIQUE KEY `ID_2` (`DEPT_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES (1,'Finance'),(2,'IT'),(3,'Administration');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `idea`
--

DROP TABLE IF EXISTS `idea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `idea` (
  `IDEA_ID` int NOT NULL AUTO_INCREMENT,
  `USER_ID` int NOT NULL,
  `CATEGORY_ID` int NOT NULL,
  `DEPT_ID` int NOT NULL,
  `IDEA_TITLE` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `IDEA_DESCRIPTION` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL,
  `FILE` text COLLATE utf8mb4_general_ci,
  `IS_ANONYMOUS` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `DATE_POSTED` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `VOTE_COUNT` int DEFAULT '0',
  `AVERAGE_RATING` decimal(10,0) DEFAULT NULL,
  `VIEW_COUNT` int DEFAULT NULL,
  PRIMARY KEY (`IDEA_ID`),
  UNIQUE KEY `ID` (`IDEA_ID`),
  KEY `STAFF_ID` (`USER_ID`),
  KEY `fk_idea_category` (`CATEGORY_ID`),
  KEY `fk_idea_dept` (`DEPT_ID`),
  CONSTRAINT `fk_idea_category` FOREIGN KEY (`CATEGORY_ID`) REFERENCES `idea_category` (`CATEGORY_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_idea_dept` FOREIGN KEY (`DEPT_ID`) REFERENCES `department` (`DEPT_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_idea_user` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `idea`
--

LOCK TABLES `idea` WRITE;
/*!40000 ALTER TABLE `idea` DISABLE KEYS */;
INSERT INTO `idea` VALUES (5,2748726,7,1,'Something','Something else else else else',NULL,'Yes','2023-05-15 08:11:16',1,-1,0),(6,2748726,3,1,'Something','Something',NULL,'No','2023-05-15 08:11:44',NULL,NULL,1),(7,2748726,7,1,'Something','Something else else else',NULL,'No','2023-05-15 08:12:14',1,-1,0),(8,2748726,2,1,'Something','Another idea',NULL,'No','2023-05-15 08:16:03',NULL,NULL,1),(9,2748726,2,1,'Something','Something else else zzz','../PDF_Uploads/FINAL_GROUP_REPORT_EWSD.pdf','No','2023-05-20 01:10:58',0,NULL,NULL),(10,2748726,3,1,'Something','We should have daily meetings for something important zzzz','../PDF_Uploads/COMP1649_Human Computer Interaction and Design.pdf','No','2023-05-21 06:06:03',0,NULL,NULL),(13,629932730,3,2,'A new idea','This is a new idea being posted by Max','../PDF_Uploads/BILLING DOC.pdf','No','2023-05-22 04:16:06',0,NULL,NULL),(14,629932730,2,2,'A new idea','We should have daily meetings for something important by Max','../PDF_Uploads/001278038_COMP1787_Zainab Ismail Patel.pdf','Yes','2023-05-22 04:17:03',1,1,NULL);
/*!40000 ALTER TABLE `idea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `idea_category`
--

DROP TABLE IF EXISTS `idea_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `idea_category` (
  `CATEGORY_ID` int NOT NULL AUTO_INCREMENT,
  `CATEGORY_NAME` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`CATEGORY_ID`),
  UNIQUE KEY `ID` (`CATEGORY_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `idea_category`
--

LOCK TABLES `idea_category` WRITE;
/*!40000 ALTER TABLE `idea_category` DISABLE KEYS */;
INSERT INTO `idea_category` VALUES (2,'Communication'),(3,'Events'),(7,'Another new category');
/*!40000 ALTER TABLE `idea_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactions`
--

DROP TABLE IF EXISTS `reactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reactions` (
  `REACTION_ID` int NOT NULL AUTO_INCREMENT,
  `IDEA_ID` int NOT NULL,
  `USER_ID` int NOT NULL,
  `RATING_ACTION` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`REACTION_ID`),
  UNIQUE KEY `ID` (`REACTION_ID`),
  UNIQUE KEY `ID_2` (`REACTION_ID`),
  KEY `IDEAS_ID` (`IDEA_ID`),
  KEY `user_id_fk_idx` (`USER_ID`),
  CONSTRAINT `reaction_idea_id_fk` FOREIGN KEY (`IDEA_ID`) REFERENCES `idea` (`IDEA_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reaction_user_id_fk` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactions`
--

LOCK TABLES `reactions` WRITE;
/*!40000 ALTER TABLE `reactions` DISABLE KEYS */;
INSERT INTO `reactions` VALUES (6,7,2748726,'down'),(8,5,2748726,'down'),(10,14,629932730,'up');
/*!40000 ALTER TABLE `reactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `ROLE_NAME` varchar(50) NOT NULL,
  PRIMARY KEY (`ROLE_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
INSERT INTO `user_roles` VALUES ('Admin'),('Quality Assurance Coordinator'),('Quality Assurance Manager'),('Staff');
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `USER_ID` int NOT NULL,
  `FIRST_NAME` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `LAST_NAME` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `EMAIL` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `PASSWORD` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL,
  `ROLE_NAME` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Staff',
  `DATE_REGISTERED` timestamp NOT NULL,
  `DEPT_ID` int DEFAULT NULL,
  PRIMARY KEY (`USER_ID`),
  UNIQUE KEY `ID` (`USER_ID`),
  UNIQUE KEY `ID_2` (`USER_ID`),
  KEY `fk_role_name` (`ROLE_NAME`),
  KEY `fk_users_dept` (`DEPT_ID`),
  CONSTRAINT `fk_role_name` FOREIGN KEY (`ROLE_NAME`) REFERENCES `user_roles` (`ROLE_NAME`),
  CONSTRAINT `fk_users_dept` FOREIGN KEY (`DEPT_ID`) REFERENCES `department` (`DEPT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (10736,'Admin','Admin','admin@gmail.com','$2y$10$NApLT0FPC3MClZbQA90vxOGdbUlUGQbbE8gmnWxGYF9VAJUwowlZG','Admin','2023-05-21 07:58:31',2),(2673008,'Barry','Allen','barry22@gmail.com','$2y$10$AUuvMKhLs/hey/pifluGxuODP/9KpiV6tZu.uNNLeixOG6DcbUtiC','Staff','2023-05-21 07:16:02',2),(2748726,'Shinobu','Kocho','shin22@gmail.com','$2y$10$nyKxMzWuW1luyaF6N26TqO3H1v42e5UuXnKq5/2yUydpGeXo2aQe.','Staff','2023-05-10 09:29:39',1),(2960591,'QA','Manager','qa.manager@gmail.com','$2y$10$SRbW/D7zc.IaaxjhdoVEFud2hAky4aKGLKGhxKUwNA46V2.a4vyoG','Quality Assurance Manager','2023-05-21 07:55:49',1),(6207020,'QA','Coordinator','qa.coordinator@gmail.com','$2y$10$mwVJ4Q17ahl2sT.aGyy2BuQ.EtbSjspzcrP4Duhxo/L7Z3e5esdSW','Quality Assurance Coordinator','2023-05-21 07:56:58',2),(629932730,'Max','Verstappen','max33@gmail.com','$2y$10$zHNduncW5Y19e8JSAXxmVehfmp426I5BTqGCaYSD8kNxX7.3isy2K','Staff','2023-05-22 04:12:27',2);
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

-- Dump completed on 2023-05-23 13:10:12
