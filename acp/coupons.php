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

$acp_body = new Template(getFileLocation('acp/coupons.php'));

$mode = isset($_GET['mode']) ? $_GET['mode'] : "home";

$acp_body->set("mode", $mode);

switch ($mode) {
	case "home": {
		$query = mysql_query("SELECT code.serial, code.used FROM cashshop_coupon_codes code") or die(mysql_error());
		
		$coupons = array();
		while ($row = mysql_fetch_array($query)) {
			$amount = mysql_query("SELECT COUNT(*) FROM cashshop_coupon_item_rewards WHERE serial = '".mysql_real_escape_string($row[0])."'") or die(mysql_error());
			$amountrow = mysql_fetch_array($amount);
			$coupons[] = array("serial" => $row[0], "used" => $row[1], "items_assigned" => $amountrow[0]);
		}
		
		$acp_body->set("coupons", $coupons);
		break;	
	}
	case "delete": {
		if (!isset($_GET['serial'])) die();
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$query = mysql_query("DELETE FROM cashshop_coupon_codes WHERE serial = '".mysql_real_escape_string($_GET['serial'])."'") or die(mysql_error());
			$query = mysql_query("DELETE FROM cashshop_coupon_item_rewards WHERE serial = '".mysql_real_escape_string($_GET['serial'])."'") or die(mysql_error());
			$acp_body->set("state", "deleted");
		}
		else {
			$acp_body->set("state", "unk");
		}
		break;	
	}
	case "change": {
		if (!isset($_GET['serial'])) die();
		$serial = mysql_real_escape_string($_GET['serial']);
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$query = mysql_query("SELECT * FROM cashshop_coupon_codes WHERE serial = '".$serial."'") or die(mysql_error());
			if (mysql_num_rows($query) == 1) {
				mysql_query("UPDATE cashshop_coupon_codes SET maplepoints = '".intval($_POST['maplepoints'])."', 
															nxcredit = '".intval($_POST['nxcredit'])."', 
															nxprepaid = '".intval($_POST['nxprepaid'])."', 
															mesos = '".intval($_POST['mesos'])."', 
															used = '".(isset($_POST['used']) ? 1 : 0)."'
															
															WHERE serial = '".$serial."'");
				
				mysql_query("DELETE FROM cashshop_coupon_item_rewards WHERE serial = '".$serial."'");
				
				$q = "INSERT INTO cashshop_coupon_item_rewards VALUES (";
				for ($i = 0; $i < sizeof($_POST['rewards_itemid']); $i++) {
					$q .= "'".$serial."', ";
					$q .= "'".intval($_POST['rewards_itemid'][$i])."', ";
					$q .= "'".intval($_POST['rewards_amount'][$i])."', ";
					$q .= "'".intval($_POST['rewards_daysusable'][$i])."')";
					if ($i < (sizeof($_POST['rewards_itemid'])-1)) {
						$q .= ", (";
					}
				}
				mysql_query($q) or die(mysql_error()."<br />".$q);
				
				
				$query = mysql_query("SELECT * FROM cashshop_coupon_codes WHERE serial = '".$serial."'") or die(mysql_error());
			
				$coupon = mysql_fetch_array($query);
				$acp_body->set("coupon", $coupon);
				$query = mysql_query("SELECT * FROM cashshop_coupon_item_rewards WHERE serial = '".$serial."'") or die(mysql_error());
				if (mysql_num_rows($query) >= 1) {
					$rewards = array();
					while ($row = mysql_fetch_array($query)) {
						$rewards[] = $row;	
					}
					$acp_body->set("rewards", $rewards);
				}
				$acp_body->set("saved", true);
			}
		}
		else {
			$query = mysql_query("SELECT * FROM cashshop_coupon_codes WHERE serial = '".$serial."'") or die(mysql_error());
			if (mysql_num_rows($query) == 1) {
				$coupon = mysql_fetch_array($query);
				$acp_body->set("coupon", $coupon);
				$query = mysql_query("SELECT * FROM cashshop_coupon_item_rewards WHERE serial = '".$serial."'") or die(mysql_error());
				if (mysql_num_rows($query) >= 1) {
					$rewards = array();
					while ($row = mysql_fetch_array($query)) {
						$rewards[] = $row;	
					}
					$acp_body->set("rewards", $rewards);
				}
			}
			
		}
		break;	
	}
	case "addreward": {
		if (!isset($_GET['serial'])) die();
		$serial = mysql_real_escape_string($_GET['serial']);
		$query = mysql_query("SELECT * FROM cashshop_coupon_codes WHERE serial = '".$serial."'") or die(mysql_error());
		if (mysql_num_rows($query) == 1) {
			$acp_body->set("serial", $serial);
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
					mysql_query("INSERT INTO cashshop_coupon_item_rewards VALUES ('".$serial."', 
																				  '".intval($_POST['itemid'])."', 
																				  '".intval($_POST['amount'])."', 
																				  '".intval($_POST['daysusable'])."');");
					$acp_body->set("added", 1);
			}
			else {
			}
		}		
		break;
	}
	case "add": {
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$serial = mysql_real_escape_string($_POST['serial']);
			$query = mysql_query("SELECT * FROM cashshop_coupon_codes WHERE serial = '".$serial."'") or die(mysql_error());
			if (mysql_num_rows($query) != 0) {
				$acp_body->set("error", 1);
			}
			else {
				$query = mysql_query("INSERT INTO cashshop_coupon_codes VALUES ('".$serial."', 
																				'".intval($_POST['maplepoints'])."', 
																				'".intval($_POST['nxcredit'])."', 
																				'".intval($_POST['nxprepaid'])."', 
																				'".intval($_POST['mesos'])."', 
																				'".(isset($_POST['used']) ? '1' : '0')."');") or die(mysql_error());
				if ($query) {
					$acp_body->set("serial", $serial);
					$acp_body->set("added", 1);
				}
			}
		}
		break;
	}
}