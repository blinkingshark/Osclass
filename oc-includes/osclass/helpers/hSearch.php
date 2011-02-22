<?php

    function osc_search() {
        if(View::newInstance()->_exists('search')) {
            return View::newInstance()->_get('search');
        } else {
            $search = new Search();
            View::newInstance()->_exportVariableToView('search', $search);
            return $search;
        }
    }

    function osc_list_orders() {
        return array(
                                 __('Newly listed')       => array('sOrder' => 'dt_pub_date', 'iOrderType' => 'desc')
                                ,__('Lower price first')  => array('sOrder' => 'f_price', 'iOrderType' => 'asc')
                                ,__('Higher price first') => array('sOrder' => 'f_price', 'iOrderType' => 'desc'));
    }
    
    function osc_search_page() {
        return View::newInstance()->_get('search_page');
    }
    
    function osc_search_total_pages() {
        return View::newInstance()->_get('search_total_pages');
    }
    
    function osc_search_has_pic() {
        return View::newInstance()->_get('search_has_pic');
    }
    
    function osc_search_order() {
        return View::newInstance()->_get('search_order');
    }
    
    function osc_search_order_type() {
        return View::newInstance()->_get('search_order_type');
    }
    
    function osc_search_pattern() {
        if(View::newInstance()->_exists('search_pattern')) {
            return View::newInstance()->_get('search_pattern');
        } else {
            return '';
        }
    }
    
    function osc_search_city() {
        return View::newInstance()->_get('search_city');
    }
    
    function osc_search_price_max() {
        return View::newInstance()->_get('search_price_max');
    }
    
    function osc_search_price_min() {
        return View::newInstance()->_get('search_price_min');
    }
    
    function osc_search_total_items() {
        return View::newInstance()->_get('search_total_items');
    }
    
    function osc_search_show_as() {
        return View::newInstance()->_get('search_show_as');
    }
    
    function osc_search_category() {
        if (View::newInstance()->_exists('search_subcategories')) {
            $category = View::newInstance()->_current('search_subcategories') ;
        } elseif (View::newInstance()->_exists('search_categories')) {
            $category = View::newInstance()->_current('search_categories') ;
        } else {
            $category = View::newInstance()->_get('search_category') ;
        }
        return($category) ;
    }
    
    function osc_update_search_url($params, $delimiter = '&amp;') {
        $merged = array_merge($_REQUEST, $params);
        return osc_base_url(true) ."?" . http_build_query($merged, '', $delimiter);
    }

    function osc_alert_form() {
        $search = osc_search();
        $search->order() ;
        $search->limit() ;
        View::newInstance()->_exportVariableToView('search_alert', base64_encode(serialize($search))) ;
        osc_current_web_theme_path('alert-form.php') ;
    }
    
    function osc_search_alert() {
        return View::newInstance()->_get('search_alert');
    }

?>