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

/* navigation
This function will take the navigation items from the database, add prefixes
and or suffexes, then will create the navigation either horizontally or
vertically 

navigation(direction, link prefix, link selected prefix, link suffix, link text prefix, link text suffix, navigation prefix, navigation suffix)
*/
function navigation($direction, $item_prefix="", $selected_prefix="", $item_suffix="", $text_prefix="", $text_suffix="", $nav_prefix="", $nav_suffix="", $seperator="") {
	$nav_level = (isset($_SESSION['nav_level'])) ? $_SESSION['nav_level'] : -1;
	switch($nav_level) {
		case 1:
			// Non-gm
			$global = "AND `global` = 1 ";
			$authorized = 0;
			break;
		case 2:
		case 3:
		case 4:
			// Gm
			$global = "AND `gm` = 1 ";
			$authorized = 0;
			break;
		default:
			// Everyone else
			$global = "AND `global` = 1 ";
			$authorized = 1;
			break;
	}

	$result = mysql_query("
				SELECT
				*
				FROM
					`phpVana_navigation`
				WHERE
					`section` = 0
				AND 
					`active` = 1 
					" . $global . "
				OR 
					(active = 1 AND `authorized` = ".$authorized.")
				ORDER BY
					`position` 
				ASC");

	$cur_page = (isset($_GET['page'])) ? $_GET['page'] : NULL;

	switch($direction) {
		case "horizontal":
			$num_rows = mysql_num_rows($result);
			$horizontal_limit = ($num_rows - 1);

			echo $nav_prefix;
			$i = 0;
			while ($data = mysql_fetch_array($result)) {
				$i_prefix = ($cur_page == $data['page']) ? $selected_prefix  : $item_prefix;
				echo "		" . $i_prefix . "<a href=\"". ($data['external'] == 1 ? $data['page'] : ("index.php" . ($data['page'] != "" ? ("?page=" . $data['page']) : NULL))) . "\">" . $text_prefix . stripslashes($data['title']) .$text_suffix . "</a>" . $item_suffix;
				echo ($i < $horizontal_limit) ? $seperator : NULL;
				$i++;
			}
			echo $nav_suffix;
			break;
		default:
			while ($data = mysql_fetch_array($result)) {
				$page = ($data['page'] != "") ? ("?page=" . $data['page']) : $data['page'];
				$i_prefix = ($page == $data['page']) ? $selected_prefix : $item_prefix;	
				echo "		" . $i_prefix . "<a href=\"index.php" . $page . "\">" . $text_prefix . stripslashes($data['title']) . $text_suffix . "</a>" . $item_suffix . "<br />";
			}
			break;
	}
}

/* check_server
This function will check to see if a server is online */
function check_server($host, $port) {
	$server = @fsockopen($host, $port, $errno, $errstr, 1);
	if (!$server) {
		return false;
	}
	else {
		fclose($server);
		return true;
	}
}
	
/* copyright_footer
This function displays a mini copyright footer */
function copyright_footer() {
	global $phpVana_config;
	$site_name = $phpVana_config['site_name'];
	$year = date('Y');
	echo '<span class="copyright">'.$site_name.' &copy; '.$year.' | Powered by <em><a href="http://sourceforge.net/projects/phpvana/">phpVana</a></em></span>';
}
	
/* hour_selector
This function will create a dropdown select for hours */
function hour_selector($current=NULL) {
	$current = ($current > 12) ? ($current - 12) : $current;
		
	$hour_mapping = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 0 => 12);

	echo "<select name=\"hour\">\n";
	foreach ($hour_mapping as $value => $hour) {
		$selected = ($current == $value) ? " selected=\"selected\"" : NULL;
		echo "<option value=\"".$value."\"".$selected.">".$hour."</option>\n";
	}
	echo "</select>\n";
}

/* minsec_selector
This function will create a dropdown select for minutes/seconds */
function minsec_selector($name, $current=NULL) {
	$m = "0";
	echo "<select name=\"$name\">\n";
	while ($m <= "59") {
		$minute = $m < 10 ? "0" . $m : $m;
		$selected = $current == $minute ? " selected=\"selected\"" : NULL;
		echo "<option value=\"".$minute."\"".$selected.">".$minute."</option>\n";
		$m++;
	}
	echo "</select>\n";
}
	
