<?php
require_once(DIR_SYSTEM . 'library/mailchimp.php');

class ControllerModuleNewsletter extends Controller {

	protected function index($setting) {
		$this->name = basename(__FILE__, '.php');

		if ($setting['store_id'] == $this->config->get('config_store_id')) {

			$this->session->data['newsletter_name_required'] = $setting['name'];
			$this->data = array_merge($this->data, $this->load->language('module/' . $this->name));
      		$this->data['heading_title'] = $setting['language_id'][$this->config->get('config_language_id')];

			if ($setting['name'] != 'off') {
        		$this->data['name'] = $setting['name'];
			} else {
				$this->data['name'] = 'off';
			}

	        if (isset($this->request->get['route'])) {
        		$this->data['action'] = (HTTP_SERVER . 'index.php?route=' . $this->request->get['route']);
			} else {
				$this->data['action'] = HTTP_SERVER;
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/'.$this->name.'.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/'.$this->name.'.tpl';
			} else {
				$this->template = 'default/template/module/'.$this->name.'.tpl';
			}


			$this->render();
		}
  	}

  	public function callback() {

		$this->language->load('module/newsletter');

		if (!isset($this->request->get['subscribe'])) {
			$this->request->get['subscribe'] = true;
		}
        
        $success = null;
        $error = null;

		if (!$this->request->get['email'] || !filter_var($this->request->get['email'], FILTER_VALIDATE_EMAIL)) {
			$error = $this->language->get('error_email');
		}

    	if (empty($this->session->data['newsletter_name_required'])) {
			$this->session->data['newsletter_name_required'] = 'optional';
		}

		if (empty($this->request->get['name'])) {
			$this->request->get['name'] = '';
		}

		if (empty($this->request->get['name2'])) {
			$this->request->get['name2'] = '';
		}

//		if ($this->session->data['newsletter_name_required'] == 'required' && !$this->request->get['name']) {
//			$this->response->setOutput($this->language->get('error_name'), $this->config->get('config_compression'));
//			return;
//		}
        if (is_null($error))
        {
            $this->load->model('account/newsletter');
            $this->load->model('account/customer');
            $total = $this->model_account_newsletter->getTotalNewsletterByEmail($this->request->get['email']);

            if ($total && $this->request->get['subscribe'] && !$this->customer->isLogged()) { // subscribe
                $error = $this->language->get('error_exists');
            } elseif ($total && $this->request->get['subscribe'] && $this->customer->getEmail() != $this->request->get['email']) { // subscribe
                $error = $this->language->get('error_exists');
            } elseif (!$total && !$this->request->get['subscribe']) { // unsubscribe
                $error = $this->language->get('error_exists');
            }
        }

        if (is_null($error))
        {
            if ($this->request->get['subscribe']) {
                $this->model_account_newsletter->subscribe($this->request->get['email'], $this->request->get['name'], $this->request->get['name2']);
                
                if ($this->config->get('newsletter_mailchimp_enabled') && $this->config->get('newsletter_mailchimp_double_optin'))
                {
                    $success = $this->language->get('text_subscribed_optin');
                } else {
                    $success = $this->language->get('text_subscribed');
                }
            } else {
                $this->model_account_newsletter->unsubscribe($this->request->get['email']);
                $success = $this->language->get('text_unsubscribed');
            }
        }
        
        $success = sprintf($success, $this->request->get['email']);
        $error = sprintf($error, $this->request->get['email']);
        
        if (isset($this->request->get['ajax']))
        {
            $ajax = array('success' => $success, 'error' => $error);
            $this->response->setOutput(json_encode($ajax), $this->config->get('config_compression'));
        } elseif ($error) {
            $this->response->setOutput($error, $this->config->get('config_compression'));
        } else {
            $this->response->setOutput($success, $this->config->get('config_compression'));
        }
	}

}
?>