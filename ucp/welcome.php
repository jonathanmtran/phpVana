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

$ucp_body = new Template(getFileLocation('ucp/welcome.php'));

// Get last login
$last_login_query = mysql_fetch_array(mysql_query("
	SELECT 
		`last_login` 
	FROM 
		`users` 
	WHERE 
		`id` = " . $_SESSION['user_id']));

$last_login = array(
	"date" => date($phpVana_config['date_format'], strtotime($last_login_query['last_login'])),
	"time" => date($phpVana_config['time_format'], strtotime($last_login_query['last_login']))
);

// Set template variables
$ucp_body->set('username', $_SESSION['username']);
$ucp_body->set('last_login', $last_login);
