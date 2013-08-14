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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="form">
                <tr>
                    <td><?php echo $entry_status; ?></td>
                    <td><select name="welcome_popup_status">
                            <?php
                            if ($welcome_popup_status) {
                                ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php
                            } else {
                                ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php
                            } // end if 
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <div id="languages" class="htabs">
                <?php foreach ($languages as $language) { ?>
                    <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                <?php } ?>
            </div>
            <?php foreach ($languages as $language) { ?>
          <div id="language<?php echo $language['language_id']; ?>">
            <table class="form">
              <tr>
                <td><?php echo $entry_content; ?></td>
                <td><textarea name="welcome_popup_content[<?php echo $language['language_id']; ?>][content]" id="content<?php echo $language['language_id']; ?>"><?php echo isset($welcome_popup_content[$language['language_id']]) ? $welcome_popup_content[$language['language_id']]['content'] : ''; ?></textarea></td>
              </tr>
             
            </table>
          </div>
          <?php } ?>
            <table class="form">
                 <tr>
                    <td><?php echo $entry_reset_cookie; ?></td>
                    <td><select name="welcome_popup_reset_cookie">
                            <?php
                            if ($welcome_popup_reset_cookie) {
                                ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php
                            } else {
                                ?>
                                <option value="1"><?php echo $text_yes; ?></option>
                                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                <?php
                            } // end if 
                            ?>
                        </select>
                    </td>
                    <input type="hidden" name="welcome_popup_timestamp" value="<?php echo time(); ?>" />
                </tr>
            </table>
        </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('content<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
</script>
<script type="text/javascript"><!--
$('#languages a').tabs();
//--></script> 
<?php echo $footer; ?>