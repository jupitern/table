<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<!-- JQUERY -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>

	<!-- BOOTSTRAP -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

	<!-- DATATABLES -->
	<!--<link href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css" rel="stylesheet">-->
	<link href="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
	<script src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
	<script src="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js"></script>

</head>
<body>

<br clear="both"/><br clear="both"/>

<div class="container">
	<div class="row">
		<div class="col-md-12">

			<?php
			require('autoload.php');

			echo \Jupitern\Datatables\Datatables::instance('dt_example')
					//->setData($data)
					->attr('class', 'table table-bordered table-striped table-hover')
					->attr('cellspacing', '0')
					->attr('width', '100%')
					->column('Name')->add()
					->column('Position')->add()
					->column('City')->add()
					->column('ID')->add()
					->column('Contract Date')->add()
					->column('Salary')->add()
					->render();
			?>

			<script type="text/javascript">

				// aply non ajax search
				$(document).ready(function() {

					var dt_example = $("#dt_example").DataTable({
						orderCellsTop: true,
						"sDom": '<"top">rt<"bottom"ip><"clear">',
						"columnDefs": [ { "targets": 3, "orderable": false } ],
						"processing": true,
						"serverSide": true,
						"ajax": {
							"url": "http://localhost:81/git_repos/datatables/examples/json.php",
							//"dataType": "jsonp"
						}
					});

					$("#dt_example thead input, #dt_example thead select").on( 'blur change', function () {
						dt_example
							.column( $(this).parent().index()+':visible' )
							.search( this.value )
							.draw();
					});

				});

			</script>

		</div>
	</div>
</div>

</body>
</html>