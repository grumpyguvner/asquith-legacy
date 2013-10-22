<?php

require_once(DIR_SYSTEM . 'library/mailchimp.php');
require_once(DIR_SYSTEM . 'library/csrest_subscribers.php');

//-----------------------------------------
// Author: Qphoria@gmail.com
// Web: http://www.OpenCartGuru.com/
//-----------------------------------------
class ModelAccountNewsletter extends Model {

    public function subscribe($email = '', $fields = array(), $listType = '') {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return;
        
        if($this->customer->IsLogged())
        {
            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            
            $fields = array_merge($customer_info,$fields);
        }
        
        if (!isset($fields['firstname'])) $fields['firstname'] = '';
        if (!isset($fields['lastname'])) $fields['lastname'] = '';
        if (!isset($fields['language'])) $fields['language'] = $this->session->data['language'];
        if (!isset($fields['currency'])) $fields['currency'] = $this->currency->getCode();
        
        if (!isset($fields['name'])) $fields['name'] = trim($fields['firstname'] . ' ' . $fields['lastname']);
        
        if ($this->config->get('newsletter_mailcampaign_enabled')) {
            $listID = $this->config->get('newsletter_mailcampaign_listid');
            
            switch ($listType)
            {
                case 'account':
                    if ($this->config->get('newsletter_mailcampaign_account_listid'))
                    {
                        $listID = $this->config->get('newsletter_mailcampaign_account_listid');
                    }
                    break;
                case 'checkout':
                    if ($this->config->get('newsletter_mailcampaign_checkout_listid'))
                    {
                        $listID = $this->config->get('newsletter_mailcampaign_checkout_listid');
                    }
                    break;
            }
            
            $data = array();
            
            $options = explode(',', $this->config->get('newsletter_mailcampaign_custom_fields'));
            $customFields = array();
            foreach($options as $option)
            {
                $option = explode(':', $option);
                if (isset($fields[$option[0]]))
                {
                    if (isset($option[1]))
                    {
                        switch ($option[1])
                        {
                            case 'date':
                                $time = strtotime($fields[$option[0]]);
                                if($time === false)
                                {
                                    $fields[$option[0]] = '';
                                }
                                else
                                {
                                   $fields[$option[0]] = date('Y/m/d', $time);   
                                }
                                break;
                            default:
                                break;
                        }
                    }
                    
                    $customFields[] = array(
                          'Key' => $option[0],
                          'Value' => $fields[$option[0]]
                    );
                }
            }
            
            $mailcampaign = new CS_REST_Subscribers($listID, $this->config->get('newsletter_mailcampaign_apikey'));
            $result = $mailcampaign->add(array('EmailAddress' => $email, 'Name' => $fields['name'],
                'CustomFields' => $customFields,
                'Resubscribe' => true
                ));

            if ($result->was_successful()) {
                //echo "Subscribed with code ".$result->http_status_code;
            } else {
                //echo 'Failed with code '.$result->http_status_code."\n<br /><pre>";
                //var_dump($result->response);
                //echo '</pre>';
            } 
        }

        if ($this->config->get('newsletter_mailchimp_enabled')) {
            $listID = $this->config->get('newsletter_mailchimp_listid');
            
            switch ($listType)
            {
                case 'account':
                    if ($this->config->get('newsletter_mailchimp_account_listid'))
                    {
                        $listID = $this->config->get('newsletter_mailchimp_account_listid');
                    }
                    break;
                case 'checkout':
                    if ($this->config->get('newsletter_mailchimp_checkout_listid'))
                    {
                        $listID = $this->config->get('newsletter_mailchimp_checkout_listid');
                    }
                    break;
            }
            
            $mailchimp = new mailchimp($this->config->get('newsletter_mailchimp_apikey'));
            
            $data = array();
                    
            if ($fields['firstname']) $data['FNAME'] = $fields['firstname'];
            if ($fields['lastname']) $data['LNAME'] = $fields['lastname'];
            
            $options = explode(',', $this->config->get('newsletter_mailchimp_custom_fields'));
            foreach($options as $option)
            {
                $option = explode(':', strtolower($option));
                if (isset($fields[$option[0]]))
                {
                    if (isset($option[1]))
                    {
                        switch ($option[1])
                        {
                            case 'date':
                                $time = strtotime($fields[$option[0]]);
                                if($time === false)
                                {
                                    $fields[$option[0]] = '';
                                }
                                else
                                {
                                   $fields[$option[0]] = date('Y/m/d', $time);   
                                }
                                break;
                            default:
                                break;
                        }
                    }
                    
                    $data[$option[0]] = $fields[$option[0]];
                }
            }
            
            $retval = $mailchimp->listSubscribe($listID, $email, $data, 'html', $this->config->get('newsletter_mailchimp_double_optin'), $this->config->get('newsletter_mailchimp_update_existing'), true, $this->config->get('newsletter_mailchimp_send_welcome'));
            
            if ($mailchimp->errorCode) {
                //echo "Unable to load listSubscribe()!\n";
                //echo "\tCode=".$api->errorCode."\n";
                //echo "\tMsg=".$api->errorMessage."\n";
            } else {
                //echo "Subscribed - look for the confirmation email!\n";
            }
        }

        if ($this->config->get('newsletter_mailchimp_enabled') || $this->config->get('newsletter_mailcampaign_enabled')) {
            return;
        }

        //If logged in and using own email, then update the customer table newsletter setting to avoid duplicates
        if ($this->customer->IsLogged()) {
            $customer_id = $this->customer->getId();
            $query = $this->db->query("SELECT DISTINCT email FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");
            if ($query->row['email'] == $email) {
                $query = $this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 1 WHERE email = '" . $this->db->escape($email) . "'");
            } else {
                $query = $this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET email = '" . $this->db->escape($email) . "', name = '" . $this->db->escape($fields['firstname']) . "', name2 = '" . $this->db->escape($fields['lastname']) . "'");
            }
        } else {
            $query = $this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET email = '" . $this->db->escape($email) . "', name = '" . $this->db->escape($fields['firstname']) . "', name2 = '" . $this->db->escape($fields['lastname']) . "'");
        }

        $this->language->load('mail/newsletter');

        $subject = $this->config->get('config_email_newsletter_subject');
        $message = $this->config->get('config_email_newsletter_body');

        if (empty($subject)) {
            $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
        }

        if (empty($message)) {
            $message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

            $message .= $this->language->get('text_details') . "\n\n";
            $message .= $this->language->get('text_thanks') . "\n";
            $message .= sprintf($this->language->get('text_signoff'), $this->config->get('config_name'));
        }

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->hostname = $this->config->get('config_smtp_host');
        $mail->username = $this->config->get('config_smtp_username');
        $mail->password = $this->config->get('config_smtp_password');
        $mail->port = $this->config->get('config_smtp_port');
        $mail->timeout = $this->config->get('config_smtp_timeout');
        $mail->setTo($email);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
        $mail->send();

        // Send to main admin email if new account email is enabled
        if ($this->config->get('config_account_mail')) {
            $subject = sprintf($this->language->get('text_notify_subject'), $this->config->get('config_name'));
            $message = sprintf($this->language->get('text_notify_details'), $email);

            $mail->setTo($this->config->get('config_email'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();

            // Send to additional alert emails if new account email is enabled
            $emails = explode(',', $this->config->get('config_alert_emails'));

            foreach ($emails as $email) {
                if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }
    }

    public function unsubscribe($email = '') {
        if ($this->config->get('newsletter_mailcampaign_enabled')) {
            $mailcampaign = new CS_REST_Subscribers($this->config->get('newsletter_mailcampaign_listid'), $this->config->get('newsletter_mailcampaign_apikey'));
            $result = $mailcampaign->unsubscribe($email);

            if ($result->was_successful()) {
                // echo "Unsubscribed with code ".$result->http_status_code;
            } else {
                // echo 'Failed with code '.$result->http_status_code."\n<br /><pre>";
                // var_dump($result->response);
                // echo '</pre>';
            }
            
            if ($this->config->get('newsletter_mailcampaign_account_listid'))
            {
                $mailcampaign = new CS_REST_Subscribers($this->config->get('newsletter_mailcampaign_account_listid'), $this->config->get('newsletter_mailcampaign_apikey'));
                $result = $mailcampaign->unsubscribe($email);
            }
            if ($this->config->get('newsletter_mailcampaign_checkout_listid'))
            {  
                $mailcampaign = new CS_REST_Subscribers($this->config->get('newsletter_mailcampaign_checkout_listid'), $this->config->get('newsletter_mailcampaign_apikey'));
                $result = $mailcampaign->unsubscribe($email);
            }
        }

        if ($this->config->get('newsletter_mailchimp_enabled')) {
            $mailchimp = new mailchimp($this->config->get('newsletter_mailchimp_apikey'));

            $retval = $mailchimp->listUnsubscribe($this->config->get('newsletter_mailchimp_listid'), $email);

            if ($mailchimp->errorCode) {
                //echo "Unable to load listUnsubscribe()!\n";
                //echo "\tCode=".$mailchimp->errorCode."\n";
                //echo "\tMsg=".$mailchimp->errorMessage."\n";
            } else {
                //echo "Returned: ".$retval."\n";
            }
            
            if ($this->config->get('newsletter_mailchimp_account_listid'))
            {
                $retval = $mailchimp->listUnsubscribe($this->config->get('newsletter_mailchimp_account_listid'), $email);
            }
            
            if ($this->config->get('newsletter_mailchimp_checkout_listid'))
            {
                $retval = $mailchimp->listUnsubscribe($this->config->get('newsletter_mailchimp_checkout_listid'), $email);
            }
        }

        if ($this->config->get('newsletter_mailchimp_enabled') || $this->config->get('newsletter_mailcampaign_enabled')) {
            return;
        }

        //If logged in and using own email, then update the customer table newsletter setting to avoid duplicates
        if ($this->customer->IsLogged()) {
            $customer_id = $this->customer->getId();
            $query = $this->db->query("SELECT DISTINCT email FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");
            if ($query->row['email'] == $email) {
                $query = $this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 0 WHERE email = '" . $this->db->escape($email) . "'");
            }
        }
        
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "newsletter WHERE email = '" . $this->db->escape($email) . "'");
    }

    public function getTotalNewsletterByEmail($email) {
        $flag = false;

        if ($this->config->get('newsletter_mailcampaign_enabled')) {
            if ($flag === false) $flag = 0;
            $mailcampaign = new CS_REST_Subscribers($this->config->get('newsletter_mailcampaign_listid'), $this->config->get('newsletter_mailcampaign_apikey'));
            $retval_campaing = $mailcampaign->get($email);
            if ($retval_campaing->was_successful() && $retval_campaing->response->State != 'Unsubscribed') {
                $flag += 1;
            }
            
            if ($this->config->get('newsletter_mailcampaign_account_listid'))
            {
                $mailcampaign = new CS_REST_Subscribers($this->config->get('newsletter_mailcampaign_account_listid'), $this->config->get('newsletter_mailcampaign_apikey'));
                $retval_campaing = $mailcampaign->get($email);
                if ($retval_campaing->was_successful() && $retval_campaing->response->State != 'Unsubscribed') {
                    $flag += 1;
                }
            }
            
            if ($this->config->get('newsletter_mailcampaign_checkout_listid'))
            {
                $mailcampaign = new CS_REST_Subscribers($this->config->get('newsletter_mailcampaign_checkout_listid'), $this->config->get('newsletter_mailcampaign_apikey'));
                $retval_campaing = $mailcampaign->get($email);
                if ($retval_campaing->was_successful() && $retval_campaing->response->State != 'Unsubscribed') {
                    $flag += 1;
                }
            }
        }

        if ($this->config->get('newsletter_mailchimp_enabled')) {
            if ($flag === false) $flag = 0;
            
            $mailchimp = new mailchimp($this->config->get('newsletter_mailchimp_apikey'));

            $retval = $mailchimp->listMemberInfo($this->config->get('newsletter_mailchimp_listid'), $email);

            if (!$mailchimp->errorCode) {
                if ($retval['success'] && $retval['data'][0]['status'] == 'subscribed')
                    $flag += 1;
            }
            
            if ($this->config->get('newsletter_mailchimp_account_listid'))
            {
                $retval = $mailchimp->listMemberInfo($this->config->get('newsletter_mailchimp_account_listid'), $email);

                if (!$mailchimp->errorCode) {
                    if ($retval['success'] && $retval['data'][0]['status'] == 'subscribed')
                        $flag += 1;
                }
            }
            
            if ($this->config->get('newsletter_mailcampaign_checkout_listid'))
            {
                $retval = $mailchimp->listMemberInfo($this->config->get('newsletter_mailcampaign_checkout_listid'), $email);

                if (!$mailchimp->errorCode) {
                    if ($retval['success'] && $retval['data'][0]['status'] == 'subscribed')
                        $flag += 1;
                }
            }
        }

        if ($flag !== FALSE) {
            return $flag;
        }

        if ($this->customer->IsLogged()) {
            $customer_id = $this->customer->getId();
            $query = $this->db->query("SELECT DISTINCT email FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");
            if ($query->row['email'] == $email) {
                $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) . "'");
            } else {
                $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsletter WHERE email = '" . $this->db->escape($email) . "'");
            }
        } else {
            $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsletter WHERE email = '" . $this->db->escape($email) . "'");
        }

        return $query->row['total'];
    }

}

?>