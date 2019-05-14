<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use Illuminate\Http\Request;
use App\Model\Professional_title;
use App\Http\Controllers\Controller;

class Professional_titleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pro_titles = Professional_title::where('delete_flag','0')->orderBy('orderby', 'ASC')->get();
        return view('settings.professional_title.show',compact('pro_titles'));
    }

    public function update_orderby(Request $request)
    {
        $data_id = $request->data_id;
        $data_orderby = $request->data_orderby;

        if(count($data_id)>1){
            foreach($data_id as $key=>$value){
                $pro_title = Professional_title::find($value);
                $pro_title->orderby = $data_orderby[$key];
                $pro_title->updated_user = session('admin_user')->id;                                                        
                $pro_title->save();
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
        return view('settings.professional_title.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Professional_title::where('delete_flag','0')->where('name',$request->name)->first()){
            return redirect()->back()->with('error','職稱已存在 不可重覆');
        }
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => '職稱 必填',
        ];
        $this->validate($request, $rules, $messages);

        if(Professional_title::where('delete_flag','0')->count() > 0){
            $final_orderby = Professional_title::where('delete_flag','0')->orderBy('orderby','DESC')->first()->orderby;
        } else {
            $final_orderby = 0;
        }

        $pro_title = new Professional_title;
        $pro_title->name = $request->name;
        $pro_title->orderby = $final_orderby + 1;
        $pro_title->created_user = session('admin_user')->id;
        $pro_title->delete_flag = 0;
        $pro_title->save();
        return redirect()->route('professional_title.index');
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
        $pro_title = Professional_title::find($id);
        if($pro_title->updated_user > 0){
            $updated_user = User::where('id',$pro_title->updated_user)->first();
        } else {
            $updated_user = User::where('id',$pro_title->created_user)->first();
        }
        return view('settings.professional_title.edit', compact('pro_title','updated_user'));
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
            $pro_title = Professional_title::find($id);
            if($pro_title->name == $request->name){
                $rules = ['name' => 'required|unique:professional_titles'.($id ? ",id,$id" : '')];
            } else {
                if($check_id = Professional_title::where('delete_flag','0')->where('name',$request->name)->first()){
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
                'name.required' => '職稱 必填',
            ];
            $this->validate($request, $rules, $messages);

            $pro_title = Professional_title::find($id);
            $pro_title->name = $request->name;
            $pro_title->updated_user = session('admin_user')->id;                                     
            $pro_title->save();
            return redirect()->route('professional_title.index')->with('message', '修改成功');
        } catch(Exception $e) {
            return redirect()->route('professional_title.index')->with('error', '修改失敗');
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
            $users = User::where('delete_flag','0')->where('professional_title_id',$id)->get();
            if(count($users)>0){
                return redirect()->route('professional_title.index')->with('error','尚有該職稱員工資料，請將其修改至其他職稱後再刪除');
            } else {
                // 刪除後排序重整
                $title = professional_title::where('id', $id)->first();
                $title_orderby = $title->orderby;
                $total = professional_title::where('delete_flag','0')->count();
                $must_change = $total - $title_orderby;
                $i = 1;
                while($i <= $must_change){
                    $title_orderby ++;
                    $title_change = professional_title::where('orderby', $title_orderby)->first();
                    $title_change->orderby = $title_change->orderby -1;
                    $title_change->save();
                    $i ++;
                }
                $title->orderby = 0;
                $title->delete_flag = 1;
                $title->deleted_at = Now();
                $title->deleted_user = session('admin_user')->id;
                $title->save();
                return redirect()->route('professional_title.index')->with('message','刪除成功');
            }
        } catch (Exception $e) {
            return redirect()->route('department.index')->with('error','刪除失敗');            
        } 
    }
}
