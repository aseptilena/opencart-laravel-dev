<div id="content">
  <script type="text/javascript">
  function action_filter_month(ele) {
    var value = ele.options[ele.selectedIndex].value;
    $('#filter_loading').show();
    $('#form-bonus').submit();
  }
  </script>
  <div class="page-header">
    <div class="container-fluid">
      <h1>會員等級</h1>
      <ul class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
        <li><a href="{{ $breadcrumb->href }}">{{ $breadcrumb->text }}</a></li>
        @endforeach
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> Customer List</h3>
      </div>
      <div class="panel-body">
        <div class="alert alert-info" id="filter_loading" style="display:none;"><i class="fa fa-clock-o"></i> Loading....
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <div class="well">
          <form action="{{ $opencart->url->link('report/bonus', 'hi=d' , 'SSL') }}" method="get" enctype="multipart/form-data" id="form-bonus" >
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label" for="input-month">月份</label>
                  <select name="filter_month" id="input-month" class="form-control" onChange="action_filter_month(this);">
                    @foreach ($months as $month)
                    <option value="{{ $month->value }}"@if ($filter_month == $month->value) selected="selected"@endif >{{ $month->text }}</option>
                    @endforeach
                  </select>
                  <input type="hidden" name="token" value="{{ $opencart->session->data['token'] }}">
                  <input type="hidden" name="route" value="report/bonus">
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>名稱</th>
                <th>數目</th>
                <th>佔比</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th class="text-right">訂單總金額</th>
                <th class="text-right">{{ number_format($order_total) }}</th>
                <th class="text-right"></th>
              </tr>
              <tr>
                <th class="text-right">利潤總金額</th>
                <th class="text-right">{{ number_format($profit_summary->consumption) }}</th>
                <th class="text-right">{{ MyMath::percent($profit_summary->consumption, $order_total) }}</th>
              </tr>
              <tr>
                <th class="text-right">紅利總金額</th>
                <th class="text-right">{{ number_format($profit_summary->bonus) }}</th>
                <th class="text-right">{{ MyMath::percent($profit_summary->bonus, $profit_summary->consumption) }}</th>
              </tr>
              <tr>
                <th class="text-right">會員總人數</th>
                <th class="text-right">{{ $total_customer }}</th>
                <th class="text-right"></th>
              </tr>
              <tr>
                <th class="text-right">新增總人數</th>
                <th class="text-right">{{ $new_customer }}</th>
                <th class="text-right">{{ MyMath::percent($new_customer, $total_customer) }}</th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>