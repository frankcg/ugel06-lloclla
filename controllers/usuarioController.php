<?php 

class usuarioController extends Controller{
	
	public function __construct(){
		parent::__construct();
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	/* ********************************************************************************************************************************
														MODULO USUARIO
	******************************************************************************************************************************** */

	public function index(){
		$this->_view->setJs(array('index'));		
		$objModel=$this->loadModel('usuario');
		$this->_view->oficina=$objModel->getoficina();
		$this->_view->renderizar('index');
	}

	public function getusuarios(){
		$objModel=$this->loadModel('usuario');
		$result=$objModel->getusuarios();

		while($reg=$result->fetch_object()){

			if($reg->ESTADO  == '1') $activo='ACTIVO'; else $activo='INACTIVO';

			$boton='<button id="'.$reg->IDUSUARIO.'" class="editusuario btn btn-primary btn-xs" data-toggle="modal" data-target="#addusuario" ><span class="fa fa-edit"></span> Editar</button>
			<button id="'.$reg->IDUSUARIO.'" class="delusuario btn btn-danger btn-xs"><span class="fa fa-close"></span> Inhabilitar </button>';

			$data ['data'] [] = array ('IDUSUARIO'=>$reg->IDUSUARIO,
				'NOMBRE'=>$reg->NOMBRE,
				'AREA'=>$reg->NOMBRE_OFICINA,
				'STATUS'=>$activo,
				'OPCIONES'=>$boton
				);
		}
		echo json_encode ( $data );
	}

	public function addusuario(){

		$usuario= strtoupper(trim($_POST['usuario']));
		$ape_pat= strtoupper(trim($_POST['ape_pat']));
		$ape_mat= strtoupper(trim($_POST['ape_mat']));
		$nombre= strtoupper(trim($_POST['nombre']));
		$dni= strtoupper(trim($_POST['dni']));
		$email= strtoupper(trim($_POST['email']));
		$telefono= strtoupper(trim($_POST['telefono']));
		$oficina= strtoupper(trim($_POST['oficina']));
		$password= strtoupper(trim($_POST['password']));
		$status= $_POST['status'];

		$objModel=$this->loadModel('usuario');
		$existencia = $objModel->existenciausuario($usuario);
		$validaciondni = $objModel->validdni($dni);
		if($existencia == '1'){
			echo 'El usuario ya existe. Pruebe con otro usuario';
		}else if($validaciondni){
			echo 'El DNI ya existe.';
		}else{
			$result = $objModel->addusuario($usuario, $ape_pat, $ape_mat, $nombre, $dni, $email, $telefono, $oficina, $password, $status);
			if($result) echo 'ok'; else 'error';
		}		
	}

	public function getusuario(){
		$idusuario = $_POST['idusuario'];		
		$objModel=$this->loadModel('usuario');
		$result=$objModel->getusuario($idusuario);
		$reg=$result->fetch_object();
		echo json_encode($reg);
	}

	public function updateusuario(){

		$usuario= strtoupper(trim($_POST['usuario']));
		$ape_pat= strtoupper(trim($_POST['ape_pat']));
		$ape_mat= strtoupper(trim($_POST['ape_mat']));
		$nombre= strtoupper(trim($_POST['nombre']));
		$dni= strtoupper(trim($_POST['dni']));
		$email= strtoupper(trim($_POST['email']));
		$telefono= strtoupper(trim($_POST['telefono']));
		$oficina= strtoupper(trim($_POST['oficina']));
		$password= strtoupper(trim($_POST['password']));
		$status= $_POST['status'];

		$objModel=$this->loadModel('usuario');
		$validaciondni = $objModel->validdni($dni);
		
		$result = $objModel->updateusuario($usuario, $ape_pat, $ape_mat, $nombre, $dni, $email, $telefono, $oficina, $password, $status);
		if($result) echo 'ok'; else 'error';
		
		
	}

	public function delusuario(){
		$idusuario = $_POST['idusuario'];
		$objModel=$this->loadModel('usuario');
		$result=$objModel->delusuario($idusuario);
		if($result) echo 'ok'; else 'error';
	}

	/* ********************************************************************************************************************************
																MODULO PERMISOS
	******************************************************************************************************************************** */
	
	public function getaccesosmodulo(){
		$idusuario = $_POST['idusuario'];
		$objModel=$this->loadModel('usuario');
		$result = $objModel->getaccesosuser($idusuario);
		$reg = $result->fetch_object();
		echo json_encode($reg);
	}

	public function addpermisos(){
		$idperfil = $_POST['idperfil'];
		$idusuario = $_POST['usuario'];

		if(isset($_POST['lectura']))$lectura='1'; else $lectura='0';
		if(isset($_POST['escritura']))$escritura='1'; else $escritura='0';	

		$objModel=$this->loadModel('usuario');	
		$result=$objModel->addpermisos($idusuario, $idperfil, $lectura, $escritura);
		if($result) echo 'ok'; else 'error';
	}

	/* ********************************************************************************************************************************
																MODULO PERFIL
	******************************************************************************************************************************** */

	public function getprofiles(){
		$objModel=$this->loadModel('usuario');

		$result = $objModel->getprofiles();

		while($reg=$result->fetch_object()){

			$boton='<button id="'.$reg->IDPERFIL.'" class="editprofile btn btn-primary btn-xs" data-toggle="modal" data-target="#addperfil" ><span class="fa fa-edit"></span> Editar</button>
			<button id="'.$reg->IDPERFIL.'" class="delprofile btn btn-danger btn-xs"><span class="fa fa-trash"></span> Eliminar </button>';

			$data ['data'] [] = array ('IDPERFIL'=>$reg->NOMBRE_PERFIL,
				'CANTMODULOS'=>$reg->CANTMODULOS,
				'IDUSUARIOCREACION'=>$reg->IDUSUARIOCREACION,
				'OPCIONES'=>$boton
				);
		}
		echo json_encode ( $data );
	}

	public function getcomoboperfil(){
		$objModel=$this->loadModel('usuario');
		$result = $objModel->getcomoboperfil();
		echo '<option value="" > SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->IDPERFIL.'" > '.$reg->NOMBRE_PERFIL.' </option>';
		}

	}

