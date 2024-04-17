-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 12, 2024 lúc 01:03 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `brawn_code_test`
--

DELIMITER $$
--
-- Thủ tục
--
DROP PROCEDURE IF EXISTS `GetWorkoutSessions`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetWorkoutSessions` (IN `startDate` DATE, IN `endDate` DATE, IN `liftId` INT, IN `reps` INT, IN `_limit` INT, IN `_offset` INT)   BEGIN
	SELECT COUNT(total) total
	FROM 
    	(SELECT
            COUNT(1) total
            FROM
                workout_sessions
                INNER JOIN sets ON workout_sessions.id = sets.workout_session_id
                LEFT JOIN users ON users.id = workout_sessions.user_id
                LEFT JOIN lifts ON lifts.id = sets.lift_id
            WHERE
                (startDate IS NULL OR workout_sessions.start_at BETWEEN startDate AND endDate)
                AND (liftId IS NULL OR sets.lift_id = liftId)
                AND (reps IS NULL OR sets.reps = reps)
            GROUP BY workout_sessions.id, sets.reps, sets.lift_id
    	) arc;
   SELECT
      workout_sessions.id,
      workout_sessions.start_at,
      workout_sessions.end_at,
      users.name,
      lifts.name AS lift_name,
      sets.reps,
      SUM(sets.reps * sets.weight) AS volume
   FROM
      workout_sessions
      INNER JOIN sets ON workout_sessions.id = sets.workout_session_id
      LEFT JOIN users ON users.id = workout_sessions.user_id
      LEFT JOIN lifts ON lifts.id = sets.lift_id
   WHERE
      (startDate IS NULL OR workout_sessions.start_at BETWEEN startDate AND endDate)
      AND (liftId IS NULL OR sets.lift_id = liftId)
      AND (reps IS NULL OR sets.reps = reps)
   GROUP BY
      workout_sessions.id,
      workout_sessions.start_at,
      workout_sessions.end_at,
      users.name,
      sets.reps,
      sets.lift_id,
      lifts.name
   ORDER BY
      workout_sessions.start_at DESC
   LIMIT _offset, _limit;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lifts`
--

DROP TABLE IF EXISTS `lifts`;
CREATE TABLE IF NOT EXISTS `lifts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lifts`
--

