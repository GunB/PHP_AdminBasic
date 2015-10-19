<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//namespace application\model_view;

class Inicio {

    //var $sesion = null;
    var $M_DATA;
    var $C_FORMING;

    function __construct() {
        $this->M_DATA = new \application\model\M_Data();
        $this->C_FORMING = new C_Forming();
    }

    function index() {
        showHeaders();
        showMenu();
        showProductos1();
        showFoot();
    }

    function registrarse() {
        showHeaders();
        showMenu();

        $form = $this->M_DATA->readFile("json/registrar_usuario.json");
        $form = $this->C_FORMING->creaForm($form);

        showForm($form); //*/
        //showProductos1();

        showFoot();
    }

}
