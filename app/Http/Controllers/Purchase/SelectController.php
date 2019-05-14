<?php

namespace App\Http\Controllers\Purchase;

use App\Model\Gallery;
use App\Model\Setting;
use App\Model\Material;
use App\Model\Supplier;
use App\Model\Warehouse;
use App\Model\Material_unit;
use Illuminate\Http\Request;
use App\Model\Material_category;
use App\Model\Material_warehouse;
use App\Http\Controllers\Controller;

class SelectController extends Controller
{
    public function selectSupplier(Request $request)
    {
        $search_code = 'all';
        if($search_code == 'all'){
            $suppliers = Supplier::where('delete_flag','0')->get();
        } else {
            $suppliers = Supplier::where('delete_flag','0')->where('category',$search_code)->get();
        }
        return view('purchase.inquiry.selectSupplier',compact('suppliers','search_code'));
    }

    public function search_supplier(Request $request)
    {
        $search_code = $request->search_category;
        if($search_code == 'all'){
            $suppliers = Supplier::where('delete_flag','0')->get();
        } else {
            $suppliers = Supplier::where('delete_flag','0')->where('category',$search_code)->get();
        }
        return view('purchase.inquiry.selectSupplier',compact('suppliers','search_code'));
    }

    public function create_supplier()
    {
        return view('purchase.inquiry.createSupplier');  
    }

    public function create_Material()
    {
        $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
        $material_units = Material_unit::orderBy('orderby', 'ASC')->get();
        $warehouses = Warehouse::where('delete_flag','0')->where('status','1')->orderBy('code','ASC')->get();
        return view('purchase.inquiry.createMaterial',compact('material_categories','material_units','warehouses'));  
    }

    // 儲存物料選擇時新增的物料
    public function store_Material(Request $request)
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

