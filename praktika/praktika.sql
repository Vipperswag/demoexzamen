-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Дек 11 2025 г., 11:17
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `praktika`
--

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id_group` int(2) NOT NULL,
  `group_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id_group`, `group_name`) VALUES
(1, 'ИСВ-23'),
(2, 'ИСП-23'),
(3, 'ИС-23'),
(4, 'ИСП-24'),
(5, 'ИС-24');

-- --------------------------------------------------------

--
-- Структура таблицы `practice_reports`
--

CREATE TABLE `practice_reports` (
  `id_report` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(2) NOT NULL,
  `specialty_id` int(2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `organization_name` varchar(100) NOT NULL,
  `organization_address` varchar(200) NOT NULL,
  `supervisor_name` varchar(100) NOT NULL,
  `supervisor_position` varchar(100) NOT NULL,
  `work_description` text NOT NULL,
  `status_id` int(1) NOT NULL DEFAULT 1,
  `teacher_comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `practice_reports`
--

INSERT INTO `practice_reports` (`id_report`, `user_id`, `group_id`, `specialty_id`, `start_date`, `end_date`, `organization_name`, `organization_address`, `supervisor_name`, `supervisor_position`, `work_description`, `status_id`, `teacher_comment`, `created_at`) VALUES
(1, 2, 2, 2, '2025-12-16', '2025-12-26', 'wsfsdf', 'sdf', 'sdfsdf', 'sdfsdf', 'sfdsdfsdf', 3, '', '2025-12-11 09:46:37'),
(2, 3, 1, 1, '2025-12-02', '2025-12-22', 'ООО Максима Групп', '140090, г. Москва, шоссе Энтузиастов, д.12', 'М.В.Максимов', 'Зам. зав. магазина', 'Сортировка одежды и ее развешивание', 1, NULL, '2025-12-11 10:17:35');

-- --------------------------------------------------------

--
-- Структура таблицы `specialties`
--

CREATE TABLE `specialties` (
  `id_specialty` int(2) NOT NULL,
  `specialty_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `specialties`
--

INSERT INTO `specialties` (`id_specialty`, `specialty_name`) VALUES
(1, 'Информационные системы и программирование'),
(2, 'Программирование в компьютерных системах'),
(3, 'Сетевое и системное администрирование');

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE `status` (
  `id_status` int(1) NOT NULL,
  `name_status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id_status`, `name_status`) VALUES
(1, 'На проверке'),
(2, 'Принято'),
(3, 'На доработку');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `user_type_id` int(1) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `otchestvo` varchar(50) NOT NULL,
  `group_id` int(2) DEFAULT NULL,
  `student_card` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id_user`, `user_type_id`, `surname`, `name`, `otchestvo`, `group_id`, `student_card`, `phone`, `email`, `username`, `password`) VALUES
(1, 2, 'Миронов', 'Денис', 'Романич', NULL, NULL, NULL, 'teacher@college.ru', 'teacher', '2c8ade1dca7c5fa01cbceaf1e6bd654b'),
(2, 1, 'test', 'test', 'test', 3, '123', '123', '2@2', '123', '202cb962ac59075b964b07152d234b70'),
(3, 1, 'test', 'test', 'test', 1, '123123', NULL, '123@123', 'test', '098f6bcd4621d373cade4e832627b4f6');

-- --------------------------------------------------------

--
-- Структура таблицы `user_type`
--

CREATE TABLE `user_type` (
  `id_user_type` int(1) NOT NULL,
  `name_user` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `user_type`
--

INSERT INTO `user_type` (`id_user_type`, `name_user`) VALUES
(1, 'студент'),
(2, 'преподаватель');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id_group`);

--
-- Индексы таблицы `practice_reports`
--
ALTER TABLE `practice_reports`
  ADD PRIMARY KEY (`id_report`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `specialty_id` (`specialty_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Индексы таблицы `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id_specialty`);

--
-- Индексы таблицы `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_type_id` (`user_type_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id_user_type`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `practice_reports`
--
ALTER TABLE `practice_reports`
  MODIFY `id_report` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `practice_reports`
--
ALTER TABLE `practice_reports`
  ADD CONSTRAINT `practice_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `practice_reports_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id_group`),
  ADD CONSTRAINT `practice_reports_ibfk_3` FOREIGN KEY (`specialty_id`) REFERENCES `specialties` (`id_specialty`),
  ADD CONSTRAINT `practice_reports_ibfk_4` FOREIGN KEY (`status_id`) REFERENCES `status` (`id_status`);

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id_user_type`),
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id_group`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
