<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;

class DepartmentController extends Controller
{

    public function index()
    {
        $deps = Department::orderBy('orderby', 'ASC')->get();

        $data = [];
        $data['deps'] = $deps;
        return view('settings.department.index', $data);
    }

    // 排序
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

    public function create()
    {
        $data = [];
        $data['dep'] = new Department;

        return view('settings.department.create', $data);
    }

    public function store(DepartmentRequest $request)
    {
        $validated = $request->validated();

        $dep = Department::create($request->all());
        $dep->created_user = session('admin_user')->id;
        $dep->orderby = Department::max_orderby() + 1;
        $dep->save();

        return redirect()->route('department.index')->with('message', '新增成功');
    }

    public function edit($id)
    {
        $data = [];
        $data['dep'] = Department::find($id);

        return view('settings.department.edit', $data);
    }

    public function update(DepartmentRequest $request, $id)
    {
        $validated = $request->validated();
        $dep = Department::find($id);
        $dep->name = $request->name;
        $dep->updated_user = session('admin_user')->id;
        $dep->save();
        return redirect()->route('department.index')->with('message', '修改成功');
    }

    public function destroy($id)
    {
        try{
            // 若員工尚有該資料 則提醒無法刪除
            $users = User::where('department_id',$id)->get();
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
                $dep->deleted_user = session('admin_user')->id;
                $dep->save();
                $dep->delete();
                return redirect()->route('department.index')->with('message','刪除成功');
            }
        } catch (Exception $e) {
            return redirect()->route('department.index')->with('error','刪除失敗');
        }
    }


}
