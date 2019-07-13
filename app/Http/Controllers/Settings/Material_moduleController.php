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
use Illuminate\Support\Facades\Storage;

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

        $data['materials'] = Material_module::appendMaterials($material_module->materials);
        $data['units'] = Material_unit::allJson();
        $data['files'] = StorageFile::allJson([
            $material_module->file1,
            $material_module->file2,
            $material_module->file3
        ]);

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

        // 打包物料模組 (不存單位、不存是否有計價)
        $materials = [];
        if (isset($request->material)) {
            for($i = 0; $i < count($request->material); $i++) {
                $materials[] = [
                    'id' => $request->material[$i],
                    'amount' => $request->material_amount[$i],
                    'cost' => $request->material_cost[$i],
                    'price' => $request->material_price[$i],

                    // 計價分類才有
                    'cal_amount' => $request->material_cal_amount[$i] ?? 0,
                    'cal_price' => $request->material_cal_price[$i] ?? 0,
                    'buy_amount' => $request->material_buy_amount[$i] ?? 0
                ];
            }
        }
        $material_module->materials = serialize($materials);

        // 處理檔案清單
        for ($i = 0; $i <= 2; $i++) {
            $col = 'file_' . ($i + 1);
            $file_input = 'file_file_' . $i;
            if(isset($request->file_will_delete[$i]) && $request->file_will_delete[$i] == 1) {
                // 刪除檔案
                $file = StorageFile::find($material_module->$col);

                if ($file) {
                    Storage::delete('public/files/' . $file->file_name);
                    Storage::delete('public/thunmbs/' . $file->file_name);
                    $file->delete();
                }
            } elseif ($request->hasFile($file_input)) {
                // 覆蓋檔案
                $file_id = StorageFile::upload($request->$file_input, $request->file_title[$i]);
                $material_module->$col = $file_id;
            } else {
                // 儲存檔案標題
                if ($material_module->$col) {
                    StorageFile::updateTitle($material_module->$col, $request->file_title[$i]);
                }
            }
        }

        $material_module->name = $request->name ?? "$code - 未命名的模組";
        $material_module->memo = $request->memo;

        $material_module->total_cost = $request->total_cost;
        $material_module->total_price = $request->total_price;

        $material_module->status = 1;
        $material_module->created_user = session('admin_user')->id;
        $material_module->delete_flag = 0;

        $material_module->save();

        return $material_module;
    }
}
