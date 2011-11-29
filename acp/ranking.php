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

$acp_body = new Template(getFileLocation('acp/ranking.php'));

if (!isset($_POST['submit'])) {
	// "selected" flag for the dropdowns
	if ($phpVana_config['ranking_show_gm_user'] == 0) {
		$ranking_show_gm_user_enabled = NULL;
		$ranking_show_gm_user_disabled = "selected=\"selected\"";	
	}
	else if ($phpVana_config['ranking_show_gm_user'] == 1) {
		$ranking_show_gm_user_enabled = "selected=\"selected\"";
		$ranking_show_gm_user_disabled = NULL;
	}
	
	if ($phpVana_config['ranking_show_gm_character'] == 0) {
		$ranking_show_gm_character_enabled = NULL;
		$ranking_show_gm_character_disabled = "selected=\"selected\"";
	}
	else if ($phpVana_config['ranking_show_gm_character'] == 1) {
		$ranking_show_gm_character_enabled = "selected=\"selected\"";
		$ranking_show_gm_character_disabled = NULL;
	}	
	
	$acp_body->set("phpVana_config", $phpVana_config);
	$acp_body->set("ranking_show_gm_user_enabled", $ranking_show_gm_user_enabled);
	$acp_body->set("ranking_show_gm_user_disabled", $ranking_show_gm_user_disabled);
	$acp_body->set("ranking_show_gm_character_enabled", $ranking_show_gm_character_enabled);
	$acp_body->set("ranking_show_gm_character_disabled", $ranking_show_gm_character_disabled);	
	$acp_body->set("submit", false);
}
else{
	// Protect against sql injection
	$ranking_module_limit = intval($_POST['ranking_module_limit']);
	$ranking_page_limit = intval($_POST['ranking_page_limit']);
	$show_gm_user = intval($_POST['show_gm_user']);
	$show_gm_character = intval($_POST['show_gm_character']);
	
	// Update the settings
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '$ranking_module_limit' WHERE `config_name` = 'ranking_module_limit'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '$ranking_page_limit' WHERE `config_name` = 'ranking_page_limit'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '$show_gm_user' WHERE `config_name` = 'ranking_show_gm_user'");
	mysql_query ("UPDATE `phpVana_config` SET `config_value` = '$show_gm_character' WHERE `config_name` = 'ranking_show_gm_character'");
	
	$acp_body->set('submit', true);
}