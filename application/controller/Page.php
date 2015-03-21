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

}
