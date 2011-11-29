<?php
/*
Copyright (C) 2008-2010 phpVana Development Team

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

$acp_body = new Template(getFileLocation('acp/settings.php'));

if (!isset($_POST['submit'])) {
	// Read the templates folder for the possible templates
	$templates = array();
	$dir = "templates/";
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				switch ($file){
					case ".":
					case "..":
					case ".svn":
						break;
					default:
						if (filetype($dir . $file) == "dir") {
							if (($phpVana_config['template']) == $file) {
								$templates[] = array (
									"file" => $file,
									"selected" => true
								);
							}
							else {
								$templates[] = array (
									"file" => $file,
									"selected" => false
								);
							}
						}
						break;
				}
			}
			closedir($dh);
		}
	}
	
	$acp_body->set('phpVana_config', $phpVana_config);
	$acp_body->set('templates', $templates);
	$acp_body->set('submit', false);
}
else{
	// Protect against sql injection
	$site_name = mysql_real_escape_string(htmlspecialchars($_POST['site_name'], ENT_QUOTES));
	$site_slogan = mysql_real_escape_string(htmlspecialchars($_POST['site_slogan'], ENT_QUOTES));
	$news_limit = mysql_real_escape_string($_POST['news_limit']);
	$template = mysql_real_escape_string($_POST['template']);
	$rebirth_cost = mysql_real_escape_string($_POST['rebirth_cost']);
	$time_format = mysql_real_escape_string($_POST['time_format']);
	$date_format = mysql_real_escape_string($_POST['date_format']);
	$char_delete_method = mysql_real_escape_string($_POST['char_delete_method']);
	$use_captcha = mysql_real_escape_string($_POST['use_captcha']);
	$auto_sub_goto = mysql_real_escape_string($_POST['auto_sub_goto']);
	$public_cap_code = mysql_real_escape_string($_POST['public_cap_code']);
	$private_cap_code = mysql_real_escape_string($_POST['private_cap_code']);
	$password_encryption = mysql_real_escape_string($_POST['password_encryption']);
	
	// Sql to update config values
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $site_name . "' WHERE `config_name` = 'site_name'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $site_slogan . "' WHERE `config_name` = 'site_slogan'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $news_limit . "' WHERE `config_name` = 'news_limit'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $template . "' WHERE `config_name` = 'template'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $rebirth_cost . "' WHERE `config_name` = 'rebirth_cost'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $time_format . "' WHERE `config_name` = 'time_format'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $date_format . "' WHERE `config_name` = 'date_format'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $char_delete_method . "' WHERE `config_name` = 'char_delete_method'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $use_captcha . "' WHERE `config_name` = 'use_captcha'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $auto_sub_goto . "' WHERE `config_name` = 'auto_sub_goto'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $public_cap_code . "' WHERE `config_name` = 'public_cap_code'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $private_cap_code . "' WHERE `config_name` = 'private_cap_code'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '" . $password_encryption . "' WHERE `config_name` = 'password_encryption'");
	
	$acp_body->set('submit', true);
}