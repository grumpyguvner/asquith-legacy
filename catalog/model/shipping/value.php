<?php 
class ModelShippingValue extends Model {    
  	public function getQuote($address) {
		$this->load->language('shipping/value');
		
		$quote_data = array();

                //2012-12-13 TEMPORARY MODIFICATION TO AMEND SORT ORDER FOR ASQUITH (ADDED DESC)
                //TODO: Add sort order to GEO Zones and use that for sorting
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name DESC");
	
		foreach ($query->rows as $result) {
			if ($this->config->get('value_' . $result['geo_zone_id'] . '_status')) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$result['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
				if ($query->num_rows) {
					$status = true;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}
		
			if ($status) {
				$cost = '';
				$value = $this->cart->getTotal();
				
				$rates = explode(',', $this->config->get('value_' . $result['geo_zone_id'] . '_rate'));
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $value) {
						if (isset($data[1])) {
							$cost = $data[1];
						}
				
						break;
					}
				}
				
				if ((string)$cost != '') { 
					$quote_data['value_' . $result['geo_zone_id']] = array(
						'code'         => 'value.value_' . $result['geo_zone_id'],
						'title'        => $result['name'] . ($this->config->get('config_cart_value') ? '  (' . $this->language->get('text_value') . ' ' . $this->value->format($value, $this->config->get('config_value_class_id')) . ')' : ''),
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('value_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('value_tax_class_id'), $this->config->get('config_tax')))
					);	
				}
			}
		}
		
		$method_data = array();
	
		if ($quote_data) {
      		$method_data = array(
        		'code'       => 'value',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('value_sort_order'),
        		'error'      => false
      		);
		}
	
		return $method_data;
  	}
}
?>