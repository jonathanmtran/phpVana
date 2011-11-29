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

$acp_body = new Template(getFileLocation("acp/navigation-acp.php"));

if (!isset($_GET['action'])) {
	// Let's spit out all all of the navigation entries
	$query = mysql_query(
		"SELECT
				*
		FROM
				`phpVana_navigation`
		WHERE
				`section` = 1
		ORDER BY
				`position` ASC"
	);
	
	$prev_position = 0;
	
	while ($data = mysql_fetch_array($query)) {
		if($data['gm'] == 1) {
			$visibility = "GM";
		}
		elseif ($data['gm'] == 2) {
			$visibility = "Super GM";
		}
		elseif ($data['gm'] == 3) {
			$visibility = "Administrator";
		}
		
		$active = ($data['active'] == 0) ? "Inactive" : "Active";
		
		$navigation_data[] = array(
			"id" => $data['id'],
			"title" => stripslashes($data['title']),
			"visibility" => $visibility,
			"active" => $active
		);
	}
	$complete = FALSE;
	$acp_body->set("navigation_data", $navigation_data);
}
else {
	// The user chose to do something
	$id = intval($_GET['id']);
	if (!isset($_POST['submit'])) {
		switch ($_GET['action']) {
			case "edit":
				// Get link information
				$data = mysql_fetch_array(mysql_query(
					"SELECT 
						* 
					FROM 
						`phpVana_navigation` 
					WHERE 
						`id` = " . $id . " 
					LIMIT 1"
				));

				// Convert the 0s and 1s back to checked
				$c_active = ($data['active'] == 1) ? " checked=\"checked\"" : NULL;

				// Assign which one gets to have the selected
				if ($data['gm'] == 3) {
					$admin = " selected=\"selected\"";
					$sgm = null;
					$gm = null;
				}
				elseif ($data['gm'] == 2) {
					$admin = null;
					$sgm = " selected=\"selected\"";
					$gm = null;
				}
				else {
					$admin = null;
					$sgm = null;
					$gm = " selected=\"selected\"";
				}

				$link_data = array(
					"id" => $id,
					"title" => $data['title'],
					"active" => $c_active,
					"admin" => $admin,
					"sgm" => $sgm,
					"gm" => $gm
				);

				$action = "edit";
				$complete = FALSE;
				$acp_body->set("data", $link_data);
				break;
		}
	}
	else {
		// We are doing something!
		switch ($_GET['action']) {
			case "edit":
				// Checkbox stuff
				$active = isset($_POST['active']) ? (($_POST['active'] == "on") ? 1 : 0) : 0;

				switch ($_POST['access']) {
					case 3:
						$access = 3;
						break;
					case 2:
						$access = 2;
						break;
					default:
						$access = 1;
						break;
				}

				// Assemble sql
				$sql = (
					"UPDATE
						`phpVana_navigation`
					SET
						`active` = " . $active . ",
						`gm` = " . $access . "
					WHERE
						`id` = " . $id
				);
				mysql_query($sql);

				$action = "edit";
				$complete = TRUE;	
				break;
		}
	}
	$acp_body->set("action", $action);
}
$acp_body->set("complete", $complete);