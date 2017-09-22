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

	public function preview($name = NULL, $dest = 'I', $watermark_text = 'PREVIEW') {
		$this->initPage();
		$this->design->decoratePdf($this->pdf, $this->data);
		$this->watermark($watermark_text);
		$this->pdf->Output($name, $dest);

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
		$this->pdf->setPage(1);
		$this->pdf->SetTextColor( 150, 150, 150 );
		$this->pdf->SetFont( 'helvetica', '', 24 );

		list($dist, $coordinates) = $this->spreadCoordinates(60);
		foreach ($coordinates as $coordinate) {
			$this->pdf->SetXY( $coordinate[0], $coordinate[1] );
			$this->pdf->Cell( $dist[0], $dist[1] - 30, $watermark_text, 0, 0, 'C' );
		}
	}

	public function spreadCoordinates($distance) {
		// $format = [w, h]
		$format = array($this->pdf->getPageWidth(), $this->pdf->getPageHeight());

		$count = $dist = array();
		foreach (range(0, 1) as $dim) {
			// How many watermarks can we fit?
			$count[$dim] = intval($format[$dim] / $distance) - 1;
			// How far apart should they be?
			$dist[$dim] = $format[$dim] / $count[$dim];
		}

		$coordinates = array();
		foreach (range(0, $count[0] - 1) as $x) {
			foreach (range(0, $count[1] - 1) as $y) {
				$coordinates[] = array($x * $dist[0], $y * $dist[1]);
			}
		}
		return array($dist, $coordinates);
	}

}
