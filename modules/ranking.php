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
$module = new Template(getFileLocation("modules/ranking.php"));

$limit = $phpVana_config['ranking_module_limit'];

// Are gm users included?
$gm_user = $phpVana_config['ranking_show_gm_user'] == 1 ? " `gm` <= 3 " : " `gm` = 0 ";

// Are gm characters included?
$gm_character = $phpVana_config['ranking_show_gm_character'] == 1 ? NULL : " AND `job` != '900' AND `job`!= '910'";

$query = mysql_query("SELECT * FROM `characters` LEFT JOIN `users` ON characters.userid = users.ID WHERE `ban_expire` < NOW() AND " . $gm_user . $gm_character . " ORDER BY `level` DESC, `exp` DESC LIMIT " . $limit);

$ranking_data = array();
$i = 1;
while ($data = mysql_fetch_array($query)) {
	$ranking_data[] = array(
		"rank" => $i,
		"character_name" => $data['name']
	);
	$i++;
}

$module->set("ranking_data", $ranking_data);