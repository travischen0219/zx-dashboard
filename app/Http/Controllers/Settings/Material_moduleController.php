<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Gallery;
use App\Model\Setting;
use App\Model\Material;
use App\Model\Material_unit;
use Illuminate\Http\Request;
use App\Model\Material_module;
use App\Http\Controllers\Controller;

class Material_moduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $search_code = 'all';
        //$inquiries = Inquiry::where('delete_flag','0')->get();
        //return view('purchase.inquiry.show',compact('inquiries','search_code'));

        $modules = Material_module::where('delete_flag','0')->get();
        return view('settings.material_module.show',compact('modules'));
    }

    public function search(Request $request)
    {
        // $search_code = $request->search_category;
        if($request->search_code){
            $modules = Material_module::where('delete_flag','0')->where('code','like','%'.$request->search_code.'%')->get();
        } else {
            $modules = Material_module::where('delete_flag','0')->get();
        }
        return view('settings.material_module.show',compact('modules'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['units'] = json_encode(Material_unit::allWithKey(), JSON_HEX_QUOT | JSON_HEX_TAG);
        return view('settings.material_module.create', $data);
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
            'name' => 'required'
        ];
        $messages = [
            'name.required' => '名稱 必填'
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

        // 另存一份 friendly data
        $materials2 = [];
        for($i = 0; $i < $total_materials; $i++) {
            $materials2[] = [
                'id' => $request->material[$i],
                'amount' => $request->materialAmount[$i],
                'cost' => $request->materialCost[$i],
                'price' => $request->materialPrice[$i]
            ];
        }

        if(count($material) > 0){
            $materials = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialUnit'=>$materialUnit,'materialPrice'=>$materialPrice];

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
                $latest_code = Setting::where('set_key','material_module_code')->first();
                $number = (int)$latest_code->set_value + 1;
                $code_str = "M".str_pad($number, 6, '0',STR_PAD_LEFT);

                $material_module = new Material_module;
                $material_module->code = $code_str;
                $material_module->name = $request->name;
                $material_module->materials = serialize($materials);
                $material_module->materials2 = serialize($materials2);
                $material_module->total_cost = $request->total_cost;
                $material_module->total_price = $request->total_price;
                $material_module->memo = $request->memo;
                $material_module->file_1 = $file_1;
                $material_module->file_2 = $file_2;
                $material_module->file_3 = $file_3;
                $material_module->status = 1;
                $material_module->created_user = session('admin_user')->id;
                $material_module->delete_flag = 0;
                $material_module->save();

                $latest_code->set_value = $number;
                $latest_code->save();
                return redirect()->route('material_module.index')->with('message', '新增成功');
            } catch(Exception $e) {
                return redirect()->route('material_module.index')->with('error', '新增失敗');
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
        $material_module = Material_module::find($id);
        $materials = unserialize($material_module->materials);

        $total_materials = count($materials['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){

            $material = Material::where('id',$materials['material'][$i])->first();

            $style = ' style="display:none"';
            $disabled = ' disabled';

            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;color:black;font-weight: bold;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$disabled.'>
                </td>
                <td>
                    <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                    <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'">
                </td>
                <td>
                    <input type="text" name="materialCost[]" id="materialCost'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialCost" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$material->cost.'" '.$disabled.'>
                </td>
                <td>
                    <span id="materialSubTotal_cost'.$materialCount.'" class="materialSubTotal_cost" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>

                <td>
                    <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$material->price.'" '.$disabled.'>
                </td>
                <td>
                    <span id="materialSubTotal_price'.$materialCount.'" class="materialSubTotal_price" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }

        if($material_module->updated_user > 0){
            $updated_user = User::where('id',$material_module->updated_user)->first();
        } else {
            $updated_user = User::where('id',$material_module->created_user)->first();
        }
        return view('settings.material_module.show_one', compact('material_module','materials','data','materialCount','updated_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $material_module = Material_module::find($id);

        $materials = unserialize($material_module->materials);

        // New: materialRows
        $materials2 = Material_module::encodeMaterials2($material_module->materials2);

        $total_materials = count($materials['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){

            $material = Material::where('id',$materials['material'][$i])->first();
            $style = '';
            $disabled = '';
            if($material_module->status != 1){
                $style = ' style="display:none"';
                $disabled = ' disabled';
            }
            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$disabled.'>
                </td>
                <td>
                    <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                    <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'">
                </td>

                <td>
                    <input type="text" name="materialCost[]" id="materialCost'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialCost" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$material->cost.'" '.$disabled.'>
                </td>
                <td>
                    <span id="materialSubTotal_cost'.$materialCount.'" class="materialSubTotal_cost" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>

                <td>
                    <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$material->price.'" '.$disabled.'>
                </td>
                <td>
                    <span id="materialSubTotal_price'.$materialCount.'" class="materialSubTotal_price" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>

            </tr>';
            $materialCount++;
        }

        if($material_module->updated_user > 0){
            $updated_user = User::where('id',$material_module->updated_user)->first();
        } else {
            $updated_user = User::where('id',$material_module->created_user)->first();
        }

        $upload_check_1 = true;
        $upload_check_2 = true;
        $upload_check_3 = true;

        if($material_module->file_1 > 0){
            $upload_check_1 = false;
        }
        if($material_module->file_2 > 0){
            $upload_check_2 = false;
        }
        if($material_module->file_3 > 0){
            $upload_check_3 = false;
        }

        $units = json_encode(Material_unit::allWithKey(), JSON_HEX_QUOT | JSON_HEX_TAG);

        return view('settings.material_module.edit', compact('material_module','materials','materials2','data','materialCount','updated_user','upload_check_1','upload_check_2','upload_check_3','units'));
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
            'name' => 'required'
        ];
        $messages = [
            'name.required' => '名稱 必填'
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

        // 另存一份 friendly data
        $materials2 = [];
        for($i = 0; $i < $total_materials; $i++) {
            $materials2[] = [
                'id' => $request->material[$i],
                'amount' => $request->materialAmount[$i],
                'cost' => $request->materialCost[$i],
                'price' => $request->materialPrice[$i]
            ];
        }

        if(count($material) > 0){
            $materials = ['material'=>$material, 'materialAmount'=>$materialAmount,'materialUnit'=>$materialUnit,'materialPrice'=>$materialPrice];

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
                $material_module = Material_module::find($id);
                $material_module->name = $request->name;
                $material_module->materials = serialize($materials);
                $material_module->materials2 = serialize($materials2);
                $material_module->total_cost = $request->total_cost;
                $material_module->total_price = $request->total_price;
                $material_module->memo = $request->memo;
                if($check_1){
                    $material_module->file_1 = $file_1;
                }
                if($check_2){
                    $material_module->file_2 = $file_2;
                }
                if($check_3){
                    $material_module->file_3 = $file_3;
                }
                $material_module->status = 1;
                $material_module->updated_user = session('admin_user')->id;
                $material_module->save();

                return redirect()->route('material_module.index')->with('message', '修改成功');

            } catch(Exception $e) {
                return redirect()->route('material_module.index')->with('error', '修改失敗');
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
            $material_module = Material_module::find($id);

            if($material_module->file_1 > 0){
                $gallery = Gallery::find($material_module->file_1);
                $gallery->delete_flag = 1;
                $gallery->deleted_at = Now();
                $gallery->deleted_user = session('admin_user')->id;
                $gallery->save();
            }
            if($material_module->file_2 > 0){
                $gallery = Gallery::find($material_module->file_2);
                $gallery->delete_flag = 1;
                $gallery->deleted_at = Now();
                $gallery->deleted_user = session('admin_user')->id;
                $gallery->save();
            }
            if($material_module->file_3 > 0){
                $gallery = Gallery::find($material_module->file_3);
                $gallery->delete_flag = 1;
                $gallery->deleted_at = Now();
                $gallery->deleted_user = session('admin_user')->id;
                $gallery->save();
            }

            $material_module->delete_flag = 1;
            $material_module->deleted_at = Now();
            $material_module->deleted_user = session('admin_user')->id;
            $material_module->save();
            return redirect()->route('material_module.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('material_module.index')->with('error','刪除失敗');
        }
    }

    public function delete_file($file_no,$material_module,$file_id)
    {
        try{
            $material_module = Material_module::find($material_module);
            if($file_no == 1){
                $material_module->file_1 = null;
            } else if($file_no == 2){
                $material_module->file_2 = null;
            } else if($file_no == 3){
                $material_module->file_3 = null;
            }
            $material_module->save();

            $gallery = Gallery::find($file_id);
            $gallery->delete_flag = 1;
            $gallery->deleted_at = Now();
            $gallery->deleted_user = session('admin_user')->id;
            $gallery->save();

            return redirect()->route('material_module.edit',$material_module->id)->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('material_module.edit',$material_module->id)->with('error','刪除失敗');
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
        $img->category = 4;
        $img->created_user = session('admin_user')->id;
        $img->delete_flag = 0;
        $img->save();
        return $img->id;
    }
}
