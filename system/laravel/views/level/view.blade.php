<div id="content">
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
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>名稱</th>
                <th>條件</th>
                <th>分紅百分比</th>
                <th>代數</th>
                <th>動作</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($levels as $level)
            <tr>
              <td class="text-left">{{ $level->id }}</td>
              <td class="text-left">{{ $level->title }}</td>
              <td class="text-left">{{ $level->conditionDescription() }}</td>
              <td class="text-right">{{ $level->commission }}</td>
              <td class="text-right">{{ $level->generation }}</td>
              <td class="text-right"><a href="{{ $opencart->url->link('sale/level/edit', 'token=' . $opencart->session->data['token'] . '&level_id=' . $level->id, 'SSL') }}" data-toggle="tooltip" title="編輯" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
            </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>