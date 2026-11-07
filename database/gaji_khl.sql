-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 07 Nov 2026 pada 03.03
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
-- Database: `gaji_khl`
--
CREATE DATABASE IF NOT EXISTS `gaji_khl` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gaji_khl`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `account_credentials`
--

CREATE TABLE `account_credentials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_akun` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `attendance_devices`
--

CREATE TABLE `attendance_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `firmware` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `attendance_logs`
--

CREATE TABLE `attendance_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_sn` varchar(255) DEFAULT NULL,
  `pin` varchar(255) NOT NULL,
  `punched_at` datetime NOT NULL,
  `status` int(11) DEFAULT NULL,
  `verify` int(11) DEFAULT NULL,
  `work_code` varchar(255) DEFAULT NULL,
  `raw_data` text DEFAULT NULL,
  `event_hash` varchar(64) NOT NULL,
  `source` varchar(20) NOT NULL DEFAULT 'adms',
  `source_ip` varchar(45) DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `borongan_employees`
--

CREATE TABLE `borongan_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_id` varchar(255) NOT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `bagian` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Struktur dari tabel `checklogs`
--

CREATE TABLE `checklogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `borongan_employee_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `scan_masuk` time DEFAULT NULL,
  `scan_pulang` time DEFAULT NULL,
  `jam_istirahat` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_jam_kerja` decimal(8,2) NOT NULL DEFAULT 0.00,
  `hourly_rate` decimal(15,2) NOT NULL DEFAULT 18000.00,
  `gaji` decimal(15,2) NOT NULL DEFAULT 0.00,
  `salary_archive_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `contracts`
--

CREATE TABLE `contracts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `nomor_kontrak` varchar(255) NOT NULL,
  `jenis_kontrak` varchar(255) NOT NULL,
  `bagian` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_berakhir` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'aktif',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `kontrak_ke` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `daily_salaries`
--

CREATE TABLE `daily_salaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `periode_mulai` date NOT NULL,
  `periode_selesai` date NOT NULL,
  `jumlah_hari_kerja` decimal(5,2) NOT NULL DEFAULT 0.00,
  `upah_harian` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_upah_harian` decimal(15,2) NOT NULL DEFAULT 0.00,
  `lembur_biasa` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tarif_lembur_biasa` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_lembur_biasa` decimal(15,2) NOT NULL DEFAULT 0.00,
  `lembur_hari_libur` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tarif_lembur_hari_libur` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_lembur_hari_libur` decimal(15,2) NOT NULL DEFAULT 0.00,
  `lembur_um` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tarif_lembur_um` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_lembur_um` decimal(15,2) NOT NULL DEFAULT 0.00,
  `lain_lain` decimal(15,2) NOT NULL DEFAULT 0.00,
  `potongan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_gaji` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `daily_work_schedules`
--

CREATE TABLE `daily_work_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `penempatan` varchar(255) DEFAULT NULL,
  `bagian` varchar(255) DEFAULT NULL,
  `work_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fingerprint_pin` varchar(255) DEFAULT NULL,
  `no_induk_karyawan` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `bagian` varchar(255) DEFAULT NULL,
  `penempatan` varchar(255) DEFAULT NULL,
  `pendidikan` varchar(255) DEFAULT NULL,
  `status_pernikahan` varchar(255) DEFAULT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat_domisili` text DEFAULT NULL,
  `provinsi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kabupaten_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kecamatan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `desa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rt` varchar(10) DEFAULT NULL,
  `rw` varchar(10) DEFAULT NULL,
  `detail_alamat` text DEFAULT NULL,
  `no_ktp` varchar(255) DEFAULT NULL,
  `pas_foto` varchar(255) DEFAULT NULL,
  `mulai_kerja` date DEFAULT NULL,
  `tanggal_kontrak` date DEFAULT NULL,
  `selesai_kontrak` date DEFAULT NULL,
  `kontrak_ke` int(10) UNSIGNED DEFAULT NULL,
  `foto_kk` varchar(255) DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `foto_biodata` varchar(255) DEFAULT NULL,
  `jumlah_sp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `jabatan` varchar(255) DEFAULT NULL,
  `jenis_karyawan` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL,
  `surat_kontrak` varchar(255) DEFAULT NULL,
  `npwp` varchar(30) DEFAULT NULL,
  `bpjs_tk_terdaftar` tinyint(1) NOT NULL DEFAULT 0,
  `no_bpjs_tk` varchar(50) DEFAULT NULL,
  `program_bpjs_tk` varchar(100) DEFAULT NULL,
  `bpjs_kesehatan_terdaftar` tinyint(1) NOT NULL DEFAULT 0,
  `no_bpjs_kesehatan` varchar(50) DEFAULT NULL,
  `kelas_bpjs_kesehatan` varchar(20) DEFAULT NULL,
  `tarif_bpjs` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_salary_rates`
--

CREATE TABLE `employee_salary_rates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `effective_from` date NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_work_schedules`
--

CREATE TABLE `employee_work_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `work_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Struktur dari tabel `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `nama_libur` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `is_national` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Struktur dari tabel `master_bagians`
--

