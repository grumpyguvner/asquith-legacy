<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?php echo $lang; ?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="<?php echo $lang; ?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="<?php echo $lang; ?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?php echo $lang; ?>"> <!--<![endif]-->
<head>
	<title><?php echo $title; ?></title>
	<base href="<?php echo $base; ?>" />
	<?php if ($description) { ?>
	<meta name="description" content="<?php echo $description; ?>" />
	<?php } ?>
	<?php if ($keywords) { ?>
	<meta name="keywords" content="<?php echo $keywords; ?>" />
	<?php } ?>
	<?php if ($icon) { ?>
	<link href="<?php echo $icon; ?>" rel="icon" />
	<?php } ?>
	<?php foreach ($links as $link) { ?>
	<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
	<?php } ?>
	<link rel="stylesheet" type="text/css" href="catalog/view/theme/asquith/stylesheet/stylesheet.css" />
	<?php foreach ($styles as $style) { ?>
	<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
	<?php } ?>
	<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
	<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/colorbox/colorbox.css" media="screen" />
	<script src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
	<script src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
	<script src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
	<script src="catalog/view/javascript/jquery/colorbox/jquery.colorbox.js"></script>
	<script src="catalog/view/javascript/jquery/tabs.js"></script>
	<script src="catalog/view/javascript/common.js"></script>
	<?php foreach ($scripts as $script) { ?>
	<script src="<?php echo $script; ?>"></script>
	<?php } ?>
	<!--[if lt IE 7]>
	<script src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
	<script>
	DD_belatedPNG.fix('#logo img');
	</script>
	<![endif]-->
        <?php if ($data_layer) echo $data_layer; ?>
	<?php echo $google_analytics; ?>
	<script src="catalog/view/javascript/modernizr-2.5.3.min.js"></script>
</head>
<body>
<div id="notification"></div>
<div id="container">
	<div id="header">
	  <?php
	  if ($logo) {
	  ?>
	  <div id="logo">
                <div class="headerbuttons">
                      <a href="/free-shipping-offer" style="padding-right: 10px;"><img src="catalog/view/theme/asquith/image/freeshipping.jpg" alt="Free UK Shipping on orders over £50" title="Free UK Shipping on orders over £50"></a>
                      <a href="/account/recommend"><img src="catalog/view/theme/asquith/image/recommend-your-friends.png" alt="Get £5 off you next order by recommending your friends" title="Get £5 off you next order by recommending your friends"></a>
                </div>
              <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a>
          </div>
          <div class="strapline">CONTEMPORARY LAYERING IN ORGANIC COTTON &amp; BAMBOO FOR YOGA, PILATES AND LIVING IN ...</div>
	  <?php } ?>
	  <div class="topRow">
	  	<div class="socialMedia">
	  		<a href="https://plus.google.com/b/102388213141542496784/I" target="_blank"><img src="catalog/view/theme/asquith/image/googleplus.png"></a>
	  		<a href="https://www.facebook.com/pages/Asquith-London-Organic-Cotton-Bamboo-Lifestyle-and-Yoga-Clothing/170293469708750?sk=wall" target="_blank"><img src="catalog/view/theme/asquith/image/facebook.gif"></a>
	  		<a href="http://twitter.com/AsquithLondon" target="_blank"><img src="catalog/view/theme/asquith/image/twitter.gif"></a>
	  		<a href="http://pinterest.com/asquithlondon/" target="_blank"><img src="catalog/view/theme/asquith/image/big-p-button.png" width="20" alt="Follow Me on Pinterest" /></a>
	  	</div>
	  	<div class="links">
	  		<a href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a>
	  		<a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
	  		<?php
	  		if (!$logged)
	  		{
	  		?>
	  		<a href="account/register">Register</a>
	  		<?php
	  		} // end if
	  		?>
	  		
	  	</div>
	  	<?php echo $cart; ?>
	  </div>
	  <div class="bottomRow">
	  		<?php echo $currency; ?>
			<div id="header_newsletter_wrapper">
                <form action="/index.php" method="get" id="header_newsletter_form" target="_blank">
                	<div id="header_newsletter" class="header_input">
                    <input type="hidden" name="route" value="module/newsletter/callback">
                    <input type="hidden" name="subscribe" value="1">
                    <input type="email" id="newsletter_email" name="email" placeholder="Email newsletter signup"><a class="action" href="#" onclick="$('#header_newsletter_form').trigger('submit');return false;">GO</a>
                	</div>
                </form>
            </div>
	  </div>
	</div>
<?php
if ($categories) {
?>
<div id="menu">
	<div id="search" class="header_input">
	<input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="Search" /><div class="action button-search"></div>
	</div>
	<ul>
	<?php foreach ($categories as $category) { ?>
	<li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
	  <?php if (!$category['children_html']=="") { ?>
	    <?php echo $category['children_html']; ?>
	  <?php } else { ?>
	    <?php if ($category['children']) { ?>
	        <?php for ($i = 0; $i < count($category['children']);) { ?>
	            <div><div><ul>
	            <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
	            <?php for (; $i < $j; $i++) { ?>
	                <?php if (isset($category['children'][$i])) { ?>
	                    <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
	                <?php } ?>
	            <?php } ?>
	            </ul></div></div>
	        <?php } ?>
	    <?php } ?>
	  <?php } ?>
	</li>
	<?php } ?>
	<li><a href="/brochure-for-yoga-clothing">Brochure</a></li>
	
	<li><a href="/asquith-yoga-clothing">About Us</a>
	 <div><div><ul>
	 <li><a href="/ethical-organic-yoga-clothing">Our Ethics</a></li>
	 <li><a href="/womens-organic-yoga-clothes">Our Fabrics</a></li>
	 <li><a href="/stylish-yoga-clothes">Our Style</a></li>
	 <li><a href="/alice-asquith-yoga-clothing">Meet Alice</a></li>
	 <li><a href="/in-the-press">In The Press</a></li>
	 <li><a href="/yoga-clothing-stockists">Stockists</a></li>
	 </ul></div></div>
	
	</li>
	<li><a href="/blog">Blog</a></li>
	</ul>
</div>
<?php
} 
$page="common/home";
if (isset($_GET['route'])) $page = $_GET['route'];
?>
<div id="content_wrapper">