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

$body = new Template(getFileLocation("acp.php"));

// Check if session exists
if (!isset($_SESSION['user_id'])) {
	header("Location: index.php?page=login&amp;error=nli");
	die();
}

// Check to see if user is a gm
$gm_query = mysql_query("SELECT * FROM `users` WHERE `gm` > 0 AND `ID` = " . $_SESSION['user_id']);
if (mysql_num_rows($gm_query) != 1) {
	header("Location:" . $_SERVER['PHP_SELF']);
	die();
}
else {
	// Calculate gm level
	$gm_level = $_SESSION['nav_level'] - 1;
	
	// Get navigation
	$nav_query = mysql_query("SELECT * FROM	`phpVana_navigation` WHERE `section` = 1 AND `gm` <= ".$gm_level." ORDER BY `position` ASC");

	$navigation = array();
	while ($data = mysql_fetch_array($nav_query)) {
		$navigation[] = array(
			"page" => $data['page'],
			"title" => $data['title']
		  );
	}
	
	$body->set("navigation", $navigation);
	$body->set("auto_sub_goto", $phpVana_config['auto_sub_goto']);	
		
	// If no value was passed to page then show the main page
	if(!isset($_GET['section']) || $_GET['section'] == "home") {
		include ("acp/welcome.php");
		$body->set("section", $acp_body);
	}
	else {
		// Otherwise, include the page from the pages directory
		$file = ("acp/" . $_GET['section'] . ".php");
		
		// Let's check to see if the file exists
		if (file_exists($file)) {
			include ($file);
			$body->set("section", $acp_body);
		}
		else {
			$body = new Template(getFileLocation("error.php"));
			$body->set("error", "404");
		}
	}
}
