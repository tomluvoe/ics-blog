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

	function showlatest($limit=10,$start=0,$tags=0,$relurl="") {
		$this->showgenericlist($this->listtype["LATESTFULL"],$limit,$start,$tags,$relurl);
	}

	function showlatestlist($limit=10,$start=0,$tags=0,$relurl="") {
		$this->showgenericlist($this->listtype["LATESTLIST"],$limit,$start,$tags,$relurl," | ");
	}

	function showlatestbrief($limit=10,$start=0,$tags=0,$relurl="") {
		$this->showgenericlist($this->listtype["LATESTBRIEF"],$limit,$start,$tags,$relurl);
	}

	function showshortentry($uid,$relurl="") {
                foreach ($this->entries as $e) {
                        if(chop($e["UID"]) == $uid) {
                                $this->showitembrief($e,$relurl);
                                return TRUE;
                        }
                }
                return FALSE;
	}
	
	function showentry($uid,$relurl="") {
		foreach ($this->entries as $e) {
			if(chop($e["UID"]) == $uid) {
				$this->showitem($e,$relurl);
				return TRUE;
			}
		}
		return FALSE;
	}

	function showentryhead($uid,$relurl="") {
                foreach ($this->entries as $e) {
                        if(chop($e["UID"]) == $uid) {
                                $this->showtitle($e,$relurl);
                                return TRUE;
                        }
                }
                return FALSE;
	}

	function showpoweredby() {
		$this->output("<DIV class=\"".$this->conf->css_powered."\">This site is powered by ics-blog.</DIV>");
	}

	function showversion() {
                $this->outputdetailsp("ics-blog ".$this->ver);
	}

// --

	public $dbg;
	private $conf;

	public $ver = "0.16";
	
	private $entries = array();
	private $showtype = array("ITEMFULL" => 0, "ITEMBRIEF" => 1, "ITEMLIST" => 2, "ITEMBRIEFLIST" => 3);

	function __construct($debug=-1) {
		$this->dbg = new debug($debug);
		$this->conf = new config();
	}

	function addjournal($cvj) {
		$this->entries = array_merge($this->entries, $cvj->getentries());
		$this->dbg->msg("DEBUG","Added entries to blog data.");
	}

	private function showgenericlist($type=0,$limit=10,$start=0,$tags=0,$relurl="",$delimiter="") {
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
				$this->showitem($e,$relurl);
				break;
			case $this->listtype["LATESTBRIEF"]:
				if($i > 1) { $this->showdelimiter(); }
				$this->showitembrieflist($e,$relurl);
				break;
			case $this->listtype["LATESTLIST"]:
				$this->showtitle($e,$relurl);
				$this->output($delimiter);
				break;
			}
		}
		switch($type) {
		case $this->listtype["LATESTFULL"]:
		case $this->listtype["LATESTBRIEF"]:
			if ($limit > 1) {
				if ($i < $num) { 
					$this->output("<DIV>"); 
					$this->shownavigation($limit,$start,$num,$relurl); 
					$this->output("</DIV>");
				}
			}
			break;
		}
	}

	private function shownavigation($limit,$start,$num,$relurl="") {
		$c = $this->conf;
		$next = $start+$limit;
		if ($start == 0 and $next >= $num) {
			return;
		}
		$this->showdelimiter();
		$this->output("<SPAN class=\"".$c->css_navigate."\">");
		if ($start > 0) {
			$prev = $start-$limit;
			if ($prev < 0) { $prev = 0; }
			$this->output("<A class=\"".$c->css_navigate."\" href=\"".$relurl."?begin=$prev\">");
			$this->output("&lt; Newer Posts");
			$this->output("</A>");
			$this->output(" &nbsp; &nbsp; ");
		}
		if ($next < $num) {
			$this->output("<A class=\"".$c->css_navigate."\" href=\"".$relurl."?begin=$next\">");
			$this->output("Older Posts &gt;");
			$this->output("</A>");
		}
		$this->output("</SPAN>");
	}

	private function showtitle($entry,$relurl="") {
		$this->showgenericitem($entry,$this->showtype["ITEMLIST"],$relurl);
	}
	
	private function showitem($entry,$relurl="") {
		$this->showgenericitem($entry,$this->showtype["ITEMFULL"],$relurl);
	}

        private function showitembrief($entry,$relurl="") {
                $this->showgenericitem($entry,$this->showtype["ITEMBRIEF"],$relurl);
        }

        private function showitembrieflist($entry,$relurl="") {
                $this->showgenericitem($entry,$this->showtype["ITEMBRIEFLIST"],$relurl);
        }

	private function showgenericitem($entry,$type,$relative_url="") {
		$e = $entry;
		$c = $this->conf;
		
		switch($type) {
		case $this->showtype["ITEMLIST"]:
			$this->output("<A class=\"".$c->css_list."\" href=\"".$relative_url.$c->uid."".$e["UID"]."\">".$e["SUMMARY"]."</A>\n");
			break;
		case $this->showtype["ITEMBRIEF"]:
                        $this->output("<DIV class=\"".$c->css_header."\"><A class=\"".$c->css_header."\" href=\"".$c->uid."".$e["UID"]."\">".$e["SUMMARY"]."</A></DIV>");
			$description = preg_replace("/<P>/","</DIV><DIV class=\"".$c->css_bodytext."\">",$e["DESCRIPTION"]);
                        $this->output("<DIV class=\"".$c->css_bodytext."\">".$description."</DIV>");
			break;
		case $this->showtype["ITEMBRIEFLIST"]:
			$this->output("<DIV class=\"".$c->css_header."\"><A class=\"".$c->css_header."\" href=\"".$relative_url.$c->uid."".$e["UID"]."\">".$e["SUMMARY"]."</A></DIV>");
			$this->output("<DIV class=\"".$c->css_author."\">Posted on ".$e["DTSTART"].", ");
			$this->output("by ".$this->contact($e["CONTACT"]));
			$this->output("</DIV>");
			$description = preg_replace("/<P>/","</DIV><DIV class=\"".$c->css_bodytext."\">",$e["DESCRIPTION"]);
			$this->output("<DIV class=\"".$c->css_bodytext."\">".substr($description,0,200)."...</DIV>");
			break;
		case $this->showtype["ITEMFULL"]:
			$this->output("<DIV class=\"".$c->css_header."\"><A class=\"".$c->css_header."\" href=\"".$c->uid."".$e["UID"]."\">".$e["SUMMARY"]."</A></DIV>");
			$this->output("<DIV class=\"".$c->css_author."\">Posted on ".$e["DTSTART"].", ");
			$this->output("by ".$this->contact($e["CONTACT"]));
			$this->output("</DIV>");
			$description = preg_replace("/<P>/","</DIV><DIV class=\"".$c->css_bodytext."\">",$e["DESCRIPTION"]);
			$this->output("<DIV class=\"".$c->css_bodytext."\">".$description."</DIV>");
			if($e["LAST-MODIFIED"] != FALSE && $e["LAST-MODIFIED"] != $e["CREATED"]) {
				$this->output("<DIV class=\"".$c->css_modified."\">Last modified on ".$e["LAST-MODIFIED"]."</DIV>");
			}
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
