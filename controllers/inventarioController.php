<?php 

class inventarioController extends Controller{
	
	public function __construct(){
		parent::__construct();		
	}
	
	public function index(){

		$this->_view->setJs(array('index'));
		$objModel=$this->loadModel('inventario');
		//$this->_view->componente=$objModel->getcomponente();
		$this->_view->renderizar('index');
	}

	public function getinventarios(){
		$objModel=$this->loadModel('inventario');
		$result = $objModel->getinventarios();

		while($reg=$result->fetch_object()){

			if($reg->ESTADO  == '1') {$activo='PROCESO'; $ver = '';} else {$activo='CONCLUIDO'; $ver = 'disabled'; }

			$boton='<button id="'.$reg->IDINVENTARIO.'" '.$ver.' class="editinventario btn btn-primary btn-xs" data-toggle="modal" data-target="#addinventario" ><span class="fa fa-edit"></span> Editar</button>
			<button id="'.$reg->IDINVENTARIO.'" '.$ver.' class="concluir btn btn-info btn-xs"><span class="fa fa-clock-o"></span> Concluir </button>';

			$data ['data'] [] = array ('NOM_INVENTARIO'=>$reg->NOM_INVENTARIO,
				'FECHAINICIO'=>$reg->FECHAINICIO,
				'FECHAFIN'=>$reg->FECHAFIN,
				'STATUS'=>$activo,
				'DESCRIPCION'=>$reg->DESCRIPCION,
				'IDUSUARIOCREACION'=>$reg->IDUSUARIOCREACION,				
				'OPCIONES'=>$boton
				);
		}
		echo json_encode ( $data );
	}

	public function addinventario(){

		$nombre  =  strtoupper(trim($_POST['nombre']));
		$fechainicio  =  $_POST['fechainicio'];
		$descripcion  =  $_POST['descripcion'];

		$objModel=$this->loadModel('inventario');
		$result = $objModel->validarinventario();
		$nombreduplicado = $objModel->validarnomduplicado($nombre);
		if($nombreduplicado >= '1'){
			echo 'Ya existe el Nombre de Inventario !!';
		}else{
			if($result >= '1'){
				echo 'Hay inventarios en proceso';
			}else{		
				$result = $objModel->addinventario($nombre, $fechainicio,$descripcion);
				if($result) echo 'ok'; else echo 'error';
			}
		}				
	}

	public function getinventario(){
		$idinventario  =  $_POST['idinventario'];
		$objModel=$this->loadModel('inventario');
		$result = $objModel->getinventario($idinventario);
		$reg = $result->fetch_object();
		echo json_encode($reg);
	}

	public function conluirinventario(){
		$idinventario  =  $_POST['idinventario'];
		$objModel=$this->loadModel('inventario');
		$result = $objModel->conluirinventario($idinventario);
		if($result) echo 'ok'; else echo 'error';	
	}

	public function updateinventario(){

		$nombre  =  strtoupper(trim($_POST['nombre']));
		$fechainicio  =  $_POST['fechainicio'];
		$idinventario  =  $_POST['idinventario'];	
		$descripcion  =  $_POST['descripcion'];	

		$objModel=$this->loadModel('inventario');		
		$nombreduplicado = $objModel->validarnomduplicado2($nombre);

		if($nombreduplicado >= '1'){
			echo 'Ya existe el Nombre de Inventario !!';
		}else{			
			$result = $objModel->updateinventario($nombre,$descripcion, $fechainicio, $idinventario);
			if($result) echo 'ok'; else echo 'error';			
		}				
	}
}


?>
