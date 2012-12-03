<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div id="newsletter_message" style="display:none; background: #FFFFCC; border: 1px solid #FFCC33; padding: 10px; margin-top: 2px; margin-bottom: 15px;"></div>
    <form action="<?php echo $action; ?>" method="post" id="module_newsletter" name="module_newsletter">
      <div style="text-align: left;">
        <span class="required">*</span><b><?php echo $entry_email; ?></b><br />
        <input style="width:90%;" type="text" name="newsletter_email"/><br />
        <?php if ($name == 'optional') { ?>
        <b><?php echo $entry_name; ?></b><br />
        <input style="width:90%;" type="text" name="newsletter_name" />
        <?php } elseif ($name == 'required') { ?>
		<span class="required">*</span><b><?php echo $entry_name; ?></b><br />
		<input style="width:90%;" type="text" name="newsletter_name" />
        <?php } else { ?>
		<input type="hidden" name="name" value="" />
        <?php } ?>
		<br />
	    <input name="subscribe" value="1" type="hidden" />
	  </div>
	  <table>
	  	<tr>
	  	  <td>
		    <table>
		      <tr>
			    <td><input type="radio" style="vertical-align: middle;" id="subscribe" name="subscribe" value="1" checked="checked"/><label style="font-size:10px; vertical-align: middle;" for="subscribe"><?php echo $text_subscribe; ?></label></td>
		  	  </tr>
		  	  <tr>
	  	  	    <td><input type="radio" style="vertical-align: middle;" id="unsubscribe" name="subscribe" value="0" /><label style="font-size:10px; vertical-align: middle;" for="unsubscribe"><?php echo $text_unsubscribe; ?></label></td>
	  		  </tr>
		    </table>
		  </td>
	  	  <td><a onclick="$('div#newsletter_message').load('index.php?route=module/newsletter/callback&subscribe=' + $('input[name=\'subscribe\']:checked').val() + '&email=' + escape($('input[name=\'newsletter_email\']').val()) + '&name=' + escape($('input[name=\'newsletter_name\']').val()), function() { $('div#newsletter_message').hide(); $('div#newsletter_message').show('slow'); });" class="button"><span><?php echo $button_go; ?></span></a></td>
	  	</tr>
	  </table>
    </form>
  </div>
</div>
