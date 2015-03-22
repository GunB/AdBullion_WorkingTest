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

    /*
     * @ $args : referal_link and browser
     */
    public function set_view_page($args = []) {
        $db = new Database();
        $data = $db->simple_insert(PAGE, $args);
        unset($db);
        return $data;
    }

    public function get_view_page($args) {
        $db = new Database();
        $data = $db->simple_select(PAGE, $args);
        unset($db);
        $resp = isset($data[0]) ? $data[0] : null;
        return $resp;
    }

    public function expire_view_page($args) {
        $db = new Database();
        $data = $db->simple_update(PAGE, array('referal_link' => null), $args);
        unset($db);
        return $data;
    }

}
