<?php 

Class exactitudModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getinventario(){
		$sql= "SELECT IDINVENTARIO, NOM_INVENTARIO FROM inventario";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function exactitud($inventario1, $inventario2){
		$sql= "SELECT a.*, (IFNULL(a.CANT1, 0) - IFNULL(a.CANT2, 0)) AS DIFERENCIA, 
				CONCAT(ROUND((( (IFNULL(a.CANT1, 0) - IFNULL(a.CANT2, 0))  / IFNULL(a.CANT1, 0)) * 100),2),'%') AS PORCENTAJE
				 FROM(
				SELECT * FROM
				(SELECT a.IDCOMPONENTE IDCOMPONENTE1,COUNT(*) CANT1, (SELECT z.NOMBRE FROM `componente` z WHERE z.IDCOMPONENTE = a.IDCOMPONENTE  ) AS NOMBRE1 FROM ACTIVO a WHERE a.IDINVENTARIO = '$inventario1' GROUP BY a.IDCOMPONENTE) AS a
				LEFT OUTER JOIN 
				(SELECT a.IDCOMPONENTE IDCOMPONENTE2,COUNT(*) CANT2, (SELECT z.NOMBRE FROM `componente` z WHERE z.IDCOMPONENTE = a.IDCOMPONENTE  ) AS NOMBRE2 FROM ACTIVO a WHERE a.IDINVENTARIO = '$inventario2' GROUP BY a.IDCOMPONENTE) AS b
				ON a.IDCOMPONENTE1 = b.IDCOMPONENTE2
				UNION ALL
				SELECT * FROM
				(SELECT a.IDCOMPONENTE ,COUNT(*) CANT1, (SELECT z.NOMBRE FROM `componente` z WHERE z.IDCOMPONENTE = a.IDCOMPONENTE  ) AS NOMBRE1 FROM ACTIVO a WHERE a.IDINVENTARIO = '$inventario1' GROUP BY a.IDCOMPONENTE) AS a
				RIGHT OUTER JOIN 
				(SELECT a.IDCOMPONENTE,COUNT(*) CANT2, (SELECT z.NOMBRE FROM `componente` z WHERE z.IDCOMPONENTE = a.IDCOMPONENTE  ) AS NOMBRE2  FROM ACTIVO a WHERE a.IDINVENTARIO = '$inventario2' GROUP BY a.IDCOMPONENTE) AS b
				ON a.IDCOMPONENTE = b.IDCOMPONENTE
				WHERE a.IDCOMPONENTE IS NULL
				) AS a ";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;


	}

	/*public function exactitud($inventario1, $inventario2){
		$sql= "SELECT * FROM
			(SELECT a.IDCOMPONENTE,COUNT(*) CANT1, (SELECT z.NOMBRE FROM `componente` z WHERE z.IDCOMPONENTE = a.IDCOMPONENTE  ) AS NOMBRE1 FROM ACTIVO a WHERE a.IDINVENTARIO = '$inventario1' GROUP BY a.IDCOMPONENTE) AS a
			LEFT OUTER JOIN 
			(SELECT a.IDCOMPONENTE,COUNT(*) CANT2, (SELECT z.NOMBRE FROM `componente` z WHERE z.IDCOMPONENTE = a.IDCOMPONENTE  ) AS NOMBRE2 FROM ACTIVO a WHERE a.IDINVENTARIO = '$inventario2' GROUP BY a.IDCOMPONENTE) AS b
			ON a.IDCOMPONENTE = b.IDCOMPONENTE
			UNION ALL
			SELECT * FROM
			(SELECT a.IDCOMPONENTE,COUNT(*) CANT1, (SELECT z.NOMBRE FROM `componente` z WHERE z.IDCOMPONENTE = a.IDCOMPONENTE  ) AS NOMBRE1 FROM ACTIVO a WHERE a.IDINVENTARIO = '$inventario1' GROUP BY a.IDCOMPONENTE) AS a
			RIGHT OUTER JOIN 
			(SELECT a.IDCOMPONENTE,COUNT(*) CANT2, (SELECT z.NOMBRE FROM `componente` z WHERE z.IDCOMPONENTE = a.IDCOMPONENTE  ) AS NOMBRE2  FROM ACTIVO a WHERE a.IDINVENTARIO = '$inventario2' GROUP BY a.IDCOMPONENTE) AS b
			ON a.IDCOMPONENTE = b.IDCOMPONENTE
			WHERE a.IDCOMPONENTE IS NULL";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}*/

	public function getnombre($inventario1, $inventario2){

		$sql= "SELECT a.*, b.IDINVENTARIO IDINVENTARIO2, b.NOM_INVENTARIO NOM_INVENTARIO2 FROM(
		SELECT IDINVENTARIO IDINVENTARIO1, NOM_INVENTARIO NOM_INVENTARIO1
		FROM inventario WHERE IDINVENTARIO  = '$inventario1'
		) AS a , inventario b WHERE b.IDINVENTARIO  = '$inventario2'";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function getdiferencia($inventario1, $inventario2){
		//GET IDACTIVO
			$sql=" SELECT B.SERIE, B.IDACTIVO
			FROM ACTIVO B
			WHERE B.IDINVENTARIO = $inventario2 
			AND B.SERIE NOT IN (SELECT A.SERIE FROM ACTIVO A WHERE A.IDINVENTARIO = $inventario1)
			UNION ALL
			SELECT B.SERIE, B.IDACTIVO
			FROM ACTIVO B
			WHERE B.IDINVENTARIO = $inventario1
			AND B.SERIE NOT IN (SELECT A.SERIE FROM ACTIVO A WHERE A.IDINVENTARIO = $inventario2);
			";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		while($reg = $result->fetch_object()){
			$array_idactivo[] = $reg->IDACTIVO;
		}
		$array = join($array_idactivo,',');
		
		$sql2="SELECT a.IDACTIVO,b.NOMBRE NOMACTIVO,a.MARCA,a.MODELO ,a.SERIE,a.ESTADO,a.IDPATRIMONIO, CONCAT(c.NOM_INVENTARIO,' - ',DATE_FORMAT(c.FECHACREACION,'%d-%m-%Y')) NOMBREINVENTARIO
			FROM `activo` a INNER JOIN `componente` b ON a.IDCOMPONENTE=b.IDCOMPONENTE
			INNER JOIN `inventario` c ON a.IDINVENTARIO=c.IDINVENTARIO
			WHERE a.IDACTIVO in ($array)";
			//echo $sql2; exit();
		$result2=$this->_db->query($sql2) or die('Error en'.$sql2);
		return $result2;
	}

	public function getnombreinventario($inventario1){

		$sql= "SELECT NOM_INVENTARIO, DATE_FORMAT(FECHAINICIO,'%d/%m/%Y') FECHAINICIO, DATE_FORMAT(FECHAFIN,'%d/%m/%Y') FECHAFIN from `inventario` where IDINVENTARIO = '$inventario1'";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;

	}

}

?>
