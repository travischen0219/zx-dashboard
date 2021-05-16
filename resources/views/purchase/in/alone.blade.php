@extends('b4.selector')

@section('title','單獨入庫')

@section('content')
  <button type="button" class="btn btn-danger float-right" onclick="parent.$.magnificPopup.close()">關閉</button>

  @include('includes.messages')
  <div>
    <label>採購單號：</label>
    <span class="text-primary">P{{ $in->code }}</span>

    <label class="ml-3">物料：</label>
    <span class="text-primary">
      {{ $material->fullCode }}
      {{ $material->fullName }}
    </span>
  </div>

  <form action="/purchase/aloneIn" method="POST" class="form form-inline" onsubmit="return checkNumber();">
    <div class="form-group">
      @csrf
      <label for="amount" class="w-auto">入庫數量：</label>
      <input type="text" name="amount" id="amount" class="form-control mr-2">
      <input type="hidden" name="in_id" value="{{ $in->id }}">
      <input type="hidden" name="material_id" value="{{ $material->id }}">
      <button type="submit" class="btn btn-primary">確定入庫</button>
    </div>
  </form>

  <script>
    function checkNumber() {
      if (confirm('確定入庫？')) {
        var amount = $("#amount").val()
        if(isNaN(amount) || amount <= 0) {
          alert('數量請輸入大於0的數字')
          return false;
        } else {
          return true
        }
      } else {
        return false
      }
    }
  </script>
  <hr>

  <h3>入庫紀錄</h3>

  <table class="table table-bordered" id="data">
    <thead>
      <tr>
          <th>日期</th>
          <th>數量</th>
          <th nowrap>(前→後) 數量</th>
          <th nowrap>目前庫存</th>
      </tr>
    </thead>

    <tbody>
      @foreach ($stocks as $stock)
        <tr>
          <td>{{ $stock->stock_date }}</td>
          <td title="數量">{{ $stock->amount }}{{ $unit }}</td>
          <td title="(前→後) 數量" nowrap>
            {{ $stock->amount_before }}{{ $unit }}
            <span class="rotate-up">→</span>
            {{ $stock->amount_after }}{{ $unit }}
          </td>
          <td title="目前庫存">{{ $stock->material->stock ?? 0 }}{{ $unit }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
