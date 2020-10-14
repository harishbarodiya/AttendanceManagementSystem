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
<div class="col l4 m4 s12 " style="height:600px;background:#479BA8">
<h4 class="center">Take Attendance</h4>
<form action="entry-attendance.php" method="POST">
<table>
    <tr>
        <td>Date :</td>
        <td> <input type="date" name="date" value="<?php print(date("Y-m-d"));?>"></td>
    </tr>
    <tr>
     <td>Course :</td>
        <td>
            <select name="course" id="course" required>
                <option value = "" disabled selected>Select Course</option>
                <?php
                $sql = "SELECT course.course_name,course.course_code FROM course INNER JOIN teaches1 ON teaches1.course_code = course.course_code where teaches1.t_id=".$_SESSION['t_id'];
                $result=$conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        
                       echo "<option value=".$row['course_code'].">".$row['course_code']."-".$row['course_name']."</option>";
                       $course_code=$row['course_code'];
                    }
                }
                ?>
             
            </select>
        </td>
    </tr>
    <tr>
        <td>Lecture :</td>
        <td>
            <select name="lecture" id="lecture" required>
                <option value = "" disabled selected>Select Lecture Slot</option>
                <option value = "1st 08:30-09:30">1st 08:30-09:30</option>
                <option value = "2nd 09:30-10:30">2nd 09:30-10:30</option>
              <option value = "3rd 10:30-11:30">3rd 10:30-11:30</option>
              <option value = "4th 11:30-12:30">4th 11:30-12:30</option>
              <option value = "5th 01:00-02:00">5th 01:00-02:00</option>
            </select>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <input style="height:35px; width: 200px;background:#B1624D;" type="submit" value="Show students" name="show-student">
        </td>
    </tr>
</table>
</form>

</div>
<div class="col l8 m8 s12" style="height:100%; background:#D6D2D2">

<!-- here comes students data on submit data  -->

<?php 
if(isset($_POST['show-student'])){
    
?>

<hr>
<div style="font-size:19px">
<?php
if(isset($_POST['date']))  $_SESSION['date'] = $_POST['date'];
if(isset($_POST['course'])) $_SESSION['course'] = $_POST['course'];
if(isset($_POST['lecture'])) $_SESSION['lecture'] = $_POST['lecture'];
?>
<span style="margin-right:30px;">Date : <?php  echo $_SESSION['date']; ?></span>
<span style="margin-right:30px;">Course : <?php  echo $_SESSION['course']; ?></span>
<span style="margin-right:30px;">Lecture : <?php  echo $_SESSION['lecture']; ?></span>
</div>
<hr>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<table class="striped">
        <thead>
          <tr>
              <th>Enrollment</th>
              <th>Name</th>
              <th>Class</th>
              <th>Section</th>
              <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
          <?php
          $course_code = $_POST['course'];
          $sql = "SELECT student.enrollment, student.name, class.class_name, class.section FROM student INNER JOIN class ON class.class_id=student.class_id INNER JOIN teaches1 ON teaches1.class_id=student.class_id WHERE teaches1.t_id='".$_SESSION['t_id']."' AND teaches1.course_code='$course_code' ORDER BY student.enrollment";
        //   $sql = "SELECT student.enrollment, student.name,class.class_name,class.section FROM student INNER JOIN study ON student.enrollment=study.enrollment INNER JOIN class ON class.class_id=student.class_id where study.course_code='$course_code' ORDER BY student.enrollment";
          $result=$conn->query($sql);
          if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
          $alreadyP="unchecked";
          $alreadyPVal="absent";
           ?>
                <tr>
                    <td><?php echo $row['enrollment'];?></td>
                    <td><?php echo $row['name'];?></td>
                    <td><?php echo $row['class_name'];?></td>
                    <td><?php echo $row['section'];?></td>
                    <td>
                        <?php
                            $sql1 = "SELECT status FROM attendance_status where date='".$_SESSION['date']."' and period='".$_SESSION['lecture']."' and enrollment='".$row['enrollment']."' and course_code='$course_code'";
                            $result1=$conn->query($sql1);
                            if($result1->num_rows > 0){
                              while($row1 = $result1->fetch_assoc()){
                                  if($row1['status']=='present'){
                                      $alreadyP="checked";
                                      $alreadyPVal="present";
                                  }
                                }
                            }
                        ?>
                        <input type='hidden' name="enrollment[<?=$row['enrollment'];?>]" value="absent"/>
                        <label>
                            <input type='checkbox' name="enrollment[<?=$row['enrollment'];?>]" <?=$alreadyP?> value="<?=$alreadyPVal?>" onchange='uncheckPresentAll(this)'/><span>Present</span>
                        </label>
                            
                    </td>
                </tr>
                <?php 
            }
        }?>
        <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>
            <label>
                <input type="checkbox" onchange="checkAll(this)" name="chk[]" id="present"/>
                <span>All present</span>
            </label>
        </td>
        </tr>
        </tbody>
</table>
<!-- script for check/uncheck all  -->
<script>
  function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
                 checkboxes[i].value="present";
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
                 checkboxes[i].value="absent";
             }
         }
     }
 }
 function uncheckPresentAll(element){
     if (!element.checked) {
         element.value="absent";
      var x = document.getElementById('present');
      x.checked=false;       
     }else{
        element.value="present";
     }
}
    </script>
<input class="right" style="height:40px; width: 200px;background:#B1624D;" type="submit" value="Save Data" name="savedata"> 
<?php
}
?>
<br>
</form>
<?php
if(isset($_POST['savedata'])){
        $enst = $_POST['enrollment'];
        
        $enroll=array_keys($enst);
        $status=array_values($enst);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
         }
         $values="";
        for($i=0; $i<count($enroll); $i++){
            $values.="('".$_SESSION['date']."', '".$_SESSION['lecture']."', '".$_SESSION['course']."','".$enroll[$i]."','".$status[$i]."'),";
        }
        $sql = "REPLACE INTO attendance_status(date, period, course_code,enrollment,status) VALUES".$values;
        $sql = substr($sql,0,-1);
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Data submitted successfully...!!!');</script>";
            unset($_POST['savedata']);
            unset($_SESSION['date']);
            unset($_SESSION['lecture']);
            unset($_SESSION['course']);
        } else {   
            echo "<script>alert('Error occured...!!!');</script>";
        }
        // echo $sql;
    }

?>


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