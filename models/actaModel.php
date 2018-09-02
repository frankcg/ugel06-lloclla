<?php 
Class actaModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getcomponente(){
		$sql= "SELECT * FROM componente";
		//ejecuta el query
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function getpersonal($idpersona){
		$sql= "SELECT CONCAT(`AP_PATERNO`,' ',`AP_MATERNO`,' ',NOMBRES) NOMBRE, c.`NOMBRE_AREA` AS AREA
			FROM `persona` a INNER JOIN `oficina`  b ON a.`IDOFICINA`=b.IDOFICINA
			INNER JOIN `area` c ON b.`IDAREA`=c.`IDAREA`
			WHERE IDPERSONA = '$idpersona'";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function getpersonalactivo($idpersona){
		$sql= "SELECT b.NOMBRE, a.MARCA, a.SERIE, a.IDPATRIMONIO
				FROM `activo` a INNER JOIN `componente` b ON a.IDCOMPONENTE=b.IDCOMPONENTE
				WHERE IDPERSONA = '$idpersona' AND a.IDINVENTARIO = (SELECT MAX(IDINVENTARIO) IDINVENTARIO FROM inventario)";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;

	}

	public function getcombopersonal(){
		$sql= "SELECT IDPERSONA, CONCAT(AP_PATERNO,' ',AP_MATERNO,' ', NOMBRES) NOMBRE FROM `persona` order by NOMBRE ";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function getactivosretiro($idpersona){

		$sql= "SELECT a.IDACTIVO, b.NOMBRE, a.MODELO, a.MARCA, a.SERIE, a.CAPACIDAD, 
				IF(a.ESTADO = '1','ACTIVO',IF(a.ESTADO = '2','DAÑADO',IF(a.ESTADO = '3','CADUCADO','OBSOLETO'))) AS ESTADO,
				a.CADUCIDAD, IF(a.TIPO = '0','SOFTWARE','HARDWARE') TIPO 
				FROM `activo` a INNER JOIN `componente` b ON a.IDCOMPONENTE = b.IDCOMPONENTE
				WHERE a.`IDPERSONA` = '$idpersona'
				AND a.IDINVENTARIO = (SELECT MAX(IDINVENTARIO) FROM `inventario`)";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;

	}

	public function createtable(){
		$sql= "CREATE table TEMPORAL ( IDACTIVO INT(4), DESCRIPCION char(50), MOTIVO char(20))";
		$this->_db->query($sql) or die('Error en'.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function addtemporal($array_activo, $array_descripcion, $array_motivo){
		$sql= "INSERT INTO temporal SET IDACTIVO = '$array_activo', DESCRIPCION = '$array_descripcion', MOTIVO = '$array_motivo'";
		$this->_db->query($sql) or die('Error en'.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function updatetemporal($array_activo){
		$sql= "UPDATE `activo` SET IDUSUARIOMOD = 'TEMPORAL' WHERE IDACTIVO IN ($array_activo)";
		//echo $sql; exit();
		$this->_db->query($sql) or die('Error en'.$sql);	

		if($this->_db->errno)
			return false;
		return true;
	}

	public function generaractaretiro($idpersona){
		$sql= "SELECT a.IDACTIVO, b.NOMBRE, a.MODELO, a.MARCA, a.SERIE, a.CAPACIDAD, a.IDUSUARIOMOD,
				IF(a.ESTADO = '1','ACTIVO',IF(a.ESTADO = '2','DAÑADO',IF(a.ESTADO = '3','CADUCADO','OBSOLETO'))) AS ESTADO,
				a.CADUCIDAD, IF(a.TIPO = '0','SOFTWARE','HARDWARE') TIPO, a.IDPATRIMONIO
				FROM `activo` a INNER JOIN `componente` b ON a.IDCOMPONENTE = b.IDCOMPONENTE
				WHERE a.IDPERSONA = '$idpersona' AND a.IDUSUARIOMOD = 'TEMPORAL'";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function updateretiro($idpersona){
		$user = $_SESSION['user'];

		//log de acta de retiro
		$sql_log="SELECT * FROM activo WHERE IDPERSONA ='$idpersona' AND IDUSUARIOMOD='TEMPORAL'";
		$result = $this->_db->query($sql_log) or die('Error en'.$sql_log);

		while($reg = $result->fetch_object()){

			$idactivo = $reg->IDACTIVO;
			$idcomponente = $reg->IDCOMPONENTE;
			$idinventario = $reg->IDINVENTARIO;

			//OBTENGO LOS DATOS DE LA TABLA TEMPORAL
			$sql_tmp = "SELECT * FROM temporal where IDACTIVO = '$idactivo'";
			$result_tmp = $this->_db->query($sql_tmp) or die('Error en'.$sql_tmp);
			$reg_tmp = $result_tmp->fetch_object();
				$descripcion =  $reg_tmp->DESCRIPCION;
				$motivo =  $reg_tmp->MOTIVO;

			//INSERTO EN LA TABLA BITACORA LOS DATOS QUE TOME DE LA TABLA TEMPORAL
			$sql_insert="INSERT INTO bitacora_activo SET IDACTIVO='$idactivo', IDCOMPONENTE = '$idcomponente', IDINVENTARIO = '$idinventario' ,TIPOACTA='RET', IDPERSONA='$idpersona', ESTADO_ACTIVO = '$motivo', DESCRIPCION='$descripcion', IDUSUARIOCREACION='$user', FECHACREACION=NOW()";
			$this->_db->query($sql_insert) or die('Error en'.$sql_insert);
		}
		
		//ELIMINO LA TABLA
		$sql_drop = "DROP TABLE temporal";
		$result_drop = $this->_db->query($sql_drop) or die('Error en'.$sql_drop);

		if($result_drop){
			//AQUI RECIEN LE HAGO EL RETIRO DEL ACTIVO A LA PERSONA
			$sql = "UPDATE activo set IDPERSONA = NULL,ESTADO='$motivo', IDUSUARIOMOD='$user', FECHAMOD=NOW() where IDPERSONA ='$idpersona' and IDUSUARIOMOD='TEMPORAL'";
			$this->_db->query($sql) or die('Error en'.$sql);
			if($this->_db->errno)
				return false;
			return true;
		}
	}

	/* **************************************************************************************************** */
    /*                                             HISTORIAL DE ACTAS
    /* **************************************************************************************************** */

    public function getcombopersonalacta(){
		$sql= "SELECT DISTINCT a.IDPERSONA, CONCAT(b.AP_PATERNO,' ',b.AP_MATERNO,' ', b.NOMBRES) NOMBRE 
			FROM `bitacora_activo` a INNER JOIN persona b ON a.`IDPERSONA`=b.`IDPERSONA`
			AND a.`IDINVENTARIO` = (SELECT MAX(IDINVENTARIO) IDINVENTARIO FROM inventario)";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function getcomponenteacta ( $idpersonal ){
		$sql= "SELECT DISTINCT c.`IDCOMPONENTE`, c.NOMBRE
				FROM `bitacora_activo` a INNER JOIN ACTIVO b ON a.`IDACTIVO`=b.IDACTIVO
				INNER JOIN componente c ON b.IDCOMPONENTE = c.`IDCOMPONENTE` 
				WHERE a.IDPERSONA = '$idpersonal' AND a.`IDINVENTARIO` = (SELECT MAX(IDINVENTARIO) IDINVENTARIO FROM inventario)";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function gethistorialacta($idpersona, $idcomponente){
		
		//OBTENER IDACTIVO (EL IDACTIVO PUEDE SER UNO O MAS)
		$sql_activo = "SELECT IDACTIVO FROM bitacora_activo WHERE IDPERSONA = '$idpersona' AND IDCOMPONENTE = '$idcomponente'";
		$result=$this->_db->query($sql_activo) or die('Error en'.$sql_activo);

		while($reg = $result->fetch_object()){
			$array_activo[] = $reg->IDACTIVO;
		}

		$idactivo = join($array_activo,",");

		$sql= "SELECT b.`IDACTIVO`, CONCAT(b.`IDINVENTARIO`,'-', e.`NOM_INVENTARIO`,'-', DATE_FORMAT(e.`FECHAINICIO`,'%d/%m/%Y')) AS INVENTARIO, c.NOMBRE AS COMPONENTE, b.MODELO, b.MARCA, b.SERIE, CONCAT(d.AP_PATERNO,' ',d.AP_MATERNO,' ', d.NOMBRES) NOMBRE, 
			IF(a.`TIPOACTA` = 'ENT','ENTREGA', 'RETIRO') ACTA, a.`IDUSUARIOCREACION`, a.`FECHACREACION` AS FECHAOPERACION
			FROM bitacora_activo a INNER JOIN activo b ON a.`IDACTIVO`=b.IDACTIVO
			INNER JOIN componente c ON a.IDCOMPONENTE = c.`IDCOMPONENTE` 
			INNER JOIN persona d ON a.`IDPERSONA`=d.`IDPERSONA`
			INNER JOIN inventario e ON b.`IDINVENTARIO`=e.`IDINVENTARIO`
			WHERE a.IDCOMPONENTE= '$idcomponente' AND a.IDACTIVO IN ($idactivo)
			ORDER BY a.`FECHACREACION` DESC";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}


}


?>
