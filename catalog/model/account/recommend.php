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
    }        else {
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($this->config->get('config_name'));
    }
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
        $mail->send();
        
        $query = $this->db->query("INSERT INTO " . DB_PREFIX . "recommend SET customer_id = '" . $customer_id . "', code = '" . $tracker . "', email = '" . $this->db->escape($data['email']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', date_added = NOW()");

    }

}

?>