CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `login` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `display_name` varchar(250) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `activation_key` varchar(255) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE `login_key` (`login`) USING BTREE,
  UNIQUE `email` (`email`),
  INDEX `display_name` (`display_name`),
  INDEX `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
