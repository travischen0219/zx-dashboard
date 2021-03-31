<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Access;
use App\Model\Access_detail;
use App\Model\User;
use App\Http\Requests\AccessRequest;

class AccessController extends Controller
{
    public function index()
    {
        $data = [];
        $data['accesses'] = Access::orderBy('orderby', 'ASC')->get();
        $data['groups'] = Access::groups();
        $data['modes'] = Access::modes();

        return view('settings.access.index', $data);
    }

    // 排序
    public function update_orderby(Request $request)
    {
        if (!User::canAdmin('admin')) {
            return false;
        }

        $data_id = $request->data_id;
        $data_orderby = $request->data_orderby;

        if (count($data_id) > 1) {
            foreach ($data_id as $key => $value) {
                $access = Access::find($value);
                $access->orderby = $data_orderby[$key];
                $access->updated_user = session('admin_user')->id;
                $access->save();
            }
            return "success";
        } else {
            // 無需排序時
            return "error_1";
        }
    }

    public function create()
    {
        $data = [];
        $data['groups'] = Access::groups();
        $data['modes'] = Access::modes();
        $data['access'] = new Access;

        return view('settings.access.create', $data);
    }

    public function store(AccessRequest $request)
    {
        if (!User::canAdmin('admin')) {
            return false;
        }

        $validated = $request->validated();

        // Store Access
        $access = new Access;
        $access->name = $request->name;
        $access->orderBy = Access::max_orderby() + 1;
        $access->created_user = session('admin_user')->id;
        $access->save();

        // Store Access_detail
        $groups = Access::groups();
        foreach ($groups as $gKey => $group) {
            $access_detail = new Access_detail;
            $access_detail->access_id = $access->id;
            $access_detail->group = $gKey;
            $access_detail->mode = $request->$gKey;
            $access_detail->save();
        }

        return redirect()->route('access.index')->with('message', '新增成功');
    }

    public function edit($id)
    {
        $data = [];
        $data['groups'] = Access::groups();
        $data['modes'] = Access::modes();
        $data['access'] = Access::find($id);

        return view('settings.access.edit', $data);
    }

    public function update(AccessRequest $request, $id)
    {
        if (!User::canAdmin('admin')) {
            return false;
        }

        $validated = $request->validated();
        $access = Access::find($id);
        $access->name = $request->name;
        $access->updated_user = session('admin_user')->id;
        $access->save();

        // Store Access_detail
        $groups = Access::groups();
        foreach ($groups as $gKey => $group) {
            $access_detail = Access_detail::where('access_id', $access->id)
                ->where('group', $gKey)
                ->first();
            $access_detail->mode = $request->$gKey;
            $access_detail->save();
        }
        return redirect()->route('access.index')->with('message', '修改成功');
    }

    public function destroy($id)
    {
        if (!User::canAdmin('admin')) {
            return false;
        }

        try{
            // 若員工尚有該資料 則提醒無法刪除
            $users = User::where('access_id', $id)->get();
            if (count($users) > 0) {
                return redirect()->route('access.index')->with('error', '尚有該權限的員工，請將其修改至其他權限後再刪除');
            } else {
                // 刪除後排序重整
                $access = Access::where('id', $id)->first();
                $access_orderby = $access->orderby;
                $total = Access::count();
                $must_change = $total - $access_orderby;
                $i = 1;
                while ($i <= $must_change) {
                    $access_orderby ++;
                    $access_change = Access::where('orderby', $dep_oaccess_orderbyrderby)->first();
                    $access_change->orderby = $access_change->orderby -1;
                    $access_change->save();
                    $i ++;
                }
                $access->orderby = 0;
                $access->deleted_user = session('admin_user')->id;
                $access->save();

                // 刪除 Detail
                Access_detail::where('access_id', $access->id)->delete();

                $access->delete();

                return redirect()->route('access.index')->with('message', '刪除成功');
            }
        } catch (Exception $e) {
            return redirect()->route('access.index')->with('error', '刪除失敗');
        }
    }
}
