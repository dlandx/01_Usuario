<?php

class BD {
    // Abributos Conectar con la BD
    private $con;
    private $error; // En caso de error
    private $host;
    private $user ;
    private $pass;
    private $bd;
    
    /**
     * Constructor de la clase, instancia los atributos...
     * @param type $h (string) host de la BBDD...
     * @param type $u (string) usuarios de la BBDD...
     * @param type $p (string) contraseña de la BBDD...
     * @param type $bd (string) nombre de la BBDD...
     */
    public function __construct($h="172.17.0.2", $u="root", $p="root", $bd="dwes") {
        $this->host = $h;
        $this->user = $u;
        $this->pass = $p;
        $this->bd = $bd;
        // Funcion conexion() -> conectar con la BBDD
        $this->con = $this->conexion();        
    }
    
    /**
     * Conectar con la BD o informar del error...
     * @return \mysqli, Retornar conexion con la BD
     */
    private function conexion(): mysqli { //: mysqli -> El tipo que devuelve...
        $con = new mysqli($this->host, $this->user, $this->pass, $this->bd);
        // Si no se puede conectar obtenemos el error producido...
        if ($con->connect_errno) {
            $this->error = "Se produjo un error en la conexion: <b>".$con->connect_error."</b>";
        }
        $con->set_charset("utf8"); //Establecer el conjunto de caracteres del cliente... UTF-8
        return $con;
    }
    
    /**
     * Realizar sentencia en la BD
     * @param type $sql (string) Sentencia a realizar
     * @return string
     */
    public function select($sql) {
        $tupla = [];
        // Si se pierde la conexion, volvemos a conectar...
        if ($this->con == null) {
            $this->con = $this->conexion();
        }        
        // Preparar la consulta SQL...
        $result = $this->con->query($sql);         
        
        //var_dump($result);
        // Sin errores ADD array los datos de la BD...
        while ($fila = $result->fetch_row()) {
            // ADD al array asociativo los valores de la BD...
            $tupla[] = $fila;
        }
        return $tupla;
    }
    
    /**
     * Funcion que cierra la conexion con la BBBDD...
     */
    public function close() {
        $this->con->close();
    }

    /**
     * Funcion que obtiene datos de la BBDD como el nombre de la columna...
     * @param string $tabla (string), Nombre de la tabla para obtener los campos
     * @return array (indexado) Nombre de los campos de la tabla BD...
     */
    public function nombres_campos($tabla): array { //strint $tabla
        $campos = [];        
        $consulta = "SELECT * FROM $tabla";
        $r = $this->con->query($consulta);
        $obj = $r->fetch_fields(); // Array de objetos de cada columna
        
        foreach ($obj as $value) {
            $campos[] = $value->name;
        }        
        return $campos;
    }
    
    /**
     * Funcion que escapa los caracteres especiales de una cadena para usarla en una sentencia SQL...
     * @param type $user (string), cadena que vamos a insertar en estaso el usuario ingresado...
     * @return string, retorna la cadena con caracteres especiales 'los escapa'...
     */
    public function caracteres($cad): string {
        // Para insertar en la BBDD caracteres especiales...
        // Por ejemplo Hola'Mundo -> al insertar comilla simple no detenga la SQL...
        return $this->con->real_escape_string($cad); // Caracteres especiales;
    }

    /**
     * Funcion que inserta los datos en la BBDD...
     * @param type $sql (string), Sentencia SQL a realizar...
     * @return string, retorna el resultado se a insertado correctamente o error...
     */
    public function insert($sql): string {
        $info = "";
        // Si se pierde la conexion, volvemos a conectar...
        if ($this->con == null) {
            $this->con = $this->conexion();
        }
        
        // Preparar la consulta SQL...
        $result = $this->con->query($sql);
        
        // Error al insertar duplicado (HTTP ERROR 500)
        if ($result === false) {
            $info = "<b>Se produjo un error:</b> ".$this->con->error; 
        } else {
            $info = "<b>El usuario se ha insertado correctamente...</b>";
        }
        
        return $info;
    }

    /**
     * Funcion para validar que el usuario este en la BBDD...
     * @param type $user (string) usuario ingresado...
     * @param type $pass (string) contraseña ingresada...
     * @return boolean, informa el exito de la operacion (si el user NO esta en la BD o SI=false)
     */
    public function existe($user, $pass) {
        $info = "El usuario no esta registrado en la BD...";
        // Si se pierde la conexion, volvemos a conectar...
        if ($this->con == null) {
            $this->con = $this->conexion();
        }        
        
        $sql = "SELECT * FROM usuarios WHERE nombre='$user'"; // nombre='$user' AND password='$pass' 
        $usuario = $this->select($sql);

        if (!empty($usuario)) { // Si $usuario=empty -> user no esta en la BD...
            // El $usuario tiene datos y es un array indexado, obtenemos los datos...
            $p= md5("$pass");
            if ($usuario[0][1] == $p && $usuario[0][0] == stripslashes($user)){ // stripslashes(str) Quita las barras de un string con comillas escapadas
                $info = false;
            }
        }
        return $info;
        
        /*
        // Solucion...
        $sql = "SELECT * FROM usuarios WHERE nombre='$user'AND password='$pass'";
        $r = $this->con->query($consulta);
        if ($r->num_rows > 0){
            return true;
        }
        return false;
        */
    }
}
