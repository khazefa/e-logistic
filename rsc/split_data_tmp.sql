-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 06, 2018 at 04:16 AM
-- Server version: 5.7.19
-- PHP Version: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbaze_logistic`
--

-- --------------------------------------------------------

--
-- Table structure for table `incomings_tmp`
--

CREATE TABLE `incomings_tmp` (
  `tmp_incoming_id` int(11) NOT NULL,
  `part_number` varchar(50) NOT NULL,
  `part_name` varchar(255) NOT NULL,
  `serial_number` varchar(50) NOT NULL,
  `tmp_incoming_uniqid` varchar(100) NOT NULL,
  `tmp_incoming_qty` int(11) NOT NULL,
  `return_status` varchar(10) NOT NULL,
  `user` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `incomings_tmp`
--

INSERT INTO `incomings_tmp` (`tmp_incoming_id`, `part_number`, `part_name`, `serial_number`, `tmp_incoming_uniqid`, `tmp_incoming_qty`, `return_status`, `user`, `fullname`, `is_deleted`) VALUES
(1620, '01803530280', '', 's2x37219', '5948c17dc6805bf823bbc148e6a67abfe9a05c1dinr', 1, 'RG', 'cid7_user1', 'FSL Admin CID7', 0),
(1633, '01750106679', '', '6478970869', '3d5fd2313f9cd1b8e4caed5e8e8c98f91ae2ca67inr', 2, 'RG', 'cid7_user2', 'FSL Admin CID7', 0),
(1612, '01803530280', '', 's2x37219', 'e0056b87687979c166c5ac4df4ea4405b06de257inr', 1, 'RG', 'cid7_user1', 'FSL Admin CID7', 0),
(1601, '01803530280', '', 's2x37219', 'f20f682ec212fe7f2b81cffd8880a665301307bcinr', 1, 'RG', 'cid7_user1', 'FSL Admin CID7', 0),
(1600, '01803530280', '', 's2x37219', 'c38acfd9135bee694331f63cc733e30431aefc91inr', 1, 'RG', 'cid7_user2', 'FSL Admin CID7', 0),
(1599, '01803530280', '', 's2x37219', '95de43cfb91223c2d3ddcb4a0a8bb476519350a9inr', 2, 'RG', 'cid7_user1', 'FSL Admin CID7', 0),
(1598, '01803530280', '', 's2x37219', '9403e8122bb7cc1d403a0bca4efe967c406da9cainr', 3, 'RG', 'cid7_user1', 'FSL Admin CID7', 0),
(1585, '01750189334', '', '', '1513806043a77df001c0480585f24a51f6b0b8afin', 1, 'RG', 'cid8_user1', 'FSL Admin CID8', 0),
(1632, '01803530409', '', 'NOSN', '3d5fd2313f9cd1b8e4caed5e8e8c98f91ae2ca67inr', 2, 'RG', 'cid7_user2', 'FSL Admin CID7', 0),
(1631, '01803530280', '', 's2x37219', '9ec565f32af4378704d2b3f6d8e08d0c28110732inr', 1, 'RG', 'cid7_user1', 'FSL Admin CID7', 0),
(1785, '01750109641', '', '7228009353', '8e8d8eb83203512ce023a5f59843c62ce1959227inr', 2, 'RG', 'cidj_user1', 'FSL Admin CIDJ 1', 0),
(1784, '01750105679', '', '5404905115', '8e8d8eb83203512ce023a5f59843c62ce1959227inr', 3, 'RG', 'cidj_user1', 'FSL Admin CIDJ 1', 0),
(1687, '01803530280', '', '6VCMZPT5', '05e07e6c4ccddbcad9e39a48b95d3484bc3e7c9finr', 2, 'RG', 'cid9_user1', 'FSL Admin CID9', 0),
(1688, '01803530280', '', '6VCMZPT5', '7223093f2b63af9a996f8273ebe8a3fd21f2123finr', 1, 'RG', 'cid9_user1', 'FSL Admin CID9', 0),
(1689, '01750106679', '', '6478740139', '7640b576f0d5238e118ea49a7370a43ece4311bfinr', 1, 'RG', 'cid9_user1', 'FSL Admin CID9', 0),
(1781, '01750106679', '', '6478970869', '6dfdfe57f0ef58811837fccc828923ff1b1d13ccinr', 1, 'RG', 'cid7_user1', 'FSL Admin CID7', 0),
(1780, '01803530409', '', 'NOSN', '6dfdfe57f0ef58811837fccc828923ff1b1d13ccinr', 1, 'RG', 'cid7_user1', 'FSL Admin CID7', 0),
(1809, '01750073167', '', '5404C17832', '8146823552daa1f207efab10cf95488d1aac0f8ainr', 1, 'RG', 'cid3_user1', 'FSL Admin CID3', 0),
(1804, '01750109659', '', '5129038007', '7b599c5ec2d3ada01f7197ba5acc5a57f803e62binr', 1, 'RG', 'cid3_user1', 'FSL Admin CID3', 0),
(1806, '01750073167', '', '5404C17832', 'ce9bdc8fd43279c12c5673c6dcd233d3f321ba36inr', 1, 'RG', 'cid3_user1', 'FSL Admin CID3', 0),
(2151, '01750159334', '', '', '25a6d5922e755630fb43e2dab0687de7663d145bin', 1, 'RG', 'cidj_user1', 'FSL Admin CIDJ 1', 0),
(2167, '01803530412', '', '806HRQB019452', 'dbf931f000c48af2d1c88a3a27b9329cdf549dd6inrOT18081907', 1, 'RG', 'cidj_user1', 'FSL Admin CIDJ 1', 0),
(2153, '01750216797', '', '9L150F1512171235', 'dbf931f000c48af2d1c88a3a27b9329cdf549dd6inrOT18081844', 1, 'RG', 'cidj_user1', 'FSL Admin CIDJ 1', 0),
(2288, '01750109641', 'Double extractor unit MDMS CMD-V4', 'A206154629', 'e61aa57861f797e3b438e7b98552a90555d4b44einrOT18082025', 1, 'RG', 'cidj_user1', 'FSL Admin CIDJ 1', 0),
(2289, '01750109641', 'Double extractor unit MDMS CMD-V4', 'A206140060', 'c3f736b12b75103b010f048aedb476cf9360f8d5inrOT18081997', 1, 'RG', 'ciet_user1', 'FSL Admin Sukabumi', 0),
(2290, '01750105679', 'CMD Controller II USB assd. with cover', '540445117', 'c3f736b12b75103b010f048aedb476cf9360f8d5inrOT18081997', 1, 'RG', 'ciet_user1', 'FSL Admin Sukabumi', 0),
(2294, '01750105679', 'CMD Controller II USB assd. with cover', '540445117', 'ab3e1dbf51cee170b1d9b7af57d247d782123454inrOT18081997', 1, 'RG', 'ciet_user2', 'FSL Admin Sukabumi', 0),
(2295, '01750109641', 'Double extractor unit MDMS CMD-V4', 'A206140060', 'ab3e1dbf51cee170b1d9b7af57d247d782123454inrOT18081997', 1, 'RG', 'ciet_user2', 'FSL Admin Sukabumi', 0),
(2411, '01750105679', 'CMD Controller II USB assd. with cover', '540445117', '2a75421c2518ee424535f981ef3eb17a9dfb7a0cinrOT18081997', 1, 'RG', 'ciet_user1', 'FSL Admin Sukabumi', 0),
(2412, '01750109641', 'Double extractor unit MDMS CMD-V4', 'A206140060', '2a75421c2518ee424535f981ef3eb17a9dfb7a0cinrOT18081997', 1, 'RG', 'ciet_user1', 'FSL Admin Sukabumi', 0),
(2850, '01750057875', 'Transport CMD-V4 horizontal FL 101mm', '94302001469', '9e844ad631263e1fa5e25ec1301a2c0e13daaf00inrOT18081854', 1, 'RG', 'cid9_user1', 'FSL Admin CID9', 0),
(2407, '01803530500', 'Receipt Printer TP28 (01750267132)', '97GG650448', '950a4e472ce49126a3614a091c1e8951a600b383inrOT18082122', 1, 'RG', 'cidj_user1', 'FSL Admin CIDJ 1', 0),
(2373, '01750109641', 'Double extractor unit MDMS CMD-V4', 'A206140060', '4d5181eefb4d5c6a2251281eebf2d1bc461f397dinrOT18081997', 1, 'RG', 'ciet_user1', 'FSL Admin Sukabumi', 0),
(2374, '01750105679', 'CMD Controller II USB assd. with cover', '540445117', '4d5181eefb4d5c6a2251281eebf2d1bc461f397dinrOT18081997', 1, 'RG', 'ciet_user1', 'FSL Admin Sukabumi', 0),
(2642, '01750082602', 'shutter CMD-V4 horizontal FL assembled', '', 'fbcde75cc1d0eeb3b82492bd04c6ba0348baf03ein', 1, 'S', 'ciet_user1', 'FSL Admin Sukabumi', 0),
(2489, '01750105679', 'CMD Controller II USB assd. with cover', '6107340432', '94c0e7bef4f8def2ef5fef2acabedfea27a22b5finrOT18082142', 1, 'RG', 'ciet_user1', 'FSL Admin Sukabumi', 0),
(2530, '01750159341', 'Keyboard V6 EPP INT CES', '102699509', 'fc7604a758c85de39c6f7eb6629d3190e8eedfb4inrOT18082184', 1, 'RG', 'cidj_user1', 'FSL Admin CIDJ 1', 0),
(2954, '01750121671', 'DVI-ADD2-PCIE-X16_SHIELD AB', 'NO SN', '95b896e086dcc4c1e1601628ef6eaa567b24f25finrOT18082541', 2, 'RG', 'cid3_user2', 'FSL Admin CID3', 0),
(3436, '01750220136', 'SHUTTER-LITE DC-MOTOR ASSY PC28X', '8200039239', 'c70c3e9238cdc19269f6d74aa0dbe89b8b502205inrOT18083020', 1, 'RG', 'cid3_user1', 'FSL Admin CID3', 0),
(3675, '01750109659', 'CMD-V4 STACKER MODUL W. SR VERT.', '', '06604d1ae787ed9971e1ca3fa753b59871abdbcbin', 4, 'S', 'ciec_user1', 'FSL Admin CIEC', 0),
(4024, '49225262000A', 'PICKER,AFD 1.5,ASSEMBLY', 'L205054698', '40df8d0940537b9ec0d3c37dfb1655dba68b0e2dinrOT18083248', 1, 'RG', 'cidj_user1', 'FSL Admin CIDJ 1', 0),
(3643, '01750109659', 'CMD-V4 STACKER MODUL W. SR VERT.', '', '8749136a3e5ca178e0fc8c0427274ecb795cd070in', 3, 'S', 'cids_user1', 'FSL Admin CIDS', 0),
(3865, '175006578', '', '', 'b907ab46d2bbe6b1526583ae156aa222ff4bdeeain', 1, 'S', 'cidh_user1', 'FSL Admin CIDH', 0),
(3866, '1750006754', '', '', 'b907ab46d2bbe6b1526583ae156aa222ff4bdeeain', 1, 'S', 'cidh_user1', 'FSL Admin CIDH', 0),
(4185, '01750109659', 'CMD-V4 STACKER MODUL W. SR VERT.', '', 'd79c6f180344854898e85030ec403107bc35c91din', 3, 'S', 'cidk_user1', 'FSL Admin CIDK', 0),
(4186, '7310000726', 'CABLE FLEXIBLE CARRIAGE HYOSUNG (7310000726)', '', 'd79c6f180344854898e85030ec403107bc35c91din', 1, 'S', 'cidk_user1', 'FSL Admin CIDK', 0),
(4347, '01750173205', 'CARD READER CHD V2CU STANDARD', '12010917', '654addc7103e122d5dbd8d7739f4f41bcb975f1finrOT18083897', 1, 'RG', 'cidt_user2', 'FSL Admin CIDT', 0),
(4285, '01750193080', 'KEYBOARD J6 EPP INT', '1A00026461', 'cc9133746fd2e25751cfd4e148cbb867e54142aeinrOT18083824', 1, 'RG', 'cido_user1', 'FSL Admin CIDO', 0),
(4558, '01803530412', 'DVD-RW/CD-RW SLIM - EXTERNAL', '803HRWN016849', '654addc7103e122d5dbd8d7739f4f41bcb975f1finrOT18083899', 1, 'RG', 'cidt_user2', 'FSL Admin CIDT', 0),
(4975, '01803530500', 'RECEIPT PRINTER TP28 (01750267132)', '97GG509830', '4f34d70a88c28f45768d1df9ba2e205340f8a54finrOT18094479', 1, 'RG', 'cidb_user2', 'FSL Admin CIDB', 0),
(5508, '01750192235', 'BASE UNIT ASKIM II D', '5404078143', 'b1a869ec304c4aaaf94cc89e60a52c9fe07d761finrOT18095013', 1, 'RG', 'cidh_user1', 'FSL Admin CIDH', 0),
(5500, '01750272110', '15&#34; OPENFRAME PROCASH VGA, IVO/YLT', '111111', '9f04d4d48794d340986b7f13a457619b77c41869inrOT18095086', 1, 'RG', 'ciec_user2', 'FSL Admin CIEC', 0),
(5092, '01750189334', 'TP13 RECEIPT PRINTER BKT080II', '', '654addc7103e122d5dbd8d7739f4f41bcb975f1fin', 1, 'S', 'cidt_user2', 'FSL Admin CIDT', 0),
(5086, '01750105679', 'CMD CONTROLLER II USB ASSD. WITH COVER', '2222', '861e80f93610285c5fc2fcc2f30d7ef5ae870426inrOT18094644', 1, 'RG', 'cidu_user2', 'FSL Admin CIDU', 0),
(5084, '01750216797', 'LCD TFT XGA, 15&#34; OPEN-FRAME', '1111', '861e80f93610285c5fc2fcc2f30d7ef5ae870426inrOT18094644', 1, 'RG', 'cidu_user2', 'FSL Admin CIDU', 0),
(5076, '01750109641M', 'DOUBLE EXTRACTOR UNIT MDMS CMD V4', 'A206144942', '654addc7103e122d5dbd8d7739f4f41bcb975f1finrOT18084021', 1, 'RG', 'cidt_user2', 'FSL Admin CIDT', 0),
(5558, '01750105679', 'CMD CONTROLLER II USB ASSD. WITH COVER', '5404365394', 'c46721c468d8047291f6b0911031b6ee10bc8243inrOT18095107', 1, 'RG', 'cid9_user1', 'FSL Admin CID9', 0),
(5325, '01750256248', 'TP28 (P3+M1+H2) 80MM RECEIPT PRINTER', '', '61c623688e1bca535a06538803472e02b4d9625fin', 1, 'S', 'ciei_user1', 'FSL Admin CIEI', 0),
(5271, '01750107936', '15&#34; LCD OPEN FRAME MONITOR', 'DTCG1642922', '654addc7103e122d5dbd8d7739f4f41bcb975f1finrOT18094656', 1, 'RG', 'cidt_user2', 'FSL Admin CIDT', 0),
(5321, '01750110039', 'RECEIPT PRINTER TP07', '', '61c623688e1bca535a06538803472e02b4d9625fin', 1, 'S', 'ciei_user1', 'FSL Admin CIEI', 0),
(5520, '49204018000A', 'FEEDSHAFT,OVRMLD', '', 'b1a869ec304c4aaaf94cc89e60a52c9fe07d761fin', 8, 'S', 'cidh_user1', 'FSL Admin CIDH', 0),
(5488, '01750105679', 'CMD CONTROLLER II USB ASSD. WITH COVER', '5404353177', '7e23a30870498462d733e15ba4c70c1559556ec0inrOT18095022', 1, 'RG', 'cid3_user1', 'FSL Admin CID3', 0),
(5632, '01750044878', 'DISTRIBUTER BOARD 4X WITH COVER', '681224', 'd19457ff9cb7c16a93af65bae4a706fdc5d191efinrOT1809575210', 1, 'RG', 'cidn_user1', 'FSL Admin CIDN', 0);

-- --------------------------------------------------------

--
-- Table structure for table `outgoings_tmp`
--

CREATE TABLE `outgoings_tmp` (
  `tmp_outgoing_id` int(11) NOT NULL,
  `part_number` varchar(50) NOT NULL,
  `part_name` varchar(255) NOT NULL,
  `serial_number` varchar(50) NOT NULL,
  `tmp_outgoing_uniqid` varchar(100) NOT NULL,
  `tmp_outgoing_qty` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `fsl_code` varchar(50) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `outgoings_tmp`
--

INSERT INTO `outgoings_tmp` (`tmp_outgoing_id`, `part_number`, `part_name`, `serial_number`, `tmp_outgoing_uniqid`, `tmp_outgoing_qty`, `user`, `fullname`, `fsl_code`, `is_deleted`) VALUES
(4102, '01750190038', 'SOFTKEY FRAME 15 INCH DDC-NDC BR PC28X', '113033', '1d6c84b446d871c510f86ddd281c98ec84684286ot', 1, 'cidj_user1', 'FSL Admin CIDJ 1', 'CIDJ', 0),
(4806, '01750109333', 'CASH TRAY CAMERA MAKU NTSC, SPARE PART', '191665', '65769cefe5f9efb18efdf3418ca58bff57a87b43ot', 1, 'cidj_user1', 'FSL Admin CIDJ 1', 'CIDJ', 0),
(4795, '01750220136', 'SHUTTER-LITE DC-MOTOR ASSY PC28X', '12345', '83cab01bebfc48e68d61736f077fea1285064a7eot', 1, 'ciec_user1', 'FSL Admin CIEC', 'CIEC', 0),
(4417, '01750109641', 'DOUBLE EXTRACTOR UNIT MDMS CMD-V4', 'NOSN', '218f5fb477eeb974288bf13e7b95b4acf3ead748ot', 1, 'cids_user1', 'FSL Admin CIDS', 'CIDS', 0),
(4805, '01750050429', 'PORTRAIT CAMERA MAKU BLACK/WHITE NTSC', '260220', '65769cefe5f9efb18efdf3418ca58bff57a87b43ot', 1, 'cidj_user1', 'FSL Admin CIDJ 1', 'CIDJ', 0),
(6930, '01750105753', 'KEYBOARD V5 EPP MYS CES', '1234455', '61c623688e1bca535a06538803472e02b4d9625fot', 1, 'ciei_user1', 'FSL Admin CIEI', 'CIEI', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `incomings_tmp`
--
ALTER TABLE `incomings_tmp`
  ADD PRIMARY KEY (`tmp_incoming_id`);

--
-- Indexes for table `outgoings_tmp`
--
ALTER TABLE `outgoings_tmp`
  ADD PRIMARY KEY (`tmp_outgoing_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `incomings_tmp`
--
ALTER TABLE `incomings_tmp`
  MODIFY `tmp_incoming_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5633;

--
-- AUTO_INCREMENT for table `outgoings_tmp`
--
ALTER TABLE `outgoings_tmp`
  MODIFY `tmp_outgoing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7324;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
