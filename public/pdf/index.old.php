<?php
header("Cache-control: private");
$con = @mysqli_connect('localhost', 'account', 'acc7996', 'db_account');

if (!$con) {
    echo "Error: " . mysqli_connect_error();
	exit();
}

$_iid=$_REQUEST["id"];
$_id=explode(",",$_REQUEST["id"]);

require_once('chinese-unicode.php'); 
require_once ('fpdi.php');

// initiate FPDI
$pdf = new FPDI('P', 'mm', [ 250,176]);
$pdf->AddBig5Font();



// Some Query
foreach($_id as $_id_value)
{
$sql = "SELECT * from buys WHERE id='".$_id_value."'";

$query 	= mysqli_query($con, $sql);
$row = mysqli_fetch_array($query);




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
$pdf->setSourceFile("buyB5.pdf");
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx,0,0,230);
//$pdf->SetFont('Arial');
$pdf->SetFont('Big5','',16);


//‰¹
//$_nono=$_nono+1;


$_nono=$row["lot_number"];

$pdf->SetXY(146, 37);
$pdf->Write(0,$_nono);

$yy=55;

//å¤šé›®

$_keycheck_last=$j+5;
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
		$pdf->SetFont('Big5','',14);
		$pdf->SetXY(14, $yy);
		$pdf->Write(0,$j);
//$materials = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialUnit'=>$materialUnit,'materialPrice'=>$materialPrice];
		//è²¨åç·¨è
		$pdf->SetXY(24, $yy);
		$pdf->Write(0,$row_m["fullCode"]);

		//èæ ¼
		$pdf->SetXY(50, $yy);
		$pdf->Write(0,mb_convert_encoding($row_m["fullName"],"BIG5","auto"));

		//•¸
		$pdf->SetXY(132, $yy);
		$pdf->Write(0,(int)$_materials["materialAmount"][$key]);

		//–®ä½
		$pdf->SetXY(147, $yy);
		$pdf->Write(0,mb_convert_encoding($row_u["name"],"BIG5","auto"));

		//–®
		$pdf->SetXY(159, $yy);
		$pdf->Write(0,$_materials["materialPrice"][$key]);

		//å°è
		$pdf->SetXY(176, $yy);
		$pdf->Write(0,$_materials["materialPrice"][$key]*$_materials["materialAmount"][$key]);

		$yy=$yy+8;
}
}


//å¦œæ‰ä‹æ–¹§å®¹è¦é¡¯ç¤º
//
$second_view=2;
//$_keycheck++;
if($total_materials > $j )
{
	$second_view=1;
	$_keycheck_last=$j+5;
	if($_keycheck_last > $total_materials)
		$_keycheck_last=$total_materials;
}
if($second_view==1)
{

$pdf->useTemplate($tplIdx,0,140,230);

//‰¹
$_nono=$_nono+1;
$_nono=$row["lot_number"];
$pdf->SetXY(146, 177);
$pdf->Write(0,$_nono);

$yy=195;

//å¤šé›®
for($key = $j; $key <= $_keycheck_last; $key++)
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
		$pdf->SetFont('Big5','',14);
		$pdf->SetXY(14, $yy);
		$pdf->Write(0,$j);
//$materials = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialUnit'=>$materialUnit,'materialPrice'=>$materialPrice];
		//è²¨åç·¨è
		$pdf->SetXY(24, $yy);
		$pdf->Write(0,$row_m["fullCode"]);

		//èæ ¼
		$pdf->SetXY(50, $yy);
		$pdf->Write(0,mb_convert_encoding($row_m["fullName"],"BIG5","auto"));

		//•¸
		$pdf->SetXY(132, $yy);
		$pdf->Write(0,(int)$_materials["materialAmount"][$key]);

		//–®ä½
		$pdf->SetXY(147, $yy);
		$pdf->Write(0,mb_convert_encoding($row_u["name"],"BIG5","auto"));

		//–®
		$pdf->SetXY(159, $yy);
		$pdf->Write(0,$_materials["materialPrice"][$key]);

		//å°è
		$pdf->SetXY(176, $yy);
		$pdf->Write(0,$_materials["materialPrice"][$key]*$_materials["materialAmount"][$key]);

		$yy=$yy+8;
}	
}

}//end second view

}//end more page
}//end $_id_value
$pdf->output();

?>