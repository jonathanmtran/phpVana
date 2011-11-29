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

$acp_body = new Template(getFileLocation("acp/edit_user.php"));

if (!isset($_POST['username'])) {
	$acp_body->set("username", false);
}
else {
	// Does the user exist?
	$user_existence = mysql_query(
		"SELECT
			*
		FROM
			`users`
		WHERE
			`username` = '" . mysql_real_escape_string($_POST['username']) . "'"
	);
	
	if(mysql_num_rows($user_existence) == 1) {
		$username = TRUE;
		
		if (!isset($_POST['submit'])) {
			$user_data = mysql_fetch_array(mysql_query(
				"SELECT * FROM
					`users`
				WHERE
					`username` = '" . mysql_real_escape_string($_POST['username']) . "'"
			));
			
			// Gender stuff
			$gender_levels = array("Male", "Female");

			for ($i = 0; $i < 2; $i++) {
				$gender[] = array(
					"value" => $i,
					"text" => $gender_levels[$i],
					"selected" => ($user_data['gender']) == $i ? " selected=\"selected\"" : NULL
				);
			}
			
			// GM level stuff
			$gm_levels = array("User", "GM", "Super GM", "Administrator");
			
			for ($i = 0; $i <4; $i++) {
				$gm[] = array(
					"value" => $i,
					"text" => $gm_levels[$i],
					"selected" => ($user_data['gm']) == $i ? " selected=\"selected\"" : NULL
				);
			}		
			
			// Create array containing user information
			$user_info = array(
				"id" => $user_data['ID'],
				"username" => $user_data['username'],
				"pin" => $user_data['pin'],
				"gender" => $gender,
				"gm" => $gm
			);
			
			// Set template variables
			$acp_body->set("user_data", $user_info);
			$acp_body->set("complete", false);
		}
		else {
			// Prepare variables accordingly
			$id = intval($_POST['id']);
			$username = mysql_real_escape_string($_POST['username']);
			$password = mysql_real_escape_string($_POST['password']);
			$pin = intval($_POST['pin']);
			$gender = intval($_POST['gender']);
			$gm = intval($_POST['gm']);
			
			// Determine if the password is goinig to be changed
			$update_password = false;
			if ($password == "") {
				$update_password = false;
			}
			else {
				// Generate salt
				$salt = NULL;
				for ($i = 0; $i < 5; $i++) {
					$salt = $salt . chr(rand(33, 126));
				}
				
				// Hash the password
				$encrypted_password = strtoupper(sha1($salt . $password));
				$update_password = true;
			}

			// Query
			mysql_query (
				"UPDATE
					`users`
				SET
					`username` = '" . $username . "',
					" . ($update_password ? "`password` = '" . mysql_real_escape_string($encrypted_password) . "',
					`salt` = '" . mysql_real_escape_string($salt) . "', " : "") . "
					`pin` = " . $pin . ",
					`gender` = " . $gender . ",
					`gm` = " . $gm . "
				WHERE
					`ID` = " . $id);
			
			// Set template variables
			$acp_body->set("complete", true);
		}
	}
	else {
		$username = FALSE;
	}
	$acp_body->set("username", $username);
}