# Установка
1) Создать таблицу в базе данных:
```
CREATE TABLE `contact` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(30) NOT NULL COMMENT 'имя',
  `last_name` varchar(30) NOT NULL COMMENT 'фамилия',
  `email` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `photo` varchar(21) DEFAULT NULL COMMENT 'идентификатор файла',
  `comment` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`phone`);

ALTER TABLE `contact`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
```
2) Прописать подключение к БД в файле configs/db.php

# Системные требования
PHP >= 5.3

Разработка велась на софте Open Server (https://ospanel.io/).