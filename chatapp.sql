-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 24 Oca 2024, 23:14:53
-- Sunucu sürümü: 10.4.28-MariaDB
-- PHP Sürümü: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `chatapp`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(11) DEFAULT NULL,
  `outgoing_msg_id` int(11) DEFAULT NULL,
  `msg` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `timestamp`) VALUES
(1, 828234392, 554622138, 'gdgdg', '2024-01-01 22:32:30'),
(2, 554622138, 828234392, 'kggıugıug', '2024-01-01 22:33:09'),
(3, 828234392, 554622138, 'ıugıugıugı', '2024-01-01 22:33:18'),
(4, 1554046833, 554622138, 'anne naber', '2024-01-02 11:25:54'),
(5, 554622138, 1554046833, 'iyidir', '2024-01-02 11:26:08'),
(6, 1554046833, 554622138, 'mesaş denedada', '2024-01-08 21:49:20'),
(7, 554622138, 1554046833, 'fasfasfafafaf', '2024-01-08 21:49:29'),
(8, 554622138, 1122295144, 'alfaıfafas', '2024-01-08 22:29:35'),
(9, 1122295144, 554622138, 'asfafasf', '2024-01-08 22:29:40');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `unique_id` int(11) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `img` varchar(100) DEFAULT 'default_avatar.png',
  `status` varchar(50) DEFAULT 'Offline',
  `seed` int(11) NOT NULL,
  `user_id` int(50) NOT NULL,
  `reset_token` varchar(1000) DEFAULT NULL,
  `verification_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`unique_id`, `fname`, `lname`, `email`, `password`, `img`, `status`, `seed`, `user_id`, `reset_token`, `verification_code`) VALUES
(203554698, 'faf', 'asfa', 'c@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '1706119852sddefault.jpg', 'Offline now', 0, 0, NULL, ''),
(513697345, 'tarik', 'yagli', 'trkygl1900@gmail.com', '$2y$10$D7BLm4ni0aWz2dh96h0.YuhHCeEq6eL6oTv.hArvkPg2qyc9FNkkO', '17061301751706119379sddefault.jpg', 'Inactive', 0, 0, NULL, '75cec56c897cbf105a4b329d0dcd011e'),
(554622138, 'kaan', 'turan', 'kaanturan005@gmail.com', '7bcf1779e7194abca1eb9bc54899aff7', '1705533068WhatsApp Image 2024-01-17 at 19.25.19 (1).jpeg', 'Offline now', 0, 0, 'e9d5e9059bb90d9d0c6a3465320ea691', ''),
(771655470, 'asd', 'asd', 'b@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '1706119754sddefault.jpg', 'Offline now', 0, 0, NULL, ''),
(828234392, 'tarik', 'yaglı', 'tarikyagli05@gmail.com', 'f8370dd2fe1f3de4c00e538135eb7e5e', '1704147927pexels-simon-berger-1183099.jpg', 'Active now', 0, 0, NULL, ''),
(1070464665, 'enes', 'alpay', 'enesalpay05@gmail.com', 'c3cdbe10cc8ae980d3587012adc5258f', '1704751642WhatsApp Image 2024-01-04 at 20.26.04.jpeg', 'Offline now', 0, 0, NULL, ''),
(1097976702, 'kaan', 'turan', 'kaanturan627@gmail.com', '7bcf1779e7194abca1eb9bc54899aff7', '1706120316sddefault.jpg', 'Offline now', 0, 0, 'fbe28806a7835182afecb1ac5e95b563', ''),
(1122295144, 'eren', 'yapal', 'erenyapal05@gmail.com', '291b74dec227f7ba5d04a1db8e99588b', '1704752969WhatsApp Image 2024-01-04 at 20.26.04.jpeg', 'Offline now', 0, 0, NULL, ''),
(1275666940, 'kaan', 'turan', 'kaanturan626@gmail.com', '202cb962ac59075b964b07152d234b70', '17061344081706119379sddefault.jpg', 'Active now', 0, 0, NULL, 'bd781494d3c3c02d134ef23e5cf5ce36'),
(1583445253, 'sfasf', 'afasf', 'a@gmail.com', '202cb962ac59075b964b07152d234b70', '1706119535sddefault.jpg', 'Offline now', 0, 0, NULL, '');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`unique_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `unique_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1625323026;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
