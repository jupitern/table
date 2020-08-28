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
		$this->attrs = ['th' => new Properties(), 'tr' => new Properties(), 'td' => new Properties()];
		$this->css = ['th' => new Properties(), 'tr' => new Properties(), 'td' => new Properties()];
	}

	public function __get( $prop )
    {
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
	 * @param $elem
	 * @param $attr
	 * @param $value
	 * @return $this
	 */
	public function attr($elem, $attr, $value)
	{
	    if (!in_array($elem, array_keys($this->attrs))) {
	        throw new \Exception("element {$elem} not available in column scope");
        }

		$this->attrs[$elem]->add($attr, $value);

		return $this;
	}

	/**
	 * add css to table <td> or <th>
	 *
     * @param $elem
	 * @param $attr
	 * @param $value
	 * @return $this
	 */
	public function css($elem, $attr, $value)
	{
		$this->css[$elem]->add($attr, $value);

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
		$this->filterData = Table::isJson($data) ? json_decode($data) : $data;
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
		if ($this->title === false) {
			return "";
		}
		
		if (empty($this->title) && !$this->isCallable($this->value)) {
			if ($this->tableInstance->titlesMode == 'underscore') $this->title = $this->underscoreToTitle($this->value);
			elseif ($this->tableInstance->titlesMode == 'camelcase') $this->title = $this->camelToTitle($this->value);
		}

		$template = '<th {attrs} style="{css}">{title}</th>';
		$attrs = $this->attrs['th']->render('{prop}="{val}" ');
		$css = $this->css['th']->render('{prop}:{val}; ');

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
		$attrs = $this->attrs['td']->render('{prop}="{val}" ');
		$css = $this->css['td']->render('{prop}:{val}; ');

		$val = "";
		if ($this->isCallable($this->value)) {
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
	 * @param string $str
	 * @return mixed
	 */
	private function camelToTitle($str)
	{
		$intermediate = preg_replace('/(?!^)([[:upper:]][[:lower:]]+)/', ' $0', $str);
		$titleStr = preg_replace('/(?!^)([[:lower:]])([[:upper:]])/', '$1 $2', $intermediate);

		return $titleStr;
	}


	/**
	 * @param string $str
	 * @return string
	 */
	private function underscoreToTitle($str)
	{
		$str = ucwords(str_replace("_", " ", $str));

		return $str;
	}
    
    
    /**
     * @param string $var
     * @return boolean
     */
    private function isCallable($var)
    {
        return (!is_string($var) && is_callable($var)) || (is_object($var) && $var instanceof \Closure);
    }
	
}
