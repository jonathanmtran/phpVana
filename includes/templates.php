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

Source code from: http://www.massassi.com/php/articles/template_engines/
*/

class Template {
	var $vars; /// Holds all the template variables

	/**
	 * Constructor
	 *
	 * @param $file string the file name you want to load
	 */
	function Template($file = null) {
		$this->file = $file;
	}

	/**
	 * Set a template variable.
	 */
	function set($name, $value) {
		$this->vars[$name] = is_object($value) ? $value->fetch() : $value;
	}

	/**
	 * Open, parse, and return the template file.
	 *
	 * @param $file string the template file name
	 */
	function fetch($file = null) {
		if (!$file) {
			$file = $this->file;
		}
		if ($this->vars != null) {
			extract($this->vars);			// Extract the vars to local namespace
		}
		ob_start();						// Start output buffering
		include($file);					// Include the file
		$contents = ob_get_contents();	// Get the contents of the buffer
		ob_end_clean();					// End buffering and discard
		return $contents;				// Return the contents
	}
}