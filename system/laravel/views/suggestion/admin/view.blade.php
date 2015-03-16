<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>商品建議</h1>
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
                <th>建議人</th>
                <th>產品</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($suggestions as $suggestion)
            <tr>
              <td class="text-left">{{ $suggestion->id }}</td>
              <td class="text-left">名字:{{ $suggestion->name }}<br>信箱:{{ $suggestion->email }}<br>電話:{{ $suggestion->phone }}</td>
              <td class="text-left">名稱:{{ $suggestion->product }}<br>網址:{{ $suggestion->url }}<br>細節:{{ $suggestion->comment }}</td>
            </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>