CREATE TABLE `master_bagians` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_jabatans`
--

CREATE TABLE `master_jabatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_kategori_regulations`
--

CREATE TABLE `master_kategori_regulations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_penempatans`
--

CREATE TABLE `master_penempatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations_new`
--

CREATE TABLE `migrations_new` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Struktur dari tabel `regions`
--

CREATE TABLE `regions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `level` enum('provinsi','kabupaten','kecamatan','desa') NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `regulations`
--

CREATE TABLE `regulations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `jenis_perijinan` varchar(255) NOT NULL,
  `regulasi` text DEFAULT NULL,
  `nomor_ijin` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_berakhir` date DEFAULT NULL,
  `masa_berlaku` varchar(255) DEFAULT NULL,
  `bagian_terkait` varchar(255) DEFAULT NULL,
  `status` enum('Terpenuhi','Tidak Terpenuhi') NOT NULL DEFAULT 'Terpenuhi',
  `keterangan` text DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_additions`
--

CREATE TABLE `salary_additions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `salary_detail_id` bigint(20) UNSIGNED NOT NULL,
  `nama_tambahan` varchar(255) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_archives`
--

CREATE TABLE `salary_archives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `archive_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_salary` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_checklogs` int(11) NOT NULL DEFAULT 0,
  `total_employees` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `archive_type` varchar(255) NOT NULL DEFAULT 'checklog',
  `filters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`filters`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_archive_details`
--

CREATE TABLE `salary_archive_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `salary_archive_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fingerprint_pin` varchar(255) DEFAULT NULL,
  `employee_name` varchar(255) NOT NULL,
  `bagian` varchar(255) DEFAULT NULL,
  `jenis_karyawan` varchar(255) DEFAULT NULL,
  `hari_hadir` decimal(10,2) NOT NULL DEFAULT 0.00,
  `jam_kerja` decimal(10,2) NOT NULL DEFAULT 0.00,
  `jam_lembur` decimal(10,2) NOT NULL DEFAULT 0.00,
  `jam_lembur_biasa` decimal(10,2) NOT NULL DEFAULT 0.00,
  `jam_lembur_libur` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tarif_harian` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tarif_jam` decimal(15,2) NOT NULL DEFAULT 0.00,
  `gaji_pokok` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_lembur` decimal(15,2) NOT NULL DEFAULT 0.00,
  `uang_makan_lembur` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_potongan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `rincian_potongan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rincian_potongan`)),
  `rincian_potongan_tambahan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rincian_potongan_tambahan`)),
  `potongan_tambahan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `alasan_potongan_tambahan` text DEFAULT NULL,
  `gaji_kotor` decimal(15,2) NOT NULL DEFAULT 0.00,
  `gaji_bersih` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `daily_breakdown` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`daily_breakdown`)),
  `total_tambahan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `rincian_nominal_tambahan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rincian_nominal_tambahan`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_attendances`
--

