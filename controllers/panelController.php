<?php 

class panelController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){
		$objModel=$this->loadModel('panel');
		$this->_view->panel=$objModel->getestadistica();
		$this->_view->stock=$objModel->getstock();
		$this->_view->renderizar('index');
	}

}

?>