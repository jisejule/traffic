<?php
require_once 'settings.php';
function get_numberdone()
{
 global $db_username;
 global $db_password;
 global $db_name;
 if (isset($_SESSION['userid'])) {
   $userid = $_SESSION['userid'];
  // require_once 'settings.php';
   $conn = new mysqli("localhost",$db_username,$db_password,$db_name);
   if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error. "$db_username,$db_password,$db_name"); }
   $total = 0;
   foreach (array(0,3,4,6,7,8,9) as $col) {
  //  $total += $conn->query(sprintf("SELECT count(*) AS totalcount FROM traffic_results_col%d WHERE userid=%d;",$col,$userid))->fetch_assoc()['totalcount'];
     $query = $conn->prepare(sprintf("SELECT count(*) AS totalcount FROM traffic_results_col%d WHERE userid=?;",$col));
     $query->bind_param('i',$userid);
     $query->execute();
     $query->bind_result($count);
     $query->fetch();
     $query->close();
     $total += $count;
   }
  // $total += $conn->query(sprintf("SELECT count(*) AS totalcount FROM traffic_tablecorners WHERE userid=%d;",$userid))->fetch_assoc()['totalcount'];
     $query = $conn->prepare("SELECT count(*) AS totalcount FROM traffic_tablecorners WHERE userid=?;");
     $query->bind_param('i',$userid);
     $query->execute();
     $query->bind_result($count);
     $query->fetch();
     $query->close();
     $total += $count;
   return $total;
 }
 return "?";
}

function get_userid()
{
 if (isset($_SESSION['userid'])) { $userid = $_SESSION['userid']; } else { $userid = 0; }
 return $userid;
}

function get_userlevel()
{
 if (isset($_SESSION['userlevel'])) { $userlevel = $_SESSION['userlevel']; } else { $userlevel = 0; }
 return $userlevel;
}

function get_username()
{
 if (isset($_SESSION['username'])) { $username = $_SESSION['username']; } else { $username = 'guest'; }
 return $username;
}



function draw_header($title)
{
  $sitename = "Crash Map Kampala";
  print "<div class='header'><p><a href='index.php'><span class='title'>$sitename";
  if (strlen($title)>1) {
    print ": $title";
  }
  print "</span></a>";
  $username = get_username();
  $userid = get_userid();
  $done = get_numberdone(); 
  $userlevel = get_userlevel();
  print "<span class='welcome'>";
  if ($username != 'guest') {
    print "$username - $done solved &nbsp; &nbsp;";
  }
  if ($userid>0) {
    print " <a href='login.php?do=logout'>logout</a> |";
  }
  else
  {
    print "<a href='login.php?do=register'>register</a> | <a href='login.php?do=login'>login</a> | ";
  }
  print " <a href='index.php'>home</a> | <a href='about.php'>about</a></span></p></div>";
}


?>
