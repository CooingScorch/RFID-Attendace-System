<?php
  include('database.php');
  $getStmt = "SELECT StudentID,StudentEmail,StudentCardID from student";
  $insertStmt = "INSERT INTO student (StudentCardID)values(?)";
  $resultGetStudent=null;
  $resultInsert=null;

      if(isset($_POST['cardID'])){
          echo $_POST['cardID'];
          $cardID=intval($_POST['cardID']);
          try{
            $resultInsert = $conn->execute_query($insertStmt,[$cardID]);
            echo"ID Captured";
          }catch(Exception $e){
            echo"Error :".$e->getMessage();
          }
      }else{
        echo"ID not Captured";
      }
?>
