<?php
require_once(__DIR__ . '/con.php');

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
$sql = "SELECT * from sales WHERE id='".$_id_value."'";

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
$pdf->setSourceFile("sale.pdf");
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx,0,0,210);
//$pdf->SetFont('Arial');
$pdf->SetFont('Big5','',16);


//��
//$_nono=$_nono+1;


$_nonos=$row["customer"];

$sql = "SELECT * from customers WHERE id='".$_nonos."'";
mysqli_query($con,"SET NAMES 'utf8'");
$query 	= mysqli_query($con, $sql);
$row_c = mysqli_fetch_array($query);
$_nono=mb_convert_encoding($row_c["shortName"],"BIG5","auto");

$pdf->SetXY(31, 30);
$pdf->Write(0,$_nono);
$_total=0;
$yy=52;

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
		$pdf->SetXY(8, $yy);
		$pdf->Write(0,$j);
//$materials = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialUnit'=>$materialUnit,'materialPrice'=>$materialPrice];
		//貨�編�$row_m["fullCode"].' '.
		$pdf->SetXY(11, $yy);
		$pdf->SetFont('Big5','',9);
		$pdf->Write(0,mb_convert_encoding($row_m["fullName"],"BIG5","auto"));

		//��格
		//$pdf->SetXY(38, $yy);
		//$pdf->Write(0,);

		$pdf->SetFont('Big5','',12);
		//�
		$cc=str_replace(' ','',$row_m["size"]);
		$pdf->SetXY(90, $yy);
		$pdf->Write(0,mb_convert_encoding($cc,"BIG5","auto"));

		//$pdf->SetXY(132, $yy);
		//$pdf->Write(0,mb_convert_encoding($row_m["color"],"BIG5","auto"));

		$pdf->SetFont('Big5','',12);
		$pdf->SetXY(124, $yy);
		$pdf->Write(0,(int)$_materials["materialAmount"][$key]);

		$pdf->SetXY(142.5, $yy);
		$pdf->Write(0,(int)$_materials["materialPrice"][$key]);

		$pdf->SetXY(156, $yy);
		$pdf->Write(0,(int)$_materials["materialPrice"][$key]*$_materials["materialAmount"][$key]);

		//���

		//��
		//$pdf->SetXY(159, $yy);
		//$pdf->Write(0,$_materials["materialPrice"][$key]);

		//小�
		//$pdf->SetXY(176, $yy);
		//$pdf->Write(0,$_materials["materialPrice"][$key]*$_materials["materialAmount"][$key]);
$_total=$_total + $_materials["materialPrice"][$key]*$_materials["materialAmount"][$key];
		$yy=$yy+25;
}

		$pdf->SetFont('Big5','',12);
		$pdf->SetXY("155.5", "234");
		$pdf->Write(0,$_total);


		$pdf->SetFont('Big5','',12);
		$pdf->SetXY("156", "240");
		$pdf->Write(0,round($_total*0.05));


		$pdf->SetFont('Big5','',12);
		$pdf->SetXY("156", "247");
		$pdf->Write(0,round($_total*0.05)+$_total);
}



}//end more page
}//end $_id_value
$pdf->output();

?>
