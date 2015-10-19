<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//namespace application\controller;

class C_Forming {

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

    /**
     * Crear formulario con validaciones
     *
     * @param string $string_form Formulario leido de json
     * @return array $resp formualario con 3 campos distintos que deben ser impresos
     * @return string $resp['id_unico'] id unico del formulario
     * @return string $resp['extra'] Opciones especiales para el validador
     * @return string $resp['extra2'] Opciones realmente especiales para validador manual
     */
    function creaForm($string_form) {
        $id_unico = md5(uniqid(rand(), true));
        $data['array'] = json_decode($string_form, true);
        $boolPasa = false;
        $resp = array();
        // <editor-fold defaultstate="collapsed" desc="errores en json">
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $boolPasa = true;
                //echo ' - Sin errores';
                break;
            case JSON_ERROR_DEPTH:
                echo ' - Excedido tamaño máximo de la pila';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                echo ' - Desbordamiento de buffer o los modos no coinciden';
                break;
            case JSON_ERROR_CTRL_CHAR:
                echo ' - Encontrado carácter de control no esperado';
                break;
            case JSON_ERROR_SYNTAX:
                echo $string_form;
                echo ' - Error de sintaxis, JSON mal formado';
                break;
            case JSON_ERROR_UTF8:
                echo ' - Caracteres UTF-8 malformados, posiblemente están mal codificados';
                break;
            default:
                echo ' - Error desconocido';
                break;
        }// </editor-fold>

