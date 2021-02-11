<?php
include('constants.php');
include('emailusers.php');

session_start();

$username = $_SESSION['valid_user'];
$query = 'select * from '. $usertable
         ." where username='$username'";
$result = $conn->query($query);
$row = mysqli_fetch_assoc($result);

if (!isset($_SESSION['valid_user']))
{
  echo "<br><a href=index.html>Home Page</a><br>";
  $conn->close();
  exit("You are not logged in.");
}

$fullname = $row['fullname'];
$email = $row['email'];
$is_employer = '';
if ($row['is_employer'])
{
  $is_employer = 'checked';
}

?>
<html>
<style>
<?php include 'jobhunterstyle.css'; ?>
#centercolumn{
        min-width: 400px;
        width: 90%;
        height: auto;
        padding: 42px;
        color: rgb(10, 156, 246);
        float: left;
        text-align: center;
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */

}

/* Modal Content */
.modal-content {
  background-image: url('schneideroffice.jpg');
  background-size: cover;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 50%;
  color: rgb(255, 255, 255);
}

.title{
  font-style: oblique;
  font-family: cursive, Arial;
  margin-top: 5px;
  background-color: rgba(0,0,0,0.85); /* Black w/ opacity */
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: red;
  text-decoration: none;
  cursor: pointer;
}
.animate {
  -webkit-animation: animatezoom 0.6s;
  animation: animatezoom 0.6s
}

@-webkit-keyframes animatezoom {
  from {-webkit-transform: scale(0)}
  to {-webkit-transform: scale(1)}
}

@keyframes animatezoom {
  from {transform: scale(0)}
  to {transform: scale(1)}
}

</style>
<body>
  <div style="width:80%; min-width:850px; height: auto; margin:auto;">
    <div id="centercolumn" style="float: none;">
      <a href="main.php"><img src = "JobHunterLogo.gif"  width = 500px height= 100px style="display:block; margin: auto; padding-bottom:30px;"></a>
      <div class="centercontent" width=500px >
        <form action="account.php" method=POST>
          Current Password*:<br />
          <input type=password name=currpassword id=currpassword style="width:325px; height:30px;" required minlength=6><br /><br />
          New Password:<br />
          <input type=password name=newpassword id=newpassword style="width:325px; height:30px;" minlength=6><br /><br />
          New Password confirmation:<br />
          <input type=password name=newpassword2 id=newpassword2 style="width:325px; height:30px;" minlength=6><br /><br />
          Fullname:<br />
          <input type=text name=newfullname id=newfullname onchange=check_fullname(newfullname) style="width:325px; height:30px;" maxlength=50 value=<?php echo $fullname; ?>><br /><br />
          Email:<br />
          <input type=email name=newemail id=newemail onchange=check_email(newemail) style="width:325px; height:30px;" maxlength=50 value=<?php echo $email; ?>><br /><br />
          Are you an Employer?:<br />
          <input type=checkbox name=is_employer id=is_employer <?php echo $is_employer; ?>><br /><br />
          <div>
            <!-- Here will show the status of the account details change -->
            <p id='notification'></p>
          </div>
          <button type=submit name=newsubmit style="width:100px; height:25px;">Submit</button>
          <button type=reset name=newreset style="width:100px; height:25px;">Reset</button><br /><br />
          *Required fields<br><br>
          You last updated your account at: <?php echo $row['updated_datetime']; ?>
        </form>
      </div>
    </div>
  </div>
