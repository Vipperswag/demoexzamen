-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 30 2025 г., 14:21
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
-- База данных: `moy_ne_sam`
--

-- --------------------------------------------------------

--
-- Структура таблицы `pay_type`
--

CREATE TABLE `pay_type` (
  `id_pay_type` int(1) NOT NULL,
  `name_pay` varchar(17) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `pay_type`
--

INSERT INTO `pay_type` (`id_pay_type`, `name_pay`) VALUES
(1, 'наличные'),
(2, 'банковской картой');

-- --------------------------------------------------------

--
-- Структура таблицы `service`
--

CREATE TABLE `service` (
  `id_service` int(1) NOT NULL,
  `address` varchar(50) NOT NULL,
  `user_id` int(1) NOT NULL,
  `service_type_id` int(1) NOT NULL,
  `data` date NOT NULL,
  `time` time(6) NOT NULL,
  `pay_type_id` int(1) NOT NULL,
  `status_id` int(1) NOT NULL,
  `reason_cancel` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `service`
--

INSERT INTO `service` (`id_service`, `address`, `user_id`, `service_type_id`, `data`, `time`, `pay_type_id`, `status_id`, `reason_cancel`) VALUES
(1, 'гроу стрит', 5, 2, '2025-12-10', '17:00:00.000000', 1, 1, '3'),
(2, 'дом пушкина', 5, 4, '2025-12-07', '20:00:00.000000', 2, 1, '7977566509'),
(3, 'дом пушкина', 5, 4, '2025-12-07', '20:00:00.000000', 2, 1, '7977566509'),
(4, 'дом пушкино', 5, 2, '2025-12-07', '20:00:00.000000', 1, 1, '7977566509');

-- --------------------------------------------------------

--
-- Структура таблицы `service_type`
--

CREATE TABLE `service_type` (
  `id_service_type` int(1) NOT NULL,
  `name_service` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `service_type`
--

INSERT INTO `service_type` (`id_service_type`, `name_service`) VALUES
(1, 'общий клининг'),
(2, 'генеральная уборка'),
(3, 'послестроительная уборка'),
(4, 'химчистка ковров и мебели');

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE `status` (
  `id_status` int(1) NOT NULL,
  `name_status` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id_status`, `name_status`) VALUES
(1, 'Выполнено'),
(2, 'В работе'),
(3, 'Отменено');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id_user` int(1) NOT NULL,
  `user_type_id` int(1) NOT NULL,
  `surname` varchar(7) NOT NULL,
  `name` varchar(5) NOT NULL,
  `otchestvo` varchar(9) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(28) NOT NULL,
  `username` varchar(8) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id_user`, `user_type_id`, `surname`, `name`, `otchestvo`, `phone`, `email`, `username`, `password`) VALUES
(1, 2, 'Елисеев', 'Артём', 'Игоревич', 'айфон 13', 'eliseevartem101@gmail.com', 'adminka2', '5f4dcc3b5aa765d61d8327deb882cf99'),
(2, 2, 'Миронов', 'Денис', 'Романович', '79775665099', 'den4ik.vipper.swag@gmail.com', 'adminka', '5f4dcc3b5aa765d61d8327deb882cf99'),
(3, 1, 'лазарев', 'мишка', 'безпапы', 'кнопочный', 'ur0dec@gmail.ru', 'mishka', '827ccb0eea8a706c4c34a16891f84e7b'),
(4, 1, 'иванtes', 'test', 'иванович', 'телефон', 'эмеил', 'спид', '827ccb0eea8a706c4c34a16891f84e7b'),
(5, 1, '1', '1', '1', '1', '1@a', '1', 'c4ca4238a0b923820dcc509a6f75849b');

-- --------------------------------------------------------

--
-- Структура таблицы `user_type`
--

CREATE TABLE `user_type` (
  `id_user_type` int(1) NOT NULL,
  `name_user` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `user_type`
--

INSERT INTO `user_type` (`id_user_type`, `name_user`) VALUES
(1, 'пользователь'),
(2, 'админ');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pay_type`
--
ALTER TABLE `pay_type`
  ADD PRIMARY KEY (`id_pay_type`);

--
-- Индексы таблицы `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_type_id` (`service_type_id`),
  ADD KEY `pay_type_id` (`pay_type_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Индексы таблицы `service_type`
--
ALTER TABLE `service_type`
  ADD PRIMARY KEY (`id_service_type`);

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
  ADD KEY `user_type_id` (`user_type_id`);

--
-- Индексы таблицы `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id_user_type`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `service_ibfk_2` FOREIGN KEY (`pay_type_id`) REFERENCES `pay_type` (`id_pay_type`),
  ADD CONSTRAINT `service_ibfk_3` FOREIGN KEY (`service_type_id`) REFERENCES `service_type` (`id_service_type`),
  ADD CONSTRAINT `service_ibfk_4` FOREIGN KEY (`status_id`) REFERENCES `status` (`id_status`);

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id_user_type`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
