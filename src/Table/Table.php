<?php
namespace Jupitern\Table;

class Table
{

	public $columns;
	public $hasFilters;

	private $data;
	private $css;
	private $attrs;
	private $tablePlugin;


	protected function __construct()
	{
		$this->css = new Properties();
		$this->attrs = new Properties();
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
	 * start a new plugin
	 *
	 * @param $pluginClassName
	 * @return TablePlugin
	 * @throws \Exception
	 */
	public function plugin($pluginClassName)
	{
		$pluginClassName = "\Jupitern\Table\\$pluginClassName";
		$this->tablePlugin = new $pluginClassName($this);
		if (!$this->tablePlugin instanceof TablePlugin) {
			throw new \Exception("{$pluginClassName} does not implement TablePlugin.");
		}
		return $this->tablePlugin;
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

		$tbody = '';
		if (count($this->data)) {
			foreach ($this->data as $row) {
				$tbody .= '<tr>';
				foreach ((array)$this->columns as $column) {
					$tbody .= $column->renderBody($row);
				}
				$tbody .= '</tr>';
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
		if ($returnOutput) return $output;
		echo $output;
	}


	private function isJson($string)
	{
		if (!is_string($string)) return false;
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

}