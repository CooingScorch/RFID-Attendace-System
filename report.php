<?php
  include("database.php");
  include("report.html");
  $stmtClass="SELECT DISTINCT className From class left join attendance on class.ClassID=attendance.ClassID where attendance.ClassID is not null";
  $result=null;
  try{
      $result=mysqli_query($conn,$stmtClass);
      while($records = mysqli_fetch_assoc($result)){
        echo"<form action='report.php' method='post'>
        <br><br><label>".$records['className']."</label>
          <input type='hidden' name='className' value='".urlencode($records['className'])."'/>
          <input type='submit' name='generate' value='Generate'/>
        </form>";
      }
    }catch(Exception $e){
    echo"Error: ".$e->getMessage();
  }
  if(isset($_POST['generate'])){
    header("Location:generateReport.php?className=".$_POST['className']);
    die();
  }
?>
