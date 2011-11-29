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

$body = new Template(getFileLocation("ucp.php"));

// Check if session exists
if (!isset($_SESSION['user_id'])) {
	header("Location: index.php?page=login&error=nli");
	die();
}

// Get navigation
$nav_query = mysql_query(
	"SELECT
		*
	FROM
		`phpVana_navigation`
	WHERE
		`section` = 2
	ORDER BY
		`position` ASC"
);

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
	include ("ucp/welcome.php");
	$body->set("section", $ucp_body);	
}
else {	
	// Otherwise, include the page from the pages directory
	$file = "ucp/" . stripslashes($_GET['section']) . ".php";
	
	// Let's check to see if the file exists
	if (file_exists($file)) {
		include ($file);
		$body->set("section", $ucp_body);
	}
	else {
		$body = new Template(getFileLocation("error.php"));
		$body->set("error", "404");
	}
}
