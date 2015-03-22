<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page
 *
 * @author Heiner
 */
class Page {

    public function __construct() {
        
    }

    public function get_country() {
        $m_country = new M_Page();
        $country = $m_country->get_country();

        echo (json_encode($country));
    }

    public function page_visit() {
        $args = array(
            'browser' => filter_input(INPUT_POST, 'browser'),
            'referal_link' => filter_input(INPUT_POST, 'referal_link')
        );
        $m_page = new M_Page();
        $resp = $m_page->set_view_page($args);

        echo json_encode($resp);
    }

    public function delete_visit() {
        $args = array(
            'params' => array(
                'referal_link' => filter_input(INPUT_POST, 'referal_link')
            )
        );
        $m_page = new M_Page();
        $page_view = $m_page->get_view_page($args);

        if (!empty($page_view)) {
            $m_page->expire_view_page($page_view);
            echo json_encode(true);
        }else{
            echo json_encode(false);
        }
    }

}
