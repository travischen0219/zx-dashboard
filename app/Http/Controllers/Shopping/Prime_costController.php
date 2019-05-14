<?php

namespace App\Http\Controllers\Shopping;

use App\Model\Sale;
use App\Model\User;
use App\Model\Gallery;
use App\Model\Material;
use Illuminate\Http\Request;
use App\Model\Apply_out_stock;
use App\Http\Controllers\Controller;


class Prime_costController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $sales = Sale::where('delete_flag','0')->where('status','2')->orderBy('sale_no','DESC')->get();
        
        return view('shopping.prime_cost.show',compact('sales','search_code'));
        
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
        $sale = Sale::find($id);
        
        $materials_profit = unserialize($sale->materials_profit);
    
        $total_materials = count($materials_profit['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){
        
            $material = Material::where('id',$materials_profit['material'][$i])->first();
            
            $style = ' style="display:none"';
            $readonly = ' readonly';
            $disabled = ' disabled';
            
            
            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td>
                    <span style="width: 100%; margin-right: 10px; overflow: hidden;color:black;line-height: 30px;"> '.$material->fullCode.' '.$material->fullName.'</span>
                </td>
                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                </td>
                
                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$materials_profit['materialAmount'][$i].'</span>
                    <input type="hidden" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" value="'.$materials_profit['materialAmount'][$i].'">
                </td>
                
                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.number_format($materials_profit['materialCost'][$i],2,'.','').'</span>
                    <input type="hidden" name="materialCost[]" id="materialCost'.$materialCount.'" class="materialCost" value="'.number_format($materials_profit['materialCost'][$i],2,'.','').'">                    
                </td>
                <td>
                    <span id="materialSubTotal_cost'.$materialCount.'" class="materialSubTotal_cost" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>

                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.number_format($materials_profit['materialPrice'][$i],2,'.','').'</span>
                    <input type="hidden" name="materialPrice[]" id="materialPrice'.$materialCount.'" class="materialPrice" value="'.number_format($materials_profit['materialPrice'][$i],2,'.','').'">
                </td>
                <td>
                    <span id="materialSubTotal_price'.$materialCount.'" class="materialSubTotal_price" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>

                <td>
                    <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }

        $updated_user = User::where('id',$sale->profit_user)->first();

        return view('shopping.prime_cost.show_one', compact('sale','materials','data','updated_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sale = Sale::find($id);
        if($sale->status_profit == null){

            $materials = unserialize($sale->materials);
    
            $total_materials = count($materials['material']);
            $materialCount = 0;
            $data = '';
            for($i = 0; $i < $total_materials; $i++){
            
                $material = Material::where('id',$materials['material'][$i])->first();
                
                $style = ' style="display:none"';
                $readonly = ' readonly';
                $disabled = ' disabled';
                
                
                $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                    <td>
                        <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default" style="width: 100%; margin-right: 10px; overflow: hidden;color:black;font-weight: bold;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                        <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                    </td>
                    <td>
                        <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                        <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'">
                    </td>
                    
                    <td>
                        <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                    </td>
                    
                    <td>
                        <input type="text" name="materialCost[]" id="materialCost'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialCost" style="width: 100px;height: 30px; vertical-align: middle;background-color:lightblue;border-color:blue;border-width:1px;" value="'.number_format($material->cost,2,'.','').'">
                    </td>
                    <td>
                        <span id="materialSubTotal_cost'.$materialCount.'" class="materialSubTotal_cost" style="line-height: 30px; vertical-align: middle;">0</span>
                    </td>
    
                    <td>
                        <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" style="width: 100px;height: 30px; vertical-align: middle;" value="'.number_format($materials['materialPrice'][$i],2,'.','').'" '.$readonly.'>
                    </td>
                    <td>
                        <span id="materialSubTotal_price'.$materialCount.'" class="materialSubTotal_price" style="line-height: 30px; vertical-align: middle;">0</span>
                    </td>
    
                    <td>
                        <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                    </td>
                </tr>';
                $materialCount++;
            }
        } else {
            $materials_profit = unserialize($sale->materials_profit);
    
            $total_materials = count($materials_profit['material']);
            $materialCount = 0;
            $data = '';
            for($i = 0; $i < $total_materials; $i++){
            
                $material = Material::where('id',$materials_profit['material'][$i])->first();
                
                $style = ' style="display:none"';
                $readonly = ' readonly';
                $disabled = ' disabled';
                
                
                $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                    <td>
                        <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default" style="width: 100%; margin-right: 10px; overflow: hidden;color:black;font-weight: bold;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                        <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials_profit['material'][$i].'">
                    </td>
                    <td>
                        <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                        <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'">
                    </td>
                    
                    <td>
                        <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials_profit['materialAmount'][$i].'" '.$readonly.'>
                    </td>
                    
                    <td>
                        <input type="text" name="materialCost[]" id="materialCost'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialCost" style="width: 100px;height: 30px; vertical-align: middle;background-color:lightblue;border-color:blue;border-width:1px;" value="'.number_format($materials_profit['materialCost'][$i],2,'.','').'">
                    </td>
                    <td>
                        <span id="materialSubTotal_cost'.$materialCount.'" class="materialSubTotal_cost" style="line-height: 30px; vertical-align: middle;">0</span>
                    </td>
    
                    <td>
                        <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" style="width: 100px;height: 30px; vertical-align: middle;" value="'.number_format($materials_profit['materialPrice'][$i],2,'.','').'" '.$readonly.'>
                    </td>
                    <td>
                        <span id="materialSubTotal_price'.$materialCount.'" class="materialSubTotal_price" style="line-height: 30px; vertical-align: middle;">0</span>
                    </td>
    
                    <td>
                        <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                    </td>
                </tr>';
                $materialCount++;
            }
        }

        if($sale->updated_user > 0){
            $updated_user = User::where('id',$sale->updated_user)->first();
        } else {
            $updated_user = User::where('id',$sale->created_user)->first();
        }


        return view('shopping.prime_cost.edit', compact('sale','materials','data','updated_user'));
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
            $total_materials = count($request->material);
            $material = [];
            $materialAmount = [];
            $materialCost = [];
            $materialPrice = [];
            for($i=0; $i < $total_materials; $i++){
                if($request->material[$i]){
                    $material[] = $request->material[$i];
                    $materialAmount[] = $request->materialAmount[$i];
                    $materialCost[] = $request->materialCost[$i];
                    $materialPrice[] = $request->materialPrice[$i];
                }
            }
            $materials_profit = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialCost'=>$materialCost,'materialPrice'=>$materialPrice];

            $sale = Sale::find($id);
            $sale->materials_profit = serialize($materials_profit);
            $sale->profit = $request->total_profit;
            $sale->status_profit = $request->status;
            $sale->profit_at = Now();
            $sale->profit_user = session('admin_user')->id;
            $sale->save();
            return redirect()->route('prime_cost.index')->with('message', '編輯成功');
        } catch(Exception $e) {
            return redirect()->route('prime_cost.index')->with('error', '存檔失敗');
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
