<?php

require_once('../../scripts/init.php');

if (!isset($_SESSION['newcompany'])) {
    ?>
    <script type="text/javascript">
        with (parent.document) {
            getElementById('img').setAttribute('src', '/images/but_excl.png');
            getElementById('msg').innerHTML = 'Error in authenticating Process...';
            getElementById('status').innerHTML = '<b>Stopped</b>';
        }
    </script>
    <?php

    exit;
}
try {
    $dbh->autocommit(FALSE);

    ignore_user_abort();
    set_time_limit(0);
// connect to the database
    $coyDB = DB_COY . $_SESSION['coyid'];

    $dbmain = getDBDataOne($dbh, "SELECT IF(EXISTS(SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . $coyDB . "'), 'Yes','No') AS db", 'db');
    if ($dbmain == 'No') {
        runDBQry($dbh, "CREATE DATABASE `{$coyDB}` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci");
    }

    $DocDB = DB_DOC . $_SESSION['coyid'];
    $dbmain = getDBDataOne($dbh, "SELECT IF(EXISTS(SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . $DocDB . "'), 'Yes','No') AS db", 'db');
    if ($dbmain == 'No') {
        runDBQry($dbh, "CREATE DATABASE `{$DocDB}` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci");
    }

    runDBQry($dbh, 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO"');

    runDBQry($dbh, "USE `{$coyDB}`");
// ON UPDATE CURRENT_TIMESTAMP
    runDBQry($dbh, "CREATE TABLE `assets` (
  `AssetID` int(11) NOT NULL AUTO_INCREMENT,
  `AssetCode` varchar(50) DEFAULT '',
  `AssetName` varchar(50) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `Category` int(11) DEFAULT NULL,
  `AssetType` int(11) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `staff` int(11) DEFAULT NULL,
  `desgtype` tinyint(2) DEFAULT NULL,
  `occupant` int(11) DEFAULT NULL,
  `designation` tinytext,
  `colour` tinyint(2) DEFAULT NULL,
  `picturefile` tinyint(2) DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  `Notes` text,
  `Status` int(11) DEFAULT NULL,
  `InUse` tinyint(1) DEFAULT '0',
  `NextSchedMaint` date DEFAULT NULL,
  `Capacity` DECIMAL(2,2) DEFAULT '0',
  `Children` smallint(6) DEFAULT '0',
  `Brand` varchar(30) DEFAULT NULL,
  `Model` varchar(30) DEFAULT NULL,
  `serialno` varchar(30) DEFAULT NULL,
  `modelno` varchar(30) DEFAULT NULL,
  `partno` varchar(30) DEFAULT NULL,
  `BarcodeNumber` varchar(30) DEFAULT NULL,
  `licenceno` varchar(30) DEFAULT NULL,
  `purchfrom` int(11) DEFAULT NULL,
  `PurchCost` decimal(19,2) DEFAULT '0.00',
  `CurPurchCost` decimal(19,2) DEFAULT '0.00',
  `AuctionValue` decimal(19,2) DEFAULT '0.00',
  `dateofpurch` date DEFAULT NULL,
  `DateSold` date DEFAULT NULL,
  `insurers` int(11) DEFAULT NULL,
  `servcomp` int(11) DEFAULT NULL,
  `insuranceno` varchar(30) DEFAULT NULL,
  `DepreciationMethod` tinyint(1) DEFAULT NULL,
  `DepreciableLife` float DEFAULT '0',
  `DepreciationValue` decimal(19,2) DEFAULT '0.00',
  `DepreciationRate` float DEFAULT '0',
  `SalvageValue` decimal(19,2) DEFAULT '0.00',
  PRIMARY KEY (`AssetID`),
  KEY `parent` (`parent`),
  KEY `Category` (`Category`),
  KEY `AssetType` (`AssetType`),
  KEY `department` (`department`),
  KEY `staff` (`staff`),
  KEY `occupant` (`occupant`),
  KEY `colour` (`colour`),
  KEY `InUse` (`InUse`),
  KEY `Status` (`Status`),
  KEY `insurers` (`insurers`),
  KEY `servcomp` (`servcomp`),
  KEY `purchfrom` (`purchfrom`),
  FULLTEXT KEY `Notes` (`description`,`Notes`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1000000001");

    runDBQry($dbh, "CREATE TABLE `bills` (
  `BillID` int(11) NOT NULL AUTO_INCREMENT,
  `Dept` int(11) NOT NULL,
  `OutetID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT '0',
  `InvoiceID` int(11) DEFAULT NULL,
  `BillTitle` varchar(50) DEFAULT NULL,
  `VendorType` tinyint(2) DEFAULT '0',
  `VendorID` int(11) DEFAULT '0',
  `CustomerName` varchar(50) DEFAULT NULL,
  `BillType` int(11) DEFAULT NULL,
  `Status` int(11) DEFAULT NULL,
  `BillDate` date DEFAULT NULL,
  `ReceivedDate` date DEFAULT NULL,
  `Amount` decimal(19,2) DEFAULT '0.00',
  `RecAccountBalance` decimal(19,2) DEFAULT '0.00',
  `Payed` DECIMAL(19,2) DEFAULT '0.00',
  `payable` TINYINT(1) NOT NULL DEFAULT '0',
  `entrytype` TINYINT(1) NOT NULL DEFAULT '0',
  `Notes` mediumtext,
  `Posted` tinyint(1) DEFAULT '0',
  `LedgerDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`BillID`),
  KEY `EmployeeID` (`EmployeeID`),
  KEY `VendorID` (`VendorID`),
  KEY `BillType` (`BillType`),
  KEY `BillDate` (`BillDate`),
  KEY `Posted` (`Posted`),
  KEY `payable` (`payable`),
  KEY `entrytype` (`entrytype`),
  KEY `Dept` (`Dept`),
  KEY `OutetID` (`OutetID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1000000001");

    runDBQry($dbh, "CREATE TABLE `classifications` (
  `catID` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` varchar(50) DEFAULT NULL,
  `parent_id` varchar(50) DEFAULT '',
  `category_name` tinytext,
  `catname` tinytext,
  `code` varchar(20) DEFAULT '',
  `catype` int(11) DEFAULT 0,
  `cat_tag` int(11) DEFAULT 0,
  `pix` tinyint(2) DEFAULT 0,
  `description` tinytext,
  `guid` varbinary(50) NOT NULL,
  `tmp_par` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`catID`),
  KEY `category_id` (`category_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "INSERT INTO `classifications` (`catID`, `category_id`, `parent_id`, `category_name`, `catname`, `catype`, `cat_tag`, `pix`, `description`) VALUES
(1, '1', 1, 'Departments', 'Departments', 1, 0, 0, 'Container for all Departments'),
(2, '2', 2, 'Products', 'Products', 2, 0, 0, 'Container for all Product Categories'),
(3, '3', 3, 'Services', 'Services', 3, 0, 0, 'Container for all Service Categories'),
(4, '4', 4, 'Assets', 'Assets', 4, 0, 0, 'Container for all Asset Categories'),
(5, '5', 5, 'Staff', 'Staff', 5, 0, 0, 'Container for all Staff Categories'),
(6, '6', 6, 'Clients', 'Clients', 6, 0, 0, 'Container for all Client Categories'),
(7, '7', 7, 'Accounts', 'Accounts', 7, 0, 0, 'Container for all Account Categories'),
(8, '8', 8, 'Documents', 'Documents', 8, 0, 0, 'Container for all Document Categories')");

    runDBQry($dbh, "CREATE TABLE `colors` (
  `colorid` tinyint(2) DEFAULT 0,
  `colorname` varchar(20) DEFAULT '',
  `colorcode` varchar(10) DEFAULT ''
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "INSERT INTO `colors` (`colorid`, `colorname`, `colorcode`) VALUES
(1, 'Beige', '#F5F5DC'),
(2, 'Black', '#000000'),
(3, 'Blue', '#0000FF'),
(4, 'Brown', '#A52A2A'),
(5, 'Burgundy', '#9E0508'),
(6, 'Champagne', '#FAECCC'),
(7, 'Charcoal', '#464646'),
(8, 'Cream', '#FFFDD0'),
(9, 'Gold', '#FFD700'),
(10, 'Gray', '#808080'),
(11, 'Green', '#008000'),
(12, 'Maroon', '#800000'),
(13, 'Off White', '#F5F5F5'),
(14, 'Orange', '#FFA500'),
(15, 'Pewter', '#96A8A1'),
(16, 'Pink', '#FFC0CB'),
(17, 'Purple', '#800080'),
(18, 'Red', '#FF0000'),
(19, 'Silver', '#C0C0C0'),
(20, 'Tan', '#D2B48C'),
(21, 'Teal', '#008080'),
(22, 'Titanium', '#FCFFF0'),
(23, 'Turquoise', '#40E0D0'),
(24, 'White', '#FFFFFF'),
(25, 'Yellow', '#FFFF00'),
(26, 'Other', '#')");

    runDBQry($dbh, "CREATE TABLE `currencies` (
  `cur_id` int(11) NOT NULL AUTO_INCREMENT,
  `currencyname` varchar(20) DEFAULT NULL,
  `symbol` varchar(5) DEFAULT NULL,
  `code` varchar(10) DEFAULT '',
  `unitname` varchar(20) DEFAULT NULL,
  `unitsymbol` varchar(5) DEFAULT NULL,
  `unitcode` varchar(10) DEFAULT '',
  `fromrate` smallint(6) DEFAULT 0,
  `torate` smallint(6) DEFAULT 0,
  `fullname` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`cur_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

    runDBQry($dbh, "INSERT INTO `currencies` (`cur_id`, `currencyname`, `symbol`, `code`, `unitname`, `unitsymbol`, `unitcode`, `fromrate`, `torate`, `fullname`) VALUES
(1, 'Naira', '=N=', '&#x20a6;', 'Kobo', 'k', 'k', 152, 1, 'Nigerian Naira'),
(2, 'Dollar', '$', '$', 'Cent', 'Â¢', '&#162;', 1, 1, 'US Dollar'),
(3, 'Pound', 'Â£', '&#163;', 'Pence', 'p', 'p', 100, 163, 'British Pound'),
(4, 'Euro', 'Eur', '&#128;', 'Cent', 'Â¢', '&#162;', 100, 144, 'Euro'),
(5, 'Yen', 'Â¥', '&#165;', 'Sen', 's', 's', 7716, 100, 'Japanese Yen'),
(6, 'Franc', 'Fr', '&#8355;', 'centime', 'Â¢', '&#162;', 463, 1, 'Franc'),
(7, 'Yuan', 'CNY', 'CNY', 'Fen', 'Â¢', '&#162;', 639, 100, 'Yuan')");

    runDBQry($dbh, "CREATE TABLE `deductions` (
  `ded_id` int(11) NOT NULL AUTO_INCREMENT,
  `par_id` int(10) DEFAULT NULL,
  `paybatchid` int(11) DEFAULT NULL,
  `Title` varchar(50) DEFAULT NULL,
  `VendorID` int(11) NOT NULL,
  `deduct` decimal(10,2) DEFAULT '0.00',
  `ded` decimal(9,2) DEFAULT '0.00',
  `bal` decimal(9,2) DEFAULT '0.0',
  `accbal` decimal(9,2) DEFAULT '0.0',
  `description` varchar(1000) DEFAULT NULL,
  `dt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ded_id`),
  KEY `paybatchid` (`paybatchid`),
  KEY `VendorID` (`VendorID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `deliveries` (
  `DeliveryID` int(11) NOT NULL AUTO_INCREMENT,
  `ShipperID` int(11) DEFAULT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `ShippedVia` varchar(50) DEFAULT NULL,
  `ShipperTrackingCode` varchar(50) DEFAULT NULL,
  `ShipDate` datetime DEFAULT NULL,
  `ShipperPhoneNumber` varchar(30) DEFAULT NULL,
  `ShippedFrom` varchar(50) DEFAULT NULL,
  `DestinationAddress` tinytext,
  `DestinationCity` varchar(50) DEFAULT NULL,
  `DestinationState` varchar(50) DEFAULT NULL,
  `DestinationPostalCode` varchar(20) DEFAULT NULL,
  `DestinationCountry` int(11) DEFAULT 0,
  `ArrivalDateTime` datetime DEFAULT NULL,
  `CurrentLocation` tinytext,
  `PackageDimensions` varchar(50) DEFAULT NULL,
  `PackageWeight` varchar(50) DEFAULT NULL,
  `PickUpLocation` tinytext,
  `PickUpDateTime` datetime DEFAULT NULL,
  `ReceivedBy` varchar(50) DEFAULT NULL,
  `FreightCharge` decimal(19,2) DEFAULT '0.0',
  `delivered` tinyint(1) DEFAULT 0,
  `Notes` mediumtext,
  PRIMARY KEY (`DeliveryID`),
  KEY `ShipperID` (`ShipperID`),
  KEY `EmployeeID` (`EmployeeID`),
  KEY `DestinationCountry` (`DestinationCountry`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `depreciation` (
  `DepreciationID` int(11) NOT NULL AUTO_INCREMENT,
  `EquipID` int(11) DEFAULT NULL,
  `DepreciationDate` datetime DEFAULT NULL,
  `DepreciationAmount` decimal(19,2) DEFAULT '0.0',
  PRIMARY KEY (`DepreciationID`),
  KEY `EquipID` (`EquipID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `deptstockded` (
  `DeductID` int(11) NOT NULL AUTO_INCREMENT,
  `DeptID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` float NOT NULL,
  `Description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`DeductID`),
  KEY `DeptID` (`DeptID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `documentfiles` (
  `DocID` int(11) NOT NULL AUTO_INCREMENT,
  `shelf` varchar(30) NOT NULL,
  `OwnerID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `Category` int(11) DEFAULT NULL,
  `fname` varchar(30) NOT NULL,
  `FileName` tinytext,
  `SecCode` varchar(255) DEFAULT NULL,
  `Description` tinytext,
  `DocDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`DocID`),
  KEY `shelf` (`shelf`),
  KEY `OwnerID` (`OwnerID`),
  KEY `EmployeeID` (`EmployeeID`),
  KEY `Category` (`Category`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE IF NOT EXISTS `edms` (
  `doc_id` int(11) NOT NULL AUTO_INCREMENT,
  `tmpl_id` int(11) NOT NULL,
  `docname` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `author` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `maindoc` int(11) NOT NULL,
  `editedby` int(11) NOT NULL,
  `edited` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editwhy` text NOT NULL DEFAULT '',
  `dept` int(11) NOT NULL,
  `approvals` varchar(100) NOT NULL,
  `approver` INT(11) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `notes` text NOT NULL,
  `unviewed` TEXT NOT NULL DEFAULT '',
  PRIMARY KEY (`doc_id`),
  KEY `author` (`author`),
  KEY `approver` (`approver`),
  KEY `created` (`created`),
  KEY `dept` (`dept`),
  KEY `edited` (`edited`),
  KEY `editedby` (`editedby`),
  KEY `maindoc` (`maindoc`),
  KEY `tmpl_id` (`tmpl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1000000001");

    runDBQry($dbh, "CREATE TABLE IF NOT EXISTS `edms_num` (
  `doc_cat` int(11) NOT NULL,
  `prefix` varchar(10) NOT NULL,
  `autonum` int(11) NOT NULL,
  PRIMARY KEY (`doc_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE IF NOT EXISTS `edms_tmpl` (
  `tmpl_id` int(10) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `tmpl_name` varchar(100) NOT NULL,
  `tmpl_det` varchar(2000) NOT NULL,
  `description` varchar(2000) NOT NULL DEFAULT '',
  `cmp_idx` SMALLINT(4) UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`tmpl_id`),
  KEY `category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `events` (
  `EventID` int(11) NOT NULL AUTO_INCREMENT,
  `EventName` varchar(50) DEFAULT NULL,
  `EventTypeID` int(11) DEFAULT NULL,
  `Status` varchar(20) DEFAULT NULL,
  `StartDate` datetime DEFAULT NULL,
  `EndDate` datetime DEFAULT NULL,
  `Confirmed` tinyint(1) DEFAULT 0,
  `AvailableSpaces` smallint(6) DEFAULT 0,
  `EventDescription` tinytext,
  `Notes` mediumtext,
  `EmployeeID` int(11) DEFAULT 0,
  PRIMARY KEY (`EventID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `expenses` (
  `ExpenseID` int(11) NOT NULL AUTO_INCREMENT,
  `Dept` int(11) NOT NULL,
  `OutetID` int(11) NOT NULL,
  `AccountID` int(11) DEFAULT '0',
  `EmployeeID` int(11) DEFAULT '0',
  `InvoiceID` int(11) DEFAULT '0',
  `ExpenseTitle` varchar(20) DEFAULT NULL,
  `AuthorizedBy` int(11) DEFAULT NULL,
  `VendorType` tinyint(2) DEFAULT '0',
  `VendorID` int(11) DEFAULT '0',
  `Recipient` varchar(50) DEFAULT NULL,
  `ExpenseType` int(11) DEFAULT NULL,
  `Amount` decimal(19,2) DEFAULT '0.00',
  `RecAccountBalance` decimal(19,2) DEFAULT '0.00',
  `AccountBalance` decimal(19,2) DEFAULT '0.00',
  `ExpenseDate` datetime DEFAULT NULL,
  `DateSubmitted` datetime DEFAULT NULL,
  `Posted` tinyint(1) DEFAULT '0',
  `Status` smallint(6) DEFAULT NULL,
  `PaymentMethodID` int(11) DEFAULT NULL,
  `PaymentMethod` varchar(100) DEFAULT '',
  `AccountName` varchar(50) NOT NULL DEFAULT '',
  `AccountNumber` varchar(30) DEFAULT NULL,
  `CheckNumber` varchar(20) DEFAULT '0',
  `CreditCardType` int(11) DEFAULT NULL,
  `CheckDate` date DEFAULT NULL,
  `Notes` tinytext,
  `LedgerDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `bills` varchar(1000) NOT NULL DEFAULT '',
  `payments` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`ExpenseID`),
  KEY `AccountID` (`AccountID`),
  KEY `EmployeeID` (`EmployeeID`),
  KEY `AuthorizedBy` (`AuthorizedBy`),
  KEY `VendorType` (`VendorType`),
  KEY `VendorID` (`VendorID`),
  KEY `ExpenseType` (`ExpenseType`),
  KEY `Posted` (`Posted`),
  KEY `CreditCardType` (`CreditCardType`),
  KEY `PaymentMethodID` (`PaymentMethodID`),
  KEY `Dept` (`Dept`),
  KEY `OutetID` (`OutetID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `invoicedetails` (
  `InvoiceDetailID` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceID` int(11) DEFAULT 0,
  `ProductID` int(11) DEFAULT 0,
  `ProductName` varchar(50) DEFAULT NULL,
  `serials` mediumtext,
  `units` float DEFAULT '0.0',
  `UnitPrice` decimal(19,2) DEFAULT '0.0',
  `Discount` decimal(19,2) DEFAULT '0.0',
  `LineTotal` decimal(19,2) DEFAULT '0.0',
  `Discnt` float DEFAULT '0.0',
  `TaxRate` float DEFAULT '0.0',
  PRIMARY KEY (`InvoiceDetailID`),
  KEY `InvoiceID` (`InvoiceID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `invoices` (
  `InvoiceID` int(11) NOT NULL AUTO_INCREMENT,
  `OutletID` int(11) DEFAULT 0,
  `AccountID` int(11) DEFAULT 0,
  `EmployeeID` int(11) DEFAULT 0,
  `VendorType` tinyint(2) DEFAULT 0,
  `VendorID` int(11) DEFAULT 0,
  `CustomerName` varchar(50) DEFAULT NULL,
  `InvoiceDate` date DEFAULT NULL,
  `TaxRate` float DEFAULT '0.0',
  `Dscnt` float DEFAULT '0.0',
  `Discount` decimal(19,2) DEFAULT '0.0',
  `TotTax` decimal(19,2) DEFAULT '0.0',
  `TotDisc` decimal(19,2) DEFAULT '0.0',
  `TotalValue` decimal(19,2) DEFAULT '0.0',
  `Grandvalue` decimal(19,2) DEFAULT '0.00',
  `RecAccountBalance` decimal(19,2) DEFAULT '0.0',
  `ExchangeFrom` smallint(6) DEFAULT 0,
  `ExchangeTo` smallint(6) DEFAULT 0,
  `InvoiceType` int(11) DEFAULT 0,
  `Status` smallint(6) DEFAULT 0,
  `Posted` tinyint(1) DEFAULT 0,
  `ShipTo` tinytext,
  `Notes` mediumtext,
  `LedgerDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`InvoiceID`),
  KEY `OutletID` (`OutletID`),
  KEY `AccountID` (`AccountID`),
  KEY `EmployeeID` (`EmployeeID`),
  KEY `VendorID` (`VendorID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `items` (
  `ItemID` int(11) NOT NULL AUTO_INCREMENT,
  `typ` tinyint(1) DEFAULT 0,
  `ExoodID` varchar(50) DEFAULT NULL,
  `ProdCode` varchar(20) DEFAULT NULL,
  `ProdName` varchar(50) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `picturefile` tinyint(2) DEFAULT 0,
  `Classification` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `UnitPrice` decimal(19,2) DEFAULT '0.0',
  `WebPrice` decimal(19,2) DEFAULT '0.0',
  `itmtax` decimal(19,2) DEFAULT '0.0',
  `InUse` tinyint(1) DEFAULT 0,
  `Notes` mediumtext,
  `exood` tinyint(1) DEFAULT 0,
  `exoodsales` tinyint(1) DEFAULT 0,
  `InfoLoad` tinyint(1) DEFAULT 0,
  `pixLoad` tinyint(1) DEFAULT 0,
  `StockLoad` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`ItemID`),
  KEY `category` (`category`),
  KEY `Classification` (`Classification`),
  KEY `status` (`status`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=10001");

    runDBQry($dbh, "CREATE TABLE `items_pkgs` (
  `PackageID` int(11) NOT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `Dscnt` int(11) DEFAULT 0,
  `Discount` decimal(19,2) DEFAULT '0.0',
  `TotTax` decimal(19,2) DEFAULT '0.0',
  `TotDisc` decimal(19,2) DEFAULT '0.0',
  `TotalValue` decimal(19,2) DEFAULT '0.0',
  `Grandvalue` decimal(19,2) DEFAULT '0.0',
  `wkday` varchar(20) NULL DEFAULT '',
  `LimitedTime` tinyint(1) DEFAULT 0,
  `outlets` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`PackageID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `items_pkgs_itms` (
  `PackItemID` int(11) NOT NULL AUTO_INCREMENT,
  `PackageID` int(11) DEFAULT 0,
  `ProductID` int(11) DEFAULT 0,
  `Quantity` float DEFAULT '0.0',
  `Discount` decimal(19,2) DEFAULT '0.0',
  `Discnt` float DEFAULT '0.0',
  PRIMARY KEY (`PackItemID`),
  KEY `PackageID` (`PackageID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `items_prod` (
  `ProductID` int(11) NOT NULL,
  `Brand` int(11) DEFAULT NULL,
  `colour` tinyint(2) DEFAULT NULL,
  `weight` float DEFAULT '0.0',
  `length` float DEFAULT '0.0',
  `width` float DEFAULT '0.0',
  `breadth` float DEFAULT '0.0',
  `warranty` int(11) DEFAULT NULL,
  `SupplierID` int(11) DEFAULT NULL,
  `serials` mediumtext,
  `xbarcodes` tinytext,
  `unit` int(11) DEFAULT NULL,
  `unitsinpack` float DEFAULT '0.0',
  `webstock` float DEFAULT '0.0',
  `ReorderLevel` float DEFAULT '0.0',
  `UnitsOnOrder` float DEFAULT '0.0',
  `IsEquip` tinyint(1) DEFAULT 0,
  `serialized` tinyint(1) DEFAULT 0,
  `LeadTime` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ProductID`),
  KEY `Brand` (`Brand`),
  KEY `warranty` (`warranty`),
  KEY `SupplierID` (`SupplierID`),
  KEY `unit` (`unit`),
  KEY `colour` (`colour`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `items_srv` (
  `ServiceID` int(11) NOT NULL,
  `department` int(11) DEFAULT NULL,
  `outlets` varchar(255) DEFAULT NULL,
  `useasset` tinyint(1) DEFAULT 0,
  `assetcat` varchar(100) DEFAULT NULL,
  `quantity` smallint(6) DEFAULT 0,
  `MachineTime` decimal(19,2) DEFAULT '0.0',
  `timetype` tinyint(1) DEFAULT 1,
  `periods` smallint(5) DEFAULT 0,
  `repeated` tinyint(1) DEFAULT 0,
  `starttime` datetime NULL,
  `endtime` datetime NULL,
  `eventdate` text DEFAULT '',
  `rec_type` varchar(64) DEFAULT '',
  `event_length` bigint(20) DEFAULT '0',  
  `alertperiod` int(11) DEFAULT 0,
  PRIMARY KEY (`ServiceID`),
  KEY `department` (`department`),
  KEY `timetype` (`timetype`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `items_srv_sched` (
  `SrvSchedID` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceDetailID` int(11) DEFAULT NULL,
  `PackItemID` INT(11) DEFAULT NULL,
  `AssetID` int(11) DEFAULT NULL,
  `MachineTime` decimal(19,2) DEFAULT '0.0',
  `EmployeeID` int(11) DEFAULT 0,
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  `renew` tinyint(1) DEFAULT 0,
  `Status` int(11) DEFAULT NULL,
  `Notes` tinytext,
  PRIMARY KEY (`SrvSchedID`),
  KEY `InvoiceDetailID` (`InvoiceDetailID`),
  KEY `AssetID` (`AssetID`),
  KEY `Status` (`Status`),
  KEY `PackItemID` (`PackItemID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `maintenance` (
  `MaintenanceID` int(11) NOT NULL AUTO_INCREMENT,
  `EquipID` int(11) DEFAULT NULL,
  `MaintenanceDate` datetime DEFAULT NULL,
  `MaintenanceDescription` tinytext,
  `VendorID` int(11) DEFAULT NULL,
  `MaintenancePerformedBy` varchar(50) DEFAULT NULL,
  `MaintenanceCost` decimal(19,2) DEFAULT '0.0',
  PRIMARY KEY (`MaintenanceID`),
  KEY `EquipID` (`EquipID`),
  KEY `VendorID` (`VendorID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `orderdetails` (
  `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(50) DEFAULT NULL,
  `serials` mediumtext,
  `Quantity` float DEFAULT '0.0',
  `unitsinpack` float DEFAULT '0.0',
  `UnitPrice` decimal(19,2) DEFAULT '0.0',
  `Margin` float DEFAULT '0.0',
  `calcost` decimal(19,2) DEFAULT '0.0',
  `sugsell` decimal(19,2) DEFAULT '0.0',
  `oldsell` decimal(19,2) DEFAULT '0.0',
  `currentstock` tinyint(1) DEFAULT 0,
  `ExpiryDate` datetime DEFAULT NULL,
  `Expires` tinyint(1) DEFAULT 0,
  `Cleared` tinyint(1) DEFAULT 0,
  `QtyinStock` float DEFAULT '0.0',
  `Received` float DEFAULT '0.0',
  PRIMARY KEY (`OrderDetailID`),
  KEY `OrderID` (`OrderID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `orderreturndet` (
  `OrderRetDetID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderDetailID` int(11) NOT NULL,
  `OrderRetID` int(11) NOT NULL,
  `units` float DEFAULT '0.0',
  `serials` mediumtext,
  PRIMARY KEY (`OrderRetDetID`),
  KEY `OrderDetailID` (`OrderDetailID`),
  KEY `OrderRetID` (`OrderRetID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `orderreturns` (
  `OrderRetID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `ReturnDate` date DEFAULT NULL,
  `ShipName` varchar(50) DEFAULT NULL,
  `ShipAddress` tinytext,
  `ShipDate` date DEFAULT NULL,
  `ShippingMethodID` varchar(50) DEFAULT NULL,
  `FreightCharge` decimal(19,2) DEFAULT '0.0',
  `expenses` decimal(19,2) DEFAULT '0.0',
  `TotalValue` decimal(19,2) DEFAULT '0.0',
  `RecAccountBalance` decimal(19,2) DEFAULT '0.0',
  `Posted` tinyint(1) DEFAULT 0,
  `Notes` tinytext,
  `LedgerDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`OrderRetID`),
  KEY `OrderID` (`OrderID`),
  KEY `EmployeeID` (`EmployeeID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `SupplierID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `OrderDate` date DEFAULT NULL,
  `PurchaseOrderNumber` varchar(30) DEFAULT NULL,
  `RequiredByDate` date DEFAULT NULL,
  `PromisedByDate` date DEFAULT NULL,
  `ShipName` varchar(50) DEFAULT NULL,
  `ShipAddress` tinytext,
  `ShipCity` varchar(50) DEFAULT NULL,
  `ShipState` varchar(50) DEFAULT NULL,
  `ShipStateOrProvince` varchar(50) DEFAULT NULL,
  `ShipPostalCode` varchar(20) DEFAULT NULL,
  `ShipCountry` int(11) DEFAULT 0,
  `ShipPhoneNumber` varchar(30) DEFAULT NULL,
  `ShipDate` date DEFAULT NULL,
  `ShippingMethodID` varchar(50) DEFAULT NULL,
  `ShopCurrency` int(11) NOT NULL,
  `Currency` int(11) NOT NULL,
  `ExchangeFrom` smallint(6) DEFAULT 0,
  `ExchangeTo` smallint(6) DEFAULT 0,
  `FreightCharge` decimal(19,2) DEFAULT '0.0',
  `SalesTaxRate` float DEFAULT '0.0',
  `Margin` float DEFAULT '0.0',
  `Dscnt` float DEFAULT NULL,
  `Discount` decimal(19,2) DEFAULT '0.0',
  `expenses` decimal(19,2) DEFAULT '0.0',
  `OrderTotal` decimal(19,2) DEFAULT '0.0',
  `TotalValue` decimal(19,2) DEFAULT '0.0',
  `RecAccountBalance` decimal(19,2) DEFAULT '0.0',
  `Posted` tinyint(1) DEFAULT 0,
  `outlet` int(11) NOT NULL DEFAULT '0',
  `Notes` mediumtext,
  `LedgerDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`OrderID`),
  KEY `SupplierID` (`SupplierID`),
  KEY `EmployeeID` (`EmployeeID`),
  KEY `Currency` (`Currency`),
  KEY `ShopCurrency` (`ShopCurrency`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "INSERT INTO `orders` (`OrderID`, `SupplierID`, `EmployeeID`, `OrderDate`, `PurchaseOrderNumber`, `RequiredByDate`, `PromisedByDate`, `ShipName`, `ShipAddress`, `ShipCity`, `ShipState`, `ShipStateOrProvince`, `ShipPostalCode`, `ShipCountry`, `ShipPhoneNumber`, `ShipDate`, `ShippingMethodID`, `ShopCurrency`, `Currency`, `ExchangeFrom`, `ExchangeTo`, `FreightCharge`, `SalesTaxRate`, `Margin`, `Dscnt`, `Discount`, `expenses`, `OrderTotal`, `TotalValue`, `Posted`, `Notes`) VALUES
(1000, 5, 1, '2011-01-17 02:03:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, 1, 1, 1, '0.00', 0, 0, 0, '0.00', '0.00', '0.00', '0.00', 1, NULL)");

    runDBQry($dbh, "CREATE TABLE `outlet` (
  `ProductID` int(11) NOT NULL DEFAULT 0,
  `OutletID` int(11) NOT NULL DEFAULT 0,
  `serials` mediumtext,
  `Shopshelf` int(11) DEFAULT NULL,
  `ShopStock` float DEFAULT '0.0',
  `Shopactlstock` float DEFAULT '0.0',
  `Shopstkcntdate` date DEFAULT NULL,
  `Shoplevel` float DEFAULT '0.0',
  `ShopNotes` tinytext,
  PRIMARY KEY (`ProductID`,`OutletID`),
  KEY `ProductID` (`ProductID`),
  KEY `OutletID` (`OutletID`),
  KEY `Shopshelf` (`Shopshelf`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `outlets` (
  `OutletID` int(11) NOT NULL AUTO_INCREMENT,
  `OutletCode` varchar(20) NOT NULL DEFAULT '',
  `OutletName` varchar(50) DEFAULT NULL,
  `Dept` int(11) DEFAULT NULL,
  `guests` varchar(255) DEFAULT '',
  `account` tinyint(1) DEFAULT '0',
  `description` text,
  PRIMARY KEY (`OutletID`),
  KEY `Dept` (`Dept`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

    runDBQry($dbh, "INSERT INTO `outlets` (`OutletID`, `OutletCode`, `OutletName`, `Dept`, `account`, `description`) VALUES
(1, 'STORE', 'Main Store', 1, 0, NULL),
(2, 'SHOP', 'Main Shop', 1, 1, NULL)");

    runDBQry($dbh, "CREATE TABLE `paybatch` (
  `paybatchid` int(11) NOT NULL AUTO_INCREMENT,
  `payday` varchar(20) DEFAULT NULL,
  `dtfrom` date DEFAULT NULL,
  `dtto` date DEFAULT NULL,
  `dategen` timestamp NULL DEFAULT NULL,
  `salary` tinyint(1) DEFAULT 0,
  `bonus` tinyint(1) DEFAULT 0,
  `Posted` tinyint(1) DEFAULT 0,
  `staffid` int(11) NOT NULL,
  `description` text,
  PRIMARY KEY (`paybatchid`),
  KEY `staffid` (`staffid`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `payments` (
  `PaymentID` int(11) NOT NULL AUTO_INCREMENT,
  `OutletID` int(11) DEFAULT 0,
  `AccountID` int(11) DEFAULT 0,
  `EmployeeID` int(11) DEFAULT 0,
  `InvoiceID` int(11) DEFAULT 0,
  `PaymentTitle` varchar(20) DEFAULT NULL,
  `VendorType` tinyint(2) DEFAULT 0,
  `VendorID` int(11) DEFAULT 0,
  `Payer` varchar(50) DEFAULT NULL,
  `PaymentType` int(11) DEFAULT 0,
  `Amount` decimal(19,2) DEFAULT '0.0',
  `RecAccountBalance` decimal(19,2) DEFAULT '0.0',
  `AccountBalance` decimal(19,2) DEFAULT '0.0',
  `PaymentDate` date DEFAULT NULL,
  `PaymentMethodID` int(11) DEFAULT 0,
  `PaymentMethod` varchar(100) DEFAULT NULL,
  `AccountName` varchar(50) NOT NULL DEFAULT '',
  `AccountNumber` varchar(30) DEFAULT NULL,
  `CheckNumber` varchar(20) DEFAULT 0,
  `CreditCardType` int(11) DEFAULT 0,
  `CheckDate` date DEFAULT NULL,
  `Status` smallint(6) DEFAULT 0,
  `Posted` tinyint(1) DEFAULT 0,
  `Notes` tinytext,
  `LedgerDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PaymentID`),
  KEY `OutletID` (`OutletID`),
  KEY `EmployeeID` (`EmployeeID`),
  KEY `VendorType` (`VendorType`),
  KEY `AccountID` (`AccountID`),
  KEY `VendorID` (`VendorID`),
  KEY `PaymentMethodID` (`PaymentMethodID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `payslip` (
  `payslip_id` int(11) NOT NULL AUTO_INCREMENT,
  `paybatchid` int(11) NOT NULL,
  `ref_no` varchar(30) DEFAULT '',
  `worked` tinyint(3) DEFAULT 0,
  `staffid` int(11) NOT NULL,
  `salaryid` int(11) NOT NULL,
  `details` tinytext,
  `code` text DEFAULT NULL,
  PRIMARY KEY (`payslip_id`),
  KEY `StaffID` (`staffid`),
  KEY `SalaryID` (`salaryid`),
  KEY `paybatchid` (`paybatchid`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `produce` (
  `ProduceID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductionID` int(11) DEFAULT 0,
  `ProductID` int(11) DEFAULT 0,
  `Quantity` float DEFAULT '0.0',
  `prodcost` decimal(19,2) DEFAULT '0.0',
  `LineTotal` decimal(19,2) DEFAULT '0.0',
  `ExpiryDate` datetime DEFAULT NULL,
  `Expires` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`ProduceID`),
  KEY `ProductionID` (`ProductionID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `production` (
  `ProductionID` int(11) NOT NULL AUTO_INCREMENT,
  `Production` varchar(50) DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `EndTime` datetime DEFAULT NULL,
  `EmployeeID` int(11) DEFAULT 0,
  `Notes` mediumtext,
  PRIMARY KEY (`ProductionID`),
  KEY `EmployeeID` (`EmployeeID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `projects` (
  `ProjectID` int(11) NOT NULL AUTO_INCREMENT,
  `ProjectName` varchar(50) DEFAULT NULL,
  `ProjectDescription` mediumtext,
  `VendorID` int(11) NOT NULL,
  `ProjectEstimate` decimal(19,2) DEFAULT '0.0',
  `EmployeeID` int(11) DEFAULT 0,
  `ProjectBeginDate` datetime DEFAULT NULL,
  `ProjectEndDate` datetime DEFAULT NULL,
  `Closed` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`ProjectID`),
  KEY `VendorID` (`VendorID`),
  KEY `EmployeeID` (`EmployeeID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `recipes` (
  `RecipeeID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT 0,
  `ProductionID` int(11) DEFAULT 0,
  `Quantity` float DEFAULT '0.0',
  `CostPrice` decimal(19,2) DEFAULT '0.0',
  `TotalValue` decimal(19,2) DEFAULT '0.0',
  PRIMARY KEY (`RecipeeID`),
  KEY `ProductID` (`ProductID`),
  KEY `ProductionID` (`ProductionID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `requisitions` (
  `RequisitID` int(11) NOT NULL AUTO_INCREMENT,
  `Outletout` int(11) DEFAULT 0,
  `Outletin` int(11) DEFAULT 0,
  `transfertype` tinyint(3) DEFAULT NULL,
  `RequestedBy` int(11) DEFAULT 0,
  `GivenBy` int(11) DEFAULT 0,
  `RequestDate` datetime DEFAULT NULL,
  `DateGiven` datetime DEFAULT NULL,
  `Transfered` tinyint(1) DEFAULT 0,
  `ShipName` varchar(50) DEFAULT NULL,
  `ShipAddress` tinytext,
  `ShipDate` date DEFAULT NULL,
  `ShippingMethodID` varchar(50) DEFAULT NULL,
  `FreightCharge` decimal(19,2) DEFAULT '0.0',
  `expenses` decimal(19,2) DEFAULT '0.0',
  `Notes` tinytext,
  `LedgerDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`RequisitID`),
  KEY `Outletout` (`Outletout`),
  KEY `Outletin` (`Outletin`),
  KEY `transfertype` (`transfertype`),
  KEY `RequestedBy` (`RequestedBy`),
  KEY `GivenBy` (`GivenBy`),
  KEY `Transfered` (`Transfered`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `req_items` (
  `transfer_id` int(11) NOT NULL AUTO_INCREMENT,
  `RequisitID` int(11) DEFAULT 0,
  `ProductID` int(11) DEFAULT 0,
  `ProductName` varchar(50) DEFAULT NULL,
  `units` float DEFAULT '0.0',
  `serials` mediumtext,
  PRIMARY KEY (`transfer_id`),
  KEY `RequisitID` (`RequisitID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `salaryscale` (
  `salary_id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_name` varchar(255) NOT NULL,
  `period` tinyint(1) DEFAULT 0,
  `parts` varchar(1000) DEFAULT NULL,
  `typs` varchar(500) DEFAULT NULL,
  `cmls` varchar(500) DEFAULT NULL,
  `ftyp` varchar(500) DEFAULT NULL,
  `oprs` varchar(500) DEFAULT NULL,
  `fncs` varchar(500) DEFAULT NULL,
  `flds` varchar(500) DEFAULT NULL,
  `wins` varchar(500) DEFAULT NULL,
  `state` varchar(500) DEFAULT NULL,
  `description` varchar(10000) DEFAULT NULL,
  PRIMARY KEY (`salary_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    /*
     * School Starts */

    runDBQry($dbh, "CREATE TABLE `sch_arms` (
  `arm_id` int(11) NOT NULL AUTO_INCREMENT,
  `arm_name` varchar(30) NOT NULL,
  `arm_code` varchar(20) NOT NULL,
  `class` int(11) NOT NULL,
  `arm_teacher` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`arm_id`),
  KEY `class` (`class`),
  KEY `arm_teacher` (`arm_teacher`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_assess` (
  `assess_id` int(11) NOT NULL AUTO_INCREMENT,
  `class` int(11) NOT NULL,
  `course` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `student` int(11) NOT NULL,
  `scores` varchar(255) DEFAULT NULL,
  `attend` varchar(1000) DEFAULT NULL,
  `attachs` varchar(2000) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 0,
  `comments` varchar(255) DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`assess_id`),
  KEY `class` (`class`),
  KEY `course` (`course`),
  KEY `term` (`term`),
  KEY `student` (`student`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_assess_struct` (
  `ass_struct_id` int(11) NOT NULL AUTO_INCREMENT,
  `ass_arm` int(11) NOT NULL,
  `ass_course` int(11) NOT NULL,
  `ass_term` int(11) NOT NULL,
  `ass_names` varchar(2000) DEFAULT NULL,
  `ass_codes` varchar(20) DEFAULT NULL,
  `ass_ca` varchar(255) DEFAULT NULL,
  `ass_state` varchar(255) DEFAULT NULL,
  `ass_grp` varchar(255) DEFAULT NULL,
  `percentages` varchar(255) DEFAULT NULL,
  `max_scores` varchar(255) DEFAULT NULL,
  `attachments` varchar(255) DEFAULT NULL,
  `attend_date` text DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`ass_struct_id`),
  KEY `ass_arm` (`ass_arm`),
  KEY `ass_course` (`ass_course`),
  KEY `ass_term` (`ass_term`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_certificates` (
  `cert_id` int(11) NOT NULL AUTO_INCREMENT,
  `cert_name` varchar(50) DEFAULT NULL,
  `cert_code` varchar(20) DEFAULT NULL,
  `department` int(11) NOT NULL,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`cert_id`),
  KEY `department` (`department`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_class` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(30) DEFAULT NULL,
  `class_code` varchar(20) DEFAULT NULL,
  `cls_level` tinyint(2) DEFAULT 0,
  `cls_teacher` int(11) DEFAULT 0,
  `program` int(11) DEFAULT 0,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`class_id`),
  KEY `cls_teacher` (`cls_teacher`),
  KEY `program` (`program`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_cls_ass_struct` (
  `cls_struct_id` int(11) NOT NULL AUTO_INCREMENT,
  `class` int(11) NOT NULL,
  `cls_term` int(11) NOT NULL,
  `cls_typ` tinyint(1) DEFAULT 0,
  `cls_names` varchar(2000) DEFAULT NULL,
  `cls_codes` varchar(20) DEFAULT NULL,
  `cls_ca` varchar(255) DEFAULT NULL,
  `cls_state` varchar(255) DEFAULT NULL,
  `cls_grp_inf` varchar(255) DEFAULT NULL,
  `cls_percentages` varchar(255) DEFAULT NULL,
  `cls_max_scores` varchar(255) DEFAULT NULL,
  `cls_ass` varchar(255) DEFAULT NULL,
  `cls_ass_state` varchar(255) DEFAULT NULL,
  `cls_sub` varchar(5000) DEFAULT NULL,
  `cls_sub_state` VARCHAR(255),
  `cls_notes` text DEFAULT NULL,
  PRIMARY KEY (`cls_struct_id`),
  UNIQUE KEY `ass_class` (`class`,`cls_term`),
  KEY `class` (`class`),
  KEY `cls_term` (`cls_term`),
  KEY `cls_typ` (`cls_typ`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_cls_attend` (
  `ass_struct_id` int(11) NOT NULL AUTO_INCREMENT,
  `class` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `attend_date` text DEFAULT NULL,
  PRIMARY KEY (`ass_struct_id`),
  KEY `class` (`class`),
  KEY `term` (`term`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(100) DEFAULT NULL,
  `course_code` varchar(20) DEFAULT NULL,
  `course_type` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `lecturer` int(11) NOT NULL,
  `arm_teachers` varchar(255) DEFAULT NULL,
  `arm_list` varchar(255) DEFAULT NULL,
  `curriculum` int(11) DEFAULT NULL,
  `classes` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `course_code` (`course_code`),
  KEY `course_type` (`course_type`),
  KEY `department` (`department`),
  KEY `lecturer` (`lecturer`),
  KEY `curriculum` (`curriculum`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_course_offer` (
  `offer_id` int(11) NOT NULL AUTO_INCREMENT,
  `student` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `courses` varchar(255) DEFAULT NULL,
  `gp` smallint(4) DEFAULT 0,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`offer_id`),
  KEY `student` (`student`),
  KEY `term` (`term`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_electives` (
  `elect_id` int(11) NOT NULL AUTO_INCREMENT,
  `elect_arm` int(11) NOT NULL,
  `elect_term` int(11) NOT NULL,
  `min` tinyint(2) DEFAULT 0,
  `max` tinyint(2) DEFAULT 0,
  `min_gp` smallint(4) DEFAULT 0,
  `max_gp` smallint(4) DEFAULT 0,
  `promote_gp` smallint(4) DEFAULT 0,
  `courses` varchar(2000) DEFAULT NULL,
  `lecturers` varchar(255) DEFAULT NULL,
  `gps` varchar(255) DEFAULT NULL,
  `core_elec` varchar(255) DEFAULT NULL,
  `grp` varchar(255) DEFAULT NULL,
  `grp_inf` varchar(2000) DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`elect_id`),
  UNIQUE KEY `elect_arm` (`elect_arm`,`elect_term`),
  KEY `elect_arm_2` (`elect_arm`),
  KEY `elect_term` (`elect_term`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_evaluate` (
  `assess_id` int(11) NOT NULL AUTO_INCREMENT,
  `class` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `student` int(11) NOT NULL,
  `scores` varchar(255) DEFAULT NULL,
  `attachs` varchar(2000) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 0,
  `comments` text DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`assess_id`),
  KEY `class` (`class`),
  KEY `term` (`term`),
  KEY `student` (`student`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_grades` (
  `grade_id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `grade` varchar(30) DEFAULT NULL,
  `grade_code` varchar(20) DEFAULT NULL,
  `grd_sys` tinyint(2) DEFAULT 0,
  `min` tinyint(3) DEFAULT 0,
  `max` tinyint(3) DEFAULT 0,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`grade_id`),
  KEY `grd_sys` (`grd_sys`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_grade_sys` (
  `grade_sys_id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `grade_sys` varchar(30) DEFAULT NULL,
  `min_pass` tinyint(2) DEFAULT 0,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`grade_sys_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_programs` (
  `prog_id` int(11) NOT NULL AUTO_INCREMENT,
  `prog_name` varchar(255) DEFAULT NULL,
  `prog_code` varchar(20) DEFAULT NULL,
  `prog_type` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT 0,
  `scheme` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `certificate` int(11) NOT NULL,
  `grade` tinyint(1) NOT NULL,
  `class_no` tinyint(2) DEFAULT 0,
  `class_pfx` varchar(30) DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`prog_id`),
  KEY `scheme` (`scheme`),
  KEY `department` (`department`),
  KEY `certificate` (`certificate`),
  KEY `grade` (`grade`),
  KEY `prog_type` (`prog_type`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_schemes` (
  `schm_id` int(11) NOT NULL AUTO_INCREMENT,
  `schm_name` varchar(50) DEFAULT '',
  `term_name` varchar(50) DEFAULT 'Term',
  `term_no` int(2) DEFAULT 0,
  `class_name` varchar(30) DEFAULT NULL,
  `arm_nm` VARCHAR(30) DEFAULT 'Arm',
  `crs_nm` VARCHAR(30) DEFAULT 'Subject',
  `lect_nm` VARCHAR(30) DEFAULT 'Teacher',
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`schm_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_sessions` (
  `sess_id` int(11) NOT NULL AUTO_INCREMENT,
  `sess_name` varchar(30) DEFAULT '',
  `scheme` int(11) NOT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `active` tinyint(1) DEFAULT 0,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`sess_id`),
  KEY `scheme` (`scheme`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_stud_attend` (
  `assess_id` int(11) NOT NULL AUTO_INCREMENT,
  `term` int(11) NOT NULL,
  `student` int(11) NOT NULL,
  `attend` varchar(1000) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`assess_id`),
  KEY `term` (`term`),
  KEY `student` (`student`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `sch_terms` (
  `term_id` int(11) NOT NULL AUTO_INCREMENT,
  `term_name` varchar(30) DEFAULT NULL,
  `session` int(11) NOT NULL,
  `num` tinyint(1) DEFAULT 0,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `active` tinyint(1) DEFAULT 0,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`term_id`),
  KEY `session` (`session`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    /*
     * School Ends */

    runDBQry($dbh, "CREATE TABLE `status` (
  `CategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) DEFAULT '',
  `Category` varchar(20) DEFAULT NULL,
  `Description` text,
  `cattype` varchar(20) DEFAULT NULL,
  `par` int(11) DEFAULT 0,
  `InUse` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`CategoryID`),
  KEY `cattype` (`cattype`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

    runDBQry($dbh, "INSERT INTO `status` (`CategoryID`, `Category`, `Description`, `cattype`, `InUse`) VALUES
(1, 'None', '', '', 1),
(2, 'Second(s)', '', 'ServiceTimeType', 1),
(3, 'Minute(s)', '', 'ServiceTimeType', 1),
(4, 'Hour(s)', '', 'ServiceTimeType', 1),
(5, 'Day(s)', '', 'ServiceTimeType', 1),
(6, 'Month(s)', '', 'ServiceTimeType', 1),
(7, 'Year(s)', '', 'ServiceTimeType', 1),
(8, 'None', '', 'SrvPeriodType', 1),
(9, 'Time Frame', '', 'SrvPeriodType', 1),
(10, 'Time Period', '', 'SrvPeriodType', 1),
(11, 'Store --> Dept.', '', 'TransferType', 1),
(12, 'Store --> Outlet', '', 'TransferType', 1),
(13, 'Store --> Store', '', 'TransferType', 1),
(14, 'Outlet --> Dept.', '', 'TransferType', 1),
(15, 'Outlet --> Outlet', '', 'TransferType', 1),
(16, 'Outlet --> Store', '', 'TransferType', 1),
(17, 'Dept.  --> Dept.', '', 'TransferType', 1),
(18, 'Dept.  --> Outlet', '', 'TransferType', 1),
(19, 'Dept.  --> Store', '', 'TransferType', 1),
(20, 'Refunded', '', 'system', 1),
(21, 'Card', '', 'PaymentType', 1),
(22, 'Cash', '', 'PaymentType', 1),
(23, 'Cheque', '', 'PaymentType', 1),
(24, 'Credit', '', 'PaymentType', 0),
(25, 'Others', '', 'PaymentType', 1),
(26, 'Available', 'Asset is available for deployment', 'AssetStatus', 1),
(27, 'In Use', 'Asset is in use', 'AssetStatus', 1),
(28, 'School', '', 'dept', 1),
(29, 'College', '', 'dept', 1),
(30, 'Faculty', '', 'dept', 1),
(31, 'Department', '', 'dept', 1),
(32, 'Printed', '', 'srv_schd_status', 1)");

    runDBQry($dbh, "CREATE TABLE `stockdeductions` (
  `StockDeductID` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceDetailID` int(11) NOT NULL,
  `OrderDetailID` int(11) NOT NULL,
  `Units` float DEFAULT '0.0',
  PRIMARY KEY (`StockDeductID`),
  KEY `InvoiceDetailID` (`InvoiceDetailID`),
  KEY `OrderDetailID` (`OrderDetailID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `tasks` (
  `TaskID` int(11) NOT NULL AUTO_INCREMENT,
  `TaskDescription` tinytext,
  `TaskType` tinyint(3) DEFAULT NULL,
  `ServiceID` int(11) DEFAULT 0,
  `TaskManager` int(11) DEFAULT 0,
  `StartDate` datetime DEFAULT NULL,
  `EndDate` datetime DEFAULT NULL,
  `Notes` mediumtext,
  `Status` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`TaskID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "INSERT INTO `tasks` (`TaskID`, `TaskDescription`, `TaskType`, `ServiceID`, `TaskManager`, `StartDate`, `EndDate`, `Notes`, `Status`) VALUES
(1, 'General Task', 1, 0, 0, NULL, NULL, NULL, 1)");

    runDBQry($dbh, "CREATE TABLE `taskshedule` (
  `EmployeeTaskID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` varchar(50) DEFAULT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `TaskID` int(11) DEFAULT NULL,
  `StartDate` datetime DEFAULT NULL,
  `EndDate` datetime DEFAULT NULL,
  `ResumeDate` datetime DEFAULT NULL,
  `CloseDate` datetime DEFAULT NULL,
  `Labour` decimal(19,2) DEFAULT '0.0',
  `Notes` tinytext,
  `Status` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`EmployeeTaskID`),
  KEY `EmployeeID` (`EmployeeID`),
  KEY `TaskID` (`TaskID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "CREATE TABLE `usergroups` (
  `usergroup` varchar(20) NOT NULL DEFAULT '',
  `permissions` varchar(1000) DEFAULT '0',
  PRIMARY KEY (`usergroup`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "INSERT INTO `usergroups` (`usergroup`, `permissions`) VALUES
('Admin', '1'),
('NoAccess', '0'),
('Basic', '0'),
('PowerGroup', '1')");

    runDBQry($dbh, "CREATE TABLE `users` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `userpass` varchar(32) DEFAULT NULL,
  `usergroup` varchar(20) DEFAULT NULL,
  `EmployeeID` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`username`),
  UNIQUE KEY `EmployeeID` (`EmployeeID`),
  KEY `usergroup` (`usergroup`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "INSERT INTO `users` (`username`, `userpass`, `usergroup`, `EmployeeID`, `active`) VALUES
('admin', 'b9d11b3be25f5a1a7dc8ca04cd310b28', 'Admin', 1, 0)");

    runDBQry($dbh, "CREATE TABLE `vendors` (
  `VendorID` int(11) NOT NULL AUTO_INCREMENT,
  `vendorcode` varchar(20) DEFAULT NULL,
  `CompanyName` varchar(50) DEFAULT NULL,
  `parentcompany` int(11) DEFAULT NULL,
  `VendorType` tinyint(3) DEFAULT NULL,
  `ClientType` tinyint(2) DEFAULT 0,
  `InUse` tinyint(1) DEFAULT 0,
  `ContactTitle` varchar(50) DEFAULT NULL,
  `ContactFirstName` varchar(30) DEFAULT NULL,
  `ContactMidName` varchar(30) DEFAULT NULL,
  `ContactLastName` varchar(50) DEFAULT NULL,
  `CompanyOrDepartment` varchar(50) DEFAULT NULL,
  `BillingAddress` tinytext,
  `City` varchar(50) DEFAULT NULL,
  `StateOrProvince` varchar(30) DEFAULT NULL,
  `Country` smallint(3) DEFAULT 0,
  `PostalCode` varchar(100) DEFAULT NULL,
  `PhoneNumber` varchar(30) DEFAULT NULL,
  `MobilePhone` varchar(30) DEFAULT NULL,
  `FaxNumber` varchar(30) DEFAULT NULL,
  `Extension` varchar(30) DEFAULT NULL,
  `EmailAddress` varchar(50) DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  `bank` smallint(6) DEFAULT NULL,
  `currency` int(11) DEFAULT NULL,
  `credit` tinyint(1) DEFAULT 0,
  `cheque` tinyint(1) DEFAULT 0,
  `amtbal` decimal(19,2) DEFAULT '0.0',
  `creditlimit` decimal(19,2) DEFAULT '0.0',
  `Discount` smallint(6) DEFAULT 0,
  `signfile` varchar(50) DEFAULT NULL,
  `logofile` varchar(50) DEFAULT NULL,
  `ReferredBy` varchar(50) DEFAULT NULL,
  `LastMeetingDate` datetime DEFAULT NULL,
  `ContactsInterests` tinytext,
  `ChildrenNames` tinytext,
  `categoryid` int(11) DEFAULT NULL,
  `DeptID` int(11) DEFAULT NULL,
  `passportno` varchar(20) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `religion` int(11) DEFAULT 0,
  `sex` int(11) DEFAULT 0,
  `marital_status` tinyint(1) DEFAULT 0,
  `nativetongue` varchar(50) DEFAULT NULL,
  `picturefile` tinyint(2) DEFAULT 0,
  `spousename` varchar(30) DEFAULT NULL,
  `ability` varchar(255) DEFAULT NULL,
  `education` int(11) NOT NULL DEFAULT 0,
  `experience` tinyint(2) NOT NULL DEFAULT 0,
  `workphone` varchar(20) DEFAULT NULL,
  `nationality` smallint(3) DEFAULT 0,
  `stateorigin` varchar(20) DEFAULT NULL,
  `locgovorigin` varchar(30) DEFAULT NULL,
  `datehired` date DEFAULT NULL,
  `datefired` date DEFAULT NULL,
  `supervisor` int(11) DEFAULT NULL,
  `homephone` varchar(20) DEFAULT NULL,
  `leavstatus` tinyint(1) DEFAULT 0,
  `emertype` varchar(20) DEFAULT NULL,
  `emername` varchar(50) DEFAULT NULL,
  `emerphone` varchar(20) DEFAULT NULL,
  `emeraddress` varchar(100) DEFAULT NULL,
  `staffrec` mediumtext,
  `salary` int(11) DEFAULT NULL,
  `tax` varchar(1000) DEFAULT NULL,
  `deduct` varchar(1000) DEFAULT NULL,
  `contract` varchar(30) DEFAULT NULL,
  `worked` tinyint(3) DEFAULT 0,
  `fingerprint` tinytext,
  `guid` varbinary(50) NOT NULL,
  PRIMARY KEY (`VendorID`),
  UNIQUE KEY `vendorcode` (`vendorcode`),
  KEY `parentcompany` (`parentcompany`),
  KEY `VendorType` (`VendorType`),
  KEY `ClientType` (`ClientType`),
  KEY `Country` (`Country`),
  KEY `salary` (`salary`),
  KEY `supervisor` (`supervisor`),
  KEY `nationality` (`nationality`),
  KEY `religion` (`religion`),
  KEY `currency` (`currency`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "INSERT INTO `vendors` (`VendorID`, `vendorcode`, `CompanyName`, `VendorType`, `ClientType`, `InUse`, `ContactLastName`, `ContactFirstName`, `ContactMidName`, `ContactTitle`, `currency`, `credit`) VALUES
(1, 'Admin',        '',                     5, 1, 1, 'Admin',                '', '', '', {$_SESSION['tmpcur']}, 0),
(2, 'Cash at Hand', 'Cash at Hand',         4, 1, 1, 'Cash at Hand',         '', '', '', {$_SESSION['tmpcur']}, 0),
(3, 'company',      'Company Transactions', 0, 0, 1, 'Company Transactions', '', '', '', {$_SESSION['tmpcur']}, 1),
(4, 'Customer',     'Customer',             1, 1, 1, 'Customer',             '', '', '', {$_SESSION['tmpcur']}, 0),
(5, 'Direct',       'Direct Purchase',      2, 2, 1, 'Direct Purchase',      '', '', '', {$_SESSION['tmpcur']}, 0),
(6, 'Vendor',       'Vendor',               3, 2, 1, 'Vendor',               '', '', '', {$_SESSION['tmpcur']}, 0)");

    runDBQry($dbh, "CREATE TABLE `warranty` (
  `WarantyID` int(11) NOT NULL AUTO_INCREMENT,
  `WarantyCode` varchar(50) DEFAULT NULL,
  `PeriodType` tinyint(3) DEFAULT NULL,
  `Duration` smallint(6) DEFAULT 0,
  `Warantee` mediumtext,
  PRIMARY KEY (`WarantyID`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001");

    runDBQry($dbh, "ALTER TABLE `assets`
  ADD CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`department`) REFERENCES `classifications` (`catID`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_ibfk_2` FOREIGN KEY (`Category`) REFERENCES `status` (`CategoryID`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_ibfk_3` FOREIGN KEY (`Status`) REFERENCES `status` (`CategoryID`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_ibfk_4` FOREIGN KEY (`purchfrom`) REFERENCES `vendors` (`VendorID`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_ibfk_5` FOREIGN KEY (`insurers`) REFERENCES `vendors` (`VendorID`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_ibfk_6` FOREIGN KEY (`servcomp`) REFERENCES `vendors` (`VendorID`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_ibfk_7` FOREIGN KEY (`staff`) REFERENCES `vendors` (`VendorID`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_ibfk_8` FOREIGN KEY (`parent`) REFERENCES `assets` (`AssetID`) ON DELETE SET NULL");

    runDBQry($dbh, "ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `bills_ibfk_2` FOREIGN KEY (`VendorID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `bills_ibfk_3` FOREIGN KEY (`BillType`) REFERENCES `classifications` (`catID`),
  ADD CONSTRAINT `bills_ibfk_4` FOREIGN KEY (`Dept`) REFERENCES `classifications`(`catID`), 
  ADD CONSTRAINT `bills_ibfk_5` FOREIGN KEY (`OutetID`) REFERENCES `outlets`(`OutletID`)");

    runDBQry($dbh, "ALTER TABLE `classifications`
  ADD CONSTRAINT `classifications_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `classifications` (`category_id`)");

    runDBQry($dbh, "ALTER TABLE `deductions`
  ADD CONSTRAINT `deductions_ibfk_1` FOREIGN KEY (`VendorID`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_ibfk_1` FOREIGN KEY (`ShipperID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `deliveries_ibfk_2` FOREIGN KEY (`EmployeeID`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `depreciation`
  ADD CONSTRAINT `depreciation_ibfk_1` FOREIGN KEY (`EquipID`) REFERENCES `assets` (`AssetID`)");

    runDBQry($dbh, "ALTER TABLE `deptstockded`
  ADD CONSTRAINT `deptstockded_ibfk_1` FOREIGN KEY (`DeptID`) REFERENCES `classifications` (`catID`),
  ADD CONSTRAINT `deptstockded_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `items_prod` (`ProductID`)");

    runDBQry($dbh, "ALTER TABLE `documentfiles`
  ADD CONSTRAINT `documentfiles_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `documentfiles_ibfk_2` FOREIGN KEY (`Category`) REFERENCES `classifications` (`catID`)");

    runDBQry($dbh, "ALTER TABLE `edms`
  ADD CONSTRAINT `edms_ibfk_5` FOREIGN KEY (`tmpl_id`) REFERENCES `edms_tmpl` (`tmpl_id`),
  ADD CONSTRAINT `edms_ibfk_1` FOREIGN KEY (`author`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `edms_ibfk_2` FOREIGN KEY (`editedby`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `edms_ibfk_3` FOREIGN KEY (`dept`) REFERENCES `classifications` (`catID`)");

    runDBQry($dbh, "ALTER TABLE `edms_tmpl`
  ADD CONSTRAINT `edms_tmpl_ibfk_1` FOREIGN KEY (`category`) REFERENCES `classifications` (`catID`)");

    runDBQry($dbh, "ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`EmployeeID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `expenses_ibfk_3` FOREIGN KEY (`VendorID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `expenses_ibfk_4` FOREIGN KEY (`AuthorizedBy`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `expenses_ibfk_5` FOREIGN KEY (`ExpenseType`) REFERENCES `classifications` (`catID`),
  ADD CONSTRAINT `expenses_ibfk_6` FOREIGN KEY (`PaymentMethodID`) REFERENCES `status` (`CategoryID`),
  ADD CONSTRAINT `expenses_ibfk_7` FOREIGN KEY (`CreditCardType`) REFERENCES `status` (`CategoryID`),
  ADD CONSTRAINT `expenses_ibfk_8` FOREIGN KEY (`Dept`) REFERENCES `classifications`(`catID`), 
  ADD CONSTRAINT `expenses_ibfk_9` FOREIGN KEY (`OutetID`) REFERENCES `outlets`(`OutletID`)");

    runDBQry($dbh, "ALTER TABLE `invoicedetails`
  ADD CONSTRAINT `invoicedetails_ibfk_1` FOREIGN KEY (`InvoiceID`) REFERENCES `invoices` (`InvoiceID`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoicedetails_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `items` (`ItemID`)");

    runDBQry($dbh, "ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`OutletID`) REFERENCES `outlets` (`OutletID`),
  ADD CONSTRAINT `invoices_ibfk_3` FOREIGN KEY (`EmployeeID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `invoices_ibfk_4` FOREIGN KEY (`VendorID`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`Classification`) REFERENCES `classifications` (`catID`),
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`status`) REFERENCES `status` (`CategoryID`)");

    runDBQry($dbh, "ALTER TABLE `items_pkgs`
  ADD CONSTRAINT `items_pkgs_ibfk_1` FOREIGN KEY (`PackageID`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE");

    runDBQry($dbh, "ALTER TABLE `items_pkgs_itms`
  ADD CONSTRAINT `items_pkgs_itms_ibfk_1` FOREIGN KEY (`PackageID`) REFERENCES `items_pkgs` (`PackageID`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_pkgs_itms_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `items` (`ItemID`)");

    runDBQry($dbh, "ALTER TABLE `items_prod`
  ADD CONSTRAINT `items_prod_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_prod_ibfk_2` FOREIGN KEY (`Brand`) REFERENCES `status` (`CategoryID`) ON DELETE SET NULL,
  ADD CONSTRAINT `items_prod_ibfk_3` FOREIGN KEY (`warranty`) REFERENCES `warranty` (`WarantyID`) ON DELETE SET NULL,
  ADD CONSTRAINT `items_prod_ibfk_4` FOREIGN KEY (`SupplierID`) REFERENCES `vendors` (`VendorID`) ON DELETE SET NULL,
  ADD CONSTRAINT `items_prod_ibfk_5` FOREIGN KEY (`unit`) REFERENCES `status` (`CategoryID`) ON DELETE SET NULL");

    runDBQry($dbh, "ALTER TABLE `items_srv`
  ADD CONSTRAINT `items_srv_ibfk_1` FOREIGN KEY (`ServiceID`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_srv_ibfk_2` FOREIGN KEY (`department`) REFERENCES `classifications` (`catID`) ON DELETE SET NULL");

    runDBQry($dbh, "ALTER TABLE `items_srv_sched`
  ADD CONSTRAINT `items_srv_sched_ibfk_1` FOREIGN KEY (`InvoiceDetailID`) REFERENCES `invoicedetails` (`InvoiceDetailID`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_srv_sched_ibfk_2` FOREIGN KEY (`AssetID`) REFERENCES `assets` (`AssetID`),
  ADD CONSTRAINT `items_srv_sched_ibfk_4` FOREIGN KEY (`Status`) REFERENCES `status` (`CategoryID`)");

    runDBQry($dbh, "ALTER TABLE `maintenance`
  ADD CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`EquipID`) REFERENCES `assets` (`AssetID`),
  ADD CONSTRAINT `maintenance_ibfk_2` FOREIGN KEY (`VendorID`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `items_prod` (`ProductID`)");

    runDBQry($dbh, "ALTER TABLE `orderreturndet`
  ADD CONSTRAINT `orderreturndet_ibfk_1` FOREIGN KEY (`OrderRetID`) REFERENCES `orderreturns` (`OrderRetID`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderreturndet_ibfk_2` FOREIGN KEY (`OrderDetailID`) REFERENCES `orderdetails` (`OrderDetailID`)");

    runDBQry($dbh, "ALTER TABLE `orderreturns`
  ADD CONSTRAINT `orderreturns_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`),
  ADD CONSTRAINT `orderreturns_ibfk_2` FOREIGN KEY (`EmployeeID`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`SupplierID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`EmployeeID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`ShopCurrency`) REFERENCES `currencies` (`cur_id`),
  ADD CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`Currency`) REFERENCES `currencies` (`cur_id`)");

    runDBQry($dbh, "ALTER TABLE `outlet`
  ADD CONSTRAINT `outlet_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `items_prod` (`ProductID`),
  ADD CONSTRAINT `outlet_ibfk_2` FOREIGN KEY (`OutletID`) REFERENCES `outlets` (`OutletID`),
  ADD CONSTRAINT `outlet_ibfk_3` FOREIGN KEY (`Shopshelf`) REFERENCES `status` (`CategoryID`) ON DELETE SET NULL");

    runDBQry($dbh, "ALTER TABLE `outlets`
  ADD CONSTRAINT `outlets_ibfk_1` FOREIGN KEY (`Dept`) REFERENCES `classifications` (`catID`)");

    runDBQry($dbh, "ALTER TABLE `paybatch`
  ADD CONSTRAINT `paybatch_ibfk_1` FOREIGN KEY (`staffid`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`EmployeeID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`VendorID`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `payments_ibfk_4` FOREIGN KEY (`OutletID`) REFERENCES `outlets` (`OutletID`)");

    runDBQry($dbh, "ALTER TABLE `payslip`
    ADD CONSTRAINT `payslip_ibfk_1` FOREIGN KEY (`paybatchid`) REFERENCES `paybatch` (`paybatchid`),
  ADD CONSTRAINT `payslip_ibfk_2` FOREIGN KEY (`salaryid`) REFERENCES `salaryscale` (`salary_id`),
  ADD CONSTRAINT `payslip_ibfk_3` FOREIGN KEY (`staffid`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `produce`
  ADD CONSTRAINT `produce_ibfk_1` FOREIGN KEY (`ProductionID`) REFERENCES `production` (`ProductionID`)");

    runDBQry($dbh, "ALTER TABLE `production`
  ADD CONSTRAINT `production_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `requisitions`
  ADD CONSTRAINT `requisitions_ibfk_1` FOREIGN KEY (`Outletout`) REFERENCES `outlets` (`OutletID`),
  ADD CONSTRAINT `requisitions_ibfk_2` FOREIGN KEY (`Outletin`) REFERENCES `outlets` (`OutletID`),
  ADD CONSTRAINT `requisitions_ibfk_3` FOREIGN KEY (`RequestedBy`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `requisitions_ibfk_4` FOREIGN KEY (`GivenBy`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `req_items`
  ADD CONSTRAINT `req_items_ibfk_4` FOREIGN KEY (`RequisitID`) REFERENCES `requisitions` (`RequisitID`),
  ADD CONSTRAINT `req_items_ibfk_5` FOREIGN KEY (`ProductID`) REFERENCES `items_prod` (`ProductID`)");

    runDBQry($dbh, "ALTER TABLE `sch_arms`
  ADD CONSTRAINT `sch_arms_ibfk_1` FOREIGN KEY (`class`) REFERENCES `sch_class` (`class_id`),
  ADD CONSTRAINT `sch_arms_ibfk_2` FOREIGN KEY (`arm_teacher`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `sch_assess`
  ADD CONSTRAINT `sch_assess_ibfk_1` FOREIGN KEY (`class`) REFERENCES `sch_class` (`class_id`),
  ADD CONSTRAINT `sch_assess_ibfk_2` FOREIGN KEY (`course`) REFERENCES `sch_courses` (`course_id`),
  ADD CONSTRAINT `sch_assess_ibfk_3` FOREIGN KEY (`term`) REFERENCES `sch_terms` (`term_id`),
  ADD CONSTRAINT `sch_assess_ibfk_4` FOREIGN KEY (`student`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `sch_assess_struct`
  ADD CONSTRAINT `sch_assess_struct_ibfk_1` FOREIGN KEY (`ass_term`) REFERENCES `sch_terms` (`term_id`),
  ADD CONSTRAINT `sch_assess_struct_ibfk_2` FOREIGN KEY (`ass_arm`) REFERENCES `sch_arms` (`arm_id`),
  ADD CONSTRAINT `sch_assess_struct_ibfk_3` FOREIGN KEY (`ass_course`) REFERENCES `sch_courses` (`course_id`)");

    runDBQry($dbh, "ALTER TABLE `sch_certificates`
  ADD CONSTRAINT `sch_certificates_ibfk_1` FOREIGN KEY (`department`) REFERENCES `classifications` (`catID`)");

    runDBQry($dbh, "ALTER TABLE `sch_class`
  ADD CONSTRAINT `sch_class_ibfk_1` FOREIGN KEY (`cls_teacher`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `sch_class_ibfk_2` FOREIGN KEY (`program`) REFERENCES `sch_programs` (`prog_id`)");

    runDBQry($dbh, "ALTER TABLE `sch_cls_ass_struct`
  ADD CONSTRAINT `sch_cls_ass_struct_ibfk_1` FOREIGN KEY (`cls_term`) REFERENCES `sch_terms` (`term_id`),
  ADD CONSTRAINT `sch_cls_ass_struct_ibfk_2` FOREIGN KEY (`class`) REFERENCES `sch_class` (`class_id`)");

    runDBQry($dbh, "ALTER TABLE `sch_cls_attend`
  ADD CONSTRAINT `sch_cls_attend_ibfk_1` FOREIGN KEY (`term`) REFERENCES `sch_terms` (`term_id`),
  ADD CONSTRAINT `sch_cls_attend_ibfk_2` FOREIGN KEY (`class`) REFERENCES `sch_arms` (`arm_id`)");

    runDBQry($dbh, "ALTER TABLE `sch_courses`
  ADD CONSTRAINT `sch_courses_ibfk_1` FOREIGN KEY (`department`) REFERENCES `classifications` (`catID`),
  ADD CONSTRAINT `sch_courses_ibfk_2` FOREIGN KEY (`lecturer`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `sch_courses_ibfk_3` FOREIGN KEY (`course_type`) REFERENCES `status` (`CategoryID`)");

    runDBQry($dbh, "ALTER TABLE `sch_course_offer`
  ADD CONSTRAINT `sch_course_offer_ibfk_1` FOREIGN KEY (`student`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `sch_course_offer_ibfk_2` FOREIGN KEY (`term`) REFERENCES `sch_terms` (`term_id`)");

    runDBQry($dbh, "ALTER TABLE `sch_electives`
  ADD CONSTRAINT `sch_electives_ibfk_1` FOREIGN KEY (`elect_term`) REFERENCES `sch_terms` (`term_id`),
  ADD CONSTRAINT `sch_electives_ibfk_2` FOREIGN KEY (`elect_arm`) REFERENCES `sch_arms` (`arm_id`)");

    runDBQry($dbh, "ALTER TABLE `sch_evaluate`
  ADD CONSTRAINT `sch_evaluate_ibfk_1` FOREIGN KEY (`class`) REFERENCES `sch_class` (`class_id`),
  ADD CONSTRAINT `sch_evaluate_ibfk_2` FOREIGN KEY (`term`) REFERENCES `sch_terms` (`term_id`),
  ADD CONSTRAINT `sch_evaluate_ibfk_3` FOREIGN KEY (`student`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `sch_grades`
  ADD CONSTRAINT `sch_grades_ibfk_1` FOREIGN KEY (`grd_sys`) REFERENCES `sch_grade_sys` (`grade_sys_id`)");

    runDBQry($dbh, "ALTER TABLE `sch_programs`
  ADD CONSTRAINT `sch_programs_ibfk_1` FOREIGN KEY (`scheme`) REFERENCES `sch_schemes` (`schm_id`),
  ADD CONSTRAINT `sch_programs_ibfk_2` FOREIGN KEY (`department`) REFERENCES `classifications` (`catID`),
  ADD CONSTRAINT `sch_programs_ibfk_3` FOREIGN KEY (`certificate`) REFERENCES `sch_certificates` (`cert_id`),
  ADD CONSTRAINT `sch_programs_ibfk_4` FOREIGN KEY (`grade`) REFERENCES `sch_grades` (`grade_id`),
  ADD CONSTRAINT `sch_programs_ibfk_5` FOREIGN KEY (`prog_type`) REFERENCES `status` (`CategoryID`)");

    runDBQry($dbh, "ALTER TABLE `sch_sessions`
  ADD CONSTRAINT `sch_sessions_ibfk_1` FOREIGN KEY (`scheme`) REFERENCES `sch_schemes` (`schm_id`)");

    runDBQry($dbh, "ALTER TABLE `sch_stud_attend`
  ADD CONSTRAINT `sch_stud_attend_ibfk_1` FOREIGN KEY (`term`) REFERENCES `sch_terms` (`term_id`),
  ADD CONSTRAINT `sch_stud_attend_ibfk_2` FOREIGN KEY (`student`) REFERENCES `vendors` (`VendorID`)");

    runDBQry($dbh, "ALTER TABLE `sch_terms`
  ADD CONSTRAINT `sch_terms_ibfk_1` FOREIGN KEY (`session`) REFERENCES `sch_sessions` (`sess_id`)");

    runDBQry($dbh, "ALTER TABLE `stockdeductions`
  ADD CONSTRAINT `stockdeductions_ibfk_1` FOREIGN KEY (`InvoiceDetailID`) REFERENCES `invoicedetails` (`InvoiceDetailID`),
  ADD CONSTRAINT `stockdeductions_ibfk_2` FOREIGN KEY (`OrderDetailID`) REFERENCES `orderdetails` (`OrderDetailID`)");

    runDBQry($dbh, "ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`usergroup`) REFERENCES `usergroups` (`usergroup`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`EmployeeID`) REFERENCES `vendors` (`VendorID`) ON DELETE CASCADE");

    runDBQry($dbh, "ALTER TABLE `vendors`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`supervisor`) REFERENCES `vendors` (`VendorID`) ON DELETE SET NULL,
  ADD CONSTRAINT `clients_ibfk_2` FOREIGN KEY (`parentcompany`) REFERENCES `vendors` (`VendorID`),
  ADD CONSTRAINT `clients_ibfk_3` FOREIGN KEY (`salary`) REFERENCES `salaryscale` (`salary_id`),
  ADD CONSTRAINT `vendors_ibfk_4` FOREIGN KEY (`currency`) REFERENCES `currencies` (`cur_id`)");

//Document Archive 
//runDBQry($dbh, "USE `phpmyadmin`");

    /* runDBQry($dbh, "INSERT INTO `pma_designer_coords` (`db_name`, `table_name`, `x`, `y`, `v`, `h`) VALUES
      ('exood_coy1', 'stockdeductions', 18, 133, 0, 1),
      ('exood_coy1', 'status', 537, 392, 0, 1),
      ('exood_coy1', 'serviceequip', 818, 575, 0, 1),
      ('exood_coy1', 'sch_terms', 248, 802, 0, 1),
      ('exood_coy1', 'sch_stud_attend', 461, 858, 0, 1),
      ('exood_coy1', 'sch_sessions', 24, 827, 0, 1),
      ('exood_coy1', 'sch_schemes', 21, 791, 0, 1),
      ('exood_coy1', 'sch_programs', 242, 757, 0, 1),
      ('exood_coy1', 'sch_grade_sys', 235, 695, 0, 1),
      ('exood_coy1', 'sch_grades', 243, 724, 0, 1),
      ('exood_coy1', 'sch_evaluate', 462, 804, 0, 1),
      ('exood_coy1', 'sch_electives', 464, 883, 0, 1),
      ('exood_coy1', 'sch_course_offer', 463, 781, 0, 1),
      ('exood_coy1', 'sch_courses', 673, 700, 0, 1),
      ('exood_coy1', 'sch_cls_attend', 462, 831, 0, 1),
      ('exood_coy1', 'sch_cls_ass_struct', 460, 735, 0, 1),
      ('exood_coy1', 'sch_class', 454, 670, 0, 1),
      ('exood_coy1', 'sch_arms', 684, 786, 0, 1),
      ('exood_coy1', 'sch_assess', 462, 703, 0, 1),
      ('exood_coy1', 'sch_assess_struct', 462, 760, 0, 1),
      ('exood_coy1', 'sch_certificates', 13, 748, 0, 1),
      ('exood_coy1', 'events', 834, 796, 0, 1),
      ('exood_coy1', 'expenses', 775, 360, 0, 1),
      ('exood_coy1', 'invoicedetails', 26, 105, 0, 1),
      ('exood_coy1', 'salaryscale', 840, 144, 0, 1),
      ('exood_coy1', 'req_items', 26, 323, 0, 1),
      ('exood_coy1', 'requisitions', 24, 290, 0, 1),
      ('exood_coy1', 'recipes', 829, 695, 0, 1),
      ('exood_coy1', 'outlets', 286, 67, 0, 1),
      ('exood_coy1', 'paybatch', 843, 54, 0, 1),
      ('exood_coy1', 'payments', 775, 416, 0, 1),
      ('exood_coy1', 'payslip', 842, 98, 0, 1),
      ('exood_coy1', 'produce', 832, 716, 0, 1),
      ('exood_coy1', 'production', 827, 678, 0, 1),
      ('exood_coy1', 'projectfiles', 820, 596, 0, 1),
      ('exood_coy1', 'projects', 823, 617, 0, 1),
      ('exood_coy1', 'outlet', 287, 96, 0, 1),
      ('exood_coy1', 'orders', 35, 220, 0, 1),
      ('exood_coy1', 'orderreturns', 23, 193, 0, 1),
      ('exood_coy1', 'orderreturndet', 28, 166, 0, 1),
      ('exood_coy1', 'orderdetails', 29, 254, 0, 1),
      ('exood_coy1', 'maintenance', 794, 281, 0, 1),
      ('exood_coy1', 'items_srv_asset', 275, 159, 0, 1),
      ('exood_coy1', 'items_srv', 276, 186, 0, 1),
      ('exood_coy1', 'items_prod', 270, 277, 0, 1),
      ('exood_coy1', 'invoices', 26, 80, 0, 1),
      ('exood_coy1', 'items', 555, 247, 0, 1),
      ('exood_coy1', 'items_pkgs', 276, 211, 0, 1),
      ('exood_coy1', 'items_pkgs_itms', 267, 232, 0, 1),
      ('exood_coy1', 'documentfiles', 790, 248, 0, 1),
      ('exood_coy1', 'deptstockded', 268, 314, 0, 1),
      ('exood_coy1', 'depreciation', 807, 544, 0, 1),
      ('exood_coy1', 'assets', 553, 200, 0, 1),
      ('exood_coy1', 'bills', 778, 389, 0, 1),
      ('exood_coy1', 'classifications', 25, 466, 0, 1),
      ('exood_coy1', 'colors', 838, 844, 0, 1),
      ('exood_coy1', 'currencies', 250, 480, 0, 1),
      ('exood_coy1', 'deductions', 841, 175, 0, 1),
      ('exood_coy1', 'deliveries', 808, 520, 0, 1),
      ('exood_coy1', 'tasks', 833, 768, 0, 1),
      ('exood_coy1', 'taskshedule', 834, 743, 0, 1),
      ('exood_coy1', 'usergroups', 20, 668, 0, 1),
      ('exood_coy1', 'users', 18, 634, 0, 1),
      ('exood_coy1', 'vendors', 429, 502, 0, 1),
      ('exood_coy1', 'warranty', 270, 255, 0, 1),
      ('exood_coy1', 'items_srv_sched', 274, 161, 0, 1);
      "); */

//Document Archive 
    runDBQry($dbh, "USE {$DocDB}");

    runDBQry($dbh, "CREATE TABLE `clicks` (
  `c_num` mediumint(9) DEFAULT 0,
  `c_url` varchar(255) DEFAULT '',
  `c_val` varchar(255) DEFAULT '',
  `c_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `engine` (
  `spider_id` mediumint(9) NOT NULL DEFAULT 0,
  `key_id` mediumint(9) DEFAULT 0,
  `weight` smallint(4) DEFAULT 0,
  KEY `key_id` (`key_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1;");

    runDBQry($dbh, "CREATE TABLE `excludes` (
  `ex_id` mediumint(11) NOT NULL AUTO_INCREMENT,
  `ex_site_id` mediumint(9) DEFAULT 0,
  `ex_path` text DEFAULT NULL,
  PRIMARY KEY (`ex_id`),
  KEY `ex_site_id` (`ex_site_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

    runDBQry($dbh, "CREATE TABLE `includes` (
  `in_id` mediumint(11) NOT NULL AUTO_INCREMENT,
  `in_site_id` mediumint(9) DEFAULT 0,
  `in_path` text DEFAULT NULL,
  PRIMARY KEY (`in_id`),
  KEY `in_site_id` (`in_site_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

    runDBQry($dbh, "CREATE TABLE `keywords` (
  `key_id` int(9) NOT NULL AUTO_INCREMENT,
  `twoletters` char(2) NULL,
  `keyword` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`key_id`),
  UNIQUE KEY `keyword` (`keyword`),
  KEY `twoletters` (`twoletters`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

    runDBQry($dbh, "CREATE TABLE `logs` (
  `l_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `l_includes` varchar(255) DEFAULT '',
  `l_excludes` varchar(127) DEFAULT NULL,
  `l_num` mediumint(9) DEFAULT 0,
  `l_mode` char(1) DEFAULT NULL,
  `l_ts` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `l_time` float NULL DEFAULT 0,
  PRIMARY KEY (`l_id`),
  KEY `l_includes` (`l_includes`),
  KEY `l_excludes` (`l_excludes`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=6");

    runDBQry($dbh, "CREATE TABLE `sites` (
  `site_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `site_url` varchar(127) DEFAULT NULL,
  `upddate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `port` smallint(6) DEFAULT 0,
  `locked` tinyint(1) DEFAULT 0,
  `stopped` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`site_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

    runDBQry($dbh, "CREATE TABLE `site_page` (
  `site_id` int(4) DEFAULT 0,
  `days` int(4) DEFAULT 0,
  `links` int(4) DEFAULT '5',
  `depth` int(4) DEFAULT '5',
  PRIMARY KEY (`site_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1");

    runDBQry($dbh, "CREATE TABLE `spider` (
  `spider_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `file` varchar(127) DEFAULT NULL,
  `first_words` mediumtext DEFAULT NULL,
  `upddate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `md5` varchar(50) DEFAULT NULL,
  `site_id` mediumint(9) DEFAULT 0,
  `path` varchar(127) DEFAULT NULL,
  `num_words` int(11) DEFAULT '1',
  `last_modified` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `filesize` int(11) DEFAULT 0,
  PRIMARY KEY (`spider_id`),
  KEY `site_id` (`site_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

    runDBQry($dbh, "CREATE TABLE `tempspider` (
  `file` text DEFAULT NULL,
  `id` mediumint(11) NULL AUTO_INCREMENT,
  `level` tinyint(6) DEFAULT 0,
  `path` text DEFAULT NULL,
  `site_id` mediumint(9) DEFAULT 0,
  `indexed` tinyint(1) DEFAULT 0,
  `upddate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `error` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");

    $sql_fl = '/custom/sql/db.php';
    if (file_exists(ROOT . $sql_fl)) {
        include_once "../../$sql_fl";
    }

    if (mysqli_error($dbh)) {
        throw new Exception("Error in SQL");
    }
    $dbh->commit();
    logout();
    ?>
    <script type="text/javascript">
        top.document.location.href = '/index.php';
    </script>
    <?php

} catch (Exception $ex) {
    $dbh->rollback();
}
$dbh->autocommit(TRUE);
?>
