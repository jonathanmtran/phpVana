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

class Timer {
	private $start_time;
	private $end_time;
	private $render_time;
	
	function start() {
		$this->start_time = explode(" ", microtime());
		$this->start_time =  $this->start_time[1] + $this->start_time[0];
	}
	
	function stop() {
		$this->end_time = explode(' ', microtime());
		$this->render_time = round($this->end_time[0] +  $this->end_time[1] - $this->start_time, 3);
	}
	
	function time() {
		return $this->render_time;
	}
}