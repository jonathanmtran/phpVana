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

$acp_body = new Template(getFileLocation('acp/user-char_lookup.php'));

if (!isset($_POST['submit'])) {
	$acp_body->set("action", null);
}
else{
	switch($_POST['mode']){ 
		// Character name is given. look for user
		case "character":
			// Protect
			$term = mysql_real_escape_string($_POST['query']);
			
			// Find out if the user exists
			$query = mysql_query("SELECT * FROM `characters` WHERE `name` LIKE ('%$term%')");
			
			// Get the number of rows returned
			$number = mysql_num_rows($query);
			
			// If the number isn't zero, we will print out a list of characters to choose from
			if ($number != "0") {
				// Define stylesheet stuff
				$color0 = ("row-even");
				$color1 = ("row-odd");
				$row_count = "";
				
				while($data = mysql_fetch_array($query)) {
					$class = ($row_count % 2) ? $color0 : $color1;
					// Lookup username
					$user = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `ID` = '".$data['userid']."' LIMIT 1"));
					$search_result[] = array(
						"character" => $data['name'],
						"user" => $user['username']
					);
					$row_count++;
				}

				$acp_body->set("query", $term);
				$acp_body->set("search_result", $search_result);
				$acp_body->set("search", "character");
			}
			else {
				$acp_body->set("search", "ns");
			}
			break;
		
		// Given: username. Find: characters
		case "user":
			// Protect
			$term = mysql_real_escape_string($_POST['query']);
			
			// Find out if the user exists
			$query = mysql_query("SELECT * FROM `users` WHERE `username` LIKE ('%$term%')");
	
			// Get the number of rows returned
			$number = mysql_num_rows($query);
	
			// If the number isn't zero, we will print out a list of characters to choose from
			if ($number != "0") {
				// Define stylesheet stuff
				$color0 = ("row-even");
				$color1 = ("row-odd");
				$row_count = "";
			
				while($data = mysql_fetch_array($query)) {
					// Switch colors
					$class = ($row_count % 2) ? $color0 : $color1;
					
					// Lookup characters
					$user_query = (mysql_query("SELECT * FROM `characters` WHERE `userid` = '".$data['ID']."'"));
					$character = array();
					while ($char_data = mysql_fetch_array($user_query)) {
						$character[] = $char_data['name'];
					}					
					
					$search_result[] = array(
						"user" => $data['username'],
						"character" => $character
					);
					$row_count++;
				}

				$acp_body->set("query", $term);				
				$acp_body->set("search_result", $search_result);
				$acp_body->set("search", "user");
			}
			else{
				$acp_body->set("search", "ns");
			}
			break;
	}
}