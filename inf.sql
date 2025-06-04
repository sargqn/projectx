CREATE DATABASE IF NOT EXISTS `demo`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `universal_portal`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL COMMENT 'Уникальный логин',
  `password` VARCHAR(255) NOT NULL COMMENT 'Хеш пароля',
  `fio` VARCHAR(100) NOT NULL COMMENT 'ФИО пользователя',
  `email` VARCHAR(100) NOT NULL COMMENT 'Email',
  `phone` VARCHAR(20) NOT NULL COMMENT 'Телефон',
  `role` ENUM('user','admin') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `requests` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `request_date` DATETIME NOT NULL COMMENT 'Дата выполнения заявки',
  `details` TEXT NOT NULL COMMENT 'Основные детали заявки',
  `type` VARCHAR(50) DEFAULT NULL COMMENT 'Тип заявки (определяется темой)',
  `status` ENUM('Новая','В работе','Отменена','Выполнена') NOT NULL DEFAULT 'Новая',
  `special_note` VARCHAR(255) DEFAULT NULL COMMENT 'Особое примечание',
  `review` TEXT DEFAULT NULL COMMENT 'Отзыв пользователя',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`username`, `password`, `fio`, `email`, `phone`, `role`)
VALUES (
    'admin',
    '$2y$10$zX5J7Qb8dLkR9s2v1w3y4e5r6t7u8i9o0p1a2s3d4f5g6h7j8k9l0m',
    'Администратор Системы',
    'admin@example.com',
    '+7(999)-999-99-99',
    'admin'
);
