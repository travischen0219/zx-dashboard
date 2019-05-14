<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Setting;
use App\Model\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $suppliers = Supplier::where('delete_flag','0')->get();
        return view('settings.supplier.show',compact('suppliers','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;

        if($search_code == 'all'){
            $suppliers = Supplier::where('delete_flag','0')->get();
        } else {
            $suppliers = Supplier::where('delete_flag','0')->where('category',$search_code)->get();
        }
        return view('settings.supplier.show',compact('suppliers','search_code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.supplier.create');  
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
            'fullName' => 'required|string',
            'shortName' => 'required|string',
        ];

        $messages = [
            'fullName.required' => '全名 不可為空',
            'shortName.required' => '簡稱 不可為空',
        ];
        $this->validate($request, $rules, $messages);

        try{
            $latest_code = Setting::where('set_key','supplier_code')->first();
            $number = (int)$latest_code->set_value + 1;
            $code_str = "S".str_pad($number, 6, '0',STR_PAD_LEFT);

            $supplier = new Supplier;
            $supplier->code = $code_str;
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
            $supplier->created_user = session('admin_user')->id;
            $supplier->delete_flag = 0;
            $supplier->save();

            $latest_code->set_value = $number;
            $latest_code->save();
            return redirect()->route('supplier.index')->with('message','新增成功');
 
        } catch (Exception $e) {
            return redirect()->route('supplier.index')->with('error','新增失敗');
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
        $supplier = Supplier::find($id);
        if($supplier->updated_user > 0){
            $updated_user = User::where('id',$supplier->updated_user)->first();
        } else {
            $updated_user = User::where('id',$supplier->created_user)->first();
        }
        return view('settings.supplier.show_one', compact('supplier','updated_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::find($id);
        if($supplier->updated_user > 0){
            $updated_user = User::where('id',$supplier->updated_user)->first();
        } else {
            $updated_user = User::where('id',$supplier->created_user)->first();
        }
        return view('settings.supplier.edit', compact('supplier','updated_user'));
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
        $rules = [
            'fullName' => 'required|string',
            'shortName' => 'required|string',            
        ];

        $messages = [
            'fullName.required' => '全名不可為空',
            'shortName.required' => '簡稱 不可為空',            
        ];
        $this->validate($request, $rules, $messages);

        try{
            $supplier = Supplier::find($id);
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
            $supplier->updated_user = session('admin_user')->id;      
            $supplier->save();
            return redirect()->route('supplier.index')->with('message','修改成功');
        } catch (Exception $e) {
            return redirect()->route('supplier.index')->with('error','修改失敗');
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
            $supplier = Supplier::find($id);
            $supplier->status = 2;
            $supplier->delete_flag = 1;
            $supplier->deleted_at = Now();
            $supplier->deleted_user = session('admin_user')->id;
            $supplier->save();
            return redirect()->route('supplier.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('supplier.index')->with('error','刪除失敗');            
        } 
    }
}
