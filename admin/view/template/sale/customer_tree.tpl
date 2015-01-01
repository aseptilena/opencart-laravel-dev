<script type="text/javascript" src="view/javascript/jquery/jstree/jstree.min.js"></script>
<link type="text/css" href="view/javascript/jquery/jstree/themes/default/style.min.css" rel="stylesheet" media="screen">
<script type="text/javascript">
$(function() {
  $('#ntree').jstree();
  $('#btree').jstree();
})
</script>
<?php if ($error_warning) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<?php if ($success) { ?>
<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<div>
  <h1>Profit History</h1>
  <div id="profit" class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>明細</th>
          <th>獎金</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($profit_histories as $history) { ?>
      <tr>
        <td class="text-left"><?php echo $history->id; ?></td>
        <td class="text-right"><?php echo $history->total; ?> X <?php echo $history->rate; ?>%</td>
        <td class="text-right"><?php echo $history->profit; ?></td>
      </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
  <h1>N Tree</h1>
  <div id="ntree" class="form-group">
    <?php echo $ntree; ?>
  </div>
  <h1>B Tree</h1>
  <div id="btree" class="form-group">
    <?php echo $btree; ?>
  </div>
</div>
