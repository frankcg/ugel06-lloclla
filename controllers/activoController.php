<?php 

class activoController extends Controller{
	
	public function __construct(){
		parent::__construct();		
	}
	
	public function index(){

		$this->_view->setJs(array('index'));
		$objModel=$this->loadModel('activo');
		$this->_view->componente=$objModel->getcomponente();
		$this->_view->inventario=$objModel->getinventario();
		$this->_view->asignar=$objModel->getpersonal();
		$this->_view->asignar2=$objModel->getpersonal();
		$this->_view->renderizar('index');
	}

	public function getactivos(){

		$objModel=$this->loadModel('activo');
		$result=$objModel->getactivos();

		while($reg=$result->fetch_object()){

			if($reg->ESTADO  == '1') $estado='ACTIVO'; else if ($reg->ESTADO  == '2')$estado='DAÑADO';else if ($reg->ESTADO  == '0')$estado='OBSOLETO'; else $estado='CADUCADO';

			$boton='<button id="'.$reg->IDACTIVO.'" class="editactivo btn btn-primary btn-xs" data-toggle="modal" data-target="#addactivo" ><span class="fa fa-edit"></span> Editar</button>';

			$data ['data'] [] = array ('NOMACTIVO'=>$reg->NOMACTIVO,
				'MARCA'=>$reg->MARCA,
				'SERIE'=>$reg->SERIE,				
				'MODELO'=>$reg->MODELO,
				'ESTADO'=>$estado,
				'CAPACIDAD'=>$reg->CAPACIDAD,
				'PERSONAL'=>$reg->PERSONAL,
				'OFICINA'=>$reg->NOMBRE_OFICINA,
				'IDPATRI'=>$reg->IDPATRIMONIO,
				'ASIGNADO'=>$reg->ASIGNADO,
				'OPCIONES'=>$boton
				);
		}
		echo json_encode ( $data );		
	}

	public function addactivo(){

		$componente = $_POST['componente'];
		$modelo = $_POST['modelo'];
		$marca = $_POST['marca'];
		$serie = $_POST['serie'];
		$capacidad = $_POST['capacidad'];
		$status = $_POST['status'];
		$caducidad = $_POST['caducidad'];
		$tipo = $_POST['tipo'];
		$medioingreso = $_POST['medioingreso'];		
		$codingreso = $_POST['codingreso'];	
		$patrimonio = $_POST['patrimonio'];
		$descripcion = $_POST['descripcion'];

		$objModel=$this->loadModel('activo');
		$result=$objModel->verificarexistenciainventario();
		$reg = $result->fetch_object();
			$idinventario=$reg->IDINVENTARIO;

		//VALIDAR QUE EL NUMERO DE SERIE NO SEA REPETIDO. en base al actual inventario
		$validarserie = $objModel->verificarserie($serie);

		//VALIDAR QUE EL NUMERO DE SERIE NO SEA REPETIDO. en base al penultimo inventario
		$validarpenultimaserie = $objModel->validarpenultimaserie($serie);

		if($validarserie){
			echo 'Numero de serie ya se encuentra registrado';
		}else if($validarpenultimaserie){
			echo 'Numero de serie ya se encuentra registrado en otro inventario';
		}else if($idinventario){			
			$result2=$objModel->addactivo($componente,$modelo,$marca,$serie,$capacidad,$status,$caducidad,$tipo,$medioingreso,$codingreso,$patrimonio,$descripcion, $idinventario);
			//echo (int)$result2;
			if($result2) $data = 1; else $data = 0;
			echo (int)$data;
		}else{
			echo 'No hay Inventario Aperturado';
		}
	}

	public function getactivo(){
		$idactivo = $_POST['idactivo'];
		$objModel=$this->loadModel('activo');
		$result=$objModel->getactivo($idactivo);
		$reg = $result->fetch_object();
		echo json_encode($reg);
	}

	public function updateactivo(){
		
		$idactivo = $_POST['idactivo'];
		$componente = $_POST['componente'];
		$modelo = $_POST['modelo'];
		$marca = $_POST['marca'];
		$serie = $_POST['serie'];
		$capacidad = $_POST['capacidad']; 
		$status = $_POST['status'];
		$caducidad = $_POST['caducidad'];
		$tipo = $_POST['tipo'];
		$medioingreso = $_POST['medioingreso'];
		$codingreso = $_POST['codingreso'];
		$patrimonio = $_POST['patrimonio'];
		$descripcion = $_POST['descripcion'];

		$objModel=$this->loadModel('activo');
		$result=$objModel->updateactivo($idactivo,$componente, $modelo, $marca, $serie, $capacidad, $status, $caducidad, $tipo, $medioingreso,$codingreso,$patrimonio, $descripcion);
		if($result) $data = 1; else $data = 0;
			echo (int)$data;

	}

