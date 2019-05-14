<?php
	header("Cache-control: private");
  session_start(); 
	
	require_once("../../inc/functions.inc.php");
	require_once("../../inc/config.inc.php");
	require_once("../../inc/settings.inc.php");
		
	require_once ('fpdi/fpdi.php');

	
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : "";	

	$db=new Database();	 	 $db->open();
	$db0=new Database();	 $db0->open();
	$db1=new Database();	 $db1->open();

	$sql=" SELECT * FROM money_order";				
	$sql.= " WHERE mid= '$id' ";					
										  	  	
		$db0->query($sql);		

				
		while($row_item = $db0->fetchArray())
			{
				$_orderid=$row_item["mid"];
				$_a1=$row_item["b_name"];
				$_a2=$row_item["b_number"];
				$_a3=$row_item["b_address"];
				$_a4=$row_item["b_tel"];
				$_a5=$row_item["b_bank_account"];
				$_a6=$row_item["b_bank_title"];
				$_a7=$row_item["b_bank_name"];
				$bank_ind=$row_item["bank_ind"];

				$_a8=$row_item["a_name"];
				$_a9=$row_item["a_tel"];
				$_a10=$row_item["a_country"];
				$_a11=$row_item["a_birthday"];
				$_a12=$row_item["a_reside_code"];
				$_a13=$row_item["a_reside_date"];
				$_orderid2 = $row_item["orderid2"];
				$_a14=$row_item["b_receive"];			

				$_a15=$row_item["p_money"];
				$_a16="";//$row_item["p_exchange"];
				$_a17=$row_item["p_fee"];			
				//$_a17="";									
				$_a18=$row_item["p_total_money"] - $_a17;
				$_a18s=$row_item["p_total_money"] - $_a17;			
				$_a19=$row_item["agent_name"];		
				
				$_a20=$row_item["orderid2"];	//orderid2
				
				$_date = $row_item["p_date"]; //	date("Y-m-d");

				$_bank=$row_item["bank"];	//orderid2

				if($_bank==242 || $_bank=="245"  || $_bank=="246" )
				{
					$_a18 = "USD ".$_a15;
				}else{
					$_a18 = "NT$ ".number_format($_a18);
				}

				if($_bank!=235 )
				{
					if($_date=="2017-09-28" || $_date=="2017-09-29" || $_date=="2017-09-30" || $_date=="2017-10-01" || $_date=="2017-10-02" || $_date=="2017-10-03" || $_date=="2017-10-04")
					{
						$_a18 = "USD ".$_a15;
					}		
				}		
				
if($_orderid2=='VN113070' || $_orderid2=='VN306532' || $_orderid2=='VN319938' || $_orderid2=='VN348319' || $_orderid2=='VN114005' || $_orderid2=='VN315450' || $_orderid2=='VN313362' || $_orderid2=='VN117884' || $_orderid2=='VN300440' || $_orderid2=='VN309215' || $_orderid2=='VN309214' || $_orderid2=='VN309216' || $_orderid2=='VN348172' || $_orderid2=='VN109150' || $_orderid2=='VN319940' || $_orderid2=='VN348173' || $_orderid2=='VN304811' || $_orderid2=='VN304812' || $_orderid2=='VN117885' || $_orderid2=='VN304182' || $_orderid2=='VN348456' || $_orderid2=='VN348176' || $_orderid2=='VN109624' || $_orderid2=='VN301978' || $_orderid2=='VN348109' || $_orderid2=='VN348110' || $_orderid2=='VN348111' || $_orderid2=='VN348181' || $_orderid2=='VN348182' || $_orderid2=='VN348184' || $_orderid2=='VN107895' || $_orderid2=='VN348115' || $_orderid2=='VN348185' || $_orderid2=='VN316628' || $_orderid2=='VN348113' || $_orderid2=='VN321713' || $_orderid2=='VN309230' || $_orderid2=='VN313372' || $_orderid2=='VN321714' || $_orderid2=='VN321715' || $_orderid2=='VN321716' || $_orderid2=='VN321717' || $_orderid2=='VN320754' || $_orderid2=='VN321718' || $_orderid2=='VN302432' || $_orderid2=='VN320755' || $_orderid2=='VN302433' || $_orderid2=='VN313367' || $_orderid2=='VN303854' || $_orderid2=='VN313368' || $_orderid2=='VN348466' || $_orderid2=='VN348467' || $_orderid2=='VN301977' || $_orderid2=='VN313370' || $_orderid2=='VN106390' || $_orderid2=='VN106389' || $_orderid2=='VN315448' || $_orderid2=='VN106450' || $_orderid2=='VN303349' || $_orderid2=='VN109087' || $_orderid2=='VN309397' || $_orderid2=='VN309232' || $_orderid2=='VN109088' || $_orderid2=='VN115384' || $_orderid2=='VN311349' || $_orderid2=='VN304289' || $_orderid2=='VN311350' || $_orderid2=='VN320718' || $_orderid2=='A0001189' || $_orderid2=='VN303350' || $_orderid2=='VN301979' || $_orderid2=='VN314902' || $_orderid2=='VN314903' || $_orderid2=='VN301980' || $_orderid2=='VN113076' || $_orderid2=='VN309231' || $_orderid2=='VN309233' || $_orderid2=='VN309234' || $_orderid2=='VN309225' || $_orderid2=='VN309226' || $_orderid2=='VN313374' || $_orderid2=='VN309227' || $_orderid2=='VN309235' || $_orderid2=='VN317244' || $_orderid2=='VN306542' || $_orderid2=='VN309236' || $_orderid2=='VN322516' || $_orderid2=='VN313375' || $_orderid2=='VN306602' || $_orderid2=='VN302499' || $_orderid2=='VN114023' || $_orderid2=='VN303383' || $_orderid2=='VN306543' || $_orderid2=='VN114024' || $_orderid2=='VN315206' || $_orderid2=='VN316375' || $_orderid2=='VN315205' || $_orderid2=='VN314904' || $_orderid2=='VN114025' || $_orderid2=='VN315207' || $_orderid2=='VN114026' || $_orderid2=='VN319819' || $_orderid2=='VN302648' || $_orderid2=='VN319820' || $_orderid2=='VN322797' || $_orderid2=='VN322798' || $_orderid2=='VN313377' || $_orderid2=='VN301981' || $_orderid2=='VN320761' || $_orderid2=='VN320757' || $_orderid2=='VN348469' || $_orderid2=='VN320758' || $_orderid2=='VN113077' || $_orderid2=='VN302500' || $_orderid2=='A0001838' || $_orderid2=='VN347512' || $_orderid2=='VN304290' || $_orderid2=='VN347511' || $_orderid2=='VN304291' || $_orderid2=='VN116400' || $_orderid2=='VN114027' || $_orderid2=='VN313772' || $_orderid2=='VN304813' || $_orderid2=='VN319821' || $_orderid2=='VN317097' || $_orderid2=='VN321500' || $_orderid2=='VN313773' || $_orderid2=='VN313774' || $_orderid2=='VN323410' || $_orderid2=='VN306544' || $_orderid2=='VN114028' || $_orderid2=='VN323404' || $_orderid2=='VN324651' || $_orderid2=='VN319822' || $_orderid2=='VN321131' || $_orderid2=='VN319253' || $_orderid2=='VN315208' || $_orderid2=='VN306545' || $_orderid2=='VN319251' || $_orderid2=='VN320762' || $_orderid2=='VN319254' || $_orderid2=='VN314077' || $_orderid2=='VN304814' || $_orderid2=='VN301982' || $_orderid2=='VN302649' || $_orderid2=='VN114029' || $_orderid2=='VN302434' || $_orderid2=='VN315209' || $_orderid2=='VN315210' || $_orderid2=='VN304192' || $_orderid2=='VN323938' || $_orderid2=='VN314906' )
				{
					$_a18 = "USD ".$_a15;
				}


				if($_orderid2=='VN309389')
				{
					$_a18 = "NT$ ".number_format($_a18s);

				}

				if($_bank==235 )
				{
					$_a18 = "NT$ ".number_format($_a18s);
				}

			}


