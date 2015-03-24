<script type="text/javascript">

  function filter_date(ele) {
    var name = $(ele).attr('name');
    var value = ele.options[ele.selectedIndex].value;
    $('#filter_loading').show();
    var url = "{{ $opencart->url->link('business/profit') }}";
    url += '&date_from='+value+'&date_to='+value;
    window.location.href = url;
  }
</script>
<div class="alert alert-info" id="filter_loading" style="display:none;"><i class="fa fa-clock-o"></i> Loading....
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<div class="well">
  <div class="row" style="margin-left: 0px;margin-right: 0px;">
    <div class="col-sm-3">
      <div class="form-group">
        <label class="control-label" for="input-date">月份</label>
        <select name="filter_date" id="input-date" class="form-control" onChange="filter_date(this);">
          @foreach ($dates as $date)
          <option value="{{ $date['value'] }}"@if ($date['value'] == $selected_dates) selected="selected"@endif >{{ $date['text'] }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
</div>
<div id="bonus-content">
  <h1>Bonus History</h1>
  <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-name">Bonus總金額</label>
    <div class="col-sm-10">
      <span class="form-text">
        {{ $bonus_sum }}
      </span>
    </div>
  </div>
  <div id="bonus" class="table-responsive">
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
</div>