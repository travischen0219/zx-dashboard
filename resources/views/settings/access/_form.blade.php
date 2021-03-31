@include('includes.messages')

<div class="form-group form-md-line-input form-md-floating-label">
  <label for="name">權限角色名稱：</label>
  <input type="text" name="name" class="form-control" id="name" value="{{ old('name') ?? $access->name }}">

  <div class="px-5 py-2">
    <table class="table table-bordered w-auto">
      <caption style="caption-side: top;">權限內容</caption>
      <thead>
        <tr class="bg-primary text-white">
          <th>名稱</th>
          <th class="text-center">無權限</th>
          <th class="text-center">檢視</th>
          <th class="text-center">管理</th>
        </tr>
      </thead>

      <tbody>
        @foreach ($groups as $gKey => $group)
          @php
            $access_mode = $access->getAccess($gKey);
          @endphp
          <tr>
            <td>{{ $group }}</td>
            @foreach ($modes as $mKey => $mode)
              <td width="100" align="center">
                <input type="radio"
                  {{ $access_mode == $mKey ? 'checked' : ''}}
                  name="{{ $gKey }}"
                  id="{{ $gKey }}-{{ $mKey }}"
                  value="{{ $mKey }}">
              </td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
