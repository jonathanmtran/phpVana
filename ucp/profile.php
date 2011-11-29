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

$ucp_body = new Template(getFileLocation("ucp/profile.php"));

if (isset($_GET['error'])) {
	$ucp_body->set("error_msg", stripslashes($_GET['error']));
}

if (!isset($_POST['submit'])) {
	$ucp_body->set("success", FALSE);
	
	// Fetch current information
	$query = mysql_query("SELECT * FROM `users` WHERE `ID` = " . $_SESSION['user_id']);
	$data = mysql_fetch_array($query);
	$ucp_body->set("username", $data['username']);
}
else {
	// Fetch current information
	$query = mysql_query("SELECT * FROM `users` WHERE `ID` = " . $_SESSION['user_id'] . " LIMIT 1");
	$data = mysql_fetch_array($query);
	
	$oldPass = mysql_real_escape_string($_POST['password']);
	$newPass1 = mysql_real_escape_string($_POST['new_password']);
	$newPass2 = mysql_real_escape_string($_POST['cnew_password']);

	// Get salt
	$salt = $data['salt'];

	// See if salt exists and do what needs to be done at that point
	$old_password = $oldPass; // No hash
	if (isset($salt)) { // Is there a salt set?
		if (strlen($salt) == 5) { // SHA-1 pass
			$old_password = strtoupper(sha1($salt . $oldPass));
		}
		elseif (strlen($salt) == 10) { // SHA-512 pass
			$old_password = strtoupper(hash("sha512", $salt . $oldPass));
		}
	}

	// Let's check for errors
	if ($old_password != $data['password'])
		header("Location: index.php?page=ucp&section=profile&error=wp");

	if ($newPass1 != $newPass2)
		header("Location: index.php?page=ucp&section=profile&error=pdm");

	// Check alphanumerical stuff and length
	if (strlen($newPass1) > 12 || strlen($newPass1) < 6)
		header("Location: index.php?page=ucp&section=profile&error=lp");

	// Generate salt
	$salt = NULL;
	$hash = "";
	if (isset($phpVana_config["password_encryption"]) && $phpVana_config["password_encryption"] == "SHA-512") {
		for ($i = 0; $i < 10; $i++) {
			$salt = $salt . chr(rand(33, 126));
		}
		$hash = hash("sha512", $salt . $newPass1);		
	}
	else {
		for ($i = 0; $i < 5; $i++) {
			$salt = $salt . chr(rand(33, 126));
		}
		$hash = sha1($salt . $newPass1);
	}
	$password = strtoupper($hash);

	// Prepare and execute sql
	mysql_query("UPDATE `users` SET `password` = '" . mysql_real_escape_string($password) . "', `salt` = '" . mysql_real_escape_string($salt) . "' WHERE `ID` = " . $_SESSION['user_id']) or die(mysql_error());

	// Escape php and print success message
	$ucp_body->set("success", true);
}