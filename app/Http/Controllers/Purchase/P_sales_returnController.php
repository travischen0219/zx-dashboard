<?php

namespace App\Http\Controllers\Purchase;

use App\Model\Buy;
use App\Model\User;
use App\Model\Stock;
use App\Model\Material;
use App\Model\Warehouse;
use App\Model\Buy_to_stock;
use Illuminate\Http\Request;
use App\Model\Account_payable;
use App\Model\Material_warehouse;
use App\Http\Controllers\Controller;

class P_sales_returnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $buys = Buy::where('delete_flag','0')->where('status_return','>','0')->get();
        return view('purchase.p_sales_return.show',compact('buys','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $buys = Buy::where('delete_flag','0')->whereIn('status_return',[1,2])->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            } else {
                $buys = Buy::where('delete_flag','0')->where('status_return',$search_code)->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            }
        } else {
            if($search_code == 'all'){
                $buys = Buy::where('delete_flag','0')->whereIn('status_return',[1,2])->get();
            } else {
                $buys = Buy::where('delete_flag','0')->where('status_return',$search_code)->get();
            }
        }
        return view('purchase.p_sales_return.show',compact('buys','search_code'));
    }
    public function search_return(Request $request)
    {

        $search_code = 'all';
        $buys = Buy::where('delete_flag','0')->where('id',$request->buy_id)->get();
    
        return view('purchase.p_sales_return.show',compact('buys','search_code'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $buy_to_stock = '';

        if(substr($request->buy_no,'0',1) != "P"){
            if(substr($request->buy_no,'0',1) != "p"){
                return redirect()->back()->with('error','採購單號必須為 P 開頭');     
            }       
        }
        if(strlen($request->buy_no) != 12){
            return redirect()->back()->with('error','採購單號長度有誤');            
        }
        $buy_no = substr($request->buy_no,'1');
        $buy = Buy::where('delete_flag','0')->where('buy_no',$buy_no)->first();

        if($buy->status != 4){
            return redirect()->back()->with('error','必須入庫後才能退貨');   
        } else if ($buy->status == 4) {
            $buy_to_stock = Buy_to_stock::where('buy_id',$buy->id)->first();
            if($buy_to_stock->status != 2){
                return redirect()->back()->with('error','必須入庫後才能退貨');     
            }
        }
        if($buy){
            if($buy->status_return > 0){
                return redirect()->back()->with('error','此單號已建立退貨');   
            }

        } else {
            return redirect()->back()->with('error','查無此單號');   
        }



        // $materials = unserialize($buy->materials);

        // $total_materials = count($materials['material']);
        // $materialCount = 0;
        // $data = '';
        // for($i = 0; $i < $total_materials; $i++){
        
        //     $material = Material::where('id',$materials['material'][$i])->first();
            
        //     $style = ' style="display:none"';
        //     $readonly = ' readonly';
        //     $disabled = ' disabled';
          
        //     $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
        //         <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
        //         <td>
        //             <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
        //             <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
        //         </td>
        //         <td>
        //             <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
        //         </td>
        //         <td>
        //             <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
        //             <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'"> 
        //         </td>
        //         <td>
        //             <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$materials['materialPrice'][$i].'" '.$readonly.'>
        //         </td>
        //         <td>
        //             <span id="materialSubTotal'.$materialCount.'" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
        //         </td>
        //     </tr>';
        //     $materialCount++;
        // }
        $materials = unserialize($buy->materials);
        $total_original_materials = count($materials['material']);
        
        $material_warehouses = unserialize($buy_to_stock->material_warehouses);

        $total_materials = count($material_warehouses['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){
            $price = '';
            $material = Material::where('id',$material_warehouses['material'][$i])->first();
            
            $warehouse = Warehouse::where('id',$material_warehouses['warehouse'][$i])->first();
            $warehouse_code = $warehouse->code;

            for($j = 0; $j < $total_original_materials; $j++){
                if($material_warehouses['material'][$i] == $materials['material'][$j]){
                    $price = $materials['materialPrice'][$j];
                }
            }

            $style = ' style="display:none"';
            $readonly = ' readonly';
            $disabled = ' disabled';
          
            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$material_warehouses['material'][$i].'">
                </td>
                <td>
                    <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                </td>
                <td>
                    <span id="materialAmount_show'.$materialCount.'" class="materialAmount_show" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material_warehouses['materialAmount'][$i].'</span>   
                    <input type="hidden" name="original_materialAmount[]" id="original_materialAmount'.$materialCount.'" class="original_materialAmount" placeholder="0" value="'.$material_warehouses['materialAmount'][$i].'">                           
                </td>
                <td>                
                    <span id="materialWarehouse_show'.$materialCount.'" class="materialWarehouse_show" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$warehouse_code.'</span>
                    <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="materialWarehouse" placeholder="0" value="'.$material_warehouses['warehouse'][$i].'">                           
                    
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;">
                </td>
                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$price.'</span>
                    <input type="hidden" name="materialPrice[]" id="materialPrice'.$materialCount.'" class="materialPrice" placeholder="0" value="'.$price.'">
                </td>
                <td>
                    <span id="materialSubTotal'.$materialCount.'" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }

        // if($buy->updated_user > 0){
        //     $updated_user = User::where('id',$buy->updated_user)->first();
        // } else {
        //     $updated_user = User::where('id',$buy->created_user)->first();
        // }
        // return view('purchase.buy.edit', compact('buy','materials','data','materialCount','updated_user'));

        $buy->return_created_at = Now();
        $buy->return_updated_at = Now();
        $buy->return_created_user = session('admin_user')->id;
        $buy->return_delete_flag = 0;
        $buy->save();
        return view('purchase.p_sales_return.create',compact('buy','materials','data','materialCount'));
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

        $buy = Buy::find($id);

        // $materials = unserialize($buy->materials);

        // $total_materials = count($materials['material']);
        // $materialCount = 0;
        // $data = '';
        // for($i = 0; $i < $total_materials; $i++){
        
        //     $material = Material::where('id',$materials['material'][$i])->first();
            
        //     $style = ' style="display:none"';
        //     $readonly = ' readonly';
        //     $disabled = ' disabled';
          
        //     $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
        //         <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
        //         <td>
        //             <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
        //             <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
        //         </td>
        //         <td>
        //             <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
        //         </td>
        //         <td>
        //             <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
        //             <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'"> 
        //         </td>
        //         <td>
        //             <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$materials['materialPrice'][$i].'" '.$readonly.'>
        //         </td>
        //         <td>
        //             <span id="materialSubTotal'.$materialCount.'" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
        //         </td>
        //     </tr>';
        //     $materialCount++;
        // }

        // $exchange = P_exchange::find($id);
        // $buy = Buy::find($exchange->buy_id);
        $materials = unserialize($buy->return_materials);

        $total_materials = count($materials['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){
        
            $material = Material::where('id',$materials['material'][$i])->first();

            $warehouse = Warehouse::where('id',$materials['warehouse'][$i])->first();
            $warehouse_code = $warehouse->code;
            
            $style = ' style="display:none"';
            if($buy->status_return == 1){
                $readonly = '';                
            } elseif($buy->status_return == 2){
                $readonly = ' readonly';
            }
            $disabled = ' disabled';
          
            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                </td>
                <td>
                    <span id="materialAmount_show'.$materialCount.'" class="materialAmount_show" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$materials['original_materialAmount'][$i].'</span>   
                    <input type="hidden" name="original_materialAmount[]" id="original_materialAmount'.$materialCount.'" class="original_materialAmount" placeholder="0" value="'.$materials['original_materialAmount'][$i].'">                           
                </td>
                <td>                
                    <span id="materialWarehouse_show'.$materialCount.'" class="materialWarehouse_show" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$warehouse_code.'</span>
                    <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="materialWarehouse" placeholder="0" value="'.$materials['warehouse'][$i].'">                           
                    
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'">
                </td>
                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$materials['materialPrice'][$i].'</span>
                    <input type="hidden" name="materialPrice[]" id="materialPrice'.$materialCount.'" class="materialPrice" placeholder="0" value="'.$materials['materialPrice'][$i].'">
                </td>
                <td>
                    <span id="materialSubTotal'.$materialCount.'" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }

        if($buy->return_updated_user > 0){
            $return_updated_user = User::where('id',$buy->return_updated_user)->first();
        } else {
            $return_updated_user = User::where('id',$buy->return_created_user)->first();
        }

        return view('purchase.p_sales_return.edit',compact('buy','materials','data','materialCount','return_updated_user'));
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
        $original_materialAmount = [];
        $materialAmount = [];
        $materialPrice = [];
        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $warehouse[] = $request->materialWarehouse[$i];
                $original_materialAmount[] = $request->original_materialAmount[$i];
                $materialAmount[] = $request->materialAmount[$i];
                $materialPrice[] = $request->materialPrice[$i];
            }
        }
        
        $return_materials = ['material'=>$material, 'warehouse'=>$warehouse, 'original_materialAmount'=>$original_materialAmount, 'materialAmount'=>$materialAmount, 'materialPrice'=>$materialPrice];

        try{

            if($request->status_return == 1){
                $buy = Buy::find($id);
                $buy->return_materials = serialize($return_materials);
                $buy->returnDate = $request->returnDate;
                if($request->realReturnDate){
                    $buy->realReturnDate = $request->realReturnDate;
                }
                $buy->memo_return = $request->memo_return;
                $buy->status_return = $request->status_return;
                $buy->return_updated_at = Now();            
                $buy->return_updated_user = session('admin_user')->id;
                $buy->save();
    
                return redirect()->route('p_sales_return.index')->with('message', '存檔成功');

            } else if($request->status_return == 2){
                $buy = Buy::find($id);
                $buy->return_materials = serialize($return_materials);
                $buy->returnDate = $request->returnDate;
                $buy->realReturnDate = $request->realReturnDate;
                $buy->memo_return = $request->memo_return;
                $buy->status_return = $request->status_return;
                $buy->return_updated_at = Now();            
                $buy->return_updated_user = session('admin_user')->id;
                $buy->save();



                for($j=0; $j < $total_materials; $j++){

                    if($materialAmount[$j] > 0){
                        // 修改退貨單
                        $material_stock = Material::find($material[$j]);
                        $material_warehouse = Material_warehouse::where('delete_flag','0')->where('material_id',$material[$j])->where('warehouse_id',$warehouse[$j])->first();
                        $warehouse_start_quantity = $material_warehouse->stock;
                        $quantity = number_format($materialAmount[$j],2,'.','');
                        $warehouse_end_quantity = $warehouse_start_quantity - $quantity;
                        $unit = $material_stock->material_unit_name->name;
                        $str = $warehouse_start_quantity.' '.$unit.' -> '.number_format($warehouse_end_quantity,2,'.','').' '.$unit;

                        // 庫存處理
                        $stock = new Stock;
                        $stock->lot_number = $buy->lot_number;
                        $stock->buy_no = $buy->buy_no;
                        $stock->stock_option = 41;
                        $stock->status = 0;
                        $stock->stock_no = $material_stock->stock_no + 1;                                                                      
                        $stock->material = $material[$j];
                        $stock->warehouse = $warehouse[$j];
                        $stock->supplier = $buy->supplier;
                        $stock->total_start_quantity = $material_stock->stock;                                    
                        $stock->start_quantity = $warehouse_start_quantity;
                        $stock->quantity = $quantity;
                        $stock->calculate_quantity = $str;
                        $stock->stock_date = $request->realReturnDate;                 
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
                }

                // 建立應付帳款
                $account_payable = new Account_payable;
                $account_payable->lot_number = $buy->lot_number;
                $account_payable->supplier = $buy->supplier;
                $account_payable->buy_no = $buy->buy_no;
                $account_payable->createDate = date("Y-m-d");

                $account_payable_no = date("Ymd")."001";
                $last_account_payable_no = Account_payable::orderBy('account_payable_no','DESC')->first();
                if($last_account_payable_no){
                    if($last_account_payable_no->account_payable_no >= $account_payable_no){
                        $account_payable_no = $last_account_payable_no->account_payable_no + 1;
                    }
                }

                $account_payable->account_payable_no = $account_payable_no;
                $account_payable->materials = serialize($return_materials);
                $account_payable->status = 1;
                $account_payable->return_status = 1;
                $account_payable->created_user = session('admin_user')->id;
                $account_payable->delete_flag = 0;
                $account_payable->save();
    
                return redirect()->route('p_sales_return.index')->with('message', '存檔成功, 並扣除庫存與建立一筆負的應付帳款');

            }
        } catch(Exception $e) {
            return redirect()->route('p_sales_return.index')->with('error', '存檔失敗');
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
        //
    }
}
