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

$acp_body = new Template(getFileLocation("acp/news.php"));

if (!isset($_GET['action'])) {
	// Spit out all of the news articles
	$query = mysql_query ("SELECT news.id, news.title, news.owner, news.date, news.time, user.username FROM phpVana_news news LEFT JOIN users user ON user.ID = news.owner ORDER BY news.date DESC, news.time DESC");
	if (mysql_num_rows($query) == 0) {
		$news_data = 0;
	}
	else {
		while ($data = mysql_fetch_array($query)) {
			$news_data[] = array(
				"id" => $data['id'],
				"title" => stripslashes($data['title']),
				"owner" => $data['username'],
				"date" =>  date($phpVana_config['date_format'], strtotime($data[3])),
				"time" => date($phpVana_config['time_format'], strtotime($data[4]))
			);
		}
	}
	$action = NULL;
	$complete = FALSE;	
	$acp_body->set("news_data", $news_data);
}
else{
	// The user chose to do something
	if (!isset($_POST['submit'])) {
		switch ($_GET['action']) {
		case "new":
			$action ="new";
			$complete = FALSE;
			break;

		case "edit":
			$data = mysql_fetch_array(mysql_query("SELECT * FROM `phpVana_news` WHERE `id` = ".$_GET['id']." LIMIT 1"));
			$news_data = array(
				"id" => $data['id'],
				"title" => stripslashes($data['title']),
				"content" => $data['content']
			 );
			$action = "edit";
			$complete = FALSE;
			$acp_body->set('news_data', $news_data);
			break;

		case "delete":
			$action = "delete";
			$complete = FALSE;
			$acp_body->set("id", intval($_GET['id']));
			break;
		}
	}
	else {
	// The user is doing something
		switch($_GET['action']) {
			case "new":
				// Mysql_real_escape these fileds
				$title = mysql_real_escape_string($_POST['title']);
				$content = mysql_real_escape_string($_POST['content']);
			
				// Insert news item
				mysql_query("
						INSERT INTO 
							`phpVana_news` 
							(`id`, 
							 `title`, 
							 `date`, 
							 `time`, 
							 `owner`, 
							 `content`)
						VALUES 
							(NULL, 
							 '" . $title . "', 
							 CURDATE(), 
							 CURTIME(), 
							 '" . $_SESSION['user_id'] . "', 
							 '" . $content . "')"
						);

				$action = "new";
				$complete = TRUE;
				break;
			
			case "edit":
				// Mysql_real_escape these fileds
				$id = intval($_GET['id']);
				$title = mysql_real_escape_string($_POST['title']);
				$content = mysql_real_escape_string($_POST['content']);
				
				mysql_query("UPDATE `phpVana_news` SET `title` = '" . $title . "', `content` = '" . $content . "' WHERE `id` = " . $id);

				$action = "edit";
				$complete = TRUE;
				break;
	
			case "delete":
				$id = intval($_GET['id']);
				mysql_query("DELETE FROM `phpVana_news` WHERE `id` = " . $id);
				mysql_query("DELETE FROM `phpVana_comments` WHERE `article_id` = " . $id);
				$action = "delete";
				$complete = TRUE;
				break;
		}
	}
}
$acp_body->set("action", $action);
$acp_body->set("complete", $complete);
