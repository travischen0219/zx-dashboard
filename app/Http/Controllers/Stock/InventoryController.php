<?php

namespace App\Http\Controllers\Stock;

use App\Model\Inventory;
use App\Model\InventoryRecord;
use App\Model\Material_category;
use App\Model\Material;
use App\Model\Stock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequest;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->status ?? 0;
        $data = [];

        $data['inventories'] = Inventory::orderBy('status', 'asc')->orderBy('id', 'desc');

        if ($status > 0) $data['inventories'] = $data['inventories']->where('status', $status);
        $data['inventories'] = $data['inventories']->get();

        $data['categories'] = Material_category::allWithCode();
        $data['statuses'] = Inventory::statuses();
        $data['status'] = $status;

        return view('stock.inventory.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['function'] = 'inventory';
        $data['title'] = '盤點 - 新增盤點';

        $inventory = new Inventory;

        $data['inventory'] = $inventory;
        $data['categories'] = Material_category::allWithID();

        return view('stock.inventory.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InventoryRequest $request)
    {
        $validated = $request->validated();

        $this->save(0, $request);
        return redirect('/stock/inventory')->with('message', '新增成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventory $inventory)
    {
        $data = [];
        $data['function'] = 'inventory';
        $data['title'] = '盤點 - 修改盤點';

        $data['inventory'] = $inventory;
        $data['categories'] = Material_category::allWithID();
        $data['statuses'] = Inventory::statuses();

        return view('stock.inventory.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(InventoryRequest $request, Inventory $inventory)
    {
        $validated = $request->validated();

        $this->save($inventory->id, $request);
        return redirect('/stock/inventory')->with('message', '修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        $inventoryRecords = InventoryRecord::where('inventory_id', $inventory->id);
        $inventoryRecords->update(['deleted_user' => session('admin_user')->id]);
        $inventoryRecords->delete();

        $inventory->deleted_user = session('admin_user')->id;
        $inventory->save();
        $inventory->delete();

        return redirect(route('inventory.index'));
    }

    public function save($id, $request)
    {
        // 新增或修改
        if ($id == 0) {
            $inventory = new Inventory;

            $code = date("Ymd") . "001";
            $last_code = Inventory::orderBy('code', 'DESC')->first();
            if ($last_code) {
                if ($last_code->code >= $code) {
                    $code = $last_code->code + 1;
                }
            }
            $inventory->code = $code;
            $inventory->status = 1;
        } else {
            $inventory = Inventory::find($id);
            $inventory->status = $request->status;
        }

        $inventory->name = $request->name ?? '';
        $inventory->category_id = $request->category_id ?? 0;
        $inventory->start_date = $request->start_date ?? null;
        $inventory->end_date = $request->end_date ?? null;
        $inventory->memo = $request->memo ?? '';

        $inventory->save();

        // 新增盤點後，新增一張盤點清單
        if ($id == 0) {
            $materials = Material::where('delete_flag','0');

            if ($inventory->category_id > 0) {
                $category = Material_category::find($inventory->category_id);
                $materials = $materials->where('material_categories_code', $category->code);
            }

            $materials = $materials->orderBy('fullCode', 'asc')->get();

            foreach ($materials as $material_id) {
                $inventoryRecord = new InventoryRecord;
                $inventoryRecord->inventory_id = $inventory->id;
                $inventoryRecord->material_id = $material_id->id;
                $inventoryRecord->original_inventory = null;
                $inventoryRecord->physical_inventory = null;
                $inventoryRecord->quick_fix = 0;
                $inventoryRecord->created_user = session('admin_user')->id;
                $inventoryRecord->save();
            }
        }

        return $inventory;
    }

    public function check(Request $request)
    {
        $id = $request->id ?? 0;
        if ($id == 0) abort(404);

        $inventory = Inventory::find($id);
        if ($inventory->status != 1) abort(404);
        $inventoryRecords = InventoryRecord::where('inventory_id', $inventory->id)->orderBy('id', 'asc')->get();

        $data = [];
        $data['inventory'] = $inventory;
        $data['inventoryRecords'] = $inventoryRecords;

        return view('stock.inventory.check', $data);
    }

    public function record(Request $request)
    {
        $id = $request->id ?? 0;
        $original_inventory = $request->original_inventory ?? 0;
        $physical_inventory = $request->physical_inventory ?? 0;
        if ($id == 0) abort(404);

        $inventoryRecord = inventoryRecord::find($id);
        $inventoryRecord->original_inventory = $original_inventory;
        $inventoryRecord->physical_inventory = $physical_inventory;
        $inventoryRecord->save();
        $inventoryRecord->diff = number_format($physical_inventory - $original_inventory, 2);

        return $inventoryRecord;
    }

    public function view(Request $request)
    {
        $id = $request->id ?? 0;
        if ($id == 0) abort(404);

        $inventory = Inventory::find($id);
        if ($inventory->status != 2) abort(404);
        $inventoryRecords = InventoryRecord::where('inventory_id', $inventory->id)->orderBy('id', 'asc')->get();

        $data = [];
        $data['inventory'] = $inventory;
        $data['inventoryRecords'] = $inventoryRecords;

        return view('stock.inventory.view', $data);
    }

    public function fix(Request $request)
    {
        $id = $request->id ?? 0;
        if ($id == 0) abort(404);

        // 盤點細目
        $inventoryRecord = InventoryRecord::find($id);
        $inventoryRecord->quick_fix = 1;
        $inventoryRecord->save();

        // 盤點表
        $inventory = Inventory::find($inventoryRecord->inventory_id);

        // 建立一筆差異
        $stock = new Stock;
        $stock->inventory_id = $inventory->id;
        $stock->type = 10;
        $stock->stock_date = date('Y-m-d');
        $stock->material_id = $inventoryRecord->material_id;
        $stock->amount = $inventoryRecord->physical_inventory - $inventoryRecord->original_inventory;
        $stock->amount_before = $inventoryRecord->physical_inventory;
        $stock->amount_after = $inventoryRecord->original_inventory;
        $stock->memo = "INV" . $inventory->code . " 快速修正";
        $stock->save();

        // 存回物料
        $inventoryRecord->material->stock = $inventoryRecord->physical_inventory;
        $inventoryRecord->material->save();

        return redirect("/stock/inventory/{$inventory->id}/view");
    }
}
