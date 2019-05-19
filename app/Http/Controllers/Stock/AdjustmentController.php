<?php

namespace App\Http\Controllers\Stock;

use App\Model\Stock;
use App\Model\Material;
use Illuminate\Http\Request;
use App\Model\Material_warehouse;
use App\Model\Warehouse;
use App\Http\Controllers\Controller;

class AdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = "all";
        $stocks = Stock::where('delete_flag','0')->where('stock_option','2')->orderBy('updated_at','desc')->get();
        return view('stock.adjustment.show',compact('stocks'));
    }

    public function search(Request $request)
    {
        $search_code = 2;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $stocks = Stock::where('delete_flag','0')->where('stock_option','<>','11')->where('lot_number','like','%'.$request->search_lot_number.'%')->orderBy('updated_at','desc')->get();
            } else {
                $stocks = Stock::where('delete_flag','0')->where('stock_option',$search_code)->where('stock_option','<>','11')->where('lot_number','like','%'.$request->search_lot_number.'%')->orderBy('updated_at','desc')->get();
            }
        } else {
            if($search_code == 'all'){
                $stocks = Stock::where('delete_flag','0')->where('stock_option','<>','11')->orderBy('updated_at','desc')->get();
            } else {
                $stocks = Stock::where('delete_flag','0')->where('stock_option',$search_code)->orderBy('updated_at','desc')->get();
            }
        }
        return view('stock.adjustment.show',compact('stocks'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stock.adjustment.create');
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
            // 'supplier' => 'required',
            'stock_date' => 'date_format:"Y-m-d"|required',

        ];
        $messages = [
            'lot_number.required' => '批號 必填',
            // 'supplier.required' => '尚未選擇 供應商',
            'stock_date.required' => '處理日期 必填',
            'stock_date.date_format' => '處理日期格式錯誤',
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
                $total_materials = count($material);
                for($i=0; $i < $total_materials; $i++){

                    $material_stock = Material::find($material[$i]);
                    $material_warehouses = Material_warehouse::where('delete_flag','0')->where('material_id',$material[$i])->get();

                    $material_start_quantity = $material_stock->stock;
                    $material_end_quantity = 0;
                    $warehouse_start_quantity = 0;
                    $quantity = 0;
                    $warehouse_end_quantity = 0;
                    $str = '';
                    $check_has_warehouse = 0;
                    if($material_warehouses->count() > 0){
                        // 有預設倉儲
                        // 判斷是否建立過倉儲
                        foreach($material_warehouses as $material_warehouse){
                            if($warehouse[$i] == $material_warehouse->warehouse_id){
                                $check_has_warehouse++;
                            }
                        }
                        if($check_has_warehouse > 0){
                            // 已建立過倉儲位置
                            foreach($material_warehouses as $material_warehouse){
                                if($warehouse[$i] == $material_warehouse->warehouse_id){
                                    $warehouse_start_quantity = $material_warehouse->stock;
                                    $quantity = number_format($materialAmount[$i],2,'.','');
                                    $warehouse_end_quantity = $warehouse_start_quantity + $quantity;
                                    $unit = $material_stock->material_unit_name->name;
                                    $str = $warehouse_start_quantity.' '.$unit.' -> '.number_format($warehouse_end_quantity,2,'.','').' '.$unit;
                                    $material_warehouse->stock = $warehouse_end_quantity;
                                    $material_warehouse->save();
                                }
                            }
                        } else {
                            // 新的倉儲位置
                            $quantity = number_format($materialAmount[$i],2,'.','');
                            $warehouse_end_quantity = $warehouse_start_quantity + $quantity;
                            $unit = $material_stock->material_unit_name->name;
                            $str = $warehouse_start_quantity.' '.$unit.' -> '.number_format($warehouse_end_quantity,2,'.','').' '.$unit;
                            $find_warehouse_category = Warehouse::find($warehouse[$i]);

                            $material_warehouse_add = new Material_warehouse;
                            $material_warehouse_add->material_id = $material[$i];
                            $material_warehouse_add->warehouse_id = $warehouse[$i];
                            $material_warehouse_add->warehouse_category_id = $find_warehouse_category->category;
                            $material_warehouse_add->stock = $quantity;
                            $material_warehouse_add->created_user = session('admin_user')->id;
                            $material_warehouse_add->delete_flag = 0;
                            $material_warehouse_add->save();
                        }
                    } else {
                        // 若無預設倉儲
                        $quantity = number_format($materialAmount[$i],2,'.','');
                        $warehouse_end_quantity = $warehouse_start_quantity + $quantity;
                        $unit = $material_stock->material_unit_name->name;
                        $str = $warehouse_start_quantity.' '.$unit.' -> '.number_format($warehouse_end_quantity,2,'.','').' '.$unit;
                        $find_warehouse_category = Warehouse::find($warehouse[$i]);

                        $material_warehouse_add = new Material_warehouse;
                        $material_warehouse_add->material_id = $material[$i];
                        $material_warehouse_add->warehouse_id = $warehouse[$i];
                        $material_warehouse_add->warehouse_category_id = $find_warehouse_category->category;
                        $material_warehouse_add->stock = $quantity;
                        $material_warehouse_add->created_user = session('admin_user')->id;
                        $material_warehouse_add->delete_flag = 0;
                        $material_warehouse_add->save();

                        $material_stock->warehouse = $warehouse[$i];
                        $material_stock->warehouse_category = $find_warehouse_category->category;
                    }

                    $stock = new Stock;
                    $stock->lot_number = $request->lot_number;
                    $stock->stock_option = 2;
                    $stock->status = 0;
                    $stock->stock_no = $material_stock->stock_no + 1;
                    $stock->material = $material[$i];
                    $stock->warehouse = $warehouse[$i];
                    $stock->total_start_quantity = $material_stock->stock;
                    $stock->start_quantity = $warehouse_start_quantity;
                    $stock->quantity = $quantity;
                    $stock->calculate_quantity = $str;
                    $stock->stock_date = $request->stock_date;
                    $stock->memo = $request->memo;
                    $stock->created_user = session('admin_user')->id;
                    $stock->delete_flag = 0;
                    $stock->save();

                    $material_end_quantity = $material_start_quantity + $quantity;
                    $material_stock->stock_no = $material_stock->stock_no + 1;
                    $material_stock->stock = $material_end_quantity;
                    $material_stock->save();
                }

                return redirect()->route('adjustment.index')->with('message', '差異處理成功');
            } catch(Exception $e) {
                return redirect()->route('adjustment.index')->with('error', '差異處理失敗');
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
