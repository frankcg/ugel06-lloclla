<?php 

class profileController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){
		$this->_view->setJs(array('index'));
		$objModel=$this->loadModel('profile');
		$this->_view->profile=$objModel->getprofile();

		//$objModel2=$this->loadModel('usuario');
		//$this->_view->areas=$objModel2->getareas();

		$this->_view->renderizar('index');
	}
	
	public function updateprofile(){
		$password= strtoupper(trim($_POST['password']));
		$archivo= $_FILES['archivo']['name'];
		$objModel=$this->loadModel('profile');
		$result = $objModel->updateprofile($password);

		$ruta = $_FILES['archivo']['tmp_name'];
		$user=$_SESSION['user'];

		$nombre_file =$user.'-'.md5(rand(1,99999).trim($_FILES['archivo']['name'])).'.jpg';
		$destino = "public/img/profiles/".$nombre_file;
		
		if($archivo){
			if (copy($ruta,$destino)){			
				$result=$objModel->updatefoto($nombre_file);			
			}else{
				$result=false;
			}
		}
		if($result) echo 'ok'; else 'error';		
	}

}

?>