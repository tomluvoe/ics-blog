<html>
<head>
<title>Ics-blog, the simple blog management program at samoht.se</title>
<?php
$include_path = "./bin";
set_include_path(get_include_path() . PATH_SEPARATOR . $include_path);

include("ics-blog.php");

$icsblog = new ics_blog("ics-blog.ics");

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

<h1>Ics Blog</h1>

Ics Blog is a simple blog management system based on the iCalendar standard (RFC 2445). It is written in PHP and compatible with all web servers supporting PHP and does not depend on any database - all data is stored in iCalendar .ics files.

<p>Please visit ics-blog's <a href="http://sourceforge.net/projects/ics-blog/">Sourceforge.net project page</a>. All program releases will be available on the project page.

<h2>Latest news</h2>

<?php

$icsblog->showlist(3);
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
