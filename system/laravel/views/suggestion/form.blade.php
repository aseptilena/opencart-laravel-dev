<form action="{{ $opencart->url->link('product/suggestion', '', 'SSL') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
  <fieldset id="account">
    <h2 class="secondary-title">團購建議</h2>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-product">商品名稱</label>
      <div class="col-sm-10">
        <input type="text" name="product" value="{{ $product or '' }}" placeholder="商品名稱" id="input-product" class="form-control" />
        @if (isset($error_product))
        <div class="text-danger">{{ $error_product }}</div>
        @endif
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-url">商品網址</label>
      <div class="col-sm-10">
        <input type="text" name="url" value="{{ $url or '' }}" placeholder="商品網址" id="input-url" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-comment">備註</label>
      <div class="col-sm-10">
        <textarea type="text" name="comment" placeholder="備註" id="input-comment" class="form-control" rows="5">{{ $comment or '' }}</textarea>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-name">你的名字</label>
      <div class="col-sm-10">
        <input type="text" name="name" value="{{ $name or '' }}" placeholder="你的名字" id="input-name" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-email">聯絡信箱</label>
      <div class="col-sm-10">
        <input type="text" name="email" value="{{ $email or '' }}" placeholder="聯絡信箱" id="input-email" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-phone">聯絡電話</label>
      <div class="col-sm-10">
        <input type="text" name="phone" value="{{ $phone or '' }}" placeholder="聯絡電話" id="input-phone" class="form-control" />
      </div>
    </div>
  </fieldset>
  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="送出" class="btn btn-primary button" />
    </div>
  </div>
</form>