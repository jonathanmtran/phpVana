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

$acp_body = new Template(getFileLocation("acp/downloads.php"));

if (!isset($_GET['action'])) {
	// Let's spit out all all of the download
	$query = mysql_query(
				"SELECT
					*
				FROM
					`phpVana_downloads`
				WHERE
					`parent` IS NULL
				ORDER BY
					`position` ASC"
				);
	
	if(mysql_num_rows($query) != 0) {
		$prev_position = 0;
	
		while ($data = mysql_fetch_array($query)) {
			// Get mirrors
			$mirror_query = mysql_query("
								SELECT
									*
								FROM
									`phpVana_downloads`
								WHERE
									`parent` = " . $data['id']
								);
			$num_mirror = mysql_num_rows($mirror_query);
		
			// Get difference for moving items up/down
			$position = $data['position'];
			$difference = ($position - $prev_position);
			
			if ($num_mirror != 0) {
				while ($m_data = mysql_fetch_array($mirror_query)) {
					$mirror_data[] = array(
						"id" => $m_data['id'],
						"address" => $m_data['address'],
						"host" => $m_data['host'],
						"active" => ($m_data['active'] == 1 ? "Active" : "Inactive")
					);
				}		
			}
			else {
				$mirror_data = NULL;
			}
			
			$download_data[] = array(
				"id" => $data['id'],
				"title" => stripslashes($data['title']),
				"description" => stripslashes(nl2br($data['description'])),
				"address" => $data['address'],
				"host" => stripslashes($data['host']),
				"active" => $data['active'],
				"difference" => $difference,
				"mirrors" => $mirror_data
			);
			
			unset($mirror_data);
			
			$prev_position = $position;
		}
		$list_downloads = TRUE;
		$acp_body->set("download_data", $download_data);
	}
	else {
		$list_downloads = FALSE;
	}
	$complete = FALSE;
	$acp_body->set("list_downloads", $list_downloads);
}
else{
	// The user chose to do something
	if (!isset($_POST['submit'])) {
		$id = isset($_GET['id']) ? intval($_GET['id']) : NULL;
		switch ($_GET['action']) {
			case "new":
				$complete = FALSE;
				$action = "new";
				break;
			
			case "edit":
				// Query for information
				$data = mysql_fetch_array(mysql_query(
					"SELECT
						*
					FROM
						`phpVana_downloads`
					WHERE
						`id` = " . $id . "
					LIMIT
						1"
				));
				
				$download_data = array(
					"id" => $data['id'],
					"title" => stripslashes($data['title']),
					"description" => stripslashes($data['description']),
					"host" => stripslashes($data['host']),
					"address" => stripslashes($data['address']),
					"active" => ($data['active'] == 1 ? " checked=\"checked\"" : NULL)
				 );
				
				$action = "edit";
				$complete = FALSE;
				$acp_body->set("data", $download_data);
				break;
			
			case "delete":
				$action = "delete";
				$complete = FALSE;
				$acp_body->set("id", $id);
				break;
	
			case "move_up":
				// Get the position of the current
				$current = mysql_fetch_array(mysql_query(
					"SELECT
						*
					FROM
						`phpVana_downloads`
					WHERE
						`id` = " . $id
				));
		
				// Subtract $difference to get the above
				$above = ($current['position'] - $_GET['difference']);
		
				// Get the link with the position of $above
				$above = mysql_fetch_array(mysql_query(
							"SELECT
								*
							FROM
								`phpVana_downloads`
							WHERE
								`position` = " . $above
							));
		
				// Get the id
				$above_id = ($above['id']);
				if ($above['id'] != ""){	
					mysql_query("
						UPDATE
							`phpVana_downloads`
						SET
							`position` = " . $above['position'] . "
						WHERE
							`id` = " . $id
						);
					
					mysql_query("
						UPDATE
							`phpVana_downloads`
						SET
							`position` = " . $current['position'] . "
						WHERE
							`id` = " . $above_id
						);
				}	
				// Throw the user back to the page. hopefully it creates an instant effect
				header ("Location: " . $_SERVER['PHP_SELF'] . "?page=acp&section=downloads");
				break;
	
			case "move_down":
				// Get the position of the current
				$current = mysql_fetch_array(mysql_query(
					"SELECT
						*
					FROM
						`phpVana_downloads`
					WHERE
						`id` = " . $id
				));
		
				// Subtract $difference to get the below
				// Top has a difference of zero. we have to figure out a workaround.
				$below = ($current['position'] + intval($_GET['difference']));
		
				// Get the entry with the position of $below
				$below = mysql_fetch_array(mysql_query(
					"SELECT
						*
					FROM
						`phpVana_downloads`
					WHERE
						`position` = " . $below
				));
		
				// Get the id of that entry if a entry doesn't exist below, go back to the downloads page
				$below_id = ($below['id']);
				if ($below['id'] != "") {
					mysql_query(
						"UPDATE
							`phpVana_downloads`
						SET
							`position` = " . $below['position'] . "
						WHERE
							`id` = " . $id
							);
			
					mysql_query(
						"UPDATE
							`phpVana_downloads`
						SET
							`position` = " . $current ['position'] . "
						WHERE
							`id` = " . $below_id
						);
				}
		
				// Throw the user back to the page. hopefully it creates an instant effect
				header ("Location: " . $_SERVER['PHP_SELF'] . "?page=acp&section=downloads");
				break;
	
			case "add_mirror":
				$action = "add_mirror";
				$complete = FALSE;
				$acp_body->set("id", $id);
				break;
		
			case "edit_mirror":
				// Pull information on this mirror
				$data = mysql_fetch_array(mysql_query(
					"SELECT
						*
					FROM
						`phpVana_downloads`
					WHERE
						`id` = " . $id
				));

				$mirror_data = array(
					"id" => $data['id'],
					"host" => $data['host'],
					"address" => $data['address'],
					"active" => ($data['active'] == 1 ? " checked" : NULL)
				);
				
				$action = "edit_mirror";
				$complete = FALSE;
				$acp_body->set("data", $mirror_data);
				break;
	
			case "delete_mirror":
				$action = "delete_mirror";
				$complete = FALSE;
				$acp_body->set("id", $id);
				break;
		}
	}
	else {
		// We are doing something now
		switch ($_GET['action']) {
			case "new":
				$title = mysql_real_escape_string($_POST['title']);
				$description = mysql_real_escape_string($_POST['description']);
				$host = mysql_real_escape_string($_POST['host']);
				$address = mysql_real_escape_string($_POST['address']);
	
				// Get last position
				$last_query = mysql_fetch_array(mysql_query("
									SELECT 
										* 
									FROM 
										`phpVana_downloads` 
									ORDER BY 
										`position` 
									DESC 
									LIMIT 1"
									));
				$last = $last_query['position'];

				// Prepare and execute sql
				mysql_query("
					INSERT INTO 
						`phpVana_downloads` 
						(`id`, 
						 `active`, 
						 `position`, 
						 `title`, 
						 `description`, 
						 `host`, 
						 `address`, 
						 `parent`)
					VALUES 
						(NULL, 
						 " . ($_POST['active'] == "on" ? 1 : 0) . ", 
						 " . ($last + 1) . ", 
						 '" . $title . "', 
						 '" . $description . "', 
						 '" . $host . "', 
						 '" . $address . "', 
						 NULL)"
					);
				
				$complete = TRUE;
				$action = "new";
				break;
			
			case "edit":
				$id = intval($_GET['id']);
				$title = mysql_real_escape_string($_POST['title']);
				$description = mysql_real_escape_string($_POST['description']);
				$host = mysql_real_escape_string($_POST['host']);
				$address = mysql_real_escape_string($_POST['address']);

				mysql_query("
					UPDATE 
						`phpVana_downloads`
					SET 
						`title` = '" . $title . "',
						`description` = '" . $description . "',
						`host` = '" . $host . "',
						`address` = '" . $address . "',
						`active` = " . (($_POST['active'] == "on") ? 1 : 0) . "
					WHERE
						`id` = " . $id
					);
				
				$complete = TRUE;
				$action = "edit";
				break;
			
			case "delete":
				$id = intval($_GET['id']);
				
				// remove the download itself
				mysql_query("
					DELETE FROM
						`phpVana_downloads`
					WHERE
						`id` = " . $id
						);
				
				// Remove the mirrors
				mysql_query("
					DELETE FROM
						`phpVana_downloads`
					WHERE
						`parent` = " . $id
					);
				
				$complete = TRUE;
				$action = "delete";
				break;
	
			case "add_mirror":
				$id = intval($_GET['id']);
				$host = mysql_real_escape_string($_POST['host']);
				$address = mysql_real_escape_string($_POST['address']);
				
				mysql_query("
					INSERT INTO
						`phpVana_downloads`
						(`active`, 
						 `position`, 
						 `host`, 
						 `address`, 
						 `parent`)
					VALUES
						(" . ($_POST['active'] == "on" ? 1 : 0) . ",  
						NULL, 
						'" .  $host . "', 
						'" . $address . "', 
						" . $id . ")"
					);
				
				$complete = TRUE;
				$action = "add_mirror";
				break;
		
			case "edit_mirror":
				$id = intval($_GET['id']);
				$host = mysql_real_escape_string($_POST['host']);
				$address = mysql_real_escape_string($_POST['address']);

				mysql_query("
					UPDATE 
						`phpVana_downloads`
					SET
						`host` = '" . $host . "',
						`address` = '" . $address . "',
						`active` = " . ($_POST['active'] == "on" ? 1 : 0) . "
					WHERE
						`id` = " . $id
					);
				
				$complete = TRUE;
				$action = "edit_mirror";
				break;
		
			case "delete_mirror":
				$id = intval($_GET['id']);
				$delete_mirror = (
					"DELETE FROM
						`phpVana_downloads`
					WHERE
						`id` = " . $id
				);
				mysql_query("
					DELETE FROM
						`phpVana_downloads`
					WHERE
						`id` = " . $id
					);
				$complete = TRUE;
				$action = "delete_mirror";
				break;
		}
	}
	$acp_body->set("action", $action);
}
$acp_body->set("complete", $complete);
