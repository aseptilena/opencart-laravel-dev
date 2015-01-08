<h1>Info</h1>
<div class="form-group">
  <label class="col-sm-2 control-label">聘階</label>
  <div class="col-sm-10">
    {{ $customer->level->title }}
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">目前個人總PV</label>
  <div class="col-sm-10">
    {{ number_format($customer->profit_record_summary()->consumption) }}
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">目前團隊總PV</label>
  <div class="col-sm-10">
    {{ number_format($customer->team_consumption()) }}
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">獲得總Bonus</label>
  <div class="col-sm-10">
    截至2014年12月，您獲得Bonus共{{ number_format($customer->profit_record_summary()->bonus) }}元
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">已領取Bonus</label>
  <div class="col-sm-10">
    {{ number_format($customer->draw) }}
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">延長資格</label>
  <div class="col-sm-10">
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">聘階升級</label>
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
      @foreach ($ready_levels as $level)
      <tr>
        <td class="text-left">{{ $level->title }}</td>
        <td class="text-left">{{ $level->conditionDescription() }}</td>
        <td class="text-left">{{ $level->achieveDescription($customer) }}</td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>