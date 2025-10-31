-- MySQL dump 10.13  Distrib 9.1.0, for Win64 (x86_64)
--
-- Host: localhost    Database: dbprojeto_v1
-- ------------------------------------------------------
-- Server version	9.1.0

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
-- Table structure for table `request_message`
--

DROP TABLE IF EXISTS `request_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `request_message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `recipient_id` int NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_msg_request_created` (`request_id`,`created_at`),
  KEY `idx_msg_sender` (`sender_id`),
  KEY `idx_msg_recipient_unread` (`recipient_id`),
  CONSTRAINT `fk_msg_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_msg_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_msg_sender` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Mensagens trocadas entre cliente e técnico dentro de um pedido. Guarda quem enviou e quem recebeu.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_message`
--

LOCK TABLES `request_message` WRITE;
/*!40000 ALTER TABLE `request_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_message` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-31 16:47:44
