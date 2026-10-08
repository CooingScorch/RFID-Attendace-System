<?php
  session_start();
  include("login.html");
  include("database.php");
  $checkAdmin ='admin';
  $checkLecturer = 'lecturer';
  $result =null;
  $role=null;
  $login=null;
  $ID=null;
  $email=null;
  $pass=null;
  $redirect=null;

if(empty($_POST['email'])  || empty($_POST["password"] )){
  return False;
}
elseif(strpos($_POST['email'],$checkAdmin)){
  $role="admin";
  $login="SELECT AdminID,AdminEmail,AdminPassword from admin";
}
elseif (strpos($_POST['email'],$checkLecturer)) {
  $role="lecturer";
  $login="SELECT LecturerID,LecturerEmail,LecturerPassword from lecturer";
}
else{
  echo"<script type='text/javascript'>
        alert('Incorrect Email/Password')
        </script>";
          die();
}

  try{
    $result=mysqli_query($conn,$login);
  }catch(mysqli_sql_exception){
    echo"Query Unsuccessful";
  }
  if($role == "admin"){
    $ID='AdminID';
    $email='AdminEmail';
    $pass='AdminPassword';
    $redirect='admin.php';
  }
  elseif($role == "lecturer"){
    $ID='LecturerID';
    $email='LecturerEmail';
    $pass='LecturerPassword';
    $redirect='lecturer.php';
  }
  while( $records=mysqli_fetch_assoc($result)){
    if($records[$email]==$_POST['email'] && password_verify($_POST['password'],$records[$pass])){
            $_SESSION['UserID']=$records[$ID];
          header('Location:'.$redirect);
          mysqli_close($conn);
          die();
          break;
    }
  }
  echo"<script type='text/javascript'>
        alert('Incorrect Email/Password')
        </script>";

?>
