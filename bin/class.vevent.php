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

include_once("class.config.php");
include_once("class.debug.php");

class vevent {

	public $dbg;
	public $conf;

	private $file = "";
	private $fileok = FALSE;
	private $ics = array("VERSION"=>FALSE, "PRODID"=>FALSE);

	private $numentries = 0;
	private $entries = array();
	private $entry;
	private $entrynew = array("UID"=>FALSE, "CREATED"=>FALSE, "DTSTAMP"=>FALSE, "DTSTART"=>FALSE, "LAST-MODIFIED"=>FALSE, "SUMMARY"=>FALSE, "DESCRIPTION"=>FALSE, "CLASS"=>FALSE, "STATUS"=>FALSE, "CONTACT"=>FALSE, "CATEGORY"=>FALSE);
	private $property = "";

	private $states = array("NONE" => 0,"VCALENDAR" => 1,"VEVENT" => 2,"MULTILINE" => 3,"DONE" => 4);
	private $state = 0; 

	function __construct($debug=-1) {
		$this->conf = new config();
		$this->dbg = new debug($debug);
	}

	function fileok() {
		$this->dbg->msg("DEBUG","File check (".$this->file.") status ".$this->fileok);
		return $this->file;
	}
	
	function getnumentries() {
		return $this->numentries;
	}
	
	function getentries() {
		return $this->entries;
	}
	
	function readfile($file) {
		if($fileok == TRUE) {
			$this->dbg->msg("ERROR","File (".$this->file.") has already been opened! Failed to open (".$file.")!");
			return FALSE;
		}
		$buf = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if($buf == FALSE) { 
			$this->dbg->msg("ERROR","File open (".$file.") failed!");
			return FALSE; 
		}

		if($this->parsebuffer($buf,$d) == FALSE) {
			$this->dbg->msg("ERROR","Buffer parsing failed! (".$file.")");
			return FALSE;
		}
		
		$this->file = $file;
		$this->fileok = TRUE;
		return TRUE;
	}
	
	private function parseline($line) {
		switch($this->state) {
			case $this->states["NONE"]:
				if(strpos($line,"BEGIN:VCALENDAR") === 0) {
					$this->state = $this->states["VCALENDAR"];
					$this->dbg->msg("DEBUG","Parseline: State None->VCalStart");
				}
				break;
			case $this->states["VCALENDAR"]:
				if(strpos($line,"VERSION:") === 0) {
					$this->ics["VERSION"] = substr($line,8);
				} else if(strpos($line,"PRODID:") === 0) {
					$this->ics["PRODID"] = substr($line,7);
				} else if(strpos($line,"BEGIN:VEVENT") === 0) { 
					$this->state = $this->states["VEVENT"];
					$this->property = "";
					$this->dbg->msg("DEBUG","Parseline: State VCalendar->VEvent");
					$this->entry = $this->entrynew;
				} else if(strpos($line,"END:VCALENDAR") === 0) {
					$this->state = $this->states["DONE"];
					$this->dbg->msg("DEBUG","Parseline: State VCalendar->Done");
				}
				break;
			case $this->states["VEVENT"]:
				$this->dbg->msg("DEBUG","Parseline: Line : ".$line);
				if(strpos($line,"END:VEVENT") === 0) {
					if($this->checkentry()) {
						$this->storeentry();
					}
					$this->state = $this->states["VCALENDAR"];
					$this->dbg->msg("DEBUG","Parseline: State VEvent->VCalendar");
					break;
				} 				
				$properties = array_keys($this->entrynew);
				$prop_match = FALSE;
				foreach ($properties as $p) {
					if(strpos($line,$p) === 0) {
						$this->property = $p;
						$prop_match = TRUE;
						break;
					}
				}
				$line_match = preg_match("/^[A-Z-]+?[:;]/",$line);
				if($prop_match == FALSE && $line_match) {
					# Unsupported tag
					$this->property = "";
					$this->dbg->msg("DEBUG","Parseline: Unsupported property");
				}
				if($prop_match) {
					$c_pos = strpos($line,":");
					if($c_pos !== FALSE) {
						$current_line = substr($line,$c_pos+1);
					} else {
						$current_line = substr($line,strlen($this->property)+1);
					}
				} else {
					$current_line = $line;
				}
				switch ($this->property) {
					case "":
						break;
					case "DTSTAMP":
					case "DTSTART":
						$this->dbg->msg("DEBUG","Parseline: Store ".$this->property." : ".$this->simpledate($current_line));
						$this->entry[$this->property] = $this->entry[$this->property].$this->simpledate($current_line);
						break;
					case "CREATED":
					case "LAST-MODIFIED":
						$this->dbg->msg("DEBUG","Parseline: Store ".$this->property." : ".$this->simpledate($current_line));
						$this->entry[$this->property] = $this->entry[$this->property].$this->simpledate($current_line,TRUE);
						break;
					default:
						$current_line = $this->htmlize($current_line);
						$this->dbg->msg("DEBUG","Parseline: Store ".$this->property." : ".$current_line);
						$this->entry[$this->property] = $this->entry[$this->property].$current_line;
						break;
				}
				break;
		}	
	}

