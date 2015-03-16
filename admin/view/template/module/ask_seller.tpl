<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-multi-payment-fee" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
    </div>
    <div class="panel-body">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-mvd-ask-seller" class="form-horizontal">
	  <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
        <li><a href="#tab-notification" data-toggle="tab"><?php echo $tab_notification; ?></a></li>
      </ul>
      <div class="tab-content">
      <div class="tab-pane active" id="tab-general">
	  <div class="form-group">
        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
        <div class="col-sm-10">
          <select name="mvd_ask_seller_status" id="input-status" class="form-control">
            <?php if ($mvd_ask_seller_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
	  <div class="form-group">
        <label class="col-sm-2 control-label" for="input-title"><span data-toggle="tooltip" title="<?php echo $help_title; ?>"><?php echo $entry_title; ?></span></label>
        <?php foreach ($languages as $language) { ?>
		<div class="col-sm-10">
            <input type="text" name="mvd_ask_seller_title[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($mvd_ask_seller_title[$language['language_id']]) ? $mvd_ask_seller_title[$language['language_id']]['title'] : $text_default_title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
			<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
		</div>
		<?php } ?>
      </div>
	  <div class="form-group">
        <label class="col-sm-2 control-label" for="input-description"><span data-toggle="tooltip" title="<?php echo $help_description; ?>"><?php echo $entry_description; ?></span></label>
        <?php foreach ($languages as $language) { ?>
		<div class="col-sm-10">
			<textarea name="mvd_ask_seller_description[<?php echo $language['language_id']; ?>][description]" rows="5" placeholder="<?php echo $entry_description; ?>" id="input-mvd-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($mvd_ask_seller_description[$language['language_id']]) ? $mvd_ask_seller_description[$language['language_id']]['description'] : $text_default_description; ?></textarea>                 
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
		</div>
		<?php } ?>
      </div>

	  <div class="table-responsive">
	  <table id="mvd_ask_seller_subject" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
			   <td class="text-left"><span data-toggle="tooltip" title="<?php echo $help_subject; ?>"><?php echo $column_text; ?></span></td>
               <td class="text-left"><?php echo $column_sort_order; ?></td>
               <td class="text-left"><?php echo $column_status; ?></td>
               <td></td>
             </tr>
         </thead>
        <tbody>
		  <?php $subject_row = 0; ?>
		  <?php if ($mvd_ask_seller_subject_text) { ?>
          <?php foreach ($mvd_ask_seller_subject_text as $mvd_subject_text) { ?>
		  <tr id="mvd-ask-seller-row<?php echo $subject_row; ?>">
			<td class="text-left">
				<?php foreach ($languages as $language) { ?>
				  <input type="text" name="mvd_ask_seller_subject_text[<?php echo $subject_row; ?>][subject][<?php echo $language['language_id']; ?>][question]" value="<?php echo isset($mvd_subject_text['subject'][$language['language_id']]) ? $mvd_subject_text['subject'][$language['language_id']]['question'] : ''; ?>" class="form-control" />
				  <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />				
				<?php } ?>
			</td>
			<td class="text-left">
			  <input type="text" name="mvd_ask_seller_subject_text[<?php echo $subject_row; ?>][sort_order]" value="<?php echo $mvd_subject_text['sort_order']; ?>" class="form-control" />				
			</td>
			<td class="text-left"><select name="mvd_ask_seller_subject_text[<?php echo $subject_row; ?>][status]" class="form-control">
            <?php if ($mvd_subject_text['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
			<?php } ?>
			</td>
            <td class="text-left"><button type="button" onclick="$('#mvd-ask-seller-row<?php echo $subject_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
           </tr>
             <?php $subject_row++; ?>
           <?php } ?>
		<?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3"></td>
             <td class="text-left"><button type="button" onclick="addSubject();" data-toggle="tooltip" title="<?php echo $button_add_text; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
          </tr>
        </tfoot>
      </table>
	  </div>
	</div>
      <div class="tab-pane" id="tab-notification">
	      <div class="form-group">
            <label class="col-sm-2 control-label" for="input-email-store-admin"><span data-toggle="tooltip" title="<?php echo $help_email_store_admin; ?>"><?php echo $entry_email_store_admin; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_ask_seller_email_store_admin) { ?>
                <input type="radio" name="mvd_ask_seller_email_store_admin" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_ask_seller_email_store_admin" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_ask_seller_email_store_admin) { ?>
                <input type="radio" name="mvd_ask_seller_email_store_admin" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_ask_seller_email_store_admin" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mvd-ask-seller-add-email-address"><span data-toggle="tooltip" title="<?php echo $help_additional_email_address; ?>"><?php echo $entry_additional_email_address; ?></span></label>
            <div class="col-sm-10">
              <div class="input-group">
                <textarea name="mvd_ask_seller_add_email_address" cols="80" rows="10" placeholder="<?php echo $entry_additional_email_address; ?>" id="input-mvd-ask-seller-add-email-address" class="form-control"><?php echo isset($mvd_ask_seller_add_email_address) ? $mvd_ask_seller_add_email_address : ''; ?></textarea>
              </div>
            </div>
          </div>
	  </div>
	</div>
    </form>
  </div>
</div>
</div>
</div>
<script type="text/javascript"><!--
var subject_row = <?php echo $subject_row; ?>;

function addSubject() {	
	html  = '<tbody id="mvd-ask-seller-row' + subject_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><?php foreach ($languages as $language) { ?>';
	html += '	 <input type="text" name="mvd_ask_seller_subject_text[' + subject_row + '][subject][<?php echo $language['language_id']; ?>][question]" value="<?php echo isset($mv_subject_text['subject'][$language['language_id']]) ? $mv_subject_text['subject'][$language['language_id']]['question'] : ''; ?>" class="form-control" />';
	html += '	 <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br/>';
	html += '    <?php } ?></td>';	
	html += '    <td class="right"><input type="text" name="mvd_ask_seller_subject_text[' + subject_row + '][sort_order]" value="" class="form-control" /></td>';
	html += '    <td class="left"><select name="mvd_ask_seller_subject_text[' + subject_row + '][status]" class="form-control">';
	html += '      <option value="0"><?php echo $text_disabled; ?></option>';
	html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '    </select></td>';
	html += '<td class="text-left"><button type="button" onclick="$(\'#mvd-ask-seller-row' + subject_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#mvd_ask_seller_subject tfoot').before(html);	
	subject_row++;
}
//--></script>

<?php echo $footer; ?>