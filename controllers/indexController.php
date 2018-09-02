<?php 

class indexController extends Controller{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->_view->setJs(array('index'));	
		//redirecciona al index de login	
		$this->_view->renderizar('index', true);
	}
	
	public function login(){
		
		$user = strtoupper(trim($_POST['user']));
		$pass = strtoupper(trim($_POST['pass']));		
				
		$intentos = isset($_COOKIE['intentos'])?$_COOKIE['intentos']:1;
		$objModel=$this->loadModel('usuario');
				
		if ($intentos>3) {
			echo 'Sistema Bloqueado';
		}else if($objModel->validactivo($user)){
			echo 'Usuario Inactivo. Comuniquese con el Administrador';
		}else{			
				
			if ($objModel->validUser($user,$pass)){
				
				$_SESSION['user']=$user;
				$_SESSION['nombre']=$objModel->getNombre($user);
				$_SESSION['foto']=$objModel->getFoto($user);					
				$_SESSION['menu'] = $objModel->getMenu($user);
				$_SESSION['lectura'] = $objModel->getlectura($user);
				$_SESSION['escritura'] = $objModel->getescritura($user);

				$intentos=1;
				setcookie('intentos',$intentos,time()+60);
				echo 'ok';
			}else
				echo 'Usuario y/o Password incorrecto';
		}
	
		$intentos++;
		setcookie('intentos',$intentos,time()+60);
	
	}
	
	public function logout(){
	
		unset($_SESSION['user']);
		unset($_SESSION['nombre']);
		unset($_SESSION['foto']);
		unset($_SESSION['menu']);
		$this->redireccionar('index');
	}
}


?>