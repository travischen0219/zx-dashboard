<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Model\Account_payable;
use App\Model\Buy;
use App\Model\Buy_to_stock;
use App\Model\Material;
use App\Model\Material_module;
use App\Model\Material_unit;
use App\Model\Processing_list;
use App\Model\Semi_finished_schedule;
use App\Model\User;
use Illuminate\Http\Request;

class BuyController extends Controller
{
    // status = 1 未採購
    // status = 2 已採購
    // status = 3 已到貨
    // status = 11 轉半成品
    // status = 4 採購轉入庫

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $buys = Buy::where('delete_flag', '0')->get();

        return view('purchase.buy.show', compact('buys', 'search_code'));
    }

    public function search(Request $request)
    {
        $search_code = $request->search_category;
        if ($request->search_lot_number) {
            if ($search_code == 'all') {
                $buys = Buy::where('delete_flag', '0')->where('lot_number', 'like', '%' . $request->search_lot_number . '%')->get();
            } else {
                $buys = Buy::where('delete_flag', '0')->where('status', $search_code)->where('lot_number', 'like', '%' . $request->search_lot_number . '%')->get();
            }
        } else {
            if ($search_code == 'all') {
                $buys = Buy::where('delete_flag', '0')->get();
            } else {
                $buys = Buy::where('delete_flag', '0')->where('status', $search_code)->get();
            }
        }
        return view('purchase.buy.show', compact('buys', 'search_code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $buy_no = date("Ymd") . "001";
        $last_buy_no = Buy::orderBy('buy_no', 'DESC')->first();
        if ($last_buy_no) {
            if ($last_buy_no->buy_no >= $buy_no) {
                $buy_no = $last_buy_no->buy_no + 1;
            }
        }
        return view('purchase.buy.create', compact('buy_no'));
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
            'lot_number' => 'required',
            'supplier' => 'required',
            // 'buyDate' => 'required',
            // 'expectedReceiveDate' => 'required',
        ];
        $messages = [
            'lot_number.required' => '批號 必填',
            'supplier.required' => '尚未選擇 供應商',
            // 'buyDate.required' => '採購日期 必填',
            // 'expectedReceiveDate.required' => '預計到貨日 必填',
        ];
        $this->validate($request, $rules, $messages);

        $total_materials = count($request->material);
        $material = [];
        $materialCalAmount = [];
        $materialCalAmount2 = [];
        $materialCalUnit = [];
        $materialCalPrice = [];
        $materialAmount = [];
        $materialUnit = [];
        $materialPrice = [];
        for ($i = 0; $i < $total_materials; $i++) {
            if ($request->material[$i]) {
                $material[] = $request->material[$i];
                $materialCalAmount[] = $request->materialCalAmount[$i];
                $materialCalAmount2[] = $request->materialCalAmount2[$i];
                $materialCalUnit[] = $request->materialCalUnit[$i];
                $materialCalPrice[] = $request->materialCalPrice[$i];
                $materialAmount[] = $request->materialAmount[$i];
                $materialPrice[] = $request->materialPrice[$i];
            }
        }

        if (count($material) > 0) {
            $materials = ['material' => $material,
                'materialCalAmount' => $materialCalAmount,
                'materialCalAmount2' => $materialCalAmount2,
                'materialCalUnit' => $materialCalUnit,
                'materialCalPrice' => $materialCalPrice,
                'materialAmount' => $materialAmount,
                'materialPrice' => $materialPrice
            ];

            try {
                $buy = new Buy;
                $buy->buy_no = $request->buy_no;
                $buy->lot_number = $request->lot_number;
                $buy->supplier = $request->supplier;
                $buy->materials = serialize($materials);
                $buy->buyDate = $request->buyDate;
                $buy->expectedReceiveDate = $request->expectedReceiveDate;
                $buy->memo = $request->memo;
                $buy->status = 1;
                $buy->created_user = session('admin_user')->id;
                $buy->delete_flag = 0;
                $buy->save();
                return redirect()->route('buy.index')->with('message', '新增成功');
            } catch (Exception $e) {
                return redirect()->route('buy.index')->with('error', '新增失敗');
            }

        } else {
            return redirect()->back()->with('error', '未選擇任何物料');
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
        $buy = Buy::find($id);
        $materials = unserialize($buy->materials);

        $total_materials = count($materials['material']);
        $materialCount = 0;
        $data = '';
        for ($i = 0; $i < $total_materials; $i++) {

            $material = Material::where('id', $materials['material'][$i])->first();

            $units = Material_unit::where('delete_flag', '0')->get();

            $select_str = '';
            $select_hidden = '';

            if ($materials['materialCalUnit'][$i] == 0 || $materials['materialCalUnit'][$i] == '') {
                $select_str = '<option value="0" selected>未指定</option>';
                if ($buy->status == 2) {
                    $select_hidden = '<input type="hidden" name="materialCalUnit[]" id="materialCalUnit' . $materialCount . '" class="materialCalUnit" value="0">';
                }
            } else {
                $select_str = '<option value="0" >未指定</option>';
                if ($buy->status == 2) {
                    $select_hidden = '<input type="hidden" name="materialCalUnit[]" id="materialCalUnit' . $materialCount . '" class="materialCalUnit" value="' . $materials['materialCalUnit'][$i] . '">';
                }
            }
            foreach ($units as $unit) {
                $select_str .= '<option value="' . $unit->id . '" ';
                if ($materials['materialCalUnit'][$i] == $unit->id) {
                    $select_str .= ' selected';
                }
                $select_str .= '> ' . $unit->name . '</option>';
            }

            if ($materials['materialAmount'][$i] > 0) {
                $materialAmount = $materials['materialAmount'][$i];
            } else {
                $materialAmount = '';
            }

            if ($materials['materialPrice'][$i] > 0) {
                $materialPrice = $materials['materialPrice'][$i];
            } else {
                if ($material->cost > 0) {
                    $materialPrice = $material->cost;
                } else {
                    $materialPrice = '';
                }

            }

            $style = '';
            $readonly = '';
            $disabled = '';
            $disabled_material = '';
            if ($buy->status == 2 || $buy->status == 4) {
                $style = ' style="display:none"';
                $readonly = ' readonly';
                $disabled = ' disabled';
                $disabled_material = ' disabled';
            }
            if ($buy->status == 2) {
                $readonly = '';
            }
            if ($buy->status == 3) {
                $style = ' style="display:none"';
                $disabled_material = ' disabled';
            }
            $data .= '<tr id="materialRow' . $materialCount . '" class="materialRow">
                <td><a href="javascript:delMaterial(' . $materialCount . ');" class="btn red" ' . $style . '><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial(' . $materialCount . ');" id="materialName' . $materialCount . '" name="materialName' . $materialCount . '" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;" ' . $disabled_material . '> ' . $material->fullCode . ' ' . $material->fullName . '</button>
                    <input type="hidden" name="material[]" id="material' . $materialCount . '" class="select_material" value="' . $materials['material'][$i] . '">
                </td>

                <td>
                    <input type="text" name="materialCalAmount2[]" id="materialCalAmount2' . $materialCount . '" class="materialCalAmount2" placeholder="0" style="width:80px; height: 30px; vertical-align: middle;" value="' . number_format($materials['materialCalAmount2'][$i], 2, '.', '') . '" ' . $readonly . '>
                </td>
                <td>
                    ' . $select_hidden . '
                    <select id="materialCalUnit' . $materialCount . '" name="materialCalUnit[]" class="materialcalUnit" style="width: 80px; line-height: 30px; vertical-align: middle;" ' . $disabled . '>
                    ' . $select_str . '
                    </select>
                </td>
                <td>
                    <input type="text" name="materialCalPrice[]" id="materialCalPrice' . $materialCount . '" class="materialCalPrice" placeholder="0" style="width: 80px;height: 30px; vertical-align: middle;" value="' . $materials['materialCalPrice'][$i] . '" ' . $readonly . '>
                </td>


                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount' . $materialCount . '" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:80px; height: 30px; vertical-align: middle;" value="' . $materialAmount . '" ' . $readonly . '>
                    / <span id="materialStock' . $materialCount . '">' . $material->stock . '</span>
                </td>
                <td>
                    <input type="text" name="materialCalAmount[]" id="materialCalAmount' . $materialCount . '" class="materialCalAmount" placeholder="0" style="width:80px; height: 30px; vertical-align: middle;" value="' . number_format($materials['materialCalAmount'][$i], 2, '.', '') . '" ' . $readonly . '>
                </td>
                <td>
                    <span id="materialUnit_show' . $materialCount . '" style="width: 100px; line-height: 30px; vertical-align: middle;">' . $material->material_unit_name->name . '</span>
                </td>
                <td>
                    <input type="text" name="materialPrice[]" id="materialPrice' . $materialCount . '" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 80px;height: 30px; vertical-align: middle;" value="' . $materialPrice . '" ' . $readonly . '>
                </td>
                <td>
                    <span id="materialSubTotal' . $materialCount . '" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }

        if ($buy->updated_user > 0) {
            $updated_user = User::where('id', $buy->updated_user)->first();
        } else {
            $updated_user = User::where('id', $buy->created_user)->first();
        }
        return view('purchase.buy.edit', compact('buy', 'materials', 'data', 'materialCount', 'updated_user'));
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
        if ($request->status == 4) {
            if ($request->realReceiveDate == '') {
                return redirect()->back()->with('error', '實際到貨日 必填');
            }
        }
        $rules = [
            // 'buyDate' => 'required',
            // 'expectedReceiveDate' => 'required',
        ];
        $messages = [
            // 'buyDate.required' => '採購日期 必填',
            // 'expectedReceiveDate.required' => '預計到貨日 必填',
        ];
        $this->validate($request, $rules, $messages);

        $total_materials = count($request->material);
        $material = [];
        $materialCalAmount = [];
        $materialCalAmount2 = [];
        $materialCalUnit = [];
        $materialCalPrice = [];
        $materialAmount = [];
        $materialUnit = [];
        $materialPrice = [];
        for ($i = 0; $i < $total_materials; $i++) {
            if ($request->material[$i]) {
                $material[] = $request->material[$i];
                $materialCalAmount[] = $request->materialCalAmount[$i];
                $materialCalAmount2[] = $request->materialCalAmount2[$i];
                $materialCalUnit[] = $request->materialCalUnit[$i];
                $materialCalPrice[] = $request->materialCalPrice[$i];
                $materialAmount[] = $request->materialAmount[$i];
                $materialPrice[] = $request->materialPrice[$i];
            }
        }

        if (count($material) > 0) {
            $materials = ['material' => $material, 'materialCalAmount' => $materialCalAmount, 'materialCalAmount2' => $materialCalAmount2, 'materialCalUnit' => $materialCalUnit, 'materialCalPrice' => $materialCalPrice, 'materialAmount' => $materialAmount, 'materialPrice' => $materialPrice];

            if ($request->status == 1 || $request->status == 2 || $request->status == 3) {

                try {
                    $buy = Buy::find($id);
                    $buy->lot_number = $request->lot_number;
                    $buy->materials = serialize($materials);
                    $buy->buyDate = $request->buyDate;
                    $buy->expectedReceiveDate = $request->expectedReceiveDate;
                    $buy->realReceiveDate = $request->realReceiveDate;
                    $buy->memo = $request->memo;
                    $buy->status = $request->status;
                    $buy->updated_user = session('admin_user')->id;
                    $buy->save();

                    return redirect()->route('buy.index')->with('message', '修改成功');
                } catch (Exception $e) {
                    return redirect()->route('buy.index')->with('error', '修改失敗');
                }
            } else if ($request->status == 11) {

                $warehouse = [];
                for ($j = 0; $j < $total_materials; $j++) {
                    $material_p = Material::find($material[$j]);
                    $warehouse[] = $material_p->warehouse;
                }
                $processing_materials = ['material' => $material, 'warehouse' => $warehouse, 'materialAmount' => $materialCalAmount, 'materialAmount2' => $materialCalAmount2];

                try {
                    $buy = Buy::find($id);
                    $buy->lot_number = $request->lot_number;
                    $buy->manufacturer = $request->manufacturer;
                    $buy->materials = serialize($materials);
                    $buy->buyDate = $request->buyDate;
                    $buy->expectedReceiveDate = $request->expectedReceiveDate;
                    $buy->realReceiveDate = $request->realReceiveDate;
                    $buy->memo = $request->memo;
                    $buy->status = $request->status;
                    $buy->updated_user = session('admin_user')->id;
                    $buy->save();

                    $processing = new Semi_finished_schedule;
                    $processing->lot_number = $request->lot_number;
                    $processing->materials = serialize($processing_materials);
                    $processing->start_date = date("Y-m-d");
                    $processing->status = 1;
                    $processing->created_user = session('admin_user')->id;
                    $processing->delete_flag = 0;
                    $processing->save();

                    $processing_list = new Processing_list;
                    $processing_list->semi_finished_schedule_id = $processing->id;
                    $processing_list->process_function_id = 0;
                    $processing_list->status = 1;
                    $processing_list->orderby = 1;
                    $processing_list->manufacturer_id = $request->manufacturer;
                    $processing_list->created_user = session('admin_user')->id;
                    $processing_list->delete_flag = 0;
                    $processing_list->save();

                    return redirect()->route('buy.index')->with('message', '修改成功 並新增一筆加工單');
                } catch (Exception $e) {
                    return redirect()->route('buy.index')->with('error', '修改失敗');
                }

            } else if ($request->status == 4) {
                // $total_check = count($materials['material']);
                // for($i=0; $i < $total_check; $i++){
                //     $mat_check = Material::find($materials['material'][$i]);
                //     if($mat_check->warehouse == 0){
                //         return redirect()->back()->with('error',$mat_check->fullName.' 尚未指定倉儲資料，需指定後才能轉入庫');
                //     }
                // }
                try {
                    $buy = Buy::find($id);
                    $buy->lot_number = $request->lot_number;
                    $buy->materials = serialize($materials);
                    $buy->buyDate = $request->buyDate;
                    $buy->expectedReceiveDate = $request->expectedReceiveDate;
                    $buy->realReceiveDate = $request->realReceiveDate;
                    $buy->memo = $request->memo;
                    $buy->status = $request->status;
                    $buy->updated_user = session('admin_user')->id;
                    $buy->save();

                    // 建立採購轉入庫
                    $buy_to_stock = new Buy_to_stock;
                    $buy_to_stock->buy_id = $id;
                    $buy_to_stock->buy_no = $buy->buy_no;
                    $buy_to_stock->lot_number = $request->lot_number;
                    $buy_to_stock->supplier = $buy->supplier;
                    $buy_to_stock->materials = serialize($materials);
                    $buy_to_stock->realReceiveDate = $request->realReceiveDate;
                    $buy_to_stock->inStockDate = $request->realReceiveDate;
                    $buy_to_stock->status = 1;
                    $buy_to_stock->created_user = session('admin_user')->id;
                    $buy_to_stock->delete_flag = 0;
                    $buy_to_stock->save();

                    // 建立應付帳款
                    $account_payable = new Account_payable;
                    $account_payable->lot_number = $request->lot_number;
                    $account_payable->supplier = $buy->supplier;
                    $account_payable->buy_no = $buy->buy_no;
                    $account_payable->createDate = date("Y-m-d");

                    $account_payable_no = date("Ymd") . "001";
                    $last_account_payable_no = Account_payable::orderBy('account_payable_no', 'DESC')->first();
                    if ($last_account_payable_no) {
                        if ($last_account_payable_no->account_payable_no >= $account_payable_no) {
                            $account_payable_no = $last_account_payable_no->account_payable_no + 1;
                        }
                    }

                    $account_payable->account_payable_no = $account_payable_no;
                    $account_payable->materials = serialize($materials);
                    $account_payable->status = 1;
                    $account_payable->created_user = session('admin_user')->id;
                    $account_payable->delete_flag = 0;
                    $account_payable->save();

                    return redirect()->route('buy.index')->with('message', '轉入庫中 並 新增一筆應付帳款單');
                } catch (Exception $e) {
                    return redirect()->route('buy.index')->with('error', '存檔失敗');
                }
            }
        } else {
            return redirect()->back()->with('error', '未選擇任何物料');
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
        try {
            $buy = Buy::find($id);
            $buy->delete_flag = 1;
            $buy->deleted_at = Now();
            $buy->deleted_user = session('admin_user')->id;
            $buy->save();
            return redirect()->route('buy.index')->with('message', '刪除成功');
        } catch (Exception $e) {
            return redirect()->route('buy.index')->with('error', '刪除失敗');
        }
    }

    public function addRow(Request $request)
    {
        $materialCount = $request->materialCount;

        $units = Material_unit::where('delete_flag', '0')->get();
        $select_str = '<option value="0" >未指定</option>';
        foreach ($units as $unit) {
            $select_str .= '<option value="' . $unit->id . '" > ' . $unit->name . '</option>';
        }

        $data = '<tr id="materialRow' . $materialCount . '" class="materialRow">
            <td><a href="javascript:delMaterial(' . $materialCount . ');" class="btn red"><i class="fa fa-remove"></i></a></td>
            <td>
                <button type="button" onclick="openSelectMaterial(' . $materialCount . ');" id="materialName' . $materialCount . '" name="materialName' . $materialCount . '" class="btn btn-default get_material_name" style="width: 80%; margin-right: 10px; overflow: hidden;"> 請選擇物料</button>
                <input type="hidden" name="material[]" id="material' . $materialCount . '" class="select_material">
            </td>
            <td>
                <input type="text" name="materialCalAmount2[]" id="materialCalAmount2' . $materialCount . '" class="materialCalAmount2" placeholder="0" style="width:80px; height: 30px; vertical-align: middle;">
            </td>
            <td>
                <select id="materialCalUnit' . $materialCount . '" name="materialCalUnit[]" class="materialCalUnit" style="width: 80px; line-height: 30px; vertical-align: middle;">
                ' . $select_str . '
                </select>
            </td>
            <td>
                <input type="text" name="materialCalPrice[]" id="materialCalPrice' . $materialCount . '" class="materialCalPrice" placeholder="0" style="width: 80px;height: 30px; vertical-align: middle;">
            </td>

            <td nowrap>
                <input type="text" name="materialAmount[]" id="materialAmount' . $materialCount . '" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:80px; height: 30px; vertical-align: middle;">
                / <span id="materialStock' . $materialCount . '">0</span>
            </td>
            <td>
            <input type="text" name="materialCalAmount[]" id="materialCalAmount' . $materialCount . '" class="materialCalAmount" placeholder="0" style="width:80px; height: 30px; vertical-align: middle;">
        </td>
            <td>
                <span id="materialUnit_show' . $materialCount . '" style="width: 100px; line-height: 30px; vertical-align: middle;">無</span>
            </td>
            <td>
                <input type="text" name="materialPrice[]" id="materialPrice' . $materialCount . '" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 80px;height: 30px; vertical-align: middle;">
            </td>
            <td>
                <span id="materialSubTotal' . $materialCount . '" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
            </td>
        </tr>';

        return $data;
    }

    public function addModule(Request $request)
    {
        $id = $request->id;
        $materialCount = $request->materialCount;
        $modules = Material_module::find($id);
        $materials = unserialize($modules->materials);
        $total_materials = count($materials['material']);
        $materialCount = $materialCount + 1;
        $return = [];
        $data = '';
        $disabled = '';
        $style = '';
        $readonly = '';

        for ($i = 0; $i < $total_materials; $i++) {
            $material = Material::where('id', $materials['material'][$i])->first();

            $units = Material_unit::where('delete_flag', '0')->get();
            if ($material->cal_unit == 0 || $material->cal_unit == '') {
                $select_str = '<option value="0" selected>未指定</option>';
            } else {
                $select_str = '<option value="0" >未指定</option>';
            }
            foreach ($units as $unit) {
                $select_str .= '<option value="' . $unit->id . '" ';
                if ($material->cal_unit == $unit->id) {
                    $select_str .= ' selected';
                }
                $select_str .= '> ' . $unit->name . '</option>';
            }

            if ($material->cal_price > 0) {
                $materialCalPrice = $material->cal_price;
            } else {
                $materialCalPrice = '';
            }

            $data .= '<tr id="materialRow' . $materialCount . '" class="materialRow">
                <td><a href="javascript:delMaterial(' . $materialCount . ');" class="btn red" ' . $style . '><i class="fa fa-remove"></i></a></td>
                <td>
                    <button type="button" onclick="openSelectMaterial(' . $materialCount . ');" id="materialName' . $materialCount . '" name="materialName' . $materialCount . '" class="btn btn-default get_material_name" style="width: 100%; margin-right: 10px; overflow: hidden;color:blue;" ' . $disabled . '> ' . $material->fullCode . ' ' . $material->fullName . '</button>
                    <input type="hidden" name="material[]" id="material' . $materialCount . '" class="select_material" value="' . $materials['material'][$i] . '">
                </td>
                <td>
                    <input type="text" name="materialCalAmount2[]" id="materialCalAmount2' . $materialCount . '" class="materialCalAmount2" placeholder="0" style="width:100px; height: 30px; vertical-align: middle;" value="' . $materials['materialAmount'][$i] . '" ' . $readonly . '>
                </td>
                <td>
                    <select id="materialCalUnit' . $materialCount . '" name="materialCalUnit[]" class="materialCalUnit" style="width: 100px; line-height: 30px; vertical-align: middle;">
                    ' . $select_str . '
                    </select>
                </td>
                <td>
                    <input type="text" name="materialCalPrice[]" id="materialCalPrice' . $materialCount . '" class="materialCalPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="' . $materialCalPrice . '" ' . $readonly . '>
                </td>

                <td>
                    <input type="text" name="materialAmount[]" id="materialAmount' . $materialCount . '" class="materialAmount" placeholder="0" onkeyup="total();" onchange="total();" style="width:100px; height: 30px; vertical-align: middle;">
                </td>
                <td>
                    <input type="text" name="materialCalAmount[]" id="materialCalAmount' . $materialCount . '" class="materialCalAmount" placeholder="0" style="width:100px; height: 30px; vertical-align: middle;" value="' . $materials['materialAmount'][$i] . '" ' . $readonly . '>
                </td>
                <td>
                    <span id="materialUnit_show' . $materialCount . '" style="width: 100px; line-height: 30px; vertical-align: middle;">' . $material->material_unit_name->name . '</span>
                </td>
                <td>
                    11<input type="text" name="materialPrice[]" id="materialPrice' . $materialCount . '" onkeyup="total();" onchange="total();" class="materialPrice" placeholder="0" style="width: 100px;height: 30px; vertical-align: middle;" value="' . $materials['cost'][$i] . '" ' . $readonly . '>
                </td>
                <td>
                    <span id="materialSubTotal' . $materialCount . '" class="materialSubTotal" style="line-height: 30px; vertical-align: middle;">0</span>
                </td>
            </tr>';
            $materialCount++;
        }
        $return['data'] = $data;
        $return['materialCount'] = $materialCount;
        return $return;
    }
}
