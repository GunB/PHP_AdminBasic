<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Mensaje {

    function __construct() {
        
    }

    function mensaje_error($mensaje = "", $url = BASE_URL) {
        showHeaders();
        showMenu();
        error_mensaje($mensaje, $url);
        showFoot();
    }
    
    function mensaje_correcto($mensaje = "", $url = BASE_URL) {
        showHeaders();
        showMenu();
        correct_mensaje($mensaje, $url);
        showFoot();
    }

}
