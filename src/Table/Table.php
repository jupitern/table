<?php
namespace Jupitern\Table;

class Table
{

	public $columns;
	public $hasFilters;

	protected $data;
	protected $css;
	protected $attrs;
	protected $rowAttrs;
	protected $tablePlugin;
	public $titlesMode = null;


	protected function __construct()
	{
		$this->css = new Properties();
		$this->attrs = new Properties();
		$this->rowAttrs = new Properties();
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
		$this->data = $this->isJson($data) ? json_decode($data) : $data;
		return $this;
	}

	/**
	 * set titles auto resolution mode from column name. Options: underscore, camelcase
	 *
	 * @param $data
	 * @return $this
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
	 * @param $attr
	 * @param $value
	 * @return $this
	 */
	public function attr($attr, $value)
	{
		$this->attrs->add($attr, $value);
		return $this;
	}

	/**
	 * add html table attributes
	 *
	 * @param $attrs
	 * @return $this
	 */
	public function attrs($attrs)
	{
		$this->attrs->addAll($attrs);
		return $this;
	}

	/**
	 * add html table row attribute
	 *
	 * @param $attr
	 * @param $value
	 * @return $this
	 */
	public function rowAttr($attr, $value)
	{
		$this->rowAttrs->add($attr, $value);
		return $this;
	}

	/**
	 * add html table row attributes
	 *
	 * @param $attrs
	 * @return $this
	 */
	public function rowAttrs($attrs)
	{
		$this->rowAttrs->addAll($attrs);
		return $this;
	}

	/**
	 * add html table style
	 *
	 * @param $attr
	 * @param $value
	 * @return $this
	 */
	public function css($attr, $value)
	{
		$this->css->add($attr, $value);
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
		$html  = '<table {attrs} {css}><thead><tr>{thead}</tr>{theadFilters}</thead>';
		$html .= '<tbody>{tbody}</tbody></table>';
		$html .= "\n\n{plugin}";

		$attrs = $this->attrs->render('{prop}="{val}" ');
		$css = $this->css->render('{prop}:{val}; ');

		$thead = '';
		$theadFilters = '';
		foreach ((array)$this->columns as $column) {
			$thead .= $column->renderHeader();
			$theadFilters .= $column->renderFilter();
		}

		$rowTemplate = "<tr {attrs}>{cells}</tr>\n";

		$tbody = '';
		if (count($this->data)) {
			foreach ($this->data as $row) {
				$cells = '';
				foreach ((array)$this->columns as $column) {
					$cells .= $column->renderBody($row);
				}
				$rowAttrs = $this->rowAttrs->render("{prop}='{val}' ", $row);
				$tbody .= str_replace(['{attrs}','{cells}'], [$rowAttrs, $cells], $rowTemplate);
			}
		}

		$plugin = $this->tablePlugin !== null ? $this->tablePlugin->render() : '';

		$output = str_replace(
			['{attrs}','{css}','{thead}','{theadFilters}','{tbody}', '{plugin}'],
			[
				$attrs, $css, $thead,
				$this->hasFilters ? "<tr>{$theadFilters}</tr>" : "",
				$tbody, $plugin
			],
			$html
		);

		if (!$returnOutput) echo $output;
		return $output;
	}


	private function isJson($string)
	{
		if (!is_string($string)) return false;
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

}
