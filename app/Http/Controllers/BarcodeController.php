<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use CodeItNow\BarcodeBundle\Utils\QrCode;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;

class BarcodeController extends Controller
{
    public function makeBarcode(Request $request)
    {
        $code = $request->code;
        $barcode = new BarcodeGenerator();
        $barcode->setText($code);
        $barcode->setType(BarcodeGenerator::Code128);
        $barcode->setScale(2);
        $barcode->setThickness(25);
        $barcode->setFontSize(12);
        $code = $barcode->generate();
        echo '<div id="popup">';
        echo '<p>'.$request->title.'</p>';
        echo '<img align="center" src="data:image/png;base64, '.$code.'" />';
        echo '</div>';        
    }

    
}