	public function getaccesoprofilenew(){
		
		$objModel=$this->loadModel('usuario');
		$result = $objModel->getaccesoprofilenew();
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->IDMODULO.'" > '.$reg->DESCRIPCION.' </option>';
		}
	}

	public function addprofile(){

		$nomperfil = strtoupper(trim($_POST['perfil']));
		$modulo = $_POST['idmodulo'];

		$objModel=$this->loadModel('usuario');

		$validar = $objModel->verificarexistencianombreperfil($nomperfil);

		if(!$validar){
			$idperfil=$objModel->addnombreperfil($nomperfil);

			foreach($modulo as $id => $valor){
					$idmodulo=$modulo[$id];
					$result=$objModel->addprofile($idmodulo, $idperfil);
			}
			if($result) echo 'ok'; else echo 'error';

		}else{
			echo 'Ya existe el Perfil';
		}

		
	}

	public function getaccesoprofile(){
		$idperfil = $_POST['idperfil'];
		$objModel=$this->loadModel('usuario');
		$result = $objModel->getaccesoprofile($idperfil);
		$reg=$result->fetch_object();
		echo json_encode($reg);
	}

	public function getaccesoprofile2(){
		$idperfil = $_POST['idperfil'];
		$objModel=$this->loadModel('usuario');
		$result = $objModel->getaccesoprofile2($idperfil);
		while ($reg = $result->fetch_object()){
			echo '<option '.$reg->SELECTED.' value="'.$reg->IDMODULO.'" > '.$reg->DESCRIPCION.' </option>';
		}
	}

	public function updateprofile(){
		$idperfil = $_POST['getidperfil'];
		$modulo = $_POST['idmodulo'];

		$objModel=$this->loadModel('usuario');
		$result = $objModel->deleteprofile($idperfil);

		foreach($modulo as $id => $valor){
				$idmodulo=$modulo[$id];
				$result=$objModel->updateprofile($idmodulo, $idperfil);
		}

		if($result) echo 'ok'; else echo 'error';
	}

	public function deleteprofile(){
		$idperfil = $_POST['idperfil'];
		$objModel=$this->loadModel('usuario');

		$verificar = $objModel->verificareliminacion($idperfil);

		if(!$verificar){
			$objModel->deleteprofilename($idperfil);
			$result = $objModel->deleteprofile($idperfil);
			if($result) echo 'ok'; else echo 'error';
		}else{
			echo 'Existen usuarios con ese Perfil Asignado';
		}
		
	}

}

?>