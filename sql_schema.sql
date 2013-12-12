CREATE TABLE IF NOT EXISTS `user_data` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `last_name` varchar(120) NOT NULL,
  `first_name` varchar(120) NOT NULL,
  `patronymic` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `hashes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(128) NOT NULL,
  `object_id` varchar(254) NOT NULL,
  `object_params` text,
  `hash` varchar(256) NOT NULL,
  `date_valid_end` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object` (`object`),
  KEY `object_id` (`object_id`),
  KEY `hash` (`hash`(255)),
  KEY `date_valid_end` (`date_valid_end`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `users`
  ADD COLUMN `user_data_id` smallint(5) unsigned default NULL AFTER `id`,
  ADD COLUMN `date_create` INT(11) unsigned,
  ADD COLUMN `date_update` INT(11) unsigned,
  ADD COLUMN `is_active` tinyint(1) unsigned default 1,
  ADD CONSTRAINT `user_data` FOREIGN KEY (`user_data_id`) REFERENCES `user_data` (`id`) ON UPDATE CASCADE;