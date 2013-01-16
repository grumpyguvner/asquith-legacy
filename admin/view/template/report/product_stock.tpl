<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $column_model; ?></td>
            <td class="left"><?php echo $column_name; ?></td>
            <?php
            foreach ($column_options as $column) {
                echo '<td class="center">' . $column . '</td>';
            }
            ?>
            <td class="right"><?php echo $column_total; ?></td>
            <td class="right"><?php echo $column_price; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($products) { ?>
          <?php foreach ($products as $product) { ?>
          <tr>
            <td class="left"><?php echo $product['model']; ?></td>
            <td class="left"><?php echo $product['name']; ?></td>
            <?php
            foreach ($column_options as $key => $column) {
                if (array_key_exists($column, $product['options']) === true) {
                    echo '<td class="center">' . $product['options'][$column] . '</td>';
                } else {
                    echo '<td class="left">&nbsp;</td>';
                }
            }
            ?>
            <td class="right"><?php echo $product['total']; ?></td>
            <td class="right"><?php echo $product['price']; ?></td>
            <!-- <td class="right"><?php echo $product['sale']; ?></td> -->
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination">
          <div class="buttons"><a onclick="location='<?php echo $export; ?>'" class="button"><span><?php echo $button_export; ?></span></a></div>
          <?php echo $pagination; ?>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>