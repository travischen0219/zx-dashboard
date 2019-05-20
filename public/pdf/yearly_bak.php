<?php
header("Cache-control: private");
$con = @mysqli_connect('localhost', 'account', 'acc7996', 'db_account');

if (!$con) {
    echo "Error: " . mysqli_connect_error();
	exit();
}

$_iid=$_REQUEST["mm"];
$_id=explode(",",$_REQUEST["id"]);

require_once('chinese-unicode.php'); 
require_once ('fpdi.php');

// initiate FPDI210  297
$pdf = new FPDI('P', 'mm', [ 210,297]);
$pdf->AddBig5Font();



// Some Query


$sql = "SELECT * from suppliers";
mysqli_query($con,"SET NAMES 'utf8'"); 
$query 	= mysqli_query($con, $sql);
while($row = mysqli_fetch_array($query))
{
	$ids=$row["id"];
	$supplier_name[$ids]=mb_convert_encoding($row["shortName"],"BIG5","auto");
}


$sql = "SELECT * from buys WHERE ( status=2 or status=4 or status=5 )";

$query 	= mysqli_query($con, $sql);
$total_materials=0;
while($row = mysqli_fetch_array($query))
{
//$row["materials"]='';
$_materials[]=unserialize($row["materials"]);
$lot_number[]=$row["lot_number"];
$supplier[]=$row["supplier"];
$id[]=$row["id"];
$total_materials = $total_materials + count($_materials['material']);
}//end $_id_value

$more_page=array("1");

$_nono=12345;
$i=0;
$_inikey=0;

$valuekey=1;
if($total_materials>45)
{
	$more_page=array("1","2");
}
if($total_materials>90)
{
	$more_page=array("1","2","3");
}
if($total_materials>135)
{
	$more_page=array("1","2","3","4");
}
if($total_materials>180)
{
	$more_page=array("1","2","3","4","5");
}
if($total_materials>225)
{
	$more_page=array("1","2","3","4","5","6");
}
if($total_materials>275)
{
	$more_page=array("1","2","3","4","5","6","7");
}
foreach($more_page as $value)
{
if($value>1)
$_inikey=$j;
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile("yearly.pdf");
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx,0,0,210);
//$pdf->SetFont('Arial');
$pdf->SetFont('Big5','',16);


//��
//$_nono=$_nono+1;


$_nono = $_id;
$pdf->SetXY(30, 52);
//$pdf->Write(0,$_nono);

$yy=35;
$_ino=0;
//多雮

$_keycheck_last=$j+11;
if($_keycheck_last > $total_materials)
	$_keycheck_last=$total_materials;

foreach($id as $idkey => $idvalue)
{


for($key = $_inikey; $key <= $_keycheck_last; $key++)
{
$j=$key+1;

$sql = "SELECT * from materials WHERE id='".$_materials[$idkey]["material"][$key]."'";
mysqli_query($con,"SET NAMES 'utf8'"); 
$query 	= mysqli_query($con, $sql);
$row_m = mysqli_fetch_array($query);

$sql = "SELECT * from material_units WHERE id='".$_materials[$idkey]["materialUnit"][$key]."'";
$query 	= mysqli_query($con, $sql);
$row_u = mysqli_fetch_array($query);

$pdf->SetFont('Big5','',12);
		$pdf->SetXY(32, 14.5);
		$pdf->Write(0,date("Y-m-d"));

		$pdf->SetFont('Big5','',12);
		$pdf->SetXY(90, 22);
		$pdf->Write(0,$_iid."-01-01 ~ ".$_iid."-12-31");


		$pdf->SetFont('Big5','',12);
		$pdf->SetXY(190, 22);
		$pdf->Write(0,"1/1");


if($_materials[$idkey]["material"][$key] > 0 )
{
	
		


	$_ino++;
	$pdf->SetFont('Big5','',12);
	$pdf->SetXY(16, $yy);
	$pdf->Write(0,(int)$_ino);


	$pdf->SetXY(32, $yy);
	$pdf->Write(0,$lot_number[$idkey]);

	$_ss=$supplier[$idkey];
	$pdf->SetXY(66.5, $yy);
	$pdf->Write(0,$supplier_name[$_ss]);

	//�
	
	$pdf->SetXY(14, $yy);
	//$pdf->Write(0,$j);
//$materials = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialUnit'=>$materialUnit,'materialPrice'=>$materialPrice];
	//貨�編�$row_m["fullCode"].' '.
	$pdf->SetFont('Big5','',9);
	$pdf->SetXY(138, $yy);
	$pdf->Write(0,mb_convert_encoding($row_m["fullName"],"BIG5","auto"));

	
	$pdf->SetXY(102, $yy);
	$pdf->Write(0,mb_convert_encoding($row_m["fullCode"],"BIG5","auto"));

	//$pdf->SetXY(132, $yy);
	//$pdf->Write(0,mb_convert_encoding($row_m["color"],"BIG5","auto"));

	//$pdf->SetFont('Big5','',12);
	//$pdf->SetXY(118, $yy);
	//$pdf->Write(0,(int)$_materials[$idkey]["materialAmount"][$key]);

	//$pdf->SetFont('Big5','',12);
	//$pdf->SetXY(140, $yy);
	//$pdf->Write(0,(int)$_materials[$idkey]["materialAmount"][$key]);


	//$pdf->SetFont('Big5','',12);
	//$pdf->SetXY(165, $yy);
	//$pdf->Write(0,(int)$_materials[$idkey]["materialPrice"][$key]);

	

	$pdf->SetFont('Big5','',12);
	$pdf->SetXY(185, $yy);
	$pdf->Write(0,(int)$_materials[$idkey]["materialAmount"][$key]*$_materials[$idkey]["materialPrice"][$key]);
	
	//���
	
	//��
	//$pdf->SetXY(159, $yy);
	//$pdf->Write(0,$_materials["materialPrice"][$key]);

	//小�
	//$pdf->SetXY(176, $yy);
	//$pdf->Write(0,$_materials["materialPrice"][$key]*$_materials["materialAmount"][$key]);

	$yy=$yy+5.25;
}
}
}


}//end more page

$pdf->output();

?>