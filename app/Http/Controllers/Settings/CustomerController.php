<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Setting;
use App\Model\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        $search_code = $request->search_category ?? 'all';

        if ($search_code == 'all') {
            $customers = Customer::all();
        } else {
            $customers = Customer::where('category', $search_code)->get();
        }

        $data = [];
        $data['customers'] = $customers;
        $data['search_code'] = $search_code;

        $categories = Customer::categories();
        $data['categories'] = $categories;

        return view('settings.customer.index', $data);
    }

    public function create()
    {
        $data['customer'] = new Customer;
        $data['customer']->status = 1;

        return view('settings.customer.create', $data);
    }

    public function store(CustomerRequest $request)
    {
        $validated = $request->validated();
        $this->save(0, $request);
        return redirect(route('customer.index'))->with('message', '新增成功');
    }

    public function show($id)
    {
        $customer = Customer::find($id);

        $data = [];
        $data['customer'] = $customer;
        $data["show"] = 1;

        return view('settings.customer.edit', $data);
    }

    public function edit($id)
    {
        $customer = Customer::find($id);

        $data = [];
        $data['customer'] = $customer;
        $data['show'] = 0;
        return view('settings.customer.edit', $data);
    }

    public function update(CustomerRequest $request, $id)
    {
        $validated = $request->validated();
        $this->save($id, $request);
        return redirect(route('customer.index'))->with('message', '修改成功');
    }

    public function destroy($id)
    {
        if (!User::canAdmin('settings')) {
            return false;
        }

        try{
            $customer = Customer::find($id);
            $customer->status = 2;
            $customer->delete_flag = 1;
            $customer->deleted_at = Now();
            $customer->deleted_user = session('admin_user')->id;
            $customer->save();
            $customer->delete();
            return redirect()->route('customer.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('customer.index')->with('error','刪除失敗');
        }
    }

    public function save($id, $request)
    {
        if (!User::canAdmin('settings')) {
            return false;
        }

        // 新增或修改
        if ($id == 0) {
            $customer = new Customer;

            $latest_code = Setting::where('set_key', 'customer_code')->first();
            $number = (int)$latest_code->set_value + 1;
            $code_str = "C" . str_pad($number, 6, '0', STR_PAD_LEFT);
            $customer->code = $code_str;
            $latest_code->set_value += 1;
            $latest_code->save();
        } else {
            $customer = Customer::find($id);
        }

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

        if ($id == 0) {
            $customer->created_user = session('admin_user')->id;
        } else {
            $customer->updated_user = session('admin_user')->id;
        }

        $customer->save();

        return $customer;
    }
}
