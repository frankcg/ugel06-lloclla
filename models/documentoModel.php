<?php 
Class documentoModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getdocumentos(){
		$sql="SELECT d.`IDDOCUMENTO`, CONCAT(p.AP_PATERNO,' ', p.AP_MATERNO,', ',NOMBRES) AS NOMBRE, d.`TIPO`,d.`DESCRIPCION`,d.`ADJUNTO`,d.`IDUSUARIOCREACION`,d.`FECHACREACION` FROM `documento` d  INNER JOIN `persona` p ON d.`IDPERSONA`=p.`IDPERSONA`";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function adddocumento($nombre,$tipodoc, $descripcion,$nombre_file, $destino, $extension, $size){
		$user = $_SESSION['user'];
		$sql="INSERT INTO documento SET IDPERSONA='$nombre',TIPO='$tipodoc',DESCRIPCION='$descripcion', ADJUNTO = '$destino', 
			NOMBRE_DOC = '$nombre_file', MIME = '$extension', SIZE = '$size', IDUSUARIOCREACION='$user',FECHACREACION=NOW()";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function getpersonal(){
		$sql="SELECT p.`IDPERSONA`, CONCAT(p.AP_PATERNO,' ', p.AP_MATERNO,' ',NOMBRES) AS NOMBRE FROM `persona` p ORDER BY NOMBRE ";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getdocumento($iddocumento){
		$sql="SELECT * FROM `documento` WHERE IDDOCUMENTO='$iddocumento'";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function updatedocumento($iddocumento, $persona,$tipodoc, $descripcion){

			$sql="UPDATE documento SET IDPERSONA='$persona',TIPO='$tipodoc',DESCRIPCION='$descripcion', IDUSUARIOMOD='$user',FECHAMOD=NOW() WHERE IDDOCUMENTO='$iddocumento'";
		

		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;

	}

	public function updatedocumentodigital($iddocumento,$nombre_file, $destino, $extension, $size){

			$sql="UPDATE documento SET  NOMBRE_DOC = '$nombre_file', ADJUNTO = '$destino', MIME = '$extension', SIZE = '$size', IDUSUARIOMOD='$user',FECHAMOD=NOW() WHERE IDDOCUMENTO='$iddocumento'";
		

		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;

	}
	


}

?>