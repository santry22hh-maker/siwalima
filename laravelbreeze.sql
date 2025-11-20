-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for laravelbreeze
CREATE DATABASE IF NOT EXISTS `laravelbreeze` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `laravelbreeze`;

-- Dumping structure for table laravelbreeze.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.cache: ~3 rows (approximately)
REPLACE INTO `cache` (`key`, `value`, `expiration`) VALUES
	('laravel-cache-b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f', 'i:1;', 1763361411),
	('laravel-cache-b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f:timer', 'i:1763361411;', 1763361411),
	('laravel-cache-spatie.permission.cache', 'a:3:{s:5:"alias";a:4:{s:1:"a";s:2:"id";s:1:"b";s:4:"name";s:1:"c";s:10:"guard_name";s:1:"r";s:5:"roles";}s:11:"permissions";a:1:{i:0;a:4:{s:1:"a";i:1;s:1:"b";s:26:"access klarifikasi backend";s:1:"c";s:3:"web";s:1:"r";a:6:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:5;i:4;i:6;i:5;i:7;}}}s:5:"roles";a:6:{i:0;a:3:{s:1:"a";i:1;s:1:"b";s:5:"Admin";s:1:"c";s:3:"web";}i:1;a:3:{s:1:"a";i:2;s:1:"b";s:8:"Penelaah";s:1:"c";s:3:"web";}i:2;a:3:{s:1:"a";i:4;s:1:"b";s:17:"Admin Klarifikasi";s:1:"c";s:3:"web";}i:3;a:3:{s:1:"a";i:5;s:1:"b";s:20:"Penelaah Klarifikasi";s:1:"c";s:3:"web";}i:4;a:3:{s:1:"a";i:6;s:1:"b";s:9:"Admin IGT";s:1:"c";s:3:"web";}i:5;a:3:{s:1:"a";i:7;s:1:"b";s:12:"Penelaah IGT";s:1:"c";s:3:"web";}}}', 1763547493);

-- Dumping structure for table laravelbreeze.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.cache_locks: ~0 rows (approximately)

