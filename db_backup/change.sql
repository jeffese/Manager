ALTER TABLE `bills` 
DROP FOREIGN KEY `bills_ibfk_3`,
DROP FOREIGN KEY `bills_ibfk_4`,
DROP `AccountID`,
ADD `Payed` DECIMAL(19,2) DEFAULT '0.00' AFTER `RecAccountBalance`,
ADD `payable` TINYINT(1) NOT NULL DEFAULT '0' AFTER `Payed`,
ADD `entrytype` TINYINT(1) NOT NULL DEFAULT '2' AFTER `payable`,
ADD `Dept` INT(11) NOT NULL AFTER `BillID`,
ADD `OutetID` INT(11) NOT NULL AFTER `Dept`,
ADD INDEX (`payable`),
ADD INDEX (`entrytype`),
ADD INDEX (`Dept`),
ADD INDEX (`OutetID`);

ALTER TABLE `bills` 
ADD CONSTRAINT `bills_ibfk_3` FOREIGN KEY (`BillType`) REFERENCES `classifications` (`catID`),
ADD CONSTRAINT `bills_ibfk_4` FOREIGN KEY (`Dept`) REFERENCES `classifications`(`catID`), 
ADD CONSTRAINT `bills_ibfk_5` FOREIGN KEY (`OutetID`) REFERENCES `outlets`(`OutletID`);

ALTER TABLE `expenses` 
ADD `Dept` INT(11) NOT NULL AFTER `ExpenseID`,
ADD `OutetID` INT(11) NOT NULL AFTER `Dept`,
ADD `bills` VARCHAR(1000) NOT NULL DEFAULT '', 
ADD `payments` VARCHAR(1000) NOT NULL DEFAULT '',
ADD INDEX (`Dept`),
ADD INDEX (`OutetID`);

ALTER TABLE `expenses` 
ADD CONSTRAINT `expenses_ibfk_8` FOREIGN KEY (`Dept`) REFERENCES `classifications`(`catID`), 
ADD CONSTRAINT `expenses_ibfk_9` FOREIGN KEY (`OutetID`) REFERENCES `outlets`(`OutletID`);
