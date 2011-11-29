CREATE TABLE `phpVana_comments` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`article_id` INT( 11 ) NOT NULL ,
`user_id` INT( 11 ) NOT NULL ,
`date` DATE NOT NULL ,
`time` TIME NOT NULL ,
`comment` TEXT NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM;

INSERT INTO `phpVana_config` (`config_name`, `config_value`) VALUES
('time_format', 'g:i a'),
('date_format', 'F d, Y');

INSERT INTO `phpVana_info` (`sql`) VALUES
('013');