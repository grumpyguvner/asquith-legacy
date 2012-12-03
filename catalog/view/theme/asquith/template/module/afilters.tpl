<?php if ($attributes) { ?>
<?php foreach($attributes as $att) { // pentru fiecare grup ?>
<?php foreach ($att['attribute_types'] as $type) { ?>
<div class="box">
    <div class="box-heading">Browse by <?php echo $type['type_name']; ?></div>
    <div class="box-content">
        <div class="box-category">
            <ul class="filters" name="att_filters[<?php echo $type['type_id']; ?>]"  style="width: 158px;">
                <?php foreach ($type['types'] as $type_value) { ?>
                    <?php $values = explode(",",$type_value); ?>
                    <?php foreach ($values as $value) { ?>
                        <?php if (isset($afilters) && (array_key_exists($type['type_id'],$afilters) && ($afilters[$type['type_id']] == trim($value)))){ ?>
                            <li><a name="<?php echo $type['type_name']; ?>" href="<?php echo  $href_no_attributes . '&att_filters[' . $type['type_id'] . ']=' .urlencode(trim($value)) ; ?>" class="active"><?php echo trim($value); ?></a></li>
                        <?php } else { ?>
                            <li><a name="<?php echo $type['type_name']; ?>" href="<?php echo  $href_no_attributes . '&att_filters[' . $type['type_id'] . ']=' .urlencode(trim($value)) ; ?>"><?php echo trim($value); ?></a></li>
                        <?php  } ?>
                    <?php  } ?>
                <?php } ?>
            </ul>
        </div>  
    </div>
</div>    
<?php } ?>
<?php } ?>
<?php } ?>