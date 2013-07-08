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
          <td><input type="text" name="recommend_firstname" value="" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_lastname; ?></td>
          <td><input type="text" name="recommend_lastname" value="" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_email; ?></td>
          <td><input type="text" name="recommend_email" value="" /></td>
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
            dataType : 'json',
            data: { firstname: $('input[name=\'recommend_firstname\']').val(), 
                    lastname: $('input[name=\'recommend_lastname\']').val(),
                    email: $('input[name=\'recommend_email\']').val() }
            }).done(function(data) {
                $("div#status_message").hide().empty().append(data.msg).show('slow');
                if (data.status==0)
                    {
                        $('input[name=\'recommend_firstname\']').val("");
                        $('input[name=\'recommend_lastname\']').val("");
                        $('input[name=\'recommend_email\']').val("");
                    }
                });
        });
//--></script>
<?php echo $footer; ?>