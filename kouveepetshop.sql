-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2020 at 03:44 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kouveepetshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pengadaan`
--

CREATE TABLE `detail_pengadaan` (
  `id_detail_pengadaan` int(11) NOT NULL,
  `id_pengadaan_produk` varchar(30) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail_pengadaan`
--

INSERT INTO `detail_pengadaan` (`id_detail_pengadaan`, `id_pengadaan_produk`, `id_produk`, `jumlah`, `harga`, `total_harga`, `created_at`, `created_by`, `modified_at`, `modified_by`) VALUES
(1, 'PO-2020-03-06-00', 1, 2, 350000, 700000, '2020-03-06 16:55:38', 'admin', NULL, NULL),
(2, 'PO-2020-03-06-00', 3, 3, 300000, 900000, '2020-03-06 16:55:38', 'admin', NULL, NULL),
(3, 'PO-2020-03-06-01', 2, 5, 250000, 1250000, '2020-03-06 16:55:38', 'admin', NULL, NULL),
(4, 'PO-2020-03-06-01', 3, 4, 240000, 960000, '2020-03-06 16:55:38', 'admin', NULL, NULL),
(5, 'PO-2020-03-06-02', 3, 10, 230000, 2300000, '2020-03-06 16:55:38', 'admin', NULL, NULL),
(6, 'PO-2020-03-06-02', 1, 5, 300000, 1500000, '2020-03-06 16:55:38', 'admin', NULL, NULL),
(7, 'PO-2020-04-30-03', 2, 4, 100000, 400000, '2020-04-30 08:35:04', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi_layanan`
--

CREATE TABLE `detail_transaksi_layanan` (
  `id_detail_transaksi_layanan` int(11) NOT NULL,
  `id_transaksi_layanan` varchar(30) NOT NULL,
  `id_harga_layanan` int(11) NOT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail_transaksi_layanan`
--

INSERT INTO `detail_transaksi_layanan` (`id_detail_transaksi_layanan`, `id_transaksi_layanan`, `id_harga_layanan`, `jumlah`, `total_harga`, `created_at`, `created_by`, `modified_at`, `modified_by`) VALUES
(1, 'LY-060320-00', 2, 1, 75000, '2020-03-06 16:55:38', 'pradnyadarsana', NULL, NULL),
(2, 'LY-060320-00', 5, 2, 80000, '2020-03-06 16:55:38', 'pradnyadarsana', NULL, NULL),
(3, 'LY-060320-01', 7, 1, 46000, '2020-03-06 16:55:38', 'kadekharyadi', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi_produk`
--

CREATE TABLE `detail_transaksi_produk` (
  `id_detail_transaksi_produk` int(11) NOT NULL,
  `id_transaksi_produk` varchar(30) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail_transaksi_produk`
--

INSERT INTO `detail_transaksi_produk` (`id_detail_transaksi_produk`, `id_transaksi_produk`, `id_produk`, `jumlah`, `total_harga`, `created_at`, `created_by`, `modified_at`, `modified_by`) VALUES
(1, 'PR-060320-00', 1, 2, 700000, '2020-03-06 16:55:38', 'kadekharyadi', NULL, NULL),
(2, 'PR-060320-00', 2, 1, 300000, '2020-03-06 16:55:38', 'kadekharyadi', NULL, NULL),
(3, 'PR-060320-01', 3, 3, 900000, '2020-03-06 16:55:38', 'kadekharyadi', NULL, NULL),
(4, 'PR-060320-01', 2, 1, 300000, '2020-03-06 16:55:38', 'kadekharyadi', NULL, NULL),
(5, 'PR-060320-02', 2, 1, 300000, '2020-03-06 16:55:38', 'pradnyadarsana', NULL, NULL),
(6, 'PR-060320-02', 3, 1, 300000, '2020-03-06 16:55:38', 'pradnyadarsana', NULL, NULL),
(7, 'PK-290320-03', 2, 2, 600000, '2020-03-29 15:23:28', 'kadekharyadi', NULL, NULL),
(11, 'PK-290320-03', 2, 4, 1200000, '2020-03-29 15:47:19', 'kadekharyadi', '2020-03-29 23:05:55', 'pradnyadarsana'),
(15, 'PR-300320-01', 3, 4, 1200000, '2020-04-30 09:06:45', 'pande', NULL, NULL),
(21, 'PK-290320-01', 3, 5, 200, '2020-05-26 14:40:28', 'admin', NULL, NULL),
(22, 'PK-290320-01', 2, 5, 200, '2020-05-26 14:41:35', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `harga_layanan`
--

CREATE TABLE `harga_layanan` (
  `id_harga_layanan` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `id_ukuran_hewan` int(11) NOT NULL,
  `harga` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL,
  `delete_by` varchar(50) DEFAULT NULL,
  `aktif` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `harga_layanan`
--

INSERT INTO `harga_layanan` (`id_harga_layanan`, `id_layanan`, `id_ukuran_hewan`, `harga`, `created_at`, `created_by`, `modified_at`, `modified_by`, `delete_at`, `delete_by`, `aktif`) VALUES
(1, 1, 1, 70000, '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(2, 1, 2, 75000, '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(3, 1, 3, 80000, '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(4, 1, 4, 85000, '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(5, 2, 1, 40000, '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(6, 2, 2, 43000, '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(7, 2, 3, 46000, '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(8, 2, 4, 49000, '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(9, 3, 1, 50000, '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(10, 3, 2, 54000, '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(11, 3, 3, 58000, '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(12, 3, 4, 62000, '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(13, 4, 1, 5000, '2020-03-19 19:18:12', 'admin', NULL, NULL, NULL, NULL, 1),
(14, 4, 2, 6000, '2020-03-19 19:18:12', 'admin', NULL, NULL, NULL, NULL, 1),
(15, 4, 3, 8000, '2020-03-19 19:48:57', 'admin', NULL, NULL, NULL, NULL, 1),
(16, 4, 4, 10000, '2020-03-19 19:48:57', 'admin', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hewan`
--

CREATE TABLE `hewan` (
  `id_hewan` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `id_jenis_hewan` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL,
  `delete_by` varchar(50) DEFAULT NULL,
  `aktif` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hewan`
--

INSERT INTO `hewan` (`id_hewan`, `id_pelanggan`, `id_jenis_hewan`, `nama`, `tanggal_lahir`, `created_at`, `created_by`, `modified_at`, `modified_by`, `delete_at`, `delete_by`, `aktif`) VALUES
(1, 1, 3, 'Mickey', '2017-07-03', '2020-03-06 16:55:38', 'pradnyadarsana', NULL, NULL, NULL, NULL, 1),
(2, 3, 2, 'Miaw', '2015-10-18', '2020-03-06 16:55:38', 'kadekharyadi', NULL, NULL, NULL, NULL, 1),
(3, 2, 1, 'Doggo', '2018-12-19', '2020-03-06 16:55:38', 'pradnyadarsana', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_hewan`
--

CREATE TABLE `jenis_hewan` (
  `id_jenis_hewan` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL,
  `delete_by` varchar(50) DEFAULT NULL,
  `aktif` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jenis_hewan`
--

INSERT INTO `jenis_hewan` (`id_jenis_hewan`, `nama`, `created_at`, `created_by`, `modified_at`, `modified_by`, `delete_at`, `delete_by`, `aktif`) VALUES
(1, 'Anjing', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(2, 'Kucing', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(3, 'Biawak', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id_layanan` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL,
  `delete_by` varchar(50) DEFAULT NULL,
  `aktif` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id_layanan`, `nama`, `created_at`, `created_by`, `modified_at`, `modified_by`, `delete_at`, `delete_by`, `aktif`) VALUES
(1, 'Grooming Mandi Kutu Anjing', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(2, 'Potong Kuku Anjing', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(3, 'Pangkas Bulu Kucing', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(4, 'Mandi Kutu Anjing', '2020-03-19 16:34:08', 'cristiano', NULL, NULL, NULL, NULL, 1),
(5, 'Cuci Sepeda', '2020-03-19 20:02:18', 'admin', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notifikasi` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id_notifikasi`, `id_produk`, `status`, `created_at`) VALUES
(1, 2, 0, '2020-05-27 06:05:27'),
(3, 3, 1, '2020-05-27 06:13:23');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL,
  `delete_by` varchar(50) DEFAULT NULL,
  `aktif` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama`, `alamat`, `tanggal_lahir`, `telp`, `username`, `password`, `role`, `created_at`, `created_by`, `modified_at`, `modified_by`, `delete_at`, `delete_by`, `aktif`) VALUES
(1, 'Pande Nyoman Pradnya', 'Jl. Perumnas C39, Condongcatur', '1999-05-29', '082345987234', 'pradnyadarsana', 'examplepass', 'Customer Service', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(2, 'Kadek Haryadi', 'Jl. Seturan 20X, Caturtunggal', '2000-03-07', '082346826936', 'kadekharyadi', 'examplepass', 'Customer Service', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(3, 'Erros Sahu', 'Jl. Maguwo 25, Maguwoharjo', '1998-04-19', '082637461623', 'errossahu', 'passcontoh', 'Kasir', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(4, 'Pande', 'jalan denpasar', '1999-09-09', '082392849283', 'pande', '$2y$10$HAUgKq41G7J/e0JoHWscUuWvrDd.RI6jT9YR8yWZb4pf7k5zZqTCq', 'Customer Service', '2020-03-28 12:01:39', 'admin', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL,
  `delete_by` varchar(50) DEFAULT NULL,
  `aktif` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `alamat`, `tanggal_lahir`, `telp`, `created_at`, `created_by`, `modified_at`, `modified_by`, `delete_at`, `delete_by`, `aktif`) VALUES
(1, 'Andrea Bemantoro', 'Jl. Nanas No. 13x, Maguwoharjo', '1997-07-13', '081234987256', '2020-03-06 16:55:38', 'pradnyadarsana', NULL, NULL, NULL, NULL, 1),
(2, 'Daiva Haryanto', 'Jl. Nanas No 14B, Maguwoharjo', '1999-02-05', '123098263827', '2020-03-06 16:55:38', 'kadekharyadi', NULL, NULL, NULL, NULL, 1),
(3, 'Joko Wiguna', 'Jl. Babarsari 44, Caturtunggal', '1995-11-19', '089018273649', '2020-03-06 16:55:38', 'pradnyadarsana', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pengadaan_produk`
--

CREATE TABLE `pengadaan_produk` (
  `id_pengadaan_produk` varchar(30) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `total` int(11) DEFAULT NULL,
  `status` varchar(30) DEFAULT 'menunggu konfirmasi',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengadaan_produk`
--

INSERT INTO `pengadaan_produk` (`id_pengadaan_produk`, `id_supplier`, `total`, `status`, `created_at`, `created_by`, `modified_at`, `modified_by`) VALUES
('PO-2020-03-06-00', 1, 1600000, 'Menunggu Konfirmasi', '2020-03-06 16:55:38', 'admin', NULL, NULL),
('PO-2020-03-06-01', 2, 2210000, 'Menunggu Konfirmasi', '2020-03-06 16:55:38', 'admin', NULL, NULL),
('PO-2020-03-06-02', 3, 3800000, 'Menunggu Konfirmasi', '2020-03-06 16:55:38', 'admin', NULL, NULL),
('PO-2020-04-30-02', 1, 9000000, 'Pesanan Selesai', '2020-04-30 06:11:57', 'admin', '2020-04-30 13:28:24', 'admin'),
('PO-2020-04-30-03', 2, 400000, 'Pesanan Diproses', '2020-04-30 06:13:00', 'admin', '2020-04-30 13:24:13', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `satuan` varchar(30) DEFAULT NULL,
  `jumlah_stok` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `min_stok` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL,
  `delete_by` varchar(50) DEFAULT NULL,
  `aktif` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama`, `satuan`, `jumlah_stok`, `harga`, `min_stok`, `gambar`, `created_at`, `created_by`, `modified_at`, `modified_by`, `delete_at`, `delete_by`, `aktif`) VALUES
(1, 'Pedigree Anjing Golden', 'sak', 13, 350000, 3, 'pedigolden.jpg', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(2, 'Kandang Anjing 1m x 1m', 'buah', 8, 300000, 10, 'kandang1m2.jpg', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(3, 'Susu Anjing Pomeranian 300gr', 'bungkus', 25, 300000, 30, 'susupom.jpg', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL,
  `delete_by` varchar(50) DEFAULT NULL,
  `aktif` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama`, `alamat`, `telp`, `created_at`, `created_by`, `modified_at`, `modified_by`, `delete_at`, `delete_by`, `aktif`) VALUES
(1, 'PT. Indofood Sejahtera', 'Jalan Kaliurang KM 9 no 90A', '083234756928', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(2, 'PT. Anjing Bahagia', 'Jalan Solo No 140 Yogyakarta', '123019837625', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(3, 'Warung Kucing Sehat', 'Jalan Babarsari no 59 Yogyakarta', '085183726937', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_layanan`
--

CREATE TABLE `transaksi_layanan` (
  `id_transaksi_layanan` varchar(30) NOT NULL,
  `id_customer_service` int(11) NOT NULL,
  `id_kasir` int(11) DEFAULT NULL,
  `id_hewan` int(11) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL,
  `diskon` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `progress` varchar(30) DEFAULT 'sedang diproses',
  `status` varchar(30) DEFAULT 'menunggu pembayaran',
  `tanggal_lunas` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaksi_layanan`
--

INSERT INTO `transaksi_layanan` (`id_transaksi_layanan`, `id_customer_service`, `id_kasir`, `id_hewan`, `subtotal`, `diskon`, `total`, `progress`, `status`, `tanggal_lunas`, `created_at`, `created_by`, `modified_at`, `modified_by`) VALUES
('LY-060320-00', 1, NULL, NULL, 155000, NULL, 155000, 'sedang diproses', 'menunggu pembayaran', NULL, '2020-03-06 16:55:38', 'pradnyadarsana', NULL, NULL),
('LY-060320-01', 2, NULL, NULL, 46000, NULL, 46000, 'Layanan Selesai', 'menunggu pembayaran', NULL, '2020-03-06 16:55:38', 'kadekharyadi', NULL, NULL),
('LY-060320-02', 1, NULL, 2, 186000, 10000, 176000, 'Layanan Selesai', 'Lunas', '2020-03-06 23:55:38', '2020-03-06 16:55:38', 'pradnyadarsana', '2020-03-06 23:55:38', 'errossahu');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_produk`
--

CREATE TABLE `transaksi_produk` (
  `id_transaksi_produk` varchar(30) NOT NULL,
  `id_customer_service` int(11) NOT NULL,
  `id_kasir` int(11) DEFAULT NULL,
  `id_hewan` int(11) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL,
  `diskon` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `status` varchar(30) DEFAULT 'menunggu pembayaran',
  `tanggal_lunas` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaksi_produk`
--

INSERT INTO `transaksi_produk` (`id_transaksi_produk`, `id_customer_service`, `id_kasir`, `id_hewan`, `subtotal`, `diskon`, `total`, `status`, `tanggal_lunas`, `created_at`, `created_by`, `modified_at`, `modified_by`) VALUES
('PK-290320-01', 1, NULL, 1, 400, 70000, 0, 'Menunggu Pembayaran', NULL, '2020-03-29 07:47:49', 'pradnyadarsana', '2020-03-29 17:27:33', 'pradnyadarsana'),
('PK-290320-02', 1, NULL, 2, 0, 50000, 0, 'Menunggu Pembayaran', NULL, '2020-03-29 07:53:24', 'pradnyadarsana', '2020-03-29 17:29:43', 'kadekharyadi'),
('PK-290320-03', 1, 3, 1, 1800000, 60000, 1740000, 'Lunas', '2020-03-30 19:27:36', '2020-03-29 07:57:56', 'pradnyadarsana', '2020-03-30 19:27:36', 'errossahu'),
('PK-290320-04', 1, NULL, NULL, NULL, NULL, NULL, 'Menunggu Pembayaran', NULL, '2020-03-29 08:02:51', 'pradnyadarsana', NULL, NULL),
('PK-290320-05', 2, NULL, NULL, 0, NULL, 0, 'Menunggu Pembayaran', NULL, '2020-03-29 10:42:31', 'kadekharyadi', '2020-03-29 17:52:38', 'pradnyadarsana'),
('PK-300320-01', 2, NULL, NULL, 0, NULL, 0, 'Menunggu Pembayaran', NULL, '2020-03-30 08:51:16', 'kadekharyadi', NULL, NULL),
('PR-060320-00', 2, 3, 3, 1000000, 30000, 970000, 'Lunas', '2020-03-06 23:55:38', '2020-03-06 16:55:38', 'kadekharyadi', '2020-03-06 23:55:38', 'errossahu'),
('PR-060320-01', 2, 3, 1, 1200000, 25000, 1175000, 'Lunas', '2020-03-06 23:55:38', '2020-03-06 16:55:38', 'kadekharyadi', '2020-03-06 23:55:38', 'errossahu'),
('PR-060320-02', 1, 3, NULL, 600000, NULL, 530000, 'Lunas', '2020-03-06 23:55:38', '2020-03-06 16:55:38', 'pradnyadarsana', '2020-03-06 23:55:38', 'errossahu'),
('PR-300320-01', 2, NULL, 1, 0, 50000, 0, 'Menunggu Pembayaran', NULL, '2020-03-30 12:29:04', 'kadekharyadi', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ukuran_hewan`
--

CREATE TABLE `ukuran_hewan` (
  `id_ukuran_hewan` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL,
  `delete_by` varchar(50) DEFAULT NULL,
  `aktif` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ukuran_hewan`
--

INSERT INTO `ukuran_hewan` (`id_ukuran_hewan`, `nama`, `created_at`, `created_by`, `modified_at`, `modified_by`, `delete_at`, `delete_by`, `aktif`) VALUES
(1, 'Small', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(2, 'Medium', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(3, 'Large', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1),
(4, 'Extra Large', '2020-03-06 16:55:38', 'admin', NULL, NULL, NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pengadaan`
--
ALTER TABLE `detail_pengadaan`
  ADD PRIMARY KEY (`id_detail_pengadaan`),
  ADD KEY `fk_detail_p_relations_pengadaa` (`id_pengadaan_produk`),
  ADD KEY `fk_detail_p_relations_produk` (`id_produk`);

--
-- Indexes for table `detail_transaksi_layanan`
--
ALTER TABLE `detail_transaksi_layanan`
  ADD PRIMARY KEY (`id_detail_transaksi_layanan`),
  ADD KEY `fk_detail_t_relations_harga_la` (`id_harga_layanan`),
  ADD KEY `fk_detail_t_relations_translay` (`id_transaksi_layanan`);

--
-- Indexes for table `detail_transaksi_produk`
--
ALTER TABLE `detail_transaksi_produk`
  ADD PRIMARY KEY (`id_detail_transaksi_produk`),
  ADD KEY `fk_detail_t_relations_produk` (`id_produk`),
  ADD KEY `fk_detail_t_relations_transprod` (`id_transaksi_produk`);

--
-- Indexes for table `harga_layanan`
--
ALTER TABLE `harga_layanan`
  ADD PRIMARY KEY (`id_harga_layanan`),
  ADD KEY `fk_harga_la_relations_layanan` (`id_layanan`),
  ADD KEY `fk_harga_la_relations_ukuran_h` (`id_ukuran_hewan`);

--
-- Indexes for table `hewan`
--
ALTER TABLE `hewan`
  ADD PRIMARY KEY (`id_hewan`),
  ADD KEY `fk_hewan_relations_jenis_he` (`id_jenis_hewan`),
  ADD KEY `fk_hewan_relations_pelangga` (`id_pelanggan`);

--
-- Indexes for table `jenis_hewan`
--
ALTER TABLE `jenis_hewan`
  ADD PRIMARY KEY (`id_jenis_hewan`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `fk_notifikasi_relations_produk` (`id_produk`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pengadaan_produk`
--
ALTER TABLE `pengadaan_produk`
  ADD PRIMARY KEY (`id_pengadaan_produk`),
  ADD KEY `fk_pengadaa_relations_supplier` (`id_supplier`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `transaksi_layanan`
--
ALTER TABLE `transaksi_layanan`
  ADD PRIMARY KEY (`id_transaksi_layanan`),
  ADD KEY `fk_translay_relations_kasir` (`id_kasir`),
  ADD KEY `fk_translay_relations_cust` (`id_customer_service`),
  ADD KEY `fk_transprod_relations_hewan` (`id_hewan`);

--
-- Indexes for table `transaksi_produk`
--
ALTER TABLE `transaksi_produk`
  ADD PRIMARY KEY (`id_transaksi_produk`),
  ADD KEY `fk_transprod_relations_kasir` (`id_kasir`),
  ADD KEY `fk_transprod_relations_cust` (`id_customer_service`),
  ADD KEY `fk_transaks_relations_hewan` (`id_hewan`);

--
-- Indexes for table `ukuran_hewan`
--
ALTER TABLE `ukuran_hewan`
  ADD PRIMARY KEY (`id_ukuran_hewan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pengadaan`
--
ALTER TABLE `detail_pengadaan`
  MODIFY `id_detail_pengadaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `detail_transaksi_layanan`
--
ALTER TABLE `detail_transaksi_layanan`
  MODIFY `id_detail_transaksi_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `detail_transaksi_produk`
--
ALTER TABLE `detail_transaksi_produk`
  MODIFY `id_detail_transaksi_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `harga_layanan`
--
ALTER TABLE `harga_layanan`
  MODIFY `id_harga_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `hewan`
--
ALTER TABLE `hewan`
  MODIFY `id_hewan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jenis_hewan`
--
ALTER TABLE `jenis_hewan`
  MODIFY `id_jenis_hewan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notifikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ukuran_hewan`
--
ALTER TABLE `ukuran_hewan`
  MODIFY `id_ukuran_hewan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pengadaan`
--
ALTER TABLE `detail_pengadaan`
  ADD CONSTRAINT `fk_detail_p_relations_pengadaa` FOREIGN KEY (`id_pengadaan_produk`) REFERENCES `pengadaan_produk` (`id_pengadaan_produk`),
  ADD CONSTRAINT `fk_detail_p_relations_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Constraints for table `detail_transaksi_layanan`
--
ALTER TABLE `detail_transaksi_layanan`
  ADD CONSTRAINT `fk_detail_t_relations_harga_la` FOREIGN KEY (`id_harga_layanan`) REFERENCES `harga_layanan` (`id_harga_layanan`),
  ADD CONSTRAINT `fk_detail_t_relations_translay` FOREIGN KEY (`id_transaksi_layanan`) REFERENCES `transaksi_layanan` (`id_transaksi_layanan`);

--
-- Constraints for table `detail_transaksi_produk`
--
ALTER TABLE `detail_transaksi_produk`
  ADD CONSTRAINT `fk_detail_t_relations_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `fk_detail_t_relations_transprod` FOREIGN KEY (`id_transaksi_produk`) REFERENCES `transaksi_produk` (`id_transaksi_produk`);

--
-- Constraints for table `harga_layanan`
--
ALTER TABLE `harga_layanan`
  ADD CONSTRAINT `fk_harga_la_relations_layanan` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`),
  ADD CONSTRAINT `fk_harga_la_relations_ukuran_h` FOREIGN KEY (`id_ukuran_hewan`) REFERENCES `ukuran_hewan` (`id_ukuran_hewan`);

--
-- Constraints for table `hewan`
--
ALTER TABLE `hewan`
  ADD CONSTRAINT `fk_hewan_relations_jenis_he` FOREIGN KEY (`id_jenis_hewan`) REFERENCES `jenis_hewan` (`id_jenis_hewan`),
  ADD CONSTRAINT `fk_hewan_relations_pelangga` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`);

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `fk_notifikasi_relations_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Constraints for table `pengadaan_produk`
--
ALTER TABLE `pengadaan_produk`
  ADD CONSTRAINT `fk_pengadaa_relations_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`);

--
-- Constraints for table `transaksi_layanan`
--
ALTER TABLE `transaksi_layanan`
  ADD CONSTRAINT `fk_translay_relations_cust` FOREIGN KEY (`id_customer_service`) REFERENCES `pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_translay_relations_kasir` FOREIGN KEY (`id_kasir`) REFERENCES `pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_transprod_relations_hewan` FOREIGN KEY (`id_hewan`) REFERENCES `hewan` (`id_hewan`);

--
-- Constraints for table `transaksi_produk`
--
ALTER TABLE `transaksi_produk`
  ADD CONSTRAINT `fk_transaks_relations_hewan` FOREIGN KEY (`id_hewan`) REFERENCES `hewan` (`id_hewan`),
  ADD CONSTRAINT `fk_transprod_relations_cust` FOREIGN KEY (`id_customer_service`) REFERENCES `pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_transprod_relations_kasir` FOREIGN KEY (`id_kasir`) REFERENCES `pegawai` (`id_pegawai`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
