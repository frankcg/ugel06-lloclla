<?php 
Class areaModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getareas(){
		$sql="SELECT * FROM `area`";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}


	public function getarea($idarea){
		$sql="SELECT * FROM `area` WHERE IDAREA='$idarea'";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addarea($nombre, $status, $descripcion){
		$sql="INSERT INTO `area` SET NOMBRE_AREA='$nombre',ESTADO = '$status', DESCRIPCION='$descripcion'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;		
	}

	public function duplicidad($nombre){
		$sql="SELECT * FROM `area` WHERE NOMBRE_AREA ='$nombre'";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows)	
			return true;
		return false;
	}	

	
	public function updatearea($idarea, $nombre, $status,$descripcion){
		$sql="UPDATE `area` SET NOMBRE_AREA='$nombre', ESTADO = '$status', DESCRIPCION='$descripcion' WHERE IDAREA = '$idarea'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;

	}


}

?>