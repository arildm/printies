<?php
/**
 * Created by PhpStorm.
 * User: arildm
 * Date: 2017-04-23
 * Time: 16:16
 */

namespace Printies;

class Field {

	public $name;

	public $label;

	public $type;

	public $required = TRUE;

	protected $attributes = array();

	public function __construct($name, $label, $type, $required = TRUE) {
		$this->name = $name;
		$this->label = $label;
		$this->type = $type;
		$this->required = $required;
	}

	public static function create($name, $label, $type, $required = TRUE) {
		$field = new static($name, $label, $type, $required);
		return $field;
	}

	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;
		return $this;
	}

	public function getAttribute($name) {
		return isset($this->attributes[$name]) ? $this->attributes[$name] : NULL;
	}

}