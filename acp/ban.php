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

$acp_body = new Template(getFileLocation("acp/ban.php"));

// Require ban reasons
require("includes/ban_reason.php");

if (!isset($_GET['action'])) {
	// Output banned users
	$ban_count = mysql_fetch_array(mysql_query(
		"SELECT
			COUNT(*)
		FROM
			`users`
		WHERE
			`ban_expire` > NOW()"
	));
	
	if ($ban_count[0] == 0) {
		$list = FALSE;
	}
	else {
		$ban_query = mysql_query(
			"SELECT
				*
			FROM
				`users`
			WHERE
				`ban_expire` > NOW()"
		);
		$banned_users = array();
		while ($data = mysql_fetch_array($ban_query)){
			$banned_users[] = array(
				"id" => $data['ID'],
				"username" => $data['username'],
				"ban_reason" => $ban_reason[$data['ban_reason']],
				"ban_expire" => $data['ban_expire']
			);
		}
		$list = TRUE;
		$acp_body->set("banned_users", $banned_users);
	}
	$action = NULL;
	$complete = FALSE;
	$acp_body->set("list", $list);
}
else {
	if (!isset($_POST['submit'])){
		// We are about to do something
		switch ($_GET['action']) {
			case "ban_user":
				foreach ($ban_reason as $code => $text){
					$reason[] = array(
						"code" => $code,
						"text" => $text
					);
				}
				$action = "ban_user";
				$complete = FALSE;
				$acp_body->set("ban_reason", $reason);
				break;
	
			case "revise_ban":
				// Get information
				$data = mysql_fetch_array(mysql_query("
							SELECT
								*
							FROM
								`users`
							WHERE
								`id` = " . intval($_GET['id'])
							));
				
				// Split date & time
				$split_dt = explode(" ", $data['ban_expire']);
				$expire_date = $split_dt[0];
				$expire_time = $split_dt[1];
				
				// Split up time
				$split_time = explode(":", $expire_time);
				$hour = $split_time[0];
				$minute = $split_time[1];
				$seconds = $split_time[2];
				
				$user_data = array(
					"id" => $data['ID'],
					"username" => $data['username'],
					"ban_reason" => $data['ban_reason'],
					"ban_expire" => $expire_date,
					"hour" => $hour,
					"minute" => $minute,
					"seconds" => $seconds
				);
				
				foreach ($ban_reason as $code => $text){
					$reason[] = array(
						"code" => $code,
						"text" => $text
					);
				}
				
				$action = "revise_ban";
				$complete = FALSE;				
				$acp_body->set("ban_reason", $reason);
				$acp_body->set("user_data", $user_data);
				break;
		
			case "remove_ban":
				$action = "remove_ban";
				$complete = FALSE;
				break;
		
			default:
				break;
		}
	}
	else{
		// Let's do something
		switch ($_GET['action']){
			case "ban_user":
				// Phrase time
				$hour = ($_POST['meridiem'] == "post" ? ($_POST['hour'] + 12) : $_POST['hour']);
			
				// Format the ban_expire data in the format of 0000-00-00 00:00:00
				$ban_expire = ($_POST['ban_expire'] . " " . $hour . ":" . $_POST['minute'] . ":" . $_POST['seconds']);
				
				mysql_query("
					UPDATE
						`users`
					SET
						`ban_expire` = '" . $ban_expire . "',
						`ban_reason` = " . intval($_POST['ban_reason']) . "
					WHERE
						`username` = '" . mysql_real_escape_string($_POST['username']) . "'"
					);
			
				// Output to user
				$action = "ban_user";
				$complete = TRUE;
				$acp_body->set("username", $_POST['username']);
				$acp_body->set("ban_reason", $ban_reason[$_POST['ban_reason']]);				
				break;
		
			case "remove_ban":
				mysql_query ("
					UPDATE
						`users`
					SET
						`ban_expire` = '0000-00-00 00:00:00',
						`ban_reason` = 0
					WHERE
						`id` = " . intval($_GET['id'])
					);
				
				$action = "remove_ban";
				$complete = TRUE;
				break;
		
			case "revise_ban":
				// Phrase time
				$hour = ($_POST['meridiem'] == "post" ? ($_POST['hour'] + 12) : $_POST['hour']);
		
				// Format the ban_expire data in the format of 0000-00-00 00:00:00
				$ban_expire = ($_POST['ban_expire'] . " " . $hour . ":" . $_POST['minute'] . ":" . $_POST['seconds']);
				
				mysql_query("
					UPDATE
						`users`
					SET
						`ban_expire` = '" . $ban_expire . "',
						`ban_reason` = " . intval($_POST['ban_reason']) . "
					WHERE
						`ID` = " . intval($_GET['id'])
					);
				
				$action = "revise_ban";
				$complete = TRUE;
				break;
		
			default:
				break;
		}
	}
}
$acp_body->set("action", $action);
$acp_body->set("complete", $complete);
