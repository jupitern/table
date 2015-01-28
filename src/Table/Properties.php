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

	public function render($template)
	{
		$output = '';
		foreach ((array)$this->properties as $prop => $val) {
			$output .= str_replace(['{prop}', '{val}'], [$prop, $val], $template);
		}
		return $output;
	}

}