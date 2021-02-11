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

$username = $_SESSION['valid_user'];
$is_employer = false;
if ($_SESSION['is_employer'] == true)
{
  $is_employer = true;
}

if (isset($_POST['removeapplication']))
{
  $jobid = $_POST['removeapplication'];
  $removequery = "DELETE FROM $appliedlist WHERE username='$username' AND jobid='$jobid'";

  if ($conn->query($removequery) !== true)
  {
    phpAlert('Error deleting record: '.$conn->error);
  }
  else
  {
    @withdrawapplication_mail($username,$jobid);
    phpAlert('Successfully withdrew application. An email has been sent to you for your notification.');
  }
}

elseif (isset($_POST['removejob']))
{
  $jobid = $_POST['removejob'];
  $query = "SELECT company,email,position FROM $joblist WHERE jobid='$jobid'";
  $jobdetails = mysqli_fetch_assoc($conn->query($query));
  $query = "SELECT email FROM $appliedlist WHERE jobid='$jobid'";
  $applicantlist = $conn->query($query);
  $removequery = "DELETE FROM $joblist WHERE jobid='$jobid'";

  if ($conn->query($removequery) !== true)
  {
    phpAlert('Error deleting record from $joblist: '.$conn->error);
  }
  else
  {
    $removequery = "DELETE FROM $appliedlist WHERE jobid='$jobid'";
    if ($conn->query($removequery) !== true)
    {
      phpAlert('Error deleting record from $appliedlist: '.$conn->error);
    }
    else
    {
      @withdrawjoblisting_mail($username,$jobdetails,$applicantlist);
      phpAlert('Successfully withdrew job listing. An email has been sent to you for your notification.');
    }
  }
}

elseif (isset($_POST['acceptjob']))
{
  $employeeuser = $_POST['employeeuser'];
  $employeruser = $_POST['employeruser'];
  $jobid = $_POST['acceptjob'];
  $query = "SELECT userdetails FROM $appliedlist WHERE jobid='$jobid' AND username='$employeeuser'";
  $applicant_details = mysqli_fetch_assoc($conn->query($query))['userdetails'];

  $removequery = "DELETE FROM $appliedlist WHERE jobid='$jobid' AND username='$employeeuser'";
  if ($conn->query($removequery) !== true)
  {
    phpAlert('Error deleting record from $appliedlist: '.$conn->error);
  }
  else
  {
    @acceptapplicant_mail($employeeuser, $employeruser, $jobid, $applicant_details);
    phpAlert('Successfully accepted applicant. An email has been sent to you for your reference.');
  }
}

$queryapplied = "SELECT jobid, userdetails,updated_datetime FROM $appliedlist WHERE username='$username'";
$resultapplied = $conn->query($queryapplied);

?>
<html>
<style>
<?php include 'jobhunterstyle.css'; ?>

