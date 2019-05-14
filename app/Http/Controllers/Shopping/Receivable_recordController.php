<?php

namespace App\Http\Controllers\Shopping;

use App\Model\User;
use App\Model\Material;
use Illuminate\Http\Request;
use App\Model\Account_receivable;
use App\Http\Controllers\Controller;

class Receivable_recordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';        
        $account_receivables = Account_receivable::where('delete_flag','0')->where('status','2')->get();
        return view("shopping.receivable_record.show",compact('account_receivables','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $account_receivables = Account_receivable::where('delete_flag','0')->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            } else {
                $account_receivables = Account_receivable::where('delete_flag','0')->where('status',$search_code)->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            }
        } else {
            if($search_code == 'all'){
                $account_receivables = Account_receivable::where('delete_flag','0')->get();
            } else {
                $account_receivables = Account_receivable::where('delete_flag','0')->where('status',$search_code)->get();
            }
        }
        return view('shopping.receivable_record.show',compact('account_receivables','search_code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account_receivable_no = date("Ymd")."001";
        $last_account_receivable_no = Account_receivable::orderBy('account_receivable_no','DESC')->first();
        if($last_account_receivable_no){
            if($last_account_receivable_no->account_receivable_no >= $account_receivable_no){
                $account_receivable_no = $last_account_receivable_no->account_receivable_no + 1;
            }
        }

        $today = getdate();
        $current_year = $today['year'];
        $current_month = $today['mon'];
        $months = [];

        if($current_month != 12){
            $last_year_months = 12 - $current_month;
            for($i = 0; $i < $current_month ; $i++ ){
                $months[] = $current_year.str_pad($current_month - $i, 2, '0',STR_PAD_LEFT);
            }
            for($j = 0; $j < $last_year_months ; $j++ ){
                $months[] = ($current_year - 1).str_pad((12 - $j), 2, '0',STR_PAD_LEFT);
            }
        } else {
            for($i = 0; $i < $current_month ; $i++ ){
                $months[] = $current_year.str_pad($current_month - $i, 2, '0',STR_PAD_LEFT);
            }
        }

        return view('shopping.account_receivable.create',compact('account_receivable_no','months'));
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
            'createDate' => 'required',
        ];
        $messages = [
            'lot_number.required' => '批號 必填',
            'supplier.required' => '尚未選擇 客戶',
            'createDate.required' => '開單日期 必填',
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

            $sale_no = null;
            if($request->sale_no != ''){
                $sale_no = substr($request->sale_no,1);
            }

            try{
                $account_receivable = new Account_receivable;
                $account_receivable->lot_number = $request->lot_number;
                $account_receivable->customer = $request->customer;
                $account_receivable->sale_no = $sale_no;
                $account_receivable->account_month = $request->month;
                $account_receivable->createDate = $request->createDate;
                $account_receivable->receivableDate = $request->receivableDate;
                $account_receivable->account_receivable_no = $request->account_receivable_no;
                $account_receivable->discount = $request->discount;    
                $account_receivable->total = $request->receivable;    
                $account_receivable->memo = $request->memo;
                $account_receivable->material_warehouses = serialize($material_warehouses);
                $account_receivable->status = $request->status;
                $account_receivable->created_user = session('admin_user')->id;
                $account_receivable->delete_flag = 0;
                $account_receivable->save();
                return redirect()->route('account_receivable.index')->with('message', '新增成功');
            } catch(Exception $e) {
                return redirect()->route('account_receivable.index')->with('error', '新增失敗');
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
        $account_receivable = Account_receivable::find($id);
        $material_warehouses = unserialize($account_receivable->material_warehouses);

        $total_materials = count($material_warehouses['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){
        
            $material = Material::where('id',$material_warehouses['material'][$i])->first();

            $style = ' style="display:none"';
            $readonly = ' readonly';
            $disabled = ' disabled';
            
            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;color:black;font-weight: bold;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$material_warehouses['material'][$i].'">
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$material_warehouses['materialAmount'][$i].'" '.$readonly.'>
                </td>
                <td>
                    <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                    <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'"> 
                </td>
                <td>
                    <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$material_warehouses['materialPrice'][$i].'" '.$readonly.'>
                </td>
                <td>
                    <span id="materialSubTotal'.$materialCount.'" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }

        if($account_receivable->updated_user > 0){
            $updated_user = User::where('id',$account_receivable->updated_user)->first();
            $created_user = User::where('id',$account_receivable->created_user)->first();
        } else {
            $updated_user = User::where('id',$account_receivable->created_user)->first();
            $created_user = User::where('id',$account_receivable->created_user)->first();            
        }

        $today = getdate();
        $current_year = $today['year'];
        $current_month = $today['mon'];
        $months = [];

        if($current_month != 12){
            $last_year_months = 12 - $current_month;
            for($i = 0; $i < $current_month ; $i++ ){
                $months[] = $current_year.str_pad($current_month - $i, 2, '0',STR_PAD_LEFT);
            }
            for($j = 0; $j < $last_year_months ; $j++ ){
                $months[] = ($current_year - 1).str_pad((12 - $j), 2, '0',STR_PAD_LEFT);
            }
        } else {
            for($i = 0; $i < $current_month ; $i++ ){
                $months[] = $current_year.str_pad($current_month - $i, 2, '0',STR_PAD_LEFT);
            }
        }

        return view('shopping.account_receivable.edit', compact('account_receivable','data','materialCount','updated_user','created_user','months'));
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

        try{
            $account_receivable = Account_receivable::find($id);
            
            if($account_receivable->sale_no == ''){
                if($request->sale_no != ''){
                    $sale_no = substr($request->sale_no,1);
                    $account_receivable->sale_no = $sale_no;
                }
            }
            $account_receivable->account_month = $request->month;

            $account_receivable->receivableDate = $request->receivableDate;
            $account_receivable->discount = $request->discount;    
            $account_receivable->total = $request->receivable;    
            $account_receivable->memo = $request->memo;

            $account_receivable->status = $request->status;
            $account_receivable->updated_user = session('admin_user')->id;
            $account_receivable->save();
            return redirect()->route('account_receivable.index')->with('message', '修改成功');
        } catch(Exception $e) {
            return redirect()->route('account_receivable.index')->with('error', '修改失敗');
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
            $account_receivable = Account_receivable::find($id);
            $account_receivable->delete_flag = 1;
            $account_receivable->deleted_at = Now();
            $account_receivable->deleted_user = session('admin_user')->id;
            $account_receivable->save();
            return redirect()->route('account_receivable.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('account_receivable.index')->with('error','刪除失敗');            
        }
    }
}
