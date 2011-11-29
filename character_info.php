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
session_start();
if (!isset($_SESSION['user_id'])) {
	header("Location: index.php?page=login&error=nli");
	die();
}

// Include config.php
if (file_exists("config.php"))
	require ("config.php");
else {
	echo ("The file, config.php doesn't exist.");
	die();
}

// Include jobs data
require("includes/job_data.php");

// Connect to the database
$link = @mysql_connect($db_host, $db_user, $db_pass);
if (!$link) {
	$error = "Cannot connect to MySQL server.<br />";
	$error .= mysql_errno() . ": " . mysql_error();
	die($error);
}

// Select the database
$db = @mysql_select_db($db_name);
if (!$db) {
	$error = "Failed to select database.<br />";
	$error .= mysql_errno() . ": " . mysql_error();
	die($error);
}

if (isset($_GET['id'])) {
	$id = intval($_GET['id']);

	// Background image
	$image = ImageCreateFromPng("images/char_info.png");

	// ImageTTFText parameters
	$font  = "fonts/arial.ttf";
	$font_size = "9";

	// Get information from database
	$query = mysql_query(
		"SELECT 
			* 
		FROM 
			`characters` 
		WHERE 
			`ID` = " . $id
	);
	$data = mysql_fetch_array($query);

	if ($_SESSION['user_id'] != $data['userid']) {
		header("Location: index.php?page=login&error=nli");
		die();
	}

	// Colors
	$background_color = imagecolorallocate($image, 255, 255, 255);
	$text_color = imagecolorallocate ($image, 0, 0, 0);

	// Adding text to image
	ImageTTFText($image, $font_size, 0, 37, 14, $text_color, $font, $job_data[$data['job']]['name']);
	ImageTTFText($image, $font_size, 0, 37, 32, $text_color, $font, $data['level']);
	ImageTTFText($image, $font_size, 0, 37, 50, $text_color, $font, $data['str']);
	ImageTTFText($image, $font_size, 0, 37, 68, $text_color, $font, $data['dex']);

	ImageTTFText($image, $font_size, 0, 126, 32, $text_color, $font, $data['fame']);
	ImageTTFText($image, $font_size, 0, 126, 50, $text_color, $font, $data['int']);
	ImageTTFText($image, $font_size, 0, 126, 68, $text_color, $font, $data['luk']);

	// Output header, image and destroy image to free memory
	header ("Content-type: image/png");
	imagePng ($image);
	imagedestroy ($image);
}
