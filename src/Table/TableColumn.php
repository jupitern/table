<?php

namespace Jupitern\Table;
use JetBrains\PhpStorm\Pure;

Class TableColumn
{
	public mixed $value;

	private Table $tableInstance;
	private string $title = "";
	private array $attrs;
	private array $css;
	private bool $filter = false;
	private mixed $filterData = null;

	/**
	 * create a new TableColumn instance
	 *
	 * @param Table $tableInstance
	 */
	#[Pure] public function __construct(Table &$tableInstance)
	{
		$this->tableInstance = $tableInstance;
		$this->attrs = ['th' => new Properties(), 'tr' => new Properties(), 'td' => new Properties()];
		$this->css = ['th' => new Properties(), 'tr' => new Properties(), 'td' => new Properties()];
	}

	public function __get(mixed $prop)
    {
		return $this->$prop;
	}

	/**
	 * set column title
	 *
	 * @param string $title
	 * @return $this
	 */
	public function title(string $title): static
    {
		$this->title = $title;

		return $this;
	}

	/**
	 * bind column value. $value can be:
	 * integer index for none associative array or json
	 * string index for associative array, json, PDO or ORM result
	 * a closure that returns a string
	 *
	 * @param mixed $value
	 * @return $this
	 */
	public function value(mixed $value): static
    {
		$this->value = $value;

		return $this;
	}

    /**
     * add attribute to table <td> or <th>
     *
     * @param string $elem
     * @param string $attr
     * @param mixed $value
     * @return $this
     * @throws \Exception
     */
	public function attr(string $elem, string $attr, mixed $value): static
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
     * @param string $elem
	 * @param string $attr
	 * @param mixed $value
	 * @return $this
	 */
	public function css(string $elem, mixed $attr, mixed $value): static
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
	public function filter(mixed $data = null): static
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
	public function add(): Table
    {
		return $this->tableInstance;
	}

	/**
	 * render column header cell <th>
	 *
	 * @return string
	 */
	public function renderHeader(): string
    {
		if (!$this->isCallable($this->value)) {
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
	public function renderFilter(): string
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
	 * @return string
	 */
	public function renderBody(&$row): string
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
	 * @return string|array|null
     */
	private function camelToTitle(string $str): string|array|null
    {
		$intermediate = preg_replace('/(?!^)([[:upper:]][[:lower:]]+)/', ' $0', $str);

        return preg_replace('/(?!^)([[:lower:]])([[:upper:]])/', '$1 $2', $intermediate);
	}


	/**
	 * @param string $str
	 * @return string
	 */
	private function underscoreToTitle(string $str): string
    {
        return ucwords(str_replace("_", " ", $str));
	}
    
    
    /**
     * @param mixed $var
     * @return boolean
     */
    private function isCallable(mixed $var): bool
    {
        return (!is_string($var) && is_callable($var)) || ($var instanceof \Closure);
    }
	
}
