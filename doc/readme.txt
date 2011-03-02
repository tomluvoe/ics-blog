ICS-BLOG 1.0
************
Ics-blog, Copyright 2009-2011 Thomas Larsson.
Ics-blog is released under the GNU Public License.
http://www.gnu.org/licenses/gpl.txt

* Change log
============
-> 1.0  - support for Google Calendar downloads
-> 0.16 - added support for multi-line urls
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
- Goto Google Calendar and create your own account
- Use index.example.php as a template for your new index.php and update 
  $icsblog = new ics_blog("http://www.google.com/calendar/ical/..."); 
  with the link to your Google Calendar.
  (Log in to Google Calendar, click Settings->Calendar Settings, 
  choose Calendars, click on the name of your calendar, copy the 
  address from Private Address->ICAL) 
- Point your browser to your site
  e.g. http://localhost/~me/

* Blog files
============
- The default way of using ics-blog is to use an online Google
  Calendar. But it is also possible to run ics-blog with a separate
  ics file. See below;

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

