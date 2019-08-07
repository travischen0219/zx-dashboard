<div class="card card-default mt-3">
    <div class="card-body">
        <h4>圖片附件</h4>

        <hr>

        <div class="row">
            @foreach ($files as $key => $file)
                <div class="col-4">
                    {{ $key + 1 }}. 名稱：<input type="text"
                        class="form-control"
                        name="file_title[]"
                        style="width: 200px;" />

                    <div class="alert alert-secondary mt-3 d-center file-preview" role="alert" style="min-height: 120px;">
                            <div id="file_preview_{{ $key }}" style="display: none;">
                                <img
                                    src=""
                                    id="file_image_{{ $key }}"
                                    onclick="previewImage(this.src)"
                                    class="mw-100"
                                    style="max-height: 100px;" />

                                <a href="javascript: void(0)"
                                    onclick="handleFileDelete({{ $key }})"
                                    class="file-delete text-danger">
                                    <i class="fas fa-trash-alt"></i> 刪除
                                </a>
                            </div>

                            <div id="file_none_{{ $key }}">沒有圖片</div>
                    </div>

                    <input type="file"
                        id="file_file_{{ $key }}"
                        name="file_file[]"
                        accept="image/*"
                        onchange="handleFileUpload(this, {{ $key }})"
                        style="width: 200px;" />
                    <input type="hidden" name="file_will_delete[]" id="file_will_delete_{{ $key }}" value="0" />
                </div>
            @endforeach
        </div>
    </div>
</div>
