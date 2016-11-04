








INSERT INTO `items` (`ItemID`, `typ`, `ExoodID`, `ProdCode`, `ProdName`, `Description`, `picturefile`, `Classification`, `category`, `status`, `UnitPrice`, `WebPrice`, `itmtax`, `InUse`, `Notes`, `exood`, `exoodsales`, `InfoLoad`, `pixLoad`, `StockLoad`) VALUES
(1, 2, '0', 'RW001', 'ROAD WORTHNESS', '', 0, 16, NULL, NULL, '1000.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(2, 2, '0', 'RW002', 'ROAD WORTHNESS (Heavy Duty)', '', 0, 16, NULL, NULL, '5000.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(3, 2, '0', 'BGD001', 'Badge', '', 0, 17, NULL, NULL, '500.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(4, 2, '0', 'HGPERM001', 'HEAVY GOODS PERMIT', '', 0, 18, NULL, NULL, '3000.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(5, 2, '0', 'HCKNY001', 'HACKNEY PERMIT (Light)', '', 0, 18, NULL, NULL, '1000.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(6, 2, '0', 'HCKNY0012', 'HACKNEY PERMIT (Medium)', '', 0, 18, NULL, NULL, '2000.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(7, 2, '0', 'HCKNY0013', 'HACKNEY PERMIT (Heavy)', '', 0, 18, NULL, NULL, '3000.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(8, 2, '0', 'KSTR001', 'KSTR 001', '', 0, 18, NULL, NULL, '500.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(9, 2, '0', 'KSTR002', 'KSTR 002', '', 0, 18, NULL, NULL, '750.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(10, 2, '0', 'KSTR003', 'KSTR 003', '', 0, 18, NULL, NULL, '3000.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(11, 3, '0', 'TAXI001', 'Taxi', '', 0, 19, NULL, NULL, '5500.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(12, 2, '0', 'VHLIC001', 'Vehicle License (Very Light)', '', 0, 20, NULL, NULL, '2500.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(13, 2, '0', 'VHLIC002', 'Vehicle License (Light)', '', 0, 20, 0, NULL, '3125.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(14, 2, '0', 'VHLIC003', 'Vehicle License (Medium)', '', 0, 20, NULL, NULL, '3750.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(15, 2, '0', 'VHLIC004', 'Vehicle License (Heavy)', '', 0, 20, 0, NULL, '5000.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(16, 2, '0', 'VHLIC005', 'HACKNEY PERMIT (Very Heavy)', '', 0, 20, 0, NULL, '6250.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(17, 2, '0', 'STCK', 'SIDE STICKER', '', 0, 17, 0, NULL, '500.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(18, 2, '0', 'YLWFRM', 'YELLOW FORM', '', 0, 17, 0, NULL, '200.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(19, 2, '0', 'POC', 'P O C', '', 0, 17, 0, NULL, '300.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(20, 2, '0', 'BKLT', 'BOOKLET', '', 0, 17, 0, NULL, '1250.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(21, 2, '0', 'eVRC01', 'eVRC 01', '', 0, 20, 0, NULL, '1750.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(22, 2, '0', 'eVRC02', 'eVRC 02', '', 0, 20, 0, NULL, '3000.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(23, 2, '0', 'VHLIC0016', 'Vehicle License (extremely Light)', '', 0, 20, NULL, NULL, '1875.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(24, 2, '0', 'VHLIC00167', 'Vehicle License (Tiny)', '', 0, 20, NULL, NULL, '1250.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(25, 2, '0', 'REG001', 'Registration 001', '', 0, 20, 0, NULL, '3000.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(26, 2, '0', 'REG002', 'Registration 002', '', 0, 20, 0, NULL, '3125.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0),
(27, 2, '0', 'REG0023', 'Registration 003', '', 0, 20, 0, NULL, '6125.00', '0.00', '0.00', 1, '', 0, 0, 0, 0, 0);

--
-- Dumping data for table `items_pkgs`
--

INSERT INTO `items_pkgs` (`PackageID`, `StartDate`, `EndDate`, `Dscnt`, `Discount`, `TotTax`, `TotDisc`, `TotalValue`, `Grandvalue`, `wkday`, `LimitedTime`, `outlets`) VALUES
(1001, NULL, NULL, 0, '0.00', '0.00', '0.00', '5500.00', '5500.00', '', 0, '2,1');

--
-- Dumping data for table `items_pkgs_itms`
--

INSERT INTO `items_pkgs_itms` (`PackItemID`, `PackageID`, `ProductID`, `Quantity`, `Discount`, `Discnt`) VALUES
(1, 1001, 8, 1, '0.00', 0),
(2, 1001, 5, 1, '0.00', 0),
(3, 1001, 3, 1, '0.00', 0),
(4, 1001, 1, 1, '0.00', 0),
(5, 1001, 12, 1, '0.00', 0);

--
-- Dumping data for table `items_srv`
--
INSERT INTO `items_srv` (`ServiceID`, `department`, `outlets`, `useasset`, `assetcat`, `quantity`, `MachineTime`, `timetype`, `periods`, `repeated`, `starttime`, `endtime`, `eventdate`, `rec_type`, `event_length`, `alertperiod`) VALUES
(1, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'month_6___#no', 0, 0),
(2, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(3, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(4, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(5, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(6, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(7, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(8, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(9, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(10, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(11, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(12, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(13, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', '', 0, 0),
(14, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(15, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', '', 0, 0),
(16, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', '', 0, 0),
(17, 12, '2,1', 0, '', 0, '0.00', 0, 1, 0, NULL, NULL, '', '', 0, 0),
(18, 12, '', 0, '', 0, '0.00', 0, 1, 0, NULL, NULL, '', '', 0, 0),
(19, 12, '', 0, '', 0, '0.00', 0, 1, 0, NULL, NULL, '', '', 0, 0),
(20, 12, '', 0, '', 0, '0.00', 0, 1, 0, NULL, NULL, '', '', 0, 0),
(21, 12, '2,1', 0, '', 0, '0.00', 0, 1, 0, NULL, NULL, '', '', 0, 0),
(22, 12, '', 0, '', 0, '0.00', 0, 1, 0, NULL, NULL, '', '', 0, 0),
(23, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 1, 0),
(24, 12, '', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'week_1___1,2,3,4,5#', 1, 0),
(25, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 1, 0),
(26, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0),
(27, 12, '2,1', 0, '', 0, '0.00', 0, 1, 1, NULL, NULL, '', 'year_1___#no', 0, 0);
