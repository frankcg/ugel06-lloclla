<?php 
Class reportebajasModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getreportebaja($fechainicio, $fechafin){
		$sql= "SELECT b.NOMBRE, a.MARCA, a.MODELO, a.SERIE, IF(a.ESTADO='1','ACTIVO',IF(a.ESTADO='2','DAÑADO',IF(a.ESTADO='0','OBSOLETO','CADUCADO'))) AS ESTADO, a.CAPACIDAD,  a.IDUSUARIOMOD, DATE_FORMAT(a.FECHAMOD,'%d-%m-%Y') FECHABAJA, IF(a.DESCRIPCION='4','DONACION',IF(a.ESTADO='5','PERDIDO','RETIRO') ) AS DESCRIPCION
			from activo a inner join `componente` b on a.IDCOMPONENTE=b.IDCOMPONENTE
			where a.IDPERSONA = '0105' and IDINVENTARIO = (SELECT MAX(IDINVENTARIO) IDINVENTARIO FROM inventario)
			AND DATE(a.FECHAMOD) BETWEEN '$fechainicio' AND '$fechafin'";		
		$result = $this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function getnombreinventario($inventario1){

		$sql= "SELECT NOM_INVENTARIO, DATE_FORMAT(FECHAINICIO,'%d/%m/%Y') FECHAINICIO, DATE_FORMAT(FECHAFIN,'%d/%m/%Y') FECHAFIN from `inventario` where IDINVENTARIO = '$inventario1'";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;

	}


}

?>