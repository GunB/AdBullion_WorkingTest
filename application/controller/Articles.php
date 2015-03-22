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
class Articles {

    public function get_articles() {
        $m_articles = new M_Articles();
        $articles = $m_articles->get_articles([
            "order_by" => "rand",
            "limit" => 4
        ]);
        
        echo json_encode($articles);
    }

}
