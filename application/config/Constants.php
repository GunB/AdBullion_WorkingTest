<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

mb_internal_encoding("UTF-8");

/**
 * Description of Constants
 *
 * @author Heiner
 */
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

/*
 * ---------------------------------------------------------------
 * BOOTING SYSTEM
 * ---------------------------------------------------------------
 *
 * Create de base route to get the functions;
 */

$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$base_url .= "://" . $_SERVER['HTTP_HOST'];
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

$base_page = "init.php";

define("BASE_URL", $base_url . $base_page);
define("BASE", $base_url . $base_page);

// functions

//Tables

define("ARTICLE", "article");
define("COUNTRY", "country");
define("PAGE", "pagevisit");
define("PURCHASE", "purchase");

define("PURCHASE_ARTICLE_ORDER", "purchase_has_article");

define("CLIENT", "client");

