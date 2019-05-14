<?php

namespace App\Http\Controllers\Stock;

use App\Model\Buy;
use App\Model\User;
use App\Model\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class On_order_followController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $search_code = 'all';
        $buys = Buy::where('delete_flag','0')->where('status','2')->get();
        return view('stock.on_order_follow.show',compact('buys'));
    }

    public function search(Request $request)
    {
        // $search_code = $request->search_category;
        // if($request->search_lot_number){
        //     if($search_code == 'all'){
        //         $buys = Buy::where('delete_flag','0')->where('status','2')->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
        //     } else {
        //         $buys = Buy::where('delete_flag','0')->where('status','2')->where('status',$search_code)->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
        //     }
        // } else {
        //     if($search_code == 'all'){
        //         $buys = Buy::where('delete_flag','0')->where('status','2')->get();
        //     } else {
        //         $buys = Buy::where('delete_flag','0')->where('status','2')->where('status',$search_code)->get();
        //     }
        // }
        $buys = Buy::where('delete_flag','0')->where('status','2')->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
        
        return view('stock.on_order_follow.show',compact('buys'));
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
        $buy = Buy::find($id);
        $materials = unserialize($buy->materials);

        $total_materials = count($materials['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){
        
            $material = Material::where('id',$materials['material'][$i])->first();
            $style = '';
            $readonly = '';
            $disabled = '';
            if($buy->status == 2 || $buy->status == 4){
                $style = ' style="display:none"';
                $readonly = ' readonly';
                $disabled = ' disabled';
            }
            if($buy->status == 3){
                $style = ' style="display:none"';
                $disabled = ' disabled';                
            }
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

        if($buy->updated_user > 0){
            $updated_user = User::where('id',$buy->updated_user)->first();
        } else {
            $updated_user = User::where('id',$buy->created_user)->first();
        }
        return view('stock.on_order_follow.show_one', compact('buy','materials','data','materialCount','updated_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
