<?php
session_start();

include 'settings.php';
include 'header.php';

//Generates the page to allow people to transcribe cells.

function process_submission()
{
  $userid = 0; //TODO Do this properly, cookies? etc.
  $name = $_GET['name'];
  $col = $_GET['col'];
  $conn = new mysqli("localhost",$db_username,$db_password,$db_name);

//-------CODE SPECIFIC TO EACH COLUMN...----------
  if ($col==0) {
    $datetime = $_GET['date_year']."_".$_GET['date_month']."_".$_GET['date_day']." ".$_GET['time_hour'].":".$_GET['time_min'].":00";
    $sql = sprintf("INSERT INTO traffic_results_col%d (userid,name,datetime) VALUES (%d,'%s','%s')",$col,$userid,mysqli_real_escape_string($conn,$name),mysqli_real_escape_string($conn,$datetime));
  } 
  if ($col==3) {
    $rawmaploc = $_GET['map_loc'];
    preg_match('/\(([0-9.]*), ([0-9.]*)\)/is',$rawmaploc,$matches);
    $lon = $matches[1];
    $lat = $matches[2];
    $sql = sprintf("INSERT INTO traffic_results_col%d (userid,name,location,lat,lon) VALUES (%d,'%s','%s',%0.4f,%0.4f)",$col,$userid,mysqli_real_escape_string($conn,$name),mysqli_real_escape_string($conn, $_GET['location']),mysqli_real_escape_string($conn, $lat),mysqli_real_escape_string($conn, $lon));
  } 
  if ($col==4) {
    $hitandrun = 'false'; 
    if (array_key_exists('hitandrun',$_GET)) {
      if ($_GET['hitandrun']=='on') { $hitandrun = 'true'; } 
    }
    $sql = sprintf("INSERT INTO traffic_results_col%d (userid,name,nature,hitandrun) VALUES (%d,'%s','%s',%s)",$col,$userid,mysqli_real_escape_string($conn,$name),mysqli_real_escape_string($conn, $_GET['nature']),$hitandrun);
  }
  if ($col==6) {
    $sql = sprintf("INSERT INTO traffic_results_col%d (userid,name,vehicle_one,vehicle_two) VALUES (%d,'%s','%s','%s')",$col,$userid,mysqli_real_escape_string($conn,$name),mysqli_real_escape_string($conn, $_GET['vehicle_one']),mysqli_real_escape_string($conn, $_GET['vehicle_two']));
  } 
  if ($col==7) {
    $nil = 'false'; 
    $more = 'false';
    if (array_key_exists('nil',$_GET)) {  if ($_GET['nil']=='on') { $nil = 'true'; } }
    if (array_key_exists('more',$_GET)) {  if ($_GET['more']=='on') { $more = 'true'; } }
    $sql = sprintf("INSERT INTO traffic_results_col%d (userid,name,fatality_one_genderage,fatality_two_genderage,fatality_one_type,fatality_two_type, more, nil) VALUES (%d,'%s','%s','%s','%s','%s',%s,%s)",$col,$userid,mysqli_real_escape_string($conn,$name),mysqli_real_escape_string($conn, $_GET['fatality_one_genderage']),mysqli_real_escape_string($conn, $_GET['fatality_two_genderage']),mysqli_real_escape_string($conn, $_GET['fatality_one_type']),mysqli_real_escape_string($conn, $_GET['fatality_two_type']),$more,$nil);
  }
//-------------------------------------------------
  $res = $conn->query($sql);
  $conn->close();
}


function draw_open_text($id,$title,$blurb)
{
  print "\n<div><p>$title</p>\n";
  print "\n<p class='blurb'>$blurb</p>\n</div>\n";
  print "<p><textarea name='$id'></textarea></p><br />";

}

