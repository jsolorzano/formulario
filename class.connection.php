<?php
/*********************************************************************
    class.connection.php

    Connection to the database!

    José Solorzano <solorzano202009@gmail.com>
**********************************************************************/
define('HOST','localhost'); //AQUÍ VA EL HOST
define('USER','root');
define('PASS','123456');
define('DBNAME','contacto');

//Connection.
class Conexion {
	
    protected $conexion;
    protected $db;

    public function conectar()
    {
        $this->conexion = @mysql_connect(HOST, USER, PASS);
        if ($this->conexion == 0) DIE("Lo sentimos, no se ha podido conectar con MySQL: " . mysql_error());
        $this->db = mysql_select_db(DBNAME, $this->conexion);
        if ($this->db == 0) DIE("Lo sentimos, no se ha podido conectar con la base datos: " . DBNAME);
        $acentos = mysql_query("SET NAMES 'utf8'",$this->conexion);

        return true;

    }

    public function desconectar()
    {
        if ($this->conectar()) {
            mysql_close($this->conexion);
        }

    }
	
	// Método para registrar una consulta
    public function insert_query()
    {
        $table = "contacto";
        
        $insert = "INSERT INTO $table (name, email, query, d_create) ";
        $insert .= "VALUE('".$_POST['name']."', '".$_POST['email']."', '".$_POST['query']."', '".date("Y-m-d H:s:i")."')";
        
        $query = mysql_query($insert, $this->conexion);
		
		return $query;
		
    }
    
    
    // Método para obtener la url base del sistema	
	public function base_url()
	{
		
		$base_url = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
		
		if($_SERVER["SERVER_NAME"] == 'localhost'){
		
			$base_url .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . '/formulario/' : $_SERVER['SERVER_NAME'] . '/formulario/';
		
		}else{
			
			$base_url .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . '/' : $_SERVER['SERVER_NAME'] . '/';
			
		}
		
		return $base_url;
		
	}
    
}

?>
