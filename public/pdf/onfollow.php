<?php
require_once(__DIR__ . '/conn.php');

$_iid=$_REQUEST["id"];
$_id=explode(",",$_REQUEST["id"]);

require_once('chinese-unicode.php');
require_once ('fpdi.php');

// initiate FPDI210 � 297
$pdf = new FPDI('P', 'mm', [ 210,297]);
$pdf->AddBig5Font();



// Some Query
foreach($_id as $_id_value)
{
	$sql = "SELECT * from buys WHERE id='".$_id_value."'";

$query 	= mysqli_query($con, $sql);
$row = mysqli_fetch_array($query);

//$row["materials"]='';
$_materials=unserialize($row["materials"]);
$total_materials = count($_materials['material']);

$more_page=array("1");

$_nono=12345;
$i=0;
$_inikey=0;

$valuekey=1;
if($total_materials>32)
{
	$more_page=array("1","2");
}
if($total_materials>64)
{
	$more_page=array("1","2","3");
}

foreach($more_page as $value)
{
if($value>1)
$_inikey=$j;
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile("on_order_follow.pdf");
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx,0,0,210);
//$pdf->SetFont('Arial');
$pdf->SetFont('Big5','',16);


//��
//$_nono=$_nono+1;


$_nono=$row["lot_number"];

$pdf->SetXY(100, 28);
$pdf->Write(0,date("Y-m-d"));

$pdf->SetXY(186.5, 28);
$pdf->Write(0,"1/1");

$yy=47;

//多雮

$_keycheck_last=$j+11;
if($_keycheck_last > $total_materials)
	$_keycheck_last=$total_materials;


for($key = $_inikey; $key <= $_keycheck_last; $key++)
{
$j=$key+1;

$sql = "SELECT * from materials WHERE id='".$_materials["material"][$key]."'";
mysqli_query($con,"SET NAMES 'utf8'");
$query 	= mysqli_query($con, $sql);
$row_m = mysqli_fetch_array($query);

$sql = "SELECT * from material_units WHERE id='".$_materials["materialUnit"][$key]."'";
$query 	= mysqli_query($con, $sql);
$row_u = mysqli_fetch_array($query);

if($_materials["material"][$key] > 0 )
{
		//�


		$pdf->SetFont('Big5','',12);
		$pdf->SetXY(14, $yy);
		$pdf->Write(0,$j);

		$pdf->SetXY(28, $yy);
		$pdf->Write(0,$_nono);

//$materials = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialUnit'=>$materialUnit,'materialPrice'=>$materialPrice];
		//貨�編�$row_m["fullCode"].' '.
		$pdf->SetXY(58, $yy);
		$pdf->Write(0,mb_convert_encoding($row_m["fullName"],"BIG5","auto"));

		//��格
		//$pdf->SetXY(38, $yy);
		//$pdf->Write(0,);

		$pdf->SetFont('Big5','',11);
		$pdf->SetXY(114, $yy);
		$pdf->Write(0,$row["buyDate"]);

		$pdf->SetXY(137, $yy);
		$pdf->Write(0,$row["expectedReceiveDate"]);


		$pdf->SetXY(100, $yy);
		$pdf->Write(0,(int)$_materials["materialAmount"][$key]);

		//���

		//��
		//$pdf->SetXY(159, $yy);
		//$pdf->Write(0,$_materials["materialPrice"][$key]);

		//小�
		//$pdf->SetXY(176, $yy);
		//$pdf->Write(0,$_materials["materialPrice"][$key]*$_materials["materialAmount"][$key]);

		$yy=$yy+7.5;
}
}



}//end more page
}//end $_id_value
$pdf->output();

?>
