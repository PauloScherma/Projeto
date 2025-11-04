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
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` smallint NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Define as permissões as roles do sistema RBAC.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('admin',1,NULL,NULL,NULL,1761928994,1761928994),('appointment.create',2,'Criar marcações',NULL,NULL,1761928994,1761928994),('appointment.delete',2,'Remover marcações',NULL,NULL,1761928994,1761928994),('appointment.update',2,'Atualizar marcações',NULL,NULL,1761928994,1761928994),('appointment.view',2,'Ver marcações',NULL,NULL,1761928994,1761928994),('assignment.assignTechnician',2,'Atribuir técnico a pedido',NULL,NULL,1761928994,1761928994),('assignment.changeTechnician',2,'Mudar técnico do pedido',NULL,NULL,1761928994,1761928994),('attachment.create',2,'Criar anexos do pedido',NULL,NULL,1761928994,1761928994),('attachment.delete',2,'Remover anexos do pedido',NULL,NULL,1761928994,1761928994),('attachment.update',2,'Atualizar anexos do pedido',NULL,NULL,1761928994,1761928994),('attachment.view',2,'Ver anexos do pedido',NULL,NULL,1761928994,1761928994),('cliente',1,NULL,NULL,NULL,1761928994,1761928994),('dashboard.view',2,'Ver dashboard',NULL,NULL,1761928994,1761928994),('gestor',1,NULL,NULL,NULL,1761928994,1761928994),('rating.create',2,'Criar avaliações',NULL,NULL,1761928994,1761928994),('rating.delete',2,'Remover avaliações',NULL,NULL,1761928994,1761928994),('rating.update',2,'Atualizar avaliações',NULL,NULL,1761928994,1761928994),('rating.view',2,'Ver avaliações',NULL,NULL,1761928994,1761928994),('request.cancel',2,'Cancelar pedidos',NULL,NULL,1761928994,1761928994),('request.changePriority',2,'Alterar prioridade do pedido',NULL,NULL,1761928994,1761928994),('request.changeStatus',2,'Alterar estado do pedido',NULL,NULL,1761928994,1761928994),('request.create',2,'Criar pedidos',NULL,NULL,1761928994,1761928994),('request.delete',2,'Remover pedidos',NULL,NULL,1761928994,1761928994),('request.update',2,'Atualizar pedidos',NULL,NULL,1761928994,1761928994),('request.validateBudget',2,'Validar ou rejeitar orçamento do pedido',NULL,NULL,1761928994,1761928994),('request.view',2,'Ver pedidos',NULL,NULL,1761928994,1761928994),('tecnico',1,NULL,NULL,NULL,1761928994,1761928994),('user.changeAvailability',2,'Atualizar disponibilidade do técnico',NULL,NULL,1761928994,1761928994),('user.create',2,'Criar utilizadores',NULL,NULL,1761928994,1761928994),('user.delete',2,'Remover utilizadores',NULL,NULL,1761928994,1761928994),('user.update',2,'Atualizar utilizadores',NULL,NULL,1761928994,1761928994),('user.view',2,'Ver utilizadores',NULL,NULL,1761928994,1761928994);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-04 16:20:37
