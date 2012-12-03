<?php
require_once(DIR_SYSTEM . 'library/mailchimp.php');

//-----------------------------------------
// Author: Qphoria@gmail.com
// Web: http://www.OpenCartGuru.com/
//-----------------------------------------
class ModelAccountNewsletter extends Model {
	public function subscribe($email = '', $name = '', $name2 = '') {
		
		if (!$email) return;
        
        if ($this->config->get('newsletter_mailchimp_enabled'))
        {
            $mailchimp = new mailchimp($this->config->get('newsletter_mailchimp_apikey'));
            
            $retval = $mailchimp->listSubscribe($this->config->get('newsletter_mailchimp_listid'), $email, array(), 'html', $this->config->get('newsletter_mailchimp_double_optin'), $this->config->get('newsletter_mailchimp_update_existing'), true, $this->config->get('newsletter_mailchimp_send_welcome'));
 
            if ($mailchimp->errorCode){
                //echo "Unable to load listSubscribe()!\n";
                //echo "\tCode=".$api->errorCode."\n";
                //echo "\tMsg=".$api->errorMessage."\n";
            } else {
                //echo "Subscribed - look for the confirmation email!\n";
            }
            return;
        } 
            
		//If logged in and using own email, then update the customer table newsletter setting to avoid duplicates
		if ($this->customer->IsLogged()) {
			$customer_id = $this->customer->getId();
			$query = $this->db->query("SELECT DISTINCT email FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
			if ($query->row['email'] == $email) {
				$query = $this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 1 WHERE email = '" . $this->db->escape($email) . "'");
			} else {
				$query = $this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET email = '" . $this->db->escape($email) . "', name = '" . $this->db->escape($name) . "', name2 = '" . $this->db->escape($name2) . "'");
			}
		} else {
			$query = $this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET email = '" . $this->db->escape($email) . "', name = '" . $this->db->escape($name) . "', name2 = '" . $this->db->escape($name2) . "'");
		}
		
		$this->language->load('mail/newsletter');
        
        $subject = $this->config->get('config_email_newsletter_subject');
        $message = $this->config->get('config_email_newsletter_body');
        
		if (empty($subject)) 
        {
            $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
        }

        if (empty($message))
        {
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
		
		if (!$email) return;
        
        if ($this->config->get('newsletter_mailchimp_enabled'))
        {
            $mailchimp = new mailchimp($this->config->get('newsletter_mailchimp_apikey'));
            
            $retval = $mailchimp->listUnsubscribe($this->config->get('newsletter_mailchimp_listid'), $email);
 
            if ($mailchimp->errorCode){
                //echo "Unable to load listUnsubscribe()!\n";
                //echo "\tCode=".$mailchimp->errorCode."\n";
                //echo "\tMsg=".$mailchimp->errorMessage."\n";
            } else {
                //echo "Returned: ".$retval."\n";
            }
            return;
        } 
        
		//If logged in and using own email, then update the customer table newsletter setting to avoid duplicates
		if ($this->customer->IsLogged()) {
			$customer_id = $this->customer->getId();
			$query = $this->db->query("SELECT DISTINCT email FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
			if ($query->row['email'] == $email) {
				$query = $this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 0 WHERE email = '" . $this->db->escape($email) . "'");
			} else {
				$query = $this->db->query("DELETE FROM " . DB_PREFIX . "newsletter WHERE email = '" . $this->db->escape($email) . "'");
			}
		} else {
			$query = $this->db->query("DELETE FROM " . DB_PREFIX . "newsletter WHERE email = '" . $this->db->escape($email) . "'");
		}
		
	}
	
	public function getTotalNewsletterByEmail($email) {
        
        if ($this->config->get('newsletter_mailchimp_enabled'))
        {
            $mailchimp = new mailchimp($this->config->get('newsletter_mailchimp_apikey'));
            
            $retval = $mailchimp->listMemberInfo($this->config->get('newsletter_mailchimp_listid'), $email);
 
            if ($mailchimp->errorCode){
                return 0;
            } else {
                return ($retval['success'] && $retval['data'][0]['status'] != 'unsubscribed') ? 1 : 0;
            }
        } 
		
		if ($this->customer->IsLogged()) {
			$customer_id = $this->customer->getId();
			$query = $this->db->query("SELECT DISTINCT email FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
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