<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace application\model;

class M_Usuario {

    //var $sesion = null;

    function __construct() {
        
    }
    
    function setUsuario($var){
        $db = new \application\config\DATABASE();
        $insert = $db->insert("usuario", $var);
        
        return $insert;
    }
}