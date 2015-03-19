<?php
//loops through the data folder adding all the images to the database so that 
//the nextImageForSegmentation.php script knows what images are available.

$imagefiles = glob('/var/www/html/data/*.JPG');
$conn = new mysqli("localhost","crashdata","gr4t3dfri2","crashdata");
foreach ($imagefiles as $imagefile)
{
  $imagefile = basename($imagefile);
  $query = $conn->prepare("INSERT INTO tablefiles (filename,segmented) VALUES (?,false);");
  $query->bind_param('s',$imagefile);
  $query->execute();
  $query->close();
}

$conn->close();
//TODO BEFORE segmenting check segments are inside image
?>
