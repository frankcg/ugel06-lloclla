<?php 
Class vejezModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getvejez($idinventario){
		$sql= "SELECT a.*, (a.DANADO + a.OBSOLETO + a.VENCIDO + a.ACTIVO) AS TOTAL,
				CONCAT(ROUND((((a.DANADO + a.OBSOLETO + a.VENCIDO ) / (a.DANADO + a.OBSOLETO + a.VENCIDO + a.ACTIVO)) * 100 ),2),'%') AS INDICADOR
				FROM(
				SELECT DISTINCT a.IDINVENTARIO, a.IDCOMPONENTE, (SELECT z.NOMBRE FROM `componente` z WHERE z.IDCOMPONENTE = a.IDCOMPONENTE) AS NOMBRE,
				(SELECT COUNT(*) FROM activo z WHERE z.`IDCOMPONENTE`=a.IDCOMPONENTE AND z.`IDINVENTARIO`=a.IDINVENTARIO AND z.`ESTADO` ='2' ) DANADO,
				(SELECT COUNT(*) FROM activo z WHERE z.`IDCOMPONENTE`=a.IDCOMPONENTE AND z.`IDINVENTARIO`=a.IDINVENTARIO AND z.`ESTADO` ='0' ) OBSOLETO,
				(SELECT COUNT(*) FROM activo z WHERE z.`IDCOMPONENTE`=a.IDCOMPONENTE AND z.`IDINVENTARIO`=a.IDINVENTARIO AND z.`ESTADO` ='3' ) VENCIDO,
				(SELECT COUNT(*) FROM activo z WHERE z.`IDCOMPONENTE`=a.IDCOMPONENTE AND z.`IDINVENTARIO`=a.IDINVENTARIO AND z.`ESTADO` ='1' ) ACTIVO
				FROM activo a
				WHERE a.IDINVENTARIO ='$idinventario'
				) AS a ";		
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