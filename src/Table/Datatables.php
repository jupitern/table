<?php
namespace Jupitern\Table;

Class Datatables extends TablePlugin
{
	public function __construct(Table &$tableInstance)
	{
		parent::__construct($tableInstance);
		$this->params([
			'orderCellsTop' => 'true',
			'sDom' => '\'<"top">rt<"bottom"ip><"clear">\'',
		]);
	}

	public function ajax($dataUrl)
	{
		$this->param('processing', 'true');
		$this->param('serverSide', 'true');
		$this->param('ajax', '{ url: \''.$dataUrl.'\', type: \'POST\' }');
		$columns = "";
		foreach ($this->tableInstance->columns as $column) {
			$columns .= '{"data": "'.$column->value.'"}, ';
		}
		$this->param('columns', '['.$columns.']');
		return $this;
	}

	public function render()
	{
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
		return str_replace(
			['{instanceName}', '{params}'],
			[$this->tableInstance->instanceName, $this->params->render("'{prop}': {val},\n\t\t\t")],
			$output
		);
	}

}