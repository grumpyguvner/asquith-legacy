<?php
class Extensions {
	
  	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
	}
		
  	public function isInstalled($code, $type = "") {
                $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `code` = '" . $code ."'" . ($type != "" ? " AND `type` = '" . $type ."'" : ""));
                if($result->num_rows)
                    return true;
                return false;
  	}	
}
?>