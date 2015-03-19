<?php

function get_numberdone()
{
 if (isset($_SESSION['userid'])) {
   $userid = $_SESSION['userid'];
   $conn = new mysqli("localhost","crashdata","gr4t3dfri2","crashdata");
   if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
   $total = 0;
   foreach (array(0,3,4,6,7) as $col) {
    $total += $conn->query(sprintf("SELECT count(*) AS totalcount FROM results_col%d WHERE userid=%d;",$col,$userid))->fetch_assoc()['totalcount'];

   }
   $total += $conn->query(sprintf("SELECT count(*) AS totalcount FROM tablecorners WHERE userid=%d;",$userid))->fetch_assoc()['totalcount'];
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
  $sitename = "Kampala Crash Collaboration";
  print "<div class='header'><p><span class='title'>$sitename: $title</span>";
  $username = get_username();
  $userid = get_userid();
  $done = get_numberdone(); 
  $userlevel = get_userlevel();
  print "<span class='welcome'>Welcome $username &nbsp; [$done solved] {access-level: $userlevel} &nbsp;";
  if ($userid>0) {
    print "(<a href='login.php?do=logout'>logout</a>)";
  }
  else
  {
    print "(<a href='login.php?do=register'>register</a>/<a href='login.php?do=login'>login</a>)";
  }
  print " <a href='index.php'>home</a></span></p></div>";
}


?>
