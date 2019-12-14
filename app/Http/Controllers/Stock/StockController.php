<?php

namespace App\Http\Controllers\Stock;

use App\Model\Stock;
use App\Model\Material;
use App\Model\Material_unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    var $title = '';
    var $text = '';

    public function __construct(Request $request)
    {
        if ($request->way == 1) {
            $this->title = '入庫';
            $this->text = 'text-success';
        } elseif ($request->way == 2) {
            $this->title = '出庫';
            $this->text = 'text-danger';
        }
    }

    public function index(Request $request)
    {
        $data = [];

        $data['way'] = $request->way ?? 0;
        $data['ways'] = Stock::ways();
        $data['type'] = $request->type ?? 0;
        $data['types1'] = Stock::types(1);
        $data['types2'] = Stock::types(2);
        $data['types'] = Stock::types($data['way']);

        $data['year'] = $request->year ?? date('Y');
        $data['month'] = $request->month ?? 0;

        $stocks = Stock::whereYear('stock_date', $data['year'])
            ->orderBy('id', 'desc');
        if ($data['way'] > 0) {
            $stocks = $stocks->where('way', $data['way']);
        }

        if ($data['type'] > 0) {
            $stocks = $stocks->where('type', $data['type']);
        }

        if ($data['month'] > 0) {
            $stocks = $stocks->whereMonth('stock_date', $data['month']);
        }

        $stocks = $stocks->get();
        $data['stocks'] = $stocks;

        return view('stock.stock.index', $data);
    }

    public function create(Request $request)
    {
        $way = $request->way ?? 0;

        $data = [];
        $data['way'] = $way;
        $data['title'] = $this->title;
        $data['text'] = $this->text;

        $data['types'] = Stock::types($way);
        $data['units'] = json_encode(Material_unit::allWithKey(), JSON_HEX_QUOT | JSON_HEX_TAG);

        return view('stock.stock.create', $data);
    }

    public function store(Request $request)
    {
        if (isset($request->material_id)) {
            for($i = 0; $i < count($request->material_id); $i++) {
                $stock = new Stock;

                $stock->lot_id = $request->lot_id;
                $stock->way = $request->way;
                $stock->type = $request->type;
                $stock->stock_date = $request->stock_date;

                // 原物料
                $m = Material::find($request->material_id[$i]);

                $stock->material_id = $request->material_id[$i];
                $stock->amount = $request->material_amount[$i];
                $stock->amount_before = $m->stock;

                if ($request->way == 1) {
                    $stock->supplier_id = $request->supplier_id;
                    $stock->amount_after = $m->stock + $stock->amount;
                } elseif ($request->way == 2) {
                    $stock->customer_id = $request->customer_id;
                    $stock->amount_after = $m->stock - $stock->amount;
                }

                $stock->memo = $request->material_memo[$i];

                $m->stock = $stock->amount_after;
                $m->save();

                $stock->save();
            }
        }

        return redirect($request->referrer)->with('message', $this->title . '成功');
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
