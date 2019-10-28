<?php

namespace App\Http\Controllers\Stock;

use App\Model\User;
use App\Model\Stock;
use App\Model\Setting;
use App\Model\Material;
use App\Model\Warehouse;
use App\Model\Manufacturer;
use App\Model\Material_unit;
use Illuminate\Http\Request;
use App\Model\Processing_list;
use App\Model\Process_function;
use App\Model\Material_warehouse;
use App\Http\Controllers\Controller;
use App\Model\Semi_finished_schedule;


class Semi_finished_scheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = "all";
        $processings = Semi_finished_schedule::where('delete_flag','0')->orderBy('created_at','desc')->get();
        return view('stock.semi_finished_schedule.show',compact('processings','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $processings = Semi_finished_schedule::where('delete_flag','0')->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            } else {
                $processings = Semi_finished_schedule::where('delete_flag','0')->where('status',$search_code)->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            }
        } else {
            if($search_code == 'all'){
                $processings = Semi_finished_schedule::where('delete_flag','0')->get();
            } else {
                $processings = Semi_finished_schedule::where('delete_flag','0')->where('status',$search_code)->get();
            }
        }
        return view('stock.semi_finished_schedule.show',compact('processings','search_code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $sale_no = date("Ymd")."001";
        // $last_sale_no = Semi_finished_schedule::orderBy('sale_no','DESC')->first();
        // if($last_sale_no){
        //     if($last_sale_no->sale_no >= $sale_no){
        //         $sale_no = $last_sale_no->sale_no + 1;
        //     }
        // }
        return view('stock.semi_finished_schedule.create');
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
            // 'supplier' => 'required',
            'start_date' => 'date_format:"Y-m-d"|required',

        ];
        $messages = [
            'lot_number.required' => '批號 必填',
            // 'supplier.required' => '尚未選擇 供應商',
            'start_date.required' => '開始日期 必填',
            'start_date.date_format' => '開始日期格式錯誤',
        ];

        $this->validate($request, $rules, $messages);

        $total_materials = count($request->material);
        $material = [];
        $warehouse = [];
        $materialAmount = [];


        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $warehouse[] = $request->materialWarehouse[$i];
                $materialAmount[] = $request->materialAmount[$i];
            }
        }

        if(count($material) > 0){
            $materials = ['material'=>$material, 'warehouse'=>$warehouse, 'materialAmount'=>$materialAmount];

            $file_1=null;
            $file_2=null;
            $file_3=null;


            try{
                $processing = new Semi_finished_schedule;
                $processing->lot_number = $request->lot_number;
                //$sale->sale_no = $request->sale_no;
                //$sale->customer = $request->customer;
                $processing->materials = serialize($materials);
                $processing->start_date = $request->start_date;
                //$sale->expireDate = $request->expireDate;
                $processing->memo = $request->memo;
                $processing->file_1 = $file_1;
                $processing->file_2 = $file_2;
                $processing->file_3 = $file_3;
                $processing->status = 1;
                $processing->created_user = session('admin_user')->id;
                $processing->delete_flag = 0;
                $processing->save();
                return redirect()->route('semi_finished_schedule.index')->with('message', '半成品進度追蹤建立成功');
            } catch(Exception $e) {
                return redirect()->route('semi_finished_schedule.index')->with('error', '半成品進度追蹤建立失敗');
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
        $processing = Semi_finished_schedule::find($id);
        $materials = unserialize($processing->materials);

        $total_materials = count($materials['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){

            $material = Material::where('id',$materials['material'][$i])->first();
            $material_warehouse = Material_warehouse::where('delete_flag','0')->where('material_id',$materials['material'][$i])->where('warehouse_id',$materials['warehouse'][$i])->first();
            $warehouse_id = $materials['warehouse'][$i];
            $warehouse = Warehouse::find($warehouse_id);
            $warehouse_name = $warehouse->code;
            $style = '';
            $readonly = '';
            $disabled = '';
            if($processing->status == 2){
                $style = ' style="display:none"';
                $readonly = ' readonly';
                $disabled = ' disabled';
            }

            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <span id="materialUnit'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                </td>
                <td>
                    <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 80%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$warehouse_name.'</button>
                    <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse" value="'.$warehouse_id.'">
                </td>
                <td>
                    <span id="materialStock'.$materialCount.'" class="materialStock" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material_warehouse->stock.'</span>
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                </td>

            </tr>';
            $materialCount++;
        }

        if($processing->updated_user > 0){
            $updated_user = User::where('id',$processing->updated_user)->first();
        } else {
            $updated_user = User::where('id',$processing->created_user)->first();
        }

        // $upload_check_1 = true;
        // $upload_check_2 = true;
        // $upload_check_3 = true;

        // if($processing->file_1 > 0){
        //     $upload_check_1 = false;
        // }
        // if($processing->file_2 > 0){
        //     $upload_check_2 = false;
        // }
        // if($processing->file_3 > 0){
        //     $upload_check_3 = false;
        // }

        return view('stock.semi_finished_schedule.show_one', compact('processing','data','updated_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $processing = Semi_finished_schedule::find($id);
        $materials = unserialize($processing->materials);

        $total_materials = count($materials['material']);
        $materialCount = 0;
        $data = '';
        for($i = 0; $i < $total_materials; $i++){

            $material = Material::where('id',$materials['material'][$i])->first();
            $material_warehouse = Material_warehouse::where('delete_flag','0')->where('material_id',$materials['material'][$i])->where('warehouse_id',$materials['warehouse'][$i])->first();
            $warehouse_id = $materials['warehouse'][$i];
            $warehouse = Warehouse::find($warehouse_id);
            $warehouse_name = $warehouse->code;
            $style = '';
            $readonly = '';
            $disabled = '';
            if($processing->status == 2){
                $style = ' style="display:none"';
                $readonly = ' readonly';
                $disabled = ' disabled';
            }

            $data .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red" '.$style.'><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial('.$materialCount.');" id="materialName'.$materialCount.'" name="materialName'.$materialCount.'" class="btn btn-default" style="width: 100%; margin-right: 10px; overflow: hidden;" '.$disabled.'> '.$material->fullCode.' '.$material->fullName.'</button>
                    <input type="hidden" name="material[]" id="material'.$materialCount.'" class="select_material" value="'.$materials['material'][$i].'">
                </td>
                <td>
                    <span id="materialUnit'.$materialCount.'" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                </td>
                <td>
                    <button type="button" onclick="openSelectWarehouse('.$materialCount.');" id="materialWarehouseName'.$materialCount.'" name="materialWarehouseName'.$materialCount.'" class="btn btn-default get_material_warehouse" style="width: 80%; margin-right: 10px; overflow: hidden;"> '.$warehouse_name.'</button>
                    <input type="hidden" name="materialWarehouse[]" id="materialWarehouse'.$materialCount.'" class="select_materialWarehouse" value="'.$warehouse_id.'">
                </td>
                <td>
                    <span id="materialStock'.$materialCount.'" class="materialStock" style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material_warehouse->stock.'</span>
                </td>
                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount'.$materialCount.'" class="materialAmount" placeholder="0" style="width:100px; height: 30px; vertical-align: middle;" value="'.$materials['materialAmount'][$i].'" '.$readonly.'>
                </td>

            </tr>';
            $materialCount++;
        }

        if($processing->updated_user > 0){
            $updated_user = User::where('id',$processing->updated_user)->first();
        } else {
            $updated_user = User::where('id',$processing->created_user)->first();
        }

        // $upload_check_1 = true;
        // $upload_check_2 = true;
        // $upload_check_3 = true;

        // if($processing->file_1 > 0){
        //     $upload_check_1 = false;
        // }
        // if($processing->file_2 > 0){
        //     $upload_check_2 = false;
        // }
        // if($processing->file_3 > 0){
        //     $upload_check_3 = false;
        // }

        return view('stock.semi_finished_schedule.edit', compact('processing','data','materialCount','updated_user'));

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
            // 'lot_number' => 'required',
            // 'supplier' => 'required',
            // 'start_date' => 'date_format:"Y-m-d"|required',

        ];
        $messages = [
            // 'lot_number.required' => '批號 必填',
            // 'supplier.required' => '尚未選擇 供應商',
            // 'start_date.required' => '開始日期 必填',
            // 'start_date.date_format' => '開始日期格式錯誤',
        ];

        $this->validate($request, $rules, $messages);

        $total_materials = count($request->material);
        $material = [];
        $warehouse = [];
        $materialAmount = [];


        for($i=0; $i < $total_materials; $i++){
            if($request->material[$i]){
                $material[] = $request->material[$i];
                $warehouse[] = $request->materialWarehouse[$i];
                $materialAmount[] = $request->materialAmount[$i];
            }
        }

        if(count($material) > 0){
            $materials = ['material'=>$material, 'warehouse'=>$warehouse, 'materialAmount'=>$materialAmount];

            // $file_1=null;
            // $file_2=null;
            // $file_3=null;


            try{
                $processing = Semi_finished_schedule::find($id);
                // $processing->lot_number = $request->lot_number;
                //$sale->sale_no = $request->sale_no;
                //$sale->customer = $request->customer;
                $processing->materials = serialize($materials);
                if($request->end_date){
                    $processing->end_date = $request->end_date;
                }
                $processing->memo = $request->memo;
                // $processing->file_1 = $file_1;
                // $processing->file_2 = $file_2;
                // $processing->file_3 = $file_3;
                $processing->status = $request->status;
                $processing->updated_user = session('admin_user')->id;
                $processing->save();
                return redirect()->route('semi_finished_schedule.index')->with('message', '半成品進度追蹤修改成功');
            } catch(Exception $e) {
                return redirect()->route('semi_finished_schedule.index')->with('error', '半成品進度追蹤修改失敗');
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
            $processing = Semi_finished_schedule::find($id);
            $processing->delete_flag = 1;
            $processing->deleted_at = Now();
            $processing->deleted_user = session('admin_user')->id;
            $processing->save();
            return redirect()->route('semi_finished_schedule.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('semi_finished_schedule.index')->with('error','刪除失敗');
        }
    }

    public function addRow_processing(Request $request)
    {
        $materialCount = $request->materialCount;

        // $manufacturers = Manufacturer::
        $process_functions = Process_function::orderBy('orderby', 'ASC')->get();

        $is_init_str = '<option value="0"> 請選擇</option>';
        foreach($process_functions as $process_function){
            $is_init_str .= '<option value="'.$process_function->id.'"> '.$process_function->name.'</option>';
        }



        $data = '<tr id="materialRow'.$materialCount.'" class="materialRow">
            <td><a href="javascript:delMaterial('.$materialCount.');" class="btn red"><i class="fa fa-remove"></i></a></td>
            <td>
                <button type="button" onclick="openSelectManufacturer('.$materialCount.');" id="manufacturerName'.$materialCount.'" name="manufacturerName'.$materialCount.'" class="btn btn-default" style="width: 100%; margin-right: 10px; overflow: hidden;"> 請選擇廠商</button>
                <input type="hidden" name="manufacturer[]" id="manufacturer'.$materialCount.'" class="select_manufacturer">
            </td>
            <td>
                <select name="processFunction[]" id="processFunction'.$materialCount.'" class="select_processFunction" style="width: 120px; height: 30px; vertical-align: middle;">
                    '.$is_init_str.'
                </select>
            </td>
            <td>
                <input type="date" name="startDate[]" id="startDate'.$materialCount.'" class="select_startDate" style="height: 30px; vertical-align: middle;">
            </td>
            <td>
                <input type="date" name="endDate[]" id="endDate'.$materialCount.'" class="select_endDate" style="height: 30px; vertical-align: middle;">
            </td>
            <td>
                <select name="processStatus[]" id="processStatus'.$materialCount.'" class="select_pprocessStatus" style="width: 120px; height: 30px; vertical-align: middle;">
                    <option value="1"> 加工中</option>
                    <option value="2"> 已完成</option>
                    <option value="3"> 取消</option>
                </select>
            </td>
            <td>
                <input type="text" name="processMemo[]" id="processMemo'.$materialCount.'" class="processMemo" placeholder="" style="width:100%; height: 30px; vertical-align: middle;">
            </td>

        </tr>';



        return $data;
    }


    public function selectManufacturer(Request $request)
    {
        $search_code = 'all';
        if($search_code == 'all'){
            $manufacturers = Manufacturer::where('delete_flag','0')->where('status','1')->get();
        } else {
            $manufacturers = Manufacturer::where('delete_flag','0')->where('status','1')->where('category',$search_code)->get();
        }
        return view('stock.selectManufacturer',compact('manufacturers','search_code'));
    }

    public function search_manufacturer(Request $request)
    {
        $search_code = $request->search_category;
        if($search_code == 'all'){
            $manufacturers = Manufacturer::where('delete_flag','0')->get();
        } else {
            $manufacturers = Manufacturer::where('delete_flag','0')->where('category',$search_code)->get();
        }
        return view('stock.selectManufacturer',compact('manufacturers','search_code'));
    }

    public function create_manufacturer()
    {
        return view('stock.createManufacturer');
    }

    public function store_manufacturer(Request $request)
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
            $latest_code = Setting::where('set_key','manufacturer_code')->first();
            $number = (int)$latest_code->set_value + 1;
            $code_str = "M".str_pad($number, 6, '0',STR_PAD_LEFT);

            // 更新最新 code
            $latest_code->set_value = $number;
            $latest_code->save();

            $manufacturer = new Manufacturer;
            $manufacturer->code = $code_str;
            $manufacturer->gpn = $request->gpn;
            $manufacturer->fullName = $request->fullName;
            $manufacturer->shortName = $request->shortName;
            $manufacturer->category = $request->category;
            $manufacturer->pay = $request->pay;
            $manufacturer->receiving = $request->receiving;
            $manufacturer->owner = $request->owner;
            $manufacturer->contact = $request->contact;
            $manufacturer->tel = $request->tel;
            $manufacturer->fax = $request->fax;
            $manufacturer->address = $request->address;
            $manufacturer->email = $request->email;
            $manufacturer->invoiceTitle = $request->invoiceTitle;
            $manufacturer->invoiceAddress = $request->invoiceAddress;
            $manufacturer->website = $request->website;
            $manufacturer->items = $request->items;
            $manufacturer->contact1 = $request->contact1;
            $manufacturer->contactContent1 = $request->contactContent1;
            $manufacturer->contactPerson1 = $request->contactPerson1;
            $manufacturer->contact2 = $request->contact2;
            $manufacturer->contactContent2 = $request->contactContent2;
            $manufacturer->contactPerson2 = $request->contactPerson2;
            $manufacturer->contact3 = $request->contact3;
            $manufacturer->contactContent3 = $request->contactContent3;
            $manufacturer->contactPerson3 = $request->contactPerson3;
            $manufacturer->memo = $request->memo;
            $manufacturer->status = $request->status;
            $manufacturer->created_user = session('admin_user')->id;
            $manufacturer->delete_flag = 0;
            $manufacturer->save();

            $latest_code->set_value = $number;
            $latest_code->save();
            return redirect()->route('selectManufacturer')->with('message','新增成功');

        } catch (Exception $e) {
            return redirect()->route('selectManufacturer')->with('error','新增失敗');
        }
    }
}
