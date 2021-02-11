<?php
include('constants.php');
include('emailusers.php');

function setnotification($is_reset)
{
  if($is_reset)
  {
    echo
    "<script type='text/javascript'>document.getElementById('notification').innerHTML = 'Password succssfully reset. Please check your email for your new temporary password.<br>Remember to change it as soon as you can to another!<br><br>';</script>";
  }
  else {
    echo
    "<script type='text/javascript'>document.getElementById('notification').innerHTML = 'Failed to reset account. Please try again.<br><br>';</script>";
  }
}
?>

<html>
<style rel="stylesheet" href="jobhunterstyle.css">

body{
  background-image: url('smileywomanbook.jpg');
  background-size: cover;
  font-family: Arial, Helvetica, sans-serif;
  background-attachment: fixed;
}

#webname{
  font-style: bold;
  font-family: cursive;
  font-size: 20px;
  color: black;
  text-align: center;
  position: fixed;
}
</style>
<body>
  <div id="homecolumn" style="padding-left: 55%; padding-right:8%; padding-top:2%;">
    <div id="webname">
      <a href="index.html"><img src = "JobHunterLogo.gif" width = 400px height = 100px style="display:block; margin: auto; padding: 42px; background-color: transparent;"></a>
      Please enter your account username:<br />
      <form action='resetacc.php' method=POST onsubmit='return validateemail(email)'>
        <input size='50' type='text' name=username id=username style="width:350px; height:30px;"
        required placeholder='Type your account username here...'><br /><br />
        Please enter your account email:<br />
        <input size='50' type='email' name=email id=email style="width:350px; height:30px;"
        required placeholder='Type your account email here...'><br /><br />
        <button type=submit name=resetsubmit id=resetsubmit style="width:150px; height:40px;">Reset account</button>
      </form>
      <div style="font-size: 12px;">
        <!-- This is to notify the user of the status of their update. (Done in PHP and JS dont change) -->
        <p id=notification></p>
      </div>
      <a href='index.html'>Home Page</a>
    </div>
  </div>

</body>

<script type='text/javascript'>
function validateemail(object)
{
  var email = object.value;
  var regexp =/^([\w-.]+)(@(?=[\w-.]+))([\w]+\.(?=[\w]+)){1,3}([\w]{2,3}$)/;
  var bool = regexp.test(email);
  if (bool == false)
  {
    alert("Please enter a valid email.");
  }
  return bool;
}
</script>
</html>

<!-- This script is meant to be at the bottom -->
<?php
  if(isset($_POST['resetsubmit']))
  {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $query = "SELECT 1 FROM $usertable WHERE username='$username' AND email='$email'";
    $result = $conn->query($query);

    if($result->num_rows > 0)
    {
      $string = uniqid();
      $starting_index = rand(0,7);
      $newpassword = substr($string, $starting_index, 6);
      $encrypted = md5($newpassword);

      $query = "UPDATE IGNORE $usertable SET password='$encrypted' WHERE username='$username'";
      $result = $conn->query($query);
      if (!$result)
      {
        phpAlert('Error resetting account: '.$conn->error);
        setnotification(0);
      }
      else {
        @accountreset_mail($username,$newpassword);
        setnotification(1);
      }
    }
    else {
      phpAlert('Your account details is wrong or does not exist.');
    }
  }
?>
<?php $conn->close(); ?>
