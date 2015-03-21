<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of M_Page
 *
 * @author Heiner
 */
class M_Page {
    
    public function get_country($args = []) {
        $db = new Database();
        $data = $db->simple_select(COUNTRY, $args);
        unset($db);
        return $data;
    }

}
