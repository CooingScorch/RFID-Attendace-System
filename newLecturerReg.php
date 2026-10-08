<?php
include('database.php');
$getClassStmt="SELECT ClassName,LecturerID from class where ClassName=?";
$getLecturerStmt = "SELECT LecturerID,LecturerEmail from lecturer";
$updateStmtClass = "UPDATE class SET LecturerID=? where ClassName=?";
$insertStmtLecturer = "INSERT into lecturer (LecturerID,Name,LecturerEmail,LecturerPassword) values(?,?,?,?)";
$resultGetLectuere=null;
$resultInsert =null;
$userCheck=null;

          include("newLecturerReg.html");
          if(isset($_POST['ID'])&& isset($_POST['email'])&&isset($_POST['password'])){
            if(preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[~`!@\#$%^&*()_+-={}[\]:;\"\'\|<,>.?/])#',$_POST['password'])
            && strlen($_POST['password'])>=8 && strlen($_POST['password'])<=12){
              $resultGetLecturer = mysqli_query($conn,$getLecturerStmt);
              while($getLecturer=$resultGetLecturer->fetch_assoc()){

                if($getLecturer['LecturerID']!=$_POST['ID']&& $getLecturer['LecturerEmail']!=$_POST['email']){
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
                        execute_query($insertStmtLecturer,
                        [$_POST['ID'],$_POST['name'],$_POST['email'],password_hash($_POST['password'],PASSWORD_BCRYPT)]);
                        if(isset($_POST['class'])){
                          echo "check";
                          foreach ($_POST['class'] as $className){
                            $resultClassInfo=$conn->execute_query($getClassStmt,[$className]);
                              while($getClassInfo=$resultClassInfo->fetch_assoc()){
                                if($getClassInfo['ClassName']==$className&&$getClassInfo['LecturerID']==null){
                                  $updateClass=$conn->
                                  execute_query($updateStmtClass,[$_POST['ID'],$className]);
                                echo $getClassInfo['Class']."added";
                              }else{
                                echo "<script type='text/javascript'>
                                alert('".$className."not available')
                                </script>";
                              }
                            }
                          }
                        }
                    header('Location:manageLecturer.php');
                    echo"<script type='text/javascript'>alert('Lecturer Registred Successfully')</script>";
                    die();
                  }catch(Exception $e){
                    echo"Error :".$e->getMessage();
                  }
                }else{
                  echo "<script type='text/javascript'>alert('Lecturer is already registered')</script>";
                }


            }else{
              echo "<script type='text/javascript'>alert('Invalid Password')</script>";
            }


          }

?>
