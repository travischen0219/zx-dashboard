<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Department;
use Illuminate\Http\Request;
use App\Model\Professional_title;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('staff_code', '<>', 'ADMIN')->where('delete_flag','0')->get();
        return view('settings.staff.show',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $deps = Department::where('delete_flag','0')->orderBy('orderby', 'ASC')->get();
        $pro_titles = Professional_title::where('delete_flag','0')->orderBy('orderby', 'ASC')->get();
        return view('settings.staff.create', compact('deps', 'pro_titles'));
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
            'staff_code' => 'required|unique:users',
            'fullname' => 'required|string',
            'username' => 'required|unique:users',
            'password' => 'required|min:8|confirmed',
            'department' => 'required',
            'pro_title' => 'required',
        ];

        $messages = [
            'staff_code.required' => '員工編號不可為空',          
            'staff_code.unique' => '員工編號已存在，不可重複',          
            'fullname.required' => '姓名不可為空',
            'username.required' => '帳號不可為空',
            'username.unique' => '帳號已存在，不可重複',
            'password.required' => '密碼不可為空',
            'password.min' => '密碼必須最少8個英文或數字組成',
            'password.confirmed' => '兩次密碼輸入不同',
            'department.required' => '需選擇 部門',
            'pro_title.required' => '需選擇 職稱',
        ];
        $this->validate($request, $rules, $messages);

        try{
            $user = new User;
            $user->staff_code = strtoupper($request->staff_code);
            $user->fullname = $request->fullname;
            $user->department_id = $request->department;
            $user->professional_title_id = $request->pro_title;
            $user->tel = $request->tel;   
            $user->mobile = $request->mobile;
            $user->address = $request->address;   
            $user->username = strtoupper($request->username);
            $user->password = bcrypt($request->password);
            $user->email = $request->email;
            $user->status = $request->status;
            $user->memo = $request->memo;   

            $user->created_user = session('admin_user')->id;
            $user->delete_flag = 0;
            $user->save();
            return redirect()->route('staff.index')->with('message','新增成功');
        } catch (Exception $e) {
            return redirect()->route('staff.index')->with('error','新增失敗');            
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $deps = Department::where('delete_flag','0')->orderBy('orderby', 'ASC')->get();
        $pro_titles = Professional_title::where('delete_flag','0')->orderBy('orderby', 'ASC')->get();
        if($user->updated_user > 0){
            $updated_user = User::where('id',$user->updated_user)->first();
        } else {
            $updated_user = User::where('id',$user->created_user)->first();
        }
        return view('settings.staff.edit', compact('user', 'deps', 'pro_titles', 'updated_user'));
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
        // $user = User::find($id);
        // if($user->staff_code == $request->staff_code){
        //     $rules = ['staff_code' => 'required|unique:users'.($id ? ",id,$id" : '')];
        // } else {
        //     if($check_id = User::where('staff_code',$request->staff_code)->first()){
        //         if($check_id->id != $id){
        //             return redirect()->back()->with('error','員工編號已存在');
        //             die;
        //         }
        //     }
        // }

        $rules = [
            'fullname' => 'required|string',
            'department' => 'required',
            'pro_title' => 'required',
        ];
        if($request->password){
            $rules = ['password' => 'required|min:8|confirmed'];
        }
        $messages = [        
            'fullname.required' => '姓名不可為空',
            'password.required' => '密碼不可為空',
            'password.min' => '密碼必須最少8個英文或數字組成',
            'password.confirmed' => '兩次密碼輸入不同',
            'department.required' => '需選擇 部門',
            'pro_title.required' => '需選擇 職稱',
        ];
        if($request->password){
            $messages = [
                'password.required' => '密碼不可為空',
                'password.min' => '密碼必須最少6個英文或數字組成',
                'password.confirmed' => '兩次密碼輸入不同',
            ];
        }
        $this->validate($request, $rules, $messages);

        try{
            $user = User::find($id);
            $user->fullname = $request->fullname;
            $user->department_id = $request->department;
            $user->professional_title_id = $request->pro_title;
            $user->tel = $request->tel;   
            $user->mobile = $request->mobile;
            $user->address = $request->address;   
            if($request->password){
                $user->password = bcrypt($request->password);
            }        
            $user->email = $request->email;
            $user->status = $request->status;
            $user->memo = $request->memo;   
            $user->updated_user = session('admin_user')->id;
            $user->save();
            return redirect()->route('staff.index')->with('message','修改成功');
        } catch (Exception $e) {
            return redirect()->route('staff.index')->with('error','修改失敗');            
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
            $user = User::find($id);
            $user->status = 2;
            $user->delete_flag = 1;
            $user->deleted_at = Now();
            $user->deleted_user = session('admin_user')->id;
            $user->save();
            return redirect()->route('staff.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('staff.index')->with('error','刪除失敗');            
        } 
    }
}
