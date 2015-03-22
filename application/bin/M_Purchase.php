<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of M_Purchase
 *
 * @author Heiner
 */
class M_Purchase {
    
    function create_purchase($page, $client, $article, $country, $args, $db = null){
        $bolDb = true;
        if (empty($db)) {
            $db = new Database();
            $bolDb = false;
        }
        
        $purchase = [
            'pagevisit_visit_ID' => $page->visit_ID,
            'client_id' => $client->id,
            'country' => $country->id
        ];
        
        $db->simple_insert(PURCHASE, $purchase, true);
        
        $article_purchase = [
            'order_id' => $db->last_insert_id(),
            'article_id' => $article->id,
            'value' => $purchase->value
        ];
        
        $data = $db->simple_insert(PURCHASE_ARTICLE_ORDER, $article_purchase);
        
        if (!$bolDb)
            unset($db);

        return $data;
    }
    
}
