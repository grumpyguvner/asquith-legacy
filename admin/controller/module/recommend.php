<?php
class ControllerModuleRecommend extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/recommend');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('recommend', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
                $this->data['text_yes'] = $this->language->get('text_yes');
                $this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

                $this->data['page_no_login'] = $this->language->get('page_no_login');
                $this->data['page_title'] = $this->language->get('page_title');
                $this->data['page_meta_title'] = $this->language->get('page_meta_title');
                $this->data['page_meta_keyword'] = $this->language->get('page_meta_keyword');
                $this->data['page_meta_description'] = $this->language->get('page_meta_description');
                $this->data['page_instructions'] = $this->language->get('page_instructions');

                $this->data['email_send_from_customer'] = $this->language->get('email_send_from_customer');
                $this->data['error_if_customer'] = $this->language->get('error_if_customer');
                $this->data['email_allow_resend'] = $this->language->get('email_allow_resend');
                $this->data['email_subject'] = $this->language->get('email_subject');
                $this->data['email_body'] = $this->language->get('email_body');

                $this->data['voucher_send_automatically'] = $this->language->get('voucher_send_automatically');
                $this->data['voucher_amount'] = $this->language->get('voucher_amount');
                
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/recommend', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/recommend', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['recommend_module'])) {
			$this->data['modules'] = $this->request->post['recommend_module'];
		} elseif ($this->config->get('recommend_module')) { 
			$this->data['modules'] = $this->config->get('recommend_module');
		}
                
		if (isset($this->request->post['recommend_page_no_login'])) {
			$this->data['recommend_page_no_login'] = $this->request->post['recommend_page_no_login'];
		} else {
			$this->data['recommend_page_no_login'] = $this->config->get('recommend_page_no_login');
		}
                
		if (isset($this->request->post['recommend_page_title'])) {
			$this->data['recommend_page_title'] = $this->request->post['recommend_page_title'];
		} else {
			$this->data['recommend_page_title'] = $this->config->get('recommend_page_title');
		}
                
		if (isset($this->request->post['recommend_page_meta_title'])) {
			$this->data['recommend_page_meta_title'] = $this->request->post['recommend_page_meta_title'];
		} else {
			$this->data['recommend_page_meta_title'] = $this->config->get('recommend_page_meta_title');
		}
                
		if (isset($this->request->post['recommend_page_meta_keyword'])) {
			$this->data['recommend_page_meta_keyword'] = $this->request->post['recommend_page_meta_keyword'];
		} else {
			$this->data['recommend_page_meta_keyword'] = $this->config->get('recommend_page_meta_keyword');
		}
                
		if (isset($this->request->post['recommend_page_meta_description'])) {
			$this->data['recommend_page_meta_description'] = $this->request->post['recommend_page_meta_description'];
		} else {
			$this->data['recommend_page_meta_description'] = $this->config->get('recommend_page_meta_description');
		}
                
		if (isset($this->request->post['recommend_page_instructions'])) {
			$this->data['recommend_page_instructions'] = $this->request->post['recommend_page_instructions'];
		} else {
			$this->data['recommend_page_instructions'] = $this->config->get('recommend_page_instructions');
		}
                
		if (isset($this->request->post['recommend_email_send_from_customer'])) {
			$this->data['recommend_email_send_from_customer'] = $this->request->post['recommend_email_send_from_customer'];
		} else {
			$this->data['recommend_email_send_from_customer'] = $this->config->get('recommend_email_send_from_customer');
		}
                
		if (isset($this->request->post['recommend_email_allow_resend'])) {
			$this->data['recommend_email_allow_resend'] = $this->request->post['recommend_email_allow_resend'];
		} else {
			$this->data['recommend_email_allow_resend'] = $this->config->get('recommend_email_allow_resend');
		}
                
		if (isset($this->request->post['recommend_error_if_customer'])) {
			$this->data['recommend_error_if_customer'] = $this->request->post['recommend_error_if_customer'];
		} else {
			$this->data['recommend_error_if_customer'] = $this->config->get('recommend_error_if_customer');
		}
                
		if (isset($this->request->post['recommend_email_subject'])) {
			$this->data['recommend_email_subject'] = $this->request->post['recommend_email_subject'];
		} else {
			$this->data['recommend_email_subject'] = $this->config->get('recommend_email_subject');
		}
                
		if (isset($this->request->post['recommend_email_body'])) {
			$this->data['recommend_email_body'] = $this->request->post['recommend_email_body'];
		} else {
			$this->data['recommend_email_body'] = $this->config->get('recommend_email_body');
		}
                
		if (isset($this->request->post['recommend_voucher_send_automatically'])) {
			$this->data['recommend_voucher_send_automatically'] = $this->request->post['recommend_voucher_send_automatically'];
		} else {
			$this->data['recommend_voucher_send_automatically'] = $this->config->get('recommend_voucher_send_automatically');
		}
                
		if (isset($this->request->post['recommend_voucher_amount'])) {
			$this->data['recommend_voucher_amount'] = $this->request->post['recommend_voucher_amount'];
		} else {
			$this->data['recommend_voucher_amount'] = $this->config->get('recommend_voucher_amount');
		}
                
		if (isset($this->request->post['recommend_voucher_subject'])) {
			$this->data['recommend_voucher_subject'] = $this->request->post['recommend_voucher_subject'];
		} else {
			$this->data['recommend_voucher_subject'] = $this->config->get('recommend_voucher_subject');
		}
                
		if (isset($this->request->post['recommend_voucher_body'])) {
			$this->data['recommend_voucher_body'] = $this->request->post['recommend_voucher_body'];
		} else {
			$this->data['recommend_voucher_body'] = $this->config->get('recommend_voucher_body');
		}

                
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'module/recommend.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/recommend')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>