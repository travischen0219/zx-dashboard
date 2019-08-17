<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Setting;
use App\Model\Manufacturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManufacturerRequest;

class ManufacturerController extends Controller
{

    public function index(Request $request)
    {
        $search_code = $request->search_category ?? 'all';

        if ($search_code == 'all') {
            $manufacturers = Manufacturer::all();
        } else {
            $manufacturers = Manufacturer::where('category', $search_code)->get();
        }

        $data = [];
        $data['manufacturers'] = $manufacturers;
        $data['search_code'] = $search_code;

        return view('settings.manufacturer.index', $data);
    }

    public function create()
    {
        $data['manufacturer'] = new Manufacturer;
        $data['manufacturer']->status = 1;

        return view('settings.manufacturer.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManufacturerRequest $request)
    {
        $validated = $request->validated();
        $this->save(0, $request);
        return redirect(route('manufacturer.index'))->with('message', '新增成功');
    }

    public function show($id)
    {
        $manufacturer = Manufacturer::find($id);

        $data = [];
        $data['manufacturer'] = $manufacturer;
        $data["show"] = 1;

        return view('settings.manufacturer.edit', $data);
    }

    public function edit($id)
    {
        $manufacturer = Manufacturer::find($id);

        $data = [];
        $data['manufacturer'] = $manufacturer;
        $data['show'] = 0;
        return view('settings.manufacturer.edit', $data);
    }

    public function update(ManufacturerRequest $request, $id)
    {
        $validated = $request->validated();
        $this->save($id, $request);
        return redirect(route('manufacturer.index'))->with('message', '修改成功');
    }

    public function destroy($id)
    {
        try{
            $manufacturer = Manufacturer::find($id);
            $manufacturer->status = 2;
            $manufacturer->delete_flag = 1;
            $manufacturer->deleted_at = Now();
            $manufacturer->deleted_user = session('admin_user')->id;
            $manufacturer->save();
            $manufacturer->delete();
            return redirect()->route('manufacturer.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('manufacturer.index')->with('error','刪除失敗');
        }
    }

    public function save($id, $request)
    {
        // 新增或修改
        if ($id == 0) {
            $manufacturer = new Manufacturer;

            $latest_code = Setting::where('set_key', 'manufacturer_code')->first();
            $number = (int)$latest_code->set_value + 1;
            $code_str = "M".str_pad($number, 6, '0', STR_PAD_LEFT);
            $manufacturer->code = $code_str;
        } else {
            $manufacturer = Manufacturer::find($id);
        }

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

        if ($id == 0) {
            $manufacturer->created_user = session('admin_user')->id;
        } else {
            $manufacturer->updated_user = session('admin_user')->id;
        }

        $manufacturer->save();

        return $manufacturer;
    }
}
