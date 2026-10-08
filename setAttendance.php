<?php
  include("database.php");
  $classStmt="SELECT class.ClassID, class.ClassName,class.DaysofWeek, class.AttendanceStart, class.AttendanceEnd, student.StudentID from class
              right join studentlist on class.ClassID = studentlist.ClassID
              right join student on studentlist.StudentID = student.StudentID
              where student.StudentCardID=?";

  $insertStmt = "INSERT INTO attendance(`Date&Time`, Status,StudentID,ClassID) values(?,?,?,?) ";
  $resultClassInfo=null;
  $classInfo=null;
  $cardID=null;
  $currentDayofWeek=null;
  $status=0;
      if(isset($_POST['cardID'])){
      $cardID=intval($_POST['cardID']);
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
        $resultClassInfo = $conn->execute_query($classStmt,[$cardID]);
        while($classInfo = $resultClassInfo->fetch_assoc()){
          if($classInfo['DaysofWeek'] == $currentDayofWeek
              && $classInfo['AttendanceStart']<=$currentTime
              && $currentTime<$classInfo['AttendanceEnd']){
                try{
                  $resultInsert = $conn->execute_query($insertStmt,[date("Y-m-d H:i:s",strtotime("now")),1,$classInfo['StudentID'],$classInfo['ClassID']]);
                  $status=1;
                  echo"attendance taken";
                  break;
                }catch(Exception $e){
                  echo"Error :".$e->getMessage();
                }
              }
        }
        if ($status==0)
        echo"attendance not taken";
      }catch(Exception $e){
        echo"Error :".$e->getMessage();
      }

    }else{
      echo"ID not Captured";
    }
?>