$pdf = new FPDI();

$pdf->AddPage();

if($_bank=="245")
$pdf->setSourceFile("pdf_philippines1.pdf");
elseif($_bank=="246")
$pdf->setSourceFile("pdf_philippines2.pdf");
else
$pdf->setSourceFile("rpt_new.pdf");

$tplIdx = $pdf->importPage(1);

$pdf->useTemplate($tplIdx);

$pdf->AddUniCNShwFont('uni'); 
$pdf->AddUniCNShwFont('uniKai','DFKaiShu-SB-Estd-BF'); 



//$pdf->SetTextColor(255, 0, 0);

$pdf->SetFont('uniKai','B',14);
$pdf->Text(168,16,$_a20);

if($_bank=="242")
{

$pdf->SetFont('uniKai','B',10); 
$pdf->Text(36,48,$_a8);
$pdf->Text(60,54,"宏元人力仲介有限公司");
$pdf->Text(28,61,$_a8);
$pdf->Text(150,74,"宏元人力仲介有限公司");


$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('uniKai','B',14); 
$pdf->Text(38, 227, "宏元人力仲介有限公司");
//$pdf->Image('Whitedot.png', 32, 222.6, 60, 6);
$pdf->Text(48, 240, "54012192");
//$pdf->Image('Whitedot.png', 100, 212, 45, 5.5);
//$pdf->Text(100, 217.2, "02-26892363");
//$pdf->Image('Whitedot.png', 150, 212, 45, 5.5);
$pdf->Text(120, 227, "02-22633999");
//$pdf->Image('Whitedot.png', 100, 222.5, 70, 5.8);
$pdf->Text(110, 240, "新北市土城區興城路102號2樓");

$pdf->Text(30,101,"ARGIBANK");
	$pdf->Text(80,101,"163131076688");
	$pdf->Text(21,95.5,"V");

}else{




if($_bank!=244)
{
$pdf->SetFont('uniKai','B',10); 
$pdf->Text(36,48,$_a8);

if($_bank!="245" && $_bank!="246")
$pdf->Text(60,54,"威龍人力仲介有限公司");

$pdf->Text(28,61,$_a8);

if($_bank!="245" && $_bank!="246")
$pdf->Text(150,74,"威龍人力仲介有限公司");
}

if($_bank==244)
{
$pdf->SetFont('uniKai','B',10); 
$pdf->Text(36,48,$_a8);
$pdf->Text(60,54,"雲輔外勞仲介有限公司");
$pdf->Text(28,61,$_a8);
$pdf->Text(150,74,"雲輔外勞仲介有限公司");
}

if($_bank==236 or $_bank==244)
{
	$pdf->Text(30,101,"DONGA BANK");
	$pdf->Text(82,101,"000150880022");
	$pdf->Text(21,95.5,"V");
}else{

if($_bank==235)
{
	//$_a6=$bank_ind;
	//$_a6=strtoupper($_a6)." BANK";
//BNI--766004107、BRI-020602001450307
	
	$pdf->SetFont('uniKai','B',10); 
	
	if($bank_ind=="BNI"){
		$pdf->Text(7,101,"VEYRON HUMAN RESOURCES AGENCY LTD");
		$pdf->Text(82,101,"766004107");
	}
	if($bank_ind=="BRI"){
		$pdf->Text(7,101,"Bank Rakyat Indonesia");
		$pdf->Text(82,101,"020602001450307");
	}
	
	//$pdf->Text(7,101,"VEYRON HUMAN RESOURCES AGENCY LTD");
	//$pdf->Text(82,101,"766004107");
	$pdf->Text(21,95.5,"V");
}


if($_bank==245 or $_bank==246)
{
	//$_a6=$bank_ind;
	//$_a6=strtoupper($_a6)." BANK";
//BNI--766004107、BRI-020602001450307
	
	//$pdf->SetFont('uniKai','B',10); 
	
	
	//$pdf->Text(7,101,"BANK ,NA");
	//$pdf->Text(82,101,"4121295554");
	
	//$pdf->Text(7,101,"VEYRON HUMAN RESOURCES AGENCY LTD");
	//$pdf->Text(82,101,"766004107");
	//$pdf->Text(21,95.5,"V");
}


}


//$pdf->Text(105,66,$_a1);
//$pdf->Text(122,81,$_a1);
//$pdf->Text(23.5,81,"ｘ");
//$pdf->Text(31,86,$_a1);
//$pdf->Text(38,96,$_a1);


//$pdf->Image('Whitedot.png', 32, 210, 60, 7);


if($_bank!=244 && $_bank!="245" && $_bank!="246")
{

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('uniKai','B',14); 
$pdf->Text(38, 227, "威龍人力仲介有限公司");
//$pdf->Image('Whitedot.png', 32, 222.6, 60, 6);
$pdf->Text(48, 240, "53420993");
//$pdf->Image('Whitedot.png', 100, 212, 45, 5.5);
//$pdf->Text(100, 217.2, "02-26892363");
//$pdf->Image('Whitedot.png', 150, 212, 45, 5.5);
$pdf->Text(120, 227, "02-26892966");
//$pdf->Image('Whitedot.png', 100, 222.5, 70, 5.8);
$pdf->Text(110, 240, "新北市樹林區三龍街115-3號2樓");
}

if($_bank==244)
{

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('uniKai','B',14); 
$pdf->Text(38, 227, "雲輔外勞仲介有限公司");
//$pdf->Image('Whitedot.png', 32, 222.6, 60, 6);
$pdf->Text(48, 240, "54810246");
//$pdf->Image('Whitedot.png', 100, 212, 45, 5.5);
//$pdf->Text(100, 217.2, "02-26892363");
//$pdf->Image('Whitedot.png', 150, 212, 45, 5.5);
$pdf->Text(120, 227, "03-4906336");
//$pdf->Image('Whitedot.png', 100, 222.5, 70, 5.8);
$pdf->SetFont('uniKai','B',12); 
$pdf->Text(110, 240, "桃園市楊梅區民族路5段285巷5號-3號2樓");
}



}


