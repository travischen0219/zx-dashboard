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
use App\Model\Helper;
use App\Model\Warehouse_category;
use App\Model\StorageFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialRequest;
use Storage;

class MaterialController extends Controller
{

    public function index(Request $request)
    {
        $search_code = $request->search_category ?? 'all';

        if (Material_unit::where('delete_flag', '0')->count() <= 0) {
            return redirect()->route('material_unit.index')->with('error', '尚無 單位 資料，請先建立');
        }

        if (Material_category::where('delete_flag', '0')->count() <= 0) {
            return redirect()->route('material_category.index')->with('error', '尚無 物料分類 資料，請先建立');
        }

        $material_categories = Material_category::orderBy('orderby', 'ASC')->get();

        if ($search_code == 'all') {
            $materials = Material::orderBy('fullCode', 'ASC')->get();
        } else {
            $materials = Material::where('material_categories_code', $search_code)
                ->orderBy('fullCode', 'ASC')->get();
        }

        $data = [];
        $data['material_categories'] = $material_categories;
        $data['materials'] = $materials;
        $data['search_code'] = $search_code;

        return view('settings.material.index', $data);
    }

    public function create()
    {
        $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
        $material_categories = Helper::arrayAppendKey($material_categories, 'code');
        $material_units = Material_unit::orderBy('orderby', 'ASC')->get();

        $data = [];
        $data['material_categories'] = $material_categories;
        $data['material_units'] = $material_units;
        $data['material'] = new Material;
        $data['material']->status = 1;

        $data['lastFullCode'] = Material::lastFullCode();

        $data['units'] = Material_unit::allJson();
        $data['files'] = StorageFile::allJson([]);

        return view('settings.material.create', $data);
    }

    public function store(MaterialRequest $request)
    {
        $validated = $request->validated();

        $this->save(0, $request);
        return redirect()->route('material.index')->with('message', '新增成功');
    }

    public function show($id)
    {
        $material = Material::find($id);
        $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
        $material_categories = Helper::arrayAppendKey($material_categories, 'code');
        $material_units = Material_unit::orderBy('orderby', 'ASC')->get();

        $data = [];
        $data['material'] = $material;
        $data['material_categories'] = $material_categories;
        $data['material_units'] = $material_units;

        $data['lastFullCode'] = Material::lastFullCode();

        $data['units'] = Material_unit::allJson();
        $data['files'] = StorageFile::allJson(
            [
                $material->file1,
                $material->file2,
                $material->file3
            ]
        );

        $data["show"] = 1;

        return view('settings.material.edit', $data);
    }

    public function edit($id)
    {
        $material = Material::find($id);
        $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
        $material_categories = Helper::arrayAppendKey($material_categories, 'code');
        $material_units = Material_unit::orderBy('orderby', 'ASC')->get();

        $data = [];
        $data['material'] = $material;
        $data['material_categories'] = $material_categories;
        $data['material_units'] = $material_units;

        $data['units'] = Material_unit::allJson();
        $data['files'] = StorageFile::allJson(
            [
                $material->file1,
                $material->file2,
                $material->file3
            ]
        );

        $data['lastFullCode'] = Material::lastFullCode();

        $data["show"] = 0;

        return view('settings.material.edit', $data);
    }

    public function update(MaterialRequest $request, $id)
    {
        $validated = $request->validated();

        $this->save($id, $request);
        return redirect()->route('material.index')->with('message', '修改成功');
    }

    public function destroy($id)
    {
        try{
            $material = Material::find($id);

            if($material->file_1 > 0) {
                $file = StorageFile::find($material->file_1);
                if ($file) {
                    Storage::delete('public/files/' . $file->file_name);
                    Storage::delete('public/thunmbs/' . $file->file_name);
                    $file->delete();
                }
            }

            if($material->file_2 > 0) {
                $file = StorageFile::find($material->file_2);
                if ($file) {
                    Storage::delete('public/files/' . $file->file_name);
                    Storage::delete('public/thunmbs/' . $file->file_name);
                    $file->delete();
                }
            }

            if($material->file_3 > 0) {
                $file = StorageFile::find($material->file_3);
                if ($file) {
                    Storage::delete('public/files/' . $file->file_name);
                    Storage::delete('public/thunmbs/' . $file->file_name);
                    $file->delete();
                }
            }

            $material->status = 2;
            $material->delete_flag = 1;
            $material->deleted_at = Now();
            $material->deleted_user = session('admin_user')->id;
            $material->save();
            $material->delete();
            return redirect()->route('material.index')->with('message', '刪除成功');
        } catch (Exception $e) {
            return redirect()->route('material.index')->with('error', '刪除失敗');
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

    public function save($id, $request)
    {
        // 新增或修改
        if ($id == 0) {
            $material = new Material;

            // 不可修改的欄位 (分類、編號、庫存)
            $material->code1 = $request->code_1;
            $material->code2 = $request->code_2;
            $material->code3 = $request->code_3;
            $material->fullcode = $request->fullCode;
            $material->material_categories_code = $request->material_category;

            $material->stock = 0;
            $material->stock_no = 0;

            $material->created_user = session('admin_user')->id;
        } else {
            $material = Material::find($id);
            $material->updated_user = session('admin_user')->id;
        }

        // 處理檔案清單
        StorageFile::packFiles($request, $material);

        $material->fullName = $request->fullName;
        $material->unit = $request->unit;
        $material->cost = $request->cost;
        $material->price = $request->price;
        $material->cal_unit = $request->cal_unit;
        $material->cal_price = $request->cal_price;
        $material->size = $request->size;
        $material->color = $request->color;
        $material->buy = $request->buy;
        $material->safe = $request->safe;
        $material->memo = $request->memo;
        $material->status = $request->status;
        $material->created_user = session('admin_user')->id;
        $material->delete_flag = 0;
        $material->save();

        return $material;
    }
}

