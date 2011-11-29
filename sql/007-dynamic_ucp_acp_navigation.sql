ALTER TABLE `phpVana_navigation` ADD `section` TINYINT( 1 ) NOT NULL AFTER `id` ;

INSERT INTO `phpVana_navigation` (`id` ,`section` ,`active` ,`position` ,`title` ,`page` ,`global` ,`authorized` ,`gm` ,`external` ,`custom`) VALUES
(NULL , '1', '1', '1', 'Website Settings', 'settings', NULL , NULL , '3', '0', '0'),
(NULL , '1', '1', '2', 'News', 'news', NULL , NULL , '3', '0', '0'),
(NULL , '1', '1', '3', 'Navigation Administration', 'navigation', NULL , NULL , '3', '0', '0'),
(NULL , '1', '1', '4', 'Downloads', 'downloads', NULL , NULL , '3', '0', '0'),
(NULL , '1', '1', '5', 'Character-Username Lookup', 'char-username_lookup', NULL , NULL , '3', '0', '0'),
(NULL , '1', '1', '6', 'Ban User', 'ban', NULL , NULL , '3', '0', '0'),
(NULL , '1', '1', '7', 'Server Status', 'server_status', NULL , NULL , '3', '0', '0'),
(NULL , '2', '1', '1', 'Update Profile', 'profile', NULL , NULL , '0', '0', '0'),
(NULL , '2', '1', '2', 'Your Characters', 'character', NULL , NULL , '0', '0', '0'),
(NULL , '2', '1', '3', 'Negative EXP Fix', 'negative_exp', NULL , NULL , '0', '0', '0'),
(NULL , '2', '1', '4', 'Rebirth', 'rebirth', NULL , NULL , '0', '0', '0');

INSERT INTO `phpVana_info` (`sql`) VALUES
('007');