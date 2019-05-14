<?php
header("Cache-control: private");
$con = @mysqli_connect('localhost', 'root', '1ulru4', 'db_account');

if (!$con) {
    echo "Error: " . mysqli_connect_error();
    exit();
}

$_iid = $_REQUEST["id"];
$_id = explode(",", $_REQUEST["id"]);

require_once 'chinese-unicode.php';
require_once 'fpdi.php';

// initiate FPDI
$pdf = new FPDI('P', 'mm', [250, 176]);
$pdf->AddBig5Font();

$last_print = 0;

// Some Query
foreach ($_id as $_id_value) {
    $sql = "SELECT * from buys WHERE id='" . $_id_value . "'";
    mysqli_query($con, "SET NAMES 'utf8'");
    $query = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($query);

    $_materials = unserialize($row["materials"]);
    $total_materials = count($_materials['material']);

    $more_page = array("1");

    $_nono = 12345;
    $i = 0;
    $_inikey = 0;

    $valuekey = 1;
    if ($total_materials > 6) {
        $more_page = array("1", "2");
    }
    if ($total_materials > 12) {
        $more_page = array("1", "2", "3");
    }
    if ($total_materials > 18) {
        $more_page = array("1", "2", "3", "4");
    }
    if ($total_materials > 24) {
        $more_page = array("1", "2", "3", "4", "5");
    }
    if ($total_materials > 30) {
        $more_page = array("1", "2", "3", "4", "5", "6");
    }
    foreach ($more_page as $value) {
        $last_print++;
        if ($value > 1) {
            $_inikey = $j;
        }

        // add a page
        $pdf->AddPage();
        // set the source file
        $pdf->setSourceFile("buyA42.pdf");
        // import page 1
        $tplIdx = $pdf->importPage(1);
        // use the imported page and place it at point 10,10 with a width of 100 mm
        $pdf->useTemplate($tplIdx, 0, 0, 210);
        //$pdf->SetFont('Arial');
        $pdf->SetFont('Big5', '', 16);

        //��
        //$_nono=$_nono+1;

        $_nono = $row["lot_number"];

        $pdf->SetXY(157, 37.5);
        $pdf->Write(0, $_nono);

        $pdf->SetFont('Big5', '', 10);

        $pdf->SetXY(117, 45);
        $pdf->Write(0, substr($row["buyDate"], 0, 4));

        $pdf->SetXY(129, 45);
        $pdf->Write(0, substr($row["buyDate"], 5, 2));

        $pdf->SetXY(138, 45);
        $pdf->Write(0, substr($row["buyDate"], 8, 2));
        $pdf->SetFont('Big5', '', 12);

        $pdf->SetFont('Big5', '', 10);
        $pdf->SetXY(166.5, 45);
        $pdf->Write(0, substr($row["expectedReceiveDate"], 0, 4));

        $pdf->SetXY(177, 45);
        $pdf->Write(0, substr($row["expectedReceiveDate"], 5, 2));

        $pdf->SetXY(186, 45);
        $pdf->Write(0, substr($row["expectedReceiveDate"], 8, 2));
        $pdf->SetFont('Big5', '', 12);

        $pdf->Image('1.png', 15.4, 75.6, 184.2, 10.8);
        $pdf->Image('1.png', 15.4, 98.1, 184.2, 10.8);
        $pdf->Image('1.png', 15.4, 120.5, 184.2, 10.8);
        $pdf->Image('1.png', 15.4, 154.1, 184.2, 10.8);
        $pdf->Image('1.png', 15.4, 176.6, 184.2, 10.8);
        $pdf->Image('1.png', 15.4, 198.9, 184.2, 10.8);

        $pdf->SetXY(50, 237);
        $pdf->Write(0, mb_convert_encoding($row["memo"], "BIG5", "utf8"));

        $sql = "SELECT * from suppliers WHERE id='" . $row["supplier"] . "'";
        mysqli_query($con, "SET NAMES 'utf8'");
        $query = mysqli_query($con, $sql);
        $row_s = mysqli_fetch_array($query);

        $pdf->SetXY(28, 44.5);
        $pdf->Write(0, mb_convert_encoding($row_s["shortName"], "BIG5", "auto"));

        $pdf->SetFont('Big5', '', 16);
        $yy = 70;

        //多雮

        $_keycheck_last = $j + 5;
        if ($_keycheck_last > $total_materials) {
            $_keycheck_last = $total_materials;
        }

        for ($key = $_inikey; $key <= $_keycheck_last; $key++) {
            $j = $key + 1;

            $sql = "SELECT * from materials WHERE id='" . $_materials["material"][$key] . "'";
            mysqli_query($con, "SET NAMES 'utf8'");
            $query = mysqli_query($con, $sql);
            $row_m = mysqli_fetch_array($query);

            $sql = "SELECT * from material_units WHERE id='" . $row_m["unit"] . "'";
            $query = mysqli_query($con, $sql);
            $row_u = mysqli_fetch_array($query);

            if ($_materials["material"][$key] > 0) {
                //�
                $pdf->SetFont('Big5', '', 14);
                $pdf->SetXY(18, $yy);
                $pdf->Write(0, $j);
                //$materials = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialUnit'=>$materialUnit,'materialPrice'=>$materialPrice];
                //貨�編�
                $pdf->SetFont('Big5', '', 11);
                $pdf->SetXY(40, $yy);
                $pdf->Write(0, $row_m["fullCode"]);

                $pdf->SetFont('Big5', '', 12);
                //��格
                $pdf->SetXY(80, $yy);
                $pdf->Write(0, mb_convert_encoding($row_m["fullName"], "BIG5", "auto"));
                $pdf->SetFont('Big5', '', 11);
                //��
                $pdf->SetXY(152, $yy);
                $pdf->Write(0, $_materials["materialCalAmount"][$key]);

                $pdf->SetFont('Big5', '', 12);
                //���
                $pdf->SetXY(168, $yy);
                $pdf->Write(0, mb_convert_encoding($row_u["name"], "BIG5", "auto"));

                // $pdf->SetXY(163.5, $yy);
                // $pdf->Write(0, "11" . mb_convert_encoding($_materials["materialUnit"][$key], "BIG5", "auto"));

                //��
                // $_a = explode(".", $_materials["materialPrice"][$key]);
                $_a = $_materials["materialPrice"][$key];
                $pdf->SetXY(176, $yy);
                $pdf->Write(0, $_a);
                $pdf->SetFont('Big5', '', 10);
                //小�
                $pdf->SetXY(187, $yy);
                $pdf->Write(0, round($_a * $_materials["materialCalAmount"][$key], 2));

                $yy = $yy + 12.5;
            }
        }
    }
} //end $_id_value
$pdf->output();
