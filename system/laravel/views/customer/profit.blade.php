
<h1>Profit History</h1>
<div class="form-group required">
  <label class="col-sm-2 control-label" for="input-name">Profit總金額</label>
  <div class="col-sm-10">
    <span class="form-text">
      {{ $profit_sum }}
    </span>
  </div>
</div>
<div id="profit" class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>金額</th>
        <th>時間</th>
      </tr>
    </thead>
    <tbody>
    @if (count($profit_histories) > 0)
      @foreach ($profit_histories as $history)
      <tr>
        <td class="text-left">{{ $history->id }}</td>
        <td class="text-right">{{ $history->amount }}</td>
        <td class="text-right">{{ $history->created_at }}</td>
      </tr>
      @endforeach
    @else
      <tr>
        <td class="text-center" colspan="3">沒有內容</td>
      </tr>
    @endif
    </tbody>
  </table>
</div>