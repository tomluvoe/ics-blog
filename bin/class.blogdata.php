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

include_once("class.debug.php");
include_once("class.config.php");

class blogdata {

	private $listtype = array("LATESTFULL" => 0,"LATESTLIST" => 1,"LATESTBRIEF" => 2);
//--

	function showdelimiter() {
		$this->output("<hr>");
	}

	function showcssinclude() {
		$this->output("<link rel=\"stylesheet\" href=\"".$this->conf->csspath.$this->conf->icsblogcss."\" type=\"text/css\"/>");
		$this->output("<link rel=\"stylesheet\" href=\"".$this->conf->csspath.$this->conf->icsmaincss."\" type=\"text/css\"/>");
	}

	function showlatest($limit=10,$start=0,$tags=0) {
		$this->showgenericlist($this->listtype["LATESTFULL"],$limit,$start,$tags);
	}

	function showlatestlist($limit=10,$start=0,$tags=0) {
		$this->showgenericlist($this->listtype["LATESTLIST"],$limit,$start,$tags);
	}

	function showlatestbrief($limit=10,$start=0,$tags=0) {
		$this->showgenericlist($this->listtype["LATESTBRIEF"],$limit,$start,$tags);
	}

	function showshortentry($uid) {
                foreach ($this->entries as $e) {
                        if(chop($e["UID"]) == $uid) {
                                $this->showitembrief($e);
                                return TRUE;
                        }
                }
                return FALSE;
	}
	
	function showentry($uid) {
		foreach ($this->entries as $e) {
			if(chop($e["UID"]) == $uid) {
				$this->showitem($e);
				return TRUE;
			}
		}
		return FALSE;
	}

	function showpoweredby() {
		$this->outputdetailsp("This site is powered by ics-blog.");
	}

	function showversion() {
                $this->outputdetailsp("ics-blog ".$this->ver);
	}

// --

	public $dbg;
	private $conf;

	public $ver = "0.14";
	
	private $entries = array();
	private $showtype = array("ITEMFULL" => 0, "ITEMBRIEF" => 1, "ITEMLIST" => 2, "ITEMBRIEFLIST" => 3);

	function __construct($debug=-1) {
		$this->dbg = new debug($debug);
		$this->conf = new config();
	}

	function outputdetailsp($msg) {
                $this->output("<P class=\"".$this->conf->css_details_1."\">");
                $this->output($msg."</P>");
	}

	function addjournal($cvj) {
		$this->entries = array_merge($this->entries, $cvj->getentries());
		$this->dbg->msg("DEBUG","Added entries to blog data.");
	}

	private function showgenericlist($type=0,$limit=10,$start=0,$tags=0) {
		$i = 0;
		$num = count($this->entries);
		$sta = $start;
		foreach ($this->entries as $e) {
			if($sta-- > 0) {
				continue;
			}
			if($this->hastag($e,$tags) == FALSE) {
				continue;
			}
			if($i++ >= $limit) {
				break;
			}
			switch($type) {
			case $this->listtype["LATESTFULL"]:
				if($i > 1) { $this->showdelimiter(); }
				$this->showitem($e);
				break;
			case $this->listtype["LATESTBRIEF"]:
				if($i > 1) { $this->showdelimiter(); }
				$this->showitembrieflist($e);
				break;
			case $this->listtype["LATESTLIST"]:
				$this->showtitle($e);
				break;
			}
		}
		switch($type) {
		case $this->listtype["LATESTFULL"]:
		case $this->listtype["LATESTBRIEF"]:
			if ($i < $num) { $this->shownavigation($limit,$start,$num); }
			break;
		}
	}

	private function shownavigation($limit,$start,$num) {
		$next = $start+$limit;
		if ($start == 0 and $next >= $num) {
			return;
		}
		$this->showdelimiter();
		$this->output("<P class=\"".$c->description_p."\">");
		if ($start > 0) {
			$prev = $start-$limit;
			if ($prev < 0) { $prev = 0; }
			$this->output("<A href=\"?begin=$prev\">");
			$this->output("&lt; Newer Posts");
			$this->output("</A>");
			$this->output(" &nbsp; &nbsp; ");
		}
		if ($next < $num) {
			$this->output("<A href=\"?begin=$next\">");
			$this->output("Older Posts &gt;");
			$this->output("</A>");
		}
		$this->output("</P>");
	}

