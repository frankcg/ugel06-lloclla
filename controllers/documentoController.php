<?php 

class documentoController extends Controller{
	
	public function __construct(){
		parent::__construct();
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){
		$this->_view->setJs(array('index'));		
		$objModel=$this->loadModel('documento');
		$this->_view->documento=$objModel->getpersonal();
		
		$this->_view->renderizar('index');
	}


	public function getdocumentos(){
		$objModel=$this->loadModel('documento');
		$result=$objModel->getdocumentos();
		
			while($reg=$result->fetch_object()){

			if($reg->TIPO  == '1') $tipo='ENTREGA'; else $tipo='RETIRO';

			$boton='<button id="'.$reg->IDDOCUMENTO.'" class="editdocumento btn btn-primary btn-xs" data-toggle="modal" data-target="#adddocumento" ><span class="fa fa-edit"></span> Editar</button>';

			$pdf='<a href="../'.$reg->ADJUNTO.'" target="_blank"><img src="../public/img/pdf.png" /></a>';

			$data ['data'] [] = array (
				'PERSONAL'=>$reg->NOMBRE,
				'TIPO'=>$tipo,
				'DESCRIPCION'=>$reg->DESCRIPCION,
				'IDUSUARIOCREACION'=>$reg->IDUSUARIOCREACION,
				'FECHACREACION'=>$reg->FECHACREACION,
				'ADJUNTO'=>$pdf,
				'OPCIONES'=>$boton
				);

		}
		echo json_encode ( $data );
	}

	public function adddocumento(){

		$persona = $_POST['persona'];	
		$tipodoc = $_POST['tipo'];
		$descripcion = $_POST['descripcion'];	
		$archivo= $_FILES['adjunto']['name'];
		$extension= $_FILES['adjunto']['type'];
		$size= $_FILES['adjunto']['size'];
		
		//echo $persona;
		//echo $persona +'/'+$tipodoc+'/'+$descripcion;

		$objModel=$this->loadModel('documento');
	
		$ruta = $_FILES['adjunto']['tmp_name'];
		$user=$_SESSION['user'];

		$nombre_file =$user.'-'.md5(rand(1,99999).trim($_FILES['adjunto']['name'])).'.pdf';
		$destino = "public/documentos/".$nombre_file;

		//echo "$persona --- $tipodoc --- $descripcion --- $nombre_file --- $destino --- $extension --- $size";
		
		if($archivo)
		{
				if (copy($ruta,$destino))
					{$result=$objModel->adddocumento($persona,$tipodoc, $descripcion,$nombre_file, $destino, $extension, $size);}
				else
					{$result=false;}
		}

		if($result) 
			$data=1; else $data=0;
			echo (int)$data;
	}

	public function getdocumento(){
		$iddocumento = $_POST['iddocumento'];

		$objModel=$this->loadModel('documento');
		$result=$objModel->getdocumento($iddocumento);
		$reg=$result->fetch_object();
		echo json_encode($reg);
	}

		public function getoficina(){
		$idoficina = $_POST['idoficina'];		
		$objModel=$this->loadModel('oficina');
		$result=$objModel->getoficina($idoficina);
		$reg=$result->fetch_object();
		echo json_encode($reg);
	}

	
	public function updatedocumento(){

		$iddocumento = $_POST['iddocumento'];
		$persona = $_POST['persona'];	
		$tipodoc = $_POST['tipo'];
		$descripcion = $_POST['descripcion'];	
	

		$objModel=$this->loadModel('documento');
	
	
		//echo "$persona --- $tipodoc --- $descripcion --- $nombre_file --- $destino --- $extension --- $size";
		$result=$objModel->updatedocumento($iddocumento,$persona,$tipodoc, $descripcion);
	

		if($result) 
			$data=1; else $data=0;
			echo (int)$data;
	}

	public function updatedocumentodigital(){

		$iddocumento = $_POST['iddocumento'];
		$archivo= $_FILES['adjunto']['name'];
		
		//DATOS DEL ARCHIVO DIGITAL
		$extension= $_FILES['adjunto']['type'];
		$size= $_FILES['adjunto']['size'];
		$ruta = $_FILES['adjunto']['tmp_name'];
		$user=$_SESSION['user'];
		$nombre_file =$user.'-'.md5(rand(1,99999).trim($_FILES['adjunto']['name'])).'.pdf';
		$destino = "public/documentos/".$nombre_file;

		$objModel=$this->loadModel('documento');		
		if($archivo)
		{
				if (copy($ruta,$destino))
					{$result=$objModel->updatedocumentodigital($iddocumento,$nombre_file, $destino, $extension, $size);}
				else
					{$result=false;}
		}
		
		if($result) 
			$data=1; else $data=0;
			echo $destino;
	}



}

?>
