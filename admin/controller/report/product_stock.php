<?php
class ControllerReportProductStock extends Controller { 
	public function index() {   
		$this->load->language('report/product_stock');

		$this->document->setTitle($this->language->get('heading_title'));
						
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
						
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/product_stock', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->load->model('report/product');
		
		$this->data['products'] = array();
		
		$data = array(
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
				
		$product_total = $this->model_report_product->getTotalProducts($data);

		$results = $this->model_report_product->getStock($data);
        
		foreach ($results as $row) {
            
            $options = explode('%,%', $row['options']);
            $stock = explode('%,%', $row['stock']);
            
            $optionStock = array_combine((array)$options, (array)$stock);
            
			$this->data['products'][] = array(
				'name'       => $row['name'],
				'model'      => $row['model'],
				'options'      => $optionStock,
				'total'      => $row['total_stock'],
				'price'      => $this->currency->format($row['price'], $this->config->get('config_currency')),
				'sale'      => $this->currency->format(0, $this->config->get('config_currency'))
			);
		}
        
        $optionsResults = $this->model_report_product->getStockOptions();
        
        $allOptions = array();
        foreach ($optionsResults as $row) {
            $allOptions[$row['option_value_id']] = $row['name'];
		}
        
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_options'] = $allOptions;
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_sale'] = $this->language->get('column_sale');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_filter'] = $this->language->get('button_filter');
        
        
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['export'] = $this->url->link('report/product_stock/download', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		
		$url = '';
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/product_stock', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();		
		
		$this->template = 'report/product_stock.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}	
    
	public function download() {
		$this->load->language('report/product_stock');
        
        // Get Model
		$this->load->model('report/product');
        
        // Stock has variable columns based on how many size options
        $optionsResults = $this->model_report_product->getStockOptions();
        
        $allOptions = array();
        foreach ($optionsResults as $row) {
            $allOptions[$row['option_value_id']] = $row['name'];
		}
        
        // build column headings
        $headings = array();
        
        $headings[] = $this->language->get('column_model');
        $headings[] = $this->language->get('column_name');
        foreach ($allOptions as $column) {
            $headings[] = $column;
		}
        $headings[] = $this->language->get('column_total');
        $headings[] = $this->language->get('column_price');
        
        
        /// retrieve and build data for excel - taking in account for variable sizes that not all products have
        $results = $this->model_report_product->getStock();
        $data = array();
        
		foreach ($results as $rownum => $row) {
            
            $options = explode('%,%', $row['options']);
            $stock = explode('%,%', $row['stock']);
            
            $optionStock = array_combine((array)$options, (array)$stock);
            
            $data[$rownum][] = $row['model'];
            $data[$rownum][] = $row['name'];
            foreach ($allOptions as $key => $column) {
                if (array_key_exists($column, $optionStock) === true) {
                    $data[$rownum][] = $optionStock[$column];
                } else {
                    $data[$rownum][] = NULL;
                }
            }
            $data[$rownum][] = $row['total_stock'];
            $data[$rownum][] = $this->currency->format($row['price'], $this->config->get('config_currency'));
		}
        
        // create data formats per column
        $settings = array();
        
        $settings[] = 'text';
        $settings[] = 'text';
        foreach ($optionsResults as $row) {
            $settings[] = 'text';
		}
        $settings[] = 'text';
        $settings[] = 'price';
        
        // load excel model
		$this->load->model('report/export');
        // send to excel builder and return spreadsheet
        $this->model_report_export->download('report_product_stock', 'Stock Report', $data, $headings, $settings);
        
		exit;
	}
}
?>