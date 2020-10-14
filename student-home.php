<?php
session_start();
if(isset($_SESSION['user']) && isset($_SESSION['enrollment'])){ 
// if user is logged in
include "includes/header.php";
include "includes/navbar.php";
include "includes/dbconn.php";
if($conn->connect_error){
    die("Connection failed :".$conn->connect_error);
}
?>
<link rel="stylesheet" href="css/homestyle.css">




<div class="row">
<div class="col l3 m5 s12 " style="height:100%;background:#479BA8">
<h5 class='left'><i>Profile :</i></h5>
</div>
<div class="col l9 m7 s12" style="height:600px; background:#D6D2D2" style="white-space: nowrap;">

 
    <button onclick="window.location.href='status-attendance.php';">Attendance Status</button>
    <button onclick="window.location.href='status-attendance.php?manual=1';">Datewise/Subjectwise Attendance</button>
    <button onclick="window.location.href='raise-complaint.php';">Raise Complaint/Request</button>
    <button onclick="window.location.href='track-complaint.php';">Track Complaint</button>

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