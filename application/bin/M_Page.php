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
    /*
     * @ $args : referal_link and browser
     */

    public function set_view_page($args = [], $db = null) {
        $bolDb = true;
        if (empty($db)) {
            $db = new Database();
            $bolDb = false;
        }
        $data = $db->simple_insert(PAGE, $args);
        if (!$bolDb)
            unset($db);
        return $data;
    }

    public function get_view_page($args, $db = null) {
        $bolDb = true;
        if (empty($db)) {
            $db = new Database();
            $bolDb = false;
        }
        $args = array('params' => $args);
        $data = $db->simple_select(PAGE, $args);
        if (!$bolDb)
            unset($db);
        $resp = isset($data[0]) ? $data[0] : null;
        return $resp;
    }

    public function expire_view_page($args, $db = null) {
        $bolDb = true;
        if (empty($db)) {
            $db = new Database();
            $bolDb = false;
        }
        $data = $db->simple_update(PAGE, array('referal_link' => null), $args);
        if (!$bolDb)
            unset($db);
        return $data;
    }

}
