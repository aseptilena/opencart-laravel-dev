<?php echo $header; ?>
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
  $('#ntree').jstree();
  $('#btree').jstree();
})
</script>

<div id="container" class="container j-container">
  <div class="row">
    <div id="content">
      <h1 class="heading-title">Profit History</h1>
      <div id="profit" class="table-responsive">
        <table class="table table-bordered table-hover list">
          <thead>
            <tr>
              <td>ID</td>
              <td>明細</td>
              <td>獎金</td>
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
      <h1 class="heading-title">N Tree</h1>
      <div id="ntree">
        <?php echo $ntree; ?>
      </div>
      <h1 class="heading-title">B Tree</h1>
      <div id="btree">
        <?php echo $btree; ?>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>