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

    function last_insert_id() {
        return $this->enlace->insert_id;
    }

    function do_query($strquery, $bolcontinue_chain = false) {
        $resp = [];

        //var_dump($strquery);

        $this->enlace->autocommit(false);
        //$this->enlace->begin_transaction(1);

        if (($resultado = $this->enlace->query($strquery)) === FALSE) {
            header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
            echo 'DB Error:';
            
            var_dump(array_merge($this->enlace->error_list, ['query' => $strquery]));
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

        if ($resultado && !is_bool($resultado)) {
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

        if (isset($args->params)) {

            $query .= " where ";

            //var_dump($args->params);

            foreach ($args->params as $key => $value) {
                array_push($arr_query, " $key = '$value'");
            }
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

        //isset($args->params) ? var_dump($query) : false;

        return $this->do_query($query);
    }

    function simple_insert($table, $args, $boolchain = false) {
        $args = (object) $args;

        $query = "INSERT INTO $table  ";

        $columns = [];
        $values = [];

        foreach ($args as $key => $value) {
            //"(column1, column2, column3,...) VALUES (value1, value2, value3,...)"
            $columns[] = "$key";
            $values[] = "'$value'";
        }

        $query .= "( " . implode(",", $columns) . " ) VALUES ( " . implode(",", $values) . " )";

        //var_dump($query);

        return $this->do_query($query, $boolchain);
    }

    function simple_update($table, $new_data, $identifiers) {
        $query = "UPDATE $table ";

        $data_nueva = [];
        $data_compare = [];

        foreach ($new_data as $key => $value) {
            if ($value === null) {
                $data_nueva[] = "$key = NULL";
            } else {
                $data_nueva[] = "$key = '$value'";
            }
        }

        foreach ($identifiers as $key => $value) {
            $data_compare[] = "$key = '$value'";
        }

        $query .= " SET " . implode(" , ", $data_nueva);
        $query .= " where " . implode(" and ", $data_compare);

        return $this->do_query($query);
    }

    function __destruct() {
        $this->enlace ? $this->enlace->close() : false;
    }

}
