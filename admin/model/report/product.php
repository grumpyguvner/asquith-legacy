<?php
class ModelReportProduct extends Model {
	public function getProductsViewed($data = array()) {
		$sql = "SELECT pd.name, p.model, p.viewed FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.viewed > 0 ORDER BY p.viewed DESC";
					
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
    
	public function getStockOptions() {
		$sql = "SELECT ov.option_value_id, ovd.name "
             . "FROM " . DB_PREFIX . "option_value ov "
             . "JOIN " . DB_PREFIX . "product_option_value pov ON (ov.option_value_id = pov.option_value_id) "
             . "LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id and ovd.language_id = '" . (int)$this->config->get('config_language_id') . "') "
             . "GROUP BY ovd.name "
             . "ORDER BY ov.sort_order ASC";
        
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
    
    
	public function getStock($data = array()) {
		$sql = "SELECT pd.name, p.model, p.price, SUM(pov.quantity) AS total_stock, GROUP_CONCAT(ovd.name ORDER BY ov.sort_order SEPARATOR '%,%') as options, GROUP_CONCAT(pov.quantity ORDER BY ov.sort_order SEPARATOR '%,%') as stock "
             . "FROM " . DB_PREFIX . "product p "
             . "LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id and pd.language_id = '" . (int)$this->config->get('config_language_id') . "') "
             . "LEFT JOIN " . DB_PREFIX . "product_option po ON (p.product_id = po.product_id) "
             . "LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (po.product_option_id = pov.product_option_id) "
             . "LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) "
             . "LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id and ovd.language_id = '" . (int)$this->config->get('config_language_id') . "') "
             . "GROUP BY p.product_id, po.option_id "
             . "ORDER BY p.model ASC";
					
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
	
	public function getTotalProducts() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product");
		
		return $query->row['total'];
	}
	
	public function getTotalProductsViewed() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE viewed > 0");
		
		return $query->row['total'];
	}
	
	public function getTotalProductViews() {
      	$query = $this->db->query("SELECT SUM(viewed) AS total FROM " . DB_PREFIX . "product");
		
		return $query->row['total'];
	}
			
	public function reset() {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = '0'");
	}
	
	public function getPurchased($data = array()) {
		$sql = "SELECT op.name, op.model, oo.value, SUM(op.quantity) AS quantity, SUM(op.total + op.total * op.tax / 100) AS total "
             . "FROM " . DB_PREFIX . "order_product op "
             . "LEFT JOIN `" . DB_PREFIX . "order_option` oo ON (oo.order_product_id = op.order_product_id) "
             . "LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
        
		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND o.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		} else {
			$sql .= " AND o.customer_group_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$sql .= " GROUP BY op.model, oo.value ORDER BY total DESC";
        
		$query = $this->db->query($sql);
        
        $productsK = array();
        foreach ($query->rows as $row)
        {
            if (!isset($productsK[$row['model']])) {
                $productsK[$row['model']] = array();
                $productsK[$row['model']]['name'] = $row['name'];
                $productsK[$row['model']]['model'] = $row['model'];
                $productsK[$row['model']]['quantity'] = 0;
                $productsK[$row['model']]['total'] = 0;
                $productsK[$row['model']]['options'] = array();
            }
            $productsK[$row['model']]['quantity'] += $row['quantity'];
            $productsK[$row['model']]['total'] += $row['total'];
            $productsK[$row['model']]['options'][$row['value']] = $row['quantity'];
        }
        
        $products = array();
        foreach($productsK as $product)
        {
            $products[] = $product;
        }
        
        usort($products, function ( $a, $b ) { 
          if(  $a['total'] ==  $b['total'] ){ return 0 ; } 
          return ($a['total'] > $b['total']) ? -1 : 1;
        });
					
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$products = array_slice($products, $data['start'], $data['limit']);
		}
		
	
		return $products;
	}
	
	public function getTotalPurchased($data) {
      	$sql = "SELECT COUNT(DISTINCT op.model) AS total FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
        
		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND o.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		} else {
			$sql .= " AND o.customer_group_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
    
	public function getPurchasedByCustomerGroup($data = array()) {
		$sql = "SELECT cg.name as `group`, op.name, op.model, oo.value, SUM(op.quantity) AS quantity, SUM(op.total + op.total * op.tax / 100) AS total "
             . "FROM " . DB_PREFIX . "order_product op "
             . "LEFT JOIN `" . DB_PREFIX . "order_option` oo ON (oo.order_product_id = op.order_product_id) "
             . "LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)"
             . "LEFT JOIN `" . DB_PREFIX . "customer_group` cg ON (cg.customer_group_id = o.customer_group_id)";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
        
		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND o.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		} else {
			$sql .= " AND o.customer_group_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$sql .= " GROUP BY o.customer_group_id, op.model, oo.value ORDER BY cg.sort_order desc, cg.name";
        
		$query = $this->db->query($sql);
        
        $productsK = array();
        foreach ($query->rows as $row)
        {
            if (!isset($productsK[$row['group'].$row['model']])) {
                $productsK[$row['group'].$row['model']] = array();
                $productsK[$row['group'].$row['model']]['group'] = $row['group'];
                $productsK[$row['group'].$row['model']]['name'] = $row['name'];
                $productsK[$row['group'].$row['model']]['model'] = $row['model'];
                $productsK[$row['group'].$row['model']]['quantity'] = 0;
                $productsK[$row['group'].$row['model']]['total'] = 0;
                $productsK[$row['group'].$row['model']]['options'] = array();
            }
            $productsK[$row['group'].$row['model']]['quantity'] += $row['quantity'];
            $productsK[$row['group'].$row['model']]['total'] += $row['total'];
            $productsK[$row['group'].$row['model']]['options'][$row['value']] = $row['quantity'];
        }
        
        $products = array();
        foreach($productsK as $product)
        {
            $products[] = $product;
        }
					
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$products = array_slice($products, $data['start'], $data['limit']);
		}
		
	
		return $products;
	}
	
	public function getTotalPurchasedByCustomerGroup($data) {
      	$sql = "SELECT COUNT(DISTINCT cg.customer_group_id, op.model) AS total FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "customer_group` cg ON (cg.customer_group_id = o.customer_group_id)";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
        
		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND o.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		} else {
			$sql .= " AND o.customer_group_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
}
?>