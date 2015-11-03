<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace application\model;

class M_Usuario {

    //var $sesion = null;
    var $DB;

    function __construct() {
        $this->DB = new \application\config\DATABASE();
    }
    
    function setUsuario($objData){
        $insert = $this->DB->insert("usuario", $objData);
        
        return $insert;
    }
    
    function logUsuario($objData){
        $insert = $this->DB->select_and("usuario", $objData);
        
        return $insert;
    }
    
    function getUsuario($obj_usuario){
        $this->DB->select_and("usuario", $obj_usuario, ["*"]);
    }
}