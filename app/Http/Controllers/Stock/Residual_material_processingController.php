<?php

namespace App\Http\Controllers\Stock;

use App\Model\Stock;
use App\Model\Material;
use Illuminate\Http\Request;
use App\Model\Material_warehouse;
use App\Http\Controllers\Controller;

class Residual_material_processingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $search_code = "all";
        $stocks = Stock::where('delete_flag','0')->where('stock_option','31')->orderBy('updated_at','desc')->get();
        return view('stock.residual_material.show',compact('stocks'));
    }

    public function search(Request $request)
    {   
        $stocks = Stock::where('delete_flag','0')->where('stock_option','31')->where('lot_number','like','%'.$request->search_lot_number.'%')->orderBy('updated_at','desc')->get();
        return view('stock.residual_material.show',compact('stocks'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stock.residual_material.create');                
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'lot_number' => 'required',                      
            'processing_date' => 'date_format:"Y-m-d"|required',
        ];
        $messages = [
            'lot_number.required' => '批號 必填',                     
            'processing_date.required' => '調撥日期 必填',
            'processing_date.date_format' => '調撥日期格式錯誤',
        ];
        $this->validate($request, $rules, $messages);

        $total_materials_check = count($request->material);
        $material = [];
        $warehouse = [];
        $materialAmount = [];

        for($i=0; $i < $total_materials_check; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $warehouse[] = $request->materialWarehouse[$i];
                $materialAmount[] = $request->materialAmount[$i];

            }
        }

        if(count($material) > 0){
            try{
                $total = count($material);
                for($i=0; $i < $total; $i++){
                    $material_stock = Material::find($material[$i]);
                    $material_warehouse = Material_warehouse::where('delete_flag','0')->where('material_id',$material[$i])->where('warehouse_id',$warehouse[$i])->first();
                    $warehouse_start_quantity = $material_warehouse->stock;
                    $quantity = number_format($materialAmount[$i],2,'.','');
                    $warehouse_end_quantity = $warehouse_start_quantity - $quantity;
                    $unit = $material_stock->material_unit_name->name;
                    $str = $warehouse_start_quantity.' '.$unit.' -> '.number_format($warehouse_end_quantity,2,'.','').' '.$unit;

                    $stock = new Stock;
                    $stock->lot_number = $request->lot_number;
                    $stock->stock_option = 31;
                    $stock->status = 0;
                    $stock->stock_no = $material_stock->stock_no + 1;                                                                      
                    $stock->material = $material[$i];
                    $stock->warehouse = $warehouse[$i];
                    $stock->total_start_quantity = $material_stock->stock;                                    
                    $stock->start_quantity = $warehouse_start_quantity;
                    $stock->quantity = $quantity;
                    $stock->calculate_quantity = $str;
                    $stock->stock_date = $request->processing_date;
                    $stock->memo = $request->memo;                    
                    $stock->created_user = session('admin_user')->id;
                    $stock->delete_flag = 0;
                    $stock->save();

                    $material_warehouse->stock = $warehouse_end_quantity;
                    $material_warehouse->save();

                    $material_end_quantity = $material_stock->stock - $quantity;
                    $material_stock->stock_no = $material_stock->stock_no + 1;
                    $material_stock->stock = $material_end_quantity;
                    $material_stock->save();
                }
                return redirect()->route('residual_material_processing.index')->with('message', '新增成功');
            } catch(Exception $e) {
                return redirect()->route('residual_material_processing.index')->with('error', '新增失敗');
            }
        } else {
            return redirect()->back()->with('error', '未選擇任何物料');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
