<script type="text/javascript">
  function goto_path(path){
    if(path!=''){
      window.location = "<?php echo $href?>"+path;
    }
  }
</script>

<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-category">     
     <!-- Shop by Brand<br /><br /> -->
     
<!--     <div class="div2">
    <?php echo $text_manufacturer; ?>
      <select id="manufacturer" name="manufacturer" onchange="location=this.value">
       <?php foreach ($manufacturers as $manufacturer) { ?>
           <?php if ($manufacturer['value'] == $manufacturer_id) { ?>
           <option value="<?php echo $manufacturer['href']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
           <?php } else { ?>
           <option value="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></option>
           <?php } ?>
           <?php } ?>
      </select>
    </div> -->
    
     <form action="<?php echo $href; ?>" method="post" name="manufacturer" id="manufacturer">
      <ul><select name='manufacturers' onchange="location=this.value" > <!-- onchange="$('#manufacturer').submit();"-->
    <!-- <option value=""> Reset Manufacturer </option>
      <option value=""></option> -->
      <?php foreach ($manufacturers as $manufacturer) { ?>
           <?php if (isset($_GET['manufacturer']) && ($manufacturer['value'] == $_GET['manufacturer'])) { ?>
           <option value="<?php echo $manufacturer['href']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
           <?php } else { ?>
           <option value="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></option>
           <?php } ?>
           <?php } ?>
           
       <!-- <?php foreach ($manufacturers as $manufacturer) { ?>        
        <li>
          <option name="<?php echo $manufacturer['name']; ?>" value="<?php echo $manufacturer['value']; ?>"><?php echo $manufacturer['name']; ?></option>
        </li>
        <?php } ?> -->
        </select>
      </ul>
      </form>
    </div>
  </div>
</div>