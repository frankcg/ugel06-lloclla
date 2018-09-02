<?php 
Class profileModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getprofile(){
		$user=$_SESSION['user'];
		$sql="SELECT a.IDUSUARIO, a.ESTADO, DATE_FORMAT(a.FECHACREACION,'%d-%m-%Y') AS FECHACREACION, a.IDPERFIL,a.RUTA_IMG, a.CONTRASENIA,CONCAT(b.AP_PATERNO,' ', b.AP_MATERNO,' ',b.NOMBRES) AS NOMBRE, b.DNI, b.CORREO,b.TELEFONO, c.NOMBRE_OFICINA, d.NOMBRE_AREA 
				FROM `usuario` a INNER JOIN `persona` b ON a.IDPERSONA=b.IDPERSONA 
				INNER JOIN `oficina` c ON b.IDOFICINA=c.IDOFICINA 
				INNER JOIN `area` d ON c.IDAREA=d.IDAREA 
				WHERE a.IDUSUARIO = '$user'";
		$result=$this->_db->query($sql) or die ('Error en '.$sql);
		$reg= null;
		if($result->num_rows)
			$reg=$result->fetch_object();
		return $reg;
	}
	

	public function updateprofile($password){
		$user=$_SESSION['user'];
		
		$sql_validar="SELECT * FROM `usuario` WHERE IDUSUARIO = '$user' AND CONTRASENIA = '$password'";
		$result=$this->_db->query($sql_validar) or die ('Error en '.$sql_validar);		

		if(!$result->num_rows){
			$sql="UPDATE usuario SET CONTRASENIA = sha1('$password') WHERE IDUSUARIO = '$user'";
			$result=$this->_db->query($sql) or die ('Error en '.$sql);
		}

		if($this->_db->errno)
			return false;
		return true;	
	}

	public function updatefoto($nombre_file){
		$user=$_SESSION['user'];
		$sql2="SELECT `RUTA_IMG` FROM usuario WHERE IDUSUARIO='$user' and RUTA_IMG is not null";
		$result=$this->_db->query($sql2) or die ('Error en '.$sql2);
		if($result->num_rows){
			$reg = $result->fetch_object();	
			unlink('public/img/profiles/'.$reg->RUTA_IMG);
		}

		$sql="UPDATE usuario SET `RUTA_IMG` = '$nombre_file' WHERE IDUSUARIO = '$user'";
		$this->_db->query($sql) or die ('Error en '.$sql);
		
		if($this->_db->errno)
			return false;
		return true;
	}

}


?>