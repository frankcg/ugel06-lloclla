<?php 
Class oficinaModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getoficinas(){
		$sql="SELECT o.`IDOFICINA`,o.`NOMBRE_OFICINA`,o.`ESTADO`,o.`DESCRIPCION`,a.`NOMBRE_AREA` FROM `oficina` o INNER JOIN `area` a ON o.`IDAREA`=a.`IDAREA`";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}


	public function getareas()
	{
		$sql="SELECT * from AREA";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}


	public function getoficina($idoficina){
		$sql="SELECT * FROM `oficina` WHERE IDOFICINA='$idoficina'";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addoficina($nombre, $status, $area, $descripcion){
		$sql="INSERT INTO `oficina` SET NOMBRE_oficina='$nombre',ESTADO = '$status', DESCRIPCION='$descripcion',IDAREA='$area'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;		
	}

	public function duplicidad($nombre){
		$sql="SELECT * FROM `oficina` WHERE NOMBRE_oficina ='$nombre'";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows)	
			return true;
		return false;
	}	

	
	public function updateoficina($idoficina, $nombre, $status, $area, $descripcion){
		$sql="UPDATE `oficina` SET NOMBRE_OFICINA='$nombre', ESTADO='$status', IDAREA='$area', DESCRIPCION='$descripcion' WHERE IDOFICINA = '$idoficina'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;

	}


}

?>