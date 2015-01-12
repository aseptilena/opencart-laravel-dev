<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-level" data-toggle="tooltip" title="儲存" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="{{ $opencart->url->link('sale/level', 'token=' . $this->session->data['token'], 'SSL') }}" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1>會員等級</h1>
      <ul class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
        <li><a href="{{ $breadcrumb->href }}">{{ $breadcrumb->text }}</a></li>
        @endforeach
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    @if (isset($success))
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ $success }}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif
    @if (isset($errors))
      @foreach ($errors as $error)
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ $error }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      @endforeach
    @endif
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> Customer List</h3>
      </div>
      <div class="panel-body">
        <form action="{{ $opencart->url->link('sale/level/edit', 'token=' . $opencart->session->data['token'] . '&level_id=' . $opencart->request->get['level_id'], 'SSL') }}" method="post" enctype="multipart/form-data" id="form-level" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-title">會員名稱</label>
            <div class="col-sm-10">
              <input type="text" name="title" value="{{{ $level->title }}}" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-commission">分紅百分比</label>
            <div class="col-sm-10">
              <input type="text" name="commission" value="{{{ $level->commission }}}" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-generation">代數</label>
            <div class="col-sm-10">
              <input type="text" name="generation" value="{{{ $level->generation }}}" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-leader">屬於領導階層</label>
            <div class="col-sm-10">
              <select name="leader" id="input-leader" class="form-control">
                <option value="1" @if ($level->leader == 1) selected="selected" @endif>是</option>
                <option value="0" @if ($level->leader == 0) selected="selected" @endif>否</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-jump">可跳升</label>
            <div class="col-sm-10">
              <select name="jump" id="input-jump" class="form-control">
                <option value="1" @if ($level->jump == 1) selected="selected" @endif>是</option>
                <option value="0" @if ($level->jump == 0) selected="selected" @endif>否</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-barrier">須達到PV數</label>
            <div class="col-sm-10">
              <input type="text" name="barrier" value="{{{ $level->barrier }}}" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-downline">擁有幾線會員</label>
            <div class="col-sm-10">
              <input type="text" name="downline" value="{{{ $level->downline }}}" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-level_id">擁有會員等級</label>
            <div class="col-sm-10">
              <select name="level_id" id="input-level_id" class="form-control">
                <option value="0" @if ($level->level_id == 0) selected="selected" @endif>無</option>
                @foreach ($levels as $lvl)
                <option value="{{ $lvl->id }}" @if ($lvl->id == $level->level_id) selected="selected" @endif>{{{ $lvl->title }}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-next">下月組織業績</label>
            <div class="col-sm-10">
              <input type="text" name="next" value="{{{ $level->next }}}" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>