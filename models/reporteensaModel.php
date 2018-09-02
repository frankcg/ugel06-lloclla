<?php 
Class reporteensaModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	
	public function getreporteensa($tipo,$fechainicio, $fechafin){ 

		if($tipo =='1')
		{
		
			$sql= "SELECT c.NOMBRE,b.IDACTIVO,b.MARCA,b.SERIE,b.IDPERSONA,d.IDPERSONA,CONCAT(d.AP_PATERNO,' ',d.AP_MATERNO,' ',d.NOMBRES) NOMBRES,b.ESTADO,b.`IDUSUARIOCREACION`,b.`FECHACREACION`,b.`IDINVENTARIO`,e.IDINVENTARIO,e.NOM_INVENTARIO,e.FECHAINICIO,e.FECHAFIN
						FROM `activo` b
						INNER JOIN `componente` c ON b.IDCOMPONENTE=c.IDCOMPONENTE
						LEFT JOIN `persona` d ON b.IDPERSONA=d.IDPERSONA
						INNER JOIN `inventario` e ON  b.IDINVENTARIO=e.IDINVENTARIO
						WHERE DATE(b.`FECHACREACION`) BETWEEN '$fechainicio' AND '$fechafin'";	
		}
		else
		{
			$sql="";
		}
		
		
		$result = $this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	 }

	
}

?>