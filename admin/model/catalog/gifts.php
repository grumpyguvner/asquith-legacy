<?php
class ModelCatalogGifts extends Model {
	public function addGift($name, $image, $price) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "gifts SET name = '" . $this->db->escape($name) . "', image = '" . $this->db->escape($image) . "', price = '" . $this->db->escape(strip_tags($price)) . "'");
	}
	
	public function deleteGifts() {
		$this->db->query("TRUNCATE TABLE  `" . DB_PREFIX . "gifts`");
	}
	
	public function getGifts() {
		$product_gifts_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gifts");		
		
		return $query->rows;
	}
	
	public function getGiftsById($gift_id) {
		$product_gifts_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gifts WHERE id = '".$gift_id."' ");		
		
		return $query->row;
	}
	
	public function giftOrder($product_id, $order_id) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gifts_order WHERE product_id = '".$this->db->escape($product_id)."' and order_id = '".$order_id."' ");
	
	if($query->num_rows == 0){
	return FALSE;
	} else {
	return $query->row;
	       }
	
	}
	
}
?>