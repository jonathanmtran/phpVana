UPDATE `phpVana_navigation` SET `page` = 'user-char_lookup' WHERE `section` = 1 AND `page` = 'char-username_lookup';

UPDATE `phpVana_navigation` SET `position` = 5 WHERE `section` = 1 AND `page` = 'settings';
UPDATE `phpVana_navigation` SET `position` = 10 WHERE `section` = 1 AND `page` = 'news';
UPDATE `phpVana_navigation` SET `position` = 15 WHERE `section` = 1 AND `page` = 'navigation';
UPDATE `phpVana_navigation` SET `position` = 30 WHERE `section` = 1 AND `page` = 'downloads';
UPDATE `phpVana_navigation` SET `position` = 35 WHERE `section` = 1 AND `page` = 'user-char_lookup';
UPDATE `phpVana_navigation` SET `position` = 40 WHERE `section` = 1 AND `page` = 'ban';
UPDATE `phpVana_navigation` SET `position` = 45 WHERE `section` = 1 AND `page` = 'server_status';

UPDATE `phpVana_navigation` SET `position` = 5 WHERE `section` = 2 AND `page` = 'profile';
UPDATE `phpVana_navigation` SET `position` = 10 WHERE `section` = 2 AND `page` = 'character';
UPDATE `phpVana_navigation` SET `position` = 15 WHERE `section` = 2 AND `page` = 'negative_exp';
UPDATE `phpVana_navigation` SET `position` = 20 WHERE `section` = 2 AND `page` = 'rebirth';

INSERT INTO `phpVana_navigation` (`id` ,`section` ,`active` ,`position` ,`title` ,`page` ,`global` ,`authorized` ,`gm` ,`external` ,`custom`) VALUES
(NULL, 1, 1, 20, 'Admin CP Navigation Administration', 'navigation-acp', NULL , NULL , 3, 0, 0);

INSERT INTO `phpVana_info` (`sql`) VALUES
('008');