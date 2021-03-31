<?php

namespace App\Http\Controllers\Shopping;

use App\Model\Out;
use App\Model\Lot;
use App\Model\Customer;
use App\Model\Pay;
use App\Model\User;

use App\Model\Material;
use App\Model\Material_unit;
use App\Model\Material_module;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OutController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->status ?? 0;
        $pay_status = $request->pay_status ?? 0;

        $data = [];

        $outs = Out::orderBy('id', 'desc');
        if ($status != 0) $outs->where('status', $status);

        // 付清
        if ($pay_status == 1) {
            $outs->where('balance', '<=', 0);
        } elseif ($pay_status == 2) {
            $outs->where('balance', '>', 0);
        }

        $outs = $outs->get();

        $data['outs'] = $outs;
        $data['statuses'] = Out::statuses();
        $data['pay_statuses'] = Out::pay_statuses();
        $data['status'] = $status;
        $data['pay_status'] = $pay_status;

        return view('shopping.out.index', $data);
    }

    public function create()
    {
        $data = [];

        $out = new Out;

        $out->status = 10;
        $out->tax = 0;

        $data['out'] = $out;
        $data['statuses'] = Out::statuses();
        $data['lots'] = Lot::allWithKey();
        $data['customers'] = Customer::allWithKey();
        $data['invoice_types'] = json_encode(Pay::types(), JSON_HEX_QUOT | JSON_HEX_TAG);
        $data['tax'] = $out->tax == 1 ? 1.05 : 1;
        $data['pays'] = json_encode([]);
        $data['material_modules'] = json_encode([]);
        $data['total_cost'] = 0;
        $data['total_price'] = 0;

        return view('shopping.out.create', $data);
    }

    public function store(Request $request)
    {
        $result = $this->save(0, $request);

        if ($result) {
            return redirect()->route('out.index')->with('message', '修改成功');
        } else {
            return redirect()->route('out.index')->with('error', '庫存不足，無法存檔');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\In  $in
     * @return \Illuminate\Http\Response
     */
    public function show(In $in)
    {
        $data['in'] = $in;
        $data['statuses'] = In::statuses();
        $data['lots'] = Lot::allWithKey();
        $data['suppliers'] = Supplier::allWithKey();
        $data['manufacturers'] = Manufacturer::allWithKey();
        $data['invoice_types'] = json_encode(Pay::types(), JSON_HEX_QUOT | JSON_HEX_TAG);

        $data['materials'] = Material::appendMaterials($in->materials);
        $data['units'] = json_encode(Material_unit::allWithKey(), JSON_HEX_QUOT | JSON_HEX_TAG);
        $data['pays'] = Pay::appendPays($in->pays);
        $data['total_cost'] = Material::getTotalCost($in->materials);
        $data['total_price'] = Material::getTotalPrice($in->materials);

        return view('purchase.in.show', $data);
    }

    public function edit(Out $out)
    {
        $data = [];

        ///////////////
        // $data['out'] = $out;
        // $data['statuses'] = Out::statuses();
        // $data['lots'] = Lot::allWithKey();
        // $data['customers'] = Customer::allWithKey();
        // $data['invoice_types'] = json_encode(Pay::types(), JSON_HEX_QUOT | JSON_HEX_TAG);

        // $data['pays'] = json_encode([]);
        // $data['material_modules'] = json_encode([]);
        // $data['total_cost'] = 0;
        // $data['total_price'] = 0;
        ///////////////

        $data['out'] = $out;
        $data['statuses'] = Out::statuses();
        $data['lots'] = Lot::allWithKey();
        $data['customers'] = Customer::allWithKey();

        $data['material_modules'] = Material_module::appendMaterialModules($out->material_modules);
        $data['pays'] = Pay::appendPays($out->pays);
        $data['total_cost'] = 0;
        $data['total_price'] = 0;
        $data['tax'] = $out->tax == 1 ? 1.05 : 1;
        $data['invoice_types'] = json_encode(Pay::types(), JSON_HEX_QUOT | JSON_HEX_TAG);

        return view('shopping.out.edit', $data);
    }

    public function update(Request $request, Out $out)
    {
        $result = $this->save($out->id, $request);

        if ($result) {
            return redirect()->route('out.index')->with('message', '修改成功');
        } else {
            return redirect()->route('out.index')->with('error', '庫存不足，無法存檔');
        }
    }

    public function destroy(Out $out, Request $request)
    {
        if (!User::canAdmin('shopping')) {
            return false;
        }

        $out->deleted_user = session('admin_user')->id;
        $out->save();
        $out->delete();

        return redirect()->route('out.index')->with('message', '刪除成功');
    }

    public function cancel(Out $out, Request $request)
    {
        // 改變庫存，更新庫存
        $stocks = Material_module::storeToStock($out, 1, 30);
        $out->status = 60;
        $out->save();

        return redirect()->route('out.index')->with('message', '已取消');
    }

    public function save($id, $request)
    {
        if (!User::canAdmin('shopping')) {
            return false;
        }

        // 新增或修改
        if ($id == 0) {
            $out = new Out;

            $code = date("Ymd") . "001";
            $last_code = Out::orderBy('code', 'DESC')->first();
            if ($last_code) {
                if ($last_code->code >= $code) {
                    $code = $last_code->code + 1;
                }
            }
            $out->code = $code;
        } else {
            $out = Out::find($id);
            $code = $out->code;
        }

        $old_status = $out->status;
        $new_status = $request->status;

        // 打包物料模組
        if ($out->status == 40) {
            // 不更新物料模組
        } else {
            $out->material_modules = Material_module::packMaterialModules($request);
        }

        $out->tax = $request->tax;

        // 打包付款資料
        $out->pays = Pay::packPays($request);

        $tax = $out->tax == 1 ? 1.05 : 1;

        $out->total_cost = $request->total_cost;
        $out->total_price = $request->total_price * $tax;
        $out->total_pay = $out->total_pay();
        $out->balance = $out->total_price - $out->total_pay;

        $out->lot_id = $request->lot_id ?? 0;
        $out->customer_id = $request->customer_id ?? 0;
        $out->created_date = $request->created_date;
        $out->expired_date = $request->expired_date;
        $out->memo = $request->memo;

        $out->status = $request->status;
        $out->created_user = session('admin_user')->id;

        // 改變庫存，更新庫存
        if ($old_status != 40 && $new_status == 40) {
            $stocks = Material_module::storeToStock($out, 2, 2);

            if ($stocks) {
                $out->save();
                return $out;
            } else {
                // $out->delete();
                return false;
            }
        } else {
            $out->save();
            return $out;
        }
    }
}
