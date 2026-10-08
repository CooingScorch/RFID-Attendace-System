<?php
  include("manageLecturer.html");
  include("database.php");
  $getLecturer = "SELECT LecturerID,Name from lecturer";
  $result=null;
  try{
    $result=mysqli_query($conn,$getLecturer);
  }catch(mysqli_sql_exception){
    echo"Query Unsuccessful";
  }
  echo"<br><br><label> ID".str_repeat("&nbsp;",18)."Name";
  while($records=mysqli_fetch_assoc($result)){
    echo "<br><br><a href='lecturerInfo.php?lecturerID=".$records['LecturerID']."'>".
    $records['LecturerID'].str_repeat("&nbsp;",15).$records['Name']."</a>";
  }
  die();
?>
