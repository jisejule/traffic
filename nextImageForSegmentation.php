<?php
$conn = new mysqli("localhost","crashdata","gr4t3dfri2","crashdata");
$query = sprintf("SELECT filename FROM tablefiles WHERE tablefile='%d' LIMIT 1",$_GET['id']);
$res = $conn->query($query);
$conn->close();
$data = mysqli_fetch_row($res);
$sourcefile = "/var/www/html/small_data/".$data[0];
$im = imageCreateFromJpeg($sourcefile);
header('Content-Type: image/jpeg');
imagejpeg($im);

?>
