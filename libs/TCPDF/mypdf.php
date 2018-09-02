<?php
require_once('tcpdf.php');



class MYPDF extends TCPDF {
	
        //Page header
	public function Header() {
            // Logo
            $image_file = K_PATH_IMAGES.'logo_ugel.png';
		//$image_file = 'images/logo_ugel.png';
		$this->Image($image_file, 10, 5, 190, 12, 'PNG', '', 'T', true, 200, '', false, false, 0, false, false, false);
            // Set font
		$this->SetFont('helvetica', 'I', 10);
            // Title
           //$this->Cell(0, 5, 'TCPDF Example 003', 1, false, 'C', 1, '', 0, false, 'M', 'M');
		$this->SetXY(100, 15);
		$this->Cell(10,10,'"Año del buen Servicio al Ciudadano"',0,1,'C'); 
	}

        // Page footer
	public function Footer() {
            // Position at 15 mm from bottom
        date_default_timezone_set("America/Lima");
		$fechaactual = date('d-m-Y H:m:s');

		$this->SetY(-15);
            // Set font
		$this->SetFont('helvetica', 'I', 8);
            // Page number
		$this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
		$this->SetXY(50, 283);
		$this->Cell(10,10,'Impreso por: '.$_SESSION['nombre'].' - '.$fechaactual.' ',0,1,'C'); 
	}
}

?>