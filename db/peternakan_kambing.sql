-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2025 at 02:01 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peternakan_kambing`
--

-- --------------------------------------------------------

--
-- Table structure for table `berat_kambing`
--

CREATE TABLE `berat_kambing` (
  `id_berat` int(11) NOT NULL,
  `id_kambing` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `berat_kg` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `berat_kambing`
--

INSERT INTO `berat_kambing` (`id_berat`, `id_kambing`, `tanggal`, `berat_kg`) VALUES
(1, 1, '2024-02-15', '5.50'),
(2, 1, '2024-04-15', '8.20'),
(3, 1, '2024-06-15', '12.10'),
(4, 2, '2024-04-20', '4.80'),
(5, 2, '2024-06-20', '7.90');

-- --------------------------------------------------------

--
-- Table structure for table `kambing`
--

CREATE TABLE `kambing` (
  `id_kambing` int(11) NOT NULL,
  `id_kandang` int(11) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Jantan','Betina') DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `asal` enum('Lahir','Adopsi') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kambing`
--

INSERT INTO `kambing` (`id_kambing`, `id_kandang`, `tanggal_lahir`, `jenis_kelamin`, `tanggal_masuk`, `asal`) VALUES
(1, 1, '2024-01-15', 'Jantan', '2024-01-15', 'Lahir'),
(2, 1, '2024-03-20', 'Betina', '2024-03-20', 'Lahir'),
(3, 2, '2023-11-05', 'Jantan', '2024-05-01', 'Adopsi'),
(4, 1, '2025-07-10', 'Betina', '2025-07-14', 'Lahir'),
(5, 1, '2025-07-10', 'Betina', '2025-07-14', 'Lahir'),
(6, 1, '2025-07-10', 'Betina', '2025-07-14', 'Lahir');

-- --------------------------------------------------------

--
-- Table structure for table `kandang`
--

CREATE TABLE `kandang` (
  `id_kandang` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kandang`
--

INSERT INTO `kandang` (`id_kandang`, `nama`, `lokasi`) VALUES
(1, 'Kandang A1', 'Area Utara'),
(2, 'Kandang B2', 'Area Selatan');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_kesehatan`
--

CREATE TABLE `riwayat_kesehatan` (
  `id_riwayat` int(11) NOT NULL,
  `id_kambing` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `deskripsi` text,
  `diagnosa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `riwayat_kesehatan`
--

INSERT INTO `riwayat_kesehatan` (`id_riwayat`, `id_kambing`, `tanggal`, `deskripsi`, `diagnosa`) VALUES
(1, 1, '2024-06-10', 'Nafsu makan menurun, terlihat lesu.', 'Indikasi cacingan'),
(2, 3, '2024-05-15', 'Pincang pada kaki depan kanan setelah bermain.', 'Keseleo ringan');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `username`, `password_hash`, `role`) VALUES
(4, 'Admin User', 'admin1', '$2y$10$TCYdBYqJZJhc0FxAS/U95efZi47vW77d4GdOsQ9TMve43ZRhyHGr6', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berat_kambing`
--
ALTER TABLE `berat_kambing`
  ADD PRIMARY KEY (`id_berat`),
  ADD KEY `id_kambing` (`id_kambing`);

--
-- Indexes for table `kambing`
--
ALTER TABLE `kambing`
  ADD PRIMARY KEY (`id_kambing`),
  ADD KEY `id_kandang` (`id_kandang`);

--
-- Indexes for table `kandang`
--
ALTER TABLE `kandang`
  ADD PRIMARY KEY (`id_kandang`);

--
-- Indexes for table `riwayat_kesehatan`
--
ALTER TABLE `riwayat_kesehatan`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_kambing` (`id_kambing`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berat_kambing`
--
ALTER TABLE `berat_kambing`
  MODIFY `id_berat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kambing`
--
ALTER TABLE `kambing`
  MODIFY `id_kambing` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kandang`
--
ALTER TABLE `kandang`
  MODIFY `id_kandang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `riwayat_kesehatan`
--
ALTER TABLE `riwayat_kesehatan`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `berat_kambing`
--
ALTER TABLE `berat_kambing`
  ADD CONSTRAINT `berat_kambing_ibfk_1` FOREIGN KEY (`id_kambing`) REFERENCES `kambing` (`id_kambing`);

--
-- Constraints for table `kambing`
--
ALTER TABLE `kambing`
  ADD CONSTRAINT `kambing_ibfk_1` FOREIGN KEY (`id_kandang`) REFERENCES `kandang` (`id_kandang`);

--
-- Constraints for table `riwayat_kesehatan`
--
ALTER TABLE `riwayat_kesehatan`
  ADD CONSTRAINT `riwayat_kesehatan_ibfk_1` FOREIGN KEY (`id_kambing`) REFERENCES `kambing` (`id_kambing`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
