<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Department;
use Illuminate\Http\Request;
use App\Model\Professional_title;
use App\Http\Controllers\Controller;
use App\Http\Requests\StaffRequest;

class StaffController extends Controller
{

    // 員工列表
    public function index()
    {
        $users = User::where('staff_code', '<>', 'ADMIN')->get();
        $data = [];
        $data['users'] = $users;

        return view('settings.staff.index', $data);
    }

    // 新增員工
    public function create()
    {
        $deps = Department::where('delete_flag','0')->orderBy('orderby', 'ASC')->get();
        $pro_titles = Professional_title::orderBy('orderby', 'ASC')->get();

        $data = [];
        $data['user'] = new User;
        $data['user']->status = 1;
        $data['deps'] = $deps;
        $data['pro_titles'] = $pro_titles;

        return view('settings.staff.create', $data);
    }

    public function store(StaffRequest $request)
    {
        $validated = $request->validated();

        $request->password = bcrypt($request->password);
        $user = User::create($request->all());
        $user->created_user = session('admin_user')->id;
        $user->password = bcrypt($user->password);
        $user->save();

        return redirect(route('staff.index'))->with('message','新增成功');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deps = Department::where('delete_flag','0')->orderBy('orderby', 'ASC')->get();
        $pro_titles = Professional_title::orderBy('orderby', 'ASC')->get();

        $data = [];
        $data['user'] = User::find($id);
        $data['deps'] = $deps;
        $data['pro_titles'] = $pro_titles;

        return view('settings.staff.edit', $data);
    }

    public function update(StaffRequest $request, $id)
    {
        $validated = $request->validated();

        $user = User::find($id);
        $user->fullname = $request->fullname;
        $user->department_id = $request->department_id;
        $user->professional_title_id = $request->professional_title_id;
        $user->tel = $request->tel;
        $user->mobile = $request->mobile;
        $user->address = $request->address;

        if ($request->password != '') {
            $user->password = bcrypt($request->password);
        }

        $user->status = $request->status;
        $user->memo = $request->memo;
        $user->updated_user = session('admin_user')->id;
        $user->save();

        return redirect(route('staff.index'))->with('message','修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        $user->deleted_user = session('admin_user')->id;
        $user->save();

        $user->delete();

        return redirect(route('staff.index'));
    }
}
