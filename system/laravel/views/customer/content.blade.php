<script type="text/javascript" src="view/javascript/jquery/jstree/jstree.min.js"></script>
<link type="text/css" href="view/javascript/jquery/jstree/themes/default/style.min.css" rel="stylesheet" media="screen">
<style type="text/css">
.pv-customer {
color: blue;
}
.pv-personal {
color: red;
}
.pv-grouply {
color: green;
}
</style>

<script type="text/javascript">
  $(function() {
    $('#ntree, #btree').each(function() {
      if ($(this).find('li').length == 0) {
        return;
      }
      $(this).jstree();
    })
  })
</script>

<script type="text/javascript">
  function filter_date(ele) {
    var name = $(ele).attr('name');
    var value = ele.options[ele.selectedIndex].value;
    $('#filter_loading').show();
    var url;
    var div;
    if (name == 'filter_profit_date') {
      url = '{{ $profit_url }}';
      div = '#profit-content';
    }
    else if (name == 'filter_bonus_date') {
      url = '{{ $bonus_url }}';
      div = '#bonus-content';
    }

    $.get(url,
      {
        token: '{{ $token }}',
        customer_id: '{{ $customer_id }}',
        date_from: value,
        date_to: value,
      }, 
      function(response) {
        $('#filter_loading').hide();
        $(div).html(response);
      });
  }
  function deposit_view(ele) {
    var id = $(ele).data('id');
    $.get('{{ $deposit_history_url }}',
      {
        token: '{{ $token }}',
        customer_id: '{{ $customer_id }}',
        deposit_id: id,
      }, 
      function(response) {
        $('#deposit-history').empty().append(response);
      });
    return false;
  }
  $(document).on('click', '#button-draw', function() {
    var $this = $(this);
    var deposit_id = $this.data('id');

    var params = $.extend($('#draw-form').serializeObject(), {
        customer_id: '{{ $customer_id }}',
        deposit_id: deposit_id
      });

    $.ajax({
      url: '{{ $draw_url }}&token={{ $token }}',
      type: 'post',
      dataType: 'json',
      data: params,
      beforeSend: function() {
        $this.button('loading');     
      },
      complete: function() {
        $this.button('reset'); 
      },
      success: function(json) {
        $('.alert').remove();
        
        if (json['error']) {
          $('#draw-form').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
      
        if (json['success']) {
          $.get('{{ $deposit_history_url }}',
            {
              token: '{{ $token }}',
              customer_id: '{{ $customer_id }}',
              deposit_id: deposit_id,
            }, 
            function(response) {
              $('#deposit-history').empty().append(response);
            });     
        }     
      },      
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
  $(document).on('click', '#button-info', function(e) {
    e.preventDefault();
    var $this = $(this);

    var params = $.extend($('#form-info').serializeObject(), {
        customer_id: '{{ $customer_id }}'
      });

    $.ajax({
      url: '{{ $info_url }}&token={{ $token }}',
      type: 'post',
      dataType: 'json',
      data: params,
      beforeSend: function() {
        $this.button('loading');     
      },
      complete: function() {
        $this.button('reset'); 
      },
      success: function(json) {
        $('.alert').remove();
        
        if (json['error']) {
          $('#form-info').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
      
        if (json['success']) {
          $('#form-info').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }     
      },      
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
</script>

<div class="alert alert-info" id="filter_loading" style="display:none;"><i class="fa fa-clock-o"></i> Loading....
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<div>
  <div id="tree-content">
    <ul class="nav nav-tabs">
      @foreach ($tabs as $tab)
      <li @if ($active_tab == $tab['link']) class="active" @endif><a href="#{{ $tab['link'] }}" data-toggle="tab">{{ $tab['name'] }}</a></li>
      @endforeach
    </ul>
    <div class="tab-content" id="tree-tabs">
      <div class="tab-pane @if ($active_tab == 'tab-info') active @endif" id="tab-info">
        <div id="info">
          {{ $info }}
        </div>
      </div>
      <div class="tab-pane @if ($active_tab == 'tab-profit-history') active @endif" id="tab-profit-history">
        <div class="well">
          <div class="row" style="margin-left: 0px;margin-right: 0px;">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-profit-date">月份</label>
                <select name="filter_profit_date" id="input-profit-date" class="form-control" onChange="filter_date(this);">
                  @foreach ($profit_dates as $key => $date)
                  <option value="{{ $date['value'] }}"@if ($key == 1) selected="selected"@endif >{{ $date['text'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <div id="profit-content">
          {{ $profit }}
        </div>
      </div>
      <div class="tab-pane @if ($active_tab == 'tab-bonuse-history') active @endif" id="tab-bonuse-history">
        <div class="well">
          <div class="row" style="margin-left: 0px;margin-right: 0px;">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-bonus-date">月份</label>
                <select name="filter_bonus_date" id="input-bonus-date" class="form-control" onChange="filter_date(this);">
                  @foreach ($bonus_dates as $key => $date)
                  <option value="{{ $date['value'] }}"@if ($key == 1) selected="selected"@endif >{{ $date['text'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <div id="bonus-content">
          {{ $bonus }}
        </div>
      </div>
      <div class="tab-pane @if ($active_tab == 'tab-ntree') active @endif" id="tab-ntree">
        <h1>N Tree</h1>
        <div id="ntree-history">
          {{ $ntree_history }}
        </div>
        <div id="ntree" class="table-responsive">
          {{ $ntree }}
        </div>
      </div>
      <div class="tab-pane @if ($active_tab == 'tab-btree') active @endif" id="tab-btree">
        <h1>B Tree</h1>
        <div id="btree-history">
          {{ $btree_history }}
        </div>
        <div id="btree" class="table-responsive">
          {{ $btree }}
        </div>
      </div>
      <div class="tab-pane @if ($active_tab == 'tab-deposit') active @endif" id="tab-deposit">
        <h1>Deposit</h1>
        <div id="deposit">
          {{ $deposit }}
        </div>

        <div id="deposit-history">
        </div>

        <fieldset>
          <legend>要求提款</legend>
          <form class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-draw"><span data-toggle="tooltip" title="輸入點數">要提取金額</span></label>
              <div class="col-sm-10">
                <input type="text" name="draw" value="" placeholder="金額" id="input-draw" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-draw-comment">留言</label>
              <div class="col-sm-10">
                <textarea name="draw_comment" rows="8" id="input-draw-comment" class="form-control"></textarea>
              </div>
            </div>
            <div class="text-right">
              <button type="button" id="button-draw" data-loading-text="送出中" class="btn btn-primary"><i class="fa fa-plus-circle"></i> 送出要求</button>
            </div>
          </form>
        </fieldset>
      </div>
    </div>
  </div>
</div>