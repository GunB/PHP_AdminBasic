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
define('ENVIRONMENT', isset($_SERVER['DATA']) ? $_SERVER['DATA'] : 'development');
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
        //runkit_function_redefine('var_dump','','return;');
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

$arr_paths = ["config", "controller", "model", "view", "model_view"];

foreach ($arr_paths as $val) {
    $system_path = $val;
    $files1 = (scandir($app_path . "/" . $system_path));
    foreach ($files1 as $key => $value) {
        if (endsWith($value, ".php")) {
            $include_path = $app_path . "/" . $system_path . "/" . $value;
            require_once $include_path;
        }
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
    //echo 'The application environment is not set correctly.';
    header('Content-Type: application/json; charset=UTF-8');
    //var_dump($_SERVER);
    $error_arr = array('message' => 'ERROR', 'code' => 1444);
    if (ENVIRONMENT === 'development') {
        $error_arr = array_merge($error_arr, $data, ["GET" => filter_input_array(INPUT_GET)], ["POST" => filter_input_array(INPUT_POST)], ["SERVER" => filter_input_array(INPUT_SERVER)]);
    }
    //var_dump($error_arr);
    //exit(0);
    die(json_encode($error_arr));
}

if ((!$_SERVER["CROSSDOMAINCHECK"]) && ENVIRONMENT != 'development') {
    $caller = ['file' => 'init', 'line' => 136];
    error($caller);
}

// Invalid characters
/*
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
  $_GET = invalid_fixing(filter_input_array(INPUT_SERVER));
  $_POST = invalid_fixing($_POST);
  // */

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

//use application\model_view;
// <editor-fold defaultstate="collapsed" desc="Start Site">
function startSite($uri, $modelview_path) {

    switch (sizeof($uri)) {
        case 0:
            $uri[0] = START_PAGE;
            startSite($uri, $modelview_path);
            //$caller = ['file' => 'init', 'line' => 171];
            //error($caller);
            exit(0);
            break;
        case 1:
            try {
                $refClass = new ReflectionClass(ucfirst("$uri[0]"));
                $class_instance = $refClass->newInstanceArgs((array) null);
                $uri[1] = "index";
                $class_instance->$uri[1]();
            } catch (Exception $e) {
                $caller = ['file' => 'init', 'line' => 183, "uri" => $uri, "error_mess" => $e];
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
                $caller = ['file' => 'init', 'line' => 194, "uri" => $uri, "error_mess" => $e];
                error($caller);
                exit(0);
            }
            break;
    }
}

// </editor-fold>

startSite($uri, $arr_paths[sizeof($arr_paths) - 1]);
