<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace application\config;

class DATABASE {

    var $DB = "mydb";
    var $HOST = "localhost";
    var $USER = "root";
    var $PASS = "";
    var $CON;

    function __construct() {
        $this->CON = mysqli_connect($this->HOST, $this->USER, $this->PASS, $this->DB);
    }

    function list_fields($table) {
        $resp = [];
        $resultado = mysqli_query($this->CON, "SHOW COLUMNS FROM $table");
        if ($resultado) {
            while ($obj = $resultado->fetch_object()) {
                array_push($resp, $obj->Field);
            }
        } else {
            echo 'No se pudo ejecutar la consulta: ' . mysqli_error($this->CON);
            exit;
        }
        return $resp;
    }

    function insert($table, $obj_data) {
        $sql_query = "INSERT INTO $table";

        $keys = [];
        $values = [];

        foreach ($obj_data as $key => $value) {
            array_push($keys, $key);
            if (is_object($value) || is_array($value)) {
                array_push($values, "'" .
                        mysqli_real_escape_string($this->CON, json_encode($value))
                        . "'");
            } else {
                array_push($values, "'" . ($value) . "'");
            }
        }

        $sql_query .= "(";
        $sql_query .= implode(",", $keys);
        $sql_query .= ") VALUES (";
        $sql_query .= implode(",", $values);
        $sql_query .= ")";
        
        $resultado = mysqli_query($this->CON, $sql_query);
        
        return $resultado;
    }

    function __destruct() {
        mysqli_close($this->CON);
    }

}
