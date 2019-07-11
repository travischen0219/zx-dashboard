<script type="text/x-template" id="file-table">
    <div class="card card-default mt-3">
        <div class="card-body">
            <h4>圖片附件</h4>

            <hr>

            <div class="row">
                <div v-for="(item, idx) in files" class="col-4">
                    @{{ idx + 1 }}. 名稱：<input type="text"
                        class="form-control"
                        v-model="item.title"
                        name="file_title[]"
                        style="width: 200px;" />

                    <div class="alert alert-secondary mt-3 d-center file-preview" role="alert" style="min-height: 120px;">
                        <template v-if="preview[idx]">
                            <img
                                v-if="preview[idx].image"
                                v-bind:src="preview[idx].data"
                                onclick="previewImage(this.src)"
                                class="mw-100"
                                style="max-height: 100px;" />

                            <a href="javascript: void(0)" @click="deleteFile(idx)" class="file-delete">
                                <i class="fas fa-trash-alt"></i> 刪除這張圖片
                            </a>
                        </template>

                        <template v-else>
                            沒有圖片
                        </template>
                    </div>

                    <input type="file"
                        ref="file"
                        name="file_file[]"
                        accept="image/*"
                        @change="handleFileUpload(idx)"
                        style="width: 200px;" />
                    <input type="hidden" name="will_delete[]" value="0" />
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
            file: {
                title: '',
                file: '',
                will_delete: 0
            },
            preview: [null, null, null]
        }
    },

    props: {
        files: Array
    },

    computed: {
        total_cost: function() {
            var total_cost = 0
            this.rows.forEach(element => {
                total_cost += parseFloat(element.cost) * parseFloat(element.amount)
            })

            return total_cost
        },
        total_price: function() {
            var total_price = 0
            this.rows.forEach(element => {
                total_price += parseFloat(element.price) * parseFloat(element.amount)
            })

            return total_price
        }
    },

    methods: {
        addFile: function() {
            this.files.push(Object.assign({}, this.file))
            this.$forceUpdate();
        },
        deleteFile: function(idx) {
            this.file.will_delete = 1
            console.log(this.file)
            this.$set(this.files, idx, this.file)
            this.$forceUpdate();
        },

        handleFileUpload(idx){
            let file = this.$refs.file[idx].files[0]
            let reader = new FileReader()

            reader.addEventListener("load", function () {
                let preview = {}
                preview.data = reader.result
                preview.image = file.type.match('image.*')
                preview.name = file.name
                preview.size = file.size
                this.$set(this.preview, idx, preview)
            }.bind(this), false)

            reader.readAsDataURL(file)
        }
    },

    mounted: function () {
        while (this.files.length < 3) {
            this.addFile()
        }
    }
});

// 套用物料
function applyMaterial(str, idx) {
    var material = JSON.parse(str)
    // console.log(material)
    var duplicate = false

    material = {
        id: material.id,
        category: material.material_categories_code,
        code: material.fullCode,
        name: material.fullName,
        amount: 0,
        cal_amount: 0,
        buy_amount: 0,
        unit: material.unit,
        cost: material.cost ? parseFloat(material.cost) : 0,
        price: material.price ? parseFloat(material.price) : 0,
        cal: material.cal ? parseInt(material.cal) : 0,
        cal_unit: material.cal_unit,
        cal_price: material.cal_price ? parseFloat(material.cal_price) : 0
    }

    // 檢查是否重複
    app.rows.forEach(function(row, i) {
        if (material.id == row.id && i != idx) {
            duplicate = true
        }
    })

    if (duplicate) {
        swalOption.type = "error"
        swalOption.title = '物料已經存在'
        swal.fire(swalOption)
    } else {
        app.$set(app.rows, idx, material)
    }
}

// 套用物料模組
function applyMaterialModule(str) {
    var material_module = JSON.parse(str)
    console.log(material_module.material2)

    var duplicate = false
    material_module.material2.forEach(function(material, idx) {

        material = {
            id: material.id,
            category: material.material_categories_code,
            code: material.fullCode,
            name: material.fullName,
            amount: 0,
            cal_amount: 0,
            buy_amount: 0,
            unit: material.unit,
            cost: material.cost ? parseFloat(material.cost) : 0,
            price: material.price ? parseFloat(material.price) : 0,
            cal: material.cal ? parseInt(material.cal) : 0,
            cal_unit: material.cal_unit,
            cal_price: material.cal_price ? parseFloat(material.cal_price) : 0
        }
    })



    // 檢查是否重複
    app.rows.forEach(function(row, i) {
        if (material.id == row.id && i != idx) {
            duplicate = true
        }
    })

    if (duplicate) {
        swalOption.type = "error"
        swalOption.title = '物料已經存在'
        swal.fire(swalOption)
    } else {
        app.$set(app.rows, idx, material)
    }
}

// 顯示 / 隱藏 批量修改
function batchEditAmount() {
    $("#batchEdit").fadeToggle('fast', function() {
        $("#batchAmount").focus()
    })
}

function checkMaterials() {
    var existMaterial = []
    var sameMaterial = []

    app.rows.forEach(function(element, index) {
        if (parseInt(element.id) != 0) {
            if (existMaterial.includes(element.id)) {
                sameMaterial.push(element.name)
            } else {
                existMaterial.push(parseInt(element.id))
            }
        }
    })

    // 有重複物料
    if (sameMaterial.length > 0) {
        swalOption.title = '選擇的物料有重複'
        swalOption.text = sameMaterial.join('\n')
        swal(swalOption)

        return false
    } else {
        return true
    }
}

function previewImage(data) {
    var image = new Image()
    image.src = data

    var w = window.open("")
    w.document.write(image.outerHTML)
}
</script>
