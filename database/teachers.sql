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
-- Структура таблицы `teachers`
--

CREATE TABLE `teachers` (
  `id` int NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `patronymic` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `education_level` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `department_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `teachers`
--

INSERT INTO `teachers` (`id`, `surname`, `name`, `patronymic`, `education_level`, `email`, `department_id`) VALUES
(11, 'Иванов', 'Иван', 'Иванович', 'Доктор наук', 'hghrht@mail.ru', 1),
(12, 'Петров', 'Петр', 'Петрович', 'Кандидат наук', 'eryehe@mail.ru', 2),
(13, 'Сидоров', 'Сидор', 'Сидорович', 'Магистр', 'hetrjtrj@mail.ru', 3),
(14, 'Смирнова', 'Анна', 'Сергеевна', 'Кандидат наук', 'rjrjjt@mail.ru', 1),
(15, 'Кузнецов', 'Дмитрий', 'Алексеевич', 'Доцент', 'jtjrjsvd@mail.ru', 4),
(16, 'Соколова', 'Елена', 'Васильевна', 'Магистр', 'uounrb@mail.ru', 2),
(17, 'Попов', 'Александр', 'Михайлович', 'Доктор наук', 'nrcvng@mail.ru', 5),
(18, 'Лебедева', 'Ольга', 'Ивановна', 'Кандидат наук', 'trbdnf@mail.ru', 3),
(19, 'Новиков', 'Сергей', 'Викторович', 'Магистр', 'qewxdvdf@mail.ru', 4),
(20, 'Морозова', 'Мария', 'Павловна', 'Доцент', 'mumynbde@mail.ru', 5);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_department_id` (`department_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `FK_department_id` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
