<html>
<head>
<title>Ics-blog, the simple blog management program at samoht.se</title>
<?php
$include_path = "./bin";
set_include_path(get_include_path() . PATH_SEPARATOR . $include_path);

include("ics-blog.php");

$icsblog = new ics_blog("ics/ics-blog.ics");

$icsblog->showcss();
?>

</head>

<body>

<table class="main" cellspacing="0">
<tbody>

<tr>

<td class="top">&nbsp;</td>

<td class="top">
Ics-Blog :: 
<a href="/index.php">home</a>  
 &gt; (ics-blog)</td>

<td class="top">&nbsp;</td>

</tr>
<tr>

<td>&nbsp;</td>

<td class="main">

<div class="head">Ics Blog</div>

Ics Blog is a simple blog management system based on the iCalendar standard (RFC 2445). It is written in PHP and compatible with all web servers supporting PHP. Ics Blog does not depend on any external database as all data is stored in iCalendar .ics files.

<p>Please visit ics-blog's <a href="http://www.samoht.se/ics-blog/">project web site</a> and <a href="http://sourceforge.net/projects/ics-blog/">Sourceforge.net project page</a>. All program releases will be available on both sites.

<div class="ics_header">Latest news</div>

<?php

$icsblog->showlatestlist();
$icsblog->showdelimiter();

$icsblog->showshortentry("purpose.ics-blog");
$icsblog->showshortentry("status.ics-blog");
$icsblog->showshortentry("requirements.ics-blog");

$icsblog->showdelimiter();

$icsblog->poweredby();
?>
</td>

<td>&nbsp;</td>

</tr>
</tbody></table>


</body>
</html>
