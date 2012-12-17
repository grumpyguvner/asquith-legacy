<?php
class ModelReportAdvancedCoupon extends Model {
	public function getAdvancedCoupons($data = array()) {
		$sql = "SELECT ch.advanced_coupon_id, c.name, c.code, COUNT(DISTINCT ch.order_id) AS `orders`, SUM(o.total) AS `total spend`, SUM(ch.amount) AS `total discount` FROM (`" . DB_PREFIX . "advanced_coupon_history` ch RIGHT JOIN `" . DB_PREFIX . "order` o ON (ch.order_id = o.order_id)) LEFT JOIN `" . DB_PREFIX . "advanced_coupon` c ON (ch.advanced_coupon_id = c.advanced_coupon_id)"; 

		$implode = array();
		
                $implode[] = "ch.advanced_coupon_id IS NOT NULL";
                
		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(ch.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(ch.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$sql .= " GROUP BY ch.advanced_coupon_id ORDER BY total DESC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
                $query = $this->db->query($sql);
		
		return $query->rows;
	}	
	
	public function getTotalAdvancedCoupons($data = array()) {
		$sql = "SELECT COUNT(DISTINCT advanced_coupon_id) AS total FROM `" . DB_PREFIX . "advanced_coupon_history`";
		
		$implode = array();
		
		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];	
	}		
}
?>