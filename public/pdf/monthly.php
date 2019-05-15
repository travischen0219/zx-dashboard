<?php
require_once(__DIR__ . '/con.php');

$_start = $_REQUEST['start'];
$_end = $_REQUEST['end'];
$_lot_number = $_REQUEST['lot_number'];
$_supplier = $_REQUEST['supplier'];
$_col = $_REQUEST['col'];
require_once 'chinese-unicode.php';
require_once 'fpdi.php';

// initiate FPDI210  297
$pdf = new FPDI('P', 'mm', [210, 297]);
$pdf->AddBig5Font();

$sql = 'SELECT * from suppliers';
mysqli_query($con, "SET NAMES 'utf8'");
$query = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($query)) {
    $ids = $row['id'];
    $supplier_name[$ids] = mb_convert_encoding($row['shortName'], 'BIG5', 'auto');
}

$sql = "SELECT * from account_payables WHERE (createDate BETWEEN '".$_start."' AND '".$_end."')";

if ($_supplier != 'all') {
    $sql .= " AND supplier = '".$_supplier."'";
}
if ($_lot_number != '') {
    $sql .= " AND lot_number = '".$_lot_number."'";
}

mysqli_query($con, "SET NAMES 'utf8'");
$query = mysqli_query($con, $sql);
$total_materials = 0;

$material_name = [];

while ($row = mysqli_fetch_array($query)) {
    $_materials = unserialize($row['materials']);

    for ($m = 0; $m < count($_materials['material']); ++$m) {
        if ($_materials['materialAmount'][$m] > 0) {
            $sql_m = "SELECT * from materials WHERE id='".$_materials['material'][$m]."'";
            mysqli_query($con, "SET NAMES 'utf8'");
            $query_m = mysqli_query($con, $sql_m);
            $row_m = mysqli_fetch_array($query_m);
            $_mm = explode('#', $row_m['fullName']);
            if ($_col == 'name') {
                $material_name[] = $_mm[0];
            } else {
                $material_name[] = '';
            }

            $material_code[] = $row_m['fullCode'];
            $material_cal_amount[] = number_format($_materials['materialCalAmount'][$m], 2, '.', '');

            // if($row["total"] == null){
            if ($row['return_status'] != 1) {
                $material_amount[] = number_format($_materials['materialAmount'][$m], 2, '.', '');
                $material_price[] = number_format($_materials['materialPrice'][$m], 2, '.', '');
                $total[] = number_format(($_materials['materialAmount'][$m] * $_materials['materialPrice'][$m]), 2, '.', '');
            } else {
                $material_amount[] = number_format(-$_materials['materialAmount'][$m], 2, '.', '');
                $material_price[] = number_format($_materials['materialPrice'][$m], 2, '.', '');
                $total[] = number_format((-$_materials['materialAmount'][$m] * $_materials['materialPrice'][$m]), 2, '.', '');
            }

            // } else {
            // 	if($row["return_status"] != 1){
            // 		$material_amount[] = number_format($_materials['materialAmount'][$m],2,'.','');
            // 		$material_price[] = number_format($_materials['materialPrice'][$m],2,'.','');
            // 		$total[] = number_format($row["total"],2,'.','');
            // 	} else {
            // 		$material_amount[] = number_format(-$_materials['materialAmount'][$m],2,'.','');
            // 		$material_price[] = number_format($_materials['materialPrice'][$m],2,'.','');
            // 		$total[] = number_format($row["total"],2,'.','');
            // 	}

            // }

            $lot_number[] = $row['lot_number'];
            $supplier[] = $row['supplier'];

            ++$total_materials;
        }
    }
}

$page_max_materials = 36;
$page_count = ceil($total_materials / $page_max_materials);

$pdf->setSourceFile('monthly.pdf');

for ($j = 1; $j <= $page_count; ++$j) {
    $tplIdx = $pdf->importPage($j);
    $pdf->AddPage();
    $pdf->useTemplate($tplIdx, 0, 0, 210);
    $pdf->SetFont('Big5', '', 12);

    if ($j == 1) {
        $start_index = 0;
        $end_index = 35;
    } else {
        $start_index = ($j - 1) * 36;
        $end_index = $j * 36 - 1;
    }

    $pdf->SetXY(32, 13);
    $pdf->Write(0, date('Y-m-d'));

    $pdf->SetXY(90, 20);
    $pdf->Write(0, $_start.' ~ '.$_end);

    $pdf->SetXY(190, 20);
    $pdf->Write(0, $j.'/'.$page_count);

    $y = 34;
    for ($i = $start_index; $i <= $end_index; ++$i) {
        $no = $i + 1;
        $pdf->SetFont('Big5', '', 11);
        $pdf->SetXY(16, $y);
        $pdf->Write(0, (int) $no);

        $pdf->SetFont('Big5', '', 11);
        $pdf->SetXY(28, $y);
        $pdf->Write(0, $lot_number[$i], 'BIG5', 'auto');

        $pdf->SetFont('Big5', '', 9);
        $_ss = $supplier[$i];
        $pdf->SetXY(50.5, $y);
        $pdf->Write(0, $supplier_name[$_ss]);

        $pdf->SetFont('Big5', '', 11);
        $pdf->SetXY(72, $y);
        $pdf->Write(0, mb_convert_encoding($material_code[$i], 'BIG5', 'auto'));

        $pdf->SetFont('Big5', '', 8);
        $pdf->SetXY(92.5, $y);
        $pdf->Write(0, mb_convert_encoding($material_name[$i], 'BIG5', 'auto'));

        $pdf->SetFont('Big5', '', 11);
        $pdf->SetXY(116, $y);
        // $pdf->Write(0,mb_convert_encoding($material_cal_amount[$i],"BIG5","auto"));
        $pdf->Cell(19, 0, mb_convert_encoding($material_cal_amount[$i], 'BIG5', 'auto'), 0, 0, 'R');

        $pdf->SetFont('Big5', '', 11);
        $pdf->SetXY(138, $y);
        // $pdf->Write(0,mb_convert_encoding($material_amount[$i],"BIG5","auto"));
        $pdf->Cell(19, 0, mb_convert_encoding($material_amount[$i], 'BIG5', 'auto'), 0, 0, 'R');

        $pdf->SetFont('Big5', '', 11);
        $pdf->SetXY(160, $y);
        // $pdf->Write(0,mb_convert_encoding($material_price[$i],"BIG5","auto"));
        $pdf->Cell(19, 0, mb_convert_encoding($material_price[$i], 'BIG5', 'auto'), 0, 0, 'R');

        $pdf->SetFont('Big5', '', 11);
        $pdf->SetXY(182, $y);
        // $pdf->Write(0,mb_convert_encoding($total[$i],"BIG5","auto"));
        $pdf->Cell(19, 0, mb_convert_encoding($total[$i], 'BIG5', 'auto'), 0, 0, 'R');

        $y += 6.83;
    }
}
$pdf->output();
