-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2026 at 04:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `logbook_magang`
--

-- --------------------------------------------------------

--
-- Table structure for table `logbooks`
--

CREATE TABLE `logbooks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hari_tanggal` date DEFAULT NULL,
  `nama_pendamping_magang` varchar(255) DEFAULT NULL,
  `nama_pendamping_ruangan` varchar(255) DEFAULT NULL,
  `tempat_ruangan` varchar(255) DEFAULT NULL,
  `uraian_kegiatan` text DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `feedback_pendamping` text DEFAULT NULL,
  `feedback_petugas` text DEFAULT NULL,
  `approved_pendamping` int(11) DEFAULT 0,
  `approved_petugas` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logbooks`
--

INSERT INTO `logbooks` (`id`, `user_id`, `hari_tanggal`, `nama_pendamping_magang`, `nama_pendamping_ruangan`, `tempat_ruangan`, `uraian_kegiatan`, `feedback`, `feedback_pendamping`, `feedback_petugas`, `approved_pendamping`, `approved_petugas`) VALUES
(2, 2, '2026-01-22', 'Nasrul Wahid, SIP.', 'ruang koleksi anak', 'U', 'Bikin project', NULL, NULL, 'bagus sip mantap', 1, 1),
(3, 2, '2026-01-22', 'Nasrul Wahid, SIP.', 'Mas Irfan', 'U', 'i', NULL, NULL, NULL, 0, 0),
(4, 2, '2026-01-27', 'Nasrul Wahid, SIP', 'Mas Irfan', 'Ruang Koleksi Umum', 'Shelving', NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` text NOT NULL,
  `nim_nip` text NOT NULL,
  `nim_hash` varchar(255) DEFAULT NULL,
  `asal` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `nim_nip`, `nim_hash`, `asal`, `password`, `role`) VALUES
(2, '1932l+oSKDRfrBNQYsErXg==', 'nZC+Q6lyN0jSVj+QPv/m4g==', '64e536294e75c0413d9702f93bbf622226a050e59e39ffe5671022e8a346ac55', 'UPN \"Veteran\" Yogyakarta', '$2y$10$KqrqLpzLwhFLBuffwHF9X.x1g/bT6BUtE74AX1RMmARi4.sXGFjCO', 'pemagang'),
(3, 'wMHXy0foUlFzGPpXOrrG6A==', 'bMbIoNM5SjeMwM+WrB5nDQ==', '4f9f10b304cfe9b2b11fcb1387f694e18f08ea358c7e9f567434d3ad6cbd7fc4', 'UPN Veteran Yogyakarta', '$2y$10$9i7LxE7SKWiHp6BtKjwmxetrZHvvmxxiKFfdp0TrkLyXBqKPdeQn.', 'pemagang'),
(4, 'pendamping', '123234567', '2963b6f12fbb0c1fd51cfd2d4bb487a6b0447d47c985c233cb0cf4a18b35e9f2', 'pendamping', '$2y$10$duIOR5XhiUkerlDWIcCX2OBJnH1zfXPARKHNVwxobvUIdwiLekiy6', 'pendamping'),
(5, 'petugas', '1234567', '8bb0cf6eb9b17d0f7d22b456f121257dc1254e1f01665370476383ea776df414', 'petugas koleksi anak', '$2y$10$wJNNgqqpthgdwvZ00.ZYyuxhMqTsGUwdUJWAe99nJ5RQelaTY.hLO', 'petugas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logbooks`
--
ALTER TABLE `logbooks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim_hash` (`nim_hash`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logbooks`
--
ALTER TABLE `logbooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logbooks`
--
ALTER TABLE `logbooks`
  ADD CONSTRAINT `logbooks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
