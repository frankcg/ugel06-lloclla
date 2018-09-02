<?php 
Class panelModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getestadistica(){
		$sql=" select a.IDINVENTARIO, b.NOM_INVENTARIO, count(*) as TOTAL,
			(select COUNT(*) from activo z where z.IDINVENTARIO = a.`IDINVENTARIO` AND z.ESTADO = '1' ) as ACTIVO,
			(SELECT COUNT(*) FROM activo z WHERE z.IDINVENTARIO = a.`IDINVENTARIO` AND z.ESTADO = '2' ) AS DANADO,
			(SELECT COUNT(*) FROM activo z WHERE z.IDINVENTARIO = a.`IDINVENTARIO` AND z.ESTADO = '0' ) AS OBSOLETO,
			(SELECT COUNT(*) FROM activo z WHERE z.IDINVENTARIO = a.`IDINVENTARIO` AND z.ESTADO = '3' ) AS CADUCADO
			from activo a inner join inventario b on a.`IDINVENTARIO`=b.IDINVENTARIO
			group by a.IDINVENTARIO, b.NOM_INVENTARIO ";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;		
	}

	public function getstock(){
		$sql="SELECT a.IDCOMPONENTE, b.NOMBRE, COUNT(*) STOCK,
		(SELECT COUNT(*) FROM activo WHERE IDINVENTARIO = a.IDINVENTARIO AND IDCOMPONENTE = a.IDCOMPONENTE AND IDPERSONA IS NOT NULL AND IDACTIVO NOT IN (SELECT IDACTIVO FROM activo WHERE IDPERSONA = '0105') ) AS ASIGNADO,
		(SELECT COUNT(*) FROM activo WHERE IDINVENTARIO = a.IDINVENTARIO AND IDCOMPONENTE = a.IDCOMPONENTE AND IDPERSONA IS NULL  AND ESTADO = '1' AND IDACTIVO NOT IN (select IDACTIVO from activo where IDPERSONA = '0105') ) AS DISPONIBLES
		from activo a inner join `componente` b ON a.IDCOMPONENTE=b.IDCOMPONENTE
		where a.IDINVENTARIO = (SELECT MAX(IDINVENTARIO) IDINVENTARIO FROM inventario)
		AND a.IDACTIVO NOT IN (select IDACTIVO from activo where IDPERSONA = '0105')
		GROUP BY a.IDCOMPONENTE ORDER BY b.NOMBRE";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;		
	}
	
	
}

?>