<?php

namespace Arildm\Printies;

abstract class Design {

	public abstract function getName();

	/**
	 * @return Field[]
	 */
	public abstract function fields();

	public abstract function getLabel($data = array());

	public function getQuantityFieldName() {
		return NULL;
	}

	public function form($data) {
		$form_builder = new FormBuilder();
		$fields = $form_builder->form($this, $data);
		return implode("\n", $fields);
	}

	public function getOrientation() {
		return 'P';
	}

	public function getUnit() {
		return 'mm';
	}

	public function getFormat() {
		return 'A4';
	}

	/**
	 * @param \TCPDF $pdf
	 * @param array $data
	 */
	public abstract function decoratePdf($pdf, $data);

}
