<?php
//-----------------------------------------
// Author: Qphoria-at-gmail.com
// Web: http://www.OpenCartGuru.com/
//-----------------------------------------
class ModelSaleNewsletter extends Model {
	public function getNewsletterEmails($limit = 50) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter LIMIT " . $limit);

		return $query->rows;
	}

	public function addNewsletterEmail($email, $name = '', $name2 = '') {
		$query = $this->db->query("SELECT email FROM " . DB_PREFIX . "newsletter WHERE `email` = '" . $email . "'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET `email` = '" . $email . "', `name` = '" . $name . "', `name2` = '" . $name2 . "'");
		}
	}

	public function deleteNewsletterEmail($email) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsletter WHERE `email` = '" . $email . "'");
	}

	public function getNewslettersByFilter($data) {
		if (!isset($data['start'])) {
			$data['start'] = 0;
		}

		if (!isset($data['limit'])) {
			$data['limit'] = 50;
		}

		if (isset($data['filter']) && $data['filter']) {
			//if (isset($data['registered']) && $data['registered']) {
			//	$query = $this->db->query("SELECT n.*, c.email FROM " . DB_PREFIX . "newsletter n, " . DB_PREFIX . "customer c WHERE (n.name LIKE '%" . $this->db->escape($data['filter']) . "%' OR c.firstname LIKE '%" . $this->db->escape($data['filter']) . "%' OR n.email LIKE '%" . $this->db->escape($data['filter']) . "%' OR c.email LIKE '%" . $this->db->escape($data['filter']) . "%') ORDER BY n.name, n.email LIMIT " . $data['start'] . "," . $data['limit']);
			//} else{
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter WHERE (name LIKE '%" . $this->db->escape($data['filter']) . "%' OR email LIKE '%" . $this->db->escape($data['filter']) . "%') ORDER BY name, email LIMIT " . $data['start'] . "," . $data['limit']);
			//}
			return $query->rows;
		} else {
			return array();
		}
	}

	public function getSubscribers() {
        $query = $this->db->query("SELECT CONCAT(name2, ' ', name) as name, email FROM " . DB_PREFIX . "newsletter");

        return $query->rows;
    }

    public function getSubscribersFromCustomerTable() {
        $query = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) as name, email FROM " . DB_PREFIX . "customer WHERE newsletter = '1'");

        return $query->rows;
    }


    public function getSubscribersByEmail($email) {
        $query = $this->db->query("SELECT CONCAT(name2, ' ', name) as name, email FROM " . DB_PREFIX . "newsletter WHERE email LIKE '%". $email ."%'");

        return $query->rows;
    }
}
?>