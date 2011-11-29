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

$acp_body = new Template(getFileLocation("acp/database_info.php"));

// Get information from vana_info
$vana_info = mysql_fetch_array(mysql_query(
	"SELECT
		*
	FROM
		`vana_info`"
));

// Get information from phpVana_info
$phpVana_sqls = mysql_query(
	"SELECT
		*
	FROM
		`phpVana_info`"
);
while ($info = mysql_fetch_array($phpVana_sqls)) {
	$phpVana_info[] = $info['sql'];
}

$acp_body->set("vana_info", $vana_info['version']);
$acp_body->set("phpVana_info", $phpVana_info);