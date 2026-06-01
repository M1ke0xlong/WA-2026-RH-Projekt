-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 01. čen 2026, 15:50
-- Verze serveru: 10.4.32-MariaDB
-- Verze PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `wa-2026-rh-projekt`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `anime`
--

CREATE TABLE `anime` (
  `id` int(11) NOT NULL,
  `primary_title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `episodes` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `anime`
--

INSERT INTO `anime` (`id`, `primary_title`, `description`, `episodes`, `image`, `added_by`, `updated_by`, `updated_at`, `created_at`) VALUES
(3, 'Šingeki no kjodži', 'Lidé byli nuceni bojovat s obry o záchranu svého pokolení. Když se schylovalo k nejhoršímu, postavili několik měst pod ochranou mohutných zdí, které obři nedokázali překonat. Po jedno celé století si lidé za hradbami mohli oddechnout a žít v relativním míru, avšak odříznuti od okolního světa. Mír ale trval jen do dne, než jedno z těchto měst, obehnané zdí, Maria, bylo napadeno dosud nevídaným šedesátimetrovým obrem, kterému se ochranu města podařilo překonat. Ústřední postava příběhu Aren Jaegar se svou adoptivní sestrou Mikasou při útoku přicházejí o svoji matku a vydávají se vstříc nehostinému světu.', 89, 'cover_6a1c69aa7caab.webp', 1, NULL, NULL, '2026-05-31 17:02:34'),
(4, 'Darling in the Franxx', NULL, 24, 'cover_6a1d52bf64b9f.jpg', 1, 1, '2026-06-01 14:31:42', '2026-06-01 09:37:03');

-- --------------------------------------------------------

--
-- Struktura tabulky `anime_gallery`
--

CREATE TABLE `anime_gallery` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `anime_gallery`
--

INSERT INTO `anime_gallery` (`id`, `anime_id`, `image_path`) VALUES
(1, 4, 'gal_6a1d52bf667c0.webp'),
(2, 4, 'gal_6a1d52bf67208.jpg'),
(3, 4, 'gal_6a1d7bae773b7.jpg');

-- --------------------------------------------------------

--
-- Struktura tabulky `anime_titles`
--

CREATE TABLE `anime_titles` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `type` enum('english','japanese','romaji') NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `anime_titles`
--

INSERT INTO `anime_titles` (`id`, `anime_id`, `type`, `title`) VALUES
(7, 3, 'english', 'Attack on Titan'),
(8, 3, 'japanese', '進撃の巨人'),
(9, 3, 'romaji', 'Shingeki no kyojin'),
(19, 4, 'english', 'Darling in the Franxx'),
(20, 4, 'japanese', 'ダーリン・イン・ザ・フランキス'),
(21, 4, 'romaji', 'darin in za furankisu');

-- --------------------------------------------------------

--
-- Struktura tabulky `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `avatar`, `created_at`) VALUES
(1, 'Raho', 'rahomancu@gmail.com', '$2y$10$XyKvQGvquyWgdj7A.e6bQuFgjddX9tV5yO7X5Bi2CUypAm7gk0LvO', 'admin', 'avatar_1_1780250251.png', '2026-05-31 16:51:29');

-- --------------------------------------------------------

--
-- Struktura tabulky `user_anime_list`
--

CREATE TABLE `user_anime_list` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `status` enum('watching','completed','on_hold','dropped','plan_to_watch') NOT NULL,
  `score` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `user_anime_list`
--

INSERT INTO `user_anime_list` (`id`, `user_id`, `anime_id`, `status`, `score`, `comment`, `updated_at`) VALUES
(1, 1, 3, 'watching', 10, NULL, '2026-06-01 09:20:02'),
(9, 1, 4, 'completed', 9, NULL, '2026-06-01 09:37:55');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `anime`
--
ALTER TABLE `anime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexy pro tabulku `anime_gallery`
--
ALTER TABLE `anime_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- Indexy pro tabulku `anime_titles`
--
ALTER TABLE `anime_titles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- Indexy pro tabulku `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anime_id` (`anime_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexy pro tabulku `user_anime_list`
--
ALTER TABLE `user_anime_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_anime` (`user_id`,`anime_id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `anime`
--
ALTER TABLE `anime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `anime_gallery`
--
ALTER TABLE `anime_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `anime_titles`
--
ALTER TABLE `anime_titles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pro tabulku `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pro tabulku `user_anime_list`
--
ALTER TABLE `user_anime_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `anime`
--
ALTER TABLE `anime`
  ADD CONSTRAINT `anime_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Omezení pro tabulku `anime_gallery`
--
ALTER TABLE `anime_gallery`
  ADD CONSTRAINT `anime_gallery_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `anime_titles`
--
ALTER TABLE `anime_titles`
  ADD CONSTRAINT `anime_titles_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `user_anime_list`
--
ALTER TABLE `user_anime_list`
  ADD CONSTRAINT `user_anime_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_anime_list_ibfk_2` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
