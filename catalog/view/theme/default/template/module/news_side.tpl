<div class="box">
    <div class="box-heading"><?php echo $heading_title; ?></div>
   <div class="box-content">
       <?php foreach ($news as $news_story) { ?>
      <div style="border-bottom: 1px dotted #aaa; margin-bottom: 5px; padding-bottom: 5px;">
	  <a style="font-weight: bold; text-decoration: none;" href="<?php echo $news_story['href']; ?>"><?php echo $news_story['title']; ?></a><br />
      <span style="color: #555; font-size: 11px;"><?php echo $news_story['short_description']; ?>...</span><b> <a href="<?php echo $news_story['href']; ?>"><?php echo $text_read_more; ?></a></b> <br />
	  <?php if ($news_story['acom']) { ?>
	  <span style="font-style: italic; color: #777;"><?php echo $news_story['total_comments']; ?> <?php echo $text_comments; ?></span>
      <?php } ?>
	  </div>
    <?php } ?>
	<a class="button" href="<?php echo $newslink; ?>"><span><?php echo $text_headlines; ?></span></a>
</div>
</div>

