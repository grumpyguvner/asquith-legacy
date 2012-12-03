<?php 
class ModelPaymentOffline extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('payment/offline');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('offline_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('offline_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('offline_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
                
                //Validate IP Address
                if ($status) {
                    $validips = str_getcsv($this->config->get('offline_ips'),",");
                    $status = in_array($this->request->server['REMOTE_ADDR'],$validips);
                }
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'offline',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('offline_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>