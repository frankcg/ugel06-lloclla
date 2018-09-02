<?php 
Class activoModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getcomponente(){
		$sql= "SELECT * FROM componente ORDER BY NOMBRE";
		//ejecuta el query
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function getinventario(){
		$sql= "SELECT IDINVENTARIO, NOM_INVENTARIO FROM inventario WHERE IDINVENTARIO != (SELECT MAX(IDINVENTARIO) IDINVENTARIO FROM inventario)";
		//ejecuta el query
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function getactivos(){
		$sql= "SELECT a.IDACTIVO,b.NOMBRE NOMACTIVO,a.MARCA,a.SERIE,a.MODELO,a.ESTADO,a.CAPACIDAD,CONCAT(`AP_PATERNO`,' ',`AP_MATERNO`,' ',NOMBRES) PERSONAL, o.`NOMBRE_OFICINA`, a.IDPATRIMONIO, IF(a.IDPERSONA IS NULL, 'NO', 'SI' )AS ASIGNADO, CONCAT(c.NOM_INVENTARIO,' - ',DATE_FORMAT(c.FECHACREACION,'%d-%m-%Y')) NOMBREINVENTARIO
			FROM `activo` a 
			INNER JOIN `componente` b ON a.IDCOMPONENTE=b.IDCOMPONENTE
			INNER JOIN `inventario` c ON a.IDINVENTARIO=c.IDINVENTARIO
			LEFT JOIN `persona`    p ON a.`IDPERSONA`=p.`IDPERSONA`
			LEFT JOIN `oficina` o ON p.`IDOFICINA`=o.`IDOFICINA`
			WHERE a.IDINVENTARIO = (SELECT MAX(IDINVENTARIO) IDINVENTARIO FROM inventario)
			AND a.IDACTIVO NOT IN (SELECT IDACTIVO FROM activo WHERE IDPERSONA = '0105')
			ORDER BY b.NOMBRE ASC";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function verificarexistenciainventario(){
		$sql= "SELECT * FROM `inventario` WHERE ESTADO = '1'";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function verificarserie($serie){
		$sql= "SELECT * FROM activo WHERE IDINVENTARIO = (SELECT MAX(IDINVENTARIO) IDINVENTARIO FROM inventario) AND SERIE = '$serie'";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result->num_rows;
	}

	public function validarpenultimaserie($serie){
		$sql= "SELECT * FROM ACTIVO WHERE IDINVENTARIO = (SELECT MAX(IDINVENTARIO) FROM `inventario` WHERE IDINVENTARIO <> (SELECT MAX(IDINVENTARIO) FROM inventario)) AND SERIE = '$serie'";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result->num_rows;
	}

	public function addactivo($componente,$modelo,$marca,$serie,$capacidad,$status,$caducidad,$tipo,$medioingreso,$codingreso,$patrimonio,$descripcion, $idinventario){
		$user = $_SESSION['user'];
		$sql= "INSERT INTO ACTIVO SET IDCOMPONENTE='$componente',MODELO='$modelo',MARCA='$marca',SERIE='$serie',CAPACIDAD='$capacidad', ESTADO='$status', CADUCIDAD='$caducidad', TIPO='$tipo', MEDIOINGRESO = '$medioingreso',CODINGRESO='$codingreso', IDPATRIMONIO='$patrimonio', DESCRIPCION='$descripcion', FECHACREACION = NOW(), IDUSUARIOCREACION = '$user', IDINVENTARIO = '$idinventario'";
		$this->_db->query($sql) or die('Error en'.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function getactivo($idactivo){
		$sql="SELECT * FROM activo WHERE IDACTIVO = '$idactivo'";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function updateactivo($idactivo,$componente, $modelo, $marca, $serie, $capacidad, $status, $caducidad, $tipo, $medioingreso,$codingreso, $patrimonio, $descripcion){
		$user = $_SESSION['user'];
		$sql="UPDATE activo SET IDCOMPONENTE='$componente', MODELO='$modelo', MARCA='$marca', SERIE='$serie', CAPACIDAD='$capacidad', ESTADO='$status', CADUCIDAD='$caducidad', TIPO='$tipo', MEDIOINGRESO='$medioingreso', CODINGRESO='$codingreso', IDPATRIMONIO='$patrimonio', DESCRIPCION='$descripcion', IDUSUARIOMOD='$user',FECHAMOD = NOW() WHERE IDACTIVO='$idactivo'";
		$this->_db->query($sql) or die('Error en'.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	//ASGINAMIENTO DE ACTIVOS
	//
	public function getpersonal(){
		$sql="SELECT IDPERSONA, CONCAT(AP_PATERNO,' ',AP_MATERNO,' ',NOMBRES) NOMBRE FROM `persona` WHERE `ESTADO` = '1' order by NOMBRE";
		$result = $this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function verificarasignacion($idactivo){
		$sql="SELECT * FROM activo WHERE IDACTIVO = '$idactivo' AND IDPERSONA IS NOT NULL";
		$result = $this->_db->query($sql) or die('Error en'.$sql);
		return $result->num_rows;
	}

	public function asignaractivo($idactivo,$idpersona, $componente, $idinventario){

		$user = $_SESSION['user'];

		//log de activo
		$sql_log="INSERT INTO bitacora_activo SET IDACTIVO='$idactivo',ESTADO_ACTIVO=1, IDINVENTARIO = '$idinventario', 
		IDCOMPONENTE = '$componente', TIPOACTA='ENT', IDPERSONA='$idpersona', IDUSUARIOCREACION='$user', FECHACREACION=NOW()";
		$this->_db->query($sql_log) or die('Error en'.$sql_log);

		$sql="UPDATE `ACTIVO` SET IDPERSONA = '$idpersona' WHERE IDACTIVO = '$idactivo'";
		$this->_db->query($sql) or die('Error en'.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	/* ***********************************************************************************
							ACTIVOS DE LOS INVENTARIOS HISTORICOS
	************************************************************************************** */

	public function getactivos_historicos($idinventario){
		$sql= "SELECT a.IDACTIVO,b.NOMBRE NOMACTIVO,a.MARCA,a.MODELO ,a.SERIE,a.ESTADO,CONCAT(`AP_PATERNO`,' ',`AP_MATERNO`,' ',NOMBRES) PERSONAL,a.IDPATRIMONIO, A.`IDUSUARIOCREACION`, A.`FECHACREACION`
			FROM `activo` a INNER JOIN `componente` b ON a.IDCOMPONENTE=b.IDCOMPONENTE
			INNER JOIN `inventario` c ON a.IDINVENTARIO=c.IDINVENTARIO
			INNER JOIN `persona` p on a.`IDPERSONA`=p.`IDPERSONA`
			WHERE c.IDINVENTARIO = '$idinventario'";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	/* ***********************************************************************************
							TRANSFERENCIA DE ACTIVOS
	************************************************************************************** */

	public function getactivos_penultimo($idpersona){

		if($idpersona == 'nada'){ $var = "AND a.IDPERSONA IS NULL";  } else {  $var = "AND a.IDPERSONA = '$idpersona'";}

		$sql= "SELECT a.IDACTIVO,b.NOMBRE NOMACTIVO,a.MARCA,a.MODELO ,a.SERIE,a.ESTADO,a.IDPATRIMONIO,CONCAT(c.NOM_INVENTARIO,' - ',DATE_FORMAT(c.FECHACREACION,'%d-%m-%Y')) NOMBREINVENTARIO
			FROM `activo` a INNER JOIN `componente` b ON a.IDCOMPONENTE=b.IDCOMPONENTE
			INNER JOIN `inventario` c ON a.IDINVENTARIO=c.IDINVENTARIO
			WHERE a.TRANSFERIDO ='0'
			AND c.IDINVENTARIO = (SELECT MAX(IDINVENTARIO) FROM `inventario` WHERE IDINVENTARIO <> (SELECT MAX(IDINVENTARIO) FROM inventario))
			$var";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function transferactivos($idactivo){

		$user = $_SESSION['user'];

		$sql_max_inv="SELECT MAX(IDINVENTARIO) IDINVENTARIO FROM `inventario`";
		$result=$this->_db->query($sql_max_inv) or die('Error en'.$sql_max_inv);
		$reg = $result->fetch_object();
			$idinventario = $reg->IDINVENTARIO;

		$sql="INSERT INTO activo (IDCOMPONENTE, MODELO, MARCA, SERIE, CAPACIDAD, ESTADO, CADUCIDAD, TIPO, DESCRIPCION, IDUSUARIOCREACION, FECHACREACION, IDUSUARIOMOD, IDPATRIMONIO, IDINVENTARIO, IDPERSONA)
			SELECT IDCOMPONENTE, MODELO, MARCA, SERIE, CAPACIDAD, ESTADO, CADUCIDAD, TIPO, DESCRIPCION, '$user', NOW(), 'TRANSFER', IDPATRIMONIO, '$idinventario', IDPERSONA
			FROM `activo` WHERE IDACTIVO = '$idactivo'";
		$this->_db->query($sql) or die('Error en'.$sql);

		//OBTENER EL ULTIMO ID
		$idactivo_last=$this->_db->insert_id;

		$sql_transfer="UPDATE activo SET `TRANSFERIDO` = '1' WHERE IDACTIVO='$idactivo'";
		$this->_db->query($sql_transfer) or die('Error en'.$sql_transfer);

		//INSERTAR EN LA BITACORA
		$sql_get="SELECT * FROM activo WHERE IDACTIVO = '$idactivo' AND IDPERSONA IS NOT NULL";
		$result_get=$this->_db->query($sql_get) or die('Error en'.$sql_get);

		if($result_get->num_rows){
			$reg2 = $result_get->fetch_object();
			$idcomponente = $reg2->IDCOMPONENTE;
			$idpersona = $reg2->IDPERSONA;
			$idusuario = $reg2->IDUSUARIOCREACION;

			$sql_bitacora="INSERT INTO bitacora_activo SET IDACTIVO='$idactivo_last', IDCOMPONENTE = '$idcomponente', IDINVENTARIO = '$idinventario' ,TIPOACTA='ENT', IDPERSONA='$idpersona', IDUSUARIOCREACION='$idusuario', FECHACREACION=NOW()";
			$this->_db->query($sql_bitacora) or die('Error en'.$sql_bitacora);
			if($this->_db->errno)
				return false;
			return true;
		}		
		
		if($this->_db->errno)
			return false;
		return true;

	}

}

?>

