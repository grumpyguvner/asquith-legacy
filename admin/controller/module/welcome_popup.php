<?php

class ControllerModuleWelcomePopup extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('module/welcome_popup');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('welcome_popup', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');
        

        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_content'] = $this->language->get('entry_content');
        $this->data['entry_reset_cookie'] = $this->language->get('entry_reset_cookie');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        
        $this->load->model('localisation/language');
        $this->data['languages'] = $this->model_localisation_language->getLanguages();
        
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/welcome_popup', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        
        $this->data['action'] = $this->url->link('module/welcome_popup', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->request->post['welcome_popup_status'])) {
            $this->data['welcome_popup_status'] = $this->request->post['welcome_popup_status'];
        } elseif ($this->config->get('welcome_popup_status')) {
            $this->data['welcome_popup_status'] = $this->config->get('welcome_popup_status');
        } else {
            $this->data['welcome_popup_status'] = 0;
        }
        
        if (isset($this->request->post['welcome_popup_content'])) {
            $this->data['welcome_popup_content'] = $this->request->post['welcome_popup_content'];
        } elseif ($this->config->get('welcome_popup_content')) {
            $this->data['welcome_popup_content'] = $this->config->get('welcome_popup_content');
        } else {
            $this->data['welcome_popup_content'] = '';
        }
        
        if (isset($this->request->post['welcome_popup_reset_cookie'])) {
            $this->data['welcome_popup_reset_cookie'] = $this->request->post['welcome_popup_reset_cookie'];
        } else {
            $this->data['welcome_popup_reset_cookie'] = '';
        }

        $this->template = 'module/welcome_popup.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );

        $this->response->setOutput($this->render());
    }
    
    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/welcome_popup')) {
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
