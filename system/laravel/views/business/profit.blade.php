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
<div id="profit-content">
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
</div>