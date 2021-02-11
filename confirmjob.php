<?php
include('constants.php');
include('emailusers.php');

session_start();
if (!isset($_SESSION['valid_user']))
{
  echo "<br><a href=index.html>Home Page</a><br>";
  $conn->close();
  exit("You are not logged in.");
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
        text-align: left;
}
.tableofconfirmation{
 border: solid;
 margin: auto;
}
</style>
<body>
  <div style="width:80%; min-width:850px; height: auto; margin:auto;">
    <div id="centercolumn" style="float: none;">
      <a href="main.php"><img src = "JobHunterLogo.gif"  width = 500px height= 100px style="display:block; margin: auto; padding-bottom:30px;"></a>
      <div class="centercontent" width=500px style="text-align: center;">
        <span style="font-family: serif; font-size: 25px;">Job Application Confirmation</span><br><br>

        <?php
          if (!isset($_POST['applysubmit']))
          {
            echo "<span>Please select a job to apply from the jobs page.<br><br></span>";
            $conn->close();
            exit("<footer>
              <div style='margin: 0 43%; width: 5%; min-width:150px;'>
                <a href=main.php>Return to Jobs Page</a>
              </div>
            </footer>");
          }

          $username = $_SESSION['valid_user'];
          $query = "SELECT email FROM $usertable WHERE username='$username'";
          $email = mysqli_fetch_assoc($conn->query($query))['email'];

          $jobid = $_POST['jobid'];
          $userdetails = $_POST['userdetails'];

          $sql = "INSERT INTO $appliedlist (username, jobid, email, userdetails)
              VALUES ('$username', '$jobid', '$email', '$userdetails')";

          $result = $conn->query($sql);
          if (!$result)
          {
            $conn->close();
            exit("<span>You have already applied for this job.<br><br></span>
            <footer>
              <div style='margin: 0 43%; width: 5%; min-width:150px;'>
                <a href=main.php>Return to Jobs Page</a>
              </div>
            </footer>");
          }

          @applyjob_mail($username, $jobid, $userdetails);

          $query = "SELECT updated_datetime FROM $appliedlist WHERE username='$username' AND jobid='$jobid'";
          $updated_datetime = mysqli_fetch_assoc($conn->query($query))['updated_datetime'];
        ?>
        <p style="font-size:20px;"><u>You have applied for the following job:</u></p><br>

        <table border="1" class="tableofconfirmation">
          <?php
            $query = "SELECT * FROM $joblist WHERE jobid=$jobid";
            $result = $conn->query($query);
            $row = mysqli_fetch_assoc($result);
          ?>
          <tr>
            <td>Company: </td>
            <td><span style='word-wrap: break-word; max-width: 800px;'><?php echo $row['company']; ?></span></td>
          </tr>
          <tr>
            <td>Email: </td>
            <td><span style='word-wrap: break-word; max-width: 800px;'><?php echo $row['email']; ?></span></td>
          </tr>
          <tr>
            <td>Job title: </td>
            <td><span style='word-wrap: break-word; max-width: 800px;'><?php echo $row['position']; ?></span></td>
          </tr>
          <tr>
            <td>Indicated salary: </td>
            <td>$<?php echo $row['salary']; ?></td>
          </tr>
          <tr>
            <td>Date listed: </td>
            <td><?php echo $row['updated_datetime']; ?></td>
          </tr>
          <tr>
            <td>Job Description: </td>
            <td><span style='word-wrap: break-word; max-width: 800px;'><?php echo $row['jd']; ?></span></td>
          </tr>
        </table>
        <br><br>
        <p style="font-size:20px;"><u>This is a copy of your submission for your reference.</u></p>
        <table border="1" class="tableofconfirmation">
          <tr>
            <td><b>Application Date:</b></td>
            <td><span><?php echo $updated_datetime; ?></span></td>
          </tr>
          <tr>
            <td><b>Full name:</b></td>
            <td><span style='word-wrap: break-word; max-width: 800px;'><?php echo $_SESSION['fullname']; ?></span></td>
          </tr>
          <tr>
            <td><b>Email:</b></td>
            <td><span style='word-wrap: break-word; max-width: 800px;'><?php echo $email; ?></span></td>
          </tr>
          <tr>
            <td><b>Applicant CV/Resume:</b></td>
            <td><span style='word-wrap: break-word; max-width: 800px;'><?php echo $userdetails; ?></span></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
<footer>
  <div style="margin: 50px 44%; width: 5%; min-width:150px;">
    <a href=main.php>Return to Jobs Page</a>
  </div>
</footer>
<?php $conn->close(); ?>
</body>
</html>
