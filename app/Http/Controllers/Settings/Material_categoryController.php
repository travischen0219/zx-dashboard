<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Material;
use Illuminate\Http\Request;
use App\Model\Material_category;
use App\Http\Controllers\Controller;

class Material_categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $material_categories = Material_category::where('delete_flag','0')->orderBy('orderby', 'ASC')->get();
        return view('settings.material_category.show',compact('material_categories'));
    }

    public function update_orderby(Request $request)
    {
        $data_id = $request->data_id;
        $data_orderby = $request->data_orderby;

        if(count($data_id)>1){
            foreach($data_id as $key=>$value){
                $cate = Material_category::find($value);
                $cate->orderby = $data_orderby[$key];
                $cate->updated_user = session('admin_user')->id;
                $cate->save();
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
        return view('settings.material_category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(Material_category::where('delete_flag','0')->where('code',$request->code)->first()){
            return redirect()->back()->with('error','分類代號已存在 不可重覆');
        }

        $rules = [
            'code' => 'required',
            'name' => 'required',
        ];
        $messages = [
            'code.required' => '分類代號 不可為空',
            'name.required' => '分類名稱 不可為空',
        ];
        $this->validate($request, $rules, $messages);

        if(Material_category::where('delete_flag','0')->count() > 0){
            $final_orderby = Material_category::where('delete_flag','0')->orderBy('orderby','DESC')->first()->orderby;
        } else {
            $final_orderby = 0;
        }

        try{
            $cate = new Material_category;
            $cate->code = $request->code;
            $cate->name = $request->name;
            $cate->cal = $request->cal;
            $cate->orderby = $final_orderby + 1;
            $cate->created_user = session('admin_user')->id;
            $cate->delete_flag = 0;
            $cate->save();
            return redirect()->route('material_category.index')->with('message', '新增成功');
        } catch(Exception $e) {
            return redirect()->route('material_category.index')->with('error', '新增失敗');

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
        $cate = Material_category::find($id);
        if($cate->updated_user > 0){
            $updated_user = User::where('id',$cate->updated_user)->first();
        } else {
            $updated_user = User::where('id',$cate->created_user)->first();
        }
        return view('settings.material_category.edit', compact('cate','updated_user'));
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
        // $cate = Material_category::find($id);
        // if($cate->code == $request->code){
        //     $rules = ['code' => 'required|unique:material_categories'.($id ? ",id,$id" : '')];
        // } else {
        //     if($check_id = Material_category::where('code',$request->code)->first()){
        //         if($check_id->id != $id){
        //             return redirect()->back()->with('error','分類代號 重覆');
        //             die;
        //         }
        //     }
        // }
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => '分類名稱 必填',
        ];
        $this->validate($request, $rules, $messages);

        try{
            $cate = Material_category::find($id);
            $cate->name = $request->name;
            $cate->cal = $request->cal;
            $cate->updated_user = session('admin_user')->id;
            $cate->save();
            return redirect()->route('material_category.index')->with('message', '修改成功');
        } catch(Exception $e) {
            return redirect()->route('material_category.index')->with('error', '修改失敗');

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
            $cate = Material_category::where('id', $id)->first();
            $materials = Material::where('delete_flag','0')->where('material_categories_code',$cate->code)->get();
            if(count($materials)>0){
                return redirect()->route('material_category.index')->with('error','尚有物料使用該分類資料，請將其修改至其他分類後再刪除');
            } else {
                // 刪除後排序重整
                $cate_orderby = $cate->orderby;
                $total = Material_category::where('delete_flag','0')->count();
                $must_change = $total - $cate_orderby;
                $i = 1;
                while($i <= $must_change){
                    $cate_orderby ++;
                    $cate_change = Material_category::where('orderby', $cate_orderby)->first();
                    $cate_change->orderby = $cate_change->orderby -1;
                    $cate_change->save();
                    $i ++;
                }
                $cate->orderby = 0;
                $cate->delete_flag = 1;
                $cate->deleted_at = Now();
                $cate->deleted_user = session('admin_user')->id;
                $cate->save();
                return redirect()->route('material_category.index')->with('message','刪除成功');
            }
        } catch (Exception $e) {
            return redirect()->route('material_category.index')->with('error','刪除失敗');
        }
    }
}
