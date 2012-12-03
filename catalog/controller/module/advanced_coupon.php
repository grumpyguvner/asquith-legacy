<?php
class ControllerModuleAdvancedCoupon extends Controller {
	protected function index($setting) {
		$this->language->load('module/advanced_coupon'); 

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['button_cart'] = $this->language->get('button_cart');
		
		$this->data['advanced_coupon_id'] = $setting['advanced_coupon_id'];
		
		$this->load->model('checkout/advanced_coupon');
		
		$advanced_coupon = $this->model_checkout_advanced_coupon->getAdvancedCouponNameCode($setting['advanced_coupon_id']);
		
		$this->data['advanced_coupon_title'] = $advanced_coupon['name'];
		$this->data['advanced_coupon_code'] = $advanced_coupon['code'];
			
		$results = $this->model_checkout_advanced_coupon->getAdvancedCouponProducts($setting['advanced_coupon_id'],$setting['product']);
		
		if(empty($results)) {
		$this->load->model('catalog/product');
		$results = $this->model_catalog_product->getProducts();
		}
		
		$this->load->model('tool/image');

		$this->data['products'] = array();
		
		if (empty($setting['limit'])) {
			$setting['limit'] = 5;
		}

		$results = array_slice($results, 0, (int)$setting['limit']);
		
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = false;
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}
					
			if ((float)$result['special']) { 
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}
			
			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}
			
			$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'price'   	 => $price,
				'special' 	 => $special,
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/advanced_coupon.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/advanced_coupon.tpl';
		} else {
			$this->template = 'default/template/module/advanced_coupon.tpl';
		}

		$this->render();
	}
}
?>