<?php

namespace App\Http\Controllers\Stock;

use App\Model\Buy;
use App\Model\User;
use App\Model\Material;
use App\Model\Inventory;
use App\Model\Material_unit;
use App\Model\Stock;
use Illuminate\Http\Request;
use App\Model\Inventory_list;
use App\Model\Material_warehouse;
use App\Model\Warehouse_category;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $inventories = Inventory::where('delete_flag','0')->get();
        return view('stock.inventory.show',compact('inventories','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if($request->search_lot_number){
            if($search_code == 'all'){
                $inventories = Inventory::where('delete_flag','0')->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            } else {
                $inventories = Inventory::where('delete_flag','0')->where('status',$search_code)->where('lot_number','like','%'.$request->search_lot_number.'%')->get();
            }
        } else {
            if($search_code == 'all'){
                $inventories = Inventory::where('delete_flag','0')->get();
            } else {
                $inventories = Inventory::where('delete_flag','0')->where('status',$search_code)->get();
            }
        }
        return view('stock.inventory.show',compact('inventories','search_code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inventory_no = date("Ymd")."001";
        $last_inventory_no = Inventory::orderBy('inventory_no','DESC')->first();
        if($last_inventory_no){
            if($last_inventory_no->inventory_no >= $inventory_no){
                $inventory_no = $last_inventory_no->inventory_no + 1;
            }
        }

        if(Warehouse_category::where('delete_flag','0')->count() > 0){
            $cates = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->get();
            return view('stock.inventory.create',compact('inventory_no','cates'));
        } else {
            return redirect()->route('warehouse_category.index')->with('error', '尚無倉儲分類資料，請先建立');
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $rules = [
            'lot_number' => 'required',
            'inventory_sdate' => 'date_format:"Y-m-d"|required',
            // 'inventory_edate' => 'date_format:"Y-m-d"|required',
        ];
        $messages = [
            'lot_number.required' => '批號 必填',
            'inventory_sdate.date_format' => '盤點開始日期限格式錯誤',
            'inventory_sdate.required' => '盤點開始日期 必填',
            // 'inventory_edate.date_format' => '盤點結束日期格式錯誤',
        ];
        $this->validate($request, $rules, $messages);

         try{
            $inventory = new Inventory;
            $inventory->lot_number = $request->lot_number;
            $inventory->inventory_no = $request->inventory_no;

            if($request->warehouse_category == 'all'){
                $inventory->warehouse_category = 0;
            } else {
                $inventory->warehouse_category = $request->warehouse_category;
            }
            $inventory->inventory_sdate = $request->inventory_sdate;
            $inventory->inventory_edate = $request->inventory_edate;
            $inventory->memo = $request->memo;
            $inventory->status = 1;
            $inventory->created_user = session('admin_user')->id;
            $inventory->delete_flag = 0;
            $inventory->save();

            if($request->warehouse_category == 'all'){
                $warehouse_categories = Warehouse_category::where('delete_flag','0')->where('status','1')->get();
                foreach($warehouse_categories as $warehouse_category){
                    $material_warehouses = Material_warehouse::where('delete_flag','0')->where('warehouse_category_id',$warehouse_category->id)->orderBy('warehouse_id','ASC')->get();
                    foreach($material_warehouses as $material){
                        $inventory_list = new Inventory_list;
                        $inventory_list->lot_number = $request->lot_number;
                        $inventory_list->inventory_id = $inventory->id;
                        $inventory_list->material_id = $material->material_id;
                        $inventory_list->warehouse_id = $material->warehouse_id;
                        $inventory_list->original_inventory = $material->stock;
                        $inventory_list->created_user = session('admin_user')->id;
                        $inventory_list->delete_flag = 0;
                        $inventory_list->save();
                    }
                }
            } else {
                $material_warehouses = Material_warehouse::where('delete_flag','0')->where('warehouse_category_id',$request->warehouse_category)->orderBy('warehouse_id','ASC')->get();
                foreach($material_warehouses as $material){
                    $inventory_list = new Inventory_list;
                    $inventory_list->lot_number = $request->lot_number;
                    $inventory_list->inventory_id = $inventory->id;
                    $inventory_list->material_id = $material->material_id;
                    $inventory_list->warehouse_id = $material->warehouse_id;
                    $inventory_list->original_inventory = $material->stock;
                    $inventory_list->created_user = session('admin_user')->id;
                    $inventory_list->delete_flag = 0;
                    $inventory_list->save();
                }

            }

            return redirect()->route('inventory.index')->with('message', '新增成功');
        } catch(Exception $e) {
            return redirect()->route('inventory.index')->with('error', '新增失敗');
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
        $inquiry = Inventory::find($id);
        if($inquiry->updated_user > 0){
            $updated_user = User::where('id',$inquiry->updated_user)->first();
        } else {
            $updated_user = User::where('id',$inquiry->created_user)->first();
        }
        return view('stock.inventory.show_one', compact('inquiry','updated_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inquiry = Inventory::find($id);
        if($inquiry->updated_user > 0){
            $updated_user = User::where('id',$inquiry->updated_user)->first();
        } else {
            $updated_user = User::where('id',$inquiry->created_user)->first();
        }
        return view('stock.inventory.edit', compact('inquiry','updated_user'));
    }

     public function edit_list($id)
    {
        $inventory = Inventory::find($id);
        $materials = Inventory_list::where('inventory_id',$id)->get();
        return view('stock.inventory.edit_list', compact('inventory','materials'));
    }

    public function show_list($id)
    {
        $inventory = Inventory::find($id);
        $materials = Inventory_list::where('inventory_id',$id)->get();
        return view('stock.inventory.show_list', compact('inventory','materials'));
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
        if($request->status == 2){
            if($request->inventory_edate == ''){
                return redirect()->back()->with('error', '盤點結束日期 必填');
            }
        }

        $rules = [
            // 'lot_number' => 'required',
            // 'inventory_sdate' => 'date_format:"Y-m-d"|required',
            'inventory_edate' => 'date_format:"Y-m-d"|required',
        ];
        $messages = [
            // 'lot_number.required' => '批號 必填',
            // 'inventory_sdate.date_format' => '盤點開始日期限格式錯誤',
            'inventory_edate.required' => '盤點結束日期 必填',
            'inventory_edate.date_format' => '盤點結束日期格式錯誤',
        ];
        $this->validate($request, $rules, $messages);


            try{
                $inquiry = Inventory::find($id);
                // $inquiry->lot_number = $request->lot_number;
                // $inquiry->inventory_sdate = $request->inventory_sdate;
                $inquiry->inventory_edate = $request->inventory_edate;
                $inquiry->memo = $request->memo;
                $inquiry->status = $request->status;
                $inquiry->updated_user = session('admin_user')->id;
                $inquiry->save();

                return redirect()->route('inventory.index')->with('message', '修改成功');

            } catch(Exception $e) {
                return redirect()->route('inventory.index')->with('error', '修改失敗');
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
            $inquiry = Inventory::find($id);
            $inquiry->delete_flag = 1;
            $inquiry->deleted_at = Now();
            $inquiry->deleted_user = session('admin_user')->id;
            $inquiry->save();
            return redirect()->route('inventory.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('inventory.index')->with('error','刪除失敗');
        }
    }

    public function quick_fix(Request $request)
    {
        $inventoryID = $request->inventoryID ?? 0;
        $id = $request->id ?? 0;

        if ($inventoryID == 0 || $id == 0) exit();

        // 盤點表
        $inventory = Inventory::find($inventoryID);

        // 盤點細目
        $inventory_list = Inventory_list::find($id);
        $inventory_list->quick_fix = 1;
        $inventory_list->save();

        // 物料
        $material = Material::find($inventory_list->material_id);

        // 物料單位
        $material_unit = Material_unit::find($material->unit);
        $unit = $material_unit->name;

        // 物料倉庫
        $material_warehouse = Material_warehouse::where('material_id', $inventory_list->material_id)
            ->where('warehouse_id', $inventory_list->warehouse_id)->first();

        // 建立一筆差異
        $stock = new Stock;
        $stock->lot_number = "{$inventory->inventory_no} {$inventory->lot_number} 快速修正";
        $stock->stock_option = 2;
        $stock->status = 0;
        $stock->stock_no = $material->stock_no + 1;   // 序號重物料序號+1
        $stock->material = $inventory_list->material_id;
        $stock->warehouse = $inventory_list->warehouse_id;
        $stock->total_start_quantity = $material->stock;
        $stock->start_quantity = $inventory_list->original_inventory;
        $stock->quantity = $inventory_list->physical_inventory - $inventory_list->original_inventory;
        $stock->calculate_quantity = "{$stock->total_start_quantity} $unit -> " . ($material->stock + ($inventory_list->physical_inventory - $inventory_list->original_inventory)) . " $unit";
        $stock->stock_date = date('Y-m-d');
        $stock->memo = '';
        $stock->created_user = session('admin_user')->id;
        $stock->delete_flag = 0;
        $stock->save();

        // 存回物料
        $material->stock = $material->stock + ($inventory_list->physical_inventory - $inventory_list->original_inventory);
        $material->stock_no = $material->stock_no + 1;
        $material->save();

        // 存回物料倉庫
        $material_warehouse->stock = $inventory_list->physical_inventory;
        $material_warehouse->save();

        return redirect('/stock/inventory/show_list/' . $inventoryID);
    }

}