	private function simpledate($buf,$time=FALSE) {
		$buf = trim($buf);
		$tim = "";
		switch(strlen($buf)) {
			case 16:
			case 15:
				if($time == TRUE) {
					$tim = " at ".substr($buf,9,2).":".substr($buf,11,2).":".substr($buf,13,2);
				}
			case 14:
			case 13:
			case 8:
				$y = substr($buf,0,4);
				$m = substr($buf,4,2);
				$d = substr($buf,6,2);
				$buf = $y."-".$m."-".$d.$tim;
				break;
			default:
				// do not do anything
				break;
		}
		return $buf;
	}

	private function htmlize($buf) {
		$buf = preg_replace("/(\\\\n)+/","\n<P>",$buf);
		$buf = preg_replace("/\\\\,/",",",$buf);
		$buf = preg_replace("/\\\\\"/","\"",$buf);
		$buf = preg_replace("/\\\\;/",";",$buf);
		return $buf;
	}
	
	private function parsebuffer($buf) {
		$this->dbg->msg("DEBUG","Start buffer parsing.");

		$this->state = $this->states["NONE"];
		foreach ($buf as $line) {
			$this->parseline($line);
		}

		if($this->state != $this->states["DONE"]) {
			$this->dbg->msg("ERROR","Parse failed. VCALENDAR:END not found in file!");
			return FALSE;
		}
		
		$this->entries = array_reverse($this->entries);
		$this->numentries = count($this->entries);
		$this->dbg->msg("INFO","iCal \"".$this->ics["PRODID"]."\" version ".$this->ics["VERSION"]." opened! Entries: ".$this->numentries);
		return TRUE;
	}
	
	private function checkentry() {
		if($this->entry["STATUS"] == FALSE) {
			$this->entry["STATUS"] = "DRAFT";
		} else {
			chop($this->entry["STATUS"]);
		}
		if($this->entry["CLASS"] == FALSE) {
			$this->entry["CLASS"] = "PUBLIC";
		} else {
			chop($this->entry["CLASS"]);
		}
		if($this->entry["CONTACT"] == FALSE) {
			$this->entry["CONTACT"] = $this->conf->author_default;
		} else {
			chop($this->entry["CONTACT"]);
		}
		chop($this->entry["UID"]);
		$this->entry["DESCRIPTION"] = preg_replace("/\:.*?\/\//","://",$this->entry["DESCRIPTION"]);

		$e = $this->entry;

		$this->dbg->msg("DEBUG","VEvent (".$e["UID"].") \"".$e["SUMMARY"]."\" - (".$e["CREATED"]." / ".$e["DTSTART"]." / ".$e["DTSTAMP"]." / ".$e["LAST-MODIFIED"].") Tags: \"".$e["CATEGORY"][0]."\",... Text: \"".substr($e["DESCRIPTION"],0,20)."\"... \"".$e["CLASS"]."\" \"".$e["STATUS"]."\"");

		if($e["UID"] != FALSE &&
		   $e["DTSTAMP"] != FALSE &&
		   $e["SUMMARY"] != FALSE &&
		   strpos($e["CLASS"],"PUBLIC") === 0  &&
		   (strpos($e["STATUS"],"DRAFT")=== 0 || strpos($e["STATUS"],"FINAL")=== 0)) {
		   	$this->dbg->msg("DEBUG","Entry (".$e["UID"].") stored.");
			return TRUE;
		}

	   	$this->dbg->msg("WARNING","Entry (".$e["UID"].") rejected! Entry is not complete.");
		return FALSE;
	}
	
	private function storeentry() {
		$this->entries[] = $this->entry;
	}
}

?>
