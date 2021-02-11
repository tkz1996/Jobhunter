<?php
include('constants.php');
include('emailusers.php');

session_start();
if (!isset($_SESSION['valid_user']))
{
  $conn->close();
  echo "<br><a href=index.html>Home Page</a><br>";
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
        <span style="font-family: serif; font-size: 25px;"><b>Create job listing:<b><br><br></span>
        <form action="createjob.php" method=POST onsubmit="return validateemail(email)">
          Company:<br />
          <input type=text name=company id=company style="width:325px; height:30px;" required><br /><br />
          Email:<br />
          <input type=email name=email id=email style="width:325px; height:30px;" required><br /><br />
          Position:<br />
          <input type=text name=position id=position style="width:325px; height:30px;" required><br /><br />
          Salary:<br />
          <input type=number name=salary id=salary style="width:325px; height:30px;" required min=0><br /><br />
          Requirements:<br />
          <textarea name=jd id=jd required rows="7" cols="90" placeholder="Type your job description here..."></textarea><br /><br />
          <div>
            <p id='notification'></p>
          </div>
          <button type=submit name=jobsubmit>Submit</button>
          <button type=reset name=jobreset>Reset</button>
        </form>
      </div>
    </div>
  </div>
</body>
<footer>
  <div style="margin: 0 44%; width: 5%; min-width:150px;">
    <a href=main.php>Return to Jobs Page</a>
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
<script>
function check_fullname(object)
{
  var name = object.value;
  var regexp =/^(?!\s*$)[a-zA-Z\s]+$/;
  var bool = regexp.test(name);
  if (bool == false)
  {
    alert("Please enter a valid name (only letters and spaces)");
  }
  return bool;
}

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

function validateform(fullname_object, email_object)
{
  return (check_fullname(fullname_object) && validateemail(email_object));
}

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
</html>

<?php
if (isset($_POST['jobsubmit']))
{
  $username = $_SESSION['valid_user'];
  $company = $_POST['company'];
  $email = $_POST['email'];
  $position = $_POST['position'];
  $salary = $_POST['salary'];
  $jd = $_POST['jd'];

  $sql = "INSERT INTO $joblist (username, company, email, position, salary, jd)
  		VALUES ('$username', '$company', '$email', '$position', '$salary', '$jd')";

  $result = $conn->query($sql);
  if (!$result)
  {
    phpAlert('Error creating record: '.$conn->error);
  	echo "<script type='text/javascript'>document.getElementById('notification').innerHTML = 'Failed to create job listing. Please try again.<br><br>';</script>";
  }
  else{
    phpAlert('Job listing successfully created.');
    echo "<script type='text/javascript'>document.getElementById('notification').innerHTML = 'Job listing for <b><u>". $position . "</u></b> at <b><u>".$company."</u></b> successfully registered.<br>An email has been sent to you with the job listing details.';</script>";
    @joblisting_mail($username, mysqli_insert_id($conn));
  }
}
?>
<?php $conn->close(); ?>
