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

include_once("class.vevent.php");
include_once("class.blogdata.php");
include_once("class.config.php");

class ics_blog {

	# DEBUG : -1 = OFF; 3 = FULL
	private $d = -1;

	private $cplugin;
	private $cblog;
	private $cconf;	

	function __construct($icsfile = "blog.ics") {

		$this->cconf = new config($this->d);
		#$this->cplugin = new vjournal($this->d);
		$this->cplugin = new vevent($this->d);
		$this->cblog = new blogdata($this->d);
		
		if($this->cplugin->readfile($this->cconf->icspath.''.$icsfile) == FALSE) {
			return FALSE;
		}
		
		$this->cblog->addjournal($this->cplugin);
	}

	function showlatest($limit=10,$tags=0) {
		$begin = $_GET["begin"];
		$this->cblog->showlatest($limit,$begin,$tags);
	}

	function showlist($limit=10,$tags=0) {
		$begin = 0;
		$this->cblog->showsummary($limit,$begin,$tags);
	}

	function showdelimiter() {
		$this->cblog->showdelimiter();
	}

	function showshortentry($uid=FALSE) {
		if($uid == FALSE) {
			$uid = $_GET["uid"];
		}
		if($uid == '' || $this->cblog->showshortentry($uid) == FALSE) {
			#$this->showlist();
		}
	}
	
	function showentry($uid=FALSE) {
		if($uid == FALSE) {
			$uid = $_GET["uid"];
		}
		if($uid == '' || $this->cblog->showentry($uid) == FALSE) {
			#$this->showlist();
		}
	}

	function showcss() {
		$this->cblog->showcssinclude();
	}

	function poweredby() {
		$this->cblog->showpoweredby();
	}
}

?>
