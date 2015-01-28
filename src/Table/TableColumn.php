<?php
namespace Jupitern\Table;

Class TableColumn
{
	public $value;

	private $tableInstance;
	private $title;
	private $attrs;
	private $css;
	private $filter = false;
	private $filterData = null;

	public function __construct(Table &$tableInstance)
	{
		$this->tableInstance = $tableInstance;
		$this->attrs = ['header' => new Properties(), 'body' => new Properties()];
		$this->css = ['header' => new Properties(), 'body' => new Properties()];
	}

	public function __get( $prop ){
		return $this->$prop;
	}

	public function title($title)
	{
		$this->title = $title;
		return $this;
	}

	public function value($value)
	{
		$this->value = $value;
		return $this;
	}

	public function attr($attr, $value, $header = false)
	{
		$this->attrs[$header ? 'header' : 'body']->add($attr, $value);
		return $this;
	}

	public function css($attr, $value, $header = false)
	{
		$this->css[$header ? 'header' : 'body']->add($attr, $value);
		return $this;
	}

	public function filter($data = null)
	{
		$this->filter = true;
		$this->filterData = $data;
		return $this;
	}

	public function add()
	{
		return $this->tableInstance;
	}

	public function renderHeader()
	{
		$template = '<th {attrs} style="{css}">{title}</th>';
		$attrs = $this->attrs['header']->render('{prop}="{val}" ');
		$css = $this->css['header']->render('{prop}:{val}; ');
		return str_replace(['{attrs}', '{css}', '{title}'], [$attrs, $css, $this->title], $template);
	}

	public function renderFilter()
	{
		if (!$this->filter) return '';
		$html = '';
		if ($this->filterData !== null) {
			$html .= '<select class="form-control input-sm" style="width: 99%"><option value=""></option>';
			foreach ($this->filterData as $row) {
				if (is_object($row)) {
					$row = array_values(get_object_vars($row));
				}
				$html .= '<option value="'.$row[0].'">'.$row[1].'</option>';
			}
			$html .= '</select>';
		}
		elseif ($this->filter) {
			$html = '<input type="text" class="form-control input-sm"  style="width: 99%">';
		}
		return '<td>'.$html.'</td>';
	}

	public function renderBody( &$row )
	{
		$template = '<td {attrs} style="{css}">{val}</td>';
		$attrs = $this->attrs['body']->render('{prop}="{val}" ');
		$css = $this->css['body']->render('{prop}:{val}; ');

		$val = "";
		if (is_callable($this->value)) {
			$val = $this->value;
			$val = $val($row);
		}
		elseif (is_object($row)) {
			$val = $row->{$this->value};
		}
		elseif (is_array($row)) {
			$val = $this->value !== null ? $row[$this->value] : '';
		}
		return str_replace(['{attrs}','{css}','{title}','{val}'], [$attrs, $css, $this->title, $val], $template);
	}

}