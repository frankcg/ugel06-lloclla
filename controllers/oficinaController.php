<?php 

class oficinaController extends Controller{
	
	public function __construct(){
		parent::__construct();
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){
		$this->_view->setJs(array('index'));		
		$objModel=$this->loadModel('oficina');
		$this->_view->oficina=$objModel->getareas();
		
		$this->_view->renderizar('index');
	}


	public function getoficinas(){
		$objModel=$this->loadModel('oficina');
		$result=$objModel->getoficinas();
		
			while($reg=$result->fetch_object()){

			if($reg->ESTADO  == '1') $activo='ACTIVO'; else $activo='INACTIVO';

			$boton='<button id="'.$reg->IDOFICINA.'" class="editoficina btn btn-primary btn-xs" data-toggle="modal" data-target="#addoficina" ><span class="fa fa-edit"></span> Editar</button>';

			$data ['data'] [] = array (
				'NOMBRE_OFICINA'=>$reg->NOMBRE_OFICINA,
				'ESTADO'=>$activo,
				'DESCRIPCION'=>$reg->DESCRIPCION,
				'IDAREA'=>$reg->NOMBRE_AREA,
				'OPCIONES'=>$boton
				);

		}
		echo json_encode ( $data );
	}

	public function addoficina(){

		$nombre = strtoupper(trim($_POST['nombre']));
		$status = $_POST['status'];
		$area = $_POST['area'];
		$descripcion = $_POST['descripcion'];

		$objModel=$this->loadModel('oficina');

		$duplicado = $objModel->duplicidad($nombre);
		if($duplicado){
			echo 'Ya existe una oficina registrada con este nombre '.$nombre;
		}
		else{
			$result = $objModel->addoficina($nombre,$status,$area,$descripcion);
			if($result) echo "ok"; else echo "error";
			}		
	}


	public function getoficina(){
		$idoficina = $_POST['idoficina'];		
		$objModel=$this->loadModel('oficina');
		$result=$objModel->getoficina($idoficina);
		$reg=$result->fetch_object();
		echo json_encode($reg);
	}

	
	public function updateoficina(){

		$idoficina = $_POST['idoficina'];
		$nombre = $_POST['nombre'];
		$status = $_POST['status'];
		$area = $_POST['area'];
		$descripcion = $_POST['descripcion'];

		$objModel=$this->loadModel('oficina');

		$result = $objModel->updateoficina($idoficina, $nombre, $status, $area, $descripcion);
		if($result) echo "ok"; else echo "error";
	}

	

}

?>
