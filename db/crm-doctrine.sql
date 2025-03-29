-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 29, 2025 alle 17:36
-- Versione del server: 10.4.27-MariaDB
-- Versione PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crm-doctrine`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `calls`
--

CREATE TABLE `calls` (
  `id` int(11) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `call_time` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `lead_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `calls`
--

INSERT INTO `calls` (`id`, `notes`, `call_time`, `status`, `lead_id`) VALUES
(4, 'Commodo aliquip dolor eiusmod quis sit esse quis elit aliqua.', '2025-01-14 13:11:00', 'Canceled', 1),
(5, 'Aliquip consequat dolore quis amet quis lorem sed fugiat sed.', '2025-03-01 13:12:00', 'Completed', 1),
(6, 'Magna duis enim magna dolor commodo elit consectetur nostrud consequat.', '2025-03-12 19:10:00', 'Canceled', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `title` varchar(220) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `uploaded_at` datetime NOT NULL,
  `lead_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `documents`
--

INSERT INTO `documents` (`id`, `title`, `filename`, `path`, `uploaded_at`, `lead_id`) VALUES
(8, '54aa3709', '1_54aa3709.png', NULL, '2025-03-28 18:04:35', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `event` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `lead_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `history`
--

INSERT INTO `history` (`id`, `created_at`, `event`, `type`, `lead_id`) VALUES
(1, '2025-03-22 14:37:47', 'Added calls', '', 1),
(2, '2025-03-28 18:59:22', 'Create new task', 'Task', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT 1,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `zip` int(5) NOT NULL,
  `country` varchar(100) NOT NULL,
  `source` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `leads`
--

INSERT INTO `leads` (`id`, `active`, `first_name`, `last_name`, `email`, `phone`, `city`, `address`, `zip`, `country`, `source`, `status`, `created_at`, `updated_at`, `user_id`, `status_id`, `source_id`, `notes`) VALUES
(1, 1, 'Luigi', 'Verdi', 'luigi@verdi.it', '32345643643', 'Milan', 'Corso Buenos Aires, 1', 20124, 'Italy', 'Facebook', 'New', '2025-03-14 11:12:13', '2025-03-14 11:12:13', 1, 1, 1, ''),
(2, 1, 'sedfsfsfsdf', 'wefwefcwefwefe', '3123213@lklk.gg', '1233232423', '12312', '324', 32423, '', '', '', '2025-03-21 14:22:33', '2025-03-21 14:49:50', 1, 15, 8, NULL),
(4, 1, 'ciao', 'wfwefwefwefw', 'efw@lk.lk', 'efwef', 'dfsdf', 'fsdfs', 6678, 'Italy', '', '', '2025-03-21 14:53:21', '2025-03-24 10:27:38', 1, 11, 1, 'aaaaaaaaaaaaaaaaaaaaaa\r\nsdfsd\r\nfsd\r\nf\r\nsdf\r\nsd\r\nfsd');

-- --------------------------------------------------------

--
-- Struttura della tabella `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `lead_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `notes`
--

INSERT INTO `notes` (`id`, `content`, `created_at`, `lead_id`) VALUES
(1, 'sdfsdfewrfefwefwef\r\nterter\r\ntert', '2025-06-27 10:41:00', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `quotations`
--

CREATE TABLE `quotations` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `quotations`
--

INSERT INTO `quotations` (`id`, `lead_id`, `code`, `title`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'bbbbbbbbbbb', 'aaaaaaaaaaaa', 'Approved', '2025-03-06 11:12:43', '2025-03-26 20:36:54'),
(5, 1, 'wefwef', 'wefwefe', 'Pending', '2025-03-26 20:13:11', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` int(11) NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`price` * `quantity`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `quotation_items`
--

INSERT INTO `quotation_items` (`id`, `quotation_id`, `service_name`, `description`, `price`, `quantity`) VALUES
(3, 5, 'wefwef', 'wefewf', '3232.00', 1),
(7, 1, '1111111111', '222222222', '300.00', 1),
(8, 1, '3333333333333', '44444444', '566.00', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `sources`
--

CREATE TABLE `sources` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `sources`
--

INSERT INTO `sources` (`id`, `name`) VALUES
(1, 'Facebook'),
(2, 'Google'),
(3, 'Email'),
(5, 'Referral'),
(6, 'Website'),
(7, 'Landing page'),
(8, 'Seo'),
(9, 'Sms marketing'),
(10, 'Newsletter');

-- --------------------------------------------------------

--
-- Struttura della tabella `statuses`
--

CREATE TABLE `statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `statuses`
--

INSERT INTO `statuses` (`id`, `name`) VALUES
(1, 'New'),
(2, 'Contacted'),
(3, 'Qualified'),
(8, 'Interested'),
(9, 'Waiting for Response'),
(10, 'Negotiation'),
(11, 'Converted'),
(12, 'Lost'),
(14, 'Follow-up scheduled'),
(15, 'Not suitable'),
(16, 'Closed');

-- --------------------------------------------------------

--
-- Struttura della tabella `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `due_date` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `lead_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tasks`
--

INSERT INTO `tasks` (`id`, `description`, `due_date`, `status`, `lead_id`) VALUES
(1, 'sdfsdfewrfefwefwef', '2025-06-27 10:41:00', 'In progress', 1),
(2, 'efdf', '2025-05-08 10:47:00', 'Canceled', 1),
(3, 'asdasdasd', '2025-03-01 18:59:00', 'In progress', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email_verified` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `email_verified`, `created_at`, `updated_at`) VALUES
(1, 'Mario Rossi', 'mario@rossi.it', '$2y$10$9mRr2BfGp69/Wxx/ns9nluxfbGdQ9CSFYz5pGXszt7Nfbxbh0.LDu', 0, '2025-03-14 11:01:58', '2025-03-14 11:01:58');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `calls`
--
ALTER TABLE `calls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_id` (`lead_id`);

--
-- Indici per le tabelle `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_id` (`lead_id`);

--
-- Indici per le tabelle `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_id` (`lead_id`);

--
-- Indici per le tabelle `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `source_id` (`source_id`);

--
-- Indici per le tabelle `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_id` (`lead_id`);

--
-- Indici per le tabelle `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_id` (`lead_id`),
  ADD KEY `code` (`code`);

--
-- Indici per le tabelle `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_id` (`quotation_id`);

--
-- Indici per le tabelle `sources`
--
ALTER TABLE `sources`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_id` (`lead_id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `calls`
--
ALTER TABLE `calls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `sources`
--
ALTER TABLE `sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT per la tabella `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT per la tabella `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `calls`
--
ALTER TABLE `calls`
  ADD CONSTRAINT `calls_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`);

--
-- Limiti per la tabella `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`);

--
-- Limiti per la tabella `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`);

--
-- Limiti per la tabella `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`);

--
-- Limiti per la tabella `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `quotation_items_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
