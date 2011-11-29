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

$body = new Template(getFileLocation("register.php"));

if (isset($_SESSION['user_id'])) {
	header("Location: " . $_SERVER['PHP_SELF']);
	die();
}
elseif (!isset($_POST['submit'])) {
	// Set the error code if we were given one
	if (isset($_GET['error']))
		$body->set("error_code", $_GET['error']);
	
	// Set template variables
	$body->set("public_cap_code", $phpVana_config['public_cap_code']);
	$body->set("char_delete_method", $phpVana_config['char_delete_method']);
	$body->set("use_captcha", $phpVana_config['use_captcha']);
	$body->set("msg", NULL);
}
else {
	// Check to see if the variables were set
	if (!isset($_POST['username']) || trim($_POST['username']) == "") {
		header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=nu");
		die();
	}

	if (!isset($_POST['password']) || trim($_POST['password']) == "") {
		header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=np");
		die();
	}
	
	if ($phpVana_config['char_delete_method'] == "birthdate") {
		if ($_POST['year'] == "" || $_POST['month'] == "" || $_POST['day'] == "") {
			header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=cdbns");
			die();
		}
	}
	elseif ($phpVana_config['char_delete_method'] == "password") {
		if ($_POST['char_delete_password'] == "") {
			header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=cdpns");
			die();
		}
	}

	// Check for alpha numeric characters
	if (!validate_input($_POST['username'], "alphanum")) {
		header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=nanu");
		die();
	}

	if ($phpVana_config['char_delete_method'] == "birthdate") {
		if (!validate_input($_POST['year'], "numeric") || !validate_input($_POST['day'], "numeric")) {
			header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=cdbnn");
			die();
		}
	}
	elseif ($phpVana_config['char_delete_method'] == "password" && !validate_input($_POST['char_delete_password'], "numeric")) {
		header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=cdpnn");
		die();
	}

	// Check lengths
	if (strlen($_POST['username']) > 12 || strlen($_POST['username']) < 4) {
		header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=lu");
		die();
	}
		
	if (strlen($_POST['password']) > 12 || strlen($_POST['password']) < 4) {
		header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=lp");
		die();
	}

	if ($phpVana_config['char_delete_method'] == "birthdate") {
		if ((strlen($_POST['year']) > 4 || strlen($_POST['year']) < 4) && (strlen($_POST['date']) > 2 || strlen($_POST['year']) < 1)) {
			header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=cdbl");
			die();
		}
	}
	elseif ($phpVana_config['char_delete_method'] == "password") {
		if (strlen($_POST['char_delete_password']) > 8) {
			header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=cdpl");
			die();
		}
	}

	if ($phpVana_config['use_captcha'] == "yes") {
		require_once('includes/recaptchalib.php');
		$resp = recaptcha_check_answer ($phpVana_config['private_cap_code'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		
		if (!$resp->is_valid) {
			header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=capt&errstr=" . $resp->error);
			die ();
		}
	}

	// Check for duplicate username
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$user_query = mysql_query(
					"SELECT 
						COUNT(*) 
					FROM 
						`users` 
					WHERE 
						`username` = '" . $username . "'"
					);
	$check_user = mysql_fetch_array($user_query);
	if ($check_user[0] != 0) {
		header("Location: " . $_SERVER['PHP_SELF'] . "?page=register&error=ue");
		die();
	}
	else {
		// Generate salt
		$salt = NULL;
		
		// Hash the password
		$hash = "";
		if (isset($phpVana_config["password_encryption"]) && $phpVana_config["password_encryption"] == "SHA-512") {
			for ($i = 0; $i < 10; $i++) {
				$salt = $salt . chr(rand(33, 126));
			}
			$hash = hash("sha512", $salt . $password);		
		}
		else {
			for ($i = 0; $i < 5; $i++) {
				$salt = $salt . chr(rand(33, 126));
			}
			$hash = sha1($salt . $password);
		}
		$password = strtoupper($hash);

		// Phrase user input into an eight digit integer
		if ($phpVana_config['char_delete_method'] == "birthdate")
			$char_delete_password = intval($_POST['year'] . $_POST['month'] . $_POST['day']);
		else//if ($phpVana_config['char_delete_method'] == "password")
			$char_delete_password = intval($_POST['char_delete_password']);

		mysql_query ("INSERT INTO `users` (`username`, `password`, `salt`, `char_delete_password`, `last_login`) VALUES ('" . $username . "', 
																											   '" . $password . "', 
																											   '" . mysql_real_escape_string($salt) . "', 
																											   '" . $char_delete_password . "', 
																											   NOW())") or die (mysql_error());

		$user_data = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `ID` = '" . mysql_insert_id() . "' LIMIT 1"));
		
		$nav_level = ($user_data['gm'] + 1);
		
		$_SESSION['nav_level'] = $nav_level;
		$_SESSION['user_id'] = $user_data['ID'];
		$_SESSION['username'] = $username;
		
		$body->set("username", $username);
	}
}
