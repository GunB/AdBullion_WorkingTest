<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Articles
 *
 * @author Heiner
 */
class M_Articles {

    public function get_articles($args = []) {
        $db = new Database();
        $articles = $db->simple_select(ARTICLE, $args);
        unset($db);
        return $articles;
    }

}
