<?php
  include("database.php");
  $stmtClass="SELECT ClassID,DaysofWeek, AttendanceEnd from class";
  $stmtallStudent="SELECT StudentID, ClassID from studentlist where ClassID=?";
  $stmtAttend="SELECT * from attendance right join studentlist on attendance.StudentID = studentlist.StudentID
                where studentlist.ClassID=? and DATE(attendance.`Date&Time`)=CURDATE() or DATE(attendance.`Date&Time`) is null";
  $insertStmt = "INSERT INTO attendance(Status,StudentID,ClassID) values(0,?,?)";
  $resultClass=null;
  $resultAttend=null;
  $currentDayofWeek=null;
  date_default_timezone_set('Asia/Kuala_Lumpur');
  switch(date("w")){
    case 0: $currentDayofWeek="Sunday";break;
    case 1: $currentDayofWeek="Monday";break;
    case 2: $currentDayofWeek="Tuesday";break;
    case 3: $currentDayofWeek="Wednesday";break;
    case 4: $currentDayofWeek="Thursday";break;
    case 5: $currentDayofWeek="Friday";break;
    case 6: $currentDayofWeek="Saturday";break;
  }
  $currentTime= date("H:i:s",strtotime("now"));
  try{
      $classInfo=mysqli_query($conn,$stmtClass);
      while($resultClass=mysqli_fetch_assoc($classInfo)){
        if($resultClass['DaysofWeek']==$currentDayofWeek && $resultClass['AttendanceEnd']<$currentTime){
          try{
                $resultAttend=$conn->execute_query($stmtAttend,[$resultClass['ClassID']]);
                while($getAttendInfo=$resultAttend->fetch_assoc()){
                  if ($getAttendInfo==NULL){
                    try{
                      $resultAllStudent = $conn->execute_query($stmtallStudent,[$resultClass['ClassID']]);
                      while($getAllStudent=$resultAllStudent->fetch_assoc()){
                        $insertAttendance = $conn->execute_query($insertStmt,[$getAllStudent['StudentID'],$resultClass['ClassID']]);
                        echo "Successfully set absent students";
                      }
                    }catch(Exception $e){
                      echo"Error: ".$e->getMessage();
                    }
                  }else if($getAttendInfo['Status']==NULL){
                    try{
                      $insertAttendance = $conn->execute_query($insertStmt,[$getAttendInfo['StudentID'],$resultClass['ClassID']]);
                      echo "Successfully set absent students";
                    }catch(Exception $e){
                      echo"Error: ".$e->getMessage();
                    }
                  }
                }
          }catch(Exception $e){
              echo"Error: ".$e->getMessage();
          }

    }
  }
  }catch(Exception $e){
    echo"Error: ".$e->getMessage();
  }
?>
