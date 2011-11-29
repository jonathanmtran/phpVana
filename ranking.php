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

$body = new Template(getFileLocation("ranking.php"));

function countAmountInVal($value, $step) {
	$counter = 0;
	$count_val = 0;
	while ($count_val < $value) {
		$count_val += $step;
		$counter++;
	}
	return $counter;
}

function getDowner($value, $step) {
	$counter = 0;
	while ($counter < $value) {
		$counter += $step;
	}
	return $counter;
}

function getSelected($var, $value) {
	return ((isset($var) && $var == $value) ? ' selected="selected"' : "");
}

// Require data for jobs
require("includes/job_data.php");
require("includes/basic_data.php");

// Define how many records to show
$char_num = $phpVana_config['ranking_page_limit'];
$char_count = 0;
@$trang = $_GET['trang'];
$body->set("auto_sub_goto", $phpVana_config['auto_sub_goto']);
$starting_num = 0;

// Define limits and ranking number based on page
if (!isset($trang)) {
	$limit = "LIMIT  0, " .$char_num;
	$rank = 1;
}
else {
	$starting_num = (($trang) - 1) * $char_num;
	$limit = "LIMIT " . $starting_num . ", " . $char_num;
	$rank = ($starting_num == 0) ? 1 : ($starting_num + 1);
}

// Are gm users included?
$gm_user = $phpVana_config['ranking_show_gm_user'] == 1 ? " users.gm <= 3" : " users.gm = 0";

// Are gm characters included?
$gm_character = $phpVana_config['ranking_show_gm_character'] == 1 ? "OR `job` = '900' OR `job` = '910'" : "AND `job` <> '900' AND `job`<> '910'";

$job = "";

// Build sql query
if (isset($_GET['sort'])) {
	switch ($_GET['sort']) {
		case "fame":
			$job = $gm_character;
			$order = "fame";
			$sort = "fame";
			break;

		case "beginner":
			$job = "AND `job` = 0";
			$order = "level";
			$sort = "beginner";
			break;

		case "bowman":
			$job = "AND job >= 300 AND job < 400";
			$order = "level";
			$sort = "bowman";
			break;

		case "magician":
			$job = "AND job >= 200 AND job < 300";
			$order = "level";
			$sort = "magician";
			break;

		case "thief":
			$job = "AND job >= 400 AND job < 500";
			$order = "level";
			$sort = "thief";
			break;

		case "warrior":
			$job = "AND job >= 100 AND job < 200";
			$order = "level";
			$sort = "warrior";
			break;

		case "pirate":
			$job = "AND job >= 500 AND job < 600";
			$order = "level";
			$sort = "pirate";
			break;
			
		case "noblesse":
			$job = "AND job >= 1000 AND job < 1100";
			$order = "level";
			$sort = "noblesse";
			break;
			
		case "windarcher":
			$job = "AND job >= 1300 AND job < 1400";
			$order = "level";
			$sort = "windarcher";
			break;
			
		case "blazewizard":
			$job = "AND job >= 1200 AND job < 1300";
			$order = "level";
			$sort = "blazewizard";
			break;
			
		case "dawnwarrior":
			$job = "AND job >= 1100 AND job < 1200";
			$order = "level";
			$sort = "dawnwarrior";
			break;
			
		case "nightwalker":
			$job = "AND job >= 1400 AND job < 1500";
			$order = "level";
			$sort = "nightwalker";
			break;
			
		case "thunderdreaker":
			$job = "AND job >= 1500 AND job < 1600";
			$order = "level";
			$sort = "thunderdreaker";
			break;
			
		case "gmjob":
			if ($phpVana_config['ranking_show_gm_character']) {
				$job = "AND job >= 900 AND job < 1000";
				$order = "level";
				$sort = "gmjob";
				break;
			}
			else {
				$job = $gm_character;
				$order = "level";
				$sort = "overall";	
			}
			
		case "gmstat":
			if ($phpVana_config['ranking_show_gm_character']) {
				$job = "AND users.gm <> 0";
				$order = "level";
				$sort = "gmstat";
				break;
			}
			else {
				$job = $gm_character;
				$order = "level";
				$sort = "overall";	
			}

		default:
			$job = $gm_character;
			$order = "level";
			$sort = "overall";
			break;
	}
}
else {
	$job = $gm_character;
	$order = "level";
	$sort = "overall";
}

