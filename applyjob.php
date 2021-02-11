<?php
include('constants.php');
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
.jd {
  border-color: white;
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
  <div style="width:80%; min-width:850px; height:auto; margin:auto; margin-left:4%;">
    <div id="rightcolumn">
      <a href="main.php"><img src = "JobHunterLogo.gif"  width = 500px height= 100px style="display:block; margin: auto; padding-bottom:30px;"></a>
        <div>
          <?php
            if (!isset($_POST['jobid']))
            {
              echo "Please select a job to apply from the jobs page.<br>";
              $conn->close();
              exit("<a href=main.php>Jobs Page</a><br>");
            }
          ?>
          <table border="1" class="jd">
            <?php
              $username = $_SESSION['valid_user'];
              $query = "SELECT email FROM $usertable where username='$username'";
              $result = $conn->query($query);
              $email = mysqli_fetch_assoc($result)['email'];

              $jobid = $_POST['jobid'];
              $query = "SELECT * FROM $joblist WHERE jobid='$jobid'";
              $result = $conn->query($query);
              while($row = mysqli_fetch_assoc($result))
              {
                echo
                "<tr>
                  <td>
                    Company name
                  </td>
                  <td style='word-wrap: break-word; max-width: 800px;'>"
                    .$row['company'].
                  '</td>
                </tr>
                <tr>
                  <td>
                    Job title
                  </td>
                  <td style="word-wrap: break-word; max-width: 800px;">'
                  .$row['position'].
                  '</td>
                </tr>
                <tr>
                  <td>
                    Indicated Salary
                  </td>
                  <td style="word-wrap: break-word; max-width: 800px;">$'
                  .$row['salary'].
                  '</td>
                </tr>
                <tr>
                  <td>
                    Job Description
                  </td>
                  <td style="word-wrap: break-word; max-width: 800px;">'
                  .$row['jd'].
                  '</td>
                </tr>
                <tr>
                  <td>
                    Date listed
                  </td>
                  <td style="word-wrap: break-word; max-width: 200px;">'
                  .$row['updated_datetime'].
                  '</td>
                </tr>';
              }
            ?>
          </table>
          <br />
        </div>
        <div>
          <form action='confirmjob.php' method=POST>
            Type all relevant infomation for this job you are applying for here:<br /><br />
            Your full name is: <span style="color: white; font-weight: bold;"><?php echo $_SESSION['fullname']; ?></span><br />
            Your email is: <span style="color: white; font-weight: bold;"><?php echo $email; ?></span><br /><br />
            Type in your Resume/CV here:<br />

            <textarea rows="5" cols="100" name="userdetails" id="userdetails" required style="margin:0px; width:510px; height:80px;"
             placeholder="Enter resume here..."></textarea>
            <?php
              echo
              '<input type="hidden" name="jobid" value='.$jobid.' />
              <input type="hidden" name="email" value='.$email.' />';
            ?>
            <br>
            <button type=submit name=applysubmit>Submit</button>
            <button type=reset name=applyreset>Reset</button>
          </form>
        </div>
        <footer>
          <a href=main.php>Return to Jobs Page</a><br>
        </footer>
      </div>
    </div>
    <footer>
      <table border="0" width= "100%" style="color:rgb(10, 156, 246); padding-left:30px; padding-right:30px;">
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
</body>
</html>
