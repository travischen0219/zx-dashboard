{{-- 物料清單 --}}
<div class="panel panel-default">
    <div class="panel-body">
        <h4>
            物料清單
            <button type="button" @click="$addRow($refs)" class="btn btn-primary btn-add">
                <i class="fa fa-plus"></i> 新增物料
            </button>
        </h4>
        <hr>

        <table id="materialTable" class="table">
            <thead>
                <tr>
                    <th width="70" nowrap>操作</th>
                    <th>物料</th>
                    <th width="150" nowrap>
                        數量
                        <a href="javascript: batchEditAmount();">
                            <small>批量修改</small>
                        </a>
                        <div id="batchEdit" style="margin-top: 2px; display: none;">
                            <input type="text" name="batchAmount" id="batchAmount" size="5" style="width: 50px;">
                            <button type="button" @click="batchAmountApply">x 倍數</button>
                        </div>
                    </th>
                    <th width="150" nowrap>單位</th>
                    <th width="150" nowrap>單位成本</th>
                    <th width="150" nowrap>成本小計</th>
                    <th width="150" nowrap>單位售價</th>
                    <th width="150" nowrap>售價小計</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(item, index) in materialRows">
                    <td title="操作">
                        <button type="button" @click="deleteRow(index)"
                            class="btn red">
                            <i class="fa fa-remove"></i>
                        </button>
                    </td>
                    <td title="物料">
                        <input type="hidden" name="material[]" v-model="item.id">
                        <button type="button"
                            @click="listMaterial(index);"
                            class="btn btn-default btn-block">
                            @{{ item.id === 0 ? '請選擇物料' : item.code + ' ' + item.name }}
                        </button>
                    </td>
                    <td title="數量">
                        <input type="text"
                            class="form-control"
                            v-model="item.amount"
                            name="materialAmount[]"
                            placeholder="請輸入數字">
                    </td>
                    <td title="單位">@{{ item.unit ? units[item.unit].name : '' }}</td>
                    <td title="單位成本">
                        <input type="text"
                            class="form-control"
                            v-model="item.cost"
                            name="materialCost[]"
                            placeholder="請輸入數字">
                    </td>
                    <td title="成本小計">
                        $@{{ item.amount * item.cost | number_format }}
                    </td>
                    <td title="單位售價">
                        <input type="text"
                            class="form-control"
                            v-model="item.price"
                            name="materialPrice[]"
                            placeholder="請輸入數字">
                    </td>
                    <td title="售價小計">
                        $@{{ item.amount * item.price | number_format }}
                    </td>
                </tr>
            </tbody>
        </table>

        <hr>

        <div class="text-right">
            共有 @{{ materialRows.length }} 種物料
            &nbsp;&nbsp;&nbsp;&nbsp;
            成本總計：$@{{ total_cost | number_format }}
            <input type="hidden" name="total_cost" v-model="total_cost">
            &nbsp;&nbsp;&nbsp;&nbsp;
            售價總計：$@{{ total_price | number_format }}
            <input type="hidden" name="total_price" v-model="total_price">
        </div>
    </div>
</div>

<script>
function batchEditAmount() {
    $("#batchEdit").fadeToggle('fast');
}

function applyMaterial(str) {
    var material = JSON.parse(str);

    material = {
        id: material.id,
        code: material.fullCode,
        name: material.fullName,
        amount: 0,
        unit: material.unit,
        cost: material.cost ? parseFloat(material.cost) : 0,
        price: material.price ? parseFloat(material.price) : 0
    };

    app.$set(app.materialRows, app.currnetIndex, material);
    app.$forceUpdate();
}

function checkMaterials() {
    var existNaN = false;
    var materialSum = 0;

    var existMaterial = [];
    var sameMaterial = [];

    app.materialRows.forEach(function(element, index) {
        // 檢查非數字
        if(isNaN(element.amount) || isNaN(element.cost) || isNaN(element.price)) {
            existNaN = true;
        }

        // 檢查物料數量
        materialSum += element.id

        // 檢查重複物料
        if (existMaterial.includes(element.id)) {
            sameMaterial.push(element.name)
        } else {
            existMaterial.push(element.id);
        }
    });

    // 有非數字
    if(existNaN){
        swalOption.title = '數量、成本或售價必須為數字';
        swal(swalOption);
        return false;
    }

    // 物料數量
    if(materialSum == 0){
        swalOption.title = '未選擇任何物料';
        swal(swalOption);
        return false;
    }

    // 有重複物料
    if (sameMaterial.length > 0) {
        swalOption.title = '選擇的物料有重複';
        swalOption.text = sameMaterial.join('\n');
        swal(swalOption);

        return false;
    }

    return true;
}
</script>
