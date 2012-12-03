<?php  

class ControllerModuleManufacturerFilter extends Controller {
	protected function index() {
		$status = true;
		
		if ($this->config->get('store_admin')) {
			$this->load->library('user');
		
			$this->user = new User($this->registry);
			
			$status = $this->user->isLogged();
		}
		
	//	if ($status) {
			$this->language->load('module/manufacturer_filter');
			
			$this->data['heading_title'] = $this->language->get('heading_title');			
			
			$this->load->model('catalog/manufacturer');
			
			$this->load->model('catalog/category');

		// Get current category
		
		if (isset($this->request->get['path'])) {

			$path = '';

		

			$parts = explode('_', (string)$this->request->get['path']);

		

			foreach ($parts as $path_id) {

				if (!$path) {

					$path = $path_id;

				} else {

					$path .= '_' . $path_id;

				}

									

				$category_info = $this->model_catalog_category->getCategory($path_id);

				

				if ($category_info) {

	       			$this->data['breadcrumbs'][] = array(

   	    				'text'      => $category_info['name'],

						'href'      => $this->url->link('product/category', 'path=' . $path),

        				'separator' => $this->language->get('text_separator')

        			);

				}

			}		

		

			$category_id = array_pop($parts);

		} else {

			$category_id = 0;

		}

		// END get current category


/* modification ---------------------------------------------------------------------------- */
           $this->load->model('catalog/manufacturer');
          
          $this->data['manufacturers'] = array();
          
          $this->data['manufacturers'][] = array(
               'href'            => $this->url->link('product/category&path=' . $this->request->get['path']),
                'value'           => 0,
                'name'            => $this->language->get('text_all'),
            );
                       
          $results = $this->model_catalog_product->getManufacturersForFiltering($category_id);
          
          foreach ($results as $result) {

             $this->data['manufacturers'][] = array(
               'href'            => $this->url->link('product/category&path=' . $this->request->get['path'] . '&manufacturer='.$result['manufacturer_id']),
                'value'           => $result['manufacturer_id'],
                'name'            => $result['manufacturer_name'],  
             );
 
          }
      /* modification ---------------------------------------------------------------------------- */
			
/*			$results = $this->model_catalog_manufacturer->getManufacturers();
			
			foreach ($results as $result) {
				$this->data['manufacturers'][] = array(
					'id' => $result['manufacturer_id'],
					'name'     => $result['name']					
				);
			}*/
			
			/* modification ---------------------------------------------------------------------------- */				
				if (isset($this->request->get['manufacturer'])) {
					//$url .= '&manufacturer=' . $this->request->get['manufacturer'];
					$manuf = $this->request->get['manufacturer'];
				}
      /* modification ---------------------------------------------------------------------------- */
	 
			$this->data['href'] = HTTP_SERVER . 'index.php?route=product/category&path='. $category_id .'&manufacturer_id=';//.$manuf;
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/manufacturer_filter.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/manufacturer_filter.tpl';
			} else {
				$this->template = 'default/template/module/manufacturer_filter.tpl';
			}
			
			$this->render();
	//	}
	}

}

?>