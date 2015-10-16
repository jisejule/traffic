<?php
session_start();
require_once 'settings.php';
require_once 'header.php';

print '<html><head><title>Kampala Crash Collaboration</title><link rel="stylesheet" href="style.css" type="text/css" media="screen"></head><body>';
if ((isset($_POST['do'])) && ($_POST['do']=='register')) {
  draw_header('Register');
  $email = $_POST['email'];
  $pword = $_POST['pword'];
  $conn = new mysqli("localhost",$db_username,$db_password,$db_name);
//TODO: CHECK USERNAME IS UNIQUE
//  $sql = sprintf("INSERT INTO traffic_users (email,pword) VALUES ('%s','%s')",mysqli_real_escape_string($conn,$email),mysqli_real_escape_string($conn,$pword));
  $query = $conn->prepare("INSERT INTO traffic_users (email,pword) VALUES (?,?)");
  $query->bind_param('ss',$email,$pword);
  if ($query->execute() === TRUE) {
    print "<p>Registered successfully. Please <a href='login.php?do=login'>login</a>.</p>";
  } else {
    print "<p>Error: " . $sql . $conn->error. "</p>";
  }
  $query->close();
}

if ((isset($_POST['do'])) && ($_POST['do']=='login')) {
  draw_header('Logging in');
  $email = $_POST['email'];
  $pword = $_POST['pword'];
  $conn = new mysqli("localhost",$db_username,$db_password,$db_name);
  $query = $conn->prepare("SELECT userid, level, COUNT(*) AS totalcount FROM traffic_users WHERE email = ? AND pword = ?");
  $query->bind_param('ss',$email,$pword);
  $query->execute();
  $query->bind_result($userid, $level, $totalcount);
  $query->fetch();
 // $res = $conn->query($sql);
  //$row = $res->fetch_assoc();
  //if ($row['totalcount']==0)
  if ($totalcount==0)
  {
    print "<div class='user_form'>";
    print "<p>Invalid username or password.</p>";
    print "</div>";
  }
  else
  {
    print "<div class='user_form'>";
    print "<p>Login successful.</p>";
    print "<p>Proceed to <a href='index.php'>the home page...</a></p>";
    print '<script language="javascript">window.location.href = "index.php" </script>';
    print "</div>";
    $_SESSION['userid'] = $userid;
    $_SESSION['username'] = $email;//TODO escape
    $_SESSION['userlevel'] = $level;//TODO escape?
    
  }
}

if ($_GET['do']=='register') {
  draw_header('Register');
  print "<div class='user_form'>";
  print "<form action='login.php' method='post'>";
  print "<p class='user_form_desc'>Email address (for logging in):</p><input type='text' name='email' /><br />";
  print "<p class='user_form_desc'>Password:</p><input type='password' name='pword' /><br />";
  print "<input type='hidden' name='do' value='register' />";
  print "<input type='submit' style='margin-top:20px; margin-left:20px;' value='Register' /></form></div>";
}

if ($_GET['do']=='login') {
  draw_header('Login');
  print "<div class='user_form'>";
  print "<form action='login.php' method='post'>";
  print "<p class='user_form_desc'>Email address (for logging in):</p><input type='text' name='email' /><br />";
  print "<p class='user_form_desc'>Password:</p><input type='password' name='pword' /><br />";
  print "<input type='hidden' name='do' value='login' />";
  print "<input type='submit' style='margin-top:20px; margin-left:20px;' value='Login' /></form></div>";
}


if ($_GET['do']=='logout') {
  draw_header('Login');
  print "<div class='user_form'>";
  print "<p>Logged out</p>";
  print "<p>Proceed to <a href='index.php'>the home page...</a></p>";
  print '<script language="javascript">window.location.href = "index.php" </script>';
  print "</div>";
  $_SESSION['userid'] = 0;
  $_SESSION['username'] = 'guest';
  $_SESSION['userlevel'] = 0;
}




?>

