<script type="text/javascript">
  $(function() {
    $('#ntree').jstree();
    $('#btree').jstree();
  })
</script>
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