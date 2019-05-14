<?php

namespace App\Http\Controllers\Shopping;

use App\Model\Sale;
use App\Model\User;
use App\Model\Gallery;
use App\Model\Material;
use Illuminate\Http\Request;
use App\Model\Apply_out_stock;
use App\Model\Material_module;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $sales = Sale::where('delete_flag','0')->orderBy('sale_no','DESC')->get();
        return view('shopping.sale.show',compact('sales','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $sales = Sale::where('delete_flag','0')->where('lot_number','like','%'.$request->search_lot_number.'%')->orderBy('sale_no','DESC')->get();
            } else {
                $sales = Sale::where('delete_flag','0')->where('status',$search_code)->where('lot_number','like','%'.$request->search_lot_number.'%')->orderBy('sale_no','DESC')->get();
            }
        } else {
            if($search_code == 'all'){
                $sales = Sale::where('delete_flag','0')->orderBy('sale_no','DESC')->get();
            } else {
                $sales = Sale::where('delete_flag','0')->where('status',$search_code)->orderBy('sale_no','DESC')->get();
            }
        }
        return view('shopping.sale.show',compact('sales','search_code'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sale_no = date("Ymd")."001";
        $last_sale_no = Sale::orderBy('sale_no','DESC')->first();
        if($last_sale_no){
            if($last_sale_no->sale_no >= $sale_no){
                $sale_no = $last_sale_no->sale_no + 1;
            }
        }
        return view('shopping.sale.create',compact('sale_no'));        
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
            'createDate' => 'date_format:"Y-m-d"|required',
            'expireDate' => 'date_format:"Y-m-d"|required',
        ];
        $messages = [
            'lot_number.required' => '批號 必填',          
            'customer.required' => '尚未選擇 客戶',
            'createDate.required' => '新增日期 必填',
            'expireDate.required' => '有效期限 必填',
            'createDate.date_format' => '新增日期格式錯誤',
            'expireDate.date_format' => '有效期限格式錯誤',
        ];
        $this->validate($request, $rules, $messages);

        $total_materials = count($request->material);
        $material = [];
        $type = [];
        $materialAmount = [];
        $materialPrice = [];
        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $type[] = $request->materialType[$i];
                $materialAmount[] = $request->materialAmount[$i];
                $materialPrice[] = $request->materialPrice[$i];
            }
        }

        if(count($material) > 0){
            $materials = ['material'=>$material, 'type'=>$type, 'materialAmount'=>$materialAmount, 'materialPrice'=>$materialPrice];

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

            try{
                $sale = new Sale;
                $sale->lot_number = $request->lot_number;
                $sale->sale_no = $request->sale_no;
                $sale->customer = $request->customer;
                $sale->materials = serialize($materials);
                $sale->createDate = $request->createDate;
                $sale->expireDate = $request->expireDate;
                $sale->memo = $request->memo;
                $sale->file_1 = $file_1;
                $sale->file_2 = $file_2;
                $sale->file_3 = $file_3;
                $sale->status = $request->status;
                $sale->created_user = session('admin_user')->id;
                $sale->delete_flag = 0;
                $sale->save();
                return redirect()->route('sale.index')->with('message', '新增成功');
            } catch(Exception $e) {
                return redirect()->route('sale.index')->with('error', '新增失敗');
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
        $sale = Sale::find($id);
        $materials = unserialize($sale->materials);

        $total_materials = count($materials['material']);
        $materialCount = 0;
        $data = '';
        $pic_data = '';
        
        $style = ' style="display:none"';
        $readonly = ' readonly';
        $disabled = ' disabled';

        for($i = 0; $i < $total_materials; $i++){
        
            if($materials['type'][$i] == 1){
                $material = Material::where('id',$materials['material'][$i])->first();
                $material_name = $material->fullCode.' '.$material->fullName;

                $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">

                    <td></td>
                    <td>
                        <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material_name.'</button>
                        <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                    </td>
    
                    <td>
                        <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                    </td>
                
                    <td>
                        <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                    </td>
                    <td>
                        <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$materials['materialPrice'][$i].'" '.$readonly.'>
                    </td>
                    <td>
                        <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                        <input type="hidden" name="materialPriceSubTotal[]" id="materialPriceSubTotal'.$materialCount.'" class="materialPriceSubTotal">
                    </td>
                </tr>';
            } else {
                $material = Material_module::where('id',$materials['material'][$i])->first();     
                $material_name = $material->code.' '.$material->name;  
                
                $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">

                    <td></td>
                    <td>
                        <button type="button" id="moduleName'.$materialCount.'" name="moduleName'.$materialCount.'" class="btn btn-default get_module_name" style="width: 100%; margin-right: 10px; overflow: hidden; color:blue;" '.$disabled.'> '.$material_name.'</button>
                        <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_module" value="'.$materials['material'][$i].'">
                    </td>
    
                    <td>
                        <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                    </td>
                
                    <td>
                        <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">組</span>
                    </td>
                    <td>
                        <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$materials['materialPrice'][$i].'" '.$readonly.'>
                    </td>
                    <td>
                        <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                        <input type="hidden" name="materialPriceSubTotal[]" id="materialPriceSubTotal'.$materialCount.'" class="materialPriceSubTotal">
                    </td>
                </tr>';

                $pre_show = '';
                $src_upload = '';
                if($material->file_1 > 0){
                    if($material->image_1->thumb_name == "file_image.jpg"){
                        $src = asset('assets/apps/img/'.$material->image_1->thumb_name);
                    }else{
                        $src = asset('upload/'.$material->image_1->thumb_name);
                        $src_upload = "'".asset('upload/'.$material->image_1->file_name)."'";  
                        $pre_show = '<a href="javascript:show_image('.$src_upload.');" class="btn btn-primary btn-sm" role="button">預覽</a>';
                    }
                }else{
                    $src = asset('assets/apps/img/no_image.png');
                }
                
                $pic_data .= '<div class="col-md-3" id="picRow'.$materialCount.'" class="picRow">
                    <div class="thumbnail" style="width:180px;">
                        <img src="'.$src.'" alt="'.$material->image_1->name.'">
                        <div class="caption">
                            <h4 class="image_name">'.$material->image_1->name.'</h4>
                            <p style="margin-top:6px;">
                                '.$pre_show.'
                                <a href="'.url('settings/file_download',$material->image_1->id).'" class="btn btn-default btn-sm" role="button" download>下載</a>
                            </p>
                        </div>
                    </div>
                </div>';
            }
        
            
            
            
            $materialCount++;
        }

        if($sale->updated_user > 0){
            $updated_user = User::where('id',$sale->updated_user)->first();
        } else {
            $updated_user = User::where('id',$sale->created_user)->first();
        }


        return view('shopping.sale.show_one', compact('sale','pic_data','materials','data','updated_user'));

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
        $materials = unserialize($sale->materials);

        $total_materials = count($materials['material']);
        $materialCount = 0;
        $data = '';
        $pic_data = '';

        $style = '';
        $readonly = '';
        $disabled = '';
        if($sale->status == 3 || $sale->status == 4){
            $style = ' style="display:none"';
            $readonly = ' readonly';
            $disabled = ' disabled';
        }


        for($i = 0; $i < $total_materials; $i++){

            if($materials['type'][$i] == 1){
                $material = Material::where('id',$materials['material'][$i])->first();
                $material_name = $material->fullCode.' '.$material->fullName;

                $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                    <input type="hidden" name="materialType[]" id="materialType'.$materialCount.'" class="materialType" value="'.$materials['type'][$i].'">
                    <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red"><i class="fa fa-remove"></i></a></td>
                    <td>
                        <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material_name.'</button>
                        <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                    </td>
    
                    <td>
                        <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                    </td>
                
                    <td>
                        <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                    </td>
                    <td>
                        <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$materials['materialPrice'][$i].'" '.$readonly.'>
                    </td>
                    <td>
                        <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                        <input type="hidden" name="materialPriceSubTotal[]" id="materialPriceSubTotal'.$materialCount.'" class="materialPriceSubTotal">
                    </td>
                </tr>';
            } else {
                $material = Material_module::where('id',$materials['material'][$i])->first();     
                $material_name = $material->code.' '.$material->name;  
                
                $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                    <input type="hidden" name="materialType[]" id="materialType'.$materialCount.'" class="materialType" value="'.$materials['type'][$i].'">
                    <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red"><i class="fa fa-remove"></i></a></td>
                    <td>
                        <button type="button" id="moduleName'.$materialCount.'" name="moduleName'.$materialCount.'" class="btn btn-default get_module_name" style="width: 100%; margin-right: 10px; overflow: hidden; color:blue;" '.$disabled.'> '.$material_name.'</button>
                        <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_module" value="'.$materials['material'][$i].'">
                    </td>
    
                    <td>
                        <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                    </td>
                
                    <td>
                        <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">組</span>
                    </td>
                    <td>
                        <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$materials['materialPrice'][$i].'" '.$readonly.'>
                    </td>
                    <td>
                        <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                        <input type="hidden" name="materialPriceSubTotal[]" id="materialPriceSubTotal'.$materialCount.'" class="materialPriceSubTotal">
                    </td>
                </tr>';

                $pre_show = '';
                $src_upload = '';
                if($material->file_1 > 0){
                    if($material->image_1->thumb_name == "file_image.jpg"){
                        $src = asset('assets/apps/img/'.$material->image_1->thumb_name);
                    }else{
                        $src = asset('upload/'.$material->image_1->thumb_name);
                        $src_upload = "'".asset('upload/'.$material->image_1->file_name)."'";  
                        $pre_show = '<a href="javascript:show_image('.$src_upload.');" class="btn btn-primary btn-sm" role="button">預覽</a>';
                    }
                }else{
                    $src = asset('assets/apps/img/no_image.png');
                }
                
                $pic_data .= '<div class="col-md-3" id="picRow'.$materialCount.'" class="picRow">
                    <div class="thumbnail" style="width:180px;">
                        <img src="'.$src.'" alt="'.$material->image_1->name.'">
                        <div class="caption">
                            <h4 class="image_name">'.$material->image_1->name.'</h4>
                            <p style="margin-top:6px;">
                                '.$pre_show.'
                                <a href="'.url('settings/file_download',$material->image_1->id).'" class="btn btn-default btn-sm" role="button" download>下載</a>
                            </p>
                        </div>
                    </div>
                </div>';
            }
        
            
            
            
            $materialCount++;
        }

        if($sale->updated_user > 0){
            $updated_user = User::where('id',$sale->updated_user)->first();
        } else {
            $updated_user = User::where('id',$sale->created_user)->first();
        }

        $upload_check_1 = true;
        $upload_check_2 = true;
        $upload_check_3 = true;

        if($sale->file_1 > 0){
            $upload_check_1 = false;
        }
        if($sale->file_2 > 0){
            $upload_check_2 = false;
        }
        if($sale->file_3 > 0){
            $upload_check_3 = false;
        }

        return view('shopping.sale.edit', compact('sale','materials','data','pic_data','materialCount','updated_user','upload_check_1','upload_check_2','upload_check_3'));
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
        if($request->status == 3){
            if($request->receiveDate == ''){
                return redirect()->back()->with('error', '完成日期 必填');            
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
        $type = [];
        $materialAmount = [];
        $materialPrice = [];
        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $type[] = $request->materialType[$i];
                $materialAmount[] = $request->materialAmount[$i];
                $materialPrice[] = $request->materialPrice[$i];
            }
        }

        if(count($material) > 0){
            $materials = ['material'=>$material, 'type'=>$type, 'materialAmount'=>$materialAmount, 'materialPrice'=>$materialPrice];

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
            
            if($request->status == 1 || $request->status == 2 || $request->status == 4){
                try{
                    $sale = Sale::find($id);                        
                    $sale->materials = serialize($materials);
                    $sale->createDate = $request->createDate;
                    $sale->expireDate = $request->expireDate;
                    $sale->receiveDate = $request->receiveDate;
                    $sale->memo = $request->memo;
                    if($check_1){
                        $sale->file_1 = $file_1;
                    }
                    if($check_2){
                        $sale->file_2 = $file_2;
                    }
                    if($check_3){
                        $sale->file_3 = $file_3;
                    }   
                    $sale->status = $request->status;
                    $sale->updated_user = session('admin_user')->id;
                    $sale->save();

                    return redirect()->route('sale.index')->with('message', '修改成功');
                } catch(Exception $e) {
                    return redirect()->route('sale.index')->with('error', '修改失敗');
                }
            } else if($request->status == 3){
                try{
                    $sale = Sale::find($id);                        
                    $sale->materials = serialize($materials);
                    $sale->createDate = $request->createDate;
                    $sale->expireDate = $request->expireDate;
                    $sale->receiveDate = $request->receiveDate;
                    $sale->memo = $request->memo;
                    if($check_1){
                        $sale->file_1 = $file_1;
                    }
                    if($check_2){
                        $sale->file_2 = $file_2;
                    }
                    if($check_3){
                        $sale->file_3 = $file_3;
                    }   
                    $sale->status = $request->status;
                    $sale->updated_user = session('admin_user')->id;
                    $sale->save();


                    $material_apply = [];
                    $materialAmount_apply = [];
                    $materialPrice_apply = [];
                    for($j=0; $j < $total_materials; $j++){

                        if($type[$j] == 1){
                            $material_apply[] = $material[$j];
                            $materialAmount_apply[] = $materialAmount[$j];
                            $materialPrice_apply[] = $materialPrice[$j];

                        } else if($type[$j] == 2){
                            $module_apply = Material_module::find($material[$j]);
                            $module_materials = unserialize($module_apply->materials);

                            $total_module_materials = count($module_materials['material']);

                            for($k = 0 ; $k < $total_module_materials ; $k++){
                                $material_apply[] = $module_materials['material'][$k];
                                $materialAmount_apply[] = $module_materials['materialAmount'][$k] * $materialAmount[$j];
                                $materialPrice_apply[] = $module_materials['materialPrice'][$k];
                            }
                        }

                    }

                    $apply_materials = ['material'=>$material_apply, 'materialAmount'=>$materialAmount_apply, 'materialPrice'=>$materialPrice_apply];

                    $apply_no = date("Ymd")."001";
                    $last_apply_no = Apply_out_stock::orderBy('apply_no','DESC')->first();
                    if($last_apply_no){
                        if($last_apply_no->apply_no >= $apply_no){
                            $apply_no = $last_apply_no->apply_no + 1;
                        }
                    }
                    $apply = new Apply_out_stock;
                    $apply->apply_no = $apply_no;
                    $apply->lot_number = $sale->lot_number;
                    $apply->sale_no = $sale->sale_no;
                    $apply->customer = $sale->customer;
                    $apply->materials = serialize($apply_materials);
                    $apply->applyDate = date("Y-m-d");
                    if($sale->file_1 != null){
                        $apply->file_1 = $sale->file_1;
                    }
                    if($sale->file_2 != null){
                        $apply->file_2 = $sale->file_2;
                    }
                    if($sale->file_3 != null){
                        $apply->file_3 = $sale->file_3;
                    }
                    $apply->status = 1;
                    $apply->created_user = session('admin_user')->id;
                    $apply->delete_flag = 0;
                    $apply->save();

                    return redirect()->route('sale.index')->with('message', '已轉 申請出庫');
                } catch(Exception $e) {
                    return redirect()->route('sale.index')->with('error', '轉 申請出庫 失敗');
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
            $sale = Sale::find($id);
            $sale->delete_flag = 1;
            $sale->deleted_at = Now();
            $sale->deleted_user = session('admin_user')->id;
            $sale->save();
            return redirect()->route('sale.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('sale.index')->with('error','刪除失敗');            
        }
    }

    public function delete_file($file_no,$sale,$file_id)
    {
        try{
            $sale = Sale::find($sale);
            if($file_no == 1){
                $sale->file_1 = null;
            } else if($file_no == 2){
                $sale->file_2 = null;
            } else if($file_no == 3){
                $sale->file_3 = null;
            }
            $sale->save();

            $gallery = Gallery::find($file_id);
            $gallery->delete_flag = 1;
            $gallery->deleted_at = Now();
            $gallery->deleted_user = session('admin_user')->id;
            $gallery->save();

            return redirect()->route('sale.edit',$sale->id)->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('sale.edit',$sale->id)->with('error','刪除失敗');            
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
        // material = 2 , warehouse = 3 ,material_module = 4, apply_out_stock = 5, sale = 6
        $img->category = 6;
        $img->created_user = session('admin_user')->id;
        $img->delete_flag = 0;
        $img->save();
        return $img->id;
    }

    public function addRow(Request $request)
    {
        $materialCount = $request->materialCount;

        $data = '<tr id="materialRow'.$materialCount.'" class="materialRow">
            <input type="hidden" name="materialType[]" id="materialType'.$materialCount.'" class="materialType" value="1">
            <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red"><i class="fa fa-remove"></i></a></td>
            <td>
                <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇物料</button>
                <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material">
            </td>
            
            <td>
                <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;">
            </td>
           
            <td>
                <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>              
            </td>
            <td>
                <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" value="" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;">
            </td>
            <td>
                <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                <input type="hidden" name="materialPriceSubTotal[]" id="materialPriceSubTotal'.$materialCount.'" class="materialPriceSubTotal">
            </td>
        </tr>';

        return $data;
    }


    public function addModule(Request $request)
    {
        $id = $request->id;
        $materialCount = $request->materialCount;
        $module = Material_module::find($id);

        $return = [];
        $data = '';
        $pic_data = '';
        $disabled = '';
        $style = '';
        $readonly = '';
        
        $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
            <input type="hidden" name="materialType[]" id="materialType'.$materialCount.'" class="materialType" value="2">
            
            <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
            <td>
                <button type="button" id="moduleName'.$materialCount.'" name="moduleName'.$materialCount.'" class="btn btn-default get_module_name" style="width: 100%; margin-right: 10px; overflow: hidden;color:blue;" '.$disabled.'> '.$module->code.' '.$module->name.'</button>
                <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_module" value="'.$module->id.'">
            </td>
            
            <td>
                <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" '.$readonly.'>
            </td>
            
            <td>
                <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">組</span>
            </td>
            <td>
                <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$module->total_price.'" '.$readonly.'>
            </td>
            <td>
                <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                <input type="hidden" name="materialPriceSubTotal[]" id="materialPriceSubTotal'.$materialCount.'" class="materialPriceSubTotal">
            </td>
        </tr>';
        
        $pre_show = '';
        $src_upload = '';
        if($module->file_1 > 0){
            if($module->image_1->thumb_name == "file_image.jpg"){
                $src = asset('assets/apps/img/'.$module->image_1->thumb_name);
            }else{
                $src = asset('upload/'.$module->image_1->thumb_name);
                $src_upload = "'".asset('upload/'.$module->image_1->file_name)."'";  
                $pre_show = '<a href="javascript:show_image('.$src_upload.');" class="btn btn-primary btn-sm" role="button">預覽</a>';
            }
        }else{
            $src = asset('assets/apps/img/no_image.png');
        }
        
        $pic_data .= '<div class="col-md-3" id="picRow'.$materialCount.'" class="picRow">
            <div class="thumbnail" style="width:180px;">
                <img src="'.$src.'" alt="'.$module->image_1->name.'">
                <div class="caption">
                    <h4 class="image_name">'.$module->image_1->name.'</h4>
                    <p style="margin-top:6px;">
                        '.$pre_show.'
                        <a href="'.url('settings/file_download',$module->image_1->id).'" class="btn btn-default btn-sm" role="button" download>下載</a>
                    </p>
                </div>
            </div>
        </div>';

        $materialCount++;

        $return['data'] = $data;
        $return['pic_data'] = $pic_data;
        $return['materialCount'] = $materialCount;
        return $return;
    }
}
