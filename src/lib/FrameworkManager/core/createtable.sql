-- MySQL --
-- user --
CREATE TABLE IF NOT EXISTS `user` (`id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'pkey', `mail` VARCHAR(1024) NOT NULL COMMENT 'メールアドレス(AES128CBC)', `pass` VARCHAR(64) NOT NULL COMMENT 'パスワード(SHA256)', PRIMARY KEY(`id`));
-- session --
CREATE TABLE IF NOT EXISTS `session` (`token` VARCHAR(255) NOT NULL COMMENT 'ワンタイムトークン', `created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'トークン作成日時', PRIMARY KEY(`token`)) ENGINE = MYISAM;
CREATE TABLE IF NOT EXISTS `sessiondata` (`uid` CHAR(64) NOT NULL COMMENT 'user_idから算出したUID', `data` TEXT DEFAULT '' COMMENT 'jsonシリアライズされたセッションデータ', `created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '作成日時', `modified` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新日時', PRIMARY KEY(`uid`)) ENGINE = MYISAM;
-- user recode--
INSERT INTO `user` (`mail`, `pass`) VALUES ('f3f941cebae9e2e53f65165db2013a1c3a86d64d1ed7b460a9776e10853512db', 'e9cee71ab932fde863338d08be4de9dfe39ea049bdafb342ce659ec5450b69ae');
DELETE FROM `user` WHERE `id` > 1 AND `mail` = 'f3f941cebae9e2e53f65165db2013a1c3a86d64d1ed7b460a9776e10853512db';
