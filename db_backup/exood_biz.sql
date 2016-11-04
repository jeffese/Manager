-- MySQL dump 10.14  Distrib 5.5.41-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: exood_biz
-- ------------------------------------------------------
-- Server version	5.5.41-MariaDB

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `CatID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` varchar(50) DEFAULT NULL,
  `parent_id` varchar(50) DEFAULT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `catname` varchar(255) DEFAULT NULL,
  `pix` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`CatID`),
  UNIQUE KEY `category_id` (`category_id`),
  KEY `parent_id` (`parent_id`),
  FULLTEXT KEY `category_name_2` (`category_name`,`catname`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `country_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(50) NOT NULL DEFAULT '',
  `continent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`country_id`),
  KEY `country` (`country`),
  KEY `continent` (`continent`)
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (1,'Afghanistan',0),(2,'Albania',0),(3,'Algeria',0),(4,'American Samoa',0),(5,'Andorra',0),(6,'Angola',0),(7,'Anguilla',0),(8,'Antartica',0),(9,'Antigua and Barbuda',0),(10,'Armenia',0),(11,'Argentina',0),(12,'Aruba',0),(13,'Australia',0),(14,'Austria',0),(15,'Azerbaijan',0),(16,'Bahamas',0),(17,'Bahrain',0),(18,'Bangladesh',0),(19,'Barbados',0),(20,'Belarus',0),(21,'Belgium',0),(22,'Belize',0),(23,'Benin',0),(24,'Bermuda',0),(25,'Bhutan',0),(26,'Bolivia',0),(27,'Bosnia-Herzegovina',0),(28,'Botswana',0),(29,'Bouvet Island',0),(30,'Brazil',0),(31,'British Indian Ocean Territory',0),(32,'Brunei Darussalam',0),(33,'Bulgaria',0),(34,'Burkina Faso',0),(35,'Burundi',0),(36,'Cambodia',0),(37,'Cameroon',0),(38,'Canada',0),(39,'Cape Verde',0),(40,'Cayman Islands',0),(41,'Central African Republic',0),(42,'Chad',0),(43,'Chile',0),(44,'China',0),(45,'Christmas Island',0),(46,'Cocos (Keeling) Islands',0),(47,'Colombia',0),(48,'Comoros',0),(49,'Congo',0),(50,'Cook Islands',0),(51,'Costa Rica',0),(52,'Croatia',0),(53,'Cuba',0),(54,'Cyprus',0),(55,'Czech Republic',0),(56,'Denmark',0),(57,'Djibouti',0),(58,'Dominica',0),(59,'Dominican Republic',0),(60,'East Timor',0),(61,'Ecuador',0),(62,'Egypt',0),(63,'El Salvador',0),(64,'Equatorial Guinea',0),(65,'Eritrea',0),(66,'Estonia',0),(67,'Ethiopia',0),(68,'Falkland Islands',0),(69,'Faroe Islands',0),(70,'Fiji',0),(71,'Finland',0),(72,'France',0),(73,'France (European Territory)',0),(74,'French Guyana',0),(75,'French Southern Territories',0),(76,'Gabon',0),(77,'Gambia',0),(78,'Georgia',0),(79,'Germany',0),(80,'Ghana',0),(81,'Gibraltar',0),(82,'Greece',0),(83,'Greenland',0),(84,'Grenada',0),(85,'Guadeloupe (French)',0),(86,'Guam',0),(87,'Guatemala',0),(88,'Guinea',0),(89,'Guinea Bissau',0),(90,'Guyana',0),(91,'Haiti',0),(92,'Heard and McDonald Islands',0),(93,'Honduras',0),(94,'Hong Kong',0),(95,'Hungary',0),(96,'Iceland',0),(97,'India',0),(98,'Indonesia',0),(99,'Iran',0),(100,'Iraq',0),(101,'Ireland',0),(102,'Israel',0),(103,'Italy',0),(104,'Ivory Coast',0),(105,'Jamaica',0),(106,'Japan',0),(107,'Jordan',0),(108,'Kazakhstan',0),(109,'Kenya',0),(110,'Kiribati',0),(111,'Kuwait',0),(112,'Kyrgyzstan',0),(113,'Laos',0),(114,'Latvia',0),(115,'Lebanon',0),(116,'Lesotho',0),(117,'Liberia',0),(118,'Libya',0),(119,'Liechtenstein',0),(120,'Lithuania',0),(121,'Luxembourg',0),(122,'Macau',0),(123,'Macedonia',0),(124,'Madagascar',0),(125,'Malawi',0),(126,'Malaysia',0),(127,'Maldives',0),(128,'Mali',0),(129,'Malta',0),(130,'Marshall Islands',0),(131,'Martinique (French)',0),(132,'Mauritania',0),(133,'Mauritius',0),(134,'Mayotte',0),(135,'Mexico',0),(136,'Micronesia',0),(137,'Moldova',0),(138,'Monaco',0),(139,'Mongolia',0),(140,'Montserrat',0),(141,'Morocco',0),(142,'Mozambique',0),(143,'Myanmar, Union of (Burma)',0),(144,'Namibia',0),(145,'Nauru',0),(146,'Nepal',0),(147,'Netherlands',0),(148,'Netherlands Antilles',0),(149,'Neutral Zone',0),(150,'New Caledonia (French)',0),(151,'New Zealand',0),(152,'Nicaragua',0),(153,'Niger',0),(154,'Nigeria',0),(155,'Niue',0),(156,'Norfolk Island',0),(157,'North Korea',0),(158,'Northern Mariana Islands',0),(159,'Norway',0),(160,'Oman',0),(161,'Pakistan',0),(162,'Palau',0),(163,'Panama',0),(164,'Papua New Guinea',0),(165,'Paraguay',0),(166,'Peru',0),(167,'Philippines',0),(168,'Pitcairn Island',0),(169,'Poland',0),(170,'Polynesia (French)',0),(171,'Portugal',0),(172,'Qatar',0),(173,'Reunion (French)',0),(174,'Romania',0),(175,'Russian Federation',0),(176,'Rwanda',0),(177,'S. Georgia & S. Sandwich Islands',0),(178,'Saint Helena',0),(179,'Saint Kitts & Nevis Anguilla',0),(180,'Saint Lucia',0),(181,'Saint Pierre and Miquelon',0),(182,'Saint Tome and Principe',0),(183,'Saint Vincent & Grenadines',0),(184,'Samoa',0),(185,'San Marino',0),(186,'Saudi Arabia',0),(187,'Senegal',0),(188,'Serbia-Montenegro',0),(189,'Seychelles',0),(190,'Sierra Leone',0),(191,'Singapore',0),(192,'Slovakia',0),(193,'Slovenia',0),(194,'Solomon Islands',0),(195,'Somalia',0),(196,'South Africa',0),(197,'South Korea',0),(198,'Spain',0),(199,'Sri Lanka',0),(200,'Sudan',0),(201,'Suriname',0),(202,'Svalbard and Jan Mayen Islands',0),(203,'Swaziland',0),(204,'Sweden',0),(205,'Switzerland',0),(206,'Syria',0),(207,'Tadjikistan',0),(208,'Taiwan',0),(209,'Tanzania',0),(210,'Thailand',0),(211,'Togo',0),(212,'Tokelau',0),(213,'Tonga',0),(214,'Trinidad and Tobago',0),(215,'Tunisia',0),(216,'Turkey',0),(217,'Turkmenistan',0),(218,'Turks and Caicos Islands',0),(219,'Tuvalu',0),(220,'Uganda',0),(221,'United Kingdom',0),(222,'Ukraine',0),(223,'United Arab Emirates',0),(224,'Uruguay',0),(225,'United States of America',0),(226,'USA Minor Outlying Islands',0),(227,'Uzbekistan',0),(228,'Vanuatu',0),(229,'Vatican City',0),(230,'Venezuela',0),(231,'Vietnam',0),(232,'Virgin Islands (British)',0),(233,'Virgin Islands (USA)',0),(234,'Wallis and Futuna Islands',0),(235,'Western Sahara',0),(236,'Yemen',0),(237,'Zaire',0),(238,'Zambia',0),(239,'Zimbabwe',0);
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coyinfo`
--

DROP TABLE IF EXISTS `coyinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coyinfo` (
  `CoyID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `CoyName` varchar(50) DEFAULT NULL,
  `CoyDir` varchar(255) DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `State` varchar(50) DEFAULT NULL,
  `Country` tinyint(3) unsigned DEFAULT NULL,
  `Tel` varchar(50) DEFAULT NULL,
  `Web` varchar(50) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Slogan` varchar(50) DEFAULT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `ReceiptComment` varchar(100) DEFAULT NULL,
  `license` varchar(255) DEFAULT NULL,
  `AutoSell` tinyint(1) DEFAULT '0',
  `AutoReceipt` tinyint(1) DEFAULT '0',
  `UseStore` tinyint(1) DEFAULT '0',
  `UsePOSPrinter` tinyint(1) DEFAULT '0',
  `currency` tinyint(3) unsigned DEFAULT NULL,
  `CashAccount` tinyint(3) unsigned DEFAULT NULL,
  `officesign` varchar(50) DEFAULT NULL,
  `negstock` tinyint(1) DEFAULT '0',
  `negcash` tinyint(1) DEFAULT '0',
  `securetransfer` tinyint(1) DEFAULT '0',
  `Tax` decimal(3,1) DEFAULT NULL,
  `ExoodID` varchar(50) DEFAULT NULL,
  `Exoodpass` varchar(50) DEFAULT NULL,
  `ExoodCoyID` int(11) unsigned DEFAULT NULL,
  `ExoodAddrID` int(11) unsigned NOT NULL,
  `AutoExoodUpdate` tinyint(1) DEFAULT '0',
  `LinkRefresh` smallint(5) unsigned DEFAULT NULL,
  `UpdateTime` smallint(5) unsigned DEFAULT NULL,
  `admin_mail` varchar(100) NOT NULL DEFAULT '',
  `email_pass` varchar(30) NOT NULL DEFAULT '',
  `smtp` varchar(50) NOT NULL DEFAULT '',
  `smtp_port` smallint(5) NOT NULL DEFAULT '25',
  `smtp_auth` tinyint(1) NOT NULL DEFAULT '1',
  `isschool` tinyint(1) NOT NULL DEFAULT '0',
  `ad_auth` tinyint(1) NOT NULL DEFAULT '0',
  `ad_host` varchar(100) NOT NULL DEFAULT '',
  `ad_user` varchar(100) NOT NULL DEFAULT '',
  `ad_pass` varchar(30) NOT NULL DEFAULT '',
  `gateway` varchar(50) NOT NULL,
  `kiosk` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`CoyID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coyinfo`
--

LOCK TABLES `coyinfo` WRITE;
/*!40000 ALTER TABLE `coyinfo` DISABLE KEYS */;
INSERT INTO `coyinfo` VALUES (1,'eVRS',NULL,'3 .','Kano','Lagos',154,'','','','','0','','1&1&1&1&1&1&1#1&1&1&1&1&1#1&1&1&1&1&1&1#1&1&1&1&1&1#1&1&1&1&1#0&1&1&1&1&1&1&1&1&1&1&1&1&1&1#1&1&1&1&1#1&1&1&1&1&1&1&1#0&1&1&1&1&1&1#1&1&1&1&1&1&1',0,0,1,1,1,2,'0',0,0,0,0.0,'','',0,0,0,0,0,'admin','Vision100%','',0,0,0,0,'','','','',1);
/*!40000 ALTER TABLE `coyinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currencies` (
  `cur_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `currencyname` varchar(20) NOT NULL,
  `symbol` varchar(5) NOT NULL,
  `code` varchar(10) NOT NULL DEFAULT '',
  `unitname` varchar(20) NOT NULL,
  `unitsymbol` varchar(5) NOT NULL,
  `unitcode` varchar(10) NOT NULL DEFAULT '',
  `fromrate` smallint(6) NOT NULL,
  `torate` smallint(6) DEFAULT NULL,
  `fullname` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`cur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES (1,'Naira','=N=','&#x20a6;','Kobo','k','k',152,1,'Nigerian Naira'),(2,'Dollar','$','$','Cent','Â¢','&#162;',1,1,'US Dollar'),(3,'Pound','Â£','&#163;','Pence','p','p',100,163,'British Pound'),(4,'Euro','Eur','&#128;','Cent','Â¢','&#162;',100,144,'Euro'),(5,'Yen','Â¥','&#165;','Sen','s','s',7716,100,'Japanese Yen'),(6,'Franc','Fr','&#8355;','centime','Â¢','&#162;',463,1,'Franc'),(7,'Yuan','CNY','CNY','Fen','Â¢','&#162;',639,100,'Yuan');
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exood_vars`
--

