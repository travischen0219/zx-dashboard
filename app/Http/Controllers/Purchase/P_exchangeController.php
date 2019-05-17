<?php

namespace App\Http\Controllers\Purchase;

use App\Model\Buy;
use App\Model\User;
use App\Model\Material;
use App\Model\P_exchange;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class P_exchangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $exchanges = P_exchange::where('delete_flag','0')->orderBy('updated_at','DESC')->get();

        // foreach($exchanges as $exchange) {
        //     dd($exchange->buy_name);
        // }

        return view('purchase.p_exchange.show',compact('exchanges','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $exchanges = P_exchange::where('delete_flag','0')->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            } else {
                $exchanges = P_exchange::where('delete_flag','0')->where('status',$search_code)->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            }
        } else {
            if($search_code == 'all'){
                $exchanges = P_exchange::where('delete_flag','0')->get();
            } else {
                $exchanges = P_exchange::where('delete_flag','0')->where('status',$search_code)->get();
            }
        }
        return view('purchase.p_exchange.show',compact('exchanges','search_code'));
    }
    public function search_exchange(Request $request)
    {

        $search_code = 'all';
        $exchanges = P_exchange::where('delete_flag','0')->where('buy_id',$request->buy_id)->orderBy('updated_at','DESC')->get();

        return view('purchase.p_exchange.show',compact('exchanges','search_code'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
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
        if($buy){

        } else {
            return redirect()->back()->with('error','查無此單號');
        }

        $materials = unserialize($buy->materials);

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
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                </td>
                <td>
                    <span id="materialAmount_show'.$materialCount.'" class="materialAmount_show" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$materials['materialAmount'][$i].'</span>
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;">
                </td>
                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$materials['materialPrice'][$i].'</span>
                    <input type="hidden" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$materials['materialPrice'][$i].'" '.$readonly.'>
                </td>
                <td>
                    <span id="materialSubTotal'.$materialCount.'" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }

        return view('purchase.p_exchange.create',compact('buy','materials','data','materialCount'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $total_materials = count($request->material);
        $material = [];
        $materialAmount = [];
        $materialPrice = [];
        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $materialAmount[] = $request->materialAmount[$i];
                $materialPrice[] = $request->materialPrice[$i];
            }
        }

        $materials = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialPrice'=>$materialPrice];

        try{
            $buy = Buy::find($request->buy_id);

            $exchange = new P_exchange;
            $exchange->lot_number = $buy->lot_number;
            $exchange->buy_id = $buy->id;
            $exchange->buy_no = $buy->buy_no;
            $exchange->supplier = $buy->supplier;
            $exchange->materials = serialize($materials);
            $exchange->exchangeDate = $request->exchangeDate;
            if($request->realExchangeDate){
                $exchange->realExchangeDate = $request->realExchangeDate;
            }
            $exchange->status = $request->status;
            $exchange->memo = $request->memo;
            $exchange->created_user = session('admin_user')->id;
            $exchange->delete_flag = 0;
            $exchange->save();

            $buy->status_exchange = $request->status;
            $buy->save();
            return redirect()->route('p_exchange.index')->with('message', '新增成功');
        } catch(Exception $e) {
            return redirect()->route('p_exchange.index')->with('error', '新增失敗');
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
        $exchange = P_exchange::find($id);
        $buy = Buy::find($exchange->buy_id);
        $original_materials = unserialize($buy->materials);

        $materials = unserialize($exchange->materials);
        $total_materials = count($materials['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){

            $material = Material::where('id',$materials['material'][$i])->first();

            $style = ' style="display:none"';
            if($exchange->status == 1){
                $readonly = '';
            } elseif($exchange->status == 2){
                $readonly = ' readonly';
            }
            $disabled = ' disabled';

            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                </td>
                <td>
                    <span id="materialAmount_show'.$materialCount.'" class="materialAmount_show" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$original_materials['materialAmount'][$i].'</span>
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'">
                </td>
                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$materials['materialPrice'][$i].'</span>
                    <input type="hidden" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$materials['materialPrice'][$i].'" '.$readonly.'>
                </td>
                <td>
                    <span id="materialSubTotal'.$materialCount.'" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }
        if($exchange->updated_user > 0){
            $updated_user = User::where('id',$exchange->updated_user)->first();
        } else {
            $updated_user = User::where('id',$exchange->created_user)->first();
        }
        return view('purchase.p_exchange.edit',compact('exchange','materials','data','materialCount','updated_user'));
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
        $materialAmount = [];
        $materialPrice = [];
        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $materialAmount[] = $request->materialAmount[$i];
                $materialPrice[] = $request->materialPrice[$i];
            }
        }

        $materials = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialPrice'=>$materialPrice];

        try{
            $exchange = P_exchange::find($id);
            $buy = Buy::find($exchange->buy_id);

            $exchange->materials = serialize($materials);
            $exchange->exchangeDate = $request->exchangeDate;
            if($request->realExchangeDate){
                $exchange->realExchangeDate = $request->realExchangeDate;
            }
            $exchange->status = $request->status;
            $exchange->memo = $request->memo;
            $exchange->updated_user = session('admin_user')->id;
            $exchange->save();

            $buy->status_exchange = $request->status;
            $buy->save();
            return redirect()->route('p_exchange.index')->with('message', '修改成功');
        } catch(Exception $e) {
            return redirect()->route('p_exchange.index')->with('error', '修改失敗');
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
