<?php

class ModelAccountRecommend extends Model {

    public function send($data) {

        if (!$data['email'] || !$data['firstname'] || !$data['lastname']) {
            return;
        }

        $customer_id = $this->customer->getId();
        $query = $this->db->query("SELECT DISTINCT email FROM " . DB_PREFIX . "recommend WHERE email = '" . $this->db->escape($data['email']) . "' AND customer_id = '" . (int) $customer_id . "'");

        if ($query->num_rows > 0)
            if (!$this->config->get('recommend_email_allow_resend'))
                return true;

        $tracker = uniqid();

        $subject = $this->config->get('recommend_email_subject');
        if (empty($subject))
            $subject = $this->language->get('email_subject');
        $message = $this->config->get('recommend_email_body');
        if (empty($message))
            $message = $this->language->get('email_body');

        $search = array();
        $replace = array();

        $search[] = '[sitename]';
        $replace[] = $this->config->get('config_name');
        $search[] = '[siteurl]';
        $replace[] = $this->config->get('config_url');
        $search[] = '[tracker]';
        $replace[] = "recommend=" . $tracker;
        $search[] = '[firstname]';
        $replace[] = $data['firstname'];
        $search[] = '[lastname]';
        $replace[] = $data['lastname'];
        $search[] = '[customer_name]';
        $replace[] = $this->customer->getFirstName() . " " . $this->customer->getLastName();

        $subject = str_replace($search, $replace, $subject);
        $message = str_replace($search, $replace, $message);

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->hostname = $this->config->get('config_smtp_host');
        $mail->username = $this->config->get('config_smtp_username');
        $mail->password = $this->config->get('config_smtp_password');
        $mail->port = $this->config->get('config_smtp_port');
        $mail->timeout = $this->config->get('config_smtp_timeout');
        $mail->setTo($data['email']);
        if ($this->config->get('recommend_email_send_from_customer')) {
            $mail->setFrom($this->customer->getEmail());
            $mail->setSender($this->customer->getFirstName() . " " . $this->customer->getLastName());
        } else {
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($this->config->get('config_name'));
        }
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
        $mail->send();

        $query = $this->db->query("INSERT INTO " . DB_PREFIX . "recommend SET customer_id = '" . $customer_id . "', code = '" . $tracker . "', email = '" . $this->db->escape($data['email']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', date_added = NOW()");
    }

    public function getRecommendByCode($code) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recommend WHERE code = '" . $this->db->escape($code) . "'");

        return $query->row;
    }

    public function sendNewCustomerVoucher($recommend_id) {
        //Reserved for future use, not currently used
    }

    public function sendNewOrderVoucher($recommend_id) {

        $this->language->load('account/recommend');

        //Does order qualify for a voucher?
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recommend WHERE recommend_id = '" . $this->db->escape($recommend_id) . "'");
        if (!$query->num_rows)
            // Invalid id
            return false;
        
        if ((int)$query->row['voucher_id'])
            // We have already sent a voucher
            return true;

        $customer_id = (int)$query->row['customer_id'];
        $customer_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");
        if (!$query->num_rows)
            // Invalid customer_id
            return false;

        $voucher_code = uniqid();

        $subject = $this->config->get('recommend_voucher_subject');
        if (empty($subject))
            $subject = $this->language->get('voucher_subject');
        $message = $this->config->get('recommend_voucher_body');
        if (empty($message))
            $message = $this->language->get('voucher_body');

        $search = array();
        $replace = array();

        $search[] = '[sitename]';
        $replace[] = $this->config->get('config_name');
        $search[] = '[siteurl]';
        $replace[] = $this->config->get('config_url');
        $search[] = '[code]';
        $replace[] = $voucher_code;
//        $search[] = '[amount]';
//        $replace[] = $this->config->get('recommend_voucher_amount');
        $search[] = '[firstname]';
        $replace[] = $customer_info->row['firstname'];
        $search[] = '[lastname]';
        $replace[] = $customer_info->row['lastname'];
        $search[] = '[friend_name]';
        $replace[] = $this->customer->getFirstName() . " " . $this->customer->getLastName();

        $subject = str_replace($search, $replace, $subject);
        $message = str_replace($search, $replace, $message);

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->hostname = $this->config->get('config_smtp_host');
        $mail->username = $this->config->get('config_smtp_username');
        $mail->password = $this->config->get('config_smtp_password');
        $mail->port = $this->config->get('config_smtp_port');
        $mail->timeout = $this->config->get('config_smtp_timeout');
        $mail->setTo($customer_info->row['email']);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
        $mail->send();

        // Create voucher
      	$this->db->query("INSERT INTO " . DB_PREFIX . "voucher SET order_id = '0', code = '" . $this->db->escape($voucher_code) . "', from_name = '" . $this->db->escape($this->config->get('config_name')) . "', from_email = '" . $this->db->escape($this->config->get('config_email')) . "', to_name = '" . $this->db->escape($customer_info->row['firstname'] . " " . $customer_info->row['lastname']) . "', to_email = '" . $this->db->escape($customer_info->row['email']) . "', voucher_theme_id = '0', message = '" . $this->db->escape($message) . "', amount = '" . (float)$this->config->get('recommend_voucher_amount') . "', status = '1', date_added = NOW()");
        $voucher_id = $this->db->getLastId();

        $this->db->query("UPDATE " . DB_PREFIX . "recommend SET voucher_id = '" . $this->db->escape($voucher_id) . "' WHERE recommend_id = '" . $this->db->escape($recommend_id) . "'");
    }

}

?>