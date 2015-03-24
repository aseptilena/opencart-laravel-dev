<script type="text/javascript" src="admin/view/javascript/jquery/jstree/jstree.min.js"></script>
<link type="text/css" href="admin/view/javascript/jquery/jstree/themes/default/style.min.css" rel="stylesheet" media="screen">
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
<h1>N Tree</h1>
<div id="ntree-history">
  {{ $ntree_history }}
</div>
<div id="ntree" class="table-responsive">
  {{ $ntree }}
</div>