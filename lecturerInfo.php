<?php
  session_start();
  $lecturerID=$_GET['lecturerID'];
  include("database.php");
  include("lecturerInfo.html");
  $selectStmt="SELECT * from lecturer
         left join class on lecturer.LecturerID=class.LecturerID where lecturer.LecturerID=?";
  $delStmt="DELETE FROM lecturer where LecturerID=?";
  $funct=null;
  $selectResult=null;
  $count=0;
  $classes=array();
  try{
        $selectResult=$conn->execute_query($selectStmt,[$lecturerID]);
        while($lecturerInfo=$selectResult->fetch_assoc()){
        if($count==0){
          echo "Name : ".$lecturerInfo['Name'].
               "<br><br>ID : ".$lecturerID."<br>Email : ".$lecturerInfo['LecturerEmail'].
               "<br>Lecturer Password : ".$lecturerInfo['LecturerPassword'];
          $_SESSION['lecturerID']=$lecturerID;
          echo "<br><br>Class :<br>";
          $count++;
        }
        if ($lecturerInfo['ClassName']!=null && !in_array($lecturerInfo['ClassName'],$classes)) {
            $classes[] = $lecturerInfo['ClassName'];
          }
        }
        foreach ($classes as $className)
        echo $className."<br>";
        echo "<form action='lecturerInfo.php?lecturerID=".$lecturerID."' method='post'>
              <br><input type='submit' name='edit' value='EDIT'>
              </form>";
        echo "<form action='lecturerInfo.php?lecturerID=".$lecturerID."' method='post'>
              <input type='submit' name='delete' value='DELETE'>
              </form>";
      }catch(Exception $e){
          echo"Error: ".$e->getMessage();
      }

      if(isset($_POST['cancel'])){
        die();
      }
      else if(isset($_POST['confirm'])){
        try{
            $result=$conn->execute_query($delStmt,[$lecturerID]);
            header('Location:manageLecturer.php');
            die();
        }catch(Exception $e){
            echo"Error: ".$e->getMessage();
        }
      }
      
      if(isset($_POST['edit'])){
          header('Location:editLecturer.php');
      }else if(isset($_POST['delete'])){
        echo "<div class='optionBtn'><form action='lecturerInfo.php?lecturerID=".$lecturerID."' method='post'>
              <br><input type='submit' name='cancel' value='CANCEL'>
              </div>
              </form>";
        echo "<div class='optionBtn'><form action='lecturerInfo.php?lecturerID=".$lecturerID."' method='post'>
              <br><input class='confirmDel'type='submit' name='confirm' value='CONFIRM'>
              </div>
              </form>";
    }


?>
