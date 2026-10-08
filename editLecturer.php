<?php
session_start();
$lecturerID=$_SESSION['lecturerID'];
include("database.php");
include("editLecturer.html");
$selectStmt="SELECT * from lecturer left join class on lecturer.LecturerID=class.LecturerID where lecturer.LecturerID=?";
$updateStmtLecturer="UPDATE lecturer set  Name=?, LecturerEmail=?, LecturerPassword=? where LecturerID=?";
$getClassStmt="SELECT ClassID from class where ClassName=?";
$updateClassStmt="UPDATE class set LecturerID=? where ClassName=?";
$deleStmt="DELETE FROM class where LecturerID=? and ClassName=?";
$funct=null;
$selectResult=null;
$count=0;
$lecturerData=array();
$checkClasses=array();
try{
      $selectResult=$conn->execute_query($selectStmt,[$lecturerID]);
      while($lecturerInfo=$selectResult->fetch_assoc()){
        if($count==0){
          $lecturerData['name']=$lecturerInfo['Name'];
          $lecturerData['ID']=$lecturerID;
          $lecturerData['email']=$lecturerInfo['LecturerEmail'];
          $lecturerData['password']=$lecturerInfo['LecturerEmail'];

          echo"<form action='editLecturer.php' method='POST'>";
          echo "<br>Name : <input type='text' name='name' value='".$lecturerInfo['Name']."'required/>".
               "<br><br>ID : <label>".$lecturerID."</label>".
               "<input type='hidden' name='ID' value='".$lecturerID."'required/>".
               "<br>Email : <input type='email' name='email' value='".$lecturerInfo['LecturerEmail']."'required/>".
               "<br>Lecturer Password : <input type='password' name='password' value='".$lecturerInfo['LecturerPassword']."'required/>";
          echo "<br><br>Class :";
          $count++;
        }
        if ($lecturerInfo['ClassName']!=null && !in_array($lecturerInfo['ClassName'],$checkClasses)) {
            $checkClasses[] = $lecturerInfo['ClassName'];
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
        $lecturerInfoUp="SELECT lecturerID from lecturer where LecturerID=?";
        if($_POST['name']!=$lecturerData['name'] or $_POST['email']!=$lecturerData['email']
           or !password_verify(  password_hash($_POST['password'],PASSWORD_BCRYPT),$lecturerData['password'])){
            try{
                  $updateStmtlecturer=$conn->execute_query($updateStmtLecturer,[$_POST['name'],$_POST['email'],
                                                            password_hash($_POST['password'],PASSWORD_BCRYPT),$_POST['ID']]);
                }catch(Exception $e){
                    echo"Error: ".$e->getMessage();
                }
        }
        $count=0;
        if (isset($_POST['class'])) {
          foreach($_POST['class'] as $className){
            if(isset($checkClasses[$count])){
              $resultClassInfo=$conn->execute_query($getClassStmt,[$checkClasses[$count]]);
              if($className!=$checkClasses[$count]){

                    $resultClassInfo=$conn->execute_query($getClassStmt,[$checkClasses[$count]]);
                    $resultUpdate=$conn->execute_query($updateClassStmt,[null,$checkClasses[$count]]);
                }
            }else{
              if($className!=null){
                  if($className!="")
                    $resultUpdate=$conn->execute_query($updateClassStmt,[$_POST['ID'],$className]);
                }
              }
                  $count++;
            }
          }
        header('Location:managelecturer.php');
        die();
      }
?>
