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

$body = new Template(getFileLocation("server_status.php"));

// Get login server
$loginserver_query = mysql_query(
	"SELECT 
		* 
	FROM 
		`phpVana_servers` 
	WHERE 
		`type` = 0 LIMIT 1"
);
$l_num = mysql_num_rows($loginserver_query);

if ($l_num == 1) {	
	$loginserver = mysql_fetch_array($loginserver_query);
	$server_data[] = array(
		"name" => "Login Server",
		"type" => 0,
		"internal_id" => $loginserver['id'],
		"result" => (check_server($loginserver['host'], $loginserver['port']))
	);

	// Now get the world servers
	$worldserver_query = mysql_query(
		"SELECT 
			* 
		FROM 
			`phpVana_servers` 
		WHERE 
			`type` = 1
		ORDER BY 
			`port` ASC"
	);
	$w_num = mysql_num_rows($worldserver_query);
	$c_num = 0;

	if ($w_num != 0) {
		while($worldserver = mysql_fetch_array($worldserver_query)) {
			// Total characters online
			$idk = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `users` WHERE online >= " . (20000 + ($worldserver['world_id'] * 100)) . " AND online < " . (20000 + ($worldserver['world_id']+1 * 100))));
			$total = $idk[0];
			// Get channel servers
			$channelserver_query = mysql_query("SELECT * FROM `phpVana_servers` WHERE `type` = 2 AND `parent` = " . $worldserver['id'] . " ORDER BY `port` ASC");
			$c_num = mysql_num_rows($channelserver_query);
			$c = 1;

			if ($c_num != 0) {
				while($channelserver = mysql_fetch_array($channelserver_query)) {
					$online_characters = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `users` WHERE online = " . (20000 + ($worldserver['world_id'] * 100) + ($c - 1))));
					$channel_servers[] = array(
						"name" => "Channel Server " . $c,
						"id" => $c,
						"internal_id" => $channelserver['id'],
						"charsonline" => $online_characters[0]
					);
					$c++;
				}
			}
			else {
				$channel_servers = NULL;
			}
			
			$cashserver_query = mysql_query("SELECT * FROM `phpVana_servers` WHERE `type` = 3 AND `parent` = " . $worldserver['id'] . " LIMIT 1");
			if (mysql_num_rows($cashserver_query) == 0) {
				$has_cash_server = false;
				$cash_server = NULL;
				$online_characters = 0;
				$cash_server_iid = 0;
			}
			else {
				$idk = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `users` WHERE online = " . (20000 + ($worldserver['world_id'] * 100) + 50)));
				$online_characters = $idk[0];
				$has_cash_server = true;
				$row = mysql_fetch_array($cashserver_query);
				$cash_server_iid = $row['id'];
			}

			// Create array for the world
			$server_data[] = array(
				"name" => $worldserver['name'],
				"type" => 1,
				"id" => $worldserver['world_id'],
				"internal_id" => $worldserver['id'],
				"exp_rate" => $worldserver['exp_rate'],
				"quest_rate" => $worldserver['quest_rate'],
				"meso_rate" => $worldserver['meso_rate'],
				"drop_rate" => $worldserver['drop_rate'],
				"total_characters" => $total,
				"channel_servers" => $channel_servers,
				"has_cash_server" => $has_cash_server,
				"cash_server_internal_id" => $cash_server_iid,
				"cash_server_chars" => $online_characters
			);
			unset($channel_servers);
		}
	}
	$body->set("w_num", $w_num);
	$body->set("c_num", $c_num);
	$body->set("server_data", $server_data);
}
$body->set("l_num", $l_num);
