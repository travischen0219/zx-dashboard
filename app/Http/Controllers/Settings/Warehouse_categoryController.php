<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Warehouse;
use Illuminate\Http\Request;
use App\Model\Warehouse_category;
use App\Http\Controllers\Controller;

class Warehouse_categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cates = Warehouse_category::where('delete_flag','0')->orderBy('orderby','ASC')->get();
        return view('settings.warehouse_category.show',compact('cates'));
    }

    public function update_orderby(Request $request)
    {
        $data_id = $request->data_id;
        $data_orderby = $request->data_orderby;

        if(count($data_id)>1){
            foreach($data_id as $key=>$value){
                $cate = Warehouse_category::find($value);
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
        return view('settings.warehouse_category.create');        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Warehouse_category::where('delete_flag','0')->where('name',$request->name)->first()){
            return redirect()->back()->with('error','分類名稱已存在 不可重覆');
        }

        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => '分類名稱 必填',
        ];
        $this->validate($request, $rules, $messages);

        if(Warehouse_category::where('delete_flag','0')->count() > 0){
            $final_orderby = Warehouse_category::where('delete_flag','0')->orderBy('orderby','DESC')->first()->orderby;
        } else {
            $final_orderby = 0;
        }

        try{
            $cate = new Warehouse_category;
            $cate->name = $request->name;
            $cate->orderby = $final_orderby + 1;
            $cate->status = $request->status;
            $cate->created_user = session('admin_user')->id;
            $cate->delete_flag = 0;
            $cate->save();
            return redirect()->route('warehouse_category.index')->with('message', '新增成功');
        } catch(Exception $e) {
            return redirect()->route('warehouse_category.index')->with('error', '新增失敗'); 
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
        $warehouse_category = Warehouse_category::find($id);
        if($warehouse_category->updated_user > 0){
            $updated_user = User::where('id',$warehouse_category->updated_user)->first();
        } else {
            $updated_user = User::where('id',$warehouse_category->created_user)->first();
        }
        return view('settings.warehouse_category.edit', compact('warehouse_category','updated_user'));
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
        $cate = Warehouse_category::find($id);
        if($cate->name == $request->name){
            $rules = ['name' => 'required|unique:warehouse_categories'.($id ? ",id,$id" : '')];
        } else {
            if($check_id = Warehouse_category::where('delete_flag','0')->where('name',$request->name)->first()){
                if($check_id->id != $id){
                    return redirect()->back()->with('error','分類名稱已存在 不可重覆');
                    die;
                }
            }
        }

        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => '分類名稱 不可為空',
        ];
        $this->validate($request, $rules, $messages);
   
        if($request->status == 2){
            // 若倉儲尚有該資料 則提醒無法將狀態關閉
            $warehouses = Warehouse::where('category',$id)->get();
            if(count($warehouses)>0){
                return redirect()->back()->with('error','尚有該分類的倉儲資料，請將其修改至其他分類後再關閉');
            }
        }
        try{
            $cate->name = $request->name;
            $cate->status = $request->status;
            $cate->updated_user = session('admin_user')->id;
            $cate->save();
            return redirect()->route('warehouse_category.index')->with('message', '修改成功');
        } catch(Exception $e) {
            return redirect()->route('warehouse_category.index')->with('error', '修改失敗'); 
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
            // 若倉儲尚有該資料 則提醒無法刪除
            $warehouses = Warehouse::where('delete_flag', '0')->where('category',$id)->get();
            if(count($warehouses)>0){
                return redirect()->route('warehouse_category.index')->with('error','尚有該分類的倉儲資料，請將其修改至其他分類後再刪除');
            } else {
                // 刪除後排序重整
                $cate = Warehouse_category::where('id', $id)->first();
                $cate_orderby = $cate->orderby;
                $total = Warehouse_category::where('delete_flag', '0')->count();
                $must_change = $total - $cate_orderby;
                $i = 1;
                while($i <= $must_change){
                    $cate_orderby ++;
                    $cate_change = Warehouse_category::where('orderby', $cate_orderby)->first();
                    $cate_change->orderby = $cate_change->orderby -1;
                    $cate_change->save();
                    $i ++;
                }
                $cate->orderby = 0;
                $cate->status = 2;
                $cate->delete_flag = 1;
                $cate->deleted_at = Now();
                $cate->deleted_user = session('admin_user')->id;
                $cate->save();
                return redirect()->route('warehouse_category.index')->with('message','刪除成功');
            }
        } catch (Exception $e) {
            return redirect()->route('warehouse_category.index')->with('error','刪除失敗');            
        } 

    }
}
