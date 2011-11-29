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

// Include exp data
require("includes/exp_data.php");

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
	
	// ImageTTFText parameters
	$font  = "fonts/arial.ttf";
	$font_size = "9.25";
	
	// Get character information
	$query = mysql_query("SELECT * FROM `characters` WHERE `ID` = " . $id);
	if (mysql_num_rows($query) == 0) {
		die("Character not found");
	}
	$data = mysql_fetch_array($query);
	$guild_query = mysql_query("SELECT name FROM `guilds` WHERE `ID` = " . $data['guildid']);
	$guild_name = "-";
	if (mysql_num_rows($guild_query) == 1)
		$guild_name = mysql_result($guild_query, 0);
	
	if ($_SESSION['user_id'] != $data['userid']) {
		header("Location: index.php?page=login&error=nli");
		die();
	}

	// Set background image
	$image = ImageCreateFromPng("images/stat_background.png");
	$image_transparency = imagecolorat($image, 0, 0);

	// Get transparency
	$transparent_image = ImageCreateFromPng("images/transparent.png");
	$transparency = imagecolorat($transparent_image, 0, 0);

	// Get job main/class
	$main = substr($data['job'], 0, 1);
	$job_main = ImageCreateFromPng("images/job/main/" . $main . ".png");
	$job_main_x = imagesx($job_main);
	$job_main_y = imagesy($job_main);
	imagecolortransparent($job_main, $transparency);
	
	// Get job sub/job
	$job_sub = ImageCreateFromPng("images/job/sub/" . $data['job'] . ".png");
	$job_sub_x = imagesx($job_sub);
	$job_sub_y = imagesy($job_sub);
	imagecolortransparent($job_sub, $transparency);
	
	// Calculate closing bracket
	$closing_bracket = (60 + $job_sub_x + 6);

	// Calculate experience points percentage
	if (isset($exp_data[$data['level']])) {
	 	$percentage = (($data['exp'] / $exp_data[$data['level']]) * 100);
	}
	else {
		$percentage = null;
	}

	// compute stats including
	$str = compute_stats("str");
	$dex = compute_stats("dex");
	$int = compute_stats("int");
	$luk = compute_stats("luk");

	// prep visual stuff
	$hp = ($data['chp'] . " / " . $data['mhp']);
	$mp = ($data['cmp'] . " / " . $data['mmp']);
	$exp = ($data['exp'] . " (" . number_format($percentage, 2, '.', ',') . "%)");

	// Colors
	$background_color = imagecolorallocate($image, 255, 255, 255);
	$black = -imagecolorallocate ($image, 1, 0, 0);
	$red = -imagecolorallocate ($image, 153, 102, 102);

	// Adding text to image
	ImageTTFText($image, $font_size, 0, 60, 45, -$black, $font, $data['name']);
	ImageTTFText($image, $font_size, 0, 60, 91, -$black, $font, $data['level']);
	ImageTTFText($image, $font_size, 0, 60, 109, -$black, $font, $guild_name);

	ImageCopyMerge($image, $job_main, 62, 55, 0, 0, $job_main_x, $job_main_y, 100);

	ImageTTFText($image, 8, 0, 61, 73, $red, $font, "[");
	ImageCopyMerge($image, $job_sub, 65, 66, 0, 0, $job_sub_x, $job_sub_y, 100);
	ImageTTFText($image, 8, 0, $closing_bracket, 73, $red, $font, "]");

	ImageTTFText($image, $font_size, 0, 60, 127, -$black, $font, $hp);
	ImageTTFText($image, $font_size, 0, 60, 145, -$black, $font, $mp);
	ImageTTFText($image, $font_size, 0, 60, 163, -$black, $font, $exp);
	ImageTTFText($image, $font_size, 0, 60, 181, -$black, $font, $data['fame']);

	ImageTTFText($image, $font_size, 0, 60, 255, -$black, $font, $str);
	ImageTTFText($image, $font_size, 0, 60, 273, -$black, $font, $dex);
	ImageTTFText($image, $font_size, 0, 60, 291, -$black, $font, $int);
	ImageTTFText($image, $font_size, 0, 60, 309, -$black, $font, $luk);

	ImageTTFText($image, $font_size, 0, 64, 226, -$black, $font, $data['ap']);

	// Output header, image and destroy image to free memory
	header ("Content-type: image/png");
	imagepng ($image);
	imagedestroy ($image);
}

// Define function to do stat work and save space
function compute_stats($att) {
	global $id, $data;
	$equip_query = "i" . $att;
	$equip_sum = mysql_fetch_array(mysql_query(
		"SELECT 
			SUM(" . $equip_query . ") 
		FROM 
			`items` 
		WHERE 
			`charid` = " . $id . " AND 
			`slot` < 0"
	));

	$base = $data[$att];
	$equips = $equip_sum[0];
	$total = ($base + $equips);

	return ($equips != 0 ? ("$total ($base+$equips)") : $total);
}
