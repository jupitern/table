<?php
namespace Jupitern\Table;

class Table
{

	public $columns;
	public $hasFilters;
    public $titlesMode = null;

    protected $data;
    protected $css = [];
    protected $attrs = [];


	protected function __construct()
	{
		$this->css['table'] = new Properties();
		$this->css['tr'] = new Properties();
		$this->attrs['table'] = new Properties();
		$this->attrs['tr'] = new Properties();
		$this->hasFilters = false;
	}

	/**
	 * Initializes the Table.
	 *
	 * @return static
	 */
	public static function instance()
	{
		return new static();
	}

	/**
	 * set data using a array, json string, pdo or your framework orm object.
	 *
	 * @param $data
	 * @return $this
	 */
	public function setData($data)
	{
		$this->data = self::isJson($data) ? json_decode($data) : $data;

		return $this;
	}

    /**
     * set titles auto resolution mode from column name. Options: underscore, camelcase
     *
     * @param $titleMode
     * @return $this
     * @throws \Exception
     */
	public function setAutoTitles($titleMode)
	{
		if (!in_array(strtolower($titleMode), ['camelcase', 'underscore'])) {
			throw new \Exception("selected titles mode options not found");
		}

		$this->titlesMode = strtolower($titleMode);

		return $this;
	}

	/**
	 * add html table attribute
	 *
	 * @param $elem
	 * @param $attr
	 * @param $value
	 * @return $this
	 */
	public function attr($elem, $attr, $value)
	{
		$this->attrs[$elem]->add($attr, $value);

		return $this;
	}

	/**
	 * add html table attributes
	 *
	 * @param $elem
	 * @param $attrs
	 * @return $this
	 */
	public function attrs($elem, $attrs)
	{
		$this->attrs[$elem]->addAll($attrs);

		return $this;
	}

	/**
	 * add html table style
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
	 * start a new column
	 *
	 * @return TableColumn
	 */
	public function column()
	{
		$column = new TableColumn($this);
		$this->columns[] = $column;

		return $column;
	}

	/**
	 * generate table html
	 *
	 * @param bool $returnOutput
	 * @return mixed
	 */
	public function render($returnOutput = false)
	{
		$html  = '<table {tableAttrs} {tableCss}><thead><tr>{thead}</tr>{theadFilters}</thead>';
		$html .= '<tbody>{tbody}</tbody></table>';

		$thead = '';
		$theadFilters = '';
		foreach ((array)$this->columns as $column) {
			$thead .= $column->renderHeader();
			$theadFilters .= $column->renderFilter();
		}

		$tbody = '';
		if (count($this->data)) {
			foreach ($this->data as $row) {
				$tbody .= '<tr ';
                $tbody .= $this->attrs['tr']->render('{prop}="{val}" ', $row);
                $tbody .= 'style="'.$this->css['tr']->render('{prop}:{val}; ', $row) .'" >';

				foreach ((array)$this->columns as $column) {
					$tbody .= $column->renderBody($row);
				}
				$tbody .= '</tr>';
			}
		}

		$output = str_replace(
			['{tableAttrs}','{tableCss}','{thead}','{theadFilters}','{tbody}'],
			[
                $this->attrs['table']->render('{prop}="{val}" '),
                $this->css['table']->render('{prop}:{val}; '),
                $thead,
				$this->hasFilters ? "<tr>{$theadFilters}</tr>" : "",
				$tbody
			],
			$html
		);

		if (!$returnOutput) echo $output;

		return $output;
	}


	public static function isJson($string)
	{
		if (!is_string($string)) return false;
		json_decode($string);

		return (json_last_error() == JSON_ERROR_NONE);
	}

}
