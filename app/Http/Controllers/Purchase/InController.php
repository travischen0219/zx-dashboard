<?php

namespace App\Http\Controllers\Purchase;

use App\Model\In;
use App\Model\Lot;
use App\Model\Supplier;
use App\Model\Manufacturer;
use App\Model\Pay;
use App\Model\User;
use App\Model\Stock;

use App\Model\Material;
use App\Model\Material_unit;
use App\Model\Material_module;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->status ?? 0;
        $pay_status = $request->pay_status ?? 0;

        $data = [];

        $ins = In::orderBy('id', 'desc');
        if ($status != 0) $ins->where('status', $status);

        // 付清
        if ($pay_status == 1) {
            $ins->where('balance', '<=', 0);
        } elseif ($pay_status == 2) {
            $ins->where('balance', '>', 0);
        }

        $ins = $ins->get();

        $data['ins'] = $ins;
        $data['statuses'] = In::statuses();
        $data['pay_statuses'] = In::pay_statuses();
        $data['status'] = $status;
        $data['pay_status'] = $pay_status;

        return view('purchase.in.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];

        $in = new In;

        $in->id = 0;
        $in->status = 10;

        $data['in'] = $in;
        $data['statuses'] = In::statuses();
        $data['lots'] = Lot::allWithKey();
        $data['suppliers'] = Supplier::allWithKey();
        $data['manufacturers'] = Manufacturer::allWithKey();
        $data['invoice_types'] = json_encode(Pay::types(), JSON_HEX_QUOT | JSON_HEX_TAG);

        $data['pays'] = json_encode([]);
        $data['materials'] = json_encode([]);
        $data['units'] = json_encode(Material_unit::allWithKey(), JSON_HEX_QUOT | JSON_HEX_TAG);
        $data['total_cost'] = 0;
        $data['in_stocks'] = 0;

        return view('purchase.in.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->save(0, $request);
        return redirect($request->referrer)->with('message', '修改成功');
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

        return view('purchase.in.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\In  $in
     * @return \Illuminate\Http\Response
     */
    public function edit(In $in)
    {
        $data = [];

        $data['in'] = $in;
        $data['statuses'] = In::statuses();
        $data['lots'] = Lot::allWithKey();
        $data['suppliers'] = Supplier::allWithKey();
        $data['manufacturers'] = Manufacturer::allWithKey();

        $data['materials'] = Material::appendMaterials($in->materials);

        $inMaterials = unserialize($in->materials);
        $in_stocks = [];

        foreach ($inMaterials as $inMaterial) {
            $in_stocks["'" . $inMaterial['id'] . "'"] = Stock::where('in_id', $in->id)
                ->where('material_id', $inMaterial['id'])
                ->sum('amount');
        }

        $data['in_stocks'] = json_encode($in_stocks, JSON_HEX_QUOT | JSON_HEX_TAG);
        $data['units'] = json_encode(Material_unit::allWithKey(), JSON_HEX_QUOT | JSON_HEX_TAG);

        $data['pays'] = Pay::appendPays($in->pays);
        $data['total_cost'] = Material::getTotalCost($in->materials);

        $data['invoice_types'] = json_encode(Pay::types(), JSON_HEX_QUOT | JSON_HEX_TAG);

        return view('purchase.in.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\In  $in
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, In $in)
    {
        $this->save($in->id, $request);
        return redirect($request->referrer)->with('message', '修改成功');
    }

    /**
     * 單獨入庫
     */
    public function aloneIn(Request $request)
    {
        if (!User::canAdmin('purchase')) {
            return false;
        }

        $in = In::find($request->in_id);
        $material = Material::find($request->material_id);
        $unit = $material->material_unit_name->name;
        $stocks = Stock::where('in_id', $request->in_id)
            ->where('material_id', $request->material_id)
            ->get();
        $data = [
            'in' => $in,
            'material' => $material,
            'unit' => $unit,
            'stocks' => $stocks
        ];

        return view('purchase.in.alone', $data);
    }

    public function aloneInStore(Request $request)
    {
        if (!User::canAdmin('purchase')) {
            return false;
        }

        $in = In::find($request->in_id);
        $material = Material::find($request->material_id);

        $stock = new Stock;
        $stock->lot_id = $in->lot_id ?? 0;
        $stock->in_id = $in->id ?? 0;
        $stock->out_id = 0;
        $stock->way = 1;    // 1入庫
        $stock->type = 2;  // 2採購轉入庫
        $stock->material_id = $material->id;
        $stock->supplier_id = $in->supplier_id ?? 0;
        $stock->customer_id = $in->customer_id ?? 0;
        $stock->amount = $request->amount;
        $stock->amount_before = $material->stock;

        $stock->amount_after = $stock->amount_before + $request->amount;

        $stock->stock_date = date('Y-m-d');
        $stock->memo = '';

        $stock->save();

        // 更新物料庫存
        $material->stock = $stock->amount_after;
        $material->save();

        return redirect("/purchase/aloneIn/{$in->id}/{$material->id}")->with('message', '入庫成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\In  $in
     * @return \Illuminate\Http\Response
     */
    public function destroy(In $in, Request $request)
    {
        if (!User::canAdmin('purchase')) {
            return false;
        }

        $in->deleted_user = session('admin_user')->id;
        $in->save();
        $in->delete();

        return redirect($request->referrer)->with('message', '刪除成功');
    }

    public function save($id, $request)
    {
        if (!User::canAdmin('purchase')) {
            return false;
        }

        // 新增或修改
        if ($id == 0) {
            $in = new In;

            $code = date("Ymd") . "001";
            $last_code = In::orderBy('code', 'DESC')->first();
            if ($last_code) {
                if ($last_code->code >= $code) {
                    $code = $last_code->code + 1;
                }
            }
            $in->code = $code;
        } else {
            $in = In::find($id);
            $code = $in->code;
        }

        $old_status = $in->status;
        $new_status = $request->status;

        // 打包物料模組
        if ($in->status == 40) {
            // 不更新物料模組
        } else {
            $in->materials = Material::packMaterials($request);
        }

        // 打包付款資料
        $in->pays = Pay::packPays($request);

        $in->total_cal = $in->total_cal();
        $in->total_cost = $in->total_cost();
        $in->total_pay = $in->total_pay();
        $in->balance = $in->total_cost - $in->total_pay;

        $in->lot_id = $request->lot_id ?? 0;
        $in->supplier_id = $request->supplier_id ?? 0;
        $in->manufacturer_id = $request->manufacturer_id ?? 0;
        $in->buy_date = $request->buy_date;
        $in->should_arrive_date = $request->should_arrive_date;
        $in->arrive_date = $request->arrive_date;
        $in->memo = $request->memo;

        $in->status = $request->status;
        $in->created_user = session('admin_user')->id;
        $in->save();

        // 改變庫存，更新庫存
        if ($old_status != 40 && $new_status == 40) {
            // Material::storeToStock($in, 1, 2);
        }

        return $in;
    }
}
