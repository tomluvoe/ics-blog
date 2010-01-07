<?php

#
#    ICS-BLOG - Copyright 2009-2010 Thomas Larsson
#
#    This file is part of Ics-Blog.
#
#    Ics-Blog is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.
#
#    Ics-Blog is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with Ics-Blog.  If not, see <http://www.gnu.org/licenses/>.
#
#

class debug {

	private $debug;
	
	function __construct($dbg=-1) {
		$this->debug = $dbg;
	}
	
	function setdebug($dbg) {
		if($dbg >= 0 && $dbg < 4) {
			$this->debug = $dbg;
			return TRUE;
		}
		return FALSE;
	}
	
	function msg($lvl,$msg,$fcn="") {
		switch($lvl) {
			case "ERROR":
				$dbg = 0;
				break;
			case "WARNING":
				$dbg = 1;
				break;
			case "INFO":
				$dbg = 2;
				break;
			case "DEBUG":
				$dbg = 3;
				break;
			default:
				return FALSE;
		}
		if($dbg > $this->debug) {
			return FALSE;
		}
		if($fcn == "") {
			$fcn = "CLASS";
		}
		print "${lvl}: $msg ($fcn)<br>\n";
	}
}

?>
