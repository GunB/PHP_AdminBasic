<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace application\model;

class M_Sesion {

    //var $sesion = null;

    var $SESSION = "SESSION";

    function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        /* session is started if you don't write this line can't use $_Session  global variable */
    }

    function create_session($obj_session) {
        $_SESSION[$this->SESSION] = $obj_session;
    }

    function get_session() {
        return $_SESSION[$this->SESSION];
    }

    function destroy_session() {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
            );
        }

        // Finalmente, destruir la sesión.
        session_destroy();
    }

}
