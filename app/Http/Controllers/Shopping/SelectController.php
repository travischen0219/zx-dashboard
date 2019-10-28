<?php

namespace App\Http\Controllers\Shopping;

use App\Model\Setting;
use App\Model\Customer;
use App\Model\Material;
use App\Model\Material_unit;
use Illuminate\Http\Request;
use App\Model\Material_module;
use App\Model\Material_category;
use App\Model\Material_warehouse;
use App\Http\Controllers\Controller;

class SelectController extends Controller
{
    public function selectCustomer(Request $request)
    {
        $search_code = 'all';
        if($search_code == 'all'){
            $customers = Customer::where('delete_flag','0')->where('status','1')->get();
        } else {
            $customers = Customer::where('delete_flag','0')->where('status','1')->where('category',$search_code)->get();
        }
        return view('shopping.selectCustomer',compact('customers','search_code'));
    }

    public function search_customer(Request $request)
    {
        $search_code = $request->search_category;
        if($search_code == 'all'){
            $customers = Customer::where('delete_flag','0')->where('status','1')->get();
        } else {
            $customers = Customer::where('delete_flag','0')->where('status','1')->where('category',$search_code)->get();
        }
        return view('shopping.selectCustomer',compact('customers','search_code'));
    }

    public function create_customer()
    {
        return view('shopping.createCustomer');
    }

    public function store_customer(Request $request)
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
            $latest_code = Setting::where('set_key','customer_code')->first();
            $number = (int)$latest_code->set_value + 1;
            $code_str = "C".str_pad($number, 6, '0',STR_PAD_LEFT);

            $latest_code->set_value += 1;
            $latest_code->save();

            $customer = new Customer;
            $customer->code = $code_str;
            $customer->gpn = $request->gpn;
            $customer->fullName = $request->fullName;
            $customer->shortName = $request->shortName;
            $customer->category = $request->category;
            $customer->pay = $request->pay;
            $customer->receiving = $request->receiving;
            $customer->owner = $request->owner;
            $customer->contact = $request->contact;
            $customer->tel = $request->tel;
            $customer->fax = $request->fax;
            $customer->address = $request->address;
            $customer->email = $request->email;
            $customer->invoiceTitle = $request->invoiceTitle;
            $customer->invoiceAddress = $request->invoiceAddress;
            $customer->website = $request->website;
            $customer->close_date = $request->close_date;
            $customer->contact1 = $request->contact1;
            $customer->contactContent1 = $request->contactContent1;
            $customer->contactPerson1 = $request->contactPerson1;
            $customer->contact2 = $request->contact2;
            $customer->contactContent2 = $request->contactContent2;
            $customer->contactPerson2 = $request->contactPerson2;
            $customer->contact3 = $request->contact3;
            $customer->contactContent3 = $request->contactContent3;
            $customer->contactPerson3 = $request->contactPerson3;
            $customer->memo = $request->memo;
            $customer->status = $request->status;
            $customer->created_user = session('admin_user')->id;
            $customer->delete_flag = 0;
            $customer->save();

