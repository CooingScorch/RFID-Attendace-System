<?php
  session_start();
  $studentID=$_GET['studentID'];
  include("database.php");
  include("studentInfo.html");
  $selectStmt="SELECT * from student
         left join studentlist on student.StudentID = studentlist.StudentID
         left join class on studentlist.ClassID=class.ClassID where student.StudentID=?";
  $delStmt="DELETE FROM student where StudentID=?";
  $funct=null;
  $selectResult=null;
  $count=0;
  $classes=array();
  try{
        $selectResult=$conn->execute_query($selectStmt,[$studentID]);
        while($studentInfo=$selectResult->fetch_assoc()){
        if($count==0){
          echo "<br>Name : ".$studentInfo['StudentName'].
               "<br><br>ID : ".$studentID."<br>Email : ".$studentInfo['StudentEmail'].
               "<br>Student Card ID : ".$studentInfo['StudentCardID'];
          $_SESSION['studentID']=$studentID;
          echo "<br><br>Class :";
          $count++;
        }
        if ($studentInfo['ClassName']!=null && !in_array($studentInfo['ClassName'],$classes)) {
            $classes[] = $studentInfo['ClassName'];
          }
        }
        foreach ($classes as $className)
        echo "<br>".$className;
        echo "<form action='studentInfo.php?studentID=".$studentID."' method='post'>
              <br><input type='submit' name='edit' value='EDIT'>
              </form>";
        echo "<form action='studentInfo.php?studentID=".$studentID."' method='post'>
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
            $result=$conn->execute_query($delStmt,[$studentID]);
            header('Location:manageStudent.php');
            die();
        }catch(Exception $e){
            echo"Error: ".$e->getMessage();
        }
      }
      if(isset($_POST['edit'])){
          header('Location:editStudent.php');
      }else if(isset($_POST['delete'])){
        echo "<div class='optionBtn'><form action='studentInfo.php?studentID=".$studentID."' method='post'>
              <br><input type='submit' name='cancel' value='CANCEL'>
              </div>
              </form>";
        echo "<div class='optionBtn'><form action='studentInfo.php?studentID=".$studentID."' method='post'>
              <br><input class='confirmDel' type='submit' name='confirm' value='CONFIRM'>
              </div>
              </form>";
    }


?>
