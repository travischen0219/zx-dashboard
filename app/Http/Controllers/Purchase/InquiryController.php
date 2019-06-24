<?php

namespace App\Http\Controllers\Purchase;

use App\Model\Buy;
use App\Model\User;
use App\Model\Inquiry;
use App\Model\Material;
use App\Model\Material_unit;
use Illuminate\Http\Request;
use App\Model\Material_module;
use App\Http\Controllers\Controller;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $inquiries = Inquiry::where('delete_flag','0')->get();
        return view('purchase.inquiry.show',compact('inquiries','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $inquiries = Inquiry::where('delete_flag','0')->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            } else {
                $inquiries = Inquiry::where('delete_flag','0')->where('status',$search_code)->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            }
        } else {
            if($search_code == 'all'){
                $inquiries = Inquiry::where('delete_flag','0')->get();
            } else {
                $inquiries = Inquiry::where('delete_flag','0')->where('status',$search_code)->get();
            }
        }
        return view('purchase.inquiry.show',compact('inquiries','search_code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inquiry_no = date("Ymd")."001";
        $last_inquiry_no = Inquiry::orderBy('inquiry_no','DESC')->first();
        if($last_inquiry_no){
            if($last_inquiry_no->inquiry_no >= $inquiry_no){
                $inquiry_no = $last_inquiry_no->inquiry_no + 1;
            }
        }
        return view('purchase.inquiry.create',compact('inquiry_no'));
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
            'askDate' => 'date_format:"Y-m-d"|required',
            'expireDate' => 'date_format:"Y-m-d"|required',
        ];
        $messages = [
            'lot_number.required' => '批號 必填',
            'supplier.required' => '尚未選擇 供應商',
            'askDate.required' => '詢價日期 必填',
            'expireDate.required' => '有效期限 必填',
            'askDate.date_format' => '詢價日期格式錯誤',
            'expireDate.date_format' => '有效期限格式錯誤',
        ];
        $this->validate($request, $rules, $messages);

        $total_materials = count($request->material);
        $material = [];
        $materialCalAmount = [];
        $materialCalUnit = [];
        $materialCalPrice = [];
        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $materialCalAmount[] = $request->materialAmount[$i];
                $materialCalUnit[] = $request->materialUnit[$i];
                $materialCalPrice[] = $request->materialPrice[$i];
            }
        }

        if(count($material) > 0){
            $materials = ['material'=>$material, 'materialCalAmount'=>$materialCalAmount,'materialCalUnit'=>$materialCalUnit,'materialCalPrice'=>$materialCalPrice];

            try{
                $inquiry = new Inquiry;
                $inquiry->inquiry_no = $request->inquiry_no;
                $inquiry->lot_number = $request->lot_number;
                $inquiry->supplier = $request->supplier;
                $inquiry->materials = serialize($materials);
                $inquiry->askDate = $request->askDate;
                $inquiry->expireDate = $request->expireDate;
                $inquiry->memo = $request->memo;
                $inquiry->status = 1;
                $inquiry->created_user = session('admin_user')->id;
                $inquiry->delete_flag = 0;
                $inquiry->save();
                return redirect()->route('inquiry.index')->with('message', '新增成功');
            } catch(Exception $e) {
                return redirect()->route('inquiry.index')->with('error', '新增失敗');
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
        $inquiry = Inquiry::find($id);
        $materials = unserialize($inquiry->materials);
        $materials2 = Material_module::encodeMaterials($inquiry->materials, true);

        $total_materials = count($materials['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){

            $material = Material::where('id',$materials['material'][$i])->first();


            $units = Material_unit::where('delete_flag','0')->get();
            if($materials['materialCalUnit'][$i] == 0 || $materials['materialCalUnit'][$i] == ''){
                $select_str = '<option value="0" selected>未指定</option>';
            } else {
                $select_str = '<option value="0" >未指定</option>';
            }
            foreach($units as $unit){
                $select_str .= '<option value="'.$unit->id.'" ';
                if($materials['materialCalUnit'][$i] == $unit->id){
                    $select_str .= ' selected';
                }
                $select_str .= '> '.$unit->name.'</option>';
            }



            $style = '';
            $disabled = '';
            if($inquiry->status != 1){
                $style = ' style="display:none"';
                $disabled = ' disabled';
            }
            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden; color:black;font-weight: bold;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialCalAmount'][$i].'" '.$disabled.'>
                </td>
                <td>
                    <select id="materialUnit'.$materialCount.'" name="materialUnit[]" class="materialUnit" style="width: 100px; line-height: 30px; vertical-align: middle;" '.$disabled.'>
                    '.$select_str.'
                    </select>
                </td>
                <td>
                    <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$materials['materialCalPrice'][$i].'" '.$disabled.'>
                </td>
                <td>
                    <span id="materialSubTotal'.$materialCount.'" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }

        if($inquiry->updated_user > 0){
            $updated_user = User::where('id',$inquiry->updated_user)->first();
        } else {
            $updated_user = User::where('id',$inquiry->created_user)->first();
        }
        return view('purchase.inquiry.edit', compact('inquiry','materials','data','materialCount','updated_user'));
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
            if($request->dealDate == ''){
                return redirect()->back()->with('error', '成交日期 必填');
            }
        }
        $rules = [
            'lot_number' => 'required',
            'askDate' => 'date_format:"Y-m-d"|required',
            'expireDate' => 'date_format:"Y-m-d"|required',
        ];
        $messages = [
            'lot_number.required' => '批號 必填',
            'askDate.required' => '詢價日期 必填',
            'expireDate.required' => '有效期限 必填',
            'askDate.date_format' => '詢價日期格式錯誤',
            'expireDate.date_format' => '有效期限格式錯誤',
        ];
        $this->validate($request, $rules, $messages);

        $total_materials = count($request->material);
        $material = [];
        $materialCalAmount = [];
        $materialCalUnit = [];
        $materialCalPrice = [];
        $materialAmount = [];
        $materialPrice = [];

        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $materialCalAmount[] = $request->materialAmount[$i];
                $materialCalUnit[] = $request->materialUnit[$i];
                $materialCalPrice[] = $request->materialPrice[$i];

                $materialAmount[] = 0;
                $materialPrice[] = 0;

            }
        }

        if(count($material) > 0){
            $materials = ['material'=>$material, 'materialCalAmount'=>$materialCalAmount,'materialCalUnit'=>$materialCalUnit,'materialCalPrice'=>$materialCalPrice, 'materialAmount'=>$materialAmount, 'materialPrice'=>$materialPrice];

            try{
                $inquiry = Inquiry::find($id);
                $inquiry->lot_number = $request->lot_number;
                $inquiry->materials = serialize($materials);
                $inquiry->askDate = $request->askDate;
                $inquiry->expireDate = $request->expireDate;
                $inquiry->dealDate = $request->dealDate;
                $inquiry->memo = $request->memo;
                $inquiry->status = $request->status;
                $inquiry->updated_user = session('admin_user')->id;
                $inquiry->save();

                if($request->status == 2){
                    $buy_no = date("Ymd")."001";
                    $last_buy_no = Buy::orderBy('buy_no','DESC')->first();
                    if($last_buy_no){
                        if($last_buy_no->buy_no >= $buy_no){
                            $buy_no = $last_buy_no->buy_no + 1;
                        }
                    }
                    $buy = new Buy;
                    $buy->buy_no = $buy_no;
                    $buy->lot_number = $request->lot_number;
                    $buy->supplier = $inquiry->supplier;
                    $buy->materials = serialize($materials);
                    $buy->status = 1;
                    $buy->created_user = session('admin_user')->id;
                    $buy->delete_flag = 0;
                    $buy->save();

                    return redirect()->route('inquiry.index')->with('message', '修改成功 並已建立採購單 : P'.$buy_no);
                } else {
                    return redirect()->route('inquiry.index')->with('message', '修改成功');
                }
            } catch(Exception $e) {
                return redirect()->route('inquiry.index')->with('error', '修改失敗');
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
            $inquiry = Inquiry::find($id);
            $inquiry->delete_flag = 1;
            $inquiry->deleted_at = Now();
            $inquiry->deleted_user = session('admin_user')->id;
            $inquiry->save();
            return redirect()->route('inquiry.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('inquiry.index')->with('error','刪除失敗');
        }
    }


    public function addRow(Request $request)
    {
        $materialCount = $request->materialCount;

        $units = Material_unit::where('delete_flag','0')->get();

        $select_str = '<option value="0" >未指定</option>';
        foreach($units as $unit){
            $select_str .= '<option value="'.$unit->id.'" > '.$unit->name.'</option>';
        }



        $data = '<tr id="materialRow'.$materialCount.'" class="materialRow">
            <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red"><i class="fa fa-remove"></i></a></td>
            <td>
                <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇物料</button>
                <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material">
            </td>
            <td>
                <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;">
            </td>
            <td>
                <select id="materialUnit'.$materialCount.'" name="materialUnit[]" class="materialUnit" style="width: 100px; line-height: 30px; vertical-align: middle;">
                '.$select_str.'
                </select>
            </td>
            <td>
                <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;">
            </td>
            <td>
                <span id="materialSubTotal'.$materialCount.'" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
            </td>
        </tr>';

        return $data;
    }

    public function addModule(Request $request)
    {
        $id = $request->id;
        $materialCount = $request->materialCount;
        $modules = Material_module::find($id);
        $materials = unserialize($modules->materials);
        $total_materials = count($materials['material']);
        $materialCount = $materialCount+1;
        $return = [];
        $data = '';
        $disabled = '';
        $style = '';
        $readonly = '';

        for($i = 0; $i < $total_materials; $i++){
            $material = Material::where('id',$materials['material'][$i])->first();

            $units = Material_unit::where('delete_flag','0')->get();
            if($material->cal_unit == 0 || $material->cal_unit == ''){
                $select_str = '<option value="0" selected>未指定</option>';
            } else {
                $select_str = '<option value="0" >未指定</option>';
            }
            foreach($units as $unit){
                $select_str .= '<option value="'.$unit->id.'" ';
                if($material->cal_unit == $unit->id){
                    $select_str .= ' selected';
                }
                $select_str .= '> '.$unit->name.'</option>';
            }

            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;color:blue;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                </td>
                <td>
                    <select id="materialUnit'.$materialCount.'" name="materialUnit[]" class="materialUnit" style="width: 100px; line-height: 30px; vertical-align: middle;">
                    '.$select_str.'
                    </select>
                </td>
                <td>
                    <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$material->cal_price.'" '.$readonly.'>
                </td>
                <td>
                    <span id="materialSubTotal'.$materialCount.'" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }
        $return['data'] = $data;
        $return['materialCount'] = $materialCount;
        return $return;
    }
}