function draw_map($id,$title,$blurb) #note, the 'id' parameter is not used, and no more than one map can exist on a page TODO
{
  print '<script src="https://maps.googleapis.com/maps/api/js"></script>';
  print '<script src="jquery-1.11.2.min.js"></script>';
  print "<script>
      function initialize() {
        var mapCanvas = document.getElementById('map-canvas');
        var mapOptions = {
          center: new google.maps.LatLng(0.32650, 32.5780),
          zoom: 14,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(mapCanvas, mapOptions);
        marker = new google.maps.Marker({
          position: new google.maps.LatLng(0,0), 
          map: map,
         // draggable:true
         });


        google.maps.event.addListener(map, 'click', function(event) {   placeMarker(event.latLng); });

        function placeMarker(location) {
          marker.setPosition(location);
	  $('input#map_loc').val(location);
        }

      }
      google.maps.event.addDomListener(window, 'load', initialize);

</script>";
  print "\n<div><p>$title</p>\n<p>";
  print "\n<p class='blurb'>$blurb</p>\n</div>\n";
  print "<input type='hidden' id='map_loc' name='map_loc' />";
  print '<div id="map-canvas"></div>';
}

function draw_date_picker($id,$title,$blurb)
{
  print "\n<div><p>$title</p>\n<p><select name='".$id."_day'>\n";
  for ($day=1;$day<=31;$day++) { print "<option value='$day'>$day</option>"; }
  print "\n</select>";
  print "\n<select name='".$id."_month'>\n";
  for ($month=1;$month<=12;$month++) { print "<option value='$month'>$month</option>"; }
  print "\n</select>\n";
  print "\n<select name='".$id."_year'>\n";
  for ($year=2008;$year<=2015;$year++) { print "<option value='$year'>$year</option>"; }
  print "\n</select></p>\n<p class='blurb'>$blurb</p>\n</div>\n";
}


function draw_time_picker($id,$title,$blurb)
{
  print "\n<div><p>$title</p>\n<p><select name='".$id."_hour'>\n";
  for ($hour=0;$hour<=24;$hour++) { printf("<option value='%02d'>%02d</option>",$hour,$hour); }
  print "\n</select>\n";
  print "\n<select name='".$id."_min'>\n";
  for ($min=0;$min<60;$min+=5) { printf("<option value='%02d'>%02d</option>",$min,$min); }
  print "\n</select></p>\n<p class='blurb'>$blurb</p>\n</div>\n";
}


function draw_select_box($id,$title,$buttons,$blurb)
{
  print "\n<div><p>$title</p>\n";
  print "<select name='$id'>\n";
  foreach ($buttons as $value=>$label)
  {
    print "<p><option value='$value'>$label</p>\n";
  }
  print "\n</select>\n<p class='blurb'>$blurb</p>\n</div>\n";
}


function draw_radio_buttons($id,$title,$buttons,$blurb,$default='') //set default to the key of the button you want to be checked by default
{
  print "\n<div><p>$title</p>\n";
  foreach ($buttons as $value=>$label)
  {
    if ($default==$value) {$checkedstring = ' checked="checked"';} else {$checkedstring = '';}
    print "<p><input type='radio' name='$id' value='$value'$checkedstring> $label</p>";
  }
  print "\n<p class='blurb'>$blurb</p>\n</div>\n";
}

function draw_checkbox($id,$title,$label,$blurb)
{
  print "\n<div><p>$title</p>\n";
  print "<p><input type='checkbox' name='$id'> $label</p>";
  print "\n<p class='blurb'>$blurb</p>\n</div>\n";
}

//  draw_checkbox('hitandrun','Hit and Run','Did the police think this was a "hit and run"?');


if (array_key_exists('name',$_GET)) {
  process_submission(); //handle if user is submitting a datapoint.
}

print '<html lang="en">
<head>
<meta charset="utf-8">
<title>Kampala Crash Collaboration</title>
<link rel="stylesheet" href="style.css" type="text/css" media="screen">
</head><body>';

draw_header('Transcription');


$col = $_GET['col'];

if ($_SESSION['userlevel'] <= 1) { //TODO: Only allow certain users to see columns 
  if (($col==7) || ($col==8) || ($col==9))
  {
    print "<p>Insufficient priviledges to access this column.</p>";
    return;
  }
}

$conn = $conn = new mysqli("localhost",$db_username,$db_password,$db_name);
$query = sprintf("SELECT tablefile, name, row, col FROM traffic_images WHERE col = %d ORDER BY rand() LIMIT 1",$col);
$res = $conn->query($query);
$conn->close();
$data = mysqli_fetch_row($res);
$filename = "segmented/$data[1].jpg";
$row = $data[2];
$actual_col = $data[3];
$tablefile = $data[0];
$name = $data[1];

print "<img src='$filename' class='tile' />";
print "<div class='formdata'>";
print "<form action='transcribe.php' method='GET'>";
print "<input type='hidden' name='name' value='$name' />";
print "<input type='hidden' name='col' value='$col' />";

//-------CODE SPECIFIC TO EACH COLUMN...----------
if ($col==0) { //date and time
  draw_date_picker('date','Date of collision','');
  draw_time_picker('time','Time of collision','There might be more than one date or time in the image, enter the first one of each.');
}
//col 1 and 2 contain private info - left out
if ($col==3) { //location
  draw_open_text('location','Location of collision','Try to copy the description of where the collision took place.');
  draw_map('map','Location','If you know Kampala well, help us by clicking on the map to identify the location of the collision');
}

if ($col==4) { //"nature"
  draw_radio_buttons('nature','Nature/Severity',array('minor'=>'Minor','serious'=>'Serious','fatal'=>'Fatal','other'=>'Other/None'),'How severe did the police feel the incident was?');
  print("<br />");
  draw_checkbox('hitandrun','Hit and Run','Hit and Run','Did the police think this was a "hit and run"?');
}

if ($col==6) { //vehicle(s) involved
draw_radio_buttons('vehicle_one','Vehicle involved #1',array(
'motorcar'=>'motorcar',
'light omnibus'=>'light omnibus',
'light goods vehicle'=>'light goods vehicle',
'medium goods vehicle'=>'medium goods vehicle',
'fuel truck'=>'fuel truck',
'motor cycle'=>'motor cycle',
'dual purpose vehicle'=>'dual purpose vehicle',
'unknown motor vehicle'=>'unknown motor vehicle',
'bicycle'=>'bicycle',
'other'=>'other'),'What was the vehicle involved');
  print("<br />");
draw_radio_buttons('vehicle_two','Vehicle involved #2 <br />(if applicable)',array(
'motorcar'=>'motorcar',
'light omnibus'=>'light omnibus',
'light goods vehicle'=>'light goods vehicle',
'medium goods vehicle'=>'medium goods vehicle',
'fuel truck'=>'fuel truck',
'motor cycle'=>'motor cycle',
'dual purpose vehicle'=>'dual purpose vehicle',
'unknown motor vehicle'=>'unknown motor vehicle',
'bicycle'=>'bicycle',
'other'=>'other',
'none'=>'(none)'),'What was the second vehicle involved (if applicable)','none');
}

if ($col==7) { //deaths
print "<h1>Fatalities</h1>";
print "<p>The gender and age of people who died in the collision are recorded such that, if a male adult pedestrian died, the form would say <span class='fixed'>'1 M/A pedestrian'</span>. If a female child (junior) passenger died, it would be recorded as <span class='fixed'>'1 F/J passenger'</span>.</p>";
print "<br />";
  draw_checkbox('nil','No fatalities reported?','Did the police report "nil" fatalities?');
print "<br /><h2>Fatality #1</h2>";
draw_select_box('fatality_one_genderage','Gender/Age',array('none'=>'(none)','ma'=>'M/A','fa'=>'F/A','mj'=>'M/J','fj'=>'F/J'),'Gender and age of the person');
draw_select_box('fatality_one_type','Situation',array('none'=>'(none)','cyclist'=>'Cyclist','motorcycle'=>'Motor Cycle','pedestrian'=>'Pedestrian','driver'=>'Driver','passenger'=>'Passenger','other'=>'Other'),'Situation/involvement of the person');

print "<br /><p>Fatality #2</h2>";
draw_select_box('fatality_two_genderage','Gender/Age',array('none'=>'(none)','ma'=>'M/A','fa'=>'F/A','mj'=>'M/J','fj'=>'F/J'),'Gender and age of the person');
draw_select_box('fatality_two_type','Situation',array('none'=>'(none)','cyclist'=>'Cyclist','motorcycle'=>'Motor Cycle','pedestrian'=>'Pedestrian','driver'=>'Driver','passenger'=>'Passenger','other'=>'Other'),'Situation/involvement of the person');
print "<br />";
  draw_checkbox('more','More?','Were there more than two fatalities?');
}
//---------------------------------------------------

print "<br />";
print "<input class='position:relative; left:40px;' type='submit' value='Submit' />";
print "</form></div>";
print "</span>";
print "</body>";
print "</html>";
?>
