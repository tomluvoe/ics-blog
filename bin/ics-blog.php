<?php

#
#    ICS-BLOG - Copyright 2009-2011 Thomas Larsson
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

### public functions

	# ics control functions

	# display list with entries, with link, date, author etc
	function showlatestfull($limit=10,$relurl="",$tags=0) {
		$begin = $_GET["begin"];
		$this->cblog->showlatest($limit,$begin,$tags,$relurl);
	}

	# display list with entries, with link, date, author etc but limited header size and characters
	function showlatestbrief($limit=10,$relurl="",$tags=0) {
		$begin = $_GET["begin"];
		$this->cblog->showlatestbrief($limit,$begin,$tags,$relurl);
	}

	# displays a list of entries, only with a links to each entry
	function showlatestlist($limit=10,$relurl="",$tags=0) {
		$begin = 0;
		$this->cblog->showlatestlist($limit,$begin,$tags,$relurl);
	}

	# display one entry, with link, date, author etc
	function showitemfull($uid=FALSE,$relurl="") {
		if($uid == FALSE) {
			$uid = $_GET["uid"];
		}
		if(!($uid == '')) {
			$this->cblog->showentry($uid,$relurl);
		}
	}

	# display one entry, without link information about date, author etc
	function showitembrief($uid=FALSE,$relurl="") {
		if($uid == FALSE) {
			$uid = $_GET["uid"];
		}
		if(!($uid == '')) { 
			$this->cblog->showshortentry($uid,$relurl);
		}	
	}

	function showitemheader($uid=FALSE,$relurl="") {
                if($uid == FALSE) {
                        $uid = $_GET["uid"];
                }
                if(!($uid == '')) {
                        $this->cblog->showentryhead($uid,$relurl);
                }      
	}
	# dividers etc

	function showdelimiter() {
		$this->cblog->showdelimiter();
	}

	function poweredby() {
		$this->cblog->showpoweredby();
	}

	# cascading style sheets html code

	function showcss() {
		$this->cblog->showcssinclude();
	}


### end public functions

	# obsolete functions - will be removed in later version

	function showlatest($limit=10,$tags=0) {
		$this->showlatestfull($limit,"",$tags);
	}

	function showentry($uid=FALSE) {
		$this->showitemfull($uid);
	}

	function showlist($limit=10,$tags=0) {
		$this->showlatestlist($limit,"",$tags);
	}

	function showshortentry($uid=FALSE) {
		$this->showitembrief($uid);
	}

	
### end public functions

	# DEBUG : -1 = OFF; 3 = FULL
	private $d = -1;

	private $cplugin;
	private $cblog;
	private $cconf;	

	function __construct($icsfile = "blog.ics", $debug = FALSE) {

		if($debug != FALSE) {
			$this->d = $debug;
		}

		$this->cconf = new config($this->d);
		$this->cplugin = new vevent($this->d);
		$this->cblog = new blogdata($this->d);
		
		if($this->cplugin->readfile($this->cconf->icspath.''.$icsfile) == FALSE) {
			return FALSE;
		}
		
		$this->cblog->addjournal($this->cplugin);
	}
}

?>