DROP TABLE IF EXISTS `exood_vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exood_vars` (
  `server_ip` varchar(50) DEFAULT NULL,
  `server_fpath` varchar(50) DEFAULT NULL,
  `shop_path` varchar(50) DEFAULT NULL,
  `ftp_server` varchar(50) DEFAULT NULL,
  `ftp_username` varchar(50) DEFAULT NULL,
  `ftp_pass` varchar(50) DEFAULT NULL,
  `hostname_exood` varchar(50) DEFAULT NULL,
  `database_exood` varchar(50) DEFAULT NULL,
  `username_exood` varchar(50) DEFAULT NULL,
  `password_exood` varchar(50) DEFAULT NULL,
  `accesschk` tinyint(1) DEFAULT '0',
  `updatestatus` tinyint(1) DEFAULT '0',
  `logmsg` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exood_vars`
--

LOCK TABLES `exood_vars` WRITE;
/*!40000 ALTER TABLE `exood_vars` DISABLE KEYS */;
/*!40000 ALTER TABLE `exood_vars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finddefs`
--

DROP TABLE IF EXISTS `finddefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `finddefs` (
  `findID` varchar(50) NOT NULL DEFAULT '',
  `combinval` varchar(50) DEFAULT NULL,
  `operatval` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`findID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finddefs`
--

LOCK TABLES `finddefs` WRITE;
/*!40000 ALTER TABLE `finddefs` DISABLE KEYS */;
INSERT INTO `finddefs` VALUES ('Bills',NULL,NULL),('cardtypes',NULL,NULL),('CashAccounts',NULL,NULL),('Categories',NULL,NULL),('Classifications',NULL,NULL),('Contractors',NULL,NULL),('Currencies',NULL,NULL),('Customers',NULL,NULL),('Equipment',NULL,NULL),('ExoodUpdate',NULL,NULL),('Expenses',NULL,NULL),('InvoiceDetails',NULL,NULL),('Invoices',NULL,NULL),('orderdetails',NULL,NULL),('OrderReturnDet',NULL,NULL),('OrderReturns',NULL,NULL),('Orders',NULL,NULL),('Payments',NULL,NULL),('Products',NULL,NULL),('RCashierPaySum',NULL,NULL),('Requisitions',NULL,NULL),('Rinvoice',NULL,NULL),('RInvoiceDet',NULL,NULL),('RItemTags',NULL,NULL),('ROrderDet',NULL,NULL),('RPurchaseOrder',NULL,NULL),('RReceptPayments',NULL,NULL),('RServiceDet',NULL,NULL),('ServiceInvDetails',NULL,NULL),('Services',NULL,NULL),('shopcats',NULL,NULL),('Shop_Transfers',NULL,NULL),('Staff',NULL,NULL),('StockDeductions',NULL,NULL),('StoreReturns',NULL,NULL),('Store_Transfers',NULL,NULL),('Suppliers',NULL,NULL),('Usergroups',NULL,NULL),('Users',NULL,NULL),('Vledger',NULL,NULL),('Vrequisitions',NULL,NULL),('VSalesPnL',NULL,NULL),('Vshop',NULL,NULL),('VShopTransfers',NULL,NULL),('Vstock',NULL,NULL),('Vstockdetails',NULL,NULL),('VStockValue',NULL,NULL),('Vstore',NULL,NULL),('VStoreReturns',NULL,NULL),('Vusergroups',NULL,NULL);
/*!40000 ALTER TABLE `finddefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `findfielddefs`
--

DROP TABLE IF EXISTS `findfielddefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `findfielddefs` (
  `findID` varchar(50) DEFAULT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `ffname` varchar(50) DEFAULT NULL,
  `ftype` tinyint(2) DEFAULT NULL,
  `bracket` varchar(3) DEFAULT NULL,
  `pseudofield` varchar(50) DEFAULT NULL,
  `fdsource` varchar(255) DEFAULT NULL,
  `fkeyfield` varchar(50) DEFAULT NULL,
  `flistfield` varchar(50) DEFAULT NULL,
  `queryval` varchar(2000) DEFAULT NULL,
  KEY `findID` (`findID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `findfielddefs`
--

LOCK TABLES `findfielddefs` WRITE;
/*!40000 ALTER TABLE `findfielddefs` DISABLE KEYS */;
INSERT INTO `findfielddefs` VALUES ('Bills','BillID','Bill ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Bills','EmployeeID','Staff ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VStaffID','VendorID','VendorName',NULL),('Bills','InvoiceNumber','Invoice Number',1,NULL,NULL,NULL,NULL,NULL,NULL),('Bills','BillTitle','Bill Title',1,'\'',NULL,NULL,NULL,NULL,NULL),('Bills','VendorType','Vendor Type',4,'\'',NULL,'Â§CustomerÂ§ Â§StaffÂ§ Â§SupplierÂ§ Â§VendorÂ§ Â¶CustomerÂ¶ Â¶StaffÂ¶ Â¶SupplierÂ¶ Â¶VendorÂ¶',NULL,NULL,NULL),('Bills','VendorID','Vendor ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VendorID','VendorID','VendorName',NULL),('Bills','CustomerName','Vendor Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Bills','Status','Status',1,'\'',NULL,NULL,NULL,NULL,NULL),('Bills','BillDate','Bill Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('Bills','ReceivedDate','Received Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('Bills','Amount','Bill Value',1,NULL,NULL,NULL,NULL,NULL,NULL),('Bills','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Bills','posted','Posted',5,NULL,NULL,NULL,NULL,NULL,NULL),('cardtypes','cardid','Card ID',1,'\'',NULL,NULL,NULL,NULL,NULL),('cardtypes','card','Card Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('cardtypes','inuse','Card in Use',5,NULL,NULL,NULL,NULL,NULL,NULL),('cardtypes','firm','Name of Firm',1,'\'',NULL,NULL,NULL,NULL,NULL),('cardtypes','address','Firm\'s Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('cardtypes','tel','Telephone',1,'\'',NULL,NULL,NULL,NULL,NULL),('cardtypes','website','Website',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','VendorID','Client ID',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','CompanyName','Company Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','ClientType','ClientType',4,'\'',NULL,'Â§IndividualÂ§ Â§CompanyÂ§ Â¶1Â¶ Â¶2Â¶',NULL,NULL,NULL),('CashAccounts','InUse','Active',5,NULL,NULL,NULL,NULL,NULL,NULL),('CashAccounts','ContactTitle','Contact Title',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','ContactFirstName','Contact First Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','ContactMidName','Contact Middle Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','ContactLastName','Contact Last Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','CompanyOrDepartment','Department',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','BillingAddress','Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','City','City',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','StateOrProvince','State',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','Country/Region','Country',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','PostalCode','Postal Code',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','PhoneNumber','Phone Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','Extension','Extension',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','FaxNumber','Fax Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','EmailAddress','Email Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('CashAccounts','currency','Currency',2,NULL,NULL,'SELECT cur_id, currencyname FROM Currencies ORDER BY currencyname','cur_id','currencyname',NULL),('CashAccounts','credit','Credit',5,NULL,NULL,NULL,NULL,NULL,NULL),('CashAccounts','cheque','Cheque',5,NULL,NULL,NULL,NULL,NULL,NULL),('CashAccounts','amtbal','Account Balance',1,NULL,NULL,NULL,NULL,NULL,NULL),('CashAccounts','creditlimit','Credit Limit',1,NULL,NULL,NULL,NULL,NULL,NULL),('CashAccounts','Discount','Discount',1,NULL,NULL,NULL,NULL,NULL,NULL),('Categories','category_id','Category ID',1,'\'',NULL,NULL,NULL,NULL,NULL),('Categories','category_name','Category Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Categories','parent_id','Parent Category',1,'\'',NULL,NULL,NULL,NULL,NULL),('Categories','catname','Category Tree',1,'\'',NULL,NULL,NULL,NULL,NULL),('Classifications','category_id','Category ID',1,'\'',NULL,NULL,NULL,NULL,NULL),('Classifications','category_name','Category Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Classifications','parent_id','Parent Category',1,'\'',NULL,NULL,NULL,NULL,NULL),('Classifications','catname','Category Tree',1,'\'',NULL,NULL,NULL,NULL,NULL),('Classifications','description','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','VendorID','Vendor ID',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','CompanyName','Company Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','ClientType','ClientType',4,'\'',NULL,'Â§IndividualÂ§ Â§CompanyÂ§ Â¶1Â¶ Â¶2Â¶',NULL,NULL,NULL),('Contractors','InUse','Active',5,NULL,NULL,NULL,NULL,NULL,NULL),('Contractors','ContactTitle','Contact Title',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','ContactFirstName','Contact First Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','ContactMidName','Contact Middle Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','ContactLastName','Contact Last Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','CompanyOrDepartment','Department',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','BillingAddress','Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','City','City',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','StateOrProvince','State',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','Country/Region','Country',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','PostalCode','Postal Code',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','PhoneNumber','Phone Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','Extension','Extension',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','FaxNumber','Fax Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','EmailAddress','Email Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Contractors','currency','Currency',2,NULL,NULL,'SELECT cur_id, currencyname FROM Currencies ORDER BY currencyname','cur_id','currencyname',NULL),('Contractors','credit','Credit',5,NULL,NULL,NULL,NULL,NULL,NULL),('Contractors','cheque','Cheque',5,NULL,NULL,NULL,NULL,NULL,NULL),('Contractors','amtbal','Account Balance',1,NULL,NULL,NULL,NULL,NULL,NULL),('Contractors','creditlimit','Credit Limit',1,NULL,NULL,NULL,NULL,NULL,NULL),('Contractors','Discount','Discount',1,NULL,NULL,NULL,NULL,NULL,NULL),('Currencies','currencyname','Currency Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Currencies','currentrate','Exchange Rate',1,NULL,NULL,NULL,NULL,NULL,NULL),('Customers','VendorID','Customer ID',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','CompanyName','Company Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','ClientType','ClientType',4,'\'',NULL,'Â§IndividualÂ§ Â§CompanyÂ§ Â¶1Â¶ Â¶2Â¶',NULL,NULL,NULL),('Customers','InUse','Active',5,NULL,NULL,NULL,NULL,NULL,NULL),('Customers','ContactTitle','Contact Title',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','ContactFirstName','Contact First Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','ContactMidName','Contact Middle Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','ContactLastName','Contact Last Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','CompanyOrDepartment','Department',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','BillingAddress','Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','City','City',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','StateOrProvince','State',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','Country/Region','Country',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','PostalCode','Postal Code',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','PhoneNumber','Phone Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','Extension','Extension',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','FaxNumber','Fax Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','EmailAddress','Email Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Customers','currency','Currency',2,NULL,NULL,'SELECT cur_id, currencyname FROM Currencies ORDER BY currencyname','cur_id','currencyname',NULL),('Customers','credit','Credit',5,NULL,NULL,NULL,NULL,NULL,NULL),('Customers','cheque','Cheque',5,NULL,NULL,NULL,NULL,NULL,NULL),('Customers','amtbal','Account Balance',1,NULL,NULL,NULL,NULL,NULL,NULL),('Customers','creditlimit','Credit Limit',1,NULL,NULL,NULL,NULL,NULL,NULL),('Customers','Discount','Discount',1,NULL,NULL,NULL,NULL,NULL,NULL),('Equipment','EquipID','Equipment ID',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','Category','Category',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','Brand','Brand',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','EquipType','Type',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','Model','Model',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','colour','colour',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','description','Description',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','serialno','Serial No.',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','modelno','Model No.',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','partno','Part No.',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','licenceno','Licence No.',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','insuranceno','Insurance No.',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','purchfrom','Purchased From',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','dateofpurch','Date of Purchase',3,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','insurers','Insurers',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','servcomp','Servcing Company',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','designation','Designation',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','dept','Department',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','usage','Usage',1,'\'',NULL,NULL,NULL,NULL,NULL),('Equipment','Status','Status',5,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','ProductID','Product ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','ExoodID','Exood ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','ProdName','Full Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','Brand','Brand',2,'\'',NULL,'SELECT * FROM brands ORDER BY brand','brand','BrandName',NULL),('ExoodUpdate','ProductDescription','Description',1,'\'',NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','oldcat','Old Category',2,'\'',NULL,'SELECT category_id, catname FROM shopcats IN \'serverpathDatabasesalesforce.mdb\' ORDER BY category_name','category_id','catname',NULL),('ExoodUpdate','category','Category',2,'\'',NULL,'SELECT category_id, catname FROM shopcats IN \'serverpathDatabasesalesforce.mdb\' ORDER BY category_name','category_id','catname',NULL),('ExoodUpdate','weight','Weight',1,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','length','Length',1,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','width','Width',1,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','webstock','Stock (Web)',1,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','stock','Stock',1,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','breadth','Breadth',1,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','UnitPrice','Unit Price',1,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','StockLoad','Upload Stock',5,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','currency','Currency',2,NULL,NULL,'SELECT cur_id, currencyname FROM Currencies ORDER BY currencyname','cur_id','currencyname',NULL),('ExoodUpdate','exoodsales','Sell on Exood.com',5,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','status','Status',4,NULL,NULL,'Â§NewÂ§ Â§OldÂ§ Â¶0Â¶ Â¶1Â¶',NULL,NULL,NULL),('ExoodUpdate','pixLoad','Upload Picture',5,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','InfoLoad','Upload Info',5,NULL,NULL,NULL,NULL,NULL,NULL),('ExoodUpdate','exoodshop','Display on Exood.com',5,NULL,NULL,NULL,NULL,NULL,NULL),('Expenses','ExpenseID','Expense ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Expenses','AccountID','Cash Account',2,'\'',NULL,'SELECT VendorID, VendorName FROM VCashAccID','VendorID','VendorName',NULL),('Expenses','EmployeeID','Staff ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VStaffID','VendorID','VendorName',NULL),('Expenses','AuthorizedBy','Posted By',2,'\'',NULL,'SELECT VendorID, VendorName FROM VStaffID','VendorID','VendorName',NULL),('Expenses','RecipientType','Recipient Type',4,'\'',NULL,'Â§CustomerÂ§ Â§StaffÂ§ Â§SupplierÂ§ Â§VendorÂ§ Â¶CustomerÂ¶ Â¶StaffÂ¶ Â¶SupplierÂ¶ Â¶VendorÂ¶',NULL,NULL,NULL),('Expenses','RecipientID','Recipient ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VendorID','VendorID','VendorName',NULL),('Expenses','Recipient','Recipient Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Expenses','ExpenseType','Expense Type',2,'\'',NULL,'SELECT * FROM ExpenseType ORDER BY Expensetype','Expensetype','Expensetype',NULL),('Expenses','InvoiceID','Invoice ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Expenses','RefNo','Ref. No',1,'\'',NULL,NULL,NULL,NULL,NULL),('Expenses','PurposeofExpense','Purpose of Expense',1,'\'',NULL,NULL,NULL,NULL,NULL),('Expenses','Amount','Expense Amount',1,NULL,NULL,NULL,NULL,NULL,NULL),('Expenses','RecAccountBalance','Recipient Account Balance',1,NULL,NULL,NULL,NULL,NULL,NULL),('Expenses','AccountBalance','Cash Account Balance',1,NULL,NULL,NULL,NULL,NULL,NULL),('Expenses','ExpenseDate','Expense Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('Expenses','DateSubmitted','Date Submitted',3,'\'',NULL,NULL,NULL,NULL,NULL),('Expenses','PaymentMethod','Payment Method',4,NULL,NULL,'Â§CashÂ§ Â§CardÂ§ Â§ChequeÂ§ Â§OthersÂ§ Â¶CashÂ¶ Â¶CardÂ¶ Â¶ChequeÂ¶ Â¶OthersÂ¶',NULL,NULL,NULL),('Expenses','Description','Description',1,'\'',NULL,NULL,NULL,NULL,NULL),('Expenses','Posted','Posted',5,NULL,NULL,NULL,NULL,NULL,NULL),('Expenses','CheckNumber','Cheque Number',1,NULL,NULL,NULL,NULL,NULL,NULL),('Expenses','CreditCardType','Credit Card Type',2,'\'',NULL,'SELECT cardid, card FROM cardtypes','cardid','card',NULL),('Expenses','CreditCardNumber','Cheque/ Card/ Track No.',1,'\'',NULL,NULL,NULL,NULL,NULL),('Expenses','PaymentMth','Bank/ Payment System',1,'\'',NULL,NULL,NULL,NULL,NULL),('Expenses','CheckDate','Cheque/ Card Exp. Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('InvoiceDetails','InvoiceDetailID','Invoice Detail ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('InvoiceDetails','InvoiceID','Invoice ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('InvoiceDetails','ProductID','Product ID',2,NULL,NULL,'SELECT ProductID, ProdName FROM Products ORDER BY ProdName','ProductID','ProdName',NULL),('InvoiceDetails','Quantity','Quantity',1,NULL,NULL,NULL,NULL,NULL,NULL),('InvoiceDetails','ProductName','Product Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('InvoiceDetails','SerialNumber','Serial Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('InvoiceDetails','UnitPrice','Unit Price',1,NULL,NULL,NULL,NULL,NULL,NULL),('InvoiceDetails','Discount','Discount (Value)',1,NULL,NULL,NULL,NULL,NULL,NULL),('InvoiceDetails','Discnt','Discount (%)',1,NULL,NULL,NULL,NULL,NULL,NULL),('InvoiceDetails','SalesPrice','Sales Price',1,NULL,NULL,NULL,NULL,NULL,NULL),('InvoiceDetails','TaxRate','Tax Rate',1,NULL,NULL,NULL,NULL,NULL,NULL),('InvoiceDetails','TotalValue','Total Value',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','InvoiceID','Invoice ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','EmployeeID','Employee ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VStaffID','VendorID','VendorName',NULL),('Invoices','CustomerType','Customer Type',4,'\'',NULL,'Â§CustomerÂ§ Â§StaffÂ§ Â§SupplierÂ§ Â§VendorÂ§ Â¶CustomerÂ¶ Â¶StaffÂ¶ Â¶SupplierÂ¶ Â¶VendorÂ¶',NULL,NULL,NULL),('Invoices','CustomerID','Customer ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VendorID','VendorID','VendorName',NULL),('Invoices','CustomerName','Customer Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Invoices','Status','Status',1,'\'',NULL,NULL,NULL,NULL,NULL),('Invoices','InvoiceDate','Invoice Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('Invoices','ShipDate','Ship Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('Invoices','ShippedTo','Shipped To',1,'\'',NULL,NULL,NULL,NULL,NULL),('Invoices','ShippedVia','Shipped Via',1,'\'',NULL,NULL,NULL,NULL,NULL),('Invoices','TaxRate','Tax Rate',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','Dscnt','Discount (%)',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','Discount','Discount (Value)',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','ServiceCharge','Service Total Value',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','ServiceDisc','Service Total Discount',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','TotTax','Total Tax',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','TotDisc','Sales Total Discount',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','TotalValue','Sales Total Value',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','Grandvalue','Grand Total',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','amtpaid','Amount Paid',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','amtchng','Change',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','ExchangeRate','Exchange Rate',1,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','Posted','Posted',5,NULL,NULL,NULL,NULL,NULL,NULL),('Invoices','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('orderdetails','OrderDetailID','Order Detail ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','OrderID','Order ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','ProductID','Product ID',2,NULL,NULL,'SELECT ProductID, ProdName FROM Products ORDER BY ProdName','ProductID','ProdName',NULL),('orderdetails','ProdName','Product Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('orderdetails','SerialNumber','Serial Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('orderdetails','Quantity','Quantity',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','UnitPrice','Unit Price',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','Dscnt','Discount (%)',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','Discount','Discount (Value)',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','SalePrice','Sale Price',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','SalesTax','Sales Tax',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','LineTotal','Line Total',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','Margin','Margin',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','calcost','Calculated Cost',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','sugsell','Suggested Selling Price',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','oldsell','Old Selling Price',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','Expires','Expires',5,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','ExpiryDate','Expiry Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('orderdetails','currentstock','Current Stock',5,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','QtyinStock','Quantity in Stock',1,NULL,NULL,NULL,NULL,NULL,NULL),('orderdetails','Cleared','Cleared',5,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturnDet','OrderRetDetID','Order Return Detail ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturnDet','OrderDetailID','Order Detail ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturnDet','OrderRetID','Order Return ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturnDet','Quantity','Quantity',1,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturnDet','SalePrice','Sale Price',1,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturnDet','LineTotal','Line Total',1,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturns','OrderRetID','Order Return ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturns','OrderID','Order ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturns','EmployeeID','Employee ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VStaffID','VendorID','VendorName',NULL),('OrderReturns','ReturnDate','Return Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('OrderReturns','ShipName','Ship Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('OrderReturns','ShipAddress','Ship Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('OrderReturns','ShipDate','Ship Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('OrderReturns','ShippingMethodID','Shipping Method',4,'\'',NULL,'Â§Air FreightÂ§ Â§Door DeliveryÂ§ Â§Parcel ServiceÂ§ Â§Sea FreightÂ§ Â§OthersÂ§ Â¶Air FreightÂ¶ Â¶Door DeliveryÂ¶ Â¶Parcel ServiceÂ¶ Â¶Sea FreightÂ¶ Â¶OthersÂ¶',NULL,NULL,NULL),('OrderReturns','FreightCharge','Freight Charge',1,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturns','Expenses','Expenses',1,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturns','TotalValue','Total Value',1,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturns','Posted','Posted',5,NULL,NULL,NULL,NULL,NULL,NULL),('OrderReturns','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','OrderID','Order ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Orders','SupplierID','Supplier ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VSupplierID','VendorID','VendorName',NULL),('Orders','EmployeeID','Employee ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VStaffID','VendorID','VendorName',NULL),('Orders','OrderDate','Order Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','PurchaseOrderNumber','Purchase Order Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','RequiredByDate','Required By Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','PromisedByDate','Promised By Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','ShipName','Ship Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','ShipAddress','Ship Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','ShipCity','Ship City',1,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','ShipState','Ship State',1,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','ShipStateOrProvince','Ship State Or Province',1,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','ShipPostalCode','Ship Postal Code',1,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','ShipCountry','Ship Country',1,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','ShipPhoneNumber','Ship Phone Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','ShipDate','Ship Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('Orders','ShippingMethodID','Shipping Method',4,'\'',NULL,'Â§Air FreightÂ§ Â§Door DeliveryÂ§ Â§Parcel ServiceÂ§ Â§Sea FreightÂ§ Â§OthersÂ§ Â¶Air FreightÂ¶ Â¶Door DeliveryÂ¶ Â¶Parcel ServiceÂ¶ Â¶Sea FreightÂ¶ Â¶OthersÂ¶',NULL,NULL,NULL),('Orders','ShopCurrency','ShopCurrency',2,NULL,NULL,'SELECT cur_id, currencyname FROM Currencies ORDER BY currencyname','cur_id',NULL,NULL),('Orders','Currency','Currency',2,NULL,NULL,'SELECT cur_id, currencyname FROM Currencies ORDER BY currencyname','cur_id',NULL,NULL),('Orders','ExchangeRate','Exchange Rate',1,NULL,NULL,NULL,NULL,NULL,NULL),('Orders','FreightCharge','Freight Charge',1,NULL,NULL,NULL,NULL,NULL,NULL),('Orders','SalesTaxRate','Sales Tax Rate',1,NULL,NULL,NULL,NULL,NULL,NULL),('Orders','Margin','Margin',1,NULL,NULL,NULL,NULL,NULL,NULL),('Orders','Dscnt','Discount (%)',1,NULL,NULL,NULL,NULL,NULL,NULL),('Orders','Discount','Discount (Value)',1,NULL,NULL,NULL,NULL,NULL,NULL),('Orders','Expenses','Expenses',1,NULL,NULL,NULL,NULL,NULL,NULL),('Orders','OrderTotal','Order Total',1,NULL,NULL,NULL,NULL,NULL,NULL),('Orders','TotalValue','Total Value',1,NULL,NULL,NULL,NULL,NULL,NULL),('Orders','Posted','Posted',5,NULL,NULL,NULL,NULL,NULL,NULL),('Orders','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Payments','PaymentID','Payment ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Payments','InvoiceID','Invoice ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Payments','EmployeeID','Employee ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VStaffID','VendorID','VendorName',NULL),('Payments','PayeeType','Payee Type',4,'\'',NULL,'Â§CustomerÂ§ Â§StaffÂ§ Â§SupplierÂ§ Â§VendorÂ§ Â¶CustomerÂ¶ Â¶StaffÂ¶ Â¶SupplierÂ¶ Â¶VendorÂ¶',NULL,NULL,NULL),('Payments','PayeeID','Payee ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VendorID','VendorID','VendorName',NULL),('Payments','Payee','Payee',1,'\'',NULL,NULL,NULL,NULL,NULL),('Payments','AccountID','Cash Account',2,'\'',NULL,'SELECT VendorID, VendorName FROM VCashAccID','VendorID','VendorName',NULL),('Payments','Amount','Payment Amount',1,NULL,NULL,NULL,NULL,NULL,NULL),('Payments','RecAccountBalance','Payer\'s Account Balance',1,NULL,NULL,NULL,NULL,NULL,NULL),('Payments','AccountBalance','Account Balance',1,NULL,NULL,NULL,NULL,NULL,NULL),('Payments','PaymentDate','Payment Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('Payments','PaymentMethod','Bank Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Payments','CheckNumber','Cheque Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Payments','CreditCardType','Credit Card Type',2,'\'',NULL,'SELECT cardid, card FROM cardtypes','cardid','card',NULL),('Payments','CreditCardNumber','Credit Card Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Payments','CardholdersName','Account Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Payments','CreditCardExpDate','Credit Card Exp/Cheque  Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('Payments','CreditCardAuthorizationNumber','Credit Card Authorization Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Payments','PaymentTerms','Payment Terms',1,'\'',NULL,NULL,NULL,NULL,NULL),('Payments','PaymentMethodID','Payment Method',4,'\'',NULL,'Â§CashÂ§ Â§ChequeÂ§ Â§CardÂ§ Â§CreditÂ§ Â§OthersÂ§ Â¶CashÂ¶ Â¶ChequeÂ¶ Â¶CardÂ¶ Â¶CreditÂ¶ Â¶OthersÂ¶',NULL,NULL,NULL),('Payments','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Payments','Posted','Posted',5,NULL,NULL,NULL,NULL,NULL,NULL),('Products','ProductID','Product ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','ExoodID','Exood ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','ProductName','Product Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Products','ProdName','Product Full Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Products','Brand','Brand',2,'\'',NULL,'SELECT * FROM brands ORDER BY brand','brand','brand',NULL),('Products','ProductDescription','Product Description',1,'\'',NULL,NULL,NULL,NULL,NULL),('Products','category','Exood Shelf',2,NULL,NULL,'SELECT catID, category_name FROM shopcats IN \'serverpathDatabasesalesforce.mdb\' ORDER BY category_name','catID','category_name',NULL),('Products','Classification','Classification',2,NULL,NULL,'SELECT catID, category_name FROM Classifications ORDER BY category_name','catID','category_name',NULL),('Products','shelf','Store Shelf',2,'\'',NULL,'SELECT * FROM shelves ORDER BY shelf','shelf','shelf',NULL),('Products','Shopshelf','Shop Shelf',2,'\'',NULL,'SELECT * FROM shelves ORDER BY shelf','shelf','shelf',NULL),('Products','colour','Colour',1,'\'',NULL,NULL,NULL,NULL,NULL),('Products','weight','Weight',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','length','Length',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','width','Width',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','breadth','Breadth',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','warranty','Warranty',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','SupplierID','Supplier ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VSupplierID','VendorID','VendorName',NULL),('Products','SerialNumber','Serial Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Products','xbarcodes','Extra Barcodes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Products','UnitsInStock','Units In Stock',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','ShopStock','Shop Stock',0,NULL,NULL,NULL,NULL,NULL,NULL),('Products','actualstock','Actual Stock Count',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','Shopactlstock','Actual Shop Stock Count',0,NULL,NULL,NULL,NULL,NULL,NULL),('Products','webstock','Stock on Exood.com',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','UnitsOnOrder','Units On Order',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','ExoodPrice','Unit Price on Exood.com',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','UnitPrice','Unit Price',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','WebPrice','WebPrice',1,NULL,NULL,NULL,NULL,NULL,NULL),('Products','stockcntdate','Date of Last Stock count',3,'\'',NULL,NULL,NULL,NULL,NULL),('Products','Shopstkcntdate','Date of Last Shop Stock count',3,'\'',NULL,NULL,NULL,NULL,NULL),('Products','ReorderLevel','ReorderLevel',1,'\'',NULL,NULL,NULL,NULL,NULL),('Products','Shoplevel','Shoplevel',1,'\'',NULL,NULL,NULL,NULL,NULL),('Products','InUse','InUse',5,NULL,NULL,NULL,NULL,NULL,NULL),('Products','LeadTime','LeadTime',1,'\'',NULL,NULL,NULL,NULL,NULL),('Products','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Products','ShopNotes','Shop Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Products','currency','Currency',2,NULL,NULL,'SELECT cur_id, currencyname FROM Currencies ORDER BY currencyname','cur_id',NULL,NULL),('Products','status','Status',4,NULL,NULL,'Â§NewÂ§ Â§OldÂ§ Â¶0Â¶ Â¶1Â¶',NULL,NULL,NULL),('Products','exoodshop','Place on Exood.com',5,NULL,NULL,NULL,NULL,NULL,NULL),('Products','exoodsales','Sell on Exood.com',5,NULL,NULL,NULL,NULL,NULL,NULL),('Requisitions','RequisitID','Requisition ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Requisitions','RequestedBy','Requested By',2,'\'',NULL,'SELECT VendorID, VendorName FROM VStaffID','VendorID','VendorName',NULL),('Requisitions','GivenBy','Given By',2,'\'',NULL,'SELECT VendorID, VendorName FROM VStaffID','VendorID','VendorName',NULL),('Requisitions','RequestDate','Request Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('Requisitions','DateGiven','Date Given',3,'\'',NULL,NULL,NULL,NULL,NULL),('Requisitions','Transfered','Transfered',5,NULL,NULL,NULL,NULL,NULL,NULL),('Requisitions','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('ServiceInvDetails','InvoiceDetailID','Invoice Detail ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('ServiceInvDetails','InvoiceID','Invoice ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('ServiceInvDetails','servicecode','Service Code',2,'\'',NULL,'SELECT servicecode, service FROM Services ORDER BY service','servicecode','service',NULL),('ServiceInvDetails','Quantity','Quantity',1,NULL,NULL,NULL,NULL,NULL,NULL),('ServiceInvDetails','UnitCharge','Unit Charge',1,NULL,NULL,NULL,NULL,NULL,NULL),('ServiceInvDetails','Discnt','Discount (%)',1,NULL,NULL,NULL,NULL,NULL,NULL),('ServiceInvDetails','Discount','Discount (Value)',1,NULL,NULL,NULL,NULL,NULL,NULL),('ServiceInvDetails','ServiceCharge','Service Charge',1,NULL,NULL,NULL,NULL,NULL,NULL),('ServiceInvDetails','TaxRate','Tax Rate',1,NULL,NULL,NULL,NULL,NULL,NULL),('ServiceInvDetails','TotalValue','Total Value',1,NULL,NULL,NULL,NULL,NULL,NULL),('Services','servicecode','Service Code',1,'\'',NULL,NULL,NULL,NULL,NULL),('Services','ExoodID','Exood ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Services','service','Service',1,'\'',NULL,NULL,NULL,NULL,NULL),('Services','description','Description',1,'\'',NULL,NULL,NULL,NULL,NULL),('Services','Department','Department',1,'\'',NULL,NULL,NULL,NULL,NULL),('Services','Classification','Classification',2,'\'',NULL,'SELECT category_id, category_name FROM Classifications ORDER BY category_name','category_id','category_name',NULL),('Services','category','Exood Category',2,'\'',NULL,'SELECT category_id, catname FROM Categories IN \'serverpathDatabasesalesforce.mdb\' ORDER BY category_name','category_id','catname',NULL),('Services','charge','Charge',1,NULL,NULL,NULL,NULL,NULL,NULL),('Services','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Services','ExoodBiz','Place on Exood Biz',5,NULL,NULL,NULL,NULL,NULL,NULL),('Shop_Transfers','transfer_id','Transfer ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Shop_Transfers','RequisitID','Requisition ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Shop_Transfers','ProductID','Product ID',2,NULL,NULL,'SELECT ProductID, ProdName FROM Products ORDER BY ProdName','ProductID','ProdName',NULL),('Shop_Transfers','UnitsInStock','Units In Stock',1,NULL,NULL,NULL,NULL,NULL,NULL),('Shop_Transfers','Units','Units',1,NULL,NULL,NULL,NULL,NULL,NULL),('Shop_Transfers','ProductName','Product Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Shop_Transfers','SerialNumber','Serial Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('shopcats','category_id','Category ID',2,'\'',NULL,'SELECT category_id FROM shopcats IN \'serverpathDatabasesalesforce.mdb\' ORDER BY category_name','category_id','category_id',NULL),('shopcats','category_name','Category Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('shopcats','parent_id','Parent Category',2,'\'',NULL,'SELECT category_id, catname FROM shopcats IN \'serverpathDatabasesalesforce.mdb\' ORDER BY category_name','category_id','catname',NULL),('shopcats','catname','Category Tree',1,'\'',NULL,NULL,NULL,NULL,NULL),('shopcats','description','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','EmployeeID','Employee ID',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','categoryid','Job Title',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','ContactTitle','Contact Title',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','ContactFirstName','Contact First Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','ContactMidName','Contact Middle Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','ContactLastName','Contact Last Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','passportno','Passport No.',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','dateofbirth','Date of Birth',3,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','religion','Religion',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','sex','Sex',4,NULL,NULL,'Â§MaleÂ§ Â§FemaleÂ§ Â¶1Â¶ Â¶2Â¶',NULL,NULL,NULL),('Staff','spousename','Spouse Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','ability','Physical Disability',4,NULL,NULL,'Â§YesÂ§ Â§NoÂ§ Â¶1Â¶ Â¶2Â¶',NULL,NULL,NULL),('Staff','workphone','Work Phone',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','Employees.extension','Extension 1',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','nationality','Nationality',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','stateorigin','State of Origin',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','locgovorigin','LGA of Origin',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','deptname','Department',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','datehired','Date Hired',3,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','datefired','Date Fired',3,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','supervisor','Supervisor',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','homephone','Home Phone',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','leavstatus','Vacation',5,NULL,NULL,NULL,NULL,NULL,NULL),('Staff','emername','Emergency Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','emerphone','Emergency Phone',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','emeraddress','Emergency Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','staffrec','Staff Record',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','Salary','Salary',1,NULL,NULL,NULL,NULL,NULL,NULL),('Staff','InUse','Active',5,NULL,NULL,NULL,NULL,NULL,NULL),('Staff','CompanyOrDepartment','Department',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','BillingAddress','Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','City','City',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','StateOrProvince','State',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','Country/Region','Country',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','PostalCode','Postal Code',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','PhoneNumber','Spouse Phone Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','Vendors.Extension','Extension 2',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','FaxNumber','Mobile Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','EmailAddress','Email Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Staff','credit','Credit',5,NULL,NULL,NULL,NULL,NULL,NULL),('Staff','cheque','Cheque',5,NULL,NULL,NULL,NULL,NULL,NULL),('Staff','amtbal','Account Balance',1,NULL,NULL,NULL,NULL,NULL,NULL),('Staff','creditlimit','Credit Limit',1,NULL,NULL,NULL,NULL,NULL,NULL),('Staff','Discount','Discount',1,NULL,NULL,NULL,NULL,NULL,NULL),('StockDeductions','StockDeductID','Stock Deduct ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('StockDeductions','InvoiceDetailID','Invoice Detail ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('StockDeductions','OrderDetailID','Order Detail ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('StockDeductions','Units','Units',1,NULL,NULL,NULL,NULL,NULL,NULL),('Store_Transfers','transfer_id','Transfer ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Store_Transfers','ReturnID','Return ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('Store_Transfers','ProductID','Product ID',2,NULL,NULL,'SELECT ProductID, ProdName FROM Products ORDER BY ProdName','ProductID','ProdName',NULL),('Store_Transfers','UnitsInStock','Units In Stock',1,NULL,NULL,NULL,NULL,NULL,NULL),('Store_Transfers','Units','Units',1,NULL,NULL,NULL,NULL,NULL,NULL),('Store_Transfers','ProductName','Product Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Store_Transfers','SerialNumber','Serial Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('StoreReturns','ReturnID','Return ID',1,NULL,NULL,NULL,NULL,NULL,NULL),('StoreReturns','ReturnedBy','Returned By',2,'\'',NULL,'SELECT VendorID, VendorName FROM VStaffID','VendorID','VendorName',NULL),('StoreReturns','ReceivedBy','Received By',2,'\'',NULL,'SELECT VendorID, VendorName FROM VStaffID','VendorID','VendorName',NULL),('StoreReturns','ReturnDate','Return Date',3,'\'',NULL,NULL,NULL,NULL,NULL),('StoreReturns','Returned','Returned',5,NULL,NULL,NULL,NULL,NULL,NULL),('StoreReturns','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','VendorID','Supplier ID',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','CompanyName','Company Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','ClientType','ClientType',4,'\'',NULL,'Â§IndividualÂ§ Â§CompanyÂ§ Â¶1Â¶ Â¶2Â¶',NULL,NULL,NULL),('Suppliers','InUse','Active',5,NULL,NULL,NULL,NULL,NULL,NULL),('Suppliers','ContactTitle','Contact Title',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','ContactFirstName','Contact First Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','ContactMidName','Contact Middle Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','ContactLastName','Contact Last Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','CompanyOrDepartment','Department',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','BillingAddress','Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','City','City',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','StateOrProvince','State',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','Country/Region','Country',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','PostalCode','Postal Code',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','PhoneNumber','Phone Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','Extension','Extension',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','FaxNumber','Fax Number',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','EmailAddress','Email Address',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','Notes','Notes',1,'\'',NULL,NULL,NULL,NULL,NULL),('Suppliers','currency','Currency',2,NULL,NULL,'SELECT cur_id, currencyname FROM Currencies ORDER BY currencyname','cur_id','currencyname',NULL),('Suppliers','credit','Credit',5,NULL,NULL,NULL,NULL,NULL,NULL),('Suppliers','cheque','Cheque',5,NULL,NULL,NULL,NULL,NULL,NULL),('Suppliers','amtbal','Account Balance',1,NULL,NULL,NULL,NULL,NULL,NULL),('Suppliers','creditlimit','Credit Limit',1,NULL,NULL,NULL,NULL,NULL,NULL),('Suppliers','Discount','Discount',1,NULL,NULL,NULL,NULL,NULL,NULL),('Usergroups','usergroup','Usergroup',1,'\'',NULL,NULL,NULL,NULL,NULL),('Users','username','User Name',1,'\'',NULL,NULL,NULL,NULL,NULL),('Users','usergroup','User Group',2,'\'',NULL,'SELECT usergroup FROM Usergroups ORDER BY usergroup','usergroup','usergroup',NULL),('Users','EmployeeID','Employee ID',2,'\'',NULL,'SELECT VendorID, VendorName FROM VStaffID','VendorID','VendorName',NULL);
/*!40000 ALTER TABLE `findfielddefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `findviews`
--

DROP TABLE IF EXISTS `findviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `findviews` (
  `ViewName` varchar(50) NOT NULL DEFAULT '',
  `TableList` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ViewName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `findviews`
--

LOCK TABLES `findviews` WRITE;
/*!40000 ALTER TABLE `findviews` DISABLE KEYS */;
INSERT INTO `findviews` VALUES ('Rinvoice','\'Invoices\''),('RInvoiceDet','\'InvoiceDetails\', \'Products\''),('RItemTags','\'Products\''),('ROrderDet','\'orderdetails\', \'Products\''),('RPaymentSum','\'Payments\', \'Staff\', \'Currencies\''),('RPurchaseOrder','\'Orders\''),('RReceptPayments','\'Payments\''),('RServiceDet','\'ServiceInvDetails\', \'Services\''),('Vledger','\'Expenses\', \'Payments\', \'Bills\', \'Invoices\''),('Vrequisitions','\'Requisitions\''),('VSalesPnL','\'Orders\', \'Invoices\', \'InvoiceDetails\', \'orderdetails\''),('Vshop','\'Products\''),('VShopTransfers','\'Requisitions\', \'Shop_Transfers\''),('Vstock','\'Products\''),('Vstockdetails','\'Orders\', \'orderdetails\''),('VStockValue','\'Orders\', \'orderdetails\', \'Products\''),('Vstore','\'Products\''),('VStoreReturns','\'StoreReturns\''),('Vusergroups','\'Usergroups\'');
/*!40000 ALTER TABLE `findviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `help`
--

DROP TABLE IF EXISTS `help`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `help` (
  `HelpID` smallint(5) NOT NULL AUTO_INCREMENT,
  `HelpName` varchar(50) DEFAULT NULL,
  `Pan` tinyint(2) unsigned DEFAULT NULL,
  `Lab` tinyint(2) unsigned DEFAULT NULL,
  `HelpFile` varchar(50) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`HelpID`),
  KEY `HelpName` (`HelpName`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `help`
--

LOCK TABLES `help` WRITE;
/*!40000 ALTER TABLE `help` DISABLE KEYS */;
INSERT INTO `help` VALUES (1,'0',NULL,NULL,'SFHelp.htm','Sales Force Main Help File');
/*!40000 ALTER TABLE `help` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `printlist`
--

DROP TABLE IF EXISTS `printlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `printlist` (
  `printcode` varchar(50) NOT NULL DEFAULT '',
  `pan` tinyint(2) DEFAULT NULL,
  `lab` tinyint(2) DEFAULT NULL,
  `sub` tinyint(1) DEFAULT '0',
  `level` tinyint(1) DEFAULT NULL,
  `qboxtype` tinyint(1) DEFAULT NULL,
  `qboxfield` tinyint(1) DEFAULT NULL,
  `printtype` tinyint(1) DEFAULT NULL,
  `fieldid` varchar(20) DEFAULT NULL,
  `orderkey` varchar(100) DEFAULT NULL,
  `keycolumn` varchar(20) DEFAULT NULL,
  `fieldlist` varchar(100) DEFAULT NULL,
  `keybrace` varchar(2) DEFAULT NULL,
  `reportfile` varchar(200) DEFAULT NULL,
  `V0` tinyint(1) DEFAULT '0',
  `head0` varchar(50) DEFAULT NULL,
  `view0` varchar(1000) DEFAULT NULL,
  `V1` tinyint(1) DEFAULT '0',
  `head1` varchar(50) DEFAULT NULL,
  `view1` varchar(1000) DEFAULT NULL,
  `V2` tinyint(1) DEFAULT '0',
  `head2` varchar(50) DEFAULT NULL,
  `view2` varchar(1000) DEFAULT NULL,
  `V3` tinyint(1) DEFAULT '0',
  `head3` varchar(50) DEFAULT NULL,
  `view3` varchar(1000) DEFAULT NULL,
  `V4` tinyint(1) DEFAULT '0',
  `head4` varchar(50) DEFAULT NULL,
  `view4` varchar(1000) DEFAULT NULL,
  `V5` tinyint(1) DEFAULT '0',
  `head5` varchar(50) DEFAULT NULL,
  `view5` varchar(1000) DEFAULT NULL,
  `V6` tinyint(1) DEFAULT '0',
  `head6` varchar(50) DEFAULT NULL,
  `view6` varchar(1000) DEFAULT NULL,
  `V7` tinyint(1) DEFAULT '0',
  `head7` varchar(50) DEFAULT NULL,
  `view7` varchar(1000) DEFAULT NULL,
  `V8` tinyint(1) DEFAULT '0',
  `head8` varchar(50) DEFAULT NULL,
  `view8` varchar(1000) DEFAULT NULL,
  `V9` tinyint(1) DEFAULT '0',
  `head9` varchar(50) DEFAULT NULL,
  `view9` varchar(1000) DEFAULT NULL,
  `Notes` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`printcode`),
  KEY `pan` (`pan`),
  KEY `lab` (`lab`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `printlist`
--

LOCK TABLES `printlist` WRITE;
/*!40000 ALTER TABLE `printlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `printlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `religion`
--

DROP TABLE IF EXISTS `religion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `religion` (
  `ReligionID` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `Religion` varchar(20) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ReligionID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `religion`
--

LOCK TABLES `religion` WRITE;
/*!40000 ALTER TABLE `religion` DISABLE KEYS */;
INSERT INTO `religion` VALUES (1,'Christian',NULL),(2,'Muslim',NULL);
/*!40000 ALTER TABLE `religion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scripts`
--

DROP TABLE IF EXISTS `scripts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scripts` (
  `script` varchar(20) NOT NULL DEFAULT '',
  `content` mediumtext,
  PRIMARY KEY (`script`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scripts`
--

LOCK TABLES `scripts` WRITE;
/*!40000 ALTER TABLE `scripts` DISABLE KEYS */;
/*!40000 ALTER TABLE `scripts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopcats`
--

DROP TABLE IF EXISTS `shopcats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shopcats` (
  `CatID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` varchar(30) DEFAULT NULL,
  `parent_id` varchar(30) DEFAULT NULL,
  `category_name` varchar(70) DEFAULT NULL,
  `catname` varchar(255) DEFAULT NULL,
  `pix` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`CatID`),
  UNIQUE KEY `category_id` (`category_id`),
  KEY `parent_id` (`parent_id`),
  FULLTEXT KEY `category_name` (`category_name`),
  FULLTEXT KEY `catname` (`catname`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopcats`
--

LOCK TABLES `shopcats` WRITE;
/*!40000 ALTER TABLE `shopcats` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopcats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `state`
--

DROP TABLE IF EXISTS `state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `state` (
  `statecode` varchar(10) NOT NULL DEFAULT '',
  `state` varchar(30) NOT NULL DEFAULT '',
  `comments` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`statecode`),
  KEY `state` (`state`),
  FULLTEXT KEY `statecode` (`statecode`,`state`,`comments`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `state`
--

LOCK TABLES `state` WRITE;
/*!40000 ALTER TABLE `state` DISABLE KEYS */;
INSERT INTO `state` VALUES ('LA','Lagos',''),('ED','Edo',''),('ABJ','Abuja',''),('KD','Kaduna',''),('KN','Kano',''),('ZM','Zamfara',''),('DT','Delta',''),('OY','Oyo',''),('OS','Osun',''),('EK','Ekiti',''),('KW','Kwara',''),('CR','Cross River',''),('AK','Akwa-Ibom',''),('AB','Abia',''),('AN','Anambra',''),('IM','Imo',''),('RV','Rivers',''),('ON','Ondo',''),('BA','Bauchi',''),('BN','Benue',''),('PL','Plateau',''),('KG','Kogi',''),('YB','Yobe',''),('EBY','Ebonyi',''),('JG','Jigawa',''),('AD','Adamawa',''),('BO','Borno',''),('SK','Sokoto',''),('EN','Enugu',''),('BY','Bayelsa',''),('TB','Taraba',''),('OG','Ogun',''),('KT','Katsina',''),('NG','Niger',''),('GM','Gombe',''),('NS','Nassarawa','');
/*!40000 ALTER TABLE `state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tablecodes`
--

DROP TABLE IF EXISTS `tablecodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tablecodes` (
  `table_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `catcode` varchar(10) NOT NULL,
  `tablecode` varchar(20) NOT NULL DEFAULT ' ',
  `dbname` varchar(50) NOT NULL,
  `mod_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`table_id`),
  KEY `dbcode` (`dbname`),
  KEY `mod_id` (`mod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tablecodes`
--

LOCK TABLES `tablecodes` WRITE;
/*!40000 ALTER TABLE `tablecodes` DISABLE KEYS */;
INSERT INTO `tablecodes` VALUES (1,'1-1','carcare','exood',1),(2,'1-2','extaccessory','exood',1),(3,'1-3','intaccessory','exood',1),(4,'1-4','performance','exood',1),(5,'1-5','parts','exood',1),(6,'1-6','tools','exood',1),(7,'10-1','breakroom','exood',1),(8,'10-2','officeclean','exood',1),(9,'10-3','officegifts','exood',1),(10,'10-4','maintenance','exood',1),(11,'10-5','officequip','exood',1),(12,'10-6','officesupplies','exood',1),(13,'11-1','care','exood',1),(14,'11-10','care','exood',1),(15,'11-11','care','exood',1),(16,'11-12','care','exood',1),(17,'11-13','care','exood',1),(18,'11-2','care','exood',1),(19,'11-3','care','exood',1),(20,'11-4','care','exood',1),(21,'11-5','care','exood',1),(22,'11-6','care','exood',1),(23,'11-7','care','exood',1),(24,'11-8','care','exood',1),(25,'11-9','nutrifitness','exood',1),(26,'12-1','cameras','exood',1),(27,'12-2','musical','exood',1),(28,'13','sports','exood',1),(29,'14-1','buildmat','exood',1),(30,'14-10','storage','exood',1),(31,'14-11','painting','exood',1),(32,'14-12','plumbing','exood',1),(33,'14-13','powertools','exood',1),(34,'14-14','safety','exood',1),(35,'14-2','conmachine','exood',1),(36,'14-3','electrical','exood',1),(37,'14-4','handtools','exood',1),(38,'14-5','hardware','exood',1),(39,'14-6','heatcool','exood',1),(40,'14-7','hydraulics','exood',1),(41,'14-8','jobsite','exood',1),(42,'14-9','landscape','exood',1),(43,'15','toys','exood',1),(44,'2','beauty','exood',1),(45,'3-1','clothaccessory','exood',1),(46,'3-2','jewelry','exood',1),(47,'3-3','headgear','exood',1),(48,'3-5','women','exood',1),(49,'3-6','men','exood',1),(50,'3-9','kidsbaby','exood',1),(51,'4-1','electaccessory','exood',1),(52,'4-2','audiovideo','exood',1),(53,'4-3','cardevices','exood',1),(54,'4-4','phones','exood',1),(55,'4-5','computers','exood',1),(56,'4-5-9','software','exood',1),(57,'4-6','games','exood',1),(58,'4-7','handhelds','exood',1),(59,'5-1','bakesupply','exood',1),(60,'5-10','giftbaskets','exood',1),(61,'5-11','healthnatural','exood',1),(62,'5-12','jamjelly','exood',1),(63,'5-13','lowcarb','exood',1),(64,'5-14','meat','exood',1),(65,'5-15','oils','exood',1),(66,'5-16','legumegrain','exood',1),(67,'5-17','preparedapp','exood',1),(68,'5-18','salsacond','exood',1),(69,'5-19','seafood','exood',1),(70,'5-2','beverages','exood',1),(71,'5-20','seasonings','exood',1),(72,'5-21','snacks','exood',1),(73,'5-22','soups','exood',1),(74,'5-23','wine','exood',1),(75,'5-3','bread','exood',1),(76,'5-4','candy','exood',1),(77,'5-5','cheese','exood',1),(78,'5-6','chocolate','exood',1),(79,'5-7','coffetea','exood',1),(80,'5-8','desserts','exood',1),(81,'5-9','fruitveg','exood',1),(82,'6-1','bedbath','exood',1),(83,'6-10','furniture','exood',1),(84,'6-11','lights','exood',1),(85,'6-12','furniture','exood',1),(86,'6-13','furniture','exood',1),(87,'6-14','furniture','exood',1),(88,'6-15','patio','exood',1),(89,'6-2','bedbath','exood',1),(90,'6-3','furniture','exood',1),(91,'6-4','furniture','exood',1),(92,'6-5','furniture','exood',1),(93,'6-6','decor','exood',1),(94,'6-7','furniture','exood',1),(95,'6-8','furniture','exood',1),(96,'6-9','furniture','exood',1),(97,'7-1','healthcare','exood',1),(98,'7-2','healthequip','exood',1),(99,'7-3','drugs','exood',1),(100,'8-1','homesupplies','exood',1),(101,'8-2','housewares','exood',1),(102,'8-3','outdoors','exood',1),(103,'8-4','pets','exood',1),(104,'9-1','book','exood',1),(105,'9-2','newsmag','exood',1),(106,'9-3','audio','exood',1),(107,'9-4','video','exood',1),(108,'3-1','house','exood',3);
/*!40000 ALTER TABLE `tablecodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendortypes`
--

DROP TABLE IF EXISTS `vendortypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendortypes` (
  `VendorID` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `VendorType` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`VendorID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendortypes`
--

LOCK TABLES `vendortypes` WRITE;
/*!40000 ALTER TABLE `vendortypes` DISABLE KEYS */;
INSERT INTO `vendortypes` VALUES (0,'Company Account'),(1,'Customer'),(2,'Supplier'),(3,'Vendor'),(4,'Cash Account'),(5,'Staff'),(6,'Parent'),(7,'Student');
/*!40000 ALTER TABLE `vendortypes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-13 10:49:51
