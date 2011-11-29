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

$job_data = array(
	000 => array("id" => 000, "name" => "Beginner", "class" => "beginner"),
	100 => array("id" => 100, "name" => "Warrior", "class" => "warrior"),
	110 => array("id" => 110, "name" => "Fighter", "class" => "warrior"),
	111 => array("id" => 111, "name" => "Crusader", "class" => "warrior"),
	112 => array("id" => 112, "name" => "Hero", "class" => "warrior"),
	120 => array("id" => 120, "name" => "Page", "class" => "warrior"),
	121 => array("id" => 121, "name" => "White Knight", "class" => "warrior"),
	122 => array("id" => 122, "name" => "Paladin", "class" => "warrior"),
	130 => array("id" => 130, "name" => "Spearman", "class" => "warrior"),
	131 => array("id" => 131, "name" => "Dragon Knight", "class" => "warrior"),
	132 => array("id" => 132, "name" => "Dark Knight", "class" => "warrior"),
	200 => array("id" => 200, "name" => "Magician", "class" => "magician"),
	210 => array("id" => 210, "name" => "Fire/Poison Wizard", "class" => "magician"),
	211 => array("id" => 211, "name" => "Fire/Poison Mage", "class" => "magician"),
	212 => array("id" => 212, "name" => "Fire/Poison Arch Mage", "class" => "magician"),
	220 => array("id" => 220, "name" => "Ice/Lightning Wizard", "class" => "magician"),
	221 => array("id" => 221, "name" => "Ice/Lightning Mage", "class" => "magician"),
	222 => array("id" => 222, "name" => "Ice/Lightning Arch Mage", "class" => "magician"),
	230 => array("id" => 230, "name" => "Cleric", "class" => "magician"),
	231 => array("id" => 231, "name" => "Priest", "class" => "magician"),
	232 => array("id" => 232, "name" => "Bishop", "class" => "magician"),
	300 => array("id" => 300, "name" => "Archer", "class" => "bowman"),
	310 => array("id" => 310, "name" => "Hunter", "class" => "bowman"),
	311 => array("id" => 311, "name" => "Ranger", "class" => "bowman"),
	312 => array("id" => 312, "name" => "Bowmaster", "class" => "bowman"),
	320 => array("id" => 320, "name" => "Crossbow man", "class" => "bowman"),
	321 => array("id" => 321, "name" => "Sniper", "class" => "bowman"),
	322 => array("id" => 322, "name" => "Marksman", "class" => "bowman"),
	400 => array("id" => 400, "name" => "Rogue", "class" => "thief"),
	410 => array("id" => 410, "name" => "Assassin", "class" => "thief"),
	411 => array("id" => 411, "name" => "Hermit", "class" => "thief"),
	412 => array("id" => 412, "name" => "Night Lord", "class" => "thief"),
	420 => array("id" => 420, "name" => "Bandit", "class" => "thief"),
	421 => array("id" => 421, "name" => "Chief Bandit", "class" => "thief"),
	422 => array("id" => 422, "name" => "Shadower", "class" => "thief"),
	500 => array("id" => 500, "name" => "Pirate", "class" => "pirate"),
	510 => array("id" => 510, "name" => "Brawler", "class" => "pirate"),
	511 => array("id" => 511, "name" => "Marauder", "class" => "pirate"),
	512 => array("id" => 512, "name" => "Viper", "class" => "pirate"),
	520 => array("id" => 520, "name" => "Gunslinger", "class" => "pirate"),
	521 => array("id" => 521, "name" => "Outlaw", "class" => "pirate"),
	522 => array("id" => 522, "name" => "Corsair", "class" => "pirate"),
	900 => array("id" => 900, "name" => "GM", "class" => "gm"),
	910 => array("id" => 910, "name" => "Super GM", "class" => "gm"),
	800 => array("id" => 920, "name" => "Maple World Leaf Brigade", "class" => "gm"),
	1000 => array("id" => 1000, "name" => "Noblesse", "class" => "beginner"),
	1100 => array("id" => 1100, "name" => "Dawn Warrior", "class" => "warrior"),
	1200 => array("id" => 1200, "name" => "Blaze Wizard", "class" => "magician"),
	1300 => array("id" => 1300, "name" => "Wind Archer", "class" => "bowman"),
	1400 => array("id" => 1400, "name" => "Night Walker", "class" => "thief"),
	1500 => array("id" => 1500, "name" => "Thunder Breaker", "class" => "pirate"),
	1110 => array("id" => 1110, "name" => "Dawn Warrior", "class" => "warrior"),
	1210 => array("id" => 1210, "name" => "Blaze Wizard", "class" => "magician"),
	1310 => array("id" => 1310, "name" => "Wind Archer", "class" => "bowman"),
	1410 => array("id" => 1410, "name" => "Night Walker", "class" => "thief"),
	1510 => array("id" => 1510, "name" => "Thunder Breaker", "class" => "pirate"),
	1111 => array("id" => 1111, "name" => "Dawn Warrior", "class" => "warrior"),
	1211 => array("id" => 1211, "name" => "Blaze Wizard", "class" => "magician"),
	1311 => array("id" => 1311, "name" => "Wind Archer", "class" => "bowman"),
	1411 => array("id" => 1411, "name" => "Night Walker", "class" => "thief"),
	1511 => array("id" => 1511, "name" => "Thunder Breaker", "class" => "pirate"),
	1112 => array("id" => 1112, "name" => "Dawn Warrior", "class" => "warrior"),
	1212 => array("id" => 1212, "name" => "Blaze Wizard", "class" => "magician"),
	1312 => array("id" => 1312, "name" => "Wind Archer", "class" => "bowman"),
	1412 => array("id" => 1412, "name" => "Night Walker", "class" => "thief"),
	1512 => array("id" => 1512, "name" => "Thunder Breaker", "class" => "pirate"),
	2000 => array("id" => 2000, "name" => "Legend", "class" => "aran")
);
