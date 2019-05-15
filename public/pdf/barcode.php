<?php
require_once(__DIR__ . '/');

$_id=explode(",",$_GET["id"]);

require_once('chinese-unicode.php');
require_once ('fpdi.php');

// initiate FPDI 210 * 297
$pdf = new FPDI('P', 'mm', [ 210,297]);
$pdf->AddBig5Font();

$page_count = $pdf->setSourceFile("has_barcode.pdf");

for($j = 1 ; $j <= $page_count ; $j++){

    $tplIdx = $pdf->importPage($j);
    $pdf->AddPage();
    $pdf->useTemplate($tplIdx,0,0,210);
    $pdf->SetFont('Big5','',14);

    if($j == 1){
        $start = 0;
        $end = 15;
    } else {
        $start = ($j - 1) * 16;
        $end = $j * 16 - 1;
    }

    $y = 9;
    for($i = $start ; $i <= $end ; $i++){

        $sql = "SELECT * from materials WHERE id='".$_id[$i]."'";
        mysqli_query($con,"SET NAMES 'utf8'");
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result);


        if(($i % 2) == 0){
            $pdf->SetXY(16, $y);
            // $pdf->Write(0,mb_convert_encoding($row["fullName"],"BIG5","auto"));
		    $pdf->Cell(72,0,mb_convert_encoding($row["fullName"],"BIG5","auto"),0,0,'C');

        } else {
            $pdf->SetXY(121, $y);
            // $pdf->Write(0,mb_convert_encoding($row["fullName"],"BIG5","auto"));
		    $pdf->Cell(72,0,mb_convert_encoding($row["fullName"],"BIG5","auto"),0,0,'C');
            $y += 36.4;
        }
    }
}

$pdf->output();

?>
