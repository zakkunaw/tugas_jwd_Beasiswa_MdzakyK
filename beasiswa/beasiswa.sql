-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Sep 2024 pada 19.02
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beasiswa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin') DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `nama`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftar`
--

CREATE TABLE `pendaftar` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `gpa` decimal(3,2) NOT NULL,
  `file` varchar(255) NOT NULL,
  `status` enum('belum_terverifikasi','Diterima') DEFAULT 'belum_terverifikasi',
  `password` varchar(255) NOT NULL,
  `beasiswa` enum('KIP','Olahraga','Akademik','Prestasi','Lainnya') DEFAULT 'Lainnya'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pendaftar`
--

INSERT INTO `pendaftar` (`id`, `nama`, `email`, `phone`, `semester`, `gpa`, `file`, `status`, `password`, `beasiswa`) VALUES
(14, 'Denis Sumargo', 'polapoli74@gmail.com', '08976787678', 'Semester 2', '3.00', 'd6f8f-04_fr.ia.02-tugas-praktik-demonstrasi_v3-esron.pdf', 'belum_terverifikasi', '$2y$10$yRtSGK4CuQ9FcANqkuTLjuhg6nfkVuBsMVnftOflsexVFe8685dqK', 'KIP'),
(15, 'aril noah', 'aril@gmail.com', '087263728273', 'Semester 3', '3.00', 'd6f8f-04_fr.ia.02-tugas-praktik-demonstrasi_v3-esron.pdf', 'belum_terverifikasi', '$2y$10$bEwTXEFX2PZdU8BN6p6mdOCOrnpf/j9qeK8keXQJJdWT4odA1iAp6', 'Akademik'),
(17, 'Jaki Kurniawan', 'dzakikurniawan26@gmail.com', '085861272870', 'Semester 3', '3.66', 'd6f8f-04_fr.ia.02-tugas-praktik-demonstrasi_v3-esron.pdf', '', '$2y$10$q08UFndLtSbfTzkCo2WDA.1D7DTcLsbZTWEc16QGrXVTseAZvbuCy', 'Prestasi'),
(18, 'Dun saridun', 'dundun@gmail.com', '083747381234', 'Semester 2', '3.48', 'd6f8f-04_fr.ia.02-tugas-praktik-demonstrasi_v3-esron.pdf', 'belum_terverifikasi', '$2y$10$y8GcBkKlvzU4q9kqF.AbYOkF/4KTK4qMc1tPohJHdUUli3SKtoEfa', 'Prestasi'),
(19, 'Syahrini', 'syahrini@gmail.com', '0897374789', 'Semester 2', '2.22', 'd6f8f-04_fr.ia.02-tugas-praktik-demonstrasi_v3-esron.pdf', 'belum_terverifikasi', '$2y$10$egU84ymoDZOL3Fe.QKJncewXQMFVIsB4lzLQSCQmWB.x/mkceUofm', 'Lainnya');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pendaftar`
--
ALTER TABLE `pendaftar`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pendaftar`
--
ALTER TABLE `pendaftar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
