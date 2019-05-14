<?php

namespace App\Http\Controllers\Shopping;

use App\Model\Sale;
use App\Model\User;
use App\Model\Gallery;
use App\Model\Material;
use App\Model\Sales_return;
use Illuminate\Http\Request;
use App\Model\Apply_out_stock;
use App\Http\Controllers\Controller;

class Sales_returnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $sales = Sales_return::where('delete_flag','0')->orderBy('updated_at','DESC')->get();
        return view('shopping.sales_return.show',compact('sales','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $sales = Sales_return::where('delete_flag','0')->where('lot_number','like','%'.$request->search_lot_number.'%')->orderBy('sale_no','DESC')->get();
            } else {
                $sales = Sales_return::where('delete_flag','0')->where('status',$search_code)->where('lot_number','like','%'.$request->search_lot_number.'%')->orderBy('sale_no','DESC')->get();
            }
        } else {
            if($search_code == 'all'){
                $sales = Sales_return::where('delete_flag','0')->orderBy('sale_no','DESC')->get();
            } else {
                $sales = Sales_return::where('delete_flag','0')->where('status',$search_code)->orderBy('sale_no','DESC')->get();
            }
        }
        return view('shopping.sales_return.show',compact('sales','search_code'));
    }

    public function search_return(Request $request)
    {
        $search_code = 'all';
        $sales = Sales_return::where('delete_flag','0')->where('sale_id',$request->sale_id)->get();
    
        return view('shopping.sales_return.show',compact('sales','search_code'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(substr($request->sale_no,'0',1) != "S"){
            if(substr($request->sale_no,'0',1) != "s"){
                return redirect()->back()->with('error','銷售單號必須為 S 開頭');     
            }       
        }
        if(strlen($request->sale_no) != 12){
            return redirect()->back()->with('error','銷售單號長度為12個字');            
        }
        $sale_no = substr($request->sale_no,'1');

        $sale = Sale::where('delete_flag','0')->where('sale_no',$sale_no)->first();
        if($sale){
            if($sale->status_return > 0){
                return redirect()->back()->with('error','此單號已建立退貨');   
            }
        } else {
            return redirect()->back()->with('error','查無此單號');   
        }
        $sale_id = $sale->id;

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
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
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


        // $sale->return_created_at = Now();
        // $sale->return_updated_at = Now();
        // $sale->return_created_user = session('admin_user')->id;
        // $sale->return_delete_flag = 0;
        // $sale->save();

        // return view('shopping.sales_return.create',compact('sale','materials','data','materialCount'));    
        return view('shopping.sales_return.create', compact('sale','materials','data','materialCount','upload_check_1','upload_check_2','upload_check_3','sale_id'));
           
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
            'returnDate' => 'date_format:"Y-m-d"|required'
        ];
        $messages = [
    
            'returnDate.required' => '退貨日期 必填',
            'returnDate.date_format' => '退貨日期格式錯誤'
        ];
        $this->validate($request, $rules, $messages);

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

        $sale = Sale::find($request->sale_id);

        try{
            $return = new Sales_return;
            $return->lot_number = $sale->lot_number;
            $return->sale_id = $sale->id;
            $return->sale_no = $sale->sale_no;
            $return->customer = $sale->customer;
            $return->materials = $sale->materials;
            $return->returnDate = $request->returnDate;
            if($request->realReturnDate){
                $return->realReturnDate = $request->realReturnDate;
            }
            $return->memo = $request->memo;
            $return->file_1 = $file_1;
            $return->file_2 = $file_2;
            $return->file_3 = $file_3;
            $return->status = $request->status;
            $return->created_user = session('admin_user')->id;
            $return->delete_flag = 0;
            $return->save();

            $sale->status_return = $request->status;
            $sale->save();

            return redirect()->route('s_sales_return.index')->with('message', '新增成功');
        } catch(Exception $e) {
            return redirect()->route('s_sales_return.index')->with('error', '新增失敗');
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
        $sale = Sales_return::find($id);
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
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default" style="width: 100%; margin-right: 10px; overflow: hidden;color:black;font-weight: bold;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
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
                    <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }

        if($sale->updated_user > 0){
            $updated_user = User::where('id',$sale->updated_user)->first();
        } else {
            $updated_user = User::where('id',$sale->created_user)->first();
        }


        return view('shopping.sales_return.show_one', compact('sale','materials','data','updated_user'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sale = Sales_return::find($id);
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
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
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

        if($sale->updated_user > 0){
            $updated_user = User::where('id',$sale->updated_user)->first();
        } else {
            $updated_user = User::where('id',$sale->created_user)->first();
        }

        return view('shopping.sales_return.edit', compact('sale','materials','data','materialCount','upload_check_1','upload_check_2','upload_check_3','updated_user'));

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
            if($request->realReturnDate == ''){
                return redirect()->back()->with('error', '退貨完成日 必填');            
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
        
        try{
            $return = Sales_return::find($id);                        
            $return->returnDate = $request->returnDate;
            $return->realReturnDate = $request->realReturnDate;
            $return->memo = $request->memo;
            if($check_1){
                $return->file_1 = $file_1;
            }
            if($check_2){
                $return->file_2 = $file_2;
            }
            if($check_3){
                $return->file_3 = $file_3;
            }   
            $return->status = $request->status;
            $return->updated_user = session('admin_user')->id;
            $return->save();

            $sale = Sale::find($return->sale_id);
            $sale->status_return = $request->status;
            $sale->save();

            return redirect()->route('s_sales_return.index')->with('message', '修改成功');
        } catch(Exception $e) {
            return redirect()->route('s_sales_return.index')->with('error', '修改失敗');
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
            $sale = Sales_return::find($id);
            $sale->delete_flag = 1;
            $sale->deleted_at = Now();
            $sale->deleted_user = session('admin_user')->id;
            $sale->save();
            return redirect()->route('s_sales_return.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('s_sales_return.index')->with('error','刪除失敗');            
        }
    }

    public function delete_file($file_no,$sale,$file_id)
    {
        try{
            $sale = Sales_return::find($sale);
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

            return redirect()->route('s_sales_return.edit',$sale->id)->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('s_sales_return.edit',$sale->id)->with('error','刪除失敗');            
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
        // material = 2 , warehouse = 3 ,material_module = 4, apply_out_stock = 5, sale = 6 , s_sales_return = 21 , s_exchange = 22
        $img->category = 21;
        $img->created_user = session('admin_user')->id;
        $img->delete_flag = 0;
        $img->save();
        return $img->id;
    }
}