CREATE TABLE `salary_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `salary_detail_id` bigint(20) UNSIGNED NOT NULL,
  `attendance_log_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `jam_istirahat` decimal(5,2) NOT NULL DEFAULT 1.00,
  `jam_kerja` decimal(5,2) NOT NULL DEFAULT 0.00,
  `jam_lembur` decimal(5,2) NOT NULL DEFAULT 0.00,
  `jam_lembur_biasa` decimal(5,2) NOT NULL DEFAULT 0.00,
  `jam_lembur_libur` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_holiday` tinyint(1) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_deductions`
--

CREATE TABLE `salary_deductions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `salary_detail_id` bigint(20) UNSIGNED NOT NULL,
  `nama_potongan` varchar(255) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_details`
--

CREATE TABLE `salary_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `salary_period_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_karyawan` varchar(255) NOT NULL,
  `tarif_harian` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tarif_jam` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tarif_lembur_biasa` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tarif_lembur_libur` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tarif_uang_makan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `jam_istirahat` decimal(5,2) NOT NULL DEFAULT 1.00,
  `hari_kerja` decimal(8,2) NOT NULL DEFAULT 0.00,
  `jam_kerja` decimal(8,2) NOT NULL DEFAULT 0.00,
  `jam_lembur_biasa` decimal(8,2) NOT NULL DEFAULT 0.00,
  `jam_lembur_libur` decimal(8,2) NOT NULL DEFAULT 0.00,
  `gaji_pokok` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_lembur` decimal(15,2) NOT NULL DEFAULT 0.00,
  `uang_makan_lembur` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_potongan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `gaji_kotor` decimal(15,2) NOT NULL DEFAULT 0.00,
  `gaji_bersih` decimal(15,2) NOT NULL DEFAULT 0.00,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_tambahan` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_periods`
--

CREATE TABLE `salary_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_settings`
--

CREATE TABLE `salary_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tmp_bpjs_kesehatan`
--

CREATE TABLE `tmp_bpjs_kesehatan` (
  `nomor_peserta` varchar(20) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `warning_letters`
--

CREATE TABLE `warning_letters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `nomor_surat` varchar(255) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `tingkat_sp` tinyint(3) UNSIGNED NOT NULL,
  `alasan_pelanggaran` text NOT NULL,
  `tanggal_mulai_berlaku` date NOT NULL,
  `tanggal_akhir_berlaku` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'aktif',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `warning_notices`
--

CREATE TABLE `warning_notices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `nomor_surat` varchar(255) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `alasan_pelanggaran` text NOT NULL,
  `tanggal_mulai_berlaku` date NOT NULL,
  `tanggal_akhir_berlaku` date NOT NULL,
  `nama_kepala_unit` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `work_schedules`
--

CREATE TABLE `work_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `account_credentials`
--
ALTER TABLE `account_credentials`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `attendance_devices`
--
ALTER TABLE `attendance_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attendance_devices_serial_number_unique` (`serial_number`);

--
-- Indeks untuk tabel `attendance_logs`
--
ALTER TABLE `attendance_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attendance_logs_event_hash_unique` (`event_hash`),
  ADD KEY `attendance_logs_device_sn_index` (`device_sn`),
  ADD KEY `attendance_logs_pin_index` (`pin`),
  ADD KEY `attendance_logs_punched_at_index` (`punched_at`);

--
-- Indeks untuk tabel `borongan_employees`
--
ALTER TABLE `borongan_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `borongan_employees_no_id_unique` (`no_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `checklogs`
--
ALTER TABLE `checklogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checklogs_borongan_employee_id_foreign` (`borongan_employee_id`),
  ADD KEY `checklogs_salary_archive_id_foreign` (`salary_archive_id`),
  ADD KEY `checklogs_employee_id_foreign` (`employee_id`);

--
-- Indeks untuk tabel `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contracts_nomor_kontrak_unique` (`nomor_kontrak`),
  ADD KEY `contracts_employee_id_foreign` (`employee_id`);

