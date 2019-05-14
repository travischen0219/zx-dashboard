<?php
header("Cache-control: private");
$con = @mysqli_connect('localhost', 'root', 'acc7996acc', 'db_account');

if (!$con) {
    echo "Error: " . mysqli_connect_error();
	exit();
}

$_iid=$_REQUEST["id"];
$_id=explode(",",$_REQUEST["id"]);

require_once('chinese-unicode.php'); 
require_once ('fpdi.php');

// initiate FPDI210 × 297
$pdf = new FPDI('P', 'mm', [ 210,297]);
$pdf->AddBig5Font();



// Some Query
foreach($_id as $_id_value)
{
$sql = "SELECT * from apply_out_stocks WHERE id='".$_id_value."'";

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
if($total_materials>12)
{
	$more_page=array("1","2");
}
if($total_materials>24)
{
	$more_page=array("1","2","3");
}
if($total_materials>36)
{
	$more_page=array("1","2","3","4");
}
if($total_materials>48)
{
	$more_page=array("1","2","3","4","5");
}
if($total_materials>60)
{
	$more_page=array("1","2","3","4","5","6");
}
foreach($more_page as $value)
{
if($value>1)
$_inikey=$j;
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile("buyA4.pdf");
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx,0,0,210);
//$pdf->SetFont('Arial');
$pdf->SetFont('Big5','',16);


//‰¹
//$_nono=$_nono+1;


$_nono=$row["lot_number"];

$pdf->SetXY(30, 32);
$pdf->Write(0,$_nono);

$yy=75;

//å¤šé›®

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
		//æ¬
		$pdf->SetFont('Big5','',12);
		$pdf->SetXY(14, $yy);
		//$pdf->Write(0,$j);
//$materials = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialUnit'=>$materialUnit,'materialPrice'=>$materialPrice];
		//è²¨åç·¨è$row_m["fullCode"].' '.
		$pdf->SetXY(24.5, $yy);
		$pdf->Write(0,mb_convert_encoding($row_m["fullName"],"BIG5","auto"));

		//èæ ¼
		//$pdf->SetXY(38, $yy);
		//$pdf->Write(0,);

$pdf->SetFont('Big5','',14);
		//•¸
		$pdf->SetXY(84, $yy);
		$pdf->Write(0,mb_convert_encoding($row_m["size"],"BIG5","auto"));

		$pdf->SetXY(132, $yy);
		$pdf->Write(0,mb_convert_encoding($row_m["color"],"BIG5","auto"));


		$pdf->SetXY(150, $yy);
		$pdf->Write(0,(int)$_materials["materialAmount"][$key]);

		//–®ä½
		
		//–®
		//$pdf->SetXY(159, $yy);
		//$pdf->Write(0,$_materials["materialPrice"][$key]);

		//å°è
		//$pdf->SetXY(176, $yy);
		//$pdf->Write(0,$_materials["materialPrice"][$key]*$_materials["materialAmount"][$key]);

		$yy=$yy+17;
}
}



}//end more page
}//end $_id_value
$pdf->output();

?>
