<div class="box" style="width: 410px; margin-left: auto; margin-right: auto;">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div id="newsletter_message" style="display:none; background: #FFFFCC; border: 1px solid #FFCC33; padding: 10px; margin-top: 2px; margin-bottom: 15px;"></div>
    <form action="<?php echo $action; ?>" method="post" id="module_newsletter" name="module_newsletter">
      <div style="text-align: left;">
        <?php if ($name == 'optional') { ?>
        <label for="newsletter_name2" style="display: inline-block; width: 105px;"><?php echo $entry_name2; ?> :</label>
        <input type="text" id="newsletter_name2" name="newsletter_name2"/><br/>
        <label for="newsletter_name" style="display: inline-block; width: 105px;"><?php echo $entry_name; ?> :</label>
        <input type="text" id="newsletter_name" name="newsletter_name"/><br/>
        <?php } elseif ($name == 'required') { ?>
                <label for="newsletter_name2" style="display: inline-block; width: 105px;"><span class="required">*</span><?php echo $entry_name2; ?> :</label>
		<input type="text" id="newsletter_name2" name="newsletter_name2"/><br/>
                <label for="newsletter_name" style="display: inline-block; width: 105px;"><span class="required">*</span><?php echo $entry_name; ?> :</label>
		<input type="text" id="newsletter_name" name="newsletter_name"/><br/>
        <?php } else { ?>
		<input type="hidden" name="name" value="" />
        <?php } ?>
        <label for="newsletter_email"><span class="required">*</span><?php echo $entry_email; ?> :</label>
        <input type="text" id="newsletter_email" name="newsletter_email"/><br/>
        <input name="subscribe" value="1" type="hidden" />
      </div>
      <input type="hidden" style="vertical-align: middle;" id="subscribe" name="subscribe" value="1" checked="checked"/>
      <input type="hidden" style="vertical-align: middle;" id="unsubscribe" name="subscribe" value="0" />
      <div style="text-align: right; margin-top: 8px; margin-right: 24px">
        <a onclick="$('div#newsletter_message').load('index.php?route=module/newsletter/callback&subscribe=' + $('input[name=\'subscribe\']:checked').val() + '&email=' + escape($('input[name=\'newsletter_email\']').val()) + '&name=' + escape($('input[name=\'newsletter_name\']').val()) + '&name2=' + escape($('input[name=\'newsletter_name2\']').val()), function() { $('div#newsletter_message').hide(); $('div#newsletter_message').show('slow'); });" class="button"><span><?php echo $button_go; ?></span></a>
      </div>
    </form>
  </div>
</div>
