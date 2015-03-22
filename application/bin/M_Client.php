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

    public function get_client($args = []) {
        $db = new Database();
        $data = $db->simple_select(CLIENT, $args);
        unset($db);
        return $data;
    }

    public function verify_client($args) {
        $args = array('params' => $args);
        $data = $this->get_client($args);

        return empty($data) ? null : $data[0];
    }

    public function try_add_client($args) {
        $arrg_verify = [
            'telefono' => ($this->get_telefono($args['telefono'])),
            'email' => ($this->get_email($args['email']))
        ];
        return $arrg_verify;
    }

    public function get_telefono($phone) {
        $args = array('params' => [
                'telefono' => $phone
        ]);
        $data = $this->get_client($args);

        return empty($data) ? null : $data[0];
    }

    public function get_email($email) {
        $args = array('params' => [
                'email' => $email
        ]);
        $data = $this->get_client($args);

        return empty($data) ? null : $data[0];
    }

}
