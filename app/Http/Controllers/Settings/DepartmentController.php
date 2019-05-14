<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deps = Department::where('delete_flag','0')->orderBy('orderby', 'ASC')->get();
        return view('settings.department.show',compact('deps'));
    }

    public function update_orderby(Request $request)
    {
        $data_id = $request->data_id;
        $data_orderby = $request->data_orderby;

        if(count($data_id)>1){
            foreach($data_id as $key=>$value){
                $dep = Department::find($value);
                $dep->orderby = $data_orderby[$key];
                $dep->updated_user = session('admin_user')->id;                                        
                $dep->save();
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
        return view('settings.department.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Department::where('delete_flag','0')->where('name',$request->name)->first()){
            return redirect()->back()->with('error','部門名稱已存在 不可重覆');
        }
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => '部門名稱 必填',
        ];
        $this->validate($request, $rules, $messages);

        if(Department::where('delete_flag','0')->count() > 0){
            $final_orderby = Department::where('delete_flag','0')->orderBy('orderby','DESC')->first()->orderby;
        } else {
            $final_orderby = 0;
        }

        try{
            $dep = new Department;
            $dep->name = $request->name;
            $dep->orderby = $final_orderby + 1;
            $dep->created_user = session('admin_user')->id;
            $dep->delete_flag = 0;
            $dep->save();
            return redirect()->route('department.index')->with('message', '新增成功');
        } catch(Exception $e) {
            return redirect()->route('department.index')->with('error', '新增失敗');
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
        $dep = Department::find($id);
        if($dep->updated_user > 0){
            $updated_user = User::where('id',$dep->updated_user)->first();
        } else {
            $updated_user = User::where('id',$dep->created_user)->first();
        }
        return view('settings.department.edit', compact('dep','updated_user'));
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
        try{
            $dep = Department::find($id);
            if($dep->name == $request->name){
                $rules = ['name' => 'required|unique:departments'.($id ? ",id,$id" : '')];
            } else {
                if($check_id = Department::where('delete_flag','0')->where('name',$request->name)->first()){
                    if($check_id->id != $id){
                        return redirect()->back()->with('error','部門名稱已存在 不可重覆');
                        die;
                    }
                }
            }

            $rules = [
                'name' => 'required',
            ];
            $messages = [
                'name.required' => '部門名稱 必填',
            ];
            $this->validate($request, $rules, $messages);

            $dep = Department::find($id);
            $dep->name = $request->name;
            $dep->updated_user = session('admin_user')->id;                             
            $dep->save();
            return redirect()->route('department.index')->with('message', '修改成功');
        } catch(Exception $e) {
            return redirect()->route('department.index')->with('error', '修改失敗');
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
            // 若員工尚有該資料 則提醒無法刪除
            $users = User::where('delete_flag','0')->where('department_id',$id)->get();
            if(count($users)>0){
                return redirect()->route('department.index')->with('error','尚有該部門員工資料，請將其修改至其他部門後再刪除');
            } else {
                // 刪除後排序重整
                $dep = Department::where('id', $id)->first();
                $dep_orderby = $dep->orderby;
                $total = Department::where('delete_flag','0')->count();
                $must_change = $total - $dep_orderby;
                $i = 1;
                while($i <= $must_change){
                    $dep_orderby ++;
                    $dep_change = Department::where('orderby', $dep_orderby)->first();
                    $dep_change->orderby = $dep_change->orderby -1;
                    $dep_change->save();
                    $i ++;
                }
                $dep->orderby = 0;
                $dep->delete_flag = 1;
                $dep->deleted_at = Now();
                $dep->deleted_user = session('admin_user')->id;
                $dep->save();
                return redirect()->route('department.index')->with('message','刪除成功');
            }
        } catch (Exception $e) {
            return redirect()->route('department.index')->with('error','刪除失敗');            
        } 
    }


}
