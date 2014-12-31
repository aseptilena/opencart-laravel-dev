<?php echo $header; ?>
<script type="text/javascript">
$(function() {
  $('#ntree').jstree();
})
</script>

<div id="container" class="container j-container">
  <div class="row">
    <div id="content">
      <div id="ntree">
        <?php echo $content; ?>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>