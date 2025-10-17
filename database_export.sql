-- MySQL dump 10.13  Distrib 8.4.6, for Linux (x86_64)
--
-- Host: localhost    Database: chris_pincel
-- ------------------------------------------------------
-- Server version	8.4.6

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `agendamentos`
--

DROP TABLE IF EXISTS `agendamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agendamentos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `servico_id` bigint unsigned NOT NULL,
  `promocao_id` bigint unsigned DEFAULT NULL,
  `codigo_cupom_usado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agendamento_original_id` bigint unsigned DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDENTE',
  `tipo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NORMAL',
  `data_hora_inicio` datetime NOT NULL,
  `data_hora_fim` datetime NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `valor_desconto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `valor_original` decimal(10,2) DEFAULT NULL,
  `valor_sinal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `forma_pagamento` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forma_pagamento_sinal` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxa_cancelamento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pagamento_confirmado` tinyint(1) NOT NULL DEFAULT '0',
  `lembrete_enviado` tinyint(1) NOT NULL DEFAULT '0',
  `canal_origem` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SITE',
  `metadados` json DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agendamentos_user_id_foreign` (`user_id`),
  KEY `agendamentos_servico_id_foreign` (`servico_id`),
  KEY `agendamentos_agendamento_original_id_foreign` (`agendamento_original_id`),
  KEY `agendamentos_data_hora_inicio_servico_id_index` (`data_hora_inicio`,`servico_id`),
  KEY `agendamentos_status_data_hora_inicio_index` (`status`,`data_hora_inicio`),
  KEY `agendamentos_promocao_id_foreign` (`promocao_id`),
  CONSTRAINT `agendamentos_agendamento_original_id_foreign` FOREIGN KEY (`agendamento_original_id`) REFERENCES `agendamentos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `agendamentos_promocao_id_foreign` FOREIGN KEY (`promocao_id`) REFERENCES `promocoes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `agendamentos_servico_id_foreign` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `agendamentos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agendamentos`
--

LOCK TABLES `agendamentos` WRITE;
/*!40000 ALTER TABLE `agendamentos` DISABLE KEYS */;
INSERT INTO `agendamentos` VALUES (90,1,5,5,'NATAL',NULL,'CONCLUIDO','NORMAL','2025-11-10 09:00:00','2025-11-10 09:30:00',34.00,6.00,40.00,10.20,'credit_card','credit_card',0.00,0,0,'SITE',NULL,'dqdq','2025-10-17 01:45:21','2025-10-17 01:46:00'),(91,5,5,NULL,NULL,NULL,'concluido','normal','2025-09-14 09:00:24','2025-09-14 09:30:24',40.00,0.00,40.00,12.00,'pix','pix',0.00,1,0,'SITE',NULL,NULL,'2025-10-17 01:47:24','2025-10-17 01:47:24'),(92,6,5,NULL,NULL,NULL,'concluido','normal','2025-08-27 14:30:24','2025-08-27 15:00:24',40.00,0.00,40.00,12.00,'pix','pix',0.00,1,0,'SITE',NULL,NULL,'2025-10-17 01:47:24','2025-10-17 01:47:24'),(93,6,6,NULL,NULL,NULL,'concluido','normal','2025-09-09 12:00:24','2025-09-09 12:45:24',60.00,0.00,60.00,18.00,'credit_card','pix',0.00,1,0,'SITE',NULL,NULL,'2025-10-17 01:47:24','2025-10-17 01:47:24'),(94,6,6,NULL,NULL,NULL,'concluido','normal','2025-09-23 14:30:24','2025-09-23 15:15:24',60.00,0.00,60.00,18.00,'pix','pix',0.00,1,0,'SITE',NULL,NULL,'2025-10-17 01:47:24','2025-10-17 01:47:24'),(95,5,8,NULL,NULL,NULL,'concluido','normal','2025-09-22 09:30:24','2025-09-22 10:30:24',80.00,0.00,80.00,24.00,'credit_card','pix',0.00,1,0,'SITE',NULL,NULL,'2025-10-17 01:47:24','2025-10-17 01:47:24'),(96,6,6,NULL,NULL,NULL,'concluido','normal','2025-09-22 11:00:24','2025-09-22 11:45:24',60.00,0.00,60.00,18.00,'credit_card','pix',0.00,1,0,'SITE',NULL,NULL,'2025-10-17 01:47:24','2025-10-17 01:47:24'),(97,6,5,NULL,NULL,NULL,'concluido','normal','2025-10-11 17:00:24','2025-10-11 17:30:24',40.00,0.00,40.00,12.00,'debit_card','pix',0.00,1,0,'SITE',NULL,NULL,'2025-10-17 01:47:24','2025-10-17 01:47:24'),(98,6,8,NULL,NULL,NULL,'concluido','normal','2025-09-26 17:30:24','2025-09-26 18:30:24',80.00,0.00,80.00,24.00,'credit_card','pix',0.00,1,0,'SITE',NULL,NULL,'2025-10-17 01:47:24','2025-10-17 01:47:24'),(99,6,6,NULL,NULL,NULL,'concluido','normal','2025-08-24 10:30:24','2025-08-24 11:15:24',60.00,0.00,60.00,18.00,'debit_card','pix',0.00,1,0,'SITE',NULL,NULL,'2025-10-17 01:47:24','2025-10-17 01:47:24'),(100,4,8,NULL,NULL,NULL,'concluido','normal','2025-10-15 12:00:24','2025-10-15 13:00:24',80.00,0.00,80.00,24.00,'debit_card','pix',0.00,1,0,'SITE',NULL,NULL,'2025-10-17 01:47:24','2025-10-17 01:47:24'),(101,7,5,NULL,NULL,NULL,'CONCLUIDO','NORMAL','2025-10-30 09:00:00','2025-10-30 09:30:00',40.00,0.00,NULL,12.00,'credit_card','credit_card',0.00,0,0,'SITE',NULL,'w','2025-10-17 01:57:21','2025-10-17 01:57:44'),(102,4,5,NULL,NULL,NULL,'concluido','NORMAL','2025-10-14 22:59:28','2025-10-14 22:59:28',40.00,0.00,NULL,0.00,NULL,NULL,0.00,0,0,'SITE',NULL,'Agendamento de teste para avaliação','2025-10-17 01:59:28','2025-10-17 01:59:28');
/*!40000 ALTER TABLE `agendamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avaliacoes`
--

DROP TABLE IF EXISTS `avaliacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avaliacoes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agendamento_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `nota` tinyint unsigned NOT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci,
  `publicado` tinyint(1) NOT NULL DEFAULT '0',
  `publicado_em` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `avaliacoes_agendamento_id_unique` (`agendamento_id`),
  KEY `avaliacoes_user_id_foreign` (`user_id`),
  CONSTRAINT `avaliacoes_agendamento_id_foreign` FOREIGN KEY (`agendamento_id`) REFERENCES `agendamentos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `avaliacoes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacoes`
--

LOCK TABLES `avaliacoes` WRITE;
/*!40000 ALTER TABLE `avaliacoes` DISABLE KEYS */;
INSERT INTO `avaliacoes` VALUES (8,97,6,5,'Atendimento de primeira! Ambiente limpo, profissional qualificada e resultado incrível. Voltarei sempre!',0,NULL,'2025-10-17 01:47:24','2025-10-17 01:47:24'),(9,98,6,4,'Muito bom! Gostei bastante do resultado. Profissional competente e educada.',1,'2025-10-16 01:47:24','2025-10-17 01:47:24','2025-10-17 01:47:24'),(10,99,6,5,'Simplesmente perfeito! Desde o agendamento até o resultado final. Estou apaixonada!',0,NULL,'2025-10-17 01:47:24','2025-10-17 01:47:24'),(11,100,4,5,'Adorei o atendimento! Super carinhosa e cuidadosa. O resultado ficou perfeito, exatamente como eu queria.',1,'2025-10-14 01:47:24','2025-10-17 01:47:24','2025-10-17 01:47:24');
/*!40000 ALTER TABLE `avaliacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bloqueios_horario`
--

DROP TABLE IF EXISTS `bloqueios_horario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bloqueios_horario` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inicio` datetime NOT NULL,
  `fim` datetime NOT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recorrente` tinyint(1) NOT NULL DEFAULT '0',
  `dia_semana` tinyint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bloqueios_horario_inicio_fim_index` (`inicio`,`fim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloqueios_horario`
--

LOCK TABLES `bloqueios_horario` WRITE;
/*!40000 ALTER TABLE `bloqueios_horario` DISABLE KEYS */;
/*!40000 ALTER TABLE `bloqueios_horario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracoes`
--

DROP TABLE IF EXISTS `configuracoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracoes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chave` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` json NOT NULL,
  `descricao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configuracoes_chave_unique` (`chave`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracoes`
--

LOCK TABLES `configuracoes` WRITE;
/*!40000 ALTER TABLE `configuracoes` DISABLE KEYS */;
INSERT INTO `configuracoes` VALUES (1,'tolerancia_atraso_minutos','{\"valor\": 15}','Minutos de tolerância antes de marcar como atraso.','2025-10-15 22:56:16','2025-10-16 03:31:57'),(2,'horas_minimas_cancelamento','{\"valor\": 24}','Horas mínimas para cancelamento sem multa.','2025-10-15 22:56:16','2025-10-16 03:31:57'),(3,'taxa_cancelamento_percentual','{\"valor\": \"50\"}','Percentual aplicado ao sinal em cancelamentos tardios.','2025-10-15 22:56:16','2025-10-16 22:33:22'),(4,'whatsapp_numero','{\"valor\": \"+5589994221565\"}','Número do remetente WhatsApp.','2025-10-15 22:56:16','2025-10-16 22:33:22'),(5,'horario_lembrete_horas','{\"valor\": \"24\"}','Antecedência em horas para envio de lembretes.','2025-10-15 22:56:16','2025-10-16 22:33:22'),(6,'pontos_por_real_gasto','{\"valor\": 1}','Taxa de conversão de reais para pontos.','2025-10-15 22:56:16','2025-10-16 03:31:57'),(7,'pontos_para_desconto','{\"valor\": 100}','Quantidade de pontos necessária para resgate.','2025-10-15 22:56:16','2025-10-16 03:31:57'),(8,'valor_desconto_pontos','{\"valor\": 10}','Valor em reais concedido ao resgatar pontos.','2025-10-15 22:56:16','2025-10-16 03:31:57'),(9,'dias_expiracao_pontos','{\"valor\": \"365\"}','Dias até expirar pontos não utilizados.','2025-10-15 22:56:16','2025-10-16 22:33:22'),(10,'sinal_percentual_padrao','{\"valor\": 30}','Percentual do valor cobrado como sinal.','2025-10-15 22:56:16','2025-10-16 03:31:57'),(11,'site_nome','{\"valor\": \"Chris Pincel Mágico\"}',NULL,'2025-10-16 03:31:57','2025-10-16 03:31:57'),(12,'site_email','{\"valor\": \"contato@chrispincelmagico.com\"}',NULL,'2025-10-16 03:31:57','2025-10-16 03:31:57'),(13,'site_telefone','{\"valor\": \"(89) 99422-1565\"}',NULL,'2025-10-16 03:31:57','2025-10-16 22:33:22'),(14,'site_endereco','{\"valor\": \"Fortaleza, CE\"}',NULL,'2025-10-16 03:31:57','2025-10-16 03:31:57'),(15,'site_instagram','{\"valor\": \"@chrispincelmagico\"}',NULL,'2025-10-16 03:31:57','2025-10-16 03:31:57'),(16,'site_facebook','{\"valor\": \"\"}',NULL,'2025-10-16 03:31:57','2025-10-16 03:31:57'),(17,'endereco_floriano','{\"valor\": \"Floriano, Piauí\"}',NULL,'2025-10-16 03:42:42','2025-10-16 03:42:42'),(18,'endereco_barao','{\"valor\": \"Barão de Grajaú, Maranhão\"}',NULL,'2025-10-16 03:42:42','2025-10-16 03:42:42'),(19,'pontos_por_real','{\"valor\": \"1\"}',NULL,'2025-10-16 03:42:42','2025-10-16 22:33:22'),(20,'desconto_pontos_percentual','{\"valor\": \"30\"}',NULL,'2025-10-16 03:42:42','2025-10-16 22:33:22'),(21,'minutos_antecedencia_cancelamento','{\"valor\": \"720\"}',NULL,'2025-10-16 03:42:42','2025-10-16 22:33:22'),(22,'limite_agendamentos_simultaneos','{\"valor\": \"3\"}',NULL,'2025-10-16 03:42:42','2025-10-16 22:33:22'),(23,'dias_antecedencia_agendamento','{\"valor\": \"30\"}',NULL,'2025-10-16 03:42:42','2025-10-16 22:33:22'),(24,'tempo_medio_atendimento','{\"valor\": \"60\"}',NULL,'2025-10-16 03:42:43','2025-10-16 22:33:22');
/*!40000 ALTER TABLE `configuracoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disponibilidades`
--

DROP TABLE IF EXISTS `disponibilidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disponibilidades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dia_semana` tinyint unsigned NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `disponibilidades_intervalo_unique` (`dia_semana`,`hora_inicio`,`hora_fim`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disponibilidades`
--

LOCK TABLES `disponibilidades` WRITE;
/*!40000 ALTER TABLE `disponibilidades` DISABLE KEYS */;
INSERT INTO `disponibilidades` VALUES (1,1,'09:00:00','18:00:00',1,'2025-10-16 04:02:36','2025-10-16 04:02:36'),(2,2,'09:00:00','18:00:00',1,'2025-10-16 04:02:36','2025-10-16 04:02:36'),(3,3,'09:00:00','18:00:00',1,'2025-10-16 04:02:36','2025-10-16 04:02:36'),(4,4,'09:00:00','18:00:00',1,'2025-10-16 04:02:36','2025-10-16 04:02:36'),(5,5,'09:00:00','18:00:00',1,'2025-10-16 04:02:36','2025-10-16 04:02:36'),(6,6,'09:00:00','14:00:00',1,'2025-10-16 04:02:36','2025-10-16 04:02:36'),(7,0,'00:00:00','00:00:00',0,'2025-10-16 04:02:36','2025-10-16 04:02:36');
/*!40000 ALTER TABLE `disponibilidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fotos_clientes`
--

DROP TABLE IF EXISTS `fotos_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fotos_clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `agendamento_id` bigint unsigned DEFAULT NULL,
  `caminho` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `legenda` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publicado` tinyint(1) NOT NULL DEFAULT '0',
  `ordem` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fotos_clientes_user_id_foreign` (`user_id`),
  KEY `fotos_clientes_agendamento_id_foreign` (`agendamento_id`),
  KEY `fotos_clientes_publicado_ordem_index` (`publicado`,`ordem`),
  CONSTRAINT `fotos_clientes_agendamento_id_foreign` FOREIGN KEY (`agendamento_id`) REFERENCES `agendamentos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fotos_clientes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fotos_clientes`
--

LOCK TABLES `fotos_clientes` WRITE;
/*!40000 ALTER TABLE `fotos_clientes` DISABLE KEYS */;
/*!40000 ALTER TABLE `fotos_clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lista_espera`
--

DROP TABLE IF EXISTS `lista_espera`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lista_espera` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `servico_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_hora_desejada` datetime DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AGUARDANDO',
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lista_espera_user_id_foreign` (`user_id`),
  KEY `lista_espera_servico_id_status_index` (`servico_id`,`status`),
  KEY `lista_espera_data_hora_desejada_index` (`data_hora_desejada`),
  CONSTRAINT `lista_espera_servico_id_foreign` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lista_espera_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lista_espera`
--

LOCK TABLES `lista_espera` WRITE;
/*!40000 ALTER TABLE `lista_espera` DISABLE KEYS */;
/*!40000 ALTER TABLE `lista_espera` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_10_15_000100_create_servicos_table',1),(5,'2025_10_15_000110_create_agendamentos_table',1),(6,'2025_10_15_000120_create_pagamentos_table',1),(7,'2025_10_15_000130_create_disponibilidades_table',1),(8,'2025_10_15_000140_create_bloqueios_horario_table',1),(9,'2025_10_15_000150_create_promocoes_table',1),(10,'2025_10_15_000160_create_pontos_fidelidade_table',1),(11,'2025_10_15_000170_create_transacoes_pontos_table',1),(12,'2025_10_15_000180_create_notificacoes_table',1),(13,'2025_10_15_000190_create_avaliacoes_table',1),(14,'2025_10_15_000200_create_fotos_clientes_table',1),(15,'2025_10_15_000210_create_configuracoes_table',1),(16,'2025_10_15_000220_create_lista_espera_table',1),(17,'2025_10_15_100500_add_is_admin_to_users_table',1),(18,'2025_10_15_210000_create_promocoes_table',2),(19,'2025_10_15_210100_create_disponibilidades_table',2),(20,'2025_10_15_215127_add_observacoes_to_agendamentos_table',3),(21,'2025_10_15_231216_add_imagem_capa_to_servicos_table',4),(22,'2025_10_15_234249_add_promocao_fields_to_agendamentos_table',5),(23,'2025_10_16_205443_add_forma_pagamento_to_agendamentos_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacoes`
--

DROP TABLE IF EXISTS `notificacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacoes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `agendamento_id` bigint unsigned DEFAULT NULL,
  `canal` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assunto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mensagem` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDENTE',
  `enviado_em` timestamp NULL DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notificacoes_user_id_foreign` (`user_id`),
  KEY `notificacoes_agendamento_id_foreign` (`agendamento_id`),
  KEY `notificacoes_canal_status_index` (`canal`,`status`),
  KEY `notificacoes_enviado_em_index` (`enviado_em`),
  CONSTRAINT `notificacoes_agendamento_id_foreign` FOREIGN KEY (`agendamento_id`) REFERENCES `agendamentos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notificacoes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacoes`
--

LOCK TABLES `notificacoes` WRITE;
/*!40000 ALTER TABLE `notificacoes` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagamentos`
--

DROP TABLE IF EXISTS `pagamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagamentos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agendamento_id` bigint unsigned NOT NULL,
  `gateway` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mercado_pago',
  `referencia_gateway` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `valor_capturado` decimal(10,2) DEFAULT NULL,
  `forma_pagamento` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `pago_em` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pagamentos_referencia_gateway_unique` (`referencia_gateway`),
  KEY `pagamentos_agendamento_id_foreign` (`agendamento_id`),
  KEY `pagamentos_status_created_at_index` (`status`,`created_at`),
  CONSTRAINT `pagamentos_agendamento_id_foreign` FOREIGN KEY (`agendamento_id`) REFERENCES `agendamentos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagamentos`
--

LOCK TABLES `pagamentos` WRITE;
/*!40000 ALTER TABLE `pagamentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pagamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pontos_fidelidade`
--

DROP TABLE IF EXISTS `pontos_fidelidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pontos_fidelidade` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `pontos_atuais` int unsigned NOT NULL DEFAULT '0',
  `pontos_acumulados` int unsigned NOT NULL DEFAULT '0',
  `ultima_atualizacao` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pontos_fidelidade_user_id_unique` (`user_id`),
  CONSTRAINT `pontos_fidelidade_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pontos_fidelidade`
--

LOCK TABLES `pontos_fidelidade` WRITE;
/*!40000 ALTER TABLE `pontos_fidelidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `pontos_fidelidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promocoes`
--

DROP TABLE IF EXISTS `promocoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promocoes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `tipo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'VALOR',
  `valor_desconto` decimal(10,2) DEFAULT NULL,
  `percentual_desconto` tinyint unsigned DEFAULT NULL,
  `codigo_cupom` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inicio_vigencia` datetime DEFAULT NULL,
  `fim_vigencia` datetime DEFAULT NULL,
  `limite_uso` int unsigned DEFAULT NULL,
  `usos_realizados` int unsigned NOT NULL DEFAULT '0',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `restricoes` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promocoes_slug_unique` (`slug`),
  UNIQUE KEY `promocoes_codigo_cupom_unique` (`codigo_cupom`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promocoes`
--

LOCK TABLES `promocoes` WRITE;
/*!40000 ALTER TABLE `promocoes` DISABLE KEYS */;
INSERT INTO `promocoes` VALUES (5,'Natal','natal','AD','PERCENTUAL',10.00,15,'NATAL','2025-10-10 00:00:00','2025-12-25 23:59:00',5,2,1,NULL,'2025-10-17 00:45:31','2025-10-17 01:45:21');
/*!40000 ALTER TABLE `promocoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicos`
--

DROP TABLE IF EXISTS `servicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `imagem_capa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duracao_minutos` smallint unsigned NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `preco_retoque` decimal(10,2) DEFAULT NULL,
  `dias_para_retoque` smallint unsigned NOT NULL DEFAULT '30',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `servicos_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicos`
--

LOCK TABLES `servicos` WRITE;
/*!40000 ALTER TABLE `servicos` DISABLE KEYS */;
INSERT INTO `servicos` VALUES (5,'Design de Sobrancelhas','design-de-sobrancelhas','Design de sobrancelhas com pinça',NULL,30,40.00,NULL,30,1,'2025-10-16 04:18:21','2025-10-16 04:18:21'),(6,'Henna','henna','Coloração de sobrancelhas com henna',NULL,45,60.00,NULL,30,1,'2025-10-16 04:18:21','2025-10-16 04:18:21'),(8,'Limpeza de Pele','limpeza-pele','Limpeza facial profunda',NULL,60,80.00,NULL,30,1,'2025-10-16 04:18:21','2025-10-16 04:18:21');
/*!40000 ALTER TABLE `servicos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transacoes_pontos`
--

DROP TABLE IF EXISTS `transacoes_pontos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transacoes_pontos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pontos_fidelidade_id` bigint unsigned NOT NULL,
  `agendamento_id` bigint unsigned DEFAULT NULL,
  `tipo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pontos` int NOT NULL,
  `valor_referencia` decimal(10,2) DEFAULT NULL,
  `descricao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadados` json DEFAULT NULL,
  `registrado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transacoes_pontos_pontos_fidelidade_id_foreign` (`pontos_fidelidade_id`),
  KEY `transacoes_pontos_agendamento_id_foreign` (`agendamento_id`),
  KEY `transacoes_pontos_tipo_registrado_em_index` (`tipo`,`registrado_em`),
  CONSTRAINT `transacoes_pontos_agendamento_id_foreign` FOREIGN KEY (`agendamento_id`) REFERENCES `agendamentos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transacoes_pontos_pontos_fidelidade_id_foreign` FOREIGN KEY (`pontos_fidelidade_id`) REFERENCES `pontos_fidelidade` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transacoes_pontos`
--

LOCK TABLES `transacoes_pontos` WRITE;
/*!40000 ALTER TABLE `transacoes_pontos` DISABLE KEYS */;
/*!40000 ALTER TABLE `transacoes_pontos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test Users','test@example.com','2025-10-15 22:56:16','$2y$12$aAlLHaTskSH88t1uLk3Dz.pocJhRqHtOdzohpxf2i4nzq.TCsb4wy',1,'SU2nG36TtsF8yfy3Sac2GqIUsxEHd9A8KYuvfVH6dyS2a36uedon6TjEQFgK','2025-10-15 22:56:16','2025-10-17 00:58:56'),(4,'Maria Silva','maria.silva@example.com',NULL,'$2y$12$Niu421n0NwgVOUdpf2/yOudla3CCPUJAYokGodqlc76af4y2jDMAG',0,NULL,'2025-10-17 01:46:05','2025-10-17 01:46:05'),(5,'Ana Santos','ana.santos@example.com',NULL,'$2y$12$TMcYz4DEHhD7conuDcLPvegCbDiTi3WREn/2OShwpm6Q.dSDSa66G',0,NULL,'2025-10-17 01:46:05','2025-10-17 01:46:05'),(6,'Juliana Costa','juliana.costa@example.com',NULL,'$2y$12$16ptfT5RlP0ooqRGlUy89usarTG99hHWC53wuVnkSgnmMdkk0Hm0u',0,NULL,'2025-10-17 01:46:05','2025-10-17 01:46:05'),(7,'PEDRO LUCAS FERREIRA BOSA','pedrolucasfbosa@gmail.com',NULL,'$2y$12$.5LkM8VM47Rre2h92iCWK.v2OR1q3WUhzSpopwurDbNckBBdRe4s2',0,NULL,'2025-10-17 01:57:02','2025-10-17 01:57:02');
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

-- Dump completed on 2025-10-16 20:48:41
