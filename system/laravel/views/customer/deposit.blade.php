<div class="form-group">
  <label class="col-sm-2 control-label">Bonus總金額</label>
  <div class="col-sm-10">
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">已提取總金額</label>
  <div class="col-sm-10">
  </div>
</div>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>時間</th>
        <th>要求金額</th>
        <th>狀態</th>
        <th>已提撥</th>
        <th>提撥金額</th>
        <th>備註</th>
        <th>查看</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($deposits as $deposit)
    <tr>
      <td class="text-left">{{ $deposit->id }}</td>
      <td class="text-left">{{ $deposit->created_at }}</td>
      <td class="text-left">{{ $deposit->request_amount }}</td>
      <td class="text-right">{{ $deposit->statusName() }}</td>
      <td class="text-right">{{ $deposit->remitName() }}</td>
      <td class="text-right">{{ $deposit->remit_amount }}</td>
      <td class="text-right">{{ $deposit->comment }}</td>
      <td class="text-right"><a data-toggle="tooltip" title="View" class="btn btn-info" data-id="{{ $deposit->id }}" onclick="deposit_view(this);"><i class="fa fa-eye"></i></a></td>
    </tr>
    @endforeach
    </tbody>
  </table>
</div>