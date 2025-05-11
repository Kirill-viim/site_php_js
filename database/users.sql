-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.2
-- Время создания: Май 11 2025 г., 15:06
-- Версия сервера: 8.2.0
-- Версия PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `site`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `surname` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `login` text COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL,
  `access` int NOT NULL,
  `email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `activ_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `phone` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fav_obj` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `extra` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `surname`, `name`, `login`, `password`, `access`, `email`, `activ_token`, `phone`, `department`, `fav_obj`, `extra`) VALUES
(1, 'Карасев', 'Карась', 'user', 'd41d8cd98f00b204e9800998ecf8427e', 0, '', '', '', '', '', ''),
(3, 'Alisawi', 'Nasser', 'naro', '310dcbbf4cce62f762a2aaa148d556bd', 0, '', '', '', '', '', ''),
(4, 'Протасов', 'Игнат', 'ignat', 'd41d8cd98f00b204e9800998ecf8427e', 0, '', '', '', '', '', ''),
(5, 'rootov', 'root', 'root', '827ccb0eea8a706c4c34a16891f84e7b', 1, 'venediktova-eleo@mail.ru', NULL, '9185600026', '', '', '???'),
(6, 'www', 'qqq', 'kirill', '202cb962ac59075b964b07152d234b70', 0, 'venediktova-eleo@mail.ru', NULL, '', '', '', ''),
(7, '34543', '55475', '865', '35cf8659cfcb13224cbd47863a34fc58', 0, NULL, NULL, '', '', '', ''),
(8, 'hfh', 'fhfh', 'qwe', '202cb962ac59075b964b07152d234b70', 0, NULL, NULL, '', '', '', ''),
(9, 'ii', 'oo', 'qwe', '698d51a19d8a121ce581499d7b701668', 0, NULL, NULL, '', '', '', ''),
(14, '22222', '11111', '11111', '202cb962ac59075b964b07152d234b70', 0, 'test.activation@mail.ru', '8f7b05050b987b1b6cc2ca19a028ad4e4e7539e94bc683a6ad114b8bffb751a8', '', '', '', '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
