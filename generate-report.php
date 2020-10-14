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
<h4 class="center">Generate Report</h4>
<form action="generate-report.php" method="POST">
<table>
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
        <td class="center">From :</td>
        <td><input type="date"  name="datefrom" value="2020-01-01"></td>
    </tr>
    <tr>
        <td class="center">To :</td>
        <td>   <input type="date" name="dateto" value="<?php print(date("Y-m-d"));?>"> </td>   
    </tr>
    <tr>
        <td></td>
        <td>
          <input style="height:35px; width: 200px;background:#B1624D;" type="submit" name="genreport" >
        </td>
    </tr>
</table>
</form>


</div>

<div class="col l8 m7 s12" style="height:100%; background:#D6D2D2">
<?php
if(isset($_POST['genreport'])){
?>
            <h5>Attendance Report</h5>
            <hr>
            <div style="font-size:20px">
            <span style="margin-right:30px;">From Date  : <?php if(isset($_POST['datefrom'])) echo $_POST['datefrom']; ?></span>
            <span style="margin-right:30px;">To Date : <?php if(isset($_POST['dateto'])) echo $_POST['dateto']; ?></span>
            </div>
            <hr>
        <table class="striped">
            <thead>
              <tr>
                  <th>Enrollment</th>
                  <th>Name</th>
                  <th>Classes Held</th>
                  <th>Presents</th>
                  <th>Absents</th>
                  <th>Percentage</th>
              </tr>
            </thead>
            <tbody>
            
          <?php
          $course_code = $_POST['course'];
          $sql = "SELECT COUNT(student.enrollment)FROM student INNER JOIN class ON class.class_id=student.class_id INNER JOIN teaches1 ON teaches1.class_id=student.class_id WHERE teaches1.t_id='".$_SESSION['t_id']."' AND teaches1.course_code='$course_code'";
          $result=$conn->query($sql);
          $noStudent = $result->fetch_assoc()['COUNT(student.enrollment)'];

          $sql = "SELECT COUNT(period)FROM attendance_status WHERE course_code='$course_code' and date BETWEEN '".$_POST['datefrom']."' and '".$_POST['dateto']."'";
          $result=$conn->query($sql);
          $noPeriods = $result->fetch_assoc()['COUNT(period)'];

          $classHeld = $noPeriods/$noStudent;

          $sql = "SELECT student.enrollment, student.name, class.class_name, class.section FROM student INNER JOIN class ON class.class_id=student.class_id INNER JOIN teaches1 ON teaches1.class_id=student.class_id WHERE teaches1.t_id='".$_SESSION['t_id']."' AND teaches1.course_code='$course_code' ORDER BY student.enrollment";
        //   $sql = "SELECT student.enrollment, student.name,class.class_name,class.section FROM student INNER JOIN study ON student.enrollment=study.enrollment INNER JOIN class ON class.class_id=student.class_id where study.course_code='$course_code' ORDER BY student.enrollment";
          $result=$conn->query($sql);
          if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
           ?>
                <tr>
                    <td><?php echo $row['enrollment'];?></td>
                    <td><?php echo $row['name'];?></td>
                    <td><?=$classHeld?></td>
                    <td>
                        <?php
                        $sql = "SELECT COUNT(status)FROM attendance_status WHERE course_code='$course_code' and enrollment='".$row['enrollment']."' and status='present' and date BETWEEN '".$_POST['datefrom']."' and '".$_POST['dateto']."'";
                        $result1=$conn->query($sql);
                        echo $noPresent = $result1->fetch_assoc()['COUNT(status)'];
                        ?>
                    
                    </td>
                    <td><?=$classHeld-$noPresent?></td>
                    <td><?php if($classHeld!=0) echo number_format(($noPresent*100)/$classHeld,2)."%"; else echo 0;?></td>
                </tr>
        
                <?php
            }
        }
                ?>

            </tbody>
            
    </table>
    <?php
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