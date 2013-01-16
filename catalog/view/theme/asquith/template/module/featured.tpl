<div class="feature box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-product">
      <?php foreach ($products as $product) { ?><div class="product">
	      <?php if ($product['thumb']) { ?>
	      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
	      <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php echo $product['shortname']; ?></a></div>
	      <?php if ($product['price']) { ?>
	      <div class="price">
	        <?php if (!$product['special']) { ?>
	        <?php echo $product['price']; ?>
	        <?php } else { ?>
	        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
	        <?php } ?>
	      </div>
	      <?php } ?>
	      
	      <?php if ($product['summary']) { ?>
	      	<div class="summary"><?php echo $product['summary']; ?></div>
	      <?php } ?>
	      <div class="more"><a href="<?php echo $product['href']; ?>">more info...</a></div>
	      <div class="more"><a href="<?php echo $product['href']; ?>" class="button">Shop This Style</a></div>
	    </div><?php } ?>
    </div>
  </div>
</div>
