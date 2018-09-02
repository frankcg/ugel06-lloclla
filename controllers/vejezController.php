<?php 

class vejezController extends Controller{
	
	public function __construct(){
		parent::__construct();
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){
		$this->_view->setJs(array('index'));		
		$objModel=$this->loadModel('vejez');

		$objModel2=$this->loadModel('exactitud');
		$this->_view->inventario=$objModel2->getinventario();
		
		//$this->_view->oficina=$objModel2->getoficina();
		
		$this->_view->renderizar('index');
	}

	public function getvejez($idinventario){

		$objModel=$this->loadModel('vejez');
		$result=$objModel->getvejez($idinventario);

		while($reg=$result->fetch_object()){

			$data ['data'] [] = array (
				'NOMBRE'=>$reg->NOMBRE,
				'ACTIVO'=>$reg->ACTIVO,
				'DANADO'=>$reg->DANADO,
				'OBSOLETO'=>$reg->OBSOLETO,
				'VENCIDO'=>$reg->VENCIDO,
				'TOTAL'=>$reg->TOTAL
			);
		}
		echo json_encode ( $data );

	}

		public function generarpdf($idinventario){

		$objModel=$this->loadModel('vejez');
		$result=$objModel->getvejez($idinventario);

        $cont = 1;

		while($reg = $result->fetch_object()){
			$grid.='
			<tr align="center">
                
                <td>'.$reg->NOMBRE.'</td>
                <td>'.$reg->DANADO.'</td>
                <td>'.$reg->OBSOLETO.'</td>
                <td>'.$reg->VENCIDO.'</td>
                <td>'.$reg->TOTAL.'</td>
                <td>'.$reg->INDICADOR.'</td>
            </tr>';

            $cont = $cont + 1;

		}

        $result2 =$objModel->getnombreinventario($idinventario);
        $reg2 = $result2->fetch_object();

		$this->getLibrary('TCPDF/mypdf');
		// Extend the TCPDF class to create custom Header and Footer
	    
// create new PDF document
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('UGEL06');
    $pdf->SetTitle('UGEL06 | INDICADOR');
    $pdf->SetSubject('UGEL06');
    //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

// ---------------------------------------------------------

// set font
    $pdf->SetFont('times', '', 10);

// add a page
    $pdf->AddPage();

// set some text to print
    $html = <<<EOF

    <!DOCTYPE html>
    <html>
    <head>
    	<title>dasdsa</title>
    </head>
    <body>

    	<h1 style="text-align: center;"><strong>VEJEZ DEL INVENTARIO</strong></h1>
        <img src="../ugel06_dev/public/img/vejez.png" width="800">
    	<div></div>
    	<table border="1" style="height: 65px;" width="591">
    		<tbody>
    			<tr align="center">
    				<th rowspan="$cont"> <p> NOMBRE INVENTARIO: $reg2->NOM_INVENTARIO <br><br> DESDE: $reg2->FECHAINICIO HASTA: $reg2->FECHAFIN </p> </th>
    				<th>CATEGORIA</th>
    				<th>UND. DAÑADAS</th>
					<th>UND. OBSOLETAS</th>
					<th>UND. VENCIDAS</th>
					<th>TOTAL</th>
					<th>INDICADOR</th>
    			</tr>
    			$grid
    		</tbody>
    	</table>




    </body>
    </html>
EOF;


// print a block of text using Write()
$pdf->writeHTML($html, true, false, true, false, '');
// add a page
//$pdf->AddPage();

// ---------------------------------------------------------

//Close and output PDF document
	$pdf->Output('INDICADOR.pdf', 'I');
		

	}


}

?>