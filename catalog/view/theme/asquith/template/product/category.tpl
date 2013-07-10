<?php echo $header; ?>
<?php if ($thumb) { ?>
    <div class="banner_image"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" /></div>
<?php } else { ?>
    <div class="no_banner_image"></div>
	<div class="breadcrumb">
	    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
	    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	    <?php } ?>
	</div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>

<div id="content">
<?php
if ($heading_title != 'Shop the Look')
{
?>
	<div id="category_header">
	  	<?php if ($products) { ?>
		  <div class="product-filter">
		    <div class="sort"><b><?php echo $text_sort; ?></b>
		      <select onchange="location = this.value;">
		        <?php foreach ($sorts as $sorts) { ?>
		        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
		        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
		        <?php } else { ?>
		        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
		        <?php } ?>
		        <?php } ?>
		      </select>
		    </div>
		  </div>
		<?php } ?>
		<h1><?php echo $heading_title; ?></h1>
	</div>
	<?php /* if ($categories) { ?>
  <h2><?php echo $text_refine; ?></h2>
  <div class="category-list">
    <?php if (count($categories) <= 5) { ?>
    <ul>
      <?php foreach ($categories as $category) { ?>
      <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <?php for ($i = 0; $i < count($categories);) { ?>
    <ul>
      <?php $j = $i + ceil(count($categories) / 4); ?>
      <?php for (; $i < $j; $i++) { ?>
      <?php if (isset($categories[$i])) { ?>
      <li><a href="<?php echo $categories[$i]['href']; ?>"><?php echo $categories[$i]['name']; ?></a></li>
      <?php } ?>
      <?php } ?>
    </ul>
    <?php } ?>
    <?php } ?>
  </div>
  <?php } */ ?>
  <?php if ($products) { ?>
	  <div class="product-list">
	    <?php foreach ($products as $product) { ?>
	    <div class="product">
	      <?php if ($product['thumb']) { ?>
	      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
	      <?php } ?>
	      <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
	      <?php if ($product['price']) { ?>
	      <div class="price">
	        <?php if (!$product['special']) { ?>
	        <?php echo $product['price']; ?>
	        <?php } else { ?>
	        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
	        <?php } ?>
	      </div>
	      <?php } ?>
	    </div>
	    <?php } ?>
	  </div>
  <?php } ?>
  <?php if (!$categories && !$products) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } ?>
  <?php
  if ($description)
  {
      echo '<div id="category_description">' . $description . '</div>';
  }
  ?>
  <?php } else { ?>
  <div id="shopthelook">
  <?php echo $content_bottom; ?>
  </div>
  <?php } ?></div>
<?php echo $footer; ?>