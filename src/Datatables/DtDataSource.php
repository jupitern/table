<?php
namespace Jupitern\Datatables;

class DtDataSource {

	private $instanceName;
	private $data;
	private $dataUrl;

	protected function __construct() { }

	public static function instance()
	{
		return new static();
	}

	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function render()
	{
		$instanceName = str_replace(' ', '', $this->instanceName);
		$html = '<table id="'.$instanceName.'" {attrs} style="{css}"><thead><tr>{thead}</tr><tr>{theadFilters}</tr></thead><tbody>{tbody}</tbody></table>';

		$attrs = '';
		foreach ((array)$this->attrs as $attrName => $attrValue) {
			$attrs .= $attrName.'="'.$attrValue.'" ';
		}

		$css = '';
		foreach ((array)$this->css as $attrName => $attrValue) {
			$css .= $attrName.': '.$attrValue.'; ';
		}

		$thead = '';
		$theadFilters = '';
		foreach ((array)$this->columns as $column) {
			$thead .= '<th '.$column->renderCss('header').' '.$column->renderAttrs('header').'>'.$column->title.'</th>';
			$theadFilters .= $column->renderFilter();
		}

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