<?php
namespace Jupitern\Table;

class Table
{

	public $instanceName;
	public $columns;

	private $data;
	private $css;
	private $attrs;
	private $tablePlugin;

	protected function __construct($instanceName)
	{
		$this->instanceName = str_replace(' ', '', $instanceName);
		$this->css = new Properties();
		$this->attrs = new Properties();
	}

	public static function instance($instanceName)
	{
		return new static($instanceName);
	}

	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function attr($attr, $value)
	{
		$this->attrs->add($attr, $value);
		return $this;
	}

	public function attrs($attrs)
	{
		$this->attrs->addAll($attrs);
		return $this;
	}

	public function css($attr, $value)
	{
		$this->css->add($attr, $value);
		return $this;
	}

	public function column($instanceName = null)
	{
		$column = new TableColumn($this, $instanceName);
		$this->columns[] = $column;
		return $column;
	}

	public function plugin($pluginClassName)
	{
		$pluginClassName = "\Jupitern\Table\\$pluginClassName";
		$this->tablePlugin = new $pluginClassName($this);
		if (!$this->tablePlugin instanceof TablePlugin) {
			throw new \Exception("{$pluginClassName} does not implement TablePlugin.");
		}
		return $this->tablePlugin;
	}

	public function render($returnOutput = false)
	{
		$html  = '<table id="{instanceName}" {attrs} {css}><thead><tr>{thead}</tr>{theadFilters}</thead>';
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
		if ($theadFilters != "") $theadFilters = "<tr>{$theadFilters}</tr>";

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
			['{instanceName}','{attrs}','{css}','{thead}','{theadFilters}','{tbody}', '{plugin}'],
			[$this->instanceName, $attrs, $css, $thead, $theadFilters, $tbody, $plugin],
			$html
		);
		if ($returnOutput) return $output;
		echo $output;
	}

}