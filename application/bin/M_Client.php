<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of M_Client
 *
 * @author Heiner
 */
class M_Client {

    //put your code here

    public function get_client($args = [], $db = null) {
        $bolDb = true;
        if (empty($db)) {
            $db = new Database();
            $bolDb = false;
        }
        $data = $db->simple_select(CLIENT, $args);
        if (!$bolDb)
            unset($db);
        return $data;
    }

    public function verify_client($args, $db = null) {
        $args = array('params' => $args);
        $data = $this->get_client($args, $db);

        return empty($data) ? null : $data[0];
    }

    public function try_add_client($args, $db) {
        $arrg_verify = [
            'telefono' => ($this->get_telefono($args['telefono'], $db)),
            'email' => ($this->get_email($args['email'], $db))
        ];
        return $arrg_verify;
    }

    public function get_telefono($phone, $db) {
        $args = array('params' => [
                'telefono' => $phone
        ]);
        $data = $this->get_client($args, $db);

        return empty($data) ? null : $data[0];
    }

    public function get_email($email, $db) {
        $args = array('params' => [
                'email' => $email
        ]);
        $data = $this->get_client($args, $db);

        return empty($data) ? null : $data[0];
    }

    public function add_client($args, $db = null, $boolchain = false) {
        $bolDb = true;
        if (empty($db)) {
            $db = new Database();
            $bolDb = false;
        }
        
        $data = $db->simple_insert(CLIENT, $args);

        if (!$bolDb)
            unset($db);
        
        return $data;
    }

}
