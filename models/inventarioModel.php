<?php 
Class inventarioModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getinventarios(){
		$sql= "SELECT IDINVENTARIO, NOM_INVENTARIO, FECHAINICIO, FECHAFIN, ESTADO, DESCRIPCION, IDUSUARIOCREACION FROM inventario";		
		$result = $this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function validarinventario(){
		$sql= "SELECT * FROM inventario WHERE ESTADO = '1'";		
		$result = $this->_db->query($sql) or die('Error en'.$sql);
		return $result->num_rows;
	}

	public function validarnomduplicado2($nombre){
		$sql= "SELECT * FROM inventario WHERE NOM_INVENTARIO = '$nombre' and ESTADO = '0'";		
		$result = $this->_db->query($sql) or die('Error en'.$sql);
		return $result->num_rows;
	}

	public function validarnomduplicado($nombre){
		$sql= "SELECT * FROM inventario WHERE NOM_INVENTARIO = '$nombre'";		
		$result = $this->_db->query($sql) or die('Error en'.$sql);
		return $result->num_rows;
	}

	public function addinventario($nombre, $fechainicio,$descripcion){
		$user = $_SESSION['user'];
		$sql= "INSERT INTO inventario SET NOM_INVENTARIO='$nombre', FECHAINICIO = '$fechainicio', ESTADO= '1', DESCRIPCION= '$descripcion', FECHACREACION = NOW(), IDUSUARIOCREACION='$user'";		
		$this->_db->query($sql) or die('Error en'.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function getinventario($idinventario){
		$sql= "SELECT * FROM inventario WHERE IDINVENTARIO = '$idinventario'";		
		$result = $this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function conluirinventario($idinventario){
		$user = $_SESSION['user'];
		$sql= "UPDATE inventario SET ESTADO = '0', FECHAFIN = NOW(), IDUSUARIOMOD = '$user' WHERE IDINVENTARIO = '$idinventario'";		
		$result = $this->_db->query($sql) or die('Error en'.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public  function updateinventario($nombre, $descripcion, $fechainicio, $idinventario){
		$user = $_SESSION['user'];
		$sql= "UPDATE inventario SET NOM_INVENTARIO='$nombre', DESCRIPCION='$descripcion', FECHAINICIO='$fechainicio', IDUSUARIOMOD='$user' WHERE IDINVENTARIO='$idinventario'";		
		$result = $this->_db->query($sql) or die('Error en'.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

}


?>