.appliedjobslist{
  margin-left: auto;
  margin-right: auto;
  border-style: solid;
}
.createdjobslist{
  border-style: solid;
  margin: auto;
}
#list_of_created_jobs{
  padding: 20px;
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
    <div>
      <a href="main.php"><img id='top' src = "JobHunterLogo.gif" width = 500px height= auto style="display:block; padding-top: 10px; padding-bottom:30px; margin-left: auto; margin-right: auto;"></a>
      <header style="text-align: center; font-size: 30px; color: white; font-family: Arial; padding-bottom:30px;"><b>Your jobs applied<?php if($is_employer) echo " and listed";?></b></header>
    </div>
    <div id='alljobcontent'>
      <div id='list_of_applied_jobs'>
        <table border="1" class="appliedjobslist" max-width='1920px'>
          <tr class="head">
            <td colspan="8">
              Your applied jobs:
            </td>
          </tr>
          <tr>
            <td>
              Your application infomation
            </td>
            <td>
              Company name
            </td>
            <td>
              Email
            </td>
            <td>
              Job title
            </td>
            <td style='word-wrap: break-word; max-width: 75px;'>
              Indicative Salary
            </td>
            <td>
              Job Description
            </td>
            <td>
              Application Date
            </td>
            <td>
              Withdraw application?
            </td>
          </tr>
          <?php
            if (mysqli_num_rows($resultapplied)<1)
            {
              echo "<tr><td colspan='8' style='text-align:center;'>You have not applied for any jobs. Why not start applying now?</td></tr>";
            }
            while($rowapplied=mysqli_fetch_assoc($resultapplied))
            {
              $jobid = $rowapplied['jobid'];
              $queryjob = "SELECT company, email, position, salary, jd FROM $joblist WHERE jobid='$jobid'";
              $resultjob = $conn->query($queryjob);
              $rowjob = mysqli_fetch_assoc($resultjob);

              echo "
              <tr>
                <td style='word-wrap: break-word; max-width: 400px;'>"
                  .$rowapplied['userdetails'].
                "</td>
                <td style='word-wrap: break-word; max-width: 200px;'>"
                  .$rowjob['company'].
                "</td>
                <td style='word-wrap: break-word; max-width: 200px;'>"
                  .$rowjob['email'].
                "</td>
                <td style='word-wrap: break-word; max-width: 200px;'>"
                  .$rowjob['position'].
                "</td>
                <td style='word-wrap: break-word; max-width: 75px;'>$"
                  .$rowjob['salary'].
                "</td>
                <td style='word-wrap: break-word; max-width: 400px;'>"
                  .$rowjob['jd'].
                "</td>
                <td style='word-wrap: break-word; max-width: 200px;'>"
                  .$rowapplied['updated_datetime'].
                "</td>
                <td style='max-width: 150px;'>
                  <form action='viewappliedjobs.php' method=POST style='text-align:center; margin:10%;'>
                    <button type='submit' name=removeapplication value=".$jobid." />
                    Withdraw</button>
                  </form>
                </td>
              </tr>
              ";
            }
          ?>
        </table><br><br>
      </div>
      <div id='list_of_created_jobs'>
        <?php
          if ($is_employer)
          {
            $queryjob = "SELECT jobid, company, email, position, salary, jd, updated_datetime FROM $joblist WHERE username='$username'";
            $resultjob = $conn->query($queryjob);

            echo '
            <table border="1" class="createdjobslist">
              <tr>
                <td colspan="12">
                  Your job listings created:
                </td>
              </tr>
              <tr>
                <td style="max-width: 100px;">
                  Company name
                </td>
                <td style="max-width: 100px;">
                  Email
                </td>
                <td style="max-width: 100px;">
                  Position
                </td>
                <td style="max-width: 75px;">
                  Salary
                </td>
                <td>
                  Job Description
                </td>
                <td style="max-width: 100px;">
                  Listing Date
                </td>
                <td style="max-width: 100px;">
                  Applicant Fullname
                </td>
                <td>
                  Applicant Email
                </td>
                <td>
                  Applicant Resume/CV
                </td>
                <td style="max-width: 100px;">
                  Application Date
                </td>
                <td>
                  Accept Applicant?
                </td>
                <td>
                  Withdraw job listing?
                </td>
              </tr>';
            if (mysqli_num_rows($resultjob)<1)
            {
              echo "<tr><td colspan='12' style='text-align:center;'>You have not listed any jobs. Why not start listing now?</td></tr>";
            }
            while($rowjob = mysqli_fetch_assoc($resultjob))
            {
              $jobid = $rowjob['jobid'];
              $queryapplied = "SELECT username, userdetails, updated_datetime FROM $appliedlist WHERE jobid='$jobid'";
              $resultapplied = $conn->query($queryapplied);
              $is_withdrawbutton_set = false;

              if (mysqli_num_rows($resultapplied)<1)
                {
                  echo "
                  <tr>
                    <td style='word-wrap: break-word; max-width: 500px;'>"
                      .$rowjob['company'].
                    "</td>
                    <td style='word-wrap: break-word; max-width: 250px;'>"
                      .$rowjob['email'].
                    "</td>
                    <td style='word-wrap: break-word; max-width: 250px;'>"
                      .$rowjob['position'].
                    "</td>
                    <td style='word-wrap: break-word; max-width: 75px;'>$"
                      .$rowjob['salary'].
                    "</td>
                    <td style='word-wrap: break-word; max-width: 500px;'>"
                      .$rowjob['jd'].
                    "</td>
                    <td style='word-wrap: break-word; max-width: 100px;'>"
                      .$rowjob['updated_datetime'].
                    "</td>
                    <td colspan='6'>
                      <form action='viewappliedjobs.php' method=POST style='text-align:center; margin:20px;'>
                        <button type='submit' name=removejob value=".$jobid." />
                        Withdraw</button>
                      </form>
                    </td>
                  </tr>";
                }

              while($rowapplied = mysqli_fetch_assoc($resultapplied))
              {
                $withdrawbutton_num_rows = mysqli_num_rows($resultapplied);
                $username = $rowapplied['username'];
                $queryuser = "SELECT fullname, email FROM $usertable WHERE username='$username'";
                $resultuser = $conn->query($queryuser);
                $rowuser = mysqli_fetch_assoc($resultuser);

                echo "
                <tr>
                  <td style='word-wrap: break-word; max-width: 100px;'>"
                    .$rowjob['company'].
                  "</td>
                  <td style='word-wrap: break-word; max-width: 100px;'>"
                    .$rowjob['email'].
                  "</td>
                  <td style='word-wrap: break-word; max-width: 100px;'>"
                    .$rowjob['position'].
                  "</td>
                  <td style='word-wrap: break-word; max-width: 75px;'>$"
                    .$rowjob['salary'].
                  "</td>
                  <td style='word-wrap: break-word; max-width: 400px;'>"
                    .$rowjob['jd'].
                  "</td>
                  <td style='word-wrap: break-word; max-width: 100px;'>"
                    .$rowjob['updated_datetime'].
                  "</td>
                  <td style='word-wrap: break-word; max-width: 100px;'>"
                    .$rowuser['fullname'].
                  "</td>
                  <td style='word-wrap: break-word; max-width: 100px;'>"
                    .$rowuser['email'].
                  "</td>
                  <td style='word-wrap: break-word; max-width: 400px;'>"
                    .$rowapplied['userdetails'].
                  "</td>
                  <td style='word-wrap: break-word; max-width: 100px;'>"
                    .$rowapplied['updated_datetime'].
                  "</td>
                  <td>
                  <form action='viewappliedjobs.php' method=POST style='text-align:center; margin:10%;'>
                    <button type='submit' name=acceptjob value=".$jobid." />
                    <input type='hidden' name=employeeuser value=".$username." />
                    <input type='hidden' name=employeruser value=".$_SESSION["valid_user"]." />
                    Accept</button>
                  </form>
                  </td>";
                if (!$is_withdrawbutton_set)
                  {
                    $is_withdrawbutton_set = true;
                    echo "
                      <td rowspan='".$withdrawbutton_num_rows."'>
                        <form action='viewappliedjobs.php' method=POST style='text-align:center; margin:10%;'>
                          <button type='submit' name=removejob value=".$jobid." />
                          Withdraw</button>
                        </form>
                      </td>
                    ";
                  }
                echo "</tr>";
              }
            }
          }
        ?>
      </div>
    </div>
</body>
<footer>
  <table border="0" width= "100%" style="color:rgb(10, 156, 246); padding-left:30px; padding-right:30px;">
    <tr style="text-align: center;">
      <td>
        <a href=main.php>Jobs Page</a>
      </td>
      <td>
        <a href='#top'>Back to top</a>
      </td>
    </tr>
    <tr><tr/>
    <tr>
      <td>
        <i>Contact us for any enquiries: <a href="mailto:contact@jobhunter.com">contact@jobhunter.com</a></i>
      </td>
      <td>
        <i>Copyright &copy; 2020 klkz@jobhunter.com</i>
      </td>
      <td>
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
<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the link that opens the modal
var aulink = document.getElementById("aboutus");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
aulink.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
<?php $conn->close(); ?>
</html>
