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

<body>

<div class="row">
<div class="col l4 m5 s12 " style="height:100%;background:#479BA8">

</div>

<div class="col l8 m7 s12" style="height:100%; background:#D6D2D2">
Coming Soon
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