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

$ucp_body = new Template(getFileLocation("ucp/negative_exp.php"));

if (!isset($_GET['character'])) {
	// Get user's characters that have negative experience points
	$character_query = mysql_query("SELECT * FROM `characters` WHERE `userid` = " . intval($_SESSION['user_id']) . " AND `exp` < 0");
	
	while ($data = mysql_fetch_array($character_query)){
		$negative_exp_characters[] = array(
			"id" => $data['ID'],
			"name" => $data['name']
		);
	}
	$ucp_body->set("num_negative_exp_character", (mysql_num_rows($character_query) != 0 ? $negative_exp_characters : 0));
}
else{
	// Get character information
	$character_data = mysql_fetch_array(mysql_query("SELECT * FROM `characters` WHERE `id` = " . intval($_GET['character'])));
	
	// Check to make sure that the user is the character's owner.
	if ($character_data['userid'] != $_SESSION['user_id'])
		$hacking = TRUE;
	else {
		if ($character_data['online'] != 0) {
			// Check to make sure that the character isn't logged in
			$online = TRUE;
		}
		else {
			// Set the character's experience points to 0
			mysql_query("UPDATE `characters` SET `exp` = 0 WHERE `ID` = " . intval($_GET['character']) . " AND `userid` = " . $_SESSION['user_id']);
			$online = FALSE;
			$hacking = FALSE;
		}
		$ucp_body->set("logged_in", $online);
	}
	$ucp_body->set("hacking", $hacking);
}
