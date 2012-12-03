<?php 
class ControllerPaymentOffline extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/offline');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('offline', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$this->data['entry_offline'] = $this->language->get('entry_offline');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_ips'] = $this->language->get('entry_ips');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if (isset($this->error['offline_' . $language['language_id']])) {
				$this->data['error_offline_' . $language['language_id']] = $this->error['offline_' . $language['language_id']];
			} else {
				$this->data['error_offline_' . $language['language_id']] = '';
			}
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
			'href'      => $this->url->link('payment/offline', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/offline', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('localisation/language');
		
		foreach ($languages as $language) {
			if (isset($this->request->post['offline_offline_' . $language['language_id']])) {
				$this->data['offline_offline_' . $language['language_id']] = $this->request->post['offline_offline_' . $language['language_id']];
			} else {
				$this->data['offline_offline_' . $language['language_id']] = $this->config->get('offline_offline_' . $language['language_id']);
			}
		}
		
		$this->data['languages'] = $languages;
		
		if (isset($this->request->post['offline_total'])) {
			$this->data['offline_total'] = $this->request->post['offline_total'];
		} else {
			$this->data['offline_total'] = $this->config->get('offline_total'); 
		} 
				
		if (isset($this->request->post['offline_order_status_id'])) {
			$this->data['offline_order_status_id'] = $this->request->post['offline_order_status_id'];
		} else {
			$this->data['offline_order_status_id'] = $this->config->get('offline_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['offline_geo_zone_id'])) {
			$this->data['offline_geo_zone_id'] = $this->request->post['offline_geo_zone_id'];
		} else {
			$this->data['offline_geo_zone_id'] = $this->config->get('offline_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['offline_ips'])) {
			$this->data['offline_ips'] = $this->request->post['offline_ips'];
		} else {
			$this->data['offline_ips'] = $this->config->get('offline_ips'); 
		} 
		
		if (isset($this->request->post['offline_status'])) {
			$this->data['offline_status'] = $this->request->post['offline_status'];
		} else {
			$this->data['offline_status'] = $this->config->get('offline_status');
		}
		
		if (isset($this->request->post['offline_sort_order'])) {
			$this->data['offline_sort_order'] = $this->request->post['offline_sort_order'];
		} else {
			$this->data['offline_sort_order'] = $this->config->get('offline_sort_order');
		}
		

		$this->template = 'payment/offline.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/offline')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if (!$this->request->post['offline_offline_' . $language['language_id']]) {
				$this->error['offline_' .  $language['language_id']] = $this->language->get('error_offline');
			}
		}
                
                if (!$this->request->post['offline_ips']) {
                        $this->error['offline_ips'] = $this->language->get('error_ips');
                }

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>