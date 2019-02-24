<?php

/**
 * Clase que contendra la validacion al ingresar en el login, resultado en tabla...
 */

class Funcion {
    
    /**
     * Constructor de la clase...
     */
    public function __construct() {        
    }
        
    /**
     * Funcion donde validamos los datos ingresados por el login...
     * @param type $user (String), recibimos el usuario ingresado...
     * @param type $pass (String), obtenermos la contraseña ingresada...
     * @return type (String | Bool), retornamos el resultado obtenido...
     */
    public function validarLogin($user, $pass) {
        $info = false;
        // Si el usuario no ha ingresado datos que lo notifique...
        if ($user == "" || $pass == ""){  
            $info = ($user == "") ? "Ingrese el usuario" : "Ingrese la contraseña";
        }        
        return $info;
    }
    
    /**
     * Funcion para mostrar el titulo 'nombre de las columnas BD' en la tabla <thead>...
     * @param type $datos (array) Vector con los datos de la tabla BD 'nombre de las columnas'
     * @return string, retorna th de la tabla (Titulo de la tabla)
     */
    public function tableHead($datos): string {
        $info = "";
        foreach ($datos as $value) {
            $info .= "<th>$value[0]</th>";
        }
        return $info;
    }
    
    /**
     * Funcion que retorna el <tbody> de la tabla 'Datos de cada tupla de la BD'...
     * @param type $datos (array), vector que contiene info de cada tupla de la BBDD...
     * @return string, retorna td 'fila' de la tabla (info de cada tupla BD)
     */
    public function tableBody($datos): string {
        $info = "";
        foreach ($datos as $value) {
            $info .= "<tr><td>$value[0]</td>"
                . "<td>$value[1]</td></tr>";
        }
        return $info;
    }
}
