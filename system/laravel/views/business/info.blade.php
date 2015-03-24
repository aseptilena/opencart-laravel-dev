<div class="pull-right">
  <button type="submit" id="button-info" data-toggle="tooltip" title="儲存" class="btn btn-primary"><i class="fa fa-save"></i></button>
</div>
<h1>Info</h1>
<form id="form-info" class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-2 control-label">會員等級</label>
    <div class="col-sm-10">
      <span class="form-text">
        {{{ $customer->level->title }}}
      </span>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">目前個人總消費紅利</label>
    <div class="col-sm-10">
      <span class="form-text">
        {{ number_format($customer->profit_record_summary()->consumption) }}
      </span>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">目前團隊總消費紅利</label>
    <div class="col-sm-10">
      <span class="form-text">
      {{ number_format($customer->team_consumption()) }}
      </span>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">獲得總回饋</label>
    <div class="col-sm-10">
      <span class="form-text">
      截至2014年12月，您獲得Bonus共{{ number_format($customer->profit_record_summary()->bonus) }}元
      </span>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">回饋餘額</label>
    <div class="col-sm-10">
      <span class="form-text">
      {{ number_format($customer->customerTransactions()->sum('amount')) }}
      </span>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">延長資格</label>
    <div class="col-sm-10">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">等待升級</label>
    <div class="col-sm-10">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>等待升階</th>
            <th>條件</th>
            <th>達成情況</th>
          </tr>
        </thead>
        <tbody>
        @if (count($ready_levels) > 0)
          @foreach ($ready_levels as $level)
          <tr>
            <td class="text-left">{{ $level->title }}</td>
            <td class="text-left">{{ $level->conditionDescription() }}</td>
            <td class="text-left">{{ $level->achieveDescription($customer) }}</td>
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
  <div class="form-group">
    <label class="col-sm-2 control-label">升級記錄</label>
    <div class="col-sm-10">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>時間</th>
            <th>資料</th>
          </tr>
        </thead>
        <tbody>
        @if (count($upgrade_histories) > 0)
          @foreach ($upgrade_histories as $history)
          <tr>
            <td class="text-left">{{ $history->month() }}</td>
            <td class="text-left">{{ $history->record }}</td>
          </tr>
          @endforeach
        @else
          <tr>
            <td class="text-center" colspan="2">沒有內容</td>
          </tr>
        @endif
        </tbody>
      </table>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">紅利紀錄</label>
    <div class="col-sm-10">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>時間</th>
            <th>資料</th>
          </tr>
        </thead>
        <tbody>
        @if (count($profit_records) > 0)
          @foreach ($profit_records as $record)
          <tr>
            <td class="text-left">{{ $record->month() }}</td>
            <td class="text-left">{{ $record->bonus_record }}</td>
          </tr>
          @endforeach
        @else
          <tr>
            <td class="text-center" colspan="2">沒有內容</td>
          </tr>
        @endif
        </tbody>
      </table>
    </div>
  </div>
</form>