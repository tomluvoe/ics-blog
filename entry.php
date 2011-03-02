<html>
<head>
<title>Ics-Blog, the simple blog management program at samoht.se</title>

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

<td class="top">

Go back to the <a class="samoht" href="index.php">Blog</a>. 

</td>

</tr>
<tr>

<td class="main">

<?php

$icsblog->showentry();

$icsblog->showdelimiter();
$icsblog->poweredby();

?>

</td>

</tr>
</tbody></table>


</body>
</html>
