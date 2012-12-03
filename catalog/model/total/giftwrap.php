<?php
class ModelTotalGiftwrap extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {

	 if(!empty($this->session->data['giftwrapping'])){
	 $gift_wrap_total = 0;
	 foreach($this->session->data['giftwrapping'] as $gift){
	 $gift_wrap_total += $gift['price'];
	 }
	 
		$total_data[] = array(
			'code'       => 'gift_wrapping_total',
			'title'      => $this->config->get('giftwrap_title'),
			'text'       => $this->currency->format(max(0, $gift_wrap_total)),
			'value'      => max(0, $gift_wrap_total),
			'sort_order' => $this->config->get('giftwrap_sort_order')
		);
		
		$total += $gift_wrap_total;
		}
	}
}
?>