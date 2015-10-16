<?php
session_start();
require_once 'header.php';

print '<html lang="en">
<head>
<meta charset="utf-8">
<title>Kampala Crash Collaboration</title>
<link rel="stylesheet" href="style.css" type="text/css" media="screen">
</head><body>';

draw_header('');

print "<div class='user_form'>";
print "<p>This project aims to transcribe the data about the<br/>";
print "road crashes that happen in Kampala, from the handwritten<br/>";
print "police records to a map, to help the city authority improve<br/>";
print "road safety, assist civil society campaign and to increase<br/>";
print "awareness around this really important issue.<br/>";
print "<br/>";
print "If you want credit for your work and maybe a gift - please register.<br/>";
print "<br/>";
print "<p>Select what you would like to help work on:</p><br />";

print "<p><a href='grid.php'>Alignment</a> and segmentation. (<b>THIS IS PRIORITY</b>)</p>";
print "<p> <br/>Of less importance for now:</p>";
print "<p>The <a href='transcribe.php?col=0'>dates and times</a>.</p>";
print "<p>The <a href='transcribe.php?col=3'>locations</a>.</p>";
print "<p>The <a href='transcribe.php?col=4'>severity</a> of the collisions.</p>";
print "<p>The <a href='transcribe.php?col=6'>vehicles involved</a>.</p>";
if ($_SESSION['userlevel']>1) {
  print "<p><br />Restricted columns:</p>";
  print "<p><a href='transcribe.php?col=7'>Fatalities</a></p>";
  print "<p><a href='transcribe.php?col=8'>Serious Injuries</a></p>";
  print "<p><a href='transcribe.php?col=9'>Other Injuries</a></p>";
}
print "</body>";
print "</html>";
?>
