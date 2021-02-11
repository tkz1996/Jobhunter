<?php
  $sender = 'f33ee@localhost'; //Put the webserver host email here.

  function withdrawapplication_mail($username, $jobid)
  {
    date_default_timezone_set('Asia/Singapore');
    $datetime = date('d/m/Y, h:i:sa');
    $sender = $GLOBALS['sender'];
    $conn = $GLOBALS['conn'];
    $usertable = $GLOBALS['usertable'];
    $joblist = $GLOBALS['joblist'];

    $query = "SELECT fullname,email FROM $usertable WHERE username='$username'";
    $row = mysqli_fetch_assoc($conn->query($query));
    $email = $row['email'];
    $fullname = $row['fullname'];
    $query = "SELECT company,position FROM $joblist WHERE jobid='$jobid'";
    $row = mysqli_fetch_assoc($conn->query($query));

    $to      = $email;
    $subject = 'Job Application Withdrawal';
    $message =
    "Hi ".$fullname.",\r\n\r\n
    You have withdrawn your application from the following job:\r\n
    Company: ".$row['company']."\r\n
    Position: ".$row['position']."\r\n
    Withdrawal Date: ".$datetime." Asia/Singapore\r\n\r\n
    Thank you for using our service!\r\n\r\n
    Jobhunters Team";
    $headers = 'From: ' .$sender. "\r\n" .
        'Reply-To: '.$sender. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers,'-f'.$sender);
  }

  function withdrawjoblisting_mail($username, $jobdetails,$applicantlist)
  {
    //Job details contains company,position and email
    //Applicantlist contains all emails of users that applied at that job

    date_default_timezone_set('Asia/Singapore');
    $datetime = date('d/m/Y, h:i:sa');

    //Email for employer withdrawing
    $sender = $GLOBALS['sender'];
    $conn = $GLOBALS['conn'];
    $usertable = $GLOBALS['usertable'];
    $joblist = $GLOBALS['joblist'];

    $query = "SELECT fullname,email FROM $usertable WHERE username='$username'";
    $row = mysqli_fetch_assoc($conn->query($query));
    $email = $row['email'];
    $fullname = $row['fullname'];

    if ($email != $jobdetails['email'])
    {
      $email = $email.', '.$jobdetails['email'];
    }

    $to      = $email;
    $subject = 'Job Listing Withdrawal';
    $message =
    "Hi ".$fullname.",\r\n\r\n
    You have withdrawn the following job listing:\r\n
    Company: ".$jobdetails['company']."
    Position: ".$jobdetails['position']."
    Withdrawal Date: ".$datetime." Asia/Singapore\r\n\r\n
    Thank you for using our service!\r\n\r\n
    Jobhunters Team";
    $headers = 'From: ' .$sender. "\r\n" .
        'Reply-To: '.$sender. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers,'-f'.$sender);

    //Email for all users that applied for the job
    if ($applicantlist->num_rows>0)
    {
      $email = '';
      while($row=mysqli_fetch_assoc($applicantlist))
      {
        $email .= $row['email'].", ";
      }
      $email = substr($email, 0, -2);

      $to      = $email;
      $subject = 'Job Listing Withdrawal';
      $message =
      "Hi User,\r\n\r\n
      The following job listing have been withdrawn:\r\n
      Company: ".$jobdetails['company']."\r\n
      Position: ".$jobdetails['position']."\r\n
      Withdrawal Date: ".$datetime." Asia/Singapore\r\n\r\n
      Please take note that the position is no longer accepting applicants.\r\n
      Thank you for using our service!\r\n\r\n
      Jobhunters Team";
      $headers = 'From: ' .$sender. "\r\n" .
          'Reply-To: '.$sender. "\r\n" .
          'X-Mailer: PHP/' . phpversion();

      mail($to, $subject, $message, $headers,'-f'.$sender);
    }
  }

  function acceptapplicant_mail($employeeuser, $employeruser, $jobid, $applicant_details)
  {
    date_default_timezone_set('Asia/Singapore');
    $datetime = date('d/m/Y, h:i:sa');

    $sender = $GLOBALS['sender'];
    $conn = $GLOBALS['conn'];
    $usertable = $GLOBALS['usertable'];
    $joblist = $GLOBALS['joblist'];
    $appliedlist = $GLOBALS['appliedlist'];

    //Email sent to accepted applicant
    $query = "SELECT company,email,position,salary,jd FROM $joblist WHERE jobid='$jobid'";
    $row = mysqli_fetch_assoc($conn->query($query));

    $query = "SELECT fullname,email FROM $usertable WHERE username='$employeeuser'";
    $temp = mysqli_fetch_assoc($conn->query($query));
    $employeeemail = $temp['email'];
    $employeefullname = $temp['fullname'];

    $to      = $employeeemail;
    $subject = 'Job Application Acceptance';
    $message =
    "Hi ".$employeefullname.",\r\n\r\n
    Congratulations! You have been accepted for the following job application:\r\n
    Company: ".$row['company']."\r\n
    Email: ".$row['email']."\r\n
    Position: ".$row['position']."\r\n
    Salary: ".$row['salary']."\r\n
    Job Description: ".$row['jd']."\r\n
    Acceptance Date: ".$datetime." Asia/Singapore\r\n\r\n
    Please contact the company with the given email to begin your hiring process!\r\n
    Thank you for using our service!\r\n\r\n
    Jobhunters Team
    ";
    $headers = 'From: ' .$sender. "\r\n" .
        'Reply-To: '.$sender. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers,'-f'.$sender);

    //Email sent to employer that accepted applicant
    $query = "SELECT fullname,email FROM $usertable WHERE username='$employeruser'";
    $temp = mysqli_fetch_assoc($conn->query($query));
    $employeremail = $temp['email'];
    $employerfullname = $temp['fullname'];

    $to      = $employeremail;
    $subject = 'Job Application Acceptance';
    $message =
    "Hi ".$employerfullname.",\r\n\r\n
    You have accepted an applicant for the job you have listed.\r\n\r\n
    The job you listed is as follows:\r\n
    Company: ".$row['company']."\r\n
    Email: ".$row['email']."\r\n
    Position: ".$row['position']."\r\n
    Salary: ".$row['salary']."\r\n
    Job Description: ".$row['jd']."\r\n\r\n
    The following are the applicants details:\r\n\r\n
    Fullname: ".$employeefullname."\r\n
    Email: ".$employeeemail."\r\n
    Applicant's Resume/CV: ".$applicant_details."\r\n\r\n
    Acceptance Date: ".$datetime." Asia/Singapore\r\n\r\n
    Please contact the applicant using the above mentioned email to begin the hiring process!\r\n
    Thank you for using our service!\r\n\r\n
    Jobhunters Team
    ";
    $headers = 'From: ' .$sender. "\r\n" .
        'Reply-To: '.$sender. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers,'-f'.$sender);
  }

  function applyjob_mail($username, $jobid, $resume)
  {
    date_default_timezone_set('Asia/Singapore');
    $datetime = date('d/m/Y, h:i:sa');
    $sender = $GLOBALS['sender'];
    $conn = $GLOBALS['conn'];
    $usertable = $GLOBALS['usertable'];
    $joblist = $GLOBALS['joblist'];

    //Email sent to applicant that applied for the job
    $query = "SELECT fullname,email FROM $usertable WHERE username='$username'";
    $temp = mysqli_fetch_assoc($conn->query($query));
    $email = $temp['email'];
    $fullname = $temp['fullname'];

    $query = "SELECT company,email,position,salary,jd FROM $joblist WHERE jobid='$jobid'";
    $row = mysqli_fetch_assoc($conn->query($query));

    $to      = $email;
    $subject = 'Job Application for '.$row['position'];
    $message =
    "Hi ".$fullname.",\r\n\r\n
    You have submitted an application for the following job:\r\n\r\n
    Company: ".$row['company']."\r\n
    Email: ".$row['email']."\r\n
    Position: ".$row['position']."\r\n
    Salary: ".$row['salary']."\r\n
    Job Description: ".$row['jd']."\r\n\r\n
    Here is a copy of your application details:\r\n\r\n
    Fullname: ".$fullname."\r\n
    Email: ".$email."\r\n
    Resume/CV: ".$resume."\r\n\r\n
    Application Date: ".$datetime." Asia/Singapore\r\n\r\n
    We wish you all the best in your application. If there are any updates, an email will be sent to notify you.\r\n
    Thank you for using our service!\r\n\r\n
    Jobhunters Team
    ";
    $headers = 'From: ' .$sender. "\r\n" .
        'Reply-To: '.$sender. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers,'-f'.$sender);
  }

  function accountchange_mail($username)
  {
    date_default_timezone_set('Asia/Singapore');
    $datetime = date('d/m/Y, h:i:sa');
    $sender = $GLOBALS['sender'];
    $conn = $GLOBALS['conn'];
    $usertable = $GLOBALS['usertable'];

    //Email sent to user that changed account details
    $query = "SELECT fullname,email FROM $usertable WHERE username='$username'";
    $temp = mysqli_fetch_assoc($conn->query($query));
    $email = $temp['email'];
    $fullname = $temp['fullname'];

    $to      = $email;
    $subject = 'Change of Account Details';
    $message =
    "Hi ".$fullname.",\r\n\r\n
    This is a notify you that your account details has been changed at ".$datetime."\r\n
    If you did not do this, please change your password immediately.\r\n\r\n
    Jobhunters Team
    ";
    $headers = 'From: ' .$sender. "\r\n" .
        'Reply-To: '.$sender. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers,'-f'.$sender);
  }

  function accountreset_mail($username, $newpassword)
  {
    date_default_timezone_set('Asia/Singapore');
    $datetime = date('d/m/Y, h:i:sa');
    $sender = $GLOBALS['sender'];
    $conn = $GLOBALS['conn'];
    $usertable = $GLOBALS['usertable'];

    //Email sent to user that changed account details
    $query = "SELECT fullname,email FROM $usertable WHERE username='$username'";
    $temp = mysqli_fetch_assoc($conn->query($query));
    $email = $temp['email'];
    $fullname = $temp['fullname'];

    $to      = $email;
    $subject = 'Account Reset for '.$username;
    $message =
    "Hi ".$fullname.",\r\n\r\n
    This is a notify you that your account have been reset at ".$datetime."\r\n\r\n
    The new password is:\r\n
    ".$newpassword."\r\n\r\n
    Please keep a copy of this password and change to a new one as soon as possible.\r\n
    If you did not do this, please change your password immediately.\r\n\r\n
    Jobhunters Team
    ";
    $headers = 'From: ' .$sender. "\r\n" .
        'Reply-To: '.$sender. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers,'-f'.$sender);
  }

  function joblisting_mail($username, $jobid)
  {
    //Job details contains company,position and email
    //Applicantlist contains all emails of users that applied at that job

    date_default_timezone_set('Asia/Singapore');
    $datetime = date('d/m/Y, h:i:sa');

    //Email for employer withdrawing
    $sender = $GLOBALS['sender'];
    $conn = $GLOBALS['conn'];
    $usertable = $GLOBALS['usertable'];
    $joblist = $GLOBALS['joblist'];

    $query = "SELECT fullname,email FROM $usertable WHERE username='$username'";
    $row = mysqli_fetch_assoc($conn->query($query));
    $email = $row['email'];
    $fullname = $row['fullname'];

    $query = "SELECT * FROM $joblist WHERE jobid='$jobid'";
    $row = mysqli_fetch_assoc($conn->query($query));

    $to      = $email;
    $subject = $row['position'].' at '.$row['company'].' Successfully Listed';
    $message =
    "Hi ".$fullname.",\r\n\r\n
    You have successfully listed the following job:\r\n
    Company: ".$row['company']."\r\n
    Email: ".$row['email']."\r\n
    Position: ".$row['position']."\r\n
    Salary: ".$row['salary']."\r\n
    Job Description: ".$row['jd']."\r\n
    Listing Date: ".$datetime." Asia/Singapore\r\n\r\n
    We hope you find the applicant you are looking for.\r\n
    Thank you for using our service!\r\n\r\n
    Jobhunters Team";
    $headers = 'From: ' .$sender. "\r\n" .
        'Reply-To: '.$sender. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers,'-f'.$sender);
  }

  function accountcreated_mail($username)
  {
    date_default_timezone_set('Asia/Singapore');
    $datetime = date('d/m/Y, h:i:sa');
    $sender = $GLOBALS['sender'];
    $conn = $GLOBALS['conn'];
    $usertable = $GLOBALS['usertable'];

    //Email sent to user that changed account details
    $query = "SELECT * FROM $usertable WHERE username='$username'";
    $temp = mysqli_fetch_assoc($conn->query($query));
    $email = $temp['email'];
    $fullname = $temp['fullname'];
    $is_employer = ' NOT';
    if ($temp['is_employer'])
    {
      $is_employer = '';
    }

    $to      = $email;
    $subject = 'Account Created for '.$username;
    $message =
    "Hi ".$fullname.",\r\n\r\n
    This is a notify you that your account have been created at ".$datetime."\r\n\r\n
    The account username is:\r\n
    ".$username."\r\n
    The account email is:\r\n
    ".$email."\r\n
    The account fullname is:\r\n
    ".$fullname."\r\n
    You have indicated that you are".$is_employer." an employer.\r\n\r\n
    Please keep a copy of your account details should you need it in the future.\r\n
    The jobhunter team warmly welcomes you to our platform and hope you have a useful time here.\r\n
    Please contact us if you have any queries about our services.\r\n\r\n
    Jobhunters Team
    ";
    $headers = 'From: ' .$sender. "\r\n" .
        'Reply-To: '.$sender. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers,'-f'.$sender);
  }
?>
