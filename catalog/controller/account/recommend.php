<?php

class ControllerAccountRecommend extends Controller {

    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/recommend', '', 'SSL');

            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->language->load('account/recommend');

        $title = $this->config->get('recommend_page_meta_title');
        if (!empty($title)) {
            $this->document->setTitle($title);
        } else {
            $this->document->setTitle($this->language->get('heading_title'));
        }
        $this->document->setDescription($this->config->get('recommend_page_meta_description'));
        $this->document->setKeywords($this->config->get('recommend_page_meta_keyword'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_recommend'),
            'href' => $this->url->link('account/recommend', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->config->get('recommend_page_title');
        if (empty($this->data['heading_title']))
            $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_instructions'] = html_entity_decode($this->config->get('recommend_page_instructions'));
        if (empty($this->data['text_instructions']))
            $this->data['text_instructions'] = $this->language->get('text_instructions');

        $this->data['text_friend'] = $this->language->get('text_friend');
        $this->data['entry_firstname'] = $this->language->get('entry_firstname');
        $this->data['entry_lastname'] = $this->language->get('entry_lastname');
        $this->data['entry_email'] = $this->language->get('entry_email');

        $this->data['button_submit'] = $this->language->get('button_submit');

        $this->data['action'] = $this->url->link('account/recommend', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/recommend.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/recommend.tpl';
        } else {
            $this->template = 'default/template/account/recommend.tpl';
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
    
    public function callback() {

        //Uses ajax to add recommendation to list
        $this->language->load('account/recommend');
        
        $success = null;
        $error = null;
        
        if (!$this->request->post['email'] || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                $error = $this->language->get('error_email');
        }
        
        if ($this->request->post['email'] && $this->request->post['email'] == $this->customer->getEmail()) {
                $error = $this->language->get('error_own_email');
        }
        
        if (!$this->request->post['firstname']) {
                $error = $this->language->get('error_firstname');
        }
        
        if (!$this->request->post['lastname']) {
                $error = $this->language->get('error_lastname');
        }

        if (!$error) {
            $this->load->model('account/customer');
            //Check whether already a customer
            $data = array (
                'filter_email' => $this->request->post['email']
            );
            $results = $this->model_account_customer->getCustomers($data);

            if (count($results) > 0) {
                if ($this->config->get('recommend_error_if_customer'))
                    $error = $this->language->get('error_already_customer');
                else
                    $success = $this->language->get('success_recommend_sent');
            } else {
                
                $this->load->model('account/recommend');
                $data = array (
                    'firstname' => $this->request->post['firstname'],
                    'lastname'  => $this->request->post['lastname'],
                    'email'     => $this->request->post['email']
                );
                $this->model_account_recommend->send($data);
                $success = $this->language->get('success_recommend_sent');
            }
        }
        
        $success = sprintf($success, $this->request->post['email']);
        $error = sprintf($error, $this->request->post['email']);
        
        $return = array();
        if ($error) {
            $return['status'] = 1;
            $return['msg'] = $error;
        } else {
            $return['status'] = 0;
            $return['msg'] = $success;
        }
        
       echo json_encode($return);
       exit;
                
    }
}

?>