INSERT INTO `lifts` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Jogging', '2024-04-11 20:28:49', '2024-04-11 20:39:57'),
(2, 'Weight lifting', '2024-04-11 20:32:45', '2024-04-11 20:32:45'),
(7, 'Weight lifting 2', '2024-04-11 20:44:14', '2024-04-11 20:44:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_02_29_152105_create_workout_sessions_table', 1),
(6, '2024_02_29_152108_create_lifts_table', 1),
(7, '2024_02_29_152116_create_sets_table', 1),
(8, '2014_10_12_100000_create_password_resets_table', 2),
(9, '2016_06_01_000001_create_oauth_auth_codes_table', 3),
(10, '2016_06_01_000002_create_oauth_access_tokens_table', 3),
(11, '2016_06_01_000003_create_oauth_refresh_tokens_table', 3),
(12, '2016_06_01_000004_create_oauth_clients_table', 3),
(13, '2016_06_01_000005_create_oauth_personal_access_clients_table', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('ab30c28b8de5e9ec92efe3c7a94eba06a74f79d972699fb29434e52204df0a4fa20fde664bb33a11', 1, 1, 'auth_api', '[]', 0, '2024-04-11 19:39:18', '2024-04-11 19:39:18', '2025-04-12 02:39:18'),
('e0401ab77aa5d6496e74d58547781d563515dbf9d6f39dfa447e7530a5bfd0f3647ca95ff6ce032d', 1, 1, 'auth_api', '[]', 0, '2024-04-11 22:38:23', '2024-04-11 22:38:23', '2025-04-12 05:38:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'hTdAwsreNuI8iFBsRJ5ou2Flubxo1HmozWlpwDO2', NULL, 'http://localhost', 1, 0, 0, '2024-04-11 19:10:03', '2024-04-11 19:10:03'),
(2, NULL, 'Laravel Password Grant Client', '1myvwUSME9KquXE7XtbvQ6EecKUOaFt02vDHrzzo', 'users', 'http://localhost', 0, 1, 0, '2024-04-11 19:10:03', '2024-04-11 19:10:03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-04-11 19:10:03', '2024-04-11 19:10:03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_api', '28be5cb40be6bd2b70f3814ff1bfec639ccb058ba91c1a22aa44390f748861cd', '[\"*\"]', NULL, NULL, '2024-04-11 19:36:12', '2024-04-11 19:36:12'),
(2, 'App\\Models\\User', 1, 'auth_api', '976ec83588153ac6be551fad1176da19cd09636f9037206abe3df6997df7f3ed', '[\"*\"]', NULL, NULL, '2024-04-11 19:37:20', '2024-04-11 19:37:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sets`
--

DROP TABLE IF EXISTS `sets`;
CREATE TABLE IF NOT EXISTS `sets` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `workout_session_id` bigint(20) UNSIGNED NOT NULL,
  `reps` double(8,2) UNSIGNED NOT NULL,
  `weight` double(8,2) UNSIGNED NOT NULL,
  `lift_id` bigint(20) UNSIGNED NOT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sets_workout_session_id_foreign` (`workout_session_id`),
  KEY `sets_lift_id_foreign` (`lift_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sets`
--

INSERT INTO `sets` (`id`, `workout_session_id`, `reps`, `weight`, `lift_id`, `order`, `created_at`, `updated_at`) VALUES
(9, 3, 1.00, 10.00, 1, 1, '2024-04-11 21:28:48', '2024-04-11 21:28:48'),
(10, 3, 2.00, 20.00, 2, 2, '2024-04-11 21:28:48', '2024-04-11 21:28:48'),
(11, 3, 2.00, 30.00, 2, 3, '2024-04-11 21:28:48', '2024-04-11 21:28:48'),
(12, 3, 4.00, 40.00, 1, 4, '2024-04-11 21:28:48', '2024-04-11 21:28:48'),
(13, 4, 1.00, 20.00, 1, 1, '2024-04-11 21:30:09', '2024-04-11 21:30:09'),
(14, 4, 2.00, 30.00, 1, 2, '2024-04-11 21:30:09', '2024-04-11 21:30:09'),
(15, 4, 3.00, 40.00, 1, 3, '2024-04-11 21:30:09', '2024-04-11 21:30:09'),
(16, 4, 4.00, 50.00, 1, 4, '2024-04-11 21:30:09', '2024-04-11 21:30:09'),
(17, 5, 1.00, 40.00, 2, 1, '2024-04-11 21:30:32', '2024-04-11 21:30:32'),
(18, 5, 2.00, 50.00, 2, 2, '2024-04-11 21:30:32', '2024-04-11 21:30:32'),
(19, 5, 2.00, 60.00, 1, 3, '2024-04-11 21:30:32', '2024-04-11 21:30:32'),
(20, 5, 4.00, 50.00, 1, 4, '2024-04-11 21:30:32', '2024-04-11 21:30:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Duong Cuong', 'your-email@gmail.com', NULL, '$2y$12$eNW4gtA17BtAK/NoFqib8ONgFMy3uI1O5KN42gsZ8UStDt/Jc2Np2', NULL, '2024-04-11 19:16:04', '2024-04-11 19:16:04');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `workout_sessions`
--

DROP TABLE IF EXISTS `workout_sessions`;
CREATE TABLE IF NOT EXISTS `workout_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workout_sessions_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `workout_sessions`
--

INSERT INTO `workout_sessions` (`id`, `user_id`, `start_at`, `end_at`, `created_at`, `updated_at`) VALUES
(3, 1, '2024-04-12 11:18:00', '2024-04-12 14:18:00', '2024-04-11 21:28:48', '2024-04-11 21:28:48'),
(4, 1, '2024-04-12 11:18:00', '2024-04-12 14:18:00', '2024-04-11 21:30:09', '2024-04-11 21:30:09'),
(5, 1, '2024-04-12 11:18:00', '2024-04-12 14:18:00', '2024-04-11 21:30:32', '2024-04-11 21:30:32');

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `sets`
--
ALTER TABLE `sets`
  ADD CONSTRAINT `sets_lift_id_foreign` FOREIGN KEY (`lift_id`) REFERENCES `lifts` (`id`),
  ADD CONSTRAINT `sets_workout_session_id_foreign` FOREIGN KEY (`workout_session_id`) REFERENCES `workout_sessions` (`id`);

--
-- Các ràng buộc cho bảng `workout_sessions`
--
ALTER TABLE `workout_sessions`
  ADD CONSTRAINT `workout_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
