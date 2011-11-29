-- Message: You can enter multiple slogans to have a random slogan displayed every time the page loads.

ALTER TABLE `phpVana_config` CHANGE `config_value` `config_value` TEXT NOT NULL;

INSERT INTO `phpVana_info` (`sql`) VALUES
("018");
