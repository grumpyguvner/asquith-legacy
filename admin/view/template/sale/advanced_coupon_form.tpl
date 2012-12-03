<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs">
      	<a href="#tab-general"><?php echo $tab_general; ?></a>
        <a href="#tab-option">Option</a>
        <?php if ($advanced_coupon_id) { ?>
        <a href="#tab-history"><?php echo $tab_advanced_coupon_history; ?></a>
        <?php } ?>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td><input name="name" value="<?php echo $name; ?>" size="50"/>
                <?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_code; ?></td>
              <td><input name="code" value="<?php echo $code; ?>" />
                <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_type; ?></td>
              <td><select name="type">
                  <?php if ($type == 'P') { ?>
                  <option value="P" selected="selected"><?php echo $text_percent; ?></option>
                  <?php } else { ?>
                  <option value="P"><?php echo $text_percent; ?></option>
                  <?php } ?>
                  <?php if ($type == 'F') { ?>
                  <option value="F" selected="selected"><?php echo $text_amount; ?></option>
                  <?php } else { ?>
                  <option value="F"><?php echo $text_amount; ?></option>
                  <?php } ?>
                  </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_discount; ?></td>
              <td><input type="text" name="discount" value="<?php echo $discount; ?>" />
              <?php if ($error_discount) { ?>
                <span class="error"><?php echo $error_discount; ?></span>
                <?php } ?>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_quantity_total; ?></td>
              <td><input type="text" name="quantity_total" value="<?php echo $quantity_total; ?>" />
              <?php if ($error_quantity_total) { ?>
                <span class="error"><?php echo $error_quantity_tota; ?></span>
                <?php } ?>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_total; ?></td>
              <td><input type="text" name="total" value="<?php echo $total; ?>" />
              <?php if ($error_total) { ?>
                <span class="error"><?php echo $error_total; ?></span>
                <?php } ?>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_product; ?></td>
              <td>
              <table><tr><td>
              <?php echo $entry_category; ?><br/>
              <div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($categories as $category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
					<?php 
                    if(isset($match_category_ids)){
                        if(in_array($category['category_id'],$match_category_ids)){
                        	$checked = 'checked="checked"';

                        }else{
                        	$checked = '';
                        }
                       }
                    ?>
                    <input type="checkbox" name="category[]" value="<?php echo $category['category_id']; ?>" <?php if(isset($checked)){ echo $checked; } ?> />                    <?php echo $category['name']; ?> </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true); CategorySelectAll();"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false); CategorySelectAll();"><?php echo $text_unselect_all; ?></a></td>
                </td>
                <td>&nbsp;</td>
                <td><?php echo $entry_manufacturer; ?><br/>
                  <div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($manufacturers as $manufacturer) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
					<?php 
                    if(isset($match_manufacturer_ids)){
                        if(in_array($manufacturer['manufacturer_id'],$match_manufacturer_ids)){
                        	$checked = 'checked="checked"';
                        }else{
                        	$checked = '';
                        }
                       }
                    ?>
                    <input type="checkbox" name="manufacturer[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" <?php if(isset($checked)){ echo $checked; } ?> />                    <?php echo $manufacturer['name']; ?> </div>
                  <?php } ?>
                </div><a onclick="$(this).parent().find(':checkbox').attr('checked', true); CategoryManufacturerSelectAll();"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false); CategoryManufacturerSelectAll();"><?php echo $text_unselect_all; ?></a>
                </td>
            </tr>
            <td>&nbsp;</td>
            <tr>
              <td><?php echo $entry_product_product; ?><br/>
              <input type="text" name="product_suggest" value="" />
              </td>
              <td>&nbsp;</td>
              <td><div class="scrollbox" id="advanced_coupon-product">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($advanced_coupon_product as $advanced_coupon_product) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="advanced_coupon-product<?php echo $advanced_coupon_product['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $advanced_coupon_product['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="product[]" value="<?php echo $advanced_coupon_product['product_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
               </td>
            </tr>
            </table>
           </td>
           </tr> 
             
                       
            <tr>
              <td><?php echo $entry_quantity_buy; ?></td>
              <td><input type="text" name="quantity_buy" value="<?php echo $quantity_buy; ?>" />
              <?php if ($error_quantity_buy) { ?>
                <span class="error"><?php echo $error_quantity_buy; ?></span>
                <?php } ?>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_quantity_sale; ?></td>
              <td><input type="text" name="quantity_sale" value="<?php echo $quantity_sale; ?>" />
              <?php if ($error_quantity_sale) { ?>
                <span class="error"><?php echo $error_quantity_sale; ?></span>
                <?php } ?>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_quantity_type; ?></td>
              <td><select name="quantity_type">
                  <?php if ($quantity_type == 'P') { ?>
                  <option value="P" selected="selected"><?php echo $text_proportional; ?></option>
                  <?php } else { ?>
                  <option value="P"><?php echo $text_proportional; ?></option>
                  <?php } ?>
                  <?php if ($quantity_type == 'F') { ?>
                  <option value="F" selected="selected"><?php echo $text_fixed; ?></option>
                  <?php } else { ?>
                  <option value="F"><?php echo $text_fixed; ?></option>
                  <?php } ?>
                  </select>
                  </td>
            </tr>
              
            <tr>
              <td><?php echo $entry_product_buy; ?></td>
              <td><table><tr><td>
              <?php echo $entry_category; ?><br/>
              <div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($categories as $category_buy) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
					<?php 
                    if(isset($match_category_buy_ids)){
                        if(in_array($category_buy['category_id'],$match_category_buy_ids)){
                        	$checked = 'checked="checked"';
                        }else{
                        	$checked = '';
                        }
                        }
                    ?>
                    
                    <input type="checkbox" name="category_buy[]" value="<?php echo $category_buy['category_id']; ?>" <?php if(isset($checked)){ echo $checked; } ?>/>                    <?php echo $category_buy['name']; ?> </div>
                  <?php } ?>
                </div><a onclick="$(this).parent().find(':checkbox').attr('checked', true); CategoryBuySelectAll();"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false); CategoryBuySelectAll();"><?php echo $text_unselect_all; ?></a>
                </td>
            	<td>&nbsp;</td>
                <td><?php echo $entry_manufacturer; ?><br/>
                  <div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($manufacturers as $manufacturer_buy) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
					<?php 
                    if(isset($match_manufacturer_buy_ids)){
                        if(in_array($manufacturer_buy['manufacturer_id'],$match_manufacturer_buy_ids)){
                        	$checked = 'checked="checked"';
                        }else{
                        	$checked = '';
                        }
                       }
                    ?>
                    <input type="checkbox" name="manufacturer_buy[]" value="<?php echo $manufacturer_buy['manufacturer_id']; ?>" <?php if(isset($checked)){ echo $checked; } ?> />                    <?php echo $manufacturer_buy['name']; ?> </div>
                  <?php } ?>
                </div><a onclick="$(this).parent().find(':checkbox').attr('checked', true); CategoryBuyManufacturerSelectAll();"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false); CategoryBuyManufacturerSelectAll();"><?php echo $text_unselect_all; ?></a>
                </td>
            </tr>
            <td>&nbsp;</td>
            <tr>
              <td><?php echo $entry_product_product; ?><br/>
              <input type="text" name="product_buy_suggest" value="" />
              </td>
              <td>&nbsp;</td>
              <td><div class="scrollbox" id="advanced_coupon-product_buy">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($advanced_coupon_product_buy as $advanced_coupon_product_buy) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="advanced_coupon-product_buy<?php echo $advanced_coupon_product_buy['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $advanced_coupon_product_buy['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="product_buy[]" value="<?php echo $advanced_coupon_product_buy['product_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            </table>
           </td> 
            </tr>                                     
            
            <tr>
              <td><?php echo $entry_date_start; ?></td>
              <td><input type="text" name="date_start" value="<?php echo $date_start; ?>" size="12" id="date-start" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_date_end; ?></td>
              <td><input type="text" name="date_end" value="<?php echo $date_end; ?>" size="12" id="date-end" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_uses_total; ?></td>
              <td><input type="text" name="uses_total" value="<?php echo $uses_total; ?>" />
              <?php if ($error_uses_total) { ?>
                <span class="error"><?php echo $error_uses_total; ?></span>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_uses_customer; ?></td>
              <td><input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>" />
              <?php if ($error_uses_customer) { ?>
                <span class="error"><?php echo $error_uses_customer; ?></span>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
              
        <div id="tab-option">
          <table class="form">
            
            <tr>
              <td><?php echo $entry_logged; ?></td>
              <td><?php if ($logged) { ?>
                <input type="radio" name="logged" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="logged" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="logged" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="logged" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_shipping; ?></td>
              <td><?php if ($shipping) { ?>
                <input type="radio" name="shipping" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="shipping" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="shipping" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="shipping" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_coupon_combine; ?></td>
              <td><?php if ($coupon_combine) { ?>
                <input type="radio" name="coupon_combine" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="coupon_combine" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="coupon_combine" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="coupon_combine" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_special_combine; ?></td>
              <td><?php if($special_combine) { ?>
                <input type="radio" name="special_combine" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="special_combine" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="special_combine" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="special_combine" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_discount_combine; ?></td>
              <td><?php if ($discount_combine) { ?>
                <input type="radio" name="discount_combine" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="discount_combine" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="discount_combine" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="discount_combine" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $advanced_coupon_store)) { ?>
                    <input type="checkbox" name="store[]" value="0" checked="checked" />
                    <?php echo $text_default; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="store[]" value="0" />
                    <?php echo $text_default; ?>
                    <?php } ?>
                  </div>
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($store['store_id'], $advanced_coupon_store)) { ?>
                    <input type="checkbox" name="store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                <?php if ($error_store) { ?>
                <span class="error"><?php echo $error_store; ?></span>
                <?php } ?>
                </td>
            </tr>
           
            <tr>
              <td><?php echo $entry_customer_group; ?></td>
              <td><div class="scrollbox">
                 <?php if ($advanced_coupon_customer_group) { ?> 
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($customer_group['customer_group_id'], $advanced_coupon_customer_group)) { ?>
                    <input type="checkbox" name="customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                    <?php echo $customer_group['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                    <?php echo $customer_group['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                  <?php } else { ?>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <input type="checkbox" name="customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                    <?php echo $customer_group['name']; ?>
                  </div>
                  <?php } ?>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                <br/>
                <?php if ($error_customer_group) { ?>
                <span class="error"><?php echo $error_customer_group; ?></span>
                <?php } ?>
                </td>
            </tr> 
          
            <tr>
              <td><?php echo $entry_currency; ?></td>
              <td><div class="scrollbox">
                   <?php if ($advanced_coupon_currency) { ?> 
                  <?php foreach ($currencies as $currency) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($currency['currency_id'], $advanced_coupon_currency)) { ?>
                    <input type="checkbox" name="currency[]" value="<?php echo $currency['currency_id']; ?>" checked="checked" />
                    <?php echo $currency['title']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="currency[]" value="<?php echo $currency['currency_id']; ?>" />
                    <?php echo $currency['title']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                  <?php } else { ?>
                   <?php foreach ($currencies as $currency) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <input type="checkbox" name="currency[]" value="<?php echo $currency['currency_id']; ?>" checked="checked" />
                    <?php echo $currency['title']; ?>
                  </div>
                  <?php } ?>
                  <?php } ?>
                </div><a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                <br/>
            <?php if ($error_currency) { ?>
                <span class="error"><?php echo $error_currency; ?></span>
                <?php } ?>
                </td>
            </tr>               
            
            <tr>
              <td><?php echo $entry_language; ?></td>
              <td><div class="scrollbox">
                  <?php if ($advanced_coupon_language) { ?> 
                  <?php foreach ($languages as $language) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($language['language_id'], $advanced_coupon_language)) { ?>
                    <input type="checkbox" name="language[]" value="<?php echo $language['language_id']; ?>" checked="checked" />
                    <?php echo $language['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="language[]" value="<?php echo $language['language_id']; ?>" />
                    <?php echo $language['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                  <?php } else { ?>
                   <?php foreach ($languages as $language) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <input type="checkbox" name="language[]" value="<?php echo $language['language_id']; ?>" checked="checked" />
                    <?php echo $language['name']; ?>
                  </div>
                  <?php } ?>
                  <?php } ?>
                </div><a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                <br/>
            <?php if ($error_language) { ?>
                <span class="error"><?php echo $error_language; ?></span>
                <?php } ?>
                </td>
            </tr> 
            
            <tr>
              <td><?php echo $entry_day; ?></td>
              <td><div class="scrollbox">
                   <?php if ($advanced_coupon_day) { ?> 
                  <?php foreach ($days as $day) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($day, $advanced_coupon_day)) { ?>
                    <input type="checkbox" name="day[]" value="<?php echo $day; ?>" checked="checked"  />
                    <?php echo $day; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="day[]" value="<?php echo $day; ?>" />
                    <?php echo $day; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                  <?php } else { ?>
                  <?php foreach ($days as $day) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <input type="checkbox" name="day[]" value="<?php echo $day; ?>" checked="checked"  />
                    <?php echo $day; ?>
                  </div>
                  <?php } ?>
                  <?php } ?>
                </div><a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                <br/>
            <?php if ($error_day) { ?>
                <span class="error"><?php echo $error_day; ?></span>
                <?php } ?>
                </td>
            </tr>                                  
            
          </table>
        </div>
        
       <?php if ($advanced_coupon_id) { ?>
        <div id="tab-history">
          <div id="history"></div>
        </div>
        <?php } ?>
        
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function CategorySelectAll(){ 
	var category_id;
	var filter_category_id = document.getElementsByName('category[]');
	
	for(j=0;j<(filter_category_id.length);j++){
	category_id = document.getElementsByName('category[]').item(j).value;
																					//alert(category_id);
	$.ajax({
		url: 'index.php?route=sale/advanced_coupon/autocompleteAdvancedCoupon&token=<?php echo $token; ?>&filter_category_id=' +  category_id + '&limit=10000',
		dataType: 'json',
		success: function(json) {
			for (i = 0; i < json.length; i++) {
				if ($(filter_category_id).attr('checked') == 'checked') {
					$('#advanced_coupon-product' + json[i]['product_id']).remove();
					
					$('#advanced_coupon-product').append('<div id="advanced_coupon-product' + json[i]['product_id'] + '">' + json[i]['name'] + '<img src="view/image/delete.png" /><input type="hidden" name="advanced_coupon_product[]" value="' + json[i]['product_id'] + '" /></div>');
				} else {
					$('#advanced_coupon-product' + json[i]['product_id']).remove();
				}			
			}
			
			$('#advanced_coupon-product div:odd').attr('class', 'odd');
			$('#advanced_coupon-product div:even').attr('class', 'even');			
		}
	});
	}
}

$('input[name=\'category[]\']').bind('change', function() {
	var filter_category_id = this;
	
	$.ajax({
		url: 'index.php?route=sale/advanced_coupon/autocompleteAdvancedCoupon&token=<?php echo $token; ?>&filter_category_id=' +  filter_category_id.value + '&limit=10000',
		dataType: 'json',
		success: function(json) {
			for (i = 0; i < json.length; i++) {
				if ($(filter_category_id).attr('checked') == 'checked') {
					$('#advanced_coupon-product' + json[i]['product_id']).remove();
					
					$('#advanced_coupon-product').append('<div id="advanced_coupon-product' + json[i]['product_id'] + '">' + json[i]['name'] + '<img src="view/image/delete.png" /><input type="hidden" name="product[]" value="' + json[i]['product_id'] + '" /></div>');
				} else {
					$('#advanced_coupon-product' + json[i]['product_id']).remove();
				}			
			}
			
			$('#advanced_coupon-product div:odd').attr('class', 'odd');
			$('#advanced_coupon-product div:even').attr('class', 'even');			
		}
	});
});

function CategoryManufacturerSelectAll(){ 
	var category_id;
	var filter_category_id = document.getElementsByName('manufacturer[]');
	
	for(j=0;j<(filter_category_id.length);j++){
	category_id = document.getElementsByName('manufacturer[]').item(j).value;
																					//alert(category_id);
	$.ajax({
		url: 'index.php?route=sale/advanced_coupon/autocompleteAdvancedCoupon&token=<?php echo $token; ?>&filter_manufacturer_id=' +  category_id + '&limit=10000',
		dataType: 'json',
		success: function(json) {
			for (i = 0; i < json.length; i++) {
				if ($(filter_category_id).attr('checked') == 'checked') {
					$('#advanced_coupon-product' + json[i]['product_id']).remove();
					
					$('#advanced_coupon-product').append('<div id="advanced_coupon-product' + json[i]['product_id'] + '">' + json[i]['name'] + '<img src="view/image/delete.png" /><input type="hidden" name="advanced_coupon_product[]" value="' + json[i]['product_id'] + '" /></div>');
				} else {
					$('#advanced_coupon-product' + json[i]['product_id']).remove();
				}			
			}
			
			$('#advanced_coupon-product div:odd').attr('class', 'odd');
			$('#advanced_coupon-product div:even').attr('class', 'even');			
		}
	});
	}
}

$('input[name=\'manufacturer[]\']').live('change', function() {
	var filter_manufacturer_id = this;
	
	$.ajax({
		url: 'index.php?route=sale/advanced_coupon/autocompleteAdvancedCoupon&token=<?php echo $token; ?>&filter_manufacturer_id=' +  filter_manufacturer_id.value + '&limit=10000',
		dataType: 'json',
		success: function(json) {
			for (i = 0; i < json.length; i++) {
				if ($(filter_manufacturer_id).attr('checked') == 'checked') {
					$('#advanced_coupon-product' + json[i]['product_id']).remove();
					
					$('#advanced_coupon-product').append('<div id="advanced_coupon-product' + json[i]['product_id'] + '">' + json[i]['name'] + '<img src="view/image/delete.png" /><input type="hidden" name="product[]" value="' + json[i]['product_id'] + '" /></div>');
				} else {
					$('#advanced_coupon-product' + json[i]['product_id']).remove();
				}			
			}
			
			$('#advanced_coupon-product div:odd').attr('class', 'odd');
			$('#advanced_coupon-product div:even').attr('class', 'even');			
		}
	});
});

$('input[name=\'product_suggest\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/advanced_coupon/autocompleteAdvancedCoupon&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#advanced_coupon-product' + ui.item.value).remove();
		
		$('#advanced_coupon-product').append('<div id="advanced_coupon-product' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="product[]" value="' + ui.item.value + '" /></div>');

		$('#advanced_coupon-product div:odd').attr('class', 'odd');
		$('#advanced_coupon-product div:even').attr('class', 'even');
		
		$('input[name=\'product_suggest\']').val('');
		
		return false;
	}
});

$('#advanced_coupon-product div img').live('click', function() {
	$(this).parent().remove();
	
	$('#advanced_coupon-product div:odd').attr('class', 'odd');
	$('#advanced_coupon-product div:even').attr('class', 'even');	
});
//--></script> 

<script type="text/javascript"><!--
function CategoryBuySelectAll(){ 
	var category_id;
	var filter_category_id = document.getElementsByName('category_buy[]');
	
	for(j=0;j<(filter_category_id.length);j++){
	category_id = document.getElementsByName('category_buy[]').item(j).value;
																					//alert(category_id);
	$.ajax({
		url: 'index.php?route=sale/advanced_coupon/autocompleteAdvancedCoupon&token=<?php echo $token; ?>&filter_category_id=' +  category_id + '&limit=10000',
		dataType: 'json',
		success: function(json) {
			for (i = 0; i < json.length; i++) {
				if ($(filter_category_id).attr('checked') == 'checked') {
					$('#advanced_coupon-product_buy' + json[i]['product_id']).remove();
					
					$('#advanced_coupon-product_buy').append('<div id="advanced_coupon-product_buy' + json[i]['product_id'] + '">' + json[i]['name'] + '<img src="view/image/delete.png" /><input type="hidden" name="product_buy[]" value="' + json[i]['product_id'] + '" /></div>');
				} else {
					$('#advanced_coupon-product_buy' + json[i]['product_id']).remove();
				}			
			}
			
			$('#advanced_coupon-product_buy div:odd').attr('class', 'odd');
			$('#advanced_coupon-product_buy div:even').attr('class', 'even');			
		}
	});
	}
}

$('input[name=\'category_buy[]\']').bind('change', function() {
	var filter_category_id = this;
	
	$.ajax({
		url: 'index.php?route=sale/advanced_coupon/autocompleteAdvancedCoupon&token=<?php echo $token; ?>&filter_category_id=' +  filter_category_id.value + '&limit=10000',
		dataType: 'json',
		success: function(json) {
			for (i = 0; i < json.length; i++) {
				if ($(filter_category_id).attr('checked') == 'checked') {
					$('#advanced_coupon-product_buy' + json[i]['product_id']).remove();
					
					$('#advanced_coupon-product_buy').append('<div id="advanced_coupon-product_buy' + json[i]['product_id'] + '">' + json[i]['name'] + '<img src="view/image/delete.png" /><input type="hidden" name="product_buy[]" value="' + json[i]['product_id'] + '" /></div>');
				} else {
					$('#advanced_coupon-product_buy' + json[i]['product_id']).remove();
				}			
			}
			
			$('#advanced_coupon-product_buy div:odd').attr('class', 'odd');
			$('#advanced_coupon-product_buy div:even').attr('class', 'even');			
		}
	});
});

function CategoryBuyManufacturerSelectAll(){ 
	var category_id;
	var filter_category_id = document.getElementsByName('manufacturer_buy[]');
	
	for(j=0;j<(filter_category_id.length);j++){
	category_id = document.getElementsByName('manufacturer_buy[]').item(j).value;
																					//alert(category_id);
	$.ajax({
		url: 'index.php?route=sale/advanced_coupon/autocompleteAdvancedCoupon&token=<?php echo $token; ?>&filter_manufacturer_id=' +  category_id + '&limit=10000',
		dataType: 'json',
		success: function(json) {
			for (i = 0; i < json.length; i++) {
				if ($(filter_category_id).attr('checked') == 'checked') {
					$('#advanced_coupon-product_buy' + json[i]['product_id']).remove();
					
					$('#advanced_coupon-product_buy').append('<div id="advanced_coupon-product_buy' + json[i]['product_id'] + '">' + json[i]['name'] + '<img src="view/image/delete.png" /><input type="hidden" name="product_buy[]" value="' + json[i]['product_id'] + '" /></div>');
				} else {
					$('#advanced_coupon-product_buy' + json[i]['product_id']).remove();
				}			
			}
			
			$('#advanced_coupon-product_buy div:odd').attr('class', 'odd');
			$('#advanced_coupon-product_buy div:even').attr('class', 'even');			
		}
	});
	}
}

$('input[name=\'manufacturer_buy[]\']').bind('change', function() {
	var filter_manufacturer_id = this;
	
	$.ajax({
		url: 'index.php?route=sale/advanced_coupon/autocompleteAdvancedCoupon&token=<?php echo $token; ?>&filter_manufacturer_id=' +  filter_manufacturer_id.value + '&limit=10000',
		dataType: 'json',
		success: function(json) {
			for (i = 0; i < json.length; i++) {
				if ($(filter_manufacturer_id).attr('checked') == 'checked') {
					$('#advanced_coupon-product_buy' + json[i]['product_id']).remove();
					
					$('#advanced_coupon-product_buy').append('<div id="advanced_coupon-product_buy' + json[i]['product_id'] + '">' + json[i]['name'] + '<img src="view/image/delete.png" /><input type="hidden" name="product_buy[]" value="' + json[i]['product_id'] + '" /></div>');
				} else {
					$('#advanced_coupon-product_buy' + json[i]['product_id']).remove();
				}			
			}
			
			$('#advanced_coupon-product_buy div:odd').attr('class', 'odd');
			$('#advanced_coupon-product_buy div:even').attr('class', 'even');			
		}
	});
});

$('input[name=\'product_buy_suggest\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/advanced_coupon/autocompleteAdvancedCoupon&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#advanced_coupon-product_buy' + ui.item.value).remove();
		
		$('#advanced_coupon-product_buy').append('<div id="advanced_coupon-product_buy' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="product_buy[]" value="' + ui.item.value + '" /></div>');

		$('#advanced_coupon-product_buy div:odd').attr('class', 'odd');
		$('#advanced_coupon-product_buy div:even').attr('class', 'even');
		
		$('input[name=\'product_buy_suggest\']').val('');
		
		return false;
	}
});

$('#advanced_coupon-product_buy div img').live('click', function() {
	$(this).parent().remove();
	
	$('#advanced_coupon-product_buy div:odd').attr('class', 'odd');
	$('#advanced_coupon-product_buy div:even').attr('class', 'even');	
});
//--></script> 


<script type="text/javascript"><!--
$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
//--></script>
<?php if ($advanced_coupon_id) { ?>
<script type="text/javascript"><!--
$('#history .pagination a').live('click', function() {
	$('#history').load(this.href);
	
	return false;
});			

$('#history').load('index.php?route=sale/advanced_coupon/history&token=<?php echo $token; ?>&advanced_coupon_id=<?php echo $advanced_coupon_id; ?>');
//--></script>
<?php } ?>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 

<?php echo $footer; ?>