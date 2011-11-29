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

$acp_body = new Template(getFileLocation("acp/server_status.php"));

if (!isset($_GET['action'])) {
	// Check to see if a login server exists
	$loginserver_check = mysql_fetch_array(mysql_query(
		"SELECT
			COUNT(*)
		FROM
			`phpVana_servers`
		WHERE
			`type` = 0
		LIMIT
			1"
	));

	if ($loginserver_check[0] == 0){
		$acp_body-> set("loginserver", false);
	}
	else {
		// Let's start to spit out the information
		$acp_body->set("loginserver", true);
		$server_data = array();

		$loginserver = mysql_fetch_array(
			mysql_query(
				"SELECT
					*
				FROM
					`phpVana_servers`
				WHERE
					`type` = 0
				LIMIT
					1"
			)
		);

		$server_data[] = array(
			"id" => $loginserver['id'],
			"type" => 0
		);

		// World servers
		$worldserver_query = mysql_query(
			"SELECT
				*
			FROM
				`phpVana_servers`
			WHERE
				`type` = 1"
		);

		$wnum = mysql_num_rows($worldserver_query);
		$w = 1;

		if ($wnum != 0) {
			while($worldserver = mysql_fetch_array($worldserver_query)) {
				$channel_servers = array();

				// Channel servers
				$channelserver_query = mysql_query(
					"SELECT
						*
					FROM
						`phpVana_servers`
					WHERE
						`type` = 2 AND
						`parent` =  " . $worldserver['id'] . "
					ORDER BY
						`port` ASC"
				);

				$cnum = mysql_num_rows($channelserver_query);
				$c = 1;
				if ($cnum != 0) {
					while($channelserver = mysql_fetch_array($channelserver_query)) {
						$channel_servers[] = array (
							"id" => $channelserver['id'],
							"type" => 2,
							"channel" => $c
						);
						$c++;
					}
				}

				$cashserver_query = mysql_query("SELECT * FROM `phpVana_servers` WHERE `type` = 3 AND `parent` = " . $worldserver['id'] . " LIMIT 1");
				
				$cashserver = null;
				
				if (mysql_num_rows($cashserver_query) == 1) {
					$row = mysql_fetch_array($cashserver_query);
					$cashserver = array(
							"id" => $row['id'],
							"type" => 3);
				}
				
				$server_data[] = array(
					"id" => $worldserver['id'],
					"type" => 1,
					"name" => $worldserver['name'],
					"exp_rate" => $worldserver['exp_rate'],
					"quest_rate" => $worldserver['quest_rate'],
					"meso_rate" => $worldserver['meso_rate'],
					"drop_rate" => $worldserver['drop_rate'],
					"channel_server" => $channel_servers,
					"cash_server" => $cashserver
				);
				unset($channel_servers);
			}
			
			
		}
		$acp_body->set("server_list", $server_data);		
	}
	$acp_body->set("action", null);
}
else {
	if (!isset($_POST['submit'])) {
		switch ($_GET['action']) {
			case "add_loginserver": {
				$acp_body->set("action", "addloginserver");
				break;
			}
			case "edit_loginserver": {
				$loginserver_data = mysql_fetch_array(mysql_query(
					"SELECT
						*
					FROM
						`phpVana_servers`
					WHERE
						`id` = " . intval($_GET['id'])
				));
				
				$ls_values = array(
					"host" => $loginserver_data['host'],
					"port" => $loginserver_data['port']
				);

				$acp_body->set("loginserver_data", $ls_values);
				$acp_body->set("action", "editloginserver");
				break;
			}
			case "add_worldserver": {
				// We will guess the port of this world server
				$last_worldserver = mysql_query("SELECT world_id, port FROM `phpVana_servers` WHERE `type` = 1 ORDER BY world_id DESC LIMIT 1");
				$last_worldserver_data = mysql_fetch_array($last_worldserver);
				
				$port = 7100;
				$id = 0;
				
				if (mysql_num_rows($last_worldserver) == 1) {
					$port = $last_worldserver_data["port"] + 100;
					$id = $last_worldserver_data["world_id"] + 1;
				}

				$acp_body->set("port", $port);
				$acp_body->set("id", $id);
				$acp_body->set("action", "addworldserver");
				break;
			}
			case "edit_worldserver": {
				$worldserver_data = mysql_fetch_array(mysql_query(
					"SELECT
						*
					FROM
						`phpVana_servers`
					WHERE
						`id` =  " . intval($_GET['id'])
				));
				
				$ws_values = array(
					"id" => $worldserver_data['id'],
					"name" => $worldserver_data['name'],
					"world_id" => $worldserver_data['world_id'],
					"host" => $worldserver_data['host'],
					"port" => $worldserver_data['port'],
					"exp_rate" => $worldserver_data['exp_rate'],
					"quest_rate" => $worldserver_data['quest_rate'],
					"meso_rate" => $worldserver_data['meso_rate'],
					"drop_rate" => $worldserver_data['drop_rate']
				);

				$acp_body->set("worldserver_data", $ws_values);
				$acp_body->set("action", "editworldserver");
				break;
			}
			case "delete_worldserver": {
				$acp_body->set("id", intval($_GET['id']));
				$acp_body->set("action", "deleteworldserver");
				break;
			}
			case "add_channelserver": {
				// We will guess the port of this world server
				$worldserver = mysql_fetch_array(mysql_query(
					"SELECT * FROM `phpVana_servers`
					WHERE
						`id` = " . intval($_GET['parent']) . "
					LIMIT 1"
				));
				
				$num_channelserver = mysql_fetch_array(mysql_query(
					"SELECT
						COUNT(*)
					FROM
						`phpVana_servers`
					WHERE
						`type` = 2 AND
						`parent` = " . intval($_GET['parent']) . "
					ORDER BY
						`port` DESC"
				));

				$acp_body->set("port", $worldserver['port'] + $num_channelserver[0] + 1);
				$acp_body->set("action", "addchannelserver");
				break;
			}
			case "edit_channelserver": {
				$channelserver_data = mysql_fetch_array(mysql_query(
					"SELECT
						*
					FROM
						`phpVana_servers`
					WHERE
						`id` = " . intval($_GET['id'])
				));
				
				$cs_data = array(
					"id" => $channelserver_data['id'],
					"host" => $channelserver_data['host'],
					"port" => $channelserver_data['port']
				);

				$acp_body->set("channelserver_data", $cs_data);
				$acp_body->set("action", "editchannelserver");
				break;
			}
			case "delete_channelserver": {
				$acp_body->set("id", intval($_GET['id']));
				$acp_body->set("action", "deletechannelserver");
				break;
			}
			case "create_cashserver": {
				// We will guess the port of this world server
				$worldserver = mysql_fetch_array(mysql_query(
					"SELECT * FROM `phpVana_servers`
					WHERE
						`id` = " . intval($_GET['parent']) . "
					LIMIT 1"
				));
				
				$num_cashserver = mysql_fetch_array(mysql_query(
					"SELECT
						COUNT(*)
					FROM
						`phpVana_servers`
					WHERE
						`type` = 3 AND
						`parent` = " . intval($_GET['parent']) . "
					ORDER BY
						`port` DESC"
				));

				$acp_body->set("port", 8000 + $worldserver['world_id']);
				$acp_body->set("action", "addcashserver");
				break;
			}
			case "edit_cashserver": {
				$cashserver_data = mysql_fetch_array(mysql_query(
					"SELECT
						*
					FROM
						`phpVana_servers`
					WHERE
						`id` = " . intval($_GET['id'])
				));

				$acp_body->set("cashserver_data", array(
					"id" => $cashserver_data['id'],
					"host" => $cashserver_data['host'],
					"port" => $cashserver_data['port']
				));
				$acp_body->set("action", "editcashserver");
				break;
			}
			case "delete_cashserver": {
				$acp_body->set("id", intval($_GET['id']));
				$acp_body->set("action", "deletecashserver");
				break;
			}
		}
	}
	else {
		switch ($_GET['action']) {
			case "add_loginserver": {
				$host = mysql_real_escape_string($_POST['host']);
				$port = intval($_POST['port']);

				$result = mysql_query (
					"INSERT INTO `phpVana_servers`
						(`type`, `host`, `port`)
					VALUES
						(0, '" . $host . "', " . $port . ")"
				);
				echo $result;
				$acp_body->set("result", $result);
				$acp_body->set("action", "addloginserver");
				break;
			}

			case "edit_loginserver":
				$host = mysql_real_escape_string($_POST['host']);
				$port = intval($_POST['port']);
				mysql_query ("
					UPDATE `phpVana_servers`
					SET
						`host` = '$host',
						`port` = $port
					WHERE
						`id` = " . intval($_GET['id'])
				);
				
				$acp_body->set("action", "editloginserver");
				break;
	
			case "add_worldserver":
				$world_id = intval($_POST['world_id']);			
				$name = mysql_real_escape_string($_POST['name']);
				$host = mysql_real_escape_string($_POST['host']);
				$port = intval($_POST['port']);
				$exp_rate = intval($_POST['exp_rate']);
				$quest_rate = intval($_POST['quest_rate']);
				$meso_rate = intval($_POST['meso_rate']);
				$drop_rate = intval($_POST['drop_rate']);
				mysql_query ("
					INSERT INTO `phpVana_servers`
						(`type`, `world_id`, `name`, `host`, `port`, `exp_rate`, `quest_rate`, `meso_rate`, `drop_rate`)
					VALUES
						(1, $world_id, '$name', '$host', $port, $exp_rate, $quest_rate, $meso_rate, $drop_rate)"
				);

				$acp_body->set("action", "addworldserver");
				break;

			case "edit_worldserver":
				$world_id = intval($_POST['world_id']);
				$name = mysql_real_escape_string($_POST['name']);
				$host = mysql_real_escape_string($_POST['host']);
				$port = intval($_POST['port']);
				$exp_rate = intval($_POST['exp_rate']);
				$quest_rate = intval($_POST['quest_rate']);
				$meso_rate = intval($_POST['meso_rate']);
				$drop_rate = intval($_POST['drop_rate']);
				mysql_query ("
					UPDATE
						`phpVana_servers`
					SET
						`world_id` = $world_id,
						`name` = '$name',
						`host` = '$host',
						`port` = $port,
						`exp_rate` = $exp_rate,
						`quest_rate` = $quest_rate,
						`meso_rate` = $meso_rate,
						`drop_rate` = $drop_rate
					WHERE
						`id` = " . intval($_GET['id'])
					);
				
				$acp_body->set("action", "editworldserver");
				break;

			case "delete_worldserver":
				// Delete world server itself
				mysql_query ("
					DELETE FROM
						`phpVana_servers`
					WHERE
						`id` = " . intval($_GET['id'])
				);
				
				// Delete channel servers
				mysql_query ("
					DELETE FROM
						`phpVana_servers`
					WHERE
						`parent` = " . intval($_GET['id'])
				);

				$acp_body->set("action", "deleteworldserver");
				break;
	
			case "add_channelserver":
				$host = mysql_real_escape_string($_POST['host']);
				$port = intval($_POST['port']);
				$parent = intval($_GET['parent']);
				mysql_query ("
					INSERT INTO `phpVana_servers`
						(`type`, `host`, `port`, `parent`)
					VALUES
						(2, '$host', $port, $parent)
				");
				
				$acp_body->set("action", "addchannelserver");
				break;

			case "edit_channelserver":
				$host = mysql_real_escape_string($_POST['host']);
				$port = intval($_POST['port']);
				mysql_query ("
					UPDATE
						`phpVana_servers`
					SET
						`host` = '$host',
						`port` = '$port'
					WHERE
						`id` = " . intval($_GET['id'])
				);
				
				$acp_body->set("action", "editchannelserver");
				break;

			case "delete_channelserver":
				// Delete channel server
				mysql_query ("
					DELETE FROM
						`phpVana_servers`
					WHERE
						`id` = " . intval($_GET['id'])
				);
				
				$acp_body->set("action", "deletechannelserver");
				break;	
				
			case "create_cashserver":
				$host = mysql_real_escape_string($_POST['host']);
				$port = intval($_POST['port']);
				$parent = intval($_GET['parent']);
				mysql_query ("INSERT INTO `phpVana_servers` (`type`, `host`, `port`, `parent`) VALUES (3, '".$host."', ".$port.", ".$parent.")");
				
				$acp_body->set("action", "addcashserver");
				break;

			case "edit_cashserver":
				$host = mysql_real_escape_string($_POST['host']);
				$port = intval($_POST['port']);
				mysql_query("UPDATE `phpVana_servers` SET `host` = '".$host."', `port` = '".$port."' WHERE `id` = " . intval($_GET['id']));
				
				$acp_body->set("action", "editcashserver");
				break;
				
			case "delete_cashserver":
				// Delete channel server
				mysql_query ("DELETE FROM `phpVana_servers` WHERE `id` = " . intval($_GET['id'])) or die(mysql_error());
				
				$acp_body->set("action", "deletecashserver");
				break;	

		}
		$acp_body->set("complete", true);
	}
}
