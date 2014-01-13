<?php
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_config` (
  `application` varchar(100) NOT NULL,
  `config_name` varchar(255) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  `config_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `config_desc` text NOT NULL,
  UNIQUE KEY `application` (`application`,`config_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
mysql_query("INSERT INTO `{$_POST['db_prefix']}_config` (`application`, `config_name`, `config_value`, `config_enabled`, `config_desc`) VALUES
('system', 'asys_hide_cms', '0', 1, ''),
('system', 'asys_hide_modules', '0', 1, ''),
('system', 'asys_language', 'English', 1, ''),
('system', 'asys_max_wrong_login_count', '5', 1, 'The maximal wrong login count until a user get locked.'),
('system', 'asys_modules_menu', '0', 1, ''),
('system', 'asys_security', '1', 1, ''),
('system', 'asys_sitetitle', 'Adminsystems', 1, ''),
('system', 'asys_tpl', 'asys', 1, ''),
('system', 'clean_pagenames', '1', 1, ''),
('system', 'global_timezone', 'Europe/Vienna', 1, ''),
('system', 'ldap_auth', '0', 1, ''),
('system', 'page_home', 'home', 1, ''),
('system', 'page_sitetitle', 'My Page', 1, ''),
('system', 'page_slogan', 'Just another Page.', 1, ''),
('system', 'page_tpl', 'default', 1, ''),
('system', 'startpage_dashboard', '1', 1, '');");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_groups` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `sys_group` tinyint(1) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `group_description` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;");
mysql_query("INSERT INTO `{$_POST['db_prefix']}_groups` (`ID`, `sys_group`, `group_name`, `group_description`) VALUES
(1, 1, 'Administrators', 'Administrator Group - All Permissions'),
(2, 1, 'Users', 'Default User Group'),
(3, 1, 'Viewers', 'User Group with no Permissions');");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_groups_variables` (
  `gid` int(255) NOT NULL,
  `conf_name` varchar(150) NOT NULL,
  `conf_value` varchar(255) NOT NULL,
  KEY `gid` (`gid`),
  KEY `conf_name` (`conf_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
mysql_query("INSERT INTO `{$_POST['db_prefix']}_groups_variables` (`gid`, `conf_name`, `conf_value`) VALUES
(3, 'access_pages_edit', '0'),
(3, 'access_pages_create', '0'),
(3, 'access_pages_delete', '0'),
(3, 'access_navman_view', '0'),
(3, 'access_navman_change', '0'),
(3, 'access_upload', '0'),
(3, 'access_about', '1'),
(3, 'access_system_administrator', '0'),
(3, 'access_system_modules', '0'),
(3, 'access_system_users', '0'),
(2, 'access_pages_edit', '1'),
(2, 'access_pages_create', '1'),
(2, 'access_pages_delete', '0'),
(2, 'access_navman_view', '1'),
(2, 'access_navman_change', '1'),
(2, 'access_upload', '1'),
(2, 'access_about', '1'),
(2, 'access_system_administrator', '0'),
(2, 'access_system_modules', '0'),
(2, 'access_system_users', '0'),
(1, 'access_pages_edit', '1'),
(1, 'access_pages_create', '1'),
(1, 'access_pages_delete', '1'),
(1, 'access_navman_view', '1'),
(1, 'access_navman_change', '1'),
(1, 'access_upload', '1'),
(1, 'access_about', '1'),
(1, 'access_system_administrator', '1'),
(1, 'access_system_modules', '1'),
(1, 'access_system_users', '1');");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_languages` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `language` varchar(255) NOT NULL,
  `lang` varchar(50) NOT NULL,
  `backend` tinyint(1) NOT NULL,
  `frontend` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;");
mysql_query("INSERT INTO `{$_POST['db_prefix']}_languages` (`ID`, `language`, `lang`, `backend`, `frontend`) VALUES
(1, 'Deutsch', 'de', 0, 1),
(2, 'English', 'en', 1, 1);");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_logs` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `log_type` varchar(50) NOT NULL,
  `log_user` varchar(50) NOT NULL,
  `log_time` int(255) NOT NULL,
  `log_ip` varchar(50) NOT NULL,
  `log_useragent` text NOT NULL,
  `log_content` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `type` (`log_type`,`log_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_modules` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `mod_name` varchar(255) NOT NULL,
  `mod_file` varchar(255) NOT NULL,
  `mod_allow_everyone` tinyint(1) NOT NULL DEFAULT '0',
  `mod_desc` text NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `mod_file` (`mod_file`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;");
mysql_query("INSERT INTO `{$_POST['db_prefix']}_modules` (`ID`, `mod_name`, `mod_file`, `mod_allow_everyone`, `mod_desc`) VALUES
(1, 'View Modules', 'asys', 0, 'View all Modules in a list'),
(2, 'Permission Viewer', 'accview', 0, 'View your effective Permissions');");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_modules_permissions` (
  `group_id` int(255) NOT NULL,
  `mod_id` int(255) NOT NULL,
  `mod_access_mode` varchar(50) NOT NULL DEFAULT 'forbid',
  KEY `group_id` (`group_id`),
  KEY `mod_id` (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_navigation_entrys` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `entry_mode` varchar(20) NOT NULL DEFAULT 'internal_link',
  `menu_id` int(255) NOT NULL DEFAULT '1',
  `entry_name` varchar(255) NOT NULL,
  `entry_alias` varchar(255) NOT NULL,
  `entry_sub` varchar(255) NOT NULL,
  `sort_value` int(255) NOT NULL,
  `asys_parameter` text NOT NULL,
  `entry_tpl` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `entry_alias` (`entry_alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;");
mysql_query("INSERT INTO `{$_POST['db_prefix']}_navigation_entrys` (`ID`, `entry_mode`, `menu_id`, `entry_name`, `entry_alias`, `entry_sub`, `sort_value`, `asys_parameter`, `entry_tpl`) VALUES
(1, 'internal_link', 0, 'a:2:{i:1;s:5:\"Start\";i:2;s:4:\"Home\";}', 'home', '', 1, '1', 'page.php');");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_pages` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) DEFAULT 'name',
  `page_category` int(255) NOT NULL DEFAULT '1',
  `time_created` int(255) NOT NULL,
  `time_edited` int(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `name` (`page_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;");
mysql_query("INSERT INTO `{$_POST['db_prefix']}_pages` (`ID`, `page_name`, `page_category`, `time_created`, `time_edited`) VALUES
(1, 'Itworks', 1, 1358077252, 1358077252);");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_pages_categories` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;");
mysql_query("INSERT INTO `{$_POST['db_prefix']}_pages_categories` (`ID`, `category_name`) VALUES
(1, 'Uncategorized');");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_pages_content` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `page_id` int(255) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `page_content` text NOT NULL,
  `page_lang` int(255) NOT NULL,
  `page_position` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;");
mysql_query("INSERT INTO `{$_POST['db_prefix']}_pages_content` (`ID`, `page_id`, `page_name`, `page_content`, `page_lang`, `page_position`) VALUES
(1, 1, 'It works!', '<p>It works! Adminsystems was installed successfully.&nbsp;</p>\r\n', 2, '');");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `mail` varchar(255) NOT NULL,
  `enable_frontend` tinyint(1) NOT NULL,
  `enable_backend` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;");
mysql_query("INSERT INTO `{$_POST['db_prefix']}_users` (`ID`, `username`, `password`, `mail`, `enable_frontend`, `enable_backend`) VALUES
(1, 'admin', '" . '$1$IMsZMaQO$QYWhB5v2QMjzwNIRCrseS0' . "', '', 0, 1);");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_users_groups` (
  `user_id` int(255) NOT NULL,
  `group_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`),
  KEY `user_id_3` (`user_id`),
  KEY `user_id_4` (`user_id`),
  KEY `user_id_5` (`user_id`),
  KEY `user_id_6` (`user_id`),
  KEY `user_id_7` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
mysql_query("INSERT INTO `{$_POST['db_prefix']}_users_groups` (`user_id`, `group_id`) VALUES
(1, 1);");
mysql_query("CREATE TABLE IF NOT EXISTS `{$_POST['db_prefix']}_users_variables` (
  `uid` int(255) NOT NULL,
  `conf_name` varchar(255) NOT NULL,
  `conf_value` varchar(255) NOT NULL,
  KEY `uid` (`uid`),
  KEY `conf_name` (`conf_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
mysql_query("INSERT INTO `{$_POST['db_prefix']}_users_variables` (`uid`, `conf_name`, `conf_value`) VALUES
(1, 'user_tpl', 'Default'),
(1, 'user_lang', 'Default'),
(1, 'wrong_logins', '0');");
?>