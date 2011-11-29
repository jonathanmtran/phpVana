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

$acp_body = new Template(getFileLocation('acp/navigation.php'));

if (!isset($_GET['action'])) {
	// Let's spit out all all of the navigation entries
	$query = mysql_query("
				SELECT 
					* 
				FROM 
					`phpVana_navigation` 
				WHERE 
					`section` = 0 
				ORDER BY 
					`position` 
				ASC"
				);
	
	$prev_position = "0";
	
	while ($data = mysql_fetch_array($query)) {
		$position = $data['position'];
		$difference = $position - $prev_position;
	
		$navigation_data[] = array(
			"id" => $data['id'],
			"title" => stripslashes($data['title']),
			"difference" => $difference,
			"active" => $data['active']
		);

		$prev_position = $position;
	}
	$action = NULL;
	$complete = FALSE;	
	$acp_body->set("navigation_data", $navigation_data);
}
else {
	// The user chose to do something
	if (!isset($_POST['submit'])) {
		switch ($_GET['action']) {
			case "new":
				$action = "new";
				$complete = FALSE;
			break;
			
			case "edit":
				// Get link information
				$data = mysql_fetch_array(mysql_query("
							SELECT 
								* 
							FROM 
								`phpVana_navigation` 
							WHERE 
								`id` = " . $_GET['id'] . " 
							LIMIT 1
							"));
				
				// Phrase access permissions like a combo lock code
				$access = $data['global'] . "-" . $data['authorized'] . "-" . $data['gm'];
				
				// Assign which one gets to have the selected
				$s_all = $s_authorized = $s_gm = $s_special = NULL;
				switch ($access) {
					case "1-1-1":
						$s_all = ' selected="selected"';
						break;
					case "0-0-0":
						$s_authorized = ' selected="selected"';					
						break;
					case "--1":
						$s_gm = ' selected="selected"';
						break;
					case "0-1-0":
						$s_special = ' selected="selected"';
						break;
				}

				// Convert the 0s and 1s back to checked
				$c_external = $data['external'] == 1 ? " checked=\"checked\"" : NULL;
				$c_active = $data['active'] == 1 ? " checked=\"checked\"" : NULL;
		
				$link_data = array(
					"id" => intval($_GET['id']),
					"title" => $data['title'],
					"page" => $data['page'],
					"external" => $c_external,
					"active" => $c_active,
					"s_all" => $s_all,
					"s_authorized" => $s_authorized,
					"s_gm" => $s_gm,
					"s_special" => $s_special
				);
				
				$action = "edit";
				$complete = FALSE;
				$acp_body->set("data", $link_data);
				break;
			
			case "delete":
				// Check to see if the item is a custom item
				$data = mysql_fetch_array(mysql_query("
							SELECT 
							* 
							FROM 
							`phpVana_navigation` 
							WHERE 
							`id` = " . intval($_GET['id'])
							));
				
				$custom = $data['custom'] == 1 ? TRUE : FALSE;
				$action = "delete";
				$complete = FALSE;
				
				$acp_body->set("id", intval($_GET['id']));
				$acp_body->set("custom", $custom);
				break;
			
			case "move_up":
				// Get the position of the current link
				$current_link = mysql_fetch_array(mysql_query("
									SELECT 
										* 
									FROM 
										`phpVana_navigation` 
									WHERE 
										`id` = " . $_GET['id']
									));
		
				// Subtract $difference to get the above link
				$above = $current_link['position'] - $_GET['difference'];
				
				// Get the link with the position of $above
				$above_link = mysql_fetch_array(mysql_query("
								SELECT 
									* 
								FROM 
									`phpVana_navigation` 
								WHERE 
									`position` = '" . $above . "'
								"));
		
				// Get the id of that link
				$above_id = $above_link['id'];
				if ($above_link['id'] != "") {
					// Update the current link
					mysql_query("
						UPDATE 
							`phpVana_navigation` 
						SET 
							`position` = '" . $above_link['position'] . "' 
						WHERE 
							`id` = " . $_GET['id']
						);
			
					// Update the above link
					mysql_query("
						UPDATE 
							`phpVana_navigation` 
						SET 
							`position` = '" . $current_link ['position'] . "' 
						WHERE 
							`id` = " . $above_id
						);
				}
				
				// Throw the user back to the page. hopefully it creates an instant effect
				header ("Location: " . $_SERVER['PHP_SELF'] . "?page=acp&section=navigation");
				break;
			
			case "move_down":
				// Get the position of the current link
				$current_link = mysql_fetch_array(mysql_query("
									SELECT 
										* 
									FROM 
										`phpVana_navigation` 
									WHERE 
										`id` = " . $_GET['id']
									));
		
				// Subtract $difference to get the below link.
				// Top link has a difference of zero. we have to figure out a workaround.
				$below = $current_link['position'] + $_GET['difference'];
		
				// Get the link with the position of $below
				$below_link = mysql_fetch_array(mysql_query("
									SELECT 
										* 
									FROM 
										`phpVana_navigation` 
									WHERE 
										`position` = '" . $below . "'"
									));
		
				// Get the id of that link. if a link doesn't exist below, go back to the navigation page
				$below_id = $below_link['id'];
				if ($below_link['id'] != "") {
					// Update the current link
					mysql_query("
						UPDATE 
							`phpVana_navigation` 
						SET 
							`position` = '" . $below_link['position'] . "' 
						WHERE 
							`id` = " . $_GET['id']
						);
			
					// Update the below link
					mysql_query("
						UPDATE 
							`phpVana_navigation` 
						SET 
							`position` = '" . $current_link ['position'] . "' 
						WHERE 
							`id` = " . $below_id
						);
				}
				
				// Throw the user back to the page. hopefully it creates an instant effect
				header ("Location: " . $_SERVER['PHP_SELF'] . "?page=acp&section=navigation");
				break;
		}
	}
	else {
		// We are doing something!
		switch ($_GET['action']) {
			case "new":
				// Try to save ourselves
				$title = mysql_real_escape_string($_POST['title']);
				$target = mysql_real_escape_string($_POST['target']);
		
				// Get last position
				$query = mysql_fetch_array(mysql_query("
							SELECT 
								* 
							FROM 
								`phpVana_navigation` 
							WHERE 
								`section` = 0 
							ORDER BY 
								`position` 
							DESC 
							LIMIT 1"
							));
				$new_position = ($query['position'] + 1);
				
				// Checkbox stuff
				if (isset($_POST['external'])) {
					switch ($_POST['external']) {
						case "on":
							$external = "1";
							break;
						default:
							$external = "0";
							break;
					}
				}
				else {
					$external = "0";
				}
				
		
				if (isset($_POST['active'])) {
					switch ($_POST['active']) {
						case "on":
							$active = "1";
							break;
						default:
							$active = "0";
							break;
					}
				}
				else {
					$active = "0";
				}
				
				// Prepare sql
				switch ($_POST['access']) {
					case "all":
						$sql = "INSERT INTO `phpVana_navigation` (`id` ,`section` ,`active` ,`position` ,`title` ,`page` ,`global` ,`authorized` ,`gm` ,`external` ,`custom`)
							VALUES (NULL , 0, '" . $active . "', '" . $new_position . "', '" . $title . "', '" . $target . "', '1', '1', '1', '" . $external . "', '1')";
						break;

					case "authorized":
						$sql = "INSERT INTO `phpVana_navigation` (`id` ,`section` ,`active` ,`position` ,`title` ,`page` ,`global` ,`authorized` ,`gm` ,`external` ,`custom`)
							VALUES (NULL , 0, '" . $active . "', '" . $new_position . "', '" . $title . "', '" . $target . "', '0', '0', '0', '" . $external . "', '1')";
						break;
				
					case "gm":
						$sql = "INSERT INTO `phpVana_navigation` (`id` ,`section` ,`active` ,`position` ,`title` ,`page` ,`global` ,`authorized` ,`gm` ,`external` ,`custom`)
							VALUES (NULL , 0, '" . $active . "', '" . $new_position . "', '" . $title . "', '" . $target . "', NULL, NULL, '1', '" . $external . "', '1')";
						break;

					case "logged_out":
						$sql = "INSERT INTO `phpVana_navigation` (`id` ,`section` ,`active` ,`position` ,`title` ,`page` ,`global` ,`authorized` ,`gm` ,`external` ,`custom`)
							VALUES (NULL , 0, '" . $active . "', '" . $new_position . "', '" . $title . "', '" . $target . "', '0', '1', '0', '" . $external . "', '1')";
						break;
				}
				mysql_query($sql);

				$action = "new";
				$complete = TRUE;
				break;
			
			case "edit":
				$title = mysql_real_escape_string($_POST['title']);
				$page = mysql_real_escape_string($_POST['target']);
				
				// Checkbox stuff
				if (isset($_POST['external'])) {
					switch ($_POST['external']) {
						case "on":
							$external = "1";
							break;
						default:
							$external = "0";
							break;
					}
				}
				else {
					$external = "0";
				}
		
				if (isset($_POST['active'])){
					switch ($_POST['active']){
						case "on":
							$active = "1";
							break;
						default:
							$active = "0";
							break;
					}
				}
				else {
					$active = "0";
				}
				
				// Access settings
				switch ($_POST['access']) {
					case "all":
						$access = "`global` = '1', `authorized` = '1', `gm` = '1'";
						break;
					
					case "authorized":
						$access = "`global` = '0', `authorized` = '0', `gm` = '0'";
						break;
					
					case "gm":
						$access = "`global` = NULL, `authorized` = NULL, `gm` = '1'";
						break;
					
					case "logged_out":
						$access = "`global` = '0', `authorized` = '1', `gm` = '0'";
						break;
				}
				// Assemble sql				
				mysql_query("UPDATE `phpVana_navigation`
					SET
						`active` = '" . $active . "',
						`title` = '" . $title . "',
						`page` = '" . $page . "',
						" . $access . ",
						`external` = '" . $external . "'
					WHERE
						`id` = " . intval($_GET['id']));
				
				$action = "edit";
				$complete = TRUE;
				break;
			
			case "delete":
				mysql_query("
					DELETE FROM 
						`phpVana_navigation` 
					WHERE 
						`id` = " . intval($_GET['id'])
					);

				$action = "delete";
				$complete = TRUE;
				break;
		}
	}	
}
$acp_body->set("action", $action);
$acp_body->set("complete", $complete);