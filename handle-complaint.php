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

<div class="row">
<div class="col l4 m5 s12 " style="height:600px;background:#479BA8">
<h5 class="center">Handle a Complaint/Request</h5>



</div>
<div class="col l8 m7 s12" style="height:600px; background:#D6D2D2">
<hr>
<h5>Complaint(s)</h5>
<hr>
<!-- fetch complaint data from database -->
  <?php
      $t_id=$_SESSION['t_id'];
      $sql = "SELECT student.name,student.enrollment,class.class_name,class.section,course.course_name,complaint.complaint,complaint.file FROM complaint INNER JOIN student ON complaint.enrollment=student.enrollment INNER JOIN course ON course.course_code=complaint.course_regarding INNER JOIN class ON class.class_id=student.class_id INNER JOIN teaches1 ON complaint.course_regarding=teaches1.course_code where teaches1.t_id=$t_id";
      $result=$conn->query($sql);
      if($result->num_rows > 0){
          while($row = $result->fetch_assoc()){
            // echo "<option value ='".$row['course_code']."'>".$row['course_code']."-".$row['course_name']."</option>";
          ?>





<table style="font-size:20px">
   <tr style="border-bottom:0">
     <td style="padding:1px">Student Name : <?=$row['name']?></td>
     <td style="padding:1px">Enrollment no. : <?=$row['enrollment']?></td>
     
   </tr>
   <tr style="border-bottom:0;">
   <td style="padding:1px">Class : <?=$row['class_name']?></td>
   <td style="padding:1px">Section : <?=$row['section']?></td>
   
   </tr>
   <tr style="border-bottom:0;">
    <td style="padding: 1px">Regarding course : <?=$row['course_name']?></td>
    
   </tr>
   <tr  style="border-bottom:0">
    <td colspan='2'>
    
    <div class="input-field">
      <textarea id="textarea" class="materialize-textarea" style="width:500px;height:100px;" readonly name="complaint"><?=$row['complaint']?></textarea>
      <label for="textarea">Message</label>
    </div>
    <?php
    if($row['file']!=null){
    ?>
    <a style="color:#B1624D"  href="complaint_files/<?php echo $row['file'] ?>" target="_blank"><i class="material-icons" style="font-size:30px;">photo</i><u>View atteached file</u></a> |
    <?php
    }
    ?>
    <a style="color:#B1624D"  href="" ><i class="material-icons" style="font-size:30px;">edit</i><u>Reply</u></a> |
    <a style="color:#B1624D"  href="" ><i class="material-icons" style="font-size:30px;">close</i><u>Dismis</u></a>
    </td>
    <td>
    
    </td>  
    
   </tr>




</table>

<hr>
<?php
}
}?>




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