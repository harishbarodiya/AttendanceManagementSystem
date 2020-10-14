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

<div class="row">
<div class="col l4 m5 s12 " style="height:600px;background:#479BA8">
<h5 class="center">Do a Complaint/Request</h5>

<form action="" method="POST">
<table>
    <tr>
        <td>Which regarding :</td>
        <td><select name="reason" required>
                <option value = "" selected disabled>Choose which regarding</option>
                <option value = "" >Reason1</option>
                <option value = "" >Reason2</option>
                <option value = "" >Reason3</option>
                
            </select>
        </td>   
    </tr>
    <tr>
        <td> Choose Course : </td>
        <td>
        <div class="input-field">
            <select name="course" required>
                <option value = "" selected disabled>Choose Course</option>
                <?php
                  $sql = "SELECT study.course_code,course.course_name FROM study INNER JOIN course ON study.course_code=course.course_code where study.enrollment=".$_SESSION['enrollment'];
                  $result=$conn->query($sql);
                  if($result->num_rows > 0){
                      while($row = $result->fetch_assoc()){
                        echo "<option value ='".$row['course_code']."'>".$row['course_code']."-".$row['course_name']."</option>";
                      }
                  } ?>
            </select>
            </div>
        </td>
    </tr>
       <tr>
           <td></td>
            <td>
              <input style="height:35px; width: 200px;background:#B1624D;" type="submit" value="Type complaint" name="typecomplaint" >
            </td>
       </tr>
  </table>
  
  
</form> 

</div>
<div class="col l8 m7 s12" style="height:600px; background:#D6D2D2">
<?php
if(isset($_POST['typecomplaint'])){
$_SESSION['course']=$_POST['course'];
?>
<h5>Type a complaint</h5>
<hr>
<br>
<form action="raise-complaint.php" method="post" enctype="multipart/form-data">
    <div class="input-field">
      <textarea id="textarea" class="materialize-textarea" maxlength="500" style="width:500px;height:100px;" name="complaint"></textarea>
      <label for="textarea">Textarea</label>
    </div>
  Attach a screenshot or a file to help better understand.(optional)
  <div class="file-field input-field">
    <div class="btn">
      <span>File</span>
      <input type="file" name="file">
    </div>
    <div class="file-path-wrapper">
      <input class="file-path validate" type="text" style="width:440px" name="file1">
    </div>
  </div>

  <center>
  <input style="height:40px; width: 200px;background:#B1624D;margin-right:60px" type="submit" value="Submit" name="submitcomp"> 
  </center>
</form>
<?php 
}
?>

<?php
if(isset($_POST['submitcomp'])){
  if ($_FILES["file"]["size"] > 1024000) {
    echo "Sorry, your file is too large.Please upload a file less than 1 mb";
  }
  $imageFileType = strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "pdf" ) {
    echo "Sorry, only JPG, JPEG, PNG & PDF files are allowed.";
  }else{
  $pname ="complaint_file-".$_SESSION['enrollment']."_".$_SESSION['course'].rand(1000,10000);
  #temporary file name to store file
  $tname = $_FILES["file"]["tmp_name"];
 
   #upload directory path
    $uploads_dir = 'complaint_files';
  #TO move the uploaded file to specific location
  move_uploaded_file($tname, $uploads_dir.'/'.$pname);

  #sql query to insert into database
  $sql = "INSERT into complaint VALUES('".$_SESSION['enrollment']."','".$_SESSION['course']."','".$_POST['complaint']."','$pname')";

  if(mysqli_query($conn,$sql)){

  echo "<script>alert('Complaint submitted')</script>";
  unset($_POST['submitcomp']);
  }
  else{
    echo "<script>alert('Error occured')</script>";
    unset($_POST['submitcomp']);
  }
}
}
?>



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