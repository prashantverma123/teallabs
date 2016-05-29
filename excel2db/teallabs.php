<?php

$connect = mysqli_connect("localhost","root","","teallabs");
  // ini_set('memory_limit', '128M');
include ("PHPExcel/IOFactory.php");

$html = "<table border ='1'>";

 $objPHPExcel = PHPExcel_IOFactory::load('student_master.xlsx');
foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
  $highestRow = $worksheet->getHighestRow();
  for ($row=1; $row <=24 ; $row++) {
    $html .="<tr>";
    $student_id = mysqli_real_escape_string($connect,$worksheet->getCellByColumnAndRow(0,$row)->getValue());
    $assesmentItem_id = mysqli_real_escape_string($connect,$worksheet->getCellByColumnAndRow(1,$row)->getValue());
    $correct = mysqli_real_escape_string($connect,$worksheet->getCellByColumnAndRow(2,$row)->getValue());
    $difficulty = mysqli_real_escape_string($connect,$worksheet->getCellByColumnAndRow(3,$row)->getValue());
    $time_started = mysqli_real_escape_string($connect,$worksheet->getCellByColumnAndRow(4,$row)->getValue());
    $time_taken = mysqli_real_escape_string($connect,$worksheet->getCellByColumnAndRow(5,$row)->getValue());
    $sql ="INSERT INTO `studentmaster` ( `student_id`,`insert_date`, `update_date`, `ip`, `status`) VALUES
    ('".$student_id."','2016-04-03 12:30:35', '2016-04-03 12:44:06', '127.0.0.1', 0)";

    mysqli_query($connect,$sql);

    // $html .='<td>'.$student_id.'</td>';
    // $html .='<td>'.$assesmentItem_id.'</td>';
    // $html .='<td>'.$correct.'</td>';
    // $html .='<td>'.$difficulty.'</td>';
    // $html .='<td>'.$time_started.'</td>';
    //   $html .='<td>'.$time_taken.'</td>';


  }
}
$html .='</table>';
echo $html;

 ?>
