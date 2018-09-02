<?php 
Class personalModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getpersonales(){
		$sql="SELECT b.IDPERSONA, CONCAT(b.AP_PATERNO,' ',b.AP_MATERNO,' ',b.NOMBRES) NOMBRE, b.DNI,b.CORREO,b.TELEFONO, b.IDOFICINA, c.NOMBRE_OFICINA, b.ESTADO,
	(SELECT COUNT(*) FROM `activo` WHERE `IDPERSONA` = b.IDPERSONA AND IDINVENTARIO = (SELECT MAX(IDINVENTARIO) IDINVENTARIO FROM inventario) ) CANT
		FROM PERSONA b 	INNER JOIN `oficina` c ON b.IDOFICINA=c.IDOFICINA";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addpersonal($ape_pat, $ape_mat, $nombre, $dni,$email, $telefono, $oficina, $status){
		$user = $_SESSION['user'];
		$sql="INSERT INTO `persona` SET NOMBRES='$nombre', `AP_PATERNO`='$ape_pat', AP_MATERNO='$ape_mat', `DNI`='$dni',CORREO='$email',TELEFONO='$telefono',
		 IDOFICINA='$oficina', ESTADO = '$status',IDUSUARIOCREACION='$user',FECHACREACION=NOW() ";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;		
	}

	public function duplicidad($dni){
		$sql="SELECT * FROM `persona` WHERE TIPOUSER = 'PERS' AND DNI='$dni'";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows)	
			return true;
		return false;
	}	

	public function getpersonal($codpersona){

		$sql="SELECT * FROM `persona` WHERE  IDPERSONA='$codpersona'";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function updatepersonal($idpersona, $ape_pat, $ape_mat, $nombre, $dni,$email, $telefono, $oficina, $status){

		$user = $_SESSION['user'];
		$sql="UPDATE `persona` SET NOMBRES='$nombre', `AP_PATERNO`='$ape_pat', AP_MATERNO='$ape_mat',CORREO='$email',TELEFONO='$telefono',
		 IDOFICINA='$oficina', ESTADO = '$status', IDUSUARIOMOD='$user' WHERE IDPERSONA = '$idpersona'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;

	}


}

?>