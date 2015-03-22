<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Error
 *
 * @author Heiner
 */
class Error {
    public static function send_error($args){
        echo json_encode($args);
    }
}
