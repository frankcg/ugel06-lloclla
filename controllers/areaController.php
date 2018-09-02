<?php 

class areaController extends Controller{
	
	public function __construct(){
		parent::__construct();
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){
		$this->_view->setJs(array('index'));		
		$objModel=$this->loadModel('area');
		
		$this->_view->renderizar('index');
	}


	public function getareas(){
		$objModel=$this->loadModel('area');
		$result=$objModel->getareas();
		
			while($reg=$result->fetch_object()){

			if($reg->ESTADO  == '1') $activo='ACTIVO'; else $activo='INACTIVO';

			$boton='<button id="'.$reg->IDAREA.'" class="editarea btn btn-primary btn-xs" data-toggle="modal" data-target="#addarea" ><span class="fa fa-edit"></span> Editar</button>';

			$data ['data'] [] = array (
				'NOMBRE_AREA'=>$reg->NOMBRE_AREA,
				'ESTADO'=>$activo,
				'DESCRIPCION'=>$reg->DESCRIPCION,
				'OPCIONES'=>$boton
				);

		}
		echo json_encode ( $data );
	}

	public function addarea(){

		$nombre = strtoupper(trim($_POST['nombre']));
		$status = $_POST['status'];
		$descripcion = $_POST['descripcion'];

		$objModel=$this->loadModel('area');

		$duplicado = $objModel->duplicidad($nombre);
		if($duplicado){
			echo 'Ya existe un área registrado con este nombre '.$nombre;
		}
		else{
			$result = $objModel->addarea($nombre,$status,$descripcion);
			if($result) echo "ok"; else echo "error";
			}		
	}


	public function getarea(){
		$idarea = $_POST['idarea'];		
		$objModel=$this->loadModel('area');
		$result=$objModel->getarea($idarea);
		$reg=$result->fetch_object();
		echo json_encode($reg);
	}


	
	public function updatearea(){

		$idarea = $_POST['idarea'];
		$nombre = $_POST['nombre'];
		$status = $_POST['status'];
		$descripcion = $_POST['descripcion'];

		$objModel=$this->loadModel('area');

		$result = $objModel->updatearea($idarea, $nombre, $status, $descripcion);
		if($result) echo "ok"; else echo "error";
	}

	

}

?>