<?php 
Class usuarioModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	/* ********************************************************************************************************************************
														CONTROLLER INDEX
	******************************************************************************************************************************** */

	public function validactivo($user){
		$sql="SELECT * FROM USUARIO WHERE IDUSUARIO='$user' and ESTADO='0'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result->num_rows;
	}

	public function validUser($user,$pass){

		$sql="SELECT * FROM USUARIO WHERE IDUSUARIO='$user' AND CONTRASENIA=SHA1('$pass')";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result->num_rows;
	}	

	public function getNombre($user){
		$sql="SELECT CONCAT(b.AP_PATERNO,' ', b.AP_MATERNO,', ',NOMBRES) AS NOMBRE
			FROM `USUARIO` a INNER JOIN PERSONA b ON a.IDPERSONA=b.IDPERSONA
			WHERE IDUSUARIO = '$user'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		$reg="";
		if($result->num_rows)
			$reg=$result->fetch_object();
		return $reg->NOMBRE;
	}

	public function getFoto($user){
		$sql="SELECT `RUTA_IMG` FROM USUARIO WHERE IDUSUARIO='$user'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		$reg="";
		if($result->num_rows)
			$reg=$result->fetch_object();
		return $reg->RUTA_IMG;
	}

	public function getMenu($user){
		$data=array();

		$sql="SELECT d.`IDMODULO`, d.DESCRIPCION, d.TIPO, d.UBICACION  FROM usuario a INNER JOIN seguridad_perfil b ON a.IDPERFIL = b.IDPERFIL INNER JOIN `seguridad_modulo_perfil` c ON b.IDPERFIL=c.IDPERFIL INNER JOIN seguridad_modulo d ON d.IDMODULO=c.IDMODULO WHERE a.`IDUSUARIO` = '$user' AND a.ESTADO = '1'";

		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		while ($reg= $result->fetch_object()){
			$data["$reg->TIPO"]["$reg->IDMODULO"]= array('DESCRIPCION'=>$reg->DESCRIPCION,'UBICACION'=>$reg->UBICACION);
		}
		return $data;
	}

	/* ********************************************************************************************************************************
														CONTROLLER USUARIO
	******************************************************************************************************************************** */
	
	public function getoficina(){
		$sql="SELECT IDOFICINA, UPPER(NOMBRE_OFICINA) NOMBRE FROM OFICINA ORDER BY NOMBRE ASC";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}
	

	public function getusuarios(){
		$sql="SELECT a.IDUSUARIO, CONCAT(b.AP_PATERNO,' ', b.AP_MATERNO,',',NOMBRES) AS NOMBRE, b.IDOFICINA, c.NOMBRE_OFICINA, a.ESTADO
			FROM `USUARIO` a INNER JOIN PERSONA b ON a.IDPERSONA=b.IDPERSONA
			INNER JOIN `oficina` c ON b.IDOFICINA=c.IDOFICINA
			WHERE b.TIPOUSER = 'USER'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function existenciausuario($usuario){
		$sql="SELECT * FROM USUARIO WHERE IDUSUARIO='$usuario'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows)
			return '1';
		else
			return '0';
	}

	public function validdni($dni){
		$sql="SELECT * FROM `persona` WHERE DNI = '$dni'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows)
			return true;
		return false;
	}
	
	public function addusuario($usuario, $ape_pat, $ape_mat, $nombre, $dni, $email, $telefono, $oficina, $password, $status){

		$user=$_SESSION['user'];
		$sql="INSERT INTO `persona` SET NOMBRES='$nombre', `AP_PATERNO`='$ape_pat', AP_MATERNO='$ape_mat', `DNI`='$dni',
		 CORREO='$email', TELEFONO='$telefono', IDOFICINA='$oficina', TIPOUSER = 'USER', FECHACREACION = NOW(), IDUSUARIOCREACION = '$user'";
		$this->_db->query($sql)or die ('Error en '.$sql);

		$idpersona=$this->_db->insert_id;

		$sql2="INSERT INTO `usuario` SET IDUSUARIO = '$usuario', CONTRASENIA=SHA1('$password'), FECHACREACION = NOW(), IDUSUARIOCREACION = '$user', IDPERSONA='$idpersona'";
		$this->_db->query($sql2)or die ('Error en '.$sql2);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function getusuario($idusuario){
		$sql="SELECT a.IDUSUARIO, b.AP_PATERNO, b.AP_MATERNO, b.NOMBRES, b.DNI, b.TELEFONO, b.CORREO,b.IDOFICINA, 
				c.NOMBRE_OFICINA, a.ESTADO, a.CONTRASENIA
				FROM `USUARIO` a INNER JOIN PERSONA b ON a.IDPERSONA=b.IDPERSONA
				INNER JOIN `oficina` c ON b.IDOFICINA=c.IDOFICINA
				WHERE IDUSUARIO = '$idusuario'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function updateusuario($usuario, $ape_pat, $ape_mat, $nombre, $dni, $email, $telefono, $oficina, $password, $status){
		$user=$_SESSION['user'];

		$sql_validar="SELECT * FROM `usuario` WHERE IDUSUARIO = '$usuario' AND CONTRASENIA = '$password'";
		$result=$this->_db->query($sql_validar) or die ('Error en '.$sql_validar);		

		if(!$result->num_rows){
			$sql="UPDATE usuario SET CONTRASENIA = sha1('$password'),ESTADO='$status', IDUSUARIOMOD='$user' WHERE IDUSUARIO = '$usuario'";
			$result=$this->_db->query($sql) or die ('Error en '.$sql);
		}else{
			$sql="UPDATE USUARIO SET ESTADO='$status', IDUSUARIOMOD='$user' WHERE IDUSUARIO = '$usuario'";
			$this->_db->query($sql)or die ('Error en '.$sql);
		}		

		$sql2=" SELECT IDPERSONA FROM `USUARIO` WHERE IDUSUARIO = '$usuario'";
		$result = $this->_db->query($sql2)or die ('Error en '.$sql2);
			$reg = $result->fetch_object();
			$idpersona=$reg->IDPERSONA;

		$sql3="UPDATE PERSONA SET NOMBRES='$nombre', `AP_PATERNO`='$ape_pat', AP_MATERNO='$ape_mat', `DNI`='$dni', CORREO='$email', TELEFONO='$telefono', IDOFICINA='$oficina', IDUSUARIOMOD = '$user' WHERE `IDPERSONA` = '$idpersona'";
		$this->_db->query($sql3)or die ('Error en '.$sql3);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function delusuario($idusuario){
		$sql="UPDATE USUARIO SET ESTADO='0' WHERE IDUSUARIO='$idusuario'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	/* ********************************************************************************************************************************
																MODULO PERMISOS
	******************************************************************************************************************************** */

	public function getaccesosuser($idusuario){
		$sql="SELECT LECTURA, ESCRITURA, IDPERFIL FROM usuario WHERE IDUSUARIO = '$idusuario'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addpermisos($idusuario, $idperfil, $lectura, $escritura){
		$user=$_SESSION['user'];
		$sql="UPDATE `usuario` SET IDPERFIL = '$idperfil', LECTURA='$lectura', ESCRITURA = '$escritura', IDUSUARIOMOD='$user' WHERE IDUSUARIO = '$idusuario'";
		$this->_db->query($sql)or die ('Error en '.$sql);	
		
		if($this->_db->errno)
			return false;
		return true;
	}

	/* ********************************************************************************************************************************
																MODULO PERFIL
	******************************************************************************************************************************** */
	
	public function getprofiles(){
		$sql="SELECT p.IDPERFIL, p.NOMBRE_PERFIL, COUNT(s.IDPERFIL) CANTMODULOS, p.IDUSUARIOCREACION FROM `seguridad_perfil` p INNER JOIN `seguridad_modulo_perfil` s ON p.IDPERFIL=s.IDPERFIL GROUP BY p.IDPERFIL";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	//GENERAR BUG
	public function getcomoboperfil(){
		$sql="SELECT DISTINCT IDPERFIL, NOMBRE_PERFIL FROM seguridad_perfil";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getaccesoprofilenew(){
		$sql="SELECT IDMODULO, DESCRIPCION FROM `seguridad_modulo`";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getaccesoprofile($idperfil){
		$sql="SELECT * FROM `seguridad_perfil` WHERE IDPERFIL = '$idperfil'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getaccesoprofile2($idperfil){
		$sql="SELECT a.SELECTED, b.`IDMODULO`, b.DESCRIPCION FROM(			
				SELECT IDMODULO,'selected' SELECTED
				FROM `seguridad_modulo_perfil` 
				WHERE IDPERFIL = '$idperfil') AS a RIGHT JOIN seguridad_modulo b  ON a.IDMODULO = b.IDMODULO";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function verificarexistencianombreperfil($nomperfil){
		$sql="SELECT * FROM `seguridad_perfil` WHERE NOMBRE_PERFIL = '$nomperfil'";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result->num_rows;
	}

	public function addnombreperfil($nomperfil){
		$user=$_SESSION['user'];	
		//INSERTA A LA TABLA PERFIL
		$sql="INSERT INTO `seguridad_perfil` SET NOMBRE_PERFIL = '$nomperfil', IDUSUARIOCREACION='$user', FECHACREACION = NOW()";
		$this->_db->query($sql)or die ('Error en '.$sql);

		$idperfil=$this->_db->insert_id;

		return $idperfil;
	}

	public function addprofile($idmodulo, $idperfil){
	
		$sql="INSERT INTO `seguridad_modulo_perfil` SET IDMODULO='$idmodulo', IDPERFIL='$idperfil'";
		$this->_db->query($sql)or die ('Error en '.$sql);

		if($this->_db->errno)
			return false;
		return true;
	}	

	public function updateprofile($idmodulo, $idperfil){
		$user=$_SESSION['user'];
		
		$sql="INSERT INTO `seguridad_modulo_perfil` SET IDMODULO='$idmodulo', IDPERFIL='$idperfil'";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);

		if($this->_db->errno)
			return false;
		return true;
	}

	public function verificareliminacion($idperfil){
		$sql="SELECT * FROM `usuario` WHERE IDPERFIL = '$idperfil'";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result->num_rows;
	}

	public function deleteprofile($idperfil){
		$sql_del="DELETE FROM seguridad_modulo_perfil WHERE IDPERFIL = '$idperfil' ";
		$this->_db->query($sql_del)or die ('Error en '.$sql_del);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function deleteprofilename($idperfil){
		$sql_del="DELETE FROM seguridad_perfil WHERE IDPERFIL = '$idperfil' ";
		$this->_db->query($sql_del)or die ('Error en '.$sql_del);
		if($this->_db->errno)
			return false;
		return true;

	}


	/* *********************************************************
	PERMISO DE LECTURA Y ESCRITURA
	********************************************************** */

	public function getlectura($user){
		$sql="SELECT LECTURA FROM `usuario` WHERE IDUSUARIO ='$user'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		$reg="";
		if($result->num_rows)
			$reg=$result->fetch_object();
		return $reg->LECTURA;
	}
	
	public function getescritura($user){
		$sql="SELECT ESCRITURA FROM `usuario` WHERE IDUSUARIO ='$user'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		$reg="";
		if($result->num_rows)
			$reg=$result->fetch_object();
		return $reg->ESCRITURA;
	}
}

?>