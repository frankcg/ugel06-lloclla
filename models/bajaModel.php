<?php 
Class bajaModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getbaja($idinventario){
		$sql= "SELECT a.IDPERSONA, a.`IDACTIVO`,b.NOMBRE, a.MODELO, a.MARCA, a.SERIE, a.CAPACIDAD, IF(a.ESTADO='1','ACTIVO', IF(a.ESTADO='2','DAÑADO',IF(a.ESTADO='0','OBSOLETO','CADUCADO')) ) AS ESTADO, IF(a.TIPO = '1','HARDWARE','SOFTWARE') TIPO, a.MEDIOINGRESO, a.IDPATRIMONIO, a.`FECHACREACION`
			FROM activo a INNER JOIN componente b ON a.`IDCOMPONENTE`=b.IDCOMPONENTE 
			WHERE a.IDINVENTARIO = '$idinventario'  AND a.IDPERSONA IS NULL";
		$result=$this->_db->query($sql) or die('Error en'.$sql);
		return $result;
	}

	public function addbaja($array_idactivo, $idinventario, $idmotivo){
		$user = $_SESSION['user'];

		//traer datos del activo
		$sql_log="SELECT * FROM activo WHERE IDINVENTARIO ='$idinventario' AND IDACTIVO in ($array_idactivo)";
		$result = $this->_db->query($sql_log) or die('Error en'.$sql_log);


		while($reg = $result->fetch_object()){
			$idactivo = $reg->IDACTIVO;
			$idcomponente = $reg->IDCOMPONENTE;
			$idinventario = $reg->IDINVENTARIO;
			$estado = $reg->ESTADO;

		//INSERTO EN LA TABLA BITACORA 
		$sql_insert="INSERT INTO bitacora_activo SET IDACTIVO ='$idactivo', IDCOMPONENTE = '$idcomponente', IDINVENTARIO = '$idinventario' ,TIPOACTA='ENT', IDPERSONA='0105', ESTADO_ACTIVO = '$estado', DESCRIPCION='$idmotivo', IDUSUARIOCREACION='$user', FECHACREACION=NOW()";
		$this->_db->query($sql_insert) or die('Error en'.$sql_insert);


		if($sql_insert){
		$sql= "UPDATE activo set IDPERSONA = '0105', descripcion = '$idmotivo', IDUSUARIOMOD = '$user', FECHAMOD=NOW() where IDACTIVO ='$idactivo' and IDINVENTARIO='$idinventario'";

		$this->_db->query($sql) or die('Error en'.$sql);
		if($this->_db->errno)
			return false;
		return true; }
			}
	}
}

?>
