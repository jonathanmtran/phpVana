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

Revised for phpVana by Jonathan
Original script by Darkmagic
Original code source: http://forum.ragezone.com/f427/release-darkmagics-ranking-system-v-95c-hair-fixes-438086/

Additional credits go to (in no specific order): Bui, Bri, Emmy, zOmgnO1, and many others.
*/
error_reporting(0);
$font = "fonts/arial.ttf";
$font_size = "9.25";
if (isset($_GET['id'])) {
	// Include the configuration file, die if doesn't exist
	if (file_exists("config.php"))
		require("config.php");
	else
		die("Config.php not found!");
	
	// Connect to the database
	$link = @mysql_connect($db_host, $db_user, $db_pass);
	if (!$link) {
		$error = "Cannot connect to MySQL server.<br />";
		$error .= mysql_errno() . ": " . mysql_error();
		die($error);
	}
	
	// Select the database
	$db = @mysql_select_db($db_name);
	if (!$db) {
		$error = "Failed to select database.<br />";
		$error .= mysql_errno() . ": " . mysql_error();
		die($error);
	}
	
	$character_id = intval($_GET['id']);
	
	// If there are no rows of data, let's just quietly die and save resources
	$getChar = mysql_query("SELECT * FROM `characters` WHERE `ID` = " . $character_id);
	if (mysql_num_rows($getChar) == 0)
		die();

	// Create blank template
	header('Content-type: image/png');
	$im = imagecreatetruecolor(120, 125);
	imagesavealpha($im, true);
	$trans = imagecolorallocatealpha($im, 0, 0, 0, 127);
	imagefill($im, 0, 0, $trans);
	
	// Get character attributes
	$character_data = mysql_fetch_array($getChar);

	// Get character equipment
	$character_equipment = mysql_query("SELECT * FROM `items` WHERE `charid` = " . $character_id . " AND `slot` < 0 ORDER BY `slot`  DESC");

	// Set everything null for the hash
	$skin = $face = $hair = $hat = $mask = $eyes = $ears = $top = $pants = $overall = $shoe = $glove = $cape = $shield = $wep = $nxwep = NULL;

	// Coordinates for center
	$mainx = 54;
	$mainy = 34;
	
	// Some other variables
	$characterwz = "images/characterwz";
	$char_img = "images/characters/" . $character_id . ".png";
	$skin = $character_data['skin'] + 2000;
	$face = $character_data['eyes'];
	$hair = $character_data['hair'];
	$necky = $mainy + 31;
	$hairshade = $character_data['skin'];
	$gender = $character_data['gender'];

	// Determine which items are visible
	// Credit goes to zOmgnO1 for improved code
	while($row2 = mysql_fetch_array($character_equipment)) {
		switch ($row2['slot']) {
			case -1:	// Hat
			case -101:	// NX Hat
				if ($row2['itemid'] != 1002186) // Invisible Hat
					$hat = $row2['itemid'];
				else
					$hat = NULL;
				break;
			case -2:	// Face Accessory
			case -102:	// NX Face Accessory
				$mask = $row2['itemid'];
				break;
			case -3:	// Eye Accessory
			case -103:	// NX Eye Accessory
				$eyes = $row2['itemid'];
				break;
			case -4:	// Earrings
			case -104:	// NX Earrings
				$ears = $row2['itemid'];
				break;
			case -5:	// Top | Overall
			case -105:	// NX Top | Overall
				if (substr($row2['itemid'], 0, 3) == "105") {
					$overall = $row2['itemid'];
					$top = NULL;
				}
				else {
					$top = $row2['itemid'];
					$overall = NULL;
				}
				break;
			case -6:	// Bottom
			case -106:	// NX Bottom
				if (!isset($overall))
					$pants = $row2['itemid'];
				break;
			case -7:	// Shoes
			case -107:	// NX Shoes
				$shoe = $row2['itemid'];
				break;
			case -8:	// Gloves
			case -108:	// NX Gloves
				$glove = $row2['itemid'];
				break;
			case -9:	// Cape
			case -109:	// NX Cape
				$cape = $row2['itemid'];
				break;
			case -10:	// Shield
			case -110:	// NX Shield
				$shield = $row2['itemid'];
				break;
			case -11:	// Weapon
				$wep = $row2['itemid'];
				break;
			case -111:	// NX Weapon
				$nxwep = $row2['itemid'];
				break;
		}
	}
	
	// Create a hash to check against to save time and resources
	$hash = sha1($skin.$face.$hair.$hat.$mask.$eyes.$ears.$top.$pants.$overall.$shoe.$glove.$cape.$shield.$wep.$nxwep);
	
	// Get hash
	$hash_query = mysql_query("SELECT * FROM `character_variables` WHERE `charid` = " . $character_id . " AND `key` = 'image_hash'");
	
	// If the hash matches, then we will just grab the image
	$hash_data = mysql_fetch_array($hash_query);
	if ($hash != $hash_data['value'] || !file_exists($char_img)) {
		// This section determines which stand to use based on the weapon you have
		// Credit goes to zOmgnO1 for improved code
		if ($wep) {
			$wepType = (int) substr($wep, 0, 3);
			switch ($wepType) {
				case 130:	// 1-Handed Sword
				case 131:	// 1-Handed Axe
				case 132:	// 1-Handed BW
				case 133:	// Dagger
				case 137:	// Wand
				case 138:	// Staff
				case 139:	// ?? Unknown
				case 145:	// Bow
				case 147:	// Claw
				case 148:	// Knucle
				case 149:	// Gun
					$stand = 1;
					break;
				case 140:	// 2-Handed Sword
				case 141:	// 2-Handed Axe
				case 142:	// 2-Handed BW
				case 143:	// Spear
				case 146:	// Crossbow
					$stand = 2;
					break;
				case 144:	// Pole Arm
					$location = "Weapon/0" . $wep . ".img/stand1.0.weapon.png";
					if (file_exists($location)) // Snowboards are stand = 1
						$stand = 1;
					else
						$stand = 2;
					break;
				default:
					$stand = 1;
					break;
			}
		}
		else {
			$stand = 1;
		}
	
		// I have no idea what this does
		// Credit goes to zOmgnO1 for improved code
		if (isset($nxwep)) {
			$wepnum = 0;
			for ($i = 29; $i <= 49; $i++) {
				$location = $characterwz."/Weapon/0" . $nxwep . ".img/" . $i . ".stand" . $stand . ".0.weapon.png";
				if (file_exists($location)) {
					$wepnum = $i;
					break;
				}
			}
		}
		else {
			$nxwep = $wep;
		}
		
		// Coordinates for the face
		if (isset($face)) {
			$facearray = get_data($face);
			if (isset($facearray['blink_0_face_origin_X']) && isset($facearray['blink_0_face_brow_X']))
				$facex = -$facearray['blink_0_face_origin_X'] - $facearray['blink_0_face_map_brow_X'];
			else
				$facex = 0;
			if (isset($facearray['blink_0_face_origin_Y']) && isset($facearray['blink_0_face_brow_Y']))
				$facey = -$facearray['blink_0_face_origin_Y'] - $facearray['blink_0_face_map_brow_Y'];
			else
				$facey = 0;
		}
		
		// Coordinates for the hair
		if (isset($hair)) {
			$hairarray = get_data($hair);
			$backhairx = -$hairarray['default_hairBelowBody_origin_X'] - $hairarray['default_hairBelowBody_map_brow_X'];
			$backhairy = -$hairarray['default_hairBelowBody_origin_Y'] - $hairarray['default_hairBelowBody_map_brow_Y'];
			$shadehairx = -$hairarray['default_hairShade_0_origin_X'] - $hairarray['default_hairShade_0_map_brow_X'];
			$shadehairy = -$hairarray['default_hairShade_0_origin_Y'] - $hairarray['default_hairShade_0_map_brow_Y'];
			$hairx = -$hairarray['default_hair_origin_X'] - $hairarray['default_hair_map_brow_X'];
			$hairy = -$hairarray['default_hair_origin_Y'] - $hairarray['default_hair_map_brow_Y'];
			$overhairx = -$hairarray['default_hairOverHead_origin_X'] - $hairarray['default_hairOverHead_map_brow_X'];
			$overhairy = -$hairarray['default_hairOverHead_origin_Y'] - $hairarray['default_hairOverHead_map_brow_Y'];
		}
	
		// Eyes
		if (isset($eyes)) {
			$eyesarray = get_data($eyes);
			$eyesx = -$eyesarray['default_default_origin_X'] - $eyesarray['default_default_map_brow_X'];
			$eyesy = -$eyesarray['default_default_origin_Y'] - $eyesarray['default_default_map_brow_Y'];
			$eyesz = $eyesarray['default_default_z'];
		}
		
		// Mask
		if (isset($mask)) {
			$maskarray = get_data($mask);
			$maskz = $maskarray['default_default_z'];
			$maskx = -$maskarray['default_default_origin_X'] - $maskarray['default_default_map_brow_X'];
			$masky = -$maskarray['default_default_origin_Y'] - $maskarray['default_default_map_brow_Y'];
		}
		
		// Ears
		if (isset($ears)) {
			$earsarray = get_data($ears);
			$earsx = -$earsarray['default_default_origin_X'] - $earsarray['default_default_map_brow_X'];
			$earsy = -$earsarray['default_default_origin_Y'] - $earsarray['default_default_map_brow_Y'];
		}
		
		// Hat
		if (isset($hat)) {
			$hatarray = get_data($hat);
			$vslot = $hatarray['info_vslot'];
			$hatx = -$hatarray['default_default_origin_X'] - $hatarray['default_default_map_brow_X'];
			$haty = -$hatarray['default_default_origin_Y'] - $hatarray['default_default_map_brow_Y'];
			$zhatx = -$hatarray['default_defaultAc_origin_X'] - $hatarray['default_defaultAc_map_brow_X'];
			$zhaty = -$hatarray['default_defaultAc_origin_Y'] - $hatarray['default_defaultAc_map_brow_Y'];
		}
		
		// Cape
		if (isset($cape)) {
			$capearray = get_data($cape);
			if($stand == 2) {
				$cape2x = -$capearray['stand2_0_cape_origin_X'] - $capearray['stand2_0_cape_map_navel_X'];
				$cape2y = -$capearray['stand2_0_cape_origin_Y'] - $capearray['stand2_0_cape_map_navel_Y'];
			}
			$capex = -$capearray['stand1_0_cape_origin_X'] - $capearray['stand1_0_cape_map_navel_X'];
			$capey = -$capearray['stand1_0_cape_origin_Y'] - $capearray['stand1_0_cape_map_navel_Y'];
			$capez = $capearray['stand1_0_cape_z'];
			$zcapex = -$capearray['stand'.$stand.'_0_capeArm_origin_X'] - $capearray['stand'.$stand.'_0_capeArm_map_navel_X'];
			$zcapey = -$capearray['stand'.$stand.'_0_capeArm_origin_Y'] - $capearray['stand'.$stand.'_0_capeArm_map_navel_Y'];
		}
		
		// Shield
		if (isset($shield)) {
			$shieldarray = get_data($shield);
			$shieldx=-$shieldarray['stand1_0_shield_origin_X']-$shieldarray['stand1_0_shield_map_navel_X'];
			$shieldy=-$shieldarray['stand1_0_shield_origin_Y']-$shieldarray['stand1_0_shield_map_navel_Y'];	
		}
		
		// Shoes
		if (isset($shoe)) {
			$shoesarray = get_data($shoe);
			$shoesx = -$shoesarray['stand1_0_shoes_origin_X'] - $shoesarray['stand1_0_shoes_map_navel_X'];
			$shoesy = -$shoesarray['stand1_0_shoes_origin_Y'] - $shoesarray['stand1_0_shoes_map_navel_Y'];
			$shoesz = $shoesarray['stand1_0_shoes_z'];
		}
		
		// Glove
		if (isset($glove)) {
			$glovearray = get_data($glove);
			$lglove1x = -$glovearray['stand1_0_lGlove_origin_X'] - $glovearray['stand1_0_lGlove_map_navel_X'];
			$lglove1y = -$glovearray['stand1_0_lGlove_origin_Y'] - $glovearray['stand1_0_lGlove_map_navel_Y'];
			$rglove1x = -$glovearray['stand1_0_rGlove_origin_X'] - $glovearray['stand1_0_rGlove_map_navel_X'];
			$rglove1y = -$glovearray['stand1_0_rGlove_origin_Y'] - $glovearray['stand1_0_rGlove_map_navel_Y'];
			$lglove2x = -$glovearray['stand2_0_lGlove_origin_X'] - $glovearray['stand2_0_lGlove_map_navel_X'];
			$lglove2y = -$glovearray['stand2_0_lGlove_origin_Y'] - $glovearray['stand2_0_lGlove_map_navel_Y'];
			$rglove2x = -$glovearray['stand2_0_rGlove_origin_X'] - $glovearray['stand2_0_rGlove_map_navel_X'];
			$rglove2y = -$glovearray['stand2_0_rGlove_origin_Y'] - $glovearray['stand2_0_rGlove_map_navel_Y'];
		}
		
		// Pants
		if (isset($pants)) {
			$pantsarray = get_data($pants);
			if ($stand == 2) {
				$pants2x = -$pantsarray['stand2_0_pants_origin_X'] - $pantsarray['stand2_0_pants_map_navel_X'];
				$pants2y = -$pantsarray['stand2_0_pants_origin_Y'] - $pantsarray['stand2_0_pants_map_navel_Y'];
			}
			$pantsx = -$pantsarray['stand1_0_pants_origin_X'] - $pantsarray['stand1_0_pants_map_navel_X'];
			$pantsy = -$pantsarray['stand1_0_pants_origin_Y'] - $pantsarray['stand1_0_pants_map_navel_Y'];	
			$pantsz = $pantsarray['stand1_0_pants_z'];
		}
		
		// Top
		if (isset($top)) {
			$mailarray = get_data($top);
			if ($stand == 2) {
				$mail2x = -$mailarray['stand2_0_mail_origin_X'] - $mailarray['stand2_0_mail_map_navel_X'];
				$mail2y = -$mailarray['stand2_0_mail_origin_Y'] - $mailarray['stand2_0_mail_map_navel_Y'];
				$maila2x = -$mailarray['stand2_0_mailArm_origin_X'] - $mailarray['stand2_0_mailArm_map_navel_X'];
				$maila2y = -$mailarray['stand2_0_mailArm_origin_Y'] - $mailarray['stand2_0_mailArm_map_navel_Y'];
			}
			$mailx = -$mailarray['stand1_0_mail_origin_X'] - $mailarray['stand1_0_mail_map_navel_X'];
			$maily = -$mailarray['stand1_0_mail_origin_Y'] - $mailarray['stand1_0_mail_map_navel_Y'];
			$mailax = -$mailarray['stand1_0_mailArm_origin_X'] - $mailarray['stand1_0_mailArm_map_navel_X'];
			$mailay = -$mailarray['stand1_0_mailArm_origin_Y'] - $mailarray['stand1_0_mailArm_map_navel_Y'];
		}
		
		// Overall
		if (isset($overall)) {
			$mailarray = get_data($overall);
			if ($stand == 2) {
				$mail2x = -$mailarray['stand2_0_mail_origin_X'] - $mailarray['stand2_0_mail_map_navel_X'];
				$mail2y = -$mailarray['stand2_0_mail_origin_Y'] - $mailarray['stand2_0_mail_map_navel_Y'];
				$maila2x = -$mailarray['stand2_0_mailArm_origin_X'] - $mailarray['stand2_0_mailArm_map_navel_X'];
				$maila2y = -$mailarray['stand2_0_mailArm_origin_Y'] - $mailarray['stand2_0_mailArm_map_navel_Y'];
			}
			$mailx = -$mailarray['stand1_0_mail_origin_X'] - $mailarray['stand1_0_mail_map_navel_X'];
			$maily = -$mailarray['stand1_0_mail_origin_Y'] - $mailarray['stand1_0_mail_map_navel_Y'];
			$mailz = $mailarray['stand1_0_mail_z'];
			$mailax = -$mailarray['stand1_0_mailArm_origin_X'] - $mailarray['stand1_0_mailArm_map_navel_X'];
			$mailay = -$mailarray['stand1_0_mailArm_origin_Y'] - $mailarray['stand1_0_mailArm_map_navel_Y'];
		}
	
		// Weapon
		if (isset($nxwep)) {
			$weaponarray = get_data($nxwep);
			$wepx = $mainx + 12;
			$wepy = $necky + 6;
			$key = isset($wepnum) ? ($wepnum . '_stand' . $stand) : ('stand' . $stand);
			if($weaponarray[$key.'_0_weapon_map_hand_X'] != NULL)
				$position='hand';
			else {
				$position='navel';
				$wepx = $mainx;
				$wepy = $necky;
			}			
			if ($key != 0) {
				$weaponz = $weaponarray[$wepnum.'_stand'.$stand.'_0_weapon_z'];
				$weaponx = -$weaponarray[$wepnum.'_stand'.$stand.'_0_weapon_origin_X'] - $weaponarray[$wepnum.'_stand'.$stand.'_0_weapon_map_'.$position.'_X'];
				$weapony = -$weaponarray[$wepnum.'_stand'.$stand.'_0_weapon_origin_Y'] - $weaponarray[$wepnum.'_stand'.$stand.'_0_weapon_map_'.$position.'_Y'];
			}
			else {
				$weaponz = $weaponarray['stand'.$stand.'_0_weapon_z'];
				$weaponx = -$weaponarray['stand'.$stand.'_0_weapon_origin_X'] - $weaponarray['stand'.$stand.'_0_weapon_map_'.$position.'_X'];
				$weapony = -$weaponarray['stand'.$stand.'_0_weapon_origin_Y'] - $weaponarray['stand'.$stand.'_0_weapon_map_'.$position.'_Y'];
			}
			if($stand == 2 && $position == 'hand') {
				$wepx -= 7;
				$wepy -= 7;
			}
		}
		
		// Shoe stuff
		$positions = array(
			"shoesTop",
			"mailChestOverHighest",			
			"pantsOverMailChest",
			"mailChest",
			"pantsOverShoesBelowMailChest",
			"shoesOverPants",
			"mailChestOverPants",
			"pants",
			"pantsBelowShoes",
			"shoes"
		);
		$other = (isset($pants)) ? $pantsz : $mailz;
		$shoe_pos = array_search($shoesz, $positions);
		$other_pos = array_search($other, $positions);
		
		// Create weaponBelowBody
		if($weaponz == 'weaponBelowBody') {
			if($wepnum)
				$wep_location = $characterwz."/Weapon/0".$nxwep.".img/".$wepnum.".stand".$stand.".0.weapon.png";
			else
				$wep_location = $characterwz."/Weapon/0".$nxwep.".img/stand".$stand.".0.weapon.png";
			add_image($wep_location, $wepx + $weaponx, $wepy + $weapony);
		}
		
		// Create top cap
		if (isset($cap)) {
			$cap_location = $characterwz."/Cap/0".$hat.".img/default.defaultAc.png";
			add_image($cap_location, $mainx + $zhatx, $mainy + $zhaty);
		}
		
		// Create back hair and cape
		if ($capez == 'capeBelowBody' && (substr_count($vslot, 'H1H2H3H4H5H6') != 1)) {
			$bhair_location = $characterwz."/Hair/000".$hair.".img/default.hairBelowBody.png";
			add_image($bhair_location, $mainx + $backhairx, $mainy + $backhairy);
		}

		if (file_exists($characterwz."/Cape/0".$cape.".img/stand2.0.cape.png") && $stand == 2) {
			add_image($characterwz."/Cape/0".$cape.".img/stand2.0.cape.png", $mainx + $cape2x, $necky + $cape2y);
		}
		elseif (file_exists($characterwz."/Cape/0".$cape.".img/stand1.0.cape.png")) {
			add_image($characterwz."/Cape/0".$cape.".img/stand1.0.cape.png", $mainx + $capex, $necky + $capey);
		}

		if ($capez != 'capeBelowBody' && (substr_count($vslot, 'H1H2H3H4H5H6') != 1)) {
			$bhair_location = $characterwz."/Hair/000".$hair.".img/default.hairBelowBody.png";
			add_image($bhair_location, $mainx + $backhairx, $mainy + $backhairy);
		}
		
		// Create shield
		if (isset($shield)) {
			$shield_location = $characterwz."/Shield/0".$shield.".img/stand1.0.shield.png";
			add_image($shield_location, $mainx + $shieldx, $necky + $shieldy);
		}
		
		// Create body 
		if($stand == "")
			$stand = 1;
		add_image($characterwz."/0000".$skin.".img/stand".$stand.".0.body.png", ($mainx + $stand) - 9, $mainy + 21);

		// Create shoes: under
		if (isset($shoe)) {
			$shoe_location = $characterwz."/Shoes/0" . $shoe . ".img/stand1.0.shoes.png";
			if ($shoe_pos > $other_pos) {
				add_image($shoe_location, $mainx + $shoesx, $necky + $shoesy);
			}
		}
		
		// Create stand1 left glove
		if (isset($glove) && $stand == 1){
			$glove_location = $characterwz."/Glove/0".$glove.".img/stand1.0.lGlove.png";
			add_image($glove_location, $mainx + $lglove1x, $necky + $lglove1y);
		}
		
		// Clothe the naked
		if (!isset($pants) || !isset($overall)) {
			if ($gender == 0)
				add_image($characterwz."/Pants/01060026.img/stand1.0.pants.png", $mainx - 3, $necky + 1);
			elseif ($gender == 1) 
				add_image($characterwz."/Pants/01061039.img/stand1.0.pants.png", $mainx - 3, $necky + 1);
		}
		
		// Create pants
		if (isset($pants)) {
			$pants_location = $characterwz."/Pants/0".$pants.".img/stand";
			if(file_exists($pants_location."2.0.pants.png") && $stand == 2)
				add_image($pants_location."2.0.pants.png", $mainx + $pants2x, $necky + $pants2y);
			elseif(file_exists($pants_location."1.0.pants.png"))
				add_image($pants_location."1.0.pants.png", $mainx + $pantsx, $necky + $pantsy);
		}	
		
		// Create top
		if (isset($top)) {
			$top_location = $characterwz."/Coat/0".$top.".img/stand";
			if(file_exists($top_location."2.0.mail.png") && $stand == 2)
				add_image($top_location."2.0.mail.png", $mainx + $mail2x, $necky + $mail2y);
			elseif(file_exists($top_location."1.0.mail.png"))
				add_image($top_location."1.0.mail.png", $mainx + $mailx, $necky + $maily);
		}
		
		// Create overall
		if (isset($overall)) {
			$overall_location = $characterwz."/Longcoat/0".$overall.".img/stand";
			if(file_exists($overall_location."2.0.mail.png") && $stand == 2)
				add_image($overall_location."2.0.mail.png", $mainx + $mail2x, $necky + $mail2y);
			elseif(file_exists($overall_location."1.0.mail.png"))
				add_image($overall_location."1.0.mail.png", $mainx + $mailx, $necky + $maily);
		}

		// Create shoes
		if (isset($shoe)) {
			if ($shoe_pos < $other_pos) {
				$shoe_location = $characterwz."/Shoes/0".$shoe.".img/stand1.0.shoes.png";
				add_image($shoe_location, $mainx + $shoesx, $necky + $shoesy);
			}
		}
		
		// Clothe the naked
		if (!($top || $overall)) {
			if ($gender == 0)
				add_image($characterwz."/Coat/01040036.img/stand1.0.mail.png", $mainx - 3, $necky - 9);
			elseif ($gender == 1)
				add_image($characterwz."/Coat/01041046.img/stand1.0.mail.png", $mainx - 3, $necky - 9);
		}
		
		// Create armBelowHeadOverMailChest
		if($weaponz == 'armBelowHeadOverMailChest') {
			if($wepnum)
				$wep_location = $characterwz."/Weapon/0".$nxwep.".img/".$wepnum.".stand".$stand.".0.weapon.png";
			else
				$wep_location = $characterwz."/Weapon/0".$nxwep.".img/stand".$stand.".0.weapon.png";
			add_image($wep_location, $wepx + $weaponx, $wepy + $weapony);
		}
		
		// Create capeArm
		if (file_exists($characterwz."/Cape/0".$cape.".img/stand" . $stand . ".0.capeArm.png"))
			add_image($characterwz."/Cape/0".$cape.".img/stand" . $stand . ".0.capeArm.png", $mainx + $zcapex, $necky + $zcapey);
			
		// Create head
		$head = $skin + 10000;
		add_image($characterwz."/000".$head.".img/front.head.png", $mainx - 15, $mainy - 12);
		
		// Create earring
		if (isset($ears)) {
			$ear_location = $characterwz."/Accessory/0".$ears.".img/default.default.png";
			add_image($ear_location, $mainx + $earsx, $mainy + $earsy);
		}
		
		// Create shade hair
		if (substr_count($vslot, 'H1H2H3H4H5H6') != 1) {
			$shair_location = $characterwz."/Hair/000".$hair.".img/default.hairShade.".$hairshade.".png";
			add_image($shair_location, $mainx + $shadehairx, $mainy + $shadehairy);
		}
		
		// Create mask
		if (isset($mask) && $maskz == "accessoryFaceBelowFace") {
			$mask_location = $characterwz."/Accessory/0".$mask.".img/default.default.png";
			add_image($mask_location, $mainx + $maskx, $mainy + $masky);
		}
		
		$face_data = get_data($face);
		// Create face
		$face_location = $characterwz."/Face/000".$face.".img/default.face.png";
		$facex = -$face_data["default_face_origin_X"] - $face_data["default_face_map_brow_X"];
		$facey = -$face_data["default_face_origin_Y"] - $face_data["default_face_map_brow_Y"];
		add_image($face_location, $mainx + $facex, $mainy + $facey);
		
		// Create mask
		if (isset($maskz) && $maskz == "accessoryFace") {
			$mask_location = $characterwz."/Accessory/0".$mask.".img/default.default.png";
			add_image($mask_location, $mainx + $maskx, $mainy + $masky);
		}
		
		// Create eyes item
		if (isset($eyes) && $eyesz == "accessoryEye") {
			$eyes_location = $characterwz."/Accessory/0".$eyes.".img/default.default.png";
			add_image($eyes_location, $mainx + $eyesx, $mainy + $eyesy);
		}
	
		// Create hair
		if (isset($hair) && (substr_count($vslot, 'H1H2H3H4H5H6') != 1)) {
			$hair_location = $characterwz."/Hair/000".$hair.".img/default.hair.png";
			add_image($hair_location, $mainx + $hairx, $mainy + $hairy);
		}
		
		// Create accessoryFaceOverFaceBelowCap
		if (isset($maskz) && $maskz == "accessoryFaceOverFaceBelowCap") {
			$mask_location = $characterwz."/Accessory/0".$mask.".img/default.default.png";
			add_image($mask_location, $mainx + $maskx, $mainy + $masky);
		}
		
		// Create hairoverhead or hat
		if (isset($hat) && ($vslot == "Cp" || $vslot == "CpH5")) {
			$hat_location = $characterwz."/Hair/000".$hair.".img/default.hairOverHead.png";
			add_image($hat_location, $mainx + $overhairx, $mainy + $overhairy);
		}
		
		// Create hat
		if (isset($hat)) {
			$cap_location = $characterwz."/Cap/0".$hat.".img/default.default.png";
			add_image($cap_location, $mainx + $hatx, $mainy + $haty);
		}
		else {
			// Create top hair
			$thair_location = $characterwz."/Hair/000".$hair.".img/default.hairOverHead.png";
			add_image($thair_location, $mainx + $overhairx, $mainy + $overhairy);
		}
		
		// Create accessoryEyeOverCap
		if (isset($eyes) && $eyesz == "accessoryEyeOverCap") {
			$eyes_location = $characterwz."/Accessory/0".$eyes.".img/default.default.png";
			add_image($eyes_location, $mainx + $eyesx, $mainy + $eyesy);
		}
		
		// Create weapon - stand 1
		if ($weaponz == 'weapon' && $stand == 1) {
			if (isset($wepnum))
				$wep_location = $characterwz."/Weapon/0".$nxwep.".img/".$wepnum.".stand".$stand.".0.weapon.png";
			else
				$wep_location = $characterwz."/Weapon/0".$nxwep.".img/stand".$stand.".0.weapon.png";
			add_image($wep_location, $wepx + $weaponx, $wepy + $weapony);
		}
		
		// Create arm
		$arm_location = $characterwz."/0000".$skin.".img/stand".$stand.".0.arm.png";
		add_image($arm_location, $mainx + (($stand == 1) ? 8 : 4), $mainy + 23);
		
		// create coatArm - top
		if (isset($top)) {
			$coatarm_location = $characterwz."/Coat/0".$top.".img/stand" . $stand . ".0.mailArm.png";
			add_image($coatarm_location, $mainx + (($stand == 1) ? $mailax : $maila2x), $necky + (($stand == 1) ? $mailay : $maila2y));
		}
		
		// create coatArm - overall
		if (isset($overall)) {
			$coatarm_location = $characterwz."/Longcoat/0".$overall.".img/stand" . $stand . ".0.mailArm.png";
			add_image($coatarm_location, $mainx + (($stand == 1) ? $mailax : $maila2x), $necky + (($stand == 1) ? $mailay : $maila2y));
		}
	
		// Create weaponOverArm
		if($weaponz == 'weaponOverArm') {	
			if(isset($wepnum))
				$wep_location = $characterwz."/Weapon/0".$nxwep.".img/".$wepnum.".stand".$stand.".0.weapon.png";
			else
				$wep_location = $characterwz."/Weapon/0".$nxwep.".img/stand".$stand.".0.weapon.png";
			add_image($wep_location,  $wepx + $weaponx, $wepy + $weapony);
		}
	
		// Create hand2
		if ($stand == 2) {
			$hand2_location = $characterwz."/0000".$skin.".img/stand2.0.hand.png";
			add_image($hand2_location,  $mainx - 10, $mainy + 26);
		}
	
		if ((isset($glove)) && $stand == 2) {
			// Create lglove2
			$lglove2_location = $characterwz."/Glove/0".$glove.".img/stand2.0.lGlove.png";
			add_image($lglove2_location,  $mainx + $lglove2x, $necky + $lglove2y);
			
			// Create rglove2		
			$rglove2_location = $characterwz."/Glove/0".$glove.".img/stand2.0.rGlove.png";
			add_image($lglove2_location,  $mainx + $rglove2x, $necky + $rglove2y);
		}
		
		if ((isset($glove)) && $stand == 1) {
			//create rglove1
			$rglove1_location = $characterwz."/Glove/0".$glove.".img/stand1.0.rGlove.png";
			add_image($rglove1_location,  $mainx + $rglove1x, $necky + $rglove1y);
		}
		
		// Create weaponOverGlove
		if ($weaponz == 'weaponOverGlove' || $weaponz == 'weaponOverHand') {
			if (isset($wepnum))
				$wep_location = $characterwz."/Weapon/0".$nxwep.".img/".$wepnum.".stand".$stand.".0.weapon.png";
			else
				$wep_location = $characterwz."/Weapon/0".$nxwep.".img/stand".$stand.".0.weapon.png";
			add_image($wep_location,  $wepx + $weaponx, $wepy + $weapony);
		}
		
		// Write hash to the character variables
		mysql_query("INSERT INTO `character_variables` VALUES ('".$character_id."', 'image_hash', '".$hash."') ON DUPLICATE KEY UPDATE `image_hash` = '".$hash."'");
		
		getName($character_data['name'], 120/2, $mainy + 71);
		
		imagepng($im, $char_img);
	}
	else {
		// Get image
		add_image($char_img, 0, 0);
	}
	// Output image
	imagepng($im);
	imagedestroy($im);
}

