ALTER TABLE `phpVana_servers` ADD `world_id` TINYINT( 3 ) default NULL AFTER `type`;
ALTER TABLE `phpVana_servers` DROP `active`;

INSERT INTO `phpVana_info` (`sql`) VALUES
('017');
