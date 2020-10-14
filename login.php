<?php
session_start();
include"includes/dbconn.php";
if($conn){
  if(isset($_POST['teacher'])){
    $_SESSION['user']="teacher";             //set user=teacher in session
    $user = $_POST['user'];
    $password = $_POST['password'];
    // escape special character
    $user = mysqli_real_escape_string($conn,$user);
    $password = mysqli_real_escape_string($conn,$password);
    $user = htmlentities($user);
    $password = htmlentities($password);
    
    $query = "SELECT * from teacher where t_id='$user' and password='$password'";
    
    $res = mysqli_query($conn,$query);  
    if(mysqli_num_rows($res) == 1){
      echo "login success";
      $_SESSION['t_id']=$user;             //set teacher id in session
      $_SESSION['home'] = "teacher-home.php";
      header("Location: teacher-home.php");
    }
    else{
      $_SESSION['message'] = "Incorrect id or password";
      header("Location: index.php");
    }
  }  else if(isset($_POST['student'])){
    $_SESSION['user']="student";             //set user=student in session
  
    $user = $_POST['user'];
    $password = $_POST['password'];
    // escape special character
    $user = mysqli_real_escape_string($conn,$user);
    $password = mysqli_real_escape_string($conn,$password);
    $user = htmlentities($user);
    $password = htmlentities($password);
    
    $query = "SELECT * from student where enrollment='$user' and password='$password'";
    
    $res = mysqli_query($conn,$query);  
    if(mysqli_num_rows($res) == 1){
      echo "login success";
      $_SESSION['home'] = "student-home.php";
      header("Location: student-home.php");
      $_SESSION['enrollment']=$user;    
    }
    else{
      $_SESSION['message'] = "Incorrect id or password";
      header("Location: index.php");
    }
  }else{
    header("Location: index.php");
  }
}
else{
  $_SESSION['message'] = "Something went wrong";
  header("Location: index.php");
}
?>