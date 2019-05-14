<?php

namespace App\Http\Controllers\Purchase;

use App\Model\User;
use App\Model\Stock;
use App\Model\Material;
use App\Model\Warehouse;
use App\Model\Buy_to_stock;
use Illuminate\Http\Request;
use App\Model\Material_warehouse;
use App\Http\Controllers\Controller;

class Buy_to_stockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $buy_to_stocks = Buy_to_stock::where('delete_flag','0')->get();
        return view('purchase.buy_to_stock.show',compact('buy_to_stocks','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $buy_to_stocks = Buy_to_stock::where('delete_flag','0')->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            } else {
                $buy_to_stocks = Buy_to_stock::where('delete_flag','0')->where('status',$search_code)->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            }
        } else {
            if($search_code == 'all'){
                $buy_to_stocks = Buy_to_stock::where('delete_flag','0')->get();
            } else {
                $buy_to_stocks = Buy_to_stock::where('delete_flag','0')->where('status',$search_code)->get();
            }
        }
        return view('purchase.buy_to_stock.show',compact('buy_to_stocks','search_code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $buy_to_stock = Buy_to_stock::find($id);

        if($buy_to_stock->material_warehouses){
            $material_warehouses = unserialize($buy_to_stock->material_warehouses);
    
            $total_materials = count($material_warehouses['material']);
            $materialCount = 0;
            $data = '';
            for($i = 0; $i < $total_materials; $i++){
            
                $material = Material::where('id',$material_warehouses['material'][$i])->first();
                $warehouse_id = $material_warehouses['warehouse'][$i];
                $warehouse = Warehouse::find($warehouse_id);
                $warehouse_name = $warehouse->code;
                $style = '';
                $readonly = '';
                $disabled = '';
                if($buy_to_stock->status == 2){
                    $style = ' style="display:none"';
                    $readonly = ' readonly';
                    $disabled = ' disabled';
                }
                
                $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                    <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                    <td>
                        <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                        <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$material_warehouses['material'][$i].'">
                    </td>
                    <td>
                        <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                        <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'"> 
                    </td>
                    <td>
                        <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 80%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$warehouse_name.'</button>
                        <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse" value="'.$warehouse_id.'">
                    </td>
                    <td>
                        <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:120px; height: 30px; vertical-align: middle;" value="'.$material_warehouses['materialAmount'][$i].'" '.$readonly.'>
                    </td>
                </tr>';
                $materialCount++;
            }
        } else {
            $materials = unserialize($buy_to_stock->materials);
    
            $total_materials = count($materials['material']);
            $materialCount = 0;
            $data = '';
            for($i = 0; $i < $total_materials; $i++){
            
                $material = Material::where('id',$materials['material'][$i])->first();

                if($material->warehouse > 0){
                    $warehouse_name = $material->warehouse_name->code;
                    $warehouse_id = $material->warehouse; 
                } else {
                    $warehouse_name = "請選擇倉儲";
                    $warehouse_id = ''; 
                }
                $style = '';
                $readonly = '';
                $disabled = '';
                if($buy_to_stock->status == 2){
                    $style = ' style="display:none"';
                    $readonly = ' readonly';
                    $disabled = ' disabled';
                }
                
                $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                    <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                    <td>
                        <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                        <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                    </td>
                    <td>
                        <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                        <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'"> 
                    </td>
                    <td>
                    <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 80%; margin-right: 10px; overflow: hidden;"> '.$warehouse_name.'</button>
                        <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse" value="'.$warehouse_id.'">
                    </td>
                    <td>
                        <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:120px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                    </td>
                </tr>';
                $materialCount++;
            }

        }

        if($buy_to_stock->updated_user > 0){
            $updated_user = User::where('id',$buy_to_stock->updated_user)->first();
        } else {
            $updated_user = User::where('id',$buy_to_stock->created_user)->first();
        }
        return view('purchase.buy_to_stock.edit', compact('buy_to_stock','materials','data','materialCount','updated_user'));
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
        $total_materials = count($request->material);
        $material = [];
        $warehouse = [];
        $materialAmount = [];

        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $warehouse[] = $request->materialWarehouse[$i];
                $materialAmount[] = $request->materialAmount[$i];
            }
        }

        if(count($material) > 0){
            $material_warehouses = ['material'=>$material, 'warehouse'=>$warehouse, 'materialAmount'=>$materialAmount];

            if($request->status == 1){
                try{
                    $buy_to_stock = Buy_to_stock::find($id);
                    $buy_to_stock->inStockDate = $request->inStockDate;
                    $buy_to_stock->material_warehouses = serialize($material_warehouses);
                    $buy_to_stock->memo = $request->memo;                
                    $buy_to_stock->updated_user = session('admin_user')->id;
                    $buy_to_stock->save();
    
                    return redirect()->route('ibuy_to_stock.index')->with('message', '編輯成功');
                } catch(Exception $e) {
                    return redirect()->route('ibuy_to_stock.index')->with('error', '存檔失敗');
                }
            } else if($request->status == 2){
                try{
                    // 儲存採購轉入庫單修改
                    $buy_to_stock = Buy_to_stock::find($id);
                    $buy_to_stock->inStockDate = $request->inStockDate;
                    $buy_to_stock->material_warehouses = serialize($material_warehouses);
                    $buy_to_stock->status = 2;                                    
                    $buy_to_stock->memo = $request->memo;                
                    $buy_to_stock->updated_user = session('admin_user')->id;
                    $buy_to_stock->save();

                    // 建立入庫
                    $total_material_stocks = count($material);
                    for($i=0; $i < $total_material_stocks; $i++){ 

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
                                        $str = number_format($warehouse_start_quantity,2,'.','').' '.$unit.' -> '.number_format($warehouse_end_quantity,2,'.','').' '.$unit;
                                        $material_warehouse->stock = $warehouse_end_quantity;
                                        $material_warehouse->updated_user = session('admin_user')->id;                                        
                                        $material_warehouse->save();
                                    }
                                }
                            } else {
                                // 新的倉儲位置
                                $quantity = number_format($materialAmount[$i],2,'.','');
                                $warehouse_end_quantity = $warehouse_start_quantity + $quantity;
                                $unit = $material_stock->material_unit_name->name;
                                $str = number_format($warehouse_start_quantity,2,'.','').' '.$unit.' -> '.number_format($warehouse_end_quantity,2,'.','').' '.$unit;
                                $find_warehouse_category = Warehouse::find($warehouse[$i]);  
                                
                                $material_warehouse_add = new Material_warehouse;
                                $material_warehouse_add->material_id = $material[$i];
                                $material_warehouse_add->warehouse_id = $warehouse[$i];
                                $material_warehouse_add->warehouse_category_id = $find_warehouse_category->category;
                                $material_warehouse_add->stock = $warehouse_end_quantity;                
                                $material_warehouse_add->created_user = session('admin_user')->id;
                                $material_warehouse_add->delete_flag = 0;
                                $material_warehouse_add->save();
                            }
                            
                        } else {
                            // 若無預設倉儲
                            $quantity = number_format($materialAmount[$i],2,'.','');
                            $warehouse_end_quantity = $warehouse_start_quantity + $quantity;
                            $unit = $material_stock->material_unit_name->name;
                            $str = number_format($warehouse_start_quantity,2,'.','').' '.$unit.' -> '.number_format($warehouse_end_quantity,2,'.','').' '.$unit;
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
                        $stock->lot_number = $buy_to_stock->lot_number;        
                        $stock->buy_no = $buy_to_stock->buy_no;        
                        $stock->stock_option = 4;
                        $stock->status = 0;
                        $stock->stock_no = $material_stock->stock_no + 1;                          
                        $stock->material = $material[$i];
                        $stock->warehouse = $warehouse[$i];
                        $stock->supplier = $buy_to_stock->supplier;
                        $stock->total_start_quantity = $material_stock->stock;                                    
                        $stock->start_quantity = $warehouse_start_quantity;
                        $stock->quantity = $quantity;
                        $stock->calculate_quantity = $str;
                        $stock->stock_date = $request->inStockDate;
                        $stock->memo = $request->memo;
                        $stock->created_user = session('admin_user')->id;
                        $stock->delete_flag = 0;
                        $stock->save();

                        $material_end_quantity = $material_start_quantity + $quantity;
                        $material_stock->stock_no = $material_stock->stock_no + 1;
                        $material_stock->stock = $material_end_quantity;
                        $material_stock->save();
                    }

                    return redirect()->route('ibuy_to_stock.index')->with('message', '已轉入庫');
                } catch(Exception $e) {
                    return redirect()->route('ibuy_to_stock.index')->with('error', '存檔失敗');
                }

            }
            

        } else {
            return redirect()->back()->with('error', '未選擇任何物料');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $buy_to_stock = Buy_to_stock::find($id);
            $buy_to_stock->delete_flag = 1;
            $buy_to_stock->deleted_at = Now();
            $buy_to_stock->deleted_user = session('admin_user')->id;
            $buy_to_stock->save();
            return redirect()->route('ibuy_to_stock.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('ibuy_to_stock.index')->with('error','刪除失敗');            
        }
    }
}


