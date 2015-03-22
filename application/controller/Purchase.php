<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Purchase
 *
 * @author Heiner
 */
class Purchase {

    public function verify_purchase($prepurchase) {
        //var_dump($_POST);
        $m_country = new M_Country();
        $m_client = new M_Client();
        $m_article = new M_Articles();

        //$prepurchase = $_POST['prepurchase'];

        $client = [
            'nombre' => $prepurchase['nombre'],
            'apellido' => $prepurchase['apellido'],
            'telefono' => $prepurchase['telefono'],
            'email' => $prepurchase['email']
        ];

        $verify = [
            'telefono' => Phone::verify_phone($client['telefono']),
            'email' => Email::verify_email($client['email']),
            'country' => ($m_country->verify_country(['id' => $prepurchase['country']])),
            'article' => ($m_article->verify_article($prepurchase['article']))
        ];

        foreach ($verify as $value) {
            if (empty($value)) {
                Error::send_error([
                    'error' => 1,
                    'verify' => $verify
                ]);
                return 0;
            }
        }

        $verify['client'] = ($m_client->verify_client($client));

        if (empty($verify['client'])) {
            $try_verify = $m_client->try_add_client($client);

            foreach ($try_verify as $value) {
                if (empty($value)) {
                    Error::send_error([
                        'error' => 2,
                        'verify' => $try_verify
                    ]);
                    return 0;
                }
            }
        }

        $prepurchase['verify'] = $verify;
        $prepurchase['client'] = $client;

        return $prepurchase;
    }
    
    function generate_values($args){
        
        $article = $args['verify']['article'];
        $country = $args['verify']['country'];
        $clientt = $args['client'];
        
        $purchase = [
            'article'   => $article,
            'client'    => $clientt,
            'country'   => $country,
            'value'     => ($country->rel * $article->peso) + $article->precio
        ];
        
        return $purchase;
    }

    function generate_prepurchase() {

        $prepurchase = $this->verify_purchase($_POST['prepurchase']);
        
        //var_dump($prepurchase);

        if (!empty($prepurchase)) {
            $m_data = new M_Purchase();
            $purchase = $this->generate_values($prepurchase);
            
            echo json_encode($purchase);
            return 1;
        } else {
            return 0;
        }
    }

}
