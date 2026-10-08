<?php
  include("manageStudent.html");
  include("database.php");
  $getStudent = "SELECT StudentID,StudentName from student";
  $result=null;
  try{
    $result=mysqli_query($conn,$getStudent);
  }catch(mysqli_sql_exception){
    echo"Query Unsuccessful";
  }
  echo"<br><br><label> ID".str_repeat("&nbsp;",18)."Name";
  while($records=mysqli_fetch_assoc($result)){
    echo "<br><br><a href='studentInfo.php?studentID=".$records['StudentID']."'>".
    $records['StudentID'].str_repeat("&nbsp;",15).$records['StudentName']."</a>";
  }
  die();
?>