-- Dumping structure for table laravelbreeze.data_igts
CREATE TABLE IF NOT EXISTS `data_igts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jenis_data` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode_update` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `format_data` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.data_igts: ~11 rows (approximately)
REPLACE INTO `data_igts` (`id`, `jenis_data`, `periode_update`, `format_data`, `created_at`, `updated_at`) VALUES
	(1, 'Peta Indikatif Penyelesaian Penguasaan Tanah', 'December 2024', 'Shapefile', '2025-10-05 01:38:44', '2025-10-05 01:38:44'),
	(3, 'Peta Kawasan Hutan dan Konservasi Perairan', 'August 2024', 'Shapefile', '2025-10-05 01:38:44', '2025-10-05 01:38:44'),
	(4, 'Penutupan Lahan Kawasan Hutan', 'Maret 2025', 'Shapefile', '2025-10-05 01:38:44', '2025-10-30 06:02:52'),
	(5, 'Penutupan Hutan', 'Maret 2025', 'Shapefile', '2025-10-30 06:00:11', '2025-10-30 06:00:11'),
	(6, 'Potensi Hutan', 'Januari 2024', 'Shapefile', '2025-10-30 06:03:17', '2025-10-30 06:03:17'),
	(7, 'NSDH Penutupan Lahan', 'Juni 2022', 'Shapefile', '2025-10-30 06:03:41', '2025-10-30 06:03:41'),
	(8, 'NSDH Kawasan Hutan', 'Tahun 2023', 'Shapefile', '2025-10-30 06:03:58', '2025-10-30 06:03:58'),
	(9, 'PIPPIB Periode  2025 Periode II', 'Oktober 2025', 'Shapefile', '2025-10-30 06:04:16', '2025-10-30 06:04:16'),
	(10, 'Deforestasi', '2023 - 2024', 'Shapefile', '2025-10-30 06:04:39', '2025-10-30 06:04:39'),
	(11, 'Reforestasi', '2023 - 2024', 'Shapefile', '2025-10-30 06:05:00', '2025-10-30 06:05:00'),
	(12, 'Sebaran Klaster IHN', 'Mei 2020', 'Shapefile', '2025-10-30 06:05:18', '2025-10-30 06:05:18');

-- Dumping structure for table laravelbreeze.data_spasials
CREATE TABLE IF NOT EXISTS `data_spasials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `permohonananalisis_id` bigint unsigned NOT NULL,
  `nama_areal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kabupaten` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coordinates` json NOT NULL,
  `geojson_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shapefile_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_paths` json DEFAULT NULL,
  `source_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `luas_ha` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `data_spasials_permohonananalisis_id_foreign` (`permohonananalisis_id`),
  CONSTRAINT `data_spasials_permohonananalisis_id_foreign` FOREIGN KEY (`permohonananalisis_id`) REFERENCES `permohonananalisis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.data_spasials: ~1 rows (approximately)
REPLACE INTO `data_spasials` (`id`, `permohonananalisis_id`, `nama_areal`, `kabupaten`, `coordinates`, `geojson_path`, `shapefile_path`, `photo_paths`, `source_type`, `luas_ha`, `created_at`, `updated_at`) VALUES
	(26, 29, 'gagasg', 'Ambon', '{"type": "Polygon", "coordinates": [[[132.7139659722222, -5.849345972222222], [132.7139799722222, -5.849424], [132.7139689722222, -5.849359972222222], [132.71717597222224, -5.853511999999999], [132.7139659722222, -5.849345972222222]]]}', 'permohonan_resmi/d1a2d242-ef1a-45fd-8624-59a3a08819b4/spasial/d1a2d242-ef1a-45fd-8624-59a3a08819b4_spasial.geojson', NULL, '"[\\"permohonan_resmi\\\\/d1a2d242-ef1a-45fd-8624-59a3a08819b4\\\\/spasial\\\\/lpXVB4uJ9Yc876thPKk9DWV8KCvK108PeLhjJ2l9.jpg\\",\\"permohonan_resmi\\\\/d1a2d242-ef1a-45fd-8624-59a3a08819b4\\\\/spasial\\\\/n6WdvLFyOIoESIbqkonojShtSbQreXY1sEV2p0C2.jpg\\",\\"permohonan_resmi\\\\/d1a2d242-ef1a-45fd-8624-59a3a08819b4\\\\/spasial\\\\/NwkYAwn1gblwcK9caaPuUD5QsVlVprvrZ6wZLDy0.jpg\\",\\"permohonan_resmi\\\\/d1a2d242-ef1a-45fd-8624-59a3a08819b4\\\\/spasial\\\\/HiAm2aW71So9sTmNSuX9reFQOsOvVpBE4V6kfzN5.jpg\\"]"', 'photo', 0.020019900517809622, '2025-11-18 06:10:17', '2025-11-18 06:10:17'),
	(28, 31, 'KOTA AMBON', 'Ambon', '{"type": "Polygon", "coordinates": [[[132.7139659722222, -5.849345972222222], [132.7139799722222, -5.849424], [132.7139689722222, -5.849359972222222], [132.71717597222224, -5.853511999999999], [132.7139659722222, -5.849345972222222]]]}', 'permohonan_resmi/a95d4005-22d0-4689-a76d-b4dcdf01d9bc/spasial/a95d4005-22d0-4689-a76d-b4dcdf01d9bc_spasial.geojson', NULL, '"[\\"permohonan_resmi\\\\/a95d4005-22d0-4689-a76d-b4dcdf01d9bc\\\\/spasial\\\\/X7hdrpc9zyR91vihGCNzzsgePvB2CceN6s7zKMIg.jpg\\",\\"permohonan_resmi\\\\/a95d4005-22d0-4689-a76d-b4dcdf01d9bc\\\\/spasial\\\\/BToMmaQlBdUogVMDdiLIMWJE1TpwE1jPS90cYq2x.jpg\\",\\"permohonan_resmi\\\\/a95d4005-22d0-4689-a76d-b4dcdf01d9bc\\\\/spasial\\\\/PFoYLJo6RATYjFtH4UH8Y5yhGaQIdSQP5G6PLEMP.jpg\\",\\"permohonan_resmi\\\\/a95d4005-22d0-4689-a76d-b4dcdf01d9bc\\\\/spasial\\\\/58HL9aHBkropNODcIdahUjHIjWb8dBXHdyrp9T4V.jpg\\"]"', 'photo', 0.020019900517809622, '2025-11-19 00:03:10', '2025-11-19 00:03:10');

-- Dumping structure for table laravelbreeze.detail_permohonans
CREATE TABLE IF NOT EXISTS `detail_permohonans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `permohonan_id` bigint unsigned NOT NULL,
  `daftar_igt_id` bigint unsigned NOT NULL,
  `cakupan_wilayah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_permohonans_permohonan_id_foreign` (`permohonan_id`),
  CONSTRAINT `detail_permohonans_permohonan_id_foreign` FOREIGN KEY (`permohonan_id`) REFERENCES `permohonans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.detail_permohonans: ~1 rows (approximately)
REPLACE INTO `detail_permohonans` (`id`, `permohonan_id`, `daftar_igt_id`, `cakupan_wilayah`, `created_at`, `updated_at`) VALUES
	(34, 28, 1, 'Provinsi Maluku', '2025-11-05 14:23:58', '2025-11-05 14:23:58'),
	(35, 29, 1, 'Kabupaten Seram Bagian Barat', '2025-11-10 05:59:56', '2025-11-10 05:59:56'),
	(36, 30, 1, 'Kabupaten Buru Selatan', '2025-11-16 04:07:27', '2025-11-16 04:07:27'),
	(37, 31, 1, 'Provinsi Maluku', '2025-11-18 12:06:02', '2025-11-18 12:06:02');

-- Dumping structure for table laravelbreeze.failed_jobs
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

-- Dumping data for table laravelbreeze.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table laravelbreeze.jobs
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

-- Dumping data for table laravelbreeze.jobs: ~0 rows (approximately)

-- Dumping structure for table laravelbreeze.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.job_batches: ~0 rows (approximately)

-- Dumping structure for table laravelbreeze.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.migrations: ~29 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_09_11_065817_create_polygons_table', 1),
	(5, '2025_09_11_065922_create_laporans_table', 1),
	(6, '2025_09_18_051652_add_role_to_users_table', 1),
	(7, '2025_10_04_032548_create_permohonan_table', 1),
	(8, '2025_10_05_101844_create_data_igt_table', 1),
	(9, '2025_10_28_144021_create_pengaduans_table', 1),
	(10, '2025_10_29_002618_create_survey_pelayanans_table', 1),
	(11, '2025_10_29_141741_create_permission_tables', 1),
	(12, '2025_10_29_145811_remove_role_column_from_users_table', 1),
	(13, '2025_10_29_153305_create_pengaduans_table', 2),
	(14, '2025_10_29_233850_update_pengaduans_for_workflow', 3),
	(15, '2025_10_30_011431_update_permohonans_table_for_workflow', 4),
	(16, '2025_10_30_135600_add_penelaah_id_to_permohonans_table', 5),
	(17, '2025_10_30_135601_add_laporan_penggunaan_to_permohonans_table', 6),
	(18, '2025_10_30_135602_add_permohonan_id_to_survey_pelayanans_table', 7),
	(19, '2025_10_30_135603_modify_permohonans_for_user_types', 8),
	(20, '2025_10_30_135604_add_catatan_revisi_to_permohonans_table', 9),
	(21, '2025_10_30_135604_add_slug_to_laporans_table', 10),
	(22, '2025_11_09_215529_create_permohonananalisis_table', 11),
	(23, '2025_11_09_215532_create_data_spasials_table', 11),
	(24, '2025_11_09_231138_add_form_fields_to_permohonananalisis_table', 12),
	(25, '2025_10_30_135604_add_kode_pelacakan_to_permohonananalisis_table', 13),
	(26, '2025_10_30_135605_add_category_to_pengaduans_table', 14),
	(27, '2025_10_30_135606_add_user_id_to_survey_pelayanans_table', 15),
	(28, '2025_10_30_135607_add_category_to_survey_pelayanans_table', 16),
	(29, '2025_10_30_135608_add_avatar_path_to_users_table', 17),
	(30, '2025_10_30_135609_add_penelaah_id_to_permohonananalisis_table', 18),
	(31, '2025_10_30_135610_add_kode_pelacakan_to_pengaduans_table', 19),
	(32, '2025_10_30_135611_add_hasil_files_to_permohonananalisis_table', 20),
	(33, '2025_10_30_135612_add_tujuan_analisis_to_permohonananalisis_table', 21);

-- Dumping structure for table laravelbreeze.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table laravelbreeze.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.model_has_roles: ~24 rows (approximately)
REPLACE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2),
	(1, 'App\\Models\\User', 4),
	(3, 'App\\Models\\User', 5),
	(2, 'App\\Models\\User', 6),
	(3, 'App\\Models\\User', 7),
	(5, 'App\\Models\\User', 7),
	(3, 'App\\Models\\User', 8),
	(5, 'App\\Models\\User', 8),
	(3, 'App\\Models\\User', 9),
	(3, 'App\\Models\\User', 10),
	(3, 'App\\Models\\User', 11),
	(3, 'App\\Models\\User', 12),
	(6, 'App\\Models\\User', 13),
	(3, 'App\\Models\\User', 14),
	(5, 'App\\Models\\User', 14),
	(3, 'App\\Models\\User', 15),
	(2, 'App\\Models\\User', 16),
	(3, 'App\\Models\\User', 17),
	(5, 'App\\Models\\User', 17),
	(3, 'App\\Models\\User', 18),
	(3, 'App\\Models\\User', 19),
	(5, 'App\\Models\\User', 19),
	(3, 'App\\Models\\User', 20);

-- Dumping structure for table laravelbreeze.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.password_reset_tokens: ~1 rows (approximately)
REPLACE INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
	('santry22.hh@gmail.com', '$2y$12$5ZfzzY4DsMiuCphMxcYFQuwYBj5wTARhXqDynixcz2en70x6QlxTq', '2025-11-15 12:10:32');

-- Dumping structure for table laravelbreeze.pengaduans
CREATE TABLE IF NOT EXISTS `pengaduans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_pelacakan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `category` enum('IGT','KLARIFIKASI','UMUM') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UMUM',
  `penelaah_id` bigint unsigned DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baru' COMMENT 'Baru, Diproses, Menunggu Persetujuan, Revisi, Selesai, Dibatalkan',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `balasan_penelaah` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengaduans_kode_pelacakan_unique` (`kode_pelacakan`),
  KEY `pengaduans_user_id_foreign` (`user_id`),
  KEY `pengaduans_penelaah_id_foreign` (`penelaah_id`),
  CONSTRAINT `pengaduans_penelaah_id_foreign` FOREIGN KEY (`penelaah_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pengaduans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.pengaduans: ~10 rows (approximately)
REPLACE INTO `pengaduans` (`id`, `kode_pelacakan`, `user_id`, `category`, `penelaah_id`, `nama`, `instansi`, `email`, `pesan`, `file`, `status`, `catatan_admin`, `balasan_penelaah`, `created_at`, `updated_at`) VALUES
	(7, NULL, 4, 'UMUM', 2, 'Hardianto Hamid, S.Hut', 'BPKH IX', 'santry22.hh@gmail.com', 'shahsfjjz', 'pengaduan_files/PrvT4hL2hPMxkMg1QDHAAGTCoea9oIymdCP32Blr.pdf', 'Selesai', 'Telah disetujui oleh Admin Kepala Seksi', 'sdgasddgsadgsdgsgsdfgjhkj.kj,jhg', '2025-10-29 14:51:24', '2025-10-29 14:56:35'),
	(8, NULL, 4, 'UMUM', 2, 'Hardianto Hamid, S.Hut', 'BPKH IX', 'santry22.hh@gmail.com', 'hradfdfsdfbasbasbs', 'pengaduan_files/FxBH0dpNEpJYu2hLcSRHnxnDTYcW95Ucoczepxkl.pdf', 'Selesai', 'Telah disetujui oleh Admin Kepala Seksi', 'teruntuk admin yang saya hormati sudah', '2025-10-29 15:03:05', '2025-10-29 15:17:31'),
	(9, NULL, 5, 'UMUM', 6, 'MBD_Poly', 'BPKH IX', 'santry22.hh@gmail.com', 'erhtjklkjhfgdsfd', 'pengaduan_files/9UuuZCe6SEMRq3goaFzRT12FAkiKIbfGJOQLRuAu.pdf', 'Selesai', 'Telah disetujui oleh Admin Kepala Seksi', 'baik kami perbaiki, terimkasih', '2025-10-29 15:24:13', '2025-10-29 15:35:07'),
	(10, NULL, 4, 'UMUM', 6, 'PPKH_BPKH_IX', 'BPKH IX', 'santry22.hh@gmail.com', 'wjklilkjhgfgfhgth', 'pengaduan_files/ZsQ1c6OkxiyzRak8HhSUYidiTNR30mYhnUezbKMu.pdf', 'Selesai', 'Telah disetujui oleh Admin Kepala Seksi', 'baik, terimkasih', '2025-10-29 15:42:28', '2025-10-29 16:00:17'),
	(11, NULL, 4, 'UMUM', 2, 'Hardianto Hamid, S.Hut', 'BPKH IX', 'santry22.hh@gmail.com', 'Data yang di berikan tidak lengkap', 'pengaduan_files/3alua9ufSahhAGH8ljSkGodm7cenT88yQsvQc1AF.pdf', 'Selesai', 'Telah disetujui oleh Admin Kepala Seksi', 'Terimakasih, akan kami cek kembali. Data sudah kami lengkapi, silakan download di tautan berikut', '2025-10-30 16:15:46', '2025-10-30 16:38:24'),
	(12, NULL, 4, 'UMUM', 6, 'Dewita', 'BPKH IX', 'santry22.hh@gmail.com', 'data tidak bisa diakses', 'pengaduan_files/sCDIAWu7sEmuhO5QcjHCLNp7MfF7XMnsrNXwbOGC.pdf', 'Selesai', 'Telah disetujui oleh Kepala Seksi', 'Baik, segera kami tindak lanjuti', '2025-11-04 01:55:46', '2025-11-04 02:02:51'),
	(13, NULL, 4, 'UMUM', NULL, 'Hardianto Hamid, S.Hut', 'BPKH IX', 'santry22.hh@gmail.com', 'hdffhsdfhsdffhsdfhsdh', 'pengaduan_files/g38NFJOdIvD1uskkpLUEbb2G5MIxSchScPGMouBT.pdf', 'Baru', NULL, NULL, '2025-11-10 06:00:43', '2025-11-10 06:00:43'),
	(17, 'PGD-1763372930caO3D', 19, 'KLARIFIKASI', 8, 'Tokent412', 'Perorangan', 'tokent412@gmail.com', 'Kontak HP: 081343164372\n\n--- Isi Laporan ---\nasfasf', 'pengaduan/19/klarifikasi/1763372930/z7fAHYOu1PEuSyQqwoMX3v3tLc4SSKrYKPa8ZsDI.pdf', 'Ditindaklanjuti', NULL, NULL, '2025-11-17 09:48:50', '2025-11-17 10:57:55'),
	(18, 'PGD-1763421372T1Yey', 19, 'KLARIFIKASI', 19, 'Tokent412', 'Perorangan', 'tokent412@gmail.com', 'Kontak HP: 081343164373\n\n--- Isi Laporan ---\ndata tidak lengkap', 'pengaduan/19/klarifikasi/1763421372/CFClFMUkFVccjhnQ08Oo7rAkrpDDyeFievAQWgc1.pdf', 'Selesai', 'Terimakasih, data telah kami perbaiki. silakan di download kembali', 'Terimakasih, data telah kami perbaiki. silakan di download kembali', '2025-11-17 23:16:12', '2025-11-18 05:57:42'),
	(19, 'PGD-1763445549OKgc8', 20, 'KLARIFIKASI', 19, 'Saypidol', 'Perorangan', 'saypidoldol@gmail.com', 'Kontak HP: 081343164372\n\n--- Isi Laporan ---\ndata yang diberikan tidak lengkap', 'pengaduan/20/klarifikasi/1763445549/PR8Tij6q8wbD3F7fvRp0tkUOURBEEFykck7cZLMq.pdf', 'Selesai', 'Terimakasih, data telah kami perbaiki. Sialakan download data anda di tautan berikut', 'Terimakasih, data telah kami perbaiki. Sialakan download data anda di tautan berikut', '2025-11-18 05:59:09', '2025-11-18 06:01:27');

-- Dumping structure for table laravelbreeze.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.permissions: ~2 rows (approximately)
REPLACE INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'access klarifikasi backend', 'web', '2025-11-17 04:25:32', '2025-11-17 04:25:32');

-- Dumping structure for table laravelbreeze.permohonananalisis
CREATE TABLE IF NOT EXISTS `permohonananalisis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `penelaah_id` bigint unsigned DEFAULT NULL,
  `catatan_penelaah` text COLLATE utf8mb4_unicode_ci,
  `file_surat_balasan_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_paket_final_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_pelacakan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pemohon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hp_pemohon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_pemohon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_surat` date DEFAULT NULL,
  `tujuan_analisis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perihal_surat` text COLLATE utf8mb4_unicode_ci,
  `file_surat_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `form_userid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_groupid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permohonananalisis_slug_unique` (`slug`),
  UNIQUE KEY `permohonananalisis_kode_pelacakan_unique` (`kode_pelacakan`),
  KEY `permohonananalisis_user_id_foreign` (`user_id`),
  KEY `permohonananalisis_penelaah_id_foreign` (`penelaah_id`),
  CONSTRAINT `permohonananalisis_penelaah_id_foreign` FOREIGN KEY (`penelaah_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `permohonananalisis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.permohonananalisis: ~2 rows (approximately)
REPLACE INTO `permohonananalisis` (`id`, `user_id`, `penelaah_id`, `catatan_penelaah`, `file_surat_balasan_path`, `file_paket_final_path`, `slug`, `kode_pelacakan`, `tipe`, `status`, `nama_pemohon`, `hp_pemohon`, `email_pemohon`, `nomor_surat`, `tanggal_surat`, `tujuan_analisis`, `perihal_surat`, `file_surat_path`, `keterangan`, `form_userid`, `form_groupid`, `created_at`, `updated_at`) VALUES
	(29, 20, NULL, NULL, NULL, NULL, 'd93d0e54-0f47-4a8a-ada7-9bc48813b24f', 'PAR-251118-HBKNBM', 'RESMI', 'Baru', 'Hardianto Hamid', '081343164372', 'santry22.hh@gmail.com', 'asdfghjklsdfghjm,', '2023-12-12', NULL, 'fsdgg', 'permohonan_resmi/d1a2d242-ef1a-45fd-8624-59a3a08819b4/surat/2dzFvjWy8ww4wXDcqB8PLvUsuBk04iZbSSvmzCnE.pdf', 'asgasg', '20', 'd1a2d242-ef1a-45fd-8624-59a3a08819b4', '2025-11-18 06:10:12', '2025-11-18 06:10:12'),
	(31, 20, NULL, NULL, NULL, NULL, '2dbdeb0a-3ef6-452c-8f1d-31648d384892', 'PAR-251119-GXCTVA', 'RESMI', 'Diajukan', 'Hardianto Hamid', '081343164372', 'santry22.hh@gmail.com', 'sfdgfhgmhgafd', '2023-12-12', 'Perizinan', 'assgag', 'permohonan_resmi/a95d4005-22d0-4689-a76d-b4dcdf01d9bc/surat/cTrhmE7B59fUM9K9LvahRsS6QVqR73tWLk2nK0Pn.pdf', 'pertek', '20', 'a95d4005-22d0-4689-a76d-b4dcdf01d9bc', '2025-11-19 00:02:48', '2025-11-19 00:02:48');

-- Dumping structure for table laravelbreeze.permohonans
CREATE TABLE IF NOT EXISTS `permohonans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `penelaah_id` bigint unsigned DEFAULT NULL,
  `nama_pemohon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_surat` date NOT NULL,
  `perihal` text COLLATE utf8mb4_unicode_ci,
  `file_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `catatan_revisi` text COLLATE utf8mb4_unicode_ci,
  `tipe_pemohon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_berita_acara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_ba_ttd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_data_final` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_paket_final` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `laporan_penggunaan_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_surat_balasan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cakupan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permohonans_user_id_foreign` (`user_id`),
  KEY `permohonans_penelaah_id_foreign` (`penelaah_id`),
  CONSTRAINT `permohonans_penelaah_id_foreign` FOREIGN KEY (`penelaah_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `permohonans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.permohonans: ~4 rows (approximately)
REPLACE INTO `permohonans` (`id`, `user_id`, `penelaah_id`, `nama_pemohon`, `nip`, `jabatan`, `instansi`, `email`, `no_hp`, `nomor_surat`, `tanggal_surat`, `perihal`, `file_surat`, `status`, `catatan_revisi`, `tipe_pemohon`, `file_berita_acara`, `file_ba_ttd`, `file_data_final`, `file_paket_final`, `laporan_penggunaan_path`, `file_surat_balasan`, `created_at`, `updated_at`, `cakupan`) VALUES
	(28, 4, 6, 'Hardianto H', '198212052003121003', 'Kepala Seksi Pengukuhan dan Perencanaan Kawasan Hutan', 'BPKH IX', 'santry22.hh@gmail.com', '081343164372', 'safasgassassgfdghsdh', '2025-11-01', 'fghdhgagfaadg', 'surat_permohonan/l4V8Gsur1ASRpGRoMw12sSWnkwEP4rz5hYqoNv9N.pdf', 'Menunggu TTD Pengguna', NULL, 'pemerintah', 'berita_acara/BA-28-1762417228.pdf', NULL, NULL, NULL, NULL, NULL, '2025-11-05 13:17:33', '2025-11-06 08:20:31', NULL),
	(29, 4, 6, 'Hardianto H', '198212052003121003', 'Kepala Seksi Pengukuhan dan Perencanaan Kawasan Hutan', 'BPKH IX', 'santry22.hh@gmail.com', '081343164372', 'safasgassassg', '2025-11-01', 'xvsdhsdh', 'surat_permohonan/o76XoyeK22MqeXwoCZXXAvJdiAPHA50pzC4JU3IL.pdf', 'Selesai', NULL, 'pemerintah', 'berita_acara/BA-29-1762755679.pdf', 'ba_ttd/kvtA5g6JZK7FlZ0JecqZSNNIeuzwmN8RSGSwRjAG.pdf', 'data_final/FCLcIiVURGjfK0qDT8xmrkoriqDuwn3hjQM9k9Yz.zip', 'paket_final/PAKET_PERMOHONAN_29_1762756935.zip', 'laporan_penggunaan/THQnidQ0O9OhccSktVxEh93BGh2ECvOysyfNlyGX.pdf', 'surat_balasan/UeMfxdncm2CgA92uuYLldZCrp66ROPpCc3GGoa02.pdf', '2025-11-10 05:59:56', '2025-11-16 04:05:33', NULL),
	(30, 4, NULL, 'Hardianto H', '198212052003121003', 'Kepala Seksi Pengukuhan dan Perencanaan Kawasan Hutan', 'BPKH IX', 'santry22.hh@gmail.com', '081343164372', 'sfdgfhgmhgafd', '2025-11-14', 'sgshasdh', 'surat_permohonan/kJuA6HyOcjJbMYrJe7XSUdTFOdWQny4iZx12lOv5.pdf', 'Pending', NULL, 'pemerintah', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-16 04:07:27', '2025-11-16 04:07:27', NULL),
	(31, 20, 2, 'Hardianto H', '198212052003121003', 'Kepala Seksi Pengukuhan dan Perencanaan Kawasan Hutan', 'BPKH IX', 'santry22.hh@gmail.com', '081343164372', 'asdfghjklsdfghjm,', '2025-11-11', 'rtykfjtdhsg', 'surat_permohonan/gFDdxMh73wCj8ZtK63VdpoqnYmjVF7L56MZyAj4e.pdf', 'Selesai', NULL, 'pemerintah', 'berita_acara/BA-31-1763467658.pdf', 'ba_ttd/WSLSIXp7ssLRoJyyxlUeueWHHA4TSGEXcGCf6KtE.pdf', 'data_final/GxwL07juheu2rNaswaT0FxZHmE1V3EHcp8u7jH2C.zip', 'paket_final/PAKET_PERMOHONAN_31_1763467757.zip', NULL, 'surat_balasan/GX3GwT0Uu1jt2b3n1uVSty4dVcIKGsbGnt9JIcX2.pdf', '2025-11-18 12:06:02', '2025-11-18 12:09:17', NULL);

-- Dumping structure for table laravelbreeze.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.roles: ~7 rows (approximately)
REPLACE INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', 'web', '2025-10-29 05:59:09', '2025-10-29 05:59:09'),
	(2, 'Penelaah', 'web', '2025-10-29 05:59:09', '2025-10-29 05:59:09'),
	(3, 'Pengguna', 'web', '2025-10-29 05:59:09', '2025-10-29 05:59:09'),
	(4, 'Admin Klarifikasi', 'web', '2025-11-17 04:14:29', '2025-11-17 04:14:29'),
	(5, 'Penelaah Klarifikasi', 'web', '2025-11-17 04:14:29', '2025-11-17 04:14:29'),
	(6, 'Admin IGT', 'web', '2025-11-17 04:14:29', '2025-11-17 04:14:29'),
	(7, 'Penelaah IGT', 'web', '2025-11-17 04:25:23', '2025-11-17 04:25:23');

-- Dumping structure for table laravelbreeze.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.role_has_permissions: ~6 rows (approximately)
REPLACE INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(1, 2),
	(1, 4),
	(1, 5),
	(1, 6),
	(1, 7);

-- Dumping structure for table laravelbreeze.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.sessions: ~2 rows (approximately)
REPLACE INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('HrKHQgphNezxpdaG2hr3zANVfSOT50boV2nK9SUx', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoicXVWbHRPQkxrcUpRMk1qQ09YWGpyVks0aXA5TEVrUkJJbkFDSjhIVCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbmtsYXJpZmlrYXNpL3Blcm1vaG9uYW4vMmRiZGViMGEtM2VmNi00NTJjLThmMWQtMzE2NDhkMzg0ODkyIjt9czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==', 1763511394);

-- Dumping structure for table laravelbreeze.survey_pelayanans
CREATE TABLE IF NOT EXISTS `survey_pelayanans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `permohonan_id` bigint unsigned DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pelayanan` date NOT NULL,
  `kebutuhan_pelayanan` json NOT NULL,
  `tujuan_penggunaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pernah_layanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `info_layanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cara_layanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_petugas_ditemui` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_petugas_dihubungi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_kompetensi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_kesopanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_info_jelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_syarat_sesuai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_syarat_wajar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_prosedur_mudah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_waktu_cepat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_biaya` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_hasil_sesuai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_kualitas_rekaman` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_layanan_keseluruhan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_sarpras` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_penanganan_pengaduan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kritik_saran` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `survey_pelayanans_permohonan_id_unique` (`permohonan_id`),
  KEY `survey_pelayanans_user_id_foreign` (`user_id`),
  CONSTRAINT `survey_pelayanans_permohonan_id_foreign` FOREIGN KEY (`permohonan_id`) REFERENCES `permohonans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `survey_pelayanans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.survey_pelayanans: ~10 rows (approximately)
REPLACE INTO `survey_pelayanans` (`id`, `user_id`, `permohonan_id`, `category`, `nama_lengkap`, `jenis_kelamin`, `pekerjaan`, `instansi`, `email`, `telepon`, `tanggal_pelayanan`, `kebutuhan_pelayanan`, `tujuan_penggunaan`, `pernah_layanan`, `info_layanan`, `cara_layanan`, `q_petugas_ditemui`, `q_petugas_dihubungi`, `q_kompetensi`, `q_kesopanan`, `q_info_jelas`, `q_syarat_sesuai`, `q_syarat_wajar`, `q_prosedur_mudah`, `q_waktu_cepat`, `q_biaya`, `q_hasil_sesuai`, `q_kualitas_rekaman`, `q_layanan_keseluruhan`, `q_sarpras`, `q_penanganan_pengaduan`, `kritik_saran`, `created_at`, `updated_at`) VALUES
	(2, NULL, NULL, 'IGT', 'Hardianto H', 'LAKI-LAKI', 'PEGAWAI PEMERINTAH', 'BPKH IX', 'santry22.hh@gmail.com', '081343164372', '2025-11-01', '["Permintaan Data IGT"]', 'Tujuan Kedinasan/Pekerjaan', 'Belum', 'Dari Media Sosial', 'Melalui telepon / Whatsapp', 'Mudah', 'Mudah', 'Kompeten', 'Sopan dan ramah', 'Cukup Informatif', 'Sesuai', 'Wajar', 'Mudah', 'Cepat', 'Tidak, Gratis', 'Sesuai', 'Memuaskan', 'Sangat Memuaskan', 'Baik', 'Dikelola dengan baik', 'terimakasih', '2025-11-01 17:04:19', '2025-11-01 17:04:19'),
	(3, NULL, NULL, 'IGT', 'Hardianto H', 'LAKI-LAKI', 'PEGAWAI PEMERINTAH', 'BPKH IX', 'santry22.hh@gmail.com', '081343164372', '2025-11-01', '["Permintaan Data IGT"]', 'Tujuan Kedinasan/Pekerjaan', 'Belum', 'Dari Teman', 'Mengirimkan surat/email', 'Mudah', 'Mudah', 'Kompeten', 'Sopan dan ramah', 'Cukup Informatif', 'Sesuai', 'Wajar', 'Mudah', 'Cepat', 'Tidak, Gratis', 'Sesuai', 'Memuaskan', 'Memuaskan', 'Baik', 'Dikelola dengan baik', 'terimakasih', '2025-11-03 13:25:46', '2025-11-03 13:25:46'),
	(7, NULL, NULL, 'IGT', 'Hardianto Hamid Hamid', 'LAKI-LAKI', 'PEGAWAI PEMERINTAH', 'BPKH IX', 'santry22.hh@gmail.com', '08111111111', '2025-11-03', '["Analisis Status Kawasan"]', 'Tujuan Kedinasan/Pekerjaan', 'Belum', 'Dari Media Sosial', 'Mendatangi kantor langsung', 'Mudah', 'Mudah', 'Sangat Kompeten', 'Sangat sopan dan ramah', 'Cukup Informatif', 'Sesuai', 'Sangat Wajar', 'Mudah', 'Sangat Cepat', 'Tidak, Gratis', 'Sesuai', 'Memuaskan', 'Sangat Memuaskan', 'Sangat Baik', 'Dikelola dengan baik', '2', '2025-11-05 06:43:08', '2025-11-05 06:43:08'),
	(11, 12, NULL, 'KLARIFIKASI', 'Tokent412', 'LAKI-LAKI', 'PEGAWAI PEMERINTAH', 'BPKH IX', 'tokent412@gmail.com', '081343164373', '2025-11-11', '["Analisis Status Kawasan"]', 'Tujuan Kedinasan/Pekerjaan', 'Belum', 'Dari Media Sosial', 'Melalui telepon / Whatsapp', 'Mudah', 'Mudah', 'Kompeten', 'Sopan dan ramah', 'Cukup Informatif', 'Sesuai', 'Wajar', 'Mudah', 'Cepat', 'Iya, Murah', 'Sesuai', 'Memuaskan', 'Memuaskan', 'Baik', 'Dikelola dengan baik', 'dasdASDADSA', '2025-11-17 14:03:46', '2025-11-17 14:03:46'),
	(12, NULL, NULL, 'KLARIFIKASI', 'Tokent412', 'LAKI-LAKI', 'PEGAWAI PEMERINTAH', 'BPKH IX', 'tokent412@gmail.com', '081343164372', '2025-11-11', '["Permintaan Data IGT"]', 'Penelitian/Riset', 'Belum', 'Dari Media Sosial', 'Mendatangi kantor langsung', 'Mudah', 'Mudah', 'Sangat Kompeten', 'Sangat sopan dan ramah', 'Sangat Informatif', 'Sangat Sesuai', 'Sangat Wajar', 'Mudah', 'Cepat', 'Tidak, Gratis', 'Sangat Sesuai', 'Sangat Memuaskan', 'Sangat Memuaskan', 'Sangat Baik', 'Dikelola dengan baik', 'sa', '2025-11-17 14:28:23', '2025-11-17 14:28:23'),
	(13, NULL, NULL, 'KLARIFIKASI', 'Tokent412', 'LAKI-LAKI', 'PEGAWAI PEMERINTAH', 'BPKH IX', 'tokent412@gmail.com', '081343164372', '2025-11-11', '["Permintaan Data IGT"]', 'Penelitian/Riset', 'Belum', 'Dari Media Sosial', 'Mendatangi kantor langsung', 'Mudah', 'Mudah', 'Sangat Kompeten', 'Sangat sopan dan ramah', 'Sangat Informatif', 'Sangat Sesuai', 'Sangat Wajar', 'Mudah', 'Cepat', 'Tidak, Gratis', 'Sangat Sesuai', 'Sangat Memuaskan', 'Sangat Memuaskan', 'Sangat Baik', 'Dikelola dengan baik', 'sa', '2025-11-17 14:28:24', '2025-11-17 14:28:24'),
	(14, 19, NULL, 'KLARIFIKASI', 'Tokent412', 'LAKI-LAKI', 'PEGAWAI PEMERINTAH', 'BPKH IX', 'tokent412@gmail.com', '081343164372', '2025-10-28', '["Permintaan Data IGT"]', 'Tujuan Kedinasan/Pekerjaan', 'Belum', 'Dari Teman', 'Melalui telepon / Whatsapp', 'Sangat Mudah', 'Mudah', 'Kompeten', 'Sopan dan ramah', 'Sangat Informatif', 'Sesuai', 'Wajar', 'Mudah', 'Cepat', 'Tidak, Gratis', 'Sesuai', 'Memuaskan', 'Memuaskan', 'Baik', 'Berfungsi kurang maksimal', 'safas', '2025-11-17 14:33:15', '2025-11-17 14:33:15'),
	(15, 19, NULL, 'KLARIFIKASI', 'Tokent412', 'LAKI-LAKI', 'PEGAWAI PEMERINTAH', 'asfasfffhhashsh', 'tokent412@gmail.com', '081343164372', '2025-11-12', '["Analisis Status Kawasan"]', 'Penelitian/Riset', 'Belum', 'Dari Media Sosial', 'Melalui telepon / Whatsapp', 'Mudah', 'Mudah', 'Kompeten', 'Sopan dan ramah', 'Cukup Informatif', 'Sesuai', 'Wajar', 'Mudah', 'Cepat', 'Tidak, Gratis', 'Sangat Sesuai', 'Memuaskan', 'Memuaskan', 'Baik', 'Berfungsi kurang maksimal', 'ok', '2025-11-17 23:40:46', '2025-11-17 23:40:46'),
	(16, 19, NULL, 'KLARIFIKASI', 'Tokent412', 'LAKI-LAKI', 'PEGAWAI PEMERINTAH', 'BPKH IX', 'tokent412@gmail.com', '081343164372', '2025-11-18', '["Analisis Status Kawasan"]', 'Penelitian/Riset', 'Belum', 'Dari Media Sosial', 'Melalui telepon / Whatsapp', 'Mudah', 'Mudah', 'Kompeten', 'Sopan dan ramah', 'Cukup Informatif', 'Sesuai', 'Wajar', 'Mudah', 'Cepat', 'Tidak, Gratis', 'Sesuai', 'Memuaskan', 'Memuaskan', 'Baik', 'Dikelola dengan baik', 'ko', '2025-11-17 23:56:53', '2025-11-17 23:56:53');

-- Dumping structure for table laravelbreeze.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravelbreeze.users: ~19 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `email`, `avatar_path`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Kepala Seksi', 'admin@bpkh.com', NULL, '2025-10-29 05:59:09', '$2y$12$uzn1s9YOXV9tHHNr2FFvt.uEsrVKqC6EepWO0yigDUFm5fZdRf1Kq', 'I2VSGR2iAdhilqzNrvWFTfnAwZrp1xAAlU0wez5IAEAiiLBiIsK2EsvCIp1P', '2025-10-29 05:59:09', '2025-10-29 05:59:09'),
	(2, 'Penelaah Data', 'penelaah@bpkh.com', NULL, '2025-10-29 05:59:09', '$2y$12$NZX9tbqGvnApVcRSk464Dui4SyxssJmV90ltdgJY7XwEgUvvrPCIa', '4Us51Q7zmbiRuvsos0k4Gkid4q5Ws1YMZJwj5GoEFoabioDJD0FPIuH0z1xv', '2025-10-29 05:59:09', '2025-10-29 05:59:09'),
	(4, 'Hardianto Hamid', 'santry22.hh@gmail.com', 'avatars/CEyDXq1a4IzlX8S1ntxhV39vtnGviDngMfpJhpB3.jpg', '2025-11-15 12:26:06', '$2y$12$4yPTMe1uG5Wnx7WKd4uJnOv38voxocFejZmr8FAuQZqqak0QJTJ9W', NULL, '2025-10-29 06:06:19', '2025-11-16 14:11:21'),
	(5, 'testing', 'test@example.com', NULL, '2025-11-18 09:20:02', '$2y$12$10XcvzWVulAZzIu1NwULZugWLp9iPijF3QSSWkKmKmFWjwSyxI0k6', NULL, '2025-10-29 14:32:56', '2025-10-29 14:32:56'),
	(6, 'Rusdi', 'rusdi@bpkh.com', NULL, '2025-11-18 09:20:52', '$2y$12$wKKrIXFeMgTMuo09F1TEdOYF.MNbkHPMxTFagU61YyHa50fC71TyC', NULL, '2025-10-29 15:20:18', '2025-10-29 15:20:18'),
	(7, 'Indra Gunawan', 'gunawan06081993@gmail.com', NULL, '2025-11-17 05:39:57', '$2y$12$vWw8pOJJB162VM4GNKufh.HK7Eb3PvpYRMuUZ3EmCresRtBZnkU.2', 'TY5jFU5Tjj9j5r42llbQVTrTQkFYrtic2cL904LT0v5UBjFnBle0btRRl0FR', '2025-11-02 21:21:34', '2025-11-17 05:39:57'),
	(8, 'Randy Juharman', 'randy.juharman@gmail.com', NULL, NULL, '$2y$12$9CWqelsAENYjmq8idcaoxe25gssNMVfbFn32xpbssBzVjzhJhdnaG', NULL, '2025-11-02 21:25:31', '2025-11-02 21:25:31'),
	(9, 'Karlina Dewi E', 'karlinal563@gmail.com', NULL, NULL, '$2y$12$49hnLhFRKAThC04ljaluKe.5qsLiCm4dvcUF6oDTAqilsiZcxb.vq', NULL, '2025-11-04 01:26:10', '2025-11-04 01:26:10'),
	(10, 'Marisa Christina Makahity', 'ichamaka14@gmail.com', NULL, NULL, '$2y$12$ZLZ2uxaoy/wDd1kR.B9w2uLHxpY7jWhgjIMyIZ3Q8DdYfVOn8XLAC', NULL, '2025-11-04 01:26:55', '2025-11-04 01:26:55'),
	(11, 'Lodriko Limahelu', 'igoforester@gmail.com', NULL, NULL, '$2y$12$rplgdVMdyY6lpiJsaPf6BOVnC4rjyxJwvIos5S62WSNX2vunWWuGq', NULL, '2025-11-04 01:28:22', '2025-11-04 01:28:22'),
	(12, 'Abdul Halil Kelirey', 'abdul.hkelirey14@gmail.com', NULL, NULL, '$2y$12$kgwkBUYIM/TkTUhRSerj4eJHnYn0bx7nU0P3yVuPVpmWBf4UoH4Rq', NULL, '2025-11-04 01:28:56', '2025-11-04 01:28:56'),
	(13, 'Marleen', 'tuakoramarleen@gmail.com', NULL, '2025-11-19 00:00:00', '$2y$12$q5q7mNX3qI3OIPnd29jQPufeLPj7c1zQjYthxc2LWOLryHPF3pIA2', NULL, '2025-11-04 01:30:34', '2025-11-04 01:30:34'),
	(14, 'Azziaro Saputra', 'azziarosaputra@gmail.com', NULL, NULL, '$2y$12$EGASpgi1ejEfWvjGN2c5VOPaY1ONrTnhmfXrz8UMzledHXz9qFIsq', NULL, '2025-11-04 01:31:46', '2025-11-04 01:31:46'),
	(15, 'Yohanes RPH', 'yohanharso@gmail.com', NULL, NULL, '$2y$12$gMXrq8.PbcPHtE6kGydZ8uqrv15wvTmyxeScrt1kD70skHQF1ts9C', NULL, '2025-11-04 01:36:21', '2025-11-04 01:36:21'),
	(16, 'Meygen Panggua', 'genobpkh9@gmail.com', NULL, NULL, '$2y$12$jGxQyRFtgDOPaiY0fobFQuz7i9l6sVf81E4D8LyyHljaUEsxKgBYG', 'NFuBHuiAIHkDXs5KcmWy1Y7XsspeFiZGI7dsJE4ci7Tvt9jqRxeGmkp514ZD', '2025-11-04 02:06:10', '2025-11-10 06:17:46'),
	(17, 'ardian', 'ardian@bpkh.com', NULL, NULL, '$2y$12$oAuWSInCBm.I0gE9Y1ZK6O5Zi//jZOFFC8xVpMWJ.AclhpUGPuxAC', NULL, '2025-11-05 12:07:58', '2025-11-05 12:07:58'),
	(18, 'Meygen Panggua', 'meygenpanggua@gmail.com', NULL, NULL, '$2y$12$z8ncPAaxLZjmvlmlZs0Bc.ROA/gQNnWZ2Tt5SxjiskMbnGovHdgM2', NULL, '2025-11-10 05:45:07', '2025-11-10 05:45:07'),
	(19, 'Tokent412', 'tokent412@gmail.com', NULL, '2025-11-17 06:36:14', '$2y$12$jOuJCKVS5I6RpUer01tAEOhmBu/TpOk0dOHnbayyLN03edOxxLkXm', NULL, '2025-11-15 12:14:05', '2025-11-15 12:14:05'),
	(20, 'Saypidol', 'saypidoldol@gmail.com', NULL, '2025-11-18 03:00:51', '$2y$12$Gmk4nRL20j5MD6o/WSeWG.gPKDNUTtBntVt9gERx/0/wM4IaRxYrO', NULL, '2025-11-18 03:00:01', '2025-11-18 03:00:01');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
