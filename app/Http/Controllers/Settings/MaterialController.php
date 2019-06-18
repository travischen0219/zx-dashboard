<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Gallery;
use App\Model\Material;
use App\Model\Warehouse;
use App\Model\Material_unit;
use Illuminate\Http\Request;
use App\Model\Material_category;
use App\Model\Material_warehouse;
use App\Model\Warehouse_category;
use App\Http\Controllers\Controller;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        if(Material_unit::where('delete_flag','0')->count()>0){
            if(Material_category::where('delete_flag','0')->count()>0){
                $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
                $materials = Material::where('delete_flag','0')->orderBy('fullCode', 'ASC')->get();
                return view('settings.material.show',compact('material_categories', 'materials','search_code'));
            } else {
                return redirect()->route('material_category.index')->with('error', '尚無 物料分類 資料，請先建立');
            }
        } else {
            return redirect()->route('material_unit.index')->with('error', '尚無 單位 資料，請先建立');
        }
    }

    public function search(Request $request)
    {
        $material_categories = Material_category::orderBy('orderby', 'ASC')->get();

        $search_code = $request->search_category;
        if ($search_code == 'all') {
            $materials = Material::where('delete_flag', '0')
                ->orderBy('fullCode', 'ASC')->get();
        } else {
            $materials = Material::where('delete_flag', '0')
                ->where('material_categories_code', $search_code)
                ->orderBy('fullCode', 'ASC')->get();
        }

        return view('settings.material.show', compact('material_categories', 'materials', 'search_code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
        $material_units = Material_unit::orderBy('orderby', 'ASC')->get();
        return view('settings.material.create',compact('material_categories','material_units'));
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
            'fullName' => 'required|string',
            'fullCode' => 'required|string|unique:materials',
        ];

        $messages = [
            'fullName.required' => '品名 必填',
            'fullCode.required' => '物料編號 不完整',
            'fullCode.unique' => '物料編號已存在，不可重複',
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

        try{
            $material = new Material;
            $material->fullName = $request->fullName;
            $material->material_categories_code = $request->material_category;
            $material->fullcode = $request->fullCode;
            $material->code1 = $request->code_1;
            $material->code2 = $request->code_2;
            $material->code3 = $request->code_3;
            $material->unit = $request->unit;
            $material->cost = $request->cost;
            $material->price = $request->price;
            $material->cal_unit = $request->cal_unit;
            $material->cal_price = $request->cal_price;
            $material->size = $request->size;
            $material->color = $request->color;
            $material->buy = $request->buy;
            $material->safe = $request->safe;

            if($request->warehouse_id > 0){
                $material->warehouse = $request->warehouse_id;
                $find_warehouse = Warehouse::find($request->warehouse_id);
                $material->warehouse_category = $find_warehouse->category;
            }

            $material->memo = $request->memo;
            $material->file_1 = $file_1;
            $material->file_2 = $file_2;
            $material->file_3 = $file_3;
            $material->stock = 0;
            $material->stock_no = 0;
            $material->status = $request->status;
            $material->created_user = session('admin_user')->id;
            $material->delete_flag = 0;
            $material->save();

            if($request->warehouse_id > 0){

                $warehouse = new Material_warehouse;
                $warehouse->material_id = $material->id;
                $warehouse->warehouse_id = $request->warehouse_id;
                $warehouse->warehouse_category_id = $material->warehouse_category;
                $warehouse->stock = 0;
                $warehouse->created_user = session('admin_user')->id;
                $warehouse->delete_flag = 0;
                $warehouse->save();
            }


            return redirect()->route('materials.index')->with('message','新增成功');
        } catch (Exception $e) {
            return redirect()->route('materials.index')->with('error','新增失敗');
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
        $material = Material::find($id);
        $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
        $material_units = Material_unit::orderBy('orderby', 'ASC')->get();
        if($material->warehouse > 0){
            $warehouse = Warehouse::where('id',$material->warehouse)->first();
        } else {
            $warehouse = '';
        }
        if($material->updated_user > 0){
            $updated_user = User::where('id',$material->updated_user)->first();
        } else {
            $updated_user = User::where('id',$material->created_user)->first();
        }
        return view('settings.material.show_one',compact('material', 'material_categories','material_units', 'updated_user', 'warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $material = Material::find($id);
        $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
        $material_units = Material_unit::orderBy('orderby', 'ASC')->get();
        if($material->warehouse > 0){
            $warehouse = Warehouse::where('id',$material->warehouse)->first();
        } else {
            $warehouse = '';
        }
        if($material->updated_user > 0){
            $updated_user = User::where('id',$material->updated_user)->first();
        } else {
            $updated_user = User::where('id',$material->created_user)->first();
        }

        $upload_check_1 = true;
        $upload_check_2 = true;
        $upload_check_3 = true;

        if($material->file_1 > 0){
            $upload_check_1 = false;
        }
        if($material->file_2 > 0){
            $upload_check_2 = false;
        }
        if($material->file_3 > 0){
            $upload_check_3 = false;
        }

        return view('settings.material.edit',compact('material', 'material_categories','material_units', 'updated_user', 'warehouse','upload_check_1','upload_check_2','upload_check_3'));
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
        //$material = Material::find($id);
        // if($material->fullName == $request->fullName){
        //     $rules = ['fullName' => 'required|unique:materials'.($id ? ",id,$id" : '')];
        // } else {
        //     if($check_id = Material::where('fullName',$request->fullName)->first()){
        //         if($check_id->id != $id){
        //             return redirect()->back()->with('error','品名已存在 不可重複');
        //             die;
        //         }
        //     }
        // }

        $rules = [
            'fullName' => 'required|string',
        ];

        $messages = [

            'fullName.required' => '品名 必填',

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
            $material = Material::find($id);
            $material->fullName = $request->fullName;
            $material->material_categories_code = $request->material_category;
            $material->unit = $request->unit;
            $material->cost = $request->cost;
            $material->price = $request->price;
            $material->cal_unit = $request->cal_unit;
            $material->cal_price = $request->cal_price;
            $material->size = $request->size;
            $material->color = $request->color;
            $material->buy = $request->buy;
            $material->safe = $request->safe;

            if($request->warehouse_id > 0 ){
                if($material->warehouse != $request->warehouse_id){
                    $material->warehouse = $request->warehouse_id;
                    $this_warehouse = Warehouse::find($request->warehouse_id);
                    $material->warehouse_category = $this_warehouse->category;

                    $material_warehouses = Material_warehouse::where('delete_flag','0')->where('material_id',$id)->get();

                    $check_has_warehouse = 0;
                    if($material_warehouses->count() > 0){
                        // 有倉儲
                        // 判斷是否建立過倉儲
                        foreach($material_warehouses as $material_warehouse){
                            if($request->warehouse_id == $material_warehouse->warehouse_id){
                                $check_has_warehouse++;
                            }
                        }
                        if($check_has_warehouse == 0){
                            // 新的倉儲位置
                            $material_warehouse_add = new Material_warehouse;
                            $material_warehouse_add->material_id = $id;
                            $material_warehouse_add->warehouse_id = $request->warehouse_id;
                            $material_warehouse_add->warehouse_category_id = $this_warehouse->category;
                            $material_warehouse_add->stock = 0;
                            $material_warehouse_add->created_user = session('admin_user')->id;
                            $material_warehouse_add->delete_flag = 0;
                            $material_warehouse_add->save();
                        }
                    } else {
                        // 若無預設倉儲
                        $material_warehouse_add = new Material_warehouse;
                        $material_warehouse_add->material_id = $id;
                        $material_warehouse_add->warehouse_id = $request->warehouse_id;
                        $material_warehouse_add->warehouse_category_id = $this_warehouse->category;
                        $material_warehouse_add->stock = 0;
                        $material_warehouse_add->created_user = session('admin_user')->id;
                        $material_warehouse_add->delete_flag = 0;
                        $material_warehouse_add->save();
                    }
                }
            }

            $material->memo = $request->memo;
            if($check_1){
                $material->file_1 = $file_1;
            }
            if($check_2){
                $material->file_2 = $file_2;
            }
            if($check_3){
                $material->file_3 = $file_3;
            }
            $material->safe = $request->safe;
            $material->status = $request->status;
            $material->updated_user = session('admin_user')->id;
            $material->save();
            return redirect()->route('materials.index')->with('message','修改成功');
        } catch (Exception $e) {
            return redirect()->route('materials.index')->with('error','修改失敗');
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
            $material = Material::find($id);

            if($material->file_1 > 0){
                $gallery = Gallery::find($material->file_1);
                $gallery->delete_flag = 1;
                $gallery->deleted_at = Now();
                $gallery->deleted_user = session('admin_user')->id;
                $gallery->save();
            }
            if($material->file_2 > 0){
                $gallery = Gallery::find($material->file_2);
                $gallery->delete_flag = 1;
                $gallery->deleted_at = Now();
                $gallery->deleted_user = session('admin_user')->id;
                $gallery->save();
            }
            if($material->file_3 > 0){
                $gallery = Gallery::find($material->file_3);
                $gallery->delete_flag = 1;
                $gallery->deleted_at = Now();
                $gallery->deleted_user = session('admin_user')->id;
                $gallery->save();
            }

            $material->status = 2;
            $material->delete_flag = 1;
            $material->deleted_at = Now();
            $material->deleted_user = session('admin_user')->id;
            $material->save();
            return redirect()->route('materials.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('materials.index')->with('error','刪除失敗');
        }
    }

    public function delete_file($file_no,$material,$file_id)
    {
        try{
            $material = Material::find($material);
            if($file_no == 1){
                $material->file_1 = null;
            } else if($file_no == 2){
                $material->file_2 = null;
            } else if($file_no == 3){
                $material->file_3 = null;
            }
            $material->save();

            $gallery = Gallery::find($file_id);
            $gallery->delete_flag = 1;
            $gallery->deleted_at = Now();
            $gallery->deleted_user = session('admin_user')->id;
            $gallery->save();

            return redirect()->route('materials.edit',$material->id)->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('materials.edit',$material->id)->with('error','刪除失敗');
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
        $img->category = 2;
        $img->created_user = session('admin_user')->id;
        $img->delete_flag = 0;
        $img->save();
        return $img->id;
    }
}

