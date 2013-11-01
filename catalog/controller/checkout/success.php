<?php

class ControllerCheckoutSuccess extends Controller {

    public function index() {

        if (isset($this->session->data['order_id']) && (!empty($this->session->data['order_id']))) {
            $this->session->data['last_order_id'] = $this->session->data['order_id'];
        }

        //Giftwrapper change
        unset($this->session->data['giftwrapping']);
        //End giftwrapper change
        if (isset($this->session->data['order_id'])) {
            $this->cart->clear();

            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['guest']);
            unset($this->session->data['comment']);
            unset($this->session->data['order_id']);
            unset($this->session->data['coupon']);
            unset($this->session->data['advanced_coupon']);
            unset($this->session->data['reward']);
            unset($this->session->data['voucher']);
            unset($this->session->data['vouchers']);
        }

        $this->language->load('checkout/success');

        if (!empty($this->session->data['last_order_id'])) {
            $this->document->setTitle(sprintf($this->language->get('heading_title_customer'), $this->session->data['last_order_id']));
        } else {
        $this->document->setTitle($this->language->get('heading_title'));
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home'),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('checkout/cart'),
            'text' => $this->language->get('text_basket'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('checkout/checkout', '', 'SSL'),
            'text' => $this->language->get('text_checkout'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('checkout/success'),
            'text' => $this->language->get('text_success'),
            'separator' => $this->language->get('text_separator')
        );

        if (!empty($this->session->data['last_order_id'])) {
            $this->data['heading_title'] = sprintf($this->language->get('heading_title_customer'), $this->session->data['last_order_id']);
        } else {
        $this->data['heading_title'] = $this->language->get('heading_title');
        }

        if ($this->customer->isLogged()) {
            $this->data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/order/info&order_id=' . $this->session->data['last_order_id'], '', 'SSL'), $this->session->data['last_order_id'], $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
        } else {
            $this->data['text_message'] = sprintf($this->language->get('text_guest'), $this->session->data['last_order_id'], $this->url->link('information/contact'));
        }

        $data_layer = array();
        if (!empty($this->session->data['last_order_id'])) {

            $this->load->model('account/order');
            $order_info = $this->model_account_order->getOrder($this->session->data['last_order_id']);

            if ($order_info) {

		$data_layer['transactionId'] = $order_info['order_id'];
		$data_layer['transactionDate'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
		$data_layer['transactionType'] = 'SALE';
		$data_layer['transactionCurrency'] = $order_info['currency_code'];
		$data_layer['transactionTotal'] = $order_info['total'];
		$data_layer['transactionShippingMethod'] = $order_info['shipping_method'];
		$data_layer['transactionPaymentType'] = $order_info['payment_method'];

                $products_data = array();
                
                $products = $this->model_account_order->getOrderProducts($this->session->data['last_order_id']);
                foreach ($products as $product) {
                    $products_data['name'] = $product['name'];
                    $products_data['sku'] = $product['model'];

                    $options = $this->model_account_order->getOrderOptions($this->session->data['last_order_id'], $product['order_product_id']);

                    foreach ($options as $option) {
                        if ($option['type'] != 'file') {
                            $value = $option['value'];
        } else {
                            $value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
        }

                        $products_data[$option['name']] = (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value);
                    }

                    $products_data['price'] = $product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0);
                    $products_data['quantity'] = $product['quantity'];
                    $data_layer['transactionProducts'][] = $products_data;
                }
                
            }
        }
        $this->setDataLayer($data_layer);

        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['continue'] = $this->url->link('common/home');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/success.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/success.tpl';
        } else {
            $this->template = 'default/template/common/success.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }

}

?>