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
-- Table structure for table `request_attachment`
--

DROP TABLE IF EXISTS `request_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `request_attachment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `uploaded_by` int NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int NOT NULL,
  `type` enum('generic','quotation') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'generic' COMMENT 'Define o tipo de anexo: ficheiro genérico ou orçamento.',
  `approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Estado de aprovação do anexo quando for um orçamento: pendente, aprovado ou rejeitado.',
  `approved_by` int DEFAULT NULL COMMENT 'Utilizador que aprovou ou rejeitou o orçamento (geralmente o cliente).',
  `approved_at` int DEFAULT NULL COMMENT 'Timestamp da aprovação/rejeição do orçamento.',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observações deixadas pelo cliente ao aprovar ou rejeitar o orçamento.',
  PRIMARY KEY (`id`),
  KEY `idx_attach_request` (`request_id`),
  KEY `idx_attach_user` (`uploaded_by`),
  KEY `fk_request_attachment_approved_by` (`approved_by`),
  CONSTRAINT `fk_attach_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_attach_user` FOREIGN KEY (`uploaded_by`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_request_attachment_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Anexos associados a um pedido. Pode incluir fotos, relatórios ou orçamentos que requerem aprovação do cliente.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_attachment`
--

LOCK TABLES `request_attachment` WRITE;
/*!40000 ALTER TABLE `request_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_attachment` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-04 16:25:28