//$pdf->SetTextColor(255, 0, 0);
$pdf->SetFont('uniKai','B',12);  
$pdf->Text(38,147,$_a1);
//$pdf->Text(46,170,$_a2);
if(strlen($_a3)==0)
{
	$_a3="NGUYEN THI KHAZ STREEET, WARD 2, DISTRIC 3, HCM CITY";
	$_a3=get_new_address($_a8,$id);
	if($_bank=="235")
	{
			$_a3="JAKARTA INDONESIA";
			$_a3=get_new_address_acc($_a1,$id);
	}
}
$pdf->SetFont('uniKai','B',9);
//$pdf->Text(106,157,$_a3);

$pdf->SetXY(112,152);
$pdf->MultiCell(70, 4, $_a3, 0, 'L');

//$pdf->MultiCell(157, 500, $_a3, 0, 'L');

if(strlen($_a4)==0)
{
	$_a4="082".rand(100000000,999999999);
}

$pdf->SetFont('uniKai','B',12);

$pdf->Text(150,147,$_a4);





if(strlen($_a6)==0)
{
	if($_bank==236 or $_bank==244)
	{
		$_a6="DONGA BANK";
	}
	if($_bank==245 or $_bank==246)
	{
		$_a6="WELLS";
	}

	
}

if(preg_match("/BANK/",strtoupper($_a6)))
{}else{
	$_a6=strtoupper($_a6)." BANK";
}


