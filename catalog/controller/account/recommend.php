<?php

require_once(DIR_SYSTEM . 'library/mailchimp.php');

class ControllerAccountRecommend extends Controller {

    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/recommend', '', 'SSL');

            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->language->load('account/recommend');

        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

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
                                $retval = $mailchimp->listSubscribe("24ebe763cc", $this->request->post['email_' . $cnt ], $additionalVars, 'html');
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

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_instructions'] = $this->language->get('text_instructions');

        $this->data['text_friend'] = $this->language->get('text_friend');
        $this->data['entry_firstname'] = $this->language->get('entry_firstname');
        $this->data['entry_lastname'] = $this->language->get('entry_lastname');
        $this->data['entry_email'] = $this->language->get('entry_email');

        $this->data['button_add_another'] = $this->language->get('button_add_another');
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

}

?>