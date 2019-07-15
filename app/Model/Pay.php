<?php

namespace App\Model;

class Pay
{
    static public function types()
    {
        $data = [];

        $data['A'] = '電子發票';
        $data['B'] = '二聯發票';
        $data['C'] = '三聯發票';
        $data['D'] = '沒發票';
        $data['E'] = '收據';

        return $data;
    }

    // 打包付款資料
    static public function packPays($request)
    {
        // 打包付款資料
        $pays = [];
        if (isset($request->pay_date)) {
            for($i = 0; $i < count($request->pay_date); $i++) {
                $pays[] = [
                    'pay_date' => $request->pay_date[$i],
                    'pay_money' => $request->pay_money[$i] ?? 0,
                    'pay_invoice_type' => $request->pay_invoice_type[$i] ?? 0,
                    'pay_invoice_no' => $request->pay_invoice_no[$i],
                    'pay_memo' => $request->pay_memo[$i]
                ];
            }
        }

        return serialize($pays);
    }

    static public function appendPays($pays, $php = false)
    {
        $data = [];
        $pays = unserialize($pays);

        $i = 0;
        foreach((array) $pays as $pay) {
            $data[$i]['pay_date'] = $pay['pay_date'];
            $data[$i]['pay_money'] = $pay['pay_money'];
            $data[$i]['pay_invoice_type'] = $pay['pay_invoice_type'];
            $data[$i]['pay_invoice_no'] = $pay['pay_invoice_no'];
            $data[$i]['pay_memo'] = $pay['pay_memo'];

            $i++;
        }

        if ($php) {
            return $data;
        } else {
            $data = json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);
            return $data;
        }
    }

    // 付款總金額
    static public function getTotalPay($pays)
    {
        $data = [];
        $pays = unserialize($pays);

        $total_pay = 0;
        foreach((array) $pays as $pay) {
            $pay_money = $pay['pay_money'] ?? 0;
            $total_pay += $pay_money;
        }

        return $total_pay;
    }
}
