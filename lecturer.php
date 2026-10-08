<?php
  session_start();
  include("database.php");
  include("lecturer.html");
  $ID=null;
  $getClasses = "SELECT DISTINCT ClassName From class WHERE LecturerID=?";
  $result =null;
  $class = null;

  if(isset($_SESSION['UserID'])){
    $ID = $_SESSION['UserID'];
    try{
      $result = $conn->execute_query($getClasses,[$ID]);
      while($class = $result->fetch_assoc()){
        echo "<br><br><a href=\"class.php?className=".$class['ClassName']."\">".$class['ClassName']."</a>";
      }
    }catch(Exception){
      echo "Query not processed";
    }

  }else{
    echo" query statement error";
  }
?>
