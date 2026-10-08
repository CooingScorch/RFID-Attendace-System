<?php
  include("database.php");
  include("class.html");
  $className = $_GET['className'];
  $statementClass = "SELECT * from class where ClassName=?";
  $statementAttendance;
  $result=null;
  $getClassInfo=null;
  echo "<br>&nbsp;<label>ID".str_repeat("&nbsp;",27)."Class Name".str_repeat("&nbsp;",27)."Day of Week</label>";
  try{
      $result = $conn->execute_query($statementClass,[$className]);
      while($getClassInfo=$result->fetch_assoc()){
        echo "<br><br><a href=\"attendanceList.php?classID=".$getClassInfo['ClassID']."\">".str_repeat("&nbsp;",3)
        .$getClassInfo['ClassID'].str_repeat("&nbsp;",15).$getClassInfo['ClassName'].str_repeat("&nbsp;",15).$getClassInfo['DaysofWeek']."</a>";
    }
  }catch(Exception){
    echo "query not accepted";
  }

?>
