<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Gallery;
use App\Model\Setting;
use App\Model\Material;
use App\Model\Material_unit;
use App\Model\StorageFile;
use Illuminate\Http\Request;
use App\Model\Material_module;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialModuleRequest;

class Material_moduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Material_module::where('delete_flag','0')->get();
        return view('settings.material_module.show', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];

        $data['material_module'] = new Material_module;
        $data['materials'] = json_encode([]);
        $data['units'] = Material_unit::allJson();
        $data['files'] = StorageFile::allJson([]);

        return view('settings.material_module.create', $data);
    }

    public function duplicate($from)
    {
        $material_module = Material_module::find($from);
        $material_module->id = 0;
        $material_module->code = null;
        $data['material_module'] = $material_module;

        $data['materials'] = Material::appendMaterials($material_module->materials);
        if (!$data['materials']) {
            return '此模組內的物料已被刪除，請回上頁刪除此模組
                <button onclick="history.back()">回上頁</button>
            ';
        }
        
        $data['units'] = Material_unit::allJson();
        $data['files'] = StorageFile::allJson([]);

        $data["show"] = 0;

        return view('settings.material_module.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 先不驗證
        // $validated = $request->validated();

        $this->save(0, $request);
        return redirect()->route('material_module.index')->with('message', '新增成功');
    }

    public function show($id)
    {
        $material_module = Material_module::find($id);
        $data['material_module'] = $material_module;

        $data['materials'] = Material::appendMaterials($material_module->materials);
        if (!$data['materials']) {
            return '此模組內的物料已被刪除，請回上頁刪除此模組
                <button onclick="history.back()">回上頁</button>
            ';
        }
        
        $data['units'] = Material_unit::allJson();
        $data['files'] = StorageFile::allJson([
            $material_module->file1,
            $material_module->file2,
            $material_module->file3
        ]);

        $data["show"] = 1;

        return view('settings.material_module.edit', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $material_module = Material_module::find($id);
        $data['material_module'] = $material_module;

        $data['materials'] = Material::appendMaterials($material_module->materials);
        if (!$data['materials']) {
            return '此模組內的物料已被刪除，請回上頁刪除此模組
                <button onclick="history.back()">回上頁</button>
            ';
        }
        
        $data['units'] = Material_unit::allJson();
        $data['files'] = StorageFile::allJson([
            $material_module->file1,
            $material_module->file2,
            $material_module->file3
        ]);

        $data["show"] = 0;

        return view('settings.material_module.edit', $data);
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
        $this->save($id, $request);

        return redirect()->route('material_module.index')->with('message', '修改成功');
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
            $material_module = Material_module::find($id);

            if($material_module->file_1 > 0) {
                $file = StorageFile::find($material_module->file_1);
                if ($file) {
                    Storage::delete('public/files/' . $file->file_name);
                    Storage::delete('public/thunmbs/' . $file->file_name);
                    $file->delete();
                }
            }

            if($material_module->file_2 > 0) {
                $file = StorageFile::find($material_module->file_2);
                if ($file) {
                    Storage::delete('public/files/' . $file->file_name);
                    Storage::delete('public/thunmbs/' . $file->file_name);
                    $file->delete();
                }
            }

            if($material_module->file_3 > 0) {
                $file = StorageFile::find($material_module->file_3);
                if ($file) {
                    Storage::delete('public/files/' . $file->file_name);
                    Storage::delete('public/thunmbs/' . $file->file_name);
                    $file->delete();
                }
            }

            $material_module->delete_flag = 1;
            $material_module->deleted_at = Now();
            $material_module->deleted_user = session('admin_user')->id;
            $material_module->save();
            return redirect()->route('material_module.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('material_module.index')->with('error','刪除失敗');
        }
    }

    public function save($id, $request)
    {
        // 新增或修改
        if ($id == 0) {
            $material_module = new Material_module;

            $latest_code = Setting::where('set_key','material_module_code')->first();
            $number = (int)$latest_code->set_value + 1;
            $code = "M" . str_pad($number, 6, '0',STR_PAD_LEFT);
            $material_module->code = $code;

            // 更新最新 code
            $latest_code->set_value = $number;
            $latest_code->save();
        } else {
            $material_module = Material_module::find($id);

            $code = $material_module->code;
        }

        // 打包物料模組
        $material_module->materials =  Material::packMaterials($request);

        // 處理檔案清單
        StorageFile::packFiles($request, $material_module);

        $material_module->name = $request->name ?? "$code - 未命名的模組";
        $material_module->memo = $request->memo;

        $material_module->total_cost = Material::getTotalCost($material_module->materials);
        // $material_module->total_cost = $request->material_total_cost ?? 0;

        $material_module->total_price = Material::getTotalPrice($material_module->materials);
        $material_module->price = $request->price ?? 0;

        $material_module->status = 1;
        $material_module->created_user = session('admin_user')->id;
        $material_module->delete_flag = 0;

        $material_module->save();

        return $material_module;
    }

    public function getall(Request $request)
    {
        $ids = $request->ids ?? [];
        $material_modules = [];

        foreach ($ids as $key => $id) {
            $material_modules[] = Material_module::find($id);
        }

        return $material_modules;
    }
}
