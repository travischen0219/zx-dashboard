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
      var amount = $("#amount").val()
      if(isNaN(amount) || amount <= 0) {
        alert('數量請輸入大於0的數字')
        return false;
      } else {
        return true
      }
    }
  </script>
  <hr>

  <h3>入庫紀錄</h3>

  <table class="table table-bordered" id="data">
    <thead>
      <tr>
          <th>類別</th>
          <th>日期</th>
          <th>單號</th>
          <th nowrap>批號</th>
          <th>物料</th>
          <th>數量</th>
          <th nowrap>(前→後) 數量</th>
          <th nowrap>目前庫存</th>
      </tr>
    </thead>
    @foreach ($stocks as $stock)


    @endforeach
  </table>
@endsection
