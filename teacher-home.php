<?php
session_start();
if(isset($_SESSION['user']) && isset($_SESSION['t_id'])){ 
// if user is logged in
include "includes/header.php";
include "includes/navbar.php";
include "includes/dbconn.php";
if($conn->connect_error){
    die("Connection failed :".$conn->connect_error);
}
?>
<link rel="stylesheet" href="css/homestyle.css">
<body>

<div class="row">
<div class="col l3 m5 s12 " style="height:100%;background:#479BA8">
<h5 class='left'><i>Profile :</i></h5>
</div>
<div class="col l9 m7 s12" style="height:600px; background:#D6D2D2" style="white-space: nowrap;">

 
    <button onclick="window.location.href='entry-attendance.php';">Attendance Entry</button>
    <button onclick="window.location.href='generate-report.php';">Generate Report</button>
    <button onclick="window.location.href='short-attendance-alert.php';">Alert for Short Attendance</button>
    <button onclick="window.location.href='handle-complaint.php';">Handle Complaint/Request</button>

</div>




<?php
include "includes/footer.php";
?>

<?php
   // end of if
}else{
    $_SESSION['message']="Please login first...!!!";
    header("Location: index.php");
    unset($_SESSION['message']);
}
?>