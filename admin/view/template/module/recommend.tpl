<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
    <div id="tabs" class="htabs">
      <a href="#tab-module">Modules</a>
      <a href="#tab-page">Page</a>
      <a href="#tab-email">Email</a>
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab-module">
      <table id="module" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $entry_layout; ?></td>
            <td class="left"><?php echo $entry_position; ?></td>
            <td class="left"><?php echo $entry_status; ?></td>
            <td class="right"><?php echo $entry_sort_order; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php $module_row = 0; ?>
        <?php foreach ($modules as $module) { ?>
        <tbody id="module-row<?php echo $module_row; ?>">
          <tr>
            <td class="left"><select name="recommend_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
            <td class="left"><select name="recommend_module[<?php echo $module_row; ?>][position]">
                <?php if ($module['position'] == 'content_top') { ?>
                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                <?php } else { ?>
                <option value="content_top"><?php echo $text_content_top; ?></option>
                <?php } ?>  
                <?php if ($module['position'] == 'content_bottom') { ?>
                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                <?php } else { ?>
                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                <?php } ?>  
                <?php if ($module['position'] == 'column_left') { ?>
                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                <?php } else { ?>
                <option value="column_left"><?php echo $text_column_left; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_right') { ?>
                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                <?php } else { ?>
                <option value="column_right"><?php echo $text_column_right; ?></option>
                <?php } ?>
              </select></td>
            <td class="left"><select name="recommend_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td class="right"><input type="text" name="recommend_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="4"></td>
            <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div id="tab-page">
      <table class="form">
        <tr>
          <td><?php echo $page_title; ?></td>
          <td><input type="text" name="recommend_page_title" value="<?php echo isset($recommend_page_title) ? $recommend_page_title : ''; ?>" size="64" /></td>
        </tr>
        <tr>
          <td><?php echo $page_meta_title; ?></td>
          <td><input type="text" name="recommend_page_meta_title" value="<?php echo isset($recommend_page_meta_title) ? $recommend_page_meta_title : ''; ?>" size="64" /></td>
        </tr>
        <tr>
          <td><?php echo $page_meta_keyword; ?></td>
          <td><textarea name="recommend_page_meta_keyword" cols="40" rows="5"><?php echo isset($recommend_page_meta_keyword) ? $recommend_page_meta_keyword : ''; ?></textarea></td>
        </tr>
        <tr>
          <td><?php echo $page_meta_description; ?></td>
          <td><textarea name="recommend_page_meta_description" cols="40" rows="5"><?php echo isset($recommend_page_meta_description) ? $recommend_page_meta_description : ''; ?></textarea></td>
        </tr>
        <tr>
          <td><?php echo $page_instructions; ?></td>
          <td><textarea name="recommend_page_instructions" id="recommend_page_instructions"><?php echo isset($recommend_page_instructions) ? $recommend_page_instructions : ''; ?></textarea></td>
        </tr>
      </table>
    </div>
    <div id="tab-email">
	  <table class="form">
  	    <tr>
          <td><?php echo $email_send_from_customer; ?></td>
          <td>
            <?php if (isset($recommend_email_send_from_customer) && $recommend_email_send_from_customer) { ?>
                <input type="radio" name="recommend_email_send_from_customer" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="recommend_email_send_from_customer" value="0" />
                <?php echo $text_no; ?>
            <?php } else { ?>
                <input type="radio" name="recommend_email_send_from_customer" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="recommend_email_send_from_customer" value="0" checked="checked" />
                <?php echo $text_no; ?>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $email_allow_resend; ?></td>
          <td>
            <?php if (isset($recommend_email_allow_resend) && $recommend_email_allow_resend) { ?>
                <input type="radio" name="recommend_email_allow_resend" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="recommend_email_allow_resend" value="0" />
                <?php echo $text_no; ?>
            <?php } else { ?>
                <input type="radio" name="recommend_email_allow_resend" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="recommend_email_allow_resend" value="0" checked="checked" />
                <?php echo $text_no; ?>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $error_if_customer; ?></td>
          <td>
            <?php if (isset($recommend_error_if_customer) && $recommend_error_if_customer) { ?>
                <input type="radio" name="recommend_error_if_customer" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="recommend_error_if_customer" value="0" />
                <?php echo $text_no; ?>
            <?php } else { ?>
                <input type="radio" name="recommend_error_if_customer" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="recommend_error_if_customer" value="0" checked="checked" />
                <?php echo $text_no; ?>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $email_subject; ?></td>
          <td><input type="text" name="recommend_email_subject" value="<?php echo isset($recommend_email_subject) ? $recommend_email_subject : ''; ?>" size="64" /></td>
        </tr>
        <tr>
          <td><?php echo $email_body; ?></td>
          <td><textarea name="recommend_email_body" id="recommend_email_body"><?php echo isset($recommend_email_body) ? $recommend_email_body : ''; ?></textarea></td>
        </tr>
      </table>
    </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
CKEDITOR.replace('recommend_page_instructions', {
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
CKEDITOR.replace('recommend_email_body', {
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
//--></script> 
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="recommend_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="recommend_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="recommend_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="recommend_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script>

<script type="text/javascript"><!--
$('.htabs a').tabs();
//--></script>
<?php echo $footer; ?>