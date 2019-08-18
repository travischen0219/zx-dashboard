<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Material;
use Illuminate\Http\Request;
use App\Model\Material_category;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialCategoryRequest;

class Material_categoryController extends Controller
{

    public function index()
    {
        $data['material_categories'] = Material_category::orderBy('orderby', 'ASC')->get();
        return view('settings.material_category.index', $data);
    }

    public function update_orderby(Request $request)
    {
        $data_id = $request->data_id;
        $data_orderby = $request->data_orderby;

        if (count($data_id) > 1) {
            foreach ($data_id as $key => $value) {
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

    public function create()
    {
        $data = [];
        $data['cate'] = new Material_category;

        return view('settings.material_category.create', $data);
    }

    public function store(MaterialCategoryRequest $request)
    {
        $validated = $request->validated();

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

    public function edit($id)
    {
        $data = [];
        $data['cate'] = Material_category::find($id);

        return view('settings.material_category.edit', $data);
    }

    public function update(MaterialCategoryRequest $request, $id)
    {
        $validated = $request->validated();

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

    public function destroy($id)
    {
        try{
            // 若物料尚有該資料 則提醒無法刪除
            $cate = Material_category::where('id', $id)->first();
            $materials = Material::where('material_categories_code', $cate->code)->get();

            if (count($materials) > 0){
                return redirect()->route('material_category.index')->with('error', '尚有物料使用該分類資料，請將其修改至其他分類後再刪除');
            } else {
                // 刪除後排序重整
                $cate_orderby = $cate->orderby;
                $total = Material_category::count();
                $must_change = $total - $cate_orderby;
                $i = 1;
                while ($i <= $must_change){
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
                $cate->delete();
                return redirect()->route('material_category.index')->with('message', '刪除成功');
            }
        } catch (Exception $e) {
            return redirect()->route('material_category.index')->with('error', '刪除失敗');
        }
    }
}
