<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of M_Country
 *
 * @author Heiner
 */
class M_Country {

    public function get_country($args = [], $db = null) {
        $bolDb = true;
        if (empty($db)) {
            $db = new Database();
            $bolDb = false;
        }
        $data = $db->simple_select(COUNTRY, $args);
        
        if (!$bolDb)
            unset($db);
        
        return $data;
    }

    public function verify_country($args, $db = null) {
        $args = array('params' => $args);
        $data = $this->get_country($args, $db);

        return empty($data) ? null : $data[0];
    }

}
