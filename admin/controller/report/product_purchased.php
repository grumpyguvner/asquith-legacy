<?php
class ControllerReportProductPurchased extends Controller { 
	public function index() {   
		$this->load->language('report/product_purchased');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}	
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = 0;
		}	
						
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
								
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
			'href'      => $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->load->model('report/product');
		
		$this->data['products'] = array();
		
		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_order_status_id' => $filter_order_status_id,
			'filter_customer_group_id' => $filter_customer_group_id,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
				
		$product_total = $this->model_report_product->getTotalPurchased($data);

		$results = $this->model_report_product->getPurchased($data);
		
		foreach ($results as $result) {
			$this->data['products'][] = array(
				'name'       => $result['name'],
				'model'      => $result['model'],
				'options'      => $result['options'],
				'quantity'   => $result['quantity'],
				'total'      => $this->currency->format($result['total'], $this->config->get('config_currency'))
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
		$this->data['text_all_group'] = $this->language->get('text_all_group');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_options'] = $allOptions;
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_total'] = $this->language->get('column_total');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_group'] = $this->language->get('entry_group');

		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        
		$this->load->model('sale/customer_group');
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();		
		
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;		
		$this->data['filter_order_status_id'] = $filter_order_status_id;
		$this->data['filter_customer_group_id'] = $filter_customer_group_id;
        
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['export'] = $this->url->link('report/product_purchased/download', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$this->template = 'report/product_purchased.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}	
    
	public function download() {
		$this->load->language('report/product_purchased');
        
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
        $headings[] = $this->language->get('column_quantity');
        $headings[] = $this->language->get('column_total');
        
        // Get Model
		$this->load->model('report/product');
        
        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}	
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = 0;
		}	
        
		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_order_status_id' => $filter_order_status_id, 
			'filter_customer_group_id' => $filter_customer_group_id
		);
        
		
        /// retrieve and build data for excel - taking in account for variable sizes that not all products have
		$results = $this->model_report_product->getPurchased($data);
        $data = array();
        
		foreach ($results as $rownum => $row) {
            
            $data[$rownum][] = $row['model'];
            $data[$rownum][] = $row['name'];
            foreach ($allOptions as $key => $column) {
                if (array_key_exists($column, $row['options']) === true) {
                    $data[$rownum][] = $row['options'][$column];
                } else {
                    $data[$rownum][] = NULL;
                }
            }
			$data[$rownum][] = $row['quantity'];
			$data[$rownum][] = $this->currency->format($row['total'], $this->config->get('config_currency'));
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
        $this->model_report_export->download('report_product_purchased', 'Product Purchased Report', $data, $headings, $settings);
        
		exit;
	}
}
?>