<?php

namespace Jupitern\Table;

class Properties
{
	private array $properties = [];

    /**
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function add(string $property, mixed $value): static
    {
		$this->properties[$property] = $value;

		return $this;
	}

    /**
     * @param array $properties
     * @return $this
     */
    public function addAll(array $properties): static
    {
		$this->properties = array_merge((array)$this->properties, $properties);

		return $this;
	}

    /**
     * @param string $template
     * @param null $context
     * @return string
     */
    public function render(string $template, $context = null): string
    {
		$output = '';
		foreach ((array)$this->properties as $prop => $value) {
			if (is_callable($value)) {
				$val = $value($context);
			} else {
				$val = $value;
			}
			$output .= str_replace(['{prop}', '{val}'], [$prop, $val], $template);
		}

		return $output;
	}

}
