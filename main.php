<?php
include('constants.php');
session_start();

if (isset($_POST['username']) && isset($_POST['password']))
{
  $username = $_POST['username'];
  $password = $_POST['password'];
  $password = md5($password);
  $query = 'select * from '. $usertable
           ." where username='$username'";
// echo "<br>" .$query. "<br>";
  $result = $conn->query($query);
  $is_userexists = false;
  if ($result->num_rows >0 )
  {
    $is_userexists = true;
    while($row = mysqli_fetch_assoc($result))
    {
      if ($row['password'] == $password)
      {
        $_SESSION['valid_user'] = $username;
        $_SESSION['fullname'] = $row['fullname'];
        $_SESSION['is_employer'] = $row['is_employer'];
        break;
      }
    }
  }
}
?>
<html>
<style>
<?php include 'jobhunterstyle.css'; ?>
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
<div style="width:80%; min-width:850px; height:auto; margin:auto; min-height: 800px; ">
  <div id="errorinloggingin" style="font-size: 18px;  color: rgb(10, 156, 246);
       width: 500px;  padding: 10px;  margin: auto; margin-top: 10px;">
    <?php
    if (!isset($_SESSION['valid_user']))
    {
      if (isset($is_userexists) && $is_userexists)
      {
        // if they've tried and failed to log in
        echo "<a href=index.html>Home Page</a><br><br>";
        $conn->close();
        exit('Incorrect password or username. Please try again.<br />');
      }
      else
      {
        // they have not tried to log in yet or have logged out
        echo "<a href=index.html>Home Page</a><br><br>";
        $conn->close();
        exit('Please log in again<br />');
      }
    }
    ?>
  </div>

  <div id = "leftcolumn">
    <table id="tableList">
      <tr id="ProfilePage">
        <div>
          <br>Welcome to JobHunter,
            <?php
              echo "<a href='account.php'>"
              .$_SESSION['fullname'].
              "</a>";
            ?><br>
        </div>
      </tr>
      <tr id="JobsApplied">
        <div>
          <br><a href="viewappliedjobs.php">Click here</a>
           to check the jobs you have applied
            <?php
             if ($_SESSION['is_employer']==true){echo ' or listed';}
            ?><br>
        </div>
      </tr>
      <tr id="LogoutPage">
        <div>
          <br>Long day? <a href="logout.php">Logout</a><br>
        </div>
      </tr>
      <?php
        if ($_SESSION['is_employer']==true)
        {
          echo
          '<tr>
            <div>
              <br>Have an opening in your company? <a href=createjob.php>Post a job listing here</a><br>
            </div>
          </tr>';
        }
      ?><br>
      <form action="main.php" method=GET>
       <tr style="height: 75px;">
         <div class="search" >
           <td style="text-align: center;">
            <input type="text" placeholder="Search jobs" name="search" style="text-align: center; margin-bottom:10px; width: 200px; height: 30px;">
            <button type=submit name=searchsubmit style="text-align: center; width: 100px; height: 30px; margin-bottom:10px;">
              <i style="font-size: 16px;">Search
              </i>
            </button>
            <span><i><br>Please search with at least 1 keyword of 3/4 letters. To get random results, just submit a blank search.</i></span>
           </td>
         </div>
       </tr>
       <tr>
         <td style="text-align: center;">
          <div class="dropdown">
          <button class="dropbtn" id="dropbtn" onclick='return false'>Filter</button>
          <input type='hidden' id=filter name=filter value=''>
            <div class="dropdown-content">
              <a style="font-size: 12px;" onclick="changefilter(' ORDER BY DATE(updated_datetime) DESC, updated_datetime DESC', filter, dropbtn, 'Most Recent')">Sort by Recent</a>
              <a style="font-size: 12px;" onclick="changefilter(' ORDER BY DATE(updated_datetime) ASC, updated_datetime ASC', filter, dropbtn, 'Most Oldest')">Sort by Oldest</a>
            </div>
         </div>
        </td>
       </tr>
      </form>
     </table>
   </div>

   <div id="rightcolumn">
     <a href="main.php"><img src = "JobHunterLogo.gif"  width = 500px height= 100px style="display:block; margin:auto;"></a>

     <div id="content-body">
         <div id="card-stack">
         <?php
           $username = $_SESSION['valid_user'];
           $query = "SELECT * FROM $joblist WHERE username!='$username'";

           if (isset($_GET['searchsubmit']) && $_GET['search']!='')
           {
             $subquery = $_GET['search'];
             $query = "SELECT * FROM $joblist WHERE username!='$username' AND MATCH(jd,position,company) AGAINST('$subquery' IN BOOLEAN MODE)";
           }

           if (!isset($_GET['filter']) || $_GET['filter'] == '')
           {
             $query .= " ORDER BY RAND() LIMIT 10";
           }
           else {
             $query .= $_GET['filter'];
           }

           $result = $conn->query($query);
           if (!$result || mysqli_num_rows($result)<1)
           {
             echo "<span style='color: rgb(10, 156, 246); font-size: 25px;'>There are no jobs that matches your search.</span>";
           }
           else
           {
             while($row = mysqli_fetch_assoc($result))
             {
               echo
               "<form action='applyjob.php' method=POST class='cards' style='cursor: pointer;' >
                 <button type='submit' name='jobid' value= ".$row['jobid']." style='background: #AAAAAA; border: none;
                         margin-left: auto; margin-right:auto; max-width: 300px; word-wrap: break-word;'/>
                   <header>
                     <h1 class='cards-header' style='max-width: 300px; word-wrap: break-word;'>Company: ".$row['company']."</h1>
                     <h2 style='max-width: 300px; word-wrap: break-word;'>Position: ".$row['position']."</h2>
                     <h3 style='max-width: 300px; word-wrap: break-word;'>Salary: $".$row['salary']."</h3>
                     <h4 style='max-width: 300px; word-wrap: break-word;'>Date: ".$row['updated_datetime']."</h4>
                   </header>
                 </button>
               </form>";
             }
           }
         ?>
         </div>
       </div>
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
function changefilter(string, filter, object, objectstring)
{
  filter.value = string;
  object.innerHTML = objectstring;
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
<?php $conn->close(); ?>
</body>
</html>
