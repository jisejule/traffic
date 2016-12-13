<?php
session_start();
require_once 'header.php';

print '<html lang="en">
<head>
<meta charset="utf-8">
<title>Kampala Crash Collaboration: About</title>
<link rel="stylesheet" href="style.css" type="text/css" media="screen">
</head><body>';

draw_header('About');

print "<div class='user_form'>";
print "<img src='book.jpg' align='right' border=3 />";
print "<h2>About the Crash Map</h2>";
print "<p>This project aims to transcribe the data about the road crashes that happen in Kampala, from the handwritten police records to a map, to help the city authority improve road safety, assist civil society campaign and to increase awareness around this really important issue.<br/><br/> The data has been collected by the police in large books (like the one illustrated to the right). A student at Makerere has visited the police stations around the city and has photographed hundreds of pages of police data. We want volunteers like yourself to help get that data out of the books and into the public domain.</p><br />";
print "<h2>About Me</h2>";
print "<p>I spent much of 2014 lecturing at Makerere university in Kampala. I used to cycle to and from work each day and was shocked by the problems of road safety in the city. I'm now a researcher at Sheffield University, but this tool has been developed as a project in my spare time and is being supported by Jimmy Kinyonyi, an MSc student at Makerere.</p>";

print "<p>To find out more, please send me an <a href='mailto:mike@michaeltsmith.org.uk'>email</a>.</p>";
print "</div>";
print "</body>";
print "</html>";
?>
