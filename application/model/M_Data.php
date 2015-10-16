<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace application\model;

class M_Data {

    //var $sesion = null;

    function __construct() {
        
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

    function readFile($string_rute, $isAbsolutePath = false) {
        $resp = null;
        if ($isAbsolutePath) {
            $resp = file_get_contents($string_rute);
        } else {
            $resp = file_get_contents(base_url() . $string_rute);
        }
        return $resp;
    }

    public function listFolderFiles($dir) {
        $arrfiles = array();
        $ffs = scandir($dir);
        //echo '<ol>';
        foreach ($ffs as $ff) {
            if ($ff != '.' && $ff != '..') {
                //echo '<br>' . $ff;
                if (is_dir($dir . '/' . $ff)) {
                    $data = $this->listFolderFiles($dir . '/' . $ff);
                    $arrfiles = array_merge($arrfiles, $data);
                } else {
                    array_push($arrfiles, $dir . '/' . $ff);
                }
                //echo '</li>';
            }
        }
        //echo '</ol>';
        return $arrfiles;
    }

}