if($_bank==235)
{
	//$_a6=$bank_ind;
	//$_a6=strtoupper($_a6)." BANK";
}

$pdf->Text(50,158,$_a6); //受款行

$pdf->SetFont('uniKai','B',12); 
if(strlen($_a5)==0)
{
/*
SHB--10碼
SACOM-12碼
THCHCOM-14碼
MB--13碼
BIDV-14碼
VIETTIN BANK-12碼
VIEECOM-13碼
ARGI BANK-13碼
東亞10碼
*/
	
	$_a5=rand(100000000000,999999999999);

	if($_a6=="DONGA MTC BANK" or $_a6=="DONGA MTC" or $_a6=="DONGA" or $_a6=="DONGA BANK" )
		$_a5=rand(1000000000,9999999999);

	if($_a6=="SHB BANK" or $_a6=="SHB")
		$_a5=rand(1000000000,9999999999);

	if($_a6=="SACOM BANK" or $_a6=="VIETTIN BANK" or $_a6=="SACOM" or $_a6=="VIETTIN")
		$_a5=rand(100000000000,999999999999);

	if($_a6=="MB BANK" or $_a6=="VIEECOM BANK" or $_a6=="MB" or $_a6=="ARGI BANK" or $_a6=="ARGI"  or $_a6=="VIEECOM")
		$_a5=rand(1000000000000,9999999999999);

	if($_a6=="THCHCOM BANK" or $_a6=="THCHCOM" or $_a6=="BIDV BANK" or $_a6=="BIDV")
		$_a5=rand(10000000000000,99999999999999);

}

