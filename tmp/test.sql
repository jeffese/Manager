SELECT `VendorID`, `AssetID`, `Classification` 
FROM `exood_coy1`.`items_srv_sched` 
INNER JOIN `exood_coy1`.`invoicedetails` ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID` 
INNER JOIN `exood_coy1`.`items` ON `invoicedetails`.ProductID=`items`.ItemID 
INNER JOIN `exood_coy1`.`invoices` ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID` 
WHERE `SrvSchedID`=75

-- SELECT `ItemID`, `ProdName`, `UnitPrice`, ShopStock, unitsinpack, `serials`, `serialized`, `itmtax`, 1000000 
FROM `exood_coy1`.`items` 
-- INNER JOIN `exood_coy1`.`items_prod` ON `items`.`ItemID`=`items_prod`.`ProductID` 
INNER JOIN `exood_coy1`.`outlet` ON `items_prod`.ProductID=outlet.ProductID 
WHERE `items`.`ItemID`=73 AND OutletID=6 
http://localprinter?f=16458&p=1&u=admin
