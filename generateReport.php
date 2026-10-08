<?php
  require("fpdf/fpdf.php");
  include("database.php");
  $className=str_replace('+',' ',$_GET['className']);
  $stmtClass="SELECT * from class where ClassName=?";
  $stmtStudent="SELECT DISTINCT studentlist.StudentID from studentlist inner join class on studentlist.ClassID = class.ClassID where ClassName=?";
  $stmtAttend= "SELECT student.studentID, student.StudentName, student.StudentEmail,attendance.Status from student
                inner join studentlist on student.StudentID = studentlist.StudentID
                inner join attendance on studentlist.StudentID = attendance.StudentID
                inner join class on class.classID= attendance.ClassID and studentlist.ClassID = attendance.ClassID
                where class.ClassName =? and studentlist.StudentID=?";
  $resultClass=null;
  $resultAttend=null;
  $belowPercent=array();

        try{
              $resultStudent=$conn->execute_query($stmtStudent,[$className]);
              $studentID=null;
              $studentName=null;
              $studentEmail=null;
              while($studentInfo=$resultStudent->fetch_assoc()){
                $studentID=$studentInfo['StudentID'];

                $totalClass=0;
                $numbAttended=0;

                $resultAttend=$conn->execute_query($stmtAttend,[$className,$studentInfo['StudentID']]);
                while($attendInfo=$resultAttend->fetch_assoc()){
                    $studentName=$attendInfo['StudentName'];
                    $studentEmail=$attendInfo['StudentEmail'];
                    if($attendInfo['Status']==1){
                      $numbAttended++;
                    }
                    $totalClass++;
                }
                if ($studentName=="" && $studentEamil==""){
                  $getStudent=$conn->execute_query("SELECT StudentName, StudentEmail from student where StudentID=?",[$studentInfo['StudentID']]);
                  $getStudent=$getStudent->fetch_assoc();
                  $studentName=$getStudent['StudentName'];
                  $studentEmail=$getStudent['StudentEmail'];
                  $numbAttended=0;

                }
                if ($numbAttended>0){
                  $percentage=$numbAttended/$totalClass*100;
                }else{
                  $percentage=0;
                }
                if($percentage<80){
                  $studentData=array(
                    "studentID"=>  $studentID ,
                    "studentName"=>  $studentName ,
                    "studentEmail"=> $studentEmail ,
                    "studentPercent"=>round($percentage,2)
                  );
                  $belowPercent[]=$studentData;
                }
                $studentName="";
                $studentEamil="";
              }
          }catch(Exception $e){
            echo 'Error: '.$e->getMessage();
          }

    generateReport($className,$belowPercent );

    function generateReport($className,$belowPercent){
      $pdf = new FPDF('P','mm','A4');
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',16);
      $pdf->Cell(190,10,$className,0,2,'C');
      $pdf->Cell(10,8,"",0,2,'C');

      $pdf->SetFont('Arial','B',11);
      $pdf->Cell(8,8,"No.",1,0,'C');
      $pdf->Cell(30,8,"ID",1,0,'C');
      $pdf->Cell(55,8,"Student",1,0,'C');
      $pdf->Cell(65,8,"Email",1,0,'C');
      $pdf->Cell(30,8,"Percentage (%)",1,1,'C');

      $count=1;
      $pdf->SetFont('Arial','',10);
      foreach ($belowPercent as $studentBarred){
        $pdf->Cell(8,8,$count,1,0,'C');
        $pdf ->Cell(30,8,$studentBarred["studentID"],1,0,'C');
        $pdf->Cell(55,8,$studentBarred["studentName"],1,0,'C');
        $pdf->Cell(65,8, $studentBarred["studentEmail"],1,0,'C');
        $pdf->Cell(30,8,$studentBarred["studentPercent"],1,1,'C');
        $count++;
      }


      $pdf->Output();

    }

?>
