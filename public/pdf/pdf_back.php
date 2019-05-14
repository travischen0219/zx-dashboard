<?php
header("Cache-control: private");
require_once('chinese-unicode.php'); 
require_once ('fpdi.php');

// initiate FPDI
$pdf = new FPDI('P', 'mm', [ 250,176]);
$pdf->AddBig5Font();

$more_page=array("1","2");

foreach($more_page as $value)
{
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile("buyB5.pdf");
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx,0,0,230);
//$pdf->SetFont('Arial');
$pdf->SetFont('Big5','',16);

//批號
$pdf->SetXY(146, 37);
$pdf->Write(0,"123456");

$yy=55;

//多項目
	for($i=1;$i<6;$i++)
	{

		//項次
		$pdf->SetFont('Big5','',14);
		$pdf->SetXY(14, $yy);
		$pdf->Write(0,$i);

		//貨品編號
		$pdf->SetXY(24, $yy);
		$pdf->Write(0,"ABC123");

		//品名規格
		$pdf->SetXY(50, $yy);
		$pdf->Write(0,iconv("UTF-8","BIG5","品名規格"));

		//數量
		$pdf->SetXY(134, $yy);
		$pdf->Write(0,"2");

		//單位
		$pdf->SetXY(146, $yy);
		$pdf->Write(0,"CM");

		//單價
		$pdf->SetXY(159, $yy);
		$pdf->Write(0,"120");

		//小計
		$pdf->SetXY(176, $yy);
		$pdf->Write(0,"120");

		$yy=$yy+8;
	}


//如果有下方內容要顯示
//
$second_view=1;
if($second_view==1)
{

$pdf->useTemplate($tplIdx,0,140,230);

//批號
$pdf->SetXY(146, 177);
$pdf->Write(0,"123456");

$yy=195;

//多項目
	for($i=1;$i<6;$i++)
	{
		//項次
		$pdf->SetFont('Big5','',14);
		$pdf->SetXY(14, $yy);
		$pdf->Write(0,$i);

		//貨品編號
		$pdf->SetXY(24, $yy);
		$pdf->Write(0,"ABC123");

		//品名規格
		$pdf->SetXY(50, $yy);
		$pdf->Write(0,iconv("UTF-8","BIG5","品名規格"));

		//數量
		$pdf->SetXY(134, $yy);
		$pdf->Write(0,"2");

		//單位
		$pdf->SetXY(146, $yy);
		$pdf->Write(0,"CM");

		//單價
		$pdf->SetXY(159, $yy);
		$pdf->Write(0,"120");

		//小計
		$pdf->SetXY(176, $yy);
		$pdf->Write(0,"120");

		$yy=$yy+8;
	}

}//end second view

}//end more page

$pdf->output();

?>