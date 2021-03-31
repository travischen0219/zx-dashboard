<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Access;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StaffRequest;

class StaffController extends Controller
{

    // 員工列表
    public function index()
    {
        $users = User::all();
        $data = [];
        $data['users'] = $users;

        return view('settings.staff.index', $data);
    }

    // 新增員工
    public function create()
    {
        $accesses = Access::orderBy('orderby', 'ASC')->get();

        $data = [];
        $data['user'] = new User;
        $data['user']->status = 1;
        $data['accesses'] = $accesses;

        return view('settings.staff.create', $data);
    }

    public function store(StaffRequest $request)
    {
        if (!User::canAdmin('admin')) {
            return false;
        }

        $validated = $request->validated();

        $request->password = bcrypt($request->password);
        $user = User::create($request->all());
        $user->created_user = session('admin_user')->id;
        $user->password = bcrypt($user->password);
        $user->save();

        return redirect(route('staff.index'))->with('message', '新增成功');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accesses = Access::orderBy('orderby', 'ASC')->get();

        $data = [];
        $data['user'] = User::find($id);
        $data['accesses'] = $accesses;

        return view('settings.staff.edit', $data);
    }

    public function update(StaffRequest $request, $id)
    {
        if (!User::canAdmin('admin')) {
            return false;
        }

        $validated = $request->validated();

        $user = User::find($id);
        $user->fullname = $request->fullname;
        $user->access_id = $request->access_id;
        $user->tel = $request->tel;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->address = $request->address;

        if ($request->password != '') {
            $user->password = bcrypt($request->password);
        }

        $user->status = $request->status;
        $user->memo = $request->memo;
        $user->updated_user = session('admin_user')->id;
        $user->save();

        return redirect(route('staff.index'))->with('message', '修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!User::canAdmin('admin')) {
            return false;
        }

        $user = User::find($id);

        $user->deleted_user = session('admin_user')->id;
        $user->save();

        $user->delete();

        return redirect(route('staff.index'));
    }
}
