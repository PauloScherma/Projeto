-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
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
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `address` (
  `id` int NOT NULL AUTO_INCREMENT,
  `profile_id` int NOT NULL,
  `street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Portugal',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_address_profile` (`profile_id`),
  KEY `idx_address_profile_type` (`profile_id`),
  CONSTRAINT `fk_address_profile` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Armazena o endereço principal associado ao perfil do utilizador.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Atribui roles concretos aos utilizadores individuais.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('admin','53',1764689785),('cliente','55',1764689904),('cliente','57',1764693766),('gestor','54',1764689852),('tecnico','56',1764689926),('tecnico','58',1765126489);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` smallint NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `rule_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Define as permissões as roles do sistema RBAC.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('admin',1,NULL,NULL,NULL,1764689785,1764689785),('appointment.create',2,'Criar marcações',NULL,NULL,1764689785,1764689785),('appointment.delete',2,'Remover marcações',NULL,NULL,1764689785,1764689785),('appointment.update',2,'Atualizar marcações',NULL,NULL,1764689785,1764689785),('appointment.view',2,'Ver marcações',NULL,NULL,1764689785,1764689785),('assignment.assignTechnician',2,'Atribuir técnico a pedido',NULL,NULL,1764689785,1764689785),('assignment.changeTechnician',2,'Mudar técnico do pedido',NULL,NULL,1764689785,1764689785),('attachment.create',2,'Criar anexos do pedido',NULL,NULL,1764689785,1764689785),('attachment.delete',2,'Remover anexos do pedido',NULL,NULL,1764689785,1764689785),('attachment.update',2,'Atualizar anexos do pedido',NULL,NULL,1764689785,1764689785),('attachment.view',2,'Ver anexos do pedido',NULL,NULL,1764689785,1764689785),('cliente',1,NULL,NULL,NULL,1764689785,1764689785),('dashboard.view',2,'Ver dashboard',NULL,NULL,1764689785,1764689785),('gestor',1,NULL,NULL,NULL,1764689785,1764689785),('rating.create',2,'Criar avaliações',NULL,NULL,1764689785,1764689785),('rating.delete',2,'Remover avaliações',NULL,NULL,1764689785,1764689785),('rating.update',2,'Atualizar avaliações',NULL,NULL,1764689785,1764689785),('rating.view',2,'Ver avaliações',NULL,NULL,1764689785,1764689785),('request.cancel',2,'Cancelar pedidos',NULL,NULL,1764689785,1764689785),('request.changePriority',2,'Alterar prioridade do pedido',NULL,NULL,1764689785,1764689785),('request.changeStatus',2,'Alterar estado do pedido',NULL,NULL,1764689785,1764689785),('request.create',2,'Criar pedidos',NULL,NULL,1764689785,1764689785),('request.delete',2,'Remover pedidos',NULL,NULL,1764689785,1764689785),('request.update',2,'Atualizar pedidos',NULL,NULL,1764689785,1764689785),('request.validateBudget',2,'Validar ou rejeitar orçamento do pedido',NULL,NULL,1764689785,1764689785),('request.view',2,'Ver pedidos',NULL,NULL,1764689785,1764689785),('tecnico',1,NULL,NULL,NULL,1764689785,1764689785),('user.changeAvailability',2,'Atualizar disponibilidade do técnico',NULL,NULL,1764689785,1764689785),('user.create',2,'Criar utilizadores',NULL,NULL,1764689785,1764689785),('user.delete',2,'Remover utilizadores',NULL,NULL,1764689785,1764689785),('user.update',2,'Atualizar utilizadores',NULL,NULL,1764689785,1764689785),('user.view',2,'Ver utilizadores',NULL,NULL,1764689785,1764689785);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `child` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Relaciona papéis e permissões entre si.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('tecnico','appointment.create'),('gestor','appointment.delete'),('tecnico','appointment.update'),('tecnico','appointment.view'),('gestor','assignment.assignTechnician'),('gestor','assignment.changeTechnician'),('tecnico','attachment.create'),('gestor','attachment.delete'),('tecnico','attachment.update'),('tecnico','attachment.view'),('gestor','dashboard.view'),('admin','gestor'),('cliente','rating.create'),('tecnico','rating.create'),('gestor','rating.delete'),('gestor','rating.update'),('cliente','rating.view'),('tecnico','rating.view'),('gestor','request.cancel'),('tecnico','request.changePriority'),('tecnico','request.changeStatus'),('cliente','request.create'),('cliente','request.delete'),('gestor','request.update'),('cliente','request.validateBudget'),('cliente','request.view'),('tecnico','request.view'),('gestor','tecnico'),('admin','user.changeAvailability'),('gestor','user.create'),('gestor','user.delete'),('gestor','user.update'),('gestor','user.view');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Regras personalizadas do sistema RBAC usadas para lógica condicional de permissões.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar_event`
--

DROP TABLE IF EXISTS `calendar_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendar_event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `technician_id` int NOT NULL,
  `start_at` int NOT NULL,
  `end_at` int NOT NULL,
  `status` enum('scheduled','rescheduled','done','canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'scheduled',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cal_tech_window` (`technician_id`,`start_at`,`end_at`),
  KEY `fk_cal_request` (`request_id`),
  CONSTRAINT `fk_cal_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cal_tech` FOREIGN KEY (`technician_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Agenda os períodos de execução dos pedidos pelos técnicos.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar_event`
--

LOCK TABLES `calendar_event` WRITE;
/*!40000 ALTER TABLE `calendar_event` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Controla as migrations executadas no Yii2 para manter a versão da estrutura da base de dados.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1761056651),('m140506_102106_rbac_init',1761057082),('m170907_052038_rbac_add_index_on_auth_assignment_user_id',1761057082),('m180523_151638_rbac_updates_indexes_without_prefix',1761057082),('m200409_110543_rbac_update_mssql_trigger',1761057082),('m130524_201442_init',1761057597),('m190124_110200_add_verification_token_column_to_user_table',1761057597),('m251021_162554_create_admin_user',1761065072);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `first_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `availability` enum('disponivel','indisponivel') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'disponivel',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_profile_user` (`user_id`),
  CONSTRAINT `fk_profile_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contém informações adicionais do user.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `title` varchar(140) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `priority` enum('low','medium','high') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` enum('new','in_progress','completed','canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `current_technician_id` int DEFAULT NULL,
  `canceled_at` datetime DEFAULT NULL,
  `canceled_by` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_request_customer_created` (`customer_id`,`created_at`),
  KEY `idx_request_status` (`status`),
  KEY `fk_request_currtech` (`current_technician_id`),
  KEY `fk_request_canceled_by` (`canceled_by`),
  CONSTRAINT `fk_request_currtech` FOREIGN KEY (`current_technician_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_request_customer` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabela central dos pedidos de serviço. Guarda o cliente, técnico atual, estado, prioridade e timestamps.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request`
--

LOCK TABLES `request` WRITE;
/*!40000 ALTER TABLE `request` DISABLE KEYS */;
INSERT INTO `request` VALUES (37,55,'clienteRequest','clienteRequest','medium','completed',58,NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_assignment`
--

DROP TABLE IF EXISTS `request_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `request_assignment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `from_technician` int DEFAULT NULL,
  `to_technician` int NOT NULL,
  `changed_by` int NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_oldTechnician_idx` (`from_technician`),
  KEY `fk_newTechnician_idx` (`to_technician`),
  KEY `fk_requestID_idx` (`request_id`),
  KEY `fk_changedByID_idx` (`changed_by`),
  CONSTRAINT `fk_changedBy` FOREIGN KEY (`changed_by`) REFERENCES `user` (`id`),
  CONSTRAINT `fk_newTechnician` FOREIGN KEY (`to_technician`) REFERENCES `user` (`id`),
  CONSTRAINT `fk_oldTechnician` FOREIGN KEY (`from_technician`) REFERENCES `user` (`id`),
  CONSTRAINT `fk_requestID` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_assignment`
--

LOCK TABLES `request_assignment` WRITE;
/*!40000 ALTER TABLE `request_assignment` DISABLE KEYS */;
INSERT INTO `request_assignment` VALUES (1,37,58,56,53,'2025-12-07 17:07:47'),(2,37,58,56,53,'2025-12-07 17:07:59'),(3,37,56,58,53,'2025-12-07 17:08:03');
/*!40000 ALTER TABLE `request_assignment` ENABLE KEYS */;
UNLOCK TABLES;

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
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `type` enum('generic','quotation') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'generic' COMMENT 'Define o tipo de anexo: ficheiro genérico ou orçamento.',
  `approval_status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Estado de aprovação do anexo quando for um orçamento: pendente, aprovado ou rejeitado.',
  `approved_by` int DEFAULT NULL COMMENT 'Utilizador que aprovou ou rejeitou o orçamento (geralmente o cliente).',
  `approved_at` int DEFAULT NULL COMMENT 'Timestamp da aprovação/rejeição do orçamento.',
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observações deixadas pelo cliente ao aprovar ou rejeitar o orçamento.',
  PRIMARY KEY (`id`),
  KEY `idx_attach_request` (`request_id`),
  KEY `idx_attach_user` (`uploaded_by`),
  KEY `fk_request_attachment_approved_by` (`approved_by`),
  CONSTRAINT `fk_attach_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_attach_user` FOREIGN KEY (`uploaded_by`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_request_attachment_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Anexos associados a um pedido. Pode incluir fotos, relatórios ou orçamentos que requerem aprovação do cliente.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_attachment`
--

LOCK TABLES `request_attachment` WRITE;
/*!40000 ALTER TABLE `request_attachment` DISABLE KEYS */;
INSERT INTO `request_attachment` VALUES (29,37,55,'uploads/attachments/clienteRequest.txt','clienteRequest.txt','2025-12-07 14:50:24','generic',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `request_attachment` ENABLE KEYS */;
UNLOCK TABLES;

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
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_msg_request_created` (`request_id`,`created_at`),
  KEY `idx_msg_sender` (`sender_id`),
  KEY `idx_msg_recipient_unread` (`recipient_id`),
  CONSTRAINT `fk_msg_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_msg_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_msg_sender` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Mensagens trocadas entre cliente e técnico dentro de um pedido. Guarda quem enviou e quem recebeu.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_message`
--

LOCK TABLES `request_message` WRITE;
/*!40000 ALTER TABLE `request_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_rating`
--

DROP TABLE IF EXISTS `request_rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `request_rating` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `title` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` tinyint NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_request_author_direction` (`request_id`),
  KEY `idx_rr_request` (`request_id`),
  KEY `fk_rr_created_by_idx` (`created_by`),
  CONSTRAINT `fk_rr_created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `fk_rr_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Avaliações de pedidos: cliente avalia técnico e técnico avalia cliente.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_rating`
--

LOCK TABLES `request_rating` WRITE;
/*!40000 ALTER TABLE `request_rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_status_history`
--

DROP TABLE IF EXISTS `request_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `request_status_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `from_status` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_status` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` int NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rsh_request_created` (`request_id`,`created_at`),
  KEY `fk_rsh_user` (`changed_by`),
  CONSTRAINT `fk_changeby` FOREIGN KEY (`changed_by`) REFERENCES `user` (`id`),
  CONSTRAINT `fk_rsh_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Histórico das mudanças de estado de cada pedido, indicando quem alterou e quando.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_status_history`
--

LOCK TABLES `request_status_history` WRITE;
/*!40000 ALTER TABLE `request_status_history` DISABLE KEYS */;
INSERT INTO `request_status_history` VALUES (1,37,'new','in_progress',53,'2025-12-07 15:57:06'),(2,37,'in_progress','completed',53,'2025-12-07 16:08:56');
/*!40000 ALTER TABLE `request_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` smallint NOT NULL DEFAULT '10',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  `verification_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Guarda as credenciais básicas dos utilizadores (login, email, password_hash e estado da conta).';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (53,'admin','A9bF7OybwFOrlBLkB-nUYNTqDwqvC8vC','$2y$13$zJvX4LaHPq4YYURgtH0M3.aarSCP091rm/HFjxuTHK5/VlNfhbAMi',NULL,'admin@admin.com',10,1764689785,1764689785,'TbqaslkiM2BcfpG-JSMJGxMfMP44_uiV_1764689785'),(54,'gestor','iATxPhqLTVNrhdqydtldSObEtsYWjkI2','$2y$13$jZaPFxIiXHVOdu2.Ng0DYud978TcLHHP/HF2kf7Idc6XzK2Onaqz6',NULL,'gestor@gestor.com',10,1764689852,1764689852,NULL),(55,'cliente','LH_h9k6fCvl03aarl1yLGt6oiuUYG0zY','$2y$13$Oskqi5CNgaIuNd31Kzl7KO974ubdtB82evKX4xe0UQYJ0mAoKLSa.',NULL,'cliente@cliente.com',10,1764689903,1764689903,NULL),(56,'tecnico','zpWQR8LDTrFPcW5Wvb1D3aDGPcKb8a1q','$2y$13$nDpNxHGQUtY.nC3ByZ/xy.Tm3qwrvqxQM6KoGbzba5DGrj/lIilwS',NULL,'tecnico@tecnico.com',10,1764689926,1764689926,NULL),(57,'teste','Fepmp17Anf679XKILmUim22dLAO2ynmD','$2y$13$BYc8NrlJnf.h0e1IcO0Pg.AhTzTlFSD4vJwlVotir37nNhwDUJmhW',NULL,'teste@teste.com',10,1764693766,1764693766,NULL),(58,'technician2','hV3vxP9ocN1l91mNzuWYOVSigbRJw8Ge','$2y$13$tAd.FVBWUJVD6AhaVdpWD.IXc.IaFju/NIIW328RJGMdu3A0s4URC',NULL,'technician2@technician2.com',10,1765126489,1765126489,NULL);
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

-- Dump completed on 2025-12-07 17:36:25