        if ($boolPasa) {

            $resp['id_unico'] = "$id_unico";
            $resp['extra'] = "";
            $resp['extra2'] = "";

            foreach ($data['array'] as $key => $value) {

                $error_message = 'Este campo es necesario';
                $require = 'required';
                if (isset($value['required'])) {
                    if ($value['required']) {
                        $require = 'required';
                        $error_message = 'Este campo es necesario';
                        if (isset($value['error_message'])) {
                            $error_message = $value['error_message'] . '';
                        }
                    } else {
                        $require = '';
                    }
                }

                if (isset($value['equalTo'])) {
                    $resp['extra'] .= $key . ': {equalTo: "#' . $id_unico . ' [name=' . "'" . $value['equalTo'] . "'" . ']"}, ';
                }

                if (isset($value['table_out']) and isset($value['table_in'])) {
                    $value['value'] = $this->get_all_from_table_form($value['table_out']);
                    //var_dump($value['value']);
                    array_push($resp, '<input readonly type="hidden" name="table_in[' . $key . ']" value="' . $value['table_in'] . '">');
                    //$resp['extra2'] .= '$(' . "'" . '[name=*"' . $key . '"]' . "'" . ').parents("tr:eq(0)").css("display","none")';
                }

                switch ($value['type']) {
                    default:
                        //var_dump($value);
                        break;
                    case "button":
                        $direc = "";
                        if ($value['site'] == "relative") {
                            $direc = site_url($value['value']);
                        } elseif ($value['site'] == "absolute") {
                            $direc = $value['value'];
                        }
                        $resp['extra2'] .= '$("#' . $id_unico . ' .' . $key . '").on("click",'
                                . 'function(event){  '
                                . 'event.preventDefault();'
                                . '$("#' . $id_unico . '").parents("form:eq(0)").attr("action","' . $direc . '");'
                                . '$("#' . $id_unico . '").parents("form:eq(0)").submit();'
                                . '  });' . "\n";
                        array_push($resp, '<button href="' . $direc . '" class="btn ' . @$value['class'] . ' ' . $key . '" >' . @$value['label'] . '</button>');
                        break;
                    case "submit":
                        $direc = "";
                        if ($value['site'] == "relative") {
                            $direc = site_url($value['value']);
                        } elseif ($value['site'] == "absolute") {
                            $direc = $value['value'];
                        }
                        $resp['extra2'] .= '$("#' . $id_unico . ' .' . $key . '").on("click",'
                                . 'function(event){  '
                                . 'event.preventDefault();'
                                . '$("#' . $id_unico . '").parents("form:eq(0)").attr("action","' . $direc . '");'
                                . '$("#' . $id_unico . '").parents("form:eq(0)").submit();'
                                . '  });' . "\n";
                        array_push($resp, '<button href="' . $direc . '" class="btn ' . @$value['class'] . ' ' . $key . '" >' . @$value['label'] . '</button>');
                        break;
                    case "action":
                        $direc = "";
                        if ($value['site'] === "relative") {
                            $direc = site_url($value['value']);
                        } elseif ($value['site'] === "absolute") {
                            $direc = $value['value'];
                        }
                        $resp['extra2'] .= '$("#' . $id_unico . '").parents("form:eq(0)").attr("action","' . $direc . '");' . "\n";
                        break;

                    case "verify":
                        $direc = "";
                        if ($value['site'] == "relative") {
                            $direc = site_url($value['value']);
                        } elseif ($value['site'] == "absolute") {
                            $direc = $value['value'];
                        }
                        $resp['extra2'] .= '$("#' . $id_unico . '").parents("form:eq(0)").prop("verify","' . $direc . '");' . "\n";
                        break;
                    case "div":
                        array_push($resp, '<div id="' . $key . '">' . '</div>');
                        break;
                    case 'readonly':
                        array_push($resp, array('<label class="control-label" for="' . $key . '">' . @$value['label'] . '</label>',
                            '<input name="' . $key . '" class="form-control" readonly type="text">'));
                        break;
                    case 'hidden':
                        array_push($resp, array('<label class="control-label" for="' . $key . '">' . '</label>',
                            '<input name="' . $key . '" class="form-control" value="' . $value['value'] . '" readonly type="hidden">'));
                        $resp['extra2'] .= '$(' . "'" . '[name="' . $key . '"]' . "'" . ').parents("tr:eq(0)").css("display","none")' . "\n";
                        break;
                    case 'page_header':
                        array_push($resp, '<div class="page-header"><h1>' . ($value['value']) . ' <small>' . @$value['placeholder'] . '</small></h1></div>');
                        break;
                    case 'info':
                        array_push($resp, '<strong>' . @$value['label'] . '</strong><p>' . $value['value'] . '</p>');
                        break;
                    case 'title':
                        array_push($resp, '<h' . $value['value'] . '>' . @$value['label'] . ' <small>' . @$value['placeholder'] . '</small></h' . $value['value'] . '>');
                        break;
                    case 'date':
                        $resp['extra'] .= $key . ': {date: true}, ';
                        $resp['extra2'] .= '$(' . "'" . '[name="' . $key . '"]' . "'" . ').datepicker({ dateFormat: "yy-mm-dd", defaultDate: +0,todayBtn: true,language: "es",autoclose: true});';
                        array_push($resp, array('<label class="control-label" for="' . $key . '">' . @$value['label'] . '</label>',
                            '<input autocomplete="off" class="form-control prevent-type" type="text" ' . $require . ' title="' . $error_message . '" data-validation="alphanumeric length" data-validation-length="min4" name="' . $key . '" value="' . $value['value'] . '" placeholder="' . @$value['placeholder'] . '">'));
                        break;
                    case 'email':
                    case 'mail':
                    case 'correo':
                        $resp['extra'] .= $key . ': {email: true}, ';
                        array_push($resp, array('<label class="control-label" for="' . $key . '">' . @$value['label'] . '</label>',
                            '<input class="form-control" type="text" ' . $require . ' title="' . $error_message . '" data-validation="alphanumeric length" data-validation-length="min4" name="' . $key . '" value="' . $value['value'] . '" placeholder="' . @$value['placeholder'] . '">'));
                        break;
                    case 'text':
                        array_push($resp, array('<label class="control-label" for="' . $key . '">' . @$value['label'] . '</label>',
                            '<input class="form-control" type="text" ' . $require . ' title="' . $error_message . '" data-validation="alphanumeric length" data-validation-length="min4" name="' . $key . '" value="' . $value['value'] . '" placeholder="' . @$value['placeholder'] . '">'));
                        break;
                    case 'number':
                    case 'numeric':
                    case 'integer':
                        array_push($resp, array('<label class="control-label" for="' . $key . '">' . @$value['label'] . '</label>',
                            '<input class="form-control" type="text" ' . $require . ' title="' . $error_message . '" data-validation="number" name="' . $key . '" value="' . $value['value'] . '" placeholder="' . @$value['placeholder'] . '">'));

                        break;
                    case 'password':
                        array_push($resp, array('<label class="control-label" for="' . $key . '">' . @$value['label'] . '</label>',
                            '<input class="form-control" type="password" ' . $require . ' title="' . $error_message . '" data-validation="alphanumeric length" data-validation-length="min4" name="' . $key . '" value="' . $value['value'] . '" placeholder="' . @$value['placeholder'] . '">'));
                        break;
                    case 'textarea':
                        array_push($resp, array('<label class="control-label" for="' . $key . '">' . @$value['label'] . '</label>',
                            '<div></div><textarea class="form-control" type="text" ' . $require . ' title="' . $error_message . '" data-validation="alphanumeric length" data-validation-length="min10" name="' . $key . '" placeholder="' . @$value['placeholder'] . '">' . $value['value'] . '</textarea>'));
                        break;

                    case 'radio':
                        $label = ('<label class="control-label" for="' . $key . '">' . @$value['label'] . '</label>');
                        if (!empty($require)) {
                            $resp['extra'] .= $key . ': {required: true,minlength: 1}, ';
                            $require = '';
                        }
                        $temp_value = array();
                        foreach ($value['value'] as $key2 => $value2) {
                            if (!is_array($value2)) {
                                array_push($temp_value, '<label title="' . $error_message . '" class = "radio-inline" for="' . $key . $key2 . '"><input ' . $require . ' id="' . $key . $key2 . '" type = "radio" value = "' . $key2 . '" name = "' . $key . '"> ' . $value2 . '</label>');
                            } else {
                                foreach ($value2 as $key3 => $value3) {
                                    array_push($temp_value, '<label class = "">' . $key3 . ' <input type = "text" class = "input-small" name = "' . $key . '[' . $key2 . ']" value = "' . $value3 . '" placeholder = "' . @$value['placeholder'] . '"></label>');
                                }
                            }
                        }
                        array_push($resp, array($label, $temp_value));
                        break;
                    case 'checkbox':
                        $label = ('<label class="control-label" for="' . $key . '">' . @$value['label'] . '</label>');
                        if (!empty($require)) {
                            $resp['extra'] .= "'" . $key . "[]'" . ': {required: true,minlength: 1}, ';
                            $require = '';
                        }
                        $temp_value = array();
                        //var_dump($value['value']);
                        foreach ($value['value'] as $key2 => $value2) {
                            if (!is_array($value2)) {
                                array_push($temp_value, '<label class = "checkbox-inline" for="' . $key . $key2 . '"><input ' . $require . ' title="' . $error_message . '" id="' . $key . $key2 . '" type = "checkbox" value = "' . $key2 . '" name = "' . $key . '[]"> ' . $value2 . '</label>');
                            } else {
                                foreach ($value2 as $key3 => $value3) {
                                    //array_push($temp_value, '<label class = "">' . $key3 . ' <input type = "text" class = "input-small" name = "' . $key . '[]" value = "' . $value3 . '" placeholder = "' . @$value['placeholder'] . '"></label>');
                                }
                            }
                        }
                        array_push($resp, array($label, $temp_value));
                        break;
                    case 'select':
                        $label = ('<label ' . $require . ' class="control-label" for="' . $key . '">' . @$value['label'] . '</label>');
                        $string_value = '<select class="form-control" name = "' . $key . '" title="' . $error_message . '" ' . $require . ' >';

                        $string_value .= '<option value = "">' . @$value['placeholder'] . '</option>';
                        foreach ($value['value'] as $key2 => $value2) {

                            $string_value .= '<option value = "' . $key2 . '">' . $value2 . '</option>';
                        }
                        $string_value .= '</select>';
                        array_push($resp, array($label, $string_value));
                        break;
                }
            }
        } else {
            $resp = array();
        }

