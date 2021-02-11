<?php
$servername = "localhost";
$phpusername = "root";
$phppassword = "";
$databasename = "userbase";
$usertable = "usertable";//Table containing all account data
$appliedlist = "appliedjobs";//Table containing jobs that user applied for
$joblist = "jobtable";//Table containing jobs that employer provide

@$conn = mysqli_connect($servername, $phpusername, $phppassword, $databasename);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (!$conn->select_db($databasename))
{
  die("Connection to user table failed.");
}

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}
?>
