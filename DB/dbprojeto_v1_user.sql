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
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` smallint NOT NULL DEFAULT '10',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  `verification_token` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (2,'paulo','u9DFFliv9wm_BLQvo2D3F8-zG46eH-8n','$2y$13$DzcfftOpwi.6mj5Kl7tNt.UQN8hWsXeOM.9B3QoeIXcscd7146xFK',NULL,'paulopinto@gmail.com',9,1761062409,1761062409,'VkmVg7eBDWsZyaEBBS_BYzAdkq5dyWsE_1761062409'),(3,'admin','gM7HgFqFaW_DB1vawd_19F3gzb2En7xV','$2y$13$BM2y2tP26Itu6Ke7Z1a6iuxxxOH6l55K522ix3v6SIjpYkVY1RnNm',NULL,'admin@admin.com',10,1761065072,1761065072,'7zC_BWu-jEPviCm593XDx46tmYKFRlWR_1761065072'),(4,'clienteTeste','8gGVxQoYpLDM1bYK2Pqa90GmVYMNOGcp','$2y$13$pq2XuexwWdbtHm2ZirBpeeDSTFNwKj8k6EmZ5BGYrbPYkLFRzkPcy',NULL,'clienteTeste@gmail.com',10,1761660398,1761660398,NULL),(5,'clienteTeste1','Be2x_TuqhezTCk6Tw_g0IWUAN2HupUQF','$2y$13$qYyplWCCQDsBsLitpzX2YOQAkIZZl64ZJ7aDsBur6wDf.fRIRAYae',NULL,'clienteTeste1@gmail.com',10,1761660599,1761660599,NULL),(6,'clienteTeste2','S8vqo6S4arCajep6EPH-DgRYVWWf1nl0','$2y$13$OvYo7FAukoOf7elnQIGQhulMJk7Gz.XqE9CQFUcY931RN3q2TqFZG',NULL,'clienteTeste2@gmail.com',10,1761660727,1761660727,NULL),(7,'clienteTeste3','J-ykusgm_h7z375GK5bHyillA2AYkY0u','$2y$13$umzyBnxx7EXVaapOB/OVretIoCfHBDtuiRjndz.wjzwMmepSxaQsC',NULL,'clienteTeste3@gmail.com',10,1761661366,1761661366,NULL),(8,'clienteTeste4','0ll4zlQX8PKNaul8P2QC_c9nGMfbGzgq','$2y$13$aS9K9tkgPpS1USgw1VVnPOOB57fHQJMVNUTdD.6UJ1DenyWbHi5U2',NULL,'clienteTeste4@gmail.com',10,1761661618,1761661618,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-31 15:01:19
