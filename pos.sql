-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Feb 2026 pada 14.24
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `item_penerimaan_barangs`
--

CREATE TABLE `item_penerimaan_barangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomor_penerimaan` varchar(255) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `item_penerimaan_barangs`
--

INSERT INTO `item_penerimaan_barangs` (`id`, `nomor_penerimaan`, `nama_produk`, `qty`, `harga_beli`, `sub_total`, `created_at`, `updated_at`) VALUES
(1, 'PBR-2101260001', 'Sabun wajah', 10, 100000, 1000000, '2026-01-21 01:22:21', '2026-01-21 01:22:21'),
(2, 'PBR-2701260002', 'Sabun wajah wanita', 10, 20000, 200000, '2026-01-26 21:45:14', '2026-01-26 21:45:14'),
(3, 'PBR-2801260003', 'sabun sunlight', 20, 20000, 400000, '2026-01-28 00:15:47', '2026-01-28 00:15:47'),
(4, 'PBR-1102260004', 'Kaos kaki smkn 1 surabaya', 1, 10000, 10000, '2026-02-10 23:41:22', '2026-02-10 23:41:22'),
(5, 'IN-20260217-0001', 'produk baju', 1, 10000, 10000, '2026-02-16 20:42:00', '2026-02-16 20:42:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `item_pengeluaran_barangs`
--

CREATE TABLE `item_pengeluaran_barangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengeluaran_barang_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_jual` decimal(15,2) NOT NULL,
  `sub_total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `diskon_item` decimal(12,2) NOT NULL DEFAULT 0.00,
  `diskon_persen` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `item_pengeluaran_barangs`
--

INSERT INTO `item_pengeluaran_barangs` (`id`, `pengeluaran_barang_id`, `product_id`, `jumlah`, `harga_jual`, `sub_total`, `created_at`, `updated_at`, `diskon_item`, `diskon_persen`) VALUES
(1, 1, 4, 10, 200000.00, 2000000.00, '2026-01-26 20:22:56', '2026-01-26 20:22:56', 0.00, 0),
(2, 2, 7, 10, 200000.00, 2000000.00, '2026-01-26 20:24:19', '2026-01-26 20:24:19', 0.00, 0),
(3, 3, 6, 20, 27000.00, 540000.00, '2026-01-26 21:46:08', '2026-01-26 21:46:08', 0.00, 0),
(4, 4, 8, 1, 200000.00, 200000.00, '2026-01-27 20:21:27', '2026-01-27 20:21:27', 0.00, 0),
(5, 5, 9, 20, 20000.00, 400000.00, '2026-01-28 00:16:38', '2026-01-28 00:16:38', 0.00, 0),
(6, 6, 5, 1, 70000.00, 70000.00, '2026-02-04 19:50:35', '2026-02-04 19:50:35', 0.00, 0),
(7, 6, 6, 1, 27000.00, 27000.00, '2026-02-04 19:50:35', '2026-02-04 19:50:35', 0.00, 0),
(8, 7, 5, 1, 70000.00, 70000.00, '2026-02-10 01:21:35', '2026-02-10 01:21:35', 0.00, 0),
(9, 7, 6, 1, 27000.00, 27000.00, '2026-02-10 01:21:35', '2026-02-10 01:21:35', 0.00, 0),
(10, 7, 9, 1, 20000.00, 20000.00, '2026-02-10 01:21:35', '2026-02-10 01:21:35', 0.00, 0),
(11, 8, 3, 1, 100000.00, 100000.00, '2026-02-10 02:21:47', '2026-02-10 02:21:47', 0.00, 0),
(12, 9, 3, 1, 100000.00, 100000.00, '2026-02-10 20:19:35', '2026-02-10 20:19:35', 0.00, 0),
(13, 10, 6, 1, 27000.00, 27000.00, '2026-02-10 20:20:07', '2026-02-10 20:20:07', 0.00, 0),
(14, 11, 7, 1, 200000.00, 200000.00, '2026-02-10 21:09:54', '2026-02-10 21:09:54', 0.00, 0),
(15, 12, 9, 2, 20000.00, 40000.00, '2026-02-10 21:27:40', '2026-02-10 21:27:40', 0.00, 0),
(16, 13, 3, 1, 100000.00, 100000.00, '2026-02-10 23:38:43', '2026-02-10 23:38:43', 0.00, 0),
(17, 14, 3, 1, 100000.00, 100000.00, '2026-02-16 20:52:19', '2026-02-16 20:52:19', 0.00, 0),
(18, 14, 4, 1, 200000.00, 200000.00, '2026-02-16 20:52:19', '2026-02-16 20:52:19', 0.00, 0),
(19, 14, 7, 1, 200000.00, 200000.00, '2026-02-16 20:52:19', '2026-02-16 20:52:19', 0.00, 0),
(20, 15, 3, 4, 100000.00, 400000.00, '2026-02-16 21:58:57', '2026-02-16 21:58:57', 0.00, 0),
(21, 16, 5, 1, 70000.00, 70000.00, '2026-02-16 22:00:57', '2026-02-16 22:00:57', 0.00, 0),
(22, 17, 6, 1, 27000.00, 27000.00, '2026-02-16 22:05:21', '2026-02-16 22:05:21', 0.00, 0),
(23, 18, 4, 1, 200000.00, 200000.00, '2026-02-16 22:08:41', '2026-02-16 22:08:41', 0.00, 0),
(24, 19, 8, 2, 200000.00, 400000.00, '2026-02-16 22:14:48', '2026-02-16 22:14:48', 0.00, 0),
(25, 20, 5, 1, 70000.00, 70000.00, '2026-02-16 22:33:06', '2026-02-16 22:33:06', 0.00, 0),
(26, 21, 6, 1, 27000.00, 27000.00, '2026-02-16 22:41:30', '2026-02-16 22:41:30', 0.00, 0),
(27, 22, 5, 1, 70000.00, 70000.00, '2026-02-16 22:44:31', '2026-02-16 22:44:31', 0.00, 0),
(28, 23, 9, 2, 20000.00, 40000.00, '2026-02-16 22:45:39', '2026-02-16 22:45:39', 0.00, 0),
(29, 30, 4, 2, 200000.00, 400000.00, '2026-02-16 23:15:12', '2026-02-16 23:15:12', 0.00, 0),
(30, 31, 4, 1, 200000.00, 200000.00, '2026-02-16 23:16:19', '2026-02-16 23:16:19', 0.00, 0),
(31, 32, 3, 2, 100000.00, 200000.00, '2026-02-16 23:20:07', '2026-02-16 23:20:07', 0.00, 0),
(32, 45, 4, 5, 200000.00, 800000.00, '2026-02-24 01:56:32', '2026-02-24 01:56:32', 0.00, 0),
(33, 46, 3, 1, 100000.00, 90000.00, '2026-02-24 06:11:30', '2026-02-24 06:11:30', 0.00, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategoris`
--

CREATE TABLE `kategoris` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategoris`
--

INSERT INTO `kategoris` (`id`, `nama_kategori`, `slug`, `deskripsi`, `created_at`, `updated_at`) VALUES
(11, 'Kaos Kaki', 'kaos-kaki', 'Kaos kaki ini serbaguna', '2026-01-20 21:01:13', '2026-01-20 21:01:13'),
(12, 'Kaos Baju', 'kaos-baju', 'ini kaos baju serbaguna', '2026-01-20 21:01:34', '2026-01-20 21:01:34'),
(13, 'Sabun wajah', 'sabun-wajah', 'sabun wajah alaami', '2026-01-20 21:13:00', '2026-01-20 21:13:00'),
(14, 'sabun cuci', 'sabun-cuci', 'sabun cuci serbaguna', '2026-01-28 00:13:32', '2026-01-28 00:13:32'),
(15, 'Kecap', 'kecap', 'Bumbu dapur kecap', '2026-02-24 05:54:38', '2026-02-24 05:54:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_06_050033_create_kategoris_table', 2),
(5, '2026_01_09_074856_create_products_table', 3),
(6, '2026_01_21_033237_create_penerimaan_barangs_table', 4),
(7, '2026_01_21_034658_create_item_penerimaan_barangs_table', 4),
(8, '2026_01_21_143820_pengeluaran_barangs', 5),
(9, '2026_01_21_145100_create_item_pengeluaran_barangs_table', 5),
(10, '2026_01_28_024925_add_role_to_users_table', 6),
(11, '2026_02_10_083543_add_image_to_products_table', 7),
(14, '2026_02_11_032719_create_transaksis_table', 8),
(15, '2026_02_11_032723_create_transaksi_items_table', 8),
(16, '2026_02_17_042436_add_diskon_dan_pajak_to_pengeluaran_barangs_table', 9),
(17, '2026_02_17_042751_add_diskon_item_to_item_pengeluaran_barangs_table', 10),
(18, '2026_02_17_060248_fix_kolom_pengeluaran_barangs', 11),
(19, '2026_02_24_041155_add_diskon_persen_to_item_pengeluaran_barangs', 12);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerimaan_barangs`
--

CREATE TABLE `penerimaan_barangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomor_penerimaan` varchar(255) NOT NULL,
  `nomor_faktur` varchar(255) NOT NULL,
  `distributor` varchar(255) NOT NULL,
  `petugas_penerima` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penerimaan_barangs`
--

INSERT INTO `penerimaan_barangs` (`id`, `nomor_penerimaan`, `nomor_faktur`, `distributor`, `petugas_penerima`, `created_at`, `updated_at`) VALUES
(1, 'PBR-2101260001', 'sk-100', 'PT.Firr', 'admin', '2026-01-21 01:22:21', '2026-01-21 01:22:21'),
(2, 'PBR-2701260002', 'sk-100', 'sigma male', 'admin', '2026-01-26 21:45:14', '2026-01-26 21:45:14'),
(3, 'PBR-2801260003', 'skj-200', 'dilon alif', 'admin', '2026-01-28 00:15:47', '2026-01-28 00:15:47'),
(4, 'PBR-1102260004', 'INV-827', 'Arel siga', 'kasir', '2026-02-10 23:41:22', '2026-02-10 23:41:22'),
(5, 'IN-20260217-0001', 'IN-20260217-0001', 'firly', 'kasir', '2026-02-16 20:42:00', '2026-02-16 20:42:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran_barangs`
--

CREATE TABLE `pengeluaran_barangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomor_pengeluaran` varchar(255) NOT NULL,
  `nama_petugas` varchar(255) NOT NULL,
  `bayar` int(11) NOT NULL,
  `kembalian` int(11) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `diskon_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `pajak` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `diskon_item` decimal(12,2) NOT NULL DEFAULT 0.00,
  `diskon_transaksi` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengeluaran_barangs`
--

INSERT INTO `pengeluaran_barangs` (`id`, `nomor_pengeluaran`, `nama_petugas`, `bayar`, `kembalian`, `metode_pembayaran`, `total_harga`, `created_at`, `updated_at`, `diskon_total`, `pajak`, `sub_total`, `subtotal`, `diskon_item`, `diskon_transaksi`) VALUES
(1, 'trx-27012600001', 'admin', 10000000, 8000000, '', 2000000, '2026-01-26 20:22:56', '2026-01-26 20:22:56', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(2, 'trx-27012600002', 'admin', 10000000, 8000000, '', 2000000, '2026-01-26 20:24:19', '2026-01-26 20:24:19', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(3, 'trx-27012600003', 'admin', 900000, 360000, '', 540000, '2026-01-26 21:46:08', '2026-01-26 21:46:08', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(4, 'trx-28012600004', 'admin', 300000, 100000, '', 200000, '2026-01-27 20:21:27', '2026-01-27 20:21:27', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(5, 'trx-28012600005', 'admin', 500000, 100000, '', 400000, '2026-01-28 00:16:38', '2026-01-28 00:16:38', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(6, 'trx-05022600006', 'kasir', 100000, 3000, '', 97000, '2026-02-04 19:50:35', '2026-02-04 19:50:35', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(7, 'trx-10022600007', 'kasir', 120000, 3000, '', 117000, '2026-02-10 01:21:35', '2026-02-10 01:21:35', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(8, 'trx-10022600008', 'kasir', 200000, 100000, '', 100000, '2026-02-10 02:21:47', '2026-02-10 02:21:47', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(9, 'trx-11022600009', 'kasir', 200000, 100000, '', 100000, '2026-02-10 20:19:35', '2026-02-10 20:19:35', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(10, 'trx-11022600010', 'kasir', 50000, 23000, '', 27000, '2026-02-10 20:20:07', '2026-02-10 20:20:07', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(11, 'trx-11022600011', 'kasir', 220000, 20000, '', 200000, '2026-02-10 21:09:54', '2026-02-10 21:09:54', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(12, 'trx-11022600012', 'kasir', 50000, 10000, '', 40000, '2026-02-10 21:27:40', '2026-02-10 21:27:40', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(13, 'trx-11022600013', 'kasir', 100000, 0, '', 100000, '2026-02-10 23:38:43', '2026-02-10 23:38:43', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(14, 'trx-17022600014', 'kasir', 1000000, 500000, '', 500000, '2026-02-16 20:52:19', '2026-02-16 20:52:19', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(15, 'TRX-17022600015', 'kasir', 500000, 100000, '', 400000, '2026-02-16 21:58:57', '2026-02-16 21:58:57', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(16, 'TRX-17022600016', 'kasir', 100000, 30000, '', 70000, '2026-02-16 22:00:57', '2026-02-16 22:00:57', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(17, 'TRX-17022600017', 'kasir', 30000, 3000, '', 27000, '2026-02-16 22:05:21', '2026-02-16 22:05:21', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(18, 'TRX-17022600018', 'kasir', 200000, 0, '', 200000, '2026-02-16 22:08:41', '2026-02-16 22:08:41', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(19, 'TRX-17022600019', 'kasir', 400000, 0, '', 400000, '2026-02-16 22:14:48', '2026-02-16 22:14:48', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(20, 'TRX-17022600020', 'kasir', 70000, 0, '', 70000, '2026-02-16 22:33:06', '2026-02-16 22:33:06', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(21, 'TRX-17022600021', 'kasir', 30000, 3000, '', 27000, '2026-02-16 22:41:30', '2026-02-16 22:41:30', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(22, 'TRX-17022600022', 'kasir', 70000, 0, '', 70000, '2026-02-16 22:44:31', '2026-02-16 22:44:31', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(23, 'TRX-17022600023', 'kasir', 50000, 10000, '', 40000, '2026-02-16 22:45:39', '2026-02-16 22:45:39', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(24, 'TRX-17022600024', 'kasir', 400000, 400000, '', 0, '2026-02-16 23:11:37', '2026-02-16 23:11:37', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(25, 'TRX-17022600025', 'kasir', 400000, 400000, '', 0, '2026-02-16 23:12:22', '2026-02-16 23:12:22', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(26, 'TRX-17022600026', 'kasir', 400000, 400000, '', 0, '2026-02-16 23:12:43', '2026-02-16 23:12:43', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(27, 'TRX-17022600027', 'kasir', 400000, 400000, '', 0, '2026-02-16 23:13:08', '2026-02-16 23:13:08', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(28, 'TRX-17022600028', 'kasir', 400000, 400000, '', 0, '2026-02-16 23:13:29', '2026-02-16 23:13:29', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(29, 'TRX-17022600029', 'kasir', 400000, 400000, '', 0, '2026-02-16 23:14:03', '2026-02-16 23:14:03', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(30, 'TRX-17022600030', 'kasir', 400000, 0, '', 400000, '2026-02-16 23:15:12', '2026-02-16 23:15:12', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(31, 'TRX-17022600031', 'kasir', 200000, 0, '', 200000, '2026-02-16 23:16:19', '2026-02-16 23:16:19', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(32, 'TRX-17022600032', 'kasir', 200000, 0, '', 200000, '2026-02-16 23:20:07', '2026-02-16 23:20:07', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(33, 'TRX-24022600033', 'kasir', 30000, 4137, 'cash', 25863, '2026-02-23 23:27:02', '2026-02-23 23:27:02', 0.00, 2563.00, 0.00, 24300.00, 0.00, 1000.00),
(34, 'TRX-24022600034', 'kasir', 30000, 4137, 'cash', 25863, '2026-02-23 23:27:07', '2026-02-23 23:27:07', 0.00, 2563.00, 0.00, 24300.00, 0.00, 1000.00),
(35, 'TRX-24022600035', 'kasir', 30000, 4137, 'cash', 25863, '2026-02-23 23:31:55', '2026-02-23 23:31:55', 0.00, 2563.00, 0.00, 24300.00, 0.00, 1000.00),
(36, 'TRX-24022600036', 'kasir', 100000, 32734, 'cash', 67266, '2026-02-23 23:32:39', '2026-02-23 23:32:39', 0.00, 6666.00, 0.00, 61600.00, 0.00, 1000.00),
(37, 'TRX-24022600037', 'kasir', 30000, 14127, 'cash', 15873, '2026-02-23 23:58:41', '2026-02-23 23:58:41', 0.00, 1573.00, 0.00, 24300.00, 0.00, 10000.00),
(38, 'TRX-24022600038', 'kasir', 60000, 1170, 'cash', 58830, '2026-02-24 00:06:45', '2026-02-24 00:06:45', 0.00, 5830.00, 0.00, 63000.00, 0.00, 10000.00),
(39, 'TRX-24022600039', 'kasir', 150000, 35448, 'cash', 114552, '2026-02-24 00:08:21', '2026-02-24 00:08:21', 0.00, 11352.00, 0.00, 123200.00, 0.00, 20000.00),
(40, 'TRX-24022600040', 'kasir', 60000, 1170, 'cash', 58830, '2026-02-24 00:31:02', '2026-02-24 00:31:02', 0.00, 5830.00, 0.00, 63000.00, 0.00, 10000.00),
(41, 'TRX-24022600041', 'kasir', 100000, 13420, 'cash', 86580, '2026-02-24 00:33:48', '2026-02-24 00:33:48', 0.00, 8580.00, 0.00, 88000.00, 0.00, 10000.00),
(42, 'TRX-24022600042', 'kasir', 200000, 11300, 'cash', 188700, '2026-02-24 00:38:27', '2026-02-24 00:38:27', 0.00, 18700.00, 0.00, 180000.00, 0.00, 10000.00),
(43, 'TRX-24022600043', 'kasir', 65000, 6170, 'cash', 58830, '2026-02-24 00:52:31', '2026-02-24 00:52:31', 0.00, 5830.00, 0.00, 63000.00, 0.00, 10000.00),
(44, 'TRX-24022600044', 'kasir', 65000, 6170, 'cash', 58830, '2026-02-24 00:52:41', '2026-02-24 00:52:41', 0.00, 5830.00, 0.00, 63000.00, 0.00, 10000.00),
(45, 'TRX-24022600045', 'kasir', 1000000, 134200, 'cash', 865800, '2026-02-24 01:56:32', '2026-02-24 01:56:32', 0.00, 85800.00, 0.00, 800000.00, 0.00, 20000.00),
(46, 'TRX-24022600046', 'kasir', 100000, 22300, 'cash', 77700, '2026-02-24 06:11:30', '2026-02-24 06:11:30', 0.00, 7700.00, 0.00, 90000.00, 0.00, 20000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kategori_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL COMMENT 'Stock Keeping Unit / kode produk',
  `image` varchar(255) DEFAULT NULL,
  `harga_jual` int(11) NOT NULL,
  `harga_beli_pokok` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `stok_minimal` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `kategori_id`, `nama_produk`, `sku`, `image`, `harga_jual`, `harga_beli_pokok`, `stok`, `stok_minimal`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 12, 'kaos olahraga', 'SKU-00001', 'products/PJjJvZQJtT6iYenDyQetrfYHShXyNPoG5cePFdHC.jpg', 100000, 10000, 197, 20, 1, '2026-01-20 21:49:30', '2026-02-24 06:11:30'),
(4, 11, 'Kaos kaki smkn 1 surabaya', 'SKU-00004', 'products/kbaDyIGd2BDUJXssXRFV6jYiPlVEMdw13PPGTQdd.jpg', 200000, 100000, 80, 10, 1, '2026-01-22 19:58:55', '2026-02-24 01:56:32'),
(5, 12, 'produk baju', 'SKU-00005', 'products/7ImM5H9IqN8X7XP8I9QxdNiEYh673llZalbWRpjy.jpg', 70000, 10000, 115, 21, 1, '2026-01-22 20:28:52', '2026-02-16 22:44:31'),
(6, 11, 'sigma shock', 'SKU-00006', NULL, 27000, 270000, 95, 20, 1, '2026-01-22 20:33:53', '2026-02-16 22:41:30'),
(7, 12, 'Baju Mbappe kura-kura', 'SKU-00007', NULL, 200000, 20000, 188, 100, 1, '2026-01-22 20:34:30', '2026-02-16 20:52:19'),
(8, 13, 'Sabun wajah wanita', 'SKU-00008', NULL, 200000, 20000, 2007, 100, 1, '2026-01-22 20:35:01', '2026-02-16 22:14:48'),
(9, 14, 'sabun sunlight', 'SKU-00009', NULL, 20000, 100000, 95, 10, 1, '2026-01-28 00:14:32', '2026-02-16 22:45:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3N3j74Aws1rIUT0KWYWu0lAEZPV45yBsBcsD4PL8', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibVZzZVdsdmJEZE1XRGV2MG03Zmd0YklhS0hIU2xZOFJHR2lMQnppOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9rYXNpciI7czo1OiJyb3V0ZSI7czoxMToia2FzaXIuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1771924513),
('jklK0X8MOAUP9amh97CPDPFGfE22KucTtnllDT5M', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTlFkNWNWWkx0RHA0VW1xQlNKblBFM1dicW5GU2IwYWpMcVljRklUZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771918188),
('QhoiBhu8tZ3OfKd5V4XikphYGtbZq0UYkXWgq1B3', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS2FmRXlrNUJrYm45UW9tUVBWUkhJVjhQbXN5MU5wZjR2aTFQWlV0MyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9rYXNpciI7czo1OiJyb3V0ZSI7czoxMToia2FzaXIuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1771938691);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksis`
--

CREATE TABLE `transaksis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kasir` varchar(255) DEFAULT NULL,
  `total` int(11) NOT NULL,
  `bayar` int(11) NOT NULL,
  `kembali` int(11) NOT NULL,
  `payment_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_items`
--

CREATE TABLE `transaksi_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaksi_id` bigint(20) UNSIGNED NOT NULL,
  `produk_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@admin.com', '2025-12-30 00:44:54', '$2y$12$LYrm46sgbl8DDrLB3Ad8xOGk94ZmbIYOY1lACgqCqqCSSLkiiA.H.', 'admin', 'GH4jAuhfhtKSc37KCjc1mCyd860zlpES4Dq5A3yusxucD2nu9EMWWr03soqA', '2025-12-30 00:44:55', '2025-12-30 00:44:55'),
(2, 'kasir', 'kasir@kasir.com', NULL, '$2y$12$Ob6M0LKx.IAFMX3U0GYvzu2w1AlLGmNV5jxia9LXnO.tg2ol7QNq6', 'kasir', NULL, '2026-01-27 19:40:44', '2026-02-04 18:28:56'),
(3, 'user', 'user@domain.com', NULL, '$2y$12$aJPyQltRcXN/PM60uHahv.NqfvLgCwcq5h9RKrlg275dMTiiD6o0K', 'admin', NULL, '2026-01-28 00:12:51', '2026-01-28 00:13:01'),
(4, 'firly', 'firly@kasir.com', NULL, '$2y$12$JRBugNHj.Zvu2vm/hBfoienURdHJNmjVB0IG8xKicDcu04sICDi9K', 'admin', NULL, '2026-02-10 23:58:02', '2026-02-10 23:58:09');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `item_penerimaan_barangs`
--
ALTER TABLE `item_penerimaan_barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `item_pengeluaran_barangs`
--
ALTER TABLE `item_pengeluaran_barangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_pengeluaran_barangs_pengeluaran_barang_id_foreign` (`pengeluaran_barang_id`),
  ADD KEY `item_pengeluaran_barangs_product_id_foreign` (`product_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategoris`
--
ALTER TABLE `kategoris`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `penerimaan_barangs`
--
ALTER TABLE `penerimaan_barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengeluaran_barangs`
--
ALTER TABLE `pengeluaran_barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_kategori_id_foreign` (`kategori_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `transaksis`
--
ALTER TABLE `transaksis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi_items`
--
ALTER TABLE `transaksi_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_items_transaksi_id_foreign` (`transaksi_id`),
  ADD KEY `transaksi_items_produk_id_foreign` (`produk_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `item_penerimaan_barangs`
--
ALTER TABLE `item_penerimaan_barangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `item_pengeluaran_barangs`
--
ALTER TABLE `item_pengeluaran_barangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategoris`
--
ALTER TABLE `kategoris`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `penerimaan_barangs`
--
ALTER TABLE `penerimaan_barangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran_barangs`
--
ALTER TABLE `pengeluaran_barangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `transaksis`
--
ALTER TABLE `transaksis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaksi_items`
--
ALTER TABLE `transaksi_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `item_pengeluaran_barangs`
--
ALTER TABLE `item_pengeluaran_barangs`
  ADD CONSTRAINT `item_pengeluaran_barangs_pengeluaran_barang_id_foreign` FOREIGN KEY (`pengeluaran_barang_id`) REFERENCES `pengeluaran_barangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_pengeluaran_barangs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategoris` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `transaksi_items`
--
ALTER TABLE `transaksi_items`
  ADD CONSTRAINT `transaksi_items_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `transaksi_items_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