	private function showtitle($entry) {
		$this->showgenericitem($entry,$this->showtype["ITEMLIST"]);
	}
	
	private function showitem($entry) {
		$this->showgenericitem($entry,$this->showtype["ITEMFULL"]);		
	}

        private function showitembrief($entry) {
                $this->showgenericitem($entry,$this->showtype["ITEMBRIEF"]);
        }

        private function showitembrieflist($entry) {
                $this->showgenericitem($entry,$this->showtype["ITEMBRIEFLIST"]);
        }

	private function showgenericitem($entry,$type) {
		$e = $entry;
		$c = $this->conf;
		
		switch($type) {
		case $this->showtype["ITEMLIST"]:
			$this->output("<P class=\"".$c->css_summary_3."\">- <A href=\"".$c->uid."".$e["UID"]."\">".$e["SUMMARY"]."</A> (".$this->contact($e["CONTACT"]).") - ".$e["DTSTART"]."</P>");
			break;
		case $this->showtype["ITEMBRIEF"]:
                        $this->output("<P class=\"".$c->css_summary_1."\">".$e["SUMMARY"]."</P>");
                        $this->output("<P class=\"".$c->css_descrip_1."\">".$e["DESCRIPTION"]."</P>");
			break;
		case $this->showtype["ITEMBRIEFLIST"]:
			$this->output("<P class=\"".$c->css_summary_2."\"><A href=\"".$c->uid."".$e["UID"]."\">".$e["SUMMARY"]."</A></P>");
			$this->output("<P class=\"".$c->css_details_2."\">Date: ".$e["DTSTART"]);
			$this->output(" / Author: ".$this->contact($e["CONTACT"]));
			if($e["LAST-MODIFIED"] != FALSE && $e["LAST-MODIFIED"] != $e["CREATED"]) {
				$this->output(" / Last-modified: ".$e["LAST-MODIFIED"]);
			}
			$this->output("</P>");
			$this->output("<P class=\"".$c->css_descrip_2."\">".substr($e["DESCRIPTION"],0,200)."...</P>");
			break;
		case $this->showtype["ITEMFULL"]:
			$this->output("<P class=\"".$c->css_summary_1."\"><A href=\"".$c->uid."".$e["UID"]."\">".$e["SUMMARY"]."</A></P>");
			$this->output("<P class=\"".$c->css_details_1."\">Date: ".$e["DTSTART"]);
			$this->output(" / Author: ".$this->contact($e["CONTACT"]));
			$this->output(" / Status: ".$e["STATUS"]);
			$this->output(" / Tags: ".$e["CATEGORY"]);
			if($e["LAST-MODIFIED"] != FALSE && $e["LAST-MODIFIED"] != $e["CREATED"]) {
				$this->output("<BR>Last-modified: ".$e["LAST-MODIFIED"]);
			}
			$this->output("</P>");
			$this->output("<P class=\"".$c->css_descrip_1."\">".$e["DESCRIPTION"]."</P>");
			break;
		}
	}

	private function contact($buf) {
		if(preg_match("/^MAILTO:(.*)/",$buf,$group)) {
			return "<A href=\"".$buf."\">".$group[1]."</A>";		
		}
		return $buf;
	}

	private function hastag($entry,$tags) {
		if ($tag == 0) {
			return TRUE;
		}
		if(is_string($tags)) {
			if(preg_match("/$tag/",$entry["CATEGORY"])) {
				return TRUE;
			}
		} else if(is_array($tags)) {
			foreach ($tags as $t) {
				if(preg_match("/$tag/",$entry["CATEGORY"])) {
					return TRUE;
				}			
			}
		}
		return FALSE;
	}
	
	private function output($buf) {
		print $buf."\n";
	}
}

?>
