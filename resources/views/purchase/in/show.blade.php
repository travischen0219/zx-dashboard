@extends('b4.app')

@section('title', '採購進貨 - 查看採購')
@section('page-header')
    <i class="fas fa-shopping-cart active-color mr-1"></i>採購進貨 - 查看採購
@endsection

@section('content')

    <form id="app" class="form">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>採購單號：</label>
                    <span class="text-primary">{{ $in->code }}</span>
                </div>

                <div class="form-group">
                    <label for="lot_id">批號：</label>
                    @if (isset($lots[$in->lot_id]))
                        {{ $lots[$in->lot_id]->code }} {{ $lots[$in->lot_id]->name }}
                    @endif
                </div>

                <div class="form-group">
                    <label for="supplier_id">供應商：</label>
                    @if (isset($suppliers[$in->supplier_id]))
                        {{ $suppliers[$in->supplier_id]->code }} {{ $suppliers[$in->supplier_id]->shortName }}
                    @endif
                </div>

                <div class="form-group">
                    <label for="manufacturer_id">加工廠商：</label>
                    @if (isset($manufacturers[$in->manufacturer_id]))
                        {{ $manufacturers[$in->manufacturer_id]->code }} {{ $manufacturers[$in->manufacturer_id]->shortName }}
                    @endif
                </div>

                <div class="form-group">
                    <label for="buy_date">採購日期：</label>{{ $in->buy_date }}
                    <label for="should_arrive_date">預計到貨：</label>{{ $in->should_arrive_date }}
                    <label for="arrive_date">實際到貨：</label>{{ $in->arrive_date }}
                </div>

                <div class="form-group">
                    <label for="status">狀態：</label>{{ $statuses[$in->status] }}
                </div>

                <div class="form-group">
                    <label for="memo" class="align-top">備註：</label>
                    {{ nl2br($in->memo) }}
                </div>

                <material-view
                    :materials="materials"
                    :units="units"
                    :module="true"
                    ref="materialView">
                </material-view>

                <div class="form-group mt-3">
                    <label> </label>
                    <button type="button"
                        onclick="location.href='{{ url()->previous() }}'"
                        class="btn btn-primary">
                        返回採購清單
                    </button>
                </div>

            </div>
        </div>
    </form>

@endsection

@section('script')
    @include('purchase.in._script')
@endsection
