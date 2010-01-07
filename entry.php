<html>
<head>
<title>Ics-blog, the simple blog management program at samoht.se</title>
<link rel="stylesheet" href="/css/samoht.css" type="text/css"/>
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
<a href="index.php">home</a>
 &gt; (ics-blog)</td>

<td class="top">&nbsp;</td>

</tr>
<tr>

<td>&nbsp;</td>

<td class="main">

<?php

$icsblog->showentry();

$icsblog->showdelimiter();
$icsblog->poweredby();
?>
</td>

<td>&nbsp;</td>

</tr>
</tbody></table>


</body>
</html>
