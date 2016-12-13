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
print "<p>We want volunteers like yourself to help get car crash data out of paper books and into the public domain. Help us improve road safety in Kampala!</p><br />";
print "<p><a href='login.php?do=register'>Register</a> if you want to receive rewards for helping.</p>";
print "<br/>";
print "<p>Select what you would like to help work on:</p><br />";
print "<p><a href='grid.php'>Aligning images</a>.</p>";
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

print "</p><br />";
print "</div>";
print "</body>";
print "</html>";
?>
