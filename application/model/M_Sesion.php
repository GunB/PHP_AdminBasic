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
        session_start();
        /* session is started if you don't write this line can't use $_Session  global variable */
    }

    function create_session($obj_session) {
        $_SESSION[$this->SESSION] = $obj_session;
    }
    
    function get_session(){
        return $_SESSION[$this->SESSION];
    }

}
