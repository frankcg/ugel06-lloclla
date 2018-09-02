<?php 

class actaController extends Controller{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->_view->setJs(array('index'));	
		$this->_view->renderizar('index');
	}

    /* **************************************************************************************************** */
    /*                                              ACTA DE ENTREGA
    /* **************************************************************************************************** */

	public function getpersonales(){
		$objModel=$this->loadModel('personal');
		$result=$objModel->getpersonales();

		while($reg=$result->fetch_object()){

			if($reg->ESTADO  == '1') $activo='ACTIVO'; else $activo='INACTIVO';

			$boton='<a href="../acta/actaentrega/'.$reg->IDPERSONA.'" target="_blank" class="btn btn-primary btn-xs"><span class="fa fa-file-pdf-o"></span> ACTA ENTREGA </a>';

			$data ['data'] [] = array (
				'DNI'=>$reg->DNI,
				'NOMBRE'=>$reg->NOMBRE,
				'NOMBRE_OFICINA'=>$reg->NOMBRE_OFICINA,
				'STATUS'=>$activo,
                'CANT'=>$reg->CANT,
				'OPCIONES'=>$boton
				);
		}
		echo json_encode ( $data );
	}

    public function actaentrega($idpersona){

        $objModel=$this->loadModel('acta');
        $result=$objModel->getpersonal($idpersona);
        $reg = $result->fetch_object();

        $result2=$objModel->getpersonalactivo($idpersona);
        while($reg2 = $result2->fetch_object()){
            $grid.='
            <tr align="center">
                <td>'.$reg2->NOMBRE.'</td>
                <td>'.$reg2->MARCA.'</td>
                <td>'.$reg2->SERIE.'</td>
                <td>'.$reg2->IDPATRIMONIO.'</td>
            </tr>';

        }

        date_default_timezone_set("America/Lima");
        $fechaactual = date('Ymd');
        $dia_actual = date('d');
        $mes_actual = date('m');
        $anio_actual = date('Y');

      
        setlocale (LC_TIME, "es_PE");
        $fechames = ucwords(strftime('%B')); 

        $this->getLibrary('TCPDF/mypdf');
            
        // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('UGEL06');
        $pdf->SetTitle('UGEL06 | ACTA DE ENTREGA');
        $pdf->SetSubject('UGEL06');

         // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

         //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

         // set font
        $pdf->SetFont('times', '', 10);

        // add a page
        $pdf->AddPage();

        // set some text to print
        $html = <<<EOF

                <!DOCTYPE html>
                <html>
                <head>
                   <title>ACTA ENTREGA</title>
               </head>
               <body>

                <h1 style="text-align: center;"><strong>ACTA DE ENTREGA</strong></h1>
                <p style="text-align: justify;">La Oficina de ETIC por la presente, hace entrega al Sr. $reg->NOMBRE, perteneciente al &Aacute;rea/Oficina de $reg->AREA , los siguientes bienes inform&aacute;ticos.</p>
                <p style="text-align: justify;">&nbsp; </p> 
                <table border="1">
                    <tbody>
                        <tr align="center">
                            <th>ACCESORIO</th>
                            <th>MARCA</th>
                            <th>N&deg; SERIE</th>
                            <th>COD. PATRIMONIO</th>
                        </tr>            
                        $grid
                    </tbody>
                </table>
                <p style="text-align: justify;">&nbsp;</p>
                <p style="padding-left: 60px;">     Es todo cuando tengo que informar para su conocimiento y dem&aacute;s fines.</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p style="text-align: right;">&nbsp;</p>
                <p style="padding-left: 30px; text-align: rigth;">Vitarte, $dia_actual de $fechames del $anio_actual</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                
                
                
                <p style="text-align: center;"><hr style="width:40%;">(e) ILICH SUMARAN QUISPE FERNANDEZ<br />Equipo de tecnologia de la informacion <br /> UGEL N&deg; 06</p>

                </body>
                </html>
EOF;
        // print a block of text using Write()
        $pdf->writeHTML($html, true, false, true, false, '');

        //Close and output PDF document
        $pdf->Output('ACTA_ENTREGA_'.$reg->NOMBRE.'.pdf', 'I');

        }

    /* **************************************************************************************************** */
    /*                                              ACTA DE RETIRO
    /* **************************************************************************************************** */

    public function retiro(){

        $this->_view->setJs(array('index'));
        $objModel=$this->loadModel('acta');
        $this->_view->personal=$objModel->getcombopersonal();
        //$reg = $result->fetch_object();
        $this->_view->renderizar('retiro');
    }

    public function getactivosretiro($idpersona){

        $objModel=$this->loadModel('acta');
        $result=$objModel->getactivosretiro($idpersona);

        while($reg=$result->fetch_object()){

           $input='<input type="checkbox" value="'.$reg->IDACTIVO.'" id="activo" class="activo" name="activo[]" >';
           $descripcion= '<textarea rows="1" cols="15" class="form-control" name="descripcion[]"></textarea>';
           $motivocancel = '<select name="motivo[]" class="form-control" >
                                <option value="1" selected=selected>ACTIVO</option>
                                <option value="2">DAÑADO</option>
                                <option value="3">CADUCADO</option>
                                <option value="0">OBSOLETO</option>
                        </select> ';

                $data ['data'] [] = array (
                'IDACTIVO'=>$input,
                'NOMBRE'=>$reg->NOMBRE,
                'MODELO'=>$reg->MODELO,
                'MARCA'=>$reg->MARCA,
                'SERIE'=>$reg->SERIE,
                'CAPACIDAD'=>$reg->CAPACIDAD,
                'ESTADO'=>$reg->ESTADO,                
                'TIPO'=>$reg->TIPO,
                'DESCRIPCION'=>$descripcion,
                'MOTIVO'=>$motivocancel
                );
        }
        echo json_encode ( $data );
    }

    public function updatetemporal(){

        $idpersona = $_POST['idpersonal'];
        $idactivo = $_POST['activo'];
        $descripcion = $_POST['descripcion'];
        $motivo = $_POST['motivo'];
        
        $objModel=$this->loadModel('acta');       

        if(!isset($_POST['activo'])){
            echo 'Seleccione Activo';          
        }else{            
            //CREANDO UNA TABLA
            $result_table = $objModel->createtable();
            if($result_table){
                //RECORRO TODOS LOS DATOS PARA INSERTARLOS EN LA TABLA TEMPORAL
                foreach ($idactivo as $key => $value) {
                    $array_activo =  $idactivo[$key];
                    $array_descripcion =  $descripcion[$key];
                    $array_motivo = $motivo[$key];
                    $result_addtable = $objModel->addtemporal($array_activo, $array_descripcion, $array_motivo);
                }
                if($result_addtable){
                    //SI LA INSERCION ES CORRECTA, PASO A REALIZAR UN UPDATE TEMPORAL
                    $array_activo = join($idactivo,",");
                    $result = $objModel->updatetemporal($array_activo);
                    if($result) echo 'ok'; else echo 'error';
                }else{
                    echo 'error';
                } 
            }            
        }
    }

    public function generaractaretiro($idpersona){

        $objModel=$this->loadModel('acta');
        $result2 = $objModel->generaractaretiro($idpersona); 
        while($reg2 = $result2->fetch_object()){
        $grid.='
            <tr align="center">
                <td>'.$reg2->NOMBRE.'</td>
                <td>'.$reg2->MARCA.'</td>
                <td>'.$reg2->SERIE.'</td>
                <td>'.$reg2->IDPATRI.'</td>
            </tr>';
        }

        $result=$objModel->getpersonal($idpersona);
        $reg = $result->fetch_object();

        $objModel->updateretiro($idpersona);

        date_default_timezone_set("America/Lima");
        $fechaactual = date('Ymd');
        $dia_actual = date('d');
        $mes_actual = date('m');
        $anio_actual = date('Y');

        $fechames = strftime('%B');
        setlocale (LC_TIME, "es_PE");

        $this->getLibrary('TCPDF/mypdf');
            
        // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('UGEL06');
        $pdf->SetTitle('UGEL06 | ACTA DE RETIRO');
        $pdf->SetSubject('UGEL06');

         // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

         //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

         // set font
        $pdf->SetFont('times', '', 10);

        // add a page
        $pdf->AddPage();

        // set some text to print
        $html = <<<EOF

                <!DOCTYPE html>
                <html>
                <head>
                   <title>ACTA RETIRO</title>
               </head>
               <body>

                <h1 style="text-align: center;"><strong>ACTA DE RETIRO</strong></h1>
                <p style="text-align: justify;">La Oficina de ETIC por la presente, hace el retiro al Sr. $reg->NOMBRE, perteneciente al &Aacute;rea de $reg->AREA , los siguientes bienes inform&aacute;ticos.</p>
                <p style="text-align: justify;">&nbsp; </p> 
                <table border="1">
                    <tbody>
                        <tr align="center">
                            <th>ACCESORIO</th>
                            <th>MARCA</th>
                            <th>N&deg; SERIE</th>
                            <th>COD. PATRIMONIO</th>
                        </tr>            
                        $grid
                    </tbody>
                </table>
                <p style="text-align: justify;">&nbsp;</p>
                <p style="padding-left: 60px;">     Es todo cuando tengo que informar para su conocimiento y dem&aacute;s fines.</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p style="text-align: right;">&nbsp;</p>
                <p style="padding-left: 30px; text-align: rigth;">Vitarte, $dia_actual de $fechames del $anio_actual</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                
                
                <p style="text-align: center;"><hr style="width:40%;">(e) ILICH SUMARAN QUISPE FERNANDEZ<br />Equipo de tecnologia de la informacion<br />UGEL N&deg; 06</p>

                </body>
                </html>
EOF;
        // print a block of text using Write()
        $pdf->writeHTML($html, true, false, true, false, '');

        //Close and output PDF document
        $pdf->Output('ACTA_ENTREGA_'.$reg->NOMBRE.'.pdf', 'I');

    }

    /* **************************************************************************************************** */
    /*                                             HISTORIAL DE ACTAS
    /* **************************************************************************************************** */

    public function historial(){

        $this->_view->setJs(array('index'));
        $objModel=$this->loadModel('acta');
        $this->_view->personal=$objModel->getcombopersonalacta();
        //$reg = $result->fetch_object();
        $this->_view->renderizar('historial');
    }

    public function getcomponenteacta($idpersonal) {
        $objModel = $this->loadModel ( 'acta' );
        $componente = $objModel->getcomponenteacta ( $idpersonal );
        $html = '';
        $html .= "<option value='' selected> Seleccione </option>";
        while ( $reg = $componente->fetch_object () )
            $html .= "<option value='$reg->IDCOMPONENTE'> $reg->NOMBRE</option>";
        echo $html;
    }

    public function gethistorialacta($idpersona, $idcomponente){

        $objModel=$this->loadModel('acta');
        $result=$objModel->gethistorialacta($idpersona, $idcomponente);

        while($reg=$result->fetch_object()){

            $data ['data'] [] = array (
                'IDACTIVO'=>$reg->IDACTIVO,
                'INVENTARIO'=>$reg->INVENTARIO,
                'COMPONENTE'=>$reg->COMPONENTE,
                'MODELO'=>$reg->MODELO,
                'MARCA'=>$reg->MARCA,
                'SERIE'=>$reg->SERIE,
                'NOMBRE'=>$reg->NOMBRE,
                'ACTA'=>$reg->ACTA,
                'IDUSUARIOCREACION'=>$reg->IDUSUARIOCREACION,
                'FECHAOPERACION'=>$reg->FECHAOPERACION                
                );
        }
        echo json_encode ( $data );
    }


}
	


?>
