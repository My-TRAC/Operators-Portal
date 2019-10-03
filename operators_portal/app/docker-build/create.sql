CREATE TABLE `company_data` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `company_telephone` varchar(15) DEFAULT NULL,
  `company_address` varchar(100) DEFAULT NULL,
  `website` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `logo_url` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `pending_registration` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `user_data` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `profile_picture_url` varchar(50) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `pending_registration` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
