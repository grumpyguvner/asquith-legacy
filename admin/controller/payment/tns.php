<?php
class ControllerPaymentTns extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/tns');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('tns', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_off'] = $this->language->get('text_off');
		
		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_security'] = $this->language->get('entry_security');
		$this->data['entry_secret'] = $this->language->get('entry_secret');
		$this->data['entry_callback'] = $this->language->get('entry_callback');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

  		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
		}

 		if (isset($this->error['secret'])) {
			$this->data['error_secret'] = $this->error['secret'];
		} else {
			$this->data['error_secret'] = '';
		}

 		if (isset($this->error['security'])) {
			$this->data['error_security'] = $this->error['security'];
		} else {
			$this->data['error_security'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/tns', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/tns', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['tns_merchant'])) {
			$this->data['tns_merchant'] = $this->request->post['tns_merchant'];
		} else {
			$this->data['tns_merchant'] = $this->config->get('tns_merchant');
		}

		if (isset($this->request->post['tns_security'])) {
			$this->data['tns_security'] = $this->request->post['tns_security'];
		} else {
			$this->data['tns_security'] = $this->config->get('tns_security');
		}

		if (isset($this->request->post['tns_secret'])) {
			$this->data['tns_secret'] = $this->request->post['tns_secret'];
		} else {
			$this->data['tns_secret'] = $this->config->get('tns_secret');
		}
		
		$this->data['callback'] = HTTP_CATALOG . 'index.php?route=payment/tns/callback';

		if (isset($this->request->post['tns_test'])) {
			$this->data['tns_test'] = $this->request->post['tns_test'];
		} else {
			$this->data['tns_test'] = $this->config->get('tns_test');
		}
		
		if (isset($this->request->post['tns_total'])) {
			$this->data['tns_total'] = $this->request->post['tns_total'];
		} else {
			$this->data['tns_total'] = $this->config->get('tns_total'); 
		} 
				
		if (isset($this->request->post['tns_order_status_id'])) {
			$this->data['tns_order_status_id'] = $this->request->post['tns_order_status_id'];
		} else {
			$this->data['tns_order_status_id'] = $this->config->get('tns_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['tns_geo_zone_id'])) {
			$this->data['tns_geo_zone_id'] = $this->request->post['tns_geo_zone_id'];
		} else {
			$this->data['tns_geo_zone_id'] = $this->config->get('tns_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['tns_status'])) {
			$this->data['tns_status'] = $this->request->post['tns_status'];
		} else {
			$this->data['tns_status'] = $this->config->get('tns_status');
		}
		
		if (isset($this->request->post['tns_sort_order'])) {
			$this->data['tns_sort_order'] = $this->request->post['tns_sort_order'];
		} else {
			$this->data['tns_sort_order'] = $this->config->get('tns_sort_order');
		}

		$this->template = 'payment/tns.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/tns')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['tns_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['tns_security']) {
			$this->error['security'] = $this->language->get('error_security');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>