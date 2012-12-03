<?php
class ControllerCatalogGifts extends Controller {
	private $error = array();
	private $_name = 'gifts';
	
	
	public function index() {   
		$this->load->language('catalog/' . $this->_name);

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/gifts');
		$this->load->model('tool/image');
		
		$name = '';
        $image = '';
        $price = '';
		
		$check_gifts_tables = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."gifts'");
			
			if($check_gifts_tables->num_rows == 0){
													
									$this->db->query("CREATE TABLE  `".DB_PREFIX."gifts` (
                                               `id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                               `image` VARCHAR( 255 ) NOT NULL ,
                                               `name` TEXT NOT NULL ,
											   `price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'
                                               ) ENGINE = MYISAM ;");
						  
																			  
												}
												
		$check_gifts_tables = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."gifts_order'");
			
			if($check_gifts_tables->num_rows == 0){
													
									$this->db->query("CREATE TABLE  `".DB_PREFIX."gifts_order` (
                                               `gift_id` INT( 10 ) NOT NULL ,
											   `name` VARCHAR(255) NOT NULL ,
                                               `order_id` VARCHAR( 255 ) NOT NULL ,
                                               `product_id` TEXT NOT NULL,
                                               `price` decimal(15,4) NOT NULL
                                               ) ENGINE = MYISAM ;");
						  
																			  
												}
	
	$found = 0;
												
		$tableFields = mysql_list_fields(DB_DATABASE, DB_PREFIX."product");
		
		for($i=0;$i<mysql_num_fields($tableFields);$i++){
// Run loop through fields.

         if(mysql_field_name($tableFields, $i) == 'giftwrap'){
            $found = 1;
                                                             }

                                                        }
														
			if($found == 0){
			$this->db->query("ALTER TABLE  `".DB_PREFIX."product` ADD  `giftwrap` TINYINT( 1 ) NOT NULL AFTER `price`");
			}
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

				$num = count($this->request->post)/3;	
		
		//First we empty gifts table to prepare adding the new gifts
		$this->model_catalog_gifts->deleteGifts();
		
	for ($i = 0; $i <= $num; $i+= 1) {
	
	if(empty($this->request->post['gift_'.$i.'_name'])){
	$this->request->post['gift_'.$i.'_name'] = '';
	}
	if(empty($this->request->post['gift_'.$i.'_image'])){
	$this->request->post['gift_'.$i.'_image'] = '';
	}
	if(empty($this->request->post['gift_'.$i.'_price'])){
	$this->request->post['gift_'.$i.'_price'] = '';
	}
	
$name = $this->request->post['gift_'.$i.'_name'];
                                                         
$image = $this->request->post['gift_'.$i.'_image'];
$price = $this->request->post['gift_'.$i.'_price'];

if(!empty($name)){
$this->model_catalog_gifts->addGift($name, $image, $price);
}
    }
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/gifts&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_price'] = $this->language->get('entry_price');
		$this->data['entry_action'] = $this->language->get('entry_action');
		
		$this->load->model('localisation/language');
        
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_gift'] = $this->language->get('button_add_gift');
        $this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 40, 40);
		
		$gifts = array();
		
		$this->data['gift'] = array();
		
		$gifts = $this->model_catalog_gifts->getGifts();
		
		foreach ($gifts as $gift) {
		
		if ($gift['image'] && file_exists(DIR_IMAGE . $gift['image'])) {
				$image = $this->model_tool_image->resize($gift['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}
			
		$this->data['gift'][] = array(
				'name'       => $gift['name'],
				'file'      => $gift['image'],
				'image'      => $image,
				'price'   => $gift['price']
			);
			                         }

			
		

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->language->get('text_success');
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->load->model('localisation/language');
		
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=catalog/gifts&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/gifts&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'];
			

		
		$this->template = 'catalog/' . $this->_name . '.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'catalog/' . $this->_name)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>