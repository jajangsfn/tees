-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 12 Nov 2020 pada 10.20
-- Versi server: 10.4.14-MariaDB
-- Versi PHP: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tees`
--
CREATE DATABASE IF NOT EXISTS `tees` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tees`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

DROP TABLE IF EXISTS `detail_transaksi`;
CREATE TABLE `detail_transaksi` (
  `id` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `percent_discount` int(3) NOT NULL,
  `total` float NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id`, `id_transaksi`, `id_produk`, `qty`, `price`, `percent_discount`, `total`, `created_date`, `updated_date`) VALUES
(1, 1, 2, 1, 2000, 0, 2000, '2020-11-12 05:48:51', '2020-11-12 05:48:51'),
(2, 2, 2, 2, 2000, 0, 4000, '2020-11-12 06:00:41', '2020-11-12 06:00:41'),
(3, 2, 3, 1, 1500, 0, 1500, '2020-11-12 06:00:41', '2020-11-12 06:00:41'),
(4, 3, 2, 2, 2000, 0, 4000, '2020-11-12 06:10:34', '2020-11-12 06:10:34'),
(5, 3, 4, 1, 1500, 0, 1500, '2020-11-12 06:10:34', '2020-11-12 06:10:34'),
(6, 4, 2, 2, 2000, 0, 4000, '2020-11-12 06:11:42', '2020-11-12 06:11:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama`, `created_date`, `updated_date`) VALUES
(1, 'Anak Laki - laki', '2020-11-11 10:21:22', '2020-11-11 10:21:22'),
(2, 'Anak Perempuan', '2020-11-11 10:21:22', '2020-11-11 10:21:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

DROP TABLE IF EXISTS `keranjang`;
CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_member` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `percent_discount` int(11) NOT NULL DEFAULT 0,
  `total` float NOT NULL,
  `flag` int(1) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `keranjang`
--

INSERT INTO `keranjang` (`id`, `id_produk`, `id_member`, `qty`, `price`, `percent_discount`, `total`, `flag`, `created_date`, `updated_date`) VALUES
(2, 1, 5, 1, 1000, 0, 1000, 1, '2020-11-11 13:49:31', '2020-11-11 13:49:31'),
(3, 3, 5, 2, 23000, 0, 46000, NULL, '2020-11-11 13:49:31', '2020-11-11 13:49:31'),
(4, 4, 5, 1, 15000, 0, 15000, NULL, '2020-11-11 13:49:31', '2020-11-11 13:49:31'),
(6, 3, 5, 2, 23000, 0, 46000, NULL, '2020-11-11 13:49:33', '2020-11-11 13:49:33'),
(7, 4, 5, 1, 15000, 0, 15000, NULL, '2020-11-11 13:49:33', '2020-11-11 13:49:33'),
(8, 3, 4, 4, 19000, 0, 76000, NULL, '2020-11-12 03:00:46', '2020-11-12 03:00:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jenkel` varchar(1) NOT NULL,
  `ttl` date NOT NULL,
  `alamat` text NOT NULL,
  `telp` varchar(20) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `member`
--

INSERT INTO `member` (`id`, `nama`, `jenkel`, `ttl`, `alamat`, `telp`, `created_date`, `updated_date`) VALUES
(4, 'Ahmad Heriawan Santoso', 'L', '1993-12-31', 'Semarang indonesia', '08291923839', '2020-11-11 06:18:38', '2020-11-11 06:18:38'),
(5, 'Ahmad Heriawan Santoso', 'L', '1993-12-31', 'Semarang indonesia', '08291923839', '2020-11-11 06:19:15', '2020-11-11 06:19:15'),
(6, 'Ahmad Heriawan Santoso', 'L', '1993-12-31', 'Semarang indonesia', '08291923839', '2020-11-11 06:19:42', '2020-11-11 06:19:42'),
(8, 'Ahmad Heriawan Santoso', 'L', '1993-12-31', 'Semarang indonesia', '08291923839', '2020-11-12 02:44:21', '2020-11-12 02:44:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

DROP TABLE IF EXISTS `produk`;
CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `percent_discount` int(3) NOT NULL DEFAULT 0,
  `promo` varchar(1) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama`, `id_kategori`, `qty`, `price`, `percent_discount`, `promo`, `created_date`, `updated_date`) VALUES
(1, 'Baju Anak', 1, 230, 25000, 0, NULL, '2020-11-11 10:21:58', '2020-11-11 10:21:58'),
(2, 'Kaos Polos', 2, 236, 180000, 0, '1', '2020-11-11 10:24:30', '2020-11-11 10:24:30'),
(4, 'Kaos Oblong', 1, 1, 89000, 0, NULL, '2020-11-11 05:15:34', '2020-11-11 05:15:34'),
(5, 'Kaos Oblong', 1, 2, 89000, 0, NULL, '2020-11-11 05:24:41', '2020-11-11 05:25:42'),
(6, 'Kaos Oblong', 1, 2, 89000, 0, NULL, '2020-11-12 02:22:04', '2020-11-12 02:22:04'),
(7, 'Kaos Oblong', 1, 2, 89000, 0, NULL, '2020-11-12 02:24:15', '2020-11-12 02:24:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `id_member` int(11) NOT NULL,
  `total` float NOT NULL,
  `flag` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `id_member`, `total`, `flag`, `created_date`, `updated_date`) VALUES
(1, 8, 2000, NULL, '2020-11-12 05:48:51', '2020-11-12 05:48:51'),
(2, 4, 5500, NULL, '2020-11-12 06:00:41', '2020-11-12 06:00:41'),
(3, 5, 5500, NULL, '2020-11-12 06:10:34', '2020-11-12 06:10:34'),
(4, 5, 4000, NULL, '2020-11-12 06:11:42', '2020-11-12 06:11:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id_member` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `id_member`, `created_date`, `updated_date`) VALUES
(1, 'aher1211', '$2y$10$3Ovwgq1jUmVVn8JuqIW9MuXxybySX0W.HEqGTpBtw9mSi4qXDkIxG', 'aher@gmail.com', 1, '2020-11-11 06:11:14', '2020-11-11 06:11:14'),
(2, 'aher1211', '$2y$10$qrPLTTYBsDRXRcnSTGXXUepUtwF8EKBn832axzeKvGboo7vRjF/Ga', 'aher@gmail.com', 2, '2020-11-11 06:15:49', '2020-11-11 06:15:49'),
(3, 'aher1211', '$2y$10$CEe5n1PQRXEfKLJRdGj6yOCBp8fCEcrBAJDNHqklaNgVF/925T3/2', 'aher@gmail.com', 3, '2020-11-11 06:18:01', '2020-11-11 06:18:01'),
(4, 'aher1211', '$2y$10$TZJyJnvQ33tJhKSiWz.37e.C0.aKw3ebnbbK1TWjkOOwWfOZTqmg2', 'aher@gmail.com', 4, '2020-11-11 06:18:38', '2020-11-11 06:18:38'),
(5, 'aher1211', '$2y$10$GbYvYB6zJVPHqb4T/.oxt.XaRzvrEMYc/SWYHfjgrspMnfuh.l4yq', 'aher@gmail.com', 5, '2020-11-11 06:19:15', '2020-11-11 06:19:15'),
(6, 'aher1211', '$2y$10$cgoT8mpCjIK2A8n00CA7IecL7FL6wlOf77Il7pHq9wxdkyMHqe2R6', 'aher@gmail.com', 6, '2020-11-11 06:19:42', '2020-11-11 06:19:42'),
(7, 'aher1211', '$2y$10$pIfXP6hq/biD1XqSaR9VSeqOcntnHuD5T7MfseNtBJ3oLo0ligfPi', 'aher1@gmail.com', 7, '2020-11-11 06:20:45', '2020-11-11 06:20:45'),
(8, 'aher1211', '$2y$10$5TYJ5L/.H9rQ6OSFJnxv1uZW5OHJ1MfWaqnGkv/xfPEMhf1iBGNfK', 'aher2@gmail.com', 8, '2020-11-12 02:44:21', '2020-11-12 02:44:21');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
