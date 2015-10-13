<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace application\controller;

class M_Forming {

    //var $sesion = null;

    function __construct() {
        
    }

    function object2Array($obj) {
        $arrObj = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($arrObj as $key => $val) {
            $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }

    function getAllPost() {
        $post = array();
        foreach (filter_input_array(INPUT_POST) as $key) {
            $data = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            if (!empty($data) or $data == 0) {
                $post[$key] = (($data));
            }
        }
        $post = (object) $post;
        return $post;
    }

    function getAllGet() {
        $post = array();
        foreach (filter_input_array(INPUT_GET) as $key) {
            $data = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            if (!empty($data) or $data == 0) {
                $post[$key] = (($data));
            }
        }
        $post = (object) $post;
        return $post;
    }

    function getAllServer() {
        $post = array();
        foreach (filter_input_array(INPUT_SERVER) as $key) {
            $data = filter_input(INPUT_SERVER, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            if (!empty($data) or $data == 0) {
                $post[$key] = (($data));
            }
        }
        $post = (object) $post;
        return $post;
    }

    function crearInput($nombre_tabla, $data, $is_detalle_json = false) {
        $fields = $this->db->list_fields($nombre_tabla);
        $input = array();
        foreach ($fields as $value) {
            if (isset($data->$value)) {
                if (!empty($data->$value)) {
                    $input[$value] = $data->$value;
                    unset($data->$value);
                } else {
                    unset($data->$value);
                }
            } elseif ($value == 'usuario_id') {
                $input['usuario_id'] = $this->m_sesion->obtener_id_sesion();
            }
        }

        if (!empty($data)) {
            if ($is_detalle_json) {
                $input['detalles'] = json_encode($data);
            } else {
                $input['detalles'] = ($data);
            }
        }

        $input = (object) $input;
        return $input;
    }

}
