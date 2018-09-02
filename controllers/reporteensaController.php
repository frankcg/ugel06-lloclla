<?php 

class reporteensaController extends Controller{
	
	public function __construct(){
		parent::__construct();
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){
		$this->_view->setJs(array('index'));		
		$objModel=$this->loadModel('reporteensa');

		$this->_view->renderizar('index');
	}


	public function getreporteensa($tipo, $fechainicio, $fechafin){

		//echo $fechainicio;
		//echo $fechafin; 


        $objModel=$this->loadModel('reporteensa');
       // exit();
        $result=$objModel->getreporteensa($tipo,$fechainicio, $fechafin);

		while($reg=$result->fetch_object()){
	
		if($reg->ESTADO  == '1') $estado='ACTIVO'; else if ($reg->ESTADO  == '2')$estado='DAÑADO';else if ($reg->ESTADO  == '0')$estado='OBSOLETO'; else $estado='CADUCADO';
		
			$data ['data'] [] = array (
				'COMPONENTE'=>$reg->NOMBRE,
				'MARCA'=>$reg->MARCA,
				'SERIE'=>$reg->SERIE,
				'PERSONA'=>$reg->NOMBRES,
				'ESTADO'=>$estado,
				'USUARIOCREACION'=>$reg->IDUSUARIOCREACION,
                'FECHACREACION'=>$reg->FECHACREACION,
                'INVENTARIO'=>$reg->NOM_INVENTARIO,
                'FECHAINICIO'=>$reg->FECHAINICIO,
                'FECHAFIN'=>$reg->FECHAFIN
			);
		}
		echo json_encode ( $data );

	}



}

?>