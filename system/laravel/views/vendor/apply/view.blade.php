<form action="{{ $opencart->url->link('account/apply_vendor', '', 'SSL') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
  <fieldset id="account">
    <h2 class="secondary-title">開店</h2>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-username">註冊帳號名稱</label>
      <div class="col-sm-10">
        <input type="text" name="username" value="{{ $username or '' }}" placeholder="註冊帳號名稱" id="input-username" class="form-control" />
        @if (isset($error_username))
        <div class="text-danger">{{ $error_username }}</div>
        @endif
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-company">開店名稱</label>
      <div class="col-sm-10">
        <input type="text" name="company" value="{{ $company or '' }}" placeholder="開店名稱" id="input-company" class="form-control" />
        @if (isset($error_company))
        <div class="text-danger">{{ $error_company }}</div>
        @endif
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-password">密碼</label>
      <div class="col-sm-10">
        <input type="password" name="password" value="{{ $password or '' }}" placeholder="密碼" id="input-password" class="form-control" />
        @if (isset($error_password))
        <div class="text-danger">{{ $error_password }}</div>
        @endif
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-store_url">商店網站網址</label>
      <div class="col-sm-10">
        <input type="text" name="store_url" value="" placeholder="商店網站網址" id="input-store_url" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-store_description">商店描述</label>
      <div class="col-sm-10">
        <input type="text" name="store_description" value="" placeholder="商店描述" id="input-store_description" class="form-control" />
      </div>
    </div>
  </fieldset>
  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="送出" class="btn btn-primary button" />
    </div>
  </div>
</form>