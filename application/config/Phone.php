<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Phone
 *
 * @author Heiner
 */
class Phone {

    public static function verify_phone($phone) {
        $bol = is_numeric($phone);
        return $bol;
    }

}
