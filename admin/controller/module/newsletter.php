<?php
require_once(DIR_SYSTEM . 'library/mailchimp.php');
require_once(DIR_SYSTEM . 'library/csrest_general.php');
require_once(DIR_SYSTEM . 'library/csrest_clients.php');

class ControllerModuleNewsletter extends Controller {
	private $error = array();
	private $name = '';

	public function index() {

		if (!isset($this->session->data['token'])) {
			$this->session->data['token'] = 0;
		}
		$this->data['token'] = $this->session->data['token'];

		$this->checkdb();

		$this->name = basename(__FILE__, '.php');

		$this->data = array_merge($this->data, $this->load->language('module/' . $this->name));

		$this->data['classname'] = $this->name;

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($this->name, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
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
			'href'      => $this->url->link('module/' . $this->name, 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

                $this->data['tab_modules'] = $this->language->get('tab_modules');
                $this->data['tab_mailchimp'] = $this->language->get('tab_mailchimp');
                $this->data['tab_mailcampaign'] = $this->language->get('tab_mailcampaign');
                
                $this->data['text_none'] = $this->language->get('text_none');
                
		$this->data['action'] = $this->url->link('module/' . $this->name, 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();

		if (isset($this->request->post[$this->name . '_module'])) {
			$this->data['modules'] = $this->request->post[$this->name . '_module'];
		} elseif ($this->config->get($this->name . '_module')) {
			$this->data['modules'] = $this->config->get($this->name . '_module');
		}
        
        $this->data['error_newsletter_override_yes'] = false;
        if ($this->config->get($this->name . '_mailchimp_enabled'))
        {
            $this->data['error_newsletter_override_yes'] = true;
        }
        
		if (isset($this->request->post[$this->name . '_mailchimp_enabled'])) {
			$this->data[$this->name . '_mailchimp_enabled'] = $this->request->post[$this->name . '_mailchimp_enabled'];
		} else {
			$this->data[$this->name . '_mailchimp_enabled'] = $this->config->get($this->name . '_mailchimp_enabled');
		}
        
		if (isset($this->request->post[$this->name . '_mailchimp_apikey'])) {
			$this->data[$this->name . '_mailchimp_apikey'] = $this->request->post[$this->name . '_mailchimp_apikey'];
		} else {
			$this->data[$this->name . '_mailchimp_apikey'] = $this->config->get($this->name . '_mailchimp_apikey');
		}
        
		if (isset($this->request->post[$this->name . '_mailchimp_listid'])) {
			$this->data[$this->name . '_mailchimp_listid'] = $this->request->post[$this->name . '_mailchimp_listid'];
		} else {
			$this->data[$this->name . '_mailchimp_listid'] = $this->config->get($this->name . '_mailchimp_listid');
		}
        
if (isset($this->request->post[$this->name . '_mailchimp_account_listid'])) {
			$this->data[$this->name . '_mailchimp_account_listid'] = $this->request->post[$this->name . '_mailchimp_account_listid'];
		} else {
			$this->data[$this->name . '_mailchimp_account_listid'] = $this->config->get($this->name . '_mailchimp_account_listid');
		}
                
                if (isset($this->request->post[$this->name . '_mailchimp_account_optin'])) {
			$this->data[$this->name . '_mailchimp_account_optin'] = $this->request->post[$this->name . '_mailchimp_account_optin'];
		} else {
			$this->data[$this->name . '_mailchimp_account_optin'] = $this->config->get($this->name . '_mailchimp_account_optin');
		}
                
                if (isset($this->request->post[$this->name . '_mailchimp_checkout_listid'])) {
			$this->data[$this->name . '_mailchimp_checkout_listid'] = $this->request->post[$this->name . '_mailchimp_checkout_listid'];
		} else {
			$this->data[$this->name . '_mailchimp_checkout_listid'] = $this->config->get($this->name . '_mailchimp_checkout_listid');
		}
                
                if (isset($this->request->post[$this->name . '_mailchimp_checkout_optin'])) {
			$this->data[$this->name . '_mailchimp_checkout_optin'] = $this->request->post[$this->name . '_mailchimp_checkout_optin'];
		} else {
			$this->data[$this->name . '_mailchimp_checkout_optin'] = $this->config->get($this->name . '_mailchimp_checkout_optin');
		}
                
                if (isset($this->request->post[$this->name . '_mailchimp_double_optin'])) {
			$this->data[$this->name . '_mailchimp_double_optin'] = $this->request->post[$this->name . '_mailchimp_double_optin'];
		} else {
			$this->data[$this->name . '_mailchimp_double_optin'] = $this->config->get($this->name . '_mailchimp_double_optin');
		}
        
		if (isset($this->request->post[$this->name . '_mailchimp_update_existing'])) {
			$this->data[$this->name . '_mailchimp_update_existing'] = $this->request->post[$this->name . '_mailchimp_update_existing'];
		} else {
			$this->data[$this->name . '_mailchimp_update_existing'] = $this->config->get($this->name . '_mailchimp_update_existing');
		}
        
		if (isset($this->request->post[$this->name . '_mailchimp_send_welcome'])) {
			$this->data[$this->name . '_mailchimp_send_welcome'] = $this->request->post[$this->name . '_mailchimp_send_welcome'];
		} else {
			$this->data[$this->name . '_mailchimp_send_welcome'] = $this->config->get($this->name . '_mailchimp_send_welcome');
		}
                
                if (isset($this->request->post[$this->name . '_mailchimp_custom_fields'])) {
			$this->data[$this->name . '_mailchimp_custom_fields'] = $this->request->post[$this->name . '_mailchimp_custom_fields'];
		} else {
			$this->data[$this->name . '_mailchimp_custom_fields'] = $this->config->get($this->name . '_mailchimp_custom_fields');
		}
        
        $this->data[$this->name . '_mailchimp_lists'] = false;
        
        if ($this->data[$this->name . '_mailchimp_apikey'])
        {
            $mailchimp = new mailchimp($this->data[$this->name . '_mailchimp_apikey']);
            
            if ($mailchimp->ping())
            {
                $lists = $mailchimp->lists();
                if (is_array($lists) && $lists['total'] > 0)
                {
                    $this->data[$this->name . '_mailchimp_lists'] = array();
                    foreach ($lists['data'] as $list)
                    {
                        $this->data[$this->name . '_mailchimp_lists'][$list['id']] = $list['name'];
                    }
                }
            }
        }
        
        
 //----------------------- Start mailcampaign ---------------------------------
                
                if ($this->config->get($this->name . '_mailcampaign_enabled'))
                {
                    $this->data['error_newsletter_override_yes'] = true;
                }
        
		if (isset($this->request->post[$this->name . '_mailcampaign_enabled'])) {
			$this->data[$this->name . '_mailcampaign_enabled'] = $this->request->post[$this->name . '_mailcampaign_enabled'];
		} else {
			$this->data[$this->name . '_mailcampaign_enabled'] = $this->config->get($this->name . '_mailcampaign_enabled');
		}
                
                if (isset($this->request->post[$this->name . '_mailcampaign_apikey'])) {
			$this->data[$this->name . '_mailcampaign_apikey'] = $this->request->post[$this->name . '_mailcampaign_apikey'];
		} elseif ($this->config->get($this->name . '_mailcampaign_apikey')) {
			$this->data[$this->name . '_mailcampaign_apikey'] = $this->config->get($this->name . '_mailcampaign_apikey');
		} else {
                        $this->data[$this->name . '_mailcampaign_apikey'] = 'c5b9a54763d113e8d24fad6e93e55c7a';
                }
                
                if (isset($this->request->post[$this->name . '_mailcampaign_listid'])) {
			$this->data[$this->name . '_mailcampaign_listid'] = $this->request->post[$this->name . '_mailcampaign_listid'];
		} else {
			$this->data[$this->name . '_mailcampaign_listid'] = $this->config->get($this->name . '_mailcampaign_listid');
		}
                
                if (isset($this->request->post[$this->name . '_mailcampaign_account_listid'])) {
			$this->data[$this->name . '_mailcampaign_account_listid'] = $this->request->post[$this->name . '_mailcampaign_account_listid'];
		} else {
			$this->data[$this->name . '_mailcampaign_account_listid'] = $this->config->get($this->name . '_mailcampaign_account_listid');
		}
                
                if (isset($this->request->post[$this->name . '_mailcampaign_account_optin'])) {
			$this->data[$this->name . '_mailcampaign_account_optin'] = $this->request->post[$this->name . '_mailcampaign_account_optin'];
		} else {
			$this->data[$this->name . '_mailcampaign_account_optin'] = $this->config->get($this->name . '_mailcampaign_account_optin');
		}
                
                if (isset($this->request->post[$this->name . '_mailcampaign_checkout_listid'])) {
			$this->data[$this->name . '_mailcampaign_checkout_listid'] = $this->request->post[$this->name . '_mailcampaign_checkout_listid'];
		} else {
			$this->data[$this->name . '_mailcampaign_checkout_listid'] = $this->config->get($this->name . '_mailcampaign_checkout_listid');
		}
                
                if (isset($this->request->post[$this->name . '_mailcampaign_custom_fields'])) {
			$this->data[$this->name . '_mailcampaign_custom_fields'] = $this->request->post[$this->name . '_mailcampaign_custom_fields'];
		} else {
			$this->data[$this->name . '_mailcampaign_custom_fields'] = $this->config->get($this->name . '_mailcampaign_custom_fields');
		}
                
                if (isset($this->request->post[$this->name . '_mailcampaign_checkout_optin'])) {
			$this->data[$this->name . '_mailcampaign_checkout_optin'] = $this->request->post[$this->name . '_mailcampaign_checkout_optin'];
		} else {
			$this->data[$this->name . '_mailcampaign_checkout_optin'] = $this->config->get($this->name . '_mailcampaign_checkout_optin');
		}
                
                if (isset($this->request->post[$this->name . '_mailcampaign_client_id'])) {
			$this->data[$this->name . '_mailcampaign_client_id'] = $this->request->post[$this->name . '_mailcampaign_client_id'];
		} else {
			$this->data[$this->name . '_mailcampaign_client_id'] = $this->config->get($this->name . '_mailcampaign_client_id');
		}
                
                /*if (isset($this->request->post[$this->name . '_mailcampaign_double_optin'])) {
			$this->data[$this->name . '_mailcampaign_double_optin'] = $this->request->post[$this->name . '_mailcampaign_double_optin'];
		} else {
			$this->data[$this->name . '_mailcampaign_double_optin'] = $this->config->get($this->name . '_mailcampaign_double_optin');
		}*/
        
		if (isset($this->request->post[$this->name . '_mailcampaign_update_existing'])) {
			$this->data[$this->name . '_mailcampaign_update_existing'] = $this->request->post[$this->name . '_mailcampaign_update_existing'];
		} else {
			$this->data[$this->name . '_mailcampaign_update_existing'] = $this->config->get($this->name . '_mailcampaign_update_existing');
		}
        
		if (isset($this->request->post[$this->name . '_mailcampaign_send_welcome'])) {
			$this->data[$this->name . '_mailcampaign_send_welcome'] = $this->request->post[$this->name . '_mailcampaign_send_welcome'];
		} else {
			$this->data[$this->name . '_mailcampaign_send_welcome'] = $this->config->get($this->name . '_mailcampaign_send_welcome');
		}
                
        
        $this->data[$this->name . '_mailcampaign_clients'] = false;                
        
        
        if ($this->data[$this->name . '_mailcampaign_apikey'] && $this->user->isSuperuser()) {
            $mailc_general = new CS_REST_General($this->data[$this->name . '_mailcampaign_apikey']);
             $clients = $mailc_general->get_clients();
               if ($clients->was_successful())
                {
                    $this->data[$this->name . '_mailcampaign_clients'] = array();
                    foreach ($clients->response as $client)
                    {
                        $this->data[$this->name . '_mailcampaign_clients'][$client->ClientID] = $client->Name;
                    }
                }
        }    
        
        
        $this->data[$this->name . '_mailcampaign_lists'] = false;
        if ($this->data[$this->name . '_mailcampaign_apikey'] && $this->data[$this->name . '_mailcampaign_client_id'])
        {
            $mailcampaign = new CS_REST_Clients($this->data[$this->name . '_mailcampaign_client_id'], $this->data[$this->name . '_mailcampaign_apikey'] );
           
                $lists = $mailcampaign->get_lists();
                if ($lists->was_successful())
                {
                    $this->data[$this->name . '_mailcampaign_lists'] = array();
                    foreach ($lists->response as $list)
                    {
                        $this->data[$this->name . '_mailcampaign_lists'][$list->ListID] = $list->Name;
                    }
                }
                        
        }
        
        
 //----------------------- End mailcampaign ---------------------------------


		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		$this->template = 'module/'.$this->name.'.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		$this->name = basename(__FILE__, '.php');
		if (!$this->user->hasPermission('modify', 'module/'.$this->name)) {
			$this->error['warning'] = $this->language->get('error_permission');
            return false;
		}

		foreach ($this->request->post as $modules) {
            if (is_array($modules))
            {
                foreach ($modules as $module) {
                    foreach ($module['language_id'] as $title) {
                        if (!strlen($title)) {
                            $this->error['title'] = $this->language->get('error_title');
                        }
                    }
                }
            }
		}
        
        if ($this->request->post['newsletter_mailchimp_enabled'])
        {
            if (!$this->request->post['newsletter_mailchimp_apikey'])
            {
                $this->error['warning'] = $this->language->get('error_newsletter_mailchimp_apikey');
                return false;
            }
            
            $mailchimp = new mailchimp($this->request->post['newsletter_mailchimp_apikey']);
            
            if (!$mailchimp->ping())
            {
                $this->error['warning'] = $this->language->get('error_newsletter_mailchimp_apikey');
                return false;
            }
            
            if (!$this->request->post['newsletter_mailchimp_listid'])
            {
                $this->error['warning'] = $this->language->get('error_newsletter_mailchimp_listid');
                return false;
            }
            
            $lists = $mailchimp->lists();
            $flag = false;
            if (is_array($lists) && $lists['total'] > 0)
            {
                foreach ($lists['data'] as $list)
                {
                    if ($list['id'] == $this->request->post['newsletter_mailchimp_listid'])
                    {
                        $flag = true;
                        break;
                    }
                }
            }
            if (!$flag)
            {
                $this->error['warning'] = $this->language->get('error_newsletter_mailchimp_listid');
                return false;
            }
        }
        
        //-------------------------- Start Mail campaign -------------------------------
        
        if ($this->request->post['newsletter_mailcampaign_enabled'])
        {
            if (empty($this->request->post['newsletter_mailcampaign_apikey']))
            {
                $this->error['warning'] = $this->language->get('error_newsletter_mailcampaign_apikey');
                return false;
            }
            
            
            $mailc_general = new CS_REST_General($this->request->post['newsletter_mailcampaign_apikey']);
            $clients = $mailc_general->get_clients();
            
            $total_clients = array();
                    foreach ($clients->response as $client)
                    {
                        $total_clients[] = $client->ClientID;
                    }
                        
            if (empty($this->request->post[$this->name . '_mailcampaign_client_id']) && !array_search($this->request->post[$this->name . '_mailcampaign_client_id'], $total_clients) ){
                $this->error['warning'] = $this->language->get('error_newsletter_mailcampaign_clients');
                return false;
                }
            
            
            
            $mailcampaign = new CS_REST_Clients($this->request->post['newsletter_mailcampaign_client_id'], $this->request->post['newsletter_mailcampaign_apikey'] );
            $lists = $mailcampaign->get_lists();
            
            $total_lists = array();
            foreach ($lists->response as $list)
                    {
                        $total_lists[] = $list->ListID;
                    }
            if(empty($this->request->post[$this->name . '_mailcampaign_listid']) && !array_search($this->request->post[$this->name . '_mailcampaign_listid'], $total_lists)){
               $this->error['warning'] = $this->language->get('error_newsletter_mailcampaign_lists');
                return false;
                }
           
        }
        
        //------------------------ End Mail Campaign ------------------------------------

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function checkdb() {

		$sql = "
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "newsletter` (
			`email` varchar(64) NOT NULL default '',
			`name` varchar(64) NOT NULL default '',
			`name2` varchar(64) NOT NULL default '',
			PRIMARY KEY  (`email`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		$this->db->query($sql);
	}

	public function insert() {
		if (!isset($this->request->get['email']) || !$this->request->get['email'] || strpos($this->request->get['email'], '@') === false) {
			return;
		}

		if (!isset($this->request->get['name']) || !$this->request->get['name']) {
			$this->request->get['name'] = '';
		}

		if (!isset($this->request->get['name2']) || !$this->request->get['name2']) {
			$this->request->get['name2'] = '';
		}

		$this->load->model('sale/newsletter');

		$this->model_sale_newsletter->addNewsletterEmail($this->request->get['email'], $this->request->get['name'], $this->request->get['name2']);

		// refresh the list
		$this->autocomplete();
	}

	public function delete() {
		if (!isset($this->request->get['email']) || !$this->request->get['email']) {
			return;
		}

		$this->load->model('sale/newsletter');

		$this->model_sale_newsletter->deleteNewsletterEmail($this->request->get['email']);

		// refresh the list
		$this->autocomplete();
	}

    public function autocomplete() {
        if (!isset($this->request->get['filter'])) {
			return;
		}

		$output = '';

        $this->load->model('sale/newsletter');

        $data = array(
            'filter' 	  => $this->request->get['filter'],
            'start'       => 0,
            'limit'       => 1000
        );

        $results = $this->model_sale_newsletter->getNewslettersByFilter($data);

		// Also include emails from registered customers
		$results2 = $this->model_sale_newsletter->getSubscribersFromCustomerTable();
		$results = array_merge($results, $results2);
		//

        $class = 'odd';

        foreach ($results as $result) {
            $class = ($class == 'even' ? 'odd' : 'even');
            $output .= '<div class="' . $class . '">';
            if ($result['name']) {
                $output .= $result['name'] . '&nbsp;';
            }
            $output .= '&lt;' . $result['email'] . '&gt;';
            $output .= '</div>';
        }

        $this->response->setOutput($output);
    }
}
?>