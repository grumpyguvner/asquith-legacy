<?php

class ModelCatalogCategory extends Model {

    public function getCategory($category_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int) $category_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status = '1'");

        return $query->row;
    }

    public function getCategories($parent_id = 0) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int) $parent_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

        return $query->rows;
    }

    public function getCategoryAttributes($category_id = 0, $att_filters = null) {
        // Get all attributes for current category
        
        $data = array();

        if (isset($att_filters) && (is_array($att_filters))) {

            foreach (array_keys($att_filters) as $filter_att) {

                $data['afilters'][$filter_att] = $att_filters[$filter_att];
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

                    // eng get bit attribute

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

                if ($barray['name'] != "Product Tabs"){
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
        }
        // END get all attributes for current category
        if(isset($big_array2))
            return $big_array2;
        else
            return array();
    }

    public function getCategoriesByParentId($category_id) {
        $category_data = array();

        $category_data[] = $category_id;

        $category_query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int) $category_id . "'");

        foreach ($category_query->rows as $category) {
            $children = $this->getCategoriesByParentId($category['category_id']);

            if ($children) {
                $category_data = array_merge($children, $category_data);
            }
        }

        return $category_data;
    }

    public function getCategoryLayoutId($category_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int) $category_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return $this->config->get('config_layout_category');
        }
    }

    public function getTotalCategoriesByCategoryId($parent_id = 0) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int) $parent_id . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status = '1'");

        return $query->row['total'];
    }

//-------------------------------------- start add products attributes filtered   -------------------------------------

    public function getParentCategory($category_id) {

        $query = $this->db->query("SELECT parent_id FROM " . DB_PREFIX . "category WHERE category_id = '" . (int) $category_id . "'");



        if ($query->num_rows) {

            return $query->row['parent_id'];
        } else {

            return false;
        }
    }

    public function getProductCategories($prodid) {
        $query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . $prodid . "'");
        if ($query->num_rows > 0) {
            return $query->rows;
        } else {
            return false;
        }
    }

//------------------------------------ end add products attributes filtered ---------------------------------------------
}

?>