-- MySQL dump 10.13  Distrib 5.7.37, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: login
-- ------------------------------------------------------
-- Server version	5.7.37-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attribute` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'address',NULL,'2022-03-24 02:07:52','2022-04-06 20:56:32',NULL),(2,'email',NULL,'2022-03-24 02:07:52','2022-04-06 20:56:32',NULL),(6,'text','<p>About Text</p>','2022-03-24 03:03:34','2022-04-06 20:56:43',NULL),(10,'phone',NULL,'2022-03-24 03:03:34','2022-04-06 20:56:32',NULL),(11,'facebook',NULL,'2022-03-24 03:03:34','2022-04-06 20:56:32',NULL),(12,'linkedin',NULL,'2022-03-24 03:03:34','2022-04-06 20:56:32',NULL),(13,'twitter',NULL,'2022-03-24 03:03:34','2022-04-06 20:56:32',NULL),(14,'instagram',NULL,'2022-03-24 03:03:34','2022-04-06 20:56:32',NULL),(17,'feature','news','2022-03-24 03:03:34','2022-03-25 03:19:47',NULL),(20,'feature','new feature','2022-03-25 03:20:24','2022-03-25 03:20:24',NULL),(21,'feature','1111111111111111111111','2022-03-30 02:43:57','2022-04-01 03:26:47',NULL),(23,'notify-email','tuma@mailinator.com','2022-03-30 02:46:48','2022-03-30 02:46:48',NULL),(25,'notify-email','kezizu@mailinator.com','2022-03-30 02:47:05','2022-03-30 02:47:05',NULL),(26,'notify-email','bacilut@mailinator.com','2022-03-31 21:37:52','2022-03-31 21:37:52',NULL),(30,'partner','https://wam-bucket.s3.us-east-2.amazonaws.com/app/website/assets/62467ba981aedb.jpg','2022-03-31 22:10:36','2022-03-31 22:12:27','Maite Mckinney'),(31,'tnc','<p>Terms</p><ul><li>a</li><li>b</li><li>c</li><li><strong>dgsdgsdgs</strong></li></ul>','2022-03-24 03:03:34','2022-04-06 21:03:42',NULL),(32,'abouttext','<h2>About abouttext abouttextabou ttextText</h2>','2022-03-24 03:03:34','2022-04-10 23:12:38',NULL);
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-12 14:08:43
