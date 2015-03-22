<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Email
 *
 * @author Heiner
 */
class Email {

    public static function verify_email($email) {
        return; !empty(filter_var($email, FILTER_VALIDATE_EMAIL));
    }

}
