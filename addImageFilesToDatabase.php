<?php
require_once "settings.php";
//loops through the data folder adding all the images to the database so that 
//the nextImageForSegmentation.php script knows what images are available.

print "Running...<br />";
$imagefiles = glob('data/*/*.JPG');
$conn = new mysqli("localhost",$db_username,$db_password,$db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
printf("Found %d images.<br />",sizeof($imagefiles));
foreach ($imagefiles as $imagefile)
{
  print "adding $imagefile <br />";
  preg_match('/\/(.*)\//is',$imagefile,$matches);
  $imagefile = basename($imagefile);
  $subdir = $matches[1];
  print "\n<br />";
  print_r($matches);
  print "\n<br />";
  $query = $conn->prepare("INSERT INTO traffic_tablefiles (filename,subdir,segmented) VALUES (?,?,false);");
  $query->bind_param('ss',$imagefile,$subdir);
  $query->execute();
  $query->close();
}

$conn->close();
print "Done!";
//TODO BEFORE segmenting check segments are inside image
?>
