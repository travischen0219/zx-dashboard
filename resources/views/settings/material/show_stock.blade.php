
<style>
    #sample_4_filter input {
        width:300px !important;
    }
    .dataTables_extended_wrapper .table.dataTable{
        margin:0 !important;
    }
</style>

<div id="pop_stock">

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <a href="javascript:parent.close_show_stock();" class="btn btn-danger red" id="close_show">關閉視窗</a>
                        {{ $title }} 庫存紀錄
                    </div>
                    <div class="tools"> </div>
                </div>

                <div class="portlet-body">
                    <table class="table table-striped table-bordered" id="sample_4" >
                        <thead>
                            <tr>
                                <th width="10%">序號</th>
                                <th width="15%">入/出庫時間</th>
                                <th width="15%">操作</th>
                                <th width="15%">倉儲</th>
                                <th width="15%">數量</th>
                                <th width="15%">總庫存</th>
                                <th width="15%">記錄人員</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($stocks as $stock)

                                <tr>
                                    <td>{{ $stock->stock_no }}</td>
                                    <td>{{ $stock->stock_date }}</td>
                                    <td>
                                        @if($stock->stock_option == 1)
                                            <span style="color:green;">入庫</span>
                                        @elseif($stock->stock_option == 2)
                                            <span style="color:red;">誤差處理</span>
                                        @elseif($stock->stock_option == 3)
                                            <span style="color:blue;">起始庫存</span>
                                        @elseif($stock->stock_option == 4)
                                            <span style="color:blue;">採購轉入庫</span>
                                        @elseif($stock->stock_option == 5)
                                            <span style="color:red;">退貨入庫</span>
                                        @elseif($stock->stock_option == 11)
                                            <span style="color:purple;">出庫</span>
                                        @elseif($stock->stock_option == 21)
                                            <span style="color:purple;">調撥出庫</span>
                                        @elseif($stock->stock_option == 22)
                                            <span style="color:purple;">調撥入庫</span>
                                        @elseif($stock->stock_option == 31)
                                            <span style="color:purple;">餘料處理</span>
                                        @endif
                                    </td>
                                    <td>{{ $stock->warehouse_name->code }}</td>

                                    @if($stock->stock_option == 1 || $stock->stock_option == 2 || $stock->stock_option == 3 || $stock->stock_option == 4 || $stock->stock_option == 5)
                                        <td align="center"
                                            @if($stock->quantity < 0)
                                                style="color:white;background-color: red;"> {{ $stock->quantity }}
                                            @else
                                                style="color:white;background-color: green;"> {{ $stock->quantity }}
                                            @endif
                                        </td>
                                    @elseif($stock->stock_option == 11)
                                        <td align="center" style="color:white;background-color: red;">
                                            -{{ $stock->quantity }}
                                        </td>
                                    @elseif($stock->stock_option == 21)
                                        <td align="center" style="color:white;background-color: red;"> -{{ $stock->quantity }} </td>
                                    @elseif($stock->stock_option == 22)
                                        <td align="center" style="color:white;background-color: green;"> {{ $stock->quantity }} </td>
                                    @elseif($stock->stock_option == 31)
                                        <td align="center" style="color:white;background-color: red;"> -{{ $stock->quantity }} </td>
                                    @endif

                                    @if($stock->stock_option == 1 || $stock->stock_option == 2 || $stock->stock_option == 3 || $stock->stock_option == 4 || $stock->stock_option == 5)
                                        <td align="center">
                                            @if(number_format($stock->total_start_quantity + $stock->quantity,2,'.','') < 0)
                                                <span style="color:red;">{{ number_format($stock->start_quantity + $stock->quantity,2,'.','') }}</span>
                                            @else
                                                <span>{{ number_format($stock->total_start_quantity + $stock->quantity,2,'.','') }}</span>
                                            @endif
                                        </td>
                                    @elseif($stock->stock_option == 11)
                                        <td align="center">
                                            @if(number_format($stock->total_start_quantity - $stock->quantity,2,'.','') < 0)
                                                <span style="color:red;">{{ number_format($stock->total_start_quantity - $stock->quantity,2,'.','') }}</span>
                                            @else
                                                <span>{{ number_format($stock->total_start_quantity - $stock->quantity,2,'.','') }}</span>
                                            @endif
                                        </td>
                                    @elseif($stock->stock_option == 21)
                                        <td align="center">
                                            @if(number_format($stock->total_start_quantity - $stock->quantity,2,'.','') < 0)
                                                <span style="color:red;">{{ number_format($stock->start_quantity - $stock->quantity,2,'.','') }}</span>
                                            @else
                                                <span>{{ number_format($stock->total_start_quantity - $stock->quantity,2,'.','') }}</span>
                                            @endif
                                        </td>
                                    @elseif($stock->stock_option == 22)
                                        <td align="center">
                                            <span>{{ number_format($stock->total_start_quantity,2,'.','') }}</span>
                                        </td>
                                    @elseif($stock->stock_option == 31)
                                        <td align="center">
                                            @if(number_format($stock->total_start_quantity - $stock->quantity,2,'.','') < 0)
                                                <span style="color:red;">{{ number_format($stock->start_quantity - $stock->quantity,2,'.','') }}</span>
                                            @else
                                                <span>{{ number_format($stock->total_start_quantity - $stock->quantity,2,'.','') }}</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td align="center">{{ $stock->user_name->fullname }}</td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->

        </div>
    </div>
</div>




<script>
    $('#sample_4').dataTable({
        "oLanguage": {
            "sInfo": "共 _TOTAL_ 筆資料 (顯示 _START_ 到 _END_ 筆)",
            "sZeroRecords": "查無資料",
            "sLengthMenu": "每頁顯示 _MENU_ 筆",
            "sInfoEmpty": "查無資料",
            "sSearch": "搜尋:",
            "oPaginate": {
                "sFirst": "第一筆",
                "sPrevious": "上一筆",
                "sNext": "下一筆",
                "sLast": "最後一筆"
            }
        },
        "language": {
            "aria": {
                "sortAscending": ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            },
            "emptyTable": "查無資料",
            "info": "共 _TOTAL_ 筆資料 (顯示 _START_ 到 _END_ 筆)",
            "infoEmpty": "查無資料",
            "infoFiltered": "(filtered1 from _MAX_ total entries)",
            "lengthMenu": "每頁顯示 _MENU_ 筆",
            "search": "搜尋:",
            "zeroRecords": "查無資料",
        },
        buttons: [
            { extend: 'print', className: 'btn dark btn-outline' ,text: '列印'},
            { extend: 'copy', className: 'btn red btn-outline' ,text: '複製'},
            { extend: 'excel', className: 'btn green btn-outline ' ,text: 'Excel 下載'},
            { extend: 'csv', className: 'btn purple btn-outline ' ,text: 'CSV 下載'},
            { extend: 'colvis', className: 'btn blue btn-outline', text: '欄位篩選'}
        ],
        responsive: false,
        "order": false,
        "lengthMenu": [
            [5, 10, 15, 20, 50, 100, -1],
            [5, 10, 15, 20, 50, 100, "All"]
        ],
        "pageLength": 15,
        "pagingType": "full_numbers",
        "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

    });


</script>
