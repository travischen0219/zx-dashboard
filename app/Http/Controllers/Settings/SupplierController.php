<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Setting;
use App\Model\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{

    public function index(Request $request)
    {
        $search_tag = $request->search_tag ?? null;
        $search_code = $request->search_category ?? 'all';

        $suppliers = Supplier::orderBy('id');

        if ($search_code == 'none') {
            $suppliers = Supplier::where('category', null);
        } elseif ($search_code != 'all') {
            $suppliers = Supplier::where('category', $search_code);
        }

        if ($search_tag != 'all') {
            if ($search_tag == 'supplier') {
                $suppliers = Supplier::where('supplier', 1);
            } elseif ($search_tag == 'manufacturer') {
                $suppliers = Supplier::where('manufacturer', 1);
            }
        }

        $suppliers = $suppliers->get();

        $data = [];
        $data['suppliers'] = $suppliers;
        $data['search_tag'] = $search_tag;
        $data['search_code'] = $search_code;

        return view('settings.supplier.index', $data);
    }

    public function create()
    {
        $data['supplier'] = new Supplier;
        $data['supplier']->supplier = 1;
        $data['supplier']->status = 1;

        return view('settings.supplier.create', $data);
    }

    public function store(SupplierRequest $request)
    {
        $validated = $request->validated();
        $this->save(0, $request);
        return redirect(route('supplier.index'))->with('message', '新增成功');
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);

        $data = [];
        $data['supplier'] = $supplier;
        $data["show"] = 1;

        return view('settings.supplier.edit', $data);
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);

        $data = [];
        $data['supplier'] = $supplier;
        $data['show'] = 0;
        return view('settings.supplier.edit', $data);
    }

    public function update(SupplierRequest $request, $id)
    {
        $validated = $request->validated();
        $this->save($id, $request);
        return redirect(route('supplier.index'))->with('message', '修改成功');
    }

    public function destroy($id)
    {
        try{
            $supplier = Supplier::find($id);
            $supplier->status = 2;
            $supplier->delete_flag = 1;
            $supplier->deleted_at = Now();
            $supplier->deleted_user = session('admin_user')->id;
            $supplier->save();
            $supplier->delete();
            return redirect()->route('supplier.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('supplier.index')->with('error','刪除失敗');
        }
    }

    public function save($id, $request)
    {
        // 新增或修改
        if ($id == 0) {
            $supplier = new Supplier;

            $latest_code = Setting::where('set_key', 'supplier_code')->first();
            $number = (int)$latest_code->set_value + 1;
            $code_str = "S".str_pad($number, 6, '0', STR_PAD_LEFT);
            $supplier->code = $code_str;

            $latest_code->set_value += 1;
            $latest_code->save();
        } else {
            $supplier = Supplier::find($id);
        }

        $supplier->supplier = $request->supplier;
        $supplier->manufacturer = $request->manufacturer;

        $supplier->gpn = $request->gpn;
        $supplier->fullName = $request->fullName;
        $supplier->shortName = $request->shortName;
        $supplier->category = $request->category;
        $supplier->pay = $request->pay;
        $supplier->receiving = $request->receiving;
        $supplier->owner = $request->owner;
        $supplier->contact = $request->contact;
        $supplier->tel = $request->tel;
        $supplier->fax = $request->fax;
        $supplier->address = $request->address;
        $supplier->email = $request->email;
        $supplier->invoiceTitle = $request->invoiceTitle;
        $supplier->invoiceAddress = $request->invoiceAddress;
        $supplier->website = $request->website;
        $supplier->items = $request->items;
        $supplier->contact1 = $request->contact1;
        $supplier->contactContent1 = $request->contactContent1;
        $supplier->contactPerson1 = $request->contactPerson1;
        $supplier->contact2 = $request->contact2;
        $supplier->contactContent2 = $request->contactContent2;
        $supplier->contactPerson2 = $request->contactPerson2;
        $supplier->contact3 = $request->contact3;
        $supplier->contactContent3 = $request->contactContent3;
        $supplier->contactPerson3 = $request->contactPerson3;
        $supplier->memo = $request->memo;
        $supplier->status = $request->status;

        if ($id == 0) {
            $supplier->created_user = session('admin_user')->id;
        } else {
            $supplier->updated_user = session('admin_user')->id;
        }

        $supplier->save();

        return $supplier;
    }
}
