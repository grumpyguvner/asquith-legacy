<?php echo $header; ?>
<div id="content">
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
  <div class="heading">
     <h1><img src="view/image/download.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	<p>1. Add some gifts</p>
	<p>2. Go to Extensions/Order Totals and activate the Gift Wraping Total module</p>
	<p>3. If u have any questions feel free to write me on support@stscript.info</p>
      <table id="module" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $entry_name; ?></td>
            <td class="left"><?php echo $entry_image; ?></td>
            <td class="left"><?php echo $entry_price; ?></td>
			<td class="left"><?php echo $entry_action; ?></td>
          </tr>
        </thead>
        <?php $module_row = 0; ?>
        <?php foreach ($gift as $g) { ?>
        <tbody id="module-row<?php echo $module_row; ?>">
          <tr>
            <td class="left"><input type="text" name="gift_<?php echo $module_row; ?>_name" value="<?php echo $g['name']; ?>" size="35" /></td>
            <td class="left">
			<input type="hidden" name="gift_<?php echo $module_row; ?>_image" value="<?php echo $g['file']; ?>" id="image<?php echo $module_row; ?>"  />
                <img src="<?php echo $g['image']; ?>" alt="" id="preview<?php echo $module_row; ?>" class="image" onclick="image_upload('image<?php echo $module_row; ?>', 'preview<?php echo $module_row; ?>');" /></td>
            <td class="left"><input type="text" name="gift_<?php echo $module_row; ?>_price" value="<?php echo $g['price']; ?>" size="15" /></td>
            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="3"></td>
            <td class="left"><a onclick="addModule();" class="button"><span><?php echo $button_add_gift; ?></span></a></td>
          </tr>
        </tfoot>
      </table>
    </form>
</div>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" name="gift_' + module_row + '_name" value="" size="35" /></td>';
	html += '    <td class="left"><input type="hidden" name="gift_' + module_row + '_image" value="" id="image' + module_row + '" /><img src="<?php echo $no_image; ?>" alt="" id="preview' + module_row + '" class="image" onclick="image_upload(\'image' + module_row + '\', \'preview' + module_row + '\');" /></td>';	
	html += '    <td class="left"><input type="text" name="gift_' + module_row + '_price" value="" size="15" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}

$('#form').bind('submit', function() {
	var module = new Array(); 

	$('#module tbody').each(function(index, element) {
		module[index] = $(element).attr('id').substr(10);
	});
});
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, preview) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {

						$('#' + preview).replaceWith('<img src="../image/' + $('#' + field).attr('value') + '" alt="" id="' + preview + '" class="image" height="40" width="40" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<?php echo $footer; ?>