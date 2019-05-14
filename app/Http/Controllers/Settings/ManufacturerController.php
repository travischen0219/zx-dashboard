<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Setting;
use App\Model\Manufacturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $manufacturers = Manufacturer::where('delete_flag','0')->get();
        return view('settings.manufacturer.show',compact('manufacturers','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;

        if($search_code == 'all'){
            $manufacturers = Manufacturer::where('delete_flag','0')->get();
        } else {
            $manufacturers = Manufacturer::where('delete_flag','0')->where('category',$search_code)->get();
        }
        return view('settings.manufacturer.show',compact('manufacturers','search_code'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.manufacturer.create');          
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
            $latest_code = Setting::where('set_key','manufacturer_code')->first();
            $number = (int)$latest_code->set_value + 1;
            $code_str = "M".str_pad($number, 6, '0',STR_PAD_LEFT);

            $manufacturer = new Manufacturer;
            $manufacturer->code = $code_str;
            $manufacturer->gpn = $request->gpn;
            $manufacturer->fullName = $request->fullName;
            $manufacturer->shortName = $request->shortName;
            $manufacturer->category = $request->category;
            $manufacturer->pay = $request->pay;
            $manufacturer->receiving = $request->receiving;
            $manufacturer->owner = $request->owner;
            $manufacturer->contact = $request->contact;
            $manufacturer->tel = $request->tel;
            $manufacturer->fax = $request->fax;
            $manufacturer->address = $request->address;
            $manufacturer->email = $request->email;
            $manufacturer->invoiceTitle = $request->invoiceTitle;
            $manufacturer->invoiceAddress = $request->invoiceAddress;
            $manufacturer->website = $request->website;
            $manufacturer->items = $request->items;
            $manufacturer->contact1 = $request->contact1;
            $manufacturer->contactContent1 = $request->contactContent1;
            $manufacturer->contactPerson1 = $request->contactPerson1;
            $manufacturer->contact2 = $request->contact2;
            $manufacturer->contactContent2 = $request->contactContent2;
            $manufacturer->contactPerson2 = $request->contactPerson2;
            $manufacturer->contact3 = $request->contact3;
            $manufacturer->contactContent3 = $request->contactContent3;
            $manufacturer->contactPerson3 = $request->contactPerson3;
            $manufacturer->memo = $request->memo;
            $manufacturer->status = $request->status;
            $manufacturer->created_user = session('admin_user')->id;
            $manufacturer->delete_flag = 0;
            $manufacturer->save();

            $latest_code->set_value = $number;
            $latest_code->save();
            return redirect()->route('manufacturer.index')->with('message','新增成功');
 
        } catch (Exception $e) {
            return redirect()->route('manufacturer.index')->with('error','新增失敗');
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
        $manufacturer = Manufacturer::find($id);
        if($manufacturer->updated_user > 0){
            $updated_user = User::where('id',$manufacturer->updated_user)->first();
        } else {
            $updated_user = User::where('id',$manufacturer->created_user)->first();
        }
        return view('settings.manufacturer.show_one', compact('manufacturer','updated_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manufacturer = Manufacturer::find($id);
        if($manufacturer->updated_user > 0){
            $updated_user = User::where('id',$manufacturer->updated_user)->first();
        } else {
            $updated_user = User::where('id',$manufacturer->created_user)->first();
        }
        return view('settings.manufacturer.edit', compact('manufacturer','updated_user'));
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
            'fullName.required' => '全名 不可為空',
            'shortName.required' => '簡稱 不可為空',            
        ];
        $this->validate($request, $rules, $messages);

        try{
            $manufacturer = Manufacturer::find($id);
            $manufacturer->gpn = $request->gpn;
            $manufacturer->fullName = $request->fullName;
            $manufacturer->shortName = $request->shortName;
            $manufacturer->category = $request->category;
            $manufacturer->pay = $request->pay;
            $manufacturer->receiving = $request->receiving;
            $manufacturer->owner = $request->owner;
            $manufacturer->contact = $request->contact;
            $manufacturer->tel = $request->tel;
            $manufacturer->fax = $request->fax;
            $manufacturer->address = $request->address;
            $manufacturer->email = $request->email;
            $manufacturer->invoiceTitle = $request->invoiceTitle;
            $manufacturer->invoiceAddress = $request->invoiceAddress;
            $manufacturer->website = $request->website;
            $manufacturer->items = $request->items;
            $manufacturer->contact1 = $request->contact1;
            $manufacturer->contactContent1 = $request->contactContent1;
            $manufacturer->contactPerson1 = $request->contactPerson1;
            $manufacturer->contact2 = $request->contact2;
            $manufacturer->contactContent2 = $request->contactContent2;
            $manufacturer->contactPerson2 = $request->contactPerson2;
            $manufacturer->contact3 = $request->contact3;
            $manufacturer->contactContent3 = $request->contactContent3;
            $manufacturer->contactPerson3 = $request->contactPerson3;
            $manufacturer->memo = $request->memo;
            $manufacturer->status = $request->status;
            $manufacturer->updated_user = session('admin_user')->id;      
            $manufacturer->save();
            return redirect()->route('manufacturer.index')->with('message','修改成功');
        } catch (Exception $e) {
            return redirect()->route('manufacturer.index')->with('error','修改失敗');
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
        //
    }
}
