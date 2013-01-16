<?php
class ControllerReportSaleAdvancedCoupon extends Controller {
	public function index() {     
		$this->load->language('report/sale_advanced_coupon');

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
			'href'      => $this->url->link('report/sale_advanced_coupon', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->load->model('report/advanced_coupon');
		
		$this->data['coupons'] = array();
		
		$data = array(
			'filter_date_start'	=> $filter_date_start, 
			'filter_date_end'	=> $filter_date_end, 
			'start'             => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'             => $this->config->get('config_admin_limit')
		);
				
		$coupon_total = $this->model_report_advanced_coupon->getTotalAdvancedCoupons($data); 
		
		$results = $this->model_report_advanced_coupon->getAdvancedCoupons($data);
	
		foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/advanced_coupon/update', 'token=' . $this->session->data['token'] . '&advanced_coupon_id=' . $result['advanced_coupon_id'] . $url, 'SSL')
			);
						
			$this->data['coupons'][] = array(
				'name'   => $result['name'],
				'code'   => $result['code'],
				'orders' => $result['orders'],
				'total_spend'  => $this->currency->format($result['total spend'], $this->config->get('config_currency')),
				'total_discount'  => $this->currency->format($result['total discount'], $this->config->get('config_currency')),
				'action' => $action
			);
		}
				 
 		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_orders'] = $this->language->get('column_orders');
		$this->data['column_total_spend'] = $this->language->get('column_total_spend');
		$this->data['column_total_discount'] = $this->language->get('column_total_discount');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		
		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->data['token'] = $this->session->data['token'];
		
		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/sale_advanced_coupon', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
        
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['export'] = $this->url->link('report/sale_advanced_coupon/download', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;	
				
		$this->template = 'report/sale_advanced_coupon.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
    
    public function download() {
	$this->load->language('report/sale_advanced_coupon');
        
        // build column headings
        $headings = array();
        
        $headings[] = $this->language->get('column_name');
        $headings[] = $this->language->get('column_code');
        $headings[] = $this->language->get('column_orders');
        $headings[] = $this->language->get('column_total_spend');
        $headings[] = $this->language->get('column_total_discount');
        
        // Get Model
        $this->load->model('report/advanced_coupon');

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
        
		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end
		);
        
		$results = $this->model_report_advanced_coupon->getAdvancedCoupons($data);
		
        $data = array();
		foreach ($results as $rownum => $result) {;		
			$data[$rownum][] = $result['name'];
			$data[$rownum][] = $result['code'];
			$data[$rownum][] = $result['orders'];
			$data[$rownum][] = $this->currency->format($result['total spend'], $this->config->get('config_currency'));
			$data[$rownum][] = $this->currency->format($result['total discount'], $this->config->get('config_currency'));
		}
        
        // create data formats per column
        $settings = array();
        
        $settings[] = 'text';
        $settings[] = 'text';
        $settings[] = 'text';
        $settings[] = 'price';
        $settings[] = 'price';
        
        // load excel model
		$this->load->model('report/export');
        // send to excel builder and return spreadsheet
        $this->model_report_export->download('report_sale_sale_advanced_coupon', 'Sale Advanced Coupon Report', $data, $headings, $settings);
        
		exit;
	}
}
?>