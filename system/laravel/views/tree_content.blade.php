<script type="text/javascript">
  $(function() {
    $('#ntree').jstree();
    $('#btree').jstree();
  })
</script>

<ul class="nav nav-tabs">
  @foreach ($tabs as $tab)
  <li @if ($active_tab == $tab['link']) class="active" @endif><a href="#{{ $tab['link'] }}" data-toggle="tab">{{ $tab['name'] }}</a></li>
  @endforeach
</ul>
<div class="tab-content" id="tree-tabs">
  <div class="tab-pane @if ($active_tab == 'tab-profit-history') active @endif" id="tab-profit-history">
    <h1>Profit History</h1>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-name">Profit總金額</label>
      <div class="col-sm-10" style="padding-top: 9px;">
        <p>{{ $profit_sum }}</p>
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
        @foreach ($profit_histories as $history)
        <tr>
          <td class="text-left">{{ $history->id }}</td>
          <td class="text-right">{{ $history->amount }}</td>
          <td class="text-right">{{ $history->created_at }}</td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="tab-pane @if ($active_tab == 'tab-bonuse-history') active @endif" id="tab-bonuse-history">
    <h1>Bonus History</h1>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-name">Bonus總金額</label>
      <div class="col-sm-10" style="padding-top: 9px;">
        {{ $bonus_sum }}
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
        @foreach ($bonus_histories as $history)
        <tr>
          <td class="text-left">{{ $history->id }}</td>
          <td class="text-right">{{ $history->source->lastname }}</td>
          <td class="text-right">{{ $history->amount }} X {{ $history->rate }}%</td>
          <td class="text-right">{{ $history->typeName() }}</td>
          <td class="text-right">{{ $history->bonus }}</td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="tab-pane @if ($active_tab == 'tab-ntree') active @endif" id="tab-ntree">
    <h1>N Tree</h1>
    <div id="ntree" class="form-group">
      {{ $ntree }}
    </div>
  </div>
  <div class="tab-pane @if ($active_tab == 'tab-btree') active @endif" id="tab-btree">
    <h1>B Tree</h1>
    <div id="btree" class="form-group">
      {{ $btree }}
    </div>
  </div>
</div>