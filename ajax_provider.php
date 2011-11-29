<?php 
require("config.php");
require("includes/functions.php");

// This is where you customise the online/offline messages
$online = '<span class="online">online</span>';
$offline = '<span class="offline">offline</span>';

$require_mysql = array(
	"server_status",
	"server_status_online",
	"whos_online"
);

$require_template = array();

$require_session = array();

if (in_array($_GET['component'], $require_mysql)) {
	file_exists("config.php") ? require("config.php") : die("Config.php not found!");
	
	$link = @mysql_connect($db_host, $db_user, $db_pass);
	if (!$link) {
		die("Connection to database server failed.");
	}
	
	$db = @mysql_select_db($db_name);
	if (!$db) {
		die("Connection to database failed.");
	}	
}

if (in_array($_GET['component'], $require_template)) {
	require("includes/template.class.php");
}

if (in_array($_GET['component'], $require_session)) {
	session_start();
}

switch($_GET['component']) {
	case "server_status": {
		$worldid = intval($_GET['wid']);
		$channelid = intval($_GET['cid']);
		$worlddata = mysql_fetch_array(mysql_query(
			"SELECT
				*
			FROM 
				`phpVana_servers` 
			WHERE 
				world_id = " . $worldid
			)) or die(mysql_error());
		
		$online_characters_query = mysql_query(
			"SELECT
				characters.name
			FROM 
				`users` 
			INNER JOIN
				characters
			ON 
				characters.userid = users.id
			WHERE 
				users.online = " . (20000 + ($worldid * 100) + ($channelid - 1)) . " 
			AND
				characters.online = 1"
			) or die(mysql_error());
		while ($online_data = mysql_fetch_array($online_characters_query)) {
			echo "<li>" . $online_data['name'] . "</li>";
		}
		echo "</ul>";
		break;
	}
	case "server_status_online": {
		$id = intval($_GET['id']);
		$q = mysql_query("SELECT host, port FROM `phpVana_servers` WHERE id = " . $id );
		if (mysql_num_rows($q) == 0) echo $offline;
		else {
			$data = mysql_fetch_array($q);
			$status = check_server($data[0], $data[1]);
			
			echo $status ? $online : $offline;
		}
		break;
	}
	case "whos_online": {
		$users_query = mysql_query("SELECT chara.name AS name, user.online AS online, chara.world_id AS world_id FROM `characters` chara, `users` user WHERE chara.online <> 0 AND user.id = chara.userid") or die (mysql_error());
		
		$num_users_online = mysql_num_rows($users_query);
		echo "<p>There ".($num_users_online == 1 ? "is 1 player" : "are " . $num_users_online . " players")." online.</p>\n\n";
		if ($num_users_online != 0) {
			echo "<p>Players online:<br />";
			while($data = mysql_fetch_array($users_query)) {
				$channelId = ($data['online'] - 20000 - ($data['world_id'] * 100));
				if ($channelId < 0) continue;
				echo $data['name']." (".getWorldName($data['world_id']).", ".($channelId == 50 ? "Cashshop" : "Channel ".($channelId + 1)).")<br />\n\n";
			}
			echo "</p>";
		}
		break;
	}
	default: {
		echo "arh";
		break;
	}
}