$pdf->Text(45,170,$_a5);//受款人帳


//$pdf->Text(157,160,"3".$_a7);
$pdf->SetFont('uniKai','B',12);  

$pdf->Text(38,187,$_a8);

$pdf->Text(140,187,$_a18);


if(strlen($_a9)< 10)
{
	$_a9=get_new_phone($_orderid);

	//$_a9="09".rand(100000,999999);
}
$_a9 = str_replace("@", "",  $_a9);

$pdf->Text(42,213,$_a9);
if($_bank!="245" && $_bank!="246")
$pdf->Text(42,200,$_a10);
//$pdf->Text(125,200,$_a11);
$pdf->Text(140,198,$_a12);

$pdf->Text(145,213,$_a13);

//$pdf->Text(75,113,$_a14);

//$pdf->Text(65,241,$_a15);
//$pdf->Text(140,241,"111".$_a16);
//$pdf->Text(65,247,$_a17);


//$pdf->Text(52,259,$_a8);
//$pdf->Text(63,268,$_a19);

$pdf->Text(39,282, (date("Y", strtotime($_date))-1911));
$pdf->Text(54,282, date("m", strtotime($_date)));
$pdf->Text(70,282, date("d", strtotime($_date)));


//$pdf->SetFont('uniKai','B',8); 
$pdf->Text(172,281, (date("d", strtotime($_date))));
$pdf->Text(158,281, date("m", strtotime($_date)));
$pdf->Text(138,281, date("Y", strtotime($_date)));
/**
if($_photo1!="")
$pdf->Image('../uploads/image/'.$_photo1,31,206,84,39); 
**/

if($_bank=="242")
{

//$pdf->Image('ed_f1.png', 132, 255, 35, '');

}else{

//$pdf->Image('ed.png', 132, 255, 35, '');

}
$pdf->Output();


