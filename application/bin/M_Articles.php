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

    public function get_articles($args = [], $db = null) {
        $bolDb = true;
        if (empty($db)) {
            $db = new Database();
            $bolDb = false;
        }
        $articles = $db->simple_select(ARTICLE, $args);

        if (!$bolDb)
            unset($db);

        return $articles;
    }

    public function verify_article($args, $db = null) {
        $args = array('params' => $args);
        $data = $this->get_articles($args, $db);

        return empty($data) ? null : $data[0];
    }

}
