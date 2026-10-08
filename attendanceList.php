<?php
  include('database.php');
  include('attendanceList.html');
  if(isset($_GET['classID'])){
      $classID=$_GET['classID'];
  }elseif (isset($_POST['ClassID'])) {
      $classID=$_POST['ClassID'];
  }

  $stmt_class="SELECT  * from class where classID=?";
  $stmtAttendance_Student = "SELECT attendance.`Date&Time`, attendance.Remark, attendance.Status ,studentlist.StudentID, student.StudentName from studentlist
            inner join student on studentlist.StudentID = student.StudentID
            left join attendance on studentlist.StudentID = attendance.StudentID where studentlist.ClassID=? and attendance.classID=? ";
  $stmtInsertRemark="UPDATE attendance set Status=?,Remark=? where StudentID=?";
  $resultClass=null;
  $resultAttend=null;
  $getListAttend=null;
  $getClassInfo=null;
  $status=null;
  $remark=null;

    $resultClass = $conn->execute_query($stmt_class,[$classID]);
    $resultAttend = $conn->execute_query($stmtAttendance_Student,[$classID,$classID]);
    $getClassInfo=$resultClass->fetch_assoc();
    echo "<br><label>".$getClassInfo['ClassName'].str_repeat("&nbsp;",15).$getClassInfo['DaysofWeek']."</label><br>";
    echo "<br><br><label><b>&nbsp;ID".str_repeat("&nbsp;",15)."Name".str_repeat("&nbsp;",13).
    "Status".str_repeat("&nbsp;",10)."Date&Time".str_repeat("&nbsp;",30)."Remark</b></label>";
    echo "<form action='attendanceList.php' method='POST'>";
  try{
    while($getListAttend=$resultAttend->fetch_assoc()){
      if($getListAttend['Status']==1){
        $status= "&nbsp;&nbsp;✔";
      }else{
        $status="X";
      }
      $studentID=$getListAttend['StudentID'];
      echo "<br><br>".$studentID.str_repeat("&nbsp;",10)."<a href='studentStatus.php?studentID=".$studentID.
      "&className=".$getClassInfo['ClassName']."&classID=".$classID."'>".
      $getListAttend['StudentName']."</a>".str_repeat("&nbsp;",10).$status.str_repeat("&nbsp;",10).$getListAttend['Date&Time'].str_repeat("&nbsp;",10).
      "<input type='text' name='remark[$studentID]' value='".$getListAttend['Remark']."'/>";
    }

    echo "<br><br><input type='submit' value='Save'/>
          <input type='hidden' name='ClassID' value='$classID'/>
          </form>";

  }catch(Exception $e){
    echo "query not processed";
    echo "<br>Error:".$e->getMessage();
  }
  try{
    if(!empty($_POST['remark']))
      foreach($_POST['remark'] as $studentID => $value){
        mysqli_data_seek($resultAttend,0);
        while($getListAttend=$resultAttend->fetch_assoc()){
        if(!empty($value)&& $getListAttend['Status']==0)
        $conn->execute_query($stmtInsertRemark,[1,$value,$studentID]);
      }
    }

  }catch(Exception $e){
    echo "<br>Error:".$e->getMessage();
  }


?>
