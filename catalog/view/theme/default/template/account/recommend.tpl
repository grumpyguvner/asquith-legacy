<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <p><?php echo $text_instructions; ?></p>
  <div id="status_message" style="display:none; background: #FFFFCC; border: 1px solid #FFCC33; padding: 10px; margin-top: 2px; margin-bottom: 15px;"></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div class="content">
      <table class="form">
        <tr>
          <td rowspan="3"><strong><?php echo $text_friend; ?></strong></td>
          <td><?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" value="" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_email; ?></td>
          <td><input type="text" name="email" value="" /></td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <div class="right"><a id="recommend_submit" class="button"><span><?php echo $button_submit; ?></span></a></div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
      $('a#recommend_submit').click(function() {
        $.ajax({
            type: "POST",
            url: 'index.php?route=account/recommend/callback',
            data: { firstname: $('input[name=\'firstname\']').val(), 
                    lastname: $('input[name=\'lastname\']').val(),
                    email: $('input[name=\'email\']').val() }
            }).done(function(msg) {
                $("div#status_message").hide().empty().append(msg).show('slow');
                });
        });
//--></script>
<?php echo $footer; ?>