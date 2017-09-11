<?php

namespace Arildm\Printies;

require_once( __DIR__ . '/../tcpdf/tcpdf.php' );
require_once( __DIR__ . '/../fpdi/fpdi.php' );

class Printie {

	/**
	 * @var \Arildm\Printies\Design
	 */
	protected $design;

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * The modifiable PDF object, if initiated.
	 *
	 * @var \FPDI|null
	 */
	protected $pdf;

	public function __construct(Design $design, array $data) {
		$this->design = $design;
		$this->data = $data;
	}

	public function preview($watermark_text = 'PREVIEW') {
		$this->initPage();
		$this->design->decoratePdf($this->pdf, $this->data);
		$this->watermark($watermark_text);
		$this->pdf->Output(NULL, 'I');

	}

	public function generate($filename) {
		$this->initPage();
		$this->design->decoratePdf($this->pdf, $this->data);
		$this->pdf->Output($filename, 'F');
	}

	protected function initPage() {
		$orientation = $this->design->getOrientation();
		$this->pdf = new \FPDI($orientation, $this->design->getUnit(), $this->design->getFormat());

		$this->pdf->SetPrintHeader(false);
		$this->pdf->SetPrintFooter(false);
		$this->pdf->setFontSubsetting(false);
		$this->pdf->AddPage($orientation);
		// Import template
		$tpl_file = $this->getPdfPath();
		$num_pages = $this->pdf->setSourceFile($tpl_file);
		$page = $this->pdf->importPage(1);
		$this->pdf->useTemplate($page, 0, 0);
	}

	protected function getPdfPath() {
		$reflection_object = new \ReflectionObject($this->design);
		$class_filename = $reflection_object->getFileName();
		return str_replace('.php', '.pdf', $class_filename);
	}

	protected function watermark($watermark_text) {
		$this->pdf->SetTextColor( 150, 150, 150 );
		$this->pdf->SetFont( 'gotham-medium', '', 14 );
		$coor = array(
			array(30,  40), array(130,  40),
			array(30, 100), array(130, 100),
			array(30, 180), array(130, 180),
			array(30, 260), array(130, 260)
		);
		foreach( $coor as $pos ) {
			$this->pdf->SetXY( $pos[0], $pos[1] );
			$this->pdf->Write( 0, $watermark_text );
		}
	}

}
