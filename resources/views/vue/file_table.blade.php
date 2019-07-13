<script type="text/x-template" id="file-table">
    <div class="card card-default mt-3">
        <div class="card-body">
            <h4>
                圖片附件 <small class="text-danger">有圖片才會保存名稱</small>
            </h4>

            <hr>

            <div class="row">
                <div v-for="(item, idx) in files" class="col-4">
                    @{{ idx + 1 }}. 名稱：<input type="text"
                        class="form-control"
                        v-model="item.title"
                        name="file_title[]"
                        style="width: 200px;" />

                    <div class="alert alert-secondary mt-3 d-center file-preview" role="alert" style="min-height: 120px;">
                        <template v-if="previews[idx]">
                            <img
                                v-bind:src="previews[idx].data"
                                @click="previewImage(idx)"
                                class="mw-100"
                                style="max-height: 100px;" />

                            <a href="javascript: void(0)"
                                @click="handleFileDelete(idx)"
                                class="file-delete text-danger">
                                <i class="fas fa-trash-alt"></i> 刪除
                            </a>
                        </template>

                        <template v-else-if="files[idx].file_name">
                            <img
                                v-bind:src="'/storage/thumbs/' + files[idx].file_name"
                                @click="previewImageFromFile(idx)"
                                class="mw-100"
                                style="max-height: 100px;" />

                            <a href="javascript: void(0)"
                                @click="handleFileDelete(idx)"
                                class="file-delete text-danger">
                                <i class="fas fa-trash-alt"></i> 刪除
                            </a>
                        </template>

                        <template v-else>
                            沒有圖片
                        </template>
                    </div>

                    <input type="file"
                        ref="file"
                        :name="'file_file_' + idx"
                        accept="image/*"
                        @change="handleFileUpload(idx)"
                        style="width: 200px;" />
                    <input type="hidden" name="file_will_delete[]" v-model="item.file_will_delete" />
                </div>
            </div>
        </div>
    </div>
</script>

<script>
Vue.component('file-table', {
    template: '#file-table',

    data: function () {
        return {
            previews: [null, null, null]
        }
    },

    props: {
        files: Array
    },

    methods: {
        handleFileUpload(idx){
            let file = this.$refs.file[idx].files[0]
            let reader = new FileReader()

            reader.addEventListener("load", function () {
                let preview = {}
                preview.data = reader.result
                preview.image = file.type.match('image.*')
                preview.name = file.name
                preview.size = file.size
                this.$set(this.previews, idx, preview)
            }.bind(this), false)

            reader.readAsDataURL(file)
        },

        handleFileDelete(idx) {
            let file = {file_will_delete: 1}
            this.$set(this.files, idx, file)
            this.$set(this.previews, idx, null)
            this.$refs.file[0].value = '';
        },

        previewImage(idx) {
            var image = new Image()
            image.src = this.previews[idx].data

            var w = window.open("")
            w.document.write(image.outerHTML)
        },

        previewImageFromFile(idx) {
            window.open("/storage/files" + this.files[idx].file_name)
        }
    }
})
</script>
