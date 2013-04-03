<?php
class ControllerCommonSeoUrl extends Controller {
	public function index() {
            // Add rewrite to url class
            if ($this->config->get('config_seo_url')) {
                    $this->url->addRewrite($this);
            }

            // Decode URL
            if (isset($this->request->get['_route_'])) {
                    $parts = explode('/', $this->request->get['_route_']);

                    switch ($parts[0]) {
                        case 'index':
                            array_shift($parts);
                        case 'account':
                            $route = implode('/', $parts);
                            $this->request->get['route'] = $route;
                            break;
                        default:
                            $route = "";
                            foreach ($parts as $part) {
                                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");

                                if ($query->num_rows) {
                                    $url = explode('=', $query->row['query']);

                                    if ($url[0] == 'product_id') {
                                        $this->request->get['product_id'] = $url[1];
                                    }
                                    //articles url
                                    if ($url[0] == 'news_id') {
                                        $this->request->get['news_id'] = $url[1];
                                    }
                                    if ($url[0] == 'ncategory_id' || $url[0] == 'ncat') {
                                        if (!isset($this->request->get['ncat'])) {
                                            $this->request->get['ncat'] = $url[1];
                                        } else {
                                            $this->request->get['ncat'] .= '_' . $url[1];
                                        }
                                    }
                                    //articles url

                                    if ($url[0] == 'category_id') {
                                        if (!isset($this->request->get['path'])) {
                                            $this->request->get['path'] = $url[1];

                                        } else {
                                            $this->request->get['path'] .= '_' . $url[1];
                                        }
                                    }	

                                    if ($url[0] == 'manufacturer_id') {
                                        $this->request->get['manufacturer_id'] = $url[1];
                                    }


                                    if ($url[0] == 'information_id') {
                                        $this->request->get['information_id'] = $url[1];
                                    }	else{
                                        $route = $url[0];
                                    }	
                                } else {
                                    $this->request->get['route'] = 'error/not_found';	
                                }
                            }

                                if (isset($this->request->get['product_id'])) {
                                        $this->request->get['route'] = 'product/product';
                                } elseif (isset($this->request->get['path'])) {
                                        $this->request->get['route'] = 'product/category';
                                } elseif (isset($this->request->get['manufacturer_id'])) {
                                        $this->request->get['route'] = 'product/manufacturer/product';
                                } elseif (isset($this->request->get['information_id'])) {
                                        $this->request->get['route'] = 'information/information';
                                } elseif (isset($this->request->get['news_id'])) {
                                        $this->request->get['route'] = 'news/article';
                                } elseif (isset($this->request->get['ncat'])) {
                                        $this->request->get['route'] = 'news/ncategory';
                                } else {
                                    $this->request->get['route'] = $route;
                                }
                    }
            }

            if (isset($this->request->get['route'])) {
                    return $this->forward($this->request->get['route']);
            }
		
        }
	
	public function rewrite($link) {
		if ($this->config->get('config_seo_url')) {
            
            $newlink = $link;
            if (strpos($link, 'index') !== false)
            {
                $newlink = preg_replace('%\?%', '&amp;', $newlink);
                $newlink = preg_replace('%/index/%', '/index.php?route=', $link);
            }
            
            
			$url_data = parse_url(str_replace('&amp;', '&', $newlink));
		
			$url = ''; 
			
			$data = array();
			
			parse_str($url_data['query'], $data);
			
			foreach ($data as $key => $value) {
				if (isset($data['route'])) {
                                        switch ($data['route']) {
                                            case "common/home":
                                                $url .= '/';
                                                unset($data[$key]);
                                                break;
                                            case "account/account":
                                            case "account/address":
                                            case "account/address_list":
                                            case "account/download":
                                            case "account/edit":
                                            case "account/forgotten":
                                            case "account/login":
                                            case "account/logout":
                                            case "account/newsletter":
                                            case "account/order":
                                            case "account/password":
                                            case "account/register":
                                            case "account/return":
                                            case "account/transaction":
                                            case "account/wishlist":
                                            case "account/voucher":
                                                $url .= '/' . $data['route'];
                                                unset($data[$key]);
                                                break;
                                            default:
                                                if (($data['route'] == 'product/product'  && $key == 'product_id') || (($data['route'] == 'product/manufacturer/product' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id') || ($data['route'] == 'news/ncategory' && $key == 'ncat') || ($data['route'] == 'news/article' && $key == 'news_id')) {
                                                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");

                                                        if ($query->num_rows) {
                                                                $url .= '/' . $query->row['keyword'];

                                                                unset($data[$key]);
                                                        }					
                                                } elseif ($key == 'path') {
                                                        $categories = explode('_', $value);

                                                        foreach ($categories as $category) {
                                                                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");

                                                                if ($query->num_rows) {
                                                                        $url .= '/' . $query->row['keyword'];
                                                                }							
                                                        }

                                                        unset($data[$key]);
                                                }
                                        }
				}
			}
		
			if ($url) {
				unset($data['route']);
			
				$query = '';
			
				if ($data) {
					foreach ($data as $key => $value) {
						$query .= '&' . $key . '=' . $value;
// -----------------------------start add attributes filters module --------------------------------------------
						if ($key == 'att_filters') {
							foreach ($value as $kez => $valz) {
								$query .= '&' . $key . '[' . $kez . ']=' . $valz;
								}
						}
// -----------------------------end add attributes filters module --------------------------------------------
					}
					
					if ($query) {
						$query = '?' . trim($query, '&');
					}
				}


				return $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url . $query;
			} else {
				return $link;
			}
		} else {
			return $link;
		}		
	}	
}
?>