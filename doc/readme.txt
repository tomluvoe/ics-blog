ICS-BLOG 0.15
*************

Ics-Blog, Copyright 2009-2010 Thomas Larsson.
Ics-Blog is released under the GNU Public License.
http://www.gnu.org/licenses/gpl.txt

* Change log
============
-> 0.15 - redefined the css and improved the interface
-> 0.14 - added functionality to show a subset of entries in one page
-> 0.13 - improved parsing to support Gnome Evolution ics calendars
-> 0.12 - rewrote the state machine for parsing iCalendar
-> 0.11 - changed VJOURNAL to VENTRY support.
-> 0.10 - first release.

* Requirements
==============
- PHP5
- Standard web server setup, e.g. Apache

* Getting started
=================
- Unpack Ics-Blog to a folder visible on your web server
  e.g. http://localhost/~me/
- Cd into bin/
- Open the file "class.config.php"
- Edit the variable $csspath to the path of your stylesheets
  e.g. "/~me/css/"
- Point your browser to your site
  e.g. http://localhost/~me/

* Blog files
============
- The blog files are stored in ics/

- The format is standard .ics format (RFC2445)
  Ics-Blog supports the VENTRY entries, the default type created
  by any software supporting VCALENDAR. All your entries in that
  ics file will be shown as blog entries.
  Create ics files with almost any calendar application!
  For example;
    http://www.mozilla.org/projects/calendar/sunbird/
    http://projects.gnome.org/evolution/
    many more..

* Create your own index.php 
===========================
- Add PHP code in the HTML head

<?php
  $include_path = "./bin";
  set_include_path(get_include_path() . PATH_SEPARATOR . $include_path);
  include("ics-blog.php");
?>

  to the beginning of your php file in order to load ics-blog.
  (assuming that the index.php file is located in the "root".)

- Open an ics file (relative path from php file) 

<?php
  $icsblog = new ics_blog("ics/ics-blog.ics");
?>

- Also within the HTML head, show the style-sheets

<?php
  $icsblog->showcss();
?>

- In your HTML body tag, use e.g. any of the following function calls,
  (open the file /bin/ics-blog.php - "public functions" - too view all 
   possible function calls)

<?php
  $icsblog->showlatestlist(5);
  $icsblog->showlatestfull(100);
  $icsblog->showdelimiter();
  $icsblog->poweredby();
?>

