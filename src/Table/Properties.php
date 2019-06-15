<?php
namespace Jupitern\Table;

class Properties
{
	private $properties;

	public function add($property, $value)
	{
		$this->properties[$property] = $value;
		return $this;
	}

	public function addAll($properties)
	{
		if (is_array($properties)) {
			$this->properties = array_merge((array)$this->properties, $properties);
		}
		return $this;
	}

	public function render($template, $context = null)
	{
		$output = '';
		foreach ((array)$this->properties as $prop => $val) {
			$val = $this->getValue($val, $context);
			$output .= str_replace(['{prop}', '{val}'], [$prop, $val], $template);
		}
		return $output;
	}

	public function getValue($value, $context)
	{
		$val = "";
		if (is_callable($value)) {
			$val = $value($context);
		} else {
			$val = $value;
		}
		return $val;
	}

}