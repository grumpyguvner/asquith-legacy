<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $title; ?></title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
<div style="width: 680px; background: url('<?php echo $image; ?>') no-repeat top right;">
  <div style="padding-top: 166px;">
    <p style="margin-top: 0px; margin-bottom: 20px;">Dear <?php echo $to_name; ?></p>
    <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_from; ?> <?php echo $text_greeting; ?></p>
    <?php if ($message) { ?>
    <?php if ($text_message) { ?>
    <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_message; ?></p>
    <?php } ?>
    <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $message; ?></p>
    <?php } ?>
    <?php if ($text_redeem) { ?>
    <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_redeem; ?></p>
    <?php } ?>
    <p style="margin-top: 0px; margin-bottom: 20px;">This voucher is valid for one year and can be redeemed when shopping online at <a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>"><?php echo $store_url; ?></a> using the following Gift Voucher redemption code: <strong><?php echo $code; ?></strong></p>
    <p style="margin-top: 0px; margin-bottom: 20px;">This number will be requested during the checkout process and will not be applied automatically. Once you’ve chosen your item(s) please select ‘Use Gift Voucher’ and then enter your voucher redemption code into the box and click ‘Apply’.</p>
    <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_footer; ?></p>
    <p style="margin-top: 0px; margin-bottom: 20px;">Enjoy the collection. Happy shopping!</p>
    <p style="margin-top: 0px; margin-bottom: 20px;">Best wishes</p>
    <p style="margin-top: 0px; margin-bottom: 20px;">Asquith London</p>
  </div>
</div>
</body>
</html>
