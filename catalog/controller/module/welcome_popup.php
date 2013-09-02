<?php

class ControllerModuleWelcomePopup extends Controller {

    public function index() {
        if ($this->extensions->isInstalled('welcome_popup', 'module') && $this->config->get('welcome_popup_status')) {
            if (!isset($this->request->cookie['welcome_popup']) || $this->request->cookie['welcome_popup'] != $this->config->get('welcome_popup_timestamp')) {
                if (!$this->customer->isLogged()) {

                    $this->language->load('module/welcome_popup');

                    $this->data['heading_title'] = $this->language->get('heading_title');

                    $content = $this->config->get('welcome_popup_content');
                    $this->data['content'] = html_entity_decode($content[$this->config->get('config_language_id')]['content'], ENT_QUOTES, 'UTF-8');

                    $this->data['newsletter_modal'] = $this->url->link('module/welcome_popup');

                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/welcome_popup.tpl')) {
                        $this->template = $this->config->get('config_template') . '/template/module/welcome_popup.tpl';
                    } else {
                        $this->template = 'default/template/module/welcome_popup.tpl';
                    }

                    $this->render();
                }
                setcookie('welcome_popup', $this->config->get('welcome_popup_timestamp'), time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
            
        }
    }
    }

}

?>
