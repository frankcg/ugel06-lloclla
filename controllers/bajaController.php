<?php 

class bajaController extends Controller{
	
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->_view->setJs(array('index'));

		$objModel2=$this->loadModel('exactitud');
		$this->_view->inventario=$objModel2->getinventario();

		$this->_view->renderizar('index');
	}

	public function getbaja($idinventario){
		$objModel=$this->loadModel('baja');
		$result=$objModel->getbaja($idinventario);

		while($reg=$result->fetch_object()){

            $input='<input type="checkbox" value="'.$reg->IDACTIVO.'" id="activo" class="activo" name="activo[]" >';
			
			$data ['data'] [] = array (
				'IDACTIVO'=>$input,
                'NOMBRE'=>$reg->NOMBRE,
				'MODELO'=>$reg->MODELO,
				'MARCA'=>$reg->MARCA,				
				'SERIE'=>$reg->SERIE,
				'ESTADO'=>$reg->ESTADO,
				'CAPACIDAD'=>$reg->CAPACIDAD,
				'MEDIOINGRESO'=>$reg->MEDIOINGRESO,
				'IDPATRIMONIO'=>$reg->IDPATRIMONIO						
			);
		}
		echo json_encode ( $data );	
	}

    public function addbaja(){
        
        $idinventario = $_POST['idinventario'];
        $idmotivo = $_POST['motivo'];
        $array_idactivo = join($_POST['activo'],',');
        $objModel=$this->loadModel('baja');

        $result=$objModel->addbaja($array_idactivo, $idinventario, $idmotivo);
        if($result) echo 1; else echo 0;
    }  
    
}

?>