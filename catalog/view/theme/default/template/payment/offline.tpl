<h2><?php echo $text_instruction; ?></h2>
<p><?php echo $text_description; ?></p>
<p><?php echo $offline; ?></p>
<p><?php echo $text_payment; ?></p>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({ 
		type: 'GET',
		url: 'index.php?route=payment/offline/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}		
	});
});
//--></script> 