function get_new_address_acc($_a8,$orderid)
{
	$db1=new Database();	 
	$db1->open();
	$_c0="";
	$_c1="";
	$_c="";

	$sqls="select * from Baddress_acc_log where name='".$_a8."' limit 1 ";
	$db1->query($sqls);	
	while($row__item1 = $db1->fetchArray())
	{
		$_c0=$row__item1["address"];
	}

if(strlen($_c0)==0)
{

	$sqls="select * from Baddress_acc where use_note=1 order by use_note asc limit 1 ";
	$db1->query($sqls);	
	while($row__item1 = $db1->fetchArray())
	{
		$_c=$row__item1["ID"];
	}
	if(strlen($_c)==0)
	{
		$sqls="select * from Baddress_acc where use_note=0 order by use_note asc limit 1 ";
		$db1->query($sqls);	
		while($row__item1 = $db1->fetchArray())
		{
			$_c=$row__item1["ID"];
		}
	}
	$_c=$_c+1;

	$sqls="select * from Baddress_acc where ID='".$_c."' ";
	$db1->query($sqls);	
	while($row__item1 = $db1->fetchArray())
	{
		$_c0=$row__item1["A"];
		$_c1=$row__item1["ID"];
	}
	if(strlen($_c0)==0)
	{
		$sqls="select * from Baddress_acc where ID=2 ";
		$db1->query($sqls);	
		while($row__item1 = $db1->fetchArray())
		{
			$_c0=$row__item1["A"];
			$_c1=$row__item1["ID"];
		}
	}
	$sqls="update Baddress_acc  set use_note=0 where ID >= 1 ";
	$db1->query($sqls);

	$sqls="update Baddress_acc  set use_note=1 where ID='".$_c1."' ";
	$db1->query($sqls);

	$sql="insert into Baddress_acc_log VALUES('','".$_c0."','".$_a8."')";
	$db1->query($sql);

	


	
}

$sqls="update money_order b_address='@".$_c0."' set where mid='".$orderid."'  and  b_address='' ";
$db1->query($sqls);	

return $_c0;

}


function get_new_address($_a8,$orderid)
{
	$db1=new Database();	 
	$db1->open();
	$_c0="";
	$_c1="";
	$_c="";

	$sqls="select * from Baddress_log where name='".$_a8."' limit 1 ";
	$db1->query($sqls);	
	while($row__item1 = $db1->fetchArray())
	{
		$_c0=$row__item1["address"];
	}

if(strlen($_c0)==0)
{

	$sqls="select * from Baddress where use_note=1 order by use_note asc limit 1 ";
	$db1->query($sqls);	
	while($row__item1 = $db1->fetchArray())
	{
		$_c=$row__item1["ID"];
	}
	if(strlen($_c)==0)
	{
		$sqls="select * from Baddress where use_note=0 order by use_note asc limit 1 ";
		$db1->query($sqls);	
		while($row__item1 = $db1->fetchArray())
		{
			$_c=$row__item1["ID"];
		}
	}
	$_c=$_c+1;

	$sqls="select * from Baddress where ID='".$_c."' ";
	$db1->query($sqls);	
	while($row__item1 = $db1->fetchArray())
	{
		$_c0=$row__item1["A"];
		$_c1=$row__item1["ID"];
	}
	if(strlen($_c0)==0)
	{
		$sqls="select * from Baddress where ID=2 ";
		$db1->query($sqls);	
		while($row__item1 = $db1->fetchArray())
		{
			$_c0=$row__item1["A"];
			$_c1=$row__item1["ID"];
		}
	}
	$sqls="update Baddress  set use_note=0 where ID >= 1 ";
	$db1->query($sqls);

	$sqls="update Baddress  set use_note=1 where ID='".$_c1."' ";
	$db1->query($sqls);

	$sql="insert into Baddress_log VALUES('','".$_c0."','".$_a8."')";
	$db1->query($sql);



	
}

$sqls="update money_order b_address='@".$_c0."' set where mid='".$orderid."'  and  b_address='' ";
$db1->query($sqls);	

return $_c0;

}

function get_new_phone($orderid)
{
	$db1=new Database();	 
	$db1->open();

	$_a9="0911".rand(100000,999999);

	
	$sqls="update money_order a_tel='@".$_a9."' set where mid='".$orderid."' ";
	$db1->query($sqls);	
	
	return $_a9;

}
?>