--
-- Indeks untuk tabel `daily_salaries`
--
ALTER TABLE `daily_salaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `daily_salaries_employee_id_periode_mulai_periode_selesai_unique` (`employee_id`,`periode_mulai`,`periode_selesai`);

--
-- Indeks untuk tabel `daily_work_schedules`
--
ALTER TABLE `daily_work_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `daily_schedule_date_bagian_unique` (`tanggal`,`bagian`),
  ADD KEY `daily_work_schedules_work_schedule_id_foreign` (`work_schedule_id`),
  ADD KEY `daily_work_schedules_penempatan_tanggal_index` (`penempatan`,`tanggal`),
  ADD KEY `daily_schedule_bagian_date_index` (`bagian`,`tanggal`);

--
-- Indeks untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_fingerprint_pin_unique` (`fingerprint_pin`),
  ADD UNIQUE KEY `employees_nik_unique` (`no_induk_karyawan`),
  ADD UNIQUE KEY `employees_no_ktp_unique` (`no_ktp`),
  ADD KEY `employees_provinsi_id_foreign` (`provinsi_id`),
  ADD KEY `employees_kabupaten_id_foreign` (`kabupaten_id`),
  ADD KEY `employees_kecamatan_id_foreign` (`kecamatan_id`),
  ADD KEY `employees_desa_id_foreign` (`desa_id`);

--
-- Indeks untuk tabel `employee_salary_rates`
--
ALTER TABLE `employee_salary_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_salary_rates_employee_id_effective_from_unique` (`employee_id`,`effective_from`),
  ADD KEY `employee_salary_rates_created_by_foreign` (`created_by`);

--
-- Indeks untuk tabel `employee_work_schedules`
--
ALTER TABLE `employee_work_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_schedule_per_tanggal_unique` (`employee_id`,`tanggal`),
  ADD KEY `employee_work_schedules_work_schedule_id_foreign` (`work_schedule_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `holidays_tanggal_unique` (`tanggal`);

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
-- Indeks untuk tabel `master_bagians`
--
ALTER TABLE `master_bagians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_bagians_nama_unique` (`nama`);

--
-- Indeks untuk tabel `master_jabatans`
--
ALTER TABLE `master_jabatans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_jabatans_nama_unique` (`nama`);

--
-- Indeks untuk tabel `master_kategori_regulations`
--
ALTER TABLE `master_kategori_regulations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_kategori_regulations_nama_unique` (`nama`);

--
-- Indeks untuk tabel `master_penempatans`
--
ALTER TABLE `master_penempatans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_penempatans_nama_unique` (`nama`);

--
-- Indeks untuk tabel `migrations_new`
--
ALTER TABLE `migrations_new`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `regions_kode_unique` (`kode`),
  ADD KEY `regions_parent_id_foreign` (`parent_id`),
  ADD KEY `regions_level_parent_id_index` (`level`,`parent_id`);

--
-- Indeks untuk tabel `regulations`
--
ALTER TABLE `regulations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `salary_additions`
--
ALTER TABLE `salary_additions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_additions_salary_detail_id_foreign` (`salary_detail_id`);

--
-- Indeks untuk tabel `salary_archives`
--
ALTER TABLE `salary_archives`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `salary_archive_details`
--
ALTER TABLE `salary_archive_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_archive_details_salary_archive_id_foreign` (`salary_archive_id`),
  ADD KEY `salary_archive_details_employee_id_foreign` (`employee_id`);

--
-- Indeks untuk tabel `salary_attendances`
--
ALTER TABLE `salary_attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_attendances_attendance_log_id_foreign` (`attendance_log_id`),
  ADD KEY `salary_attendances_salary_detail_id_tanggal_index` (`salary_detail_id`,`tanggal`);

--
-- Indeks untuk tabel `salary_deductions`
--
ALTER TABLE `salary_deductions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_deductions_salary_detail_id_foreign` (`salary_detail_id`);

--
-- Indeks untuk tabel `salary_details`
--
ALTER TABLE `salary_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `salary_details_salary_period_id_employee_id_unique` (`salary_period_id`,`employee_id`),
  ADD KEY `salary_details_employee_id_foreign` (`employee_id`);

