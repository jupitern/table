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

	/**
	 * create a new TableColumn instance
	 *
	 * @param Table $tableInstance
	 */
	public function __construct(Table &$tableInstance)
	{
		$this->tableInstance = $tableInstance;
		$this->attrs = ['header' => new Properties(), 'body' => new Properties()];
		$this->css = ['header' => new Properties(), 'body' => new Properties()];
	}

	public function __get( $prop ){
		return $this->$prop;
	}

	/**
	 * set column title
	 *
	 * @param $title
	 * @return $this
	 */
	public function title($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * bind colunm value. $value can be:
	 * integer index for none associative array or json
	 * string index for associative array, json, PDO or ORM result
	 * a closure that returns a string
	 *
	 * @param $value
	 * @return $this
	 */
	public function value($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * add a attribute to table <td> or <th>
	 *
	 * @param $attr
	 * @param $value
	 * @param bool $header
	 * @return $this
	 */
	public function attr($attr, $value, $header = false)
	{
		$this->attrs[$header ? 'header' : 'body']->add($attr, $value);
		return $this;
	}

	/**
	 * add css to table <td> or <th>
	 *
	 * @param $attr
	 * @param $value
	 * @param bool $header
	 * @return $this
	 */
	public function css($attr, $value, $header = false)
	{
		$this->css[$header ? 'header' : 'body']->add($attr, $value);
		return $this;
	}

	/**
	 * add a filter to this column.
	 * $data can be array (associative or not), json, PDO or ORM result
	 *
	 * @param null $data
	 * @return $this
	 */
	public function filter($data = null)
	{
		$this->filterData = $this->isJson($data) ? json_decode($data) : $data;
		$this->filter = true;
		$this->tableInstance->hasFilters = true;
		return $this;
	}

	/**
	 * add this column to the table
	 *
	 * @return Table
	 */
	public function add()
	{
		return $this->tableInstance;
	}

	/**
	 * render column header cell <th>
	 *
	 * @return mixed
	 */
	public function renderHeader()
	{
		$template = '<th {attrs} style="{css}">{title}</th>';
		$attrs = $this->attrs['header']->render('{prop}="{val}" ');
		$css = $this->css['header']->render('{prop}:{val}; ');
		return str_replace(['{attrs}', '{css}', '{title}'], [$attrs, $css, $this->title], $template);
	}

	/**
	 * render column filter
	 *
	 * @return string
	 */
	public function renderFilter()
	{
		$html = '';
		if ($this->filterData !== null) {
			$html .= '<select class="form-control input-sm" style="width: 99%"><option value=""></option>';
			foreach ($this->filterData as $option) {
				if (is_string($option)) {
					$option = [$option, $option];
				}
				elseif (is_object($option)) {
					$option = array_values(get_object_vars($option));
				}
				$html .= '<option value="'.$option[0].'">'.$option[1].'</option>';
			}
			$html .= '</select>';
		}
		elseif ($this->filter) {
			$html = '<input type="text" class="form-control input-sm"  style="width: 99%">';
		}
		return '<td>'.$html.'</td>';
	}

	/**
	 * render column body cell <td>
	 *
	 * @param $row
	 * @return mixed
	 */
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


	/**
	 * Check if string if json
	 *
	 * @param $string
	 * @return bool
	 */
	private function isJson($string)
	{
		if (!is_string($string)) return false;
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

}