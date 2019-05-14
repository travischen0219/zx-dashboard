<?php

namespace App\Http\Controllers\Stock;

use App\Model\Stock;
use App\Model\Material;
use App\Model\Warehouse;
use Illuminate\Http\Request;
use App\Model\Material_warehouse;
use App\Model\Transfer_inventory;
use App\Http\Controllers\Controller;

class Transfer_inventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = "all";
        $transfers = Transfer_inventory::where('delete_flag','0')->orderBy('updated_at','desc')->get();
        return view('stock.transfer_inventory.show',compact('transfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stock.transfer_inventory.create');        
        
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

            'transfer_date' => 'date_format:"Y-m-d"|required',

        ];
        $messages = [
            'lot_number.required' => '批號 必填',                     

            'transfer_date.required' => '調撥日期 必填',
            'transfer_date.date_format' => '調撥日期格式錯誤',
        ];
        $this->validate($request, $rules, $messages);

        $total_materials_check = count($request->material);
        $material = [];
        $warehouse = [];
        $new_warehouse = [];
        $materialAmount = [];

        for($i=0; $i < $total_materials_check; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $warehouse[] = $request->materialWarehouse[$i];
                $new_warehouse[] = $request->materialNewWarehouse[$i];
                $materialAmount[] = $request->materialAmount[$i];

            }
        }

        if(count($material) > 0){

            try{
                $total_materials = count($material);
                for($i=0; $i < $total_materials; $i++){ 

                    $material_stock = Material::find($material[$i]);
                    $material_warehouses = Material_warehouse::where('delete_flag','0')->where('material_id',$material[$i])->get();

                    // $material_start_quantity = $material_stock->stock;
                    // $material_end_quantity = 0;
                    $o_warehouse_start_quantity = 0;
                    $n_warehouse_start_quantity = 0;
                    $quantity = 0;
                    $o_warehouse_end_quantity = 0;
                    $n_warehouse_end_quantity = 0;
                    $o_str = '';
                    $n_str = '';
                    $check_has_new_warehouse = 0;

                    // 判斷新倉儲是否存在
                    foreach($material_warehouses as $material_warehouse){
                        if($new_warehouse[$i] == $material_warehouse->warehouse_id){
                            $check_has_new_warehouse++;
                        }
                    }
                    if($check_has_new_warehouse > 0){
                        // 已建立過倉儲位置
                        foreach($material_warehouses as $material_warehouse){
                            if($new_warehouse[$i] == $material_warehouse->warehouse_id){

                                $o_warehouse = Material_warehouse::where('delete_flag','0')->where('warehouse_id',$warehouse[$i])->first();
                                $o_warehouse_start_quantity = $o_warehouse->stock;

                                $n_warehouse_start_quantity = $material_warehouse->stock;
                                $quantity = number_format($materialAmount[$i],2,'.','');
                                $o_warehouse_end_quantity = $o_warehouse_start_quantity - $quantity;
                                $n_warehouse_end_quantity = $n_warehouse_start_quantity + $quantity;
                                $unit = $material_stock->material_unit_name->name;
                                $o_str = $o_warehouse_start_quantity.' '.$unit.' -> '.number_format($o_warehouse_end_quantity,2,'.','').' '.$unit;
                                $n_str = $n_warehouse_start_quantity.' '.$unit.' -> '.number_format($n_warehouse_end_quantity,2,'.','').' '.$unit;
                                
                                $material_warehouse->stock = $n_warehouse_end_quantity;
                                $material_warehouse->save();
                                $o_warehouse->stock = $o_warehouse_end_quantity;
                                $o_warehouse->save();
                            }
                        }
                    } else {
                        // 新的倉儲位置
                        $o_warehouse = Material_warehouse::where('delete_flag','0')->where('warehouse_id',$warehouse[$i])->first();
                        $o_warehouse_start_quantity = $o_warehouse->stock;

                        $quantity = number_format($materialAmount[$i],2,'.','');

                        $o_warehouse_end_quantity = $o_warehouse_start_quantity - $quantity;                            
                        $n_warehouse_end_quantity = $n_warehouse_start_quantity + $quantity;
                        $unit = $material_stock->material_unit_name->name;
                        $o_str = $o_warehouse_start_quantity.' '.$unit.' -> '.number_format($o_warehouse_end_quantity,2,'.','').' '.$unit;
                        $n_str = $n_warehouse_start_quantity.' '.$unit.' -> '.number_format($n_warehouse_end_quantity,2,'.','').' '.$unit;

                        $find_new_warehouse_category = Warehouse::find($new_warehouse[$i]);  
                        
                        $material_warehouse_add = new Material_warehouse;
                        $material_warehouse_add->material_id = $material[$i];
                        $material_warehouse_add->warehouse_id = $new_warehouse[$i];
                        $material_warehouse_add->warehouse_category_id = $find_new_warehouse_category->category;
                        $material_warehouse_add->stock = $n_warehouse_end_quantity;                
                        $material_warehouse_add->created_user = session('admin_user')->id;
                        $material_warehouse_add->delete_flag = 0;
                        $material_warehouse_add->save();
                        $o_warehouse->stock = $o_warehouse_end_quantity;
                        $o_warehouse->save();
                    }
                    
                    $transfer = new Transfer_inventory;
                    $transfer->lot_number = $request->lot_number;
                    $transfer->transfer_date = $request->transfer_date;
                    $transfer->material_id = $material[$i];
                    $transfer->quantity = $quantity;
                    $transfer->original_warehouse = $o_warehouse->warehouse_id;
                    $transfer->original_calculate_quantity = $o_str;
                    $transfer->new_warehouse = $new_warehouse[$i];
                    $transfer->new_calculate_quantity = $n_str;
                    $transfer->created_user = session('admin_user')->id;
                    $transfer->delete_flag = 0;
                    $transfer->save();
                    
                    $o_stock = new Stock;
                    $o_stock->lot_number = $request->lot_number;        
                    $o_stock->stock_option = 21;
                    $o_stock->status = 0;            
                    $o_stock->stock_no = $material_stock->stock_no + 1;                                                              
                    $o_stock->material = $material[$i];
                    $o_stock->warehouse = $o_warehouse->warehouse_id;
                    $o_stock->total_start_quantity = $material_stock->stock;                                    
                    $o_stock->start_quantity = $o_warehouse_start_quantity;
                    $o_stock->quantity = $quantity;
                    $o_stock->calculate_quantity = $o_str;
                    $o_stock->stock_date = $request->transfer_date;
                    $o_stock->memo = $request->memo;
                    $o_stock->created_user = session('admin_user')->id;
                    $o_stock->delete_flag = 0;
                    $o_stock->save();

                    $n_stock = new Stock;
                    $n_stock->lot_number = $request->lot_number;        
                    $n_stock->stock_option = 22;
                    $n_stock->status = 0;            
                    $n_stock->stock_no = $material_stock->stock_no + 2;                                                              
                    $n_stock->material = $material[$i];
                    $n_stock->warehouse = $new_warehouse[$i];
                    $n_stock->total_start_quantity = $material_stock->stock;                                    
                    $n_stock->start_quantity = $n_warehouse_start_quantity;
                    $n_stock->quantity = $quantity;
                    $n_stock->calculate_quantity = $n_str;
                    $n_stock->stock_date = $request->transfer_date;
                    $n_stock->memo = $request->memo;
                    $n_stock->created_user = session('admin_user')->id;
                    $n_stock->delete_flag = 0;
                    $n_stock->save();

                    $material_stock->stock_no = $material_stock->stock_no + 2;                    
                    $material_stock->save();
                }

                return redirect()->route('transfer_inventory.index')->with('message', '調撥成功');
            } catch(Exception $e) {
                return redirect()->route('transfer_inventory.index')->with('error', '調撥失敗');
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
