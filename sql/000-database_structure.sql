SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `phpVana_config` (
  `config_name` varchar(255) NOT NULL,
  `config_value` TEXT NOT NULL,
  PRIMARY KEY  (`config_name`)
);

INSERT INTO `phpVana_config` (`config_name`, `config_value`) VALUES
('site_name', 'MyStory'),
('site_slogan', 'Your slogan here.'),
('ranking_module_limit', '5'),
('ranking_page_limit', '5'),
('template', 'CoolWater'),
('news_limit', '5'),
('rebirth_cost', '50000000'),
('ranking_show_gm_user', 0), 
('ranking_show_gm_character', 0),
('time_format', 'g:i a'),
('date_format', 'F d, Y'),
('char_delete_method', 'birthdate'),
('use_captcha', 'yes'),
('auto_sub_goto', 'yes'),
('public_cap_code', '6Lew8gcAAAAAAFZl3F4RmTFqq5CnxnXt9fv15I62'),
('private_cap_code', '6Lew8gcAAAAAAFpQQIFFjzng2KEXNlwoddXWhAIE '),
('password_encryption', 'SHA-1');

CREATE TABLE IF NOT EXISTS `phpVana_downloads` (
  `id` tinyint(2) NOT NULL auto_increment,
  `active` tinyint(1) NOT NULL,
  `position` tinyint(2) default NULL,
  `title` varchar(255) NOT NULL,
  `description` TEXT NOT NULL,
  `host` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `parent` tinyint(2) default NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `phpVana_navigation` (
  `id` tinyint(2) NOT NULL auto_increment,
  `section` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `position` tinyint(2) NOT NULL,
  `title` varchar(255) NOT NULL,
  `page` varchar(255) NOT NULL,
  `global` smallint(1) default NULL,
  `authorized` tinyint(1) default NULL,
  `gm` tinyint(1) NOT NULL,
  `external` tinyint(1) NOT NULL,
  `custom` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

INSERT INTO `phpVana_navigation` (`id`, `section`, `active`, `position`, `title`, `page`, `global`, `authorized`, `gm`, `external`, `custom`) VALUES
(NULL, 0, 1, 5, 'Home', '', 1, 1, 1, 0, 0),
(NULL, 0, 1, 10, 'Register', 'register', 0, 1, 0, 0, 0),
(NULL, 0, 1, 15, 'Login', 'login', 0, 1, 0, 0, 0),
(NULL, 0, 1, 20, 'Logout', 'logout', 0, 0, 0, 0, 0),
(NULL, 0, 1, 25, 'Ranking', 'ranking', 1, 1, 1, 0, 0),
(NULL, 0, 1, 30, 'Who\\''s Online', 'whos_online', 1, 1, 1, 0, 0),
(NULL, 0, 1, 35, 'User CP', 'ucp', 0, 0, 0, 0, 0),
(NULL, 0, 1, 40, 'Admin CP', 'acp', NULL, NULL, 1, 0, 0),
(NULL, 0, 1, 45, 'Downloads', 'downloads', 1, 1, 1, 0, 0),
(NULL, 0, 1, 50, 'Server Status', 'server_status', 1, 1, 1, 0, 0),
(NULL, 1, 1, 5, 'Website Settings', 'settings', NULL , NULL , 3, 0, 0),
(NULL, 1, 1, 10, 'News', 'news', NULL , NULL , 3, 0, 0),
(NULL, 1, 1, 15, 'Navigation Administration', 'navigation', NULL , NULL , 3, 0, 0),
(NULL, 1, 1, 20, 'Admin CP Navigation Administration', 'navigation-acp', NULL , NULL , 3, 0, 0),
(NULL, 1, 1, 30, 'Downloads', 'downloads', NULL , NULL , 3, 0, 0),
(NULL, 1, 1, 35, 'Character-Username Lookup', 'user-char_lookup', NULL , NULL , 3, 0, 0),
(NULL, 1, 1, 40, 'Ban User', 'ban', NULL , NULL , 3, 0, 0),
(NULL, 1, 1, 45, 'Server Status', 'server_status', NULL , NULL , 3, 0, 0),
(NULL, 1, 1, 50, 'Database Information', 'database_info', NULL , NULL , 3, 0, 0),
(NULL, 1, 1, 55, 'Ranking Settings', 'ranking', NULL , NULL , 3, NULL, 0),
(NULL, 1, 1, 60, 'Edit User Account', 'edit_user', NULL , NULL , 3, NULL, 0),
(NULL, 1, 1, 65, 'Database Update', 'database_update', NULL , NULL , 3, NULL, 0),
(NULL, 2, 1, 5, 'Update Profile', 'profile', NULL , NULL , 0, 0, 0),
(NULL, 2, 1, 10, 'Your Characters', 'character', NULL , NULL , 0, 0, 0),
(NULL, 2, 1, 15, 'Negative EXP Fix', 'negative_exp', NULL , NULL , 0, 0, 0),
(NULL, 2, 1, 20, 'Rebirth', 'rebirth', NULL , NULL , 0, 0, 0);

CREATE TABLE IF NOT EXISTS `phpVana_news` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `owner` int(11) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `phpVana_servers` (
  `id` int(4) NOT NULL auto_increment,
  `active` tinyint(1) NOT NULL,
  `type` int(1) NOT NULL,
  `world_id` int(1) default NULL,
  `name` varchar(255) default NULL,
  `host` varchar(255) NOT NULL,
  `port` smallint(5) NOT NULL,
  `exp_rate` smallint(5) default NULL,
  `quest_rate` smallint(5) default NULL,
  `meso_rate` smallint(5) default NULL,
  `drop_rate` smallint(5) default NULL,
  `parent` int(4) default NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `phpVana_comments` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`article_id` INT( 11 ) NOT NULL ,
`user_id` INT( 11 ) NOT NULL ,
`date` DATE NOT NULL ,
`time` TIME NOT NULL ,
`comment` TEXT NOT NULL ,
PRIMARY KEY ( `id` )
);

CREATE TABLE IF NOT EXISTS `phpVana_info` (
  `sql` varchar(3) NOT NULL
);

INSERT INTO `phpVana_info` (`sql`) VALUES
(000),
(001),
(002),
(003),
(004),
(005),
(006),
(007),
(008),
(009),
(010),
(011),
(012),
(013),
(014),
(015),
(016),
(017),
(018),
(019),
(020),
(021),
(022),
(023),
(024);