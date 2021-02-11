<?php
$servername = "localhost";
$phpusername = "root";
$phppassword = "";
$databasename = "userbase";
$usertable = "usertable";//Table containing all account data
$appliedlist = "appliedjobs";//Table containing jobs that user applied for
$joblist = "jobtable";//Table containing jobs that employer provide
// Create connection
$conn = mysqli_connect($servername, $phpusername, $phppassword);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE $databasename";
$db_selected = mysqli_select_db($conn, $databasename);
if (!$db_selected) {
    if(mysqli_query($conn, $sql)){
      echo "Database created successfully.<br>";
    }
    else {
      echo "Error creating database: " . mysqli_error($conn);
    }
} else {
  echo "Database exists.<br>";
}

$conn = mysqli_connect($servername, $phpusername, $phppassword, $databasename);

// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS $usertable (
username VARCHAR(30) NOT NULL PRIMARY KEY,
password VARCHAR(80) NOT NULL,
email VARCHAR(50) NOT NULL,
fullname VARCHAR(50) NOT NULL,
is_employer BOOLEAN,
updated_datetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
//Add ENGINE=MyISAM at the end of the bracket of $sql to change engine to MyISAM
//$sql = "CREATE TABLE IF NOT EXISTS $usertable (
// username VARCHAR(30) NOT NULL PRIMARY KEY,
// password VARCHAR(80) NOT NULL,
// email VARCHAR(50) NOT NULL,
// fullname VARCHAR(50) NOT NULL,
// is_employer BOOLEAN,
// updated_datetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
// )ENGINE=MyISAM";
if (mysqli_query($conn, $sql)) {
    echo "Table users created successfully.<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

$sql = "CREATE TABLE IF NOT EXISTS $appliedlist (
username VARCHAR(30) NOT NULL,
jobid INT(10) NOT NULL,
email VARCHAR(50) NOT NULL,
userdetails TEXT(5000) NOT NULL,
PRIMARY KEY (username,jobid),
FULLTEXT KEY (userdetails),
updated_datetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $sql)) {
    echo "Table appliedlist created successfully.<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

$sql = "CREATE TABLE IF NOT EXISTS $joblist (
jobid INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(30) NOT NULL,
company VARCHAR(50) NOT NULL,
email VARCHAR(50) NOT NULL,
position VARCHAR(50) NOT NULL,
salary INT(10) NOT NULL,
jd TEXT(5000) NOT NULL,
FULLTEXT KEY (jd, position, company),
updated_datetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $sql)) {
    echo "Table joblist created successfully.<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
/* NOT NULL - Each row must contain a value for that column, null values are not allowed
DEFAULT value - Set a default value that is added when no other value is passed
UNSIGNED - Used for number types, limits the stored data to positive numbers and zero
AUTO INCREMENT - MySQL automatically increases the value of the field by 1 each time a new record is added
PRIMARY KEY - Used to uniquely identify the rows in a table. The column with PRIMARY KEY setting is often an ID number, and is often used with AUTO_INCREMENT
*/

/* The SQL query must be quoted in PHP
String values inside the SQL query must be quoted
Numeric values must not be quoted
The word NULL must not be quoted*/

$temp = 'billgates';
$temp = md5($temp);
$sql = "INSERT IGNORE INTO $usertable (username, password, email, fullname, is_employer)
VALUES ('billgates', '$temp', 'billgates@microsoft.com', 'Bill Gates', true);";
$temp = 'warrenbuffett';
$temp = md5($temp);
$sql .= "INSERT IGNORE INTO $usertable (username, password, email, fullname, is_employer)
VALUES ('warrenbuffett', '$temp', 'warrenbuffett@money.com', 'Warren Buffet', true);";
$temp = 'ee4717';
$temp = md5($temp);
$sql .= "INSERT IGNORE INTO $usertable (username, password, email, fullname, is_employer)
VALUES ('ee4717', '$temp', 'f33ee@localhost', 'EE4717', true);";
$temp = 'kenzi';
$temp = md5($temp);
$sql .= "INSERT IGNORE INTO $usertable (username, password, email, fullname, is_employer)
VALUES ('kenzi', '$temp', 'kenzi@ntu.com', 'Ken Zi', false);";
$temp = 'serang';
$temp = md5($temp);
$sql .= "INSERT IGNORE INTO $usertable (username, password, email, fullname, is_employer)
VALUES ('serang', '$temp', 'serang@ntu.com', 'Sera Ng', false);";
$temp = 'f33ee';
$temp = md5($temp);
$sql .= "INSERT IGNORE INTO $usertable (username, password, email, fullname, is_employer)
VALUES ('f33ee', '$temp', 'f33ee@localhost', 'f33ee', false);";
$temp = 'root';
$temp = md5($temp);
$sql .= "INSERT IGNORE INTO $usertable (username, password, email, fullname, is_employer)
VALUES ('root', '$temp', 'root@localhost', 'root', true);";

if ($conn->multi_query($sql))
{
  echo "New users created successfully.<br>";
  do
  {
    if ($result = $conn->store_result())
    {
      $result->free();
    }
  } while($conn->next_result());
}
else
{
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
$sql = '';

$temp = "Facilitates, coaches, and provides leadership and resources for a team consisting of an average of 20 Chat Team Members.
 Provides technical support, advice, and experience and enables the team with regards to designing, developing, and deploying billing service strategies, processes, and work flow.
 Responsible for the efficient handling of inbound customer inquiries relating to their ADT invoice.<br><br>
Education/Certification:<br>
High school diploma or equivalent required. Two year degree in business, liberal arts or other related program is preferred.<br><br>
Experience:<br>
Two (2) years experience in a customer service related position<br>
Two (2) years experience in a supervisory capacity, preferably in a service environment managing non-exempt level employees.<br><br>
Skills:<br>
Managerial and excellent communication and interpersonal skills required<br>
Must be PC proficient<br>
Must have understanding of call center dynamics and key measurements<br>
Some knowledge of telephony capabilities CMS, CenterView, Auto-dialers helpful.";
$sql .= "INSERT IGNORE INTO $joblist (username, company, email, position, salary, jd, updated_datetime)
VALUES ('billgates', 'Microsoft', 'billgates@microsoft.com', 'Manager', 6000, '$temp', '2020-10-18 15:23:01');";

$temp = "Your responsibilities include the following:<br>
Analyze electrical schematics, one-line diagrams, piping and instrumentation diagrams<br>
Evaluate electrical component design using available manufacturer information including datasheets<br>
Identify failure modes and mechanisms of electrical components<br>
Develop test plans and acceptance criteria for components to assure critical functions<br>
Complete test of electrical components using electrical lab equipment<br>
Prepare, document and present technical data to internal and external customers<br>
Identify, prioritize and drive process improvement opportunities that contribute to product quality and minimize cost of quality<br><br>
Qualifications/Requirements :<br>
Bachelors Degree in Electrical Engineering from and accredited college or university<br><br>
Skills:<br>
Experience in electrical product design and manufacturing.<br>
Experience in electrical protection schemes.<br>
Internship experience in Electrical Engineering or Science-based field.<br>
Experience with industry software tools e.g. (ETAP, Smart Plant Instrumentation/Electrical, National Instruments LabVIEW, etc).";
$sql .= "INSERT IGNORE INTO $joblist (username, company, email, position, salary, jd, updated_datetime)
VALUES ('billgates', 'Microsoft', 'billgates@microsoft.com', 'Engineer', 4000, '$temp', '2020-10-23 09:52:25');";

$temp = "Your day-to-day responsibilities will include:<br>
Manage, facilitate, and innovate on teamwide programs and processes to cultivate employee learning and growth<br>
Develop and drive the execution and iteration of our teamwide employee development processes<br>
Oversee the administration of our promotion and compensation philosophies and policies to ensure we fairly and effectively reward, motivate and retain our top talent<br>
Develop, implement, and refine programs and initiatives to improve the capacity and effectiveness of managers <br><br>
Preferred qualifications:<br>
Strong understanding of evidence-based and human-centered people operations principles and practices to drive employee engagement and effectiveness<br>
Ability to create and operationalize new, organization-wide systems and processes for fast-scaling organizations<br>
Experience working with data to drive people driven decisions<br>
Demonstrated passion for our organizational mission and impact<br>
Outstanding verbal and written communication skills";
$sql .= "INSERT IGNORE INTO $joblist (username, company, email, position, salary, jd, updated_datetime)
VALUES ('billgates', 'Microsoft', 'billgates@microsoft.com', 'HR Associate', 3000, '$temp', '2020-11-03 08:11:42');";

$temp = "How you can make an impact:<br>
Take part in key learning opportunities across multiple functional areas of the People team including recruiting onboarding, talent development, employee surveys, and organizational strategy support projects<br>
Assist with a variety of People team processes and administration<br>
Help manage the onboarding process for our local and global employees as well as contractors<br>
Work in our applicant tracking system to execute our recruiting and hiring activities<br>
Help coordinate interview schedules for applicants and stakeholders<br>
Partner with People team stakeholders on current projects<br><br>
What You Bring to the Table:<br>
Current college student (or recent graduate) working towards a Bachelors Degree (preferably with a focus in Human Resources, Business, Communications, I/O Psychology, or other related field)<br>
Familiar with Gmail, Google Docs, Google Sheets, and Google Slides<br>
Excellent verbal, written and interpersonal communication skills<br>
Fluency in English is a must<br>
Positive, self-motivated individual with high level of enthusiasm and willingness to learn<br>
This being a remote role, the candidate should be extremely organized and can open to learning both from others as well as independently";
$sql .= "INSERT IGNORE INTO $joblist (username, company, email, position, salary, jd, updated_datetime)
VALUES ('billgates', 'Microsoft', 'billgates@microsoft.com', 'Intern', 1500, '$temp', '2020-10-12 12:03:10');";

$temp = "Duties will include accounting, auditing and tax preparation under the supervision of staff and management.<br><br>
Candidates will possess the following qualities:<br>
BS in Accounting<br>
Good communication skills and client service ability<br>
Drive to continually develop knowledge base and expertise<br>
Strong attention to detail, highly ethical, self-directed, and results-oriented<br>
Organized, friendly and team-oriented";
$sql .= "INSERT IGNORE INTO $joblist (username, company, email, position, salary, jd, updated_datetime)
VALUES ('warrenbuffett', 'Berkshire Hathaway', 'warrenbuffett@money.com', 'Accountant', 3500, '$temp', '2020-09-19 16:53:42');";

$temp = "We are currently seeking motivated Accounting Interns.<br>
 We are willing to train the right candidates and offer a very nurturing environment.<br>
 Duties will include accounting, auditing and tax preparation under the supervision of staff and management.<br>
 Flexible and part-time schedules available.<br><br>
 Candidates will possess the following qualities:<br>
Currently pursuing their BS in Accounting<br>
Good communication skills and client service ability<br>
Drive to continually develop knowledge base and expertise<br>
Strong attention to detail, highly ethical, self-directed, and results-oriented<br>
Organized, friendly and team-oriented";
$sql .= "INSERT IGNORE INTO $joblist (username, company, email, position, salary, jd, updated_datetime)
VALUES ('warrenbuffett', 'Berkshire Hathaway', 'warrenbuffett@money.com', 'Intern', 1500, '$temp', '2020-09-07 20:09:35');";

$temp = "We are currently seeking an experienced full-time Accounting & Audit Manager.<br>
 Candidate should have 6+ years of public accounting experience and technical expertise in GAAP, GAAS, GAGAS & DOL audits.<br>
 Individual would be responsible for all phases of audit, compilation and review engagements, and managing staff, reporting directly to partner.<br>
 Individual must possess a Bachelor or Master degree in accounting, valid CPA license and proficiency in use of computers and tax/accounting software.<br><br>
Applicants should possess:<br>
BA/BS/MA in Accounting<br>
Over 6 years of audit experience<br>
CPA License<br>
Technical expertise in GAAP, GAAS, GAGAS & DOL audits<br>
Peer Review/Quality Control compliance, a plus<br>
Excellent communication skills in writing and speech<br>
Strong attention to detail, highly ethical, self-directed and results oriented<br>
Knowledge of accounting software, technology and research tools<br>
Organized, friendly and team oriented";
$sql .= "INSERT IGNORE INTO $joblist (username, company, email, position, salary, jd, updated_datetime)
VALUES ('warrenbuffett', 'Berkshire Hathaway', 'warrenbuffett@money.com', 'Audit Manager', 4500, '$temp', '2020-10-05 10:31:24');";

$temp = "Duties may include answering phones, preparing mail/packages, copying, scanning, typing, data entry, filing, scheduling appointments, and running local errands.<br><br>
Applicants should possess:<br>
Good general computer knowledge<br>
Experience using Microsoft Word (tables preferred) & Microsoft Excel<br>
Learns quickly and follows instructions well<br>
Excellent communication skills in writing and speech<br>
Organized, friendly and team oriented<br>
Strong attention to detail, highly ethical, self-directed, and results-oriented<br>
Flexible schedule preferred";
$sql .= "INSERT IGNORE INTO $joblist (username, company, email, position, salary, jd, updated_datetime)
VALUES ('warrenbuffett', 'Berkshire Hathaway', 'warrenbuffett@money.com', 'Front Desk Receptionist', 2500, '$temp', '2020-10-09 09:52:12');";

$temp = "The successful candidate will use established protocols to analyze acoustic data in order to create a database of individually specific signature whistles of dolphins.<br>
 They will also quality check acoustic files and field notes, digitize analog recordings, and assist with website development and maintenance.<br>
 Work will be entirely computer based and will not involve field work.<br>
Please include a cover letter, CV, and the names and contact information for three references when applying. Target hiring date will be in January 2021.<br><br>
ESSENTIAL FUNCTIONS:<br>
Analysis of dolphin acoustic data using Raven software<br>
Integration of annotated data into Matlab based database<br>
Digitization of analog tapes<br>
Quality checking of digitized tapes and field notes<br>
Website development and maintenance<br><br>
DESIRED EDUCATION & EXPERIENCE:<br>
Bachelor Degree in any science field<br>
Quick learner with high attention to detail<br>
Computer skills, good communication, and critical thinking<br><br>
Highly Desirable:<br>
Experience with software programs Raven and Matlab<br>
Website development and maintenance<br>
Comfortable with acoustic recording hardware<br><br>
Physical Requirements:<br>
Occupational requirements include talking and working around others and/or with others.<br>
 This is a continuously sedentary position working at a computer and will very often require independent work.<br>
 Hearing requirements include the ability to hear and respond to instructions. Other physical tasks include repetitive motion (at the computer), and climbing ladders/stools.";
$sql .= "INSERT IGNORE INTO $joblist (username, company, email, position, salary, jd, updated_datetime)
VALUES ('ee4717', 'Nanyang Technological university', 'f33ee@localhost', 'Research Assistant', 2800, '$temp', '2020-10-28 17:11:52');";

$temp = "Develop, test, implement, and maintain web applications<br>
Diagnose and resolve reported problems and issues<br>
Look for and make recommendations to improve applications and processes<br>
Participate in daily standups and team meetings<br><br>
You should have:<br>
Graduate level knowledge of HTML, CSS, SQL, and PHP or JavaScript<br>
A strong work ethic and a willingness to learn<br>
Good critical thinking and problem solving skills<br>
Excellent written and verbal communication skills<br>
The ability to set goals and meet deadlines";
$sql .= "INSERT IGNORE INTO $joblist (username, company, email, position, salary, jd, updated_datetime)
VALUES ('ee4717', 'Nanyang Technological university', 'f33ee@localhost', 'Web Developer', 3800, '$temp', '2020-10-10 11:52:07');";

$temp = "Applications are invited for the position of Teaching Assistant/Instructor/Lecturer for the Data Literacy Programme (DLP) and several other modules in Data Science and Artificial Intelligence (DS/AI).<br>
 The DS/AI modules and DLP Programme is catered to administrative employees of NTU.<br>
 The duties include facilitation of classroom discussion, learner consultation, supervision of group projects, and design of assessment tasks.<br>
 Successful candidates are expected to commit to two years of full-time service.<br><br>
Qualifications<br>
Bachelor, Master degree or Phd, preferably with work experience in data science or teaching experience.<br><br>
Desirable attributes:<br>
(a) Competency in R Programming.<br>
(b) Competency in Data Visualization Techniques.<br>
(c) Competency in Machine Learning Techniques.<br>
(d) Competence in interpreting and communicating numerical information.<br>
(e) Interest in academic interaction with adults from all disciplines.<br>
(f) Being proactive and reflective about teaching practice.<br>
Please attach CV and all university education transcripts. Only shortlisted applicants will be notified.";
$sql .= "INSERT IGNORE INTO $joblist (username, company, email, position, salary, jd, updated_datetime)
VALUES ('ee4717', 'Nanyang Technological university', 'f33ee@localhost', 'Teaching Assistant', 3200, '$temp', '2020-10-28 16:45:49');";

$temp = "We are seeking a part time lecturer to teach one course, K6312 Information Mining and Analysis.<br>
 The course is offered in the Wee Kim Wee School of Communication and Information MSc in Knowledge Management programme.<br>
 It examines the collection, processing, and utilisation of data ethically and securely to achieve organisational data maturity, and the use of data analytics and machine learning to solve problems in an organisation.<br>
 The preferred candidate should have experience teaching at the graduate level and have knowledge of the topics covered in the course.<br>
 The course format consists of a three-hour weekly session that comprises lectures, tutorials and/or seminar discussions.<br>
 The Instructor will be responsible for leading each three-hour session in addition to syllabus planning, class preparation, marking for assessment and student consultation.<br><br>
Requirements:<br>
Masters degree or higher <br>
Background in data science is strongly preferred<br>
A working knowledge of Python and familiarity with the Python ecosystem <br><br>
Further information about the University and the School can be viewed at the following websites:<br>
NTU : www.ntu.edu.sg<br>
WKWSCI : http://www.wkwsci.ntu.edu.sg/<br>
Only shortlisted candidates will be notified.";
$sql .= "INSERT IGNORE INTO $joblist (username, company, email, position, salary, jd, updated_datetime)
VALUES ('ee4717', 'Nanyang Technological university', 'f33ee@localhost', 'Part-Time Lecturer', 4600, '$temp', '2020-10-30 10:33:52');";

if ($conn->multi_query($sql))
{
  echo "New jobs created successfully.";
  do
  {
    if ($result = $conn->store_result())
    {
      $result->free();
    }
  } while($conn->next_result());

}
else
{
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
