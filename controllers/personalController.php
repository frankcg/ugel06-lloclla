<?php 

class personalController extends Controller{
	
	public function __construct(){
		parent::__construct();
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){
		$this->_view->setJs(array('index'));		
		$objModel=$this->loadModel('personal');
		
		$objModel2=$this->loadModel('usuario');
		$this->_view->oficina=$objModel2->getoficina();
		
		$this->_view->renderizar('index');
	}


	public function getpersonales(){
		$objModel=$this->loadModel('personal');
		$result=$objModel->getpersonales();

		while($reg=$result->fetch_object()){

			if($reg->ESTADO  == '1') $activo='ACTIVO'; else $activo='INACTIVO';

			$boton='<button id="'.$reg->IDPERSONA.'" class="editpersonal btn btn-primary btn-xs" data-toggle="modal" data-target="#addpersonal" ><span class="fa fa-edit"></span> Editar</button>';

			$data ['data'] [] = array (
				'DNI'=>$reg->DNI,
				'NOMBRE'=>$reg->NOMBRE,
				'EMAIL'=>$reg->CORREO,
				'TELEFONO'=>$reg->TELEFONO,
				'NOMBRE_OFICINA'=>$reg->NOMBRE_OFICINA,
				'STATUS'=>$activo,
				'OPCIONES'=>$boton
				);
		}
		echo json_encode ( $data );
	}

	public function addpersonal(){

		$ape_pat = strtoupper(trim($_POST['ape_pat']));
		$ape_mat = strtoupper(trim($_POST['ape_mat']));
		$nombre = strtoupper(trim($_POST['nombre']));
		$dni = $_POST['dni'];
		$email = $_POST['email'];
		$telefono = $_POST['telefono'];
		$oficina = $_POST['oficina'];
		$status = $_POST['status'];

		$objModel=$this->loadModel('personal');

		$duplicado = $objModel->duplicidad($dni);
		if($duplicado){
			echo 'Ya existe Personal Registrado con este DNI '.$dni;
		}else{
			$result = $objModel->addpersonal($ape_pat, $ape_mat, $nombre, $dni,$email,$telefono, $oficina, $status);
			if($result) echo "ok"; else echo "error";
		}		
	}


	public function getpersonal(){
		$codpersona = $_POST['codpersona'];		
		$objModel=$this->loadModel('personal');
		$result=$objModel->getpersonal($codpersona);
		$reg=$result->fetch_object();
		echo json_encode($reg);
	}

	public function updatepersonal(){

		$idpersona = $_POST['idpersona'];
		$ape_pat = $_POST['ape_pat'];
		$ape_mat = $_POST['ape_mat'];
		$nombre = $_POST['nombre'];
		$dni = $_POST['dni'];
		$email = $_POST['email'];
		$telefono = $_POST['telefono'];
		$oficina = $_POST['oficina'];
		$status = $_POST['status'];

		$objModel=$this->loadModel('personal');

		$result = $objModel->updatepersonal($idpersona, $ape_pat, $ape_mat, $nombre, $dni,$email,$telefono,$oficina, $status);
		if($result) echo "ok"; else echo "error";
	}

	

}

?>