<h1>Bonus History</h1>
<div class="form-group required">
  <label class="col-sm-2 control-label" for="input-name">Bonus總金額</label>
  <div class="col-sm-10">
    <span class="form-text">
      {{ $bonus_sum }}
    </span>
  </div>
</div>
<div id="profit" class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>來源</th>
        <th>明細</th>
        <th>種類</th>
        <th>獎金</th>
      </tr>
    </thead>
    <tbody>
    @if (count($bonus_histories) > 0)
      @foreach ($bonus_histories as $history)
      <tr>
        <td class="text-left">{{ $history->id }}</td>
        <td class="text-right">{{ $history->source->lastname }}</td>
        <td class="text-right">{{ $history->amount }} X {{ $history->rate }}%</td>
        <td class="text-right">{{ $history->typeName() }}</td>
        <td class="text-right">{{ $history->bonus }}</td>
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