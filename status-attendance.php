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
<script>
$(document).ready(function(){
  $("#datewise").click(function(){
    $("#datefrom").show();
    $("#dateto").show();
    $("#course").hide();
    $("#show").show();
  });
});
$(document).ready(function(){
  $("#subjectwise").click(function(){
    $("#course").show();
    $("#datefrom").hide();
    $("#dateto").hide();
    $("#show").show();
  });
});
</script>
<body>

<div class="row">
<div class="col l4 m5 s12 " style="height:100%;background:#479BA8">
<h4 class="center">Attendance Status</h4>

<form action="status-attendance.php" method="POST">
<table>
    <tr>
    <td>
        <label class="black-text">
        <input class="with-gap" name="mode" type="radio" id="datewise" value="datewise"/>
        <span class="dw">Date-Wise</span>
      </label>
        </td>
        <td >
        <label class="black-text">
        <input class="with-gap"  name="mode" type="radio" id="subjectwise" value="subjectwise"/>
        <span>Subject-Wise</span>
      </label>
        </td>
    </tr>
    <tr id="datefrom" hidden>
        <td class="center">From :</td>
        <td><input type="date"  name="datefrom" value="2020-01-01"></td>
    </tr>
    <tr id="dateto" hidden>
        <td class="center">To :</td>
        <td>   <input type="date" name="dateto" value="<?php print(date("Y-m-d"));?>"> </td>   
    </tr>
    <tr id="course" hidden >
        <td> Choose Courses : </td>
        <td>
        <div class="input-field col s12">
            <select name="course[]" multiple="multiple">
                <option value = "all" selected disabled>All Course</option>
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
       <tr id="show" hidden>
           <td></td>
            <td>
              <input style="height:35px; width: 200px;background:#B1624D;" type="submit" value="Show Attendance" name="showattendance" >
            </td>
       </tr>
  </table>
  
  
</form> 

</div>
<div class="col l8 m7 s12" style="height:100%; background:#D6D2D2">
<?php

?>
<!-- There are 3 type of attendances status 
 1.) default
 2.) datewise
 3.) course wise -->

 
