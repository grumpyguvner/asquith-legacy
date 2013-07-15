<?php echo $header; ?>
<div class="no_banner_image"></div>
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
</div>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
    <div class="product-info">
        <div class="left"><?php if ($images) { ?>
                            <div class="image-additional">
                                <ul id="imageCarousel" class="jcarousel-skin-tango">
                                <?php
                                if ($additional) {
                                    ?><li><a href="<?php echo $popup; ?>" target="_blank" class="colorbox imageAdditional" rel="colorbox" data-main="<?php echo $thumb; ?>"><img src="<?php echo $additional; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" class="imageAdditional"/></li><?php
                    }
                    foreach ($images as $image) {
                        if (empty($image['video'])) {
                                        ?><li><a href="<?php echo $image['popup']; ?>" target="_blank" class="colorbox imageAdditional" rel="colorbox" data-main="<?php echo $image['main']; ?>"><img src="<?php echo $image['thumb']; ?>" alt="" /></a></li><?php
                        } else {
                                        ?><li><a href="http://www.youtube.com/v/<?php echo $image['video']; ?>?rel=0&wmode=transparent" class="colorbox videoAdditional" rel="colorbox" data-video="<?php echo $image['video']; ?>" target="_blank" style="line-height:<?php echo $additionalHeight; ?>px;height:<?php echo $additionalHeight; ?>px;width:<?php echo $additionalWidth; ?>px;"><img src="http://img.youtube.com/vi/<?php echo $image['video']; ?>/0.jpg" alt="" /><span class="play-button" style="height:<?php echo $additionalHeight; ?>px;width:<?php echo $additionalWidth; ?>px;"></span></a></li><?php
                            }
                        }
                                ?>
                                </ul>
                            </div>
                        <?php } ?>
                    <?php if ($thumb || $images) { ?>
                        <div class="image" id="wrap-image">
                            <?php if ($thumb) { ?>
                                <img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" data-colorbox="" />
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
        
        <div class="right">
            <div>
                <?php if ($manufacturer_logo) { ?>
                <div class="manufacturer"><a href="<?php echo $manufacturers; ?>"><img src="<?php echo $manufacturer_logo; ?>" title="<?php echo $manufacturer; ?>" alt="<?php echo $manufacturer; ?>" id="image-manufacturer" /></a></div>
                <?php } else { ?>
                <?php if ($manufacturer) { ?>
                <div class="manufacturer"><span><?php echo $text_manufacturer; ?></span> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></div>
                <?php } ?>
                <?php } ?>
                <h1><?php echo $heading_title; ?></h1>
                <div class="model"><?php echo $model; ?></div>
                <?php if ($price) { ?>
                <div class="price">
                    <?php if (!$special) { ?>
                    <span class="price-rrp"><?php echo $price; ?></span>
                    <?php } else { ?>
                    <span class="price-old"><?php echo $price; ?></span> <?php echo $text_special_price; ?> <span class="price-new"><?php echo $special; ?></span>
                    <?php } ?>
                    <br />
                    <?php if ($tax) { ?><?php echo $tax; ?></span><br />
                    <?php } ?>
                    <?php if ($points) { ?>
                    <span class="reward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></span><br />
                    <?php } ?>
                    <?php if ($discounts) { ?>
                    <br />
                    <div class="discount">
                        <?php foreach ($discounts as $discount) { ?>
                        <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?><br />
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <div class="description">
                    <?php if ($reward) { ?>
                    <span><?php echo $text_reward; ?></span> <?php echo $reward; ?><br />
                    <?php } ?>
                    <span><?php echo $text_stock; ?></span> <?php echo $stock; ?>
                </div>
                <?php if ($options) { ?>
                <div class="options">
                    <?php foreach ($options as $option) { ?>
	                 	<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
	                 	<span class="label"><?php if ($option['required']) echo '<span class="required">*</span>'; ?><?php echo $option['name']; ?>:</span>
	                    <?php 
	                    switch ($option['type']) {
	                    	case 'select':
	                    ?>
		                        <select name="option[<?php echo $option['product_option_id']; ?>]">
		                            <option value=""><?php echo $text_select; ?></option>
		                            <?php foreach ($option['option_value'] as $option_value) { ?>
		                            <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
		                                <?php if ($option_value['price']) { ?>
		                                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
		                                <?php } ?>
		                            </option>
		                            <?php } ?>
		                        </select>
	                    <?php 
	                    		break;
	                    	case 'radio':
	                    ?>
	                        <?php foreach ($option['option_value'] as $option_value) { ?>
	                        <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
	                        <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
	                            <?php if ($option_value['price']) { ?>
	                            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
	                            <?php } ?>
	                        </label>
	                        <br />
	                        <?php } ?>
	                    <?php 
	                    		break;
	                    	case 'checkbox':
	                    ?>
	                        <?php foreach ($option['option_value'] as $option_value) { ?>
	                        <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
	                        <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
	                            <?php if ($option_value['price']) { ?>
	                            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
	                            <?php } ?>
	                        </label>
	                        <br />
	                        <?php } ?>
	                    <?php 
	                    		break;
	                    	case 'image':
	                    ?>
	                        <div class="option-image">
	                            <?php foreach ($option['option_value'] as $option_value) { ?>
	                            <div>
	                                <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /> <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /> <?php echo $option_value['name']; ?>
	                                        <?php if ($option_value['price']) { ?>
	                                        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
	                                        <?php } ?>
	                                    </label>
	                            </div>
	                            <?php } ?>
	                        </div>
	                    <?php 
	                    		break;
	                    	case 'text':
	                    ?>
	                        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
	                    <?php 
	                    		break;
	                    	case 'textarea':
	                    ?>
	                        <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
	                    <?php 
	                    		break;
	                    	case 'file':
	                    ?>
	                        <a id="button-option-<?php echo $option['product_option_id']; ?>" class="button"><?php echo $button_upload; ?></a>
	                        <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
	                    <?php 
	                    		break;
	                    	case 'date':
	                    ?>
	                        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
	                    <?php 
	                    		break;
	                    	case 'datetime':
	                    ?>
	                        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
	                    <?php 
	                    		break;
	                    	case 'time':
	                    ?>
	                        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
	                    <?php 
	                    		break;
	                    }
	                    ?>
	                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <div class="addToCart">
                    <?php // echo $text_qty; ?>
                    <input type="hidden" name="quantity" size="2" value="<?php echo $minimum; ?>" />
                    <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
                    &nbsp;<input type="button" value="<?php echo $button_cart; ?>" id="button-cart" class="button" />
                </div>
                <div class="cart">
                    
                    <?php if ($minimum > 1) { ?>
                    <div class="minimum">&nbsp;<?php echo $text_minimum; ?></div>
                    <?php } ?>
	                <?php if ($review_status) { ?>
		                <div class="review">
		                    &nbsp;<img src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $reviews; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $text_write; ?></a>
		                </div>
	                <?php } ?>
	                <div class="share">
	                	<div class="addthis_default_style"><a class="addthis_button_favorites">Bookmark</a></div>&nbsp;|&nbsp;<div class="addthis_default_style"><a class="addthis_button_compact"><?php echo $text_share; ?></a></div>&nbsp;|&nbsp;<a href="#" onclick="addToWishList('<?php echo $product_id; ?>');return false;"><?php echo $button_wishlist; ?></a>&nbsp;|&nbsp;<a href="#" onclick="addToCompare('<?php echo $product_id; ?>');return false;"><?php echo $button_compare; ?></a>
	                    <script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script>
	                </div>
                </div>
                <div id="descTabs" class="tabs_wrapper">
                    <div class="htabs"><a href="#tab-description"><?php echo $tab_description; ?></a>
                        <?php if ($attribute_groups) { ?>
                        <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>
                        <?php } ?>
                        <?php if ($product_tabs) { ?>
                        <?php foreach ($product_tabs as $product_tab) { ?>
                        <a href="#tab-<?php echo preg_replace('%\s+%', '-', $product_tab['name']); ?>"><?php echo $product_tab['name']; ?></a>
                        <?php } ?>
                        <?php } ?>
                        <?php if ($review_status) { ?>
                        <a href="#tab-review"><?php echo $tab_review; ?></a>
                        <?php } ?>
                    </div>
                    <div class="tab_content_wrapper">
                        <div id="tab-description" class="tab-content"><?php echo $description; ?></div>
                        <?php if ($attribute_groups) { ?>
                        <div id="tab-attribute" class="tab-content">
                            <table class="attribute">
                                <?php foreach ($attribute_groups as $attribute_group) { ?>
                                <thead>
                                    <tr>
                                        <td colspan="2"><?php echo $attribute_group['name']; ?></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                                    <tr>
                                        <td><?php echo $attribute['name']; ?></td>
                                        <td><?php echo $attribute['text']; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <?php } ?>
                            </table>
                        </div>
                        <?php } ?>
                        <?php if ($product_tabs) { ?>
                        <?php foreach ($product_tabs as $product_tab) { ?>
                            <div id="tab-<?php echo preg_replace('%\s+%', '-', $product_tab['name']); ?>" class="tab-content"><?php echo $product_tab['text']; ?></div>
                        <?php } ?>
                        <?php } ?>
                        <?php if ($review_status) { ?>
                        <div id="tab-review" class="tab-content">
                        
                            <div id="review"></div>
                            <h2 id="review-title"><?php echo $text_write; ?></h2>
                            <b><?php echo $entry_name; ?></b><br />
                            <input type="text" name="name" value="" />
                            <br />
                            <br />
                            <b><?php echo $entry_review; ?></b>
                            <textarea name="text" cols="40" rows="8" style="width: 98%;"></textarea>
                            <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
                            <br />
                            <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
                            <input type="radio" name="rating" value="1" />
                            &nbsp;
                            <input type="radio" name="rating" value="2" />
                            &nbsp;
                            <input type="radio" name="rating" value="3" />
                            &nbsp;
                            <input type="radio" name="rating" value="4" />
                            &nbsp;
                            <input type="radio" name="rating" value="5" />
                            &nbsp; <span><?php echo $entry_good; ?></span><br />
                            <br />
						     <?php
						     /* Added 25/7/12 TF : Turn Captcha on and off */
						     if (isset($entry_captcha)) {
						     ?>
						    <b><?php echo $entry_captcha; ?></b><br />
						    <input type="text" name="captcha" value="" />
						    <br />
						    <img src="index.php?route=product/product/captcha" alt="" id="captcha" /><br />
						    <br />
						     <?php
						     }
						     /* End turn Captcha on and off */
						     ?>
                            <div class="buttons">
                                <div class="right"><a id="button-review" class="button"><?php echo $button_continue; ?></a></div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($products || $alsoBought) { ?>
        <div id="relatedAlsoBought" class="tabs_wrapper">
            <div class="htabs">
                <?php if ($products) { ?>
                <a href="#tab-products">Related Products</a>
                <?php } ?>
                <?php if ($alsoBought) { ?>
                <a href="#tab-alsoBought">Customers Also Bought</a>
                <?php } ?>
            </div>
	        <div class="tab_content_wrapper">
	        	<?php if ($products) { ?>
	        	<div id="tab-products" class="tab-content product-list">
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
	        	<?php if ($alsoBought) { ?>
		        	<div id="tab-alsoBought" class="tab-content">
		        	<?php foreach ($alsoBought as $product) { ?>
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
	        </div>
	    </div>
        <?php } ?>
    </div>
    <?php if ($tags) { ?>
    <div class="tags"><b><?php echo $text_tags; ?></b>
        <?php foreach ($tags as $tag) { ?>
        <a href="<?php echo $tag['href']; ?>"><?php echo $tag['tag']; ?></a>,
        <?php } ?>
    </div>
    <?php } ?>
    <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#button-cart').bind('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();
			
			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
					}
				}
			} 
			
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
				$('.success').fadeIn('slow');
					
				$('#cart-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 
			}	
		}
	});
});
//--></script>
<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
	action: 'index.php?route=product/product/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
	},
	onComplete: function(file, json) {
		$('.error').remove();
		
		if (json.success) {
			alert(json.success);
			
			$('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json.file);
		}
		
		if (json.error) {
			$('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json.error + '</span>');
		}
		
		$('.loading').remove();	
	}
});
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript">
        
    $(document).ready(function () {
        $colorboxIndex = 0;
        $colorboxOpen = 0;
            
        $('.image-additional').delegate('li','click', function(event) {
            if ($(this).find('a.videoAdditional').length)
            {
                $('.left .image').html('<iframe id="playingMovie" width="256" height="343" src="http://www.youtube.com/embed/' + $(this).find('a.videoAdditional').data('video') + '?autoplay=1&rel=0&theme=light&autohide=1" frameborder="0" allowfullscreen></iframe>');
                   return false;
            } else {
                if (!$colorboxOpen)
                {
                    $('.left .image').html('<img src="' + $(this).find('a').data('main') + '" alt="" />');
                    $colorboxIndex = $('.image-additional li').index($(this));
                    return false;
                } else {
                    $colorboxOpen = 0;
                }
            }
        });
            
        $('.left .image').delegate('img','click', function(){
            $colorboxOpen = 1;
            $('.image-additional li a').eq($colorboxIndex).trigger('click');
        });
        
        $('.colorbox').colorbox({
            iframe: true,
            overlayClose: true,
            opacity: 0.5,
            maxWidth: '100%',
            maxHeight: '100%',
            height: 1000,
            width: 1000,
            fixed: true,
            scalePhotos: false,
            scrolling: true
        });
        
    });
        
</script>
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').slideUp('slow');
		
	$('#review').load(this.href);
	
	$('#review').slideDown('slow');
	
	return false;
});			

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').bind('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-review').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#review-title').after('<div class="warning">' + data.error + '</div>');
			}
			
			if (data.success) {
				$('#review-title').after('<div class="success">' + data.success + '</div>');
								
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});
//--></script> 
<script type="text/javascript"><!--

$('#descTabs .htabs a').tabs();
$('#relatedAlsoBought .htabs a').tabs();
//--></script> 
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
if ($.browser.msie && $.browser.version == 6) {
	$('.date, .datetime, .time').bgIframe();
}

$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script> 
<script type="text/javascript">
jQuery(document).ready(function() {    
    jQuery('#imageCarousel').jcarousel({
    	 vertical: true,
         scroll: 3
    });
});
</script>
<?php echo $footer; ?>