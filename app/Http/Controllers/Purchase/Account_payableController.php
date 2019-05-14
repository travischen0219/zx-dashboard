<?php

namespace App\Http\Controllers\Purchase;

use App\Model\User;
use App\Model\Material;
use Illuminate\Http\Request;
use App\Model\Account_payable;
use App\Http\Controllers\Controller;

class Account_payableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';        
        $account_payables = Account_payable::where('delete_flag','0')->get();
        return view("purchase.account_payable.show",compact('account_payables','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $account_payables = Account_payable::where('delete_flag','0')->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            } else {
                $account_payables = Account_payable::where('delete_flag','0')->where('status',$search_code)->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            }
        } else {
            if($search_code == 'all'){
                $account_payables = Account_payable::where('delete_flag','0')->get();
            } else {
                $account_payables = Account_payable::where('delete_flag','0')->where('status',$search_code)->get();
            }
        }
        return view('purchase.account_payable.show',compact('account_payables','search_code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account_payable_no = date("Ymd")."001";
        $last_account_payable_no = Account_payable::orderBy('account_payable_no','DESC')->first();
        if($last_account_payable_no){
            if($last_account_payable_no->account_payable_no >= $account_payable_no){
                $account_payable_no = $last_account_payable_no->account_payable_no + 1;
            }
        }

        // $today = getdate();
        // $current_year = $today['year'];
        // $current_month = $today['mon'];
        // $months = [];

        // if($current_month != 12){
        //     $last_year_months = 12 - $current_month;
        //     for($i = 0; $i < $current_month ; $i++ ){
        //         $months[] = $current_year.str_pad($current_month - $i, 2, '0',STR_PAD_LEFT);
        //     }
        //     for($j = 0; $j < $last_year_months ; $j++ ){
        //         $months[] = ($current_year - 1).str_pad((12 - $j), 2, '0',STR_PAD_LEFT);
        //     }
        // } else {
        //     for($i = 0; $i < $current_month ; $i++ ){
        //         $months[] = $current_year.str_pad($current_month - $i, 2, '0',STR_PAD_LEFT);
        //     }
        // }

        return view('purchase.account_payable.create',compact('account_payable_no'));
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
            'supplier' => 'required',
            'createDate' => 'required',
        ];
        $messages = [
            'lot_number.required' => '批號 必填',
            'supplier.required' => '尚未選擇 供應商',
            'createDate.required' => '開單日期 必填',
        ];
        $this->validate($request, $rules, $messages);

        $total_materials = count($request->material);
        $material = [];
        $materialAmount = [];
        $materialUnit = [];
        $materialPrice = [];
        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $materialAmount[] = $request->materialAmount[$i];
                $materialUnit[] = $request->materialUnit[$i];
                $materialPrice[] = $request->materialPrice[$i];
            }
        }

        if(count($material) > 0){
            $materials = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialUnit'=>$materialUnit,'materialPrice'=>$materialPrice];

            $buy_no = null;
            if($request->buy_no != ''){
                $buy_no = substr($request->buy_no,1);
            }
            try{
                $account_payable = new Account_payable;
                $account_payable->lot_number = $request->lot_number;
                $account_payable->supplier = $request->supplier;
                $account_payable->buy_no = $buy_no;
                // $account_payable->account_month = $request->month;
                $account_payable->createDate = $request->createDate;
                $account_payable->payDate = $request->payDate;
                $account_payable->account_payable_no = $request->account_payable_no;
                $account_payable->total = $request->payable;    
                $account_payable->memo = $request->memo;
                $account_payable->materials = serialize($materials);
                $account_payable->status = $request->status;
                $account_payable->created_user = session('admin_user')->id;
                $account_payable->delete_flag = 0;
                $account_payable->save();
                return redirect()->route('account_payable.index')->with('message', '新增成功');
            } catch(Exception $e) {
                return redirect()->route('account_payable.index')->with('error', '新增失敗');
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
        $account_payable = Account_payable::find($id);
        $materials = unserialize($account_payable->materials);

        $total_materials = count($materials['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){
        
            $material = Material::where('id',$materials['material'][$i])->first();
            

                $style = ' style="display:none"';
                $readonly = ' readonly';
                $disabled = ' disabled';

            
            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;color:black;font-weight: bold;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                </td>
                <td>
                    <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                    <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'"> 
                </td>
                <td>
                    <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$materials['materialPrice'][$i].'" '.$readonly.'>
                </td>
                <td>
                    <span id="materialSubTotal'.$materialCount.'" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }

        if($account_payable->updated_user > 0){
            $updated_user = User::where('id',$account_payable->updated_user)->first();
            $created_user = User::where('id',$account_payable->created_user)->first();
        } else {
            $updated_user = User::where('id',$account_payable->created_user)->first();
            $created_user = User::where('id',$account_payable->created_user)->first();            
        }

        // $today = getdate();
        // $current_year = $today['year'];
        // $current_month = $today['mon'];
        // $months = [];

        // if($current_month != 12){
        //     $last_year_months = 12 - $current_month;
        //     for($i = 0; $i < $current_month ; $i++ ){
        //         $months[] = $current_year.str_pad($current_month - $i, 2, '0',STR_PAD_LEFT);
        //     }
        //     for($j = 0; $j < $last_year_months ; $j++ ){
        //         $months[] = ($current_year - 1).str_pad((12 - $j), 2, '0',STR_PAD_LEFT);
        //     }
        // } else {
        //     for($i = 0; $i < $current_month ; $i++ ){
        //         $months[] = $current_year.str_pad($current_month - $i, 2, '0',STR_PAD_LEFT);
        //     }
        // }

        return view('purchase.account_payable.edit', compact('account_payable','materials','data','materialCount','updated_user','created_user'));
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
        $rules = [
            // 'lot_number' => 'required',            
            // 'supplier' => 'required',
            // 'createDate' => 'required',
        ];
        $messages = [
            // 'lot_number.required' => '批號 必填',
            // 'supplier.required' => '尚未選擇 供應商',
            // 'createDate.required' => '開單日期 必填',
        ];
        $this->validate($request, $rules, $messages);
        
        try{
            $account_payable = Account_payable::find($id);
            if($account_payable->buy_no == ''){
                $buy_no = null;            
                if($request->buy_no != ''){
                    $buy_no = substr($request->buy_no,1);
                    $account_payable->buy_no = $buy_no;
                }
                $account_payable->buy_no = $buy_no;
            }

            $account_payable->payDate = $request->payDate;
            $account_payable->total = $request->payable;    
            $account_payable->memo = $request->memo;
            $account_payable->status = $request->status;
            $account_payable->updated_user = session('admin_user')->id;
            $account_payable->save();
            return redirect()->route('account_payable.index')->with('message', '修改成功');
        } catch(Exception $e) {
            return redirect()->route('account_payable.index')->with('error', '修改成功');
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
            $account_payable = Account_payable::find($id);
            $account_payable->delete_flag = 1;
            $account_payable->deleted_at = Now();
            $account_payable->deleted_user = session('admin_user')->id;
            $account_payable->save();
            return redirect()->route('account_payable.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('account_payable.index')->with('error','刪除失敗');            
        }
    }
}
