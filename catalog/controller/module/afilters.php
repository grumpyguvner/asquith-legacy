<?php

class ControllerModuleAFilters extends Controller {

    protected function index() {

        $this->language->load('module/afilters');

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

        // Get current category
        if (isset($this->request->get['path'])) {

            $path = '';
            $parts = explode('_', (string) $this->request->get['path']);

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = $path_id;
                } else {
                    $path .= '_' . $path_id;
                }
                $category_info = $this->model_catalog_category->getCategory($path_id);
                if ($category_info) {
                    $this->data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('product/category', 'path=' . $path),
                        'separator' => $this->language->get('text_separator')
                    );
                }
            }
            $category_id = array_pop($parts);
        } else {
            $category_id = 0;
        }
        // END get current category
        //--------------------------------------------------------------------------------------------------
        // Get total products for current category
        $product_total = $this->model_catalog_product->getTotalProducts(array('filter_category_id' => $category_id));

        // END get total products for current category
        //--------------------------------------------------------------------------------------------------
        // Get all attributes for current category
        if (isset($this->request->get['att_filters']) && (is_array($this->request->get['att_filters']))) {
            foreach (array_keys($this->request->get['att_filters']) as $filter_att) {
                $data['afilters'][$filter_att] = $this->request->get['att_filters'][$filter_att];
            }
        }

        if (isset($data['afilters'])) {
            $myafilters = $data['afilters'];
        } else {
            $myafilters = array();
        }

        $data['filter_category_id'] = $category_id;

        $results = $this->model_catalog_product->getProductsAFiltered($data);

        foreach ($results as $product) {

            $attributes = $this->model_catalog_product->getProductAttributes($product['product_id']);

            $category_attributes[] = $attributes; // mutat din forul de mai jos
            foreach ($attributes as $attribute) {
                if (is_array($attribute['attribute'])) {
                    foreach ($attribute['attribute'] as $attribute_value) {

                        $category_attributes_values[$attribute_value['attribute_id']][] = array(
                            'attribute_text' => $attribute_value['text'],
                            'attribute_name' => $attribute_value['name'], // name added

                            'attribute_id' => $attribute_value['attribute_id']
                        );
                    };
                } else {
                    $category_attributes_values[$attribute['attribute_id']][] = array(
                        'attribute_text' => '', //$option_value['name'],
                        'attribute_name' => '', //added
                        'attribute_id' => 0//$option_value['option_value_id']
                    );
                }
            }
        }

        unset($attributes);

        $big_array = array();

        if (isset($category_attributes)) {

            $i = 0;

            foreach ($category_attributes as $pattribute) { // parcurg produsele
                foreach ($pattribute as $gattribute) { // pentru toate grupele de atribute - fiecare grup al fiecarui produs
                    $attribute_array = $gattribute['attribute'];

                    // start get big attribute
                    $j = $gattribute['attribute_group_id'];
                    $mybool = false;
                    $myattr = false;
                    foreach ($big_array as $barray) {
                        if (in_array($j, $barray)) {
                            $mybool = true;
                        }
                        if (in_array($gattribute['attribute'], $barray)) {
                            $myattr = true;
                        }
                    }
                    if (!($mybool)) {
                        $big_array[$i]['attribute_group_id'] = $j;
                        $big_array[$i]['name'] = $gattribute['name'];
                        $big_array[$i]['attribute_types'] = array(); //$gattribute['attribute'][0]['attribute_id'];
                        //$big_array[$i]['attribute'][] = $gattribute['attribute'];
                        $i++;
                    }
                    // end get bit attribute

                    foreach ($attribute_array as $att_array) { // pentru fiecare attribut - al fiecarui grup
                        $attribute_ids[] = $att_array['attribute_id'];
                        // start experiment
                        //	$big_array[$i]['attribute'][]
                        $all_attribs[$att_array['attribute_id']][] = array(
                            'attribute_text' => $attribute_value['text'],
                            'attribute_name' => $attribute_value['name']
                        );
                        // end experiment
                    }
                }
            }

            $i = 0;
            foreach ($big_array as $barray) {
                foreach ($category_attributes as $pattribute) {
                    foreach ($pattribute as $gattribute) {
                        $attribute_array = $gattribute['attribute'];
                        if ($big_array[$i]['attribute_group_id'] == $gattribute['attribute_group_id']) {
                            foreach ($attribute_array as $att_array) {
                                if (!in_array($att_array['attribute_id'], $big_array[$i]['attribute_types'])) {
                                    $big_array[$i]['attribute_types'][] = $att_array['attribute_id'];
                                }
                            }
                        };
                    };
                };
                $i++;
            };

            if (isset($attribute_ids)) {
                $attributes_ids = array_unique($attribute_ids); // or should it by unique by name?/ text
            } else {
                $attributes_ids = array();
            }

            $attributes = array();
            foreach ($attributes_ids as $attribute_id) { // pentru fiecare Attribute ID unic
                $i = $attribute_id;
                $attributes[$i] = array();

                foreach ($category_attributes_values[$i] as $pgroup) { // foreach of all products attributes groups (deja unice) - arrayuri de valori non-unice
                    if (!in_array($pgroup, $attributes[$i])) {
                        $attributes[$i][] = $pgroup;
                        //$found = true;	
                    }
                }
            }

            $big_array2 = array();
            $i = 0;

            foreach ($big_array as $barray) {

                $big_array2[$i]['attribute_group_id'] = $barray['attribute_group_id'];
                $big_array2[$i]['name'] = $barray['name'];
                $big_array2[$i]['attribute_types'] = array();

                $types = $barray['attribute_types'];

                $j = 0;

                foreach ($types as $type) {

                    $big_array2[$i]['attribute_types'][$j]['type_id'] = $type;
                    $big_array2[$i]['attribute_types'][$j]['type_name'] = $attributes[$type][0]['attribute_name'];
                    $big_array2[$i]['attribute_types'][$j]['types'] = array();

                    foreach ($attributes[$type] as $atts) {

                        $big_array2[$i]['attribute_types'][$j]['types'][] = $atts['attribute_text'];
                    }
                    $j++;
                }

                $i++;
            }
        }
        // END get all attributes for current category
        // get URL

        $this->load->model('catalog/category');

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_catalog_limit');
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['manufacturer_id'])) {
            $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
            $filter = $this->request->get['manufacturer_id'];
        } else {
            $filter = "";
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }


        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }
        $this->data['href_no_attributes'] = $this->url->link('product/category', 'path=' . $path . $url);

        // start URL
        if (isset($data['afilters'])) {

            $afilterURL = $data['afilters'];
        }

        if (isset($afilterURL)) {
            foreach ($afilterURL as $key => $val) {
                $url .= '&att_filters[' . $key . ']=' . $val;
            }
        }


        // END GET URL
        //--------------------------------------------------------------------------------------------------


        $this->data['href'] = $this->url->link('product/category', 'path=' . $path . $url);
        
//        if (isset($big_array2)) {
//            $this->data['attributes'] = $big_array2;
        $filters = $this->model_catalog_category->getCategoryAttributes($category_id);
        if (isset($filters)) {
            $this->data['attributes'] = $filters;
        } else {
            $this->data['attributes'] = "";
        }

        $this->data['afilters'] = $myafilters;

        if (isset($this->request->get['att_filters']) && (is_array($this->request->get['att_filters']))) {
            foreach (array_keys($this->request->get['att_filters']) as $filter_att) {
                $data['afilters'][$filter_att] = $this->request->get['att_filters'][$filter_att];
            }
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/afilters.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/afilters.tpl';
        } else {
            $this->template = 'default/template/module/afilters.tpl';
        }
        $this->render();
    }

}

?>