<?php  
class ControllerModuleCategory extends Controller {
	protected function index($setting) {
		$this->language->load('module/category');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}
		
		if (isset($parts[0])) {
			$this->data['category_id'] = $parts[0];
		} else {
			$this->data['category_id'] = 0;
		}
		
		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}
		
		if (isset($parts[2])) {
			$this->data['grandchild_id'] = $parts[2];
		} else {
			$this->data['grandchild_id'] = 0;
		}
							
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		
		$this->data['categories'] = array();
					
		$categories = $this->model_catalog_category->getCategories(0);
		
		foreach ($categories as $category) {
			if ($setting['count']) {
				$data = array(
					'filter_category_id'  => $category['category_id'],
					'filter_sub_category' => true	
				);
				
				$product_total = $this->model_catalog_product->getTotalProducts($data);
			
				$this->data['categories'][] = array(
					'category_id' => $category['category_id'],
					'name'        => $category['name'] . ' (' . $product_total . ')',
					'children'    => $this->_child_category($category, $category['category_id'], 2, 3, $setting),
					'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
				);				
			} else {
				$this->data['categories'][] = array(
					'category_id' => $category['category_id'],
					'name'        => $category['name'],
					'children'    => $this->_child_category($category, $category['category_id'], 2, 3, $setting),
					'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
				);			
			}
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/category.tpl';
		} else {
			$this->template = 'default/template/module/category.tpl';
		}
		
		$this->render();
  	}
  	
  	
  	/**
  	 * _child_category function.
  	 * Function to recusively go through the categories to get children up to a maximum depth.
  	 * @author Tim Faulkner 16/08/2012
  	 *
  	 * @access private
  	 * @param mixed $category
  	 * @param mixed $path
  	 * @param mixed $level
  	 * @param mixed $max
  	 * @return void
  	 */
  	private function _child_category ($category, $path, $level, $max, $setting) {
	  	$children_data = array();
	  		
  		if ($level <= $max)
  		{
		  	$children = $this->model_catalog_category->getCategories($category['category_id']);
				
			foreach ($children as $child) {	
					
				if ($setting['count']) {
				
					$data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);	
					
					$product_total = $this->model_catalog_product->getTotalProducts($data);
					
					$children_data[] = array(
						'category_id' => $child['category_id'],
						'name'        => $child['name'] . ' (' . $product_total . ')',
						'children'    => $this->_child_category($child, $path . '_' . $child['category_id'], $level++, 3, $setting),
						'href'        => $this->url->link('product/category', 'path=' . $path . '_' . $child['category_id'])	
					);						
				} else {
					$children_data[] = array(
						'category_id' => $child['category_id'],
						'name'        => $child['name'],
						'children'    => $this->_child_category($child, $path . '_' . $child['category_id'], $level++, 3, $setting),
						'href'        => $this->url->link('product/category', 'path=' . $path . '_' . $child['category_id'])	
					);						
				}			
			}
		}
		return $children_data;
  	}
}
?>