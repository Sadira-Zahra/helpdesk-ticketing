-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 01, 2025 at 07:53 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `helpdesk_ticket`
--

-- --------------------------------------------------------

--
-- Table structure for table `c45_evaluation`
--

CREATE TABLE `c45_evaluation` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `recommended_urgency_id` bigint UNSIGNED NOT NULL,
  `actual_urgency_id` bigint UNSIGNED NOT NULL,
  `is_match` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `c45_evaluation`
--

INSERT INTO `c45_evaluation` (`id`, `ticket_id`, `recommended_urgency_id`, `actual_urgency_id`, `is_match`, `created_at`) VALUES
(1, 2, 5, 5, 1, '2025-11-19 00:49:05'),
(2, 8, 5, 5, 1, '2025-11-19 01:06:36'),
(3, 7, 5, 5, 1, '2025-11-19 01:28:45'),
(4, 2, 5, 5, 1, '2025-11-19 01:39:46'),
(5, 2, 5, 5, 1, '2025-11-19 08:41:57'),
(6, 2, 5, 5, 1, '2025-11-19 08:43:27'),
(7, 2, 5, 5, 1, '2025-11-19 09:13:40'),
(8, 2, 5, 5, 1, '2025-11-19 09:17:05'),
(9, 9, 5, 5, 1, '2025-11-19 09:28:47'),
(10, 9, 5, 5, 1, '2025-11-19 09:29:28'),
(11, 9, 5, 5, 1, '2025-11-19 09:29:52'),
(12, 9, 5, 5, 1, '2025-11-19 09:33:13'),
(13, 9, 5, 4, 0, '2025-11-19 09:33:40'),
(14, 9, 5, 5, 1, '2025-11-19 09:34:08'),
(15, 7, 5, 5, 1, '2025-11-19 09:39:29'),
(16, 8, 5, 5, 1, '2025-11-19 09:40:05'),
(17, 8, 5, 5, 1, '2025-11-19 09:52:18'),
(18, 8, 5, 5, 1, '2025-11-19 09:54:03'),
(19, 8, 5, 5, 1, '2025-11-19 10:18:39'),
(20, 8, 5, 5, 1, '2025-11-19 10:24:31'),
(21, 8, 5, 5, 1, '2025-11-19 10:25:02'),
(22, 4, 5, 5, 1, '2025-11-25 07:48:29'),
(23, 4, 5, 5, 1, '2025-11-25 07:53:20'),
(24, 4, 5, 5, 1, '2025-11-30 05:09:09');

-- --------------------------------------------------------

--
-- Table structure for table `c45_model`
--

