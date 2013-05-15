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
        
        
        $success = sprintf($success, $this->request->post['email']);
        $error = sprintf($error, $this->request->post['email']);
        
        if ($error)
            echo json_encode($error);
        else
            echo json_encode($success);
        exit;
        
        
        $affiliate_ref = $this->_getAffiliateCode();

            $this->load->model('account/customer');
            
            $cnt = 0;
            while (true) {
                ++$cnt;
                if (!isset($this->request->post['email_' . $cnt ]))
                        break;

                //Check whether already a customer
                $data = array (
                    'filter_email' => $this->request->post['email_' . $cnt ]
                );
                $results = $this->model_account_customer->getCustomers($data);
                
                if (count($results) > 0) {
                    print_r("already a customer");
                } else {
                    print_r("new!");
                    if (filter_var($this->request->post['email_' . $cnt ], FILTER_VALIDATE_EMAIL) && $this->config->get('newsletter_mailchimp_enabled')) {
                        $mailchimp = new mailchimp($this->config->get('newsletter_mailchimp_apikey'));

//                        $retval = $mailchimp->listMemberInfo($this->config->get('newsletter_mailchimp_listid'), $this->request->post['email_' . $cnt ]);
                        $retval = $mailchimp->listMemberInfo("24ebe763cc", $this->request->post['email_' . $cnt ]);

                        if (!$mailchimp->errorCode) {
                            $alreadyExists = ($retval['success'] && $retval['data'][0]['status'] != 'unsubscribed') ? 1 : 0;
                            if ($alreadyExists) {
                                print_r("already recommended");
                            } else {
                                print_r("new recommendation");
                                $additionalVars = array (
                                    'FNAME' => $this->request->post['firstname_' . $cnt ],
                                    'LNAME' => $this->request->post['lastname_' . $cnt ],
                                    'AFFILIATE' => "xxxxxxx",
                                    'RECOMBY' => $this->customer->getFirstName() . " " . $this->customer->getLastName()
                                );
                                $retval = $mailchimp->listSubscribe("24ebe763cc", $this->request->post['email_' . $cnt ], $additionalVars, 'html', false, false, true, true);
                                var_dump($retval);
                           }
                        }
                    }
                }
                var_dump($results);
                die("happy");
                
                print_r($this->request->post['email_' . $cnt ]);
                print_r();
                print_r();
                $this->data['recommend'] = $this->customer->getRecommend();


                $this->session->data['success'] = $this->language->get('text_success');
                
            }

            $this->redirect($this->url->link('account/account', '', 'SSL'));
                
    }

    private function _getAffiliateCode() {
        //Fetch the affiliate code for the logged in customer
        $this->load->model('affiliate/affiliate');
        $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByEmail($this->customer->getEmail());
        //If no affiliate record is found then we need to create one
        if (!$affiliate_info) {
            $data['firstname'] = $this->customer->getFirstName();
            $data['lastname'] = $this->customer->getLastName();
            $data['email'] = $this->customer->getEmail();
            $data['telephone'] = $this->customer->getTelephone();
            $data['fax'] = $this->customer->getFax();
            
//            ) . "', password = '" . $this->db->escape(md5($data['password'])) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', code = '" . $this->db->escape(uniqid()) . "', commission = '" . (float)$this->config->get('config_commission') . "', tax = '" . $this->db->escape($data['tax']) . "', payment = '" . $this->db->escape($data['payment']) . "', cheque = '" . $this->db->escape($data['cheque']) . "', paypal = '" . $this->db->escape($data['paypal']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_branch_number = '" . $this->db->escape($data['bank_branch_number']) . "', bank_swift_code = '" . $this->db->escape($data['bank_swift_code']) . "', bank_account_name = '" . $this->db->escape($data['bank_account_name']) . "', bank_account_number = '" . $this->db->escape($data['bank_account_number']) . "', status = '1', date_added = NOW()");

        }
    }
}

?>