            $latest_code->set_value = $number;
            $latest_code->save();
            return redirect()->route('selectCustomer')->with('message','新增成功');

        } catch (Exception $e) {
            return redirect()->route('selectCustomer')->with('error','新增失敗');
        }
    }

    public function addRow_inventory(Request $request)
    {
        $materialCount = $request->materialCount;

        $data = '<tr id="materialRow'.$materialCount.'" class="materialRow">
            <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red"><i class="fa fa-remove"></i></a></td>
            <td>
                <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇物料</button>
                <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material">
            </td>
            <td>
                <span id="materialStock_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">0</span>
                <input type="hidden" name="materialStock[]" id="materialStock'.$materialCount.'" class="materialStock">
            </td>
            <td>
                <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;">
            </td>
            <td>
                <span id="materialSubTotal_show'.$materialCount.'" class="materialSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                <input type="hidden" name="materialSubTotal[]" id="materialSubTotal'.$materialCount.'" class="materialSubTotal">
            </td>
            <td>
                <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
                <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit">
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

    public function addRow_apply_out(Request $request)
    {
        $materialCount = $request->materialCount;

        $data = '<tr id="materialRow'.$materialCount.'" class="materialRow">
            <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red"><i class="fa fa-remove"></i></a></td>
            <td>
                <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇物料</button>
                <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material">
            </td>
            <td>
                <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
                <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit">
            </td>
            <td>
                <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇倉儲</button>
                <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse">
            </td>
            <td>
                <span id="materialStock_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">0</span>
                <input type="hidden" name="materialStock[]" id="materialStock'.$materialCount.'" class="materialStock">
            </td>
            <td>
                <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;">
            </td>
            <td>
                <span id="materialSubTotal_show'.$materialCount.'" class="materialSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                <input type="hidden" name="materialSubTotal[]" id="materialSubTotal'.$materialCount.'" class="materialSubTotal">
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

    public function selectMaterial_inventory(Request $request)
    {
        $search_code = 'all';
        if(Material_unit::count()>0){
            if(Material_category::count()>0){
                $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
                $materials = Material::where('delete_flag','0')->where('status','1')->where('stock','>','0')->get();
                return view('shopping.selectMaterial',compact('materials','material_categories','search_code'));
            } else {
                return redirect()->route('material_category.index')->with('error', '尚無 物料分類 資料，請先建立');
            }
        } else {
            return view('shopping.selectMaterial',compact('materials','material_categories','search_code'));
        }
    }

    public function search_material_inventory(Request $request)
    {
        $material_categories = Material_category::orderBy('orderby', 'ASC')->get();
        $search_code = $request->search_category;
        if($search_code == 'all'){
            $materials = Material::where('delete_flag','0')->where('status','1')->where('stock','>','0')->get();
        } else {
            $materials = Material::where('delete_flag','0')->where('status','1')->where('stock','>','0')->where('material_categories_code',$search_code)->get();
        }
        return view('shopping.selectMaterial',compact('material_categories', 'materials', 'search_code'));
    }

    public function selectMaterial_module_inventory(Request $request)
    {
        $modules = Material_module::where('delete_flag','0')->get();
        return view('shopping.selectMaterial_module',compact('modules'));
    }

    public function search_material_module_inventory(Request $request)
    {
        if($request->search_code){
            $modules = Material_module::where('delete_flag','0')->where('code','like','%'.$request->search_code.'%')->get();
        } else {
            $modules = Material_module::where('delete_flag','0')->get();
        }
        return view('shopping.selectMaterial_module',compact('modules'));
    }

    public function addModule_inventory(Request $request)
    {
        $id = $request->id;
        $materialCount = $request->materialCount;
        $modules = Material_module::find($id);
        $materials = unserialize($modules->materials);
        $total_materials = count($materials['material']);
        $return = [];
        $data = '';
        $disabled = '';
        $style = '';
        $readonly = '';

        for($i = 0; $i < $total_materials; $i++){
            $material = Material::where('id',$materials['material'][$i])->first();
            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;color:blue;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <span id="materialStock_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->stock.'</span>
                    <input type="hidden" name="materialStock[]" id="materialStock'.$materialCount.'" class="materialStock" value="'.$material->stock.'">
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                </td>
                <td>
                    <span id="materialSubTotal_show'.$materialCount.'" class="materialSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                    <input type="hidden" name="materialSubTotal[]" id="materialSubTotal'.$materialCount.'" class="materialSubTotal">
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
                    <input type="hidden" name="materialPriceSubTotal[]" id="materialPriceSubTotal'.$materialCount.'" class="materialPriceSubTotal">
                </td>
            </tr>';
            $materialCount++;
        }
        $return['data'] = $data;
        $return['materialCount'] = $materialCount;
        return $return;
    }

    public function addModule_apply_out(Request $request)
    {
        $id = $request->id;
        $materialCount = $request->materialCount;
        $modules = Material_module::find($id);
        $materials = unserialize($modules->materials);
        $total_materials = count($materials['material']);

        $return = [];
        $data = '';
        $disabled = '';
        $style = '';
        $readonly = '';

        for($i = 0; $i < $total_materials; $i++){
            $material = Material::where('id',$materials['material'][$i])->first();
            $material_warehouse = Material_warehouse::where('delete_flag','0')->where('material_id',$material->id)->where('warehouse_id',$material->warehouse)->first();

            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;color:blue;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                    <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'">
                </td>
                <td>
                    <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 100%; margin-right: 10px; overflow: hidden;"> '.$material->warehouse_name->code.'</button>
                    <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse" value="'.$material->warehouse.'">
                </td>
                <td>
                    <span id="materialStock_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material_warehouse->stock.'</span>
                    <input type="hidden" name="materialStock[]" id="materialStock'.$materialCount.'" class="materialStock" value="'.$material_warehouse->stock.'">
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                </td>
                <td>
                    <span id="materialSubTotal_show'.$materialCount.'" class="materialSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                    <input type="hidden" name="materialSubTotal[]" id="materialSubTotal'.$materialCount.'" class="materialSubTotal">
                </td>
                <td>
                    <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$materials['materialPrice'][$i].'" '.$readonly.'>
                </td>
                <td>
                    <span id="materialPriceSubTotal_show'.$materialCount.'" class="materialPriceSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                    <input type="hidden" name="materialPriceSubTotal[]" id="materialPriceSubTotal'.$materialCount.'" class="materialPriceSubTotal">
                </td>
            </tr>';
            $materialCount++;
        }
        $return['data'] = $data;
        $return['materialCount'] = $materialCount;
        return $return;
    }

    public function addModule_purchase(Request $request)
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
                    <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                    <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'">
                </td>
                <td>
                    <input type="text" name="materialPrice[]" id="materialPrice'.$materialCount.'" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="'.$material->cost.'" '.$readonly.'>
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

    public function addRow_out_stock(Request $request)
    {
        $materialCount = $request->materialCount;

        $data = '<tr id="materialRow'.$materialCount.'" class="materialRow">
            <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red"><i class="fa fa-remove"></i></a></td>
            <td>
                <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇物料</button>
                <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material">
            </td>
            <td>
                <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇倉儲</button>
                <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse">
            </td>
            <td>
                <span id="materialStock_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">0</span>
                <input type="hidden" name="materialStock[]" id="materialStock'.$materialCount.'" class="materialStock">
            </td>
            <td>
                <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;">
            </td>
            <td>
                <span id="materialSubTotal_show'.$materialCount.'" class="materialSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                <input type="hidden" name="materialSubTotal[]" id="materialSubTotal'.$materialCount.'" class="materialSubTotal">
            </td>
            <td>
                <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
                <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit">
            </td>
            <input type="hidden" name="stock_id[]" id="stock_id'.$materialCount.'" class="stock_id" value="0">
        </tr>';

        return $data;
    }

    public function addModule_out_stock(Request $request)
    {
        $id = $request->id;
        $materialCount = $request->materialCount;
        $modules = Material_module::find($id);
        $materials = unserialize($modules->materials);
        $total_materials = count($materials['material']);
        $return = [];
        $data = '';
        $disabled = '';
        $style = '';
        $readonly = '';

        for($i = 0; $i < $total_materials; $i++){

            $material = Material::where('id',$materials['material'][$i])->first();
            $material_warehouse = Material_warehouse::where('delete_flag','0')->where('material_id',$material->id)->where('warehouse_id',$material->warehouse)->first();


            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;color:blue;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 100%; margin-right: 10px; overflow: hidden;"> '.$material->warehouse_name->code.'</button>
                    <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse" value="'.$material->warehouse.'">
                </td>
                <td>
                    <span id="materialStock_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material_warehouse->stock.'</span>
                    <input type="hidden" name="materialStock[]" id="materialStock'.$materialCount.'" class="materialStock" value="'.$material_warehouse->stock.'">
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                </td>
                <td>
                    <span id="materialSubTotal_show'.$materialCount.'" class="materialSubTotal_show" style="line-height: 30px; vertical-align: middle;">0</span>
                    <input type="hidden" name="materialSubTotal[]" id="materialSubTotal'.$materialCount.'" class="materialSubTotal">
                </td>
                <td>
                    <span id="materialUnit_show'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                    <input type="hidden" name="materialUnit[]" id="materialUnit'.$materialCount.'" class="materialUnit" value="'.$material->unit.'">
                </td>
                <input type="hidden" name="stock_id[]" id="stock_id'.$materialCount.'" class="stock_id" value="0">
            </tr>';
            $materialCount++;
        }
        $return['data'] = $data;
        $return['materialCount'] = $materialCount;
        return $return;
    }
}
