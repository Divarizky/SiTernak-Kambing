-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2025 at 06:51 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_sensor`
--

CREATE TABLE `data_sensor` (
  `id` int(11) NOT NULL,
  `id_kandang` int(11) DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `suhu` decimal(4,1) NOT NULL,
  `kelembapan` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_sensor`
--

INSERT INTO `data_sensor` (`id`, `id_kandang`, `timestamp`, `suhu`, `kelembapan`) VALUES
(6, 4, '2025-07-17 11:02:44', 28.0, 50),
(7, 4, '2025-07-17 11:12:06', 30.0, 60);

-- --------------------------------------------------------

--
-- Table structure for table `kambing`
--

CREATE TABLE `kambing` (
  `id_kambing` int(11) NOT NULL,
  `id_kandang` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `jenis_kelamin` enum('Jantan','Betina') DEFAULT NULL,
  `ras` varchar(50) DEFAULT NULL,
  `berat` decimal(10,2) DEFAULT NULL,
  `status_kesehatan` varchar(25) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `asal` enum('Lahir','Adopsi') DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kambing`
--

INSERT INTO `kambing` (`id_kambing`, `id_kandang`, `name`, `age`, `jenis_kelamin`, `ras`, `berat`, `status_kesehatan`, `tanggal_lahir`, `tanggal_masuk`, `asal`, `last_updated`) VALUES
(9, 4, 'KB005', 24, 'Jantan', 'Nubian', 52.00, 'Sehat', '2025-07-10', '2025-07-10', 'Lahir', '2025-07-16 19:47:22'),
(10, 4, 'KB002', 2, 'Jantan', 'Saanen', 34.00, 'Perlu Perhatian', '2025-05-15', '2025-07-16', 'Adopsi', '2025-07-16 22:04:17');

-- --------------------------------------------------------

--
-- Table structure for table `kandang`
--

CREATE TABLE `kandang` (
  `id_kandang` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kandang`
--

INSERT INTO `kandang` (`id_kandang`, `nama`, `lokasi`) VALUES
(3, 'Kandang 1', 'Area Utara'),
(4, 'Kandang 2', 'Area Barat');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_kesehatan`
--

CREATE TABLE `riwayat_kesehatan` (
  `id_riwayat` int(11) NOT NULL,
  `id_kambing` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `diagnosa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `username`, `password`, `role`) VALUES
(5, 'admin', 'admin', '$2y$10$TKpGe3XcYJPhJqqAo1sPG.Xn7XcaYCXxWPl67NpP08M/kpeBTnp/2', 'user'),
(6, 'tester', 'tester', '$2y$10$3VciaLWflS9PKKIopEEsmeqsFomXGuJeHvGlPXY6YDBBxdbVmBy9G', 'user');

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
-- Indexes for table `data_sensor`
--
ALTER TABLE `data_sensor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kandang` (`id_kandang`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
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
-- AUTO_INCREMENT for table `data_sensor`
--
ALTER TABLE `data_sensor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kambing`
--
ALTER TABLE `kambing`
  MODIFY `id_kambing` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kandang`
--
ALTER TABLE `kandang`
  MODIFY `id_kandang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `riwayat_kesehatan`
--
ALTER TABLE `riwayat_kesehatan`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `berat_kambing`
--
ALTER TABLE `berat_kambing`
  ADD CONSTRAINT `berat_kambing_ibfk_1` FOREIGN KEY (`id_kambing`) REFERENCES `kambing` (`id_kambing`);

--
-- Constraints for table `data_sensor`
--
ALTER TABLE `data_sensor`
  ADD CONSTRAINT `data_sensor_ibfk_1` FOREIGN KEY (`id_kandang`) REFERENCES `kandang` (`id_kandang`);

--
-- Constraints for table `kambing`
--
ALTER TABLE `kambing`
  ADD CONSTRAINT `kambing_ibfk_1` FOREIGN KEY (`id_kandang`) REFERENCES `kandang` (`id_kandang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
