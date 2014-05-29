-- MySQL --
-- users --
CREATE TABLE IF NOT EXISTS `users` (`id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'PKey', `mail` VARCHAR(255) NOT NULL COMMENT 'メールアドレス', `pass` VARCHAR(255) NOT NULL COMMENT 'パスワード', PRIMARY KEY(`id`), UNIQUE(`mail`, `pass`));