CREATE TABLE `c45_model` (
  `id` bigint UNSIGNED NOT NULL,
  `tree_json` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `rules_json` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `accuracy` decimal(5,2) NOT NULL DEFAULT '0.00',
  `data_count` int NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `c45_model`
--

INSERT INTO `c45_model` (`id`, `tree_json`, `rules_json`, `accuracy`, `data_count`, `updated_at`) VALUES
(1, '{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"pc, perbaikan\":{\"type\":\"leaf\",\"class\":\"High\"},\"printer, pc, repair\":{\"type\":\"node\",\"attribute\":\"dept_terdampak\",\"branches\":{\"Single Dept\":{\"type\":\"leaf\",\"class\":\"High\"},\"Multiple Dept\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"printer, pc, repair\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"printer, pc, repair\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"printer, pc, repair\":{\"type\":\"leaf\",\"class\":\"Medium\"}}}}}}}}},\"laptop, repair\":{\"type\":\"leaf\",\"class\":\"Medium\"},\"setting\":{\"type\":\"leaf\",\"class\":\"Urgent\"},\"printer, pc, perbaikan\":{\"type\":\"leaf\",\"class\":\"High\"},\"setting, software\":{\"type\":\"leaf\",\"class\":\"Urgent\"},\"laptop, replacement\":{\"type\":\"leaf\",\"class\":\"Medium\"},\"printer, setting\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"printer, setting\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"printer, setting\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"printer, setting\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"printer, setting\":{\"type\":\"leaf\",\"class\":\"Medium\"}}}}}}}}},\"pc, replacement\":{\"type\":\"leaf\",\"class\":\"Medium\"},\"laptop, pc, repair\":{\"type\":\"leaf\",\"class\":\"High\"},\"install\":{\"type\":\"leaf\",\"class\":\"Medium\"},\"printer, laptop, repair\":{\"type\":\"leaf\",\"class\":\"Medium\"},\"pc\":{\"type\":\"leaf\",\"class\":\"Medium\"},\"repair, software\":{\"type\":\"leaf\",\"class\":\"Urgent\"},\"setting, email\":{\"type\":\"leaf\",\"class\":\"High\"},\"printer\":{\"type\":\"leaf\",\"class\":\"Medium\"},\"laptop\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"laptop\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"laptop\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"laptop\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"laptop\":{\"type\":\"leaf\",\"class\":\"Medium\"}}}}}}}}},\"perbaikan, internet\":{\"type\":\"leaf\",\"class\":\"High\"},\"printer, maintenance\":{\"type\":\"node\",\"attribute\":\"dept_terdampak\",\"branches\":{\"Single Dept\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"printer, maintenance\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"printer, maintenance\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"printer, maintenance\":{\"type\":\"leaf\",\"class\":\"Medium\"}}}}}}},\"Multiple Dept\":{\"type\":\"leaf\",\"class\":\"Medium\"}}},\"printer, pc\":{\"type\":\"leaf\",\"class\":\"High\"},\"umum\":{\"type\":\"node\",\"attribute\":\"dept_terdampak\",\"branches\":{\"Single Dept\":{\"type\":\"node\",\"attribute\":\"tipe_masalah\",\"branches\":{\"Software\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"umum\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"umum\":{\"type\":\"leaf\",\"class\":\"Medium\"}}}}},\"Printer\":{\"type\":\"leaf\",\"class\":\"High\"},\"Lainnya\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"umum\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"umum\":{\"type\":\"leaf\",\"class\":\"Medium\"}}}}},\"Network\":{\"type\":\"leaf\",\"class\":\"Medium\"}}},\"Multiple Dept\":{\"type\":\"leaf\",\"class\":\"High\"}}},\"laptop, install\":{\"type\":\"leaf\",\"class\":\"Medium\"},\"printer, repair\":{\"type\":\"leaf\",\"class\":\"Medium\"},\"update\":{\"type\":\"node\",\"attribute\":\"dept_terdampak\",\"branches\":{\"Multiple Dept\":{\"type\":\"leaf\",\"class\":\"Medium\"},\"Single Dept\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"update\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"update\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"update\":{\"type\":\"leaf\",\"class\":\"Medium\"}}}}}}}}},\"pc, jaringan\":{\"type\":\"leaf\",\"class\":\"Medium\"},\"laptop, setting\":{\"type\":\"leaf\",\"class\":\"Urgent\"},\"repair\":{\"type\":\"leaf\",\"class\":\"Low\"},\"email, password\":{\"type\":\"node\",\"attribute\":\"dept_terdampak\",\"branches\":{\"Single Dept\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"email, password\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"email, password\":{\"type\":\"node\",\"attribute\":\"kata_kunci\",\"branches\":{\"email, password\":{\"type\":\"leaf\",\"class\":\"Medium\"}}}}}}},\"Multiple Dept\":{\"type\":\"leaf\",\"class\":\"Medium\"}}},\"pc, wifi\":{\"type\":\"leaf\",\"class\":\"Medium\"},\"email\":{\"type\":\"leaf\",\"class\":\"Low\"}}}', '[{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"pc, perbaikan\"}],\"conclusion\":\"High\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"printer, pc, repair\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Single Dept\"}],\"conclusion\":\"High\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"printer, pc, repair\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Multiple Dept\"},{\"attribute\":\"kata_kunci\",\"value\":\"printer, pc, repair\"},{\"attribute\":\"kata_kunci\",\"value\":\"printer, pc, repair\"},{\"attribute\":\"kata_kunci\",\"value\":\"printer, pc, repair\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"laptop, repair\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"setting\"}],\"conclusion\":\"Urgent\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"printer, pc, perbaikan\"}],\"conclusion\":\"High\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"setting, software\"}],\"conclusion\":\"Urgent\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"laptop, replacement\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"printer, setting\"},{\"attribute\":\"kata_kunci\",\"value\":\"printer, setting\"},{\"attribute\":\"kata_kunci\",\"value\":\"printer, setting\"},{\"attribute\":\"kata_kunci\",\"value\":\"printer, setting\"},{\"attribute\":\"kata_kunci\",\"value\":\"printer, setting\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"pc, replacement\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"laptop, pc, repair\"}],\"conclusion\":\"High\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"install\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"printer, laptop, repair\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"pc\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"repair, software\"}],\"conclusion\":\"Urgent\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"setting, email\"}],\"conclusion\":\"High\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"printer\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"laptop\"},{\"attribute\":\"kata_kunci\",\"value\":\"laptop\"},{\"attribute\":\"kata_kunci\",\"value\":\"laptop\"},{\"attribute\":\"kata_kunci\",\"value\":\"laptop\"},{\"attribute\":\"kata_kunci\",\"value\":\"laptop\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"perbaikan, internet\"}],\"conclusion\":\"High\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"printer, maintenance\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Single Dept\"},{\"attribute\":\"kata_kunci\",\"value\":\"printer, maintenance\"},{\"attribute\":\"kata_kunci\",\"value\":\"printer, maintenance\"},{\"attribute\":\"kata_kunci\",\"value\":\"printer, maintenance\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"printer, maintenance\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Multiple Dept\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"printer, pc\"}],\"conclusion\":\"High\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"umum\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Single Dept\"},{\"attribute\":\"tipe_masalah\",\"value\":\"Software\"},{\"attribute\":\"kata_kunci\",\"value\":\"umum\"},{\"attribute\":\"kata_kunci\",\"value\":\"umum\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"umum\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Single Dept\"},{\"attribute\":\"tipe_masalah\",\"value\":\"Printer\"}],\"conclusion\":\"High\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"umum\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Single Dept\"},{\"attribute\":\"tipe_masalah\",\"value\":\"Lainnya\"},{\"attribute\":\"kata_kunci\",\"value\":\"umum\"},{\"attribute\":\"kata_kunci\",\"value\":\"umum\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"umum\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Single Dept\"},{\"attribute\":\"tipe_masalah\",\"value\":\"Network\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"umum\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Multiple Dept\"}],\"conclusion\":\"High\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"laptop, install\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"printer, repair\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"update\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Multiple Dept\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"update\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Single Dept\"},{\"attribute\":\"kata_kunci\",\"value\":\"update\"},{\"attribute\":\"kata_kunci\",\"value\":\"update\"},{\"attribute\":\"kata_kunci\",\"value\":\"update\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"pc, jaringan\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"laptop, setting\"}],\"conclusion\":\"Urgent\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"repair\"}],\"conclusion\":\"Low\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"email, password\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Single Dept\"},{\"attribute\":\"kata_kunci\",\"value\":\"email, password\"},{\"attribute\":\"kata_kunci\",\"value\":\"email, password\"},{\"attribute\":\"kata_kunci\",\"value\":\"email, password\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"email, password\"},{\"attribute\":\"dept_terdampak\",\"value\":\"Multiple Dept\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"pc, wifi\"}],\"conclusion\":\"Medium\"},{\"conditions\":[{\"attribute\":\"kata_kunci\",\"value\":\"email\"}],\"conclusion\":\"Low\"}]', '88.61', 79, '2025-11-30 07:53:08');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_departemen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departemen`
--

INSERT INTO `departemen` (`id`, `nama_departemen`, `created_at`, `updated_at`) VALUES
(5, 'IT', '2025-11-03 20:26:15', '2025-11-03 20:26:15'),
(6, 'GA', '2025-11-03 20:26:15', '2025-11-19 00:12:30'),
(14, 'EHS', '2025-11-19 00:12:36', '2025-11-19 00:12:36'),
(15, 'FA', '2025-11-19 00:24:54', '2025-11-19 00:24:54'),
(16, 'PUR', '2025-11-19 00:25:02', '2025-11-19 00:25:02'),
(18, 'PROD', '2025-11-30 02:11:07', '2025-11-30 02:11:07');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('gowuSV881U95yjYMumDoGMolOFBHcCyMCqD8u7ao', 32, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ1lDbURZTUxxYVBXYUNxZXBUQ2NncHZKMXJiREd0bmw1MzJJbzNPdyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly9sb2NhbGhvc3QvaGVscGRlc2tfdGlja2V0aW5nX3N5c3RlbS90aWtldCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjMyO30=', 1764573258);

-- --------------------------------------------------------

--
-- Table structure for table `tiket`
--

CREATE TABLE `tiket` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `departemen_id` bigint UNSIGNED NOT NULL,
  `nomor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_masalah` enum('Hardware','Software','Network','Email') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kata_kunci` text COLLATE utf8mb4_unicode_ci,
  `dept_terdampak` enum('Produksi','Non-Produksi') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recommended_urgency_id` bigint UNSIGNED DEFAULT NULL,
  `confidence_score` decimal(3,2) DEFAULT NULL,
  `urgency_id` bigint UNSIGNED DEFAULT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('open','pending','progress','finish','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `tanggal_selesai` timestamp NULL DEFAULT NULL,
  `teknisi_id` bigint UNSIGNED DEFAULT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `solusi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tiket`
--

INSERT INTO `tiket` (`id`, `user_id`, `departemen_id`, `nomor`, `tanggal`, `judul`, `keterangan`, `tipe_masalah`, `kata_kunci`, `dept_terdampak`, `recommended_urgency_id`, `confidence_score`, `urgency_id`, `gambar`, `status`, `tanggal_selesai`, `teknisi_id`, `catatan`, `solusi`, `created_at`, `updated_at`) VALUES
(2, 17, 5, 'U-20251119-001', '2025-11-19 00:23:50', 'Printer', 'Printer paper jam', 'Hardware', 'printer, paper', 'Non-Produksi', 5, '0.70', 5, 'tiket/EnwAGsv5Hbdmvz5JjtUKyCKsvzjLODpQ8Ls5Gq36.jpg', 'pending', '2025-11-20 00:23:50', 14, NULL, NULL, '2025-11-19 00:23:50', '2025-11-19 09:17:05'),
(4, 32, 16, 'U-20251119-002', '2025-11-19 00:46:25', 'Laptop', 'laptop error', 'Hardware', 'laptop, error', 'Non-Produksi', 5, '0.70', 5, 'tiket/rq3XcpPV5xHBbkQbdaVl41EkR6egHWtt65YYD6Wk.jpg', 'pending', '2025-11-20 00:46:25', 31, 'Tiket di-unassign oleh admin pada 30/11/2025 12:08', NULL, '2025-11-19 00:46:25', '2025-11-30 05:09:09'),
(7, 32, 16, 'TKT20251119-001', '2025-11-19 01:03:08', 'Printer', 'Printer di IKN ga bisa', 'Hardware', 'printer, bisa', 'Produksi', 5, '0.70', 5, 'tiket/BupGumTUs1OiVOZQ9ChQwT6XZFjwPhnTwdZevMry.jpg', 'closed', '2025-11-19 10:25:55', 14, NULL, 'bersihin aja', '2025-11-19 01:03:08', '2025-11-19 10:27:42'),
(8, 32, 16, 'TKT20251119-002', '2025-11-19 01:04:08', 'Laptop', 'Laptop mati', 'Hardware', 'laptop, mati', 'Non-Produksi', 5, '0.70', 5, NULL, 'closed', '2025-11-19 10:25:26', 14, NULL, 'cas', '2025-11-19 01:04:08', '2025-11-19 10:27:37'),
(9, 32, 16, 'TKT-20251119-001', '2025-11-19 09:23:48', 'Printer', 'Printer di office macet ga bisa print', 'Hardware', 'printer, office, macet, bisa, print', 'Produksi', 5, '0.70', 5, 'tiket/gYrgkub70tcOjygm3WAlRiAcqs3SK0C0Yo3mEL1B.jpg', 'progress', '2025-11-20 09:23:48', 14, NULL, NULL, '2025-11-19 09:23:48', '2025-11-19 09:38:04');

-- --------------------------------------------------------

--
-- Table structure for table `tiket_training`
--

CREATE TABLE `tiket_training` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal_problem` date NOT NULL,
  `deskripsi_problem` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tipe masalah: Printer, Hardware, Network, dll',
  `sla_target_hrs` int NOT NULL,
  `actual_hrs` int NOT NULL,
  `urgency_level` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Auto mapped dari SLA',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tiket_training`
--

INSERT INTO `tiket_training` (`id`, `tanggal_problem`, `deskripsi_problem`, `kategori`, `sla_target_hrs`, `actual_hrs`, `urgency_level`, `created_at`, `updated_at`) VALUES
(1, '2025-03-10', 'Perbaikan pc di ASM dan IKN Machining', 'Hardware', 6, 1, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(2, '2025-03-13', 'Repair printer LQ di PPC', 'Printer', 6, 1, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(3, '2025-03-19', 'Repair printer LQ di PPC', 'Printer', 6, 1, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(4, '2025-03-21', 'Repair laptop user', 'Hardware', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(5, '2025-03-25', 'Setting Jam digital ET & ASM', 'Configuration', 3, 1, 'Urgent', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(6, '2025-04-08', 'Perbaikan Pc dan Printer di IKN Machining', 'Printer', 6, 1, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(7, '2025-04-09', 'Setting software kamera cognex ASM', 'Software', 3, 2, 'Urgent', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(8, '2025-04-14', 'Setting Jam ASM & ET', 'Configuration', 3, 1, 'Urgent', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(9, '2025-04-17', 'Replacement laptop ENG  Asri', 'Hardware', 24, 6, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(10, '2025-04-17', 'Setting printer QC', 'Printer', 24, 2, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(11, '2025-04-22', 'Replacement Pc warehouse consumable', 'Hardware', 24, 5, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(12, '2025-04-23', 'Repair laptop tidak bisa menyala user PPC', 'Hardware', 6, 2, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(13, '2025-04-23', 'Install Net. Framework', 'Software', 24, 2, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(14, '2025-04-24', 'Install HRP', 'Software', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(15, '2025-04-29', 'Repair printer LQ di PPC', 'Printer', 6, 1, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(16, '2025-04-29', 'Repair Laptop & Printer di MTN', 'Printer', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(17, '2025-05-07', 'Join domain mini PC', 'Hardware', 24, 1, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(18, '2025-05-07', 'Repair software cognex ASM', 'Software', 3, 2, 'Urgent', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(19, '2025-05-19', 'Setting email user FA', 'Software', 6, 1, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(20, '2025-05-19', 'Service printer', 'Printer', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(21, '2025-05-23', 'Set up laptop baru user', 'Hardware', 24, 5, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(22, '2025-05-26', 'Perbaikan internet gudang consumable', 'Network', 6, 2, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(23, '2025-06-02', 'Maintenance printer tinta bocor QC', 'Printer', 24, 4, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(24, '2025-06-03', 'Replace printer di PPC', 'Printer', 6, 2, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(25, '2025-06-09', 'Instalasi mesin scanner di PPC', 'Hardware', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(26, '2025-06-09', 'Migrasi anti virus Cortex', 'Software', 24, 7, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(27, '2025-06-10', 'Migrasi anti virus Cortex', 'Software', 24, 7, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(28, '2025-06-11', 'Migrasi anti virus Cortex', 'Software', 24, 7, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(29, '2025-06-12', 'Migrasi anti virus Cortex', 'Software', 24, 7, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(30, '2025-06-13', 'Install Office new laptop HR', 'Hardware', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(31, '2025-06-16', 'Replacement laptop ENG  Asri', 'Hardware', 24, 4, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(32, '2025-06-24', 'Setting Jam ASM & ET', 'Configuration', 3, 1, 'Urgent', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(33, '2025-06-25', 'Setting Ms.Office ET', 'Software', 3, 1, 'Urgent', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(34, '2025-07-02', 'Replacement PC PPC dan FA', 'Hardware', 24, 5, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(35, '2025-07-15', 'Replacement PC PPC dan FA', 'Hardware', 24, 5, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(36, '2025-07-17', 'Setting email user FA', 'Software', 6, 1, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(37, '2025-07-21', 'Setting Printer dan kabel LAN di MTN Office', 'Printer', 6, 3, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(38, '2025-07-23', 'Repalcement PC QC Incoming', 'Hardware', 24, 5, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(39, '2025-07-25', 'Repair printer', 'Printer', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(40, '2025-08-04', 'Repalcement PC PPC', 'Hardware', 24, 5, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(41, '2025-08-04', 'Update windows 10 all endpoint MII', 'Software', 24, 5, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(42, '2025-08-05', 'Update windows 10 all endpoint MII', 'Software', 24, 5, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(43, '2025-08-06', 'Update windows 10 all endpoint MII', 'Software', 24, 5, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(44, '2025-08-08', 'MTN tidak bisa print LPB', 'Printer', 6, 1, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(45, '2025-08-12', 'Nyeting jaringan di PC Security', 'Hardware', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(46, '2025-08-15', 'Pengecekan cctv & pergantian beberapa switch', 'Network', 6, 3, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(47, '2025-08-18', 'Setting laptop baru untuk manager QC', 'Hardware', 3, 1, 'Urgent', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(48, '2025-08-19', 'Repalcement laptop Ruslani', 'Hardware', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(49, '2025-08-22', 'Deploy printer di security untuk digitalisasi LPB IPB', 'Printer', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(50, '2025-08-25', 'Repair printer QC Kalibrasi', 'Printer', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(51, '2025-08-28', 'Pengecekan dan pergantian switch ASM & ET', 'Network', 6, 3, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(52, '2025-09-02', 'Instalasi AutoCAD Qdojo', 'Software', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(53, '2025-09-02', 'Maintenance printer IKN Produksi', 'Printer', 6, 2, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(54, '2025-09-08', 'Support instalasi Vending Machine MTN', 'Lainnya', 24, 4, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(55, '2025-09-17', 'Support instalasi Vending Machine MTN', 'Lainnya', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(56, '2025-09-25', 'Update Cortex', 'Software', 24, 4, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(57, '2025-09-26', 'Instalasi krimping kabel LAN 5 titik mesin', 'Network', 24, 3, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(58, '2025-09-29', 'Repair windows user FA ibu Peni', 'Software', 36, 24, 'Low', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(59, '2025-10-02', 'Setting Jam ASM & ET', 'Configuration', 3, 1, 'Urgent', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(60, '2025-10-06', 'Repair printer LQ PPC', 'Printer', 6, 1, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(61, '2025-10-27', 'Repalce laptop user FA Pak Sony', 'Hardware', 3, 2, 'Urgent', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(62, '2025-10-30', 'Reset password email user FM Produksi Machining', 'Software', 24, 2, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(63, '2025-10-31', 'Repair printer laser jet ppc', 'Printer', 24, 2, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(64, '2025-11-01', 'Maintenance printer IKN QC', 'Printer', 24, 1, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(65, '2025-11-04', 'Repair printer laset jet PPC', 'Printer', 6, 3, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(66, '2025-11-04', 'Backup data dan replace data user FA', 'Lainnya', 24, 6, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(67, '2025-11-10', 'Update Certificate Menlo', 'Software', 6, 4, 'High', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(68, '2025-11-12', 'Repair printer PPC LaserJet', 'Printer', 24, 4, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(69, '2025-11-13', 'Reset Password Email user ASM Sutikno', 'Software', 24, 2, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(70, '2025-11-13', 'Problem jaringan PC PPC Machining', 'Hardware', 24, 8, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(71, '2025-11-20', 'Problem jaringan PC PPC Machining (converter bermasalah)', 'Hardware', 24, 5, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(72, '2025-11-21', 'Problem jaringan PC PPC Machining', 'Hardware', 24, 2, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(73, '2025-11-25', 'Problem outlook search bar GA User', 'Software', 36, 1, 'Low', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(74, '2025-11-30', 'Problem outlook search bar MTN User', 'Software', 36, 1, 'Low', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(75, '2025-11-30', 'Problem koneksi wifi User PPC', 'Hardware', 24, 1, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(76, '2025-11-30', 'Reset email user', 'Software', 36, 1, 'Low', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(77, '2025-11-28', 'Reset password email & sinkron outlook', 'Software', 36, 1, 'Low', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(78, '2025-11-30', 'Deactive Menlo', 'Lainnya', 36, 1, 'Low', '2025-11-30 07:52:54', '2025-11-30 07:52:54'),
(79, '2025-11-30', 'Repair printer bocor Epson L360', 'Printer', 24, 2, 'Medium', '2025-11-30 07:52:54', '2025-11-30 07:52:54');

-- --------------------------------------------------------

--
-- Table structure for table `urgency`
--

CREATE TABLE `urgency` (
  `id` bigint UNSIGNED NOT NULL,
  `urgency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `urgency`
--

INSERT INTO `urgency` (`id`, `urgency`, `jam`, `created_at`, `updated_at`) VALUES
(4, 'High', 6, '2025-11-05 20:56:44', '2025-11-05 20:56:44'),
(5, 'Medium', 24, '2025-11-05 20:56:58', '2025-11-05 20:56:58'),
(6, 'Low', 36, '2025-11-05 20:57:08', '2025-11-05 20:57:08'),
(7, 'Critical', 3, '2025-11-30 02:12:39', '2025-11-30 02:12:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','administrator','teknisi','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `admin_type` enum('IT','GA','EHS') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `departemen_id` bigint UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nik`, `username`, `nama`, `email`, `no_telepon`, `photo`, `role`, `admin_type`, `departemen_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(12, '1234567890123456', 'admin IT', 'Admin User', 'admin@helpdesk.local', '081234567890', 'users/UYaVQ3LYzjqqRrFaN0j1Sg42rnFMGxpAQV1xeMQ0.jpg', 'admin', NULL, 5, NULL, '$2y$12$RYaZHpfMKBjBXT7h5M2JsOtxJymExFoP8viX16lpQKJ0QdRyIfkae', NULL, '2025-11-03 20:29:26', '2025-11-05 21:22:20'),
(13, '1234567890123457', NULL, 'Administrator User', 'administrator@helpdesk.local', '081234567891', 'users/k9D5BOKXXf9st52Lu79dyiIifSgV6GPBAjvP65Og.jpg', 'administrator', NULL, NULL, NULL, '$2y$12$EOMjJ4Kiu43Fft/62TxVEesjN0HEhCMULyTq8O4E2Jzx.SEug78De', NULL, '2025-11-03 20:29:26', '2025-11-05 06:28:24'),
(14, '1234567890123458', 'Teknisi 1 IT', 'Teknisi Satu', 'khusus.wa.dira.aja@gmail.com', '081234567892', 'users/fD72gAxsNWhCMrPrV9dqOzWxfZqzYG2NRf4yIO7L.jpg', 'teknisi', NULL, 5, NULL, '$2y$12$/aVcru7p01L8Ki2aTZFFA.yYpaDHAaCEtrZwunav5fI9xXBFg7np2', NULL, '2025-11-03 20:29:26', '2025-11-19 09:37:43'),
(17, '1234567890123461', 'User IT', 'User Satu', 'user1@helpdesk.local', '081234567895', NULL, 'user', NULL, 5, NULL, '$2y$12$ii/1JmpR7Z6YKW/eHqCc.ulfL2dEt3ZmUURTrBx6C9Bt02VC9GAmS', NULL, '2025-11-03 20:29:27', '2025-11-30 23:21:20'),
(18, '1234567890123462', 'User GA EHS', 'User Dua', 'user2@helpdesk.local', '081234567896', NULL, 'user', NULL, 6, NULL, '$2y$12$K1hP9viDrPfEZIbVpsM2bu5GzaaXFZASLb9KjQ.5J0Cezdxhoks26', NULL, '2025-11-03 20:29:28', '2025-11-05 21:50:21'),
(19, '1234567890123463', 'User HR', 'User Tiga', 'user3@helpdesk.local', '081234567897', 'users/KJgvfQ6fXgWdR6RnV0IwR8ZSpq4Zwl4FyK0wH9me.jpg', 'user', NULL, 15, NULL, '$2y$12$CHKbuq9ENxMIPGXPkUSYs.UCgtNtG5.WwS4N5RevXkvFRFMagY5tW', NULL, '2025-11-03 20:29:28', '2025-11-30 02:06:01'),
(20, '1234567890123464', 'User PUR', 'User Empat', 'user4@helpdesk.local', '081234567898', NULL, 'user', NULL, NULL, NULL, '$2y$12$eUDxI7T71bzmzPIjUAGxNunTvLdx9W5cHq6F7XbmNnfJG6.D4xghW', NULL, '2025-11-03 20:29:28', '2025-11-05 21:49:14'),
(23, '12345643345677887', 'administrator', 'Super Administrator', 'administrator@gmail.com', NULL, 'users/4QeeLdoKh6z0rPi6G6Ze07ZchPqgwpSiaIyqQDyw.jpg', 'administrator', NULL, NULL, NULL, '$2y$12$HXMmIESHkk/nedqrX1mtjex.qXlewbvCLSdZ/qrXPDDKuiPf5qn3a', NULL, '2025-11-04 01:02:15', '2025-11-30 23:17:17'),
(25, '10192', 'super admin', 'super admin', 'superadmin@gmail.com', NULL, 'users/lj7t8RI9tkqfyxEo2EpK30HiV9f1R9iyjAlOLEU3.jpg', 'administrator', NULL, NULL, NULL, '$2y$12$jmfHfCYO5muSo.Jd1Tob.uIQVqcH6Y8WxbUYLFzmXxgIO7G1x.2SS', NULL, '2025-11-05 06:32:05', '2025-11-29 08:58:01'),
(26, '4748', 'admin dua', 'admin 2', 'admin2@gmail.com', NULL, 'users/w6y4pEabXCrMIId3oSlkpbf0Wy0XZiP50RypOiDQ.jpg', 'admin', NULL, 6, NULL, '$2y$12$hSZSmxRtYyQv8oZeLG.R5OR2BfnGU2NNL3/8VdaIIW6RuSL71CFFC', NULL, '2025-11-05 06:37:15', '2025-11-05 21:22:04'),
(28, '27139', 'admin HR', 'admin hr', 'adminhr@gmail.com', NULL, 'users/NC6zGVMQOpenP0BNkORl4K9dfcK55WQU7KeBeWb1.jpg', 'admin', NULL, NULL, NULL, '$2y$12$Dzqm4/V6EqTDxTiCXI9fs.x4Bhp/B3HAtW.HNQ19/lVEvFE6Ebohe', NULL, '2025-11-05 23:36:35', '2025-11-05 23:36:35'),
(30, '1234567890', 'admin dira', 'dira admin', 'sadirazahra.aydin@gmail.com', NULL, 'users/1Dr3wUEVhQchtd9b7dhTnAbjEj6RYNDRY3mVGHPV.jpg', 'admin', NULL, 5, NULL, '$2y$12$QvwLyb8Jw6ZM64bknhfaleuzH.Fkg0TSm3x775ZqTV/Y1pBS.CoTG', NULL, '2025-11-19 00:13:46', '2025-11-30 00:56:22'),
(31, '37383939', 'Teknisi IT 2', 'Teknisi 2 IT', 'zahraydin01@gmail.com', NULL, 'users/tF6HQXu4IRBYFJOaN202hJyxVrgrd6C1EwLcvHnY.jpg', 'teknisi', NULL, 5, NULL, '$2y$12$MxwNQBFscprsAPzP2wZJnOM3pDzQXYGTEaqT.aifI9PUTADzC2HRS', NULL, '2025-11-19 00:16:08', '2025-11-30 00:57:37'),
(32, '372929', 'user purchasing', 'user pur', 'userpur@gmail.com', NULL, 'users/BbLJYnlX06NQCpexLdJQ6QfuaUo3nOUagPop6BZy.jpg', 'user', NULL, 16, NULL, '$2y$12$44lNeLG9g5A1PfYA1TLs7.U99qwQnYe2Mqt8EleQai.QBs968QUEa', NULL, '2025-11-19 00:25:56', '2025-11-19 00:25:56'),
(33, '4213', 'super admin 2', 'super admin 2', 'superadmin2@gmail.com', NULL, 'users/GdCk9wWRgqLuMT3cd6x8F8FuwNSWuPpWaJfOfi7r.jpg', 'administrator', NULL, NULL, NULL, '$2y$12$VBWKgqlBabam7ZqQSaiWre38j2aQxZMb5QZGbC8YKw1frXsXz9irm', NULL, '2025-11-30 00:09:33', '2025-11-30 00:31:49'),
(34, '345662', 'user fa', 'user fa', 'userfa@gmail.com', NULL, NULL, 'user', NULL, 15, NULL, '$2y$12$qgS4ChyWAefR01miXu118.RPOytNcOH307M4nraUEeN5vWGQkyTf.', NULL, '2025-11-30 23:28:27', '2025-11-30 23:28:27'),
(35, '28192', 'user production', 'user production ya testing', 'userpdca@gmail.com', NULL, 'users/nhD4dwI2VGXMCja68gCuwr1DN0HCWTCMljGtFvBM.jpg', 'user', NULL, 18, NULL, '$2y$12$uyId9jgTMVTgnyz5bJ5EwOl5IyuaUWbR6Dg48bTjF5UoSDIAY0XGa', NULL, '2025-11-30 23:30:49', '2025-11-30 23:54:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `c45_evaluation`
--
ALTER TABLE `c45_evaluation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `recommended_urgency_id` (`recommended_urgency_id`),
  ADD KEY `actual_urgency_id` (`actual_urgency_id`);

--
-- Indexes for table `c45_model`
--
ALTER TABLE `c45_model`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tiket`
--
ALTER TABLE `tiket`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tiket_nomor_unique` (`nomor`),
  ADD KEY `tiket_user_id_foreign` (`user_id`),
  ADD KEY `tiket_departemen_id_foreign` (`departemen_id`),
  ADD KEY `tiket_urgency_id_foreign` (`urgency_id`),
  ADD KEY `tiket_teknisi_id_foreign` (`teknisi_id`);

--
-- Indexes for table `tiket_training`
--
ALTER TABLE `tiket_training`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kategori` (`kategori`),
  ADD KEY `idx_urgency` (`urgency_level`);

--
-- Indexes for table `urgency`
--
ALTER TABLE `urgency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nik_unique` (`nik`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `users_departemen_id_foreign` (`departemen_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `c45_evaluation`
--
ALTER TABLE `c45_evaluation`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `c45_model`
--
ALTER TABLE `c45_model`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `departemen`
--
ALTER TABLE `departemen`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tiket`
--
ALTER TABLE `tiket`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tiket_training`
--
ALTER TABLE `tiket_training`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `urgency`
--
ALTER TABLE `urgency`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `c45_evaluation`
--
ALTER TABLE `c45_evaluation`
  ADD CONSTRAINT `c45_evaluation_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tiket` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `c45_evaluation_ibfk_2` FOREIGN KEY (`recommended_urgency_id`) REFERENCES `urgency` (`id`),
  ADD CONSTRAINT `c45_evaluation_ibfk_3` FOREIGN KEY (`actual_urgency_id`) REFERENCES `urgency` (`id`);

--
-- Constraints for table `tiket`
--
ALTER TABLE `tiket`
  ADD CONSTRAINT `tiket_departemen_id_foreign` FOREIGN KEY (`departemen_id`) REFERENCES `departemen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tiket_teknisi_id_foreign` FOREIGN KEY (`teknisi_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tiket_urgency_id_foreign` FOREIGN KEY (`urgency_id`) REFERENCES `urgency` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tiket_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_departemen_id_foreign` FOREIGN KEY (`departemen_id`) REFERENCES `departemen` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
