<?php

namespace Printies;

abstract class Design {

	/**
	 * The modifiable PDF object, if initiated.
	 *
	 * @var \FPDI|null
	 */
	protected $pdf;

	public abstract function getName();

	/**
	 * @return Field[]
	 */
	public abstract function fields();

	public abstract function getLabel($data = array());

	public function alterPrice($price, $data) {
		return $price;
	}

	public function form() {
		$form_builder = new FormBuilder();
		return $form_builder->form($this);
	}

	protected abstract function decoratePdf($data);

	public function preview($data) {
		$this->initPage();
		$this->decoratePdf($data);
		$this->watermark();
		$this->pdf->Output(NULL, 'I');

	}

	public function generate($data) {
		$this->initPage();
		$this->decoratePdf($data);
	}

	protected function initPage() {
		$this->pdf = new \FPDI();
		$this->pdf->SetPrintHeader(false);
		$this->pdf->SetPrintFooter(false);
		$this->pdf->setFontSubsetting(false);
		$this->pdf->AddPage('P');
		// Import template
		$tpl_file = $this->getPdfPath();
		$num_pages = $this->pdf->setSourceFile($tpl_file);
		$page = $this->pdf->importPage(1);
		$this->pdf->useTemplate($page, 0, 0);
	}

	protected function getPdfPath() {
		$reflector = new \ReflectionClass(static::class);
		return str_replace('.php', '.pdf', $reflector->getFileName());
	}

	protected function watermark() {
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
			$this->pdf->Write( 0, __('PREVIEW', 'butique') );
		}
	}

}