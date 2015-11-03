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

        $mv_mensaje = new Mensaje();

        if (!$insert) {
            $mv_mensaje->mensaje_error("No se ha podido agregar el usuario, el correo ingresado ya existe");
        }else{
            $mv_mensaje->mensaje_correcto("Usuario agregado correctamente");
        }
    }
    
    function iniciar_sesion(){
        $m_usuario = new \application\model\M_Usuario();
        $m_data = new \application\model\M_Data();
        $c_forming = new C_Forming();

        $post = $m_data->getAllPost();
        $data = $c_forming->crearInput("usuario", $post);
        
        $usuario = $m_usuario->logUsuario($data);
        //var_dump($usuario);
        
        $mv_mensaje = new Mensaje();
        
        if(sizeof($usuario) > 0){
            $usuario = $usuario[0];
            $m_sesion = new application\model\M_Sesion();
            $usuario = $m_usuario->getUsuario($usuario);
            
            $m_sesion->create_session($usuario);
            var_dump($usuario);
            
            $mv_mensaje->mensaje_correcto("Bienvenido");
        }else{
            $mv_mensaje->mensaje_error("Los datos ingresados son probablemente incorrectos... Intentelo denuevo");
        }
    }
}