/* meridiem_selector
This function will create an am/pm selector given hour*/
function meridiem_selector($hour = NULL) {
	$ante = $post = NULL;
	if ($hour < 13)
		$ante = " selected=\"selected\"";
	else
		$post = " selected=\"selected\"";
	
	echo "<select name=\"meridiem\">
		<option value=\"ante\"".$ante.">A.M.</option>
		<option value=\"post\"".$post.">P.M.</option>
	</select>";
}

/* module()
This function facilitates the modules found on the side(s) of the page */
function module($module_name) {
	global $phpVana_config;
	global $template_path;
	include ("modules/" . $module_name . ".php");
	echo $module->fetch(getFileLocation("modules/" . $module_name . ".php"));
}

/* news()
This function facilittes the display of news */
function news($display="", $category="", $number="") {
	global $phpVana_config;
	global $template_path;
	
	// Since none of the parameters are set, we will just display good ol' news
	if($display == "" && $category == "" && $number == "") {
		// Check if template has a file for news. If not then revert to CoolWater
		$news_template = getFileLocation("modules/news.php");
		$news = new Template($news_template);
		
		// Get the number of news posts
		$num_query = mysql_fetch_array(mysql_query("
						SELECT 
							COUNT(*) 
						FROM 
							`phpVana_news`"
						));
		
		// Display news if the number is greater than zero
		if ($num_query[0] != 0) {
			$query = mysql_query ("SELECT news.*, user.username FROM phpVana_news news LEFT JOIN users user ON user.ID = news.owner ORDER BY news.date DESC, news.time DESC LIMIT " . $phpVana_config['news_limit']);
		
			while ($data = mysql_fetch_array($query)) {
				$comments = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `phpVana_comments` WHERE `article_id` = " . $data['id']));
				$news_data[] = array(
					"id" => $data['id'],
					"title" => stripslashes($data['title']),
					"owner" => $data['username'],
					"date" => date($phpVana_config['date_format'], strtotime($data['date'])),
					"time" => date($phpVana_config['time_format'], strtotime($data['time'])),
					"content" => nl2br(stripslashes($data['content'])),
					"num_comments" => $comments['0']
				);
			}
			$news->set("news_data", $news_data);
			$news->set("news", true);
		}
		else {
			$news->set("news", false);
		}
		echo $news->fetch($news_template);
	}
	// TODO: Otherwise, we will go and display headlines from categories
}

/* error_message()
This function displays an error message from the given code */
function error_message($code) {
	global $template_path;
	
	// Check if template has a file for error messages. If not then revert to CoolWater
	$error_messages = getFileLocation("error_messages.php");
	$error_msg = new Template($error_messages);
	
	$error_msg->set("code", $code);
	echo $error_msg->fetch($error_messages);
}

/* validate_input()
Validates input given type and returns true or false */
function validate_input($string, $type) {
	switch($type) {
		case "alphanum":
			$regex = "[^A-Za-z0-9]";
			break;
		
		case "numeric":
			$regex = "[^0-9]";
			break;
	}
	
	$matches = NULL;
	$result = preg_replace($regex, NULL, $string);
	return ($result == $string ? TRUE : FALSE);
}

function get_world($worldid) {
	require("basic_data.php");
	echo '<img src="images/world_'.$worldid.'.gif" alt="'.$worldnames[$worldid].'" style="display:inline-block; vertical-align:middle;" />';
}

function getWorldName($worldid) {
	require("basic_data.php");
	return $worldnames[$worldid];
}

function getFileLocation($filename) {
	global $template_path;
	$template_file =  $template_path . "/" . $filename;
	$layout_file = "layout/" . $filename;
	return file_exists($template_file) ? $template_file : $layout_file;	
}

function js_sc($str) {
	$pattern[0] = '/script/';
	$pattern[1] = '/on/';
	$replacement[0] = 'scr<b></b>ipt';
	$replacement[1] = 'o<b></b>n';
	$string = preg_replace($pattern, $replacement, $str);
	return $string;
}

function parseString($str) {
	return js_sc(htmlentities($str, ENT_QUOTES));;
}

?>