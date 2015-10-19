<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class C_Usuario {

    //var $sesion = null;

    function __construct() {
        
    }

    function registro() {
        $m_usuario = new \application\model\M_Usuario();
        $m_data = new \application\model\M_Data();
        $c_forming = new C_Forming();

        $post = $m_data->getAllPost();
        unset($post->contrasena2);
        $data = $c_forming->crearInput("usuario", $post);

        $insert = $m_usuario->setUsuario($data);

        showHeaders();
        showMenu();

        if (!$insert) {
            error_mensaje("No se ha podido agregar el usuario, el correo ingresado ya existe");
        }else{
            correct_mensaje("Usuario agregado correctamente");
        }
        
        showFoot();
    }
    
    function iniciar_sesion(){
        $m_usuario = new \application\model\M_Usuario();
        $m_data = new \application\model\M_Data();
        $c_forming = new C_Forming();

        $post = $m_data->getAllPost();
        $data = $c_forming->crearInput("usuario", $post);
    }
}
