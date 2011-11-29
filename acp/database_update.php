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

// Include the template for this page
$acp_body = new Template(getFileLocation("acp/database_update.php"));

// Get data from phpTopsite_info table
$info_query = mysql_query("SELECT *	FROM `phpVana_info`");

while($data = mysql_fetch_array($info_query)) {
	$executed_sql_files[] = $data['sql'];
}

if(!isset($_GET['file'])) {	
	// Read the sql folder for possible database updates
	$dir = "sql/";
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) != false) {
				if (substr($file, -3, 3) == "sql" && is_numeric(substr($file, 0, 3))) {
					if (filetype($dir . $file) == "file") {
						$number = substr($file, 0, 3);
						$sql_files[] = array (
							"file" => $file,
							"executed" => (in_array($number, $executed_sql_files) ? TRUE : FALSE)
						);
					}
				}
			}
		}
		closedir($dh);
	}
	sort($sql_files);
	$acp_body->set("database_info", $sql_files);
}
else {
	if (!in_array(substr(stripslashes($_GET['file']), 0, 3), $executed_sql_files)) {
		// SQL file import taken from
		// http://www.t4vn.net/example/showcode/SQL-script-import-function.html
		$lines = file("sql/" . stripslashes($_GET['file']));
		$scriptfile = false;
		
		foreach($lines as $line) {
			$line = trim($line);
			
			if(strstr($line, "-- Message:")) {
				$message = str_replace("-- Message:", NULL, $line);
			}
			elseif(!strstr($line, "--")) {
				$scriptfile .= " " . $line;
			}
		}
		
		$queries = explode(";", $scriptfile);
		
		foreach($queries as $query) {
			if($query == "") {
				continue;
			}
			mysql_query($query . ";");
		}
		$execute_result = true;
	}
	else {
		$execute_result = false;
	}

	$acp_body->set("execute_result", $execute_result);
	$acp_body->set("sql_file", stripslashes($_GET['file']));
	$acp_body->set("sql_message", empty($message) ? FALSE : str_replace("\\n", "<br />", $message));
}
