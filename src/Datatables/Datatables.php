<?php
namespace Jupitern\Datatables;

class Datatables {

	private $instanceName;
	private $data;
	private $dataUrl;
	private $columns;
	private $css;
	private $attrs;
	private $jsParams;
	private $jsOutput;

	protected function __construct($instanceName)
	{
		$this->instanceName = str_replace(' ', '', $instanceName);
		$this->jsOutput = true;
		$this->jsParams([
			'orderCellsTop' => 'true',
			'sDom' => '\'<"top">rt<"bottom"ip><"clear">\'',
		]);
	}

	public static function instance($instanceName)
	{
		return new static($instanceName);
	}

	public function setDataUrl($dataUrl)
	{
		$this->dataUrl = $dataUrl;
		return $this;
	}

	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function attr($attr, $value)
	{
		$this->attrs[$attr] = $value;
		return $this;
	}

	public function attrs($attrs)
	{
		$this->attrs = array_merge((array)$this->attrs, $attrs);
		return $this;
	}

	public function css($attr, $value)
	{
		$this->css[$attr] = $value;
		return $this;
	}

	public function column($title)
	{
		$column = new DtColumn($this, $title);
		$this->columns[] = $column;
		return $column;
	}

	public function jsParam($param, $value)
	{
		$this->jsParams[$param] = $value;
		return $this;
	}

	public function jsParams($params)
	{
		$this->jsParams = array_merge((array)$this->jsParams, $params);
		return $this;
	}

	public function disableJs()
	{
		$this->jsOutput = false;
		return $this;
	}

	public function render($returnOutput = false)
	{
		$html  = '<table id="{instanceName}" {attrs} style="{css}"><thead><tr>{thead}</tr>{theadFilters}</thead>';
		$html .= '<tbody>{tbody}</tbody></table> {javascript}';

		$attrs = '';
		foreach ((array)$this->attrs as $attrName => $attrValue) {
			$attrs .= $attrName.'="'.$attrValue.'" ';
		}

		$css = '';
		foreach ((array)$this->css as $attrName => $attrValue) {
			$css .= $attrName.': '.$attrValue.'; ';
		}

		$thead = '';
		$hasTableFilters = false;
		$theadFilters = '';
		foreach ((array)$this->columns as $column) {
			$thead .= '<th '.$column->renderCss('header').' '.$column->renderAttrs('header').'>'.$column->title.'</th>';
			$theadFilters .= $column->renderFilter();
			if ($column->filter) $hasTableFilters = true;
		}
		if (!$hasTableFilters) $theadFilters = '';

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
		$output = str_replace(
			['{instanceName}', '{attrs}', '{css}', '{thead}', '{tbody}', '{theadFilters}', '{javascript}'],
			[$this->instanceName, $attrs, $css, $thead, $tbody, $theadFilters, $this->renderJs()],
			$html
		);
		if ($returnOutput) return $output;
		echo $output;
	}

	private function renderJs()
	{
		if (!$this->jsOutput) return '';
		$params = '';
		foreach ($this->jsParams as $name => $value) {
			$params .= '"'.$name.'": '.$value.','."\n\t\t\t";
		}

		$output = '
<script type="text/javascript">
	$(document).ready(function() {
		// init datatables
		var {instanceName} = $("#{instanceName}").DataTable({
			{params}
		});

		// aply search to thead input and select fields
		$("#{instanceName} thead input, #{instanceName} thead select").on( "blur change", function () {
			{instanceName}
				.column( $(this).parent().index()+":visible" )
				.search( this.value )
				.draw();
		});

	});
</script>
';
		return str_replace(['{instanceName}', '{params}'], [$this->instanceName, $params], $output);
	}

}