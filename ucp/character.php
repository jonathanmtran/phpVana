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
$ucp_body = new Template(getFileLocation("ucp/character.php"));

// Require data for jobs
require("includes/job_data.php");
// Require data for map names
require("includes/map_data.php");

if (!isset($_GET['character'])) {
	$character_data = array();
	$query = mysql_query("SELECT ID, name FROM `characters` WHERE `userid` = " . intval($_SESSION['user_id']) . " ORDER BY world_id ASC");

	while ($data = mysql_fetch_array($query)) {
		$character_data[] = array(
			"id" => $data['ID'],
			"name" => $data['name']
		);
	}
	
	$ucp_body->set("characters", $character_data);
}
else {
	$query = mysql_query("SELECT name, userid FROM characters WHERE	id = " . intval($_GET['character'])) or die(mysql_error());

	$query_result = mysql_fetch_array($query);

	if ($_SESSION['user_id'] != $query_result['userid']) {
		header("Location: index.php?page=login&error=nli");
		die();
	}
	
	
	$ucp_body->set("name", $query_result['name']);
	$ucp_body->set("character_id", $_GET['character']);
	
	$query = mysql_query("
		SELECT 
			bc.name AS name,
			bc.level AS level,
			bc.job AS job,
			bc_user.online AS online,
			bc.world_id AS world_id,
			bc.map AS map
		FROM 
			`buddylist` AS buddy
		INNER JOIN 
			`characters` AS bc
		ON 
			bc.id = buddy.buddy_charid
		INNER JOIN 
			`users` AS bc_user
		ON 
			bc.userid = bc_user.ID
		WHERE 
			`charid` = " . intval($_GET['character']));
	$buddylist_data = array();
	
	while ($data = mysql_fetch_array($query)) {
		$buddylist_data[] = array(
			"name" => $data['name'],
			"level" => $data['level'],
			"map" => $map_data[$data['map']],
			"channel" => $data['online'],
			"job" => $job_data[$data['job']]['name']
		);
	}
	
	$ucp_body->set("num_buddies", mysql_num_rows($query));
	$ucp_body->set("buddies", $buddylist_data);
}
