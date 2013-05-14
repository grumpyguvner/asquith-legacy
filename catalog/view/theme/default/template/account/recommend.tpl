<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <p><?php echo $text_instructions; ?></p>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div class="content">
      <table class="form">
        <tr>
          <td rowspan="3"><strong><?php echo $text_friend; ?> 1</strong></td>
          <td><?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname_1" value="" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname_1" value="" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_email; ?></td>
          <td><input type="text" name="email_1" value="" /></td>
        </tr>
        <tr>
          <td rowspan="3"><strong><?php echo $text_friend; ?> 2</strong></td>
          <td><?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname_2" value="" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname_2" value="" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_email; ?></td>
          <td><input type="text" name="email_2" value="" /></td>
        </tr>
        <tr>
          <td rowspan="3"><strong><?php echo $text_friend; ?> 3</strong></td>
          <td><?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname_3" value="" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname_3" value="" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_email; ?></td>
          <td><input type="text" name="email_3" value="" /></td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <div class="right"><input type="submit" value="<?php echo $button_submit; ?>" class="button" /></div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>