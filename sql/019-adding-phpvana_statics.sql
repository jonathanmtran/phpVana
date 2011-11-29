-- Message: Added really simple statics code

CREATE TABLE IF NOT EXISTS `phpVana_statistics` (
  `type` int(2) NOT NULL,
  `update_time` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL
);

INSERT INTO `phpVana_info` (`sql`) VALUES
("019");
