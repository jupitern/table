<?php
namespace Jupitern\Datatables;

class Datatables {

	private $instanceName;
	private $data;
	private $dataUrl;
	private $columns = [];
	private $css;
	private $attrs;

	protected function __construct($instanceName)
	{
		$this->instanceName = $instanceName;
	}

	public static function instance($instanceName)
	{
		return new static($instanceName);
	}

	public function setDataUrl($dataUrl)
	{
		$this->dataUrl = $dataUrl;
		return $this;
	}

	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function attr($attr, $value)
	{
		$this->attrs[$attr] = $value;
		return $this;
	}

	public function css($attr, $value)
	{
		$this->css[$attr] = $value;
		return $this;
	}

	public function column($title)
	{
		$column = new DtColumn($this, $title);
		$this->columns[] = $column;
		return $column;
	}

	public function render()
	{
		$instanceName = str_replace(' ', '', $this->instanceName);
		$html = '<table id="'.$instanceName.'" {attrs} style="{css}"><thead><tr>{thead}</tr>{theadFilters}</thead><tbody>{tbody}</tbody></table>';

		$attrs = '';
		foreach ((array)$this->attrs as $attrName => $attrValue) {
			$attrs .= $attrName.'="'.$attrValue.'" ';
		}

		$css = '';
		foreach ((array)$this->css as $attrName => $attrValue) {
			$css .= $attrName.': '.$attrValue.'; ';
		}

		$thead = '';
		$hasTableFilters = false;
		$theadFilters = '';
		foreach ($this->columns as $column) {
			$thead .= '<th '.$column->renderCss('header').' '.$column->renderAttrs('header').'>'.$column->title.'</th>';
			$theadFilters .= $column->renderFilter();
			if ($column->filter) $hasTableFilters = true;
		}
		if (!$hasTableFilters) $theadFilters = '';

		$tbody = '';
		foreach ((array)$this->data as $row) {
			$tbody .= '<tr>';
			foreach ((array)$this->columns as $column) {
				if (is_callable($column->value)) {
					$val = $column->value;
					$val = $val($row);
				}
				else {
					$val = $column->value !== null ? $row->{$column->value} : '';
				}
				$tbody .= '<td '.$column->renderCss('body').' '.$column->renderAttrs('body').'>'. $val .'</td>';
			}
			$tbody .= '</tr>';
		}
		return str_replace(
			['{attrs}', '{css}', '{thead}', '{tbody}', '{theadFilters}'],
			[$attrs, $css, $thead, $tbody, $theadFilters],
			$html
		);
	}
}