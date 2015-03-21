<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Database
 *
 * @author Heiner
 */
class Database {

    var $server = "T08qdB.adsbullion.com";
    var $user = "test1";
    var $pass = "T08qdB";
    var $db = "test1";
    var $connection_active = false;
    var $enlace = null;

    function __construct() {
        $this->enlace = new mysqli($this->server, $this->user, $this->pass, $this->db, 3306);
        if (!$this->enlace) {
            if (ENVIRONMENT == "development") {
                die('No pudo conectarse');
            }
            $this->connection_active = false;
        } else {
            $this->connection_active = true;
            $this->enlace->set_charset("utf8");
        }

        //var_dump($this->enlace);
        //echo 'Conectado satisfactoriamente';
        //mysql_close($enlace);
    }

    function do_query($strquery, $bolcontinue_chain = false) {
        $resp = [];

        $this->enlace->autocommit(false);
        //$this->enlace->begin_transaction(1);

        if (($resultado = $this->enlace->query($strquery)) === FALSE) {
            return FALSE;
        }
        //$resultado = mysqli_fetch_object($query);

        if (ENVIRONMENT == "development") {
            //var_dump($strquery);
            //var_dump($resultado);
        }

        if (!$resultado) {
            $resp = null;
            $bolcontinue_chain ? $this->rollback() : null;
            if (ENVIRONMENT == "development") {
                die('Consulta no válida: ' . "\n $strquery");
            }
        }

        if ($resultado) {
            while ($obj = mysqli_fetch_object($resultado)) {
                //var_dump($obj);
                array_push($resp, $obj);
            }
            //var_dump($resp);
        }

        if (!$bolcontinue_chain) {
            $this->end_transaction();
        }

        return $resp;
    }

    function end_transaction() {
        $this->enlace->commit();
        //$this->enlace->begin_transaction(0);
        $this->enlace->autocommit(true);
    }

    function rollback() {
        $this->enlace->rollback();
        $this->enlace->autocommit(true);
    }

    /*
     * 
     * $table: Name of table to query
     * $args: object or asoc_array with
     *      id => especific id of row
     *      in => array of ids
     *      order_by => type of sort (asc, desc, rand)
     *      limit => max number of rows
     * 
     */

    function simple_select($table, $args = []) {

        $args = (object) $args;

        $query = "Select * from " . $table;

        $arr_query = [];

        if (isset($args->id)) {
            array_push($arr_query, "where id = '$args->id'");
        }

        if (isset($args->in)) {
            array_push($arr_query, "where id in ('" . implode("','", $args->in) . "')");
        }

        $query .= " " . implode(" and ", $arr_query) . " ";

        if (isset($args->order_by)) {
            if (strtolower($args->order_by) == "rand") {
                $args->order_by .= "()";
            }
            $query .= " order by $args->order_by ";
        }

        if (isset($args->limit)) {
            $query .= " limit $args->limit ";
        }
        
        //var_dump($query);

        return $this->do_query($query);
    }

    function __destruct() {
        $this->enlace ? $this->enlace->close() : false;
    }

}
