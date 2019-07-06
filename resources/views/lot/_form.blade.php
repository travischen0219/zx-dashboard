<div class="form-group">
    <label for="code">批號：</label>
    <input type="text" name="code" id="code" value="{{ $lot->code }}"
        class="form-control" placeholder="請輸入批號">
</div>

<div class="form-group">
    <label for="code">案件名稱：</label>
    <input type="text" name="name" id="name" value="{{ $lot->name }}"
        class="form-control" placeholder="請輸入案件名稱">
</div>

<div class="form-group">
    {!! Form::label('customer_id', '客戶：') !!}

    {!! Form::button('按此選擇客戶', [
        'class' => 'btn btn-primary',
        'onclick' => 'alert(123)'
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('start_date', '日期：') !!}

    {!! Form::text('start_date', $lot->start_date, [
        'class' => 'form-control',
        'placeholder' => '請輸入開始日期'
    ]) !!}

    {!! Form::label('end_date', '至', ['class' => 'w-auto mx-2']) !!}

    {!! Form::text('end_date', $lot->end_date, [
        'class' => 'form-control',
        'placeholder' => '請輸入結束日期'
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('status', '案件狀態：') !!}

    {!! Form::text('status', $lot->status, [
        'class' => 'form-control',
        'placeholder' => '請輸入案件狀態'
    ]) !!}

    <span class="text-warning ml-2">連動報價單</span>
</div>

<div class="form-group">
    {!! Form::label(null, '是否完工：') !!}

    {!! Form::checkbox('is_finished', '1', $lot->is_finished, ['id' => 'is_finished']) !!}
    {!! Form::label('is_finished', '已經完工取勾選此方塊', ['class' => 'w-auto text-primary']) !!}
</div>

<div class="form-group">
    {!! Form::label('memo', '備註：', ['class' => 'align-top']) !!}

    {!! Form::textarea('memo', $lot->memo, [
        'class' => 'form-control',
        'placeholder' => '請輸入備註',
        'rows' => 5
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('', '') !!}

    {!! Form::submit('新增批號', [
        'class' => 'btn btn-primary',
        'onclick' => 'alert(123)'
    ]) !!}

    {!! Form::button('取消', [
        'class' => 'btn btn-link ml-3',
        'onclick' => 'alert(123)'
    ]) !!}
</div>
