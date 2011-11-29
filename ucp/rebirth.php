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

$ucp_body = new Template(getFileLocation("ucp/rebirth.php"));

$character = intval($_GET['character']);
$ucp_body->set("rebirth_cost", $phpVana_config['rebirth_cost']);

if ($character == 0) {
	// Get user's characters that are eligible for rebirth
	$character_query = mysql_query("
		SELECT 
			* 
		FROM 
			`characters` 
		WHERE 
			`userid` = '" . $_SESSION['user_id'] . "' 
		AND 
			level >= 200
		");
	
	$eligible_characters = array();
	while ($data = mysql_fetch_array($character_query)){
		$eligible_characters[] = array(
			"id" => $data['ID'],
			"name" => $data['name'],
			"mesos" => $data['mesos']
		);
	}
	$ucp_body->set("eligible_characters", $eligible_characters);
}
else{
	// Get character information
	$character_data = mysql_fetch_array(mysql_query("SELECT * FROM `characters` WHERE `ID` = '" . $character . "'"));
	$mesos = false;
	$online = false;
	
	// Check to make sure that the user is the character's owner.
	if ($character_data['userid'] == $_SESSION['user_id']) {
		
		// Check to make sure that the character isn't logged in
		$online = $character_data['online'] != 0;
		
		// Check mesos
		if($character_data['mesos'] >= $phpVana_config['rebirth_cost']) {
			$mesos = true;
		}
	}

	// Update
	if (!$online && $mesos) {
		$mesos = $character_data['mesos'] - $phpVana_config['rebirth_cost'];
		mysql_query("
			UPDATE 
				`characters` 
			SET 
				`exp` = '0', 
				`level` = '1', 
				`job` = '0', 
				`mesos` = '" . $mesos . "' 
			WHERE 
				`ID` = '" . $character . "' 
			AND 
				`userid` = '" . $_SESSION['user_id'] . "'
			");
	}
	
	$ucp_body->set("id", $character);
	$ucp_body->set("character_mesos", $character_data['mesos']);
	$ucp_body->set("mesos", $mesos);
	$ucp_body->set("online", $online);
}