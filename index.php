<?php
session_start();
include 'header.php';

print '<html lang="en">
<head>
<meta charset="utf-8">
<title>Kampala Crash Collaboration</title>
<link rel="stylesheet" href="style.css" type="text/css" media="screen">
</head><body>';

draw_header('');

print "<div class='user_form'>";
print "<p>Select what you would like to help work on:</p><br />";

print "<p><a href='grid.php'>Alignment</a> and segmentation.</p>";
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
