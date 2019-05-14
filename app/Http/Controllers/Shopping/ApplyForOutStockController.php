<?php

namespace App\Http\Controllers\Shopping;

use App\Model\User;
use App\Model\Stock;
use App\Model\Gallery;
use App\Model\Material;
use App\Model\Warehouse;
use Illuminate\Http\Request;
use App\Model\Apply_out_stock;
use App\Model\Account_receivable;
use App\Model\Material_warehouse;
use App\Http\Controllers\Controller;

class ApplyForOutStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $applies = Apply_out_stock::where('delete_flag','0')->orderBy('apply_no','DESC')->get();
        return view('shopping.apply.show',compact('applies','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $applies = Apply_out_stock::where('delete_flag','0')->where('lot_number','like','%'.$request->search_lot_number.'%')->orderBy('apply_no','DESC')->get();
            } else {
                $applies = Apply_out_stock::where('delete_flag','0')->where('status',$search_code)->where('lot_number','like','%'.$request->search_lot_number.'%')->orderBy('apply_no','DESC')->get();
            }
        } else {
            if($search_code == 'all'){
                $applies = Apply_out_stock::where('delete_flag','0')->orderBy('apply_no','DESC')->get();
            } else {
                $applies = Apply_out_stock::where('delete_flag','0')->where('status',$search_code)->orderBy('apply_no','DESC')->get();
            }
        }
        return view('shopping.apply.show',compact('applies','search_code'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $apply_no = date("Ymd")."001";
        $last_apply_no = Apply_out_stock::orderBy('apply_no','DESC')->first();
        if($last_apply_no){
            if($last_apply_no->apply_no >= $apply_no){
                $apply_no = $last_apply_no->apply_no + 1;
            }
        }
        return view('shopping.apply.create',compact('apply_no')); 
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
            'applyDate' => 'date_format:"Y-m-d"|required',
            'expireDate' => 'date_format:"Y-m-d"|required',
        ];
        $messages = [
            'lot_number.required' => '批號 必填',          
            'customer.required' => '尚未選擇 客戶',
            'applyDate.required' => '申請日期 必填',
            'expireDate.required' => '有效期限 必填',
            'applyDate.date_format' => '申請日期格式錯誤',
            'expireDate.date_format' => '有效期限格式錯誤',
        ];
        $this->validate($request, $rules, $messages);

        $total_materials = count($request->material);
        $material = [];
        $warehouse = [];
        $materialAmount = [];
        $materialPrice = [];
        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $warehouse[] = $request->materialWarehouse[$i];
                $materialAmount[] = $request->materialAmount[$i];
                $materialPrice[] = $request->materialPrice[$i];
            }
        }

        if(count($material) > 0){
            $material_warehouses = ['material'=>$material, 'warehouse'=>$warehouse, 'materialAmount'=>$materialAmount, 'materialPrice'=>$materialPrice];
            
            $file_1=null;
            $file_2=null;
            $file_3=null;
            if($request->hasFile('upload_image_1')){
                $file_1 = $this->file_process($request->name_1, $request->upload_image_1);
            } 
            if($request->hasFile('upload_image_2')){
                $file_2 = $this->file_process($request->name_2, $request->upload_image_2);
            }
            if($request->hasFile('upload_image_3')){
                $file_3 = $this->file_process($request->name_3, $request->upload_image_3);
            }

            $sale_no = null;
            if($request->sale_no != ''){
                $sale_no = substr($request->sale_no,1);
            }

            try{
                $apply = new Apply_out_stock;
                $apply->apply_no = $request->apply_no;
                $apply->lot_number = $request->lot_number;
                $apply->sale_no = $sale_no;
                $apply->customer = $request->customer;
                $apply->material_warehouses = serialize($material_warehouses);
                $apply->applyDate = $request->applyDate;
                $apply->expireDate = $request->expireDate;
                $apply->memo = $request->memo;
                $apply->file_1 = $file_1;
                $apply->file_2 = $file_2;
                $apply->file_3 = $file_3;
                $apply->status = 1;
                $apply->created_user = session('admin_user')->id;
                $apply->delete_flag = 0;
                $apply->save();
                return redirect()->route('apply_out_stock.index')->with('message', '新增成功');
            } catch(Exception $e) {
                return redirect()->route('apply_out_stock.index')->with('error', '新增失敗');
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
        $apply = Apply_out_stock::find($id);
    
        $material_warehouses = unserialize($apply->material_warehouses);
        $total_materials = count($material_warehouses['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){
        
            $material = Material::where('id',$material_warehouses['material'][$i])->first();
            $material_warehouse = Material_warehouse::where('material_id',$material_warehouses['material'][$i])->where('warehouse_id',$material_warehouses['warehouse'][$i])->first();
            $warehouse_id = $material_warehouses['warehouse'][$i];
            $warehouse = Warehouse::find($warehouse_id);
            $warehouse_name = $warehouse->code;
            $style = '';
            $readonly = '';
            $disabled = '';
            if($apply->status == 2){
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
                    <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$warehouse_name.'</button>
                    <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse" value="'.$warehouse_id.'">
                </td>

                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$material_warehouses['materialAmount'][$i].'" '.$readonly.'>
                </td>

                <td>
                    <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$material_warehouses['materialPrice'][$i].'" '.$readonly.'>
                </td>
                <td>
                    <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                    <input type="hidden" name="materialPriceSubTotal[]" id="materialPriceSubTotal'.$materialCount.'" class="materialPriceSubTotal">
                </td>
            </tr>';

            
            $materialCount++;
        }


        if($apply->updated_user > 0){
            $updated_user = User::where('id',$apply->updated_user)->first();
        } else {
            $updated_user = User::where('id',$apply->created_user)->first();
        }

        return view('shopping.apply.show_one', compact('apply','materials','data','materialCount','updated_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $apply = Apply_out_stock::find($id);

        if($apply->material_warehouses){
            $material_warehouses = unserialize($apply->material_warehouses);
    
            $total_materials = count($material_warehouses['material']);
            $materialCount = 0;
            $data = '';
            for($i = 0; $i < $total_materials; $i++){
            
                $material = Material::where('id',$material_warehouses['material'][$i])->first();
                $material_warehouse = Material_warehouse::where('material_id',$material_warehouses['material'][$i])->where('warehouse_id',$material_warehouses['warehouse'][$i])->first();
                $warehouse_id = $material_warehouses['warehouse'][$i];
                $warehouse = Warehouse::find($warehouse_id);
                $warehouse_name = $warehouse->code;
                $style = '';
                $readonly = '';
                $disabled = '';
                if($apply->status == 2){
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
                        <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$warehouse_name.'</button>
                        <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse" value="'.$warehouse_id.'">
                    </td>
                    <td>
                        <span id="materialStock_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material_warehouse->stock.'</span>
                        <input type="hidden" name="materialStock[]" id="materialStock'.$materialCount.'" class="materialStock" value="'.$material_warehouse->stock.'">                
                    </td>
                    <td>
                        <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$material_warehouses['materialAmount'][$i].'" '.$readonly.'>
                    </td>
                    <td>
                        <span id="materialSubTotal_show'.$materialCount.'" class="materialSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                        <input type="hidden" name="materialSubTotal[]" id="materialSubTotal'.$materialCount.'" class="materialSubTotal">
                    </td>
                    <td>
                        <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$material_warehouses['materialPrice'][$i].'" '.$readonly.'>
                    </td>
                    <td>
                        <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                        <input type="hidden" name="materialPriceSubTotal[]" id="materialPriceSubTotal'.$materialCount.'" class="materialPriceSubTotal">
                    </td>
                </tr>';

              
                $materialCount++;
            }
        } else {
            $materials = unserialize($apply->materials);
            $total_materials = count($materials['material']);
            $materialCount = 0;
            $data = '';
            for($i = 0; $i < $total_materials; $i++){
            
                $material = Material::where('id',$materials['material'][$i])->first();
                $material_warehouse = Material_warehouse::where('material_id',$materials['material'][$i])->where('warehouse_id',$material->warehouse)->first();                
                $warehouse_name = $material->warehouse_name->code;
                $warehouse_id = $material->warehouse; 
                $style = '';
                $readonly = '';
                $disabled = '';
                if($apply->status == 2){
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
                        <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 100%; margin-right: 10px; overflow: hidden;"> '.$material->warehouse_name->code.'</button>
                        <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse" value="'.$material->warehouse.'">
                    </td>
                    <td>
                        <span id="materialStock_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material_warehouse->stock.'</span>
                        <input type="hidden" name="materialStock[]" id="materialStock'.$materialCount.'" class="materialStock" value="'.$material_warehouse->stock.'">                
                    </td>
                    <td>
                        <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                    </td>
                    <td>
                        <span id="materialSubTotal_show'.$materialCount.'" class="materialSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                        <input type="hidden" name="materialSubTotal[]" id="materialSubTotal'.$materialCount.'" class="materialSubTotal">
                    </td>
                    <td>
                        <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$materials['materialPrice'][$i].'" '.$readonly.'>
                    </td>
                    <td>
                        <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                        <input type="hidden" name="materialPriceSubTotal[]" id="materialPriceSubTotal'.$materialCount.'" class="materialPriceSubTotal">
                    </td>
                </tr>';
                $materialCount++;
            }

        }


        if($apply->updated_user > 0){
            $updated_user = User::where('id',$apply->updated_user)->first();
        } else {
            $updated_user = User::where('id',$apply->created_user)->first();
        }

        $upload_check_1 = true;
        $upload_check_2 = true;
        $upload_check_3 = true;

        if($apply->file_1 > 0){
            $upload_check_1 = false;
        }
        if($apply->file_2 > 0){
            $upload_check_2 = false;
        }
        if($apply->file_3 > 0){
            $upload_check_3 = false;
        }

        return view('shopping.apply.edit', compact('apply','materials','data','materialCount','updated_user','upload_check_1','upload_check_2','upload_check_3'));
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
        if($request->status == 2){
            if($request->receiveDate == ''){
                return redirect()->back()->with('error', '通過日期 必填');            
            }
        }
        $rules = [
            // 'lot_number' => 'required',                           
            // 'buyDate' => 'required',
            // 'expectedReceiveDate' => 'required',
        ];
        $messages = [
            // 'lot_number.required' => '批號 必填',                      
            // 'buyDate.required' => '採購日期 必填',
            // 'expectedReceiveDate.required' => '預計到貨日 必填',
        ];
        $this->validate($request, $rules, $messages);

        $total_materials = count($request->material);
        $material = [];
        $warehouse = [];
        $materialAmount = [];
        $materialPrice = [];
        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $warehouse[] = $request->materialWarehouse[$i];
                $materialAmount[] = $request->materialAmount[$i];
                $materialPrice[] = $request->materialPrice[$i];
            }
        }
        
        if(count($material) > 0){
            $material_warehouses = ['material'=>$material, 'warehouse'=>$warehouse, 'materialAmount'=>$materialAmount, 'materialPrice'=>$materialPrice];

            $file_1=null;
            $file_2=null;
            $file_3=null;
            $check_1 = false;
            $check_2 = false;
            $check_3 = false;
            if($request->hasFile('upload_image_1')){
                $file_1 = $this->file_process($request->name_1, $request->upload_image_1);
                $check_1 = true;
            } 
            if($request->hasFile('upload_image_2')){
                $file_2 = $this->file_process($request->name_2, $request->upload_image_2);
                $check_2 = true;
            }
            if($request->hasFile('upload_image_3')){
                $file_3 = $this->file_process($request->name_3, $request->upload_image_3);
                $check_3 = true;
            }

            $sale_no = null;
            if($request->sale_no != ''){
                $sale_no = substr($request->sale_no,1);
            }
            
            if($request->status == 1){
                try{
                    $apply = Apply_out_stock::find($id);
                    $apply->sale_no = $sale_no;                                    
                    $apply->material_warehouses = serialize($material_warehouses);
                    $apply->applyDate = $request->applyDate;
                    $apply->expireDate = $request->expireDate;
                    $apply->receiveDate = $request->receiveDate;
                    $apply->memo = $request->memo;
                    if($check_1){
                        $apply->file_1 = $file_1;
                    }
                    if($check_2){
                        $apply->file_2 = $file_2;
                    }
                    if($check_3){
                        $apply->file_3 = $file_3;
                    }   
                    $apply->status = $request->status;
                    $apply->updated_user = session('admin_user')->id;
                    $apply->save();

                    return redirect()->route('apply_out_stock.index')->with('message', '修改成功');
                } catch(Exception $e) {
                    return redirect()->route('apply_out_stock.index')->with('error', '修改失敗');
                }
            } else if($request->status == 2){
                try{
                    $apply = Apply_out_stock::find($id);
                    $apply->sale_no = $sale_no;                    
                    $apply->material_warehouses = serialize($material_warehouses);
                    $apply->applyDate = $request->applyDate;
                    $apply->expireDate = $request->expireDate;
                    $apply->receiveDate = $request->receiveDate;
                    $apply->memo = $request->memo;
                    if($check_1){
                        $apply->file_1 = $file_1;
                    }
                    if($check_2){
                        $apply->file_2 = $file_2;
                    }
                    if($check_3){
                        $apply->file_3 = $file_3;
                    }   
                    $apply->status = $request->status;
                    $apply->updated_user = session('admin_user')->id;
                    $apply->save();


                    $account_receivable_no = date("Ymd")."001";
                    $last_account_receivable_no = Account_receivable::orderBy('account_receivable_no','DESC')->first();
                    if($last_account_receivable_no){
                        if($last_account_receivable_no->account_receivable_no >= $account_receivable_no){
                            $account_receivable_no = $last_account_receivable_no->account_receivable_no + 1;
                        }
                    }
                    $account_receivable = new Account_receivable;
                    $account_receivable->lot_number = $apply->lot_number;
                    $account_receivable->customer = $apply->customer;
                    $account_receivable->sale_no = $sale_no;
                    $account_receivable->createDate = $request->receiveDate;
                    $account_receivable->account_receivable_no = $account_receivable_no;
                    $account_receivable->material_warehouses = serialize($material_warehouses);
                    $account_receivable->status = 1;
                    $account_receivable->created_user = session('admin_user')->id;
                    $account_receivable->delete_flag = 0;
                    $account_receivable->save();

                    $total = count($material_warehouses['material']);
                    for($i=0; $i < $total; $i++){
                        $material_stock = Material::find($material_warehouses['material'][$i]);
                        $material_warehouse = Material_warehouse::where('material_id',$material_warehouses['material'][$i])->where('warehouse_id',$material_warehouses['warehouse'][$i])->first();                
                        $warehouse_start_quantity = $material_warehouse->stock;
                        $quantity = number_format($material_warehouses['materialAmount'][$i],2,'.','');
                        $warehouse_end_quantity = $warehouse_start_quantity - $quantity;                        
                        $unit = $material_stock->material_unit_name->name;                        
                        $str = $warehouse_start_quantity.' '.$unit.' -> '.number_format($warehouse_end_quantity,2,'.','').' '.$unit;
                        
                        $stock = new Stock;
                        $stock->lot_number = $apply->lot_number;  
                        $stock->sale_no = $sale_no;                       
                        $stock->stock_option = 11;
                        $stock->status = 0;
                        $stock->stock_no = $material_stock->stock_no + 1;                                                  
                        $stock->material = $material_warehouses['material'][$i];
                        $stock->warehouse = $material_warehouses['warehouse'][$i];                        
                        $stock->customer = $apply->customer;
                        $stock->total_start_quantity = $material_stock->stock;                                                            
                        $stock->start_quantity = $warehouse_start_quantity;                        
                        $stock->quantity = $quantity;
                        $stock->calculate_quantity = $str;                        
                        $stock->stock_date = $request->receiveDate;
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

                    return redirect()->route('apply_out_stock.index')->with('message', '已轉出庫 與 新增一筆應收帳款單');
                } catch(Exception $e) {
                    return redirect()->route('apply_out_stock.index')->with('error', '轉出庫失敗');
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
            $apply = Apply_out_stock::find($id);
            $apply->delete_flag = 1;
            $apply->deleted_at = Now();
            $apply->deleted_user = session('admin_user')->id;
            $apply->save();
            return redirect()->route('apply_out_stock.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('apply_out_stock.index')->with('error','刪除失敗');            
        }
    }

    public function delete_file($file_no,$apply,$file_id)
    {
        try{
            $apply = Apply_out_stock::find($apply);
            if($file_no == 1){
                $apply->file_1 = null;
            } else if($file_no == 2){
                $apply->file_2 = null;
            } else if($file_no == 3){
                $apply->file_3 = null;
            }
            $apply->save();

            $gallery = Gallery::find($file_id);
            $gallery->delete_flag = 1;
            $gallery->deleted_at = Now();
            $gallery->deleted_user = session('admin_user')->id;
            $gallery->save();

            return redirect()->route('apply_out_stock.edit',$apply->id)->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('apply_out_stock.edit',$apply->id)->with('error','刪除失敗');            
        } 

    }

    private function thumb_process($origin_file_name, $tmp_file_name, $img_type, $tmp_w, $tmp_h)
    {
        $width = $tmp_w;
        $height = $tmp_h;

        $src_image = imagecreatefromstring(file_get_contents(asset('upload/'.$origin_file_name)));
        $src_width = imagesx($src_image);
        $src_height = imagesy($src_image);
        
        $tmp_image_width = 0;
        $tmp_image_height = 0;
        if ($src_width / $src_height >= $width / $height) {
            $tmp_image_width = $width;
            $tmp_image_height = round($tmp_image_width * $src_height / $src_width);
        } else {
            $tmp_image_height = $height;
            $tmp_image_width = round($tmp_image_height * $src_width / $src_height);
        }
        
        $tmpImage = imagecreatetruecolor($tmp_image_width, $tmp_image_height);
        imagecopyresampled($tmpImage, $src_image, 0, 0, 0, 0, $tmp_image_width, $tmp_image_height, $src_width, $src_height);
        
        $final_image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($final_image, 255, 255, 255);
        imagefill($final_image, 0, 0, $color);
        
        $x = round(($width - $tmp_image_width) / 2);
        $y = round(($height - $tmp_image_height) / 2);
        
        imagecopy($final_image, $tmpImage, $x, $y, 0, 0, $tmp_image_width, $tmp_image_height);

        if($img_type == '.jpeg' || $img_type == '.jpg'){
            $img_type = '.jpeg';
        }
        $func = "image".substr($img_type,1);
        $func($final_image,'upload/'.$tmp_file_name);
        if(isset($final_image)) {imagedestroy($final_image);}
        
    }
    private function file_process($name, $file)
    {
        $imageName = $file->getClientOriginalName();
        $fileType = strtolower(strrchr($imageName,'.'));
        $fileName = time().'_'.mt_rand(100,999);
        $thumb_origin = $fileName.$fileType;
        if($fileType == '.jpeg' || $fileType == '.png' || $fileType == '.jpg'){
            $thumb_450 = $fileName.'_450'.$fileType;
            $file->move('upload', $thumb_origin);
            $this->thumb_process($thumb_origin, $thumb_450, $fileType, 450, 450);
        } else {
            $thumb_450 = "file_image.jpg";            
            $file->move('upload', $thumb_origin);
        }
        $img = new Gallery;
        $img->name = $name;
        $img->origin_file_name = $imageName;
        $img->file_name = $thumb_origin;
        $img->thumb_name = $thumb_450;
        // material = 2 , warehouse = 3 ,material_module = 4, apply_out_stock = 5
        $img->category = 5;
        $img->created_user = session('admin_user')->id;
        $img->delete_flag = 0;
        $img->save();
        return $img->id;
    }
}
