<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Image;
use Illuminate\Support\Facades\Storage;

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

    static public function updateTitle($id, $title)
    {
        $file = StorageFile::find($id);

        if ($file) {
            $file->title = $title ?? '';
            $file->save();
        }
    }

    // 處理檔案清單
    static public function packFiles($request, $record)
    {
        // 3 個欄位 (file_1, file_2, file_3)
        for ($i = 0; $i <= 2; $i++) {
            $col = 'file_' . ($i + 1);
            $file_input = 'file_file_' . $i;
            if(isset($request->file_will_delete[$i]) && $request->file_will_delete[$i] == 1) {
                // 刪除檔案
                $file = StorageFile::find($record->$col);

                if ($file) {
                    Storage::delete('public/files/' . $file->file_name);
                    Storage::delete('public/thunmbs/' . $file->file_name);
                    $file->delete();
                }
            } elseif ($request->hasFile($file_input)) {
                // 覆蓋檔案
                $file_id = StorageFile::upload($request->$file_input, $request->file_title[$i]);
                $record->$col = $file_id;
            } else {
                // 儲存檔案標題
                if ($record->$col) {
                    StorageFile::updateTitle($record->$col, $request->file_title[$i]);
                }
            }
        }

        return [$record->file_1, $record->file_2, $record->file_3];
    }
}
