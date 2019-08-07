<?php

namespace App\Http\Controllers\Settings;

use App\Model\User;
use App\Model\Gallery;
use App\Model\Warehouse;
use Illuminate\Http\Request;
use App\Model\Warehouse_category;
use App\Http\Controllers\Controller;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Warehouse_category::where('delete_flag', '0')->count() > 0) {
            $search_like = '';

            $cate_first = Warehouse_category::where('delete_flag', '0')
                ->where('status', '1')
                ->orderBy('orderby', 'ASC')
                ->first();
            $search_code = $cate_first->id;
            $cates = Warehouse_category::where('delete_flag', '0')
                ->where('status', '1')
                ->orderBy('orderby', 'ASC')
                ->get();
            $warehouses = Warehouse::where('delete_flag', '0')
                ->where('category', $cate_first->id)
                ->orderBy('code', 'ASC')
                ->get();
            return view(
                'settings.warehouse.show',
                compact('warehouses', 'search_code', 'cates', 'search_like')
            );
        } else {
            return redirect()->route('warehouse_category.index')->with('error', '尚無倉儲分類資料，請先建立');
        }
    }

    public function search(Request $request)
    {
        $cates = Warehouse_category::where('delete_flag', '0')
            ->where('status', '1')
            ->orderBy('orderby', 'ASC')
            ->get();

        $search_like = $request->search_codeOrName;
        $search_code = $request->search_category;
        $warehouses = Warehouse::where(
            function ($query) use ($search_code,$search_like) {
                $query->where('delete_flag', '0')
                    ->where('category', $search_code)
                    ->where('code', 'like', '%' . $search_like . '%');
            }
        )->orWhere(
            function ($query) use ($search_code,$search_like) {
                $query->where('delete_flag', '0')
                    ->where('category', $search_code)
                    ->where('fullName', 'like', '%' . $search_like . '%');
            }
        )->orderBy('code', 'ASC')->get();

        return view(
            'settings.warehouse.show',
            compact('warehouses', 'search_code', 'cates', 'search_like')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cates = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->get();
        return view('settings.warehouse.create',compact('cates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $same_code = Warehouse::where('delete_flag','0')->where('code',$request->code)->first();
        if($same_code){
            return redirect()->back()->with('error', '倉儲編號已存在，不可重複');
        }

        $rules = [
            'category' => 'required',
            'code' => 'required',
            'fullName' => 'required',
        ];

        $messages = [
            'category.required' => '分類 必選',
            'fullName.required' => '倉儲名稱 必填',
            'code.required' => '倉儲編號 必填',
            // 'code.unique' => '倉儲編號已存在，不可重複',
        ];
        $this->validate($request, $rules, $messages);

        $file_1=null;
        $file_2=null;
        $file_3=null;
        if($request->hasFile('upload_image_1')){
            $file_1 = $this->file_process($request->name_1, $request->upload_image_1);
        }
        if($request->hasFile('upload_image_2')){
            $file_2 = $this->file_process($request->name_2, $request->upload_image_2);
        }
        if($request->hasFile('upload_image_3')){
            $file_3 = $this->file_process($request->name_3, $request->upload_image_3);
        }

        try{
            $warehouse = new Warehouse;
            $warehouse->category = $request->category;
            $warehouse->fullName = $request->fullName;
            $warehouse->code = $request->code;
            $warehouse->location = $request->location;
            $warehouse->size = $request->size;
            $warehouse->file_1 = $file_1;
            $warehouse->file_2 = $file_2;
            $warehouse->file_3 = $file_3;
            $warehouse->status = $request->status;
            $warehouse->created_user = session('admin_user')->id;
            $warehouse->delete_flag = 0;
            $warehouse->save();
            return redirect()->route('warehouses.index')->with('message','新增成功');
        } catch (Exception $e) {
            return redirect()->route('warehouses.index')->with('error','新增失敗');
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
        $cates = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->get();
        $warehouse = Warehouse::find($id);

        if($warehouse->updated_user > 0){
            $updated_user = User::where('id',$warehouse->updated_user)->first();
        } else {
            $updated_user = User::where('id',$warehouse->created_user)->first();
        }

        return view('settings.warehouse.show_one', compact('warehouse','updated_user','cates'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cates = Warehouse_category::where('delete_flag','0')->where('status','1')->orderBy('orderby','ASC')->get();
        $warehouse = Warehouse::find($id);

        if($warehouse->updated_user > 0){
            $updated_user = User::where('id',$warehouse->updated_user)->first();
        } else {
            $updated_user = User::where('id',$warehouse->created_user)->first();
        }

        $upload_check_1 = true;
        $upload_check_2 = true;
        $upload_check_3 = true;

        if($warehouse->file_1 > 0){
            $upload_check_1 = false;
        }
        if($warehouse->file_2 > 0){
            $upload_check_2 = false;
        }
        if($warehouse->file_3 > 0){
            $upload_check_3 = false;
        }

        return view('settings.warehouse.edit', compact('warehouse','updated_user','cates','upload_check_1','upload_check_2','upload_check_3'));
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
            'category' => 'required',
            'fullName' => 'required',
        ];

        $messages = [
            'category.required' => '分類 必選',
            'fullName.required' => '倉儲名稱 必填',
        ];
        $this->validate($request, $rules, $messages);

        $file_1=null;
        $file_2=null;
        $file_3=null;
        $check_1 = false;
        $check_2 = false;
        $check_3 = false;
        if($request->hasFile('upload_image_1')){
            $file_1 = $this->file_process($request->name_1, $request->upload_image_1);
            $check_1 = true;
        }
        if($request->hasFile('upload_image_2')){
            $file_2 = $this->file_process($request->name_2, $request->upload_image_2);
            $check_2 = true;
        }
        if($request->hasFile('upload_image_3')){
            $file_3 = $this->file_process($request->name_3, $request->upload_image_3);
            $check_3 = true;
        }

        try{
            $warehouse = Warehouse::find($id);
            $warehouse->category = $request->category;
            $warehouse->fullName = $request->fullName;
            $warehouse->location = $request->location;
            $warehouse->size = $request->size;
            if($check_1){
                $warehouse->file_1 = $file_1;
            }
            if($check_2){
                $warehouse->file_2 = $file_2;
            }
            if($check_3){
                $warehouse->file_3 = $file_3;
            }
            $warehouse->status = $request->status;
            $warehouse->updated_user = session('admin_user')->id;
            $warehouse->save();
            return redirect()->route('warehouses.index')->with('message','修改成功');
        } catch (Exception $e) {
            return redirect()->route('warehouses.index')->with('error','修改成功');
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
            $warehouse = Warehouse::find($id);

            if($warehouse->file_1 > 0){
                $gallery = Gallery::find($warehouse->file_1);
                $gallery->delete_flag = 1;
                $gallery->deleted_at = Now();
                $gallery->deleted_user = session('admin_user')->id;
                $gallery->save();
            }
            if($warehouse->file_2 > 0){
                $gallery = Gallery::find($warehouse->file_2);
                $gallery->delete_flag = 1;
                $gallery->deleted_at = Now();
                $gallery->deleted_user = session('admin_user')->id;
                $gallery->save();
            }
            if($warehouse->file_3 > 0){
                $gallery = Gallery::find($warehouse->file_3);
                $gallery->delete_flag = 1;
                $gallery->deleted_at = Now();
                $gallery->deleted_user = session('admin_user')->id;
                $gallery->save();
            }

            $warehouse->status = 2;
            $warehouse->delete_flag = 1;
            $warehouse->deleted_at = Now();
            $warehouse->deleted_user = session('admin_user')->id;
            $warehouse->save();
            return redirect()->route('warehouses.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('warehouses.index')->with('error','刪除失敗');
        }
    }

    public function delete_file($file_no,$warehouse,$file_id)
    {
        try{
            $warehouse = Warehouse::find($warehouse);
            if($file_no == 1){
                $warehouse->file_1 = null;
            } else if($file_no == 2){
                $warehouse->file_2 = null;
            } else if($file_no == 3){
                $warehouse->file_3 = null;
            }
            $warehouse->save();

            $gallery = Gallery::find($file_id);
            $gallery->delete_flag = 1;
            $gallery->deleted_at = Now();
            $gallery->deleted_user = session('admin_user')->id;
            $gallery->save();

            return redirect()->route('warehouses.edit',$warehouse->id)->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('warehouses.edit',$warehouse->id)->with('error','刪除失敗');
        }

    }

    private function thumb_process($origin_file_name, $tmp_file_name, $img_type, $tmp_w, $tmp_h)
    {
        $width = $tmp_w;
        $height = $tmp_h;

        $src_image = imagecreatefromstring(file_get_contents(asset('upload/'.$origin_file_name)));
        $src_width = imagesx($src_image);
        $src_height = imagesy($src_image);

        $tmp_image_width = 0;
        $tmp_image_height = 0;
        if ($src_width / $src_height >= $width / $height) {
            $tmp_image_width = $width;
            $tmp_image_height = round($tmp_image_width * $src_height / $src_width);
        } else {
            $tmp_image_height = $height;
            $tmp_image_width = round($tmp_image_height * $src_width / $src_height);
        }

        $tmpImage = imagecreatetruecolor($tmp_image_width, $tmp_image_height);
        imagecopyresampled($tmpImage, $src_image, 0, 0, 0, 0, $tmp_image_width, $tmp_image_height, $src_width, $src_height);

        $final_image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($final_image, 255, 255, 255);
        imagefill($final_image, 0, 0, $color);

        $x = round(($width - $tmp_image_width) / 2);
        $y = round(($height - $tmp_image_height) / 2);

        imagecopy($final_image, $tmpImage, $x, $y, 0, 0, $tmp_image_width, $tmp_image_height);

        if($img_type == '.jpeg' || $img_type == '.jpg'){
            $img_type = '.jpeg';
        }
        $func = "image".substr($img_type,1);
        $func($final_image,'upload/'.$tmp_file_name);
        if(isset($final_image)) {imagedestroy($final_image);}

    }
    private function file_process($name, $file)
    {
        $imageName = $file->getClientOriginalName();
        $fileType = strtolower(strrchr($imageName,'.'));
        $fileName = time().'_'.mt_rand(100,999);
        $thumb_origin = $fileName.$fileType;
        if($fileType == '.jpeg' || $fileType == '.png' || $fileType == '.jpg'){
            $thumb_450 = $fileName.'_450'.$fileType;
            $file->move('upload', $thumb_origin);
            $this->thumb_process($thumb_origin, $thumb_450, $fileType, 450, 450);
        } else {
            $thumb_450 = "file_image.jpg";
            $file->move('upload', $thumb_origin);
        }
        $img = new Gallery;
        $img->name = $name;
        $img->origin_file_name = $imageName;
        $img->file_name = $thumb_origin;
        $img->thumb_name = $thumb_450;
        // material = 2 , warehouse = 3
        $img->category = 3;
        $img->created_user = session('admin_user')->id;
        $img->delete_flag = 0;
        $img->save();
        return $img->id;
    }
}
