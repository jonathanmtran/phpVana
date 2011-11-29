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

$body = new Template(getFileLocation("news.php"));

// An article id wasn't given. Let's show news
if (!isset($_GET['id'])) {
	// Define limits for pageing
	if (!isset($_GET['trang'])) {
		$limit = " LIMIT  0," . $phpVana_config['news_limit'];
	}
	else {
		$starting_num = ((intval($_GET['trang'])) - 1) * $phpVana_config['news_limit'];
		$limit = " LIMIT " . $starting_num . "," . $phpVana_config['news_limit'];
	}
	
	// Get news articles
	$query = mysql_query("SELECT * FROM `phpVana_news` LEFT JOIN `users` ON users.id = phpVana_news.owner " . $limit);
	
	// Phrase the data into an array
	$news_data = array();
	while ($data = mysql_fetch_array($query)) {
		$comments = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `phpVana_comments` WHERE `article_id` = " . $data['id']));
		$news_data[] = array(
			"id" => $data['id'],
			"title" => $data['title'],
			"date" => date($phpVana_config['date_format'], strtotime($data['date'])),
			"time" => $data['time'],
			"owner" => $data['username'],
			"content" => nl2br(stripslashes(parseString($data['content']))),
			"num_comments" => $comments[0]
		);
	}
	
	// Calculate paging
	$count = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `phpVana_news`"));
	$pages = ceil($count[0] / $phpVana_config['news_limit']);
	
	// Trang is Vietnamese for "page"
	if (isset($_GET['trang'])) {
		$trang = intval($_GET['trang']);
	}
	else {
		$trang = 1;
	}
	
	// Calculate previous and next links
	if ($trang > 1) {
		$previous = $trang - 1;
	}
	else {
		$previous = NULL;
	}
	
	// Phrase data into an array
	$paging_data = array(
		"pages" => $pages,
		"sort" => $sort,
		"previous" => $previous,
		"current" => $trang,
		"next" => $next
	);
	
	// Set template variables
	$body->set("id", false);
	$body->set("news_data", $news_data);
	$body->set("paging_data", $paging_data);	
}
// An article id was given, let's get the news article and it's comments
else {
	if (isset($_GET['do'])) {
		// Prevent sql injection
		$article_id = intval($_GET['id']);
		
		if($_GET['do'] == "post_comment") {
			// Prevent sql injection
			$comment = mysql_real_escape_string($_POST['message']);
			
			// Build and execute query
			$post_comment = "INSERT INTO `phpVana_comments` (`article_id`, `user_id`, `date`, `time`, `comment`) VALUES (" . $article_id . ", " . $_SESSION['user_id'] . ", NOW(), NOW(), '" . $comment . "')";
			mysql_query($post_comment);
		}
		elseif ($_GET['do'] == "delete_comment") {		
			// If they are gms then they can delete comments
			if ($_SESSION['nav_level'] > 2) {
				// Prevent sql injection
				$comment_id = intval($_GET['comment_id']);
				
				// Delete comment
				mysql_query("DELETE FROM `phpVana_comments` WHERE `id` = '" . $comment_id . "'");
			}
		}
		header("Location:" . $SERVER['PHP_SELF'] . "?page=news&id=" . $article_id);
	}
	// Get news article
	$article_data = mysql_fetch_array(mysql_query("SELECT * FROM `phpVana_news` LEFT JOIN `users` ON users.id = phpVana_news.owner WHERE phpVana_news.id = " . intval($_GET['id']) ));

	// Get and phrase comments
	$comments = mysql_query("SELECT * FROM `phpVana_comments` LEFT JOIN `users` ON users.id = phpVana_comments.user_id WHERE `article_id` = ".intval($_GET['id'])." ORDER BY `date`, `time` ASC");
	$article_comments = array();
	while ($data = mysql_fetch_array($comments)) {
		$article_comments[] = array(
			"id" => $data['id'],
			"username" => $data['username'],
			"date" => date($phpVana_config['date_format'], strtotime($data['date'])),
			"time" => date($phpVana_config['time_format'], strtotime($data['time'])),
			"comment" => nl2br(stripslashes(parseString($data['comment'])))
		);
	}
	
	// Phrase data for the template
	$article = array(
		"id" => intval($_GET['id']),
		"title" => stripslashes($article_data['title']),
		"date" => date($phpVana_config['date_format'], strtotime($article_data['date'])),
		"time" => date($phpVana_config['time_format'], strtotime($article_data['time'])),
		"owner" => $article_data['username'],
		"content" => nl2br(stripslashes(parseString($article_data['content']))),		
		"comments" => $article_comments,
		"num_comments" => mysql_num_rows($comments)
	);
	
	// Set template variables
	$body->set("id", true);
	$body->set("news_data", $article);
}