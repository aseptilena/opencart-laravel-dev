<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>月份</th>
        <th>新增人數</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($tree_histories as $history)
    <tr>
      <td class="text-right">{{ $history->date->format('Y年m月') }}</td>
      <td class="text-left">{{ $history->count }}</td>
    </tr>
    @endforeach
    <tr>
      <td class="text-right">總共</td>
      <td class="text-left">{{ $total }}</td>
    </tr>
    </tbody>
  </table>
</div>