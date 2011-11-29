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

$body = new Template(getFileLocation("downloads.php"));

// Find out if we have anything to display.
$num_query = mysql_fetch_array(mysql_query(
	"SELECT 
		COUNT(*) 
	FROM 
		`phpVana_downloads` 
	WHERE 
		`active` = 1 AND 
		`parent` IS NULL"
));
$num = $num_query[0];

// If there is more than zero entries then displaty the rest of the page
if ($num != 0) {	
	$download_data = array();
	
	// Query the database for active downloads
	$query = mysql_query("
		SELECT 
			* 
		FROM 
			`phpVana_downloads` 
		WHERE 
			`active` = 1 AND 
			`parent` IS NULL 
		ORDER BY 
			`position` ASC"
	);

	while ($data = mysql_fetch_array($query)) {
		$download_data[] = array(
			"title" => $data['title'],
			"description" => stripslashes(nl2br($data['description'])),
			"host" => $data['host'],
			"address" => $data['address'],
			"mirror" => "parent"
		);
		
		// Get mirrors
		$mirror_query = mysql_query(
			"SELECT 
				*
			 FROM 
				`phpVana_downloads` 
			WHERE 
				`active` = 1 AND 
				`parent` = " . $data['id']
		);
		while ($m_data = mysql_fetch_array($mirror_query)) {
			$download_data[] = array(
				"host" => $m_data['host'],
				"address" => $m_data['address'],
				"mirror" => "mirror"
			);
		}
		// This is a dummy value ment to create line breaks and what not
		$download_data[] = array("mirror" => "neither");
	}

	$body->set("download_data", $download_data);
	$flag = TRUE;
}
else {
	$flag = FALSE;
}
$body->set("data", $flag);
