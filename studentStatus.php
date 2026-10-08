<?php
  include('database.php');
  include('studentStatus.html');
  $studentID=$_GET['studentID'];
  $className=$_GET['className'];
  $classID=$_GET['classID'];
  $stmtAttendance_Student = "SELECT attendance.`Date&Time`, attendance.Status,attendance.Remark, studentlist.StudentID, student.StudentName from studentlist
          inner join student on studentlist.StudentID = student.StudentID
          right join attendance on studentlist.StudentID = attendance.StudentID
          inner join class on studentlist.classID = class.classID and studentlist.ClassID = attendance.ClassID
          where studentlist.StudentID=? and class.ClassName=? and studentlist.classID=?";
  $resultName=null;
  $resultAttend=null;
  $attendance=null;
  $status=null;
  $totalClass=0;
  $numbAttended=0;
          try{
            $result=$conn->execute_query("SELECT StudentName from student where StudentID=?",[$studentID])->fetch_assoc();
            echo "<b>".$result['StudentName']."</b>";
            $resultAttend=$conn->execute_query($stmtAttendance_Student,[$studentID,$className,$classID]);
            while($attendance=$resultAttend->fetch_assoc()){
              if($attendance['Status']==1){
                $status= "&nbsp;&nbsp;✔";
                $numbAttended++;
              }else{
                $status="X";
              }
              $totalClass++;
              echo"<br><br><label>".$attendance['Date&Time'].str_repeat("&nbsp;",10).$status;
            }
            if ($numbAttended>0){
              $percentage=$numbAttended/$totalClass*100;
            }else{
              $percentage=0;
            }
            echo"<br><br><label class='percent'>".round($percentage,2)."%";
          }catch(Exception $e){
            echo"Error".$e->getMessage();
          }
?>
