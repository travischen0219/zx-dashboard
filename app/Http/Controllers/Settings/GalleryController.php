<?php

namespace App\Http\Controllers\Settings;

use App\Model\Gallery;
use App\Model\User;
use App\Libs\ImageProcess;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Gallery::where('delete_flag','0')->paginate(16);
        return view('settings.gallery.show',compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.gallery.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return strlen($request->name);die;
        if($request->hasFile('upload_image'))
        {
            $rules = [
                'name' => 'required | string',
                'upload_image' => 'required | mimes:jpeg,jpg,png',
            ];
            $messages = [
                'name.required' => '名稱 必填',
                'upload_image.required' => '未上傳檔案',
                'upload_image.mimes' => '必須為 jpeg, jpg, png 檔案',
            ];
            $this->validate($request, $rules, $messages);

            $imageName = $request->upload_image->getClientOriginalName();
            $fileType = strtolower(strrchr($imageName,'.'));
            $fileName = time().'_'.mt_rand(100,999);
            $thumb_origin = $fileName.$fileType;
            $thumb_450 = $fileName.'_450'.$fileType;
            $request->upload_image->move('upload', $thumb_origin);

            $this->thumb_process($thumb_origin, $thumb_450, $fileType, 450, 450);

            $img = new Gallery;
            $img->name = $request->name;
            $img->origin_file_name = $imageName;
            $img->file_name = $thumb_origin;
            $img->thumb_name = $thumb_450;
            $img->category = 1;
            $img->created_user = session('admin_user')->id;
            $img->delete_flag = 0;
            $img->save();
            return redirect()->route('gallery.index')->with('message','上傳檔案成功');
        } else {
            return redirect()->back()->with('error','未選擇圖片上傳');
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
        //
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
        //
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
            $img = Gallery::find($id);
            $img->delete_flag = 1;
            $img->deleted_at = Now();
            $img->deleted_user = session('admin_user')->id;
            $img->save();
            return redirect()->route('gallery.index')->with('message','刪除成功');
        } catch (Exception $e) {
            return redirect()->route('gallery.index')->with('error','刪除失敗');
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

    public function file_download($id)
    {
        $img = Gallery::find($id);
        $img_path = 'upload/'.$img->file_name;
        $headers = array('Content-Type: application/octet-stream');

        return Response::download($img_path, $img->origin_file_name, $headers);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $images = Gallery::where('delete_flag','0')->where('name','like','%'.$search.'%')->paginate(16);
        return view('settings.gallery.show',compact('images'));
    }
}
