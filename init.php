<?php

/*
 * ---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 * ---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 */
define('ENVIRONMENT', isset($_SERVER['DATA']) ? $_SERVER['DATA'] : 'production');

/*
 * ---------------------------------------------------------------
 * ERROR REPORTING
 * ---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (ENVIRONMENT) {
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;

    case 'testing':
    case 'production':
        runkit_function_redefine('var_dump','','return;');
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}

/*
 * ---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 * ---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same directory
 * as this file.
 */

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

$app_path = "application";

$system_path = 'config';

$files1 = (scandir($app_path . "/" . $system_path));

foreach ($files1 as $key => $value) {
    if (endsWith($value, ".php")) {
        $include_path = $app_path . "/" . $system_path . "/" . $value;
        require_once $include_path;
    }
}

$model_path = "bin";

$files1 = (scandir($app_path . "/" . $model_path));

foreach ($files1 as $key => $value) {
    if (endsWith($value, ".php")) {
        $include_path = $app_path . "/" . $model_path . "/" . $value;
        require_once $include_path;
    }
}

$controller_path = "controller";

$files1 = (scandir($app_path . "/" . $controller_path));

foreach ($files1 as $key => $value) {
    if (endsWith($value, ".php")) {
        $include_path = $app_path . "/" . $controller_path . "/" . $value;
        require_once $include_path;
    }
}

/*
 * ---------------------------------------------------------------
 * SYSTEM PROTECTION
 * ---------------------------------------------------------------
 * 
 * This will change any invalid character in a new one,
 * also prevents crossdomain.
 * 
 */

// Crossdomain
if (isset($_SERVER["HTTP_REFERER"]) && IS_AJAX) {
    if (strpos(BASE_URL, $_SERVER["HTTP_REFERER"]) !== false) {
        $_SERVER["CROSSDOMAINCHECK"] = true;
    } else {
        $_SERVER["CROSSDOMAINCHECK"] = false;
    }
} else {
    $_SERVER["CROSSDOMAINCHECK"] = false;
}

if (!IS_AJAX) {
    //header('Location: index.html');
}

function error($data) {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'The application environment is not set correctly.';
    header('Content-Type: application/json; charset=UTF-8');
    //var_dump($_SERVER);

    $error_arr = array('message' => 'ERROR', 'code' => 1444);

    if (ENVIRONMENT === 'development') {
        $error_arr = array_merge($error_arr, ['file' => $data['file'], 'line' => $data['line']], $_GET, $_POST, $_SERVER);
    }

    die(json_encode($error_arr));
}

if ((!$_SERVER["CROSSDOMAINCHECK"]) && ENVIRONMENT != 'development') {
    $caller = ['file' => 'init', 'line' => 136];
    error($caller);
}

// Invalid characters

function invalid_fixing($data) {
    foreach ($data as $key => $value) {

        if (is_array($value) || is_object($value)) {
            $data[$key] = invalid_fixing($value);
        } else {
            $data[$key] = htmlspecialchars($value);
        }
    }
    array_filter($data);
    return $data;
}

$_GET = invalid_fixing($_GET);
$_POST = invalid_fixing($_POST);

/*
 * ---------------------------------------------------------------
 * SYSTEM FUNCTION
 * ---------------------------------------------------------------
 * 
 * Calling data
 * 
 */

$uri = array_filter(explode("/", htmlspecialchars(filter_input(INPUT_SERVER, "PATH_INFO"))));
$uri = array_merge($uri, array_filter(explode("/", htmlspecialchars(filter_input(INPUT_SERVER, "QUERY_STRING")))));
foreach ($uri as $key => $value) {
    $value = htmlspecialchars($value);
    $value = strpos($value, "?") ? substr($value, 0, strpos($value, "?")) : $value; //Macheteo de NGINX
    $value = strpos($value, "&") ? substr($value, 0, strpos($value, "&")) : $value; //Macheteo de NGINX
    //echo $value."<br>";
    $uri[$key] = $value;
}

$_SERVER["URI"] = $uri;

switch (sizeof($uri)) {
    case 0:
        $caller = ['file' => 'init', 'line' => 171];
        error($caller);
        exit(0);
        break;
    case 1:
        try {
            $refClass = new ReflectionClass("$uri[0]");
            $class_instance = $refClass->newInstanceArgs((array) null);
        } catch (Exception $e) {
            $caller = ['file' => 'init', 'line' => 182];
            error($caller);
            exit(0);
        }
        break;
    default :
        try {
            $refClass = new ReflectionClass("$uri[0]");
            $class_instance = $refClass->newInstanceArgs((array) null);
            $class_instance->$uri[1]();
        } catch (Exception $e) {
            $caller = ['file' => 'init', 'line' => 194];
            error($caller);
            exit(0);
        }
        break;
}