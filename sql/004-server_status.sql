CREATE TABLE IF NOT EXISTS `phpVana_servers` (
  `id` int(4) NOT NULL auto_increment,
  `active` tinyint(1) NOT NULL,
  `type` int(1) NOT NULL,
  `name` varchar(255) default NULL,
  `host` varchar(255) NOT NULL,
  `port` smallint(5) NOT NULL,
  `exp_rate` smallint(5) default NULL,
  `quest_rate` smallint(5) default NULL,
  `meso_rate` smallint(5) default NULL,
  `drop_rate` smallint(5) default NULL,
  `parent` int(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO `phpVana_info` (`sql`) VALUES
('004');