</body>
<footer>
  <div style="margin: 0 45%; width: 5%; min-width:125px;">
    <a href=main.php>Back to Jobs Page</a>
  </div>
  <table border="0" width= "100%" style="color:rgb(10, 156, 246); padding-left: 30px; padding-right:30px;">
    <tr>
      <td style="text-align: center; margin: auto; width: 30%">
        <i>Contact us for any enquiries: <a href="mailto:contact@jobhunter.com">contact@jobhunter.com</a></i>
      </td>
      <td style="text-align: center; margin: auto; width: 30%">
        <i>Copyright &copy; 2020 klkz@jobhunter.com</i>
      </td>
      <td style="text-align: center; margin: auto; width: 30%">
        <i>
          <p id="aboutus">About Us</p>
        </i>
          <!-- The Modal -->
          <div id="myModal" class="modal">
            <div class="modal-content animate">
              <span class="close">&times;</span>
              <h1 class="title">About Us</h1>
              <p style="background-color: rgba(0,0,0,0.85);">
                Our culture is our foundation, and we take great pride in it. <br>
                It’s the collective personality of our organization. <br>
                It sets us apart, defines who we are, and shapes what we aspire to be.<br><br>
                Below are the <b>three pillars</b> we strive to uphold that underscore what we do and how we do it.<br>
                <b><u>Transformation</u></b>: Every one of us is here to transform ourselves, our company, and our world for the better.<br>
                <b><u>Collaboration</u></b>: We’re capable of so much more when we work together.<br>
                <b><u>Results</u></b>: We set clear goals. We measure our success. We fix what doesn’t work. We deliver.</p>
            </div>
          </div>
      </td>
    </tr>
  </table>
</footer>
<script type="text/javascript" src="createacc.js">
</script>
</html>
<?php
if (isset($_POST['newsubmit']))
{

  $currpassword = $_POST['currpassword'];
  $currpassword = md5($currpassword);

  if ($row['password'] == $currpassword)
  {
    $subquery = '';
    $is_validpw = true;

    if ((isset($_POST['newpassword']) && $_POST['newpassword'] != '') ||
    (isset($_POST['newpassword2']) && $_POST['newpassword2'] != ''))
    {
      if ($_POST['newpassword'] == $_POST['newpassword2'])
      {
        $temp = md5($_POST['newpassword']);
        $subquery .= " password='$temp',";
      }
      else
      {
        $is_validpw = false;
        phpAlert('Your new password do not match. Please try again.');
        echo
        "<script type='text/javascript'>document.getElementById('notification').innerHTML = 'Your new password do not match. Please try again.<br><br>';</script>";
      }
    }
    if (isset($_POST['newfullname']) && $_POST['newfullname']!='')
    {
      $temp = $_POST['newfullname'];
      $subquery .= " fullname='$temp',";
    }
    if (isset($_POST['newemail']) && $_POST['newemail']!='')
    {
      $temp = $_POST['newemail'];
      $subquery .= " email='$temp',";
    }
    if (isset($_POST['is_employer']))
    {
      $temp = 1;
    }
    else
    {
      $temp = 0;
    }
    $subquery .= " is_employer='$temp',";
    $subquery = substr($subquery, 0, -1);

    if ($is_validpw)
    {
      $query = "UPDATE IGNORE $usertable SET". $subquery ." WHERE username='$username'";
      $changeaccount = $conn->query($query);
      if (!$changeaccount)
      {
        phpAlert('Error changing account details: '.$conn->error);
        echo
        "<script type='text/javascript'>document.getElementById('notification').innerHTML = 'Failed to change account details. Please try again.<br><br>';</script>";
      }
      else
      {
        @accountchange_mail($username);

        $_SESSION['is_employer'] = false;
        if (isset($_POST['is_employer']))
        {
          $_SESSION['is_employer'] = true;
        }
        if (isset($_POST['newfullname']) && $_POST['newfullname']!='')
        {
          $_SESSION['fullname'] =  $_POST['newfullname'];
        }

        phpAlert('Account details changed successfully.');
        echo
        "<script type='text/javascript'>document.getElementById('notification').innerHTML = 'Account details changed successfully! An email notification has been sent to you.<br><br>';</script>";
      }
    }
  }
  else
  {
    phpAlert('You have entered the wrong password for your account. Please verify and try again.');
    echo
    "<script type='text/javascript'>document.getElementById('notification').innerHTML = 'The password you entered is wrong. Please try again.<br><br>';</script>";
  }
}
?>
<?php $conn->close(); ?>
