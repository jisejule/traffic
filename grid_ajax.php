<?php 
session_start();

include "settings.php";
$userid = $_SESSION['userid'];

//This script is called by the ajax request on the page to save the data.
$in = $_GET;
$id = 0;

$tablefile = 1;
$timestamp = 0;
$conn = new mysqli("localhost",$db_username,$db_password,$db_name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = sprintf("INSERT INTO traffic_tablecorners (userid,tablefile,topleftx,toplefty,toprightx,toprighty,bottomleftx,bottomlefty,bottomrightx,bottomrighty,rows)
VALUES (%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d)",
$userid,$in['imageId'],$in['topleft'][0],$in['topleft'][1],$in['topright'][0],$in['topright'][1],$in['bottomleft'][0],$in['bottomleft'][1],$in['bottomright'][0],$in['bottomright'][1],$in['noRows']);
// Perform Query

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . $conn->error;
}

$conn->close();
?>
