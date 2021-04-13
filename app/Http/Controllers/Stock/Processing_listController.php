<?php

namespace App\Http\Controllers\Stock;

use App\Model\Material;
use App\Model\Warehouse;
use App\Model\Manufacturer;
use Illuminate\Http\Request;
use App\Model\Processing_list;
use App\Model\Process_function;
use App\Model\Material_warehouse;
use App\Http\Controllers\Controller;
use App\Model\Semi_finished_schedule;
use App\Model\User;

class Processing_listController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $processing = Semi_finished_schedule::find($id);
        $materials = unserialize($processing->materials);

        $total_materials = count($materials['material']);
        $data = '';
        for($i = 0; $i < $total_materials; $i++){

            $material = Material::where('id',$materials['material'][$i])->first();
            // $material_warehouse = Material_warehouse::where('delete_flag','0')->where('material_id',$materials['material'][$i])->where('warehouse_id',$materials['warehouse'][$i])->first();
            // $warehouse_id = $materials['warehouse'][$i];
            $warehouse = Warehouse::find($materials['warehouse'][$i]);
            $warehouse_name = $warehouse->code;

            $data .= '<tr>

                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->fullCode.' '.$material->fullName.'</span>
                </td>
                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                </td>
                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$warehouse_name.'</span>
                </td>

                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$materials['materialAmount'][$i].'</span>
                </td>

            </tr>';

        }

        $processing_lists = Processing_list::where('delete_flag','0')->where('semi_finished_schedule_id',$id)->orderBy('orderby','ASC')->get();
        $process_functions = Process_function::orderBy('orderby', 'ASC')->get();
        $total_processings = count($processing_lists);
        $data_process = '';
        $materialCount = 0;




        foreach($processing_lists as $processing_list){
            $manufacturer = Manufacturer::where('id',$processing_list->manufacturer_id)->first();

            if($processing_list->process_function_id == 0){
                $function_name = '未加工';
            } else {
                $function_name = Process_function::find($processing_list->process_function_id)->name;
            }

            $is_init_str = '<span style="width: 100px; line-height: 30px; vertical-align: middle;"> '.$function_name.'</span>';



            $status_str = '';

            if($processing_list->status == 1){
                $status_str .= '<span style="width: 100px; line-height: 30px; vertical-align: middle;"> 加工中</span>';
            } elseif($processing_list->status == 2) {
                $status_str .= '<span style="width: 100px; line-height: 30px; vertical-align: middle;"> 已完成</span>';
            } elseif($processing_list->status == 3){
                $status_str .= '<span style="width: 100px; line-height: 30px; vertical-align: middle;"> 取消</span>';
            }

            $readonly = '';
            $disabled = '';
            if($processing_list->status == 2 || $processing_list->status == 3){
                $readonly = ' readonly';
                $disabled = ' disabled';
            }

            $data_process .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td></td>
                <td>
                    <button type="button" onclick="openSelectManufacturer('.$materialCount.');" id="manufacturerName'.$materialCount.'" name="manufacturerName'.$materialCount.'" class="btn btn-default" style="width: 100%; margin-right: 10px; overflow: hidden; color:black;font-weight: bold;" disabled> '.$manufacturer->code.' '.$manufacturer->shortName.'</button>
                    <input type="hidden" name="manufacturer[]" id="manufacturer'.$materialCount.'" class="select_manufacturer" value="'.$processing_list->manufacturer_id.'">
                </td>
                <td>
                        '.$is_init_str.'
                </td>
                <td>
                    <input type="date" name="startDate[]" id="startDate'.$materialCount.'" class="select_startDate" style="height: 30px; vertical-align: middle;" value="'.$processing_list->start_date.'" '.$readonly.'>
                </td>
                <td>
                    <input type="date" name="endDate[]" id="endDate'.$materialCount.'" class="select_endDate" style="height: 30px; vertical-align: middle;" value="'.$processing_list->end_date.'" '.$readonly.'>
                </td>
                <td>
                        '.$status_str.'
                </td>
                <td>
                    <input type="text" name="processMemo[]" id="processMemo'.$materialCount.'" class="processMemo" placeholder="" style="width:100%; height: 30px; vertical-align: middle;" value="'.$processing_list->memo.'" '.$readonly.'>
                </td>

            </tr>';
            $materialCount++;
        }

        // if($processing->updated_user > 0){
        //     $updated_user = User::where('id',$processing->updated_user)->first();
        // } else {
        //     $updated_user = User::where('id',$processing->created_user)->first();
        // }

        return view('stock.semi_finished_schedule.show_list', compact('processing','data','data_process'));
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
        $data = '';
        for($i = 0; $i < $total_materials; $i++){

            $material = Material::where('id',$materials['material'][$i])->first();
            // $material_warehouse = Material_warehouse::where('delete_flag','0')->where('material_id',$materials['material'][$i])->where('warehouse_id',$materials['warehouse'][$i])->first();
            // $warehouse_id = $materials['warehouse'][$i];
            $warehouse = Warehouse::find($materials['warehouse'][$i]);
            $warehouse_name = $warehouse->code;

            $data .= '<tr>

                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->fullCode.' '.$material->fullName.'</span>
                </td>
                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$material->material_unit_name->name.'</span>
                </td>
                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$warehouse_name.'</span>
                </td>

                <td>
                    <span style="width: 100px; line-height: 30px; vertical-align: middle;">'.$materials['materialAmount'][$i].'</span>
                </td>

            </tr>';

        }

        $processing_lists = Processing_list::where('delete_flag','0')->where('semi_finished_schedule_id',$id)->orderBy('orderby','ASC')->get();
        $process_functions = Process_function::orderBy('orderby', 'ASC')->get();
        $total_processings = count($processing_lists);
        $data_process = '';
        $materialCount = 0;




        foreach($processing_lists as $processing_list){
            $manufacturer = Manufacturer::where('id',$processing_list->manufacturer_id)->first();
            if($processing_list->process_function_id > 0){
                $function_name = Process_function::find($processing_list->process_function_id)->name;
            }
            $is_init_str = '';
            if($processing_list->status == 1){
                $is_init_str .= '<select name="processFunction[]" id="processFunction'.$materialCount.'" class="select_processFunction" style="width: 120px; height: 30px; vertical-align: middle;" ><option value="0"> 請選擇</option>';
                foreach($process_functions as $process_function){
                    if($processing_list->process_function_id == $process_function->id){
                        $is_init_str .= '<option value="'.$process_function->id.'" selected> '.$process_function->name.'</option>';
                    } else {
                        $is_init_str .= '<option value="'.$process_function->id.'"> '.$process_function->name.'</option>';
                    }
                }
                $is_init_str .= '</select>';

            } elseif($processing_list->status == 2 || $processing_list->status == 3) {
                $is_init_str .= '<span style="width: 100px; line-height: 30px; vertical-align: middle;"> '.$function_name.'</span>';
                $is_init_str .= '<select name="processFunction[]" id="processFunction'.$materialCount.'" class="select_processFunction" style="display:none;" ><option value="0"> 請選擇</option>';
                foreach($process_functions as $process_function){
                    if($processing_list->process_function_id == $process_function->id){
                        $is_init_str .= '<option value="'.$process_function->id.'" selected> '.$process_function->name.'</option>';
                    } else {
                        $is_init_str .= '<option value="'.$process_function->id.'"> '.$process_function->name.'</option>';
                    }
                }
                $is_init_str .= '</select>';
            }


            $status_str = '';

            if($processing_list->status == 1){
                $status_str .= '<select name="processStatus[]" id="processStatus'.$materialCount.'" class="select_pprocessStatus" style="width: 120px; height: 30px; vertical-align: middle;"><option value="1" selected> 加工中</option>
                <option value="2" > 已完成</option>
                <option value="3" > 取消</option>';
                $status_str .= '</select>';
            } elseif($processing_list->status == 2) {
                $status_str .= '<span style="width: 100px; line-height: 30px; vertical-align: middle;"> 已完成</span>';
                $status_str .= '<select name="processStatus[]" id="processStatus'.$materialCount.'" class="select_pprocessStatus" style="display:none;"><option value="1" > 加工中</option>
                <option value="2" selected> 已完成</option>
                <option value="3" > 取消</option>';
                $status_str .= '</select>';
            } elseif($processing_list->status == 3){
                $status_str .= '<span style="width: 100px; line-height: 30px; vertical-align: middle;"> 取消</span>';
                $status_str .= '<select name="processStatus[]" id="processStatus'.$materialCount.'" class="select_pprocessStatus" style="display:none;"><option value="1" > 加工中</option>
                <option value="2" > 已完成</option>
                <option value="3" selected> 取消</option>';
                $status_str .= '</select>';
            }

            $readonly = '';
            $disabled = '';
            if($processing_list->status == 2 || $processing_list->status == 3){
                $readonly = ' readonly';
                $disabled = ' disabled';
            }

            $data_process .= '<tr id="materialRow'.$materialCount.'" class="materialRow">
                <td></td>
                <td>
                    <button type="button" onclick="openSelectManufacturer('.$materialCount.');" id="manufacturerName'.$materialCount.'" name="manufacturerName'.$materialCount.'" class="btn btn-default" style="width: 100%; margin-right: 10px; overflow: hidden;color:black;font-weight: bold;" disabled> '.$manufacturer->code.' '.$manufacturer->shortName.'</button>
                    <input type="hidden" name="manufacturer[]" id="manufacturer'.$materialCount.'" class="select_manufacturer" value="'.$processing_list->manufacturer_id.'">
                </td>
                <td>
                        '.$is_init_str.'
                </td>
                <td>
                    <input type="date" name="startDate[]" id="startDate'.$materialCount.'" class="select_startDate" style="height: 30px; vertical-align: middle;" value="'.$processing_list->start_date.'" '.$readonly.'>
                </td>
                <td>
                    <input type="date" name="endDate[]" id="endDate'.$materialCount.'" class="select_endDate" style="height: 30px; vertical-align: middle;" value="'.$processing_list->end_date.'" '.$readonly.'>
                </td>
                <td>
                        '.$status_str.'
                </td>
                <td>
                    <input type="text" name="processMemo[]" id="processMemo'.$materialCount.'" class="processMemo" placeholder="" style="width:100%; height: 30px; vertical-align: middle;" value="'.$processing_list->memo.'" '.$readonly.'>
                </td>

            </tr>';
            $materialCount++;
        }





        // if($processing->updated_user > 0){
        //     $updated_user = User::where('id',$processing->updated_user)->first();
        // } else {
        //     $updated_user = User::where('id',$processing->created_user)->first();
        // }

        return view('stock.semi_finished_schedule.edit_list', compact('processing','data','data_process','materialCount'));
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
        $total_manufacturers = count($request->manufacturer);

        $manufacturer = [];
        $processFunction = [];
        $startDate = [];
        $endDate = [];
        $processStatus = [];
        $processMemo = [];


        for($i=0; $i < $total_manufacturers; $i++){
            if($request->manufacturer[$i]){
                $manufacturer[] = $request->manufacturer[$i];
                $processFunction[] = $request->processFunction[$i];
                $startDate[] = $request->startDate[$i];
                $endDate[] = $request->endDate[$i];
                $processStatus[] = $request->processStatus[$i];
                $processMemo[] = $request->processMemo[$i];
            }
        }

        $old_processings = Processing_list::where('delete_flag','0')->where('semi_finished_schedule_id',$id)->orderBy('orderby','ASC')->get();
        $final_orderby = count($old_processings);

        $processings = ['manufacturer'=>$manufacturer, 'processFunction'=>$processFunction,'startDate'=>$startDate,'endDate'=>$endDate,'processStatus'=>$processStatus,'processMemo'=>$processMemo];

        $new_processing_count = count($manufacturer);

        $new_loop_start = $final_orderby;

        if($final_orderby > 0){


            foreach($old_processings as $old_processing){
                $j = (int)($old_processing->orderby - 1);

                $old_processing->process_function_id = $processings['processFunction'][$j];
                $old_processing->start_date = $processings['startDate'][$j];
                if($processings['endDate'][$j]){
                    $old_processing->end_date = $processings['endDate'][$j];
                }
                $old_processing->status = $processings['processStatus'][$j];
                $old_processing->memo = $processings['processMemo'][$j];
                $old_processing->updated_user = session('admin_user')->id;
                $old_processing->save();
            }

            if($new_processing_count - $final_orderby > 0){
                for($k = $new_loop_start ; $k < $new_processing_count ; $k++){
                    $new_processing = new Processing_list;
                    $new_processing->semi_finished_schedule_id = $id;
                    $new_processing->orderby = $k + 1;
                    $new_processing->manufacturer_id = $processings['manufacturer'][$k];
                    $new_processing->process_function_id = $processings['processFunction'][$k];
                    $new_processing->start_date = $processings['startDate'][$k];
                    if($processings['endDate'][$k]){
                        $new_processing->end_date = $processings['endDate'][$k];
                    }
                    $new_processing->status = $processings['processStatus'][$k];
                    $new_processing->memo = $processings['processMemo'][$k];

                    $new_processing->created_user = session('admin_user')->id;
                    $new_processing->delete_flag = 0;
                    $new_processing->save();
                }

            }

        } else {
            for($i=0 ; $i < $new_processing_count ; $i++){
                $processing = new Processing_list;
                $processing->semi_finished_schedule_id = $id;
                $processing->orderby = $i + 1;
                $processing->manufacturer_id = $processings['manufacturer'][$i];
                $processing->process_function_id = $processings['processFunction'][$i];
                $processing->start_date = $processings['startDate'][$i];
                if($processings['endDate'][$i]){
                    $processing->end_date = $processings['endDate'][$i];
                }
                $processing->status = $processings['processStatus'][$i];
                $processing->memo = $processings['processMemo'][$i];

                $processing->created_user = session('admin_user')->id;
                $processing->delete_flag = 0;
                $processing->save();

            }

        }

        return redirect()->route('semi_finished_schedule.index')->with('message', '存檔成功');

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
