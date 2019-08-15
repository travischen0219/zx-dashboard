<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use Illuminate\Http\Request;
use App\Model\Professional_title;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfessionalTitleRequest;

class Professional_titleController extends Controller
{

    public function index()
    {
        $pro_titles = Professional_title::orderBy('orderby', 'ASC')->get();

        $data = [];
        $data['pro_titles'] = $pro_titles;

        return view('settings.professional_title.index', $data);
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
        $data = [];
        $data['pro_title'] = new Professional_title;

        return view('settings.professional_title.create', $data);
    }

    public function store(ProfessionalTitleRequest $request)
    {
        $validated = $request->validated();

        $pro_title = Professional_title::create($request->all());
        $pro_title->created_user = session('admin_user')->id;
        $pro_title->orderby = Professional_title::max_orderby() + 1;
        $pro_title->save();

        return redirect()->route('professional_title.index')->with('message', '新增成功');
    }

    public function edit($id)
    {
        $data = [];
        $data['pro_title'] = Professional_title::find($id);

        return view('settings.professional_title.edit', $data);
    }

    public function update(ProfessionalTitleRequest $request, $id)
    {
        $validated = $request->validated();
        $pro_title = Professional_title::find($id);
        $pro_title->name = $request->name;
        $pro_title->updated_user = session('admin_user')->id;
        $pro_title->save();
        return redirect()->route('professional_title.index')->with('message', '修改成功');
    }

    public function destroy($id)
    {
        try{
            // 若員工尚有該資料 則提醒無法刪除
            $users = User::where('professional_title_id', $id)->get();
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
                $title->delete();

                return redirect()->route('professional_title.index')->with('message','刪除成功');
            }
        } catch (Exception $e) {
            return redirect()->route('department.index')->with('error','刪除失敗');
        }
    }
}
