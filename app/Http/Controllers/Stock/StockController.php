<?php

namespace App\Http\Controllers\Stock;

use App\Model\Stock;
use App\Model\Material;
use App\Model\Material_unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];

        $data['function'] = 'in';
        $data['title'] = '採購進貨 - 入庫';
        $data['types'] = Stock::types();
        $data['type'] = $request->type ?? 0;

        $stocks = Stock::whereIn('type', [1, 2, 3]);
        if ($request->type > 0) {
            $stocks = $stocks->where('type', $request->type);
        }
        $stocks = $stocks->orderBy('id', 'desc')->get();
        $data['stocks'] = $stocks;

        return view('stock.stock.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['function'] = 'in';
        $data['title'] = '採購進貨 - 新增入庫';

        $data['types'] = Stock::types();
        $data['units'] = json_encode(Material_unit::allWithKey(), JSON_HEX_QUOT | JSON_HEX_TAG);

        return view('stock.stock.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->material_id)) {
            for($i = 0; $i < count($request->material_id); $i++) {
                $stock = new Stock;

                $stock->lot_id = $request->lot_id;
                $stock->supplier_id = $request->supplier_id;
                $stock->type = $request->type;
                $stock->stock_date = $request->stock_date;

                // 原物料
                $m = Material::find($request->material_id[$i]);

                $stock->material_id = $request->material_id[$i];
                $stock->amount = $request->material_amount[$i];
                $stock->amount_before = $m->stock;
                $stock->amount_after = $m->stock + $stock->amount;
                $stock->memo = $request->material_memo[$i];

                $m->stock = $stock->amount_after;
                $m->save();

                $stock->save();
            }
        }

        return redirect($request->referrer)->with('message', '入庫成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
    }

    public function save($id, $request)
    {
        // 新增或修改
        if ($id == 0) {
            $stock = new Stock;
        } else {
            exit();
        }

        return $stock;
    }
}
