<?php
session_start();
include 'settings.php';
include 'header.php';

print '<html><head><title>Kampala Crash Collaboration</title><link rel="stylesheet" href="style.css" type="text/css" media="screen"></head><body>';
if ((isset($_POST['do'])) && ($_POST['do']=='register')) {
  draw_header('Register');
  $email = $_POST['email'];
  $pword = $_POST['pword'];
  $conn = new mysqli("localhost",$db_name,$db_password,$db_username);
//TODO: CHECK USERNAME IS UNIQUE
  $sql = sprintf("INSERT INTO traffic_users (email,pword) VALUES ('%s','%s')",mysqli_real_escape_string($conn,$email),mysqli_real_escape_string($conn,$pword));

  if ($conn->query($sql) === TRUE) {
    print "<p>Registered successfully. Please <a href='login.php?do=login'>login</a>.</p>";
  } else {
    print "<p>Error: " . $sql . $conn->error. "</p>";
  }
}

if ((isset($_POST['do'])) && ($_POST['do']=='login')) {
  draw_header('Logging in');
  $email = $_POST['email'];
  $pword = $_POST['pword'];
  $conn = new mysqli("localhost",$db_name,$db_password,$db_username);
  $sql = sprintf("SELECT userid, level, COUNT(*) AS totalcount FROM traffic_users WHERE email = '%s' AND pword = '%s';",mysqli_real_escape_string($conn,$email),mysqli_real_escape_string($conn,$pword));
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  if ($row['totalcount']==0)
  {
    print "<div class='user_form'>";
    print "<p>Invalid username or password.</p>";
    print "</div>";
  }
  else
  {
    print "<div class='user_form'>";
    print "<p>Login successful.</p>";
    print "<p>Proceed to <a href='transcribe.php'>transcription...</a></p>";
    print '<script language="javascript">window.location.href = "transcribe.php" </script>';
    print "</div>";
    $_SESSION['userid'] = $row['userid'];
    $_SESSION['username'] = $email; //TODO escape
    $_SESSION['userlevel'] = $row['level']; //TODO escape
    
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
  print "</div>";
  $_SESSION['userid'] = 0;
  $_SESSION['username'] = 'guest';
  $_SESSION['userlevel'] = 0;
}




?>

