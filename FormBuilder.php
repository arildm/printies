<?php

namespace Printies;

class FormBuilder {

	/**
	 * Builds a simple form suitable for most cases.
	 *
	 * @param Design $design
	 *   The design object.
	 * @param array $form_data
	 *   (optional) Any already-submitted form data.
	 *
	 * @return string
	 *   The form.
	 */
	public function form($design, $form_data = array()) {
		$output = '';
		foreach ($design->fields() as $field) {
			$field_input = (!empty($form_data[$field->name]) ? $form_data[$field->name] : '');
			$output .= '<p>' . $field->label . ': ';
			switch ($field->type) {
				case 'text':
					$output .= sprintf('<input name="%s" value="%s">', $field->name, $field_input);
					break;
				case 'integer':
					$attributes = 'min="' . $field->getAttribute('min') . '" max="' . $field->getAttribute('max') . '"';
					$output .= sprintf('<input type="number" name="%s" value="%s" %s">', $field->name, $field_input, $attributes);
					break;
			}
			$output .= '</p>';
		}
		return $output;
	}

}