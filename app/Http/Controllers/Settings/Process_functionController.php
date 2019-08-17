<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use Illuminate\Http\Request;
use App\Model\Process_function;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessFunctionRequest;

class Process_functionController extends Controller
{

    public function index()
    {
        $process_functions = Process_function::orderBy('orderby', 'ASC')->get();

        $data = [];
        $data['process_functions'] = $process_functions;

        return view('settings.process_function.index', $data);
    }

    public function update_orderby(Request $request)
    {
        $data_id = $request->data_id;
        $data_orderby = $request->data_orderby;

        if (count($data_id) > 1) {
            foreach ($data_id as $key=>$value) {
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

    public function create()
    {
        $data = [];
        $data['unit'] = new Process_function;

        return view('settings.process_function.create', $data);
    }

    public function store(ProcessFunctionRequest $request)
    {
        $validated = $request->validated();

        $unit = Process_function::create($request->all());
        $unit->created_user = session('admin_user')->id;
        $unit->orderby = Process_function::max_orderby() + 1;
        $unit->save();

        return redirect()->route('process_function.index')->with('message', '新增成功');
    }

    public function edit($id)
    {
        $data = [];
        $data['unit'] = Process_function::find($id);

        return view('settings.process_function.edit', $data);
    }

    public function update(ProcessFunctionRequest $request, $id)
    {
        $validated = $request->validated();

        $unit = Process_function::find($id);
        $unit->name = $request->name;
        $unit->updated_user = session('admin_user')->id;
        $unit->save();
        return redirect()->route('process_function.index')->with('message', '修改成功');
    }

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
            $unit->delete();
            return redirect()->route('process_function.index')->with('message','刪除成功');

        } catch (Exception $e) {
            return redirect()->route('process_function.index')->with('error','刪除失敗');
        }
    }
}
