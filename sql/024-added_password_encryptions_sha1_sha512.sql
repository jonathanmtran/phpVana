-- Message: This is an update for you to determine if you want to use SHA-1 or SHA-512 for encrypting passwords in the database. NOTE: The SHA-512 encryption is added in Vana in rev 2897. Don't use SHA-512 if your Vana repo is outdated/lower than rev 2897.

INSERT INTO `phpVana_config` (`config_name`, `config_value`) VALUES
('password_encryption', 'SHA-1');

INSERT INTO `phpVana_info` (`sql`) VALUES
("024");