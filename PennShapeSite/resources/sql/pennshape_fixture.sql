-- MySQL dump 10.13  Distrib 5.5.32, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: ndg_pennshape
-- ------------------------------------------------------
-- Server version	5.5.32-0ubuntu0.12.04.1

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
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (3,'PennShapeSignup StartDate',NULL,'2013-10-30 23:40:38','2013-10-31 00:39:23'),(4,'NdgPattern-IsEditAllowed','1','2013-10-30 23:53:43','2013-10-31 01:00:14');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `edge`
--

LOCK TABLES `edge` WRITE;
/*!40000 ALTER TABLE `edge` DISABLE KEYS */;
/*!40000 ALTER TABLE `edge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `instance`
--

LOCK TABLES `instance` WRITE;
/*!40000 ALTER TABLE `instance` DISABLE KEYS */;
INSERT INTO `instance` VALUES (1,'Demo 1.0','Three-node net','','',0,'2013-11-01 16:57:08','2013-11-01 16:17:57');
/*!40000 ALTER TABLE `instance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `node`
--

LOCK TABLES `node` WRITE;
/*!40000 ALTER TABLE `node` DISABLE KEYS */;
/*!40000 ALTER TABLE `node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `pattern`
--

LOCK TABLES `pattern` WRITE;
/*!40000 ALTER TABLE `pattern` DISABLE KEYS */;
INSERT INTO `pattern` VALUES (1,'Solo game','1',NULL,0,'2013-10-23 03:23:40','2013-10-23 01:23:40'),(2,'Pair game','1 2\r\n2 1','N=2, Z=1',1,'2013-10-23 03:24:01','2013-10-29 14:10:38'),(3,'Three-node net','1 2 3\r\n2 1 3\r\n3 1 2','N=3, Z=2',0,'2013-10-23 03:24:38','2013-10-29 14:36:54'),(4,'Service Manager','1  2\r\n2  3','',0,'2013-10-26 15:44:07','2013-10-29 14:23:38'),(5,'Service Manager 2','1  2\r\n2  3','',0,'2013-10-26 16:54:15','2013-10-29 14:10:52'),(6,'Service Manager','1  2\r\n2  3','',0,'2013-10-28 14:33:28','2013-10-29 14:37:21'),(7,'Three-node net','1 2 3\r\n2 1 3\r\n3 1 2','N=3, Z=2',0,'2013-10-29 15:27:55','2013-10-29 14:27:55'),(8,'Three-node net','1 2 3\r\n2 1 3\r\n3 1 2','N=3, Z=2',0,'2013-10-29 16:30:15','2013-10-29 15:38:01'),(9,'isArchived 01','1  2\r\n2  3','',0,'2013-10-29 16:30:59','2013-10-29 15:38:01'),(10,'isArchived 02','1  2\r\n2  3','Woot',1,'2013-10-29 16:48:11','2013-10-29 15:49:55');
/*!40000 ALTER TABLE `pattern` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `template`
--

LOCK TABLES `template` WRITE;
/*!40000 ALTER TABLE `template` DISABLE KEYS */;
INSERT INTO `template` VALUES (1,2,'First Tz','Woo-hoo','4.##  #pattern  Low stake',0,0,'2013-10-26 17:23:17','2013-10-26 22:02:51'),(2,3,'2 Tz Ea','Woo-hoo','4.##  #pattern  Low stake',0,1,'2013-10-27 05:30:45','2013-10-29 15:56:01');
/*!40000 ALTER TABLE `template` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-11-01 17:34:13
