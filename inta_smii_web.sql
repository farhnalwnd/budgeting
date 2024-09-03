-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table intra_smii_web.activity_log
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.activity_log: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.departments
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.departments: ~9 rows (approximately)
INSERT INTO `departments` (`id`, `department_name`, `department_slug`, `created_at`, `updated_at`) VALUES
	(1, 'Engineering & Maintainance', 'engineering-maintainance', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(2, 'Finance Admin', 'finance-admin', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(3, 'HCD', 'hcd', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(4, 'Manufacturing', 'manufacturing', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(5, 'QM & HSE', 'qm-hse', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(6, 'R&D', 'rd', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(7, 'Sales & Marketing', 'sales-marketing', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(8, 'Supply Chain', 'supply-chain', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(9, 'Secret', 'secret', '2024-09-02 08:50:47', '2024-09-02 08:50:47');

-- Dumping structure for table intra_smii_web.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.inventories
CREATE TABLE IF NOT EXISTS `inventories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ld_part` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pt_desc1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ld_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ld_qty_oh` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pt_um` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ld_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ld_loc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ld_lot` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aging_days` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ld_expire` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventories_ld_part_unique` (`ld_part`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.inventories: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.jobs: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.levels
CREATE TABLE IF NOT EXISTS `levels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `level_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.levels: ~8 rows (approximately)
INSERT INTO `levels` (`id`, `level_name`, `level_slug`, `created_at`, `updated_at`) VALUES
	(1, 'I', 'i', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(2, 'II', 'ii', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(3, 'III', 'iii', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(4, 'IV', 'iv', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(5, 'V', 'v', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(6, 'VI', 'vi', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(7, 'VII', 'vii', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(8, 'Developer', 'developer', '2024-09-02 08:50:47', '2024-09-02 08:50:47');

-- Dumping structure for table intra_smii_web.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.migrations: ~23 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
	(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
	(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
	(6, '2016_06_01_000004_create_oauth_clients_table', 1),
	(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
	(8, '2019_08_19_000000_create_failed_jobs_table', 1),
	(9, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(10, '2024_05_07_032023_create_departments_table', 1),
	(11, '2024_05_07_032032_create_levels_table', 1),
	(12, '2024_05_07_032039_create_positions_table', 1),
	(13, '2024_05_07_032256_add_position_id_in_user_table', 1),
	(14, '2024_05_07_033400_create_permission_tables', 1),
	(15, '2024_07_09_083357_add_department_id_to_table_user', 1),
	(16, '2024_08_01_150007_create_jobs_table', 1),
	(17, '2024_08_07_082454_create_activity_log_table', 1),
	(18, '2024_08_07_082455_add_event_column_to_activity_log_table', 1),
	(19, '2024_08_07_082456_add_batch_uuid_column_to_activity_log_table', 1),
	(20, '2024_08_20_144538_create_inventories_table', 1),
	(21, '2024_08_29_101818_create_productions_table', 1),
	(22, '2024_08_29_101825_create_sales_table', 1),
	(23, '2024_09_02_160532_create_notifications_table', 2);

-- Dumping structure for table intra_smii_web.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.model_has_roles: ~3 rows (approximately)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2),
	(3, 'App\\Models\\User', 3);

-- Dumping structure for table intra_smii_web.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` int NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.notifications: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.oauth_access_tokens
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.oauth_access_tokens: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.oauth_auth_codes
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.oauth_auth_codes: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.oauth_clients
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.oauth_clients: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.oauth_personal_access_clients
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.oauth_personal_access_clients: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.oauth_refresh_tokens
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.oauth_refresh_tokens: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.permissions: ~51 rows (approximately)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'view role', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(2, 'create role', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(3, 'update role', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(4, 'delete role', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(5, 'view permission', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(6, 'create permission', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(7, 'update permission', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(8, 'delete permission', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(9, 'view user', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(10, 'create user', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(11, 'update user', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(12, 'delete user', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(13, 'view product', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(14, 'create product', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(15, 'update product', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(16, 'delete product', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(17, 'view finance', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(18, 'create finance', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(19, 'update finance', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(20, 'delete finance', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(21, 'view department', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(22, 'create department', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(23, 'update department', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(24, 'delete department', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(25, 'view position', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(26, 'create position', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(27, 'update position', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(28, 'delete position', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(29, 'view level', 'web', '2024-09-02 08:50:46', '2024-09-02 08:50:46'),
	(30, 'create level', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(31, 'update level', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(32, 'delete level', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(33, 'view dashboard Finance', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(34, 'view dashboard Sales & Marketing', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(35, 'view dashboard Supply Chain', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(36, 'view dashboard HCD', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(37, 'view dashboard Engineering & Maintainance', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(38, 'view dashboard Manufacturing', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(39, 'view dashboard QM & HSE', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(40, 'view dashboard R&D', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(41, 'view requisition', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(42, 'get data master', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(43, 'view browse requisition', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(44, 'create maintenance requisition', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(45, 'update maintenance requisition', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(46, 'delete maintenance requisition', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(47, 'print maintenance requisition', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(48, 'view production dashboard', 'web', '2024-09-03 13:13:48', '2024-09-03 13:13:49'),
	(49, 'view sales dashboard', 'web', '2024-09-03 13:18:13', '2024-09-03 13:18:14'),
	(50, 'view data dashboard', 'web', '2024-09-03 13:18:39', '2024-09-03 13:18:40'),
	(51, 'view inventory dashboard', 'web', '2024-09-03 13:24:49', '2024-09-03 13:24:50');

-- Dumping structure for table intra_smii_web.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.positions
CREATE TABLE IF NOT EXISTS `positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `position_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  `level_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `positions_department_id_foreign` (`department_id`),
  KEY `positions_level_id_foreign` (`level_id`),
  CONSTRAINT `positions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `positions_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.positions: ~14 rows (approximately)
INSERT INTO `positions` (`id`, `position_name`, `position_slug`, `department_id`, `level_id`, `created_at`, `updated_at`) VALUES
	(1, 'Developer', 'developer', 9, 8, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(2, 'General Manager', 'general-manager', 1, 7, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(3, 'Department Head Finance', 'department-head-finance', 2, 6, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(4, 'Assistant Manager MIS', 'assistant-manager-mis', 2, 5, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(5, 'Manager Accounting & Tax', 'manager-accounting-tax', 2, 5, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(6, 'Manager Bussiness Opr. Control', 'manager-bussiness-opr-control', 2, 5, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(7, 'IT Support', 'it-support', 2, 3, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(8, 'Web Developer', 'web-developer', 2, 4, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(9, 'Supervisor - MIS ', 'supervisor-mis', 2, 4, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(10, 'Department Head Supply Chain', 'department-head-supply-chain', 8, 6, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(11, 'Manager - Logistic', 'manager-logistic', 8, 6, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(12, 'Manager - PPIC', 'manager-ppic', 8, 6, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(13, 'Manager - Purchasing', 'manager-purchasing', 8, 6, '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(14, 'Supervisor - Export', 'supervisor-export', 8, 5, '2024-09-02 08:50:47', '2024-09-02 08:50:47');

-- Dumping structure for table intra_smii_web.productions
CREATE TABLE IF NOT EXISTS `productions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tr_trnbr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tr_nbr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tr_effdate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tr_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tr_prod_line` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tr_part` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_desc1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tr_qty_loc` int DEFAULT NULL,
  `Weight_in_KG` decimal(10,2) DEFAULT NULL,
  `Line` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_draw` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.productions: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.roles: ~4 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'super-admin', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(2, 'admin', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(3, 'staff', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47'),
	(4, 'user', 'web', '2024-09-02 08:50:47', '2024-09-02 08:50:47');

-- Dumping structure for table intra_smii_web.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.role_has_permissions: ~65 rows (approximately)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(17, 1),
	(18, 1),
	(19, 1),
	(20, 1),
	(21, 1),
	(22, 1),
	(23, 1),
	(24, 1),
	(25, 1),
	(26, 1),
	(27, 1),
	(28, 1),
	(29, 1),
	(30, 1),
	(31, 1),
	(32, 1),
	(33, 1),
	(34, 1),
	(35, 1),
	(36, 1),
	(37, 1),
	(38, 1),
	(39, 1),
	(40, 1),
	(41, 1),
	(42, 1),
	(43, 1),
	(44, 1),
	(45, 1),
	(46, 1),
	(47, 1),
	(48, 1),
	(49, 1),
	(50, 1),
	(51, 1),
	(1, 2),
	(2, 2),
	(3, 2),
	(5, 2),
	(6, 2),
	(9, 2),
	(10, 2),
	(11, 2),
	(13, 2),
	(14, 2),
	(15, 2),
	(33, 3),
	(34, 4),
	(43, 4);

-- Dumping structure for table intra_smii_web.sales
CREATE TABLE IF NOT EXISTS `sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tr_trnbr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tr_addr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tr_effdate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tr_ton` decimal(8,3) DEFAULT NULL,
  `cm_region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cm_rmks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_cmmt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `margin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_desc1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_prod_line` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pl_desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ad_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tr_slspsn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_part` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_draw` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.sales: ~0 rows (approximately)

-- Dumping structure for table intra_smii_web.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','non active') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `position_id` bigint unsigned DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_position_id_foreign` (`position_id`),
  KEY `users_department_id_foreign` (`department_id`),
  CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `users_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table intra_smii_web.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `nik`, `username`, `name`, `email`, `email_verified_at`, `password`, `avatar`, `status`, `remember_token`, `created_at`, `updated_at`, `position_id`, `department_id`) VALUES
	(1, 'AG1111', 'super', 'Super Admin', 'superadmin@gmail.com', '2024-09-02 08:50:48', '$2y$12$dEPgm27ZaNDoLhBEWB2mLOcDMoOKpy4SNtC8JMowPmi.gyJzDX4Ci', NULL, 'active', NULL, '2024-09-02 08:50:48', '2024-09-02 08:50:48', 1, 1),
	(2, 'AG2222', 'admin', 'Admin', 'admin@gmail.com', '2024-09-02 08:50:48', '$2y$12$MDVplWlTbQp.tW5NbXPVxOe0B3BLVAzBABsYG87iJQv52AfHCz0LG', NULL, 'active', NULL, '2024-09-02 08:50:48', '2024-09-02 08:50:48', 3, 1),
	(3, 'AG3333', 'staff', 'Staff', 'staff@gmail.com', '2024-09-02 08:50:49', '$2y$12$ZkvgHklzBaQZ0ZorhavjNuXXHKWVYhagHRSvpy7pxOW4QwCDEu3Fq', NULL, 'active', NULL, '2024-09-02 08:50:49', '2024-09-02 08:50:49', 3, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
