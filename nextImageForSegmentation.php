<?php
session_start();
require_once 'settings.php';

$conn = new mysqli("localhost",$db_username,$db_password,$db_name);
$query = sprintf("SELECT subdir, filename FROM traffic_tablefiles WHERE tablefile='%d' LIMIT 1;",$_GET['id']);
$res = $conn->query($query) or die("$query error: ".mysqli_error());
$conn->close();
$data = mysqli_fetch_row($res);
//print_r($data);
$sourcefile = "small_data/".$data[0]."/".$data[1];
error_log(print_r($sourcefile, TRUE)); 
$im = imageCreateFromJpeg($sourcefile);
header('Content-Type: image/jpeg');
imagejpeg($im);
//print $sourcefile;
?>
