<?php
namespace Jupitern\Datatables;

Class DtColumn
{
	private $datatable;
	private $title;
	private $field;
	private $value;
	private $attrs;
	private $css;
	private $filter = false;
	private $filterData = null;

	public function __construct(Datatables &$datatable, $title)
	{
		$this->datatable = $datatable;
		$this->title = $title;
	}

	public function __get( $prop ){
		return $this->$prop;
	}

	public function value($value)
	{
		$this->value = $value;
		return $this;
	}

	public function bind($field)
	{
		$this->field = $field;
		return $this;
	}

	public function attr($attr, $value, $header = false)
	{
		$this->attrs[$header ? 'header' : 'body'][$attr] = $value;
		return $this;
	}

	public function css($attr, $value, $header = false)
	{
		$this->css[$header ? 'header' : 'body'][$attr] = $value;
		return $this;
	}

	public function add()
	{
		return $this->datatable;
	}

	public function filter($data = null)
	{
		$this->filter = true;
		$this->filterData = $data;
		return $this;
	}

	public function renderAttrs($type)
	{
		$html = '';
		if (array_key_exists($type, (array)$this->attrs)) {
			foreach ($this->attrs[$type] as $attrName => $attrValue) {
				$html .= $attrName . '="' . $attrValue . '" ';
			}
		}
		return $html;
	}

	public function renderCss($type)
	{
		$html = '';
		if (array_key_exists($type, (array)$this->css)) {
			foreach ($this->css[$type] as $attrName => $attrValue) {
				$html .= $attrName.': '.$attrValue.'; ';
			}
		}
		return $html !== '' ? 'style="'.$html.'"' : '';
	}

	public function renderFilter()
	{
		$html = '';
		if ($this->filter && $this->filterData !== null) {
			$html .= '<select class="form-control input-sm" style="width: 99%"><option value=""></option>';
			foreach ($this->filterData as $row) {
				$row = array_values(get_object_vars($row));
				$html .= '<option value="'.$row[0].'">'.$row[1].'</option>';
			}
			$html .= '</select>';
		}
		elseif ($this->filter) {
			$html = '<input type="text" class="form-control input-sm"  style="width: 99%">';
		}
		return '<td>'.$html.'</td>';
	}

}