<!-- date/subject wise attendance status -->
<?php 
if(isset($_POST['showattendance'])){
    if(isset($_POST['mode'])){ 
      if($_POST['mode']=="datewise"){
        ?>
        <h5>Datewise Attendance</h5>
        <hr>
        <div style="font-size:20px">
        <span style="margin-right:30px;">From Date  : <?php if(isset($_POST['datefrom'])) echo $_POST['datefrom']; ?></span>
        <span style="margin-right:30px;">To Date : <?php if(isset($_POST['dateto'])) echo $_POST['dateto']; ?></span>
        </div>
        <hr>
    <table class="striped">
        <thead>
          <tr>
              <th>Date</th>
              <th>Period</th>
              <th>Course</th>
              <th>Status</th>
          </tr>
        </thead>
        <tbody>
          
          <?php
          $total=0;
          $present=0;
          $sql = "SELECT attendance_status.date, attendance_status.period,course.course_name,attendance_status.status FROM attendance_status INNER JOIN course ON attendance_status.course_code=course.course_code where attendance_status.enrollment=".$_SESSION['enrollment']." and attendance_status.date BETWEEN '".$_POST['datefrom']."' and '".$_POST['dateto']."' ORDER BY attendance_status.date,attendance_status.period";
          $result=$conn->query($sql);
           if($result->num_rows > 0){
             while($row = $result->fetch_assoc()){
               ?>
                 <!-- echo "<tr><td>".$row['date']."</td>"."<td>".$row['period']."</td>"."<td>".$row['course_name']."</td>"."<td>".$row['status']."</td></tr>"; -->
                 <tr <?php if($row['status']=='absent') echo "style=color:red";  else $present++;?>>
                <td><?=$row['date']?></td>
                <td><?=$row['period']?></td>
                <td><?=$row['course_name']?></td>
                <td><?=$row['status']?></td>
              </tr>




          <?php
          $total++;
             }
         }
        ?>
       
        </tbody>
</table>
<!-- report -->

<hr>
        <div style="font-size:20px">
        <span style="margin-right:30px;">Total Classes  : <?= $total; ?></span>
        <span style="margin-right:30px;">Presents : <?= $present; ?></span>
        <span style="margin-right:30px; color:red">Absent : <?= $total-$present; ?></span>
        <span style="margin-right:30px;">Percentage : <?=  number_format(($present*100)/$total,2)."%";?></span>
        </div>
        <hr>


<?php
    }  //datewise selected if closed
    // ----------Subjectwise attendance----------
    else if($_POST['mode']=="subjectwise"){
        ?>
        <h5>Subjectwise Attendance</h5>
        <hr>
        
        <table class="striped">
            <thead>
              <tr>
                  <th>Course</th>
                  <th>Date</th>
                  <th>Period</th>
                  <th>Status</th>
              </tr>
            </thead>
            <tbody>
              
              <?php
              $present=0;
              $total=0;
              if(isset($_POST['course'])){
               
                $course = $_POST['course'];
                $coursestr = "";
                foreach($course as $c){
                  $coursestr .= "'".$c."',";
                }
                $coursestr = substr($coursestr,0,-1);
                $sql = "SELECT course.course_name,attendance_status.date, attendance_status.period,attendance_status.status FROM attendance_status INNER JOIN course ON attendance_status.course_code=course.course_code where attendance_status.enrollment=".$_SESSION['enrollment']." and attendance_status.course_code IN($coursestr) ORDER by attendance_status.course_code, attendance_status.date,attendance_status.period";
              } else{
                $sql = "SELECT course.course_name,attendance_status.date, attendance_status.period,attendance_status.status FROM attendance_status INNER JOIN course ON attendance_status.course_code=course.course_code where attendance_status.enrollment=".$_SESSION['enrollment']." ORDER by attendance_status.course_code, attendance_status.date,attendance_status.period";
              }
              
              $result=$conn->query($sql);
              $crs_name="";
               if($result->num_rows > 0){
                 while($row = $result->fetch_assoc()){
                  if($row['course_name'] !== $crs_name && $crs_name !== ""){  ?>
                  </tbody>
                  </table>
                   <hr>
                    <div style="font-size:20px">
                      <span style="margin-right:50px;">Total Classes  : <?= $total; ?></span>
                      <span style="margin-right:50px;">Presents : <?= $present; ?></span>
                      <span style="margin-right:50px; color:red">Absent : <?= $total-$present; ?></span>
                      <span style="margin-right:50px;">Percentage : <?=  number_format(($present*100)/$total,2)."%";?></span>
                    </div>
                   <hr>
                   <table  class="striped">



                  <?php
                     $present=0;
                    $total=0;

                  }
                   ?>
                     <tr <?php if($row['status']=='absent') echo "style=color:red"; else $present++;?>>
                      <td><?=$row['course_name']?></td>
                <td><?=$row['date']?></td>
                <td><?=$row['period']?></td>
                <td><?=$row['status']?></td>
              </tr>

                 <?php
                 $crs_name=$row['course_name'];
                 $total++;
                 }
             }
            ?>
           <!-- <tr><td colspan=4 style='padding:1px;'><hr>harish<hr></td></tr> -->
           </tbody> 
    </table>
           <hr>
        <div style="font-size:20px">
        <span style="margin-right:50px;">Total Classes  : <?= $total; ?></span>
        <span style="margin-right:50px;">Presents : <?= $present; ?></span>
        <span style="margin-right:50px; color:red">Absent : <?= $total-$present; ?></span>
        <span style="margin-right:50px;">Percentage : <?=  number_format(($present*100)/$total,2)."%";?></span>
        </div>
        <hr>
        </td>
      </tr>

            
    
<?php

    }  //subjectwise selected if closed


  }    //mode select if closed
  else{
    echo"select a mode first";
  }


}
// if subjectwise/datewise attendance button clicked
else if(isset($_GET['manual'])){

}
else{
?>
<!-- default attendance status -->
<div style="overflow-y:scroll;">
<table class="striped">
        <thead>
          <tr>
              <th>Date</th>
              <th>Period</th>
              <th>Course</th>
              <th>Status</th>
          </tr>
        </thead>
        <tbody>
          
          <?php
          $present=0;
          $total=0;
          $sql = "SELECT attendance_status.date, attendance_status.period,course.course_name,attendance_status.status FROM attendance_status INNER JOIN course ON attendance_status.course_code=course.course_code where attendance_status.enrollment=".$_SESSION['enrollment']." ORDER BY attendance_status.date,attendance_status.period";
          $result=$conn->query($sql);
           if($result->num_rows > 0){
             while($row = $result->fetch_assoc()){
               ?>
              <tr <?php if($row['status']=='absent') echo "style=color:red"; else $present++;?>>
                <td><?=$row['date']?></td>
                <td><?=$row['period']?></td>
                <td><?=$row['course_name']?></td>
                <td><?=$row['status']?></td>
              </tr>
            <?php
            $total++;
             }
         } ?>
       
        </tbody>
</table>
</div>
<!-- report -->
<div>
        <hr>
        <div style="font-size:20px">
        <span style="margin-right:30px;">Total Classes  : <?= $total; ?></span>
        <span style="margin-right:30px;">Presents : <?= $present; ?></span>
        <span style="margin-right:30px; color:red">Absent : <?= $total-$present; ?></span>
        <span style="margin-right:30px;">Percentage : <?=  number_format(($present*100)/$total,2)."%";?></span>
        </div>
        <hr>
        </div>
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