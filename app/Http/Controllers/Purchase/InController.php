<?php

namespace App\Http\Controllers\Purchase;

use App\Model\In;
use App\Model\Lot;
use App\Model\Supplier;
use App\Model\Manufacturer;
use App\Model\Pay;

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
        $status = $request->status ?? '';

        $data = [];

        $ins = In::orderBy('id', 'desc');
        if ($status != '') $ins->where('status', $status);
        $ins = $ins->get();

        $data['ins'] = $ins;
        $data['statuses'] = In::statuses();
        $data['status'] = $status;

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
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\In  $in
     * @return \Illuminate\Http\Response
     */
    public function destroy(In $in, Request $request)
    {
        $in->deleted_user = session('admin_user')->id;
        $in->save();
        $in->delete();

        return redirect($request->referrer)->with('message', '刪除成功');
    }

    public function save($id, $request)
    {
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
            Material::storeToStock($in, 2);
        }

        return $in;
    }
}
