<?php 

class reportebajasController extends Controller{
	
	public function __construct(){
		parent::__construct();
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){
		$this->_view->setJs(array('index'));		
		$objModel=$this->loadModel('reportebajas');
			
		$this->_view->renderizar('index');
	}

    public function getreportebaja ($fechainicio, $fechafin){
        $objModel=$this->loadModel('reportebajas');
        $result=$objModel->getreportebaja($fechainicio, $fechafin);
        
        while($reg=$result->fetch_object()){
        //if($reg->DESCRIPCION  == '4') $descripcion='DONACION'; else  $descripcion='PERDIDO';
            $data ['data'] [] = array (
                'NOMBRE'=>$reg->NOMBRE,
                'MARCA'=>$reg->MARCA,
                'MODELO'=>$reg->MODELO,
                'SERIE'=>$reg->SERIE,
                'ESTADO'=>$reg->ESTADO,
                'CAPACIDAD'=>$reg->CAPACIDAD,
                'IDUSUARIOMOD'=>$reg->IDUSUARIOMOD,
                'FECHABAJA'=>$reg->FECHABAJA,
                'DESCRIPCION'=>$reg->DESCRIPCION,
            );
        }
        echo json_encode ( $data );


    }

	public function informebaja($fechainicio, $fechafin){
        $objModel=$this->loadModel('reportebajas');
        $result=$objModel->getreportebaja($fechainicio, $fechafin);

        while($reg = $result->fetch_object()){
            $grid.='
            <tr align="center">
                <td>'.$reg->NOMBRE.'</td>
                <td>'.$reg->MARCA.'</td>
                <td>'.$reg->SERIE.'</td>
                <td>'.$reg->FECHABAJA.'</td>
                <td>'.$reg->ESTADO.'</td>
                <td>'.$reg->DESCRIPCION.'</td>
            </tr>';

        }

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
        $pdf->SetTitle('UGEL06 | INFORME DE BAJA DE ACTIVOS');
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
                   <title>INFORME DE BAJA</title>
               </head>
               <body>

                <h1 style="text-align: center;"><strong>INFORME DE BIENES A DAR DE BAJA</strong></h1>
                <p style="text-align: justify;">La Oficina de ETIC por la presente, solicita el retiro de componentes informaticos en desuso los cuales se detallan a continuacion:</p>
                <p style="text-align: justify;">&nbsp; </p> 
                <table border="1">
                    <tbody>
                        <tr align="center" style="background-color: #E6E6E6">
                            <th>COMPONENTE</th>
                            <th>MARCA</th>
                            <th>N&deg; SERIE</th>
                            <th>FECHA BAJA</th>
                            <th>ESTADO</th>
                            <th>DESCRIPCION</th>
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
                
                
                <p style="text-align: center;"><hr style="width:40%;">(e) ILICH SUMARAN QUISPE FERNANDEZ<br />Equipo de tecnologia de la informacion <br /> UGEL N&deg; 06</p>

                </body>
                </html>
EOF;
        // print a block of text using Write()
        $pdf->writeHTML($html, true, false, true, false, '');

        //Close and output PDF document
        $pdf->Output('INFORME_BAJA.pdf', 'I');

    }

		


}

?>