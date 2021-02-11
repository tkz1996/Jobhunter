<?php
  session_start();

  // store to test if they *were* logged in
  @$old_user = $_SESSION['valid_user'];
  unset($_SESSION['valid_user']);
  unset($_SESSION['fullname']);
  unset($_SESSION['is_employer']);
  session_destroy();
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
  <div id="centercolumn">
    <a href="index.html"><img src = "JobHunterLogo.gif"  width = 500px height= 100px style="display:block; margin: auto; padding-bottom:30px;"></a>
    <div id="logoutportion" style="padding-top: 32px; font-size: 20px;">
      <?php
        if (!empty($old_user))
        {
          echo 'You have logged out. Thank you for using JobHunter, we look forward to seeing you again!<br />';
        }
        else
        {
          // if they weren't logged in but came to this page somehow
          echo 'You have not logged in! Click the link below to return to the login page.<br />';
        }
      ?>
      <a href="index.html">Back to main page</a>
    </div>
  </div>
</body>
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
</html>
