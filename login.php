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

$body = new Template(getFileLocation("login.php"));

if (isset($_GET['error'])) {
	$body->set('error_code', $_GET['error']);
}

if (!isset($_POST['submit'])) {
	$body->set('msg', NULL);
}
else{
	// Preventative steps
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);

	// Check if user exists
	$query = mysql_query("SELECT * FROM `users` WHERE `username` = '" . $username . "' LIMIT 1");

	if (mysql_num_rows($query) == 1) {
		$user_data = mysql_fetch_array($query);

		// Get salt
		$salt = $user_data['salt'];

		// See if salt exists and do what needs to be done at that point

		$supplied_password = $password; // No hash
		if (isset($salt)) { // Is there a salt set?
			if (strlen($salt) == 5) { // SHA-1 pass
				$supplied_password = strtoupper(sha1($salt . $password));
			}
			elseif (strlen($salt) == 10) { // SHA-512 pass
				$supplied_password = strtoupper(hash("sha512", $salt . $password));
			}
		}

		// Check against password in the database
		if ($supplied_password == $user_data['password']) {

			// Assign session variables
			$nav_level = ($user_data['gm'] + 1);

			$_SESSION['nav_level'] = $nav_level;
			$_SESSION['user_id'] = $user_data['ID'];
			$_SESSION['username'] = $username;

			// Redirect back to user control pannel with welcome
			header("Location: " . $_SERVER['PHP_SELF'] . "?page=ucp&welcome=yes");
		}
		else {
			// Credentials are invalid. Redirect back to login page with error
			header("Location: " . $_SERVER['PHP_SELF'] . "?page=login&error=invalid");
		}
	}
	else {
		// User does not exist. Redirect back to login page with error
		header("Location: " . $_SERVER['PHP_SELF'] . "?page=login&error=invalid");
	}
}