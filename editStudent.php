<?php
session_start();
$studentID=$_SESSION['studentID'];
include("database.php");
include("editStudent.html");
$selectStmt="SELECT * from student
       left join studentlist on student.StudentID = studentlist.StudentID
       left join class on studentlist.ClassID=class.ClassID where student.StudentID=?";
$getClassStmt="SELECT ClassID from class where ClassName=?";
$insertStmt="INSERT INTO studentlist (StudentID,ClassID) values(?,?)";
$updateStmtStudent="UPDATE student set StudentID=?, StudentName=?, StudentEmail=? where StudentCardID=?";
$deleteStmt="DELETE from studentlist where StudentID=? and ClassID=?";
$selectResult=null;
$count=0;
$studentData=array();
$checkClasses=array();
try{
      $selectResult=$conn->execute_query($selectStmt,[$studentID]);
      while($studentInfo=$selectResult->fetch_assoc()){
      if($count==0){
        $studentData['name']=$studentInfo['StudentName'];
        $studentData['ID']=$studentID;
        $studentData['email']=$studentInfo['StudentEmail'];

        echo"<form action='editStudent.php' method='POST'>";
        echo "<br>Name : <input type='text' name='name' value='".$studentInfo['StudentName']."' required/>".
             "<br><br>ID : <input type='text' name='ID' value='".$studentID."'required/>".
             "<br>Email : <input type='email' name='email' value='".$studentInfo['StudentEmail']."'required/>".
             "<br>Student Card ID : <label>".$studentInfo['StudentCardID']."</label>".
             "<input type='hidden' name='cardID' value='".$studentInfo['StudentCardID']."'/>";
        echo "<br><br>Class :";
        $count++;
      }
      if ($studentInfo['ClassName']!=null && !in_array($studentInfo['ClassName'],$checkClasses)) {
          $checkClasses[] = $studentInfo['ClassName'];
        }
      }

      foreach ($checkClasses as $className)
        echo "<br><input type='text'  name='class[]' value='".$className."'/>";


      echo"<br><br><input type='submit' name='update' value='DONE'>
      </form>";

    }catch(Exception $e){
        echo"Error: ".$e->getMessage();
    }
    if(isset($_POST['update'])){
      $studentInfoUp="SELECT StudentID from student where StudentCardID=?";
      if($_POST['name']!=$studentData['name'] or $_POST['ID']!=$studentData['ID'] or $_POST['email']!=$studentData['email']){
          try{
                $updateStmtStudent=$conn->execute_query($updateStmtStudent,[$_POST['ID'],$_POST['name'],$_POST['email'],$_POST['cardID']]);
              }catch(Exception $e){
                  echo"Error: ".$e->getMessage();
              }
      }
      $count=0;
      if (isset($_POST['class'])) {
        foreach($_POST['class'] as $className){
          $resultStudent=$conn->execute_query($studentInfoUp,[$_POST['cardID']]);
          $recordStudent=$resultStudent->fetch_assoc();
          if(isset($checkClasses[$count])){
            /*CHnage*/$resultClassInfoInrt=$conn->execute_query($getClassStmt,[$className]);
            $resultClassInfoDel=$conn->execute_query($getClassStmt,[$checkClasses[$count]]);
            if($className!=$checkClasses[$count]){
                  $resultClassInfo=$conn->execute_query($getClassStmt,[$checkClasses[$count]]);
                  while($getClassInfo=$resultClassInfo->fetch_assoc()){
                    $resultDel=$conn->execute_query($deleteStmt,[$recordStudent['StudentID'],$getClassInfo['ClassID']]);
                }

            }
          }else{
            if($className!=null){
              $resultClassInfo=$conn->execute_query($getClassStmt,[$className]);
              while($getClassInfo=$resultClassInfo->fetch_assoc()){
                if($getClassInfo['ClassID']!=null)
                  $insertNew=$conn->execute_query($insertStmt,[$recordStudent['StudentID'],$getClassInfo['ClassID']]);
              }
            }
          }
            $count++;
        }
      }
      header('Location:manageStudent.php');
      die();
    }

?>