$q = "SELECT @row:=@row+1 AS ranknum, characters.*, users.gm FROM (SELECT @row:= 0) a, characters LEFT JOIN `users` ON characters.userid = users.ID WHERE ban_expire < NOW() AND ".$gm_user." ".$job." ORDER BY `".$order."` DESC, `exp` DESC ";
$query = mysql_query($q);
$count = mysql_num_rows($query);

$found = false;
if (isset($_GET['char_name']) && $_GET['char_name'] != "") {
	$charname = strtolower($_GET['char_name']);
	$counter = 0;
	$foundCharID = -1;
	$qa = "SELECT ID FROM characters WHERE `name` = '".mysql_real_escape_string($charname)."' LIMIT 1";
	$qu = mysql_query($qa) or die(mysql_error());
	if (mysql_num_rows($qu) == 1) {
		$row = mysql_fetch_array($qu);
		$que = mysql_query("SELECT @row:=@row+1 AS ranknum, characters.name, characters.ID FROM (SELECT @row:= 0) a, characters LEFT JOIN `users` ON characters.userid = users.ID WHERE ban_expire < NOW() AND ".$gm_user." ".$job." ORDER BY `".$order."` DESC, `exp` DESC") or die("206:".mysql_error());
		while ($row = mysql_fetch_array($que)) {
			if (strtolower($row['name']) == $charname) {
				break;
			}
			$counter++;
		}
		$trang = round($counter / $char_num);
		$rank = (($trang) * $char_num);
		$starting_num = $rank;
		$limit = " LIMIT " . $rank . ", " . $char_num;
		$body->set("found_char_rank", $row["ID"]);
	}
	else {
		$body->set("found_char_rank", -1);
	
	}
}

$query = mysql_query($q.$limit) or die("224:".mysql_error());
// Phrase data into array
$ranking_data = array();

while ($data = mysql_fetch_array($query)) {
	$starting_num++;
	$move = 0;
	if ($sort == "fame") {
		$move = $data['fame_opos'] - $data['fame_cpos'];
	}
	elseif ($sort == "beginner" || $sort == "bowman" || $sort == "magican" || $sort == "thief" || $sort == "warrior" || $sort == "pirate") {
		$move = $data['job_cpos'] - $data['job_opos'];
	}
	else {
		$move = $data['overall_opos'] - $data['overall_cpos'];
	}

	$jobClass = "beginner";
	$jobName = "Unknown?";
	if (isset($job_data[$data['job']])) {
		$jobClass = $job_data[$data['job']]['class'];
		$jobName = $job_data[$data['job']]['name'];
	}
	$ranking_data[] = array(
		"rank" => $starting_num,
		"id" => $data['ID'],
		"name" => $data['name'],
		"world_id" => $data['world_id'],
		"exp" => $data['exp'],
		"job_class" => $jobClass,
		"job_name" => $jobName,
		"level" => $data['level'],
		"fame" => $data['fame'],
		"move" => $move
	);
}
// Calculate paging
$pages = ceil($count / $char_num);

// Trang is Vietnamese for "page"
$page = isset($trang) ? intval($trang) : 1;

// Calculate previous and next links
$previous = $page > 1 ? $page - 1 : NULL;
$next = $page < $pages ? $page + 1 : NULL;

// Phrase data into an array
$paging_data = array(
	"pages" => $pages,
	"sort" => $sort,
	"previous" => $previous,
	"current" => $page,
	"next" => $next
);

$page_url = '<a href="'.$_SERVER['PHP_SELF'].'?page=ranking&amp;trang=%d&amp;sort='.$paging_data['sort'].'">%s</a> ';
$pages_list = "<br />";
for ($i = 1; $i <= $pages && $i <= 100; $i++) {
	if ($i != $page)
		$pages_list .= sprintf($page_url, $i, $i);
	else
		$pages_list .= '<strong>['.$i.']</strong> ';
}

$body->set("max_records", $char_num);
$body->set("num_records", $char_num);
$body->set("sort", $sort);
$body->set("paging_data", $paging_data);
$body->set("ranking_data", $ranking_data);
$body->set("pages_list", $pages_list);
$body->set("worldnames", $worldnames);
$body->set("show_gms", $phpVana_config['ranking_show_gm_character']);