        return $resp;
    }

    /**
     * Mostrar datos
     *
     * @param array $array_forms_json Formularios de los que se van a sacar los datos para traducir los indices
     * @param array $array_display posiciones en el array $data que se traduciran. Si es nulo se traduciran todos
     * @param array $array_mod Arreglo clave => valor donde se colocará la función modificadora del valor
     * @return array $resp Valores obtenidos de una consulta en su estado ya traducido
     */
    function mostrarForm($array_forms_json, $data, $array_display = null, $array_mod = null) {

        $get_data = null;
        $resp = array();

        //$array_display = null;
        if (!empty($array_display)) {
            $get_data = (object) array();
            foreach ($array_display as $value) {
                foreach ($array_forms_json as $value2) {
                    if (isset($value2->$value)) {
                        $get_data->$value = $value2->$value;
                        break;
                        //array_push($get_data, $value2->$value);
                    }
                }
            }
        }

        if (empty($get_data)) {
            $get_data = array();
            foreach ($array_forms_json as $key => $value) {
                $value = (array) $value;
                foreach ($value as $key1 => $value1) {
                    $get_data[$key1] = $value1;
                }
            }
            $get_data = (object) $get_data;
        }
        //var_dump($data);
        //var_dump($get_data);

        foreach ($data as $key => $valor) {
            if (isset($get_data->$key)) {

                $value = (array) $get_data->$key;

                if (isset($value['table_out']) and isset($value['table_in'])) {
                    $value['value'] = (object) $this->get_all_from_table_form($value['table_out']);
                }

                switch ($value['type']) {
                    case 'text':
                    case 'number':
                    case 'numeric':
                    case 'integer':
                    case 'textarea':
                        if (is_object($valor)) {
                            ($resp[@$value['label']] = $valor->scalar);
                        } else {
                            ($resp[@$value['label']] = $valor);
                        }
                        if (isset($array_mod[$key])) {
                            foreach ($array_mod[$key] as $keya => $valuea) {
                                $resp[@$value['label']] = (call_user_func_array(array($this, $keya), array($resp[@$value['label']], $valuea[0], $valuea[1])));
                            }
                        }
                        break;
                    case 'radio':
                    case 'select':
                        if (is_object($valor)) {
                            $index = $valor->scalar;
                        } else {
                            $index = $valor;
                        }
                        if (isset($value['value']->$index)) {
                            ($resp[@$value['label']] = $value['value']->$index);
                        } else {
                            //var_dump( $index);
                        }

                        break;
                    case 'checkbox':
                        $temp_array = array();
                        //var_dump($valor);
                        //var_dump($value['value']);
                        foreach ($valor as $key => $value3) {
                            if (isset($value['value']->$value3)) {
                                array_push($temp_array, $value['value']->$value3);
                            } else {
                                //var_dump($key);
                                //var_dump($value['value']);
                                //var_dump($valor->$key);
                            }
                        }
                        $resp[@$value['label']] = $temp_array;
                        break;
                }
            }
        }

        return $resp;
    }

}
