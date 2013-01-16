<?php
class ControllerPaymentTns extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$this->data['action'] = $this->url->link('payment/tns/gateway');

		$this->data['order_id'] = $order_info['order_id'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/tns.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/tns.tpl';
		} else {
			$this->template = 'default/template/payment/tns.tpl';
		}	
		
		$this->render();
	}
    
	public function gateway() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        
        $orderAttempt = $this->session->data['orderTransactions'];
        if (!is_array($orderAttempt)) $orderAttempt = array();
        $orderRef = (!isset($orderAttempt[$order_info['order_id']])) ? 1 : $orderAttempt[$order_info['order_id']]+1;
        $orderAttempt[$order_info['order_id']] = $orderRef;
        $this->session->data['orderTransactions'] = $orderAttempt;
		
        // build array of params in alphabetical order to create secure hash then create url to redirect user to.
        $payment = array();
        
        $payment['vpc_AccessCode'] = $this->config->get('tns_security');
        $payment['vpc_Amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
        $payment['vpc_Command'] = 'pay';
        $payment['vpc_Locale'] = 'en';
        $payment['vpc_Merchant'] = $this->config->get('tns_merchant');
        if ($this->config->get('tns_test')) $payment['vpc_Merchant'] = 'TEST' . $payment['vpc_Merchant'];
        $payment['vpc_MerchTxnRef'] = $order_info['order_id'] . '-' . $orderRef;
        $payment['vpc_OrderInfo'] = $order_info['order_id'];
        $payment['vpc_ReturnURL'] = $this->url->link('payment/tns/callback');
        $payment['vpc_Version'] = 1;
        
        $payment['vpc_SecureHash'] = md5(bin2hex($this->config->get('tns_secret').implode('', $payment)));
        
        $url = 'https://im.dialectpayments.com/vpcpay?' . http_build_query($payment);
        
		
		$this->redirect($url);
	}
	
	public function callback() {
		$this->language->load('payment/tns');
	
		$this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

		if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
			$this->data['base'] = $this->config->get('config_url');
		} else {
			$this->data['base'] = $this->config->get('config_ssl');
		}
	
		$this->data['language'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
	
		$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		
		$this->data['text_response'] = $this->language->get('text_response');
		$this->data['text_success'] = $this->language->get('text_success');
		$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
		$this->data['text_failure'] = $this->language->get('text_failure');
		$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/checkout', '', 'SSL'));
        
        // creat array of values returned,to build hash to check against secure hash
        $process = array();
        
        if (isset($this->request->get['vpc_AcqResponseCode'])) $process['vpc_AcqResponseCode'] = $this->request->get['vpc_AcqResponseCode'];
        if (isset($this->request->get['vpc_Amount'])) $process['vpc_Amount'] = $this->request->get['vpc_Amount'];
        if (isset($this->request->get['vpc_AuthorizeId'])) $process['vpc_AuthorizeId'] = $this->request->get['vpc_AuthorizeId'];
        if (isset($this->request->get['vpc_BatchNo'])) $process['vpc_BatchNo'] = $this->request->get['vpc_BatchNo'];
        if (isset($this->request->get['vpc_Card'])) $process['vpc_Card'] = $this->request->get['vpc_Card'];
        if (isset($this->request->get['vpc_Command'])) $process['vpc_Command'] = $this->request->get['vpc_Command'];
        if (isset($this->request->get['vpc_Merchant'])) $process['vpc_Merchant'] = $this->request->get['vpc_Merchant'];
        if (isset($this->request->get['vpc_MerchTxnRef'])) $process['vpc_MerchTxnRef'] = $this->request->get['vpc_MerchTxnRef'];
        if (isset($this->request->get['vpc_Message'])) $process['vpc_Message'] = $this->request->get['vpc_Message'];
        if (isset($this->request->get['vpc_OrderInfo'])) $process['vpc_OrderInfo'] = $this->request->get['vpc_OrderInfo'];
        if (isset($this->request->get['vpc_ReceiptNo'])) $process['vpc_ReceiptNo'] = $this->request->get['vpc_ReceiptNo'];
        if (isset($this->request->get['vpc_TransactionNo'])) $process['vpc_TransactionNo'] = $this->request->get['vpc_TransactionNo'];
        if (isset($this->request->get['vpc_TxnResponseCode'])) $process['vpc_TxnResponseCode'] = $this->request->get['vpc_TxnResponseCode'];
        
        $calcHash = md5(bin2hex($this->config->get('tns_secret').implode('', $process)));
	
		if (isset($process['vpc_TxnResponseCode']) && $process['vpc_TxnResponseCode'] == 0) { 
			$this->load->model('checkout/order');

			// If returned successful but callbackPW doesn't match, set order to pendind and record reason
			if (isset($this->request->get['vpc_SecureHash']) && ($calcHash == $this->request->get['vpc_SecureHash'])) {
				$this->model_checkout_order->confirm($process['vpc_OrderInfo'], $this->config->get('tns_order_status_id'));
			} else {
				$this->model_checkout_order->confirm($process['vpc_OrderInfo'], $this->config->get('config_order_status_id'), $this->language->get('text_hash_mismatch'));
			}
	
			$message = '';

			if (isset($process['vpc_TransactionNo']))   $message .= 'transNo: ' . $process['vpc_TransactionNo'] . "\n";
			if (isset($process['vpc_OrderInfo']))       $message .= 'orderNo: ' . $process['vpc_OrderInfo'] . "\n";
			if (isset($process['vpc_AuthorizeId']))     $message .= 'authId: ' . $process['vpc_AuthorizeId'] . "\n";
			if (isset($process['vpc_ReceiptNo']))       $message .= 'recieptNo: ' . $process['vpc_ReceiptNo'] . "\n";
			if (isset($process['vpc_BatchNo']))         $message .= 'batchNo: ' . $process['vpc_BatchNo'] . "\n";
			if (isset($process['vpc_AcqResponseCode'])) $message .= 'acqResCode: ' . $process['vpc_AcqResponseCode'] . "\n";
			if (isset($process['vpc_TxnResponseCode'])) $message .= 'resCode: ' . $process['vpc_TxnResponseCode'] . "\n";
			if (isset($process['vpc_Card']))            $message .= 'card: ' . $process['vpc_Card'] . "\n";
			if (isset($process['vpc_Message']))         $message .= 'message: ' . $process['vpc_Message'] . "\n";
		
			$this->model_checkout_order->update($process['vpc_OrderInfo'], $this->config->get('tns_order_status_id'), $message, false);
	
			$this->data['continue'] = $this->url->link('checkout/success');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/tns_success.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/tns_success.tpl';
			} else {
				$this->template = 'default/template/payment/tns_success.tpl';
			}	
	
			$this->response->setOutput($this->render());				
		} else {
			$this->data['continue'] = $this->url->link('checkout/cart');
	
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/tns_failure.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/tns_failure.tpl';
			} else {
				$this->template = 'default/template/payment/tns_failure.tpl';
			}
			
			$this->response->setOutput($this->render());					
		}
	}
}