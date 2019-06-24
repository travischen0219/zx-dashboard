@extends('b4.app')

@section('title','倉儲資料')
@section('page-header')
    <i class="fas fa-warehouse active-color"></i> 基本資料 - 倉儲資料
@endsection

@section('css')
    <style>
        a:hover {
            text-decoration: none;
        }
        .info-box {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            height: 80px;
            display: flex;
            cursor: default;
            background-color: #fff;
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
        }
        .info-box .icon {
            overflow: hidden;
            width: 80px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .info-box .icon i {
            font-size: 64px;
            color: rgba(255, 255, 255, 0.5);
            transition: .2s ease;
        }
        .info-box .icon i:hover {
            transform: rotate(45deg);
            zoom: 1.5;
        }
        .info-box .content {
            font-size: 24px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 1;
        }
        .info-box .content .text {
            margin: 0;
            font-size: 16px;
            position: absolute;
            top: 8px;
            right: 12px;
        }

        .info-box .content .text a+a {
            margin-left: 2px;
        }

        .info-box .content .title {
            font-size: 18px;
        }

        .info-box .text {
            display: none;
        }

        #popup {
            width: 400px;
            height: 160px;
            display: block;
            background-color: white;
            margin: auto;

        }

        #popup p {
            padding-top: 20px;
            display: block;
            text-align: center;
        }

        #popup img {
            display: block;
            margin: 0 auto;
            padding: 0px 20px;
        }
    </style>
@endsection

@section('content')

    <form role="form" class="form-inline" action="{{ route('warehouses.search') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label mr-2">分類：</label>
            <select class="form-control" name="search_category" id="search_category">
                @foreach($cates as $cate)
                <option value="{{$cate->id}}" {{ $search_code == $cate->id ? 'selected' : '' }}>
                    {{$cate->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group ml-4">
            <label class="control-label mr-2">編號/名稱：</label>
            <input type="text" class="form-control" name="search_codeOrName" id="search_codeOrName" value="{{ $search_like }}">
        </div>

        <button type="submit" class="btn btn-primary ml-2">搜 尋</button>
    </form>

    @include('includes.messages')
    <a href="{{ route('warehouses.create') }}" class="mt-3 btn btn-primary">
        <i class="fa fa-plus"></i> 新增倉儲
    </a>

    @if(count($warehouses) > 0)
        <div class="row my-4">
            @foreach($warehouses as $warehouse)
                <div class="col-md-6">
                    <div class="info-box hover-zoom-effect">
                        <div class="icon bg-primary">
                            <a href='javascript: barcode("{{$warehouse->fullName}}", "{{$warehouse->code}}");'>
                                <i class="fas fa-border-all"></i>
                            </a>
                        </div>
                        <div class="content ml-2">
                            <div class="text">
                                <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="col-amber">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="javascript:;" class="col-amber" onclick="
                                    if(confirm('確定要刪除嗎 ?')){
                                        event.preventDefault();
                                        document.getElementById('delete-form-{{$warehouse->id}}').submit();
                                    } else {
                                        event.preventDefault();
                                    }"><i class="fas fa-trash-alt text-danger"></i>
                                </a>
                                <form id="delete-form-{{$warehouse->id}}"
                                    action="{{ route('warehouses.destroy', $warehouse->id) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                            </div>
                            <div>
                                <a href="{{ route('warehouses.show', $warehouse->id) }}">
                                    <div class="title text-warning">
                                        {{$warehouse->warehouse_category->name}}
                                        <span class="text-primary">
                                            {{$warehouse->fullName}}
                                        </span>
                                    </div>
                                    <div>
                                        {{$warehouse->code}}
                                        @if($warehouse->status == 2)
                                        <span style="color:red;"> (關閉)</span>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="h4 mt-3">查無資料</div>
    @endif

@endsection

@section('script')

<script>
    $(function () {
        $(".info-box").hover(function () {
            $(this).find('.text').show();
        }, function () {
            $(this).find('.text').hide();
        });

    });

    function barcode(title, code) {
        $.magnificPopup.open({
            showCloseBtn: false,
            enableEscapeKey: false,
            closeOnBgClick: true,
            fixedContentPos: false,
            modal: false,
            type: 'ajax',
            items: {
                src: "{{route('barcode')}}"
            },
            ajax: {
                settings: {
                    type: 'GET',
                    data: {
                        title: title,
                        code: code
                    }
                }
            }
        });
    }
</script>
@endsection
