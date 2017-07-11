<?php

namespace Arildm\Printies;

class FormBuilder {

	/**
	 * Builds a simple form suitable for most cases.
	 *
	 * @param Design $design
	 *   The design object.
	 * @param array $form_data
	 *   (optional) Any already-submitted form data.
	 *
	 * @return string[]
	 *   The form.
	 */
	public function form($design, $form_data = array()) {
		$fields = '';
		foreach ($design->fields() as $field) {
			if ($design->getQuantityFieldName() == $field->name) {
				continue;
			}
			$field_input = (!empty($form_data[$field->name]) ? $form_data[$field->name] : '');
			$output = '<p>' . $field->label . ': ';
			switch ($field->type) {
				case 'text':
					$output .= sprintf('<input name="%s" required="%s" value="%s">', $field->name, $field->required ? 'required' : '', $field_input);
					break;
				case 'integer':
					$attributes = 'min="' . $field->getAttribute('min') . '" max="' . $field->getAttribute('max') . '"';
					$output .= sprintf('<input type="number" name="%s" required="%s" value="%s" %s>', $field->name, $field->required ? 'required' : '', $field_input, $attributes);
					break;
			}
			$output .= '</p>';
			$fields[$field->name] = $output;
		}
		return $fields;
	}

}