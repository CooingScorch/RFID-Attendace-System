<?php
include('database.php');
$getClassStmt="SELECT ClassID from class where ClassName=?";
$getStudentStmt = "SELECT StudentID,StudentEmail,StudentCardID from student";
$getCardStmt = "SELECT StudentCardID from student
             where StudentID=''  and StudentEmail='' and StudentName=''  and StudentCardID is not null";
$updateStmtStudent = "UPDATE student SET StudentID=?,StudentName=?,StudentEmail=?
               where StudentID=''  and StudentEmail='' and StudentName=''  and StudentCardID is not null";
$insertStmtStudent = "INSERT into studentlist (StudentID,ClassID) values(?,?)";
$resultGetCard=null;
$resultGetStudent=null;
$resultInsert =null;
$locateNewID =null;
$userCheck=null;
  try{
          $resultGetCard = mysqli_query($conn,$getCardStmt);
          echo"<img class='INTI_LOGO'src='https://i.postimg.cc/TY1g3zFz/2560px-Inti-IU-logo-new-removebg-preview.png'/>";
          echo "<br><label>ID Card :</labe>";
          if($getCard=$resultGetCard->fetch_assoc())
            echo"&nbsp;".$getCard['StudentCardID'];
          include("newStudentReg.html");
          if(isset($_POST['ID'])&& isset($_POST['email'])&&isset($_POST['name'])){
            $resultGetStudent = mysqli_query($conn,$getStudentStmt);
            while($getStudent=$resultGetStudent->fetch_assoc()){
              if($getStudent['StudentID']!=$_POST['ID']&& $getStudent['StudentEmail']!=$_POST['email']){
                $userCheck=0;
                break;
              }else{
                $userCheck=-1;
                continue;
              }
            }
            if($userCheck==0){
              try{
                    $resultInsertStudent=$conn->
                    execute_query($updateStmtStudent,[$_POST['ID'],$_POST['name'],$_POST['email']]);
                    foreach ($_POST['class'] as $className){
                      $resultClassInfo=$conn->execute_query($getClassStmt,[$className]);
                        while($getClassInfo=$resultClassInfo->fetch_assoc()){
                          if($getClassInfo['ClassID']!=null){
                          $resultEnrollClass=$conn->
                          execute_query($insertStmtStudent,[$_POST['ID'],$getClassInfo['ClassID']]);
                        }else{
                          echo "<script type='text/javascript'>
                          alert('".$className."not available')
                          </script>";
                        }
                      }
                    }
                echo"<script type='text/javascript'>alert('Student Registred Successfully')</script>";
                header('Location:manageStudent.php');
                die();
              }catch(Exception $e){
                echo"Error :".$e->getMessage();
              }
            }else{
              echo "<script type='text/javascript'>alert('User is already registered')</script>";
            }
          }
     }catch(Exception $e){
       echo"Error :".$e->getMessage();
     }

?>
