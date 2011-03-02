<html>
<head>
<title>Ics-blog, the simple blog management program at samoht.se</title>
<?php
$include_path = "./bin";
set_include_path(get_include_path() . PATH_SEPARATOR . $include_path);

include("ics-blog.php");

$icsblog = new ics_blog("http://www.google.com/calendar/ical/oc00qs2bn1o5odabmc0obt25og%40group.calendar.google.com/private-5a4f1e288edb5cb817dccba3a7b71c2a/basic.ics");

$icsblog->showcss();
?>

</head>

<body>

<table class="main" cellspacing="0">
<tbody>

<tr>

<td class="top" colspan="2">
GOOGLE CALENDAR BLOG
</td>

<td class="top" align="center" colspan="2">
<a class="top" href="/">Home</a>
/
<a class="top" href="/frugal/">Frugal</a>
/
<a class="top" href="/books/">Books</a>
/
<a class="top" href="/blog/">Blog</a>

/
<a class="samoht" href="/ics-blog/">Ics-Blog</a>
</td>
</tr>

<tr>
<td class="highlight" colspan="4">
Google Calendar Blog (also called Ics-Blog) is a free and simple blog management system.

<p>1. 5 minute installation.
<br>2. No database required.
<br>3. As easy to update as adding an entry in your calendar.
<p><em>But, what does it look like? <br>Like this, you are looking at it right now. The entries below are generated automatically from an existing Google Calendar.</em>
</td>
</tr>

<tr>

<td class="mainad0" colspan="3">

<div class="ics_bodytext">
Google Calendar Blog (also called Ics Blog) is a free and simple blog management system. 

<p>It is written in PHP and compatible with all web servers supporting PHP. Ics Blog does not depend on any external database set up on the web server, as all data is stored in your own <a href="http://calendar.google.com">Google Calendar</a>. Every entry you add to your calendar shows up as a new entry in your blog. 

<p>Even this page is just now automatically generated from the Ics Blog Google Calendar.

<p>Installation is easy, quick (5 minutes) and requires not super user access. Only make sure your web server has PHP support.

<div class="ics_header">Getting started</div>
<ol>
<li> Create your own Google Calendar (<a href="http://calendar.google.com">Click here and then on create account</a>)
<li> Download Ics Blog (<a href="http://sourceforge.net/projects/ics-blog/">Click here and then download</a>)
<li> Unzip Ics Blog into your user-directory on your web server
<li> Edit bin/class.config.php and update <em>public $csspath = "/css/";</em> with the path to the css files. (probably /css/)
<li> Open index.example.php and update <em>$icsblog = new ics_blog("http://www.google.com/calendar/ical/..."); </em> with the link to your Google Calendar.
<br>(Log in to Google Calendar, click Settings-&gt;Calendar Settings, choose Calendars, click on the name of your calendar, copy the address from Private Address-&gt;ICAL).
<li> Done! 
</ol> 
</div>
<div class="ics_header">Latest entries in the Google Calendar</div>

<?php

$icsblog->showlatestlist();
$icsblog->showdelimiter();

$icsblog->showlatest();

$icsblog->showdelimiter();

$icsblog->poweredby();
?>
</td>

<td class="mainad1">
<script type="text/javascript"><!--
google_ad_client = "pub-4630488243791118";
/* Frugal, 160x600, created 6/14/10 */ 
google_ad_slot = "9825946503";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

</td>


</tr>

<tr>
<td><td><td><td>
</tr>

</tbody></table>


</body>
</html>
