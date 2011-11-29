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

// Include main functions
require("includes/functions.php");
require("includes/templates.php");
require("includes/timer.class.php");

// Start the timer
$timer = new Timer();
$timer->start();

// Start the session
session_start();

// Include the configuration file, die if doesn't exist
file_exists("config.php") ? require("config.php") : die("Config.php not found!");

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

// Load configuration
$config_result = mysql_query("
					SELECT 
						* 
					FROM 
						`phpVana_config`"
					);
while ($row = mysql_fetch_assoc($config_result)) {
	$phpVana_config[$row['config_name']] = stripslashes($row['config_value']);
}

// Load the template
$template = $phpVana_config['template'];

$template_path = file_exists("templates/" . $template . "/template.php") ? "templates/" . $template : "templates/CoolWater";
$tpl = new Template($template_path . "/template.php");

// Slogans
if(isset($phpVana_config['site_slogan'])) {
	$slogans = explode("\n", $phpVana_config['site_slogan']);
	$slogan_number = rand(0, count($slogans) - 1);
	$slogan = trim($slogans[$slogan_number]);
}

$html_title = $phpVana_config['site_name'];
if($slogan != NULL) {
	$html_title .= ": " . $slogan;
}

// Load content
if(isset($_GET['page'])) {
	$page = stripslashes($_GET['page']);
	// Look to see if the page exists
	if(file_exists($page . ".php")) {
		include($page . ".php");
	}
	else {
		$body = new Template(getFileLocation("error.php"));
		$body->set("error", "404");
	}
}
else {
	include("frontpage.php");
}

// End timer
$timer->stop();

// Set variables and output page to user
$tpl->set("content", $body);
$tpl->set("html_title", $html_title);
$tpl->set("render_time", $timer->time());
$tpl->set("site_name", $phpVana_config['site_name']);
$tpl->set("site_slogan", $slogan);
$tpl->set("template", $template);

echo $tpl->fetch($template_path . "/template.php");
