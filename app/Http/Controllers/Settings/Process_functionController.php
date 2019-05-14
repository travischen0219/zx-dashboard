<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use Illuminate\Http\Request;
use App\Model\Process_function;
use App\Http\Controllers\Controller;

class Process_functionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $process_functions = Process_function::where('delete_flag','0')->orderBy('orderby', 'ASC')->get();
        return view('settings.process_function.show',compact('process_functions'));
    }

    public function update_orderby(Request $request)
    {
        $data_id = $request->data_id;
        $data_orderby = $request->data_orderby;

        if(count($data_id)>1){
            foreach($data_id as $key=>$value){
                $unit = Process_function::find($value);
                $unit->orderby = $data_orderby[$key];
                $unit->updated_user = session('admin_user')->id;                                                         
                $unit->save();
            }
            return "success";
        } else {
            // 無需排序時
            return "error_1";
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.process_function.create');                
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Process_function::where('delete_flag','0')->where('name',$request->name)->first()){
            return redirect()->back()->with('error','加工名稱已存在 不可重覆');
        }

        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => '加工名稱 必填',
        ];
        $this->validate($request, $rules, $messages);

        if(Process_function::where('delete_flag','0')->count() > 0){
            $final_orderby = Process_function::where('delete_flag','0')->orderBy('orderby','DESC')->first()->orderby;
        } else {
            $final_orderby = 0;
        }

        try{
            $unit = new Process_function;
            $unit->name = $request->name;
            $unit->orderby = $final_orderby + 1;
            $unit->created_user = session('admin_user')->id;
            $unit->delete_flag = 0;
            $unit->save();
            return redirect()->route('process_function.index')->with('message', '新增成功');
        } catch(Exception $e) {
            return redirect()->route('process_function.index')->with('error', '新增失敗');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unit = Process_function::find($id);
        if($unit->updated_user > 0){
            $updated_user = User::where('id',$unit->updated_user)->first();
        } else {
            $updated_user = User::where('id',$unit->created_user)->first();
        }
        return view('settings.process_function.edit', compact('unit','updated_user'));
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
        $unit = Process_function::find($id);
        if($unit->name == $request->name){
            $rules = ['name' => 'required|unique:material_units'.($id ? ",id,$id" : '')];
        } else {
            if($check_id = Process_function::where('delete_flag','0')->where('name',$request->name)->first()){
                if($check_id->id != $id){
                    return redirect()->back()->with('error','加工名稱已存在 不可重覆');
                    die;
                }
            }
        }

        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => '加工名稱 必填',
        ];
        $this->validate($request, $rules, $messages);

        try{
            $unit = Process_function::find($id);
            $unit->name = $request->name;
            $unit->updated_user = session('admin_user')->id;                   
            $unit->save();
            return redirect()->route('process_function.index')->with('message', '修改成功');
        } catch(Exception $e) {
            return redirect()->route('process_function.index')->with('error', '修改失敗');
            
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
            // 刪除後排序重整
            $unit = Process_function::where('id', $id)->first();
            $unit_orderby = $unit->orderby;
            $total = Process_function::where('delete_flag','0')->count();
            $must_change = $total - $unit_orderby;
            $i = 1;
            while($i <= $must_change){
                $unit_orderby ++;
                $unit_change = Process_function::where('orderby', $unit_orderby)->first();
                $unit_change->orderby = $unit_change->orderby -1;
                $unit_change->save();
                $i ++;
            }
            $unit->orderby = 0;
            $unit->delete_flag = 1;
            $unit->deleted_at = Now();
            $unit->deleted_user = session('admin_user')->id;
            $unit->save();
            return redirect()->route('process_function.index')->with('message','刪除成功');
            
        } catch (Exception $e) {
            return redirect()->route('process_function.index')->with('error','刪除失敗');            
        } 
    }
}
