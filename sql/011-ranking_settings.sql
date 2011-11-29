INSERT INTO `phpVana_config` (`config_name` ,`config_value`) VALUES 
('ranking_show_gm_user', 0), 
('ranking_show_gm_character', 0);

INSERT INTO `phpVana_navigation` (`id` ,`section` ,`active` ,`position` ,`title` ,`page` ,`global` ,`authorized` ,`gm` ,`external` ,`custom`) VALUES 
(NULL , '1', '1', '55', 'Ranking Settings', 'ranking', NULL , NULL , '3', 'NULL', '0');

INSERT INTO `phpVana_info` (`sql`) VALUES
('011');
