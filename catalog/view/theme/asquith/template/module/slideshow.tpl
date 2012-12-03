<div class="slideshow">
    <div id="slideshow<?php echo $module; ?>" class="nivoSlider" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;">
        <?php $cnt=1; ?>
        <?php foreach ($banners as $banner) { ?>
            <?php if ($banner['link']) { ?>
                <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="" title="#caption_<?php echo $cnt; ?>" /></a>
            <?php } else { ?>
                <img src="<?php echo $banner['image']; ?>" alt="" title="#caption_<?php echo $cnt; ?>" />
            <?php } ?>
            <?php $cnt++; ?>
        <?php } ?>
    </div>
    <?php $cnt=1; ?>
    <?php foreach ($banners as $banner) { ?>
<!--        <div id="caption_<?php echo $cnt; ?>" class="nivo-html-caption"><span style="font-size: 30pt; margin-left: 20px;"><?php echo $banner['title']; ?></span></div> -->
        <?php $cnt++; ?>
    <?php } ?>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#slideshow<?php echo $module; ?>').nivoSlider({
            
            effect: 'fade' // Specify sets like: 'fold,fade,sliceDown'
            });
});
--></script>