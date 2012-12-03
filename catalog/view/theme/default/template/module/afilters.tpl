 <?php if ($attributes) { ?>

 <div class="box">

 <div class="box-heading"><?php echo $heading_title; ?></div>

    

<div class="box-content">

<div class="box-category">

<ul>

<form action="<?php echo $href; ?>" method="get" name="afilter" id="afilter">
<?php if (isset($_GET['path'])) { ?>
<input type="hidden" name="route" value="product/category" />
<input type="hidden" name="path" value="<?php echo $_GET['path']; ?>" />
<?php } ?>
	<?php foreach($attributes as $att) { // pentru fiecare grup ?>

    <li style="border-top: 1px solid #EEE; padding: 8px 8px 8px 0px;">

      <div style="display:inline-block; width: 158px; font-weight: bold;"> <?php echo $att['name']; ?> 	</div>
       
       <?php foreach ($att['attribute_types'] as $type) { ?>

            <select class="filters" name="att_filters[<?php echo $type['type_id']; ?>]"  onchange="$('#afilter').submit();" style="width: 158px;">

           	

				<option name="<?php echo $type['type_name']; ?>" value="<?php //echo $type['type_id']; ?>0"><?php echo $type['type_name']; ?></option>



				<?php foreach ($type['types'] as $type_value) { ?>

                <?php // var_dump($afilters[$type['type_id']]); ?>

                <?php if (isset($afilters) && ($afilters[$type['type_id']] == $type_value)){ ?>

                	

                    <option name="<?php echo $type_value; ?>" value="<?php echo $type_value; ?>" selected="selected"> <?php echo $type_value;?> </option>

                    	

               <?php } else { ?>

                

                	<option name="<?php echo $type_value; ?>" value="<?php echo $type_value; ?>"> <?php echo $type_value ?> </option>



              	<?php  } ?>

				<?php } ?>

                

    		</select>
            
            <?php } ?>

           </li> 

    <?php } ?>

                </form>

    </ul>

    

   </div>  

</div>

</div>    

<?php } ?>



