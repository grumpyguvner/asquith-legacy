<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
<p><?php echo $text_shipping_method; ?></p>
<table class="radio">
    <?php foreach ($shipping_methods as $shipping_method) { ?>
    <tr>
        <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
    </tr>
    <?php if (!$shipping_method['error']) { ?>
    <?php foreach ($shipping_method['quote'] as $quote) { ?>
    <tr class="highlight">
        <td><?php if ($quote['code'] == $code || !$code) { ?>
            <?php $code = $quote['code']; ?>
            <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
            <?php } ?></td>
        <td><label for="<?php echo $quote['code']; ?>"><?php echo $quote['title']; ?></label></td>
        <td style="text-align: right;"><label for="<?php echo $quote['code']; ?>"><?php echo $quote['text']; ?></label></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
        <td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
    </tr>
    <?php } ?>
    <?php } ?>
</table>
<br />
<?php } ?>
<b><?php echo $text_comments; ?></b>
<textarea name="comment" rows="8" style="width: 98%;"><?php echo $comment; ?></textarea>
<br />
<br />
<?php
$this->language->load('checkout/gifts');

$this->load->model('catalog/gifts');

$giftwrap_data = array();

$gifwraps = $this->model_catalog_gifts->getGifts();

foreach($gifwraps as $gw){

$this->load->model('tool/image');

if ($gw['image'] && file_exists(DIR_IMAGE . $gw['image'])) {
$image = $this->model_tool_image->resize($gw['image'], 45, 45);
} else {
$image = $this->model_tool_image->resize('no_image.jpg', 45, 45);
}

$giftwrap_data[] = array(
'id' => $gw['id'],
'name' => $gw['name'],
'price' => $this->currency->format($gw['price'], $currency = '', $value = '', $format = FALSE),
'image' => $image,
'formated_price' => $this->currency->format($gw['price'])
); 

}

$product_data = array();

foreach ($this->cart->getProducts() as $product) {

if($this->model_catalog_gifts->checkGift($product['product_id'])==1){

$product_data[] = array(
'product_id' => $product['product_id'],
'name' => $product['name']
); 

}


}

//Gift Wrapping Start
if($product_data){
?>
<b style="margin-bottom: 2px; display: block;"><?php echo $this->language->get('text_giftwrap_title'); ?></b>
<div class="content">
    <script type="text/javascript" language="JavaScript"> 
        function send_card_info(product_id, price, giftwrap_id, giftwrap_name){
 
        $.post("index.php?route=checkout/checkout/gifts",
    { 
        product_id: product_id,
        price: price,
        giftwrap_id: giftwrap_id,
        giftwrap_name: giftwrap_name 
    },
    function(data){
 
}
);
}
function updateWrapCost(id) {
src = document.getElementById('giftwrap_'+id);
 
src_val = src.options[src.selectedIndex].value;
 
src_split = src_val.split('__');
src_val_product = src_split[0];
src_val_price = src_split[1];
src_val_giftwrap_id = src_split[2];
src_val_giftwrap_name = src_split[3];
 
 
send_card_info(src_val_product, src_val_price, src_val_giftwrap_id, src_val_giftwrap_name);
}
    </script> 
    <div id="gift_wrap_form">
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><?php
                    echo $this->language->get('text_giftwrap_text');
                    ?></td>
            </tr>
            <tr>
                <td>
                    <ul style="float:left; width:100%; list-style:none;"> 
                        <?php
                        foreach($product_data as $product){
                        ?>
                        <li>
                            <table border="0" cellpadding="0" cellspacing="0" width="90%">
                                <tr class="giftwrap">
                                    <td align="left"><?php echo $product['name']; ?></td>
                                    <td align="right"> <?php

                                        if($giftwrap_data){
                                        ?>
                                        <select name="giftwrap_<?php echo $product['product_id']; ?>" id="giftwrap_<?php echo $product['product_id']; ?>" onchange="updateWrapCost('<?php echo $product['product_id']; ?>')">
                                            <option value="<?php echo $product['product_id']; ?>"><?php echo $this->language->get('text_giftwrap_select'); ?></option>
                                            <?php
                                            foreach($giftwrap_data as $gw_data){
                                            ?>
                                            <option value="<?php echo $product['product_id']; ?>__<?php
                                                    echo $gw_data['price'];
                                                    ?>__<?php
                                                    echo $gw_data['id'];
                                                    ?>__<?php
                                                    echo $gw_data['name'];
                                                    ?>"><?php
                                                echo $gw_data['name'];
                                                ?> (<?php
                                                echo $gw_data['formated_price'];
                                                ?>)</option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>

                            </table>
                        </li>
                        <?php
                        }
                        ?>
                    </ul>
                </td>
            </tr>
            <?php

            if($giftwrap_data){
            ?>
            <tr>
                <td>
                    <ul style="float:left; width:100%; list-style:none;">
                        <?php
                        foreach($giftwrap_data as $gw_data){
                        ?>
                        <li style="text-align:center; width:50px; float:left;">
                            <?php
                            echo $gw_data['name'];
                            ?><br /><img src="<?php
                                         echo $gw_data['image'];
                                         ?>" />
                        </li>
                        <?php
                        }
                        ?>
                    </ul> 
                </td>
            </tr>
            <?php
            }
            ?>

        </table>
    </div>
</div>
<?php
}
//Gift Wrapping End
?>
<style>
    .giftwrap {
        background:#f8f8f8;
    }
    .giftwrap td {
        padding:5px;
    }
    .giftwrap select {
        width:200px;
    }
    .giftwrap:hover {
        background: #F1FFDD;
        cursor: pointer;
    }
</style>
<div class="buttons">
    <div class="right">
        <input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-method" class="button" />
    </div>
</div>
