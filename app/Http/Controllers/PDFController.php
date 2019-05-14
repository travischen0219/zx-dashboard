<?php

namespace App\Http\Controllers;

use App\Model\Material;
use setasign\Fpdi\Fpdi;
use Illuminate\Http\Request;
use setasign\Fpdi\PdfReader;
use CodeItNow\BarcodeBundle\Utils\QrCode;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;

class PDFController extends Controller
{
    public function barcode_pdf($id)
    {
        header("Cache-control: private");
        require_once('../vendor/setasign/fpdf/fpdf.php');
       
        $_id=explode(",",$id);

        // initiate FPDI
        $pdf = new Fpdi('P', 'mm', [210,297]);

        $total_material = count($_id);

        $more_page=array("1");
        $page_max_materials = 16;

        $page = ceil($total_material / $page_max_materials);

        $id_array = [];

        for($i = 1 ; $i <= $page ; $i++){

            // add a page
            $pdf->AddPage();
            // set the source file
            $pdf->setSourceFile("pdf/barcodeA4.pdf");
            // import page 1
            $tplIdx = $pdf->importPage(1);
            // use the imported page and place it at point 10,10 with a width of 100 mm
            $pdf->useTemplate($tplIdx,0,0,210);
            $pdf->SetFont('Arial');
    
            $y = 13;
    
            if($i == 1){
                $materials = Material::whereIn('id',$_id)->orderBy('fullCode', 'ASC')->take(16)->get();
            } else {
                $skip = ($i-1) * 16;
                $materials = Material::whereIn('id',$_id)->orderBy('fullCode', 'ASC')->skip($skip)->take(16)->get();
            }

            foreach($materials as $key => $material){

                $id_array[] = $material->id;

                $code = $material->fullCode;
                $barcode = new BarcodeGenerator();
                $barcode->setText($code);
                $barcode->setType(BarcodeGenerator::Code128);
                $barcode->setScale(2);
                $barcode->setThickness(25);
                $barcode->setFontSize(12);
                $code = $barcode->generate();
                $pic = "data:image/png;base64, ".$code;
                if(($key % 2) == 0){
                    $pdf->Image($pic, 17, $y, 70, 20, 'png');
                } else {
                    $pdf->Image($pic, 122, $y, 70, 20, 'png');
                    $y += 36.4;
                }
            }
        }

        $id = implode(',',$id_array);


        // $pdf->output();
            
        $pdf->output('pdf/has_barcode.pdf','F');

        echo '<meta http-equiv=REFRESH CONTENT=0;url=../pdf/barcode.php?id='.$id.'>';
        exit;


        
    }


}
