<?php
class ModelCatalogGifts extends Model {
	public function getGifts() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gifts");		
		
		return $query->rows;
	}
	public function checkGift($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '".$this->db->escape($product_id)."' ");		
		
		if($query->row['giftwrap']){
		return 1;
		} else {
		return 0;
		}
		
	}
}
?>