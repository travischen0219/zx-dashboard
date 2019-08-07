<?php

namespace App\Http\Controllers\Purchase;

use App\Model\Stock;
use App\Model\Material;
use App\Model\Warehouse;
use App\Model\Material_unit;
use Illuminate\Http\Request;
use App\Model\Material_warehouse;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    // stock_option
    // 1 一般入庫
    // 2 誤差處理
    // 3 起始庫存
    // 4 採購轉入庫
    // 5 退貨入庫
    // 11 出庫
    // 21 調撥出庫
    // 22 調撥入庫
    // 31 餘料處理
    // 41 採購退貨出庫

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = "all";
        $stocks = Stock::where('delete_flag','0')->where('stock_option','<>','2')->where('stock_option','<>','11')->where('stock_option','<>','21')->orderBy('stock_date','desc')->orderBy('updated_at','desc')->orderBy('id','desc')->get();
        return view('purchase.stock.show',compact('stocks','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $stocks = Stock::where('delete_flag','0')->where('stock_option','<>','2')->where('stock_option','<>','11')->where('stock_option','<>','21')->where('lot_number','like','%'.$request->search_lot_number.'%')->orderBy('stock_date','desc')->orderBy('updated_at','desc')->orderBy('id','desc')->get();
            } else {
                $stocks = Stock::where('delete_flag','0')->where('stock_option',$search_code)->where('stock_option','<>','2')->where('stock_option','<>','11')->where('stock_option','<>','21')->where('lot_number','like','%'.$request->search_lot_number.'%')->orderBy('stock_date','desc')->orderBy('updated_at','desc')->orderBy('id','desc')->get();
            }
        } else {
            if($search_code == 'all'){
                $stocks = Stock::where('delete_flag','0')->where('stock_option','<>','2')->where('stock_option','<>','11')->where('stock_option','<>','21')->orderBy('stock_date','desc')->orderBy('updated_at','desc')->orderBy('id','desc')->get();
            } else {
                $stocks = Stock::where('delete_flag','0')->where('stock_option',$search_code)->where('stock_option','<>','2')->where('stock_option','<>','11')->where('stock_option','<>','21')->orderBy('stock_date','desc')->orderBy('updated_at','desc')->orderBy('id','desc')->get();
            }
        }
        return view('purchase.stock.show',compact('stocks','search_code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('purchase.stock.create');
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
            // 'lot_number' => 'required',
            'supplier' => 'required',
            'stock_date' => 'date_format:"Y-m-d"|required',

        ];
        $messages = [
            // 'lot_number.required' => '批號 必填',
            'supplier.required' => '尚未選擇 供應商',
            'stock_date.required' => '入庫日期 必填',
            'stock_date.date_format' => '入庫日期格式錯誤',
        ];
        $this->validate($request, $rules, $messages);

        $total_materials_check = count($request->material);
        $materialOption = [];
        $material = [];
        $warehouse = [];
        $materialAmount = [];

        for($i=0; $i < $total_materials_check; $i++){
            if($request->material[$i]){
                $materialOption[] = $request->materialOption[$i];
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
                    $stock->stock_option = $materialOption[$i];
                    $stock->status = 0;
                    $stock->stock_no = $material_stock->stock_no + 1;
                    $stock->material = $material[$i];
                    $stock->warehouse = $warehouse[$i];
                    $stock->supplier = $request->supplier;
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

                return redirect()->route('stock.index')->with('message', '新增成功');
            } catch(Exception $e) {
                return redirect()->route('stock.index')->with('error', '新增失敗');
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
        // $buy_no = $stock->buy_no;
        // $stock_materials = Stock::where('delete_flag','0')->where('buy_no',$buy_no)->get();

        // $materialCount = 0;
        // $data = '';

        // foreach($stock_materials as $stock_material){
        //     $material_id = $stock_material->material;
        //     $material = Material::where('id',$material_id)->first();

        //     if($material->warehouse > 0){
        //         $warehouse = $material->warehouse_name->code;
        //         $warehouse_id = $material->warehouse;
        //     } else {
        //         $warehouse = "請選擇倉儲";
        //         $warehouse_id = '';
        //     }

        //     $is_init_str = '';
        //     // $is_init_str .= '<option value="1"> 一般入庫</option>';

        //     // 已改為 庫存盤點->差異處理
        //     // $is_init_str .= '<option value="2"> 庫存調整</option>';

        //     // $is_init_str .= '<option value="3"> 起始庫存</option>';
        //     $is_init_str .= '<option value="4" selected> 採購單轉入庫</option>';
        //     // $is_init_str .= '<option value="5"> 退貨入庫</option>';

        //     $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
        //         <td></td>
        //         <td>
        //             <select name="materialOption[]" id="materialOption'.$materialCount.'" style="width: 120px; height: 30px; vertical-align: middle;" disabled>
        //                 '.$is_init_str.'
        //             </select>
        //         </td>
        //         <td>
        //             <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" disabled> '.$material->fullCode.' '.$material->fullName.'</button>
        //             <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$material_id.'">
        //         </td>
        //         <td>
        //             <span id="materialUnit'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
        //         </td>
        //         <td>
        //             <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 80%; margin-right: 10px; overflow: hidden;"> '.$warehouse.'</button>
        //             <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse" value="'.$warehouse_id.'">
        //         </td>
        //         <td>
        //             <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" style="width:120px; height: 30px; vertical-align: middle;" value="'.$stock->quantity.'" disabled>
        //         </td>
        //         <input type="hidden" name="stock_id[]" id="stock_id'.$materialCount.'" class="stock_id" value="'.$stock_material->id.'">
        //     </tr>';
        //     $materialCount++;
        // }
        // return view('purchase.stock.edit', compact('stock','data','materialCount'));

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
        //     // 'supplier' => 'required',
        //     'stock_date' => 'date_format:"Y-m-d"|required',

        // ];
        // $messages = [
        //     // 'lot_number.required' => '批號 必填',
        //     // 'supplier.required' => '尚未選擇 供應商',
        //     'stock_date.required' => '入庫日期 必填',
        //     'stock_date.date_format' => '入庫日期格式錯誤',
        // ];
        // $this->validate($request, $rules, $messages);

        // $total_materials_check = count($request->material);

        // try{
        //     for($i=0; $i < $total_materials_check; $i++){
        //         if($request->material[$i]){

        //             $stock = Stock::find($request->stock_id[$i]);
        //             $material_stock = Material::find($stock->material);
        //             $material_warehouses = Material_warehouse::where('delete_flag','0')->where('material_id',$request->material[$i])->get();

        //             $material_start_quantity = $material_stock->stock;
        //             $material_end_quantity = 0;
        //             $warehouse_start_quantity = 0;
        //             $quantity = 0;
        //             $warehouse_end_quantity = 0;
        //             $str = '';
        //             $check_has_warehouse = 0;
        //             if($material_warehouses->count() > 0){
        //                 // 有指定預設倉儲
        //                 // 判斷是否建立過倉儲
        //                 foreach($material_warehouses as $material_warehouse){
        //                     if($request->materialWarehouse[$i] == $material_warehouse->warehouse_id){
        //                         $check_has_warehouse++;
        //                     }
        //                 }

        //                 if($check_has_warehouse > 0){
        //                     // 已建立過倉儲位置
        //                     foreach($material_warehouses as $material_warehouse){
        //                         if($request->materialWarehouse[$i] == $material_warehouse->warehouse_id){
        //                             $warehouse_start_quantity = $material_warehouse->stock;
        //                             $quantity = number_format($stock->quantity,2,'.','');
        //                             $warehouse_end_quantity = $warehouse_start_quantity + $quantity;
        //                             $unit = $material_stock->material_unit_name->name;
        //                             $str = $warehouse_start_quantity.' '.$unit.' -> '.number_format($warehouse_end_quantity,2,'.','').' '.$unit;
        //                             $material_warehouse->stock = $warehouse_end_quantity;
        //                             $material_warehouse->save();
        //                         }
        //                     }
        //                 } else {
        //                     // 新的倉儲位置
        //                     $quantity = number_format($stock->quantity,2,'.','');
        //                     $warehouse_end_quantity = $warehouse_start_quantity + $quantity;
        //                     $unit = $material_stock->material_unit_name->name;
        //                     $str = $warehouse_start_quantity.' '.$unit.' -> '.number_format($warehouse_end_quantity,2,'.','').' '.$unit;
        //                     $find_warehouse_category = Warehouse::find($request->materialWarehouse[$i]);

        //                     $material_warehouse_add = new Material_warehouse;
        //                     $material_warehouse_add->material_id = $request->material[$i];
        //                     $material_warehouse_add->warehouse_id = $request->materialWarehouse[$i];
        //                     $material_warehouse_add->warehouse_category_id = $find_warehouse_category->category;
        //                     $material_warehouse_add->stock = $quantity;
        //                     $material_warehouse_add->created_user = session('admin_user')->id;
        //                     $material_warehouse_add->delete_flag = 0;
        //                     $material_warehouse_add->save();
        //                 }

        //             } else {
        //                 // 若無預設倉儲
        //                 $quantity = number_format($stock->quantity,2,'.','');
        //                 $warehouse_end_quantity = $warehouse_start_quantity + $quantity;
        //                 $unit = $material_stock->material_unit_name->name;
        //                 $str = $warehouse_start_quantity.' '.$unit.' -> '.number_format($warehouse_end_quantity,2,'.','').' '.$unit;
        //                 $find_warehouse_category = Warehouse::find($request->materialWarehouse[$i]);

        //                 $material_warehouse_add = new Material_warehouse;
        //                 $material_warehouse_add->material_id = $request->material[$i];
        //                 $material_warehouse_add->warehouse_id = $request->materialWarehouse[$i];
        //                 $material_warehouse_add->warehouse_category_id = $find_warehouse_category->category;
        //                 $material_warehouse_add->stock = $quantity;
        //                 $material_warehouse_add->created_user = session('admin_user')->id;
        //                 $material_warehouse_add->delete_flag = 0;
        //                 $material_warehouse_add->save();

        //                 $material_stock->warehouse = $request->materialWarehouse[$i];
        //                 $material_stock->warehouse_category = $find_warehouse_category->category;
        //             }
        //         }


        //         $stock->status = 0;
        //         $stock->warehouse = $request->materialWarehouse[$i];
        //         $stock->total_start_quantity = $material_stock->stock;
        //         $stock->start_quantity = $warehouse_start_quantity;
        //         $stock->calculate_quantity = $str;
        //         $stock->stock_date = $request->stock_date;
        //         $stock->memo = $request->memo;
        //         $stock->updated_user = session('admin_user')->id;;
        //         $stock->save();

        //         $material_end_quantity = $material_start_quantity + $quantity;
        //         $material_stock->stock = $material_end_quantity;
        //         $material_stock->save();
        //     }
        //     return redirect()->route('stock.index')->with('message', '編輯成功');
        // } catch(Exception $e) {
        //     return redirect()->route('stock.index')->with('error', '編輯失敗');
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