// Function to phrase data into an array
function get_data($itemid) {
	$query = mysql_query("SELECT * FROM `phpVana_characterwz` WHERE `itemid` = ".$itemid);
	$item_info = array();
	while($data = mysql_fetch_array($query)) {
			$item_info[$data['key']] = ($data['value']);
	}
	return $item_info;
}

// Function to add element to the image
function add_image($location, $x, $y) {
	global $im;
	if (file_exists($location)) {
		$image = imagecreatefrompng($location);
		imagecopy($im, $image, $x, $y, 0, 0, imagesx($image), imagesy($image));
	}
}

function getName($name, $x, $y) {
	global $character_id;
	global $im;
	global $font;
	global $font_size;

	$startWidth = $x - calculateWidth($name)/2;
	$endWidth = $x + calculateWidth($name)/2;
	imagefillroundedrect($im, $startWidth, $y - 17, $endWidth - 1, $y - 2, 1, imagecolorallocate($im, 85, 85, 85));
	ImageTTFText($im, $font_size, 0, $startWidth + 3, $y - 5, imagecolorallocate($im, 255, 255, 255), $font, $name);
	$q = mysql_query("SELECT g.name, g.logobg, g.logobgcolor, g.logo, g.logocolor FROM characters c INNER JOIN guilds g ON g.id = c.guildid WHERE c.id = ".$character_id);
	
	if (mysql_num_rows($q) == 1) {
		$res = mysql_fetch_array($q);
		$name = $res[0];
		$hasemblem = ($res[1] != 0 || $res[2] != 0 || $res[3] != 0 || $res[4] != 0) ? true : false;
		$startWidth = $x - calculateWidth($name)/2;
		$endWidth = $x + calculateWidth($name)/2;
		
		imagefillroundedrect($im, $startWidth, $y, $endWidth - 1, $y + 15, 1, imagecolorallocate($im, 85, 85, 85));
		ImageTTFText($im, $font_size, 0, $startWidth + 2, $y + 12, imagecolorallocate($im, 255, 255, 255), $font, $name);
		ImageTTFText($im, $font_size, 0, $startWidth + 3, $y + 12, imagecolorallocate($im, 255, 255, 255), $font, $name);
		
		if ($hasemblem) {
			if ($res[1] != 0 || $res[2] != 0) {
				add_image('images/uiwz/GuildMark/BackGround/0000'.$res[1].'/'.$res[2].'.png', $startWidth - 18, $y + 1);
			}
			if ($res[3] != 0 || $res[4] != 0) {
				$name = "";
				$sort = floor($res[3] / 1000);
				if ($sort == 2) $name = "Animal";
				elseif ($sort == 3) $name = "Plant";
				elseif ($sort == 4) $name = "Pattern";
				elseif ($sort == 5) $name = "Letter";
				elseif ($sort == 9) $name = "Etc";
				add_image('images/uiwz/GuildMark/Mark/'.$name.'/0000'.$res[3].'/'.$res[4].'.png', $startWidth - 17, $y + 2);
			}
		}
	}
}

function calculateWidth($name) {
	global $font;
	global $font_size;
	$width = 7;
	$bbox = imagettfbbox($font_size, 0, $font, $name);
	$width += abs($bbox[4] - $bbox[0]);
	return $width;
}

function imagefillroundedrect($im, $x, $y, $cx, $cy, $rad, $col) {
	imagefilledrectangle($im,$x,$y+$rad,$cx,$cy-$rad,$col);
	imagefilledrectangle($im,$x+$rad,$y,$cx-$rad,$cy,$col);
	$dia = $rad*2;
	imagefilledellipse($im, $x+$rad, $y+$rad, $rad*2, $dia, $col);
	imagefilledellipse($im, $x+$rad, $cy-$rad, $rad*2, $dia, $col);
	imagefilledellipse($im, $cx-$rad, $cy-$rad, $rad*2, $dia, $col);
	imagefilledellipse($im, $cx-$rad, $y+$rad, $rad*2, $dia, $col);
}