--
-- Indeks untuk tabel `salary_periods`
--
ALTER TABLE `salary_periods`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `salary_settings`
--
ALTER TABLE `salary_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `salary_settings_key_unique` (`key`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `warning_letters`
--
ALTER TABLE `warning_letters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warning_letters_nomor_surat_unique` (`nomor_surat`),
  ADD KEY `warning_letters_employee_id_foreign` (`employee_id`);

--
-- Indeks untuk tabel `warning_notices`
--
ALTER TABLE `warning_notices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warning_notices_nomor_surat_unique` (`nomor_surat`),
  ADD KEY `warning_notices_employee_id_foreign` (`employee_id`);

--
-- Indeks untuk tabel `work_schedules`
--
ALTER TABLE `work_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `work_schedules_kode_unique` (`kode`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `account_credentials`
--
ALTER TABLE `account_credentials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `attendance_devices`
--
ALTER TABLE `attendance_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `attendance_logs`
--
ALTER TABLE `attendance_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `borongan_employees`
--
ALTER TABLE `borongan_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `checklogs`
--
ALTER TABLE `checklogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `daily_salaries`
--
ALTER TABLE `daily_salaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `daily_work_schedules`
--
ALTER TABLE `daily_work_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `employee_salary_rates`
--
ALTER TABLE `employee_salary_rates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `employee_work_schedules`
--
ALTER TABLE `employee_work_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `master_bagians`
--
ALTER TABLE `master_bagians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `master_jabatans`
--
ALTER TABLE `master_jabatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `master_kategori_regulations`
--
ALTER TABLE `master_kategori_regulations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `master_penempatans`
--
ALTER TABLE `master_penempatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations_new`
--
ALTER TABLE `migrations_new`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `regulations`
--
ALTER TABLE `regulations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `salary_additions`
--
ALTER TABLE `salary_additions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `salary_archives`
--
ALTER TABLE `salary_archives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `salary_archive_details`
--
ALTER TABLE `salary_archive_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `salary_attendances`
--
ALTER TABLE `salary_attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `salary_deductions`
--
ALTER TABLE `salary_deductions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `salary_details`
--
ALTER TABLE `salary_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `salary_periods`
--
ALTER TABLE `salary_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `salary_settings`
--
ALTER TABLE `salary_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `warning_letters`
--
ALTER TABLE `warning_letters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `warning_notices`
--
ALTER TABLE `warning_notices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `work_schedules`
--
ALTER TABLE `work_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `checklogs`
--
ALTER TABLE `checklogs`
  ADD CONSTRAINT `checklogs_borongan_employee_id_foreign` FOREIGN KEY (`borongan_employee_id`) REFERENCES `borongan_employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checklogs_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `checklogs_salary_archive_id_foreign` FOREIGN KEY (`salary_archive_id`) REFERENCES `salary_archives` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Ketidakleluasaan untuk tabel `daily_salaries`
--
ALTER TABLE `daily_salaries`
  ADD CONSTRAINT `daily_salaries_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `daily_work_schedules`
--
ALTER TABLE `daily_work_schedules`
  ADD CONSTRAINT `daily_work_schedules_work_schedule_id_foreign` FOREIGN KEY (`work_schedule_id`) REFERENCES `work_schedules` (`id`);

--
-- Ketidakleluasaan untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_kabupaten_id_foreign` FOREIGN KEY (`kabupaten_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_kecamatan_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_provinsi_id_foreign` FOREIGN KEY (`provinsi_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `employee_salary_rates`
--
ALTER TABLE `employee_salary_rates`
  ADD CONSTRAINT `employee_salary_rates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employee_salary_rates_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `employee_work_schedules`
--
ALTER TABLE `employee_work_schedules`
  ADD CONSTRAINT `employee_work_schedules_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_work_schedules_work_schedule_id_foreign` FOREIGN KEY (`work_schedule_id`) REFERENCES `work_schedules` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `regions`
--
ALTER TABLE `regions`
  ADD CONSTRAINT `regions_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `salary_additions`
--
ALTER TABLE `salary_additions`
  ADD CONSTRAINT `salary_additions_salary_detail_id_foreign` FOREIGN KEY (`salary_detail_id`) REFERENCES `salary_details` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `salary_archive_details`
--
ALTER TABLE `salary_archive_details`
  ADD CONSTRAINT `salary_archive_details_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `salary_archive_details_salary_archive_id_foreign` FOREIGN KEY (`salary_archive_id`) REFERENCES `salary_archives` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `salary_attendances`
--
ALTER TABLE `salary_attendances`
  ADD CONSTRAINT `salary_attendances_attendance_log_id_foreign` FOREIGN KEY (`attendance_log_id`) REFERENCES `attendance_logs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `salary_attendances_salary_detail_id_foreign` FOREIGN KEY (`salary_detail_id`) REFERENCES `salary_details` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `salary_deductions`
--
ALTER TABLE `salary_deductions`
  ADD CONSTRAINT `salary_deductions_salary_detail_id_foreign` FOREIGN KEY (`salary_detail_id`) REFERENCES `salary_details` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `salary_details`
--
ALTER TABLE `salary_details`
  ADD CONSTRAINT `salary_details_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salary_details_salary_period_id_foreign` FOREIGN KEY (`salary_period_id`) REFERENCES `salary_periods` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `warning_letters`
--
ALTER TABLE `warning_letters`
  ADD CONSTRAINT `warning_letters_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Ketidakleluasaan untuk tabel `warning_notices`
--
ALTER TABLE `warning_notices`
  ADD CONSTRAINT `warning_notices_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);