            return redirect()->route('selectMaterial')->with('message','新增成功');
        } catch (Exception $e) {
            return redirect()->route('selectMaterial')->with('error','新增失敗');
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
        // material = 2 , warehouse = 3 ,material_module = 4, apply_out_stock = 5 , sale = 6
        $img->category = 2;
        $img->created_user = session('admin_user')->id;
        $img->delete_flag = 0;
        $img->save();
        return $img->id;
    }

    public function store_supplier(Request $request)
    {
        $rules = [
            'fullName' => 'required|string',
            'shortName' => 'required|string',
        ];

        $messages = [
            'fullName.required' => '全名 不可為空',
            'shortName.required' => '簡稱 不可為空',
        ];
        $this->validate($request, $rules, $messages);

        try{
            $latest_code = Setting::where('set_key','supplier_code')->first();
            $number = (int)$latest_code->set_value + 1;
            $code_str = "S".str_pad($number, 6, '0',STR_PAD_LEFT);

            $supplier = new Supplier;
            $supplier->code = $code_str;
            $supplier->gpn = $request->gpn;
            $supplier->fullName = $request->fullName;
            $supplier->shortName = $request->shortName;
            $supplier->category = $request->category;
            $supplier->pay = $request->pay;
            $supplier->receiving = $request->receiving;
            $supplier->owner = $request->owner;
            $supplier->contact = $request->contact;
            $supplier->tel = $request->tel;
            $supplier->fax = $request->fax;
            $supplier->address = $request->address;
            $supplier->email = $request->email;
            $supplier->invoiceTitle = $request->invoiceTitle;
            $supplier->invoiceAddress = $request->invoiceAddress;
            $supplier->website = $request->website;
            $supplier->items = $request->items;
            $supplier->contact1 = $request->contact1;
            $supplier->contactContent1 = $request->contactContent1;
            $supplier->contactPerson1 = $request->contactPerson1;
            $supplier->contact2 = $request->contact2;
            $supplier->contactContent2 = $request->contactContent2;
            $supplier->contactPerson2 = $request->contactPerson2;
            $supplier->contact3 = $request->contact3;
            $supplier->contactContent3 = $request->contactContent3;
            $supplier->contactPerson3 = $request->contactPerson3;
            $supplier->memo = $request->memo;
            $supplier->status = $request->status;
            $supplier->created_user = session('admin_user')->id;
            $supplier->delete_flag = 0;
            $supplier->save();

            $latest_code->set_value = $number;
            $latest_code->save();
            return redirect()->route('selectSupplier')->with('message','新增成功');
 
        } catch (Exception $e) {
            return redirect()->route('selectSupplier')->with('error','新增失敗');
        }
    }

    public function addRow(Request $request)
    {
        $materialCount = $request->materialCount;

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
                <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
                <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit">                
            </td>
            <td>
                <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" value="" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;">
            </td>
            <td>
                <span id="materialSubTotal'.$materialCount.'" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
            </td>
        </tr>';

        return $data;
    }

    public function addRow_module(Request $request)
    {
        $materialCount = $request->materialCount;

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
                <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
                <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit">                
            </td>

            <td>
                <input type="text" name="materialCost[]" id="materialCost'.$materialCount.'" value="" onkeyup="total();" onchange="total();" class="materialCost" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;">
            </td>
            <td>
                <span id="materialSubTotal_cost'.$materialCount.'" class="materialSubTotal_cost" style="line-height: 30px; vertical-align: middle;">0</span>
            </td>

            <td>
                <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" value="" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;">
            </td>
            <td>
                <span id="materialSubTotal_price'.$materialCount.'" class="materialSubTotal_price" style="line-height: 30px; vertical-align: middle;">0</span>
            </td>
        </tr>';

        return $data;
    }

    public function selectMaterial(Request $request)
    {
        $search_code = 'all';
        if(Material_unit::count()>0){
            if(Material_category::count()>0){
                $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
                $materials = Material::where('delete_flag','0')->where('status','1')->where('unit','<>','0')->get();
                return view('purchase.inquiry.selectMaterial',compact('materials','material_categories','search_code'));
            } else {
                return redirect()->route('material_category.index')->with('error', '尚無 物料分類 資料，請先建立');            
            }
        } else {
            return view('purchase.inquiry.selectMaterial',compact('materials','material_categories','search_code'));
        }
    }

    public function search_material(Request $request)
    {
        $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
        $search_code = $request->search_category;
        if($search_code == 'all'){
            $materials = Material::where('delete_flag','0')->where('status','1')->where('unit','<>','0')->get();
        } else {
            $materials = Material::where('delete_flag','0')->where('status','1')->where('unit','<>','0')->where('material_categories_code',$search_code)->get();
        }
        return view('purchase.inquiry.selectMaterial',compact('material_categories', 'materials', 'search_code'));
    }

    // 入庫、差異處理 選擇物料
    public function selectMaterial_stock(Request $request)
    {
        $search_code = 'all';
        if(Material_unit::count()>0){
            if(Material_category::count()>0){
                $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
                // $materials = Material::where('delete_flag','0')->where('status','1')->where('unit','<>','0')->where('warehouse','>','0')->get();
                $materials = Material::where('delete_flag','0')->where('status','1')->where('unit','<>','0')->get();
                return view('purchase.stock.selectMaterial',compact('materials','material_categories','search_code'));
            } else {
                return redirect()->route('material_category.index')->with('error', '尚無 物料分類 資料，請先建立');            
            }
        } else {
            return view('purchase.stock.selectMaterial',compact('materials','material_categories','search_code'));
        }
    }

    public function search_material_stock(Request $request)
    {
        $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
        $search_code = $request->search_category;
        if($search_code == 'all'){
            $materials = Material::where('delete_flag','0')->where('status','1')->where('unit','<>','0')->where('warehouse','>','0')->get();
        } else {
            $materials = Material::where('delete_flag','0')->where('status','1')->where('unit','<>','0')->where('warehouse','>','0')->where('material_categories_code',$search_code)->get();
        }
        return view('purchase.stock.selectMaterial',compact('material_categories', 'materials', 'search_code'));
    }

    // 採購轉入庫 新增物料列
    public function addRow_toStock(Request $request)
    {
        $materialCount = $request->materialCount;

        $data = '<tr id="materialRow'.$materialCount.'" class="materialRow">
            <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red"><i class="fa fa-remove"></i></a></td>
            <td>
                <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇物料</button>
                <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material">
            </td>
            <td>
                <span id="materialUnit'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
            </td>
            <td>
                <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 80%; margin-right: 10px; overflow: hidden;"> 請選擇倉儲</button>
                <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse">
            </td>
            <td>
                <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" style="width:120px; height: 30px; vertical-align: middle;">
            </td>
            
        </tr>';

        return $data;
    }

    public function addRow_stock(Request $request)
    {
        $materialCount = $request->materialCount;
  
        $is_init_str = '';
        $is_init_str .= '<option value="1"> 一般入庫</option>';

        // 已改為 庫存盤點->差異處理
        // $is_init_str .= '<option value="2"> 庫存調整</option>';

        $is_init_str .= '<option value="3"> 起始庫存</option>';
        $is_init_str .= '<option value="4"> 採購單轉入庫</option>';
        $is_init_str .= '<option value="5"> 退貨入庫</option>';

        $units = Material_unit::where('delete_flag','0')->orderBy('orderby', 'ASC')->get();     

        $data = '<tr id="materialRow'.$materialCount.'" class="materialRow">
            <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red"><i class="fa fa-remove"></i></a></td>
            <td>
                <select name="materialOption[]" id="materialOption'.$materialCount.'" style="width: 120px; height: 30px; vertical-align: middle;">
                    '.$is_init_str.'
                </select>
            </td>
            <td>
                <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇物料</button>
                <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material">
            </td>
            <td>
                <span id="materialUnit'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
            </td>
            <td>
                <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 80%; margin-right: 10px; overflow: hidden;"> 請選擇倉儲</button>
                <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse">
            </td>
            <td>
                <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" style="width:120px; height: 30px; vertical-align: middle;">
            </td>
            
        </tr>';

        return $data;
    }

    public function addRow_adjustment(Request $request)
    {
        $materialCount = $request->materialCount;

        $data = '<tr id="materialRow'.$materialCount.'" class="materialRow">
            <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red"><i class="fa fa-remove"></i></a></td>
            <td>
                <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇物料</button>
                <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material">
            </td>
            <td>
                <span id="materialUnit'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
            </td>
            <td>
                <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 80%; margin-right: 10px; overflow: hidden;"> 請選擇倉儲</button>
                <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse">
            </td>
            <td>
                <span id="materialStock'.$materialCount.'" class="materialStock" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
            </td>
            <td>
                <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" style="width:100px; height: 30px; vertical-align: middle;">
            </td>
            
        </tr>';

        return $data;
    }

    public function addRow_transfer(Request $request)
    {
        $materialCount = $request->materialCount;

        $data = '<tr id="materialRow'.$materialCount.'" class="materialRow">
            <td>
                <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇物料</button>
                <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material">
            </td>
            <td>
                <span id="materialUnit'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
            </td>
            <td>
                <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇倉儲</button>
                <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse">
            </td>
            <td>
                <span id="materialStock'.$materialCount.'" class="materialStock" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
            </td>
            <td>
                <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" style="width:100px; height: 30px; vertical-align: middle;">
            </td>
            <td>
                <button type="button" onclick="openSelectNewWarehouse('.$materialCount.');" id="materialNewWarehouseName'.$materialCount.'" name="materialNewWarehouseName'.$materialCount.'" class="btn btn-default get_material_new_warehouse" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇倉儲</button>
                <input type="hidden" name="materialNewWarehouse[]" id="materialNewWarehouse'.$materialCount.'" class="select_materialNewWarehouse">
            </td>
        </tr>';

        return $data;
    }



    public function addRow_semi(Request $request)
    {
        $materialCount = $request->materialCount;

        $data = '<tr id="materialRow'.$materialCount.'" class="materialRow">
            <td>
                <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇物料</button>
                <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material">
            </td>
            <td>
                <span id="materialUnit'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
            </td>
            <td>
                <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇倉儲</button>
                <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse">
            </td>
            <td>
                <span id="materialStock'.$materialCount.'" class="materialStock" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
            </td>
            <td>
                <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" style="width:100px; height: 30px; vertical-align: middle;">
            </td>
          
        </tr>';

        return $data;
    }


    public function get_warehouse_stock(Request $request)
    {
        $material_warehouses = Material_warehouse::where('delete_flag','0')->where('material_id',$request->material_id)->get();
        
        $stock = 0;
        foreach($material_warehouses as $material_warehouse){

            if($material_warehouse->warehouse_id == $request->warehouse_id){
                $stock = $material_warehouse->stock;
            }
        }
        return $stock;
    }
}
