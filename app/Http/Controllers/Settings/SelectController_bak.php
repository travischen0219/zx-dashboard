<?php

namespace App\Http\Controllers\Settings;

use App\Model\Stock;
use App\Model\Material;
use App\Model\Warehouse;
use Illuminate\Http\Request;
use App\Model\Material_warehouse;
use App\Model\Warehouse_category;
use App\Http\Controllers\Controller;

class SelectController extends Controller
{
    // 物料管理的倉儲選擇
    public function selectWarehouse(Request $request)
    {
        if(Warehouse_category::where('delete_flag','0')->count() > 0){
            $cate_first = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->first();
            $search_code = $cate_first->id;
            $cates = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->get();  
            $warehouses = Warehouse::where('delete_flag','0')->where('category',$cate_first->id)->orderBy('code','ASC')->get();
            return view('settings.selectWarehouse',compact('warehouses','search_code','cates'));
        } else {
            return redirect()->route('warehouse_category.index')->with('error', '尚無倉儲分類資料，請先建立');                        
        }
    }

    public function search_warehouse(Request $request)
    {
        $cates = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->get();       
        
        $search_like = $request->search_codeOrName;
        $search_code = $request->search_category;
        $warehouses = Warehouse::where(function($query) use ($search_code,$search_like) {
                                    $query->where('delete_flag','0')
                                        ->where('category',$search_code)
                                        ->where('code','like','%'.$search_like.'%');
                                })
                                ->orWhere(function($query) use ($search_code,$search_like) {
                                    $query->where('delete_flag','0')
                                        ->where('category',$search_code)
                                        ->where('fullName','like','%'.$search_like.'%');
                                })
                                ->orderBy('code','ASC')
                                ->get();
        return view('settings.selectWarehouse',compact('warehouses','search_code','cates'));
    }

    public function show_stock(Request $request)
    {
        $stocks = Stock::where('material',$request->id)->where('status', '0')->orderBy('stock_no','DESC')->get();
        $material = Material::find($request->id);
        $title = $material->fullCode.' / '.$material->fullName.' / '.$material->material_unit_name->name.' / '.$material->size;
        return view('settings.material.show_stock',compact('stocks','title'));
    }

    // 入庫的倉儲選擇
    public function selectWarehouse_stock($id){
        
        $material = Material::find($id);
        $material_warehouses = Material_warehouse::where('delete_flag','0')->where('material_id',$id)->get();

        if(Warehouse_category::where('delete_flag','0')->count() > 0){
            $cate_first = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->first();
            $search_code = $cate_first->id;
            $cates = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->get();  
            $warehouses = Warehouse::where('delete_flag','0')->where('category',$cate_first->id)->orderBy('code','ASC')->get();
            return view('purchase.stock.selectWarehouse_stock',compact('warehouses','search_code','cates','material_warehouses','id'));
        } else {
            return redirect()->route('warehouse_category.index')->with('error', '尚無倉儲分類資料，請先建立');                        
        }
    }
    // 入庫的倉儲選擇搜尋
    public function search_warehouse_stock(Request $request)
    {
        $id = $request->id;
        $material = Material::find($id);
        $material_warehouses = Material_warehouse::where('delete_flag','0')->where('material_id',$id)->get();

        $cates = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->get();       
        $search_like = $request->search_codeOrName;
        $search_code = $request->search_category;
        $warehouses = Warehouse::where(function($query) use ($search_code,$search_like) {
                                    $query->where('delete_flag','0')
                                        ->where('category',$search_code)
                                        ->where('code','like','%'.$search_like.'%');
                                })
                                ->orWhere(function($query) use ($search_code,$search_like) {
                                    $query->where('delete_flag','0')
                                        ->where('category',$search_code)
                                        ->where('fullName','like','%'.$search_like.'%');
                                })
                                ->orderBy('code','ASC')
                                ->get();
        return view('purchase.stock.selectWarehouse_stock',compact('warehouses','search_code','cates','material_warehouses','id'));
    }


    // 差異處理的倉儲選擇
    public function selectWarehouse_byMaterial($id){
        $material_warehouses = Material_warehouse::where('delete_flag','0')->where('material_id',$id)->get();
        $array = [];
        foreach($material_warehouses as $material_warehouse){
            $array[] = $material_warehouse->warehouse_id;           
        }
        $warehouses = Warehouse::where('delete_flag','0')->whereIn('id',$array)->orderBy('code','ASC')->get();
        return view('stock.adjustment.selectWarehouse_byMaterial',compact('warehouses','material_warehouses'));
    }

    // 調撥的新倉儲選擇
    public function selectNewWarehouse_stock($id){
        
        $material = Material::find($id);
        $material_warehouses = Material_warehouse::where('delete_flag','0')->where('material_id',$id)->get();

        if(Warehouse_category::where('delete_flag','0')->count() > 0){
            $cate_first = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->first();
            $search_code = $cate_first->id;
            $cates = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->get();  
            $warehouses = Warehouse::where('delete_flag','0')->where('category',$cate_first->id)->orderBy('code','ASC')->get();
            return view('stock.transfer_inventory.select_new_warehouse',compact('warehouses','search_code','cates','material_warehouses','id'));
        } else {
            return redirect()->route('warehouse_category.index')->with('error', '尚無倉儲分類資料，請先建立');                        
        }
    }
    // 調撥的新倉儲選擇搜尋
    public function search_new_warehouse_stock(Request $request)
    {
        $id = $request->id;
        $material = Material::find($id);
        $material_warehouses = Material_warehouse::where('delete_flag','0')->where('material_id',$id)->get();

        $cates = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->get();       
        $search_like = $request->search_codeOrName;
        $search_code = $request->search_category;
        $warehouses = Warehouse::where(function($query) use ($search_code,$search_like) {
                                    $query->where('delete_flag','0')
                                        ->where('category',$search_code)
                                        ->where('code','like','%'.$search_like.'%');
                                })
                                ->orWhere(function($query) use ($search_code,$search_like) {
                                    $query->where('delete_flag','0')
                                        ->where('category',$search_code)
                                        ->where('fullName','like','%'.$search_like.'%');
                                })
                                ->orderBy('code','ASC')
                                ->get();
        return view('stock.transfer_inventory.select_new_warehouse',compact('warehouses','search_code','cates','material_warehouses','id'));
    }

}