--
-- Database: `gaji_khl_recovery`
--
CREATE DATABASE IF NOT EXISTS `gaji_khl_recovery` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gaji_khl_recovery`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `account_credentials`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.account_credentials: #1932 - Table &#039;gaji_khl_recovery.account_credentials&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `attendance_devices`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.attendance_devices: #1932 - Table &#039;gaji_khl_recovery.attendance_devices&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `attendance_logs`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.attendance_logs: #1932 - Table &#039;gaji_khl_recovery.attendance_logs&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `borongan_employees`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.borongan_employees: #1932 - Table &#039;gaji_khl_recovery.borongan_employees&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.cache: #1932 - Table &#039;gaji_khl_recovery.cache&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.cache_locks: #1932 - Table &#039;gaji_khl_recovery.cache_locks&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `checklogs`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.checklogs: #1932 - Table &#039;gaji_khl_recovery.checklogs&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `daily_salaries`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.daily_salaries: #1932 - Table &#039;gaji_khl_recovery.daily_salaries&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.employees: #1932 - Table &#039;gaji_khl_recovery.employees&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_work_schedules`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.employee_work_schedules: #1932 - Table &#039;gaji_khl_recovery.employee_work_schedules&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `holidays`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.holidays: #1932 - Table &#039;gaji_khl_recovery.holidays&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.jobs: #1932 - Table &#039;gaji_khl_recovery.jobs&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.migrations: #1932 - Table &#039;gaji_khl_recovery.migrations&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.password_reset_tokens: #1932 - Table &#039;gaji_khl_recovery.password_reset_tokens&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `regulations`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.regulations: #1932 - Table &#039;gaji_khl_recovery.regulations&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_archives`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.salary_archives: #1932 - Table &#039;gaji_khl_recovery.salary_archives&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_attendances`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.salary_attendances: #1932 - Table &#039;gaji_khl_recovery.salary_attendances&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_deductions`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.salary_deductions: #1932 - Table &#039;gaji_khl_recovery.salary_deductions&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_details`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.salary_details: #1932 - Table &#039;gaji_khl_recovery.salary_details&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_periods`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.salary_periods: #1932 - Table &#039;gaji_khl_recovery.salary_periods&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.sessions: #1932 - Table &#039;gaji_khl_recovery.sessions&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.users: #1932 - Table &#039;gaji_khl_recovery.users&#039; doesn&#039;t exist in engine

-- --------------------------------------------------------

