<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Setting;
use App\Model\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $customers = Customer::where('delete_flag','0')->get();
        return view('settings.customer.show',compact('customers','search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;

        if($search_code == 'all'){
            $customers = Customer::where('delete_flag','0')->get();
        } else {
            $customers = Customer::where('delete_flag','0')->where('category',$search_code)->get();
        }
        return view('settings.customer.show',compact('customers','search_code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.customer.create');
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
            $latest_code = Setting::where('set_key','customer_code')->first();
            $number = (int)$latest_code->set_value + 1;
            $code_str = "C".str_pad($number, 6, '0',STR_PAD_LEFT);

            $customer = new Customer;
            $customer->code = $code_str;
            $customer->gpn = $request->gpn;
            $customer->fullName = $request->fullName;
            $customer->shortName = $request->shortName;
            $customer->category = $request->category;
            $customer->pay = $request->pay;
            $customer->receiving = $request->receiving;
            $customer->owner = $request->owner;
            $customer->contact = $request->contact;
            $customer->tel = $request->tel;
            $customer->fax = $request->fax;
            $customer->address = $request->address;
            $customer->email = $request->email;
            $customer->invoiceTitle = $request->invoiceTitle;
            $customer->invoiceAddress = $request->invoiceAddress;
            $customer->website = $request->website;
            $customer->close_date = $request->close_date;
            $customer->contact1 = $request->contact1;
            $customer->contactContent1 = $request->contactContent1;
            $customer->contactPerson1 = $request->contactPerson1;
            $customer->contact2 = $request->contact2;
            $customer->contactContent2 = $request->contactContent2;
            $customer->contactPerson2 = $request->contactPerson2;
            $customer->contact3 = $request->contact3;
            $customer->contactContent3 = $request->contactContent3;
            $customer->contactPerson3 = $request->contactPerson3;
            $customer->memo = $request->memo;
            $customer->status = $request->status;
            $customer->created_user = session('admin_user')->id;
            $customer->delete_flag = 0;
            $customer->save();

            $latest_code->set_value = $number;
            $latest_code->save();
            return redirect()->route('customers.index')->with('message','新增成功');
 
        } catch (Exception $e) {
            return redirect()->route('customers.index')->with('error','新增失敗');
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
        $customer = Customer::find($id);
        if($customer->updated_user > 0){
            $updated_user = User::where('id',$customer->updated_user)->first();
        } else {
            $updated_user = User::where('id',$customer->created_user)->first();
        }
        return view('settings.customer.show_one', compact('customer','updated_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::find($id);
        if($customer->updated_user > 0){
            $updated_user = User::where('id',$customer->updated_user)->first();
        } else {
            $updated_user = User::where('id',$customer->created_user)->first();
        }
        return view('settings.customer.edit', compact('customer','updated_user'));
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
            $customer = Customer::find($id);
            $customer->gpn = $request->gpn;
            $customer->fullName = $request->fullName;
            $customer->shortName = $request->shortName;
            $customer->category = $request->category;
            $customer->pay = $request->pay;
            $customer->receiving = $request->receiving;
            $customer->owner = $request->owner;
            $customer->contact = $request->contact;
            $customer->tel = $request->tel;
            $customer->fax = $request->fax;
            $customer->address = $request->address;
            $customer->email = $request->email;
            $customer->invoiceTitle = $request->invoiceTitle;
            $customer->invoiceAddress = $request->invoiceAddress;
            $customer->website = $request->website;
            $customer->close_date = $request->close_date;
            $customer->contact1 = $request->contact1;
            $customer->contactContent1 = $request->contactContent1;
            $customer->contactPerson1 = $request->contactPerson1;
            $customer->contact2 = $request->contact2;
            $customer->contactContent2 = $request->contactContent2;
            $customer->contactPerson2 = $request->contactPerson2;
            $customer->contact3 = $request->contact3;
            $customer->contactContent3 = $request->contactContent3;
            $customer->contactPerson3 = $request->contactPerson3;
            $customer->memo = $request->memo;
            $customer->status = $request->status;
            $customer->updated_user = session('admin_user')->id;      
            $customer->save();
            return redirect()->route('customers.index')->with('message','修改成功');
        } catch (Exception $e) {
            return redirect()->route('customers.index')->with('error','修改失敗');
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
            $customer = Customer::find($id);
            $customer->status = 2;
            $customer->delete_flag = 1;
            $customer->deleted_at = Now();
            $customer->deleted_user = session('admin_user')->id;
            $customer->save();
            return redirect()->route('customers.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('customers.index')->with('error','刪除失敗');            
        } 
    }
}
