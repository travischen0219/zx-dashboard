<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Image;

class StorageFile extends Model
{
    static public function upload($input, $title)
    {
        $file = new StorageFile;

        // 原始檔名
        $file->origin_name = $input->getClientOriginalName();

        $file->file_name = basename($input->store('public/files'));
        $file->title = $title;

        // 存一份縮圖
        $thumb = Image::make(public_path('/storage/files/' . $file->file_name));
        $thumb->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $thumb->save('storage/thumbs/' . basename($file->file_name));

        $file->save();

        return $file->id;
    }

    static public function allJson($files)
    {
        return json_encode([
            $files[0] ?? (object)[],
            $files[1] ?? (object)[],
            $files[2] ?? (object)[]
        ], JSON_HEX_QUOT | JSON_HEX_TAG);
    }
}