--
-- Struktur dari tabel `work_schedules`
--
-- Kesalahan membaca struktur untuk tabel gaji_khl_recovery.work_schedules: #1932 - Table &#039;gaji_khl_recovery.work_schedules&#039; doesn&#039;t exist in engine
--
-- Database: `pest_control_db`
--
CREATE DATABASE IF NOT EXISTS `pest_control_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pest_control_db`;

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
-- Struktur dari tabel `entries`
--

CREATE TABLE `entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trap_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `tindakan` varchar(255) DEFAULT NULL,
  `aktivitas` enum('LOW','MEDIUM','HIGH') NOT NULL DEFAULT 'LOW',
  `hasil` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Struktur dari tabel `rekomendasi_approval_histories`
--

CREATE TABLE `rekomendasi_approval_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rekomendasi_perbaikan_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `aksi` varchar(255) NOT NULL,
  `status_sebelumnya` varchar(255) DEFAULT NULL,
  `status_sesudahnya` varchar(255) NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekomendasi_perbaikans`
--

CREATE TABLE `rekomendasi_perbaikans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entry_id` bigint(20) UNSIGNED NOT NULL,
  `rekomendasi_catatan` text DEFAULT NULL,
  `rekomendasi_gambar` varchar(255) DEFAULT NULL,
  `perbaikan_catatan` text DEFAULT NULL,
  `perbaikan_gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status_persetujuan` varchar(255) NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `diputuskan_pada` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Struktur dari tabel `traps`
--

CREATE TABLE `traps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `no_trap` varchar(255) NOT NULL,
  `type_detector` varchar(255) NOT NULL,
  `spesies_hama` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','pengelola') NOT NULL DEFAULT 'pengelola',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `entries`
--
ALTER TABLE `entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entries_trap_id_foreign` (`trap_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indeks untuk tabel `rekomendasi_approval_histories`
--
ALTER TABLE `rekomendasi_approval_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rekomendasi_approval_histories_rekomendasi_perbaikan_id_foreign` (`rekomendasi_perbaikan_id`),
  ADD KEY `rekomendasi_approval_histories_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `rekomendasi_perbaikans`
--
ALTER TABLE `rekomendasi_perbaikans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rekomendasi_perbaikans_entry_id_foreign` (`entry_id`),
  ADD KEY `rekomendasi_perbaikans_approved_by_foreign` (`approved_by`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `traps`
--
ALTER TABLE `traps`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT untuk tabel `entries`
--
ALTER TABLE `entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rekomendasi_approval_histories`
--
ALTER TABLE `rekomendasi_approval_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rekomendasi_perbaikans`
--
ALTER TABLE `rekomendasi_perbaikans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `traps`
--
ALTER TABLE `traps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `entries`
--
ALTER TABLE `entries`
  ADD CONSTRAINT `entries_trap_id_foreign` FOREIGN KEY (`trap_id`) REFERENCES `traps` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rekomendasi_approval_histories`
--
ALTER TABLE `rekomendasi_approval_histories`
  ADD CONSTRAINT `rekomendasi_approval_histories_rekomendasi_perbaikan_id_foreign` FOREIGN KEY (`rekomendasi_perbaikan_id`) REFERENCES `rekomendasi_perbaikans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rekomendasi_approval_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `rekomendasi_perbaikans`
--
ALTER TABLE `rekomendasi_perbaikans`
  ADD CONSTRAINT `rekomendasi_perbaikans_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rekomendasi_perbaikans_entry_id_foreign` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE;
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Struktur dari tabel `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indeks untuk tabel `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indeks untuk tabel `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indeks untuk tabel `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indeks untuk tabel `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indeks untuk tabel `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indeks untuk tabel `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indeks untuk tabel `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indeks untuk tabel `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indeks untuk tabel `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indeks untuk tabel `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indeks untuk tabel `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indeks untuk tabel `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indeks untuk tabel `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indeks untuk tabel `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indeks untuk tabel `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indeks untuk tabel `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indeks untuk tabel `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `starfood_company`
--
CREATE DATABASE IF NOT EXISTS `starfood_company` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `starfood_company`;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
