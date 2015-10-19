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
        foreach (filter_input_array(INPUT_POST) as $key => $value) {
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

    /**
     * Leer un archivo
     * @param string $string_rute     Ruta del archivo a leer
     * @param bool $isAbsolutePath      Buscar en una ruta especifica, por defecto es relativa
     * @return sring Retorna el contenido del archivo
     */
    function readFile($string_rute, $isAbsolutePath = false) {
        $resp = null;
        if ($isAbsolutePath) {
            $resp = file_get_contents($string_rute);
        } else {
            $resp = file_get_contents(base_url($string_rute));
        }
        
        $resp = mb_convert_encoding($resp, 'HTML-ENTITIES', "UTF-8");
        return $resp;
    }

    /**
     * Get an array that represents directory tree
     * @param string $directory     Directory path
     * @param bool $recursive         Include sub directories
     * @param bool $listDirs         Include directories on listing
     * @param bool $listFiles         Include files on listing
     * @param regex $exclude         Exclude paths that matches this regex
     */
    function directoryToArray($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '') {
        $arrayItems = array();
        $skipByExclude = false;
        $handle = opendir($directory);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
                if ($exclude) {
                    preg_match($exclude, $file, $skipByExclude);
                }
                if (!$skip && !$skipByExclude) {
                    if (is_dir($directory . DIRECTORY_SEPARATOR . $file)) {
                        if ($recursive) {
                            $arrayItems = array_merge($arrayItems, $this->directoryToArray($directory . DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude));
                        }
                        if ($listDirs) {
                            $file = $directory . DIRECTORY_SEPARATOR . $file;
                            $arrayItems[] = $file;
                        }
                    } else {
                        if ($listFiles) {
                            $file = $directory . DIRECTORY_SEPARATOR . $file;
                            $arrayItems[] = $file;
                        }
                    }
                }
            }
            closedir($handle);
        }
        return $arrayItems;
    }

}
