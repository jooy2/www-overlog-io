/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- capstone_proj 데이터베이스 구조 내보내기
DROP DATABASE IF EXISTS `capstone_proj`;
CREATE DATABASE IF NOT EXISTS `capstone_proj` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `capstone_proj`;

-- 테이블 capstone_proj.data_log_os 구조 내보내기
DROP TABLE IF EXISTS `data_log_os`;
CREATE TABLE IF NOT EXISTS `data_log_os` (
  `d_id` int(11) NOT NULL AUTO_INCREMENT,
  `d_name_search` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `d_name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `d_desc` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `d_icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `d_mod_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`d_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 capstone_proj.data_log_task 구조 내보내기
DROP TABLE IF EXISTS `data_log_task`;
CREATE TABLE IF NOT EXISTS `data_log_task` (
  `d_id` int(11) NOT NULL AUTO_INCREMENT,
  `d_name` varchar(100) DEFAULT NULL,
  `d_desc` varchar(300) DEFAULT NULL,
  `d_icon` varchar(100) DEFAULT NULL,
  `d_mod_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`d_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 capstone_proj.data_log_type 구조 내보내기
DROP TABLE IF EXISTS `data_log_type`;
CREATE TABLE IF NOT EXISTS `data_log_type` (
  `d_id` int(11) NOT NULL AUTO_INCREMENT,
  `d_name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `d_avail` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'analytics',
  `d_desc` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `d_task` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `d_icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'file outline',
  `d_author` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'anonymous',
  `d_sample` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '예시 데이터 없음',
  `d_mod_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`d_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 capstone_proj.login_users 구조 내보내기
DROP TABLE IF EXISTS `login_users`;
CREATE TABLE IF NOT EXISTS `login_users` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_account` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_passwd` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_email` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `u_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `u_is_admin` tinyint(4) NOT NULL DEFAULT 0,
  `u_reg_date` datetime NOT NULL,
  `u_mod_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 capstone_proj.log_monitor 구조 내보내기
DROP TABLE IF EXISTS `log_monitor`;
CREATE TABLE IF NOT EXISTS `log_monitor` (
  `l_id` int(11) NOT NULL AUTO_INCREMENT,
  `l_no` int(11) NOT NULL DEFAULT 1 COMMENT '장치당 고유번호',
  `m_id` int(11) NOT NULL DEFAULT 0 COMMENT '타겟 모니터',
  `l_os_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `l_host_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `l_cpu_use` float DEFAULT 0,
  `l_cpu_sys` float DEFAULT 0,
  `l_cpu_top` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `l_mem_top` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `l_disk_use` int(11) DEFAULT 0,
  `l_disk_total` int(11) DEFAULT 0,
  `l_mem_use` int(11) DEFAULT 0,
  `l_mem_total` int(11) DEFAULT 0,
  `l_network_rx_byte` bigint(20) DEFAULT NULL,
  `l_network_tx_byte` bigint(20) DEFAULT NULL,
  `l_network_rx_packet` bigint(20) DEFAULT NULL,
  `l_network_tx_packet` bigint(20) DEFAULT NULL,
  `l_note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `l_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`l_id`),
  KEY `FK__user_monitor` (`m_id`),
  CONSTRAINT `FK__user_monitor` FOREIGN KEY (`m_id`) REFERENCES `user_monitor` (`m_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 capstone_proj.user_monitor 구조 내보내기
DROP TABLE IF EXISTS `user_monitor`;
CREATE TABLE IF NOT EXISTS `user_monitor` (
  `m_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` int(11) NOT NULL DEFAULT 1,
  `u_share_id` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/1/',
  `m_is_active` tinyint(2) NOT NULL DEFAULT 1,
  `m_is_obsolete` tinyint(2) NOT NULL DEFAULT 0,
  `m_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unnamed',
  `m_desc` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Auto-Generated',
  `m_os_type` tinyint(4) NOT NULL DEFAULT 1,
  `m_data_type` smallint(6) NOT NULL DEFAULT 1,
  `m_dashboard_type` tinyint(4) NOT NULL DEFAULT 0,
  `m_icon` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'server',
  `m_host_ip` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_host_domain` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_token` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_reg_date` datetime DEFAULT NULL,
  `m_mod_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`m_id`),
  KEY `FK__login_users` (`u_id`),
  CONSTRAINT `FK__login_users` FOREIGN KEY (`u_id`) REFERENCES `login_users` (`u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
