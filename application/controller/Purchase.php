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
        $m_page = new M_Page();

        $db = new Database();

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
            'country' => ($m_country->verify_country(['id' => $prepurchase['country']], $db)),
            'article' => ($m_article->verify_article($prepurchase['article'], $db)),
            'page' => $m_page->get_view_page(['referal_link' => $prepurchase['referal_link']], $db)
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

        $verify['client'] = ($m_client->verify_client($client, $db));

        //var_dump($verify['client']);

        if (empty($verify['client'])) {
            $try_verify = $m_client->try_add_client($client, $db);

            foreach ($try_verify as $value) {
                if (!empty($value)) {
                    Error::send_error([
                        'error' => 2,
                        'verify' => $try_verify
                    ]);
                    return 0;
                }
            }
        } else {
            $client = (array) $verify['client'];
        }

        $prepurchase['verify'] = $verify;
        $prepurchase['client'] = $client;
        
        //var_dump($prepurchase);

        return $prepurchase;
    }

    function generate_values($args) {

        $article = $args['verify']['article'];
        $country = $args['verify']['country'];
        $pagevis = $args['verify']['page'];
        $clientt = $args['client'];

        $purchase = [
            'article' => $article,
            'client' => $clientt,
            'country' => $country,
            'page' => $pagevis,
            'value' => ($country->rel * $article->peso) + $article->precio
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

    function purchase_article() {
        $m_client = new M_Client();
        $m_purchase = new M_Purchase();

        $db = new Database();

        $purchase = $this->verify_purchase($_POST['purchase'], $db);

        if (!empty($purchase)) {
            $m_data = new M_Purchase();

            $purchase = $this->generate_values($purchase);

            //var_dump($purchase);

            if (empty($purchase['client']['id'])) {
                $m_client->add_client($purchase['client'], $db);
                $purchase['client']['id'] = $db->last_insert_id();
            }

            $data = $m_purchase->create_purchase((object) $purchase['page'], (object) $purchase['client'], (object) $purchase['article'], (object) $purchase['country'], (object) $purchase, $db);

            unset($db);

            echo json_encode($data);
            return $data;
        } else {
            unset($db);
            echo json_encode(false);
            return 0;
        }
    }

}
