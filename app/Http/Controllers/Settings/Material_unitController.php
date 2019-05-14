<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Material;
use Illuminate\Http\Request;
use App\Model\Material_unit;
use App\Http\Controllers\Controller;

class Material_unitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $material_units = Material_unit::where('delete_flag','0')->orderBy('orderby', 'ASC')->get();
        return view('settings.material_unit.show',compact('material_units'));
    }

    public function update_orderby(Request $request)
    {
        $data_id = $request->data_id;
        $data_orderby = $request->data_orderby;

        if(count($data_id)>1){
            foreach($data_id as $key=>$value){
                $unit = Material_unit::find($value);
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
        return view('settings.material_unit.create');        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Material_unit::where('delete_flag','0')->where('name',$request->name)->first()){
            return redirect()->back()->with('error','單位名稱已存在 不可重覆');
        }

        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => '單位名稱 必填',
        ];
        $this->validate($request, $rules, $messages);

        if(Material_unit::where('delete_flag','0')->count() > 0){
            $final_orderby = Material_unit::where('delete_flag','0')->orderBy('orderby','DESC')->first()->orderby;
        } else {
            $final_orderby = 0;
        }

        try{
            $unit = new Material_unit;
            $unit->name = $request->name;
            $unit->orderby = $final_orderby + 1;
            $unit->created_user = session('admin_user')->id;
            $unit->delete_flag = 0;
            $unit->save();
            return redirect()->route('material_unit.index')->with('message', '新增成功');
        } catch(Exception $e) {
            return redirect()->route('material_unit.index')->with('error', '新增失敗');
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
        $unit = Material_unit::find($id);
        if($unit->updated_user > 0){
            $updated_user = User::where('id',$unit->updated_user)->first();
        } else {
            $updated_user = User::where('id',$unit->created_user)->first();
        }
        return view('settings.material_unit.edit', compact('unit','updated_user'));
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
        $unit = Material_unit::find($id);
        if($unit->name == $request->name){
            $rules = ['name' => 'required|unique:material_units'.($id ? ",id,$id" : '')];
        } else {
            if($check_id = Material_unit::where('delete_flag','0')->where('name',$request->name)->first()){
                if($check_id->id != $id){
                    return redirect()->back()->with('error','單位名稱已存在 不可重覆');
                    die;
                }
            }
        }

        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => '單位名稱 必填',
        ];
        $this->validate($request, $rules, $messages);

        try{
            $unit = Material_unit::find($id);
            $unit->name = $request->name;
            $unit->updated_user = session('admin_user')->id;                   
            $unit->save();
            return redirect()->route('material_unit.index')->with('message', '修改成功');
        } catch(Exception $e) {
            return redirect()->route('material_unit.index')->with('error', '修改成功');
            
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
            // 若物料尚有該資料 則提醒無法刪除
            $materials = Material::where('delete_flag','0')->where('unit',$id)->get();
            if(count($materials)>0){
                return redirect()->route('material_unit.index')->with('error','尚有物料使用該單位，請將其修改至其他單位後再刪除');
            } else {
                // 刪除後排序重整
                $unit = Material_unit::where('id', $id)->first();
                $unit_orderby = $unit->orderby;
                $total = Material_unit::where('delete_flag','0')->count();
                $must_change = $total - $unit_orderby;
                $i = 1;
                while($i <= $must_change){
                    $unit_orderby ++;
                    $unit_change = Material_unit::where('orderby', $unit_orderby)->first();
                    $unit_change->orderby = $unit_change->orderby -1;
                    $unit_change->save();
                    $i ++;
                }
                $unit->orderby = 0;
                $unit->delete_flag = 1;
                $unit->deleted_at = Now();
                $unit->deleted_user = session('admin_user')->id;
                $unit->save();
                return redirect()->route('material_unit.index')->with('message','刪除成功');
            }
        } catch (Exception $e) {
            return redirect()->route('material_unit.index')->with('error','刪除失敗');            
        } 
    }
}
