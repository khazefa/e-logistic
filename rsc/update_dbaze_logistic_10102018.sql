-- --------------------------------------------------------
-- Host:                         localhost
-- Versi server:                 5.7.19 - MySQL Community Server (GPL)
-- OS Server:                    Win64
-- HeidiSQL Versi:               9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- membuang struktur untuk table dbaze_logistic.sfrepair
DROP TABLE IF EXISTS `sfrepair`;
CREATE TABLE IF NOT EXISTS `sfrepair` (
  `sfrepair_id` int(11) NOT NULL AUTO_INCREMENT,
  `sfrepair_num` varchar(50) NOT NULL,
  `sfrepair_date` date DEFAULT NULL,
  `sfrepair_purpose` varchar(50) NOT NULL,
  `sfrepair_notes` varchar(100) NOT NULL,
  `vendor_code` varchar(50) NOT NULL,
  `sfrepair_qty` int(5) NOT NULL,
  `user_key` varchar(50) NOT NULL,
  `fsl_code` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sfrepair_id`),
  UNIQUE KEY `Index 2` (`sfrepair_num`)
) ENGINE=MyISAM AUTO_INCREMENT=1262 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.
-- membuang struktur untuk table dbaze_logistic.sfrepair_detail
DROP TABLE IF EXISTS `sfrepair_detail`;
CREATE TABLE IF NOT EXISTS `sfrepair_detail` (
  `dt_sfrepair_id` int(11) NOT NULL AUTO_INCREMENT,
  `sfrepair_num` varchar(50) NOT NULL,
  `part_number` varchar(50) NOT NULL,
  `dt_sfrepair_qty` int(11) NOT NULL,
  `return_status` varchar(10) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `id_sfrepair` int(11) NOT NULL,
  PRIMARY KEY (`dt_sfrepair_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2441 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.
-- membuang struktur untuk table dbaze_logistic.sfrepair_tmp
DROP TABLE IF EXISTS `sfrepair_tmp`;
CREATE TABLE IF NOT EXISTS `sfrepair_tmp` (
  `tmp_sfrepair_id` int(11) NOT NULL AUTO_INCREMENT,
  `part_number` varchar(50) NOT NULL,
  `part_name` varchar(255) NOT NULL,
  `tmp_sfrepair_uniqid` varchar(100) NOT NULL,
  `tmp_sfrepair_qty` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `fsl_code` varchar(50) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tmp_sfrepair_id`)
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.
-- membuang struktur untuk table dbaze_logistic.sfvendor
DROP TABLE IF EXISTS `sfvendor`;
CREATE TABLE IF NOT EXISTS `sfvendor` (
  `sfvendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `sfvendor_num` varchar(50) NOT NULL,
  `sfvendor_po_num` varchar(50) DEFAULT NULL,
  `sfvendor_date` date DEFAULT NULL,
  `sfvendor_purpose` varchar(50) NOT NULL,
  `sfvendor_notes` varchar(100) NOT NULL,
  `vendor_code` varchar(50) NOT NULL,
  `sfvendor_qty` int(5) NOT NULL,
  `user_key` varchar(50) NOT NULL,
  `fsl_code` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sfvendor_id`),
  UNIQUE KEY `Index 2` (`sfvendor_num`)
) ENGINE=MyISAM AUTO_INCREMENT=1266 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.
-- membuang struktur untuk table dbaze_logistic.sfvendor_detail
DROP TABLE IF EXISTS `sfvendor_detail`;
CREATE TABLE IF NOT EXISTS `sfvendor_detail` (
  `dt_sfvendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `sfvendor_num` varchar(50) NOT NULL,
  `part_number` varchar(50) NOT NULL,
  `dt_sfvendor_qty` int(11) NOT NULL,
  `return_status` varchar(10) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `id_sfvendor` int(11) NOT NULL,
  PRIMARY KEY (`dt_sfvendor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2450 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.
-- membuang struktur untuk table dbaze_logistic.sfvendor_tmp
DROP TABLE IF EXISTS `sfvendor_tmp`;
CREATE TABLE IF NOT EXISTS `sfvendor_tmp` (
  `tmp_sfvendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `part_number` varchar(50) NOT NULL,
  `part_name` varchar(255) NOT NULL,
  `tmp_sfvendor_uniqid` varchar(100) NOT NULL,
  `tmp_sfvendor_qty` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `fsl_code` varchar(50) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tmp_sfvendor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=158 DEFAULT CHARSET=latin1;

-- membuang struktur untuk table dbaze_logistic.vendor_supply
DROP TABLE IF EXISTS `vendor_supply`;
CREATE TABLE IF NOT EXISTS `vendor_supply` (
  `vendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_code` varchar(30) NOT NULL,
  `vendor_name` varchar(100) NOT NULL,
  `vendor_address` text NOT NULL,
  `vendor_regional` varchar(50) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendor_id`),
  UNIQUE KEY `Index 2` (`vendor_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel dbaze_logistic.vendor_supply: ~2 rows (lebih kurang)
/*!40000 ALTER TABLE `vendor_supply` DISABLE KEYS */;
INSERT INTO `vendor_supply` (`vendor_id`, `vendor_code`, `vendor_name`, `vendor_address`, `vendor_regional`, `create_at`, `update_at`, `is_deleted`) VALUES
	(1, '12324', 'PT HIJAU DAUN', 'Jakarta', 'Indonesia', '2018-10-10 15:44:44', '2018-10-10 15:44:44', 0),
	(2, '12323', 'PT DAUN MUDA', 'Ruko Sebelah JL.Mangga No 200 Setiamakmur Bekasi, Jawa Barat', 'Indonesia', '2018-10-10 15:44:44', '2018-10-10 15:44:44', 0);
/*!40000 ALTER TABLE `vendor_supply` ENABLE KEYS */;

-- membuang struktur untuk view dbaze_logistic.viewdetailsfrepair
-- Menghapus tabel sementara dan menciptakan struktur VIEW terakhir
CREATE OR REPLACE VIEW `viewdetailsfrepair` AS SELECT od.sfrepair_num, od.part_number, p.part_name, od.dt_sfrepair_qty, od.return_status, od.created_at, od.is_deleted 
FROM sfrepair_detail AS od 
INNER JOIN sfrepair AS o ON od.sfrepair_num = o.sfrepair_num 
INNER JOIN parts AS p ON od.part_number = p.part_number ;

-- membuang struktur untuk view dbaze_logistic.viewdetailsfvendor
-- Menghapus tabel sementara dan menciptakan struktur VIEW terakhir
CREATE OR REPLACE VIEW `viewdetailsfvendor` AS SELECT od.sfvendor_num, od.part_number, p.part_name, od.dt_sfvendor_qty, od.return_status, od.created_at, od.is_deleted 
FROM sfvendor_detail AS od 
INNER JOIN sfvendor AS o ON od.sfvendor_num = o.sfvendor_num 
INNER JOIN parts AS p ON od.part_number = p.part_number  WITH CASCADED CHECK OPTION ;

-- membuang struktur untuk view dbaze_logistic.viewsfrepair
-- Menghapus tabel sementara dan menciptakan struktur VIEW terakhir
CREATE OR REPLACE VIEW `viewsfrepair` AS SELECT 
	`dn`.`sfrepair_num` AS `sfrepair_num`,
	`dn`.`sfrepair_date` AS `sfrepair_date`,
	`dn`.`sfrepair_notes` AS `sfrepair_notes`,
	`dn`.`sfrepair_purpose` AS `sfrepair_purpose`,
	`u`.`user_fullname` AS `user_fullname`,
	`dn`.`created_at` AS `created_at`,
	`dn`.`is_deleted` AS `is_deleted`
FROM 
	(`sfrepair` `dn`JOIN `users` `u` ON `u`.`user_key` = `dn`.`user_key`) WITH CASCADED CHECK OPTION ;

-- membuang struktur untuk view dbaze_logistic.viewsfvendor
-- Menghapus tabel sementara dan menciptakan struktur VIEW terakhir
CREATE OR REPLACE VIEW `viewsfvendor` AS SELECT 
	`dn`.`sfvendor_num` AS `sfvendor_num`,
	`dn`.`sfvendor_date` AS `sfvendor_date`,
	`dn`.`sfvendor_notes` AS `sfvendor_notes`,
	`dn`.`vendor_code` AS `vendor_code`,
	`vd`.`vendor_name` AS `vendor_name`,
	`vd`.`vendor_address` AS `vendor_address`,
	`dn`.`sfvendor_purpose` AS `sfvendor_purpose`,
	`dn`.`sfvendor_po_num` AS `sfvendor_po_num`,
	`u`.`user_fullname` AS `user_fullname`,
	`dn`.`created_at` AS `created_at`,
	`dn`.`is_deleted` AS `is_deleted`
FROM 
	(`sfvendor` `dn`JOIN `users` `u` ON `u`.`user_key` = `dn`.`user_key`)
	INNER JOIN vendor_supply vd ON vd.vendor_code = dn.vendor_code WITH CASCADED CHECK OPTION ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;