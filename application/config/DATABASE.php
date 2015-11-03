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
    
    function select_and($table, $obj_data, $arr_selec = ["id"]) {
        
        $sql_query = "Select";

        $resp = [];
        $keys = [];
        $values = [];
        $both = [];

        foreach ($obj_data as $key => $value) {
            array_push($keys, $key);
            if (is_object($value) || is_array($value)) {
                array_push($values, "'" .
                        mysqli_real_escape_string($this->CON, json_encode($value))
                        . "'");
                array_push($both, "$key = '" .
                        mysqli_real_escape_string($this->CON, json_encode($value))
                        . "'");
            } else {
                array_push($values, "'" . ($value) . "'");
                array_push($both, "$key = '" . ($value) . "'");
            }
        }

        $sql_query .= " ";
        $sql_query .= implode(", ", $arr_selec);
        $sql_query .= " ";
        $sql_query .= "from $table";
        $sql_query .= " ";
        $sql_query .= "where";
        $sql_query .= " ";
        $sql_query .= implode(" and ", $both);
        
        $resultado = mysqli_query($this->CON, $sql_query);
        
        var_dump($sql_query);
        
        if ($resultado) {
            while ($obj = $resultado->fetch_object()) {
                $resp_obj = (object)[];
                foreach ($arr_selec as $key => $value) {
                    if($value == "*"){
                        $list = $this->list_fields($table);
                        foreach ($list as $val) {
                            $resp_obj->$val = $obj->$val;
                        }
                    }else{
                        $resp_obj->$value = $obj->$value;
                    }
                }
                array_push($resp, $resp_obj);
                //
            }
        } else {
            echo 'No se pudo ejecutar la consulta: ' . mysqli_error($this->CON);
            exit;
        }
        
        return $resp;
    }

    function __destruct() {
        mysqli_close($this->CON);
    }

}
