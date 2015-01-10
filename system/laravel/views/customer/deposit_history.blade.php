<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>時間</th>
        <th>管理者</th>
        <th>記錄</th>
        <th>金額變化</th>
        <th>備註</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($deposit_histories as $history)
    <tr>
      <td class="text-left">{{ $history->id }}</td>
      <td class="text-left">{{ $history->created_at }}</td>
      <td class="text-left">{{ $history->user->username }}</td>
      <td class="text-left">{{{ $history->detail }}}</td>
      <td class="text-right">{{ $history->amount }}</td>
      <td class="text-left">{{{ $history->comment }}}</td>
    </tr>
    @endforeach
    </tbody>
  </table>
</div>
<fieldset>
  <legend>允許提款</legend>
  <form id="draw-form" class="form-horizontal">
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-deposit-status">變更狀態</label>
      <div class="col-sm-10">
        <select name="deposit_status_id" id="input-deposit-status" class="form-control">
          @foreach ($statuses as $status)
          <option value="{{ $status->id }}" @if ($deposit->status == $status->id) selected="selected" @endif >{{ $status->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-draw"><span data-toggle="tooltip" title="輸入點數">提撥金額</span></label>
      <div class="col-sm-10">
        <input type="text" name="draw_amount" value="" placeholder="金額" id="input-draw" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-deposit-reply">給使用者訊息</label>
      <div class="col-sm-10">
        <textarea name="deposit_reply" rows="8" id="input-deposit-reply" class="form-control">{{{ $deposit->reply }}}</textarea>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-draw-comment">備註</label>
      <div class="col-sm-10">
        <textarea name="draw_comment" rows="8" id="input-draw-comment" class="form-control"></textarea>
      </div>
    </div>
    <div class="text-right">
      <button type="button" id="button-draw" data-loading-text="送出中" class="btn btn-primary" data-id="{{ $deposit->id }}"><i class="fa fa-plus-circle"></i> 送出要求</button>
    </div>
  </form>
</fieldset>