	public function verificarasignacion(){
		$idactivo = $_POST['idactivo'];
		$objModel=$this->loadModel('activo');
		$result=$objModel->verificarasignacion($idactivo);
		if($result) echo 1;else echo 0;
	}

	public function asignaractivo(){

		$idactivo = $_POST['idactivo'];
		$idpersona = $_POST['idpersona'];
		$componente = $_POST['componente'];
		$idinventario = $_POST['idinventario'];
		//$estado = $_POST['status'];
		
		//echo "$estado";

		$objModel=$this->loadModel('activo');
		$result=$objModel->asignaractivo($idactivo,$idpersona, $componente, $idinventario);
		if($result) $data = 1; else $data = 0;
			echo (int)$data;
	}

	/* ***********************************************************************************
							ACTIVOS DE LOS INVENTARIOS HISTORICOS
	************************************************************************************** */

	public function getactivos_historicos($idinventario){

		$objModel=$this->loadModel('activo');
		$result=$objModel->getactivos_historicos($idinventario);

		while($reg=$result->fetch_object()){

			if($reg->ESTADO  == '1') $estado='ACTIVO'; else if ($reg->ESTADO  == '2')$estado='DAÑADO';else if ($reg->ESTADO  == '0')$estado='OBSOLETO'; else $estado='CADUCADO';

			$boton='<button id="'.$reg->IDACTIVO.'" class="editactivo btn btn-primary btn-xs" data-toggle="modal" data-target="#addactivo" ><span class="fa fa-edit"></span> Editar</button>';

			$data ['data'] [] = array ('NOMACTIVO'=>$reg->NOMACTIVO,
				'MARCA'=>$reg->MARCA,
				'MODELO'=>$reg->MODELO,
				'SERIE'=>$reg->SERIE,				
				'ESTADO'=>$estado,
				'PERSONAL'=>$reg->PERSONAL,
				'IDPATRI'=>$reg->IDPATRIMONIO,
				'USERCREACION'=>$reg->IDUSUARIOCREACION,
				'FECHACREACION'=>$reg->FECHACREACION				
				);
		}
		echo json_encode ( $data );		
	}

	/* ***********************************************************************************
							TRANSFERENCIA DE ACTIVOS
	************************************************************************************** */

	public function getactivos_penultimo($idpersona){

		$objModel=$this->loadModel('activo');
		$result=$objModel->getactivos_penultimo($idpersona);

		while($reg=$result->fetch_object()){

			$input='<input type="checkbox" value="'.$reg->IDACTIVO.'" id="transferencia" class="transferencia" name="transferencia[]" >';

			if($reg->ESTADO  == '1') $estado='ACTIVO'; else if ($reg->ESTADO  == '2')$estado='DAÑADO';else if ($reg->ESTADO  == '0')$estado='OBSOLETO'; else $estado='CADUCADO';

			$boton='<button id="'.$reg->IDACTIVO.'" class="editactivo btn btn-primary btn-xs" data-toggle="modal" data-target="#addactivo" ><span class="fa fa-edit"></span> Editar</button>';

			$data ['data'] [] = array (
				'INPUT'=>$input,
				'NOMACTIVO'=>$reg->NOMACTIVO,
				'MARCA'=>$reg->MARCA,
				'MODELO'=>$reg->MODELO,
				'SERIE'=>$reg->SERIE,				
				'ESTADO'=>$estado,
				'IDPATRI'=>$reg->IDPATRIMONIO,
				'NOMBREINVENTARIO'=>$reg->NOMBREINVENTARIO				
				);
		}
		echo json_encode ( $data );		
	}

	public function transferactivos(){

		$transferencia = $_POST['transferencia'];
		$objModel=$this->loadModel('activo');

		$result2=$objModel->verificarexistenciainventario();
		$reg2 = $result2->fetch_object();
			$idinventario=$reg2->IDINVENTARIO;

		if($transferencia==''){
			echo 'Seleccione Activos';
		}else if ($idinventario){
			foreach ($transferencia as $key => $value) {
				$idactivo = $transferencia[$key];
				$result=$objModel->transferactivos($idactivo);
			}
			if($result) echo 1; else echo 0;
		}else{
			echo 'No hay Inventario Aperturado';
		}
		
	}



}
?>