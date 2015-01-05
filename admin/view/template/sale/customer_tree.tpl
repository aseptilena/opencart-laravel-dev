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
  function filter_profit_date(ele) {
    var value = ele.options[ele.selectedIndex].value;
    $('#filter_loading').show();
    $('#tree-content').load('<?php echo $url_get_content; ?>&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&date_from='+value+'&date_to='+value+'&active_tab='+$("#tree-tabs>div.tab-pane.active").attr('id'), function() {
      $('#filter_loading').hide();
    });
  }
</script>
<div class="alert alert-info" id="filter_loading" style="display:none;"><i class="fa fa-clock-o"></i> Loading....
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<div>
  <div id="tree-filter" class="well">
    <div class="row" style="margin-left: 0px;margin-right: 0px;">
      <div class="col-sm-3">
        <div class="form-group">
          <label class="control-label" for="input-profit-date">月份</label>
          <select name="filter_profit_date_id" id="input-profit-date" class="form-control" onChange="filter_profit_date(this);">
            <?php foreach ($profit_dates as $key => $profit_date) { ?>
            <option value="<?php echo $profit_date['value']; ?>"<?php if ($key == 1) { ?> selected="selected"<?php } ?>><?php echo $profit_date['text']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
  </div>
  <div id="tree-content">
    <?php echo $tree_content; ?>
  </div>
</div>
