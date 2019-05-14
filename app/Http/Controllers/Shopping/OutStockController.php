<?php

namespace App\Http\Controllers\Shopping;

use App\Model\Stock;
use App\Model\Material;
use Illuminate\Http\Request;
use App\Model\Apply_out_stock;
use App\Model\Material_warehouse;
use App\Http\Controllers\Controller;

class OutStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::where('delete_flag','0')->where('stock_option','11')->orderBy('stock_date','desc')->orderBy('updated_at','desc')->orderBy('id','desc')->get();        
        return view('shopping.out_stock.show',compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shopping.out_stock.create');        
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
            'customer' => 'required',
            'outDate' => 'date_format:"Y-m-d"|required',

        ];
        $messages = [
            'lot_number.required' => '批號 必填',                                 
            'customer.required' => '尚未選擇 客戶',
            'outDate.required' => '出庫日期 必填',
            'outDate.date_format' => '出庫日期格式錯誤',
        ];
        $this->validate($request, $rules, $messages);

        $total_materials = count($request->material);

        for($i=0; $i < $total_materials; $i++){
            if($request->materialAmount[$i] < 0){
                return redirect()->back()->with('error','出庫數量不可為負數');
            }
            if($request->materialPrice[$i] < 0){
                return redirect()->back()->with('error','單價不可為負數');
            }
        }
        
        for($i=0; $i < $total_materials; $i++){
            if($request->materialAmount[$i] < 0){
                return redirect()->back()->with('error','出庫數量不可為負數');
            }
            if($request->materialPrice[$i] < 0){
                return redirect()->back()->with('error','單價不可為負數');
            }
        }

        $material = [];
        $warehouse = [];        
        $materialAmount = [];
        $materialPrice = [];
        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $materialAmount[] = $request->materialAmount[$i];
                $warehouse[] = $request->materialWarehouse[$i];                
                $materialPrice[] = $request->materialPrice[$i];
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
                    $stock->stock_option = 11;
                    $stock->status = 0;
                    $stock->stock_no = $material_stock->stock_no + 1;                                                                      
                    $stock->material = $material[$i];
                    $stock->warehouse = $warehouse[$i];
                    $stock->customer = $request->customer;
                    $stock->total_start_quantity = $material_stock->stock;                                    
                    $stock->start_quantity = $warehouse_start_quantity;
                    $stock->quantity = $quantity;
                    $stock->calculate_quantity = $str;
                    $stock->stock_date = $request->outDate;
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

                return redirect()->route('out_stock.index')->with('message', '新增出庫成功');
            } catch(Exception $e) {
                return redirect()->route('out_stock.index')->with('error', '新增出庫失敗');
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
        // $stock = Stock::find($id);
        // $sale_no = $stock->sale_no;
        // $stock_materials = Stock::where('delete_flag','0')->where('sale_no',$sale_no)->get();

        // $materialCount = 0;
        // $data = '';

        // foreach($stock_materials as $stock_material){
        //     $material_id = $stock_material->material;
        //     $material = Material::where('id',$material_id)->first();
            
        //     if($material->warehouse > 0){
        //         $warehouse = $material->warehouse_name->code;
        //         $warehouse_id = $material->warehouse;
        //         $get_warehouse_stock = Material_warehouse::where('delete_flag','0')->where('material_id',$material_id)->where('warehouse_id',$warehouse_id)->first();
        //         $warehouse_stock = $get_warehouse_stock->stock;
        //     } else {
        //         $warehouse = "請選擇倉儲";
        //         $warehouse_id = '';
        //         $warehouse_stock = 0;
        //     }


        //     $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
        //         <td></td>
        //         <td>
        //             <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" disabled> '.$material->fullCode.' '.$material->fullName.'</button>
        //             <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$material_id.'">
        //         </td>
        //         <td>
        //             <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 100%; margin-right: 10px; overflow: hidden;"> '.$warehouse.'</button>
        //             <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse" value="'.$warehouse_id.'">
        //         </td>
        //         <td>
        //             <span id="materialStock_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$warehouse_stock.'</span>
        //             <input type="hidden" name="materialStock[]" id="materialStock'.$materialCount.'" class="materialStock" value="'.$warehouse_stock.'">                
        //         </td>
        //         <td>
        //             <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$stock->quantity.'">
        //         </td>
        //         <td>
        //             <span id="materialSubTotal_show'.$materialCount.'" class="materialSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
        //             <input type="hidden" name="materialSubTotal[]" id="materialSubTotal'.$materialCount.'" class="materialSubTotal">
        //         </td>
        //         <td>
        //             <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
        //             <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'"> 
        //         </td>
        //         <input type="hidden" name="stock_id[]" id="stock_id'.$materialCount.'" class="stock_id" value="'.$stock_material->id.'">              
        //     </tr>';
        //     $materialCount++;               
        // }
        // return view('shopping.out_stock.edit', compact('stock','data','materialCount'));
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
        // $rules = [
        //     // 'lot_number' => 'required',                                  
        //     // 'customer' => 'required',
        //     'outDate' => 'date_format:"Y-m-d"|required',

        // ];
        // $messages = [
        //     // 'lot_number.required' => '批號 必填',                                 
        //     // 'customer.required' => '尚未選擇 客戶',
        //     'outDate.required' => '出庫日期 必填',
        //     'outDate.date_format' => '出庫日期格式錯誤',
        // ];
        // $this->validate($request, $rules, $messages);

        // $total_materials = count($request->material);

        // // for($i=0; $i < $total_materials; $i++){
        // //     if($request->materialAmount[$i] < 0){
        // //         return redirect()->back()->with('error','出庫數量不可為負數');
        // //     }
          
        // // }
        
        // // for($i=0; $i < $total_materials; $i++){
        // //     if($request->materialAmount[$i] < 0){
        // //         return redirect()->back()->with('error','出庫數量不可為負數');
        // //     }
           
        // // }

        // $material = [];
        // $warehouse = [];        
        // $materialAmount = [];
        // $stock_id = [];
        // for($i=0; $i < $total_materials; $i++){
        //     if($request->material[$i]){
        //         $material[] = $request->material[$i];
        //         $materialAmount[] = $request->materialAmount[$i];
        //         $warehouse[] = $request->materialWarehouse[$i];                
        //         $stock_id[] = $request->stock_id[$i];
        //     }
        // }
        

        // if(count($material) > 0){

        //     try{
        //         $total = count($material);
        //         for($i=0; $i < $total; $i++){

        //             if($stock_id[$i] > 0){
        //                 $stock = Stock::find($stock_id[$i]);
        //                 $material_stock = Material::find($material[$i]);
        //                 $material_warehouse = Material_warehouse::where('delete_flag','0')->where('material_id',$material[$i])->where('warehouse_id',$warehouse[$i])->first();

        //                 $warehouse_start_quantity = $material_warehouse->stock;
        //                 $qua = number_format($materialAmount[$i],2,'.','');
        //                 $end = $warehouse_start_quantity - $qua;
        //                 $unit = $material_stock->material_unit_name->name;
        //                 $cal_str = $warehouse_start_quantity.' '.$unit.' -> '.number_format($end,2,'.','').' '.$unit;
        //                 $stock->status = 0;
        //                 $stock->warehouse = $warehouse[$i];
        //                 $stock->total_start_quantity = $material_stock->stock;                                    
        //                 $stock->start_quantity = $warehouse_start_quantity;
        //                 $stock->quantity = $qua;
        //                 $stock->calculate_quantity = $cal_str;
        //                 $stock->stock_date = $request->outDate;
        //                 $stock->memo = $request->memo;                    
        //                 $stock->updated_user = session('admin_user')->id;
        //                 $stock->save();

        //                 $material_warehouse->stock = $end;
        //                 $material_warehouse->save();

        //                 $material_stock->stock = $material_stock->stock - $qua;
        //                 $material_stock->save();
        //             } else {
        //                 $material_stock = Material::find($material[$i]);
        //                 $material_warehouse = Material_warehouse::where('delete_flag','0')->where('material_id',$material[$i])->where('warehouse_id',$warehouse[$i])->first();
        //                 $warehouse_start_quantity = $material_warehouse->stock;
        //                 $qua = number_format($materialAmount[$i],2,'.','');
        //                 $end = $warehouse_start_quantity - $qua;
        //                 $unit = $material_stock->material_unit_name->name;
        //                 $cal_str = $warehouse_start_quantity.' '.$unit.' -> '.number_format($end,2,'.','').' '.$unit;

        //                 $original_stock = Stock::find($id);

        //                 $stock = new Stock;
        //                 $stock->lot_number = $original_stock->lot_number;
        //                 $stock->sale_no = $original_stock->sale_no;
        //                 $stock->stock_option = 11;
        //                 $stock->status = 0;
        //                 $stock->material = $material[$i];
        //                 $stock->warehouse = $warehouse[$i];
        //                 $stock->customer = $original_stock->customer;
        //                 $stock->total_start_quantity = $material_stock->stock;                                    
        //                 $stock->start_quantity = $warehouse_start_quantity;
        //                 $stock->quantity = $qua;
        //                 $stock->calculate_quantity = $cal_str;
        //                 $stock->stock_date = $request->outDate;
        //                 $stock->memo = $request->memo;                    
        //                 $stock->created_user = session('admin_user')->id;
        //                 $stock->updated_at = date('Y-m-d H:i:s');
        //                 $stock->delete_flag = 0;
        //                 $stock->save();
    
        //                 $material_warehouse->stock = $end;
        //                 $material_warehouse->save();
    
        //                 $material_stock->stock = $material_stock->stock - $qua;
        //                 $material_stock->save();
        //             }
                    


        //         }
        //         return redirect()->route('out_stock.index')->with('message', '編輯出庫成功');
        //     } catch(Exception $e) {
        //         return redirect()->route('out_stock.index')->with('error', '編輯出庫失敗');
        //     }

        // } else {
        //     return redirect()->back()->with('error', '未選擇任何物料');
        // }
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
