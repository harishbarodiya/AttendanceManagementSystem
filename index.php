<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/materialize.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <title>AMS-login</title>

  <script>
        function student(){
          var btn = document.getElementById("btn");
            btn.style.left="110px";
            var user = document.getElementById("login");
            user.name="student";
          }
        function teacher(){
          var btn = document.getElementById("btn");
            btn.style.left="0";
            var user = document.getElementById("login");
            user.name="teacher";
        }
    
    </script>
</head>
<body>
  
<center><h5>Attendance Management System</h5></center>
<div class="login-box">
<div class="button-box">
      <div id="btn"></div>
      <div  style="white-space:nowrap">
    <button type="button" class="toggle-btn" value="teacher" onclick=teacher()>teacher</button>
    <button type="button" class="toggle-btn" name="student"  onclick=student()>Student</button>
    </div>
</div>

  <span style="color:red">
  <?php if(isset($_SESSION['message'])){
          echo $_SESSION['message'];
          unset($_SESSION['message']);
        }
   ?>
  </span>
<!--  <h1>login here</h1>-->
<form action="login.php" method="post">
  <p> Username </p>
  <input type="text" name="user" placeholder="Enter Username" style="color:white" required autofocus>
  <p>Password</p>
  <input type="password" name="password" placeholder="Enter Password" style="color:white" required>
  <input type="submit" name="teacher" id="login" value="login" size="100">
  <a href="#" >Forget Password</a>
 </form>
 </div>
 
 </body>
 </html>

 <?php
//  include "includes/footer.php";
 ?>